<?php

namespace App\Models;

use CodeIgniter\Model;

class IncidenciaModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function getTipos()
    {
        return $this->db->table('incidencia_tipos')
            ->where('activo', 1)
            ->orderBy('id', 'ASC')
            ->get()->getResultArray();
    }

    public function getTiposGrouped()
    {
        $tipos = $this->getTipos();
        $grouped = ['negativa' => [], 'positiva' => [], 'neutral' => []];
        foreach ($tipos as $t) {
            $grouped[$t['tipo']][] = $t;
        }
        return $grouped;
    }

    public function registrar(array $datos)
    {
        $this->db->table('incidencia_registro')->insert($datos);
        return $this->db->insertID();
    }

    public function eliminar($id)
    {
        $registro = $this->db->table('incidencia_registro')
            ->where('id', $id)->get()->getRowArray();
        if ($registro) {
            $this->db->table('incidencia_registro')->where('id', $id)->delete();
        }
        return $registro;
    }

    public function contarIncidencias($student_id, $subject_id, $phase_id)
    {
        return (int) $this->db->table('incidencia_registro ir')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->where('ir.student_id', $student_id)
            ->where('ir.subject_id', $subject_id)
            ->where('ir.phase_id', $phase_id)
            ->where('it.tipo', 'negativa')
            ->countAllResults();
    }

    public function getConteos($student_id, $subject_id, $phase_id)
    {
        $rows = $this->db->table('incidencia_registro ir')
            ->select('it.tipo, COUNT(*) as total')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->where('ir.student_id', $student_id)
            ->where('ir.subject_id', $subject_id)
            ->where('ir.phase_id', $phase_id)
            ->groupBy('it.tipo')
            ->get()->getResultArray();

        $c = ['negativa' => 0, 'positiva' => 0, 'neutral' => 0];
        foreach ($rows as $row) {
            if (isset($c[$row['tipo']])) {
                $c[$row['tipo']] = (int) $row['total'];
            }
        }
        $BoletaMod = new \App\Models\BoletaModel();
        $boletasPts = $BoletaMod->impactoNota($student_id, $subject_id, $phase_id);
        $c['boletas'] = (int)($boletasPts / 3);
        $c['nota'] = max(0, min(10, round(10 - $c['negativa'] * 0.5 + $c['positiva'] * 0.5 - $boletasPts, 1)));
        return $c;
    }

    public function getConteosBulk(array $student_ids, $subject_id, $phase_id)
    {
        if (empty($student_ids)) return [];

        $rows = $this->db->table('incidencia_registro ir')
            ->select('ir.student_id, it.tipo, COUNT(*) as total')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->whereIn('ir.student_id', $student_ids)
            ->where('ir.subject_id', $subject_id)
            ->where('ir.phase_id', $phase_id)
            ->groupBy('ir.student_id, it.tipo')
            ->get()->getResultArray();

        $result = [];
        foreach ($rows as $row) {
            $sid = $row['student_id'];
            if (!isset($result[$sid])) {
                $result[$sid] = ['negativa' => 0, 'positiva' => 0, 'neutral' => 0];
            }
            if (isset($result[$sid][$row['tipo']])) {
                $result[$sid][$row['tipo']] = (int) $row['total'];
            }
        }

        $BoletaMod = new \App\Models\BoletaModel();
        $boletasImpacto = $BoletaMod->impactoNotaBulk($student_ids, $subject_id, $phase_id);

        foreach ($student_ids as $sid) {
            if (!isset($result[$sid])) {
                $result[$sid] = ['negativa' => 0, 'positiva' => 0, 'neutral' => 0];
            }
            $c = $result[$sid];
            $pts = isset($boletasImpacto[$sid]) ? $boletasImpacto[$sid] : 0;
            $result[$sid]['boletas'] = (int)($pts / 3);
            $result[$sid]['nota'] = max(0, min(10, round(10 - $c['negativa'] * 0.5 + $c['positiva'] * 0.5 - $pts, 1)));
        }

        return $result;
    }

    public function calcularNota($student_id, $subject_id, $phase_id)
    {
        $neg = (int) $this->db->table('incidencia_registro ir')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->where('ir.student_id', $student_id)
            ->where('ir.subject_id', $subject_id)
            ->where('ir.phase_id', $phase_id)
            ->where('it.tipo', 'negativa')
            ->countAllResults();

        $pos = (int) $this->db->table('incidencia_registro ir')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->where('ir.student_id', $student_id)
            ->where('ir.subject_id', $subject_id)
            ->where('ir.phase_id', $phase_id)
            ->where('it.tipo', 'positiva')
            ->countAllResults();

        $BoletaMod = new \App\Models\BoletaModel();
        $boletasPts = $BoletaMod->impactoNota($student_id, $subject_id, $phase_id);

        return max(0, min(10, round(10 - $neg * 0.5 + $pos * 0.5 - $boletasPts, 1)));
    }

    public function getRegistroSeccion($section_id, $phase_id)
    {
        return $this->db->table('incidencia_registro ir')
            ->select('ir.id, ir.student_id, ir.subject_id, ir.phase_id, ir.fecha,
                      ir.observacion, ir.created_at,
                      it.nombre, it.icono,
                      st.name as student_name, st.lastname as student_lastname,
                      st.lastname2 as student_lastname2,
                      sub.name as subject_name')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->join('t_student st', 'st.student_id = ir.student_id')
            ->join('subject sub', 'sub.subject_id = ir.subject_id')
            ->where('st.section_id', $section_id)
            ->where('ir.phase_id', $phase_id)
            ->orderBy('ir.created_at', 'DESC')
            ->get()->getResultArray();
    }

    public function getRegistroEstudiante($student_id, $phase_id, $subject_id = null)
    {
        $builder = $this->db->table('incidencia_registro ir')
            ->select('ir.*, it.nombre, it.icono, it.tipo, sub.name as subject_name')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->join('subject sub', 'sub.subject_id = ir.subject_id')
            ->where('ir.student_id', $student_id)
            ->where('ir.phase_id', $phase_id);

        if ($subject_id) {
            $builder->where('ir.subject_id', $subject_id);
        }

        return $builder->orderBy('ir.created_at', 'DESC')->get()->getResultArray();
    }

    public function getTipoById($id)
    {
        return $this->db->table('incidencia_tipos')->where('id', $id)->get()->getRowArray();
    }

    public function tieneCompromiso($student_id, $subject_id, $phase_id)
    {
        return (bool) $this->db->table('incidencia_compromisos')
            ->where('student_id', $student_id)
            ->where('subject_id', $subject_id)
            ->where('phase_id', $phase_id)
            ->countAllResults();
    }

    public function registrarCompromiso(array $datos)
    {
        $this->db->table('incidencia_compromisos')->insert($datos);
        return $this->db->insertID();
    }

    public function updateObservacion($id, $observacion)
    {
        $this->db->table('incidencia_registro')
            ->where('id', $id)
            ->update(['observacion' => $observacion]);
    }

    public function getLogisticasHoy($student_id, $date, $phase_id)
    {
        return $this->db->table('incidencia_registro ir')
            ->select('ir.*, it.nombre, it.icono, it.tipo, sub.name as subject_name')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->join('subject sub', 'sub.subject_id = ir.subject_id')
            ->where('ir.student_id', $student_id)
            ->where('ir.fecha', $date)
            ->where('ir.phase_id', $phase_id)
            ->where('it.tipo', 'neutral')
            ->orderBy('ir.created_at', 'ASC')
            ->get()->getResultArray();
    }
}
