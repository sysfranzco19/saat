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

    // ── Métodos por maestro (Primaria 3ro-6to) ──────────────────────────────

    /**
     * Returns subject_ids taught by $teacher_id in $section_id.
     */
    public function getSubjectIdsByTeacher($teacher_id, $section_id): array
    {
        $rows = $this->db->query(
            "SELECT subject_id FROM tiqui0_tiquiasis26.subject
             WHERE teacher_id = ? AND section_id = ?",
            [$teacher_id, $section_id]
        )->getResultArray();
        return array_column($rows, 'subject_id');
    }

    /**
     * Like getConteos() but aggregates across all subjects of teacher in that section.
     */
    public function getConteosByTeacher($student_id, $teacher_id, $section_id, $phase_id): array
    {
        $subject_ids = $this->getSubjectIdsByTeacher($teacher_id, $section_id);

        $c = ['negativa' => 0, 'positiva' => 0, 'neutral' => 0];

        if (!empty($subject_ids)) {
            $rows = $this->db->table('incidencia_registro ir')
                ->select('it.tipo, COUNT(*) as total')
                ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
                ->where('ir.student_id', $student_id)
                ->whereIn('ir.subject_id', $subject_ids)
                ->where('ir.phase_id', $phase_id)
                ->groupBy('it.tipo')
                ->get()->getResultArray();

            foreach ($rows as $row) {
                if (isset($c[$row['tipo']])) {
                    $c[$row['tipo']] = (int) $row['total'];
                }
            }
        }

        // Boletas: aula sumada por todas las materias del maestro + recreo (una vez)
        $boletasPts = 0;
        if (!empty($subject_ids)) {
            $aula = (int) $this->db->query(
                "SELECT COUNT(*) as total FROM tiqui0_tiquisaat26.boletas_registro
                 WHERE student_id = ? AND subject_id IN (" . implode(',', array_map('intval', $subject_ids)) . ")
                   AND phase_id = ? AND tipo = 'aula'",
                [$student_id, $phase_id]
            )->getRowArray()['total'];

            $recreo = (int) $this->db->query(
                "SELECT COUNT(*) as total FROM tiqui0_tiquisaat26.boletas_registro
                 WHERE student_id = ? AND phase_id = ? AND tipo = 'recreo'",
                [$student_id, $phase_id]
            )->getRowArray()['total'];

            $boletasPts = ($aula + $recreo) * 3;
        }

        $c['boletas'] = (int)($boletasPts / 3);
        $c['nota'] = max(0, min(10, round(10 - $c['negativa'] * 0.5 + $c['positiva'] * 0.5 - $boletasPts, 1)));
        return $c;
    }

    /**
     * Bulk version of getConteosByTeacher — uses 3 queries total regardless of class size.
     */
    public function getConteosBulkByTeacher(array $student_ids, $teacher_id, $section_id, $phase_id): array
    {
        if (empty($student_ids)) return [];

        $subject_ids = $this->getSubjectIdsByTeacher($teacher_id, $section_id);

        $result = [];
        foreach ($student_ids as $sid) {
            $result[$sid] = ['negativa' => 0, 'positiva' => 0, 'neutral' => 0];
        }

        if (!empty($subject_ids)) {
            // Query 1: incidencias por tipo para todos los alumnos
            $rows = $this->db->table('incidencia_registro ir')
                ->select('ir.student_id, it.tipo, COUNT(*) as total')
                ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
                ->whereIn('ir.student_id', $student_ids)
                ->whereIn('ir.subject_id', $subject_ids)
                ->where('ir.phase_id', $phase_id)
                ->groupBy('ir.student_id, it.tipo')
                ->get()->getResultArray();

            foreach ($rows as $row) {
                $sid = $row['student_id'];
                if (isset($result[$sid][$row['tipo']])) {
                    $result[$sid][$row['tipo']] = (int) $row['total'];
                }
            }

            // Query 2: boletas aula para todas las materias del maestro (bulk)
            $sidList     = implode(',', array_map('intval', $student_ids));
            $subList     = implode(',', array_map('intval', $subject_ids));
            $aulaRows    = $this->db->query(
                "SELECT student_id, COUNT(*) as total FROM tiqui0_tiquisaat26.boletas_registro
                 WHERE student_id IN ($sidList) AND subject_id IN ($subList)
                   AND phase_id = ? AND tipo = 'aula'
                 GROUP BY student_id",
                [$phase_id]
            )->getResultArray();

            // Query 3: boletas recreo (afectan todas las materias)
            $recreoRows  = $this->db->query(
                "SELECT student_id, COUNT(*) as total FROM tiqui0_tiquisaat26.boletas_registro
                 WHERE student_id IN ($sidList) AND phase_id = ? AND tipo = 'recreo'
                 GROUP BY student_id",
                [$phase_id]
            )->getResultArray();

            $boletasMap = [];
            foreach ($aulaRows as $r)  { $boletasMap[$r['student_id']] = ($boletasMap[$r['student_id']] ?? 0) + (int)$r['total']; }
            foreach ($recreoRows as $r) { $boletasMap[$r['student_id']] = ($boletasMap[$r['student_id']] ?? 0) + (int)$r['total']; }
        }

        foreach ($student_ids as $sid) {
            $c          = $result[$sid];
            $boletasCnt = isset($boletasMap) ? ($boletasMap[$sid] ?? 0) : 0;
            $boletasPts = $boletasCnt * 3;
            $result[$sid]['boletas'] = $boletasCnt;
            $result[$sid]['nota']    = max(0, min(10, round(10 - $c['negativa'] * 0.5 + $c['positiva'] * 0.5 - $boletasPts, 1)));
        }

        return $result;
    }

    /**
     * Check if a teacher-level compromiso (acta de reunión) exists.
     */
    public function tieneCompromisoByTeacher($student_id, $teacher_id, $phase_id): bool
    {
        return (bool) $this->db->table('incidencia_compromisos')
            ->where('student_id', $student_id)
            ->where('teacher_id', $teacher_id)
            ->where('phase_id', $phase_id)
            ->where('subject_id IS NULL', null, false)
            ->countAllResults();
    }

    /**
     * Returns all incidencias for a student in the subjects taught by a specific teacher — used by student_profile for primaria 3-6.
     */
    public function getRegistroEstudianteByTeacher($student_id, $teacher_id, $section_id, $phase_id): array
    {
        $subject_ids = $this->getSubjectIdsByTeacher($teacher_id, $section_id);
        if (empty($subject_ids)) return [];

        return $this->db->table('incidencia_registro ir')
            ->select('ir.*, it.nombre, it.icono, it.tipo, sub.name as subject_name')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->join('tiqui0_tiquiasis26.subject sub', 'sub.subject_id = ir.subject_id')
            ->where('ir.student_id', $student_id)
            ->whereIn('ir.subject_id', $subject_ids)
            ->where('ir.phase_id', $phase_id)
            ->orderBy('ir.created_at', 'DESC')
            ->get()->getResultArray();
    }

    /**
     * Like getRegistroEstudiante but also returns teacher name — used by parent view for primaria 3-6.
     */
    public function getRegistroEstudianteConMaestro($student_id, $phase_id): array
    {
        return $this->db->table('incidencia_registro ir')
            ->select('ir.*, it.nombre, it.icono, it.tipo, sub.name as subject_name, t.name as teacher_name, t.teacher_id as teacher_id')
            ->join('incidencia_tipos it', 'it.id = ir.incidencia_tipo_id')
            ->join('tiqui0_tiquiasis26.subject sub', 'sub.subject_id = ir.subject_id')
            ->join('tiqui0_tiquisaat26.teacher t', 't.teacher_id = sub.teacher_id')
            ->where('ir.student_id', $student_id)
            ->where('ir.phase_id', $phase_id)
            ->whereIn('it.tipo', ['negativa', 'positiva'])
            ->orderBy('ir.created_at', 'DESC')
            ->get()->getResultArray();
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
