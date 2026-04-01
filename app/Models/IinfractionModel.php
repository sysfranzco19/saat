<?php

namespace App\Models;

use CodeIgniter\Model;

class IinfractionModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function listar_infraction()
    {
        $infraction = $this->db->query("SELECT * FROM i_infractions");
        return $infraction->getResultArray();
    }
    public function get_infraction($data){
        $infraction = $this->db->table('i_infractions');
        $infraction->where($data);
        return $infraction->get()->getResultArray();
    }
    public function insert_infraction($datos)
    {
        $infraction = $this->db->table("i_infractions");
        $infraction->insert($datos);
        return $this->db->insertID();
    }
    public function update_infraction($datos, $infraction_id)
    {
        $infraction = $this->db->table('i_infractions');
        $infraction->set($datos);
        $infraction->where('infraction_id', $infraction_id);
        return $infraction->update();
    }
    public function delete_infraction($data)
    {
        $infraction = $this->db->table('i_infractions');
        $infraction->where($data);
        return $infraction->delete();
    }

    public function infraction_section($section_id){
        $sql='SELECT i.infraction_id, i.student_id, i.subject_id, i.date, i.detail, i.number, i.supervised, c.criteria, t.style, s.name as materia FROM i_infractions i
        INNER JOIN i_criteria c ON(i.criterio_id=c.criteria_id) 
        INNER JOIN i_types_fouls t ON(c.type_foul_id=t.type_foul_id) 
        INNER JOIN subject s ON(i.subject_id=s.subject_id)
        WHERE i.section_id='.$section_id;
        $infraction = $this->db->query($sql);
        return $infraction->getResultArray();
    }
    public function infraction_subjects($section_id){
        $sql='SELECT i.student_id, i.subject_id, s.name, COUNT(i.student_id) as cantidad FROM i_infractions i
        INNER JOIN subject s ON(i.subject_id=s.subject_id)
        WHERE i.section_id='.$section_id.' 
        GROUP BY i.student_id, i.subject_id, s.name;';
        $infraction = $this->db->query($sql);
        return $infraction->getResultArray();
    }
    public function infraction_student($student_id, $subject_id){
        $sql='SELECT i.*, c.criteria, t.style, s.name as materia, d.name as docente FROM i_infractions i
        INNER JOIN i_criteria c ON(i.criterio_id=c.criteria_id) 
        INNER JOIN i_types_fouls t ON(c.type_foul_id=t.type_foul_id) 
        INNER JOIN subject s ON(i.subject_id=s.subject_id) 
        INNER JOIN teacher d ON(s.teacher_id=d.teacher_id) 
        WHERE i.student_id='.$student_id.' AND i.subject_id='.$subject_id;
        $infraction = $this->db->query($sql);
        return $infraction->getResultArray();
    }
    public function infractions_student($student_id){
        $sql='SELECT i.infraction_id, i.date, s.name as materia, d.name as docente, c.criteria, i.detail FROM i_infractions i
        INNER JOIN i_criteria c ON(i.criterio_id=c.criteria_id) 
        INNER JOIN i_types_fouls t ON(c.type_foul_id=t.type_foul_id) 
        INNER JOIN subject s ON(i.subject_id=s.subject_id) 
        INNER JOIN teacher d ON(s.teacher_id=d.teacher_id) 
        WHERE i.student_id='.$student_id;
        $infraction = $this->db->query($sql);
        return $infraction->getResultArray();
    }
    public function infraction_family($family_id){
        $sql='SELECT i.*, c.criteria, t.style, s.name as materia, d.name as docente FROM i_infractions i
        INNER JOIN i_criteria c ON(i.criterio_id=c.criteria_id) 
        INNER JOIN i_types_fouls t ON(c.type_foul_id=t.type_foul_id) 
        INNER JOIN subject s ON(i.subject_id=s.subject_id)
        INNER JOIN t_student a ON(a.student_id=i.student_id) 
        INNER JOIN teacher d ON(s.teacher_id=d.teacher_id) 
        WHERE a.family_id='.$family_id;
        $infraction = $this->db->query($sql);
        return $infraction->getResultArray();
    }
    public function infraction_edit($criterio_id){
        $sql='SELECT i.*, c.criteria, c.type_foul_id, t.style, s.name as materia, CONCAT(a.lastname, " ", a.lastname2," ", a.name) as student FROM i_infractions i
        INNER JOIN i_criteria c ON(i.criterio_id=c.criteria_id) 
        INNER JOIN i_types_fouls t ON(c.type_foul_id=t.type_foul_id) 
        INNER JOIN subject s ON(i.subject_id=s.subject_id)
        INNER JOIN t_student a ON(i.student_id=a.student_id)
        WHERE i.infraction_id='.$criterio_id;
        $infraction = $this->db->query($sql);
        return $infraction->getResultArray();
    }
    public function infraction_emails($student_id, $subject_id){
        $sql='SELECT DISTINCT(t.email) as emailDocente
        FROM i_infractions i 
        INNER JOIN t_student s ON(s.student_id=i.student_id) 
        INNER JOIN subject m ON(i.subject_id=m.subject_id)
        INNER JOIN teacher t ON(m.teacher_id=t.teacher_id) 
        WHERE i.student_id='.$student_id.' AND i.subject_id='.$subject_id;
        $infraction = $this->db->query($sql);
        return $infraction->getResultArray();
    }
    public function infraction_letter(){
        $sql='SELECT i.student_id, a.section_id, CONCAT(a.lastname, " ", a.lastname2," ", a.name) as student, COUNT(*) as Indisciplinas 
        FROM i_infractions i INNER JOIN t_student a ON(i.student_id=a.student_id)
        GROUP BY i.student_id HAVING COUNT(*)>=4 ORDER BY a.lastname, a.lastname2, a.name';
        $infraction = $this->db->query($sql);
        return $infraction->getResultArray();
    }
}