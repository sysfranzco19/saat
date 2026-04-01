<?php

namespace App\Models;

use CodeIgniter\Model;

class PhaseModel extends Model
{
    function get_phases($type) {
        $Setting = $this->db->table('phase');
        $Setting->where($type);
        return $Setting->get()->getResultArray();
    }
}