<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\FamilyModel;
use App\Models\StudentModel;
use App\Models\TeacherModel;
use App\Models\SectionModel;
use App\Models\AssistanceModel;
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
            $key = $row['materia'] . '||' . $row['teacher_id'];
            if (!isset($by_grade[$grade][$key])) {
                $by_grade[$grade][$key] = [
                    'materia' => $row['materia'],
                    'docente' => $row['docente'],
                    'teacher_id' => $row['teacher_id'],
                    'personal_email' => $row['personal_email'],
                    'grado' => $row['grado'],
                    'class_id' => $row['class_id'],
                    'secciones' => [],
                    'has_any_pdf' => false,
                ];
            }
            $filename = 'CC_' . $row['subject_id'] . '_' . $phase_id . '.pdf';
            $has_pdf = file_exists($upload_path . $filename);
            $by_grade[$grade][$key]['secciones'][] = [
                'seccion' => $row['seccion'],
                'subject_id' => $row['subject_id'],
                'has_pdf' => $has_pdf,
                'pdf_file' => $filename,
            ];
            if ($has_pdf) {
                $by_grade[$grade][$key]['has_any_pdf'] = true;
            }
        }

        $total_uploaded = 0;
        $total_pending = 0;
        $teachers_uploaded = [];
        $teachers_pending = [];

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

        $page_data['by_grade'] = $by_grade;
        $page_data['total_uploaded'] = $total_uploaded;
        $page_data['total_pending'] = $total_pending;
        $page_data['teachers_uploaded'] = $teachers_uploaded;
        $page_data['teachers_pending'] = $teachers_pending;

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

        //Assistancedate_idphase_id
        $AssisMod = new AssistanceModel();
        //$licencias = $AssisMod->studentsSection($subjects[0]['section_id'], $teacher_id);
        //$page_data['licencias']  = $licencias;
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

        //Assistance
        $AssisMod = new AssistanceModel();
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

        // Incidencias por estudiante + materia (filtramos via subquery para evitar JOIN cross-db)
        $inc_raw = $db->query("
            SELECT bl.student_id, bl.subject_id, COUNT(bl.id) AS total
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id AND bt.type = 'negative'
            WHERE bl.student_id IN (
                SELECT student_id FROM tiqui0_tiquiasis26.t_student WHERE section_id = ?
            )
            GROUP BY bl.student_id, bl.subject_id
        ", [$section_id])->getResultArray();

        // Matriz: student_id => subject_id => count
        $matriz = [];
        foreach ($inc_raw as $row) {
            $matriz[$row['student_id']][$row['subject_id']] = (int)$row['total'];
        }

        // Total por estudiante y stats globales
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

        // Detalle de incidencias negativas
        $detalle = $db->query("
            SELECT bt.name AS tipo, bt.icon, bt.points,
                   ad.date_class AS fecha,
                   IFNULL(sub.name, '—') AS materia,
                   bl.observation
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt   ON bl.behavior_type_id = bt.id
            JOIN tiqui0_tiquiasis26.attendance_dates ad ON bl.date_id = ad.date_id
            LEFT JOIN tiqui0_tiquiasis26.subject sub    ON bl.subject_id = sub.subject_id
            WHERE bl.student_id = ? AND bt.type = 'negative'
            ORDER BY ad.date_class DESC
        ", [$student_id])->getResultArray();

        // Resumen por tipo
        $por_tipo = $db->query("
            SELECT bt.name AS tipo, bt.icon, COUNT(*) AS total
            FROM tiqui0_tiquiweb26.behavior_log bl
            JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id
            WHERE bl.student_id = ? AND bt.type = 'negative'
            GROUP BY bt.id
            ORDER BY total DESC
        ", [$student_id])->getResultArray();

        return $this->response->setJSON([
            'student' => $student,
            'detalle' => $detalle,
            'por_tipo' => $por_tipo,
            'total' => count($detalle),
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
        if ($session->get('login_type') != 'manager')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $Self = new SelfappraisalModel();
        $rows = $Self->pending_all($page_data['phase_id']);

        // Group by section
        $por_curso = [];
        foreach ($rows as $row) {
            $por_curso[$row['completo']][] = $row;
        }
        $page_data['por_curso'] = $por_curso;
        $page_data['page_name'] = 'self_director';
        $page_data['page_title'] = 'Autoevaluaciones Pendientes';
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
        $teacher_id = $session->get('teacher_id');
        if ($session->get('adviser'))
            return redirect()->to(base_url());

        //Section
        $data = ["director_id" => $teacher_id];
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
}

