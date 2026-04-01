<?php

namespace App\Models;

use CodeIgniter\Model;

class EvaluationModel extends Model
{
    protected $DBGroup = 'tiquipaya';
    protected $table = 'evaluations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['subject_id', 'section_id', 'teacher_id', 'title', 'description', 'date', 'type', 'created_at'];

    public function getEvaluationsBySubject($subject_id)
    {
        return $this->where('subject_id', $subject_id)
            ->orderBy('date', 'ASC')
            ->findAll();
    }

    public function getEvaluationsBySection($section_id)
    {
        return $this->select('evaluations.*, subject.name as subject_name')
            ->join('subject', 'subject.subject_id = evaluations.subject_id')
            ->where('evaluations.section_id', $section_id)
            ->orderBy('date', 'ASC')
            ->findAll();
    }

    public function getEvaluationsByTeacher($teacher_id)
    {
        return $this->select('evaluations.*, subject.name as subject_name, section.name as section_name, section.nick_name')
            ->join('subject', 'subject.subject_id = evaluations.subject_id')
            ->join('section', 'section.section_id = evaluations.section_id')
            ->where('evaluations.teacher_id', $teacher_id)
            ->orderBy('date', 'DESC') // Most recent/future first? Or ASC? Let's do ASC for schedule.
            ->findAll();
    }

    public function getDailyCount($section_id, $date)
    {
        return $this->where('section_id', $section_id)
            ->where('date', $date)
            ->countAllResults();
    }

    public function addEvaluation($data)
    {
        return $this->insert($data);
    }

    public function deleteEvaluation($id)
    {
        return $this->delete($id);
    }

    public function updateEvaluation($id, $data)
    {
        return $this->update($id, $data);
    }
}
