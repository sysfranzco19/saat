<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsenceModel extends Model
{
    protected $DBGroup = 'asistencia';
    public function listar_absence($secretary_id)
    {
        $sql = "SELECT a.*, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student, s.nick_name, CONCAT(t.name, ' - ', m.name) as doc_materia FROM t_ausencias a 
                INNER JOIN t_student e ON(a.student_id=e.student_id)
                INNER JOIN section s ON(e.section_id=s.section_id) 
                INNER JOIN subject m ON(a.subject_id=m.subject_id) 
                INNER JOIN teacher t ON(m.teacher_id=t.teacher_id)
                WHERE s.secretary_id=".$secretary_id." ORDER BY a.fecha DESC LIMIT 50";
        $Absences = $this->db->query($sql);
        return $Absences->getResult();
    }
    public function get_absence($ausencia_id){
        $sql = "SELECT a.*, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student, e.email, e.family_id, s.completo, s.nick_name, e.section_id, CONCAT(t.name, ' - ', m.name) as doc_materia FROM t_ausencias a 
                INNER JOIN t_student e ON(e.student_id=a.student_id) 
                INNER JOIN section s ON(e.section_id=s.section_id) 
                INNER JOIN subject m ON(a.subject_id=m.subject_id) 
                INNER JOIN teacher t ON(m.teacher_id=t.teacher_id) 
                WHERE a.ausencia_id=".$ausencia_id;
        $Absence = $this->db->query($sql);
        return $Absence->getResultArray();
    }
    public function get_absences_student($student_id){
        $sql = "SELECT a.*, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student, e.email, e.family_id, s.completo, s.nick_name, e.section_id, CONCAT(t.name, ' - ', m.name) as doc_materia FROM t_ausencias a 
                INNER JOIN t_student e ON(e.student_id=a.student_id) 
                INNER JOIN section s ON(e.section_id=s.section_id) 
                INNER JOIN subject m ON(a.subject_id=m.subject_id) 
                INNER JOIN teacher t ON(m.teacher_id=t.teacher_id) 
                WHERE a.student_id=".$student_id;
        $Absence = $this->db->query($sql);
        return $Absence->getResultArray();
    }
    public function insert_absence($datos)
    {
        $Absences = $this->db->table("t_ausencias");
        $Absences->insert($datos);
        return $this->db->insertID();
    }
    public function update_absence($datos, $ausencia_id)
    {
        $Absences = $this->db->table('t_ausencias');
        $Absences->set($datos);
        $Absences->where('ausencia_id', $ausencia_id);
        return $Absences->update();
    }
    public function delete_absence($data)
    {
        $Absences = $this->db->table('t_ausencias');
        $Absences->where($data);
        return $Absences->delete();
    }
    
}