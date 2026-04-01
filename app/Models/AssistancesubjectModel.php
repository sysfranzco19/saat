<?php

namespace App\Models;

use CodeIgniter\Model;

class AssistancesubjectModel extends Model
{
    protected $DBGroup = 'asistencia';

    public function listar_assistance_subject()
    {
        $assistance_subject = $this->db->query("SELECT * FROM assistance_subject");
        return $assistance_subject->getResult();
    }
    public function get_assistance_subject($data){
        $assistance_subject = $this->db->table('assistance_subject');
        $assistance_subject->where($data);
        return $assistance_subject->get()->getResultArray();
    }
    public function insert_assistance_subject($datos)
    {
        $assistance_subject = $this->db->table("assistance_subject");
        $assistance_subject->insert($datos);
        return $this->db->insertID();
    }
    public function update_assistance_subject($datos, $assistance_subject_id)
    {
        $assistance_subject = $this->db->table('assistance_subject');
        $assistance_subject->set($datos);
        $assistance_subject->where('assistance_subject_id', $assistance_subject_id);
        return $assistance_subject->update();
    }
    public function update_assistance_date($datos, $subject_id, $date_id)
    {
        $assistance_subject = $this->db->table('assistance_subject');
        $assistance_subject->set($datos);
        $assistance_subject->where('subject_id', $subject_id);
        $array = array('subject_id' => $subject_id, 'date_id' => $date_id);
        $assistance_subject->where($array);
        return $assistance_subject->update();
    }
    public function delete_assistance_subject($data)
    {
        $assistance_subject = $this->db->table('assistance_subject');
        $assistance_subject->where($data);
        return $assistance_subject->delete();
    }
    public function assis_subject($subject_id, $phase_id){
        $sql = "SELECT assistance_subject_id, status, student_id, date_id FROM assistance_subject WHERE subject_id=".$subject_id." AND date_id IN(SELECT t1.date_id FROM attendance_dates as t1 WHERE t1.phase_id=".$phase_id." ORDER BY t1.date_class DESC)";
        $assistance_subject = $this->db->query($sql);
        return $assistance_subject->getResultArray();
    }
    public function assis_dates($subject_id, $phase_id){
        $sql = "SELECT DISTINCT t1.date_id, t2.date_class FROM assistance_subject as t1 
                INNER JOIN attendance_dates as t2 ON(t1.date_id=t2.date_id) 
                WHERE t1.subject_id=".$subject_id." AND t2.phase_id=".$phase_id." ORDER BY t2.date_class";
        $assistance_subject = $this->db->query($sql);
        return $assistance_subject->getResultArray();
    }
    public function assis_student($student_id, $phase_id){
        $sql = 'SELECT t2.assistance_subject_id, t3.subject_id, t3.name as subject, t5.name as teacher, t4.date_class, t2.status
                FROM assistance_subject as t2 INNER JOIN subject as t3 ON(t2.subject_id=t3.subject_id)
                INNER JOIN attendance_dates as t4 ON(t2.date_id=t4.date_id)                                    
                INNER JOIN teacher as t5 ON(t3.teacher_id=t5.teacher_id) 
                WHERE t2.student_id='.$student_id.' AND t4.phase_id='.$phase_id.' ORDER BY t4.date_class DESC';
        $assistance_subject = $this->db->query($sql);
        return $assistance_subject->getResultArray();
    }
    public function assis_licencia($student_id, $fecha_ini, $fecha_fin){
        $sql = "SELECT a.assistance_subject_id, d.date_class, s.name as materia, t.name as docente, t.email FROM assistance_subject a
        INNER JOIN attendance_dates d ON(a.date_id=d.date_id)
        INNER JOIN subject s ON(a.subject_id=s.subject_id) 
        INNER JOIN teacher t ON(s.teacher_id=t.teacher_id)
        WHERE a.status=0 AND student_id=".$student_id." AND d.date_class>='".$fecha_ini."' AND d.date_class<='".$fecha_fin."'";
        $assis_licencia = $this->db->query($sql);
        return $assis_licencia->getResultArray();
    }

    public function atte_student_range($student_id, $fecha_ini, $fecha_fin){
        $sql = "SELECT a.assistance_subject_id, d.date_class, a.status, s.name as materia, t.name as docente FROM assistance_subject a 
        INNER JOIN attendance_dates d ON(a.date_id=d.date_id) 
        INNER JOIN subject s ON(a.subject_id=s.subject_id) 
        INNER JOIN teacher t ON(s.teacher_id=t.teacher_id)
        WHERE d.date_class>='".$fecha_ini."' AND d.date_class<='".$fecha_fin."' AND a.student_id=".$student_id.";";
        $assis_licencia = $this->db->query($sql);
        return $assis_licencia->getResultArray();
    }

    public function assis_previous($section_id, $date_id){
        $sql = "SELECT a.student_id, a.status, a.periodos FROM assistance_subject a 
        WHERE a.subject_id IN(SELECT m.subject_id FROM subject m WHERE m.section_id=".$section_id.") AND a.date_id=".$date_id."  AND a.periodos=(SELECT MAX(s.periodos) FROM assistance_subject s WHERE s.date_id=".$date_id." )";
        $assistance_subject = $this->db->query($sql);
        return $assistance_subject->getResultArray();
    }
    

    // Pone Ausente(0) en todos los registros del rango de fechas (licencia por dias)
    public function noauth_licencia_dia($student_id, $fecha_inicio, $fecha_fin)
    {
        $sql = "UPDATE assistance_subject SET status=0 WHERE student_id=".(int)$student_id." AND date_id IN (SELECT date_id FROM attendance_dates WHERE date_class >= '".$fecha_inicio."'  AND date_class <= '".$fecha_fin."')";
        return $this->db->query($sql);
    }

    // Pone Ausente(0) en el periodo especifico de una fecha (licencia por horas)
    public function noauth_licencia_periodo($student_id, $fecha, $periodo_id)
    {
        $sql = "UPDATE assistance_subject SET status=0 WHERE student_id=".(int)$student_id." AND periodos=".(int)$periodo_id." AND date_id IN (SELECT date_id FROM attendance_dates WHERE date_class='".$fecha."')";
        return $this->db->query($sql);
    }

}
