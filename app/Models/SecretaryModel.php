<?php
namespace App\Models;
use CodeIgniter\Model;
class SecretaryModel extends Model
{
    public function listar_secretary()
    {
        $secretary = $this->db->query("SELECT * FROM secretary");
        return $secretary->getResult();
    }
    public function get_secretary($data){
        $secretary = $this->db->table('secretary');
        $secretary->where($data);
        return $secretary->get()->getResultArray();
    }
    public function insert_secretary($datos)
    {
        $secretary = $this->db->table("secretary");
        $secretary->insert($datos);
        return $this->db->insertID();
    }
    public function update_secretary($datos, $secretary_id)
    {
        $secretary = $this->db->table('secretary');
        $secretary->set($datos);
        $secretary->where('secretary_id', $secretary_id);
        return $secretary->update();
    }
    public function delete_secretary($data)
    {
        $secretary = $this->db->table('secretary');
        $secretary->where($data);
        return $secretary->delete();
    }
    public function get_sections_by_secretary_id($secretary_id)
    {
        $secretary = $this->db->table('secretary');
        $secretary->select('section_ini, section_fin');
        $secretary->where('secretary_id', $secretary_id);
        $result = $secretary->get()->getRowArray();
        return $result;
    }
}