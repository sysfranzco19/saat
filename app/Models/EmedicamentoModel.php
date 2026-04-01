<?php

namespace App\Models;

use CodeIgniter\Model;

class EmedicamentoModel extends Model
{
    protected $DBGroup = 'asistencia';
    public function listarEmedicamentos()
    {
        $Emedicamento = $this->db->query("SELECT * FROM e_medicamentos");
        return $Emedicamento->getResult();
    }
    public function getEmedicamento($data){
        $Emedicamento = $this->db->table('e_medicamentos');
        $Emedicamento->where($data);
        return $Emedicamento->get()->getResultArray();
    }
    public function insertEmedicamento($datos)
    {
        $Emedicamento = $this->db->table("e_medicamentos");
        $Emedicamento->insert($datos);
        return $this->db->insertID();
    }
    public function updateEmedicamento($datos, $id)
    {
        $Emedicamento = $this->db->table('e_medicamentos');
        $Emedicamento->set($datos);
        $Emedicamento->where('id', $id);
        return $Emedicamento->update();
    }
    public function deleteEmedicamento($data)
    {
        $Emedicamento = $this->db->table('e_medicamentos');
        $Emedicamento->where($data);
        return $Emedicamento->delete();
    }
}