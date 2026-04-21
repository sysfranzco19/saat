<?php
namespace App\Models;
use CodeIgniter\Model;
class SelfappraisalModel extends Model
{
    protected $DBGroup      = 'tiquipaya';
    protected $table        = 'self_appraisal';
    protected $primaryKey   = 'self_id';
    protected $allowedFields = [
        'auto1','auto2','auto3','auto4','auto5',
        'auto6','auto7','auto8','auto9','auto10',
        'autoevaluacion','descripcion','student_id','phase_id'
    ];
    public function listar_self_appraisal()
    {
        $self_appraisal = $this->db->query("SELECT * FROM self_appraisal");
        return $self_appraisal->getResult();
    }
    public function get_self_appraisal($data)
    {
        $self_appraisal = $this->db->table('self_appraisal');
        $self_appraisal->where($data);
        return $self_appraisal->get()->getResultArray();
    }
    public function insert_self_appraisal($datos)
    {
        $self_appraisal = $this->db->table('self_appraisal');
        $self_appraisal->insert($datos);
        return $this->db->insertID();
    }
    public function update_self_appraisal($datos, $self_appraisal_id)
    {
        $self_appraisal = $this->db->table('self_appraisal');
        $self_appraisal->set($datos);
        $self_appraisal->where('self_id', $self_appraisal_id);
        return $self_appraisal->update();
    }
    public function delete_self_appraisal($data)
    {
        $self_appraisal = $this->db->table('self_appraisal');
        $self_appraisal->where($data);
        return $self_appraisal->delete();
    }
    public function self_section($section_id, $phase_id)
    {
        $sql = "SELECT ad.*, CONCAT(s.lastname, ' ', s.lastname2, ' ', s.name) AS nombre
        FROM self_appraisal AS ad
        INNER JOIN t_student AS s ON (ad.student_id = s.student_id)
        INNER JOIN section AS c ON (s.section_id = c.section_id)
        WHERE ad.phase_id = " . $phase_id . " AND s.section_id = " . $section_id;
        return $this->db->query($sql)->getResultArray();
    }
    public function self_director($director_id, $phase_id)
    {
        $sql = 'SELECT t1.student_id,
                CONCAT(t1.lastname, " ", t1.lastname2, " ", t1.name) AS student,
                t1.retirement_date,
                t2.completo,
                t2.section_id,
                CASE WHEN sa.self_id IS NOT NULL THEN 1 ELSE 0 END AS tiene_auto,
                sa.autoevaluacion
        FROM t_student AS t1
        INNER JOIN section AS t2 ON (t1.section_id = t2.section_id)
        LEFT JOIN self_appraisal AS sa ON (sa.student_id = t1.student_id AND sa.phase_id = ' . $phase_id . ')
        WHERE t1.activo = 1 AND t1.matricula <> 0
        AND t2.director_id = ' . $director_id . '
        ORDER BY t2.section_id, t1.lastname, t1.lastname2, t1.name';
        return $this->db->query($sql)->getResultArray();
    }
}
