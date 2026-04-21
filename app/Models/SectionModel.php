<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $DBGroup = 'tiquipaya';
    protected $table = 'section';
    protected $primaryKey = 'section_id';

    public function listar_section()
    {
        $section = $this->db->query("SELECT * FROM section");
        return $section->getResult();
    }
    public function get_section($data)
    {
        $section = $this->db->table('section');
        $section->where($data);
        return $section->get()->getResultArray();
    }
    public function insert_section($datos)
    {
        $section = $this->db->table("section");
        $section->insert($datos);
        return $this->db->insertID();
    }
    public function update_section($datos, $section_id)
    {
        $section = $this->db->table('section');
        $section->set($datos);
        $section->where('section_id', $section_id);
        return $section->update();
    }
    public function delete_section($data)
    {
        $section = $this->db->table('section');
        $section->where($data);
        return $section->delete();
    }
    public function section_docente($teacher_id)
    {
        $sql = 'SELECT * FROM section WHERE section_id in (SELECT section_id FROM subject WHERE teacher_id=' . $teacher_id . ') ORDER BY section_id';
        $section = $this->db->query($sql);
        return $section->getResultArray();
    }
    public function section_secretary($secretary_id)
    {
        $sql = '';
        if ($secretary_id > 3) {
            $sql = 'SELECT t1.section_id, t1.completo, t2.teacher_id, t2.name, t1.nick_name FROM section as t1 
            INNER JOIN teacher as t2 ON(t1.teacher_id=t2.teacher_id) 
            WHERE t1.active=1 ORDER BY t1.section_id';
        } else {
            $sql = 'SELECT t1.section_id, t1.completo, t2.teacher_id, t2.name, t1.nick_name FROM section as t1 
            INNER JOIN teacher as t2 ON(t1.teacher_id=t2.teacher_id) 
            WHERE t1.secretary_id=' . $secretary_id . ' ORDER BY t1.section_id';
        }
        $section = $this->db->query($sql);
        return $section->getResultArray();
    }
    public function section_bth($phase_id)
    {
        $sql = "SELECT s.section_id, s.completo, m.subject_id, m.locked FROM section s INNER JOIN subject m ON(m.section_id=s.section_id) 
        WHERE s.active=1 AND s.section_id>270 AND m.name='TEC. TECNOLÓGICA' AND m.teacher_id=61 ORDER BY s.section_id";
        $section = $this->db->query($sql);
        return $section->getResultArray();
    }
    public function section_emails($section_id)
    {
        $sql = "SELECT s.section_id, t.email as emailDocente, d.email as emailDirector FROM section s 
        INNER JOIN teacher t ON(s.teacher_id=t.teacher_id) 
        INNER JOIN manager d ON(s.director_id=d.manager_id) 
        WHERE s.section_id=" . $section_id;
        $section = $this->db->query($sql);
        return $section->getResultArray();
    }
    public function sections_all_by_grade()
    {
        $sql = "SELECT class_id, grade FROM section WHERE active=1 GROUP BY class_id, grade ORDER BY MIN(section_id)";
        return $this->db->query($sql)->getResultArray();
    }
    public function sections_dir($director_id)
    {
        $sql = "SELECT class_id, grade FROM section WHERE active=1 AND director_id=" . $director_id . " GROUP BY class_id, grade";
        $section = $this->db->query($sql);
        return $section->getResultArray();
    }
    public function sections_range($section_ini, $section_fin)
    {
        $sql = "SELECT * FROM section WHERE active=1 AND section_id>=" . $section_ini . " AND section_id<=" . $section_fin . " ORDER BY section_id";
        $section = $this->db->query($sql);
        return $section->getResultArray();
    }
}