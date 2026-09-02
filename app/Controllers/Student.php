<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\SubjectModel;
use App\Models\DocumentModel;
use App\Models\SectionModel;
use App\Models\BehaviorsModel;
use App\Models\SelfappraisalModel;
use App\Models\StudentModel;
use App\Models\MoraModel;
use App\Models\CsamarksModel;
use App\Models\CsamarksdetailsModel;
use App\Models\IinfractionModel;
use App\Models\BehaviorModel;
use App\Models\ScoreModel;

class Student extends BaseController
{
    public function dashboard()
    {
        $session = session();
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());


        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['self_appraisal'] = $Setting->get_self_appraisal();

        //Curso
        $data = ["section_id" => $session->get('section_id')];
        $SectionMod = new SectionModel();
        $respuesta = $SectionMod->get_section($data);
        $page_data['curso'] = $respuesta[0]['completo'];
        $page_data['schedule'] = $respuesta[0]['schedule'];


        //$page_data['horario']  = $horario[0]->link;
        $page_data['login_type'] = $session->get('login_type');
        $page_data['page_title'] = "Dashboard";
        $page_data['page_name'] = "dashboard";
        return view('backend/index', $page_data);
    }
    /****CARTA DE CONTENIDOS****/
    function content_letter()
    {
        $session = session();
        $student_id = $session->get('student_id');
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //Curso
        $data = ["section_id" => $session->get('section_id')];
        $SectionMod = new SectionModel();
        $respuesta = $SectionMod->get_section($data);
        $page_data['curso'] = $respuesta[0]['completo'];

        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);

        //Documento
        $Document = new DocumentModel();
        $letter = $Document->document_link("content_letter", "section", $session->get('section_id'));
        if (count($letter) > 0) {
            $page_data['letter'] = $letter[0]->link;
        } else {
            //Materias
            $Subject = new SubjectModel();
            $subjects = $Subject->subjects_student($session->get('section_id'), $students[0]->section_id, $students[0]->sex);
            $page_data['materias'] = $subjects;
        }
        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['page_name'] = 'content_letter';
        $page_data['page_title'] = 'Cartas de Contenidos';
        return view('backend/index', $page_data);
    }
    /************************REPORTES CONDUCTUALES *************************/
    function behaviors()
    {
        $session = session();
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //Reportes Conductuales
        $BehaviorsMod = new BehaviorsModel();
        $students = $BehaviorsMod->behaviors_student($page_data['phase_id'], $session->get('student_id'));
        $page_data['behaviors'] = $students;
        //Vista
        $page_data['page_name'] = 'behaviors';
        $page_data['page_title'] = 'Reporte Conductual';
        return view('backend/index', $page_data);
    }
    /****Biblioteca Virtual****/
    function virtual_library_prim()
    {
        $session = session();
        //Curso
        $data = ["section_id" => $session->get('section_id')];
        $SectionMod = new SectionModel();
        $respuesta = $SectionMod->get_section($data);
        $page_data['curso'] = $respuesta[0]['completo'];
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
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
        $data = ["section_id" => $session->get('section_id')];
        $SectionMod = new SectionModel();
        $respuesta = $SectionMod->get_section($data);
        $page_data['curso'] = $respuesta[0]['completo'];
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
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
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'class_protocol';
        $page_data['page_title'] = 'Protocolo de Clases';
        return view('backend/index', $page_data);
    }
    /****protocolo_De_BIOSEGURIDAD****/
    function biosafety_protocol()
    {
        $session = session();
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['page_name'] = 'biosafety_protocol';
        $page_data['page_title'] = 'Protocolo de Bioseguridad';
        return view('backend/index', $page_data);
    }
/*****************************REPORTE DE EVALUACIONES*********************************/
    function evaluation_report()
    {
        $session = session();
        $student_id = $session->get('student_id');
        if ($session->get('login_type') != 'student')
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
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;


        //MORA
        $data_mora = ["mora_id" => $student_id];
        $mo = new MoraModel();
        $mora = $mo->get_mora($data_mora);
        if (count($mora) == 1) {
            //BLOQUEAMOS PAGINA A MOROSOS
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
    /********************************AUTOEVALUACION ****************/
    function self_appraisal()
    {
        $session = session();
        $student_id = $session->get('student_id');
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        //Settings
        $page_data['student_id'] = $student_id;
        $page_data['student'] = $students[0]->nombre;
        $page_data['section'] = $students[0]->completo;
        $page_data['section_id'] = $students[0]->section_id;
        //Fase Actual
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = 'Autoevaluación';
        //Autoevaluaciones
        $data = [
            "student_id" => $student_id,
            "phase_id" => $page_data['phase_id']
        ];
        $self = new SelfappraisalModel();
        $autos = $self->get_self_appraisal($data);
        $page_data['autos'] = $autos;
        //Direccionamos segun el nivel
        if ($page_data['section_id'] < 271) {
            $page_data['page_name'] = 'self_appraisal1';
        } else {
            $page_data['page_name'] = 'self_appraisal2';
        }
        return view('backend/index', $page_data);
    }
    function self_appraisal_save()
    {
        $session = session();
        $student_id = $session->get('student_id');
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student_id'] = $student_id;
        $page_data['student'] = $students[0]->nombre;
        $page_data['section'] = $students[0]->completo;
        $page_data['section_id'] = $students[0]->section_id;
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = 'Autoevaluación';
        $self = new SelfappraisalModel();
        $existe = $self->get_self_appraisal([
            "student_id" => $student_id,
            "phase_id"   => $page_data['phase_id']
        ]);
        if (count($existe) == 0) {
            $datos = [
                'student_id' => $student_id,
                'phase_id'   => $page_data['phase_id'],
            ];
            $sum = 0;
            for ($i = 1; $i <= 10; $i++) {
                $val = intval($_POST['chk_auto_' . $i] ?? 0);
                $datos['auto' . $i] = $val;
                $sum += $val;
            }
            $datos['autoevaluacion'] = (int) round($sum * 0.5);
            if ($page_data['section_id'] >= 271) {
                $datos['descripcion'] = $_POST['descripcion'] ?? '';
            }
            $self->insert_self_appraisal($datos);
            $session->set('flash_message', 'Autoevaluación Guardada Correctamente');
        }
        $page_data['page_name'] = ($page_data['section_id'] < 271) ? 'self_appraisal1' : 'self_appraisal2';
        $page_data['autos'] = $self->get_self_appraisal([
            "student_id" => $student_id,
            "phase_id"   => $page_data['phase_id']
        ]);
        return view('backend/index', $page_data);
    }
    /*****************************BOLETIN DE NOTAS*********************************/
    function report_card($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());
        if ($student_id === '')
            $student_id = $session->get('student_id');
        //Configuraciones
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //DATOS DEL ESTUDIANTE
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $student_nombre = $students[0]->nombre;
        $student_completo = $students[0]->completo;
        //MORA — bloqueamos si el estudiante es deudor
        $mo = new MoraModel();
        $mora = $mo->get_mora(["mora_id" => $student_id]);
        if (count($mora) == 1) {
            //BLOQUEAMOS PAGINA A MOROSOS
            $page_data['student_id'] = $student_id;
            $page_data['student'] = $student_nombre;
            $page_data['completo'] = $student_completo;
            $page_data['account_type'] = 'student';
            $page_data['page_name']    = 'error_6';
            $page_data['page_title']   = 'Boletines de Notas';
            return view('backend/index', $page_data);
        }

        // Construir info de PDF para el estudiante

        $sid  = $student_id;
        $pdfs = [];
        for ($t = 1; $t <= 3; $t++) {
            $archivo = 'RepT' . $t . strval(60900045 + $sid) . '.pdf';
            $pdfs[$t] = [
                'archivo' => $archivo,
                'exists'  => file_exists(FCPATH . 'uploads/t1/' . $archivo),
                'url'     => base_url('uploads/t1/' . $archivo),
            ];
        }
        $students_pdf[] = [
            'student_id' => $sid,
            'student'    => $student_nombre,
            'completo'   => $student_completo,
            'pdfs'       => $pdfs,
        ];

        $page_data['students_pdf'] = $students_pdf;
        //VISTA
        $page_data['account_type'] = 'student';
        $page_data['page_name'] = 'reportcards';
        $page_data['page_title'] = 'Boletines de Notas';
        return view('backend/index', $page_data);
    }
    function infractions()
    {
        $session = session();
        $student_id = $session->get('student_id');
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());


        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['students'] = $students;

        $IinfractionMod = new IinfractionModel();
        $page_data['infractions'] = $IinfractionMod->infraction_student($student_id);

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['page_name'] = 'infractions';
        $page_data['page_title'] = 'Planilla de Indisciplina';
        return view('backend/index', $page_data);
    }

    public function gamified_behavior()
    {
        helper('grade');
        $session    = session();
        $student_id = $session->get('student_id');
        if ($session->get('login_type') != 'student')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['login_type']    = $session->get('login_type');
        $page_data['phase_id']      = $Setting->get_phase_id();
        $page_data['phase_name']    = $Setting->get_phase_name();
        $page_data['system_title']  = $Setting->get_system_title();
        $page_data['system_name']   = $Setting->get_system_name();

        $StudentMod = new StudentModel();
        $students   = $StudentMod->datosStudent($student_id);
        $stu        = $students[0];

        $page_data['student_id']   = $student_id;
        $page_data['student_name'] = $stu->nombre;
        $page_data['curso']        = $stu->completo;

        $SubjectMod   = new SubjectModel();
        $subjects     = $SubjectMod->subjects_student($stu->section_id, $stu->sex);
        $section_id_p = (int) $stu->section_id;

        // ── T1 (phase 1): behavior_log — desglose por materia ──
        $BehaviorMod = new BehaviorModel();
        $ScoreMod    = new ScoreModel();

        $recentDate_t1    = \Config\Database::connect('asistencia')->table('attendance_dates')
            ->where('phase_id', 1)->orderBy('date_class', 'DESC')->limit(1)->get()->getRowArray();
        $currentDateId_t1 = $recentDate_t1 ? $recentDate_t1['date_id'] : null;

        $subjectStats_t1   = [];
        $globalPositive_t1 = 0;
        $globalNegative_t1 = 0;
        $timeline_t1       = [];

        foreach ($subjects as $sub) {
            $subjId = $sub['subject_id'];
            $logs   = $BehaviorMod->getStudentLog($student_id, null, $subjId, 1);

            $pos = count(array_filter($logs, fn($l) => $l['type'] == 'positive'));
            $neg = count(array_filter($logs, fn($l) => $l['type'] == 'negative'));
            $globalPositive_t1 += $pos;
            $globalNegative_t1 += $neg;

            foreach ($logs as &$log) {
                $log['subject_name'] = $sub['name'];
                $log['teacher_name'] = $sub['profe'];
            }
            unset($log);
            $timeline_t1 = array_merge($timeline_t1, $logs);

            $puntosDelSer = 10;
            if ($currentDateId_t1) {
                $scaled       = ($ScoreMod->getDailyScore($student_id, $currentDateId_t1, $subjId) / 100) * 10;
                $puntosDelSer = max(0, min(10, round($scaled, 1)));
            }
            $subjectStats_t1[] = [
                'subject_id'     => $subjId,
                'name'           => $sub['name'],
                'teacher'        => $sub['profe'],
                'positive_count' => $pos,
                'negative_count' => $neg,
                'ser_score'      => $puntosDelSer,
            ];
        }
        usort($timeline_t1, fn($a, $b) => strtotime($b['created_at']) <=> strtotime($a['created_at']));

        $page_data['subject_stats_t1']   = $subjectStats_t1;
        $page_data['global_positive_t1'] = $globalPositive_t1;
        $page_data['global_negative_t1'] = $globalNegative_t1;
        $page_data['timeline_t1']        = $timeline_t1;

        // ── T2 y T3 (phase 2, 3): incidencia_registro — desglose por maestro ──
        $IncidenciaMod = new \App\Models\IncidenciaModel();

        $teacherMap = [];
        foreach ($subjects as $sub) {
            $tid = (int) $sub['teacher_id'];
            if (!isset($teacherMap[$tid]))
                $teacherMap[$tid] = ['teacher_id' => $tid, 'teacher_name' => $sub['profe'], 'subjects' => []];
            $teacherMap[$tid]['subjects'][] = $sub['name'];
        }

        foreach ([2 => 't2', 3 => 't3'] as $phase => $suffix) {
            $teacher_stats  = [];
            $globalNegativa = 0;
            $globalPositiva = 0;

            foreach ($teacherMap as $tid => $tdata) {
                $conteos         = $IncidenciaMod->getConteosByTeacher($student_id, $tid, $section_id_p, $phase);
                $globalNegativa += $conteos['negativa'];
                $globalPositiva += $conteos['positiva'];
                $teacher_stats[] = [
                    'teacher_id'   => $tid,
                    'teacher_name' => $tdata['teacher_name'],
                    'subjects'     => implode(', ', $tdata['subjects']),
                    'negativa'     => $conteos['negativa'],
                    'positiva'     => $conteos['positiva'],
                    'nota'         => $conteos['nota'],
                ];
            }
            usort($teacher_stats, fn($a, $b) => $a['nota'] <=> $b['nota']);

            $page_data["teacher_stats_{$suffix}"]   = $teacher_stats;
            $page_data["timeline_{$suffix}"]        = $IncidenciaMod->getRegistroEstudianteConMaestro($student_id, $phase);
            $page_data["global_negativa_{$suffix}"] = $globalNegativa;
            $page_data["global_positiva_{$suffix}"] = $globalPositiva;
        }

        $page_data['page_name']  = 'gamified_behavior';
        $page_data['page_title'] = 'Historial de Comportamiento';
        return view('backend/index', $page_data);
    }
}
