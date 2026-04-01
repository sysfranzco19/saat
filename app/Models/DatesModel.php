<?php

namespace App\Models;

use CodeIgniter\Model;

class DatesModel extends Model
{
    protected $DBGroup = 'asistencia';

    public function listar_attendance_dates()
    {
        $attendance_dates = $this->db->query("SELECT * FROM attendance_dates");
        return $attendance_dates->getResult();
    }
    public function get_attendance_dates($data){
        $attendance_dates = $this->db->table('attendance_dates');
        $attendance_dates->where($data);
        return $attendance_dates->get()->getResultArray();
    }
    public function insert_attendance_dates($datos)
    {
        $attendance_dates = $this->db->table("attendance_dates");
        $attendance_dates->insert($datos);
        return $this->db->insertID();
    }
    public function update_attendance_dates($datos, $attendance_dates_id)
    {
        $attendance_dates = $this->db->table('attendance_dates');
        $attendance_dates->set($datos);
        $attendance_dates->where('attendance_dates_id', $attendance_dates_id);
        return $attendance_dates->update();
    }
    public function delete_attendance_dates($data)
    {
        $attendance_dates = $this->db->table('attendance_dates');
        $attendance_dates->where($data);
        return $attendance_dates->delete();
    }
    public function dias_subject($subject_id, $phase_id){
        $sql = "SELECT DISTINCT t1.date_id, t2.date_class, t1.subject_id FROM assistance_subject as t1 
        INNER JOIN attendance_dates as t2 ON(t1.date_id=t2.date_id) 
        WHERE t1.subject_id=".$subject_id." AND t2.phase_id=".$phase_id." ORDER BY t2.date_class ";
        $attendance_dates = $this->db->query($sql);
        return $attendance_dates->getResultArray();
    }

}