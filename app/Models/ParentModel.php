<?php



namespace App\Models;



use CodeIgniter\Model;



class ParentModel extends Model
{

    public function listar_parent()
    {

        $parent = $this->db->query("SELECT * FROM t_parent");

        return $parent->getResult();

    }

    public function get_parent($data)
    {

        $parent = $this->db->table('t_parent');

        $parent->where($data);

        return $parent->get()->getResultArray();

    }

    public function insert_parent($datos)
    {

        $parent = $this->db->table("t_parent");

        $parent->insert($datos);

        return $this->db->insertID();

    }

    public function update_parent($datos, $parent_id)
    {

        $parent = $this->db->table('t_parent');

        $parent->set($datos);

        $parent->where('parent_id', $parent_id);

        return $parent->update();

    }

    public function delete_parent($data)
    {

        $parent = $this->db->table('t_parent');

        $parent->where($data);

        return $parent->delete();

    }



    public function get_padre_info($family_id)
    {
        $sql2 = 'SELECT p.parent_id, p.card, l.shortened, p.lastname1, p.lastname2, p.name, p.idiom, p.occupation, p.degree_instruction, birthday 
        FROM t_parent as p INNER JOIN place as l ON(p.place_card=l.place_id) WHERE p.relationship_id=1 AND p.family_id=' . $family_id;
        $padre = $this->db->query($sql2);
        return $padre->getResult();
    }

    public function get_madre_info($family_id)
    {

        //$sql2 = 'SELECT parent_id, name, lastname1, lastname2, card, place_card, profession, occupation, business, workphone, cellphone, idiom, degree_instruction FROM parent WHERE family_id='.$family_id.' AND relationship_id=1';

        //$padre = $this->db->query($sql2)->result_array();



        $sql3 = 'SELECT p.parent_id, p.card, l.shortened, p.lastname1, p.lastname2, p.name, p.idiom, p.occupation, p.degree_instruction, birthday

        FROM t_parent as p INNER JOIN place as l ON(p.place_card=l.place_id) WHERE p.relationship_id=2 AND p.family_id=' . $family_id;

        $madre = $this->db->query($sql3);

        return $madre->getResult();

    }

    public function get_parent_relationship($family_id, $relationship_id)
    {

        $sql = 'SELECT p.* FROM t_parent as p WHERE p.relationship_id=' . $relationship_id . ' AND p.family_id=' . $family_id;

        $parent = $this->db->query($sql);

        return $parent->getResult();

    }

    public function get_parent_info($family_id)
    {
        $sql = "SELECT p.parent_id, CONCAT(p.lastname1, ' ', p.lastname2, ' ', p.name) as nombre, r.relationship, p.profession, p.cellphone, p.email, p.relationship_id FROM t_parent as p 
        INNER JOIN  relationship r ON(p.relationship_id=r.relationship_id) WHERE p.family_id=" . $family_id;
        $parent = $this->db->query($sql);
        return $parent->getResultArray();
    }

}