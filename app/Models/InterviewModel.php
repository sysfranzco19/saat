<?php
namespace App\Models;

use CodeIgniter\Model;

class InterviewModel extends Model
{
    protected $table = 'interviews';
    protected $primaryKey = 'interview_id';
    protected $allowedFields = ['student_id', 'teacher_id', 'section_id', 'assistant', 'reason', 'description', 'agreements', 'attachment', 'date', 'follow_up_date', 'status'];

    public function getInterviewsByTeacher($teacher_id, $section_id = null)
    {
        $builder = $this->db->table('interviews');
        $builder->select('interviews.*, t_student.name, t_student.lastname');
        $builder->join('t_student', 't_student.student_id = interviews.student_id');
        $builder->where('interviews.teacher_id', $teacher_id);
        if ($section_id) {
            $builder->where('interviews.section_id', $section_id);
        }
        $builder->where('interviews.status', 1); // Active
        $builder->orderBy('interviews.date', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getInterview($interview_id)
    {
        return $this->where('interview_id', $interview_id)->first();
    }
}
