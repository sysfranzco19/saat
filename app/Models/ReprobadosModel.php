<?php

namespace App\Models;

use CodeIgniter\Model;

class ReprobadosModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    /**
     * Devuelve el detalle (una fila por materia reprobada) de los estudiantes
     * reprobados (nota < 51) de un trimestre, entre las secciones 271-343
     * (rango usado en el resto del módulo de Secretaria) y solo alumnos activos.
     * Es la misma consulta que se venía usando manualmente.
     */
    public function getReprobadosDetalle($phase_id)
    {
        $sql = "SELECT n.student_id,
                       e.lastname, e.lastname2, e.name,
                       CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) AS student,
                       e.family_id,
                       c.section_id, c.nick_name AS curso,
                       s.name AS materia,
                       n.phase_id, n.total_average,
                       f.email1, f.email2
                FROM csamarks n
                INNER JOIN t_student e ON (n.student_id = e.student_id)
                INNER JOIN t_family f ON (e.family_id = f.family_id)
                INNER JOIN subject s ON (n.subject_id = s.subject_id)
                INNER JOIN section c ON (e.section_id = c.section_id)
                WHERE n.total_average < 51
                  AND n.phase_id = ?
                  AND e.student_id IN (
                        SELECT student_id FROM t_student
                        WHERE activo = 1 AND activo_administracion = 1
                          AND section_id >= 271 AND section_id <= 343
                  )
                ORDER BY c.completo, e.lastname, e.lastname2, e.name, s.name";

        return $this->db->query($sql, [$phase_id])->getResultArray();
    }

    /**
     * Agrupa el detalle anterior por estudiante, con la lista de materias/notas.
     */
    public function getReprobadosAgrupados($phase_id)
    {
        $rows = $this->getReprobadosDetalle($phase_id);
        $agrupado = [];

        foreach ($rows as $r) {
            $sid = $r['student_id'];
            if (!isset($agrupado[$sid])) {
                $agrupado[$sid] = [
                    'student_id' => $sid,
                    'lastname'   => $r['lastname'],
                    'lastname2'  => $r['lastname2'],
                    'name'       => $r['name'],
                    'student'    => $r['student'],
                    'family_id'  => $r['family_id'],
                    'section_id' => $r['section_id'],
                    'curso'      => $r['curso'],
                    'email1'     => $r['email1'],
                    'email2'     => $r['email2'],
                    'materias'   => [],
                ];
            }
            $agrupado[$sid]['materias'][] = [
                'materia' => $r['materia'],
                'nota'    => $r['total_average'],
            ];
        }

        return array_values($agrupado);
    }
}
