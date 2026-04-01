<?php

namespace App\Models;

use CodeIgniter\Model;

class AssistanceobsModel extends Model
{
    protected $DBGroup = 'asistencia';

    public function listar_assistance_obs()
    {
        $assistance_obs = $this->db->query("SELECT * FROM assistance_obs");
        return $assistance_obs->getResultArray();
    }
    public function get_assistance_obs($data){
        $assistance_obs = $this->db->table('assistance_obs');
        $assistance_obs->where($data);
        return $assistance_obs->get()->getResultArray();
    }
    public function insert_assistance_obs($datos)
    {
        $assistance_obs = $this->db->table("assistance_obs");
        $assistance_obs->insert($datos);
        return $this->db->insertID();
    }
    public function update_assistance_obs($datos, $assistance_obs_id)
    {
        $assistance_obs = $this->db->table('assistance_obs');
        $assistance_obs->set($datos);
        $assistance_obs->where('assistance_obs_id', $assistance_obs_id);
        return $assistance_obs->update();
    }
    public function delete_assistance_obs($data)
    {
        $assistance_obs = $this->db->table('assistance_obs');
        $assistance_obs->where($data);
        return $assistance_obs->delete();
    }
}