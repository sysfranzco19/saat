<?php

namespace App\Models;

use CodeIgniter\Model;

class CsamarksModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function listar_csamarks()
    {
        $csamarks = $this->db->query("SELECT * FROM csamarks");
        return $csamarks->getResult();
    }
    public function get_csamarks($data){
        $csamarks = $this->db->table('csamarks');
        $csamarks->where($data);
        return $csamarks->get()->getResultArray();
    }
    public function insert_csamarks($datos)
    {
        $csamarks = $this->db->table("csamarks");
        $csamarks->insert($datos);
        return $this->db->insertID();
    }
    public function update_csamarks($datos, $csamarks_id)
    {
        $csamarks = $this->db->table('csamarks');
        $csamarks->set($datos);
        $csamarks->where('csamarks_id', $csamarks_id);
        return $csamarks->update();
    }
    public function delete_csamarks($data)
    {
        $csamarks = $this->db->table('csamarks');
        $csamarks->where($data);
        return $csamarks->delete();
    }
    public function csamarks_subject($subject_id, $phase_id){
        $sql = 'SELECT t1.*, CONCAT(lastname, " ", lastname2," ", name) as student FROM csamarks as t1 
        INNER JOIN t_student as t2 ON(t1.student_id=t2.student_id) 
        WHERE t1.phase_id='.$phase_id.' AND t1.subject_id = '.$subject_id;
        $subject = $this->db->query($sql);
        //return $student->get()->getResultArray();
        //return $subject->getResult();
        return $subject->getResultArray();
    }
    public function csamarks_student($student_id, $phase_id){
        $sql = 'SELECT t1.*, CONCAT(lastname, " ", lastname2," ", name) as student FROM csamarks as t1 
        INNER JOIN t_student as t2 ON(t1.student_id=t2.student_id) 
        WHERE t1.phase_id='.$phase_id.' AND t1.student_id = '.$student_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function csamarks_half_student($student_id, $phase_id){
        $sql = 'SELECT s.name as materia, t.name as docente, ((c.saber_average)) as saber, ((c.hacer_average)) as hacer FROM csamarks as c 
        INNER JOIN subject as s ON(c.subject_id = s.subject_id) 
        INNER JOIN teacher as t ON(s.teacher_id = t.teacher_id)
        WHERE c.phase_id='.$phase_id.' AND c.student_id = '.$student_id;
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    public function csamarks_centralizer($student_id, $phase_id){
        $sql = "SELECT c.student_id, s.name, c.total_average as obtained_mark 
        FROM csamarks as c INNER JOIN subject as s ON (c.subject_id=s.subject_id) WHERE c.student_id=".$student_id." AND c.phase_id=".$phase_id;
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    public function csamarks_ed_fisica($student_id, $phase_id){
        $sql = "SELECT t1.phase_id, t1.total_average FROM csamarks as t1 INNER JOIN subject as t2 ON (t1.subject_id=t2.subject_id) 
        WHERE t1.student_id=".$student_id." AND t1.phase_id=".$phase_id." AND 
        t2.teacher_id=(SELECT IF(sex='M',11,9) FROM t_student as t2 WHERE t2.student_id=".$student_id.")";
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    public function csamarks_cantralize_bth($student_id, $phase_id){
        $sql ="SELECT c.student_id, a.ser5, a.dec5, SUM(ROUND(c.total_average*(s.hours/100)+0.0000000001)) as nota_bth 
        FROM csamarks c INNER JOIN subject s ON(c.subject_id=s.subject_id) 
        INNER JOIN self_appraisal a ON(c.student_id=a.student_id)
        WHERE s.hours>1 AND a.phase_id=".$phase_id." AND c.phase_id=".$phase_id." AND c.student_id=".$student_id." AND s.name LIKE 'BTH%' GROUP BY c.student_id, a.ser5, a.dec5";
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    public function csamarks_student_bth($student_id, $subject_id, $phase_id){
        $sql = "SELECT * FROM csamarks WHERE phase_id=".$phase_id." AND student_id=".$student_id." AND subject_id=".$subject_id;
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    public function csamarks_section($section_id){
        $sql = "SELECT c.* FROM csamarks c INNER JOIN subject m ON(c.subject_id=m.subject_id) WHERE m.section_id=".$section_id;
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    
    public function csamarks_ranking($student_id, $phase_id){
        $sql = 'SELECT csamarks.student_id, subject.name, csamarks.total_average as obtained_mark 
                FROM csamarks INNER JOIN subject ON (csamarks.subject_id=subject.subject_id ) 
                WHERE student_id='.$student_id.' and phase_id='.$phase_id;
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }

    public function csamarks_subject_update($subject_id, $phase_id)
    {
        $query = "UPDATE csamarks SET total_average=ser_average+saber_average+hacer_average+autoevaluacion WHERE phase_id=".$phase_id." AND subject_id=".$subject_id;
        $csamarks = $this->db->query($sql);
    }
    public function csamarks_avgs($section_id, $phase_id){
        $sql ="SELECT e.student_id, AVG(n.total_average) as promedio FROM csamarks as n 
        INNER JOIN t_student e ON n.student_id=e.student_id 
        INNER JOIN section c ON e.section_id=c.section_id 
        WHERE n.phase_id=".$phase_id." AND e.section_id=".$section_id."
        GROUP BY e.student_id;";
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    public function csamarks_notes($section_id, $phase_id){
        $sql ="SELECT n.csamarks_id, n.subject_id, n.student_id, n.ser_average, n.saber_average, n.hacer_average, n.autoevaluacion, n.total_average
        FROM csamarks n INNER JOIN t_student e ON n.student_id=e.student_id
        WHERE n.phase_id=".$phase_id." AND n.total_average is not NULL AND e.section_id=".$section_id.";";
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    public function csamarks_avg_grade($class_id, $phase_id){
        $sql = "SELECT n.student_id, s.nick_name, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student, AVG(n.total_average) as promedio 
        FROM csamarks n INNER JOIN t_student e ON(n.student_id=e.student_id) 
        INNER JOIN section s ON(e.section_id=s.section_id)
        WHERE n.phase_id=".$phase_id." AND s.class_id=".$class_id." GROUP BY n.student_id, student, s.nick_name  
        ORDER BY promedio;";
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    public function csamarks_student_pa($student_id){
        $sql = "SELECT 
                    n.student_id,
                    s.name,
                    SUM(CASE WHEN n.phase_id = 1 THEN n.total_average ELSE 0 END) AS T1,
                    SUM(CASE WHEN n.phase_id = 2 THEN n.total_average ELSE 0 END) AS T2,
                    SUM(CASE WHEN n.phase_id = 3 THEN n.total_average ELSE 0 END) AS T3,
                    (SUM(CASE WHEN n.phase_id = 1 THEN n.total_average ELSE 0 END)+
                    SUM(CASE WHEN n.phase_id = 2 THEN n.total_average ELSE 0 END)+
                    SUM(CASE WHEN n.phase_id = 3 THEN n.total_average ELSE 0 END))/3 AS PA
                FROM 
                    csamarks n
                INNER JOIN
                    subject s ON n.subject_id=s.subject_id
                WHERE
                    n.student_id=".$student_id."
                GROUP BY 
                    n.student_id, s.name ORDER BY T1, T2, T3;";
        $csamarks = $this->db->query($sql);
        return $csamarks->getResultArray();
    }
    public function saber_hacer_section($section_id, $phase_id){
        $sql = "SELECT CONCAT(e.lastname, ' ', e.lastname2, ' ', e.name) AS student, AVG(c.saber_average) as saber, AVG(c.hacer_average) as hacer
        FROM 
            csamarks c
        INNER JOIN 
            t_student e ON c.student_id = e.student_id
        INNER JOIN 
            subject s ON c.subject_id = s.subject_id
        INNER JOIN 
            teacher t ON s.teacher_id = t.teacher_id
        WHERE 
            s.section_id=".$section_id." AND c.phase_id=".$phase_id."
        GROUP BY 
            CONCAT(e.lastname, ' ', e.lastname2, ' ', e.name);";
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
}