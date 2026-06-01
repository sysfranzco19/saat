<?php

namespace App\Models;

use CodeIgniter\Model;

class PlaceModel extends Model
{
    public function get_places()
    {
        return $this->db->table('place')
            ->orderBy('place', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_place($place_id)
    {
        return $this->db->table('place')
            ->where('place_id', $place_id)
            ->get()
            ->getRowArray();
    }
}
