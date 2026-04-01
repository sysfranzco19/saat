<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $DBGroup = 'tiquipaya';
    public function listar_document()
    {
        $document = $this->db->query("SELECT * FROM document");
        return $document->getResult();
    }
    public function get_document($data){
        $document = $this->db->table('document');
        $document->where($data);
        return $document->get()->getResultArray();
    }
    public function insert_document($datos)
    {
        $document = $this->db->table("document");
        $document->insert($datos);
        return $this->db->insertID();
    }
    public function update_document($datos, $document_id)
    {
        $document = $this->db->table('document');
        $document->set($datos);
        $document->where('document_id', $document_id);
        return $document->update();
    }
    public function delete_document($data)
    {
        $document = $this->db->table('document');
        $document->where($data);
        return $document->delete();
    }
    public function document_link($document, $type, $code){
        $sql = "SELECT d.* FROM document as d WHERE d.document='".$document."' AND d.type='".$type."' AND d.code='".$code."'"; 
        $document = $this->db->query($sql);
        //return $student->get()->getResultArray();
        return $document->getResult();
    }

}