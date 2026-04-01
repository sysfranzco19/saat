<?php

namespace App\Models;

use CodeIgniter\Model;

class MotivoModel extends Model
{
    protected $DBGroup = 'asistencia';
    protected $table = 't_motivos';
    protected $primaryKey = 'motivo_id'; // Definir la clave primaria si es necesario
    protected $returnType = 'array'; // Definir el tipo de retorno si es necesario

    public function listarMotivos()
    {
        $Motivos = $this->db->query("SELECT * FROM t_motivos");
        return $Motivos->getResult();
    }
    public function getMotivo($data){
        $Motivos = $this->db->table('t_motivos');
        $Motivos->where($data);
        return $Motivos->get()->getResultArray();
    }
    public function insertMotivo($datos)
    {
        $Motivos = $this->db->table("t_motivos");
        $Motivos->insert($datos);
        return $this->db->insertID();
    }
    public function updateMotivo($datos, $motivo_id)
    {
        $Motivos = $this->db->table('t_motivos');
        $Motivos->set($datos);
        $Motivos->where('motivo_id', $motivo_id);
        return $Motivos->update();
    }
    public function deleteMotivo($data)
    {
        $Motivos = $this->db->table('t_motivos');
        $Motivos->where($data);
        return $Motivos->delete();
    }
    public function get_motivos()
    {
        return $this->findAll();
    }
}
