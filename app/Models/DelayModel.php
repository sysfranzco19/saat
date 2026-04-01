<?php



namespace App\Models;



use CodeIgniter\Model;



class DelayModel extends Model

{

    protected $DBGroup = 'asistencia';
    
    public function listar_delay()

    {

        $delay = $this->db->query("SELECT * FROM delay");

        return $delay->getResult();

    }

    public function get_delay($data){

        $delay = $this->db->table('delay');

        $delay->where($data);

        return $delay->get()->getResultArray();

    }

    public function insert_delay($datos)

    {

        $delay = $this->db->table("delay");

        $delay->insert($datos);

        return $this->db->insertID();

    }

    public function update_delay($datos, $delay_id)

    {

        $delay = $this->db->table('delay');

        $delay->set($datos);

        $delay->where('delay_id', $delay_id);

        return $delay->update();

    }

    public function delete_delay($data)

    {

        $delay = $this->db->table('delay');

        $delay->where($data);

        return $delay->delete();

    }

    public function delay_student($student_id, $phase_id){

        if ($phase_id==0) {

            $sql = "SELECT d.*, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student, s.completo, 

            ROUND(TIME_TO_SEC(TIMEDIFF(hora_llegada, hora_ingreso)) / 60) AS tarde_con, a.date_class 

            FROM delay as d INNER JOIN t_student e ON(d.student_id=e.student_id) 

            INNER JOIN attendance_dates as a ON(d.date_id=a.date_id) 

            INNER JOIN section s ON(e.section_id=s.section_id) WHERE d.student_id=".$student_id;

        }else{

            $sql = "SELECT d.*, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student, s.completo, 

            ROUND(TIME_TO_SEC(TIMEDIFF(hora_llegada, hora_ingreso)) / 60) AS tarde_con, a.date_class 

            FROM delay as d INNER JOIN t_student e ON(d.student_id=e.student_id) 

            INNER JOIN attendance_dates as a ON(d.date_id=a.date_id) 

            INNER JOIN section s ON(e.section_id=s.section_id) WHERE a.phase_id=".$phase_id." AND d.student_id=".$student_id;

        }

         

        $delay = $this->db->query($sql);

        return $delay->getResultArray();

    }

    public function delays_date($date_id){

        $sql = "SELECT d.*, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student, s.completo, 

        ROUND(TIME_TO_SEC(TIMEDIFF(hora_llegada, hora_ingreso)) / 60) AS tarde_con, a.date_class 

        FROM delay as d INNER JOIN t_student e ON(d.student_id=e.student_id) 

        INNER JOIN attendance_dates as a ON(d.date_id=a.date_id) 

        INNER JOIN section s ON(e.section_id=s.section_id) WHERE d.date_id=".$date_id." ORDER BY d.hora_llegada DESC";

        $delay = $this->db->query($sql);

        return $delay->getResultArray();

    }

    public function get_delay_student($student_id){

        $sql = "SELECT d.*, 

        ROUND(TIME_TO_SEC(TIMEDIFF(hora_llegada, hora_ingreso)) / 60) AS tarde_con, a.date_class 

        FROM delay as d 

        INNER JOIN attendance_dates as a ON(d.date_id=a.date_id) WHERE d.student_id=".intval($student_id);



        $delay = $this->db->query($sql);

        return $delay->getResultArray();

    }

    public function getDelay($delay_id){

        $sql = "SELECT d.*, CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student, s.completo, a.date_class FROM delay d 

            INNER JOIN t_student e ON(d.student_id=e.student_id) 

            INNER JOIN attendance_dates as a ON(d.date_id=a.date_id) 

            INNER JOIN section s ON(e.section_id=s.section_id) WHERE d.delay_id=".$delay_id;

        $delay = $this->db->query($sql);

        return $delay->getResultArray();

    }
   


}