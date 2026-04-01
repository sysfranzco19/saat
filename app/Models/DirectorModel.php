<?php

namespace App\Models;

use CodeIgniter\Model;

class DirectorModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function listar_director()
    {
        $director = $this->db->query("SELECT * FROM director");
        return $director->getResult();
    }
    public function get_director($data){
        $director = $this->db->table('director');
        $director->where($data);
        return $director->get()->getResultArray();
    }
    public function obtenerdirector($data){
        $Usuario = $this->db->table('director');
        $Usuario->where($data);
        return $Usuario->get()->getResultArray();
    }
    public function insert_director($datos)
    {
        $director = $this->db->table("director");
        $director->insert($datos);
        return $this->db->insertID();
    }
    public function update_director($datos, $director_id)
    {
        $director = $this->db->table('director');
        $director->set($datos);
        $director->where('director_id', $director_id);
        return $director->update();
    }
    public function delete_director($data)
    {
        $director = $this->db->table('director');
        $director->where($data);
        return $director->delete();
    }
}