<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\FamilyModel;
use App\Models\StudentModel;
use App\Models\DocumentModel;
use App\Models\BehaviorsModel;
use App\Models\MoraModel;
use App\Models\SubjectModel;
use App\Models\CsamarksModel;
use App\Models\CsamarksdetailsModel;
use App\Models\IinfractionModel;
use App\Models\LicenciaModel;
use App\Models\ContinuityModel;
use App\Models\AssistancesubjectModel;
use App\Models\DatesModel;
use App\Models\DelayModel;
use App\Models\AbsenceModel;
use App\Models\ScoreModel;
use App\Models\BehaviorModel;

class Parents extends BaseController
{
    public function dashboard()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $page_data['family_id'] = $session->get('family_id');
        $page_data['login_type'] = $session->get('login_type');
        //DatosFamilia
        $family = new FamilyModel();
        $data = ["family_id" => $family_id];
        $datos2 = $family->get_family($data);
        $page_data['familia'] = $datos2[0];

        $Setting = new SettingModel();
        $page_data['status'] = $datos2[0]["status"];

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Dashboard";
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = "dashboard";
        return view('backend/index', $page_data);
    }
    public function family_data()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //DatosFamilia
        $family = new FamilyModel();
        $data = ["family_id" => $family_id];
        $datos2 = $family->get_family($data);
        $page_data['fam'] = $datos2[0];

        $Setting = new SettingModel();
        $page_data['family_id'] = $family_id;
        $page_data['status'] = $datos2[0]["status"];
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Datos Familia";
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = "family_data";
        return view('backend/index', $page_data);
    }
    /****CARTA DE CONTENIDOS****/
    function content_letter()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;
        //CARTAS DE CONTENIDOS
        $document = array();
        foreach ($students as $stu):
            //Documento
            $Document = new DocumentModel();
            $letter = $Document->document_link("content_letter", "section", $stu['section_id']);
            if (count($letter) > 0) {
                $document['letter' . $stu['section_id']] = $letter[0]->link;
            } else {
                //Materias
                $Subject = new SubjectModel();
                $subjects = $Subject->subjects_student($stu['section_id'], $stu['sex']);
                $document['materias' . $stu['section_id']] = $subjects;
            }
        endforeach;
        $page_data['document'] = $document;
        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'content_letter';
        $page_data['page_title'] = 'Cartas de Contenidos';
        return view('backend/index', $page_data);
    }
    /***************************HORARIOS DE ENTREVISTAS *****************/
    function interview_schedule()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;

        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'interview_schedule';
        $page_data['page_title'] = 'Horario de Entrevistas';
        return view('backend/index', $page_data);
    }
    /****REPORTE CONDUCTUAL****/
    function behaviors()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Reportes Conductuales
        $BehaviorsMod = new BehaviorsModel();
        $behaviors = $BehaviorsMod->behaviors_family($page_data['phase_id'], $family_id);
        $page_data['behaviors'] = $behaviors;
        $page_data['family_id'] = $family_id;

        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'behaviors';
        $page_data['page_title'] = 'Reportes conductuales';
        return view('backend/index', $page_data);
    }
    function behaviors_child($student_id = '')
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        //Reportes Conductuales
        $BehaviorsMod = new BehaviorsModel();
        $respuesta = $BehaviorsMod->update_behaviors_student($student_id);
        $students = $BehaviorsMod->behaviors_student($page_data['phase_id'], $student_id);
        $page_data['behaviors'] = $students;
        //Actualizamos Behaviors

        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'behaviors_child';
        $page_data['page_title'] = 'Reportes conductuales';
        return view('backend/index', $page_data);
    }
    /****MIS HIJOS****/
    function enrolled_children()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Configuraciones
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;

        //VISTA
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'enrolled_children';
        $page_data['page_title'] = 'Mis Hijos';
        return view('backend/index', $page_data);
    }

    /*****************************BOLETIN DE NOTAS*********************************/
    function report_card($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Configuraciones
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student_id'] = $student_id;
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;


        //MORA
        $data_mora = ["mora_id" => $student_id];
        $mo = new MoraModel();
        $mora = $mo->get_mora($data_mora);
        if (count($mora) == 1) {
            //BLOQUEAMOS PAGINA A MOROSOS
            $page_data['account_type'] = 'parents';
            $page_data['page_name'] = 'error_6';
            $page_data['page_title'] = 'Reporte de Evaluaciones';
            return view('backend/index', $page_data);
        } else {
            //Enviamos todas las Materias
            $Subject = new SubjectModel();
            $subjects = $Subject->subjects_student($students[0]->section_id, $students[0]->sex);
            $page_data['subjects'] = $subjects;
            //Detalles
            $CsamarksdetailsMod = new CsamarksdetailsModel();
            $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim_curso($page_data['phase_id'], "saber", $students[0]->section_id);
            $page_data['details_saber'] = $csamarksdetails;
            $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim_curso($page_data['phase_id'], "hacer", $students[0]->section_id);
            $page_data['details_hacer'] = $csamarksdetails;
            //Enviamos las Notas
            $CsamarksMod = new CsamarksModel();
            $csamarks = $CsamarksMod->csamarks_student($student_id, $page_data['phase_id']);
            $page_data['csamarks'] = $csamarks;

            //VISTA REPOR CARD
            $page_data['page_name'] = 'report_card';
            $page_data['page_title'] = 'Reporte de Evaluaciones';
            return view('backend/index', $page_data);
        }
    }
    function report_half($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Configuraciones
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student_id'] = $student_id;
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;


        //MORA
        $data_mora = ["mora_id" => $student_id];
        $mo = new MoraModel();
        $mora = $mo->get_mora($data_mora);
        if (count($mora) == 1) {
            //BLOQUEAMOS PAGINA A MOROSOS
            $page_data['account_type'] = 'parents';
            $page_data['page_name'] = 'error_6';
            $page_data['page_title'] = 'Reporte de Evaluaciones';
            return view('backend/index', $page_data);
        } else {
            //Enviamos todas las Materias
            $Subject = new SubjectModel();
            $subjects = $Subject->subjects_student($students[0]->section_id, $students[0]->sex);
            $page_data['subjects'] = $subjects;
            //Detalles
            $CsamarksdetailsMod = new CsamarksdetailsModel();
            $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim_curso($page_data['phase_id'], "saber", $students[0]->section_id);
            $page_data['details_saber'] = $csamarksdetails;
            $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim_curso($page_data['phase_id'], "hacer", $students[0]->section_id);
            $page_data['details_hacer'] = $csamarksdetails;
            //Enviamos las Notas
            $CsamarksMod = new CsamarksModel();
            $csamarks = $CsamarksMod->csamarks_student($student_id, $page_data['phase_id']);
            $page_data['csamarks'] = $csamarks;

            //VISTA REPOR CARD
            $page_data['page_name'] = 'report_half';
            $page_data['page_title'] = 'Reporte de Evaluaciones';
            return view('backend/index', $page_data);
        }
    }
    /***************************CONTACTOS DE ADM*****************/
    function contacts()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;

        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'contacts';
        $page_data['page_title'] = 'Contactos';
        return view('backend/index', $page_data);
    }

    public function gamified_behavior($student_id = '')
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents') {
            return redirect()->to(base_url());
        }

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;

        // Si no seleccionó un hijo, y tiene hijos, seleccionamos el primero por defecto
        if (empty($student_id) && count($students) > 0) {
            $student_id = $students[0]['student_id'];
        }

        $page_data['student_id'] = $student_id;
        $current_student = null;
        foreach ($students as $s) {
            if ($s['student_id'] == $student_id) {
                $current_student = $s;
                break;
            }
        }

        if (!$current_student) {
            return redirect()->to(base_url() . 'parents/dashboard');
        }

        $page_data['student_name'] = $current_student['student'];
        $page_data['curso'] = $current_student['completo'];

        // Modelos
        $SubjectMod = new SubjectModel();
        $BehaviorMod = new BehaviorModel();
        $ScoreMod = new ScoreModel();

        // Materias
        $subjects = $SubjectMod->subjects_student($current_student['section_id'], $current_student['sex']);

        $subjectStats = [];
        $globalPositive = 0;
        $globalNegative = 0;
        $allLogs = [];

        $rawBehaviors = $BehaviorMod->getBehaviors();
        $page_data['behaviors'] = $rawBehaviors;

        $recentDate = \Config\Database::connect('asistencia')->table('attendance_dates')
            ->where('phase_id', $page_data['phase_id'])
            ->orderBy('date_class', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
        $currentDateId = $recentDate ? $recentDate['date_id'] : null;

        foreach ($subjects as $sub) {
            $subjId = $sub['subject_id'];

            $logs = $BehaviorMod->getStudentLog($student_id, null, $subjId, $page_data['phase_id']);

            $pos = count(array_filter($logs, function ($l) {
                return $l['type'] == 'positive';
            }));
            $neg = count(array_filter($logs, function ($l) {
                return $l['type'] == 'negative';
            }));

            $globalPositive += $pos;
            $globalNegative += $neg;

            foreach ($logs as &$log) {
                $log['subject_name'] = $sub['name'];
                $log['teacher_name'] = $sub['profe'];
            }
            $allLogs = array_merge($allLogs, $logs);

            $puntosDelSer = 10;
            if ($currentDateId) {
                $cumulativeScore = $ScoreMod->getDailyScore($student_id, $currentDateId, $subjId);
                $scaled = ($cumulativeScore / 100) * 10;
                $puntosDelSer = round($scaled, 1);
                if ($puntosDelSer > 10)
                    $puntosDelSer = 10;
                if ($puntosDelSer < 0)
                    $puntosDelSer = 0;
            }

            $subjectStats[] = [
                'subject_id' => $subjId,
                'name' => $sub['name'],
                'teacher' => $sub['profe'],
                'positive_count' => $pos,
                'negative_count' => $neg,
                'ser_score' => $puntosDelSer
            ];
        }

        usort($allLogs, function ($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });

        $page_data['subject_stats'] = $subjectStats;
        $page_data['global_positive'] = $globalPositive;
        $page_data['global_negative'] = $globalNegative;
        $page_data['timeline_logs'] = $allLogs;
        $page_data['student_id'] = $student_id;
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'gamified_behavior';
        $page_data['page_title'] = 'Historial de Comportamiento';
        return view('backend/index', $page_data);
    }
    /***************************METODOS DE PAGO*****************/
    function payment_methods()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;

        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'payment_methods';
        $page_data['page_title'] = 'Metodos de Pago';
        return view('backend/index', $page_data);
    }
    /***************************ENTREVISTAS*****************/
    function interviews()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'interviews';
        $page_data['page_title'] = 'Horario Entrevistas';
        return view('backend/index', $page_data);
    }
    /****Biblioteca Virtual****/
    function virtual_library_prim()
    {
        $session = session();
        //Curso
        //$data = ["section_id" => $session->get('section_id')];
        //$SectionMod = new SectionModel();
        //$respuesta = $SectionMod->get_section($data);
        //$page_data['curso'] = $respuesta[0]['completo'];
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'virtual_library_prim';
        $page_data['page_title'] = 'Biblioteca Virtual Primaria';
        return view('backend/index', $page_data);
    }
    function virtual_library_sec()
    {
        $session = session();
        //Curso
        //$data = ["section_id" => $session->get('section_id')];
        //$SectionMod = new SectionModel();
        //$respuesta = $SectionMod->get_section($data);
        //$page_data['curso'] = $respuesta[0]['completo'];
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'virtual_library_sec';
        $page_data['page_title'] = 'Biblioteca Virtual Secundaria';
        return view('backend/index', $page_data);
    }

    /****Protocolo de CLASES Virtual****/
    function class_protocol()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'class_protocol';
        $page_data['page_title'] = 'Protocolo de Clases';
        return view('backend/index', $page_data);
    }
    /****protocolo_De_BIOSEGURIDAD****/
    function biosafety_protocol()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'biosafety_protocol';
        $page_data['page_title'] = 'Protocolo de Bioseguridad';
        return view('backend/index', $page_data);
    }

    function achievement_diffusion()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'achievement_diffusion';
        $page_data['page_title'] = 'Difusión de Logro Estudiantil';
        return view('backend/index', $page_data);
    }
    
    function profile()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'error_5';
        $page_data['page_title'] = 'Pagina en Contruccion';
        return view('backend/index', $page_data);
    }
    function infractions()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());



        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;

        $IinfractionMod = new IinfractionModel();
        $page_data['infractions'] = $IinfractionMod->infraction_family($family_id);

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'infractions';
        $page_data['page_title'] = 'Planilla de Indisciplina';
        return view('backend/index', $page_data);
    }
    function report_licenses($student_id)
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;

        //Cursos
        $LicenciaMod = new LicenciaModel();
        $licencias = $LicenciaMod->licenciasStudent($student_id);
        $page_data['licencias'] = $licencias;

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'report_licenses';
        $page_data['page_title'] = 'Reporte de Licencias';
        return view('backend/index', $page_data);
    }
    function licenses()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;
        // Obtener las licencias de cada estudiante
        $LicenciaMod = new LicenciaModel();
        $licencias = [];
        foreach ($students as $row) {
            $licencias[$row['student_id']] = $LicenciaMod->licenciasStudent($row['student_id']);
        }

        // Pasar las licencias a la vista
        $page_data['licencias'] = $licencias;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'licenses';
        $page_data['page_title'] = 'Licencias';
        return view('backend/index', $page_data);
    }
    public function license_save_dia()
    {
        date_default_timezone_set('America/La_Paz');
        $session = session();

        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $Licencia = new LicenciaModel();
        $StudentMod = new StudentModel();

        $students = $StudentMod->datosStudent($_POST['student_id']);

        $fecha_solicitud = date("Y-m-d H:i:s");
        $student_id = $_POST['student_id'];
        $section_id = $students[0]->section_id;

        if ($section_id < 231) {
            $session->set('flash_message_error', 'La solicitud de licencias por plataforma no está habilitada para su nivel.');
            return redirect()->to(base_url() . 'parents/licenses/');
        }

        $inicio = date("Y-m-d", strtotime($_POST['fecha_inicio']));
        $fin = date("Y-m-d", strtotime($_POST['fecha_fin']));

        $datosLicencia = [
            "student_id" => $student_id,
            "tipo_id" => 1,
            "fecha_solicitud" => $fecha_solicitud,
            "solicitante" => trim($_POST['parent_text']),
            "parentesco_id" => $_POST['parents'],
            "motivo_id" => $_POST['motivo_id'],
            "detalle" => trim($_POST['detalle']),
            "medio_id" => 10,
            "enviado" => 0
        ];

        $licencias_id = $Licencia->insert($datosLicencia);

        if ($licencias_id) {

            $datosDia = [
                "licencias_id" => $licencias_id,
                "fecha_inicio" => $inicio,
                "fecha_fin" => $fin,
                "cantidad_dias" => 0
            ];

            db_connect('asistencia')->table('t_licencias_dia')->insert($datosDia);
        }

        $session->set('flash_message', 'Se guardó la licencia correctamente.');

        return redirect()->to(base_url() . 'parents/licenses/');
    }
    public function license_save_periodo()
    {
        date_default_timezone_set('America/La_Paz');
        $session = session();

        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $Licencia = new LicenciaModel();
        $StudentMod = new StudentModel();

        $students = $StudentMod->datosStudent($_POST['student_id']);

        $fecha_solicitud = date("Y-m-d H:i:s");
        $student_id = $_POST['student_id'];
        $section_id = $students[0]->section_id;

        if ($section_id < 231) {
            $session->set('flash_message_error', 'La solicitud de licencias por plataforma no está habilitada para su nivel.');
            return redirect()->to(base_url() . 'parents/licenses/');
        }

        $fecha = date("Y-m-d", strtotime($_POST['fecha']));
        $periodos = $_POST['periodos']; // array de periodos

        $datosLicencia = [
            "student_id" => $student_id,
            "tipo_id" => 2,
            "fecha_solicitud" => $fecha_solicitud,
            "solicitante" => trim($_POST['parent_text']),
            "parentesco_id" => $_POST['parents'],
            "motivo_id" => $_POST['motivo_id'],
            "detalle" => trim($_POST['detalle']),
            "medio_id" => 10,
            "enviado" => 0
        ];

        $licencias_id = $Licencia->insert($datosLicencia);

        if ($licencias_id) {

            foreach ($periodos as $periodo_id) {

                $datosPeriodo = [
                    "licencias_id" => $licencias_id,
                    "fecha" => $fecha,
                    "periodo_id" => $periodo_id
                ];

                db_connect('asistencia')->table('t_licencias_periodo')->insert($datosPeriodo);
            }
        }

        $session->set('flash_message', 'Se guardó la licencia por periodo correctamente.');

        return redirect()->to(base_url() . 'parents/licenses/');
    }
    public function license_save()
    {
        date_default_timezone_set('America/La_Paz');
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $Licencia = new LicenciaModel();
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($_POST['student_id']);

        // Fecha actual del sistema
        $fecha_php = date("Y-m-d H:i:s");
        $fecha_solicitud = $fecha_php;

        // Datos comunes
        $student_id = $_POST['student_id'];
        $section_id = $students[0]->section_id;

        // ❌ Restricción por nivel (Nivel Inicial/Primario < 231)
        if ($section_id < 231) {
            $session->set('flash_message_error', 'La solicitud de licencias por plataforma no está habilitada para su nivel.');
            return redirect()->to(base_url() . 'parents/licenses/');
        }

        $tipo = $_POST['tipo'];
        $motivo_id = $_POST['motivo_id'];
        $detalle = trim($_POST['detalle']);
        $parentesco_id = $_POST['parents'];
        $solicitante = trim($_POST['parent_text']);

        // Datos comunes para t_licencias
        $datos = [
            "student_id"   => $student_id,
            "tipo_id"      => $tipo,
            "fecha_solicitud" => $fecha_solicitud,
            "solicitante"  => $solicitante,
            "parentesco_id" => $parentesco_id,
            "motivo_id"    => $motivo_id,
            "detalle"      => $detalle,
            "medio_id"     => '10',
            "enviado"      => 0,
        ];

        if ($tipo == '2') {
            // Licencia por periodo
            $fecha_p  = date("Y-m-d", strtotime($_POST['fecha_solicitud']));
            $periodo_id = (int)$_POST['periodo_id'];
        } else {
            // Licencia por días
            $inicio = date("Y-m-d", strtotime($_POST['fecha_inicio']));
            $fin    = date("Y-m-d", strtotime($_POST['fecha_fin']));
        }

        // 🔍 Control de duplicados contra subtablas
        $db_asis  = db_connect('asistencia');
        $hace5min = date("Y-m-d H:i:s", strtotime('-5 minutes'));

        if ($tipo == '2') {
            $exists = $db_asis->query(
                "SELECT l.licencias_id FROM t_licencias l
                 INNER JOIN t_licencias_periodo lp ON lp.licencias_id = l.licencias_id
                 WHERE l.student_id = ? AND l.tipo_id = ? AND l.motivo_id = ? AND l.detalle = ?
                   AND lp.fecha = ? AND lp.periodo_id = ?
                   AND l.fecha_solicitud >= ?
                 LIMIT 1",
                [$student_id, $tipo, $motivo_id, $detalle, $fecha_p, $periodo_id, $hace5min]
            )->getRow();
        } else {
            $exists = $db_asis->query(
                "SELECT l.licencias_id FROM t_licencias l
                 INNER JOIN t_licencias_dia ld ON ld.licencias_id = l.licencias_id
                 WHERE l.student_id = ? AND l.tipo_id = ? AND l.motivo_id = ? AND l.detalle = ?
                   AND ld.fecha_inicio = ? AND ld.fecha_fin = ?
                   AND l.fecha_solicitud >= ?
                 LIMIT 1",
                [$student_id, $tipo, $motivo_id, $detalle, $inicio, $fin, $hace5min]
            )->getRow();
        }

        if ($exists) {
            $session->set('flash_message_error', 'Ya existe una licencia con los mismos datos recientemente registrada.');
            return redirect()->to(base_url() . 'parents/licenses/');
        }

        // Inserción en t_licencias
        $Licencia    = new LicenciaModel();
        $licencias_id = $Licencia->insertLicencia($datos);

        if ($licencias_id) {
            if ($tipo == '2') {
                $db_asis->table('t_licencias_periodo')->insert([
                    "licencias_id" => $licencias_id,
                    "fecha"        => $fecha_p,
                    "periodo_id"   => $periodo_id,
                ]);
            } else {
                $db_asis->table('t_licencias_dia')->insert([
                    "licencias_id" => $licencias_id,
                    "fecha_inicio" => $inicio,
                    "fecha_fin"    => $fin,
                    "cantidad_dias" => 0,
                ]);
            }
        }

        // Manejo del archivo comprobante médico
        if ($this->request->getFile('comprobante_medico') && $this->request->getFile('comprobante_medico')->isValid()) {
            $file = $this->request->getFile('comprobante_medico');
            $extension = $file->getClientExtension();
            $newName = "comprobante_" . strval($licencias_id) . '.' . $extension;
            $file->move('uploads/comprobantes_medicos', $newName);
        }

        if ($licencias_id > 0) {
            $session->set('flash_message', 'Se guardó la licencia correctamente.');
        } else {
            $session->set('flash_message_error', 'Error al registrar la licencia.');
        }

        return redirect()->to(base_url() . 'parents/licenses/');
    }
    public function license_savexxx()
    {
        date_default_timezone_set('America/La_Paz');
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        //FECHA SOLICITUD
        $fecha_php = date("Y-m-d H:i:s");  // Captura la fecha y hora actual del sistema
        $timestamp = strtotime($fecha_php);
        $fecha_solicitud = $fecha_php;
        if ($_POST['tipo'] == '2') {
            $fecha_solicitud = date("Y-m-d", strtotime($_POST['fecha_solicitud']));
            $inicio = date("H:i:s", strtotime($_POST['hora_inicio']));
            $fin = date("H:i:s", strtotime($_POST['hora_fin']));
            $datos = [
                "student_id" => $_POST['student_id'],
                "tipo_id" => $_POST['tipo'],
                "fecha_solicitud" => $fecha_solicitud,
                "solicitante" => $_POST['parent_text'],
                "parentesco_id" => $_POST['parents'],
                "motivo_id" => $_POST['motivo_id'],
                "detalle" => $_POST['detalle'],
                "medio_id" => '10',
                "fecha_inicio" => NULL,
                "fecha_fin" => NULL,
                "hora_inicio" => $inicio,
                "hora_fin" => $fin,
                "cantidad_dias" => '0',
                "enviado" => 0,
            ];

        } else {
            $inicio = date("Y-m-d", strtotime($_POST['fecha_inicio']));
            $fin = date("Y-m-d", strtotime($_POST['fecha_fin']));
            $datos = [
                "student_id" => $_POST['student_id'],
                "tipo_id" => $_POST['tipo'],
                "fecha_solicitud" => $fecha_solicitud,
                "solicitante" => $_POST['parent_text'],
                "parentesco_id" => $_POST['parents'],
                "motivo_id" => $_POST['motivo_id'],
                "detalle" => $_POST['detalle'],
                "medio_id" => '10',
                "fecha_inicio" => $inicio,
                "fecha_fin" => $fin,
                "hora_inicio" => NULL,
                "hora_fin" => NULL,
                "cantidad_dias" => '0',
                "enviado" => 0,
            ];
        }
        $Licencia = new LicenciaModel();
        $respuesta = $Licencia->insertLicencia($datos);
        //$insertedID = $Licencia->insertID(); 

        // Manejo del archivo comprobante_medico (si se subió)
        if ($this->request->getFile('comprobante_medico') && $this->request->getFile('comprobante_medico')->isValid()) {
            $file = $this->request->getFile('comprobante_medico');
            $extension = $file->getClientExtension(); // Obtener la extensión del archivo original
            $newName = "comprobante_" . strval($respuesta) . '.' . $extension; // Genera un nombre aleatorio para evitar colisiones
            $file->move('uploads/comprobantes_medicos', $newName);
            //$datos['comprobante_medico'] = $newName; // Añadir el nombre del archivo a los datos
        }


        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó la licencia Correctamente ');
            return redirect()->to(base_url() . 'parents/licenses/');

        } else {
            $session->set('flash_message_error', 'Error al registrar licencia');
            return redirect()->to(base_url() . 'parents/licenses/');
        }
    }
    //********************************************************CONTINUIDAD 2024 */
    function continuity_student()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        //Configuraciones
        $Setting = new SettingModel();
        $page_data['gestion'] = $Setting->get_gestion('gestion') + 1;
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //HIJOS
        $ContinuityMod = new ContinuityModel();
        $students = $ContinuityMod->continuity_family($family_id);
        $page_data['students'] = $students;
        $moroso = 0;
        foreach ($students as $row):
            //MORA
            $data_mora = ["mora_id" => $row['student_id']];
            $mo = new MoraModel();
            $mora = $mo->get_mora($data_mora);
            if (count($mora) == 1) {
                $moroso = 1;
                $page_data['student'] = $row['student'];
                $page_data['completo'] = $row['completo'];
            }
        endforeach;
        if ($moroso == 1) {
            //BLOQUEAMOS PAGINA A MOROSOS
            $page_data['account_type'] = 'parents';
            $page_data['page_name'] = 'error_6';
            $page_data['page_title'] = 'Continuidad 2026';
            return view('backend/index', $page_data);
        } else {
            //VISTA
            $page_data['page_name'] = 'continuity_student';
            $page_data['page_title'] = 'Continuidad 2026';
            return view('backend/index', $page_data);
        }


    }
    function continuity_save()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());



        //CONTINUIDAD
        $data_continuity = ["student_id" => $_POST['student_id']];
        $ContinuityMod = new ContinuityModel();
        $continuity = $ContinuityMod->get_continuity($data_continuity);
        if (count($continuity) == 1) {
            //Actualizamos
            $datos = [
                "respuesta" => $_POST['continuidad'],
                "obs" => "",
            ];
            $respuesta = $ContinuityMod->update_continuity($datos, $continuity[0]['continuity_id']);
            $session->set('flash_message', 'Respuesta Actualizada Correctamente: ' . $_POST['continuidad']);
        } else {
            //Guardamos
            $datos = [
                "student_id" => $_POST['student_id'],
                "gestion" => $_POST['gestion'],
                "respuesta" => $_POST['continuidad'],
                "obs" => "",
            ];
            $respuesta = $ContinuityMod->insert_continuity($datos);
            $session->set('flash_message', 'Respuesta guardada Correctamente: ' . $_POST['continuidad']);
        }

        //$session->set('flash_message', 'Respuesta guardada Correctamente:'.$_POST['continuidad']);
        return redirect()->to(base_url() . 'parents/continuity_student');

    }
    function gallery()
    {
        $session = session();
        $family_id = $session->get('family_id');
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'gallery';
        $page_data['page_title'] = 'Galería SAAT';
        return view('backend/index', $page_data);
    }
    /*********************************OPCIONES ESTUDIANTE ******************/
    function student_attendance($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
        //Asistencias
        $AssistanceMod = new AssistancesubjectModel();
        $asis = $AssistanceMod->assis_student($student_id, $page_data['phase_id']);
        $page_data['asis'] = $asis;
        //Vista
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'student_attendance';
        $page_data['page_title'] = 'Asistencias';
        return view('backend/index', $page_data);
    }
    function student_licenses($student_id)
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student_id'] = $student_id;
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
        $page_data['family_id'] = $students[0]->family_id;
        //Licencias
        $LicenciaMod = new LicenciaModel();
        $licencias = $LicenciaMod->licenciasStudent($student_id);
        $page_data['licencias'] = $licencias;
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'student_licenses';
        $page_data['page_title'] = 'Reporte de Licencias';
        return view('backend/index', $page_data);
    }
    public function student_absences($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
        //DELAYS
        $AbsenceMod = new AbsenceModel();
        $page_data['absences'] = $AbsenceMod->get_absences_student($student_id);
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['page_title'] = "Ausencias del Estudiante";
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = "student_absences";
        return view('backend/index', $page_data);
    }
    public function student_delays($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
        //DELAYS
        $DelayMod = new DelayModel();
        $page_data['delays'] = $DelayMod->get_delay_student($student_id);

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['page_title'] = "Retrasos del Estudiante";
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = "student_delays";
        return view('backend/index', $page_data);
    }
    /*********************************OPCIONES ESTUDIANTE ******************/
}
