<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function listar_teacher()
    {
        $teacher = $this->db->query("SELECT * FROM teacher");
        return $teacher->getResult();
    }
    public function get_teacher($data){
        $teacher = $this->db->table('teacher');
        $teacher->where($data);
        $teacher->orderBy('name', 'ASC');
        return $teacher->get()->getResultArray();
    }
    public function obtenerTeacher($data){
        $Usuario = $this->db->table('teacher');
        $Usuario->where($data);
        return $Usuario->get()->getResultArray();
    }
    public function insert_teacher($datos)
    {
        $teacher = $this->db->table("teacher");
        $teacher->insert($datos);
        return $this->db->insertID();
    }
    public function update_teacher($datos, $teacher_id)
    {
        $teacher = $this->db->table('teacher');
        $teacher->set($datos);
        $teacher->where('teacher_id', $teacher_id);
        return $teacher->update();
    }
    public function delete_teacher($data)
    {
        $teacher = $this->db->table('teacher');
        $teacher->where($data);
        return $teacher->delete();
    }
}