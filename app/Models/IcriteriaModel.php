<?php

namespace App\Models;

use CodeIgniter\Model;

class IcriteriaModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function listar_criteria()
    {
        $criteria = $this->db->query("SELECT * FROM i_criteria");
        return $criteria->getResultArray();
    }
    public function get_criteria($data){
        $criteria = $this->db->table('i_criteria');
        $criteria->where($data);
        return $criteria->get()->getResultArray();
    }
    public function insert_criteria($datos)
    {
        $criteria = $this->db->table("i_criteria");
        $criteria->insert($datos);
        return $this->db->insertID();
    }
    public function update_criteria($datos, $criteria_id)
    {
        $criteria = $this->db->table('i_criteria');
        $criteria->set($datos);
        $criteria->where('criteria_id', $criteria_id);
        return $criteria->update();
    }
    public function delete_criteria($data)
    {
        $criteria = $this->db->table('i_criteria');
        $criteria->where($data);
        return $criteria->delete();
    }
}