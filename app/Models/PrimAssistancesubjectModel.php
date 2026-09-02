<?php
namespace App\Models;
use CodeIgniter\Model;

class PrimAssistancesubjectModel extends Model
{
    protected $DBGroup = 'asistencia';

    public function get_assistance_subject_bulk($date_id, $subject_id, array $student_ids, $periodo)
    {
        if (empty($student_ids)) return [];

        $rows = $this->db->table('prim_assistance_subject')
            ->whereIn('student_id', $student_ids)
            ->where('date_id', $date_id)
            ->where('subject_id', $subject_id)
            ->where('periodos', $periodo)
            ->get()->getResultArray();

        $result = [];
        foreach ($rows as $row) {
            $result[$row['student_id']] = $row;
        }
        return $result;
    }

    public function update_assistance_subject_batch(array $rows): void
    {
        if (!empty($rows)) {
            $this->db->table('prim_assistance_subject')->updateBatch($rows, 'assistance_subject_id');
        }
    }

    public function insert_assistance_subject_batch(array $rows): void
    {
        if (!empty($rows)) {
            $this->db->table('prim_assistance_subject')->insertBatch($rows);
        }
    }

    public function assis_previous($section_id, $date_id)
    {
        // El MAX(periodos) debe acotarse a esta misma sección: date_id es una fecha
        // compartida por todo el colegio, así que sin este filtro el máximo podía
        // venir de otra sección con más períodos ese día, y la subconsulta externa
        // nunca encontraba coincidencia (el indicador quedaba siempre apagado).
        $sql = "SELECT a.student_id, a.status, a.periodos
                FROM prim_assistance_subject a
                INNER JOIN subject m ON m.subject_id = a.subject_id
                WHERE m.section_id = ? AND a.date_id = ?
                  AND a.periodos = (
                      SELECT MAX(s.periodos)
                      FROM prim_assistance_subject s
                      INNER JOIN subject sm ON sm.subject_id = s.subject_id
                      WHERE s.date_id = ? AND sm.section_id = ?
                  )";
        return $this->db->query($sql, [(int)$section_id, (int)$date_id, (int)$date_id, (int)$section_id])->getResultArray();
    }

    public function upsert_daily(array $students_data, string $date_class, int $teacher_id, ?string $registrado_por_nombre = null, ?string $registrado_por_rol = null): void
    {
        foreach ($students_data as $sid => $statusVal) {
            $existing = $this->db->table('prim_assistance')
                ->where('student_id', $sid)
                ->where('date', $date_class)
                ->get()->getRowArray();

            $datos = ['status' => $statusVal, 'registered_by' => $teacher_id, 'registered_by_fecha' => date('Y-m-d H:i:s')];
            if ($registrado_por_nombre !== null) $datos['registered_by_nombre'] = $registrado_por_nombre;
            if ($registrado_por_rol !== null)    $datos['registered_by_rol']    = $registrado_por_rol;

            if ($existing) {
                $this->db->table('prim_assistance')
                    ->where('assistance_id', $existing['assistance_id'])
                    ->update($datos);
            } else {
                $datos['student_id'] = $sid;
                $datos['date']       = $date_class;
                $this->db->table('prim_assistance')->insert($datos);
            }
        }
    }

    /**
     * Corrige/guarda la asistencia diaria (prim_assistance) de un alumno para
     * una fecha puntual. Reutilizada tanto por secretaría (Asistencia del Día)
     * como por el maestro (corrección desde el Reporte de Asistencia).
     * Rechaza el cambio si el status no es válido (solo Presente/Ausente/Retraso
     * son editables a mano — Licencia solo se asigna vía licencias aprobadas) o
     * si el día ya está cubierto por una licencia de día aprobada.
     */
    public function guardarAsistenciaDiaria(int $student_id, string $date, int $status, ?string $obs, string $nombre, string $rol, ?int $registeredBy): bool
    {
        if (!$student_id || !$date || !in_array($status, [0, 1, 3], true)) return false;

        $enPrimaria = $this->db->query(
            "SELECT 1 FROM t_student WHERE student_id = ? AND section_id BETWEEN 231 AND 263",
            [$student_id]
        )->getRow();
        if (!$enPrimaria) return false;

        $licDia = $this->db->query(
            "SELECT 1 FROM prim_licencias l
             INNER JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
             WHERE l.student_id = ? AND l.enviado = 1
               AND ld.fecha_inicio <= ? AND ld.fecha_fin >= ?
             LIMIT 1",
            [$student_id, $date, $date]
        )->getRow();
        if ($licDia) return false;

        $existing = $this->db->table('prim_assistance')
            ->where('student_id', $student_id)
            ->where('date', $date)
            ->get()->getRowArray();

        $datos = [
            'status'               => $status,
            'observation'          => $obs,
            'registered_by'        => $registeredBy,
            'registered_by_nombre' => $nombre,
            'registered_by_rol'    => $rol,
            'registered_by_fecha'  => date('Y-m-d H:i:s'),
        ];

        if ($existing) {
            $this->db->table('prim_assistance')->where('assistance_id', $existing['assistance_id'])->update($datos);
        } else {
            $datos['student_id'] = $student_id;
            $datos['date']       = $date;
            $this->db->table('prim_assistance')->insert($datos);
        }

        return true;
    }

    public function get_daily_bulk(array $student_ids, string $date_class): array
    {
        if (empty($student_ids)) return [];
        $rows = $this->db->table('prim_assistance')
            ->whereIn('student_id', $student_ids)
            ->where('date', $date_class)
            ->get()->getResultArray();
        return array_column($rows, null, 'student_id');
    }

    public function assis_subject(int $subject_id, int $phase_id): array
    {
        $sql = "SELECT a.assistance_subject_id, a.status, a.student_id, a.date_id
                FROM prim_assistance_subject a
                INNER JOIN attendance_dates d ON d.date_id = a.date_id
                WHERE a.subject_id = ? AND d.phase_id = ?
                ORDER BY d.date_class DESC";
        return $this->db->query($sql, [$subject_id, $phase_id])->getResultArray();
    }

    /**
     * Fechas con registro de asistencia diaria (prim_assistance) para un curso,
     * sin importar en qué materia se haya pasado lista ese día — pero solo las
     * fechas en las que ESTE maestro (en alguna de sus materias de este curso)
     * efectivamente registró algo en prim_assistance_subject. Así, una maestra
     * con más de una materia en el mismo curso ve todas sus propias llamadas
     * unificadas, sin mezclar fechas registradas únicamente por otro maestro
     * que dicta otra materia a ese mismo curso.
     * Reemplaza a dias_subject() (que lee de la tabla de secundaria) para primaria 3-6.
     */
    public function dias_diarios_section(int $section_id, int $phase_id, int $teacher_id): array
    {
        $sql = "SELECT DISTINCT ad.date_id, ad.date_class
                FROM prim_assistance_subject pas
                INNER JOIN subject sub ON sub.subject_id = pas.subject_id
                INNER JOIN attendance_dates ad ON ad.date_id = pas.date_id AND ad.phase_id = ?
                WHERE sub.section_id = ? AND sub.teacher_id = ?
                ORDER BY ad.date_class";
        return $this->db->query($sql, [$phase_id, $section_id, $teacher_id])->getResultArray();
    }

    /**
     * Estado de asistencia diaria (prim_assistance) de toda la sección, ya
     * consolidado entre materias ("última llamada manda"). assistance_id se
     * expone como assistance_subject_id solo para que la vista del reporte
     * (compartida con secundaria) pueda seguir armando la grilla sin cambios;
     * NO usar ese valor para editar en prim_assistance_subject.
     * Igual que dias_diarios_section(), solo incluye fechas donde este maestro
     * (via alguna de sus materias en este curso) registró algo — el valor que
     * se muestra sigue siendo el diario consolidado (por si otro maestro o
     * secretaría lo corrigió después), pero no se filtran fechas ajenas a él.
     */
    public function asis_diarios_section(int $section_id, int $phase_id, int $teacher_id): array
    {
        $sql = "SELECT pa.assistance_id AS assistance_subject_id, pa.student_id, pa.status,
                    ad.date_id,
                    CASE
                        WHEN EXISTS (
                            SELECT 1 FROM prim_licencias l
                            INNER JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
                            WHERE l.student_id = pa.student_id AND l.enviado = 1
                              AND pa.date BETWEEN ld.fecha_inicio AND ld.fecha_fin
                        ) THEN 1
                        WHEN EXISTS (
                            SELECT 1 FROM prim_licencias l2
                            INNER JOIN prim_licencias_periodo lp2 ON lp2.licencias_id = l2.licencias_id
                            WHERE l2.student_id = pa.student_id AND l2.enviado = 1
                              AND lp2.fecha = pa.date
                        ) THEN 2
                        ELSE NULL
                    END AS tipo_licencia
                FROM prim_assistance pa
                INNER JOIN t_student s ON s.student_id = pa.student_id
                INNER JOIN attendance_dates ad ON ad.date_class = pa.date AND ad.phase_id = ?
                WHERE s.section_id = ?
                  AND EXISTS (
                      SELECT 1 FROM prim_assistance_subject pas
                      INNER JOIN subject sub ON sub.subject_id = pas.subject_id
                      WHERE sub.section_id = s.section_id
                        AND sub.teacher_id = ?
                        AND pas.date_id = ad.date_id
                  )";
        return $this->db->query($sql, [$phase_id, $section_id, $teacher_id])->getResultArray();
    }
}
