<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    function get_settings($type) {
        $Setting = $this->db->table('settings');
        $Setting->where($type);
        return $Setting->get()->getResultArray();
    }
    function get_system_name(){
        $Setting = $this->db->table('settings');
        $Setting->where(['type' => 'system_name']);
        $res = $Setting->get()->getResultArray();
        foreach ($res as $row)
            return $row['description'];
    }
    function get_system_title(){
        $Setting = $this->db->table('settings');
        $Setting->where(['type' => 'system_title']);
        $res = $Setting->get()->getResultArray();
        foreach ($res as $row)
            return $row['description'];
    }
    function get_phase_id(){
        $Setting = $this->db->table('phase');
        $Setting->where(['activo' => 1]);
        $res = $Setting->get()->getResultArray();
        foreach ($res as $row)
            return $row['phase_id'];
    }
    function get_phase_name(){
        $Setting = $this->db->table('phase');
        $Setting->where(['activo' => 1]);
        $res = $Setting->get()->getResultArray();
        foreach ($res as $row)
            return $row['name'];
    }
    function get_phase(){
        $Setting = $this->db->table('phase');
        $Setting->where(['activo' => 1]);
        $res = $Setting->get()->getResultArray();
        foreach ($res as $row)
            return $row['abreviado'];
    }
    function get_self_appraisal(){
        $Setting = $this->db->table('settings');
        $Setting->where(['type' => 'self_appraisal']);
        $res = $Setting->get()->getResultArray();
        foreach ($res as $row)
            return $row['description'];
    }
    function get_entry_time(){
        $Setting = $this->db->table('settings');
        $Setting->where(['type' => 'entry_time']);
        $res = $Setting->get()->getResultArray();
        foreach ($res as $row)
            return $row['description'];
    }
    function get_gestion(){
        $Setting = $this->db->table('settings');
        $Setting->where(['type' => 'gestion']);
        $res = $Setting->get()->getResultArray();
        foreach ($res as $row)
            return $row['description'];
    }
}