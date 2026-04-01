<?php

namespace App\Models;

use CodeIgniter\Model;

class CsamarksdetailsModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    public function listar_csamarks_details()
    {
        $csamarks_details = $this->db->query("SELECT * FROM csamarks_details");
        return $csamarks_details->getResult();
    }
    public function get_csamarks_details($data){
        $csamarks_details = $this->db->table('csamarks_details');
        $csamarks_details->where($data);
        return $csamarks_details->get()->getResultArray();
    }
    public function insert_csamarks_details($datos)
    {
        $csamarks_details = $this->db->table("csamarks_details");
        $csamarks_details->insert($datos);
        return $this->db->insertID();
    }
    public function update_csamarks_details($datos, $csamarks_details_id)
    {
        $csamarks_details = $this->db->table('csamarks_details');
        $csamarks_details->set($datos);
        $csamarks_details->where('csamarks_details_id', $csamarks_details_id);
        return $csamarks_details->update();
    }
    public function delete_csamarks_details($data)
    {
        $csamarks_details = $this->db->table('csamarks_details');
        $csamarks_details->where($data);
        return $csamarks_details->delete();
    }
    public function csamarks_details_subject($subject_id, $phase_id){
        $sql = 'SELECT t1.* FROM csamarks_details as t1 WHERE t1.phase_id='.$phase_id.' AND t1.subject_id = '.$subject_id;
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function csamarks_details_dim($subject_id, $phase_id,$dim){
        $sql = "SELECT t1.* FROM csamarks_details as t1 WHERE t1.phase_id=".$phase_id." AND t1.subject_id = ".$subject_id." AND t1.columna LIKE '".$dim."%'";
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
    public function csamarks_details_dim_curso($phase_id, $dim, $section_id){
        $sql = "SELECT t1.* FROM csamarks_details as t1 WHERE t1.phase_id=".$phase_id." AND t1.subject_id IN (SELECT subject_id FROM subject WHERE section_id=".$section_id.") AND t1.columna LIKE '".$dim."%'";
        $subject = $this->db->query($sql);
        return $subject->getResultArray();
    }
}