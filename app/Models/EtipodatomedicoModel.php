<?php

namespace App\Models;

use CodeIgniter\Model;

class EtipodatomedicoModel extends Model
{
    protected $DBGroup = 'asistencia';
    public function listarEtipodatomedicos()
    {
        $Etipodatomedico = $this->db->query("SELECT * FROM e_tipo_dato_medico");
        return $Etipodatomedico ->getResult();
    }
    public function getEtipodatomedico($data){
        $Etipodatomedico  = $this->db->table('e_tipo_dato_medico');
        $Etipodatomedico ->where($data);
        return $Etipodatomedico ->get()->getResultArray();
    }
    public function insertEtipodatomedico($datos)
    {
        $Etipodatomedico  = $this->db->table("e_tipo_dato_medico");
        $Etipodatomedico ->insert($datos);
        return $this->db->insertID();
    }
    public function updateEtipodatomedico($datos, $id)
    {
        $Etipodatomedico  = $this->db->table('e_tipo_dato_medico');
        $Etipodatomedico ->set($datos);
        $Etipodatomedico ->where('id', $id);
        return $Etipodatomedico ->update();
    }
    public function deleteEtipodatomedico($data)
    {
        $Etipodatomedico  = $this->db->table('e_tipo_dato_medico');
        $Etipodatomedico ->where($data);
        return $Etipodatomedico ->delete();
    }
}