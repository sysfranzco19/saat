<?php

namespace App\Models;

use CodeIgniter\Model;

class ItypesfoulsModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function listar_types_fouls()
    {
        $types_fouls = $this->db->query("SELECT * FROM i_types_fouls");
        return $types_fouls->getResultArray();
    }
    public function get_types_fouls($data){
        $types_fouls = $this->db->table('i_types_fouls');
        $types_fouls->where($data);
        return $types_fouls->get()->getResultArray();
    }
    public function insert_types_fouls($datos)
    {
        $types_fouls = $this->db->table("i_types_fouls");
        $types_fouls->insert($datos);
        return $this->db->insertID();
    }
    public function update_types_fouls($datos, $types_fouls_id)
    {
        $types_fouls = $this->db->table('i_types_fouls');
        $types_fouls->set($datos);
        $types_fouls->where('types_fouls_id', $types_fouls_id);
        return $types_fouls->update();
    }
    public function delete_types_fouls($data)
    {
        $types_fouls = $this->db->table('i_types_fouls');
        $types_fouls->where($data);
        return $types_fouls->delete();
    }
}