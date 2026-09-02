<?php

namespace App\Models;

use CodeIgniter\Model;

class BoletaModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function registrar(array $datos)
    {
        $this->db->table('boletas_registro')->insert($datos);
        return $this->db->insertID();
    }

    public function eliminar($id)
    {
        $row = $this->db->table('boletas_registro')->where('id', $id)->get()->getRowArray();
        if ($row) {
            $this->db->table('boletas_registro')->where('id', $id)->delete();
        }
        return $row;
    }

    public function getBoletasSeccion($section_id, $phase_id)
    {
        return $this->db->table('boletas_registro br')
            ->select('br.id, br.student_id, br.subject_id, br.phase_id, br.tipo,
                      br.descripcion, br.fecha, br.dias_suspension, br.medidas_restaurativas,
                      br.created_at,
                      CONCAT(st.lastname, " ", st.lastname2, " ", st.name) AS student_name,
                      sub.name AS subject_name')
            ->join('t_student st', 'st.student_id = br.student_id')
            ->join('subject sub', 'sub.subject_id = br.subject_id', 'left')
            ->where('st.section_id', $section_id)
            ->where('br.phase_id', $phase_id)
            ->orderBy('br.created_at', 'DESC')
            ->get()->getResultArray();
    }

    // Returns [section_id => cantidad de boletas] for a list of sections in a phase
    public function getConteoPorSeccion(array $section_ids, $phase_id)
    {
        if (empty($section_ids)) return [];

        $rows = $this->db->table('boletas_registro br')
            ->select('st.section_id, COUNT(*) AS total')
            ->join('t_student st', 'st.student_id = br.student_id')
            ->whereIn('st.section_id', $section_ids)
            ->where('br.phase_id', $phase_id)
            ->groupBy('st.section_id')
            ->get()->getResultArray();

        $result = [];
        foreach ($section_ids as $sid) {
            $result[$sid] = 0;
        }
        foreach ($rows as $row) {
            $result[$row['section_id']] = (int) $row['total'];
        }
        return $result;
    }

    public function getBoletasEstudiante($student_id, $phase_id, $subject_id = null)
    {
        $builder = $this->db->table('boletas_registro br')
            ->select('br.*, sub.name AS subject_name')
            ->join('subject sub', 'sub.subject_id = br.subject_id', 'left')
            ->where('br.student_id', $student_id)
            ->where('br.phase_id', $phase_id);

        if ($subject_id) {
            $builder->groupStart()
                ->where('br.subject_id', $subject_id)
                ->orWhere('br.tipo', 'recreo')
            ->groupEnd();
        }

        return $builder->orderBy('br.created_at', 'DESC')->get()->getResultArray();
    }

    // Returns total -3 pts impact for a student/subject/phase
    public function impactoNota($student_id, $subject_id, $phase_id)
    {
        // Boletas dentro del aula (solo esa materia)
        $aula = (int) $this->db->table('boletas_registro')
            ->where('student_id', $student_id)
            ->where('subject_id', $subject_id)
            ->where('phase_id', $phase_id)
            ->where('tipo', 'aula')
            ->countAllResults();

        // Boletas fuera del aula (afectan todas las materias)
        $recreo = (int) $this->db->table('boletas_registro')
            ->where('student_id', $student_id)
            ->where('phase_id', $phase_id)
            ->where('tipo', 'recreo')
            ->countAllResults();

        return ($aula + $recreo) * 3;
    }

    public function impactoNotaBulk(array $student_ids, $subject_id, $phase_id)
    {
        if (empty($student_ids)) return [];

        // Aula boletas for this subject
        $aulaRows = $this->db->table('boletas_registro')
            ->select('student_id, COUNT(*) as total')
            ->whereIn('student_id', $student_ids)
            ->where('subject_id', $subject_id)
            ->where('phase_id', $phase_id)
            ->where('tipo', 'aula')
            ->groupBy('student_id')
            ->get()->getResultArray();

        // Recreo boletas (all subjects)
        $recreoRows = $this->db->table('boletas_registro')
            ->select('student_id, COUNT(*) as total')
            ->whereIn('student_id', $student_ids)
            ->where('phase_id', $phase_id)
            ->where('tipo', 'recreo')
            ->groupBy('student_id')
            ->get()->getResultArray();

        $result = [];
        foreach ($student_ids as $sid) {
            $result[$sid] = 0;
        }
        foreach ($aulaRows as $row) {
            $result[$row['student_id']] += (int)$row['total'] * 3;
        }
        foreach ($recreoRows as $row) {
            $result[$row['student_id']] += (int)$row['total'] * 3;
        }
        return $result;
    }
}
