<?php

namespace App\Models;

use CodeIgniter\Model;

class BehaviorsModel extends Model
{
    protected $DBGroup = 'tiquipaya';
    public function listar_behaviors()
    {
        $behaviors = $this->db->query("SELECT * FROM behaviors");
        return $behaviors->getResult();
    }
    public function get_behaviors($data){
        $behaviors = $this->db->table('behaviors');
        $behaviors->where($data);
        return $behaviors->get()->getResultArray();
    }
    public function insert_behaviors($datos)
    {
        $behaviors = $this->db->table("behaviors");
        $behaviors->insert($datos);
        return $this->db->insertID();
    }
    public function update_behaviors($datos, $behaviors_id)
    {
        $behaviors = $this->db->table('behaviors');
        $behaviors->set($datos);
        $behaviors->where('behaviors_id', $behaviors_id);
        return $behaviors->update();
    }
    public function update_behaviors_student($student_id)
    {
        $datos = ["viewed" => 1 ];
        $behaviors = $this->db->table('behaviors');
        $behaviors->set($datos);
        $behaviors->where('student_id', $student_id);
        return $behaviors->update();
    }
    public function delete_behaviors($data)
    {
        $behaviors = $this->db->table('behaviors');
        $behaviors->where($data);
        return $behaviors->delete();
    }

    public function behaviors_subject($section_id, $teacher_id){
        $sql='SELECT t1.behavior_id, t1.student_id, CONCAT(t2.lastname, " ", t2.lastname2," ", t2.name) as student, 
                t3.name as subject, t4.name as teacher, t1.behavior, t1.date, t1.type, t1.viewed FROM behaviors as t1 
                INNER JOIN t_student as t2 ON(t1.student_id=t2.student_id)
                INNER JOIN subject as t3 ON(t1.subject_id=t3.subject_id)
                INNER JOIN teacher as t4 ON(t3.teacher_id=t4.teacher_id)
                WHERE t3.section_id='.$section_id.' AND t3.teacher_id='.$teacher_id.' ORDER BY t1.date DESC';
        /*$sql = "SELECT s.student_id, CONCAT(s.lastname, ' ', s.lastname2, ' ',s.name) AS nombre, c.nick_name, ad.diagnostico, ad.adaptaciones, ad.observaciones 
        FROM behaviors as ad 
        INNER JOIN t_student as s ON(ad.student_id=s.student_id)
        INNER JOIN section as c ON(s.section_id=c.section_id)";
        */
        $behaviors = $this->db->query($sql);
        //return $student->get()->getResultArray();
        return $behaviors->getResultArray();
    }

    public function behaviors_student($phase_id, $student_id){
        $sql='SELECT t1.behavior_id, t1.date, t2.name as materia, t3.name as docente, t1.behavior, t1.type 
        FROM behaviors AS t1 INNER JOIN subject AS t2 ON(t1.subject_id=t2.subject_id) 
        INNER JOIN teacher AS t3 ON(t2.teacher_id=t3.teacher_id) WHERE t1.phase_id='.$phase_id.' AND t1.student_id='.$student_id.' ORDER BY t1.date DESC';
        $behaviors = $this->db->query($sql);
        return $behaviors->getResultArray();
    }
    public function behaviors_family($phase_id, $family_id){
        $sql="SELECT t1.student_id, CONCAT(t1.lastname,' ', t1.lastname2,' ', t1.name) as student, t3.completo, COUNT(t2.behavior_id) as reportes 
        FROM t_student as t1 INNER JOIN section as t3 ON(t1.section_id=t3.section_id)
        LEFT JOIN behaviors as t2 ON(t1.student_id=t2.student_id) 
        WHERE t1.family_id=".$family_id." GROUP BY t1.student_id";
        $behaviors = $this->db->query($sql);
        return $behaviors->getResultArray();
    }

}