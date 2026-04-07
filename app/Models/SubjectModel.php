<?php
namespace App\Models;
use CodeIgniter\Model;
class SubjectModel extends Model
{
    protected $DBGroup = 'tiquipaya';
    protected $table = 'subject';
    protected $primaryKey = 'subject_id';
    public function listar_subject()
    {
        $subject = $this->db->query("SELECT * FROM subject");
        return $subject->getResult();
    }
    public function get_subject($data)
    {
        $subject = $this->db->table('subject');
        $subject->where($data);
        return $subject->get()->getResultArray();
    }
    public function insert_subject($datos)
    {
        $subject = $this->db->table("subject");
        $subject->insert($datos);
        return $this->db->insertID();
    }
    public function update_subject($datos, $subject_id)
    {
        $subject = $this->db->table('subject');
        $subject->set($datos);
        $subject->where('subject_id', $subject_id);
        return $subject->update();
    }
    public function delete_subject($data)
    {
        $subject = $this->db->table('subject');
        $subject->where($data);
        return $subject->delete();
    }
    public function subjects_docente($teacher_id)
    {
        $sql = "SELECT t1.subject_id, t2.completo as curso, t1.name as materia, t1.section_id, t3.link FROM subject AS t1
INNER JOIN section AS t2 ON(t1.section_id=t2.section_id)
LEFT JOIN document as t3 ON(t1.section_id=t3.code) WHERE t1.teacher_id=" . $teacher_id;
        $subject = $this->db->query($sql);
        //return $student->get()->getResultArray();
        return $subject->getResult();
    }
    public function subjects_teacher($teacher_id)
    {
        $sql = 'SELECT t1.subject_id,t2.teacher_id,t1.name as materia,t2.name as profe, t3.completo, t3.section_id, t1.sheet_id,
t4.link
    FROM subject as t1 INNER JOIN teacher as t2 ON(t1.teacher_id=t2.teacher_id)
    INNER JOIN section as t3 ON(t1.section_id=t3.section_id)
    LEFT JOIN document as t4 ON(t1.section_id=t4.code)
    WHERE t2.teacher_id=' . $teacher_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function subjects_student($section_id, $sex)
    {
        $sql = "";
        if ($section_id > 230) {
            if ($sex == 'M') {
                $sql = "SELECT s.*, t.name as profe FROM subject as s
INNER JOIN teacher as t ON(s.teacher_id=t.teacher_id)
WHERE s.teacher_id<>9 AND s.section_id=" . $section_id;
            } else {
                $sql = "SELECT s.*, t.name as profe FROM subject as s
    INNER JOIN teacher as t ON(s.teacher_id=t.teacher_id)
    WHERE s.teacher_id<>11 AND s.section_id=" . $section_id;
            }
        } else {
            $sql = "SELECT s.*, t.name as profe FROM subject as s
        INNER JOIN teacher as t ON(s.teacher_id=t.teacher_id)
        WHERE s.section_id=" . $section_id;
        }
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function subject_section($subject_id)
    {
        $sql = 'SELECT t1.*, t2.section_id, t2.nick_name, t3.email as emailDocente, t4.folder, t2.completo, t3.name as
        docente FROM subject as t1
        INNER JOIN section as t2 ON(t1.section_id=t2.section_id)
        INNER JOIN teacher as t3 ON(t1.teacher_id=t3.teacher_id)
        INNER JOIN director as t4 ON(t2.director_id=t4.teacher_id) WHERE t1.subject_id=' . $subject_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function subject_docente_name($subject_id)
    {
        $sql = 'SELECT t1.subject_id, t1.name as materia, t2.section_id, t2.nick_name, t3.name as docente, t3.email as
        emailDocente FROM subject as t1
        INNER JOIN section as t2 ON(t1.section_id=t2.section_id)
        INNER JOIN teacher as t3 ON(t1.teacher_id=t3.teacher_id) WHERE t1.subject_id=' . $subject_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function subject_prim12($teacher_id)
    {
        $sql = 'SELECT t1.*, t2.section_id, t2.nick_name, (SELECT COUNT(b.subject_id) FROM behaviors as b WHERE
        b.subject_id=t1.subject_id) as cantidad
        FROM subject as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) WHERE t2.section_id<231 AND
            t1.teacher_id=' . $teacher_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function subject_prim36($teacher_id)
    {
        $sql = ' SELECT t1.*, t2.section_id, t2.nick_name, (SELECT COUNT(b.subject_id) FROM behaviors as b WHERE
            b.subject_id=t1.subject_id) as cantidad FROM subject as t1 INNER JOIN section as t2
            ON(t1.section_id=t2.section_id) WHERE t2.section_id>=231 AND t2.section_id<271 AND t1.teacher_id=' . $teacher_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function subject_sec13($teacher_id)
    {
        $sql = ' SELECT t1.*, t2.section_id, t2.nick_name, (SELECT COUNT(b.subject_id) FROM behaviors as b WHERE
                b.subject_id=t1.subject_id) as cantidad FROM subject as t1 INNER JOIN section as t2
                ON(t1.section_id=t2.section_id) WHERE t2.section_id>=271 AND t2.section_id<321 AND t1.teacher_id=' . $teacher_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function subject_sec46($teacher_id)
    {
        $sql = ' SELECT t1.*, t2.section_id, t2.nick_name, (SELECT COUNT(b.subject_id) FROM behaviors as b WHERE
                    b.subject_id=t1.subject_id) as cantidad FROM subject as t1 INNER JOIN section as t2
                    ON(t1.section_id=t2.section_id) WHERE t2.section_id>=321 AND t1.teacher_id=' . $teacher_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function subjects_section($section_id)
    {
        $sql = 'SELECT t1.*, t2.section_id, t2.nick_name, t3.email as emailDocente, t4.folder, t2.completo,
                    t3.name as docente FROM subject as t1
                    INNER JOIN section as t2 ON(t1.section_id=t2.section_id)
                    INNER JOIN teacher as t3 ON(t1.teacher_id=t3.teacher_id)
                    INNER JOIN director as t4 ON(t2.director_id=t4.teacher_id) WHERE t2.section_id=' . $section_id;
        $subject = $this->db->query($sql);
        //return $student->get()->getResultArray();
        //return $subject->getResult();
        return $subject->getResultArray();
    }
    public function materia_curso($section_id)
    {
        $sql = 'SELECT t1.*, t2.section_id, t2.nick_name, t3.email as emailDocente, t4.folder, t2.completo,
                    t3.name as docente FROM subject as t1
                    INNER JOIN section as t2 ON(t1.section_id=t2.section_id)
                    INNER JOIN teacher as t3 ON(t1.teacher_id=t3.teacher_id)
                    INNER JOIN director as t4 ON(t2.director_id=t4.teacher_id) WHERE t2.section_id=' . $section_id;
        $subject = $this->db->query($sql);
        //return $student->get()->getResultArray();
        //return $subject->getResult();
        //return $subject->getResultArray();
        return $subject->getResult();
    }
    public function subject_errors($subject_id, $phase_id)
    {
        $sql = "SELECT c.*, s.* FROM csamarks c INNER JOIN t_student s ON(c.student_id=s.student_id)
                    WHERE s.activo=1 AND c.phase_id=" . $phase_id . " AND c.subject_id=" . $subject_id . " AND
                    (c.ser_average=0 OR c.saber_average=0 OR c.hacer_average=0 OR
                    c.autoevaluacion=0 OR c.total_average IS NULL)";
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function subjects_section_bth($section_id)
    {
        $sql = "SELECT s.subject_id, s.name as materia, t.name as docente, s.hours FROM subject s
                    INNER JOIN teacher t ON(s.teacher_id=t.teacher_id) WHERE s.name LIKE 'BTH%' AND
                    s.section_id=" . $section_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    function dir_notes_teacher($director_id)
    {
        $sql = "SELECT t.teacher_id, t.name, COUNT(m.subject_id) as cantidad FROM subject m
                    INNER JOIN section c ON(m.section_id=c.section_id)
                    RIGHT JOIN teacher t ON(m.teacher_id=t.teacher_id)
                    WHERE c.director_id=" . $director_id . " AND m.locked=0 AND c.section_id>=211
                    GROUP BY t.teacher_id, t.name;";
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    function dir_notes_subject($director_id)
    {
        $sql = "SELECT t.teacher_id, t.name as docente, m.name FROM subject m
                    INNER JOIN section c ON(m.section_id=c.section_id)
                    INNER JOIN teacher t ON(m.teacher_id=t.teacher_id)
                    WHERE c.director_id=" . $director_id . " AND m.locked=0;";
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
}
