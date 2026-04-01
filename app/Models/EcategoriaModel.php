<?php

namespace App\Models;

use CodeIgniter\Model;

class EcategoriaModel extends Model
{
    protected $DBGroup = 'asistencia';
    public function listarEcategorias()
    {
        $Categorias = $this->db->query("SELECT * FROM e_categorias");
        return $Categorias->getResult();
    }
    public function getEcategoria($data){
        $Ecategoria = $this->db->table('e_categorias');
        $Ecategoria->where($data);
        return $Ecategoria->get()->getResultArray();
    }
    public function insertEcategoria($datos)
    {
        $Categorias = $this->db->table("e_categorias");
        $Categorias->insert($datos);
        return $this->db->insertID();
    }
    public function updateEcategoria($datos, $id)
    {
        $Categorias = $this->db->table('e_categorias');
        $Categorias->set($datos);
        $Categorias->where('id', $id);
        return $Categorias->update();
    }
    public function deleteEcategoria($data)
    {
        $Categorias = $this->db->table('e_categorias');
        $Categorias->where($data);
        return $Categorias->delete();
    }
}