<?php
namespace App\Models;
use CodeIgniter\Model;

class PrimCambioRecojoModel extends Model
{
    protected $DBGroup = 'asistencia';

    public function crear(array $datos): int
    {
        $this->db->table('prim_cambio_recojo')->insert($datos);
        return $this->db->insertID();
    }

    public function listarPorEstudiante(int $student_id, int $limit = 20): array
    {
        $sql = "SELECT c.*,
                    p.parentesco AS parentesco_solicitante,
                    COALESCE(pp.parentesco, c.persona_parentesco_otro) AS persona_parentesco
                FROM prim_cambio_recojo c
                LEFT JOIN t_parentesco p  ON p.parentesco_id  = c.parentesco_id
                LEFT JOIN t_parentesco pp ON pp.parentesco_id = c.persona_parentesco_id
                WHERE c.student_id = ?
                ORDER BY c.fecha_solicitud DESC
                LIMIT ?";
        return $this->db->query($sql, [$student_id, $limit])->getResultArray();
    }

    public function getCambioRecojo(int $id): array
    {
        $sql = "SELECT c.*,
                    CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                    s.family_id, s.section_id, sec.nick_name, sec.completo,
                    p.parentesco AS parentesco_solicitante,
                    COALESCE(pp.parentesco, c.persona_parentesco_otro) AS persona_parentesco
                FROM prim_cambio_recojo c
                INNER JOIN t_student s    ON s.student_id = c.student_id
                LEFT JOIN section sec     ON sec.section_id = s.section_id
                LEFT JOIN t_parentesco p  ON p.parentesco_id  = c.parentesco_id
                LEFT JOIN t_parentesco pp ON pp.parentesco_id = c.persona_parentesco_id
                WHERE c.cambio_id = ?";
        return $this->db->query($sql, [$id])->getResultArray();
    }

    public function listarData(string $fecha_ini, string $fecha_fin, string $estado, string $search): array
    {
        $where = "WHERE s.section_id BETWEEN 231 AND 263";

        if ($fecha_ini && $fecha_fin) {
            $where .= " AND c.fecha BETWEEN " . $this->db->escape($fecha_ini) . " AND " . $this->db->escape($fecha_fin);
        }
        if ($estado === 'pending')  $where .= " AND c.enviado = 0";
        if ($estado === 'approved') $where .= " AND c.enviado = 1";
        if ($estado === 'rejected') $where .= " AND c.enviado = 2";
        if ($search !== '') {
            $s = $this->db->escapeString($search);
            $where .= " AND (CONCAT(s.lastname,' ',s.lastname2,' ',s.name) LIKE '%$s%'
                        OR sec.nick_name LIKE '%$s%' OR c.persona_nombre LIKE '%$s%')";
        }

        $sql = "SELECT c.*,
                    CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                    sec.nick_name,
                    p.parentesco AS parentesco_solicitante,
                    COALESCE(pp.parentesco, c.persona_parentesco_otro) AS persona_parentesco
                FROM prim_cambio_recojo c
                INNER JOIN t_student s ON s.student_id = c.student_id
                INNER JOIN section sec ON sec.section_id = s.section_id
                LEFT JOIN t_parentesco p  ON p.parentesco_id  = c.parentesco_id
                LEFT JOIN t_parentesco pp ON pp.parentesco_id = c.persona_parentesco_id
                $where
                ORDER BY c.fecha_solicitud DESC
                LIMIT 500";
        return $this->db->query($sql)->getResultArray();
    }

    public function actualizarEstado(int $id, int $estado, ?string $obs = null): void
    {
        $data = ['enviado' => $estado];
        if ($obs !== null) $data['obs_secretaria'] = $obs;
        $this->db->table('prim_cambio_recojo')->where('cambio_id', $id)->update($data);
    }
}
