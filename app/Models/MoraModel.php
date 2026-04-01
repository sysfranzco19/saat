<?php

namespace App\Models;

use CodeIgniter\Model;

class MoraModel extends Model
{
    public function listar_mora()
    {
        $mora = $this->db->query("SELECT * FROM t_mora");
        return $mora->getResult();
    }
    public function get_mora($data){
        $mora = $this->db->table('t_mora');
        $mora->where($data);
        return $mora->get()->getResultArray();
    }
    public function insert_mora($datos)
    {
        $mora = $this->db->table("t_mora");
        $mora->insert($datos);
        return $this->db->insertID();
    }
    public function update_mora($datos, $mora_id)
    {
        $mora = $this->db->table('t_mora');
        $mora->set($datos);
        $mora->where('mora_id', $mora_id);
        return $mora->update();
    }
    public function delete_mora($data)
    {
        $mora = $this->db->table('t_mora');
        $mora->where($data);
        return $mora->delete();
    }
}