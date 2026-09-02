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
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        helper('grade');
    }

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

        $all_children = (new StudentModel())->students_family($family_id);
        $page_data['has_primaria']   = !empty(array_filter($all_children, fn($c) => isPrimaria36($c['grade'])));
        $page_data['has_secundaria'] = !empty(array_filter($all_children, fn($c) => !isPrimaria36($c['grade']) && ($c['section_id'] ?? 0) >= 231));

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

        $Setting = new SettingModel();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();

        $StudentMod = new StudentModel();
        $students   = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;

        // Drive links for primary/inicial keyed by grade slug → [1=>url, 2=>url, 3=>url]
        $drive_links = [
            'kinder' => [
                1 => 'https://drive.google.com/drive/folders/1vOm60FQZLHdaxzjSnQxGIUk1jAjDw5rm',
                2 => 'https://drive.google.com/drive/folders/1Z9ngUrjpckTXgwq6Q1XnQO8datFjYjDU',
                3 => 'https://drive.google.com/drive/folders/133wcdBdPUVcHNWKQROdwaIeLL1yUvLzB',
            ],
            '1ro' => [
                1 => 'https://drive.google.com/drive/folders/1-WJrLAwxGws_EvoEDWja-VxxZckJGG0j',
                2 => 'https://drive.google.com/drive/folders/1N8H67MqdDFPSs4KIwwWN7syYDsWD8H7e',
                3 => 'https://drive.google.com/drive/folders/1LkJ7j2kTT9k41A52HmXtsweUsuSKrCEp',
            ],
            '2do' => [
                1 => 'https://drive.google.com/drive/folders/1fHzSlqkQnDcrUOVihlO9iskIesAZP2Ev',
                2 => 'https://drive.google.com/drive/folders/1cA-_jAyFvO_XdOoJhy8_Io90JUh7JETB',
                3 => 'https://drive.google.com/drive/folders/118ArJF0oEYehYNPUMcqBwyQRREkmbo4p',
            ],
            '3ro' => [
                1 => 'https://drive.google.com/drive/folders/1Nle5_Y4gIn2uuX_D3HMxuaSNw4k-BNMt',
                2 => 'https://drive.google.com/drive/folders/1L_T1cwrqVtHNQnDKuuppzHq5WacVtOcM',
                3 => 'https://drive.google.com/drive/folders/1s46I5t8OpgRu9TqXZwuCc0xiXXOzAUFF',
            ],
            '4to' => [
                1 => 'https://drive.google.com/drive/folders/1uVN3aECHhbddKDRjDfU-CP00AmgyeKzJ',
                2 => 'https://drive.google.com/drive/folders/1KH9POAhjKoDFfokmoX6O3H47XVMU2EjF',
                3 => 'https://drive.google.com/drive/folders/1MBYgERK54enYjqe-vM39CJzCX49ooTRO',
            ],
            '5to' => [
                1 => 'https://drive.google.com/drive/folders/1ChJvHQdkf8sC7mwLGzk1Oqhs6SwtefmC',
                2 => 'https://drive.google.com/drive/folders/1mtXYtrq-PR8IsyWcVrA6RXNuk777hziw',
                3 => 'https://drive.google.com/drive/folders/1j91ToPsSDGWjXpCCm271N8Mosm1c9Kyb',
            ],
            '6to' => [
                1 => 'https://drive.google.com/drive/folders/1Ul9hN3QuWmMvFktptjD8G_5L8CZCDHnj',
                2 => 'https://drive.google.com/drive/folders/1mUsh3pY3w_cyyQEv4O7_CZi8KSqU1b4k',
                3 => 'https://drive.google.com/drive/folders/1w6MWIjn_2ZXa-4qJQbMe5zhMDsGCq7vS',
            ],
        ];

        $Subject      = new SubjectModel();
        $student_data = [];

        foreach ($students as $stu) {
            $sid        = $stu['student_id'];
            $section_id = (int)$stu['section_id'];
            $is_sec     = stripos($stu['grade'] ?? '', 'secundaria') !== false;

            if ($is_sec) {
                $subjects = $Subject->subjects_student($section_id, $stu['sex']);

                // Build canonical_id map: MIN(subject_id) per (name, teacher_id) for this grade
                $class_id      = $Subject->get_class_id_for_section($section_id);
                $canonical_map = [];
                if ($class_id) {
                    foreach ($Subject->canonical_subjects_for_grade($class_id) as $r) {
                        $canonical_map[$r['name'] . '||' . $r['teacher_id']] = (int)$r['canonical_id'];
                    }
                }

                $subjects_out = [];
                foreach ($subjects as $sub) {
                    $map_key      = $sub['name'] . '||' . $sub['teacher_id'];
                    $canonical_id = $canonical_map[$map_key] ?? $sub['subject_id'];
                    $trims        = [];
                    for ($t = 1; $t <= 3; $t++) {
                        $fname     = "CC_{$canonical_id}_T{$t}.pdf";
                        $trims[$t] = file_exists(FCPATH . 'uploads/content_letter/' . $fname) ? $fname : null;
                    }
                    $subjects_out[] = ['name' => $sub['name'], 'trims' => $trims];
                }
                $student_data[$sid] = ['type' => 'secondary', 'subjects' => $subjects_out];
            } else {
                $grade_key = $this->_gradeKey($stu['grade'] ?? '');
                $student_data[$sid] = [
                    'type'        => 'primary',
                    'drive_links' => $grade_key ? ($drive_links[$grade_key] ?? null) : null,
                ];
            }
        }

        $page_data['student_data'] = $student_data;
        $page_data['login_type']   = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['page_name']    = 'content_letter';
        $page_data['page_title']   = 'Cartas de Contenidos';
        return view('backend/index', $page_data);
    }

    private function _gradeKey(string $grade): ?string
    {
        $g = strtolower(trim($grade));
        // Kinder: grade field is "Inicial" in this DB
        if ($g === 'inicial' || strpos($g, 'kinder') !== false)       return 'kinder';
        if (preg_match('/\b(1ro|1°|primero)\b/', $g))                 return '1ro';
        if (preg_match('/\b(2do|2°|segundo|segundi)\b/', $g))         return '2do';
        if (preg_match('/\b(3ro|3°|tercero)\b/', $g))                 return '3ro';
        if (preg_match('/\b(4to|4°|cuarto)\b/', $g))                  return '4to';
        if (preg_match('/\b(5to|5°|quinto)\b/', $g))                  return '5to';
        if (preg_match('/\b(6to|6°|sexto)\b/', $g))                   return '6to';
        return null;
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
    function reportcards()
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
        //MORA — bloqueamos si algún hijo es deudor
        $mo = new MoraModel();
        foreach ($students as $stu) {
            $mora = $mo->get_mora(["mora_id" => $stu['student_id']]);
            if (count($mora) == 1) {
                //BLOQUEAMOS PAGINA A MOROSOS
                $page_data['student_id'] = $stu['student_id'];
                $page_data['student'] = $stu['student'];
                $page_data['completo'] = $stu['completo'];
                $page_data['account_type'] = 'parents';
                $page_data['page_name']    = 'error_6';
                $page_data['page_title']   = 'Boletines de Notas';
                return view('backend/index', $page_data);
            }
        }
        // Construir info de PDFs por estudiante
        $students_pdf = [];
        foreach ($students as $stu) {
            $sid  = $stu['student_id'];
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
                'student'    => $stu['student'],
                'completo'   => $stu['completo'],
                'pdfs'       => $pdfs,
            ];
        }
        $page_data['students_pdf'] = $students_pdf;
        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['account_type'] = 'parents';
        $page_data['page_name'] = 'reportcards';
        $page_data['page_title'] = 'Boletines de Notas';
        return view('backend/index', $page_data);
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
        helper('grade');
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

        $SubjectMod = new SubjectModel();
        $subjects   = $SubjectMod->subjects_student($current_student['section_id'], $current_student['sex']);
        $section_id_p = (int) $current_student['section_id'];

        // ── T1 (phase 1): behavior_log — desglose por materia ──────────────
        $BehaviorMod = new BehaviorModel();
        $ScoreMod    = new ScoreModel();

        $recentDate_t1 = \Config\Database::connect('asistencia')->table('attendance_dates')
            ->where('phase_id', 1)
            ->orderBy('date_class', 'DESC')
            ->limit(1)->get()->getRowArray();
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
                $scaled = ($ScoreMod->getDailyScore($student_id, $currentDateId_t1, $subjId) / 100) * 10;
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

        // ── T2 y T3 (phase 2, 3): incidencia_registro — desglose por maestro
        $IncidenciaMod = new \App\Models\IncidenciaModel();

        // Agrupar materias por maestro (una sola vez)
        $teacherMap = [];
        foreach ($subjects as $sub) {
            $tid = (int) $sub['teacher_id'];
            if (!isset($teacherMap[$tid])) {
                $teacherMap[$tid] = [
                    'teacher_id'   => $tid,
                    'teacher_name' => $sub['profe'],
                    'subjects'     => [],
                ];
            }
            $teacherMap[$tid]['subjects'][] = $sub['name'];
        }

        foreach ([2 => 't2', 3 => 't3'] as $phase => $suffix) {
            $teacher_stats  = [];
            $globalNegativa = 0;
            $globalPositiva = 0;

            foreach ($teacherMap as $tid => $tdata) {
                $conteos = $IncidenciaMod->getConteosByTeacher($student_id, $tid, $section_id_p, $phase);
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

        $page_data['student_id']   = $student_id;
        $page_data['account_type'] = 'parents';
        $page_data['page_name']    = 'gamified_behavior';
        $page_data['page_title']   = 'Historial de Comportamiento';
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
        $page_data['phase_id']   = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        // Datos del padre logueado
        $ParentMod = new \App\Models\ParentModel();
        $parent = $ParentMod->get_parent(['parent_id' => $session->get('parent_id')]);
        $page_data['parent'] = $parent[0] ?? [];
        // Lugares de nacimiento
        $PlaceMod = new \App\Models\PlaceModel();
        $page_data['places'] = $PlaceMod->get_places();
        // Detectar si tiene hijos en primaria 3ro-6to para mostrar botón de licencias
        $family_id = $session->get('family_id');
        $all_children = (new StudentModel())->students_family($family_id);
        $page_data['has_primaria'] = !empty(array_filter($all_children, fn($c) => isPrimaria36($c['grade'])));
        $page_data['account_type'] = 'parents';
        $page_data['page_name']    = 'profile';
        $page_data['page_title']   = 'Mi Perfil';
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

        $Licencia   = new LicenciaModel();
        $StudentMod = new StudentModel();

        $student_id = (int) ($_POST['student_id'] ?? 0);
        $students   = $StudentMod->datosStudent($student_id);

        if (empty($students) || (int) $students[0]->family_id !== (int) $session->get('family_id')) {
            $session->set('flash_message_error', 'No tiene permiso para solicitar licencias para este alumno.');
            return redirect()->to(base_url() . 'parents/licenses/');
        }

        $section_id = $students[0]->section_id;

        if ($section_id < 231) {
            $session->set('flash_message_error', 'La solicitud de licencias por plataforma no está habilitada para su nivel.');
            return redirect()->to(base_url() . 'parents/licenses/');
        }

        $inicio = date("Y-m-d", strtotime($_POST['fecha_inicio']));
        $fin    = date("Y-m-d", strtotime($_POST['fecha_fin']));

        // Calcular días y fraccion_cupo
        $cantidad_dias = (int) round((strtotime($fin) - strtotime($inicio)) / 86400) + 1;

// Determinar si el motivo es excepción
        $db       = db_connect('asistencia');
        $motivoRow = $db->query(
            "SELECT es_excepcion FROM t_motivos WHERE motivo_id = ? LIMIT 1",
            [(int)$_POST['motivo_id']]
        )->getRowArray();
        $es_excepcion = ($motivoRow && $motivoRow['es_excepcion']) ? 1 : 0;
        $fraccion_cupo = $es_excepcion ? 0.0 : (float)$cantidad_dias;

        // Primaria 3ro-6to usa tablas prim_*, el resto usa t_licencias
        $esPrimaria36 = ($section_id >= 231 && $section_id <= 263);
        $tabla_lic    = $esPrimaria36 ? 'prim_licencias'     : 't_licencias';
        $tabla_dia    = $esPrimaria36 ? 'prim_licencias_dia' : 't_licencias_dia';

        // Hora de cierre (solo primaria 3-6, solo padres): no se puede pedir
        // por la plataforma una licencia que empiece hoy después de esta hora.
        if ($esPrimaria36 && $inicio === date('Y-m-d')) {
            $horaCierre = (new \Config\PrimReglas())->horaCierre;
            if (date('H:i') > $horaCierre) {
                $horaCierreTexto = date('g:i a', strtotime($horaCierre));
                $session->set('flash_message_error', '⚠️ El horario para solicitar licencias del mismo día cerró a las ' . $horaCierreTexto . '. Por favor comuníquese con la secretaría de su nivel para coordinar.');
                return redirect()->to(base_url('parents/prim_licencias?student_id=' . $student_id . '&form=dia'));
            }
        }

        // Cupo trimestral (solo primaria 3-6): si el alumno ya está en el límite
        // de 9 días, no se permite registrar más licencias salvo excepción.
        if ($esPrimaria36 && !$es_excepcion) {
            $Setting  = new SettingModel();
            $phaseRow = \Config\Database::connect('tiquipaya')
                ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$Setting->get_phase_id()])
                ->getRowArray();
            if ($phaseRow) {
                $cupoActual = (new \App\Models\PrimCupoModel())->calcularCupo(
                    $student_id, $phaseRow['inicio'], $phaseRow['fin']
                );
                if ($cupoActual['limite9']) {
                    $session->set('flash_message_error', '⚠️ Este alumno ya alcanzó el límite de 9 días de licencia para este trimestre y no podrá solicitar más licencias durante el resto del trimestre. Las actividades académicas no serán reprogramadas. Si tiene dudas, puede comunicarse con la secretaría de su nivel.');
                    return redirect()->to(base_url('parents/prim_licencias?student_id=' . $student_id . '&form=dia'));
                }
            }
        }

        // Más de 3 días: exige carta de solicitud adjunta (primaria)
        if ($esPrimaria36 && $cantidad_dias > 3) {
            $cartaFile = $this->request->getFile('carta_solicitud');
            if (!$cartaFile || !$cartaFile->isValid() || $cartaFile->hasMoved()) {
                $session->set('flash_message_error', 'Para licencias de más de 3 días debe adjuntar la carta de solicitud.');
                return redirect()->to(base_url('parents/prim_licencias?student_id=' . $student_id . '&form=dia'));
            }
        }

        // Evitar doble envío
        $existing = $db->query(
            "SELECT l.licencias_id FROM {$tabla_lic} l
             INNER JOIN {$tabla_dia} ld ON ld.licencias_id = l.licencias_id
             WHERE l.student_id = ? AND l.tipo_id = 1
               AND DATE(l.fecha_solicitud) = CURDATE()
               AND ld.fecha_inicio = ? AND ld.fecha_fin = ?
             LIMIT 1",
            [$student_id, $inicio, $fin]
        )->getRow();

        if ($existing) {
            $licencias_id = $existing->licencias_id;
        } else {
            $doc_pendiente = (!empty($_POST['doc_pendiente']) && $_POST['doc_pendiente'] == '1') ? 1 : 0;

            $datosLicencia = [
                "student_id"      => $student_id,
                "tipo_id"         => 1,
                "fecha_solicitud" => date("Y-m-d H:i:s"),
                "solicitante"     => trim($_POST['parent_text']),
                "parentesco_id"   => $_POST['parents'],
                "motivo_id"       => $_POST['motivo_id'],
                "detalle"         => trim($_POST['detalle']),
                "medio_id"        => 10,
                "enviado"         => 0,
                "es_excepcion"    => $es_excepcion,
                "fraccion_cupo"   => $fraccion_cupo,
                "doc_pendiente"   => $doc_pendiente,
            ];

            if ($esPrimaria36) {
                $db->table($tabla_lic)->insert($datosLicencia);
                $licencias_id = $db->insertID();
            } else {
                $licencias_id = $Licencia->insert($datosLicencia);
            }

            if ($licencias_id) {
                $db->table($tabla_dia)->insert([
                    "licencias_id"  => $licencias_id,
                    "fecha_inicio"  => $inicio,
                    "fecha_fin"     => $fin,
                    "cantidad_dias" => $cantidad_dias,
                ]);
            }
        }

        // Subir comprobante médico
        if ($licencias_id) {
            $fileInput = $this->request->getFile('comprobante_medico');
            if ($fileInput && $fileInput->isValid() && !$fileInput->hasMoved()) {
                $extension         = strtolower($fileInput->getClientExtension());
                $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                $uploadDir         = FCPATH . 'uploads/comprobantes_medicos';

                if (in_array($extension, $allowedExtensions) && $fileInput->getSize() <= 5 * 1024 * 1024) {
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $newName = 'comprobante_' . $licencias_id . '.' . $extension;
                    if ($fileInput->move($uploadDir, $newName)) {
                        if ($esPrimaria36) {
                            $db->table($tabla_lic)->where('licencias_id', $licencias_id)->update(['comprobante_medico' => $newName]);
                        } else {
                            $Licencia->updateLicencia(['comprobante_medico' => $newName], $licencias_id);
                        }
                    }
                }
            }
        }

        // Subir carta de solicitud (obligatoria si > 3 días, primaria)
        if ($licencias_id && $esPrimaria36 && $cantidad_dias > 3) {
            $cartaFile = $this->request->getFile('carta_solicitud');
            if ($cartaFile && $cartaFile->isValid() && !$cartaFile->hasMoved()) {
                $extension         = strtolower($cartaFile->getClientExtension());
                $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                $uploadDir         = FCPATH . 'uploads/cartas_solicitud';

                if (in_array($extension, $allowedExtensions) && $cartaFile->getSize() <= 5 * 1024 * 1024) {
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $newName = 'carta_' . $licencias_id . '.' . $extension;
                    if ($cartaFile->move($uploadDir, $newName)) {
                        $db->table($tabla_lic)->where('licencias_id', $licencias_id)->update(['carta_solicitud' => $newName]);
                    }
                }
            }
        }

        // Verificar/generar alertas de cupo (6 y 9 días) tras la solicitud
        if ($licencias_id && $esPrimaria36) {
            $SettingAlerta = new SettingModel();
            $phaseAlerta   = \Config\Database::connect('tiquipaya')
                ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$SettingAlerta->get_phase_id()])
                ->getRowArray();
            if ($phaseAlerta) {
                (new \App\Models\PrimCupoModel())->verificarYGenerarAlertas(
                    $student_id, $SettingAlerta->get_phase_id(), $phaseAlerta['inicio'], $phaseAlerta['fin']
                );
            }
        }

        $session->set('flash_message', 'Se guardó la licencia correctamente.');
        $dest = $esPrimaria36 ? 'parents/prim_licencias?student_id=' . $student_id : 'parents/licenses/';
        return redirect()->to(base_url() . $dest);
    }
    public function license_save_periodo()
    {
        date_default_timezone_set('America/La_Paz');
        $session = session();

        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $Licencia   = new LicenciaModel();
        $StudentMod = new StudentModel();

        $student_id = (int) ($_POST['student_id'] ?? 0);
        $students   = $StudentMod->datosStudent($student_id);

        if (empty($students) || (int) $students[0]->family_id !== (int) $session->get('family_id')) {
            $session->set('flash_message_error', 'No tiene permiso para solicitar licencias para este alumno.');
            return redirect()->to(base_url() . 'parents/licenses/');
        }

        $section_id = $students[0]->section_id;

        if ($section_id < 231) {
            $session->set('flash_message_error', 'La solicitud de licencias por plataforma no está habilitada para su nivel.');
            return redirect()->to(base_url() . 'parents/licenses/');
        }

        $fecha    = date("Y-m-d", strtotime($_POST['fecha']));
        $periodos = $_POST['periodos']; // array de periodo_ids
        $hora_salida     = $_POST['hora_salida']     ?? null;
        $hora_fin_clases = $_POST['hora_fin_clases'] ?? null;

        $db = db_connect('asistencia');

        // Calcular fraccion_cupo según cuántos períodos se marcaron, contando cada
        // período como 1 hora: más de 2 períodos (>2 horas) = 1 día completo.
        $fraccion_cupo = (is_array($periodos) && count($periodos) > 2) ? 1.0 : 0.5;

        // Determinar si el motivo es excepción
        $motivoRow = $db->query(
            "SELECT es_excepcion FROM t_motivos WHERE motivo_id = ? LIMIT 1",
            [(int)$_POST['motivo_id']]
        )->getRowArray();
        $es_excepcion = ($motivoRow && $motivoRow['es_excepcion']) ? 1 : 0;
        if ($es_excepcion) $fraccion_cupo = 0.0;

        // Primaria 3ro-6to usa tablas prim_*
        $esPrimaria36   = ($section_id >= 231 && $section_id <= 263);
        $tabla_lic      = $esPrimaria36 ? 'prim_licencias'          : 't_licencias';
        $tabla_periodo  = $esPrimaria36 ? 'prim_licencias_periodo'  : 't_licencias_periodo';

        // Hora de cierre (solo primaria 3-6, solo padres): no se puede pedir
        // por la plataforma una licencia por período para hoy después de esta hora.
        if ($esPrimaria36 && $fecha === date('Y-m-d')) {
            $horaCierre = (new \Config\PrimReglas())->horaCierre;
            if (date('H:i') > $horaCierre) {
                $horaCierreTexto = date('g:i a', strtotime($horaCierre));
                $session->set('flash_message_error', '⚠️ El horario para solicitar licencias por período del mismo día cerró a las ' . $horaCierreTexto . '. Por favor comuníquese con la secretaría de su nivel para coordinar.');
                return redirect()->to(base_url('parents/prim_licencias?student_id=' . $student_id . '&form=sal'));
            }
        }

        // Cupo trimestral (solo primaria 3-6): si el alumno ya está en el límite
        // de 9 días, no se permite registrar más licencias salvo excepción.
        if ($esPrimaria36 && !$es_excepcion) {
            $Setting  = new SettingModel();
            $phaseRow = \Config\Database::connect('tiquipaya')
                ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$Setting->get_phase_id()])
                ->getRowArray();
            if ($phaseRow) {
                $cupoActual = (new \App\Models\PrimCupoModel())->calcularCupo(
                    $student_id, $phaseRow['inicio'], $phaseRow['fin']
                );
                if ($cupoActual['limite9']) {
                    $session->set('flash_message_error', '⚠️ Este alumno ya alcanzó el límite de 9 días de licencia para este trimestre y no podrá solicitar más licencias durante el resto del trimestre. Las actividades académicas no serán reprogramadas. Si tiene dudas, puede comunicarse con la secretaría de su nivel.');
                    return redirect()->to(base_url('parents/prim_licencias?student_id=' . $student_id . '&form=sal'));
                }
            }
        }

        // Evitar doble envío
        $existing = $db->query(
            "SELECT l.licencias_id FROM {$tabla_lic} l
             INNER JOIN {$tabla_periodo} lp ON lp.licencias_id = l.licencias_id
             WHERE l.student_id = ? AND l.tipo_id = 2
               AND DATE(l.fecha_solicitud) = CURDATE()
               AND lp.fecha = ?
             LIMIT 1",
            [$student_id, $fecha]
        )->getRow();

        if ($existing) {
            $licencias_id = $existing->licencias_id;
        } else {
            $doc_pendiente = (!empty($_POST['doc_pendiente']) && $_POST['doc_pendiente'] == '1') ? 1 : 0;

            $datosLicencia = [
                "student_id"      => $student_id,
                "tipo_id"         => 2,
                "fecha_solicitud" => date("Y-m-d H:i:s"),
                "hora_salida"     => $hora_salida,
                "hora_fin_clases" => $hora_fin_clases,
                "solicitante"     => trim($_POST['parent_text']),
                "parentesco_id"   => $_POST['parents'],
                "motivo_id"       => $_POST['motivo_id'],
                "detalle"         => trim($_POST['detalle']),
                "medio_id"        => 10,
                "enviado"         => 0,
                "es_excepcion"    => $es_excepcion,
                "fraccion_cupo"   => $fraccion_cupo,
                "doc_pendiente"   => $doc_pendiente,
            ];

            if ($esPrimaria36) {
                $datosLicencia['recoge_nombre']        = trim($_POST['recoge_nombre'] ?? '');
                $datosLicencia['recoge_parentesco_id'] = (int)($_POST['recoge_parentesco_id'] ?? 0) ?: null;
                $datosLicencia['se_reincorpora']        = !empty($_POST['se_reincorpora']) ? 1 : 0;
                $db->table($tabla_lic)->insert($datosLicencia);
                $licencias_id = $db->insertID();
            } else {
                $licencias_id = $Licencia->insert($datosLicencia);
            }

            if ($licencias_id) {
                foreach ($periodos as $periodo_id) {
                    $db->table($tabla_periodo)->insert([
                        "licencias_id" => $licencias_id,
                        "fecha"        => $fecha,
                        "periodo_id"   => (int)$periodo_id,
                    ]);
                }
            }
        }

        // Subir comprobante médico
        if ($licencias_id) {
            $fileInput = $this->request->getFile('comprobante_medico');
            if ($fileInput && $fileInput->isValid() && !$fileInput->hasMoved()) {
                $extension         = strtolower($fileInput->getClientExtension());
                $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                $uploadDir         = FCPATH . 'uploads/comprobantes_medicos';

                if (in_array($extension, $allowedExtensions) && $fileInput->getSize() <= 5 * 1024 * 1024) {
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                    $newName = 'comprobante_' . $licencias_id . '.' . $extension;
                    if ($fileInput->move($uploadDir, $newName)) {
                        if ($esPrimaria36) {
                            $db->table($tabla_lic)->where('licencias_id', $licencias_id)->update(['comprobante_medico' => $newName]);
                        } else {
                            $Licencia->updateLicencia(['comprobante_medico' => $newName], $licencias_id);
                        }
                    }
                }
            }
        }

        // Verificar/generar alertas de cupo (6 y 9 días) tras la solicitud
        if ($licencias_id && $esPrimaria36) {
            $SettingAlerta = new SettingModel();
            $phaseAlerta   = \Config\Database::connect('tiquipaya')
                ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$SettingAlerta->get_phase_id()])
                ->getRowArray();
            if ($phaseAlerta) {
                (new \App\Models\PrimCupoModel())->verificarYGenerarAlertas(
                    $student_id, $SettingAlerta->get_phase_id(), $phaseAlerta['inicio'], $phaseAlerta['fin']
                );
            }
        }

        $session->set('flash_message', 'Se guardó la licencia por periodos correctamente.');
        $dest = $esPrimaria36 ? 'parents/prim_licencias?student_id=' . $student_id : 'parents/licenses/';
        return redirect()->to(base_url() . $dest);
    }

    public function prim_upload_comprobante()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return $this->response->setJSON(['ok' => false, 'msg' => 'No autorizado']);

        $licencias_id = (int)($this->request->getPost('licencias_id') ?? 0);
        $student_id   = (int)($this->request->getPost('student_id')   ?? 0);
        if (!$licencias_id || !$student_id)
            return $this->response->setJSON(['ok' => false, 'msg' => 'Datos incompletos']);

        $db = db_connect('asistencia');

        // Verificar que la licencia pertenece a un hijo de este padre
        $family_id = $session->get('family_id');
        $check = $db->query(
            "SELECT l.licencias_id FROM prim_licencias l
             INNER JOIN t_student s ON s.student_id = l.student_id
             WHERE l.licencias_id = ? AND l.student_id = ? AND s.family_id = ?
             LIMIT 1",
            [$licencias_id, $student_id, $family_id]
        )->getRow();

        if (!$check)
            return $this->response->setJSON(['ok' => false, 'msg' => 'Licencia no encontrada']);

        $fileInput = $this->request->getFile('comprobante_medico');
        if (!$fileInput || !$fileInput->isValid() || $fileInput->hasMoved())
            return $this->response->setJSON(['ok' => false, 'msg' => 'Archivo no válido']);

        $extension         = strtolower($fileInput->getClientExtension());
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $uploadDir         = FCPATH . 'uploads/comprobantes_medicos';

        if (!in_array($extension, $allowedExtensions))
            return $this->response->setJSON(['ok' => false, 'msg' => 'Formato no permitido (pdf, jpg, png)']);
        if ($fileInput->getSize() > 5 * 1024 * 1024)
            return $this->response->setJSON(['ok' => false, 'msg' => 'El archivo supera los 5 MB']);

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $newName = 'comprobante_' . $licencias_id . '.' . $extension;

        if (!$fileInput->move($uploadDir, $newName, true))
            return $this->response->setJSON(['ok' => false, 'msg' => 'Error al guardar el archivo']);

        $db->table('prim_licencias')
           ->where('licencias_id', $licencias_id)
           ->update(['comprobante_medico' => $newName, 'doc_pendiente' => 0]);

        return $this->response->setJSON(['ok' => true, 'archivo' => $newName]);
    }

    /**
     * Permite al padre cancelar (soft-delete) una solicitud de licencia propia
     * mientras siga pendiente. Una vez aprobada/rechazada, solo secretaría puede eliminarla.
     */
    public function prim_licencia_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return $this->response->setJSON(['ok' => false, 'msg' => 'No autorizado']);

        $licencias_id = (int)($this->request->getPost('licencias_id') ?? 0);
        if (!$licencias_id)
            return $this->response->setJSON(['ok' => false, 'msg' => 'Datos incompletos']);

        $family_id = $session->get('family_id');
        $db = db_connect('asistencia');

        $lic = $db->query(
            "SELECT l.licencias_id, l.enviado FROM prim_licencias l
             INNER JOIN t_student s ON s.student_id = l.student_id
             WHERE l.licencias_id = ? AND s.family_id = ?
             LIMIT 1",
            [$licencias_id, $family_id]
        )->getRowArray();

        if (!$lic)
            return $this->response->setJSON(['ok' => false, 'msg' => 'Licencia no encontrada']);

        if ((int)$lic['enviado'] !== 0)
            return $this->response->setJSON(['ok' => false, 'msg' => 'Solo puede cancelar solicitudes que aún están pendientes. Para casos ya resueltos, comuníquese con secretaría de nivel.']);

        // Se deja registrado que la canceló el propio padre/madre, para que secretaría
        // lo distinga de una eliminación hecha por el colegio al revisar el historial.
        (new \App\Models\PrimLicenciaModel())->eliminarLicencia($licencias_id, 'Cancelada por el padre/madre de familia.');
        return $this->response->setJSON(['ok' => true]);
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
            // Acepta 'fecha' (modal_hora) o toma la fecha de hoy como fallback
            $fecha_p    = isset($_POST['fecha']) && $_POST['fecha'] !== ''
                          ? date("Y-m-d", strtotime($_POST['fecha']))
                          : date("Y-m-d");
            $periodo_id = (int)($_POST['periodo_id'] ?? 0);
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
                   AND lp.fecha = ?
                   AND l.fecha_solicitud >= ?
                 LIMIT 1",
                [$student_id, $tipo, $motivo_id, $detalle, $fecha_p, $hace5min]
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

        if ($licencias_id > 0) {
            // Manejo del archivo comprobante médico
            $fileInput = $this->request->getFile('comprobante_medico');
            if ($fileInput && $fileInput->isValid() && !$fileInput->hasMoved()) {
                $extension        = strtolower($fileInput->getClientExtension());
                $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
                $maxSize          = 5 * 1024 * 1024; // 5 MB
                $uploadDir        = FCPATH . 'uploads/comprobantes_medicos';

                if (!in_array($extension, $allowedExtensions)) {
                    $session->set('flash_message_error', 'Formato de comprobante no permitido. Use PDF, JPG o PNG.');
                } elseif ($fileInput->getSize() > $maxSize) {
                    $session->set('flash_message_error', 'El comprobante supera el tamaño máximo de 5 MB.');
                } else {
                    // Crear directorio si no existe
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $newName = 'comprobante_' . $licencias_id . '.' . $extension;

                    if ($fileInput->move($uploadDir, $newName)) {
                        $Licencia->updateLicencia(['comprobante_medico' => $newName], $licencias_id);
                        $session->set('flash_message', 'Se guardó la licencia con comprobante correctamente.');
                    } else {
                        $session->set('flash_message_error', 'La licencia se guardó pero no se pudo subir el comprobante.');
                    }
                }
            } else {
                $session->set('flash_message', 'Se guardó la licencia correctamente.');
            }
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

    /****ACTUALIZAR PERFIL****/
    public function profile_update()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $parent_id = $this->request->getPost('parent_id');

        $datos = [
            'name'               => $this->request->getPost('name'),
            'lastname1'          => $this->request->getPost('lastname1'),
            'lastname2'          => $this->request->getPost('lastname2'),
            'birthday'           => $this->request->getPost('birthday')    ?: null,
            'place_birth'        => $this->request->getPost('place_birth'),
            'card'               => $this->request->getPost('card'),
            'phone'              => $this->request->getPost('phone'),
            'cellphone'          => $this->request->getPost('cellphone'),
            'workphone'          => $this->request->getPost('workphone'),
            'email'              => $this->request->getPost('email'),
            'personal_email'     => $this->request->getPost('personal_email'),
            'profession'         => $this->request->getPost('profession'),
            'occupation'         => $this->request->getPost('occupation'),
            'business'           => $this->request->getPost('business'),
            'idiom'              => $this->request->getPost('idiom'),
            'address'            => $this->request->getPost('address'),
            'reference'          => $this->request->getPost('reference'),
        ];

        $ParentMod = new \App\Models\ParentModel();
        $ParentMod->update_parent($datos, $parent_id);

        $session->set('flash_message', 'Datos actualizados correctamente.');
        return redirect()->to(base_url() . 'parents/profile');
    }

    /****LICENCIAS PRIMARIA ****/
    public function prim_licencias()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $family_id = $session->get('family_id');
        $Setting   = new SettingModel();

        $page_data['phase_id']     = $Setting->get_phase_id();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();

        // Hijos de la familia que sean primaria 3ro-6to
        $all_children  = (new StudentModel())->students_family($family_id);
        $children_prim = array_values(array_filter($all_children, fn($c) => isPrimaria36($c['grade'])));
        $page_data['children_prim'] = $children_prim;

        // Alumno seleccionado (GET param o primero por defecto)
        $selected_id = (int)($this->request->getGet('student_id') ?? 0);
        $selected    = null;
        foreach ($children_prim as $c) {
            if ($c['student_id'] == $selected_id) { $selected = $c; break; }
        }
        if (!$selected && !empty($children_prim)) {
            $selected    = $children_prim[0];
            $selected_id = $selected['student_id'];
        }
        $page_data['selected']    = $selected;
        $page_data['selected_id'] = $selected_id;

        // Historial de licencias del alumno seleccionado
        $page_data['licencias'] = $selected
            ? (new \App\Models\PrimLicenciaModel())->licenciasStudent($selected_id)
            : [];

        // Historial de avisos de cambio de recojo del alumno seleccionado
        $page_data['historial_recojo'] = $selected
            ? (new \App\Models\PrimCambioRecojoModel())->listarPorEstudiante($selected_id)
            : [];

        // Datos para los formularios
        $page_data['parentescos']       = (new \App\Models\ParentescoModel())->listarPadreMadre();
        $page_data['parentescos_todos'] = (new \App\Models\ParentescoModel())->listarParentescos();
        $page_data['motivos']           = (new \App\Models\MotivoModel())->listarMotivos();
        $page_data['family_id']         = $family_id;

        $page_data['account_type'] = 'parents';
        $page_data['page_name']    = 'prim_licencias';
        $page_data['page_title']   = 'Licencias Primaria';
        return view('backend/index', $page_data);
    }

    public function prim_cupo_estudiante()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return $this->response->setStatusCode(403);

        $student_id = (int)$this->request->getPost('student_id');
        $family_id  = $session->get('family_id');

        $ok = \Config\Database::connect('asistencia')
            ->query("SELECT 1 FROM t_student WHERE student_id = ? AND family_id = ?", [$student_id, $family_id])
            ->getRowArray();
        if (!$ok) return $this->response->setStatusCode(403);

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phaseRow = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$phase_id])
            ->getRowArray();

        if (!$phaseRow) return $this->response->setJSON(['error' => 'Sin fase activa']);

        $cupo = (new \App\Models\PrimCupoModel())->calcularCupo($student_id, $phaseRow['inicio'], $phaseRow['fin']);
        return $this->response->setJSON($cupo);
    }

    // -------------------------------------------------------------------------
    // CAMBIO DE RECOJO — Primaria 3ro-6to
    // -------------------------------------------------------------------------

    public function prim_cambio_recojo_create()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $family_id  = $session->get('family_id');
        $student_id = (int)$this->request->getPost('student_id');

        // Verificar que el alumno pertenece a esta familia y es primaria 3ro-6to
        $students = (new StudentModel())->students_family($family_id);
        $student  = null;
        foreach ($students as $s) {
            if ((int)$s['student_id'] === $student_id) { $student = $s; break; }
        }
        if (!$student || !isPrimaria36($student['grade'])) {
            return redirect()->to(base_url('parents/enrolled_children'));
        }

        $redirectBack = base_url('parents/prim_licencias?student_id=' . $student_id . '&form=rec');

        // Hora de cierre (solo padres): cambio de recojo es siempre para hoy.
        $horaCierre = (new \Config\PrimReglas())->horaCierre;
        if (date('H:i') > $horaCierre) {
            $horaCierreTexto = date('g:i a', strtotime($horaCierre));
            $session->set('flash_message_error', '⚠️ El horario para avisar un cambio de recojo cerró a las ' . $horaCierreTexto . '. Por favor comuníquese con la secretaría de su nivel para coordinar.');
            return redirect()->to($redirectBack);
        }

        $tipo                   = (int)$this->request->getPost('tipo');
        $solicitante            = trim($this->request->getPost('parent_text') ?? '');
        $parentesco_id          = (int)$this->request->getPost('parents');
        $persona_nombre         = trim($this->request->getPost('persona_nombre') ?? '');
        $persona_parentesco_id  = (int)($this->request->getPost('persona_parentesco_id') ?? 0);
        $persona_parentesco_otro= trim($this->request->getPost('persona_parentesco_otro') ?? '');
        $detalle                = trim($this->request->getPost('detalle') ?? '');

        if (!in_array($tipo, [1, 2, 3], true) || $solicitante === '' || !$parentesco_id) {
            $session->set('flash_message_error', 'Complete todos los campos requeridos.');
            return redirect()->to($redirectBack);
        }
        if ($tipo === 1 && ($persona_nombre === '' || (!$persona_parentesco_id && $persona_parentesco_otro === ''))) {
            $session->set('flash_message_error', 'Indique el nombre y parentesco de la persona que recogerá al estudiante.');
            return redirect()->to($redirectBack);
        }
        if ($tipo === 3 && $detalle === '') {
            $session->set('flash_message_error', 'Describa el motivo del cambio.');
            return redirect()->to($redirectBack);
        }

        (new \App\Models\PrimCambioRecojoModel())->crear([
            'student_id'             => $student_id,
            'fecha'                  => date('Y-m-d'),
            'tipo'                   => $tipo,
            'solicitante'            => $solicitante,
            'parentesco_id'          => $parentesco_id,
            'persona_nombre'         => $tipo === 1 ? $persona_nombre : null,
            'persona_parentesco_id'  => ($tipo === 1 && $persona_parentesco_id) ? $persona_parentesco_id : null,
            'persona_parentesco_otro'=> ($tipo === 1 && !$persona_parentesco_id && $persona_parentesco_otro !== '') ? $persona_parentesco_otro : null,
            'detalle'                => $detalle !== '' ? $detalle : null,
            'enviado'                => 0,
            'fecha_solicitud'        => date('Y-m-d H:i:s'),
        ]);

        $session->set('flash_message', 'Aviso de cambio de recojo registrado. Queda pendiente de aprobación por secretaría.');
        return redirect()->to($redirectBack);
    }

    /****ACTUALIZAR CONTRASEÑA****/
    public function password_update()
    {
        $session = session();
        if ($session->get('login_type') != 'parents')
            return redirect()->to(base_url());

        $parent_id   = $this->request->getPost('parent_id');
        $old_password = $this->request->getPost('old_password');
        $new_password = $this->request->getPost('new_password');
        $confirm      = $this->request->getPost('confirm_password');

        $ParentMod = new \App\Models\ParentModel();
        $parent    = $ParentMod->get_parent(['parent_id' => $parent_id]);

        if (empty($parent)) {
            $session->set('flash_message_error', 'Padre no encontrado.');
            return redirect()->to(base_url() . 'parents/profile');
        }

        if ($parent[0]['password'] !== md5($old_password)) {
            $session->set('flash_message_error', 'La contraseña actual es incorrecta.');
            return redirect()->to(base_url() . 'parents/profile');
        }

        if ($new_password !== $confirm) {
            $session->set('flash_message_error', 'Las contraseñas nuevas no coinciden.');
            return redirect()->to(base_url() . 'parents/profile');
        }

        if (strlen($new_password) < 4) {
            $session->set('flash_message_error', 'La contraseña debe tener al menos 4 caracteres.');
            return redirect()->to(base_url() . 'parents/profile');
        }

        $ParentMod->update_parent([
            'password' => md5($new_password),
            'code'     => $new_password,
        ], $parent_id);

        $session->set('flash_message', 'Contraseña actualizada correctamente.');
        return redirect()->to(base_url() . 'parents/dashboard');
    }

}
