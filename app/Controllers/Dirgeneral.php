<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\ServerModel;
use App\Models\StudentModel;
use App\Models\SectionModel;
use App\Models\FamilyModel;
use App\Models\ParentModel;
use App\Models\SubjectModel;
use App\Models\IinfractionModel;
use App\Models\IcriteriaModel;
use App\Models\ItypesfoulsModel;
use App\Models\DelayModel;
use App\Models\ContinuityModel;

class Dirgeneral extends BaseController
{
    /*
    public function index()
    {
        return view('welcome_message');
    }*/
    public function dg_continuity_results()
    {
        $session = session();
        $teacher_id  = $session->get('teacher_id');

        
        if ($teacher_id!=77)
            return redirect()->to(base_url());
        //Total students
        $StudentMod = new StudentModel();
        $page_data['students_prospective'] = $StudentMod->students_prospective();
        //Students continuity values
        $ContinuityMod = new ContinuityModel();
        $data = ["respuesta" => 'SI'];
        $page_data['students_si']  = $ContinuityMod->get_continuity($data);
        $data = ["respuesta" => 'NO'];
        $page_data['students_no']  = $ContinuityMod->get_continuity($data);
        $data = ["respuesta" => 'INDECISO'];
        $page_data['students_in']  = $ContinuityMod->get_continuity($data);
        //Students continuity
        $page_data['continuity_students_10']  = $ContinuityMod->continuity_students_10();
        //Settings
        $Setting = new SettingModel(); 
        $page_data['login_type']  = $session->get('login_type');
        $page_data['phase_id']  = $Setting->get_phase_id();
        $page_data['phase_name']  = $Setting->get_phase_name();
        $page_data['system_title']  = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['page_name']  = 'dg_continuity_results';
        $page_data['page_title'] = 'Continuidad 2024';
        return view('backend/index', $page_data);
    }
    
}
