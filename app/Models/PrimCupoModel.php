<?php
namespace App\Models;

use CodeIgniter\Model;

class PrimCupoModel extends Model
{
    protected $DBGroup = 'asistencia';

    // -------------------------------------------------------------------------
    // Cálculo de cupo consumido por un estudiante en un trimestre
    // -------------------------------------------------------------------------

    /**
     * Devuelve el desglose de cupo consumido:
     *   ausencias_puras     => días ausente sin ninguna licencia registrada
     *   dias_licencia       => días consumidos por licencias tipo Día (no excepción)
     *   salidas_anticipadas => fracción acumulada de salidas anticipadas (no excepción)
     *   excepciones         => días de excepción (internación, accidente grave, etc.)
     *   total               => días totales consumidos del cupo
     */
    public function calcularCupo(int $student_id, string $phase_inicio, string $phase_fin): array
    {
        // 1. Ausencias puras: status=0 en prim_assistance NO cubiertas por ninguna licencia
        //    (ni de día, ni por horas — un día con salida anticipada puede quedar marcado
        //    Ausente en el diario si el maestro pasó lista después de que el alumno se fue;
        //    esa fracción ya la aporta la licencia por horas, así que no debe sumarse dos veces).
        $sql_puras = "
            SELECT COUNT(*) AS total
            FROM prim_assistance pa
            WHERE pa.student_id = ?
              AND pa.status = 0
              AND pa.date BETWEEN ? AND ?
              AND NOT EXISTS (
                  SELECT 1
                  FROM prim_licencias l
                  INNER JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
                  WHERE l.student_id = pa.student_id
                    AND l.enviado NOT IN (2, 3)
                    AND pa.date BETWEEN ld.fecha_inicio AND ld.fecha_fin
              )
              AND NOT EXISTS (
                  SELECT 1
                  FROM prim_licencias l2
                  INNER JOIN prim_licencias_periodo lp2 ON lp2.licencias_id = l2.licencias_id
                  WHERE l2.student_id = pa.student_id
                    AND l2.enviado NOT IN (2, 3)
                    AND lp2.fecha = pa.date
              )
        ";
        $ausencias_puras = (float) $this->db->query($sql_puras, [$student_id, $phase_inicio, $phase_fin])
            ->getRow()->total;

        // 2. Días consumidos por licencias de tipo Día (tipo_id=1) NO excepción
        $sql_licencias = "
            SELECT COALESCE(SUM(l.fraccion_cupo), 0) AS total
            FROM prim_licencias l
            INNER JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
            WHERE l.student_id = ?
              AND l.tipo_id = 1
              AND l.es_excepcion = 0
              AND l.enviado NOT IN (2, 3)
              AND ld.fecha_inicio BETWEEN ? AND ?
        ";
        $dias_licencia = (float) $this->db->query($sql_licencias, [$student_id, $phase_inicio, $phase_fin])
            ->getRow()->total;

        // 3. Salidas anticipadas (tipo_id=2) NO excepción
        $sql_salidas = "
            SELECT COALESCE(SUM(l.fraccion_cupo), 0) AS total
            FROM prim_licencias l
            INNER JOIN prim_licencias_periodo lp ON lp.licencias_id = l.licencias_id
            WHERE l.student_id = ?
              AND l.tipo_id = 2
              AND l.es_excepcion = 0
              AND l.enviado NOT IN (2, 3)
              AND lp.fecha BETWEEN ? AND ?
        ";
        $salidas_anticipadas = (float) $this->db->query($sql_salidas, [$student_id, $phase_inicio, $phase_fin])
            ->getRow()->total;

        // 4. Días de excepción (solo informativo, no suman al cupo)
        $sql_excep = "
            SELECT COALESCE(SUM(ld.cantidad_dias), 0) AS total
            FROM prim_licencias l
            INNER JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
            WHERE l.student_id = ?
              AND l.es_excepcion = 1
              AND l.enviado NOT IN (2, 3)
              AND ld.fecha_inicio BETWEEN ? AND ?
        ";
        $excepciones = (float) $this->db->query($sql_excep, [$student_id, $phase_inicio, $phase_fin])
            ->getRow()->total;

        $total = $ausencias_puras + $dias_licencia + $salidas_anticipadas;

        return [
            'ausencias_puras'     => $ausencias_puras,
            'dias_licencia'       => $dias_licencia,
            'salidas_anticipadas' => $salidas_anticipadas,
            'excepciones'         => $excepciones,
            'total'               => $total,
            'alerta6'             => $total >= 6,
            'limite9'             => $total >= 9,
            'cupo_restante'       => max(0, 9 - $total),
        ];
    }

    // -------------------------------------------------------------------------
    // Resumen de cupo para todos los estudiantes de una sección
    // -------------------------------------------------------------------------

    public function resumenSeccion(int $section_id, string $phase_inicio, string $phase_fin): array
    {
        $sql = "
            SELECT
                s.student_id,
                CONCAT(s.lastname, ' ', s.lastname2, ' ', s.name) AS student,
                sec.nick_name,

                -- Ausencias puras (no cubiertas por ninguna licencia, ni de día ni por horas)
                (SELECT COUNT(*)
                 FROM prim_assistance pa
                 WHERE pa.student_id = s.student_id
                   AND pa.status = 0
                   AND pa.date BETWEEN ? AND ?
                   AND NOT EXISTS (
                       SELECT 1 FROM prim_licencias lx
                       INNER JOIN prim_licencias_dia ldx ON ldx.licencias_id = lx.licencias_id
                       WHERE lx.student_id = pa.student_id
                         AND lx.enviado NOT IN (2, 3)
                         AND pa.date BETWEEN ldx.fecha_inicio AND ldx.fecha_fin
                   )
                   AND NOT EXISTS (
                       SELECT 1 FROM prim_licencias lxp
                       INNER JOIN prim_licencias_periodo lpxp ON lpxp.licencias_id = lxp.licencias_id
                       WHERE lxp.student_id = pa.student_id
                         AND lxp.enviado NOT IN (2, 3)
                         AND lpxp.fecha = pa.date
                   )
                ) AS ausencias_puras,

                -- Licencias por días no excepción
                COALESCE((
                    SELECT SUM(l2.fraccion_cupo)
                    FROM prim_licencias l2
                    INNER JOIN prim_licencias_dia ld2 ON ld2.licencias_id = l2.licencias_id
                    WHERE l2.student_id = s.student_id
                      AND l2.tipo_id = 1 AND l2.es_excepcion = 0
                      AND l2.enviado NOT IN (2, 3)
                      AND ld2.fecha_inicio BETWEEN ? AND ?
                ), 0) AS dias_licencia,

                -- Salidas anticipadas no excepción
                COALESCE((
                    SELECT SUM(l3.fraccion_cupo)
                    FROM prim_licencias l3
                    INNER JOIN prim_licencias_periodo lp3 ON lp3.licencias_id = l3.licencias_id
                    WHERE l3.student_id = s.student_id
                      AND l3.tipo_id = 2 AND l3.es_excepcion = 0
                      AND l3.enviado NOT IN (2, 3)
                      AND lp3.fecha BETWEEN ? AND ?
                ), 0) AS salidas_anticipadas

            FROM t_student s
            INNER JOIN section sec ON sec.section_id = s.section_id
            WHERE sec.section_id = ?
              AND s.matricula > 0
              AND s.activo = 1
            ORDER BY s.lastname, s.lastname2, s.name
        ";

        $rows = $this->db->query($sql, [
            $phase_inicio, $phase_fin,   // ausencias_puras
            $phase_inicio, $phase_fin,   // dias_licencia
            $phase_inicio, $phase_fin,   // salidas_anticipadas
            $section_id,
        ])->getResultArray();

        foreach ($rows as &$row) {
            $row['total']         = (float)$row['ausencias_puras'] + (float)$row['dias_licencia'] + (float)$row['salidas_anticipadas'];
            $row['alerta6']       = $row['total'] >= 6;
            $row['limite9']       = $row['total'] >= 9;
            $row['cupo_restante'] = max(0, 9 - $row['total']);
        }

        return $rows;
    }

    // -------------------------------------------------------------------------
    // Alertas
    // -------------------------------------------------------------------------

    public function getAlerta(int $student_id, int $phase_id, string $tipo): ?array
    {
        $row = $this->db->table('prim_alertas_cupo')
            ->where('student_id', $student_id)
            ->where('phase_id', $phase_id)
            ->where('tipo', $tipo)
            ->get()->getRowArray();
        return $row ?: null;
    }

    public function getAlertas(int $student_id, int $phase_id): array
    {
        return $this->db->table('prim_alertas_cupo')
            ->where('student_id', $student_id)
            ->where('phase_id', $phase_id)
            ->get()->getResultArray();
    }

    /**
     * Verifica si el cupo alcanzó 6 o 9 días y genera las alertas correspondientes.
     * Es idempotente: no crea duplicados gracias al UNIQUE KEY (student_id, phase_id, tipo).
     * Devuelve array con las alertas nuevas generadas (vacío si no hubo ninguna).
     */
    public function verificarYGenerarAlertas(int $student_id, int $phase_id, string $phase_inicio, string $phase_fin): array
    {
        $cupo    = $this->calcularCupo($student_id, $phase_inicio, $phase_fin);
        $nuevas  = [];

        $umbrales = [
            'alerta6' => ['condicion' => $cupo['total'] >= 6, 'label' => 'Alerta 6 días'],
            'limite9' => ['condicion' => $cupo['total'] >= 9, 'label' => 'Límite 9 días'],
        ];

        foreach ($umbrales as $tipo => $cfg) {
            if (!$cfg['condicion']) continue;
            if ($this->getAlerta($student_id, $phase_id, $tipo)) continue;

            $this->db->table('prim_alertas_cupo')->insert([
                'student_id'     => $student_id,
                'phase_id'       => $phase_id,
                'tipo'           => $tipo,
                'dias_consumidos' => $cupo['total'],
                'fecha_alerta'   => date('Y-m-d H:i:s'),
            ]);
            $nuevas[] = $tipo;

            if ($tipo === 'alerta6') {
                $this->_notificarAlerta6($student_id, $cupo['total']);
            }
        }

        return $nuevas;
    }

    /**
     * Envía el correo de alerta de 6 días solo a la secretaría de la sección
     * del alumno (ella se encarga de notificar a familia y consejero/a).
     * Respeta Config\PrimAlertas::$enviarCorreoAlerta6: en modo de prueba
     * (false) no manda nada, solo lo deja pasar de largo.
     */
    private function _notificarAlerta6(int $student_id, float $dias): void
    {
        if (!(new \Config\PrimAlertas())->enviarCorreoAlerta6) return;

        $stu = $this->db->table('t_student')
            ->select('t_student.lastname, t_student.lastname2, t_student.name, t_student.section_id')
            ->where('student_id', $student_id)
            ->get()->getRowArray();
        if (!$stu) return;

        $seccion = $this->db->table('section')
            ->select('nick_name, secretary_id')
            ->where('section_id', $stu['section_id'])
            ->get()->getRowArray();

        $emailSecretaria = '';
        if (!empty($seccion['secretary_id'])) {
            $sec = \Config\Database::connect('default')
                ->table('secretary')
                ->select('email')
                ->where('secretary_id', $seccion['secretary_id'])
                ->get()->getRowArray();
            $emailSecretaria = $sec['email'] ?? '';
        }

        $to = trim($emailSecretaria);
        if (!$to) return;

        $nombre  = trim($stu['lastname'] . ' ' . $stu['lastname2'] . ' ' . $stu['name']);
        $mensaje = (new \App\Models\EmailModel())->alerta_cupo6_email($nombre, $dias, $seccion['nick_name'] ?? '');

        $headers   = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        @mail($to, 'Alerta de Cupo Trimestral (6 días) — U.E. Tiquipaya', $mensaje, implode("\r\n", $headers));
    }

    /**
     * Ejecuta la verificación para todos los estudiantes de una sección de una sola vez.
     * Llamar después de guardar asistencia.
     */
    public function verificarSeccion(int $section_id, int $phase_id, string $phase_inicio, string $phase_fin): array
    {
        $students = $this->db->table('t_student')
            ->select('student_id')
            ->where('section_id', $section_id)
            ->where('matricula >', 0)
            ->where('activo', 1)
            ->get()->getResultArray();

        $alertas_generadas = [];
        foreach ($students as $s) {
            $nuevas = $this->verificarYGenerarAlertas((int)$s['student_id'], $phase_id, $phase_inicio, $phase_fin);
            if (!empty($nuevas)) {
                $alertas_generadas[$s['student_id']] = $nuevas;
            }
        }
        return $alertas_generadas;
    }

    // -------------------------------------------------------------------------
    // Alertas pendientes de notificación (para vista del consejero)
    // -------------------------------------------------------------------------

    public function alertasPendientes(int $section_ini, int $section_fin, int $phase_id): array
    {
        $sql = "
            SELECT
                ac.alerta_id, ac.student_id, ac.tipo, ac.dias_consumidos, ac.fecha_alerta,
                ac.consejero_notificado, ac.fecha_notificacion,
                CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                sec.nick_name, sec.completo
            FROM prim_alertas_cupo ac
            INNER JOIN t_student s ON s.student_id = ac.student_id
            INNER JOIN section sec ON sec.section_id = s.section_id
            WHERE ac.phase_id = ?
              AND sec.section_id BETWEEN ? AND ?
            ORDER BY ac.tipo DESC, ac.fecha_alerta DESC
        ";
        return $this->db->query($sql, [$phase_id, $section_ini, $section_fin])->getResultArray();
    }

    public function marcarNotificado(int $alerta_id): void
    {
        $this->db->table('prim_alertas_cupo')
            ->where('alerta_id', $alerta_id)
            ->update([
                'consejero_notificado' => 1,
                'fecha_notificacion'   => date('Y-m-d H:i:s'),
            ]);
    }

    // -------------------------------------------------------------------------
    // Actas del consejero
    // -------------------------------------------------------------------------

    public function insertActa(array $datos): int
    {
        $this->db->table('prim_actas_consejero')->insert($datos);
        return $this->db->insertID();
    }

    public function getActas(int $student_id, int $phase_id): array
    {
        return $this->db->table('prim_actas_consejero')
            ->where('student_id', $student_id)
            ->where('phase_id', $phase_id)
            ->orderBy('fecha_convocatoria', 'DESC')
            ->get()->getResultArray();
    }
}
