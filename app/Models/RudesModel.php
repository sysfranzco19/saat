<?php

namespace App\Models;

use CodeIgniter\Model;

class RudesModel extends Model
{
    public function listar_rudes()
    {
        $rudes = $this->db->query("SELECT * FROM rudes");
        return $rudes->getResult();
    }
    public function get_rudes($data){
        $rudes = $this->db->table('rudes');
        $rudes->where($data);
        return $rudes->get()->getResultArray();
    }
    public function get_rudes_info($student_id){
        $consulta = $this->db->query("SELECT * FROM rudes WHERE id_rude = ".$student_id);
        return $consulta->getResult();
    }
    public function insert_rudes($datos)
    {
        $rudes = $this->db->table("rudes");
        $rudes->insert($datos);
        return $this->db->insertID();
    }
    public function update_rudes($datos, $rudes_id)
    {
        $rudes = $this->db->table('rudes');
        $rudes->set($datos);
        $rudes->where('id_rude', $rudes_id);
        return $rudes->update();
    }
    public function delete_rudes($data)
    {
        $rudes = $this->db->table('rudes');
        $rudes->where($data);
        return $rudes->delete();
    }
}