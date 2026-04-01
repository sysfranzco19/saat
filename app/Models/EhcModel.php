<?php

namespace App\Models;

use CodeIgniter\Model;

class EhcModel extends Model
{
    protected $DBGroup = 'asistencia';
    public function listarEhcs()
    {
        $Ehc = $this->db->query("SELECT h.*, s.nombre as sintoma, m.nombre as medicamento, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as nombre FROM e_hcs as h 
                                LEFT JOIN e_sintomas as s ON h.sintoma_id=s.id 
                                LEFT JOIN e_medicamentos as m ON h.medicamento_id=m.id 
                                LEFT JOIN t_student as e ON h.student_id=e.student_id");
        return $Ehc->getResult();
    }
    public function getEhc($data){
        $Ehc = $this->db->table('e_hcs');
        $Ehc->where($data);
        return $Ehc->get()->getResultArray();
    }
    public function insertEhc($datos)
    {
        $Ehc = $this->db->table("e_hcs");
        $Ehc->insert($datos);
        return $this->db->insertID();
    }
    public function updateEhc($datos, $id)
    {
        $Ehc = $this->db->table('e_hcs');
        $Ehc->set($datos);
        $Ehc->where('id', $id);
        return $Ehc->update();
    }
    public function deleteEhc($data)
    {
        $Ehc = $this->db->table('e_hcs');
        $Ehc->where($data);
        return $Ehc->delete();
    }
}