<?php

namespace App\Models;

use CodeIgniter\Model;

class EsintomaModel extends Model
{
    protected $DBGroup = 'asistencia';
    public function listarEsintomas()
    {
        $Esintoma = $this->db->query("SELECT s.*, c.nombre as categoria FROM e_sintomas as s LEFT JOIN e_categorias as c ON s.categoria_id=c.id");
        return $Esintoma->getResult();
    }
    public function getEsintoma($data){
        $Esintoma = $this->db->table('e_sintomas');
        $Esintoma->where($data);
        return $Esintoma->get()->getResultArray();
    }
    public function insertEsintoma($datos)
    {
        $Esintoma = $this->db->table("e_sintomas");
        $Esintoma->insert($datos);
        return $this->db->insertID();
    }
    public function updateEsintoma($datos, $id)
    {
        $Esintoma = $this->db->table('e_sintomas');
        $Esintoma->set($datos);
        $Esintoma->where('id', $id);
        return $Esintoma->update();
    }
    public function deleteEsintoma($data)
    {
        $Esintoma = $this->db->table('e_sintomas');
        $Esintoma->where($data);
        return $Esintoma->delete();
    }
}