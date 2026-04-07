<?php
namespace App\Models;
use CodeIgniter\Model;
class StudentModel extends Model
{
    protected $table = 't_student';
    protected $primaryKey = 'student_id';
    public function insertStudent($datos)
    {
        $Student = $this->db->table("t_student");
        $Student->insert($datos);
        return $this->db->affectedRows();
    }
    public function updateStudent($student_id, $data)
    {
        $builder = $this->db->table('t_student');
        $builder->where('student_id', $student_id);
        $builder->update($data);
        return $this->db->affectedRows();
    }
    public function get_student($data)
    {
        $student = $this->db->table('t_student');
        $student->where($data);
        return $student->get()->getResultArray();
    }
    public function activesStudent()
    {
        $Student = $this->db->query("SELECT student_id, CONCAT(lastname,' ', lastname2, ' ', name) as nombre FROM t_student WHERE activo=1 ORDER BY lastname, lastname2, name");
        return $Student->getResult();
    }
    public function datosStudent($student_id)
    {
        $sql = "SELECT s.student_id, CONCAT(s.lastname,' ', s.lastname2, ' ', s.name) as nombre, s.email, c.completo, s.family_id, s.section_id , s.sex, f.email1, f.email2 
                FROM t_student s INNER JOIN section c ON(s.section_id=c.section_id) 
                INNER JOIN t_family f ON(s.family_id=f.family_id)
                WHERE s.activo=1 AND s.student_id=" . $student_id;
        $Student = $this->db->query($sql);
        //return $Student->get()->getResultArray();
        return $Student->getResult();
    }
    public function studentFamily($family_id)
    {
        $student = $this->db->query("SELECT * FROM t_student WHERE family_id=" . $family_id . " ORDER BY lastname, lastname2, name");
        //return $student->get()->getResultArray();
        return $student->getResult();
    }
    public function studentFamilyGet($family_id)
    {
        $sql = "SELECT s.student_id, CONCAT(s.lastname,' ', s.lastname2, ' ', s.name) as student FROM t_student s 
                WHERE s.family_id=" . $family_id . " ORDER BY s.lastname, s.lastname2, s.name";
        $student = $this->db->query($sql);
        return $student->getResult();
    }
    public function truncateStudent()
    {
        $Student = $this->db->table('t_student');
        $Student->truncate();
        return $Student->get()->getResultArray();
    }
    public function listarTablas()
    {
        $Medios = $this->db->query("SELECT * FROM t_importar");
        return $Medios->getResult();
    }
    public function studentsSection($section_id, $teacher_id)
    {
        //$sql = 'SELECT * FROM section WHERE section_id in (SELECT section_id FROM subject WHERE teacher_id='.$teacher_id.') ORDER BY section_id';
        $sql = "";
        if ($section_id > 400) {
            $sql = 'SELECT student_id, CONCAT(lastname, " ", lastname2," ", name) as student, ddjj, retirement_date FROM t_student WHERE nit_id=' . $section_id . ' AND matricula>0 AND activo=1 ORDER BY lastname, lastname2';
        } else {
            if ($teacher_id == 9) {
                $sql = 'SELECT student_id, CONCAT(lastname, " ", lastname2," ", name) as student, ddjj, retirement_date FROM t_student WHERE section_id=' . $section_id . ' and sex="F" AND matricula>0 AND activo=1 ORDER BY lastname, lastname2';
            } elseif ($teacher_id == 11) {
                $sql = 'SELECT student_id, CONCAT(lastname, " ", lastname2," ", name) as student, ddjj, retirement_date FROM t_student WHERE section_id=' . $section_id . ' and sex="M" AND matricula>0 AND activo=1 ORDER BY lastname, lastname2';
            } else {
                $sql = 'SELECT student_id, CONCAT(lastname, " ", lastname2," ", name) as student, ddjj, retirement_date FROM t_student WHERE section_id=' . $section_id . ' AND matricula>0 AND activo=1 ORDER BY lastname, lastname2';
            }
        }
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function students_family($family_id)
    {
        $sql = "SELECT t1.*, CONCAT(t1.lastname,' ', t1.lastname2, ' ', t1.name) as student, t2.completo FROM t_student as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) 
        WHERE t1.family_id=" . $family_id . " ORDER BY t1.section_id";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function students_user($user, $sel, $id)
    {
        $sql = "";
        switch ($user) {
            case 'adviser':
                $sql = "SELECT t1.student_id, t1.lastname, t1.lastname2, t1.name, t2.completo, t2.nick_name, t1.section_id 
                FROM t_student as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) WHERE t1.activo=1 AND t2.teacher_id=" . $id . " AND t1.lastname LIKE '%" . $sel . "%' ORDER BY t1.lastname";
                break;
            case 'manager':
                $sql = "SELECT t1.student_id, t1.lastname, t1.lastname2, t1.name, t2.completo, t2.nick_name, t1.section_id 
                FROM t_student as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) WHERE t1.activo=1 AND t1.lastname LIKE '%" . $sel . "%' ORDER BY t1.lastname";
                break;
            case 'director':
                $sql = "SELECT t1.student_id, t1.lastname, t1.lastname2, t1.name, t2.completo, t2.nick_name, t1.section_id 
                FROM t_student as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) WHERE t1.activo=1 AND t2.director_id=" . $id . " AND t1.lastname LIKE '%" . $sel . "%' ORDER BY t1.lastname";
                $student = $this->db->query($sql);
                if (count($student->getResultArray()) == 0) {
                    $sql = "SELECT t1.student_id, t1.lastname, t1.lastname2, t1.name, t2.completo, t2.nick_name, t1.section_id 
                    FROM t_student as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) WHERE t1.activo=1 AND t1.lastname LIKE '%" . $sel . "%' ORDER BY t1.lastname";
                }
                break;
            case 'secretary':
                $sql = "SELECT t1.student_id, t1.lastname, t1.lastname2, t1.name, t2.completo, t2.nick_name, t1.section_id 
                FROM t_student as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) WHERE t1.activo=1 AND t1.lastname LIKE '%" . $sel . "%' ORDER BY t1.lastname";
                break;
            default:
                # code...
                break;
        }
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function students_family_user($sel)
    {
        $sql = "SELECT t1.student_id, t1.family_id, t1.lastname, t1.lastname2, t1.name, t2.completo, t2.nick_name, t1.section_id 
        FROM t_student as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) WHERE  t1.family_id IN(SELECT f.family_id FROM t_family f WHERE f.lastname1 LIKE '%" . $sel . "%' ORDER BY f.lastname1) 
         ORDER BY t1.family_id";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function student_secretary($secretary_id)
    {
        $sql = "";
        if ($secretary_id > 3) {
            $sql = "SELECT s.*, c.nick_name FROM t_student as s INNER JOIN section as c ON(s.section_id=c.section_id) 
            WHERE s.activo=1 AND s.section_id>=271 ORDER BY c.nick_name, s.lastname, s.lastname2, s.name";
        } else {
            $sql = "SELECT s.*, c.nick_name FROM t_student as s INNER JOIN section as c ON(s.section_id=c.section_id) 
            WHERE s.activo=1 AND c.secretary_id=" . $secretary_id . " ORDER BY c.nick_name, s.lastname, s.lastname2, s.name";
        }
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function student_manager($manager_id)
    {
        $sql = "SELECT s.student_id, s.lastname, s.lastname2, s.name, c.nick_name FROM t_student as s INNER JOIN section as c ON(s.section_id=c.section_id) 
                WHERE s.activo=1 AND s.matricula<>0 ORDER BY s.lastname, s.lastname2, s.name";
        //return $student->get()->getResultArray();
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function student_active($section_id)
    {
        $sql = "SELECT e.student_id,e.lastname,e.lastname2,e.name from t_student as e WHERE e.activo=1 AND e.matricula<>0 AND e.section_id=" . $section_id . " ORDER BY e.lastname, e.lastname2, e.name";
        //return $student->get()->getResultArray();
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function student_class($class_id)
    {
        $sql = "SELECT e.student_id,e.lastname,e.lastname2,e.name, s.nick_name FROM t_student as e 
        INNER JOIN section s ON e.section_id=s.section_id 
        WHERE e.activo=1 AND e.matricula<>0 AND e.section_id AND s.class_id=" . $class_id . " ORDER BY e.lastname, e.lastname2, e.name";
        //return $student->get()->getResultArray();
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function students_prospective()
    {
        $sql = "SELECT e.student_id,e.lastname,e.lastname2,e.name from t_student as e WHERE e.activo=1 AND e.ddjj=0 AND e.section_id<341";
        //return $student->get()->getResultArray();
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    //ACTUALIZAR MATRÍCULA
    //protected $table      = 't_student';
    //protected $primaryKey = 'id';
    //protected $useAutoIncrement = true;
    //protected $allowedFields = ['matricula', 'nombre', 'otras_columnas']; // Asegúrate de incluir todas las columnas necesarias
    public function getapiMatricula($studentId)
    {
        $sql = "UPDATE t_student SET matricula= (SELECT mat FROM (SELECT max(matricula) + 1 as mat FROM t_student) as x) WHERE student_id=" . $studentId;
        //return $student->get()->getResultArray();
        $student = $this->db->query($sql);
        return $student;
    }
    public function updateTStudent()
    {
        // Conectar a la primera base de datos Y LEEMOS t_student
        $db1 = $this->db;
        $query = $db1->table('t_student')->get()->getResultArray();
        // Copiar y reemplazar la tabla t_student a TIQUIPAYA
        $db2 = \Config\Database::connect('tiquipaya');
        $db2->table('t_student')->truncate();
        foreach ($query as $row) {
            $db2->table('t_student')->insert($row);
        }
        // Copiar y reemplazar la tabla t_student a ASISTENCIA
        $db3 = \Config\Database::connect('asistencia');
        $db3->table('t_student')->truncate();
        foreach ($query as $row) {
            $db3->table('t_student')->insert($row);
        }
    }
    public function students_by_sections($section_ids, $sel)
    {
        if (empty($section_ids)) {
            return [];
        }
        $ids = implode(',', $section_ids);
        $sql = "SELECT DISTINCT t1.student_id, t1.lastname, t1.lastname2, t1.name, t2.completo, t2.nick_name, t1.section_id 
                FROM t_student as t1 
                INNER JOIN section as t2 ON(t1.section_id=t2.section_id) 
                WHERE t1.activo=1 AND t1.section_id IN ($ids) AND t1.lastname LIKE '%" . $sel . "%' ORDER BY t1.lastname";
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
    public function getStudentsFamily($family_id)
    {
        $sql = "SELECT e.student_id, 
            CONCAT(e.lastname,' ', e.lastname2, ' ', e.name) as student,
            DATE_FORMAT(e.birthday, '%d/%m/%Y') AS birthday, e.card, e.rude, p.shortened as emision, s.grade 
            FROM t_student as e 
            LEFT JOIN place as p ON(p.place_id=e.place_card) 
            LEFT JOIN section as s ON(e.section_id=s.section_id) 
            WHERE e.activo=1 AND e.family_id=" . $family_id . " ORDER BY e.lastname, e.lastname2, e.name";
        //return $student->get()->getResultArray();
        $student = $this->db->query($sql);
        return $student->getResultArray();
    }
}
