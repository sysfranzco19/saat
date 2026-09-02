<?php

namespace App\Models;

use CodeIgniter\Model;

class PhaseModel extends Model
{
    protected $table      = 'phase';
    protected $primaryKey = 'phase_id';

    function get_phases($type) {
        $Setting = $this->db->table('phase');
        $Setting->where($type);
        return $Setting->get()->getResultArray();
    }
    function listar_phases() {
        $Setting = $this->db->table('phase');
        $Setting->orderBy('phase_id', 'ASC');
        return $Setting->get()->getResultArray();
    }
    public function get_phase($data)
    {
        $Phase = $this->db->table('phase');
        $Phase->where($data);
        return $Phase->get()->getResultArray();
    }
    public function next_phase_id()
    {
        $sql = "SELECT IFNULL(MAX(phase_id), 0) + 1 AS next_id FROM phase";
        $res = $this->db->query($sql)->getRowArray();
        return (int) $res['next_id'];
    }
    public function insert_phase($datos)
    {
        // Solo un trimestre puede estar activo a la vez
        if (!empty($datos['activo'])) {
            $this->db->table('phase')->update(['activo' => 0]);
        }
        $Phase = $this->db->table('phase');
        $Phase->insert($datos);
        return $this->db->insertID();
    }
    public function update_phase($datos, $phase_id)
    {
        // Solo un trimestre puede estar activo a la vez
        if (!empty($datos['activo'])) {
            $this->db->table('phase')->where('phase_id !=', $phase_id)->update(['activo' => 0]);
        }
        $Phase = $this->db->table('phase');
        $Phase->set($datos);
        $Phase->where('phase_id', $phase_id);
        return $Phase->update();
    }
    public function delete_phase($phase_id)
    {
        $Phase = $this->db->table('phase');
        $Phase->where('phase_id', $phase_id);
        return $Phase->delete();
    }
    public function updateTPhase()
    {
        // Leemos phase de la base principal (tiqui0_tiquiweb26)
        $db1 = $this->db;
        $query = $db1->table('phase')->get()->getResultArray();
        // Replicamos hacia tiqui0_tiquisaat26 (espejo)
        $db2 = \Config\Database::connect('tiquipaya');
        $db2->table('phase')->truncate();
        foreach ($query as $row) {
            $db2->table('phase')->insert($row);
        }
    }
}
