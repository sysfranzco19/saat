<?php

namespace App\Models;

use CodeIgniter\Model;

class CrudModel extends Model
{
    ////////IMAGE URL//////////
    function get_image_url($type = '', $id = '') {
        $image_url = base_url() . 'uploads/user.jpg';

        if (file_exists('uploads/' . $type . '_image/' . $id . '.jpg')){
            $image_url = base_url() . 'uploads/' . $type . '_image/' . $id . '.jpg';
        }
        else if($type == "student"){
            $this->db->select(array('roll', 'code'));
            $query = $this->db->get_where('student', array(
                "student_id"=>$id)
            );
            $student_data = $query->result_array();
            $student_code = '';
            if(count($student_data)>0){
                foreach ($student_data as $sd) {
                    $student_code = $sd['code'];
                    $student_roll = $sd['roll'];
                    break;
                }
            }
            if($student_code != ''){
                $img_code = 'uploads/student_image/codes/' . $student_code . '.jpg';
                $img_roll = 'uploads/student_image/codes/' . $student_code . '_' . $student_roll . '.jpg';
                if (file_exists($img_code))
                    $image_url = base_url() . $img_code;
                else if (file_exists($img_roll)) //casos especiales gemelos, trillizos, o que tienen mismo code
                    $image_url = base_url() . $img_roll;
            }
        }

        return $image_url;
    }
}