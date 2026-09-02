<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * "Descargo de Aplazados": constancia que el docente titular de una materia
 * llena por cada estudiante aplazado (csamarks.total_average < 51), explicando
 * qué hizo antes del aplazo. Requisito para poder consolidar notas.
 *
 * Sigue el mismo patrón de diseño que incidencia_compromisos/IncidenciaModel
 * (ver IncidenciaModel::registrarCompromiso / tieneCompromiso).
 */
class NotaDescargoModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function getDescargo($student_id, $subject_id, $phase_id)
    {
        $row = $this->db->table('nota_descargos')
            ->where('student_id', $student_id)
            ->where('subject_id', $subject_id)
            ->where('phase_id', $phase_id)
            ->get()->getRowArray();
        return $row ?: null;
    }

    public function tieneDescargo($student_id, $subject_id, $phase_id): bool
    {
        return (bool) $this->db->table('nota_descargos')
            ->where('student_id', $student_id)
            ->where('subject_id', $subject_id)
            ->where('phase_id', $phase_id)
            ->countAllResults();
    }

    /**
     * Inserta o actualiza (upsert manual) el descargo de un estudiante en una
     * materia/trimestre. Retorna el id del registro.
     */
    public function saveDescargo(array $datos): int
    {
        $existente = $this->getDescargo($datos['student_id'], $datos['subject_id'], $datos['phase_id']);
        if ($existente) {
            $datos['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('nota_descargos')->where('id', $existente['id'])->update($datos);
            return (int) $existente['id'];
        }
        $datos['created_at'] = date('Y-m-d H:i:s');
        $this->db->table('nota_descargos')->insert($datos);
        return (int) $this->db->insertID();
    }

    /**
     * Datos completos de un descargo (con nombres) para armar el PDF o
     * verificar propiedad (teacher_id) antes de mostrarlo/descargarlo.
     */
    public function getForPdf($id)
    {
        $sql = "SELECT d.*, CONCAT(s.lastname, ' ', s.lastname2, ' ', s.name) as student,
                    sub.name as subject_name, sec.completo as curso, t.name as teacher_name
                FROM nota_descargos d
                INNER JOIN t_student s ON(d.student_id = s.student_id)
                INNER JOIN subject sub ON(d.subject_id = sub.subject_id)
                INNER JOIN section sec ON(d.section_id = sec.section_id)
                INNER JOIN teacher t ON(d.teacher_id = t.teacher_id)
                WHERE d.id = " . (int) $id;
        $row = $this->db->query($sql)->getRowArray();
        return $row ?: null;
    }

    /**
     * Historial de descargos generados por un docente (para "Mis Descargos"),
     * filtrable por trimestre y/o materia.
     */
    public function getHistorial($teacher_id, $phase_id = null, $subject_id = null): array
    {
        $builder = $this->db->table('nota_descargos d')
            ->select("d.*, CONCAT(s.lastname, ' ', s.lastname2, ' ', s.name) as student,
                sub.name as subject_name, sec.completo as curso, t.name as teacher_name")
            ->join('t_student s', 's.student_id = d.student_id')
            ->join('subject sub', 'sub.subject_id = d.subject_id')
            ->join('section sec', 'sec.section_id = d.section_id')
            ->join('teacher t', 't.teacher_id = d.teacher_id')
            ->where('d.teacher_id', $teacher_id);

        if (!empty($phase_id)) {
            $builder->where('d.phase_id', $phase_id);
        }
        if (!empty($subject_id)) {
            $builder->where('d.subject_id', $subject_id);
        }

        return $builder->orderBy('d.created_at', 'DESC')->get()->getResultArray();
    }

    /**
     * Trimestres para los que este docente ya tiene descargos guardados,
     * usado para poblar el filtro de "gestión/trimestre" en Mis Descargos.
     */
    public function getFasesByTeacher($teacher_id): array
    {
        return $this->db->table('nota_descargos')
            ->select('phase_id, phase_name')
            ->where('teacher_id', $teacher_id)
            ->groupBy('phase_id, phase_name')
            ->orderBy('phase_id', 'DESC')
            ->get()->getResultArray();
    }

    public function updateArchivoFirmado($id, $filename)
    {
        return $this->db->table('nota_descargos')->where('id', $id)->update(['archivo_firmado' => $filename]);
    }
}
