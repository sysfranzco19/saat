<?php

namespace App\Models;

use CodeIgniter\Model;

class LicenciaModel extends Model
{
    protected $DBGroup = 'asistencia';
    protected $table = 't_licencias'; // 👈 nombre exacto de tu tabla
    protected $primaryKey = 'licencias_id'; // 👈 tu campo AUTO_INCREMENT
    protected $allowedFields = [
        'student_id',
        'tipo_id',
        'fecha_solicitud',
        'hora_salida',
        'solicitante',
        'parentesco_id',
        'motivo_id',
        'detalle',
        'medio_id',
        'enviado',
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';

    public function listarLicencias()
    {
        $Licencias = $this->db->query("SELECT * FROM t_licencias");
        return $Licencias->getResult();
    }
    public function vistaLicencias($section_ini, $section_fin)
    {
        $sql = "SELECT l.licencias_id, l.student_id, tl.tipo,
            CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student,
            CONCAT(m.motivo, ' - ', l.detalle) as detalle,
            COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) as inicio,
            COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ')) as fin,
            c.nick_name, l.enviado, l.fecha_solicitud
        FROM t_licencias l
        INNER JOIN t_student e ON e.student_id=l.student_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
        INNER JOIN t_motivos m ON l.motivo_id=m.motivo_id
        INNER JOIN section c ON e.section_id=c.section_id
        LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
        LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id
        WHERE c.section_id>=" . $section_ini . " AND c.section_id<=" . $section_fin . "
        GROUP BY l.licencias_id, l.student_id, tl.tipo, e.lastname, e.lastname2, e.name, m.motivo, l.detalle, ld.fecha_inicio, ld.fecha_fin, c.nick_name, l.enviado, l.fecha_solicitud
        ORDER BY l.fecha_solicitud DESC LIMIT 1000";
        $Licencias = $this->db->query($sql);
        return $Licencias->getResult();
    }
    public function vistaLicencias2($secretary_id)
    {
        $sql = "SELECT l.licencias_id, l.student_id, tl.tipo,
            CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student,
            CONCAT(m.motivo, ' - ', l.detalle) as detalle,
            COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) as inicio,
            COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ')) as fin,
            c.nick_name, l.enviado, l.fecha_solicitud
        FROM t_licencias l
        INNER JOIN t_student e ON e.student_id=l.student_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
        INNER JOIN t_motivos m ON l.motivo_id=m.motivo_id
        INNER JOIN section c ON e.section_id=c.section_id
        LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
        LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id
        WHERE c.secretary_id=" . $secretary_id . "
        GROUP BY l.licencias_id, l.student_id, tl.tipo, e.lastname, e.lastname2, e.name, m.motivo, l.detalle, ld.fecha_inicio, ld.fecha_fin, c.nick_name, l.enviado, l.fecha_solicitud
        ORDER BY l.fecha_solicitud DESC";
        $Licencias = $this->db->query($sql);
        return $Licencias->getResult();
    }
    public function licencias_auth($section_ini, $section_fin)
    {
        $sql = "SELECT l.licencias_id, DATE_FORMAT(l.fecha_solicitud,'%d-%m-%Y') AS fecha, l.student_id, tl.tipo, l.solicitante,
            CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student,
            CONCAT(m.motivo, ' - ', l.detalle) as detalle,
            COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) as inicio,
            COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ')) as fin,
            c.nick_name, l.enviado, l.fecha_solicitud
        FROM t_licencias l
        INNER JOIN t_student e ON e.student_id=l.student_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
        INNER JOIN t_motivos m ON l.motivo_id=m.motivo_id
        INNER JOIN section c ON e.section_id=c.section_id
        LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
        LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id
        WHERE c.section_id>=" . $section_ini . " AND c.section_id<=" . $section_fin . " AND l.medio_id=10 AND l.enviado=0
        GROUP BY l.licencias_id, l.fecha_solicitud, l.student_id, tl.tipo, l.solicitante, e.lastname, e.lastname2, e.name, m.motivo, l.detalle, ld.fecha_inicio, ld.fecha_fin, c.nick_name, l.enviado
        ORDER BY l.fecha_solicitud DESC";
        $Licencias = $this->db->query($sql);
        return $Licencias->getResult();
    }
    public function licencias_all($section_ini, $section_fin)
    {
        $sql = "SELECT l.licencias_id, DATE_FORMAT(l.fecha_solicitud,'%d-%m-%Y') AS fecha, l.student_id, tl.tipo, l.solicitante,
            CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student,
            CONCAT(m.motivo, ' - ', l.detalle) as detalle,
            COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) as inicio,
            COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ')) as fin,
            c.nick_name, l.enviado, l.fecha_solicitud
        FROM t_licencias l
        INNER JOIN t_student e ON e.student_id=l.student_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
        INNER JOIN t_motivos m ON l.motivo_id=m.motivo_id
        INNER JOIN section c ON e.section_id=c.section_id
        LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
        LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id
        WHERE c.section_id>=" . $section_ini . " AND c.section_id<=" . $section_fin . "
        GROUP BY l.licencias_id, l.fecha_solicitud, l.student_id, tl.tipo, l.solicitante, e.lastname, e.lastname2, e.name, m.motivo, l.detalle, ld.fecha_inicio, ld.fecha_fin, c.nick_name, l.enviado
        ORDER BY l.fecha_solicitud";
        $Licencias = $this->db->query($sql);
        return $Licencias->getResult();
    }

    public function licencias_todas_data($section_ini, $section_fin, $secretary_id, $search, $start, $length, $order_col, $order_dir)
    {
        $cols = [
            0 => 'student',
            1 => 'c.nick_name',
            2 => 'tl.tipo',
            3 => 'detalle',
            4 => 'l.fecha_solicitud',
            5 => 'inicio',
            6 => 'fin',
            7 => 'l.enviado',
        ];
        $order_field = $cols[(int)$order_col] ?? 'l.fecha_solicitud';
        $order_dir   = strtoupper($order_dir) === 'ASC' ? 'ASC' : 'DESC';

        $joins = "FROM t_licencias l
            INNER JOIN t_student e ON e.student_id=l.student_id
            INNER JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
            INNER JOIN t_motivos m ON l.motivo_id=m.motivo_id
            INNER JOIN section c ON e.section_id=c.section_id
            LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
            LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
            LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id";

        if ($section_ini && $section_fin) {
            $base_where = "WHERE c.section_id>=" . (int)$section_ini . " AND c.section_id<=" . (int)$section_fin;
        } elseif ($secretary_id) {
            $base_where = "WHERE c.secretary_id=" . (int)$secretary_id;
        } else {
            $base_where = "WHERE 1=1";
        }

        // Total sin filtro de busqueda
        $total_sql = "SELECT COUNT(*) as total FROM (SELECT l.licencias_id $joins $base_where GROUP BY l.licencias_id) t";
        $records_total = $this->db->query($total_sql)->getRow()->total;

        // Filtro de busqueda
        $where = $base_where;
        if (!empty($search)) {
            $s = $this->db->escapeString($search);
            $where .= " AND (CONCAT(e.lastname,' ',e.lastname2,' ',e.name) LIKE '%$s%'
                OR c.nick_name LIKE '%$s%'
                OR tl.tipo LIKE '%$s%'
                OR CONCAT(m.motivo,' - ',l.detalle) LIKE '%$s%'
                OR DATE_FORMAT(l.fecha_solicitud,'%d-%m-%Y') LIKE '%$s%')";
        }

        // Total filtrado
        $filtered_sql = "SELECT COUNT(*) as total FROM (SELECT l.licencias_id $joins $where GROUP BY l.licencias_id) t";
        $records_filtered = $this->db->query($filtered_sql)->getRow()->total;

        // Datos paginados
        $data_sql = "SELECT l.licencias_id, l.student_id, l.tipo_id, tl.tipo,
            CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student,
            CONCAT(m.motivo, ' - ', l.detalle) as detalle,
            COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) as inicio,
            COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ')) as fin,
            ld.cantidad_dias,
            c.nick_name, l.enviado,
            DATE_FORMAT(l.fecha_solicitud,'%d-%m-%Y %H:%i') as fecha_solicitud
            $joins $where
            GROUP BY l.licencias_id, l.student_id, l.tipo_id, tl.tipo, e.lastname, e.lastname2, e.name, m.motivo, l.detalle, ld.fecha_inicio, ld.fecha_fin, ld.cantidad_dias, c.nick_name, l.enviado, l.fecha_solicitud
            ORDER BY $order_field $order_dir, l.fecha_solicitud DESC
            LIMIT " . (int)$length . " OFFSET " . (int)$start;

        $data = $this->db->query($data_sql)->getResult();

        return [
            'recordsTotal'    => $records_total,
            'recordsFiltered' => $records_filtered,
            'data'            => $data,
        ];
    }

    public function licenciasFecha($fecha)
    {
        $sql = "SELECT l.licencias_id, tl.tipo,
            CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student,
            l.fecha_solicitud, CONCAT(m.motivo, ' - ', l.detalle) as detalle, s.nick_name,
            COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) as inicio,
            COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ')) as fin
        FROM t_licencias l
        INNER JOIN t_student e ON e.student_id=l.student_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
        INNER JOIN t_motivos m ON l.motivo_id=m.motivo_id
        INNER JOIN section s ON e.section_id=s.section_id
        LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
        LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id
        WHERE l.fecha_solicitud LIKE '" . $fecha . "%'
        GROUP BY l.licencias_id, tl.tipo, e.lastname, e.lastname2, e.name, l.fecha_solicitud, m.motivo, l.detalle, s.nick_name, ld.fecha_inicio, ld.fecha_fin";
        $Licencias = $this->db->query($sql);
        return $Licencias->getResult();
    }
    public function getLicencia($licencia_id)
    {
        $sql = "SELECT l.*,
            CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student,
            e.email, e.family_id, s.completo, s.section_id, s.nick_name, tm.medio, m.motivo,
            ld.fecha_inicio, ld.fecha_fin, ld.cantidad_dias,
            MIN(lp.fecha) as fecha_periodo,
            GROUP_CONCAT(
                CONCAT(p.periodo, ' (', TIME_FORMAT(p.hora_inicio,'%H:%i'), ' - ', TIME_FORMAT(p.hora_fin,'%H:%i'), ')')
                ORDER BY lp.id SEPARATOR ', '
            ) as periodos_nombre
        FROM t_licencias l
        LEFT JOIN t_student e ON e.student_id=l.student_id
        LEFT JOIN section s ON e.section_id=s.section_id
        LEFT JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
        LEFT JOIN t_medios tm ON l.medio_id=tm.medio_id
        LEFT JOIN t_motivos m ON l.motivo_id=m.motivo_id
        LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
        LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id
        WHERE l.licencias_id=" . $licencia_id . "
        GROUP BY l.licencias_id, l.student_id, l.tipo_id, l.motivo_id, l.medio_id, l.solicitante, l.detalle, l.enviado, l.fecha_solicitud,
            e.lastname, e.lastname2, e.name, e.email, e.family_id,
            s.completo, s.section_id, s.nick_name, tm.medio, m.motivo,
            ld.fecha_inicio, ld.fecha_fin, ld.cantidad_dias";
        $Licencias = $this->db->query($sql);
        return $Licencias->getResultArray();
    }
    public function get_licencia($data)
    {
        $licencia = $this->db->table('t_licencias');
        $licencia->where($data);
        return $licencia->get()->getResultArray();
    }
    public function insertLicencia($datos)
    {
        $Licencias = $this->db->table("t_licencias");
        $Licencias->insert($datos);
        return $this->db->insertID();
    }
    public function updateLicencia($datos, $licencias_id)
    {
        $Licencias = $this->db->table('t_licencias');
        $Licencias->set($datos);
        $Licencias->where('licencias_id', $licencias_id);
        return $Licencias->update();
    }
    public function deleteLicencia($data)
    {
        $licencias_id = $data['licencias_id'];

        $this->db->table('t_licencias_dia')->where('licencias_id', $licencias_id)->delete();
        $this->db->table('t_licencias_periodo')->where('licencias_id', $licencias_id)->delete();

        $Licencias = $this->db->table('t_licencias');
        $Licencias->where($data);
        return $Licencias->delete();
    }
    public function licencias_fecha($section_id, $fecha)
    {
        $sql = "SELECT 
                    l.student_id,
                    CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                    mo.motivo AS detalle
                FROM t_licencias l
                INNER JOIN t_licencias_dia ld ON ld.licencias_id = l.licencias_id
                INNER JOIN t_student s ON l.student_id = s.student_id
                INNER JOIN t_motivos mo ON l.motivo_id = mo.motivo_id
                WHERE ld.fecha_inicio <= ?
                AND ld.fecha_fin >= ?
                AND s.section_id = ?";

        $query = $this->db->query($sql, [$fecha, $fecha, $section_id]);

        return $query->getResultArray();
    }
    public function licencias_periodo($section_id, $fecha, $periodo_id)
    {
        $sql = "SELECT 
                    l.student_id,
                    CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                    mo.motivo AS detalle
                FROM t_licencias l
                INNER JOIN t_licencias_periodo lp 
                    ON lp.licencias_id = l.licencias_id
                INNER JOIN t_student s 
                    ON l.student_id = s.student_id
                INNER JOIN t_motivos mo 
                    ON l.motivo_id = mo.motivo_id
                WHERE lp.fecha = ?
                AND lp.periodo_id = ?
                AND s.section_id = ?";

        $query = $this->db->query($sql, [$fecha, $periodo_id, $section_id]);

        return $query->getResultArray();
    }
    public function licencias_tipo_fecha($fecha, $section_ini, $section_fin)
    {
        $sql = "SELECT l.licencias_id, l.student_id, l.tipo_id, tl.tipo,
            CONCAT(s.lastname, ' ', s.lastname2,' ', s.name) as student,
            c.completo, par.parentesco, mo.motivo, me.medio,
            CONCAT(mo.motivo, ' - ', l.detalle) as detalle, l.solicitante,
            COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) as inicio,
            COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ')) as fin,
            l.enviado, l.fecha_solicitud
        FROM t_licencias l
        INNER JOIN t_student s ON l.student_id=s.student_id
        INNER JOIN section c ON s.section_id=c.section_id
        INNER JOIN t_parentesco par ON l.parentesco_id=par.parentesco_id
        INNER JOIN t_motivos mo ON l.motivo_id=mo.motivo_id
        INNER JOIN t_medios me ON l.medio_id=me.medio_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
        LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
        LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id
        WHERE c.section_id>=" . $section_ini . " AND c.section_id<=" . $section_fin . " AND l.fecha_solicitud LIKE '" . $fecha . "%'
        GROUP BY l.licencias_id, l.student_id, l.tipo_id, tl.tipo,
            s.lastname, s.lastname2, s.name, c.completo, par.parentesco, mo.motivo, me.medio,
            l.detalle, ld.fecha_inicio, ld.fecha_fin, l.enviado, l.fecha_solicitud";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function licencias_tipo_fecha2($fecha, $secretary_id)
    {
        $sql = "SELECT l.licencias_id, l.student_id, l.tipo_id, tl.tipo,
            CONCAT(s.lastname, ' ', s.lastname2,' ', s.name) as student,
            c.completo, par.parentesco, mo.motivo, me.medio,
            CONCAT(mo.motivo, ' - ', l.detalle) as detalle,
            COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) as inicio,
            COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ')) as fin,
            l.enviado, l.fecha_solicitud
        FROM t_licencias l
        INNER JOIN t_student s ON l.student_id=s.student_id
        INNER JOIN section c ON s.section_id=c.section_id
        INNER JOIN t_parentesco par ON l.parentesco_id=par.parentesco_id
        INNER JOIN t_motivos mo ON l.motivo_id=mo.motivo_id
        INNER JOIN t_medios me ON l.medio_id=me.medio_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
        LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
        LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id
        WHERE c.secretary_id=" . $secretary_id . " AND l.fecha_solicitud LIKE '" . $fecha . "%'
        GROUP BY l.licencias_id, l.student_id, l.tipo_id, tl.tipo,
            s.lastname, s.lastname2, s.name, c.completo, par.parentesco, mo.motivo, me.medio,
            l.detalle, ld.fecha_inicio, ld.fecha_fin, l.enviado, l.fecha_solicitud";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }

    public function licencias_curso_suma($fechaIni, $fechaFin, $cursoIni, $cursoFin)
    {
        $sql = "SELECT e.student_id, CONCAT(e.lastname, ' ', e.lastname2,' ', e.name) as student, c.completo,
            COALESCE(SUM(ld.cantidad_dias), 0) as licencias,
            IF(SUM(a.cantidad) IS NULL, 0, SUM(a.cantidad)) as ausencias,
            (COALESCE(SUM(ld.cantidad_dias), 0) + IF(SUM(a.cantidad) IS NULL, 0, SUM(a.cantidad))) as total
        FROM t_licencias l
        INNER JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        INNER JOIN t_student e ON l.student_id=e.student_id
        INNER JOIN section c ON e.section_id=c.section_id
        LEFT JOIN t_ausencias a ON e.student_id=a.student_id
        WHERE l.tipo_id=1 AND e.section_id>=" . $cursoIni . " AND e.section_id<=" . $cursoFin . "
        AND ld.fecha_inicio>='" . $fechaIni . "' AND ld.fecha_fin<='" . $fechaFin . "'
        GROUP BY e.student_id";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }

    public function licenciasStudent($student_id)
    {
        $sql = "
        SELECT
            l.licencias_id,
            l.student_id,
            tl.tipo,
            'dia' AS modalidad,
            CONCAT(e.lastname,' ',e.lastname2,' ',e.name) AS student,
            CONCAT(m.motivo,' - ',l.detalle) AS detalle,
            DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y') AS inicio,
            DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y') AS fin,
            ld.fecha_fin AS fecha_fin_cmp,
            ld.cantidad_dias,
            l.hora_salida,
            l.enviado,
            DATE_FORMAT(l.fecha_solicitud,'%d-%m-%Y') AS fecha
        FROM t_licencias l
        INNER JOIN t_licencias_dia ld ON ld.licencias_id = l.licencias_id
        INNER JOIN t_student e ON e.student_id = l.student_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id = tl.tipo_id
        INNER JOIN t_motivos m ON l.motivo_id = m.motivo_id
        WHERE l.student_id = ? AND l.tipo_id = 1

        UNION ALL

        SELECT
            l.licencias_id,
            l.student_id,
            tl.tipo,
            'hora' AS modalidad,
            CONCAT(e.lastname,' ',e.lastname2,' ',e.name) AS student,
            CONCAT(m.motivo,' - ',l.detalle) AS detalle,
            DATE_FORMAT(lp.fecha,'%d-%m-%Y') AS inicio,
            CONCAT(p.periodo, IF(l.hora_salida IS NOT NULL AND l.hora_salida != '00:00:00', CONCAT(' (', TIME_FORMAT(l.hora_salida,'%H:%i'), ')'), '')) AS fin,
            lp.fecha AS fecha_fin_cmp,
            NULL AS cantidad_dias,
            l.hora_salida,
            l.enviado,
            DATE_FORMAT(l.fecha_solicitud,'%d-%m-%Y') AS fecha
        FROM t_licencias l
        INNER JOIN t_licencias_periodo lp ON lp.licencias_id = l.licencias_id
        INNER JOIN periodo p ON p.periodo_id = lp.periodo_id
        INNER JOIN t_student e ON e.student_id = l.student_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id = tl.tipo_id
        INNER JOIN t_motivos m ON l.motivo_id = m.motivo_id
        WHERE l.student_id = ? AND l.tipo_id = 2

        ORDER BY fecha DESC
        ";

        $query = $this->db->query($sql, [$student_id, $student_id]);

        return $query->getResult();
    }
    public function licencias_curso_fechas($fechaIni, $fechaFin, $cursoIni, $cursoFin)
    {
        $sql = "SELECT l.licencias_id, l.student_id, l.tipo_id, tl.tipo,
            CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student,
            c.completo, par.parentesco, me.medio, CONCAT(m.motivo, ' - ', l.detalle) as detalle, l.solicitante,
            COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) as inicio,
            COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ')) as fin,
            l.enviado, DATE_FORMAT(l.fecha_solicitud,'%d-%m-%Y') as fecha_solicitud
        FROM t_licencias l
        INNER JOIN t_student e ON e.student_id=l.student_id
        INNER JOIN t_tipo_licencia tl ON l.tipo_id=tl.tipo_id
        INNER JOIN t_motivos m ON l.motivo_id=m.motivo_id
        INNER JOIN section c ON e.section_id=c.section_id
        INNER JOIN t_parentesco par ON l.parentesco_id=par.parentesco_id
        INNER JOIN t_medios me ON l.medio_id=me.medio_id
        LEFT JOIN t_licencias_dia ld ON ld.licencias_id=l.licencias_id
        LEFT JOIN t_licencias_periodo lp ON lp.licencias_id=l.licencias_id
        LEFT JOIN periodo p ON p.periodo_id=lp.periodo_id
        WHERE e.section_id>=" . $cursoIni . " AND e.section_id<=" . $cursoFin . "
        AND (
            (l.tipo_id = 1 AND ld.fecha_inicio >= '" . $fechaIni . "' AND ld.fecha_fin <= '" . $fechaFin . "')
            OR
            (l.tipo_id = 2 AND lp.fecha >= '" . $fechaIni . "' AND lp.fecha <= '" . $fechaFin . "')
        )
        GROUP BY l.licencias_id, l.student_id, l.tipo_id, tl.tipo, e.lastname, e.lastname2, e.name, c.completo, par.parentesco, me.medio, m.motivo, l.detalle, l.solicitante, ld.fecha_inicio, ld.fecha_fin, l.enviado, l.fecha_solicitud
        ORDER BY l.fecha_solicitud DESC";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
}