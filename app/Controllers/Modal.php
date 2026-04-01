<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class Modal extends BaseController
{
    public function index()
    {
        //
    }
    function popup($page_name = '' , $param1 = '' ,  $param2 = '' , $param3 = '' , $param4 = '' , $param5 = '' )
	{
        $session = session();
		$account_type		=	$session->get('login_type');
		$page_data['param1']=$param1;
		$page_data['param2']=$param2;
		$page_data['param3']=$param3;
		$page_data['param4']=$param4;
		$page_data['param5']=$param5;
        $Setting = new SettingModel();
        $page_data['system_title']  = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['phase_id']  = $Setting->get_phase_id();
        $page_data['phase_name']  = $Setting->get_phase_name();
		//$this->load->view($page_name, $page_data);
        //return view('backend/'.$account_type.'/'.$page_name, $page_data);
        //return 'backend/'.$account_type.'/'.$page_name;
        return view('backend/'.$account_type.'/'.$page_name, $page_data);
        //return "Hola".$account_type;
		//$this->load->view( 'backend.php' ,$page_data);
		//$this->load->view( 'backend/teacher/modal_student_profile.php' ,$page_data);
	}
    function popup_all($page_name = '' , $param1 = '' ,  $param2 = '' , $param3 = '' , $param4 = '' , $param5 = '' )
	{
        $session = session();
		$account_type		=	$session->get('login_type');
		$page_data['param1']=$param1;
		$page_data['param2']=$param2;
		$page_data['param3']=$param3;
		$page_data['param4']=$param4;
		$page_data['param5']=$param5;
        $Setting = new SettingModel();
        $page_data['system_title']  = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['phase_id']  = $Setting->get_phase_id();
        $page_data['phase_name']  = $Setting->get_phase_name();
		//$this->load->view($page_name, $page_data);
        //return view('backend/'.$account_type.'/'.$page_name, $page_data);
        //return 'backend/'.$account_type.'/'.$page_name;
        return view('backend/'.$page_name, $page_data);
        //return "Hola".$account_type;
		//$this->load->view( 'backend.php' ,$page_data);
		//$this->load->view( 'backend/teacher/modal_student_profile.php' ,$page_data);
	}
}
