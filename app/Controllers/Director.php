<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;

use Google\Client;
use Google\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\SettingModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\SectionModel;
use App\Models\DocumentModel;
use App\Models\CrudModel;
use App\Models\AssistanceModel;
use App\Models\DatesModel;
use App\Models\AssistancesubjectModel;
use App\Models\ApigoogleModel;
use App\Models\AdaptationsModel;
use App\Models\BehaviorsModel;
use App\Models\EmailModel;
use App\Models\FamilyModel;
use App\Models\CsamarksModel;
use App\Models\CsamarksdetailsModel;
use App\Models\TeacherModel;
use App\Models\SelfappraisalModel;
use App\Models\MoraModel;
use App\Models\LicenciaModel;
use App\Models\AbsenceModel;
use App\Models\IinfractionModel;
use App\Models\IcriteriaModel;
use App\Models\ItypesfoulsModel;
use App\Models\ParentModel;
use App\Models\DelayModel;

class Director extends BaseController
{
    public function index()
    {
        return;
    }

    function sections_statistics()
    {
        $session = session();
        $teacher_id  = $session->get('teacher_id');
        if ($session->get('adviser'))
            return redirect()->to(base_url());

        //Section
        $data = [ "director_id" => $teacher_id ];
        $Section = new SectionModel();
        $cursos = $Section->get_section($data);
        $page_data['sections'] = $cursos ;
        //Settings
        $Setting = new SettingModel(); 
        $page_data['login_type']  = $session->get('login_type');
        $page_data['phase_id']  = $Setting->get_phase_id();
        $page_data['phase_name']  = $Setting->get_phase_name();
        $page_data['system_title']  = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['page_name']  = 'sections_statistics';
        $page_data['page_title'] = 'Estadisticas por Curso';
        return view('backend/index', $page_data);
    }
    function low_averages($section_id='')
    {
        $session = session();
        $teacher_id  = $session->get('teacher_id');
        if ($session->get('adviser'))
            return redirect()->to(base_url());
        //Actualizamos Planilla Promedios Bajos
        $Document = new DocumentModel();
        $low_averages = $Document->document_link("low_averages", "director", $section_id);

        $ApigoogleMod = new ApigoogleModel();
        $apigoogle = $ApigoogleMod->low_update($low_averages[0]->link);
        //echo $low_averages;
        $url = "https://docs.google.com/spreadsheets/d/".$low_averages[0]->link;
        header("Location: $url");
        exit();

    }

}
