<?php

namespace App\Models;

use CodeIgniter\Model;

class ManagerModel extends Model
{
    protected $table = 'manager';
    protected $primaryKey = 'manager_id';
    protected $allowedFields = [
        'name',
        'birthday',
        'religion',
        'address',
        'reference',
        'phone',
        'cellphone',
        'personal_email',
        'email',
        'password',
        'level',
        'employee_id',
        'section_ini',
        'section_fin'
    ];

    /**
     * Get all managers
     */
    public function listar_manager()
    {
        return $this->findAll();
    }

    /**
     * Get manager by data
     */
    public function get_manager($data)
    {
        return $this->where($data)->findAll();
    }

    /**
     * Insert new manager
     */
    public function insert_manager($datos)
    {
        return $this->insert($datos);
    }

    /**
     * Update manager
     */
    public function update_manager($datos, $manager_id)
    {
        return $this->update($manager_id, $datos);
    }

    /**
     * Delete manager
     */
    public function delete_manager($manager_id)
    {
        return $this->delete($manager_id);
    }
}
