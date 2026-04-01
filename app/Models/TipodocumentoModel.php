<?php

namespace App\Models;

use CodeIgniter\Model;

class TipodocumentoModel extends Model
{
    public function listar_tipo_documento()
    {
        $tipo_documento = $this->db->query("SELECT * FROM t_tipo_documento");
        return $tipo_documento->getResult();
    }
    public function get_tipo_documento($data){
        $tipo_documento = $this->db->table('t_tipo_documento');
        $tipo_documento->where($data);
        return $tipo_documento->get()->getResultArray();
    }
    public function insert_tipo_documento($datos)
    {
        $tipo_documento = $this->db->table("t_tipo_documento");
        $tipo_documento->insert($datos);
        return $this->db->insertID();
    }
    public function update_tipo_documento($datos, $tipo_documento_id)
    {
        $tipo_documento = $this->db->table('t_tipo_documento');
        $tipo_documento->set($datos);
        $tipo_documento->where('tipo_documento_id', $tipo_documento_id);
        return $tipo_documento->update();
    }
    public function delete_tipo_documento($data)
    {
        $tipo_documento = $this->db->table('t_tipo_documento');
        $tipo_documento->where($data);
        return $tipo_documento->delete();
    }
}