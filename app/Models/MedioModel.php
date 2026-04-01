<?php

namespace App\Models;

use CodeIgniter\Model;

class MedioModel extends Model
{
    protected $DBGroup = 'asistencia';
    public function listarMedios()
    {
        $Medios = $this->db->query("SELECT * FROM t_medios");
        return $Medios->getResult();
    }
    public function getMedio($data){
        $Medio = $this->db->table('t_medios');
        $Medio->where($data);
        return $Medio->get()->getResultArray();
    }
    public function insertMedio($datos)
    {
        $Medios = $this->db->table("t_medios");
        $Medios->insert($datos);
        return $this->db->insertID();
    }
    public function updateMedio($datos, $medio_id)
    {
        $Medios = $this->db->table('t_medios');
        $Medios->set($datos);
        $Medios->where('medio_id', $medio_id);
        return $Medios->update();
    }
    public function deleteMedio($data)
    {
        $Medios = $this->db->table('t_medios');
        $Medios->where($data);
        return $Medios->delete();
    }
}