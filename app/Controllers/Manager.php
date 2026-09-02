<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\FamilyModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\SectionModel;
use App\Models\AssistanceobsModel;
use App\Models\MedioModel;
use App\Models\ParentescoModel;
use App\Models\MotivoModel;
use App\Models\LicenciaModel;
use App\Models\AbsenceModel;
use App\Models\EmailModel;
use App\Models\AssistancesubjectModel;
use App\Models\DelayModel;
use App\Models\IinfractionModel;
use App\Models\DatesModel;
use App\Models\ParentModel;
use App\Models\CsamarksModel;
use App\Models\CsamarksdetailsModel;
use App\Models\SubjectModel;
use App\Models\EcategoriaModel;
use App\Models\EsintomaModel;
use App\Models\EmedicamentoModel;
use App\Models\EhcModel;
use App\Models\EtipodatomedicoModel;
use App\Models\EdatomedicoModel;
use App\Models\ManagerModel;
use App\Models\SelfappraisalModel;
use App\Models\EvaluationModel;
use App\Models\BehaviorsModel;

class Manager extends BaseController
{
    public function index()
    {
        //
    }
    public function dashboard()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $StudentMod = new StudentModel();
        $FamilyMod = new FamilyModel();
        $EhcMod = new EhcModel();
        $DelayMod = new DelayModel();
        $DatesMod = new DatesModel();

        $today = date('Y-m-d');
        $date_data = $DatesMod->get_attendance_dates(['date_class' => $today]);
        $delays_count = 0;
        if (!empty($date_data)) {
            $delays = $DelayMod->get_delay(['date_id' => $date_data[0]['date_id']]);
            $delays_count = count($delays);
        }

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Dashboard";
        $page_data['page_name'] = "dashboard";

        // Statistics
        $page_data['total_students'] = count($StudentMod->activesStudent());
        $page_data['total_families'] = count($FamilyMod->activesFamily());
        $page_data['total_ehcs'] = count($EhcMod->listarEhcs());

        // Total incidencias negativas para el card del dashboard
        $db_inc = \Config\Database::connect('default');
        $inc_count = $db_inc->query("
            SELECT COUNT(bl.id) AS total
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id
            WHERE bt.type = 'negative'
        ")->getRowArray();
        $page_data['total_incidencias'] = $inc_count['total'] ?? 0;
        $page_data['today_delays'] = $delays_count;

        // Ausentes de hoy
        $db = \Config\Database::connect('default');
        $page_data['top_ausencias'] = $db->query("
            SELECT TRIM(CONCAT(s.name, ' ', IFNULL(s.lastname, ''))) AS alumno,
                   sec.grade AS grado, sec.name AS seccion,
                   COUNT(ast.assistance_subject_id) AS total_ausencias,
                   COUNT(DISTINCT ast.subject_id)   AS materias_afectadas
            FROM tiqui0_tiquiasis26.assistance_subject ast
            JOIN tiqui0_tiquiasis26.attendance_dates   ad  ON ast.date_id    = ad.date_id
            JOIN tiqui0_tiquiasis26.t_student          s   ON ast.student_id = s.student_id
            JOIN tiqui0_tiquiasis26.section            sec ON s.section_id   = sec.section_id
            WHERE ast.status = 0 AND ad.date_class = CURDATE()
            GROUP BY ast.student_id
            HAVING total_ausencias > 0
            ORDER BY sec.grade, s.lastname
        ")->getResultArray();

        // Licencias activas hoy
        $db = \Config\Database::connect('default');
        /*
        $page_data['today_licenses'] = $db->query("
            SELECT l.licencias_id,
                   TRIM(CONCAT(s.name, ' ', IFNULL(s.lastname, ''))) AS alumno,
                   sec.name   AS seccion,
                   sec.grade  AS grado,
                   tl.tipo    AS tipo,
                   tm.motivo  AS motivo,
                   l.fecha_inicio, l.fecha_fin,
                   l.hora_inicio,  l.hora_fin,
                   l.cantidad_dias, l.solicitante, l.detalle
            FROM tiqui0_tiquiasis26.t_licencias l
            JOIN tiqui0_tiquiasis26.t_student      s  ON l.student_id  = s.student_id
            JOIN tiqui0_tiquiasis26.section        sec ON s.section_id  = sec.section_id
            JOIN tiqui0_tiquiasis26.t_tipo_licencia tl ON l.tipo_id     = tl.tipo_id
            JOIN tiqui0_tiquiasis26.t_motivos       tm ON l.motivo_id   = tm.motivo_id
            WHERE (
                (l.tipo_id = 1 AND CURDATE() BETWEEN l.fecha_inicio AND l.fecha_fin)
                OR
                (l.tipo_id = 2 AND DATE(l.fecha_solicitud) = CURDATE())
            )
            ORDER BY l.fecha_solicitud DESC
        ")->getResultArray();
        */
        try {
            $page_data['today_licenses'] = $db->query("
                SELECT
                    l.licencias_id,
                    TRIM(CONCAT(s.name, ' ', IFNULL(s.lastname, ''))) AS alumno,
                    sec.name   AS seccion,
                    sec.grade  AS grado,
                    tl.tipo    AS tipo,
                    tm.motivo  AS motivo,
                    ld.fecha_inicio,
                    ld.fecha_fin,
                    ld.cantidad_dias,
                    l.solicitante,
                    l.detalle
                FROM tiqui0_tiquiasis26.t_licencias l
                JOIN tiqui0_tiquiasis26.t_licencias_dia ld
                    ON ld.licencias_id = l.licencias_id
                JOIN tiqui0_tiquiasis26.t_student s
                    ON l.student_id = s.student_id
                JOIN tiqui0_tiquiasis26.section sec
                    ON s.section_id = sec.section_id
                JOIN tiqui0_tiquiasis26.t_tipo_licencia tl
                    ON l.tipo_id = tl.tipo_id
                JOIN tiqui0_tiquiasis26.t_motivos tm
                    ON l.motivo_id = tm.motivo_id
                WHERE CURDATE() BETWEEN ld.fecha_inicio AND ld.fecha_fin
                ORDER BY l.fecha_solicitud DESC
            ")->getResultArray();
        } catch (\Exception $e) {
            $page_data['today_licenses'] = [];
        }

        return view('backend/index', $page_data);
    }
    /****Biblioteca Virtual****/
    function virtual_library_prim()
    {
        $session = session();
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

    public function cartas_contenido()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $phase_id;
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = 'Cartas de Contenido — Secundaria';
        $page_data['page_name'] = 'cartas_contenido';

        $db = \Config\Database::connect('default');
        $rows = $db->query("
            SELECT sub.subject_id, sub.name AS materia,
                   t.teacher_id, t.name AS docente, t.personal_email,
                   sec.name AS seccion, sec.grade AS grado,
                   c.class_id, c.name AS clase
            FROM tiqui0_tiquisaat26.subject sub
            JOIN tiqui0_tiquisaat26.section sec ON sub.section_id = sec.section_id
            JOIN tiqui0_tiquisaat26.teacher t   ON sub.teacher_id = t.teacher_id
            JOIN tiqui0_tiquiweb26.class c      ON sec.class_id  = c.class_id
            WHERE sec.grade LIKE '%Secundaria%'
            ORDER BY c.class_id, sub.name, t.teacher_id, sec.name
        ")->getResultArray();

        $upload_path = FCPATH . 'uploads/content_letter/';
        $by_grade = [];

        foreach ($rows as $row) {
            $grade = $row['grado'];
            $key   = $row['materia'] . '||' . $row['teacher_id'];
            if (!isset($by_grade[$grade][$key])) {
                $by_grade[$grade][$key] = [
                    'materia'        => $row['materia'],
                    'docente'        => $row['docente'],
                    'teacher_id'     => $row['teacher_id'],
                    'personal_email' => $row['personal_email'],
                    'grado'          => $row['grado'],
                    'class_id'       => $row['class_id'],
                    'secciones'      => [],
                    'trims'          => [1 => null, 2 => null, 3 => null],
                    'has_any_pdf'    => false,
                ];
            }
            // Check T1, T2, T3 for this section's subject
            for ($t = 1; $t <= 3; $t++) {
                $filename = 'CC_' . $row['subject_id'] . '_T' . $t . '.pdf';
                if (file_exists($upload_path . $filename)) {
                    if (!$by_grade[$grade][$key]['trims'][$t]) {
                        $by_grade[$grade][$key]['trims'][$t] = $filename;
                    }
                    $by_grade[$grade][$key]['has_any_pdf'] = true;
                }
            }
            $by_grade[$grade][$key]['secciones'][] = [
                'seccion'    => $row['seccion'],
                'subject_id' => $row['subject_id'],
            ];
        }

        $total_uploaded    = 0;
        $total_pending     = 0;
        $teachers_uploaded = [];
        $teachers_pending  = [];

        foreach ($by_grade as $subjects) {
            foreach ($subjects as $sub) {
                if ($sub['has_any_pdf']) {
                    $total_uploaded++;
                    $teachers_uploaded[$sub['teacher_id']] = $sub['docente'];
                } else {
                    $total_pending++;
                    $teachers_pending[$sub['teacher_id']] = $sub['docente'];
                }
            }
        }

        $page_data['by_grade']          = $by_grade;
        $page_data['total_uploaded']     = $total_uploaded;
        $page_data['total_pending']      = $total_pending;
        $page_data['teachers_uploaded']  = $teachers_uploaded;
        $page_data['teachers_pending']   = $teachers_pending;

        return view('backend/index', $page_data);
    }
    /**********************************BEGIN BUSQUEDAS *********************/
    function student_search($user = '', $sel = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Buscamos estudiantes
        $page_data['user'] = $user;
        $page_data['busqueda'] = '';
        if ($sel <> '0') {
            $StudentMod = new StudentModel();
            $students = $StudentMod->students_user($user, $sel, 0);
            $page_data['resultado'] = $students;
            $page_data['busqueda'] = $sel;
        }
        //Vista
        $page_data['page_name'] = 'student_search';
        $page_data['page_title'] = 'Registrar Datos';
        return view('backend/index', $page_data);
    }
    function family_search($user = '', $sel = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Buscamos estudiantes
        $page_data['user'] = $user;
        $page_data['busqueda'] = '';
        if ($sel <> '0') {
            $FamilyMod = new FamilyModel();
            $familys = $FamilyMod->family_user($sel);
            $page_data['resultado'] = $familys;
            $page_data['busqueda'] = $sel;
            $StudentMod = new StudentModel();
            $students = $StudentMod->students_family_user($sel);
            $page_data['students'] = $students;
        }
        //Vista
        $page_data['page_name'] = 'family_search';
        $page_data['page_title'] = 'Buscar Familias';
        return view('backend/index', $page_data);
    }
    public function delay_score()
    {
        $session = session();
        if ($session->get('login_type') != 'manager') {
            return redirect()->to(base_url());
        }

        // Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        // Fetch students with delays
        $DelayModel = new DelayModel();
        $page_data['students_with_delays'] = $DelayModel->get_students_with_delays();

        // Set view properties
        $page_data['page_name'] = 'delay_score';
        $page_data['page_title'] = 'Retrasos de Estudiantes';

        return view('backend/index', $page_data);
    }

    public function family_info($family_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        //Familia
        $data = ["family_id" => $family_id];
        $FamilyMod = new FamilyModel();
        $family = $FamilyMod->get_family_datas($data);
        $page_data['fam'] = $family[0];
        //HIJOS
        $StudentMod = new StudentModel();
        $students = $StudentMod->students_family($family_id);
        $page_data['students'] = $students;
        //PARENTS
        $ParentMod = new ParentModel();
        $parents = $ParentMod->get_parent_info($family_id);
        $page_data['parents'] = $parents;

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Información de Familia";
        $page_data['page_name'] = "family_info";
        return view('backend/index', $page_data);
    }
    /**********************************END BUSQUEDAS ***********************/
    /*********************************OPCIONES ESTUDIANTE ******************/
    public function student_all()
    {
        $session = \Config\Services::session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        if ($this->request->isAJAX()) {
            $StudentMod = new StudentModel();
            $data['data'] = $StudentMod->activesStudent();

            return $this->response->setJSON($data);
        }
    }
    function student_attendance($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
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
        $page_data['page_name'] = 'student_attendance';
        $page_data['page_title'] = 'Asistencias';
        return view('backend/index', $page_data);
    }
    function student_licenses($student_id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
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
        $page_data['page_name'] = 'student_licenses';
        $page_data['page_title'] = 'Reporte de Licencias';
        return view('backend/index', $page_data);
    }
    function student_infractions($student_id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
        //Faltas Leves
        $IinfractionMod = new IinfractionModel();
        $infractions = $IinfractionMod->infractions_student($student_id);
        $page_data['infractions'] = $infractions;
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'student_infractions';
        $page_data['page_title'] = 'Reporte de Faltas Leves';
        return view('backend/index', $page_data);
    }
    public function student_absences($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
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
        $page_data['page_title'] = "Ausencias del Estudiante";
        $page_data['page_name'] = "student_absences";
        return view('backend/index', $page_data);
    }
    public function student_delays($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
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
        $page_data['page_title'] = "Retrasos del Estudiante";
        $page_data['page_name'] = "student_delays";
        return view('backend/index', $page_data);
    }
    /*********************************OPCIONES ESTUDIANTE ******************/
    /**********************************BEGIN RETRASOS***********************/
    public function delays($student_id = '')
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        //$Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_manager($manager_id);
        $page_data['students'] = $students;
        $page_data['student_id'] = $student_id;


        $Setting = new SettingModel();
        //$page_data['mensaje'] = $mensaje;
        $page_data['entry_time'] = $Setting->get_entry_time();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Retrasos";
        $page_data['page_name'] = "delays";
        return view('backend/index', $page_data);
    }
    public function delay_create()
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        //Date
        $date_id = 0;
        $data = ["date_class" => $_POST['fechaRetraso']];
        $DatesMod = new DatesModel();
        $respuesta = $DatesMod->get_attendance_dates($data);
        if (count($respuesta) >= 1) {
            $date_id = $respuesta[0]['date_id'];
        } else {
            $Setting = new SettingModel();
            $phase_id = $Setting->get_phase_id();
            $datos = [
                "date_class" => $_POST['fechaRetraso'],
                "phase_id" => $phase_id,
            ];
            $respuesta = $DatesMod->insert_attendance_dates($datos);
            $date_id = $respuesta;
        }

        $datos = [
            "student_id" => $_POST['student_id'],
            "date_id" => $date_id,
            "hora_ingreso" => $_POST['horaIngreso'],
            "hora_llegada" => $_POST['horaLlegada'],
            "estado" => '1',
            "motivo" => $_POST['motivo'],
        ];
        $DelayMod = new DelayModel();
        $respuesta = $DelayMod->insert_delay($datos);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó el retraso correctamente');
            return redirect()->to(base_url() . 'manager/delays/' . $_POST['student_id']);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/delays/' . $_POST['student_id']);
        }
    }
    public function delay_get($delay_id)
    {
        $DelayMod = new DelayModel();
        $respuesta = $DelayMod->getDelay($delay_id);
        //return $respuesta[0]['nick_name'].' - '.$respuesta[0]['student'].' - '.$respuesta[0]['detalle'];
        return json_encode($respuesta);
    }
    public function delay_delete()
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $delay_id = $_POST['delay_id'];
        $DelayMod = new DelayModel();
        $data = ["delay_id" => $delay_id];
        $respuesta = $DelayMod->delete_delay($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Retraso eliminado.');
            return redirect()->to(base_url() . 'manager/delays/' . $_POST['student_id']);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/delays/' . $_POST['student_id']);
        }
    }
    public function delay_update()
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $date_id = 0;
        $data = ["date_class" => $_POST['fechaRetraso']];
        $DatesMod = new DatesModel();
        $respuesta = $DatesMod->get_attendance_dates($data);
        if (count($respuesta) == 1) {
            $date_id = $respuesta[0]['date_id'];
        } else {
            $Setting = new SettingModel();
            $phase_id = $Setting->get_phase_id();
            $datos = [
                "date_class" => $_POST['fechaRetraso'],
                "phase_id" => $phase_id,
            ];
            $respuesta = $DatesMod->insert_attendance_dates($datos);
            $date_id = $respuesta;
        }
        $datos = [
            "date_id" => $date_id,
            "hora_ingreso" => $_POST['horaIngreso'],
            "hora_llegada" => $_POST['horaLlegada'],
            "motivo" => $_POST['motivo'],
        ];
        $DelayMod = new DelayModel();
        $respuesta = $DelayMod->update_delay($datos, $_POST['delay_id']);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó el retraso Correctamente');
            return redirect()->to(base_url() . 'manager/delays/' . $_POST['student_id']);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/delays/' . $_POST['student_id']);
        }
    }
    public function delay_xlsx($student_id = '', $phase_id = '')
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        //Retrasos
        $DelayMod = new DelayModel();
        $delays = $DelayMod->delay_student($student_id, $phase_id);
        //Instanciamos la libreria EXCEL y Abrimos el Template
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        //**************ABRIMOS EXCEL DE ACUERDO A EL CURSO QUE CORRESPONDE
        $obj_PHPExcel = $obj_Reader->load('templates/delays_student.xlsx');
        //ESTUDIANTE
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $student_name = $students[0]->nombre;
        $curso = $students[0]->completo;
        //FASES
        $titulo = "";
        switch ($phase_id) {
            case 0:
                $titulo = "RETRASOS AL INGRESO DEL ESTUDIANTE 2024 - ANUAL";
                break;
            case 1:
                $titulo = "RETRASOS AL INGRESO DEL ESTUDIANTE 2024 - 1er TRIM";
                break;
            case 2:
                $titulo = "RETRASOS AL INGRESO DEL ESTUDIANTE 2024 - 2do TRIM";
                break;
            case 3:
                $titulo = "RETRASOS AL INGRESO DEL ESTUDIANTE 2024 - 3er TRIM";
                break;
        }
        //Escribimos ENCABEZADO en el EXCEL
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A3', date("d/m/Y"));
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A4', $student_name);
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A5', $curso);
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A2', $titulo);



        $conter = 7;
        foreach ($delays as $del):
            if ($conter == 7) {

            }
            //$fecha = date("d/m/Y", strtotime($del['date']));
            $obj_PHPExcel->getActiveSheet()->SetCellValue('A' . $conter, $conter - 6);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $del['date_class']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('C' . $conter, $del['hora_ingreso']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('D' . $conter, $del['hora_llegada']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('E' . $conter, $del['tarde_con']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('F' . $conter, $del['motivo']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('G' . $conter, $del['estado']);
            $conter++;
            if ($conter == 14) {
                break;
            }
        endforeach;

        //Section

        $fileName = 'Rts_' . $student_name . '.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);

    }
    public function delays_day_new()
    {
        $Setting = new SettingModel();
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Retrasos del día";
        $page_data['page_name'] = "delays_day_new";
        $page_data['delays'] = []; // Por defecto, vacío.
        $page_data['date'] = null;

        // Verifica si se está enviando una fecha por POST.
        if ($this->request->getMethod() === 'post') {
            $data = ["date_class" => $this->request->getPost('fechaRetraso')];
            $DatesMod = new DatesModel();
            $respuesta = $DatesMod->get_attendance_dates($data);

            if (count($respuesta) >= 1) {
                $page_data['date_id'] = $respuesta[0]['date_id'];
                $page_data['date'] = $respuesta[0]['date_class'];
            } else {
                $page_data['date_id'] = 0;
                $page_data['date'] = $this->request->getPost('fechaRetraso');
            }

            $DelayMod = new DelayModel();
            $page_data['delays'] = $DelayMod->delays_date($page_data['date_id']);
        }

        return view('backend/index', $page_data);
    }

    /**********************************END RETRASOS***********************/
    function notes_half_student($student_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('adviser'))
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
        $page_data['student_id'] = $student_id;
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
        //Notas
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_half_student($student_id, $page_data['phase_id']);
        $page_data['csamarks'] = $csamarks;
        //Vista
        $page_data['page_name'] = 'notes_half_student';
        $page_data['page_title'] = 'Medio Trimestre';
        return view('backend/index', $page_data);
    }
    function student_notes($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        //Configuraciones
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Datos estudiantes
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;



        //Enviamos todas las Materias
        $Subject = new SubjectModel();
        $subjects = $Subject->subjects_student($students[0]->section_id, $students[0]->sex);
        $page_data['subjects'] = $subjects;
        //Detalles
        $CsamarksdetailsMod = new CsamarksdetailsModel();
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim_curso($page_data['phase_id'], "ser", $students[0]->section_id);
        $page_data['details_ser'] = $csamarksdetails;
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim_curso($page_data['phase_id'], "saber", $students[0]->section_id);
        $page_data['details_saber'] = $csamarksdetails;
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim_curso($page_data['phase_id'], "hacer", $students[0]->section_id);
        $page_data['details_hacer'] = $csamarksdetails;
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim_curso($page_data['phase_id'], "decidir", $students[0]->section_id);
        $page_data['details_decidir'] = $csamarksdetails;
        //Enviamos las Notas
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_student($student_id, $page_data['phase_id']);
        $page_data['csamarks'] = $csamarks;

        //VISTA REPOR CARD
        $page_data['page_name'] = 'student_notes';
        $page_data['page_title'] = 'Notas del Estudiante';
        return view('backend/index', $page_data);
    }
    /**********************************ENFERMERIA CATEGORIA ***********************/
    public function ecategoria()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Ecategoria = new EcategoriaModel();
        $datos = $Ecategoria->listarEcategorias();

        $page_data = ["datos" => $datos];

        $Setting = new SettingModel();
        $mensaje = session('mensaje');
        $page_data['mensaje'] = $mensaje;
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Ecategorias";
        $page_data['page_name'] = "ecategoria";
        return view('backend/index', $page_data);
    }
    public function ecategoria_create()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = ["nombre" => $_POST['nombre'],];
        $Ecategoria = new EcategoriaModel();
        $respuesta = $Ecategoria->insertEcategoria($datos);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó la categoria de síntoma Correctamente');
            return redirect()->to(base_url() . '/manager/ecategoria');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/ecategoria');
        }
    }
    public function ecategoria_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $data = ["id" => $id];
        $Ecategoria = new EcategoriaModel();
        $respuesta = $Ecategoria->getEcategoria($data);
        return $respuesta[0]['nombre'];
        //return print_r($respuesta);
    }
    public function ecategoria_all()
    {
        $session = \Config\Services::session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        if ($this->request->isAJAX()) {
            $categoriaModel = new EcategoriaModel();
            $data['data'] = $categoriaModel->listarEcategorias();

            return $this->response->setJSON($data);
        }
    }
    public function ecategoria_update()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = [
            "nombre" => $_POST['nombre'],
        ];
        $id = $_POST['id'];
        $Ecategoria = new EcategoriaModel();
        $respuesta = $Ecategoria->updateEcategoria($datos, $id);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Ecategoria de Comunicacion actualizado Correctamente');
            return redirect()->to(base_url() . 'manager/ecategoria');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/ecategoria');
        }
    }
    public function ecategoria_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $id = $_POST['id'];
        $Ecategoria = new EcategoriaModel();
        $data = ["id" => $id];
        $respuesta = $Ecategoria->deleteEcategoria($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se elimino la ecategoria de csímtoma Correctamente');
            return redirect()->to(base_url() . 'manager/ecategoria');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/ecategoria');
        }
    }
    /**********************************ENFERMERIA SINTOMAS ***********************/
    public function esintoma()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $EsintomaMod = new EsintomaModel();
        $datos = $EsintomaMod->listarEsintomas();

        $page_data = ["datos" => $datos];

        $Setting = new SettingModel();
        $mensaje = session('mensaje');
        $page_data['mensaje'] = $mensaje;
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Síntomas";
        $page_data['page_name'] = "esintoma";
        return view('backend/index', $page_data);
    }
    public function esintoma_create()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = ["nombre" => $_POST['nombre'], "categoria_id" => $_POST['categoria'],];
        $EsintomaMod = new EsintomaModel();
        $respuesta = $EsintomaMod->insertEsintoma($datos);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó el síntoma Correctamente');
            return redirect()->to(base_url() . 'manager/esintoma');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/esintoma');
        }
    }
    public function esintoma_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $data = ["id" => $id];
        $EsintomaMod = new EsintomaModel();
        $respuesta = $EsintomaMod->getEsintoma($data);
        // Asegúrate de devolver los datos como JSON
        return $this->response->setJSON($respuesta[0]);
        //return print_r($respuesta);
    }
    public function esintoma_all()
    {
        $session = \Config\Services::session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        if ($this->request->isAJAX()) {
            $EsintomaMod = new EsintomaModel();
            $data['data'] = $EsintomaMod->listarEsintomas();

            return $this->response->setJSON($data);
        }
    }
    public function esintoma_update()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = [
            "nombre" => $_POST['nombre'],
            "categoria_id" => $_POST['categoria'],
        ];
        $id = $_POST['id'];
        $EsintomaMod = new EsintomaModel();
        $respuesta = $EsintomaMod->updateEsintoma($datos, $id);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Síntoma actualizado Correctamente');
            return redirect()->to(base_url() . 'manager/esintoma');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/esintoma');
        }
    }
    public function esintoma_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $id = $_POST['id'];
        $EsintomaMod = new EsintomaModel();
        $data = ["id" => $id];
        $respuesta = $EsintomaMod->deleteEsintoma($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se elimino el Síntoma Correctamente');
            return redirect()->to(base_url() . 'manager/esintoma');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/esintoma');
        }
    }
    /**********************************ENFERMERIA MEDICAMENTOS ***********************/
    public function emedicamento()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Emedicamento = new EmedicamentoModel();
        $datos = $Emedicamento->listarEmedicamentos();

        $page_data = ["datos" => $datos];

        $Setting = new SettingModel();
        $mensaje = session('mensaje');
        $page_data['mensaje'] = $mensaje;
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Emedicamentos";
        $page_data['page_name'] = "emedicamento";
        return view('backend/index', $page_data);
    }
    public function emedicamento_create()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = ["nombre" => $_POST['nombre'],];
        $Emedicamento = new EmedicamentoModel();
        $respuesta = $Emedicamento->insertEmedicamento($datos);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó la medicamento de síntoma Correctamente');
            return redirect()->to(base_url() . '/manager/emedicamento');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/emedicamento');
        }
    }
    public function emedicamento_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $data = ["id" => $id];
        $Emedicamento = new EmedicamentoModel();
        $respuesta = $Emedicamento->getEmedicamento($data);
        return $respuesta[0]['nombre'];
        //return print_r($respuesta);
    }
    public function emedicamento_all()
    {
        $session = \Config\Services::session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        if ($this->request->isAJAX()) {
            $medicamentoModel = new EmedicamentoModel();
            $data['data'] = $medicamentoModel->listarEmedicamentos();

            return $this->response->setJSON($data);
        }
    }
    public function emedicamento_update()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = [
            "nombre" => $_POST['nombre'],
        ];
        $id = $_POST['id'];
        $Emedicamento = new EmedicamentoModel();
        $respuesta = $Emedicamento->updateEmedicamento($datos, $id);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Emedicamento de Comunicacion actualizado Correctamente');
            return redirect()->to(base_url() . 'manager/emedicamento');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/emedicamento');
        }
    }
    public function emedicamento_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $id = $_POST['id'];
        $Emedicamento = new EmedicamentoModel();
        $data = ["id" => $id];
        $respuesta = $Emedicamento->deleteEmedicamento($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se elimino la emedicamento de csímtoma Correctamente');
            return redirect()->to(base_url() . 'manager/emedicamento');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/emedicamento');
        }
    }
    /**********************************ENFERMERIA HISTORIAL CLINICO ***********************/
    public function ehc()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $EhcMod = new EhcModel();
        $datos = $EhcMod->listarEhcs();

        $page_data = ["datos" => $datos];

        $Setting = new SettingModel();
        $mensaje = session('mensaje');
        $page_data['mensaje'] = $mensaje;
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Visitas";
        $page_data['page_name'] = "ehc";
        return view('backend/index', $page_data);
    }
    public function ehc_create()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = [
            "student_id" => $_POST['student'],
            "fecha" => $_POST['fecha'],
            "hora_ingreso" => $_POST['hora_ingreso'],
            "hora_salida" => $_POST['hora_salida'],
            "sintoma_id" => $_POST['sintoma'],
            "medicamento_id" => $_POST['medicamento'],
        ];
        $EhcMod = new EhcModel();
        $respuesta = $EhcMod->insertEhc($datos);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó los datos Correctamente');
            return redirect()->to(base_url() . 'manager/ehc');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/ehc');
        }
    }



    public function ehc_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $data = ["id" => $id];
        $EhcMod = new EhcModel();
        $respuesta = $EhcMod->getEhc($data);
        // Asegúrate de devolver los datos como JSON
        return $this->response->setJSON($respuesta[0]);
        //return print_r($respuesta);
    }
    public function ehc_all()
    {
        $session = \Config\Services::session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        if ($this->request->isAJAX()) {
            $EhcMod = new EhcModel();
            $data['data'] = $EhcMod->listarEhcs();

            return $this->response->setJSON($data);
        }
    }
    public function ehc_update()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = [
            "student_id" => $_POST['student'],
            "fecha" => $_POST['fecha'],
            "hora_ingreso" => $_POST['hora_ingreso'],
            "hora_salida" => $_POST['hora_salida'],
            "sintoma_id" => $_POST['sintoma'],
            "medicamento_id" => $_POST['medicamento'],
        ];
        $id = $_POST['id'];
        $EhcMod = new EhcModel();
        $respuesta = $EhcMod->updateEhc($datos, $id);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Síntoma actualizado Correctamente');
            return redirect()->to(base_url() . 'manager/ehc');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/ehc');
        }
    }
    public function ehc_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $id = $_POST['id'];
        $EhcMod = new EhcModel();
        $data = ["id" => $id];
        $respuesta = $EhcMod->deleteEhc($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se elimino el Síntoma Correctamente');
            return redirect()->to(base_url() . 'manager/ehc');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/ehc');
        }
    }
    /**********************************ENFERMERIA TIPO DATO MEDICO ***********************/
    public function etipodatomedico()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Etipodatomedico = new EtipodatomedicoModel();
        $datos = $Etipodatomedico->listarEtipodatomedicos();

        $page_data = ["datos" => $datos];

        $Setting = new SettingModel();
        $mensaje = session('mensaje');
        $page_data['mensaje'] = $mensaje;
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Etipodatomedicos";
        $page_data['page_name'] = "etipodatomedico";
        return view('backend/index', $page_data);
    }
    public function etipodatomedico_create()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = ["nombre" => $_POST['nombre'],];
        $Etipodatomedico = new EtipodatomedicoModel();
        $respuesta = $Etipodatomedico->insertEtipodatomedico($datos);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó la tipodatomedico de síntoma Correctamente');
            return redirect()->to(base_url() . '/manager/etipodatomedico');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/etipodatomedico');
        }
    }
    public function etipodatomedico_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $data = ["id" => $id];
        $Etipodatomedico = new EtipodatomedicoModel();
        $respuesta = $Etipodatomedico->getEtipodatomedico($data);
        return $respuesta[0]['nombre'];
        //return print_r($respuesta);
    }
    public function etipodatomedico_all()
    {
        $session = \Config\Services::session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        if ($this->request->isAJAX()) {
            $tipodatomedicoModel = new EtipodatomedicoModel();
            $data['data'] = $tipodatomedicoModel->listarEtipodatomedicos();

            return $this->response->setJSON($data);
        }
    }
    public function etipodatomedico_update()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = [
            "nombre" => $_POST['nombre'],
        ];
        $id = $_POST['id'];
        $Etipodatomedico = new EtipodatomedicoModel();
        $respuesta = $Etipodatomedico->updateEtipodatomedico($datos, $id);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Etipodatomedico de Comunicacion actualizado Correctamente');
            return redirect()->to(base_url() . 'manager/etipodatomedico');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/etipodatomedico');
        }
    }
    public function etipodatomedico_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $id = $_POST['id'];
        $Etipodatomedico = new EtipodatomedicoModel();
        $data = ["id" => $id];
        $respuesta = $Etipodatomedico->deleteEtipodatomedico($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se elimino la etipodatomedico de csímtoma Correctamente');
            return redirect()->to(base_url() . 'manager/etipodatomedico');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/etipodatomedico');
        }
    }
    /**********************************ENFERMERIA DATOS MEDICOS ***********************/
    public function edatomedico()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $EdatomedicoMod = new EdatomedicoModel();
        $datos = $EdatomedicoMod->listarEdatomedicos();

        $page_data = ["datos" => $datos];

        $Setting = new SettingModel();
        $mensaje = session('mensaje');
        $page_data['mensaje'] = $mensaje;
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Síntomas";
        $page_data['page_name'] = "edatomedico";
        return view('backend/index', $page_data);
    }
    public function edatomedico_create()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = [
            "student_id" => $_POST['student'],
            "tipo_id" => $_POST['tipo'],
            "descripcion" => $_POST['descripcion'],
        ];
        $EdatomedicoMod = new EdatomedicoModel();
        $respuesta = $EdatomedicoMod->insertEdatomedico($datos);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó el síntoma Correctamente');
            return redirect()->to(base_url() . 'manager/edatomedico');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/edatomedico');
        }
    }
    public function edatomedico_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $data = ["id" => $id];
        $EdatomedicoMod = new EdatomedicoModel();
        $respuesta = $EdatomedicoMod->getEdatomedico($data);
        // Asegúrate de devolver los datos como JSON
        return $this->response->setJSON($respuesta[0]);
        //return print_r($respuesta);
    }
    public function edatomedico_all()
    {
        $session = \Config\Services::session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        if ($this->request->isAJAX()) {
            $EdatomedicoMod = new EdatomedicoModel();
            $data['data'] = $EdatomedicoMod->listarEdatomedicos();

            return $this->response->setJSON($data);
        }
    }
    public function edatomedico_update()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $datos = [
            "student_id" => $_POST['student'],
            "tipo_id" => $_POST['tipo'],
            "descripcion" => $_POST['descripcion'],
        ];
        $id = $_POST['id'];
        $EdatomedicoMod = new EdatomedicoModel();
        $respuesta = $EdatomedicoMod->updateEdatomedico($datos, $id);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Síntoma actualizado Correctamente');
            return redirect()->to(base_url() . 'manager/edatomedico');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/edatomedico');
        }
    }
    public function edatomedico_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $id = $_POST['id'];
        $EdatomedicoMod = new EdatomedicoModel();
        $data = ["id" => $id];
        $respuesta = $EdatomedicoMod->deleteEdatomedico($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se elimino el Síntoma Correctamente');
            return redirect()->to(base_url() . 'manager/edatomedico');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'manager/edatomedico');
        }
    }
    /**************************************** PROFILE ********************* */
    public function profile()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $manager_id = $session->get('manager_id');
        $ManagerMod = new ManagerModel();
        $manager_data = $ManagerMod->get_manager(['manager_id' => $manager_id]);

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Mi Perfil";
        $page_data['page_name'] = "profile";
        $page_data['manager'] = $manager_data[0];

        return view('backend/index', $page_data);
    }

    public function profile_update()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $manager_id = $session->get('manager_id');
        $data = [
            'name' => $this->request->getPost('name'),
            'birthday' => $this->request->getPost('birthday'),
            'religion' => $this->request->getPost('religion'),
            'address' => $this->request->getPost('address'),
            'reference' => $this->request->getPost('reference'),
            'phone' => $this->request->getPost('phone'),
            'cellphone' => $this->request->getPost('cellphone'),
            'personal_email' => $this->request->getPost('personal_email'),
            'email' => $this->request->getPost('email')
        ];

        $ManagerMod = new ManagerModel();
        if ($ManagerMod->update_manager($data, $manager_id)) {
            $session->set('flash_message', 'Perfil actualizado correctamente');
        } else {
            $session->set('flash_message_error', 'Error al actualizar el perfil');
        }

        return redirect()->to(base_url() . 'manager/profile');
    }

    public function password_update()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $manager_id = $session->get('manager_id');
        $old_password = $this->request->getPost('old_password');
        $new_password = $this->request->getPost('new_password');
        $confirm_password = $this->request->getPost('confirm_password');

        $ManagerMod = new ManagerModel();
        $manager = $ManagerMod->get_manager(['manager_id' => $manager_id]);

        if (md5($old_password) === $manager[0]['password']) {
            if ($new_password === $confirm_password) {
                $data = ['password' => md5($new_password)];
                if ($ManagerMod->update_manager($data, $manager_id)) {
                    $session->set('flash_message', 'Contraseña actualizada correctamente');
                } else {
                    $session->set('flash_message_error', 'Error al actualizar la contraseña');
                }
            } else {
                $session->set('flash_message_error', 'Las contraseñas no coinciden');
            }
        } else {
            $session->set('flash_message_error', 'La contraseña actual es incorrecta');
        }

        return redirect()->to(base_url() . 'manager/profile');
    }

    public function students_list()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $manager_id = $session->get('manager_id');
        $ManagerMod = new ManagerModel();
        $manager_data = $ManagerMod->get_manager(['manager_id' => $manager_id]);
        $manager = $manager_data[0];

        $SectionMod = new SectionModel();
        $sections = $SectionMod->sections_range($manager['section_ini'], $manager['section_fin']);

        $StudentMod = new StudentModel();
        $grouped_students = [];

        foreach ($sections as $sec) {
            $students = $StudentMod->student_active($sec['section_id']);
            if (!empty($students)) {
                $grouped_students[$sec['nick_name']] = [
                    'section_id' => $sec['section_id'],
                    'completo' => $sec['completo'],
                    'students' => $students
                ];
            }
        }

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Lista de Estudiantes";
        $page_data['page_name'] = "students_list";
        $page_data['grouped_students'] = $grouped_students;

        return view('backend/index', $page_data);
    }

    public function directivo()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $db = \Config\Database::connect('default');

        // KPIs globales de notas
        $kpi_notas = $db->query("
            SELECT AVG(score) as promedio_global,
              ROUND(SUM(CASE WHEN score >= 51 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as pct_aprobados,
              ROUND(SUM(CASE WHEN score < 51 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as pct_reprobados,
              COUNT(*) as total_notas
            FROM tiqui0_tiquiweb26.daily_scores
        ")->getRowArray();

        // Asistencia global
        $kpi_asistencia = $db->query("
            SELECT COUNT(*) as total,
              SUM(CASE WHEN status=1 THEN 1 ELSE 0 END) as presentes,
              SUM(CASE WHEN status=0 THEN 1 ELSE 0 END) as ausentes,
              ROUND(SUM(CASE WHEN status=1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as pct_asistencia
            FROM tiqui0_tiquiasis26.assistance_subject
        ")->getRowArray();

        // Promedio por nivel educativo
        $promedio_por_nivel = $db->query("
            SELECT c.name as nivel, c.class_id,
              ROUND(AVG(ds.score), 1) as promedio,
              ROUND(SUM(CASE WHEN ds.score >= 51 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as pct_aprobados,
              COUNT(DISTINCT ds.student_id) as alumnos
            FROM tiqui0_tiquiweb26.daily_scores ds
            JOIN tiqui0_tiquisaat26.subject sub ON ds.subject_id = sub.subject_id
            JOIN tiqui0_tiquisaat26.section sec ON sub.section_id = sec.section_id
            JOIN tiqui0_tiquiweb26.class c ON sec.class_id = c.class_id
            GROUP BY c.class_id, c.name
            ORDER BY c.class_id
        ")->getResultArray();

        // Evolución por período
        $evolucion_periodos = $db->query("
            SELECT period, ROUND(AVG(score), 1) as promedio,
              ROUND(SUM(CASE WHEN score >= 51 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as pct_aprobados,
              COUNT(*) as total_notas
            FROM tiqui0_tiquiweb26.daily_scores
            GROUP BY period
            ORDER BY period
        ")->getResultArray();

        // Top 8 materias por promedio
        $top_materias = $db->query("
            SELECT sub.name as materia, ROUND(AVG(ds.score), 1) as promedio, COUNT(*) as total_notas
            FROM tiqui0_tiquiweb26.daily_scores ds
            JOIN tiqui0_tiquisaat26.subject sub ON ds.subject_id = sub.subject_id
            GROUP BY sub.subject_id, sub.name
            HAVING total_notas > 3
            ORDER BY promedio DESC
            LIMIT 8
        ")->getResultArray();

        // Bottom 8 materias por promedio
        $bottom_materias = $db->query("
            SELECT sub.name as materia, ROUND(AVG(ds.score), 1) as promedio, COUNT(*) as total_notas
            FROM tiqui0_tiquiweb26.daily_scores ds
            JOIN tiqui0_tiquisaat26.subject sub ON ds.subject_id = sub.subject_id
            GROUP BY sub.subject_id, sub.name
            HAVING total_notas > 3
            ORDER BY promedio ASC
            LIMIT 8
        ")->getResultArray();

        // Alumnos en riesgo académico
        $alumnos_riesgo = $db->query("
            SELECT CONCAT(TRIM(s.name), ' ', TRIM(IFNULL(s.lastname,''))) as alumno,
              sec.name as seccion, c.name as nivel, ROUND(AVG(ds.score), 1) as promedio,
              COUNT(DISTINCT ds.subject_id) as materias_evaluadas
            FROM tiqui0_tiquiweb26.daily_scores ds
            JOIN tiqui0_tiquiweb26.t_student s ON ds.student_id = s.student_id
            JOIN tiqui0_tiquisaat26.subject sub ON ds.subject_id = sub.subject_id
            JOIN tiqui0_tiquisaat26.section sec ON sub.section_id = sec.section_id
            JOIN tiqui0_tiquiweb26.class c ON sec.class_id = c.class_id
            GROUP BY ds.student_id, s.name, s.lastname, sec.name, c.name
            HAVING promedio < 60
            ORDER BY promedio ASC
            LIMIT 20
        ")->getResultArray();

        // Asistencia por nivel educativo
        $asistencia_por_nivel = $db->query("
            SELECT c.name as nivel, c.class_id,
              COUNT(*) as total,
              SUM(CASE WHEN ast.status=1 THEN 1 ELSE 0 END) as presentes,
              ROUND(SUM(CASE WHEN ast.status=1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as pct
            FROM tiqui0_tiquiasis26.assistance_subject ast
            JOIN tiqui0_tiquiasis26.subject sub ON ast.subject_id = sub.subject_id
            JOIN tiqui0_tiquiasis26.section sec ON sub.section_id = sec.section_id
            JOIN tiqui0_tiquiweb26.class c ON sec.class_id = c.class_id
            GROUP BY c.class_id, c.name
            ORDER BY c.class_id
        ")->getResultArray();

        // Asistencia por mes
        $asistencia_por_mes = $db->query("
            SELECT DATE_FORMAT(ad.date_class, '%b %Y') as mes,
              COUNT(*) as total,
              SUM(CASE WHEN ast.status=1 THEN 1 ELSE 0 END) as presentes,
              ROUND(SUM(CASE WHEN ast.status=1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as pct
            FROM tiqui0_tiquiasis26.assistance_subject ast
            JOIN tiqui0_tiquiasis26.attendance_dates ad ON ast.date_id = ad.date_id
            GROUP BY DATE_FORMAT(ad.date_class, '%Y-%m'), DATE_FORMAT(ad.date_class, '%b %Y')
            ORDER BY DATE_FORMAT(ad.date_class, '%Y-%m')
        ")->getResultArray();

        // Alumnos con ausencias críticas
        $ausencias_criticas = $db->query("
            SELECT CONCAT(TRIM(s.name), ' ', TRIM(IFNULL(s.lastname,''))) as alumno,
              sec.name as seccion, c.name as nivel,
              SUM(IFNULL(ta.cantidad, 1)) as total_ausencias
            FROM tiqui0_tiquiasis26.t_ausencias ta
            JOIN tiqui0_tiquiasis26.t_student s ON ta.student_id = s.student_id
            JOIN tiqui0_tiquiasis26.subject sub ON ta.subject_id = sub.subject_id
            JOIN tiqui0_tiquiasis26.section sec ON sub.section_id = sec.section_id
            JOIN tiqui0_tiquiweb26.class c ON sec.class_id = c.class_id
            GROUP BY ta.student_id, s.name, s.lastname, sec.name, c.name
            HAVING total_ausencias >= 3
            ORDER BY total_ausencias DESC
            LIMIT 20
        ")->getResultArray();

        // Carga docente
        $carga_docente = $db->query("
            SELECT t.name as docente,
              COUNT(DISTINCT sub.subject_id) as total_materias,
              COUNT(DISTINCT sec.class_id) as niveles,
              SUM(COALESCE(sub.hours, 0)) as total_horas
            FROM tiqui0_tiquisaat26.teacher t
            JOIN tiqui0_tiquisaat26.subject sub ON t.teacher_id = sub.teacher_id
            JOIN tiqui0_tiquisaat26.section sec ON sub.section_id = sec.section_id
            WHERE t.active = 1
            GROUP BY t.teacher_id, t.name
            ORDER BY total_materias DESC
            LIMIT 20
        ")->getResultArray();

        // Distribución de materias por nivel
        $distribucion_nivel = $db->query("
            SELECT c.name as nivel,
              COUNT(DISTINCT sub.subject_id) as total_materias,
              COUNT(DISTINCT sub.teacher_id) as total_docentes
            FROM tiqui0_tiquisaat26.subject sub
            JOIN tiqui0_tiquisaat26.section sec ON sub.section_id = sec.section_id
            JOIN tiqui0_tiquiweb26.class c ON sec.class_id = c.class_id
            GROUP BY c.class_id, c.name
            ORDER BY c.class_id
        ")->getResultArray();

        $Setting = new \App\Models\SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'directivo';
        $page_data['page_title'] = 'Dashboard Directivo';
        $page_data['kpi_notas'] = $kpi_notas ?? [];
        $page_data['kpi_asistencia'] = $kpi_asistencia ?? [];
        $page_data['promedio_por_nivel'] = $promedio_por_nivel;
        $page_data['evolucion_periodos'] = $evolucion_periodos;
        $page_data['top_materias'] = $top_materias;
        $page_data['bottom_materias'] = $bottom_materias;
        $page_data['alumnos_riesgo'] = $alumnos_riesgo;
        $page_data['asistencia_por_nivel'] = $asistencia_por_nivel;
        $page_data['asistencia_por_mes'] = $asistencia_por_mes;
        $page_data['ausencias_criticas'] = $ausencias_criticas;
        $page_data['carga_docente'] = $carga_docente;
        $page_data['distribucion_nivel'] = $distribucion_nivel;

        return view('backend/index', $page_data);
    }

    public function incidencias()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $db = \Config\Database::connect('default');
        $Setting = new \App\Models\SettingModel();

        // Resumen por curso (todos los cursos activos, con incidencias o sin ellas)
        $por_curso = $db->query("
            SELECT
                sec.grade          AS grado,
                sec.name           AS seccion,
                sec.section_id,
                COUNT(bl.id)                                                           AS total_incidencias,
                COUNT(DISTINCT CASE WHEN bl.id IS NOT NULL THEN s.student_id END)      AS alumnos_con_incidencias
            FROM tiqui0_tiquiasis26.section sec
            LEFT JOIN tiqui0_tiquiasis26.t_student s        ON s.section_id = sec.section_id
            LEFT JOIN tiqui0_tiquiweb26.behavior_log bl     ON bl.student_id = s.student_id
            LEFT JOIN tiqui0_tiquiweb26.behavior_types bt   ON bl.behavior_type_id = bt.id AND bt.type = 'negative'
            WHERE sec.active = 1
            GROUP BY sec.section_id
            ORDER BY FIELD(LEFT(sec.grade,1),'I','1','2','3','4','5','6'), sec.grade, sec.name
        ")->getResultArray();

        // Docentes de primaria/inicial
        $teachers_prim = $db->query("
            SELECT DISTINCT t.teacher_id, t.name AS teacher_name
            FROM tiqui0_tiquisaat26.teacher t
            JOIN tiqui0_tiquiasis26.subject sub ON sub.teacher_id = t.teacher_id
            JOIN tiqui0_tiquiasis26.section sec ON sec.section_id = sub.section_id
            WHERE sec.active = 1
              AND (sec.grade LIKE '%Primaria%' OR sec.grade LIKE '%Inicial%')
            ORDER BY t.name
        ")->getResultArray();

        // Docentes de secundaria
        $teachers_sec = $db->query("
            SELECT DISTINCT t.teacher_id, t.name AS teacher_name
            FROM tiqui0_tiquisaat26.teacher t
            JOIN tiqui0_tiquiasis26.subject sub ON sub.teacher_id = t.teacher_id
            JOIN tiqui0_tiquiasis26.section sec ON sec.section_id = sub.section_id
            WHERE sec.active = 1
              AND sec.grade NOT LIKE '%Primaria%'
              AND sec.grade NOT LIKE '%Inicial%'
            ORDER BY t.name
        ")->getResultArray();

        // Incidencias por sección + docente, calculado desde la asociación docente→materia.
        // Para cada materia de una sección (section_id + teacher_id del subject),
        // contamos cuántas incidencias tienen ese subject_id en behavior_log.
        $por_teacher_raw = $db->query("
            SELECT
                sub.section_id,
                sub.teacher_id,
                COUNT(bl.id) AS total
            FROM tiqui0_tiquiasis26.subject sub
            JOIN tiqui0_tiquiweb26.behavior_log bl ON bl.subject_id = sub.subject_id
            JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id AND bt.type = 'negative'
            JOIN tiqui0_tiquiasis26.section sec ON sec.section_id = sub.section_id
            WHERE sec.active = 1
              AND sub.teacher_id IS NOT NULL
            GROUP BY sub.section_id, sub.teacher_id
        ")->getResultArray();

        // Matriz: section_id => teacher_id => total
        $matriz_teachers = [];
        foreach ($por_teacher_raw as $row) {
            $matriz_teachers[$row['section_id']][$row['teacher_id']] = (int)$row['total'];
        }

        // Estudiantes con alertas basadas en incidencias POR MATERIA
        // Alerta = cuando acumula 5/10/18 incidencias en una MISMA materia
        $top_estudiantes = $db->query("
            SELECT
                alumno, student_id, grado, seccion,
                max_en_materia,
                total_incidencias,
                peor_materia
            FROM (
                SELECT
                    TRIM(CONCAT(s.name, ' ', IFNULL(s.lastname, ''))) AS alumno,
                    s.student_id,
                    sec.grade  AS grado,
                    sec.name   AS seccion,
                    MAX(cnt.inc_materia)  AS max_en_materia,
                    SUM(cnt.inc_materia)  AS total_incidencias,
                    SUBSTRING_INDEX(
                        GROUP_CONCAT(cnt.materia ORDER BY cnt.inc_materia DESC SEPARATOR '||'),
                        '||', 1
                    ) AS peor_materia
                FROM (
                    SELECT bl.student_id,
                           IFNULL(sub.name, '(sin materia)') AS materia,
                           COUNT(bl.id) AS inc_materia
                    FROM tiqui0_tiquiweb26.behavior_log bl
                    JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id AND bt.type = 'negative'
                    LEFT JOIN tiqui0_tiquiasis26.subject sub ON sub.subject_id = bl.subject_id
                    GROUP BY bl.student_id, bl.subject_id
                ) cnt
                JOIN tiqui0_tiquiasis26.t_student s   ON s.student_id = cnt.student_id
                JOIN tiqui0_tiquiasis26.section sec    ON s.section_id = sec.section_id
                GROUP BY s.student_id
                HAVING max_en_materia >= 5
            ) ranked
            ORDER BY max_en_materia DESC
        ")->getResultArray();

        // Distribución por tipo de incidencia (para gráfico)
        $por_tipo = $db->query("
            SELECT bt.name, bt.icon, COUNT(bl.id) AS total
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id
            WHERE bt.type = 'negative'
            GROUP BY bt.id
            ORDER BY total DESC
        ")->getResultArray();

        // Evolución por fecha (últimos 30 registros)
        $evolucion = $db->query("
            SELECT ad.date_class AS fecha, COUNT(bl.id) AS total
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id
            JOIN tiqui0_tiquiasis26.attendance_dates ad ON bl.date_id = ad.date_id
            WHERE bt.type = 'negative'
            GROUP BY ad.date_class
            ORDER BY ad.date_class ASC
        ")->getResultArray();

        // T2: datos del sistema nuevo (incidencia_registro en tiqui0_tiquisaat26)
        $db_t2 = \Config\Database::connect('tiquipaya');

        $t2_total_neg = (int) $db_t2->query("
            SELECT COUNT(*) AS c
            FROM incidencia_registro ir
            JOIN incidencia_tipos it ON ir.incidencia_tipo_id = it.id
            WHERE it.tipo = 'negativa'
        ")->getRowArray()['c'];

        $t2_total_pos = (int) $db_t2->query("
            SELECT COUNT(*) AS c
            FROM incidencia_registro ir
            JOIN incidencia_tipos it ON ir.incidencia_tipo_id = it.id
            WHERE it.tipo = 'positiva'
        ")->getRowArray()['c'];

        $t2_por_tipo = $db_t2->query("
            SELECT it.nombre AS name, it.icono AS icon, it.tipo AS type, COUNT(*) AS total
            FROM incidencia_registro ir
            JOIN incidencia_tipos it ON ir.incidencia_tipo_id = it.id
            GROUP BY it.id
            ORDER BY total DESC
        ")->getResultArray();

        $t2_evolucion = $db_t2->query("
            SELECT ir.fecha, COUNT(*) AS total
            FROM incidencia_registro ir
            JOIN incidencia_tipos it ON ir.incidencia_tipo_id = it.id
            WHERE it.tipo = 'negativa'
            GROUP BY ir.fecha
            ORDER BY ir.fecha ASC
        ")->getResultArray();

        $t2_top_negativos_raw = $db_t2->query("
            SELECT ir.student_id, COUNT(*) AS total_neg,
                   s.name AS sn, s.lastname AS sl, s.lastname2 AS sl2,
                   sec.grade AS grado, sec.name AS seccion
            FROM incidencia_registro ir
            JOIN incidencia_tipos it ON ir.incidencia_tipo_id = it.id AND it.tipo = 'negativa'
            JOIN tiqui0_tiquiasis26.t_student s ON s.student_id = ir.student_id
            JOIN tiqui0_tiquiasis26.section sec ON sec.section_id = s.section_id
            GROUP BY ir.student_id
            HAVING total_neg >= 3
            ORDER BY total_neg DESC
            LIMIT 15
        ")->getResultArray();

        $t2_top_negativos = [];
        foreach ($t2_top_negativos_raw as $tn) {
            $tn['alumno'] = trim($tn['sn'] . ' ' . ($tn['sl'] ?? '') . ' ' . ($tn['sl2'] ?? ''));
            $t2_top_negativos[] = $tn;
        }

        // Separar primaria y secundaria del resumen por curso
        $primaria = [];
        $secundaria = [];
        foreach ($por_curso as $curso) {
            $g = strtolower($curso['grado']);
            if (strpos($g, 'primaria') !== false || strpos($g, 'inicial') !== false) {
                $primaria[] = $curso;
            } else {
                $secundaria[] = $curso;
            }
        }

        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'incidencias';
        $page_data['page_title'] = 'Control de Incidencias';
        $page_data['primaria'] = $primaria;
        $page_data['secundaria'] = $secundaria;
        $page_data['teachers_prim'] = $teachers_prim;
        $page_data['teachers_sec'] = $teachers_sec;
        $page_data['matriz_teachers'] = $matriz_teachers;
        $page_data['top_estudiantes'] = $top_estudiantes;
        $page_data['por_tipo'] = $por_tipo;
        $page_data['evolucion'] = $evolucion;
        $page_data['t2_total_neg']     = $t2_total_neg;
        $page_data['t2_total_pos']     = $t2_total_pos;
        $page_data['t2_por_tipo']      = $t2_por_tipo;
        $page_data['t2_evolucion']     = $t2_evolucion;
        $page_data['t2_top_negativos'] = $t2_top_negativos;

        return view('backend/index', $page_data);
    }

    // Perfil de incidencias por curso
    public function incidencias_seccion($section_id = 0)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $section_id = (int) $section_id;
        $db = \Config\Database::connect('default');
        $Setting = new \App\Models\SettingModel();

        // Info de la sección
        $seccion = $db->query("
            SELECT sec.section_id, sec.grade, sec.name, sec.completo, sec.nick_name
            FROM tiqui0_tiquiasis26.section sec
            WHERE sec.section_id = ?
        ", [$section_id])->getRowArray();

        if (!$seccion) return redirect()->to(base_url('manager/incidencias'));

        // Materias de la sección con su docente
        $materias = $db->query("
            SELECT sub.subject_id, sub.name AS materia, t.name AS teacher_name
            FROM tiqui0_tiquiasis26.subject sub
            LEFT JOIN tiqui0_tiquisaat26.teacher t ON t.teacher_id = sub.teacher_id
            WHERE sub.section_id = ?
            ORDER BY sub.name
        ", [$section_id])->getResultArray();

        // Todos los estudiantes de la sección
        $estudiantes = $db->query("
            SELECT s.student_id,
                TRIM(CONCAT(s.name, ' ', IFNULL(s.lastname,''), ' ', IFNULL(s.lastname2,''))) AS alumno
            FROM tiqui0_tiquiasis26.t_student s
            WHERE s.section_id = ?
            ORDER BY s.name, s.lastname
        ", [$section_id])->getResultArray();

        // T1: Incidencias por estudiante + materia (sistema antiguo)
        $inc_raw = $db->query("
            SELECT bl.student_id, bl.subject_id, COUNT(bl.id) AS total
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id AND bt.type = 'negative'
            WHERE bl.student_id IN (
                SELECT student_id FROM tiqui0_tiquiasis26.t_student WHERE section_id = ?
            )
            GROUP BY bl.student_id, bl.subject_id
        ", [$section_id])->getResultArray();

        // Matriz T1: student_id => subject_id => count
        $matriz = [];
        foreach ($inc_raw as $row) {
            $matriz[$row['student_id']][$row['subject_id']] = (int)$row['total'];
        }

        $totales     = [];
        $total_inc   = 0;
        $alumnos_con = 0;
        foreach ($matriz as $sid => $subs) {
            $t = array_sum($subs);
            $totales[$sid] = $t;
            $total_inc    += $t;
            if ($t > 0) $alumnos_con++;
        }
        $max_inc = !empty($totales) ? max($totales) : 1;

        // T2: Incidencias por estudiante + materia (sistema nuevo)
        $db_t2 = \Config\Database::connect('tiquipaya');
        $inc_raw_t2 = $db_t2->query("
            SELECT ir.student_id, ir.subject_id, COUNT(*) AS total
            FROM incidencia_registro ir
            JOIN incidencia_tipos it ON ir.incidencia_tipo_id = it.id AND it.tipo = 'negativa'
            WHERE ir.student_id IN (
                SELECT student_id FROM tiqui0_tiquiasis26.t_student WHERE section_id = ?
            )
            GROUP BY ir.student_id, ir.subject_id
        ", [$section_id])->getResultArray();

        $matriz_t2     = [];
        $totales_t2    = [];
        $total_inc_t2  = 0;
        $alumnos_t2    = 0;
        foreach ($inc_raw_t2 as $row) {
            $matriz_t2[$row['student_id']][$row['subject_id']] = (int)$row['total'];
        }
        foreach ($matriz_t2 as $sid => $subs) {
            $t = array_sum($subs);
            $totales_t2[$sid] = $t;
            $total_inc_t2    += $t;
            if ($t > 0) $alumnos_t2++;
        }

        $page_data['login_type']   = $session->get('login_type');
        $page_data['cuenta']       = $session->get('cuenta');
        $page_data['phase_id']     = $Setting->get_phase_id();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['page_name']    = 'incidencias_seccion';
        $page_data['page_title']   = 'Perfil de Incidencias — ' . ($seccion['completo'] ?? $seccion['grade'] . ' ' . $seccion['name']);
        $page_data['seccion']      = $seccion;
        $page_data['materias']     = $materias;
        $page_data['estudiantes']  = $estudiantes;
        $page_data['matriz']       = $matriz;
        $page_data['totales']      = $totales;
        $page_data['total_inc']    = $total_inc;
        $page_data['alumnos_con']  = $alumnos_con;
        $page_data['max_inc']      = $max_inc;
        $page_data['matriz_t2']    = $matriz_t2;
        $page_data['totales_t2']   = $totales_t2;
        $page_data['total_inc_t2'] = $total_inc_t2;
        $page_data['alumnos_t2']   = $alumnos_t2;

        return view('backend/index', $page_data);
    }

    // AJAX: autocomplete de estudiantes para búsqueda en incidencias
    public function incidencias_search_students()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return $this->response->setStatusCode(403);

        $q = trim($this->request->getGet('q') ?? '');
        if (strlen($q) < 2)
            return $this->response->setJSON([]);

        $db = \Config\Database::connect('default');
        $rows = $db->query("
            SELECT s.student_id,
                   TRIM(CONCAT(s.name, ' ', IFNULL(s.lastname, ''), ' ', IFNULL(s.lastname2,''))) AS nombre,
                   sec.grade AS grado, sec.name AS seccion
            FROM tiqui0_tiquiasis26.t_student s
            JOIN tiqui0_tiquiasis26.section sec ON s.section_id = sec.section_id
            WHERE sec.active = 1
              AND CONCAT(s.name, ' ', IFNULL(s.lastname,''), ' ', IFNULL(s.lastname2,'')) LIKE ?
            ORDER BY s.name, s.lastname
            LIMIT 10
        ", ['%' . $q . '%'])->getResultArray();

        return $this->response->setJSON($rows);
    }

    // AJAX: resumen de incidencias de un estudiante
    public function incidencias_student($student_id = 0)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return $this->response->setStatusCode(403);

        $student_id = (int) $student_id;
        if ($student_id <= 0)
            return $this->response->setJSON(['error' => 'invalid']);

        $db = \Config\Database::connect('default');

        // Datos del estudiante
        $student = $db->query("
            SELECT TRIM(CONCAT(s.name, ' ', IFNULL(s.lastname,''), ' ', IFNULL(s.lastname2,''))) AS nombre,
                   sec.grade AS grado, sec.name AS seccion, sec.section_id
            FROM tiqui0_tiquiasis26.t_student s
            JOIN tiqui0_tiquiasis26.section sec ON s.section_id = sec.section_id
            WHERE s.student_id = ?
        ", [$student_id])->getRowArray();

        if (!$student)
            return $this->response->setJSON(['error' => 'not found']);

        // T1: todas las incidencias del sistema antiguo (behavior_log)
        $detalle = $db->query("
            SELECT bt.name AS tipo, bt.icon, bt.type AS tipo_clase, bt.points,
                   ad.date_class AS fecha,
                   IFNULL(sub.name, '—') AS materia,
                   bl.observation
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt   ON bl.behavior_type_id = bt.id
            JOIN tiqui0_tiquiasis26.attendance_dates ad ON bl.date_id = ad.date_id
            LEFT JOIN tiqui0_tiquiasis26.subject sub    ON bl.subject_id = sub.subject_id
            WHERE bl.student_id = ?
            ORDER BY ad.date_class DESC
        ", [$student_id])->getResultArray();

        $por_tipo = $db->query("
            SELECT bt.name AS tipo, bt.icon, bt.type AS tipo_clase, COUNT(*) AS total
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id
            WHERE bl.student_id = ?
            GROUP BY bt.id
            ORDER BY total DESC
        ", [$student_id])->getResultArray();

        // T2: sistema nuevo (incidencia_registro en tiqui0_tiquisaat26)
        $db_t2 = \Config\Database::connect('tiquipaya');

        $detalle_t2 = $db_t2->query("
            SELECT it.nombre AS tipo, it.icono AS icon, it.tipo AS tipo_clase,
                   ir.fecha,
                   IFNULL(sub.name, '—') AS materia,
                   ir.observacion AS observation,
                   ir.phase_id
            FROM incidencia_registro ir
            JOIN incidencia_tipos it ON ir.incidencia_tipo_id = it.id
            LEFT JOIN tiqui0_tiquiasis26.subject sub ON ir.subject_id = sub.subject_id
            WHERE ir.student_id = ?
            ORDER BY ir.fecha DESC, ir.created_at DESC
        ", [$student_id])->getResultArray();

        $por_tipo_t2 = $db_t2->query("
            SELECT it.nombre AS tipo, it.icono AS icon, it.tipo AS tipo_clase, COUNT(*) AS total
            FROM incidencia_registro ir
            JOIN incidencia_tipos it ON ir.incidencia_tipo_id = it.id
            WHERE ir.student_id = ?
            GROUP BY it.id
            ORDER BY total DESC
        ", [$student_id])->getResultArray();

        return $this->response->setJSON([
            'student'     => $student,
            'detalle'     => $detalle,
            'por_tipo'    => $por_tipo,
            'total'       => count($detalle),
            'detalle_t2'  => $detalle_t2,
            'por_tipo_t2' => $por_tipo_t2,
            'total_t2'    => count($detalle_t2),
        ]);
    }

    public function student_search_ajax($sel = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return $this->response->setJSON([]);

        if (strlen($sel) < 2)
            return $this->response->setJSON([]);

        $StudentMod = new StudentModel();
        $rows = $StudentMod->students_user('manager', $sel, 0);
        $results = [];
        foreach ($rows as $r) {
            $results[] = [
                'student_id' => $r['student_id'],
                'nombre' => $r['lastname'] . ' ' . $r['lastname2'] . ' ' . $r['name'],
                'completo' => $r['nick_name'],
            ];
        }
        return $this->response->setJSON($results);
    }

    public function student_summary($student_id = 0)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return $this->response->setJSON(['error' => 'unauthorized']);

        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        $StudentMod = new StudentModel();
        $info = $StudentMod->datosStudent($student_id);
        if (empty($info))
            return $this->response->setJSON(['error' => 'not_found']);
        $info = $info[0];

        $AbsenceMod = new AbsenceModel();
        $absences = $AbsenceMod->get_absences_student($student_id);

        $DelayMod = new DelayModel();
        $delays = $DelayMod->get_delay_student($student_id);

        $LicMod = new LicenciaModel();
        $licenses = $LicMod->licenciasStudent($student_id);

        $CsaMod = new CsamarksModel();
        $grades = $CsaMod->csamarks_centralizer($student_id, $phase_id);

        $db = \Config\Database::connect('default');
        $incidencias = $db->query("
            SELECT bt.name AS tipo, bt.icon, ad.date_class AS fecha,
                   IFNULL(sub.name, '—') AS materia, bl.observation
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt    ON bl.behavior_type_id = bt.id
            JOIN tiqui0_tiquiasis26.attendance_dates ad  ON bl.date_id = ad.date_id
            LEFT JOIN tiqui0_tiquiasis26.subject sub     ON bl.subject_id = sub.subject_id
            WHERE bl.student_id = ? AND bt.type = 'negative'
            ORDER BY ad.date_class DESC
        ", [$student_id])->getResultArray();

        return $this->response->setJSON([
            'info' => $info,
            'absences' => $absences,
            'delays' => array_map(fn($d) => [
                'date_class' => $d['date_class'],
                'tarde_con' => $d['tarde_con'],
            ], $delays),
            'licenses' => array_map(fn($l) => (array) $l, (array) $licenses),
            'incidencias' => $incidencias,
            'grades' => $grades,
            'phase_id' => $phase_id,
        ]);
    }

    public function class_dir()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $Section = new SectionModel();
        $page_data['class'] = $Section->sections_all_by_grade();
        $page_data['page_name'] = 'class_dir';
        $page_data['page_title'] = 'Cursos Director';
        return view('backend/index', $page_data);
    }

    public function self_director()
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $Self = new SelfappraisalModel();
        $rows = $Self->self_director($manager_id, $page_data['phase_id']);

        // Agrupar por curso manteniendo orden de section_id
        $por_curso = [];
        foreach ($rows as $row) {
            $key = $row['section_id'];
            if (!isset($por_curso[$key])) {
                $por_curso[$key] = [
                    'completo'   => $row['completo'],
                    'section_id' => $row['section_id'],
                    'total'      => 0,
                    'con_auto'   => 0,
                    'estudiantes'=> []
                ];
            }
            $por_curso[$key]['total']++;
            if ($row['tiene_auto']) $por_curso[$key]['con_auto']++;
            $por_curso[$key]['estudiantes'][] = $row;
        }
        $page_data['por_curso'] = $por_curso;
        $page_data['page_name'] = 'self_director';
        $page_data['page_title'] = 'Autoevaluaciones';
        return view('backend/index', $page_data);
    }

    public function evaluation_planner()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();

        $db = \Config\Database::connect('tiquipaya');

        // All sections
        $SectionMod = new SectionModel();
        $page_data['sections'] = $SectionMod->get_section([]);

        // All teachers that have at least one evaluation
        $page_data['teachers'] = $db->query("
            SELECT DISTINCT t.teacher_id, t.name as teacher_name
            FROM evaluations e
            JOIN tiqui0_tiquisaat26.teacher t ON t.teacher_id = e.teacher_id
            ORDER BY t.name ASC
        ")->getResultArray();

        // All subjects that have at least one evaluation
        $page_data['subjects'] = $db->query("
            SELECT DISTINCT sub.subject_id, sub.name as subject_name
            FROM evaluations e
            JOIN subject sub ON sub.subject_id = e.subject_id
            ORDER BY sub.name ASC
        ")->getResultArray();

        // Stats
        $EvaluationMod = new EvaluationModel();
        $all = $EvaluationMod->select('evaluations.*, subject.name as subject_name, section.name as section_name, section.nick_name')
            ->join('subject', 'subject.subject_id = evaluations.subject_id')
            ->join('section', 'section.section_id = evaluations.section_id')
            ->orderBy('date', 'ASC')
            ->findAll();

        $today    = date('Y-m-d');
        $in7days  = date('Y-m-d', strtotime('+7 days'));
        $upcoming = 0;
        $dayCounts = [];
        foreach ($all as $ev) {
            if ($ev['date'] >= $today && $ev['date'] <= $in7days) $upcoming++;
            $key = $ev['date'] . '_' . $ev['section_id'];
            $dayCounts[$key] = ($dayCounts[$key] ?? 0) + 1;
        }
        $saturated = count(array_filter($dayCounts, fn($c) => $c >= 3));

        $page_data['total_evaluations'] = count($all);
        $page_data['upcoming_count']    = $upcoming;
        $page_data['saturated_days']    = $saturated;
        $page_data['page_name']         = 'evaluation_planner';
        $page_data['page_title']        = 'Planificador de Evaluaciones';
        return view('backend/index', $page_data);
    }

    private function _ep_builder()
    {
        $db = \Config\Database::connect('tiquipaya');
        $builder = $db->table('evaluations e')
            ->select('e.*, sub.name as subject_name, sec.name as section_name, sec.nick_name, t.name as teacher_name')
            ->join('subject sub', 'sub.subject_id = e.subject_id')
            ->join('section sec', 'sec.section_id = e.section_id')
            ->join('tiqui0_tiquisaat26.teacher t', 't.teacher_id = e.teacher_id', 'left')
            ->orderBy('e.date', 'ASC');

        $section_id = $this->request->getPost('section_id');
        $teacher_id = $this->request->getPost('teacher_id');
        $subject_id = $this->request->getPost('subject_id');

        if (!empty($section_id)) $builder->where('e.section_id', $section_id);
        if (!empty($teacher_id)) $builder->where('e.teacher_id', $teacher_id);
        if (!empty($subject_id)) $builder->where('e.subject_id', $subject_id);

        return $builder;
    }

    public function get_manager_calendar_events()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return $this->response->setJSON([]);

        $events = $this->_ep_builder()->get()->getResultArray();

        $calendarEvents = [];
        foreach ($events as $ev) {
            $calendarEvents[] = [
                'id'           => $ev['id'],
                'title'        => ($ev['nick_name'] ?: $ev['section_name']) . ' – ' . $ev['subject_name'] . ': ' . $ev['title'],
                'raw_title'    => $ev['title'],
                'subject_name' => $ev['subject_name'],
                'section_name' => $ev['nick_name'] ?: $ev['section_name'],
                'teacher_name' => $ev['teacher_name'] ?? '—',
                'section_id'   => $ev['section_id'],
                'start'        => $ev['date'],
                'allDay'       => true,
                'color'        => '#3699FF',
                'description'  => $ev['description'] ?? '',
                'extendedProps' => [
                    'subject_name' => $ev['subject_name'],
                    'section_name' => $ev['nick_name'] ?: $ev['section_name'],
                    'teacher_name' => $ev['teacher_name'] ?? '—',
                    'raw_title'    => $ev['title'],
                    'description'  => $ev['description'] ?? '',
                ],
            ];
        }

        return $this->response->setJSON($calendarEvents);
    }

    public function get_all_evaluations_manager()
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return $this->response->setJSON([]);

        $rows = $this->_ep_builder()->get()->getResultArray();

        return $this->response->setJSON($rows);
    }

    function sections_dir()
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('adviser'))
            return redirect()->to(base_url());

        //Section
        $data = ["director_id" => $manager_id];
        $Section = new SectionModel();
        $cursos = $Section->get_section($data);
        $page_data['sections'] = $cursos;
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'sections_dir';
        $page_data['page_title'] = 'Cursos del Director';
        return view('backend/index', $page_data);
    }

    function generate_centralizer($section_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('adviser'))
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $gestion = $Setting->get_gestion();
        //Estudiantes del curso
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_active($section_id);
        $conter = 8;
        //Instanciamos la libreria
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        //**************ABRIMOS EXCEL DE ACUERDO A EL CURSO QUE CORRESPONDE
        if ($section_id >= 211 And $section_id <= 224) {
            $obj_PHPExcel = $obj_Reader->load('templates/cp12.xlsx');
            $obj_PHPExcel->setActiveSheetIndex(0);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row):
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $est);
                //******************RELLENAMOS NOTAS*************************
                for ($i = 0; $i < $phase_id; $i++) {
                    list($cnat, $ing, $lening, $prom, $lenque, $fisqui) = array(0, 0, 0, 0, 0, 0);
                    $b = 1 + $i;
                    //Notas
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    foreach ($notas as $nota) {
                        if (!isset($nota['obtained_mark'])) {
                            $nota['obtained_mark'] = '0';
                        }
                        switch ($nota['name']) {
                            case 'LENGUAJE':
                                $lening += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(44 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'READING':
                                $ing += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(48 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'GRAMMAR':
                                $ing += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(52 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'SOCIALES':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'E. FÍSICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MÚSICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'ARTE':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MATEMÁTICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'COMPUTACIÓN':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'SCIENCE':
                                $cnat += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(64 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'C. NATURALES':
                                $cnat += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(68 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'F. HUMANA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34 + $b, $conter, $nota['obtained_mark']);
                                break;
                        }
                    }
                    
                    if ($ing != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(56 + $b, $conter, round($ing / 2));
                        $lening += round($ing / 2);
                    }
                    if ($lening != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(60 + $b, $conter, round($lening / 2));
                    }
                    if ($cnat != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(72 + $b, $conter, round($cnat / 2));
                    }
                    $prom += round($lening / 2) + round($cnat / 2);
                    if ($prom != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(38 + $b, $conter, round($prom / 9));
                    }
                }
                $conter++;
            endforeach;

        } elseif ($section_id >= 231 And $section_id <= 263) {
            $obj_PHPExcel = $obj_Reader->load('templates/cp36.xlsx');
            $obj_PHPExcel->setActiveSheetIndex(0);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row):
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $est);
                //******************RELLENAMOS NOTAS*************************
                for ($i = 0; $i < $phase_id; $i++) {
                    list($cnat, $ing, $lening, $prom, $lenque, $val) = array(0, 0, 0, 0, 0, 0);
                    $b = 1 + $i;
                    //Notas
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    foreach ($notas as $nota) {
                        if (!isset($nota['obtained_mark'])) {
                            $nota['obtained_mark'] = '0';
                        }
                        switch ($nota['name']) {
                            case 'LENGUAJE':
                                $lening += round($nota['obtained_mark']*0.45);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(44 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'QUECHUA':
                                $lening += round($nota['obtained_mark']*0.05);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(48 + $b, $conter, $nota['obtained_mark']);
                                break;  
                            case 'READING':
                                $ing+=$nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(52 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'GRAMMAR':
                                $ing+=$nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(56 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'SOCIALES':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'E. FÍSICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MÚSICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'ARTE':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MATEMÁTICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'COMPUTACIÓN':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'SCIENCE':
                                $cnat += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(68 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'C. NATURALES':
                                $cnat += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(72 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'F. HUMANA':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34 + $b, $conter, $nota['obtained_mark']);
                                break;
                        }
                    }
                    if($ing!=0){
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(60 + $b, $conter, round($ing/2));
                        $lening+=round(round($ing/2)*0.5);
                    }
                    if($lening!=0){$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(64 + $b, $conter, round($lening));}
                    if ($cnat != 0) {$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(76 + $b, $conter, round($cnat / 2));}
                    $prom += round($lening) + round($cnat / 2);
                    if ($prom != 0) {$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(38 + $b, $conter, round($prom / 9));}
                }
                $conter++;
            endforeach;
        } elseif ($section_id >= 271 And $section_id <= 283) {
            //***************1RO Y 2DO DE SECUNDARIA
            $obj_PHPExcel = $obj_Reader->load('templates/cs12.xlsx');
            $obj_PHPExcel->setActiveSheetIndex(0);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row):
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $est);
                //******************RELLENAMOS NOTAS*************************
                for ($i = 0; $i < $phase_id; $i++) {
                    list($cnat, $ing, $lening, $prom, $lenque, $fisqui) = array(0, 0, 0, 0, 0, 0);
                    $b = 1 + $i;
                    //Notas
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        $prom += round($ef['total_average']);
                        if ($ef['total_average'] != 0) {
                            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $ef['total_average']);
                        }
                    }
                    //para las otras materias
                    foreach ($notas as $nota) {
                        if (!isset($nota['obtained_mark'])) {
                            $nota['obtained_mark'] = '0';
                        }
                        switch ($nota['name']) {
                            case 'LENGUAJE':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'LITERATURE':
                                $ing += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(52 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'GRAMMAR':
                                $ing += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(56 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'SOCIALES':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MÚSICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'ART. PLAST.':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MATEMÁTICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'TEC. TECNOLÓGICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'BIOLOGÍA':
                                $fisqui += round($nota['obtained_mark'] * 0.8);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(64 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'FÍSICA':
                                $fisqui += round($nota['obtained_mark'] * 0.1);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(68 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'QUÍMICA':
                                $fisqui += round($nota['obtained_mark'] * 0.1);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(72 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'PSICOLOGÍA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(38 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'VAL_ESP_REL':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(42 + $b, $conter, $nota['obtained_mark']);
                                break;
                        }
                    }
                    if ($ing != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(60 + $b, $conter, round($ing / 2));
                        $prom += round($ing / 2);
                    }
                    if ($fisqui != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(76 + $b, $conter, $fisqui);
                        $prom += $fisqui;
                    }
                    if ($prom != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(46 + $b, $conter, round($prom / 11));
                    }
                }
                $conter++;
            endforeach;
        } elseif ($section_id >= 311 And $section_id <= 323) {
            //***************3RO y 4to DE sECUNDARIA***********************
            $obj_PHPExcel = $obj_Reader->load('templates/cs34.xlsx');
            $obj_PHPExcel->setActiveSheetIndex(0);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row):
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $est);
                //******************RELLENAMOS NOTAS*************************
                for ($i = 0; $i < $phase_id; $i++) {
                    list($cnat, $ing, $lening, $prom, $lenque, $fisqui) = array(0, 0, 0, 0, 0, 0);
                    $b = 1 + $i;
                    //Notas
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    //solo para educacion fisica
                    foreach ($ed_fisica as $ef) {
                        $prom += round($ef['total_average']);
                        if ($ef['total_average'] != 0) {
                            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $ef['total_average']);
                        }
                    }
                    //para las otras materias
                    foreach ($notas as $nota) {
                        if (!isset($nota['obtained_mark'])) {
                            $nota['obtained_mark'] = '0';
                        }
                        switch ($nota['name']) {
                            case 'LITERATURA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'LENGUAJE':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'LITERATURE':
                                $ing += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(60 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'GRAMMAR':
                                $ing += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(64 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'SOCIALES':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MÚSICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'ART. PLAST.':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MATEMÁTICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'TEC. TECNOLÓGICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'BIOLOGÍA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'FÍSICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(38 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'QUÍMICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(42 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'PSICOLOGÍA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->SetCellValue('BK6', 'Psicología');
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(46 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'FILOSOFÍA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->SetCellValue('BK6', 'Filosofía');
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(46 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'VAL_ESP_REL':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(50 + $b, $conter, $nota['obtained_mark']);
                                break;
                        }
                    }
                    if ($ing != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(68 + $b, $conter, round($ing / 2));
                        $prom += round($ing / 2);
                    }
                    
                    if ($prom != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(54 + $b, $conter, round($prom / 13));
                    }
                }
                $conter++;
            endforeach;
        } elseif ($section_id >= 331 And $section_id <= 343) {
            //*************** 5tO y 6to DE sECUNDARIA **************************
            $obj_PHPExcel = $obj_Reader->load('templates/cs56.xlsx');
            $obj_PHPExcel->setActiveSheetIndex(0);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row):
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $est);
                //******************RELLENAMOS NOTAS*************************
                for ($i = 0; $i < $phase_id; $i++) {
                    list($cnat, $ing, $lening, $prom, $lenque, $fisqui) = array(0, 0, 0, 0, 0, 0);
                    $b = 1 + $i;
                    //Notas
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    //solo para educacion fisica
                    foreach ($ed_fisica as $ef) {
                        $prom += round($ef['total_average']);
                        if ($ef['total_average'] != 0) {
                            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $ef['total_average']);
                        }
                    }
                    //para las otras materias
                    foreach ($notas as $nota) {
                        if (!isset($nota['obtained_mark'])) {
                            $nota['obtained_mark'] = '0';
                        }
                        switch ($nota['name']) {
                            case 'LITERATURA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'LITERATURE':
                                $ing += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(60 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'GRAMMAR':
                                $ing += $nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(64 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'SOCIALES':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MÚSICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'ART. PLAST.':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MATEMÁTICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'TEC. TECNOLÓGICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'BIOLOGÍA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(34 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'FÍSICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(38 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'QUÍMICA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(42 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'FILOSOFÍA':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(46 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'VAL_ESP_REL':
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(50 + $b, $conter, $nota['obtained_mark']);
                                break;
                        }
                    }
                    if ($ing != 0) {
                        $prom += round($ing / 2);
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(68 + $b, $conter, round($ing / 2));
                    }
                    
                    if ($prom != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(54 + $b, $conter, round($prom / 13));
                    }
                }
                $conter++;
            endforeach;
        }

        //Section
        $data = ["section_id" => $section_id];
        $SectionMod = new SectionModel();
        $section = $SectionMod->get_section($data);
        $fileName = $section[0]['completo'] . '.xlsx';
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A4', "GESTIÓN " . $gestion . " NOTAS OFICIALES");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A5', strtoupper($section[0]['completo']));
        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A43', 'Generado el : ' . $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);
    }

    //******************************** BEGIN - DIRECTOR **************/

    public function delays_student($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
        $DelayMod = new DelayModel();
        $page_data['delays'] = $DelayMod->get_delay_student($student_id);
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Retrasos del Estudiante";
        $page_data['page_name'] = "delays_student";
        return view('backend/index', $page_data);
    }

    public function absences_student($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
        $AbsenceMod = new AbsenceModel();
        $page_data['absences'] = $AbsenceMod->get_absences_student($student_id);
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Ausencias del Estudiante";
        $page_data['page_name'] = "absences_student";
        return view('backend/index', $page_data);
    }

    function subjects_section($section_id = '')
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $data = ["director_id" => $manager_id];
        $Section = new SectionModel();
        $cursos = $Section->get_section($data);
        $page_data['completo'] = $cursos[0]['completo'];
        $Subject = new SubjectModel();
        $subjects = $Subject->subjects_section($section_id);
        $page_data['subjects'] = $subjects;
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'subjects_section';
        $page_data['page_title'] = 'Registros de Notas';
        return view('backend/index', $page_data);
    }

    function notes_half_student_xls($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $phase_name = $Setting->get_phase_name();
        $phase_id = $Setting->get_phase_id();
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $student = $students[0]->nombre;
        $completo = $students[0]->completo;
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_half_student($student_id, $phase_id);
        $fileName = "half_" . $student . ".xlsx";
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load('templates/half.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValueByColumnAndRow(1, 3, strtoupper($phase_name));
        $sheet->setCellValueByColumnAndRow(1, 4, strtoupper($student));
        $sheet->setCellValueByColumnAndRow(1, 5, strtoupper($completo));
        $count = 8;
        foreach ($csamarks as $row):
            $saber = 0;
            $hacer = 0;
            if (isset($row['saber'])) {
                $saber = round($row['saber']);
            }
            if (isset($row['hacer'])) {
                $hacer = round($row['hacer']);
            }
            $prom = round(($saber + $hacer) / 2);
            $sheet->setCellValueByColumnAndRow(1, $count, $row['materia']);
            $sheet->setCellValueByColumnAndRow(2, $count, $row['docente']);
            $sheet->setCellValueByColumnAndRow(3, $count, $saber);
            $sheet->setCellValueByColumnAndRow(4, $count, $hacer);
            $sheet->setCellValueByColumnAndRow(5, $count, $prom);
            $count += 1;
        endforeach;
        $fecha_actual = date("d/m/Y");
        $sheet->setCellValueByColumnAndRow(5, 35, $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);
    }

    function section_notes($section_id = '')
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $self = new SelfappraisalModel();
        $autos = $self->self_director($manager_id, $page_data['phase_id']);
        $page_data['students'] = $autos;
        $data = ["section_id" => $section_id];
        $SectionMod = new SectionModel();
        $section = $SectionMod->get_section($data);
        $page_data['section_id'] = $section_id;
        $page_data['completo'] = $section[0]['completo'];
        $Subject = new SubjectModel();
        $subjects = $Subject->subjects_section($section_id);
        $page_data['subjects'] = $subjects;
        $StudentMod = new StudentModel();
        $page_data['students'] = $StudentMod->student_active($section_id);
        $CsamarksMod = new CsamarksModel();
        $page_data['notas'] = $CsamarksMod->csamarks_section($section_id);
        $page_data['page_name'] = 'section_notes';
        $page_data['page_title'] = 'Centralizador de Notas';
        return view('backend/index', $page_data);
    }

    function teacher_notes()
    {
        $session = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Subject = new SubjectModel();
        $subjects = $Subject->dir_notes_teacher($manager_id);
        $page_data['teachers'] = $subjects;
        $subjects2 = $Subject->dir_notes_subject($manager_id);
        $page_data['subjects'] = $subjects2;
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'teacher_notes';
        $page_data['page_title'] = 'Centralizador de Notas';
        return view('backend/index', $page_data);
    }

    function student_statistics($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'student_statistics';
        $page_data['page_title'] = 'Estadísticas Estudiantes';
        return view('backend/index', $page_data);
    }

    function student_communications($student_id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $BehaviorsMod = new BehaviorsModel();
        $respuesta = $BehaviorsMod->update_behaviors_student($student_id);
        $students = $BehaviorsMod->behaviors_student($page_data['phase_id'], $student_id);
        $page_data['behaviors'] = $students;
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'student_communications';
        $page_data['page_title'] = 'Reporte de Faltas Leves';
        return view('backend/index', $page_data);
    }

    function grade_averages($class_id, $phase_id)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $CsamarksMod = new CsamarksModel();
        $page_data['averages'] = $CsamarksMod->csamarks_avg_grade($class_id, $phase_id);
        $data = ["class_id" => $class_id];
        $Section = new SectionModel();
        $curso = $Section->get_section($data);
        $page_data['grade'] = $curso[0]['grade'];
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'grade_averages';
        $page_data['page_title'] = 'Promedios bajos grado';
        return view('backend/index', $page_data);
    }

    function generate_ranking($section_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $gestion = $Setting->get_gestion();
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_active($section_id);
        $conter = 8;
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $obj_PHPExcel = $obj_Reader->load('templates/rnk.xlsx');
        $notaBim = array(0, 0, 0, 0, 0);
        $alumnos = [];
        if ($section_id >= 211 And $section_id <= 224) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_active($section_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($section_id >= 231 And $section_id <= 263) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_active($section_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $cnat = 0;
                    $ing = 0;
                    $lening = 0;
                    $prom = 0;
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        $prom += round($ef['total_average']);
                    }
                    foreach ($notas as $nota) {
                        switch ($nota['name']) {
                            case 'LENGUAJE': $lening += $nota['obtained_mark']; break;
                            case 'READING': $ing += $nota['obtained_mark']; break;
                            case 'GRAMMAR': $ing += $nota['obtained_mark']; break;
                            case 'SOCIALES': $prom += round($nota['obtained_mark']); break;
                            case 'MÚSICA': $prom += round($nota['obtained_mark']); break;
                            case 'ARTE': $prom += round($nota['obtained_mark']); break;
                            case 'MATEMÁTICA': $prom += round($nota['obtained_mark']); break;
                            case 'COMPUTACIÓN': $prom += round($nota['obtained_mark']); break;
                            case 'SCIENCE': $cnat += $nota['obtained_mark']; break;
                            case 'C. NATURALES': $cnat += $nota['obtained_mark']; break;
                            case 'F. HUMANA': $prom += round($nota['obtained_mark']); break;
                        }
                    }
                    if ($ing != 0) { $lening += round($ing / 2); }
                    $prom += round($lening / 2) + round($cnat / 2);
                    if ($prom != 0) { $notaBim[$b] = round($prom / 9, 2); }
                }
                $final = round(($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id, 2);
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($section_id >= 271 And $section_id <= 283) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_active($section_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ing = 0; $cnat = 0; $prom = 0;
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) { $prom += round($ef['total_average']); }
                    foreach ($notas as $nota) {
                        switch ($nota['name']) {
                            case 'LITERATURA': $prom += round($nota['obtained_mark']); break;
                            case 'LENGUAJE': $prom += round($nota['obtained_mark']); break;
                            //case 'QUECHUA': $lenque += $nota['obtained_mark']; break;
                            case 'LITERATURE': $ing += $nota['obtained_mark']; break;
                            case 'GRAMMAR': $ing += $nota['obtained_mark']; break;
                            case 'SOCIALES': $prom += round($nota['obtained_mark']); break;
                            case 'MÚSICA': $prom += round($nota['obtained_mark']); break;
                            case 'ART. PLAST.': $prom += round($nota['obtained_mark']); break;
                            case 'MATEMÁTICA': $prom += round($nota['obtained_mark']); break;
                            case 'TEC. TECNOLÓGICA': $prom += round($nota['obtained_mark']); break;
                            case 'BIOLOGÍA': $cnat += round($nota['obtained_mark'] * 0.8); break;
                            case 'FÍSICA': $cnat += round($nota['obtained_mark'] * 0.1); break;
                            case 'QUÍMICA': $cnat += round($nota['obtained_mark'] * 0.1); break;
                            case 'PSICOLOGÍA': $prom += round($nota['obtained_mark']); break;
                            case 'FILOSOFÍA': $prom += round($nota['obtained_mark']); break;
                            case 'VAL_ESP_REL': $prom += round($nota['obtained_mark']); break;
                        }
                    }
                    $prom += round($ing / 2) + round($cnat);
                    if ($prom != 0) { $notaBim[$b] = round($prom / 11, 2); }
                }
                $final = round(($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id, 2);
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($section_id >= 311 And $section_id <= 323) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_active($section_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ing = 0; $prom = 0;
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) { $prom += round($ef['total_average']); }
                    foreach ($notas as $nota) {
                        switch ($nota['name']) {
                            case 'LITERATURA': $prom += round($nota['obtained_mark']); break;
                            case 'LENGUAJE': $prom += round($nota['obtained_mark']); break;
                            //case 'QUECHUA': $lenque += $nota['obtained_mark']; break;
                            case 'LITERATURE': $ing += $nota['obtained_mark']; break;
                            case 'GRAMMAR': $ing += $nota['obtained_mark']; break;
                            case 'SOCIALES': $prom += round($nota['obtained_mark']); break;
                            case 'MÚSICA': $prom += round($nota['obtained_mark']); break;
                            case 'ART. PLAST.': $prom += round($nota['obtained_mark']); break;
                            case 'MATEMÁTICA': $prom += round($nota['obtained_mark']); break;
                            case 'TEC. TECNOLÓGICA': $prom += round($nota['obtained_mark']); break;
                            case 'BIOLOGÍA': $prom += round($nota['obtained_mark']); break;
                            case 'FÍSICA': $prom += round($nota['obtained_mark']); break;
                            case 'QUÍMICA': $prom += round($nota['obtained_mark']); break;
                            case 'PSICOLOGÍA': $prom += round($nota['obtained_mark']); break;
                            case 'FILOSOFÍA': $prom += round($nota['obtained_mark']); break;
                            case 'VAL_ESP_REL': $prom += round($nota['obtained_mark']); break;
                        }
                    }
                    $prom +=  round($ing / 2);
                    if ($prom != 0) { $notaBim[$b] = round($prom / 13, 2); }
                }
                $final = round(($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id, 2);
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($section_id >= 331 And $section_id <= 343) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_active($section_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $final = 0;
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ing = 0; $prom = 0;
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) { $prom += round($ef['total_average']); }
                    foreach ($notas as $nota) {
                        switch ($nota['name']) {
                            case 'LITERATURA': $prom += round($nota['obtained_mark']); break;
                            case 'LITERATURE': $ing += round($nota['obtained_mark']); break;
                            case 'GRAMMAR': $ing += round($nota['obtained_mark']); break;
                            case 'SOCIALES': $prom += round($nota['obtained_mark']); break;
                            case 'MÚSICA': $prom += round($nota['obtained_mark']); break;
                            case 'ART. PLAST.': $prom += round($nota['obtained_mark']); break;
                            case 'MATEMÁTICA': $prom += round($nota['obtained_mark']); break;
                            case 'TEC. TECNOLÓGICA': $prom += round($nota['obtained_mark']); break;
                            case 'BIOLOGÍA': $prom += round($nota['obtained_mark']); break;
                            case 'FÍSICA': $prom += round($nota['obtained_mark']); break;
                            case 'QUÍMICA': $prom += round($nota['obtained_mark']); break;
                            case 'FILOSOFÍA': $prom += round($nota['obtained_mark']); break;
                            case 'VAL_ESP_REL': $prom += round($nota['obtained_mark']); break;
                        }
                    }
                    $prom += round($ing / 2);
                    if ($prom != 0) { $notaBim[$b] = round($prom / 13, 2); }
                }
                $final = round(($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id, 2);
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        }
        $fila = 8;
        foreach ($alumnos as $key => $row) { $aux[$key] = $row['final']; }
        array_multisort($aux, SORT_DESC, $alumnos);
        foreach ($alumnos as $key => $row) {
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $row['nombre']);
            if ($row['prom1'] != 0) { $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $row['prom1']); }
            if ($row['prom2'] != 0) { $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $row['prom2']); }
            if ($row['prom3'] != 0) { $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $row['prom3']); }
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $row['final']);
            $fila++;
        }
        $data = ["section_id" => $section_id];
        $SectionMod = new SectionModel();
        $section = $SectionMod->get_section($data);
        $fileName = 'RNK_' . $section[0]['completo'] . '.xlsx';
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A4', "GESTIÓN " . $gestion . " RANKING OFICIAL");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A5', strtoupper($section[0]['completo']));
        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('F42', 'Generado el : ' . $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);
    }

    function ranking_class($class_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $gestion = $Setting->get_gestion();
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_class($class_id);
        $conter = 8;
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $obj_PHPExcel = $obj_Reader->load('templates/rnkgrade.xlsx');
        $notaBim = array(0, 0, 0, 0, 0);
        $alumnos = [];
        if ($class_id >= 21 And $class_id <= 22) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $alumnos[] = array('nombre' => $est, 'curso' => $row['nick_name'], 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($class_id >= 23 And $class_id <= 26) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $cnat = 0; $ing = 0; $lening = 0; $prom = 0;
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) { $prom += round($ef['total_average']); }
                    foreach ($notas as $nota) {
                        if ($nota['obtained_mark'] !== null) {
                            switch ($nota['name']) {
                                case 'LENGUAJE': $lening += $nota['obtained_mark']; break;
                                case 'READING': $ing += $nota['obtained_mark']; break;
                                case 'GRAMMAR': $ing += $nota['obtained_mark']; break;
                                case 'SOCIALES': $prom += round($nota['obtained_mark']); break;
                                case 'MÚSICA': $prom += round($nota['obtained_mark']); break;
                                case 'ARTE': $prom += round($nota['obtained_mark']); break;
                                case 'MATEMÁTICA': $prom += round($nota['obtained_mark']); break;
                                case 'COMPUTACIÓN': $prom += round($nota['obtained_mark']); break;
                                case 'SCIENCE': $cnat += $nota['obtained_mark']; break;
                                case 'C. NATURALES': $cnat += $nota['obtained_mark']; break;
                                case 'F. HUMANA': $prom += round($nota['obtained_mark']); break;
                            }
                        }
                    }
                    if ($ing != 0) { $lening += round($ing / 2); }
                    $prom += round($lening / 2) + round($cnat / 2);
                    if ($prom != 0) { $notaBim[$b] = round($prom / 9, 2); }
                }
                $final = round(($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id, 2);
                $alumnos[] = array('nombre' => $est, 'curso' => $row['nick_name'], 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($class_id >= 27 And $class_id <= 28) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ing = 0; $cnat = 0; $prom = 0;
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) { $prom += round($ef['total_average']); }
                    foreach ($notas as $nota) {
                        if ($nota['obtained_mark'] !== null) {
                            switch ($nota['name']) {
                                case 'LITERATURA': $prom += round($nota['obtained_mark']); break;
                                case 'LENGUAJE': $prom += round($nota['obtained_mark']); break;
                                case 'LITERATURE': $ing += $nota['obtained_mark']; break;
                                case 'GRAMMAR': $ing += $nota['obtained_mark']; break;
                                case 'SOCIALES': $prom += round($nota['obtained_mark']); break;
                                case 'MÚSICA': $prom += round($nota['obtained_mark']); break;
                                case 'ART. PLAST.': $prom += round($nota['obtained_mark']); break;
                                case 'MATEMÁTICA': $prom += round($nota['obtained_mark']); break;
                                case 'TEC. TECNOLÓGICA': $prom += round($nota['obtained_mark']); break;
                                case 'BIOLOGÍA': $cnat += round($nota['obtained_mark'] * 0.8); break;
                                case 'FÍSICA': $cnat += round($nota['obtained_mark'] * 0.1); break;
                                case 'QUÍMICA': $cnat += round($nota['obtained_mark'] * 0.1); break;
                                case 'PSICOLOGÍA': $prom += round($nota['obtained_mark']); break;
                                case 'FILOSOFÍA': $prom += round($nota['obtained_mark']); break;
                                case 'VAL_ESP_REL': $prom += round($nota['obtained_mark']); break;
                            }
                        }
                    }
                    $prom += round($ing / 2) + round($cnat);
                    if ($prom != 0) { $notaBim[$b] = round($prom / 11, 2); }
                }
                $final = round(($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id, 2);
                $alumnos[] = array('nombre' => $est, 'curso' => $row['nick_name'], 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($class_id >= 31 And $class_id <= 32) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ing = 0; $prom = 0;
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        if ($ef['total_average'] !== null) { $prom += round($ef['total_average']); }
                    }
                    foreach ($notas as $nota) {
                        if ($nota['obtained_mark'] !== null) {
                            switch ($nota['name']) {
                                case 'LITERATURA': $prom += round($nota['obtained_mark']); break;
                                case 'LENGUAJE': $prom += round($nota['obtained_mark']); break;
                                case 'LITERATURE': $ing += $nota['obtained_mark']; break;
                                case 'GRAMMAR': $ing += $nota['obtained_mark']; break;
                                case 'SOCIALES': $prom += round($nota['obtained_mark']); break;
                                case 'MÚSICA': $prom += round($nota['obtained_mark']); break;
                                case 'ART. PLAST.': $prom += round($nota['obtained_mark']); break;
                                case 'MATEMÁTICA': $prom += round($nota['obtained_mark']); break;
                                case 'TEC. TECNOLÓGICA': $prom += round($nota['obtained_mark']); break;
                                case 'BIOLOGÍA': $prom += round($nota['obtained_mark']); break;
                                case 'FÍSICA': $prom += round($nota['obtained_mark']); break;
                                case 'QUÍMICA': $prom += round($nota['obtained_mark']); break;
                                case 'PSICOLOGÍA': $prom += round($nota['obtained_mark']); break;
                                case 'FILOSOFÍA': $prom += round($nota['obtained_mark']); break;
                                case 'VAL_ESP_REL': $prom += round($nota['obtained_mark']); break;
                            }
                        }
                    }
                    $prom += round($ing / 2);
                    if ($prom != 0) { $notaBim[$b] = round($prom / 13, 2); }
                }
                $final = round(($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id, 2);
                $alumnos[] = array('nombre' => $est, 'curso' => $row['nick_name'], 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($class_id >= 33 And $class_id <= 34) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ing = 0; $prom = 0;
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        if ($ef['total_average'] !== null) { $prom += round($ef['total_average']); }
                    }
                    foreach ($notas as $nota) {
                        if ($nota['obtained_mark'] !== null) {
                            switch ($nota['name']) {
                                case 'LITERATURA': $prom += round($nota['obtained_mark']); break;
                                case 'LITERATURE': $ing += $nota['obtained_mark']; break;
                                case 'GRAMMAR': $ing += $nota['obtained_mark']; break;
                                case 'SOCIALES': $prom += round($nota['obtained_mark']); break;
                                case 'MÚSICA': $prom += round($nota['obtained_mark']); break;
                                case 'ART. PLAST.': $prom += round($nota['obtained_mark']); break;
                                case 'MATEMÁTICA': $prom += round($nota['obtained_mark']); break;
                                case 'TEC. TECNOLÓGICA': $prom += round($nota['obtained_mark']); break;
                                case 'BIOLOGÍA': $prom += round($nota['obtained_mark']); break;
                                case 'FÍSICA': $prom += round($nota['obtained_mark']); break;
                                case 'QUÍMICA': $prom += round($nota['obtained_mark']); break;
                                case 'FILOSOFÍA': $prom += round($nota['obtained_mark']); break;
                                case 'VAL_ESP_REL': $prom += round($nota['obtained_mark']); break;
                            }
                        }
                    }
                    $prom += round($ing / 2);
                    if ($prom != 0) { $notaBim[$b] = round($prom / 13, 2); }
                }
                $final = round(($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id, 2);
                $alumnos[] = array('nombre' => $est, 'curso' => $row['nick_name'], 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        }
        $fila = 8;
        foreach ($alumnos as $key => $row) { $aux[$key] = $row['final']; }
        array_multisort($aux, SORT_DESC, $alumnos);
        foreach ($alumnos as $key => $row) {
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $row['nombre']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $row['curso']);
            if ($row['prom1'] != 0) { $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $row['prom1']); }
            if ($row['prom2'] != 0) { $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $row['prom2']); }
            if ($row['prom3'] != 0) { $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $row['prom3']); }
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $fila, $row['final']);
            $fila++;
        }
        $data = ["class_id" => $class_id];
        $SectionMod = new SectionModel();
        $section = $SectionMod->get_section($data);
        $fileName = 'RNK_' . $section[0]['grade'] . '.xlsx';
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A4', "GESTIÓN " . $gestion . " RANKING OFICIAL");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A5', strtoupper($section[0]['grade']));
        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('F102', 'Generado el : ' . $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);
    }

    function low_averages($section_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $data = ["section_id" => $section_id];
        $Section = new SectionModel();
        $curso = $Section->get_section($data);
        $page_data['section_id'] = $curso[0]['section_id'];
        $page_data['curso'] = $curso[0]['completo'];
        $Subject = new SubjectModel();
        $page_data['materias'] = $Subject->subjects_section($section_id);
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($section_id, 0);
        $page_data['students'] = $students;
        $CsamarksMod = new CsamarksModel();
        $page_data['avgs'] = $CsamarksMod->csamarks_avgs($section_id, 1);
        $page_data['notas'] = $CsamarksMod->csamarks_notes($section_id, 1);
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'low_averages';
        $page_data['page_title'] = 'Promedios más Bajos';
        return view('backend/index', $page_data);
    }

    function low_averages_xlsx($section_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $gestion = $Setting->get_gestion();
        $Subject = new SubjectModel();
        $materias = $Subject->subjects_section($section_id);
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($section_id, 0);
        $CsamarksMod = new CsamarksModel();
        $avgs = $CsamarksMod->csamarks_avgs($section_id, 1);
        $notas = $CsamarksMod->csamarks_notes($section_id, 1);
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $obj_PHPExcel = $obj_Reader->load('templates/low.xlsx');
        $obj_PHPExcel->setActiveSheetIndex(0);
        $column = 4;
        $ed_fisica = 0;
        foreach ($materias as $mat):
            if ($mat['name'] == 'E. FÍSICA') { $ed_fisica++; }
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 5, $mat['name']);
            if ($ed_fisica == 1) { $column = $column - 1; }
            $column++;
        endforeach;
        $conter = 6;
        foreach ($students as $row1):
            foreach ($avgs as $avg) {
                if ($row1['student_id'] == $avg['student_id']) {
                    $alumnos[] = array('student_id' => $row1['student_id'], 'nombre' => $row1['student'], 'promedio' => round($avg['promedio'], 2));
                    break;
                }
            }
        endforeach;
        foreach ($alumnos as $key => $row) { $aux[$key] = $row['promedio']; }
        array_multisort($aux, SORT_ASC, $alumnos);
        $nro = 0;
        $conter = 6;
        foreach ($alumnos as $key => $row):
            $nro += 1;
            $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $row['nombre']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('C' . $conter, $row['promedio']);
            $column = 4;
            $ed_fisica = 0;
            foreach ($materias as $mat):
                if ($mat['name'] == 'E. FÍSICA') { $ed_fisica++; }
                if (count($notas) > 0) {
                    foreach ($notas as $not):
                        if ($mat['subject_id'] == $not['subject_id'] AND $row['student_id'] == $not['student_id']) {
                            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, $conter, $not['total_average']);
                        }
                    endforeach;
                }
                if ($ed_fisica == 1) { $column = $column - 1; }
                $column++;
            endforeach;
            $conter++;
        endforeach;
        $data = ["section_id" => $section_id];
        $SectionMod = new SectionModel();
        $section = $SectionMod->get_section($data);
        $fileName = 'NotasBajas_' . $section[0]['completo'] . '.xlsx';
        $obj_PHPExcel->getActiveSheet()->SetCellValue('D3', strtoupper($section[0]['completo']));
        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('D4', 'Generado el : ' . $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);
    }

    function saber_hacer_xlsx($section_id = '', $phase_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());
        $CsamarksMod = new CsamarksModel();
        $notas = $CsamarksMod->saber_hacer_section($section_id, $phase_id);
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $obj_PHPExcel = $obj_Reader->load('templates/saber_hacer.xlsx');
        $obj_PHPExcel->setActiveSheetIndex(0);
        $conter = 6;
        foreach ($notas as $not):
            $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $not['student']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('C' . $conter, $not['saber']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('D' . $conter, $not['hacer']);
            $conter++;
        endforeach;
        $data = ["section_id" => $section_id];
        $SectionMod = new SectionModel();
        $section = $SectionMod->get_section($data);
        $fileName = 'SaberHacer_' . $section[0]['completo'] . '.xlsx';
        $obj_PHPExcel->getActiveSheet()->SetCellValue('B3', strtoupper($section[0]['completo']));
        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('B4', 'Generado el : ' . $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);
    }

    //******************************** END - DIRECTOR **************/

    // =========================================================================
    // ALERTAS DE CUPO TRIMESTRAL — Art.8 y Art.9 Reglamento 2026
    // =========================================================================

    public function alertas_cupo()
    {
        $session    = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        $ManagerMod   = new ManagerModel();
        $manager_data = $ManagerMod->get_manager(['manager_id' => $manager_id]);
        $manager      = $manager_data[0];

        $section_ini = (int)$manager['section_ini'];
        $section_fin = (int)$manager['section_fin'];

        // Solo aplica para primaria 3ro-6to (231-263)
        $sec_ini_prim = max($section_ini, 231);
        $sec_fin_prim = min($section_fin, 263);

        if ($sec_ini_prim > $sec_fin_prim) {
            $page_data['sin_primaria'] = true;
            $page_data['alertas']      = [];
            $page_data['resumen']      = [];
        } else {
            $page_data['sin_primaria'] = false;

            $phaseRow = \Config\Database::connect('tiquipaya')
                ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$phase_id])
                ->getRowArray();

            $CupoMod = new \App\Models\PrimCupoModel();

            // Alertas de todos los alumnos en el rango primaria
            $page_data['alertas'] = $CupoMod->alertasPendientes($sec_ini_prim, $sec_fin_prim, $phase_id);

            // Resumen de cupo por sección primaria
            $asistDB   = \Config\Database::connect('asistencia');
            $secciones = $asistDB->query(
                "SELECT section_id, nick_name, completo FROM section
                 WHERE section_id BETWEEN ? AND ? ORDER BY section_id",
                [$sec_ini_prim, $sec_fin_prim]
            )->getResultArray();

            $resumen = [];
            foreach ($secciones as $sec) {
                $filas = $CupoMod->resumenSeccion(
                    (int)$sec['section_id'],
                    $phaseRow['inicio'],
                    $phaseRow['fin']
                );
                if (!empty($filas)) {
                    $resumen[] = [
                        'seccion'  => $sec,
                        'alumnos'  => $filas,
                        'en_alerta'  => count(array_filter($filas, fn($f) => $f['total'] >= 6 && $f['total'] < 9)),
                        'en_limite'  => count(array_filter($filas, fn($f) => $f['total'] >= 9)),
                    ];
                }
            }
            $page_data['resumen']     = $resumen;
            $page_data['phase_ini']   = $phaseRow['inicio'];
            $page_data['phase_fin']   = $phaseRow['fin'];
        }

        $page_data['manager']       = $manager;
        $page_data['phase_id']      = $phase_id;
        $page_data['phase_name']    = $Setting->get_phase_name();
        $page_data['system_title']  = $Setting->get_system_title();
        $page_data['system_name']   = $Setting->get_system_name();
        $page_data['login_type']    = 'manager';
        $page_data['page_name']     = 'alertas_cupo';
        $page_data['page_title']    = 'Alertas de Cupo Trimestral';
        return view('backend/index', $page_data);
    }

    public function acta_notificar($alerta_id = 0)
    {
        $session = session();
        if ($session->get('login_type') != 'manager')
            return $this->response->setJSON(['ok' => false]);

        $CupoMod = new \App\Models\PrimCupoModel();
        $CupoMod->marcarNotificado((int)$alerta_id);
        return $this->response->setJSON(['ok' => true]);
    }

    public function acta_save()
    {
        $session    = session();
        $manager_id = $session->get('manager_id');
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        $CupoMod = new \App\Models\PrimCupoModel();
        $CupoMod->insertActa([
            'student_id'         => (int)$this->request->getPost('student_id'),
            'alerta_id'          => $this->request->getPost('alerta_id') ?: null,
            'phase_id'           => $phase_id,
            'fecha_convocatoria' => date('Y-m-d H:i:s'),
            'fecha_reunion'      => $this->request->getPost('fecha_reunion') ?: null,
            'asistio'            => (int)$this->request->getPost('asistio'),
            'observaciones'      => $this->request->getPost('observaciones'),
            'consejero_id'       => $manager_id,
        ]);

        // Marcar la alerta como notificada
        $alerta_id = $this->request->getPost('alerta_id');
        if ($alerta_id) $CupoMod->marcarNotificado((int)$alerta_id);

        $session->set('flash_message', 'Acta registrada correctamente.');
        return redirect()->to(base_url('manager/alertas_cupo'));
    }

    // ===================================================================
    // ===== ASISTENCIA PRIMARIA 3ro-6to (copiado de Secretary) =========
    // ===================================================================

    private function _primSecretaryData(): array
    {
        $session      = session();
        $esManager    = $session->get('login_type') === 'manager';
        $secretary_id = $session->get('secretary_id');
        $Setting      = new SettingModel();

        if ($esManager) {
            // Dirección Técnica Primaria (manager con level=1): rango fijo, no
            // depende de secretary_id porque esta sesión no tiene ninguno.
            $sec_ini = 231;
            $sec_fin = 263;
        } else {
            // Datos del secretario
            $SecMod = new SecretaryModel();
            $sec    = $SecMod->get_secretary(['secretary_id' => $secretary_id]);
            $sec    = !empty($sec) ? $sec[0] : [];

            // Rango de secciones primaria 3-6 para este secretario
            $sec_ini = max((int)($sec['section_ini'] ?? 231), 231);
            $sec_fin = min((int)($sec['section_fin'] ?? 263), 263);
        }

        // Secciones primaria
        $db       = \Config\Database::connect('asistencia');
        $sections = $db->query(
            "SELECT section_id, nick_name, completo FROM section
             WHERE section_id BETWEEN ? AND ? ORDER BY section_id",
            [$sec_ini, $sec_fin]
        )->getResultArray();

        // Alumnos agrupados por sección
        $students_raw = $esManager
            ? (new StudentModel())->student_manager($session->get('manager_id'))
            : (new StudentModel())->student_secretary($secretary_id);
        $grouped = [];
        foreach ($students_raw as $s) {
            if ($s['section_id'] < 231 || $s['section_id'] > 263) continue;
            $sid = $s['section_id'];
            if (!isset($grouped[$sid])) $grouped[$sid] = [];
            $grouped[$sid][] = [
                'student_id' => $s['student_id'],
                'name'       => trim($s['lastname'] . ' ' . $s['lastname2'] . ' ' . $s['name']),
            ];
        }

        // Fase activa
        $phase_id   = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();
        $phaseRow   = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$phase_id])
            ->getRowArray();

        // Cupo de todos los alumnos primaria (resumen por sección)
        $cupos_map = [];
        if ($phaseRow) {
            $CupoMod = new \App\Models\PrimCupoModel();
            foreach ($sections as $sec_row) {
                $filas = $CupoMod->resumenSeccion(
                    (int)$sec_row['section_id'],
                    $phaseRow['inicio'],
                    $phaseRow['fin']
                );
                foreach ($filas as $f) {
                    $cupos_map[$f['student_id']] = $f;
                }
            }
        }

        return [
            'secretary_id' => $secretary_id,
            'sections'     => $sections,
            'grouped'      => $grouped,
            'cupos_map'    => $cupos_map,
            'phase_id'     => $phase_id,
            'phase_name'   => $phase_name,
            'phase_ini'    => $phaseRow['inicio'] ?? null,
            'phase_fin'    => $phaseRow['fin']    ?? null,
            'system_title' => $Setting->get_system_title(),
            'system_name'  => $Setting->get_system_name(),
            'login_type'   => 'secretary',
            'account_type' => 'secretary',
        ];
    }

    /**
     * Fragmento SQL reutilizable: verdadero cuando la fecha `pa.date` de la fila
     * externa (alias `pa`) NO está cubierta por ninguna licencia de día vigente
     * (una licencia rechazada o eliminada, enviado=2/3, no cuenta como cobertura).
     */
    private function _sinLicenciaSubquery(): string
    {
        return "NOT EXISTS (
                    SELECT 1 FROM prim_licencias l
                    INNER JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
                    WHERE l.student_id = pa.student_id
                      AND l.enviado NOT IN (2, 3)
                      AND pa.date BETWEEN ld.fecha_inicio AND ld.fecha_fin
                )";
    }

    /**
     * Personal autorizado a Asistencia Primaria: secretaría, o Dirección Técnica
     * (cuenta manager con level=1 — mismo nivel que ya activa el menú "Dirección
     * Técnica" en manager/_header.php).
     */
    private function _esPersonalPrimaria(): bool
    {
        $session = session();
        $tipo = $session->get('login_type');
        if ($tipo === 'secretary') return true;
        if ($tipo === 'manager' && (int) $session->get('level') === 1) return true;
        return false;
    }

    // ── Panel General ────────────────────────────────────────────────────────

    public function prim_dashboard()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $data  = $this->_primSecretaryData();
        $db    = \Config\Database::connect('asistencia');
        $today = date('Y-m-d');

        // 1. Pendientes de aprobar (licencias por día, separadas por duración)
        $pendRow = $db->query(
            "SELECT
                SUM(CASE WHEN l.tipo_id = 1 AND ld.cantidad_dias <= 3 THEN 1 ELSE 0 END) AS dia_corta,
                SUM(CASE WHEN l.tipo_id = 1 AND ld.cantidad_dias > 3  THEN 1 ELSE 0 END) AS dia_larga,
                SUM(CASE WHEN l.tipo_id = 2 THEN 1 ELSE 0 END) AS periodo
             FROM prim_licencias l
             INNER JOIN t_student s ON s.student_id = l.student_id
             LEFT JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
             WHERE s.section_id BETWEEN 231 AND 263 AND l.enviado = 0"
        )->getRowArray();
        $data['pend_dia_corta'] = (int)($pendRow['dia_corta'] ?? 0);
        $data['pend_dia_larga'] = (int)($pendRow['dia_larga'] ?? 0);
        $data['pend_periodo']   = (int)($pendRow['periodo']   ?? 0);

        // 2. Alumnos en alerta / límite de cupo (reutiliza cupos_map ya calculado)
        $cnt_alerta = 0; $cnt_limite = 0;
        foreach ($data['cupos_map'] as $c) {
            if (!empty($c['limite9']))     $cnt_limite++;
            elseif (!empty($c['alerta6'])) $cnt_alerta++;
        }
        $data['cnt_alerta'] = $cnt_alerta;
        $data['cnt_limite'] = $cnt_limite;

        // 3. Asistencia de hoy
        $asisHoy = $db->query(
            "SELECT
                SUM(CASE WHEN pa.status=1 THEN 1 ELSE 0 END) AS presentes,
                SUM(CASE WHEN pa.status=0 THEN 1 ELSE 0 END) AS ausentes,
                SUM(CASE WHEN pa.status=2 THEN 1 ELSE 0 END) AS con_licencia,
                SUM(CASE WHEN pa.status=3 THEN 1 ELSE 0 END) AS retrasos,
                COUNT(*) AS registrados
             FROM prim_assistance pa
             INNER JOIN t_student s ON s.student_id = pa.student_id
             WHERE s.section_id BETWEEN 231 AND 263 AND pa.date = ?",
            [$today]
        )->getRowArray();
        $data['asis_hoy'] = $asisHoy ?: ['presentes'=>0,'ausentes'=>0,'con_licencia'=>0,'retrasos'=>0,'registrados'=>0];

        $data['total_alumnos'] = (int)($db->query(
            "SELECT COUNT(*) AS n FROM t_student WHERE section_id BETWEEN 231 AND 263 AND matricula > 0 AND activo = 1"
        )->getRow()->n ?? 0);

        // 3b. Asistencia de hoy por curso (¿el maestro ya pasó lista completa?)
        $seccionesRows = $db->query(
            "SELECT s.section_id, sec.nick_name,
                    COUNT(DISTINCT s.student_id) AS total_alumnos,
                    COUNT(DISTINCT pa.student_id) AS registrados
             FROM t_student s
             INNER JOIN section sec ON sec.section_id = s.section_id
             LEFT JOIN prim_assistance pa ON pa.student_id = s.student_id AND pa.date = ?
             WHERE s.section_id BETWEEN 231 AND 263 AND s.matricula > 0 AND s.activo = 1
             GROUP BY s.section_id, sec.nick_name
             ORDER BY s.section_id",
            [$today]
        )->getResultArray();

        $cursos_pendientes = [];
        $cursos_completos  = 0;
        foreach ($seccionesRows as $sr) {
            $completo = ((int)$sr['total_alumnos'] > 0 && (int)$sr['registrados'] >= (int)$sr['total_alumnos']);
            if ($completo) {
                $cursos_completos++;
            } else {
                $cursos_pendientes[] = $sr;
            }
        }
        $data['cursos_total']       = count($seccionesRows);
        $data['cursos_completos']   = $cursos_completos;
        $data['cursos_pendientes']  = $cursos_pendientes;

        // 4. Ausencias sin justificar (trimestre activo)
        $data['sin_justificar'] = 0;
        if ($data['phase_ini'] && $data['phase_fin']) {
            $data['sin_justificar'] = (int)($db->query(
                "SELECT COUNT(DISTINCT pa.student_id) AS n
                 FROM prim_assistance pa
                 INNER JOIN t_student s ON s.student_id = pa.student_id
                 WHERE s.section_id BETWEEN 231 AND 263
                   AND pa.status = 0
                   AND pa.date BETWEEN ? AND ?
                   AND " . $this->_sinLicenciaSubquery(),
                [$data['phase_ini'], $data['phase_fin']]
            )->getRow()->n ?? 0);
        }

        // 5. Cambios de recojo aprobados para hoy (separado de los pendientes)
        //    Reutiliza el mismo query que respalda la tabla de secretary/prim_cambio_recojo,
        //    filtrado al día de hoy y a los ya aprobados.
        $RecojoMod = new \App\Models\PrimCambioRecojoModel();
        $recojoHoy = $RecojoMod->listarData($today, $today, 'approved', '');
        $data['recojo_hoy']       = $recojoHoy;
        $data['recojo_hoy_count'] = count($recojoHoy);

        // 5b. Cambios de recojo pendientes de aprobar (cualquier fecha)
        $data['recojo_pend_count'] = count($RecojoMod->listarData('', '', 'pending', ''));

        $data['page_name']  = 'prim_dashboard';
        $data['page_title'] = 'Panel General — Primaria';
        return view('backend/index', $data);
    }

    // ── Asistencia del Día ───────────────────────────────────────────────────

    public function prim_asistencia()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $data = $this->_primSecretaryData();
        $data['page_name']  = 'prim_asistencia';
        $data['page_title'] = 'Asistencia del Día — Primaria';
        return view('backend/index', $data);
    }

    public function prim_asistencia_data()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $date = $this->request->getPost('date');
        if (!$date) return $this->response->setJSON([]);

        $db = \Config\Database::connect('asistencia');

        // Asistencia diaria de primaria 3-6 para la fecha
        $att_rows = $db->query(
            "SELECT pa.student_id, pa.status, pa.observation,
                    pa.registered_by_nombre, pa.registered_by_rol, pa.registered_by_fecha
             FROM prim_assistance pa
             INNER JOIN t_student s ON s.student_id = pa.student_id
             WHERE s.section_id BETWEEN 231 AND 263 AND pa.date = ?",
            [$date]
        )->getResultArray();
        $att_map = array_column($att_rows, null, 'student_id');

        // Licencias por día (ausencia completa)
        $lic_dia = $db->query(
            "SELECT l.student_id, mo.motivo, l.es_excepcion
             FROM prim_licencias l
             INNER JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
             INNER JOIN t_student s ON s.student_id = l.student_id
             INNER JOIN t_motivos mo ON mo.motivo_id = l.motivo_id
             WHERE s.section_id BETWEEN 231 AND 263
               AND ld.fecha_inicio <= ? AND ld.fecha_fin >= ?",
            [$date, $date]
        )->getResultArray();
        $lic_dia_map = array_column($lic_dia, null, 'student_id');

        // Salidas anticipadas del día
        $lic_sal = $db->query(
            "SELECT DISTINCT l.student_id, l.hora_salida, mo.motivo, l.es_excepcion
             FROM prim_licencias l
             INNER JOIN prim_licencias_periodo lp ON lp.licencias_id = l.licencias_id
             INNER JOIN t_student s ON s.student_id = l.student_id
             INNER JOIN t_motivos mo ON mo.motivo_id = l.motivo_id
             WHERE s.section_id BETWEEN 231 AND 263 AND lp.fecha = ?",
            [$date]
        )->getResultArray();
        $lic_sal_map = array_column($lic_sal, null, 'student_id');

        return $this->response->setJSON([
            'attendance' => $att_map,
            'lic_dia'    => $lic_dia_map,
            'lic_sal'    => $lic_sal_map,
        ]);
    }

    /**
     * Valida y guarda (o corrige) el registro diario de asistencia de un alumno.
     * Devuelve false si el alumno no es de primaria 3-6, si el status no es válido,
     * o si el día ya está cubierto por una licencia de día aprobada (en ese caso el
     * estado es automático y no se puede pisar a mano — hay que modificar la
     * licencia en Gestión de Licencias).
     */
    private function _guardarAsistenciaAlumno($db, int $student_id, string $date, int $status, ?string $obs, string $nombre, string $rol, ?int $registeredBy): bool
    {
        return (new \App\Models\PrimAssistancesubjectModel())
            ->guardarAsistenciaDiaria($student_id, $date, $status, $obs, $nombre, $rol, $registeredBy);
    }

    /**
     * Permite a secretaría/dirección registrar o corregir en un solo paso la
     * asistencia diaria (Presente/Ausente/Retraso) de varios alumnos de primaria,
     * en respaldo de lo que registra el profesor.
     */
    public function prim_asistencia_save_bulk()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $date = $this->request->getPost('date');
        $rows = $this->request->getPost('rows');
        if (!$date || empty($rows) || !is_array($rows)) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Datos inválidos.']);
        }

        $db     = \Config\Database::connect('asistencia');
        $nombre = $session->get('name');
        $rol    = $session->get('login_type') === 'manager' ? 'Dirección Técnica' : 'Secretaría';
        $regBy  = $session->get('secretary_id') ?: $session->get('manager_id');

        $saved    = 0;
        $omitidos = 0;

        foreach ($rows as $student_id => $row) {
            $status = (int)($row['status'] ?? -1);
            $obs    = trim($row['observation'] ?? '') ?: null;

            $ok = $this->_guardarAsistenciaAlumno($db, (int)$student_id, $date, $status, $obs, $nombre, $rol, $regBy);
            $ok ? $saved++ : $omitidos++;
        }

        if ($saved > 0) {
            $Setting  = new SettingModel();
            $phase_id = $Setting->get_phase_id();
            $phaseRow = \Config\Database::connect('tiquipaya')
                ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$phase_id])
                ->getRowArray();
            if ($phaseRow) {
                $CupoMod = new \App\Models\PrimCupoModel();
                foreach (array_keys($rows) as $sid) {
                    $CupoMod->verificarYGenerarAlertas((int)$sid, $phase_id, $phaseRow['inicio'], $phaseRow['fin']);
                }
            }
        }

        return $this->response->setJSON([
            'ok'       => true,
            'saved'    => $saved,
            'omitidos' => $omitidos,
        ]);
    }

    /**
     * Cupo consumido en el rango de fechas, por alumno, para todas las secciones
     * de primaria 3ro-6to. Reutilizado por el resumen del trimestre y la grilla del mes.
     */
    private function _cupoMapRango($db, string $fecha_ini, string $fecha_fin): array
    {
        $CupoMod  = new \App\Models\PrimCupoModel();
        $cupo_map = [];
        $seccionesRango = $db->query(
            "SELECT DISTINCT section_id FROM t_student
             WHERE section_id BETWEEN 231 AND 263 AND matricula > 0 AND activo = 1"
        )->getResultArray();
        foreach ($seccionesRango as $s) {
            $resumenSeccion = $CupoMod->resumenSeccion((int)$s['section_id'], $fecha_ini, $fecha_fin);
            foreach ($resumenSeccion as $r) {
                $cupo_map[$r['student_id']] = round((float)$r['total'], 1);
            }
        }
        return $cupo_map;
    }

    public function prim_asistencia_resumen()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $fecha_ini = $this->request->getPost('fecha_ini');
        $fecha_fin = $this->request->getPost('fecha_fin');
        if (!$fecha_ini || !$fecha_fin) return $this->response->setJSON([]);

        $db = \Config\Database::connect('asistencia');

        // Conteos de asistencia por estudiante en el rango
        $rows = $db->query(
            "SELECT pa.student_id,
                    SUM(CASE WHEN pa.status = 1 THEN 1 ELSE 0 END) AS dias_presente,
                    SUM(CASE WHEN pa.status = 0 THEN 1 ELSE 0 END) AS dias_ausente,
                    SUM(CASE WHEN pa.status = 2 THEN 1 ELSE 0 END) AS dias_licencia,
                    SUM(CASE WHEN pa.status = 3 THEN 1 ELSE 0 END) AS dias_retraso,
                    COUNT(*) AS dias_registrados
             FROM prim_assistance pa
             INNER JOIN t_student s ON s.student_id = pa.student_id
             WHERE s.section_id BETWEEN 231 AND 263
               AND pa.date BETWEEN ? AND ?
             GROUP BY pa.student_id",
            [$fecha_ini, $fecha_fin]
        )->getResultArray();
        $asis_map = array_column($rows, null, 'student_id');

        return $this->response->setJSON([
            'asistencia' => $asis_map,
            'cupo'       => $this->_cupoMapRango($db, $fecha_ini, $fecha_fin),
        ]);
    }

    /**
     * Grilla de asistencia por fecha (una columna por día) para la vista "Mes".
     * Devuelve el estado diario de cada alumno además del cupo consumido en el rango.
     */
    public function prim_asistencia_mes_data()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $fecha_ini = $this->request->getPost('fecha_ini');
        $fecha_fin = $this->request->getPost('fecha_fin');
        if (!$fecha_ini || !$fecha_fin) return $this->response->setJSON([]);

        $db = \Config\Database::connect('asistencia');

        $rows = $db->query(
            "SELECT pa.student_id, pa.date, pa.status
             FROM prim_assistance pa
             INNER JOIN t_student s ON s.student_id = pa.student_id
             WHERE s.section_id BETWEEN 231 AND 263
               AND pa.date BETWEEN ? AND ?",
            [$fecha_ini, $fecha_fin]
        )->getResultArray();

        $dias_map = [];
        foreach ($rows as $r) {
            $dias_map[$r['student_id']][$r['date']] = (int)$r['status'];
        }

        return $this->response->setJSON([
            'dias' => $dias_map,
            'cupo' => $this->_cupoMapRango($db, $fecha_ini, $fecha_fin),
        ]);
    }

    // ── Gestión de Licencias ─────────────────────────────────────────────────

    public function prim_licencias()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $data = $this->_primSecretaryData();

        $students_flat = [];
        foreach ($data['grouped'] as $sid => $alumnos) {
            foreach ($alumnos as $a) {
                $sectionLabel = '';
                foreach ($data['sections'] as $sec) {
                    if ($sec['section_id'] == $sid) { $sectionLabel = $sec['nick_name']; break; }
                }
                $students_flat[] = array_merge($a, ['section_id' => $sid, 'nick_name' => $sectionLabel]);
            }
        }

        $data['students_flat'] = $students_flat;
        $data['motivos']       = (new MotivoModel())->listarMotivos();
        $data['medios']        = (new MedioModel())->listarMedios();
        $data['parentescos']   = (new ParentescoModel())->listarParentescos();
        $data['page_name']  = 'prim_licencias';
        $data['page_title'] = 'Gestión de Licencias — Primaria';
        return view('backend/index', $data);
    }

    public function prim_licencias_periodo_add()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $data = $this->_primSecretaryData();

        $students_flat = [];
        foreach ($data['grouped'] as $sid => $alumnos) {
            foreach ($alumnos as $a) {
                $sectionLabel = '';
                foreach ($data['sections'] as $sec) {
                    if ($sec['section_id'] == $sid) { $sectionLabel = $sec['nick_name']; break; }
                }
                $students_flat[] = array_merge($a, ['section_id' => $sid, 'nick_name' => $sectionLabel]);
            }
        }

        $data['students_flat'] = $students_flat;
        $data['motivos']       = (new MotivoModel())->listarMotivos();
        $data['medios']        = (new MedioModel())->listarMedios();
        $data['parentescos']   = (new ParentescoModel())->listarParentescos();
        $data['page_name']  = 'prim_licencias_periodo_add';
        $data['page_title'] = 'Nueva Licencia por Período — Primaria';
        return view('backend/index', $data);
    }

    public function prim_licencias_data()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $search    = $this->request->getPost('search') ?? '';
        $estado    = $this->request->getPost('estado') ?? 'all';
        $Setting   = new SettingModel();
        $phaseRowL = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$Setting->get_phase_id()])
            ->getRowArray();
        $ph_ini = $phaseRowL['inicio'] ?? date('Y-m-01');
        $ph_fin = $phaseRowL['fin']    ?? date('Y-m-d');
        $fecha_ini = $this->request->getPost('fecha_ini') ?: $ph_ini;
        $fecha_fin = $this->request->getPost('fecha_fin') ?: $ph_fin;

        // Clamp al trimestre activo
        if ($fecha_ini < $ph_ini) $fecha_ini = $ph_ini;
        if ($fecha_fin > $ph_fin) $fecha_fin = $ph_fin;

        $db  = \Config\Database::connect('asistencia');
        $where = "WHERE s.section_id BETWEEN 231 AND 263";

        if ($fecha_ini && $fecha_fin) {
            $where .= " AND DATE(l.fecha_solicitud) BETWEEN " .
                $db->escape($fecha_ini) . " AND " . $db->escape($fecha_fin);
        }
        if ($estado === 'pending')  $where .= " AND l.enviado = 0";
        if ($estado === 'approved') $where .= " AND l.enviado = 1";
        if ($estado === 'rejected') $where .= " AND l.enviado = 2";
        if ($estado === 'deleted')  $where .= " AND l.enviado = 3";
        if (!empty($search)) {
            $s = $db->escapeString($search);
            $where .= " AND (CONCAT(s.lastname,' ',s.lastname2,' ',s.name) LIKE '%$s%'
                        OR sec.nick_name LIKE '%$s%' OR mo.motivo LIKE '%$s%')";
        }

        $sql = "SELECT l.licencias_id, l.student_id, l.tipo_id, l.fecha_solicitud,
                    l.enviado, l.es_excepcion, l.fraccion_cupo, l.hora_salida,
                    l.doc_pendiente, l.comprobante_medico, l.carta_solicitud,
                    l.recoge_nombre, pr.parentesco AS recoge_parentesco, l.se_reincorpora,
                    l.obs_secretaria, l.aprobado_por_nombre, l.aprobado_por_rol,
                    tl.tipo, mo.motivo, l.detalle,
                    CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                    sec.nick_name,
                    COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'), DATE_FORMAT(MIN(lp.fecha),'%d-%m-%Y')) AS inicio,
                    COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'), '') AS fin,
                    ld.cantidad_dias,
                    GROUP_CONCAT(DISTINCT per.periodo ORDER BY per.hora_inicio SEPARATOR ', ') AS periodos_nombre
                FROM prim_licencias l
                INNER JOIN t_student s       ON s.student_id = l.student_id
                INNER JOIN section sec        ON sec.section_id = s.section_id
                INNER JOIN t_tipo_licencia tl ON tl.tipo_id = l.tipo_id
                INNER JOIN t_motivos mo       ON mo.motivo_id = l.motivo_id
                LEFT JOIN prim_licencias_dia ld    ON ld.licencias_id = l.licencias_id
                LEFT JOIN prim_licencias_periodo lp ON lp.licencias_id = l.licencias_id
                LEFT JOIN periodo per          ON per.periodo_id = lp.periodo_id
                LEFT JOIN t_parentesco pr     ON pr.parentesco_id = l.recoge_parentesco_id
                $where
                GROUP BY l.licencias_id, l.student_id, l.tipo_id, l.fecha_solicitud,
                    l.enviado, l.es_excepcion, l.fraccion_cupo, l.hora_salida,
                    l.doc_pendiente, l.comprobante_medico, l.carta_solicitud,
                    l.recoge_nombre, pr.parentesco, l.se_reincorpora, l.obs_secretaria,
                    l.aprobado_por_nombre, l.aprobado_por_rol,
                    tl.tipo, mo.motivo, l.detalle,
                    s.lastname, s.lastname2, s.name, sec.nick_name,
                    ld.fecha_inicio, ld.fecha_fin, ld.cantidad_dias
                ORDER BY l.fecha_solicitud DESC
                LIMIT 500";

        $rows = $db->query($sql)->getResultArray();
        return $this->response->setJSON($rows);
    }

    public function prim_licencias_auth()
    {
        $session    = session();
        $emailSecre = $session->get('email');
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $licencia_id    = (int)$this->request->getPost('licencias_id');
        $student_id     = (int)$this->request->getPost('student_id');
        $obs_secretaria = trim($this->request->getPost('obs_secretaria') ?? '');
        $es_excepcion   = $this->request->getPost('es_excepcion');

        $PrimLicMod = new \App\Models\PrimLicenciaModel();
        $licencia   = $PrimLicMod->getLicenciaPrim($licencia_id);

        if (empty($licencia)) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Licencia no encontrada']);
        }
        $lic = $licencia[0];

        // Emails de familia y sección
        $FamilyMod     = new FamilyModel();
        $SectionMod    = new SectionModel();
        $family        = $FamilyMod->get_family_emails($lic['student_id']);
        $emailsSection = $SectionMod->section_emails($lic['section_id']);

        $email1         = $family[0]['email1']              ?? '';
        $email2         = $family[0]['email2']              ?? '';
        $emailConsejero = $emailsSection[0]['emailDocente'] ?? '';

        $inicio = $lic['tipo_id'] == 2 ? ($lic['fecha_periodo'] ?? '') : ($lic['fecha_inicio'] ?? '');
        $fin    = $lic['tipo_id'] == 2 ? ($lic['periodos_nombre'] ?? '') : ($lic['fecha_fin'] ?? '');

        $EmailMod = new EmailModel();
        $mensaje  = $EmailMod->license_auth_email(
            $lic['student'], $lic['tipo_id'], $inicio, $fin,
            $lic['detalle'], $lic['motivo'], $lic['solicitante'], $lic['fecha_solicitud']
        );

        // Inyectar nota de secretaria en el email si la escribió
        if ($obs_secretaria !== '') {
            $nota = '<div style="margin:15px 0;padding:12px 16px;background:#f0fff4;border-left:4px solid #50cd89;border-radius:4px;">'
                  . '<strong>Nota de Secretaría:</strong> ' . htmlspecialchars($obs_secretaria)
                  . '</div>';
            $mensaje = str_replace('</body>', $nota . '</body>', $mensaje);
        }

        $subject = 'Licencia aprobada: ' . $lic['motivo'] . ' — U.E. Tiquipaya';
        $to      = trim($email1 . ($email2 ? ', ' . $email2 : ''), ', ');
        $to     .= ($emailConsejero ? ', ' . $emailConsejero : '')
                 . ', ' . $emailSecre . ', saat@tiquipaya.edu.bo';

        $headers   = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'From: Secretaria <' . $emailSecre . '>';
        $mailSent  = @mail($to, $subject, $mensaje, implode("\r\n", $headers));

        $PrimLicMod->updateEnviado($licencia_id, 1);
        $datosAprobacion = [
            'aprobado_por_nombre' => $session->get('name'),
            'aprobado_por_rol'    => $session->get('login_type') === 'manager' ? 'Dirección Técnica' : 'Secretaría',
        ];
        if ($obs_secretaria !== '') $datosAprobacion['obs_secretaria'] = $obs_secretaria;
        if ($es_excepcion !== null) {
            $esExcepcionInt = (int)$es_excepcion ? 1 : 0;
            $datosAprobacion['es_excepcion']  = $esExcepcionInt;
            // Recalcular fraccion_cupo según la decisión de secretaría: si deja de ser
            // excepción, vuelve a consumir cupo (días completos para tipo día; para
            // período se recalcula la duración real de los períodos marcados, igual
            // que al crear la licencia — no se asume ½ día a ciegas).
            if ($esExcepcionInt) {
                $datosAprobacion['fraccion_cupo'] = 0;
            } elseif ($lic['tipo_id'] == 1) {
                $datosAprobacion['fraccion_cupo'] = (float)($lic['cantidad_dias'] ?? 1);
            } else {
                // Cada período cuenta como 1 hora: más de 2 períodos = 1 día completo.
                $cantPeriodos = (int) (\Config\Database::connect('asistencia')
                    ->table('prim_licencias_periodo')
                    ->where('licencias_id', $licencia_id)
                    ->countAllResults());
                $datosAprobacion['fraccion_cupo'] = ($cantPeriodos > 2) ? 1.0 : 0.5;
            }
        }
        \Config\Database::connect('asistencia')
            ->table('prim_licencias')
            ->where('licencias_id', $licencia_id)
            ->update($datosAprobacion);

        // Sincronizar prim_assistance: marcar días cubiertos como "Licencia" (status=2),
        // sin importar si el maestro ya lo había registrado como Ausente o Presente/Retraso
        // (el día completo queda excusado). Si no había ningún registro ese día, no hay
        // nada que corregir todavía; se marcará solo cuando el maestro pase lista.
        if ($lic['tipo_id'] == 1 && !empty($lic['fecha_inicio']) && !empty($lic['fecha_fin'])) {
            $dbAsis = \Config\Database::connect('asistencia');
            $dayPtr = new \DateTime($lic['fecha_inicio']);
            $dayEnd = new \DateTime($lic['fecha_fin']);
            $dayEnd->modify('+1 day');
            while ($dayPtr < $dayEnd) {
                $dbAsis->table('prim_assistance')
                    ->where('student_id', $lic['student_id'])
                    ->where('date', $dayPtr->format('Y-m-d'))
                    ->where('status !=', 2)
                    ->update(['status' => 2]);
                $dayPtr->modify('+1 day');
            }
        }

        return $this->response->setJSON([
            'ok'      => true,
            'enviado' => $mailSent,
            'msg'     => $mailSent ? '' : 'Aprobada, pero el correo no pudo enviarse.',
        ]);
    }

    public function prim_licencias_noauth()
    {
        $session    = session();
        $emailSecre = $session->get('email');
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $licencia_id    = (int)$this->request->getPost('licencias_id');
        $student_id     = (int)$this->request->getPost('student_id');
        $obs_secretaria = trim($this->request->getPost('obs_secretaria') ?? '');

        $PrimLicMod = new \App\Models\PrimLicenciaModel();
        $licencia   = $PrimLicMod->getLicenciaPrim($licencia_id);

        if (empty($licencia)) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Licencia no encontrada']);
        }
        $lic = $licencia[0];

        $FamilyMod     = new FamilyModel();
        $SectionMod    = new SectionModel();
        $family        = $FamilyMod->get_family_emails($lic['student_id']);
        $emailsSection = $SectionMod->section_emails($lic['section_id']);

        $email1         = $family[0]['email1']              ?? '';
        $email2         = $family[0]['email2']              ?? '';
        $emailConsejero = $emailsSection[0]['emailDocente'] ?? '';

        $inicio = $lic['tipo_id'] == 2 ? ($lic['fecha_periodo'] ?? '') : ($lic['fecha_inicio'] ?? '');
        $fin    = $lic['tipo_id'] == 2 ? ($lic['periodos_nombre'] ?? '') : ($lic['fecha_fin'] ?? '');

        $EmailMod = new EmailModel();
        $mensaje  = $EmailMod->license_noauth_email(
            $lic['student'], $lic['tipo_id'], $inicio, $fin,
            $lic['detalle'], $lic['motivo'], $lic['solicitante'], $lic['fecha_solicitud']
        );

        // Inyectar motivo de rechazo si lo escribió
        if ($obs_secretaria !== '') {
            $nota = '<div style="margin:15px 0;padding:12px 16px;background:#fff0f2;border-left:4px solid #f1416c;border-radius:4px;">'
                  . '<strong>Motivo del rechazo:</strong> ' . htmlspecialchars($obs_secretaria)
                  . '</div>';
            $mensaje = str_replace('</body>', $nota . '</body>', $mensaje);
        }

        $subject = 'Licencia no aprobada: ' . $lic['motivo'] . ' — U.E. Tiquipaya';
        $to      = trim($email1 . ($email2 ? ', ' . $email2 : ''), ', ');
        $to     .= ($emailConsejero ? ', ' . $emailConsejero : '')
                 . ', ' . $emailSecre . ', saat@tiquipaya.edu.bo';

        $headers   = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'From: Secretaria <' . $emailSecre . '>';
        $mailSent  = @mail($to, $subject, $mensaje, implode("\r\n", $headers));

        $PrimLicMod->updateEnviado($licencia_id, 2);
        $datosRechazo = [
            'aprobado_por_nombre' => $session->get('name'),
            'aprobado_por_rol'    => $session->get('login_type') === 'manager' ? 'Dirección Técnica' : 'Secretaría',
        ];
        if ($obs_secretaria !== '') $datosRechazo['obs_secretaria'] = $obs_secretaria;
        \Config\Database::connect('asistencia')
            ->table('prim_licencias')
            ->where('licencias_id', $licencia_id)
            ->update($datosRechazo);

        // Revertir prim_assistance: si la licencia ya estaba aprobada y marcó días
        // como "Licencia" (status=2), al rechazarla vuelven a quedar como "Ausente" (status=0)
        if ($lic['tipo_id'] == 1 && !empty($lic['fecha_inicio']) && !empty($lic['fecha_fin'])) {
            $dbAsis = \Config\Database::connect('asistencia');
            $dayPtr = new \DateTime($lic['fecha_inicio']);
            $dayEnd = new \DateTime($lic['fecha_fin']);
            $dayEnd->modify('+1 day');
            while ($dayPtr < $dayEnd) {
                $dbAsis->table('prim_assistance')
                    ->where('student_id', $lic['student_id'])
                    ->where('date', $dayPtr->format('Y-m-d'))
                    ->where('status', 2)
                    ->update(['status' => 0]);
                $dayPtr->modify('+1 day');
            }
        }

        return $this->response->setJSON([
            'ok'      => true,
            'enviado' => $mailSent,
            'msg'     => $mailSent ? '' : 'Rechazada, pero el correo no pudo enviarse.',
        ]);
    }

    public function prim_licencias_waive_doc()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $licencia_id = (int)$this->request->getPost('licencias_id');
        if (!$licencia_id)
            return $this->response->setJSON(['ok' => false, 'msg' => 'ID inválido']);

        \Config\Database::connect('asistencia')
            ->table('prim_licencias')
            ->where('licencias_id', $licencia_id)
            ->update(['doc_pendiente' => 0]);

        return $this->response->setJSON(['ok' => true]);
    }

    /**
     * Elimina (soft-delete, enviado=3) una licencia de primaria, en cualquier estado.
     * Si estaba aprobada y había marcado días como "Licencia" en prim_assistance,
     * revierte esos días a "Ausente" — igual que al rechazar una ya aprobada.
     */
    public function prim_licencias_delete()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $licencia_id = (int)$this->request->getPost('licencias_id');
        $obs         = trim($this->request->getPost('obs_secretaria') ?? '');
        if (!$licencia_id) return $this->response->setJSON(['ok' => false, 'msg' => 'ID inválido']);

        $PrimLicMod = new \App\Models\PrimLicenciaModel();
        $licencia   = $PrimLicMod->getLicenciaPrim($licencia_id);
        if (empty($licencia)) return $this->response->setJSON(['ok' => false, 'msg' => 'Licencia no encontrada']);
        $lic = $licencia[0];

        if ($lic['enviado'] == 1 && $lic['tipo_id'] == 1 && !empty($lic['fecha_inicio']) && !empty($lic['fecha_fin'])) {
            $dbAsis = \Config\Database::connect('asistencia');
            $dayPtr = new \DateTime($lic['fecha_inicio']);
            $dayEnd = new \DateTime($lic['fecha_fin']);
            $dayEnd->modify('+1 day');
            while ($dayPtr < $dayEnd) {
                $dbAsis->table('prim_assistance')
                    ->where('student_id', $lic['student_id'])
                    ->where('date', $dayPtr->format('Y-m-d'))
                    ->where('status', 2)
                    ->update(['status' => 0]);
                $dayPtr->modify('+1 day');
            }
        }

        // Queda registrado quién y cuándo eliminó, para que secretaría pueda auditar
        // qué pasó con la solicitud (visible en la pestaña "Eliminadas").
        $datosEliminacion = [
            'enviado'             => 3,
            'aprobado_por_nombre' => $session->get('name'),
            'aprobado_por_rol'    => $session->get('login_type') === 'manager' ? 'Dirección Técnica' : 'Secretaría',
        ];
        if ($obs !== '') $datosEliminacion['obs_secretaria'] = $obs;
        \Config\Database::connect('asistencia')
            ->table('prim_licencias')
            ->where('licencias_id', $licencia_id)
            ->update($datosEliminacion);

        return $this->response->setJSON(['ok' => true]);
    }

    // ── Nueva Licencia Primaria (Días) ───────────────────────────────────────

    public function prim_licencias_create()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $student_id    = (int)$this->request->getPost('student_id');
        $motivo_id     = (int)$this->request->getPost('motivo');
        $medio_id      = (int)$this->request->getPost('medio');
        $parentesco_id = (int)$this->request->getPost('parentesco');
        $solicitante   = trim($this->request->getPost('solicitante') ?? '');
        $detalle       = trim($this->request->getPost('detalle') ?? '');
        $fecha_inicio  = $this->request->getPost('fecha_inicio');
        $fecha_fin     = $this->request->getPost('fecha_fin');
        $cantidad      = (int)($this->request->getPost('cantidad') ?? 1);
        $es_excepcion  = (int)($this->request->getPost('es_excepcion') ?? 0);
        $fechaStr      = $this->request->getPost('fechaSolicita');
        $fecha_solicitud = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $fechaStr) . ':00'));

        $fraccion_cupo = $es_excepcion ? 0 : $cantidad;

        // Cupo trimestral: si el alumno ya está en el límite de 9 días, no se
        // permite registrar más licencias salvo que se marque como excepción.
        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phaseRow = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$phase_id])
            ->getRowArray();
        if (!$es_excepcion && $phaseRow) {
            $cupoActual = (new \App\Models\PrimCupoModel())->calcularCupo(
                $student_id, $phaseRow['inicio'], $phaseRow['fin']
            );
            if ($cupoActual['limite9']) {
                return $this->response->setJSON([
                    'ok'  => false,
                    'msg' => '⚠️ Este alumno ya alcanzó el límite de 9 días de licencia para este trimestre y no podrá solicitar más licencias durante el resto del trimestre. Las actividades no serán reprogramadas. Para continuar, marca esta solicitud como Excepción.',
                ]);
            }
        }

        $db = \Config\Database::connect('asistencia');
        $db->transBegin();

        $db->table('prim_licencias')->insert([
            'student_id'     => $student_id,
            'tipo_id'        => 1,
            'motivo_id'      => $motivo_id,
            'medio_id'       => $medio_id,
            'parentesco_id'  => $parentesco_id,
            'solicitante'    => $solicitante,
            'detalle'        => $detalle,
            'enviado'        => 0,
            'es_excepcion'   => $es_excepcion,
            'fraccion_cupo'  => $fraccion_cupo,
            'fecha_solicitud'=> $fecha_solicitud,
        ]);
        $licencias_id = $db->insertID();

        if ($licencias_id) {
            $db->table('prim_licencias_dia')->insert([
                'licencias_id'  => $licencias_id,
                'fecha_inicio'  => date('Y-m-d', strtotime($fecha_inicio)),
                'fecha_fin'     => date('Y-m-d', strtotime($fecha_fin)),
                'cantidad_dias' => $cantidad,
            ]);
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            $licencias_id = 0;
        } else {
            $db->transCommit();
        }

        if ($licencias_id) {
            // Verificar alertas de cupo
            if ($phaseRow && !$es_excepcion) {
                (new \App\Models\PrimCupoModel())->verificarYGenerarAlertas(
                    $student_id, $phase_id, $phaseRow['inicio'], $phaseRow['fin']
                );
            }

            return $this->response->setJSON(['ok' => true]);
        }

        return $this->response->setJSON(['ok' => false, 'msg' => 'Error al registrar la licencia.']);
    }

    // ── Nueva Licencia por Período Primaria ─────────────────────────────────

    public function prim_licencias_periodo_create()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $student_id    = (int)$this->request->getPost('student_id');
        $motivo_id     = (int)$this->request->getPost('motivo');
        $medio_id      = (int)$this->request->getPost('medio');
        $parentesco_id = (int)$this->request->getPost('parentesco');
        $solicitante   = trim($this->request->getPost('solicitante') ?? '');
        $detalle       = trim($this->request->getPost('detalle') ?? '');
        $fecha         = $this->request->getPost('fecha');
        $hora_salida   = $this->request->getPost('hora_salida') ?: null;
        $periodos      = $this->request->getPost('periodos') ?? [];
        if (!is_array($periodos)) {
            $periodos = ($periodos === null || $periodos === '') ? [] : [$periodos];
        }
        $es_excepcion  = (int)($this->request->getPost('es_excepcion') ?? 0);
        $fechaStr      = $this->request->getPost('fechaSolicita');
        $fecha_solicitud = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $fechaStr) . ':00'));
        $recoge_nombre        = trim($this->request->getPost('recoge_nombre') ?? '');
        $recoge_parentesco_id = (int)($this->request->getPost('recoge_parentesco_id') ?? 0) ?: null;
        $se_reincorpora       = $this->request->getPost('se_reincorpora') ? 1 : 0;

        if (empty($periodos)) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Debe seleccionar al menos un período.']);
        }

        // Calcular fraccion_cupo contando cada período como 1 hora: más de 2
        // períodos marcados (>2 horas) = 1 día completo de cupo, si no, ½ día.
        $fraccion_cupo = (count($periodos) > 2) ? 1.0 : 0.5;
        if ($es_excepcion) $fraccion_cupo = 0;

        // Cupo trimestral: si el alumno ya está en el límite de 9 días, no se
        // permite registrar más licencias salvo que se marque como excepción.
        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phaseRow = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$phase_id])
            ->getRowArray();
        if (!$es_excepcion && $phaseRow) {
            $cupoActual = (new \App\Models\PrimCupoModel())->calcularCupo(
                $student_id, $phaseRow['inicio'], $phaseRow['fin']
            );
            if ($cupoActual['limite9']) {
                return $this->response->setJSON([
                    'ok'  => false,
                    'msg' => '⚠️ Este alumno ya alcanzó el límite de 9 días de licencia para este trimestre y no podrá solicitar más licencias durante el resto del trimestre. Las actividades no serán reprogramadas. Para continuar, marca esta solicitud como Excepción.',
                ]);
            }
        }

        $db = \Config\Database::connect('asistencia');
        $db->transBegin();

        $db->table('prim_licencias')->insert([
            'student_id'            => $student_id,
            'tipo_id'                => 2,
            'motivo_id'              => $motivo_id,
            'medio_id'               => $medio_id,
            'parentesco_id'          => $parentesco_id,
            'solicitante'            => $solicitante,
            'detalle'                => $detalle,
            'hora_salida'            => $hora_salida,
            'enviado'                => 0,
            'es_excepcion'           => $es_excepcion,
            'fraccion_cupo'          => $fraccion_cupo,
            'fecha_solicitud'        => $fecha_solicitud,
            'recoge_nombre'          => $recoge_nombre,
            'recoge_parentesco_id'   => $recoge_parentesco_id,
            'se_reincorpora'         => $se_reincorpora,
        ]);
        $licencias_id = $db->insertID();

        if ($licencias_id) {
            foreach ($periodos as $periodo_id) {
                $db->table('prim_licencias_periodo')->insert([
                    'licencias_id' => $licencias_id,
                    'periodo_id'   => (int)$periodo_id,
                    'fecha'        => date('Y-m-d', strtotime($fecha)),
                ]);
            }
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            $licencias_id = 0;
        } else {
            $db->transCommit();
        }

        if ($licencias_id) {
            if ($phaseRow && !$es_excepcion) {
                (new \App\Models\PrimCupoModel())->verificarYGenerarAlertas(
                    $student_id, $phase_id, $phaseRow['inicio'], $phaseRow['fin']
                );
            }

            return $this->response->setJSON(['ok' => true]);
        }

        return $this->response->setJSON(['ok' => false, 'msg' => 'Error al registrar la licencia por período.']);
    }

    // ── Cambio de Recojo Primaria ────────────────────────────────────────────

    public function prim_cambio_recojo()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $data = $this->_primSecretaryData();

        $students_flat = [];
        foreach ($data['grouped'] as $sid => $alumnos) {
            foreach ($alumnos as $a) {
                $sectionLabel = '';
                foreach ($data['sections'] as $sec) {
                    if ($sec['section_id'] == $sid) { $sectionLabel = $sec['nick_name']; break; }
                }
                $students_flat[] = array_merge($a, ['section_id' => $sid, 'nick_name' => $sectionLabel]);
            }
        }

        $data['students_flat'] = $students_flat;
        $data['parentescos']   = (new ParentescoModel())->listarParentescos();
        $data['page_name']  = 'prim_cambio_recojo';
        $data['page_title'] = 'Cambio de Recojo — Primaria';
        return view('backend/index', $data);
    }

    public function prim_cambio_recojo_data()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $search    = $this->request->getPost('search') ?? '';
        $estado    = $this->request->getPost('estado') ?? 'pending';
        $fecha_ini = $this->request->getPost('fecha_ini') ?: date('Y-m-d', strtotime('-30 days'));
        $fecha_fin = $this->request->getPost('fecha_fin') ?: date('Y-m-d');

        $rows = (new \App\Models\PrimCambioRecojoModel())->listarData($fecha_ini, $fecha_fin, $estado, $search);
        return $this->response->setJSON($rows);
    }

    public function prim_cambio_recojo_auth()
    {
        $session    = session();
        $emailSecre = $session->get('email');
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $id  = (int)$this->request->getPost('cambio_id');
        $obs = trim($this->request->getPost('obs_secretaria') ?? '');
        if (!$id) return $this->response->setJSON(['ok' => false, 'msg' => 'ID inválido']);

        $RecojoMod = new \App\Models\PrimCambioRecojoModel();
        $cambio    = $RecojoMod->getCambioRecojo($id);
        if (empty($cambio)) return $this->response->setJSON(['ok' => false, 'msg' => 'Registro no encontrado']);
        $c = $cambio[0];

        $mailSent = $this->_enviarCorreoRecojo($c, $obs, true, $emailSecre);

        $RecojoMod->actualizarEstado($id, 1, $obs !== '' ? $obs : null);
        return $this->response->setJSON([
            'ok'      => true,
            'enviado' => $mailSent,
            'msg'     => $mailSent ? '' : 'Aprobado, pero el correo no pudo enviarse.',
        ]);
    }

    public function prim_cambio_recojo_noauth()
    {
        $session    = session();
        $emailSecre = $session->get('email');
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $id  = (int)$this->request->getPost('cambio_id');
        $obs = trim($this->request->getPost('obs_secretaria') ?? '');
        if (!$id) return $this->response->setJSON(['ok' => false, 'msg' => 'ID inválido']);

        $RecojoMod = new \App\Models\PrimCambioRecojoModel();
        $cambio    = $RecojoMod->getCambioRecojo($id);
        if (empty($cambio)) return $this->response->setJSON(['ok' => false, 'msg' => 'Registro no encontrado']);
        $c = $cambio[0];

        $mailSent = $this->_enviarCorreoRecojo($c, $obs, false, $emailSecre);

        $RecojoMod->actualizarEstado($id, 2, $obs !== '' ? $obs : null);
        return $this->response->setJSON([
            'ok'      => true,
            'enviado' => $mailSent,
            'msg'     => $mailSent ? '' : 'Rechazado, pero el correo no pudo enviarse.',
        ]);
    }

    /**
     * Envía el correo de aprobación/rechazo de un aviso de cambio de recojo.
     * Reutiliza el mismo patrón de destinatarios que las licencias (familia + consejero + secretaría).
     */
    private function _enviarCorreoRecojo(array $c, string $obs, bool $aprobado, string $emailSecre): bool
    {
        $FamilyMod     = new FamilyModel();
        $SectionMod    = new SectionModel();
        $family        = $FamilyMod->get_family_emails($c['student_id']);
        $emailsSection = $SectionMod->section_emails($c['section_id']);

        $email1         = $family[0]['email1']              ?? '';
        $email2         = $family[0]['email2']              ?? '';
        $emailConsejero = $emailsSection[0]['emailDocente'] ?? '';

        $detalleTexto = $c['tipo'] == 1
            ? 'Recogerá otra persona: ' . $c['persona_nombre'] . ($c['persona_parentesco'] ? ' (' . $c['persona_parentesco'] . ')' : '')
            : ($c['tipo'] == 2 ? 'No usará transporte escolar' : ('Otro: ' . $c['detalle']));

        $EmailMod = new EmailModel();
        $mensaje  = $aprobado
            ? $EmailMod->recojo_auth_email($c['student'], $detalleTexto, $c['solicitante'], $c['fecha'], $obs)
            : $EmailMod->recojo_noauth_email($c['student'], $detalleTexto, $c['solicitante'], $c['fecha'], $obs);

        $subject = $aprobado
            ? 'Cambio de Recojo aprobado — U.E. Tiquipaya'
            : 'Cambio de Recojo no aprobado — U.E. Tiquipaya';
        $to  = trim($email1 . ($email2 ? ', ' . $email2 : ''), ', ');
        $to .= ($emailConsejero ? ', ' . $emailConsejero : '') . ', ' . $emailSecre . ', saat@tiquipaya.edu.bo';

        $headers   = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'From: Secretaria <' . $emailSecre . '>';
        return @mail($to, $subject, $mensaje, implode("\r\n", $headers));
    }

    public function prim_cambio_recojo_create()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $student_id             = (int)$this->request->getPost('student_id');
        $tipo                   = (int)$this->request->getPost('tipo');
        $solicitante            = trim($this->request->getPost('solicitante') ?? '');
        $parentesco_id          = (int)$this->request->getPost('parentesco');
        $persona_nombre         = trim($this->request->getPost('persona_nombre') ?? '');
        $persona_parentesco_id  = (int)($this->request->getPost('persona_parentesco_id') ?? 0);
        $persona_parentesco_otro= trim($this->request->getPost('persona_parentesco_otro') ?? '');
        $detalle                = trim($this->request->getPost('detalle') ?? '');

        if (!$student_id || !in_array($tipo, [1, 2, 3], true) || $solicitante === '' || !$parentesco_id) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Complete todos los campos requeridos.']);
        }
        if ($tipo === 1 && ($persona_nombre === '' || (!$persona_parentesco_id && $persona_parentesco_otro === ''))) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Indique el nombre y parentesco de la persona que recogerá al estudiante.']);
        }
        if ($tipo === 3 && $detalle === '') {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Describa el motivo del cambio.']);
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
            'enviado'                => 1,
            'obs_secretaria'         => 'Registrado por secretaría vía llamada telefónica.',
            'fecha_solicitud'        => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['ok' => true]);
    }

    public function prim_cupo_estudiante()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $student_id = (int)$this->request->getPost('student_id');
        if (!$student_id)
            return $this->response->setJSON(['error' => 'Sin student_id']);

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phaseRow = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$phase_id])
            ->getRowArray();

        if (!$phaseRow)
            return $this->response->setJSON(['error' => 'Sin fase activa']);

        $cupo = (new \App\Models\PrimCupoModel())->calcularCupo(
            $student_id, $phaseRow['inicio'], $phaseRow['fin']
        );
        return $this->response->setJSON($cupo);
    }

    // ── Retrasos Primaria ────────────────────────────────────────────────────

    public function prim_retrasos_create()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $student_id   = (int)$this->request->getPost('student_id');
        $section_id   = (int)$this->request->getPost('section_id');
        $fecha        = $this->request->getPost('fecha');
        $hora_entrada = $this->request->getPost('hora_entrada') ?: null;
        $motivo       = trim($this->request->getPost('motivo') ?? 'Sin información');
        $detalle      = trim($this->request->getPost('detalle') ?? '');

        if (!$student_id || !$fecha) {
            return $this->response->setJSON(['ok' => false, 'msg' => 'Datos incompletos.']);
        }

        $phase_id = (new SettingModel())->get_phase_id();

        $db = \Config\Database::connect('asistencia');
        $db->table('prim_retrasos')->insert([
            'student_id'   => $student_id,
            'section_id'   => $section_id,
            'fecha'        => $fecha,
            'hora_entrada' => $hora_entrada,
            'motivo'       => $motivo,
            'detalle'      => $detalle,
            'phase_id'     => $phase_id,
            'created_by'   => $session->get('secretary_id') ?: $session->get('manager_id'),
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['ok' => true]);
    }

    public function prim_retrasos_data()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $db = \Config\Database::connect('asistencia');

        $fecha_ini = $this->request->getPost('fecha_ini') ?: date('Y-m-d');
        $fecha_fin = $this->request->getPost('fecha_fin') ?: date('Y-m-d');
        $search    = $this->request->getPost('search') ?? '';

        $where  = "WHERE r.fecha BETWEEN ? AND ?";
        $params = [$fecha_ini, $fecha_fin];
        if (!empty($search)) {
            $s = $db->escapeString($search);
            $where .= " AND (CONCAT(st.lastname,' ',st.lastname2,' ',st.name) LIKE '%$s%'
                        OR s.nick_name LIKE '%$s%' OR r.motivo LIKE '%$s%')";
        }

        $rows = $db->query(
            "SELECT r.fecha, r.hora_entrada, r.motivo, r.detalle,
                    s.nick_name AS curso,
                    CONCAT(st.lastname,' ',st.lastname2,' ',st.name) AS alumno
             FROM prim_retrasos r
             INNER JOIN section s    ON s.section_id  = r.section_id
             INNER JOIN t_student st ON st.student_id = r.student_id
             $where
             ORDER BY r.fecha DESC, r.hora_entrada",
            $params
        )->getResultArray();

        return $this->response->setJSON($rows);
    }

    public function prim_retrasos()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $data = $this->_primSecretaryData();

        $students_flat = [];
        foreach ($data['grouped'] as $sid => $alumnos) {
            foreach ($alumnos as $a) {
                $sectionLabel = '';
                foreach ($data['sections'] as $sec) {
                    if ($sec['section_id'] == $sid) { $sectionLabel = $sec['nick_name']; break; }
                }
                $students_flat[] = array_merge($a, ['section_id' => $sid, 'nick_name' => $sectionLabel]);
            }
        }
        $data['students_flat'] = $students_flat;

        $db  = \Config\Database::connect('asistencia');

        $data['retrasos_trimestre'] = $db->query(
            "SELECT st.student_id,
                    CONCAT(st.lastname,' ',st.lastname2,' ',st.name) AS alumno,
                    s.nick_name AS curso,
                    COUNT(*) AS total_retrasos,
                    MIN(r.fecha) AS primera_fecha,
                    MAX(r.fecha) AS ultima_fecha
             FROM prim_retrasos r
             INNER JOIN section s    ON s.section_id  = r.section_id
             INNER JOIN t_student st ON st.student_id = r.student_id
             WHERE r.phase_id = ?
             GROUP BY r.student_id, st.lastname, st.lastname2, st.name, s.nick_name
             ORDER BY total_retrasos DESC, alumno",
            [$data['phase_id']]
        )->getResultArray();

        $data['page_name']  = 'prim_retrasos';
        $data['page_title'] = 'Retrasos — Primaria';
        return view('backend/index', $data);
    }

    public function prim_retraso_count()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        $student_id = (int)$this->request->getPost('student_id');
        if (!$student_id)
            return $this->response->setJSON(['count' => 0]);

        $phase_id = (new SettingModel())->get_phase_id();
        $db       = \Config\Database::connect('asistencia');
        $row      = $db->query(
            "SELECT COUNT(*) AS n FROM prim_retrasos WHERE student_id = ? AND phase_id = ?",
            [$student_id, $phase_id]
        )->getRowArray();

        return $this->response->setJSON(['count' => (int)($row['n'] ?? 0)]);
    }

    // ── Ausencias sin Licencia ───────────────────────────────────────────────

    public function prim_ausencias()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $data = $this->_primSecretaryData();
        $data['page_name']  = 'prim_ausencias';
        $data['page_title'] = 'Ausencias sin Licencia — Primaria';
        return view('backend/index', $data);
    }

    public function prim_ausencias_data()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return $this->response->setStatusCode(403);

        // Usar fechas del trimestre activo como fallback
        $Setting  = new SettingModel();
        $phaseRow = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$Setting->get_phase_id()])
            ->getRowArray();
        $phase_ini_default = $phaseRow['inicio'] ?? date('Y-m-01');
        $phase_fin_default = $phaseRow['fin']    ?? date('Y-m-d');

        $fecha_ini = $this->request->getPost('fecha_ini') ?: $phase_ini_default;
        $fecha_fin = $this->request->getPost('fecha_fin') ?: $phase_fin_default;

        // Clamp: no permitir fechas fuera del trimestre activo
        if ($fecha_ini < $phase_ini_default) $fecha_ini = $phase_ini_default;
        if ($fecha_fin > $phase_fin_default) $fecha_fin = $phase_fin_default;
        $search = $this->request->getPost('search') ?? '';
        $todos  = $this->request->getPost('todos') == '1';

        $db = \Config\Database::connect('asistencia');

        $where_search = '';
        if (!empty($search)) {
            $s = $db->escapeString($search);
            $where_search = " AND (CONCAT(s.lastname,' ',s.lastname2,' ',s.name) LIKE '%$s%'
                              OR sec.nick_name LIKE '%$s%')";
        }

        if ($todos) {
            // Vista "Todos los alumnos": roster completo con su cupo del trimestre,
            // sin filtrar por ausencias/rango de fechas.
            $sql = "SELECT
                        s.student_id,
                        CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                        sec.nick_name, sec.section_id,
                        NULL AS fechas, 0 AS total_ausencias
                    FROM t_student s
                    INNER JOIN section sec ON sec.section_id = s.section_id
                    WHERE sec.section_id BETWEEN 231 AND 263
                      AND s.matricula > 0 AND s.activo = 1
                      $where_search
                    ORDER BY sec.section_id, s.lastname, s.lastname2, s.name";
            $rows = $db->query($sql)->getResultArray();
        } else {
            // Ausencias sin licencia por alumno en el rango de fechas
            $sql = "SELECT
                        s.student_id,
                        CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                        sec.nick_name, sec.section_id,
                        GROUP_CONCAT(pa.date ORDER BY pa.date ASC SEPARATOR ',') AS fechas,
                        COUNT(pa.date) AS total_ausencias
                    FROM prim_assistance pa
                    INNER JOIN t_student s   ON s.student_id = pa.student_id
                    INNER JOIN section sec   ON sec.section_id = s.section_id
                    WHERE pa.status = 0
                      AND pa.date BETWEEN ? AND ?
                      AND sec.section_id BETWEEN 231 AND 263
                      AND " . $this->_sinLicenciaSubquery() . "
                      $where_search
                    GROUP BY s.student_id, s.lastname, s.lastname2, s.name, sec.nick_name, sec.section_id
                    ORDER BY total_ausencias DESC, sec.section_id, s.lastname";
            $rows = $db->query($sql, [$fecha_ini, $fecha_fin])->getResultArray();
        }

        // Agregar cupo del trimestre activo a cada alumno (una consulta por sección,
        // reutilizando PrimCupoModel::resumenSeccion en vez de calcularCupo fila por fila)
        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phaseRow = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$phase_id])
            ->getRowArray();

        if ($phaseRow && $rows) {
            $CupoMod  = new \App\Models\PrimCupoModel();
            $cupo_map = [];
            foreach (array_unique(array_column($rows, 'section_id')) as $sid) {
                $filas = $CupoMod->resumenSeccion((int)$sid, $phaseRow['inicio'], $phaseRow['fin']);
                foreach ($filas as $f) {
                    $cupo_map[$f['student_id']] = $f;
                }
            }
            foreach ($rows as &$row) {
                $c = $cupo_map[$row['student_id']] ?? null;
                $row['cupo_total']    = $c['total']         ?? 0;
                $row['cupo_restante'] = $c['cupo_restante'] ?? 9;
                $row['alerta6']       = $c['alerta6']       ?? false;
                $row['limite9']       = $c['limite9']       ?? false;
            }
            unset($row);
        }

        return $this->response->setJSON($rows);
    }

    /**
     * Exporta a un .xlsx real las ausencias sin licencia (o el roster completo con
     * cupo, si viene todos=1), respetando los mismos filtros que la vista en pantalla.
     */
    public function prim_ausencias_xlsx()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $Setting  = new SettingModel();
        $phaseRow = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin, name FROM phase WHERE phase_id = ?", [$Setting->get_phase_id()])
            ->getRowArray();
        $phase_ini_default = $phaseRow['inicio'] ?? date('Y-m-01');
        $phase_fin_default = $phaseRow['fin']    ?? date('Y-m-d');

        $fecha_ini = $this->request->getGet('fecha_ini') ?: $phase_ini_default;
        $fecha_fin = $this->request->getGet('fecha_fin') ?: $phase_fin_default;
        if ($fecha_ini < $phase_ini_default) $fecha_ini = $phase_ini_default;
        if ($fecha_fin > $phase_fin_default) $fecha_fin = $phase_fin_default;
        $search = $this->request->getGet('search') ?? '';
        $todos  = $this->request->getGet('todos') == '1';

        $db = \Config\Database::connect('asistencia');

        $where_search = '';
        if (!empty($search)) {
            $s = $db->escapeString($search);
            $where_search = " AND (CONCAT(s.lastname,' ',s.lastname2,' ',s.name) LIKE '%$s%'
                              OR sec.nick_name LIKE '%$s%')";
        }

        if ($todos) {
            $sql = "SELECT
                        s.student_id,
                        CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                        sec.nick_name, sec.section_id,
                        NULL AS fechas, 0 AS total_ausencias
                    FROM t_student s
                    INNER JOIN section sec ON sec.section_id = s.section_id
                    WHERE sec.section_id BETWEEN 231 AND 263
                      AND s.matricula > 0 AND s.activo = 1
                      $where_search
                    ORDER BY sec.section_id, s.lastname, s.lastname2, s.name";
            $rows = $db->query($sql)->getResultArray();
        } else {
            $sql = "SELECT
                        s.student_id,
                        CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS student,
                        sec.nick_name, sec.section_id,
                        GROUP_CONCAT(pa.date ORDER BY pa.date ASC SEPARATOR ',') AS fechas,
                        COUNT(pa.date) AS total_ausencias
                    FROM prim_assistance pa
                    INNER JOIN t_student s   ON s.student_id = pa.student_id
                    INNER JOIN section sec   ON sec.section_id = s.section_id
                    WHERE pa.status = 0
                      AND pa.date BETWEEN ? AND ?
                      AND sec.section_id BETWEEN 231 AND 263
                      AND " . $this->_sinLicenciaSubquery() . "
                      $where_search
                    GROUP BY s.student_id, s.lastname, s.lastname2, s.name, sec.nick_name, sec.section_id
                    ORDER BY total_ausencias DESC, sec.section_id, s.lastname";
            $rows = $db->query($sql, [$fecha_ini, $fecha_fin])->getResultArray();
        }

        // Cupo del trimestre activo para cada alumno
        $cupo_map = [];
        if ($rows) {
            $CupoMod = new \App\Models\PrimCupoModel();
            foreach (array_unique(array_column($rows, 'section_id')) as $sid) {
                foreach ($CupoMod->resumenSeccion((int)$sid, $phase_ini_default, $phase_fin_default) as $f) {
                    $cupo_map[$f['student_id']] = $f;
                }
            }
        }

        $ss = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sh = $ss->getActiveSheet();
        $sh->setTitle('Ausencias sin licencia');

        $sh->fromArray(['#', 'Alumno', 'Curso', 'Días sin licencia', 'Fechas ausentes', 'Cupo trimestral (rango)'], null, 'A1');
        $sh->getStyle('A1:F1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sh->getStyle('A1:F1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1A73E8');

        $r = 2;
        foreach ($rows as $i => $row) {
            $cupoTotal = $cupo_map[$row['student_id']]['total'] ?? 0;
            $fechas = $row['fechas']
                ? implode(', ', array_map(fn($f) => date('d-m-Y', strtotime($f)), explode(',', $row['fechas'])))
                : '—';
            $sh->fromArray([
                $i + 1,
                $row['student'],
                $row['nick_name'],
                (int)$row['total_ausencias'],
                $fechas,
                number_format((float)$cupoTotal, 1) . '/9',
            ], null, "A{$r}");
            $r++;
        }

        foreach (range('A', 'F') as $col) {
            $sh->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Ausencias_sin_licencia_' . date('Y-m-d_His') . '.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($ss, 'Xlsx');
        $writer->save($fileName);
        return $this->response->download($fileName, null);
    }

    // ── Reportes ─────────────────────────────────────────────────────────────

    public function prim_reportes()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $data = $this->_primSecretaryData();
        $data['page_name']  = 'prim_reportes';
        $data['page_title'] = 'Reportes — Primaria';
        return view('backend/index', $data);
    }

    public function prim_reportes_xlsx()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $tipo      = $this->request->getPost('tipo');
        $Setting   = new SettingModel();
        $phase_id  = $Setting->get_phase_id();
        $phaseRow  = \Config\Database::connect('tiquipaya')
            ->query("SELECT inicio, fin, name FROM phase WHERE phase_id = ?", [$phase_id])
            ->getRowArray();
        $ph_ini   = $phaseRow['inicio'] ?? date('Y-m-01');
        $ph_fin   = $phaseRow['fin']    ?? date('Y-m-d');
        $ph_name  = $phaseRow['name']   ?? '';

        $f_ini = $this->request->getPost('fecha_ini') ?: $ph_ini;
        $f_fin = $this->request->getPost('fecha_fin') ?: $ph_fin;
        if ($f_ini < $ph_ini) $f_ini = $ph_ini;
        if ($f_fin > $ph_fin) $f_fin = $ph_fin;

        $db     = \Config\Database::connect('asistencia');
        $titulo = '';
        $html   = '';
        $estados_lic = [0 => 'Pendiente', 1 => 'Aprobada', 2 => 'Rechazada', 3 => 'Eliminada'];

        $ss  = new Spreadsheet();
        $sh  = $ss->getActiveSheet();

        // Estilo de encabezado
        $hdrFont = ['bold' => true, 'color' => ['rgb' => 'FFFFFF']];
        $hdrFill = ['fillType' => 'solid', 'color' => ['rgb' => '1BC5BD']];
        $hdr = ['font' => $hdrFont, 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1A73E8']]];

        switch ($tipo) {

            case 'asistencia_alumno':
                $student_id = (int)$this->request->getPost('student_id');
                $stu = $db->query(
                    "SELECT CONCAT(lastname,' ',lastname2,' ',name) AS nombre, section_id FROM t_student WHERE student_id=?",
                    [$student_id]
                )->getRowArray();
                $sec = $db->query("SELECT nick_name FROM section WHERE section_id=?",
                    [$stu['section_id'] ?? 0])->getRowArray();
                $rows = $db->query(
                    "SELECT date, status, arrival_time, observation
                     FROM prim_assistance WHERE student_id=? AND date BETWEEN ? AND ?
                     ORDER BY date",
                    [$student_id, $f_ini, $f_fin]
                )->getResultArray();
                $estados = [0=>'Ausente',1=>'Presente',2=>'Licencia',3=>'Retraso'];
                $titulo  = 'Asistencia — ' . ($stu['nombre'] ?? '') . ' (' . ($sec['nick_name'] ?? '') . ')';
                $sub     = "Período: " . date('d-m-Y', strtotime($f_ini)) . " → " . date('d-m-Y', strtotime($f_fin));
                $html  = "<table><thead><tr><th>#</th><th>Fecha</th><th>Estado</th><th>Llegada</th><th>Observación</th></tr></thead><tbody>";
                foreach ($rows as $i => $row) {
                    $cls = $row['status'] == 0 ? 'ausente' : ($row['status'] == 2 ? 'licencia' : '');
                    $html .= "<tr class='$cls'><td>" . ($i+1) . "</td><td>" . date('d-m-Y', strtotime($row['date'])) .
                             "</td><td>" . ($estados[$row['status']] ?? '') . "</td><td>" .
                             substr($row['arrival_time'] ?? '', 0, 5) . "</td><td>" .
                             htmlspecialchars($row['observation'] ?? '') . "</td></tr>";
                }
                $html .= "</tbody></table>";
                break;

            case 'licencias':
                $estado_post = $this->request->getPost('estado') ?? 'all';
                $where = "WHERE s.section_id BETWEEN 231 AND 263
                           AND DATE(l.fecha_solicitud) BETWEEN '$f_ini' AND '$f_fin'";
                if ($estado_post === 'pending')  $where .= " AND l.enviado=0";
                if ($estado_post === 'approved') $where .= " AND l.enviado=1";
                if ($estado_post === 'rejected') $where .= " AND l.enviado=2";
                $rows = $db->query("
                    SELECT CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS alumno,
                        sec.nick_name, tl.tipo, mo.motivo, l.detalle, l.es_excepcion,
                        l.fraccion_cupo, l.fecha_solicitud, l.enviado,
                        COALESCE(DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y'),'') AS inicio,
                        COALESCE(DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y'),'') AS fin,
                        ld.cantidad_dias
                    FROM prim_licencias l
                    INNER JOIN t_student s       ON s.student_id=l.student_id
                    INNER JOIN section sec        ON sec.section_id=s.section_id
                    INNER JOIN t_tipo_licencia tl ON tl.tipo_id=l.tipo_id
                    INNER JOIN t_motivos mo       ON mo.motivo_id=l.motivo_id
                    LEFT JOIN prim_licencias_dia ld ON ld.licencias_id=l.licencias_id
                    $where GROUP BY l.licencias_id ORDER BY l.fecha_solicitud DESC
                ")->getResultArray();
                $titulo = 'Licencias — Primaria 3ro–6to';
                $sub    = date('d-m-Y', strtotime($f_ini)) . " → " . date('d-m-Y', strtotime($f_fin));
                $html  = "<table><thead><tr><th>#</th><th>Alumno</th><th>Curso</th><th>Tipo</th><th>Motivo</th><th>Período</th><th>Cupo</th><th>Estado</th><th>Fecha solicitud</th></tr></thead><tbody>";
                foreach ($rows as $i => $row) {
                    $periodo = $row['inicio'] . ($row['fin'] && $row['fin'] !== $row['inicio'] ? " → " . $row['fin'] : '');
                    $cupo    = $row['es_excepcion'] ? 'Excepción' : number_format((float)$row['fraccion_cupo'], 1) . ' día(s)';
                    $est_cls = $row['enviado'] == 1 ? 'aprobada' : ($row['enviado'] == 2 ? 'rechazada' : '');
                    $html   .= "<tr class='$est_cls'><td>" . ($i+1) . "</td><td>" . htmlspecialchars($row['alumno']) .
                               "</td><td>" . $row['nick_name'] . "</td><td>" . $row['tipo'] . "</td><td>" .
                               htmlspecialchars($row['motivo']) . ($row['es_excepcion'] ? ' ⭐' : '') . "</td><td>" .
                               $periodo . "</td><td>" . $cupo . "</td><td>" . ($estados_lic[$row['enviado']] ?? '') .
                               "</td><td>" . date('d-m-Y H:i', strtotime($row['fecha_solicitud'])) . "</td></tr>";
                }
                $html .= "</tbody></table>";
                break;

            case 'ausencias':
                $rows = $db->query("
                    SELECT CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS alumno, sec.nick_name,
                        GROUP_CONCAT(DATE_FORMAT(pa.date,'%d-%m-%Y') ORDER BY pa.date SEPARATOR ', ') AS fechas,
                        COUNT(pa.date) AS total
                    FROM prim_assistance pa
                    INNER JOIN t_student s ON s.student_id=pa.student_id
                    INNER JOIN section sec ON sec.section_id=s.section_id
                    WHERE pa.status=0 AND pa.date BETWEEN ? AND ?
                      AND sec.section_id BETWEEN 231 AND 263
                      AND NOT EXISTS (
                          SELECT 1 FROM prim_licencias l
                          INNER JOIN prim_licencias_dia ld ON ld.licencias_id=l.licencias_id
                          WHERE l.student_id=pa.student_id
                            AND pa.date BETWEEN ld.fecha_inicio AND ld.fecha_fin
                      )
                    GROUP BY pa.student_id ORDER BY total DESC, sec.section_id, s.lastname
                ", [$f_ini, $f_fin])->getResultArray();
                $titulo = 'Ausencias sin Licencia — Primaria 3ro–6to';
                $sub    = date('d-m-Y', strtotime($f_ini)) . " → " . date('d-m-Y', strtotime($f_fin));
                $html  = "<table><thead><tr><th>#</th><th>Alumno</th><th>Curso</th><th>Días</th><th>Fechas ausentes</th></tr></thead><tbody>";
                foreach ($rows as $i => $row) {
                    $cls   = $row['total'] >= 9 ? 'ausente' : ($row['total'] >= 6 ? 'alerta' : '');
                    $html .= "<tr class='$cls'><td>" . ($i+1) . "</td><td>" . htmlspecialchars($row['alumno']) .
                             "</td><td>" . $row['nick_name'] . "</td><td><strong>" . $row['total'] . "</strong></td><td>" .
                             $row['fechas'] . "</td></tr>";
                }
                $html .= "</tbody></table>";
                break;

            case 'cupo':
            default:
                $CupoMod = new \App\Models\PrimCupoModel();
                $secRows = $db->query(
                    "SELECT section_id, nick_name FROM section WHERE section_id BETWEEN 231 AND 263 ORDER BY section_id"
                )->getResultArray();
                $titulo = 'Cupo Trimestral — Primaria 3ro–6to';
                $sub    = "$ph_name: " . date('d-m-Y', strtotime($ph_ini)) . " → " . date('d-m-Y', strtotime($ph_fin));
                $html  = "<table><thead><tr><th>#</th><th>Alumno</th><th>Curso</th><th>Ausencias</th><th>Licencias</th><th>Salidas</th><th>Total</th><th>Restante</th><th>Estado</th></tr></thead><tbody>";
                $n = 1;
                foreach ($secRows as $sec) {
                    foreach ($CupoMod->resumenSeccion((int)$sec['section_id'], $ph_ini, $ph_fin) as $al) {
                        $total  = (float)$al['total'];
                        $estado = $total >= 9 ? 'LÍMITE' : ($total >= 6 ? 'ALERTA' : 'OK');
                        $cls    = $total >= 9 ? 'ausente' : ($total >= 6 ? 'alerta' : '');
                        $html  .= "<tr class='$cls'><td>$n</td><td>" . htmlspecialchars($al['student']) .
                                  "</td><td>" . $sec['nick_name'] . "</td><td>" . $al['ausencias_puras'] .
                                  "</td><td>" . $al['dias_licencia'] . "</td><td>" . $al['salidas_anticipadas'] .
                                  "</td><td><strong>" . number_format($total, 1) . "/9</strong></td><td>" .
                                  number_format(max(0, 9-$total), 1) . "</td><td>$estado</td></tr>";
                        $n++;
                    }
                }
                $html .= "</tbody></table>";
                break;
        }

        // Retornar HTML imprimible con auto-print
        return $this->response->setBody($this->_reporteHtml($titulo, $sub ?? '', $html, $ph_name));
    }

    private function _reporteHtml(string $titulo, string $subtitulo, string $tabla, string $trimestre): string
    {
        return <<<HTML
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
<title>{$titulo}</title>
<style>
  body   { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; color: #222; }
  h2     { margin: 0 0 2px; font-size: 14px; }
  .sub   { color: #666; font-size: 11px; margin-bottom: 12px; }
  .logo  { font-weight: bold; font-size: 13px; color: #1a73e8; }
  table  { width: 100%; border-collapse: collapse; margin-top: 8px; }
  th     { background: #1a73e8; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
  td     { padding: 5px 8px; border-bottom: 1px solid #e0e0e0; }
  tr:nth-child(even) td { background: #f8f9fc; }
  tr.ausente td { background: #fff0f2 !important; }
  tr.alerta  td { background: #fffde7 !important; }
  tr.licencia td { background: #e8f4ff !important; }
  tr.aprobada td { background: #e8fff3 !important; }
  tr.rechazada td { background: #fff0f2 !important; }
  .pie   { margin-top: 16px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
  @media print {
    .no-print { display: none; }
    body { margin: 10px; }
  }
</style>
</head><body>
<div class="no-print" style="margin-bottom:12px;">
  <button onclick="window.print()" style="background:#1a73e8;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;font-weight:bold;font-size:13px;">
    🖨️ Imprimir / Guardar PDF
  </button>
  <button onclick="window.close()" style="background:#eee;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;margin-left:8px;">
    Cerrar
  </button>
</div>
<div class="logo">U.E. Tiquipaya — Sistema SAAT</div>
<h2>{$titulo}</h2>
<div class="sub">{$subtitulo} &nbsp;·&nbsp; {$trimestre} &nbsp;·&nbsp; Generado: {$this->_now()}</div>
{$tabla}
<div class="pie">Sistema SAAT · U.E. Tiquipaya · Reporte generado automáticamente</div>
</body></html>
HTML;
    }

    private function _now(): string
    {
        return date('d-m-Y H:i');
    }
}
