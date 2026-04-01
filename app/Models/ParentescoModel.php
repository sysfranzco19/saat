<?php

namespace App\Models;

use CodeIgniter\Model;

class ParentescoModel extends Model
{
    protected $DBGroup = 'tiquipaya';
    public function listarParentescos()
    {
        $Parentescos = $this->db->query("SELECT * FROM t_parentesco");
        return $Parentescos->getResult();
    }
    public function getParentesco($data){
        $Parentescos = $this->db->table('t_parentesco');
        $Parentescos->where($data);
        return $Parentescos->get()->getResultArray();
    }
    public function insertParentesco($datos)
    {
        $Parentescos = $this->db->table("t_parentesco");
        $Parentescos->insert($datos);
        return $this->db->insertID();
    }
    public function updateParentesco($datos, $parentesco_id)
    {
        $Parentescos = $this->db->table('t_parentesco');
        $Parentescos->set($datos);
        $Parentescos->where('parentesco_id', $parentesco_id);
        return $Parentescos->update();
    }
    public function deleteParentesco($data)
    {
        $Parentescos = $this->db->table('t_parentesco');
        $Parentescos->where($data);
        return $Parentescos->delete();
    }
}
