<?php

namespace App\Models;

use CodeIgniter\Model;

class EdatomedicoModel extends Model
{
    protected $DBGroup = 'asistencia';
    public function listarEdatomedicos()
    {
        $Edatomedico = $this->db->query("SELECT d.*, t.nombre as tipo, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student FROM e_dato_medico as d 
        LEFT JOIN e_tipo_dato_medico as t ON d.tipo_id=t.id
        LEFT JOIN t_student as e ON d.student_id=e.student_id");
        return $Edatomedico->getResult();
    }
    public function getEdatomedico($data){
        $Edatomedico = $this->db->table('e_dato_medico');
        $Edatomedico->where($data);
        return $Edatomedico->get()->getResultArray();
    }
    public function insertEdatomedico($datos)
    {
        $Edatomedico = $this->db->table("e_dato_medico");
        $Edatomedico->insert($datos);
        return $this->db->insertID();
    }
    public function updateEdatomedico($datos, $id)
    {
        $Edatomedico = $this->db->table('e_dato_medico');
        $Edatomedico->set($datos);
        $Edatomedico->where('id', $id);
        return $Edatomedico->update();
    }
    public function deleteEdatomedico($data)
    {
        $Edatomedico = $this->db->table('e_dato_medico');
        $Edatomedico->where($data);
        return $Edatomedico->delete();
    }
}