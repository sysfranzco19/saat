<?php

namespace App\Models;

use CodeIgniter\Model;

class FamilyModel extends Model
{
    public function insertFamily($datos)
    {
        $Family = $this->db->table("t_family");
        $Family->insert($datos);
        return $this->db->affectedRows();
    }
    public function activesFamily()
    {
        $Family = $this->db->query("SELECT family_id, CONCAT(lastname1,' ', lastname2) as family FROM t_family ORDER BY lastname1, lastname2");
        return $Family->getResult();
    }
    public function truncateFamily(){
        //$Family = $this->db->table('t_family');
        //$Family->truncate();
        //return $Family->get()->getResultArray();
    }
    public function get_family($data){
        $family = $this->db->table('t_family');
        $family->where($data);
        return $family->get()->getResultArray();
    }
    public function get_family_datas($data){
        $family = $this->db->table('t_family as f');
        $family->select('f.*, r.relation');
        $family->join('relation_parent as r', 'f.relation_id = r.relation_parent_id');
        $family->where($data);
        return $family->get()->getResultArray();
    }
    public function get_family_student($student_id){
        $sql = "SELECT f.family_id, f.email1, f.email2 FROM t_student s 
        INNER JOIN t_family f ON(f.family_id=s.family_id) 
        WHERE s.student_id=".$student_id;
        $family = $this->db->query($sql);
        return $family->getResultArray();
    }
    /***************************************************SOLO PARA ADMINISTRACION ***************** */
    public function update_family($datos, $family_id)
    {
        $family = $this->db->table('t_family');
        $family->set($datos);
        $family->where('family_id', $family_id);
        return $family->update();
    }
    public function get_family_emails($student_id){
        $sql = "SELECT s.student_id, s.section_id, f.email1, f.email2 FROM t_student s 
        INNER JOIN t_family f ON(s.family_id=f.family_id) 
        WHERE s.student_id=".$student_id;
        $family = $this->db->query($sql);
        return $family->getResultArray();
    }
    public function get_apifamily($family_id){
        $sql = "SELECT f.* FROM t_family as f WHERE f.family_id=".$family_id; 
        $family = $this->db->query($sql);
        return $family->getResultArray();
        //$Family = $this->db->query("SELECT f.* FROM t_family as f WHERE f.family_id=".$family_id);
        //return $Family->getResult();
    }
    public function family_user($sel){
        $sql = "SELECT f.* FROM t_family f INNER JOIN t_student e ON(f.family_id=e.family_id) WHERE e.matricula<>0 AND f.lastname1 LIKE '%" .$sel. "%' ORDER BY f.lastname1";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
}