<?php

namespace App\Models;

use CodeIgniter\Model;

class ContinuityModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function listar_continuity()
    {
        $continuity = $this->db->query("SELECT * FROM continuity");
        return $continuity->getResult();
    }
    public function get_continuity($data){
        $continuity = $this->db->table('continuity');
        $continuity->where($data);
        return $continuity->get()->getResultArray();
    }
    public function insert_continuity($datos)
    {
        $continuity = $this->db->table("continuity");
        $continuity->insert($datos);
        return $this->db->insertID();
    }
    public function update_continuity($datos, $continuity_id)
    {
        $continuity = $this->db->table('continuity');
        $continuity->set($datos);
        $continuity->where('continuity_id', $continuity_id);
        return $continuity->update();
    }
    public function delete_continuity($data)
    {
        $continuity = $this->db->table('continuity');
        $continuity->where($data);
        return $continuity->delete();
    }
    public function continuity_family($family_id){
        $sql = "SELECT t1.student_id, CONCAT(t1.lastname,' ', t1.lastname2, ' ', t1.name) as student, t2.completo, t3.respuesta, t1.section_id 
        FROM t_student as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) 
        LEFT JOIN continuity as t3 ON(t1.student_id=t3.student_id) WHERE t1.family_id=".$family_id." AND t1.section_id<341 AND t1.activo=1 ORDER BY t1.section_id";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function continuity_students_10(){
        $sql = "SELECT CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student, s.completo, c.respuesta 
        FROM continuity c INNER JOIN t_student e ON(c.student_id=e.student_id) 
        INNER JOIN section s ON(e.section_id=s.section_id)
        WHERE 1 ORDER BY `continuity_id` DESC LIMIT 10";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
}