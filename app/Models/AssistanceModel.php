<?php
namespace App\Models;
use CodeIgniter\Model;
class AssistanceModel extends Model
{
    protected $DBGroup      = 'asistencia';
    protected $table        = 'assistance';
    protected $primaryKey   = 'assistance_id';
    protected $allowedFields = [
        'student_id', 'date', 'status', 'observation',
        'arrival_time', 'registered_by', 'created_at',
    ];
    // status: 0=Absent, 1=Present, 2=License, 3=Late
    public function get_assistance($data)
    {
        return $this->db->table('assistance')->where($data)->get()->getResultArray();
    }
    public function insert_assistance($datos)
    {
        $this->db->table('assistance')->insert($datos);
        return $this->db->insertID();
    }
    public function update_assistance($datos, $assistance_id)
    {
        $builder = $this->db->table('assistance');
        $builder->set($datos);
        $builder->where('assistance_id', $assistance_id);
        return $builder->update();
    }
    public function upsert_assistance($student_id, $date, $datos)
    {
        $existing = $this->db->table('assistance')
            ->where('student_id', $student_id)
            ->where('date', $date)
            ->get()->getRowArray();
        if ($existing) {
            $this->db->table('assistance')
                ->where('assistance_id', $existing['assistance_id'])
                ->update($datos);
            return $existing['assistance_id'];
        }
        $datos['student_id'] = $student_id;
        $datos['date']       = $date;
        $this->db->table('assistance')->insert($datos);
        return $this->db->insertID();
    }
    public function delete_assistance($data)
    {
        return $this->db->table('assistance')->where($data)->delete();
    }
    public function listar_assistance($section_id = null, $fecha = null)
    {
        $builder = $this->db->table('assistance a')
            ->select('a.*, CONCAT(s.lastname, " ", s.lastname2, " ", s.name) AS student, sec.nick_name')
            ->join('t_student s', 's.student_id = a.student_id')
            ->join('section sec', 'sec.section_id = s.section_id');
        if ($section_id) $builder->where('s.section_id', $section_id);
        if ($fecha)      $builder->where('a.date', $fecha);
        return $builder->get()->getResultArray();
    }
    public function listar_por_fechas($section_id, $fecha_inicio, $fecha_fin)
    {
        return $this->db->table('assistance a')
            ->select('a.*, CONCAT(s.lastname, " ", s.lastname2, " ", s.name) AS student')
            ->join('t_student s', 's.student_id = a.student_id')
            ->where('s.section_id', $section_id)
            ->where('a.date >=', $fecha_inicio)
            ->where('a.date <=', $fecha_fin)
            ->orderBy('a.date', 'ASC')
            ->get()->getResultArray();
    }
    public function get_assistance_by_date($date, array $student_ids)
    {
        return $this->db->table('assistance')
            ->select('student_id, status, observation, arrival_time')
            ->where('date', $date)
            ->whereIn('student_id', $student_ids)
            ->get()->getResultArray();
    }
    public function studentsAssis($section_id, $teacher_id, $fecha)
    {
        $licSubquery = "SELECT a.student_id FROM assistance a WHERE a.date='" . $fecha . "' AND a.status=2";
        $base = "SELECT s.student_id, CONCAT(s.lastname, ' ', s.lastname2,' ', s.name) as student,
                 s.ddjj, s.retirement_date,
                 IF(s.student_id IN (" . $licSubquery . "),1,0) as estado
                 FROM t_student s
                 WHERE s.section_id=" . $section_id . " AND s.matricula>0 AND s.activo=1";
        if ($teacher_id == 9) {
            $sql = $base . " AND s.sex='F' ORDER BY s.lastname, s.lastname2";
        } elseif ($teacher_id == 11) {
            $sql = $base . " AND s.sex='M' ORDER BY s.lastname, s.lastname2";
        } else {
            $sql = $base . " ORDER BY s.lastname, s.lastname2";
        }
        return $this->db->query($sql)->getResultArray();
    }
}