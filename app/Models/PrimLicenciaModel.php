<?php
namespace App\Models;
use CodeIgniter\Model;

class PrimLicenciaModel extends Model
{
    protected $DBGroup = 'asistencia';

    public function licencias_fecha($section_id, $fecha)
    {
        $sql = "SELECT
                    l.student_id,
                    CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                    CONCAT(m.motivo, ' - ', l.detalle) AS detalle
                FROM prim_licencias l
                INNER JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
                INNER JOIN t_student s ON l.student_id = s.student_id
                INNER JOIN t_motivos m ON l.motivo_id = m.motivo_id
                WHERE ld.fecha_inicio <= ? AND ld.fecha_fin >= ? AND s.section_id = ?
                AND l.enviado = 1";
        return $this->db->query($sql, [$fecha, $fecha, $section_id])->getResultArray();
    }

    public function licencias_periodo($section_id, $fecha, $periodo_id)
    {
        $sql = "SELECT
                    l.student_id,
                    CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                    mo.motivo AS detalle
                FROM prim_licencias l
                INNER JOIN prim_licencias_periodo lp ON lp.licencias_id = l.licencias_id
                INNER JOIN t_student s ON l.student_id = s.student_id
                INNER JOIN t_motivos mo ON l.motivo_id = mo.motivo_id
                WHERE lp.fecha = ? AND lp.periodo_id = ? AND s.section_id = ?
                AND l.enviado = 1";
        return $this->db->query($sql, [$fecha, $periodo_id, $section_id])->getResultArray();
    }

    /**
     * Datos completos de una licencia prim_ para usar en el email de autorización.
     * Replica getLicencia() de LicenciaModel pero sobre tablas prim_*.
     */
    public function getLicenciaPrim(int $licencia_id): array
    {
        $sql = "SELECT l.*,
                    CONCAT(e.lastname,' ',e.lastname2,' ',e.name) AS student,
                    e.email, e.family_id, s.completo, s.section_id, s.nick_name,
                    tm.medio, m.motivo,
                    ld.fecha_inicio, ld.fecha_fin, ld.cantidad_dias,
                    MIN(lp.fecha) AS fecha_periodo,
                    GROUP_CONCAT(
                        CONCAT(p.periodo,' (',TIME_FORMAT(p.hora_inicio,'%H:%i'),' - ',TIME_FORMAT(p.hora_fin,'%H:%i'),')')
                        ORDER BY lp.id SEPARATOR ', '
                    ) AS periodos_nombre
                FROM prim_licencias l
                LEFT JOIN t_student e       ON e.student_id = l.student_id
                LEFT JOIN section s         ON e.section_id = s.section_id
                LEFT JOIN t_medios tm       ON l.medio_id = tm.medio_id
                LEFT JOIN t_motivos m       ON l.motivo_id = m.motivo_id
                LEFT JOIN prim_licencias_dia ld     ON ld.licencias_id = l.licencias_id
                LEFT JOIN prim_licencias_periodo lp  ON lp.licencias_id = l.licencias_id
                LEFT JOIN periodo p         ON p.periodo_id = lp.periodo_id
                WHERE l.licencias_id = ?
                GROUP BY l.licencias_id, l.student_id, l.tipo_id, l.motivo_id, l.medio_id,
                    l.solicitante, l.detalle, l.enviado, l.fecha_solicitud,
                    e.lastname, e.lastname2, e.name, e.email, e.family_id,
                    s.completo, s.section_id, s.nick_name, tm.medio, m.motivo,
                    ld.fecha_inicio, ld.fecha_fin, ld.cantidad_dias";
        return $this->db->query($sql, [$licencia_id])->getResultArray();
    }

    public function licenciasStudent(int $student_id): array
    {
        $sql = "SELECT l.licencias_id, l.tipo_id, l.enviado, l.fecha_solicitud,
                    m.motivo, l.detalle, l.es_excepcion, l.fraccion_cupo,
                    l.comprobante_medico, l.carta_solicitud, l.doc_pendiente,
                    l.recoge_nombre, pr.parentesco AS recoge_parentesco,
                    ld.fecha_inicio, ld.fecha_fin, ld.cantidad_dias,
                    MIN(lp.fecha) AS fecha_periodo,
                    GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ') AS periodos_nombre
                FROM prim_licencias l
                LEFT JOIN t_motivos m        ON m.motivo_id = l.motivo_id
                LEFT JOIN prim_licencias_dia ld      ON ld.licencias_id = l.licencias_id
                LEFT JOIN prim_licencias_periodo lp  ON lp.licencias_id = l.licencias_id
                LEFT JOIN periodo p          ON p.periodo_id = lp.periodo_id
                LEFT JOIN t_parentesco pr    ON pr.parentesco_id = l.recoge_parentesco_id
                WHERE l.student_id = ?
                GROUP BY l.licencias_id, l.tipo_id, l.enviado, l.fecha_solicitud,
                         m.motivo, l.detalle, l.es_excepcion, l.fraccion_cupo,
                         l.recoge_nombre, pr.parentesco,
                         ld.fecha_inicio, ld.fecha_fin, ld.cantidad_dias
                ORDER BY l.fecha_solicitud DESC
                LIMIT 50";
        return $this->db->query($sql, [$student_id])->getResultArray();
    }

    public function updateEnviado(int $licencia_id, int $estado): void
    {
        $this->db->table('prim_licencias')
            ->where('licencias_id', $licencia_id)
            ->update(['enviado' => $estado]);
    }

    /**
     * Marca una licencia como eliminada (enviado = 3) sin borrar el registro,
     * para conservar el historial y poder revertir el cupo/asistencia asociados.
     */
    public function eliminarLicencia(int $licencia_id, ?string $obs = null): void
    {
        $datos = ['enviado' => 3];
        if ($obs !== null && $obs !== '') $datos['obs_secretaria'] = $obs;
        $this->db->table('prim_licencias')
            ->where('licencias_id', $licencia_id)
            ->update($datos);
    }
}
