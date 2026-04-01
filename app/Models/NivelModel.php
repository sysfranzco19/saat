<?php

namespace App\Models;

use CodeIgniter\Model;

class NivelModel extends Model
{
    protected $DBGroup = 'asistencia';
    protected $table = 'nivel';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nivel',
        'abreviado',
        'inicio',
        'fin',
        'director_id'
    ];

    /**
     * Get all levels
     */
    public function listar_niveles()
    {
        return $this->findAll();
    }

    /**
     * Get level by data
     */
    public function get_nivel($data)
    {
        return $this->where($data)->findAll();
    }

    /**
     * Insert new level
     */
    public function insert_nivel($datos)
    {
        return $this->insert($datos);
    }

    /**
     * Update level
     */
    public function update_nivel($datos, $id)
    {
        return $this->update($id, $datos);
    }

    /**
     * Delete level
     */
    public function delete_nivel($id)
    {
        return $this->delete($id);
    }
}
