#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Migration script: t_licencias -> t_licencias (new) + t_licencias_dia + t_licencias_periodo
"""

import re
import sys

INPUT_FILE  = r"c:\xampp\htdocs\saat\t_licencias.sql"
OUTPUT_FILE = r"c:\xampp\htdocs\saat\t_licencias_migration.sql"
BATCH_SIZE  = 500

# -----------------------------------------------------------------
# Period mapping: hora_inicio (string "HH:MM:SS") -> periodo_id
# -----------------------------------------------------------------
PERIOD_MAP = {
    "07:50:00": 8,
    "08:35:00": 9,
    "09:20:00": 10,
    "10:05:00": 10,   # end of period 10
    "10:25:00": 11,
    "10:30:00": 11,   # special alias
    "11:10:00": 12,
    "11:55:00": 12,   # end of period 12
    "12:15:00": 13,
    "12:30:00": 13,   # special alias
    "13:00:00": 14,
    "13:45:00": 15,
    "14:00:00": 15,   # map 14:00 -> 15 (closest)
    "14:30:00": 15,
}

def hora_to_periodo(hora_str):
    """Map a hora_inicio string to a periodo_id."""
    if hora_str is None:
        return 8
    cleaned = hora_str.strip("'")
    return PERIOD_MAP.get(cleaned, 8)


# -----------------------------------------------------------------
# Robust SQL tuple parser
# -----------------------------------------------------------------
def parse_one_values_block(data_block):
    """
    Parse one VALUES data block into a list of field-lists.
    Handles single-quoted strings with backslash and '' escaping,
    NULL values, and any other unquoted SQL literals.
    Stops when it encounters something that is not a '(' (e.g. ';' or end of block).
    """
    rows = []
    i = 0
    n = len(data_block)

    while i < n:
        # Skip whitespace, commas, newlines between rows
        while i < n and data_block[i] in (" ", "\t", "\n", "\r", ","):
            i += 1
        if i >= n:
            break

        if data_block[i] != "(":
            # End of this INSERT block (hit ';' or next INSERT keyword, etc.)
            break

        # We are at the opening paren of a row tuple
        i += 1  # skip '('
        fields = []
        current = []

        while i < n:
            ch = data_block[i]

            if ch == "'":
                # Start of a quoted string — collect until closing quote,
                # respecting \' and '' escapes
                i += 1
                string_chars = ["'"]
                while i < n:
                    c = data_block[i]
                    if c == "\\" and i + 1 < n:
                        string_chars.append(c)
                        string_chars.append(data_block[i + 1])
                        i += 2
                    elif c == "'" and i + 1 < n and data_block[i + 1] == "'":
                        # '' -> escaped single quote inside string
                        string_chars.append("''")
                        i += 2
                    elif c == "'":
                        string_chars.append("'")
                        i += 1
                        break
                    else:
                        string_chars.append(c)
                        i += 1
                current.append("".join(string_chars))

            elif ch == ",":
                # Field separator — save current field
                fields.append("".join(current).strip())
                current = []
                i += 1

            elif ch == ")":
                # End of this row tuple
                fields.append("".join(current).strip())
                i += 1  # skip ')'
                break

            else:
                current.append(ch)
                i += 1

        if fields:
            rows.append(fields)

    return rows


def parse_sql_tuples(sql_text):
    """
    Find ALL INSERT INTO t_licencias ... VALUES blocks in sql_text,
    parse every one, and return a combined list of row field-lists.
    """
    insert_pattern = re.compile(
        r"INSERT INTO `t_licencias`\s*\([^)]*\)\s*VALUES\s*\n",
        re.IGNORECASE,
    )

    all_rows = []
    for m in insert_pattern.finditer(sql_text):
        block_start = m.end()
        # The block ends at the ';' that terminates this INSERT statement.
        # Find the next ';' that is NOT inside a string.
        # We do this by scanning forward with string-awareness.
        pos = block_start
        block_end = len(sql_text)
        in_string = False
        while pos < len(sql_text):
            c = sql_text[pos]
            if in_string:
                if c == "\\" and pos + 1 < len(sql_text):
                    pos += 2  # skip escaped char
                    continue
                elif c == "'" and pos + 1 < len(sql_text) and sql_text[pos + 1] == "'":
                    pos += 2  # skip '' escape
                    continue
                elif c == "'":
                    in_string = False
            else:
                if c == "'":
                    in_string = True
                elif c == ";":
                    block_end = pos + 1
                    break
            pos += 1

        data_block = sql_text[block_start:block_end]
        rows = parse_one_values_block(data_block)
        all_rows.extend(rows)

    if not all_rows:
        raise ValueError("Could not find any INSERT INTO t_licencias ... VALUES blocks")

    return all_rows


# -----------------------------------------------------------------
# Field indices in the ORIGINAL table
# -----------------------------------------------------------------
# licencias_id, student_id, tipo_id, fecha_solicitud, solicitante,
# parentesco_id, motivo_id, detalle, medio_id, fecha_inicio, fecha_fin,
# hora_inicio, hora_fin, cantidad_dias, enviado
IDX = {
    "licencias_id":    0,
    "student_id":      1,
    "tipo_id":         2,
    "fecha_solicitud": 3,
    "solicitante":     4,
    "parentesco_id":   5,
    "motivo_id":       6,
    "detalle":         7,
    "medio_id":        8,
    "fecha_inicio":    9,
    "fecha_fin":       10,
    "hora_inicio":     11,
    "hora_fin":        12,
    "cantidad_dias":   13,
    "enviado":         14,
}


def get(row, name):
    return row[IDX[name]]


def build_batches(rows_sql, batch_size=BATCH_SIZE):
    """Split a list of SQL value strings into batches."""
    batches = []
    for start in range(0, len(rows_sql), batch_size):
        batches.append(rows_sql[start : start + batch_size])
    return batches


def write_insert_batches(f, table, columns, rows_sql, batch_size=BATCH_SIZE):
    """Write batched INSERT statements."""
    col_str = ", ".join(f"`{c}`" for c in columns)
    batches = build_batches(rows_sql, batch_size)
    for batch in batches:
        f.write(f"INSERT INTO `{table}` ({col_str}) VALUES\n")
        f.write(",\n".join(batch))
        f.write(";\n\n")


# -----------------------------------------------------------------
# Main
# -----------------------------------------------------------------
def main():
    print(f"Reading {INPUT_FILE} ...")
    with open(INPUT_FILE, "r", encoding="utf-8", errors="replace") as fh:
        sql_text = fh.read()

    print(f"File size: {len(sql_text):,} bytes")
    print("Parsing tuples ...")

    rows = parse_sql_tuples(sql_text)
    print(f"Parsed {len(rows)} rows")

    if not rows:
        print("ERROR: no rows parsed — aborting.")
        sys.exit(1)

    # ---- Validate a few rows -------------------------------------------
    for r in rows[:3]:
        print(f"  Sample row[{get(r,'licencias_id')}]: tipo={get(r,'tipo_id')} "
              f"solicitante={get(r,'solicitante')[:40]!r}")

    # ---- Build output rows ---------------------------------------------
    lic_rows   = []  # new t_licencias
    dia_rows   = []  # t_licencias_dia  (tipo_id=1)
    per_rows   = []  # t_licencias_periodo (tipo_id=2)

    for r in rows:
        lid          = get(r, "licencias_id")
        student_id   = get(r, "student_id")
        tipo_id      = get(r, "tipo_id")
        fecha_sol    = get(r, "fecha_solicitud")
        solicitante  = get(r, "solicitante")
        parentesco   = get(r, "parentesco_id")
        motivo       = get(r, "motivo_id")
        detalle      = get(r, "detalle")
        medio        = get(r, "medio_id")
        fecha_inicio = get(r, "fecha_inicio")
        fecha_fin    = get(r, "fecha_fin")
        hora_inicio  = get(r, "hora_inicio")
        hora_fin     = get(r, "hora_fin")
        cantidad     = get(r, "cantidad_dias")
        enviado      = get(r, "enviado")

        # --- new t_licencias row (without date/time/days fields) ---
        lic_rows.append(
            f"({lid}, {student_id}, {tipo_id}, {fecha_sol}, {solicitante}, "
            f"{parentesco}, {motivo}, {detalle}, {medio}, {enviado})"
        )

        tipo_int = int(tipo_id) if tipo_id not in ("NULL", "") else 0

        if tipo_int == 1:
            # t_licencias_dia
            dia_rows.append(
                f"({lid}, {fecha_inicio}, {fecha_fin}, {cantidad})"
            )

        elif tipo_int == 2:
            # t_licencias_periodo
            # fecha = DATE part of fecha_solicitud
            # fecha_solicitud is like '2026-02-02 08:20:00'
            if fecha_sol != "NULL" and fecha_sol.startswith("'"):
                date_part = "'" + fecha_sol[1:11] + "'"
            else:
                date_part = fecha_sol

            periodo = hora_to_periodo(hora_inicio if hora_inicio != "NULL" else None)

            per_rows.append(
                f"({lid}, {date_part}, {periodo})"
            )

    print(f"  t_licencias rows  : {len(lic_rows)}")
    print(f"  t_licencias_dia   : {len(dia_rows)}")
    print(f"  t_licencias_periodo: {len(per_rows)}")

    # ---- Write output --------------------------------------------------
    print(f"Writing {OUTPUT_FILE} ...")
    with open(OUTPUT_FILE, "w", encoding="utf-8") as out:
        out.write("-- =============================================================\n")
        out.write("-- Migration: t_licencias -> new schema\n")
        out.write("-- Generated by migrate_licencias.py\n")
        out.write(f"-- Source rows : {len(rows)}\n")
        out.write(f"-- t_licencias : {len(lic_rows)}\n")
        out.write(f"-- t_licencias_dia : {len(dia_rows)}\n")
        out.write(f"-- t_licencias_periodo : {len(per_rows)}\n")
        out.write("-- =============================================================\n\n")
        out.write("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n")
        out.write("SET NAMES utf8mb4;\n\n")

        # --- t_licencias ---
        out.write("-- -------------------------------------------------------------\n")
        out.write("-- 1. New t_licencias (without date/time/days columns)\n")
        out.write("-- -------------------------------------------------------------\n\n")
        write_insert_batches(
            out,
            "t_licencias",
            ["licencias_id", "student_id", "tipo_id", "fecha_solicitud",
             "solicitante", "parentesco_id", "motivo_id", "detalle",
             "medio_id", "enviado"],
            lic_rows,
        )

        # --- t_licencias_dia ---
        out.write("-- -------------------------------------------------------------\n")
        out.write("-- 2. t_licencias_dia (tipo_id=1 records)\n")
        out.write("-- -------------------------------------------------------------\n\n")
        if dia_rows:
            write_insert_batches(
                out,
                "t_licencias_dia",
                ["licencias_id", "fecha_inicio", "fecha_fin", "cantidad_dias"],
                dia_rows,
            )
        else:
            out.write("-- No tipo_id=1 records found.\n\n")

        # --- t_licencias_periodo ---
        out.write("-- -------------------------------------------------------------\n")
        out.write("-- 3. t_licencias_periodo (tipo_id=2 records)\n")
        out.write("-- -------------------------------------------------------------\n\n")
        if per_rows:
            write_insert_batches(
                out,
                "t_licencias_periodo",
                ["licencias_id", "fecha", "periodo_id"],
                per_rows,
            )
        else:
            out.write("-- No tipo_id=2 records found.\n\n")

    print("Done.")


if __name__ == "__main__":
    main()