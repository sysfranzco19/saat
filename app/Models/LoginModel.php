<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    public function obtenerUsuario($data)
    {
        $Usuario = $this->db->table('admin');
        $Usuario->where($data);
        return $Usuario->get()->getResultArray();
    }
    public function obtenerAdmin($data)
    {
        $Usuario = $this->db->table('admin');
        $Usuario->where($data);
        return $Usuario->get()->getResultArray();
    }

    public function obtenerManager($data)
    {
        $Usuario = $this->db->table('manager');
        $Usuario->where($data);
        return $Usuario->get()->getResultArray();
    }

    public function obtenerParent($data)
    {
        $Usuario = $this->db->table('t_parent');
        $Usuario->where($data);
        return $Usuario->get()->getResultArray();
    }
    public function obtenerStudent($data)
    {
        $Usuario = $this->db->table('t_student');
        $Usuario->where($data);
        return $Usuario->get()->getResultArray();
    }
    public function obtenerSecretary($data)
    {
        $Usuario = $this->db->table('secretary');
        $Usuario->where($data);
        return $Usuario->get()->getResultArray();
    }
    public function getUserBy(string $column, string $value)
    {
        return $this->where($column, $value)->first();
    }
    public function updateParent($id, $data)
    {
        return $this->db->table('t_parent')->where('parent_id', $id)->update($data);
    }

    public function updateAdmin($id, $data)
    {
        return $this->db->table('admin')->where('admin_id', $id)->update($data);
    }
    public function updateStudent($id, $data)
    {
        return $this->db->table('t_student')->where('student_id', $id)->update($data);
    }

}
