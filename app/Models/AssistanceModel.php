<?php

namespace App\Models;

use CodeIgniter\Model;

class AssistanceModel extends Model
{
    protected $DBGroup = 'asistencia';

    public function listar_assistance()
    {
        $assistance = $this->db->query("SELECT * FROM assistance");
        return $assistance->getResult();
    }
    public function get_assistance($data){
        $assistance = $this->db->table('assistance');
        $assistance->where($data);
        return $assistance->get()->getResultArray();
    }
    public function insert_assistance($datos)
    {
        $assistance = $this->db->table("assistance");
        $assistance->insert($datos);
        return $this->db->insertID();
    }
    public function update_assistance($datos, $assistance_id)
    {
        $assistance = $this->db->table('assistance');
        $assistance->set($datos);
        $assistance->where('assistance_id', $assistance_id);
        return $assistance->update();
    }
    public function delete_assistance($data)
    {
        $assistance = $this->db->table('assistance');
        $assistance->where($data);
        return $assistance->delete();
    }
    public function assistance_link($assistance, $type, $code){
        $sql = "SELECT d.* FROM assistance as d WHERE d.assistance='".$assistance."' AND d.type='".$type."' AND d.code='".$code."'";
        $assistance = $this->db->query($sql);
        return $assistance->getResult();
    }
    public function licencias_limit(){
        $sql = "SELECT t1.*, CONCAT(s.lastname, ' ', s.lastname2, ' ',s.name) AS nombre FROM assistance as t1
        INNER JOIN t_student as s ON(t1.student_id=s.student_id) WHERE 1 LIMIT 50";
        $section = $this->db->query($sql);
        return $section->getResultArray();
    }
    public function studentsAssis($section_id, $teacher_id, $fecha){
        $sql="";
        $licSubquery = "SELECT l.student_id FROM t_licencias l INNER JOIN t_licencias_dia ld ON ld.licencias_id = l.licencias_id WHERE ld.fecha_inicio<='".$fecha."' AND ld.fecha_fin>='".$fecha."'";
        if ($teacher_id==9) {
            $sql="SELECT s.student_id, CONCAT(s.lastname, ' ', s.lastname2,' ', s.name) as student, s.ddjj, s.retirement_date,
                IF(s.student_id In (".$licSubquery."),1,0) as estado
                FROM t_student s WHERE s.section_id=".$section_id." AND s.matricula>0 AND s.activo=1  AND sex='F' ORDER BY s.lastname, s.lastname2;";
        }else{
            if ($teacher_id==11) {
                $sql="SELECT s.student_id, CONCAT(s.lastname, ' ', s.lastname2,' ', s.name) as student, s.ddjj, s.retirement_date,
                IF(s.student_id In (".$licSubquery."),1,0) as estado
                FROM t_student s WHERE s.section_id=".$section_id." AND s.matricula>0 AND s.activo=1 AND sex='M' ORDER BY s.lastname, s.lastname2;";
            }else{
                $sql="SELECT s.student_id, CONCAT(s.lastname, ' ', s.lastname2,' ', s.name) as student, s.ddjj, s.retirement_date,
                IF(s.student_id In (".$licSubquery."),1,0) as estado
                FROM t_student s WHERE s.section_id=".$section_id." AND s.matricula>0 AND s.activo=1 ORDER BY s.lastname, s.lastname2;";
            }
        }
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }

}
