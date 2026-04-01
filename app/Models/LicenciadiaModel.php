<?php

namespace App\Models;

use CodeIgniter\Model;

class LicenciadiaModel extends Model
{
    protected $DBGroup = 'asistencia'; // Asegúrate de que este es el grupo de BD correcto
    protected $table = 't_licencias_dia'; 
    protected $primaryKey = 'id'; 
    
    protected $useAutoIncrement = true;
    
    protected $allowedFields = [
        'licencias_id',
        'fecha_inicio',
        'fecha_fin',
        'cantidad_dias'
    ];

    protected $useTimestamps = false;
    protected $returnType = 'array';
    
    // --- MÉTODOS CRUD PERSONALIZADOS ---

    public function listarLicenciasDia()
    {
        $LicenciasDia = $this->db->query("SELECT * FROM t_licencias_dia");
        return $LicenciasDia->getResult();
    }

    public function getLicenciaDia($id)
    {
        $sql = "SELECT * FROM t_licencias_dia WHERE id=" . $id;
        $LicenciasDia = $this->db->query($sql);
        return $LicenciasDia->getResultArray();
    }

    public function get_licencia_dia($data)
    {
        $LicenciasDia = $this->db->table('t_licencias_dia');
        $LicenciasDia->where($data);
        return $LicenciasDia->get()->getResultArray();
    }

    public function insertLicenciaDia($datos)
    {
        $LicenciasDia = $this->db->table("t_licencias_dia");
        $LicenciasDia->insert($datos);
        return $this->db->insertID();
    }

    public function updateLicenciaDia($datos, $id)
    {
        $LicenciasDia = $this->db->table('t_licencias_dia');
        $LicenciasDia->set($datos);
        $LicenciasDia->where('id', $id);
        return $LicenciasDia->update();
    }

    public function deleteLicenciaDia($data)
    {
        $LicenciasDia = $this->db->table('t_licencias_dia');
        $LicenciasDia->where($data);
        return $LicenciasDia->delete();
    }

    // --- EXTRA: Buscar todos los días de una licencia específica ---
    public function get_por_licencia($licencias_id)
    {
        return $this->where('licencias_id', $licencias_id)->findAll();
    }
}
