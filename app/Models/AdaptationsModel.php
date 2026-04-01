<?php

namespace App\Models;

use CodeIgniter\Model;

class AdaptationsModel extends Model
{
    protected $DBGroup = 'tiquipaya';
    public function listar_curricular_adaptations()
    {
        $curricular_adaptations = $this->db->query("SELECT * FROM curricular_adaptations");
        return $curricular_adaptations->getResult();
    }
    public function get_curricular_adaptations($data){
        $curricular_adaptations = $this->db->table('curricular_adaptations');
        $curricular_adaptations->where($data);
        return $curricular_adaptations->get()->getResultArray();
    }
    public function insert_curricular_adaptations($datos)
    {
        $curricular_adaptations = $this->db->table("curricular_adaptations");
        $curricular_adaptations->insert($datos);
        return $this->db->insertID();
    }
    public function update_curricular_adaptations($datos, $curricular_adaptations_id)
    {
        $curricular_adaptations = $this->db->table('curricular_adaptations');
        $curricular_adaptations->set($datos);
        $curricular_adaptations->where('curricular_adaptations_id', $curricular_adaptations_id);
        return $curricular_adaptations->update();
    }
    public function delete_curricular_adaptations($data)
    {
        $curricular_adaptations = $this->db->table('curricular_adaptations');
        $curricular_adaptations->where($data);
        return $curricular_adaptations->delete();
    }

    public function adaptations(){
        $sql = "SELECT s.student_id, CONCAT(s.lastname, ' ', s.lastname2, ' ',s.name) AS nombre, c.nick_name, ad.diagnostico, ad.adaptaciones, ad.observaciones 
        FROM curricular_adaptations as ad 
        INNER JOIN t_student as s ON(ad.student_id=s.student_id)
        INNER JOIN section as c ON(s.section_id=c.section_id)";
        $adaptations = $this->db->query($sql);
        //return $student->get()->getResultArray();
        return $adaptations->getResultArray();
    }
}