<?php

namespace App\Models;

use CodeIgniter\Model;

class LicenciaperiodoModel extends Model
{
    protected $DBGroup = 'asistencia'; // Asegúrate de que este es el grupo de BD correcto
    protected $table = 't_licencias_periodo'; 
    protected $primaryKey = 'id'; 
    
    protected $useAutoIncrement = true;
    
    protected $allowedFields = [
        'licencias_id',
        'fecha',
        'periodo_id'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';
    
    // --- MÉTODOS CRUD PERSONALIZADOS ---

    public function listarLicenciasPeriodo()
    {
        $LicenciasPeriodo = $this->db->query("SELECT * FROM t_licencias_periodo");
        return $LicenciasPeriodo->getResult();
    }

    public function getLicenciaPeriodo($id)
    {
        $sql = "SELECT * FROM t_licencias_periodo WHERE id=" . $id;
        $LicenciasPeriodo = $this->db->query($sql);
        return $LicenciasPeriodo->getResultArray();
    }

    public function get_licencia_periodo($data)
    {
        $LicenciasPeriodo = $this->db->table('t_licencias_periodo');
        $LicenciasPeriodo->where($data);
        return $LicenciasPeriodo->get()->getResultArray();
    }

    public function insertLicenciaPeriodo($datos)
    {
        $LicenciasPeriodo = $this->db->table("t_licencias_periodo");
        $LicenciasPeriodo->insert($datos);
        return $this->db->insertID();
    }

    public function updateLicenciaPeriodo($datos, $id)
    {
        $LicenciasPeriodo = $this->db->table('t_licencias_periodo');
        $LicenciasPeriodo->set($datos);
        $LicenciasPeriodo->where('id', $id);
        return $LicenciasPeriodo->update();
    }

    public function deleteLicenciaPeriodo($data)
    {
        $LicenciasPeriodo = $this->db->table('t_licencias_periodo');
        $LicenciasPeriodo->where($data);
        return $LicenciasPeriodo->delete();
    }

    // --- EXTRA: Buscar todos los periodos de una licencia específica ---
    public function get_por_licencia($licencias_id)
    {
        return $this->where('licencias_id', $licencias_id)->findAll();
    }
}
