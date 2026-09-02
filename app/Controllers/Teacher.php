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
use App\Models\PrimLicenciaModel;
use App\Models\PrimAssistancesubjectModel;
use App\Models\PrimCupoModel;
use App\Models\AbsenceModel;
use App\Models\IinfractionModel;
use App\Models\IcriteriaModel;
use App\Models\ItypesfoulsModel;
use App\Models\ParentModel;
use App\Models\DelayModel;
use App\Models\ScoreModel;
use App\Models\BehaviorModel;
use App\Models\IncidenciaModel;
use App\Models\BoletaModel;
use App\Models\InterviewModel;
use App\Models\EvaluationModel;
use App\Models\EhcModel;
use App\Models\NotaDescargoModel;

class Teacher extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        helper('grade');
    }

    public function index()
    {
        //
    }

    // Temporary DB Maintenance
    function update_db_structure()
    {
        $db = \Config\Database::connect();
        try {
            // 1. Check daily_scores period column
            $exists = false;
            // Check if table exists first
            $tableExists = $db->tableExists('daily_scores');

            if (!$tableExists) {
                // Create daily_scores table
                $sql = "CREATE TABLE IF NOT EXISTS daily_scores (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    student_id INT(11) NOT NULL,
                    subject_id INT(11) NOT NULL,
                    date_id INT(11) NOT NULL,
                    score INT(11) DEFAULT 100,
                    period VARCHAR(10) DEFAULT '1',
                    PRIMARY KEY (id)
                )";
                $db->query($sql);
                echo "Table 'daily_scores' created.<br>";
            } else {
                $fields = $db->getFieldData('daily_scores');
                foreach ($fields as $field) {
                    if ($field->name === 'period') {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $db->query("ALTER TABLE daily_scores ADD COLUMN period VARCHAR(10) DEFAULT '1' AFTER subject_id");
                    echo "Column 'period' added to daily_scores.<br>";
                }
            }

            // 2b. Restore: Create table safely if not exists
            $sql = "CREATE TABLE IF NOT EXISTS behavior_types (
            id INT(11) NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            icon VARCHAR(50) NOT NULL,
            points INT(11) NOT NULL,
            type ENUM('positive', 'negative', 'neutral') NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $db->query($sql);

            // Ensure ENUM is updated if table already exists
            try {
                $db->query("ALTER TABLE behavior_types MODIFY COLUMN type ENUM('positive', 'negative', 'neutral') NOT NULL");
            } catch (\Exception $e) {
            }

            echo "Table 'behavior_types' check/create done.<br>";

            // 3. Strict 8-Item Enforce (User Request - WhatsApp Style Emojis)
            // ID 1: No present.. (-5) -> ❌
            // ID 2: Participacion Positiva (+5) -> ⭐
            // ID 3: Llegada tardia (-5) -> ⏰
            // ID 4: Comer en clases (-5) -> 🍔
            // ID 5: Uso de celular (-5) -> 📱
            // ID 6: Indisciplina/ruido (-5) -> 📢
            // ID 7: Uniforme incompleto (-5) -> 👔
            // ID 8: Otro (-5) -> 📌

            $items = [
                [1, 'No present&oacute; tarea', '&#128221;', -5, 'negative'],    // 📝 (Memo)
                [2, 'Participaci&oacute;n Positiva', '&#127775;', 5, 'positive'], // 🌟 (Glowing Star)
                [3, 'Llegada tard&iacute;a', '&#9200;', -5, 'negative'],          // ⏰ (Alarm Clock)
                [4, 'Comer en clases', '&#127828;', -5, 'negative'],              // 🍔 (Hamburger)
                [5, 'Uso de celular', '&#128241;', -5, 'negative'],               // 📱 (Mobile Phone)
                [6, 'Indisciplina/ruido', '&#128227;', -5, 'negative'],           // 📢 (Megaphone)
                [7, 'Uniforme incompleto', '&#128085;', -5, 'negative'],          // 👕 (T-Shirt)
                [8, 'Otro', '&#128204;', -5, 'negative'],                         // 📌 (Pushpin)
                [9, 'Olvid&oacute; su material', '&#127890;', -5, 'negative'],    // 🎒 (Backpack - using School Satchel entity)
                [10, 'Enfermer&iacute;a', '&#127973;', 0, 'neutral'],             // 🏥 (Hospital)
                [11, 'Salida al Ba&ntilde;o', '&#128701;', 0, 'neutral']          // 🚽 (Toilet)
            ];

            foreach ($items as $item) {
                $id = $item[0];
                $name = $db->escape($item[1]);
                $icon = $db->escape($item[2]);
                $points = $item[3];
                $type = $db->escape($item[4]);

                $sql = "INSERT INTO behavior_types (id, name, icon, points, type) VALUES ($id, $name, $icon, $points, $type)
                        ON DUPLICATE KEY UPDATE name=$name, icon=$icon, points=$points, type=$type";
                $db->query($sql);
            }

            // Ensure ENUM and charset are correct
            $db->query("ALTER TABLE behavior_types MODIFY COLUMN type ENUM('positive', 'negative', 'neutral') NOT NULL");
            $db->query("ALTER TABLE behavior_types CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            // Cleanup checks: Remove any ID > 11 to ensure EXACTLY 11 items
            try {
                $db->query("DELETE FROM behavior_types WHERE id > 11");
            } catch (\Exception $e) {
                // Ignore
            }

            echo "Database updated: Strict 11 Criteria with Emojis Enforced.<br>";

            $sql_create_log = "CREATE TABLE IF NOT EXISTS behavior_log (
                id INT(11) NOT NULL AUTO_INCREMENT,
                student_id INT(11) NOT NULL,
                behavior_type_id INT(11) NOT NULL,
                subject_id INT(11) NOT NULL,
                date_id INT(11) NOT NULL,
                observation TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $db->query($sql_create_log);

            // Ensure observation column exists if table was already there
            $logFields = $db->getFieldData('behavior_log');
            $obsExists = false;
            foreach ($logFields as $f) {
                if ($f->name === 'observation') {
                    $obsExists = true;
                    break;
                }
            }
            if (!$obsExists) {
                $db->query("ALTER TABLE behavior_log ADD COLUMN observation TEXT AFTER date_id");
            }

            echo "Table 'behavior_log' check/create done.<br>";

            // Ensure period column exists in behavior_log
            $logFields = $db->getFieldData('behavior_log');
            $periodExists = false;
            foreach ($logFields as $f) {
                if ($f->name === 'period') {
                    $periodExists = true;
                    break;
                }
            }
            if (!$periodExists) {
                $db->query("ALTER TABLE behavior_log ADD COLUMN period VARCHAR(10) DEFAULT '1' AFTER date_id");
                echo "Column 'period' added to behavior_log.<br>";
            }

            // 6. Create system_feedback table
            $sql_feedback = "CREATE TABLE IF NOT EXISTS system_feedback (
                id INT(11) NOT NULL AUTO_INCREMENT,
                user_id INT(11) NOT NULL,
                user_type VARCHAR(50) NOT NULL,
                comment TEXT NOT NULL,
                url VARCHAR(255),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $db->query($sql_feedback);
            echo "Table 'system_feedback' check/create done.<br>";

            // 5. Create interviews table
            $sql_create_interviews = "CREATE TABLE IF NOT EXISTS interviews (
                interview_id INT(11) NOT NULL AUTO_INCREMENT,
                student_id INT(11) NOT NULL,
                teacher_id INT(11) NOT NULL,
                section_id INT(11) NOT NULL,
                assistant VARCHAR(255) NOT NULL,
                reason VARCHAR(50) NOT NULL,
                description TEXT,
                agreements TEXT,
                attachment VARCHAR(255),
                date DATETIME DEFAULT CURRENT_TIMESTAMP,
                follow_up_date DATE,
                status INT(11) DEFAULT 1,
                PRIMARY KEY (interview_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
            $db->query($sql_create_interviews);
            echo "Table 'interviews' check/create done.<br>";

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        // Tabla de Evaluaciones
        $t_evaluations = "CREATE TABLE IF NOT EXISTS evaluations (
            evaluation_id INT(11) NOT NULL AUTO_INCREMENT,
            subject_id INT(11) NOT NULL,
            section_id INT(11) NOT NULL,
            teacher_id INT(11) NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            date DATE NOT NULL,
            type VARCHAR(50) DEFAULT 'exam',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (evaluation_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $db->query($t_evaluations);

    }

    public function dashboard()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $Document = new DocumentModel();
        $horario = $Document->document_link("horario", "teacher", $session->get('teacher_id'));
        if (isset($horario[0]->link)) {
            $page_data['horario'] = $horario[0]->link;
        } else {
            $page_data['horario'] = '0';
        }
        $carpeta = $Document->document_link("carpeta", "teacher", $session->get('teacher_id'));
        if (isset($carpeta[0]->link)) {
            $page_data['carpeta'] = $carpeta[0]->link;
        } else {
            $page_data['carpeta'] = '0';
        }
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Dashboard";
        $page_data['page_name'] = "dashboard";
        return view('backend/index', $page_data);
    }

    public function students_list()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $teacher_id = $session->get('teacher_id');
        $Setting = new SettingModel();
        $Section = new SectionModel();
        $Student = new StudentModel();

        // Get sections for the teacher
        $sections = $Section->section_docente($teacher_id);

        $grouped_students = [];
        foreach ($sections as $sec) {
            $section_id = $sec['section_id'];
            $students = $Student->studentsSection($section_id, $teacher_id);
            $grouped_students[$sec['nick_name']] = [
                'section_id' => $section_id,
                'completo' => $sec['completo'],
                'students' => $students
            ];
        }

        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Lista de Estudiantes";
        $page_data['page_name'] = "students_list";
        $page_data['grouped_students'] = $grouped_students;

        return view('backend/index', $page_data);
    }

    public function error()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Error";
        $page_data['page_name'] = "error_5";
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
    /****Protocolo de CLASES Virtual****/
    function class_protocol()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
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
    function content_letter()
    {
        $session = session();
        $Subject = new SubjectModel();
        $raw     = $Subject->subjects_docente($session->get('teacher_id'));

        // Group secondary subjects by (materia, class_id); keep primary individual.
        // canonical_id = MIN subject_id of the group (used as filename key).
        $grouped = [];
        foreach ($raw as $row) {
            $is_sec = stripos($row->grade ?? '', 'secundaria') !== false;
            if ($is_sec) {
                $gkey = $row->materia . '||' . $row->class_id;
                if (!isset($grouped[$gkey])) {
                    $grouped[$gkey] = [
                        'canonical_id' => $row->subject_id,
                        'materia'      => $row->materia,
                        'nivel'        => $row->grade,
                    ];
                } else {
                    if ($row->subject_id < $grouped[$gkey]['canonical_id']) {
                        $grouped[$gkey]['canonical_id'] = $row->subject_id;
                    }
                }
            } else {
                $gkey = 'P_' . $row->subject_id;
                $grouped[$gkey] = [
                    'canonical_id' => $row->subject_id,
                    'materia'      => $row->materia,
                    'nivel'        => $row->curso,
                ];
            }
        }

        $Setting = new SettingModel();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['materias']     = $grouped;
        $page_data['page_name']    = 'content_letter';
        $page_data['page_title']   = 'Cartas de Contenidos';
        return view('backend/index', $page_data);
    }
    function upfile_letter($param1 = '')
    {
        $session = session();
        $Setting = new SettingModel();
        //RECUPERAMOS EL BIMESTRE ACTUAL
        $phase_id = $Setting->get_phase_id();
        $subject_id = $param1;
        $nomArchivo = "CC_" . $subject_id . "_" . $phase_id . ".pdf";
        //Eliminamos archivo para reemplazar
        $nombre_fichero = $_SERVER['DOCUMENT_ROOT'] . "/plataforma/public/uploads/content_letter/" . $nomArchivo;
        if (file_exists($nombre_fichero)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . "/plataforma/public/uploads/content_letter/" . $nomArchivo);
        }
        $validationRule = [
            'userfile' => [
                'uploaded[file]',
                'mime_in[file,application/pdf]'
            ]
        ];
        if (!$this->validate($validationRule)) {
            $page_data['errors'] = $this->validator->getErrors();
            //$session->set('flash_message_error', var_dump($this->validator->getErrors()));
            //return redirect()->to(base_url().'/'.$session->get('login_type').'/content_letter/');
        }
        $archivoFile = $this->request->getFile('userfile');

        if (!$archivoFile->hasMoved()) {
            $archivoFile->move($_SERVER['DOCUMENT_ROOT'] . "/plataforma/public/uploads/content_letter/", $nomArchivo);
            $session->set('flash_message', 'Archivo Cargado correctamente');
            return redirect()->to(base_url() . '/' . $session->get('login_type') . '/content_letter/');
        } else {
            $session->set('flash_message_error', 'Error al cargar');
            return redirect()->to(base_url() . '/' . $session->get('login_type') . '/content_letter/');
        }
    }
    function upfile_letter_trim($subject_id = 0, $trim = 0)
    {
        $session = session();
        $subject_id = (int)$subject_id;
        $trim       = (int)$trim;

        if (!$subject_id || !in_array($trim, [1, 2, 3])) {
            $session->set('flash_message_error', 'Parámetros inválidos.');
            return redirect()->to(base_url() . '/' . $session->get('login_type') . '/content_letter');
        }

        $nomArchivo  = "CC_{$subject_id}_T{$trim}.pdf";
        $uploadPath  = FCPATH . 'uploads/content_letter/';

        if (file_exists($uploadPath . $nomArchivo)) {
            unlink($uploadPath . $nomArchivo);
        }

        $archivoFile = $this->request->getFile('userfile');

        if ($archivoFile && $archivoFile->isValid() && !$archivoFile->hasMoved()) {
            if (strtolower($archivoFile->getClientExtension()) === 'pdf') {
                $archivoFile->move($uploadPath, $nomArchivo);
                $session->set('flash_message', 'Carta del Trimestre ' . $trim . ' cargada correctamente.');
            } else {
                $session->set('flash_message_error', 'Solo se permiten archivos PDF.');
            }
        } else {
            $session->set('flash_message_error', 'Error al cargar el archivo. Asegúrese de seleccionar un PDF.');
        }

        return redirect()->to(base_url() . '/' . $session->get('login_type') . '/content_letter');
    }
    function pdcs()
    {
        $session = session();
        $Subject = new SubjectModel();


        $materias = $Subject->subjects_docente($session->get('teacher_id'));

        //$page_data['teacher_id'] = $this->session->userdata('teacher_id');
        $Setting = new SettingModel();

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['materias'] = $materias;
        $page_data['page_name'] = 'pdcs';
        $page_data['page_title'] = 'Plan de Dasarrollo curricular';
        return view('backend/index', $page_data);
    }
    function upfile_pdcs($param1 = '')
    {
        $session = session();
        $Setting = new SettingModel();
        //RECUPERAMOS EL BIMESTRE ACTUAL
        $phase_id = $Setting->get_phase_id();
        $subject_id = $param1;
        //$subject_id = $_POST['subject_id'];
        $nomArchivo = "PDC_" . $subject_id . "_" . $phase_id . ".pdf";
        //Eliminamos archivo para reemplazar
        $nombre_fichero = $_SERVER['DOCUMENT_ROOT'] . "/plataforma/public/uploads/PDCs/" . $nomArchivo;
        if (file_exists($nombre_fichero)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . "/plataforma/public/uploads/PDCs/" . $nomArchivo);
        }
        $validationRule = [
            'userfile' => [
                'uploaded[file]',
                'mime_in[file,application/pdf]'
            ]
        ];
        if (!$this->validate($validationRule)) {
            $page_data['errors'] = $this->validator->getErrors();
            //$session->set('flash_message_error', var_dump($this->validator->getErrors()));
            //return redirect()->to(base_url().'/'.$session->get('login_type').'/content_letter/');
        }
        $archivoFile = $this->request->getFile('userfile');

        if (!$archivoFile->hasMoved()) {
            $archivoFile->move($_SERVER['DOCUMENT_ROOT'] . "/plataforma/public/uploads/PDCs/", $nomArchivo);
            $session->set('flash_message', 'Archivo Cargado correctamente');
            return redirect()->to(base_url() . '/' . $session->get('login_type') . '/pdcs/');
        } else {
            $session->set('flash_message_error', 'Error al cargar');
            return redirect()->to(base_url() . '/' . $session->get('login_type') . '/pdcs/');
        }
    }
    /****************************************ASSISTENCE ***************************************/
    function assistance()
    {
        $session = session();
        $Subject = new SubjectModel();

        $Section = new SectionModel();
        $page_data['cursos'] = $Section->section_docente($session->get('teacher_id'));
        $subjects = $Subject->subjects_teacher($session->get('teacher_id'));

        //$page_data['teacher_id'] = $this->session->userdata('teacher_id');
        $Setting = new SettingModel();

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['subjects'] = $subjects;
        $page_data['page_name'] = 'assistance';
        $page_data['page_title'] = 'Asistencias';
        return view('backend/index', $page_data);
    }
    function attendance($subject_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');

        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subject_section($subject_id);
        if (empty($subjects) || (int)$subjects[0]['teacher_id'] !== (int)$teacher_id) {
            return redirect()->to(base_url());
        }
        $page_data['curso'] = $subjects[0]['nick_name'] . " - " . $subjects[0]['name'];
        $page_data['subject_id'] = $subject_id;
        $section_id = $subjects[0]['section_id'];

        $Setting = new SettingModel();

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        if ($section_id < 231) {
            return $this->attendance_inicial($subject_id);
        } else {
            $page_data['page_name'] = 'attendance';
            $page_data['page_title'] = 'Asistencia';
            return view('backend/index', $page_data);
        }
    }
    function attendance_inicial($subject_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');

        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subject_section($subject_id);
        $page_data['curso'] = $subjects[0]['nick_name'] . " - " . $subjects[0]['name'];
        $page_data['subject_id'] = $subject_id;

        $Setting = new SettingModel();

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'attendance_inicial';
        $page_data['page_title'] = 'Asistencia';
        return view('backend/index', $page_data);
    }

    function student_profile($student_id, $subject_id)
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');

        // Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        // Student Data
        $StudentMod = new StudentModel();
        $student = $StudentMod->find($student_id);

        // Detect primaria 3-6 for teacher-level profile
        $asistDbProf = \Config\Database::connect('asistencia');
        $sectionInfoProf = $asistDbProf->query(
            "SELECT sec.grade, sec.nick_name FROM t_student s JOIN section sec ON sec.section_id = s.section_id WHERE s.student_id = ?",
            [$student_id]
        )->getRowArray();
        $gradeProf      = $sectionInfoProf['grade'] ?? '';
        $nickProf       = $sectionInfoProf['nick_name'] ?? '';
        $esPrimaria36   = isPrimaria36($gradeProf);
        $sectionIdProf  = (int)($student['section_id'] ?? 0);

        // Subject Data
        $SubjectMod = new SubjectModel();
        if ($esPrimaria36) {
            // Get teacher name for the title
            $teacherRow = $asistDbProf->query(
                "SELECT t.name as teacher_name FROM tiqui0_tiquisaat26.teacher t WHERE t.teacher_id = ?",
                [$teacher_id]
            )->getRowArray();
            $page_data['subject_name'] = 'Todas las materias';
            $page_data['curso'] = $nickProf . ' — Prof. ' . ($teacherRow['teacher_name'] ?? '');
        } elseif ($subject_id > 0) {
            $subjects = $SubjectMod->subject_section($subject_id);
            $page_data['subject_name'] = $subjects[0]['name'];
            $page_data['curso'] = $subjects[0]['nick_name'] . " - " . $subjects[0]['name'];
        } else {
            $page_data['subject_name'] = 'Historial General';
            $page_data['curso'] = $student['lastname'] . ' ' . $student['name'];
        }

        $IncidenciaMod = new IncidenciaModel();

        if ($esPrimaria36) {
            $logs = $IncidenciaMod->getRegistroEstudianteByTeacher($student_id, $teacher_id, $sectionIdProf, $page_data['phase_id']);
        } else {
            $logs = $IncidenciaMod->getRegistroEstudiante($student_id, $page_data['phase_id'], $subject_id > 0 ? $subject_id : null);
        }

        // Merge boletas as "falta grave" entries
        $BoletaMod = new BoletaModel();
        // Para primaria 3-6 traer todas las boletas (recreo + aula de cualquier materia del alumno)
        $boletaSubjectFilter = ($esPrimaria36 || !($subject_id > 0)) ? null : $subject_id;
        $boletas = $BoletaMod->getBoletasEstudiante($student_id, $page_data['phase_id'], $boletaSubjectFilter);
        foreach ($boletas as $b) {
            $logs[] = [
                'id'                  => 'boleta_' . $b['id'],
                'source'              => 'boleta',
                'incidencia_tipo_id'  => null,
                'nombre'              => 'Boleta Verde – Falta Grave',
                'icono'               => '🟢',
                'tipo'                => 'grave',
                'observacion'         => $b['descripcion'],
                'subject_name'        => $b['subject_name'] ?? ($b['tipo'] === 'recreo' ? 'Todas las materias' : null),
                'created_at'          => $b['fecha'] . ' 00:00:00',
                'fecha'               => $b['fecha'],
                'dias_suspension'     => (int)$b['dias_suspension'],
                'medidas_restaurativas' => $b['medidas_restaurativas'],
            ];
        }
        // Sort merged array by date descending
        usort($logs, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));
        $page_data['logs'] = $logs;

        // Daily Logistics (Neutral type: Enfermería/Baño)
        $currentDate = $this->request->getGet('date') ?: date('Y-m-d');
        $page_data['logistics'] = $IncidenciaMod->getLogisticasHoy($student_id, $currentDate, $page_data['phase_id']);

        // Puntos del Ser
        if ($esPrimaria36) {
            $conteosPerfil = $IncidenciaMod->getConteosByTeacher($student_id, $teacher_id, $sectionIdProf, $page_data['phase_id']);
            $page_data['puntos_del_ser'] = $conteosPerfil['nota'];
        } else {
            $page_data['puntos_del_ser'] = $subject_id > 0
                ? $IncidenciaMod->calcularNota($student_id, $subject_id, $page_data['phase_id'])
                : 10;
        }

        // Chart Data
        $behaviorCounts = [];
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount  = 0;
        $graveCount    = 0;
        foreach ($logs as $log) {
            if ($log['tipo'] === 'grave') {
                $graveCount++;
                continue;
            }
            $bid = $log['incidencia_tipo_id'];
            if (!isset($behaviorCounts[$bid])) {
                $behaviorCounts[$bid] = [
                    'nombre' => $log['nombre'],
                    'icono'  => $log['icono'],
                    'count'  => 0,
                    'tipo'   => $log['tipo']
                ];
            }
            $behaviorCounts[$bid]['count']++;
            if ($log['tipo'] === 'positiva') {
                $positiveCount++;
            } elseif ($log['tipo'] === 'negativa') {
                $negativeCount++;
            } else {
                $neutralCount++;
            }
        }
        if ($graveCount > 0) {
            $behaviorCounts['grave'] = [
                'nombre' => 'Boleta Verde – Falta Grave',
                'icono'  => '🟢',
                'count'  => $graveCount,
                'tipo'   => 'grave',
            ];
        }

        $page_data['positive_incidents'] = $positiveCount;
        $page_data['negative_incidents'] = $negativeCount;
        $page_data['neutral_incidents']  = $neutralCount;
        $page_data['grave_incidents']    = $graveCount;
        $page_data['behavior_counts']    = $behaviorCounts;

        // PRIMER TRIMESTRE: datos del sistema antiguo (behavior_log / tiqui0_tiquiweb26)
        // Para primaria 3-6 no filtramos por materia; para otros niveles filtramos por subject_id
        $BehaviorMod = new BehaviorModel();
        $rawLogsT1 = $BehaviorMod->getStudentLog($student_id, null, ($esPrimaria36 || !($subject_id > 0)) ? null : $subject_id);

        $logsT1 = [];
        foreach ($rawLogsT1 as $log) {
            $logsT1[] = [
                'id'                 => $log['id'],
                'nombre'             => html_entity_decode($log['name'], ENT_QUOTES, 'UTF-8'),
                'icono'              => html_entity_decode($log['icon'], ENT_QUOTES, 'UTF-8'),
                'tipo'               => $log['type'] === 'positive' ? 'positiva' : ($log['type'] === 'negative' ? 'negativa' : 'neutral'),
                'observacion'        => $log['observation'],
                'subject_name'       => $log['subject_name'],
                'created_at'         => $log['created_at'],
                'incidencia_tipo_id' => null,
                'source'             => 'behavior_log',
            ];
        }

        $t1BehaviorCounts = [];
        $t1Positive = 0;
        $t1Negative = 0;
        $t1Neutral  = 0;
        foreach ($logsT1 as $log) {
            $key = $log['nombre'];
            if (!isset($t1BehaviorCounts[$key])) {
                $t1BehaviorCounts[$key] = ['nombre' => $log['nombre'], 'icono' => $log['icono'], 'count' => 0, 'tipo' => $log['tipo']];
            }
            $t1BehaviorCounts[$key]['count']++;
            if ($log['tipo'] === 'positiva') $t1Positive++;
            elseif ($log['tipo'] === 'negativa') $t1Negative++;
            else $t1Neutral++;
        }

        $page_data['logs_t1']            = $logsT1;
        $page_data['t1_positive']        = $t1Positive;
        $page_data['t1_negative']        = $t1Negative;
        $page_data['t1_neutral']         = $t1Neutral;
        $page_data['t1_behavior_counts'] = $t1BehaviorCounts;
        $page_data['active_tab']         = $page_data['phase_id'] >= 2 ? 't2' : 't1';

        // Alertas activas
        $alertas = [];
        if ($esPrimaria36) {
            // Una sola alerta a nivel de maestro
            $notaProf = $page_data['puntos_del_ser'];
            if ($notaProf <= 8) {
                $nivel = $notaProf < 7 ? 'danger' : ($notaProf < 8 ? 'warning' : 'info');
                $alertas[] = [
                    'materia'    => 'Todas las materias',
                    'subject_id' => 0,
                    'nota'       => $notaProf,
                    'nivel'      => $nivel,
                ];
            }
        } else {
            $SubjectMod2 = new SubjectModel();
            $materias = $SubjectMod2->subjects_student($student['section_id'], $student['sex']);
            foreach ($materias as $mat) {
                $nota = $IncidenciaMod->calcularNota($student_id, $mat['subject_id'], $page_data['phase_id']);
                if ($nota <= 8) {
                    $nivel = $nota < 7 ? 'danger' : ($nota < 8 ? 'warning' : 'info');
                    $alertas[] = [
                        'materia'    => $mat['name'],
                        'subject_id' => $mat['subject_id'],
                        'nota'       => $nota,
                        'nivel'      => $nivel,
                    ];
                }
            }
            usort($alertas, fn($a, $b) => $a['nota'] <=> $b['nota']);
        }
        $page_data['alertas'] = $alertas;

        $page_data['student']    = $student;
        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'student_profile';
        $page_data['page_title'] = 'Perfil del Estudiante';

        return view('backend/index', $page_data);
    }

    function incidence_register()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $teacher_id = $session->get('teacher_id');

        $SubjectMod = new SubjectModel();
        $subjects   = $SubjectMod->subjects_teacher($teacher_id);

        $IncidenciaMod = new IncidenciaModel();

        $Setting = new SettingModel();
        $page_data['phase_id']    = $Setting->get_phase_id();
        $page_data['phase_name']  = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['subjects']    = $subjects;
        $page_data['tipos']       = $IncidenciaMod->getTipos();
        $tiposGrouped = $IncidenciaMod->getTiposGrouped();
        $page_data['tipos_negativa'] = $tiposGrouped['negativa'];
        $page_data['tipos_positiva'] = $tiposGrouped['positiva'];
        $page_data['tipos_neutral']  = $tiposGrouped['neutral'];
        $page_data['page_name']   = 'incidence_register';
        $page_data['page_title']  = 'Registrar Incidencia';

        return view('backend/index', $page_data);
    }

    function search_students_incidence()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return $this->response->setJSON([]);

        $teacher_id = $session->get('teacher_id');
        $query      = $this->request->getGet('q');
        $section_id = $this->request->getGet('section_id');

        $db = \Config\Database::connect('tiquipaya');
        $builder = $db->table('t_student s')
            ->select('s.student_id, CONCAT(s.lastname," ",s.lastname2," ",s.name) as nombre, sec.completo, s.section_id')
            ->join('section sec', 'sec.section_id = s.section_id')
            ->join('subject sub', 'sub.section_id = s.section_id')
            ->where('sub.teacher_id', $teacher_id)
            ->where('s.activo', 1)
            ->groupBy('s.student_id')
            ->orderBy('s.lastname', 'ASC');

        if ($section_id) {
            // Búsqueda por curso
            $builder->where('s.section_id', $section_id);
        } elseif (strlen($query) >= 2) {
            // Búsqueda por nombre
            $builder->groupStart()
                ->like('s.lastname', $query)
                ->orLike('s.lastname2', $query)
                ->orLike('s.name', $query)
            ->groupEnd()
            ->limit(15);
        } else {
            return $this->response->setJSON([]);
        }

        return $this->response->setJSON($builder->get()->getResultArray());
    }

    function resolve_date_id()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return $this->response->setJSON(['status' => 'error']);

        $subject_id = $this->request->getPost('subject_id');
        $date       = $this->request->getPost('date');

        if (!$date)
            return $this->response->setJSON(['status' => 'error', 'message' => 'Faltan datos']);

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        return $this->response->setJSON([
            'status'   => 'success',
            'date_id'  => 0,
            'phase_id' => $phase_id,
        ]);
    }

    function behavior_analysis_student($student_id, $subject_id = 0)
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $teacher_id = $session->get('teacher_id');

        // Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        // Subject Data
        $SubjectMod = new SubjectModel();
        $page_data['subject_name'] = 'Todas las Materias';
        $page_data['curso'] = '';
        if ($subject_id > 0) {
            $subjects = $SubjectMod->subject_section($subject_id);
            $page_data['subject_name'] = $subjects[0]['name'] ?? 'Materia';
            $page_data['curso'] = ($subjects[0]['nick_name'] ?? '') . " - " . ($subjects[0]['name'] ?? '');
        }

        // Student Data
        $StudentMod = new StudentModel();
        $student = $StudentMod->find($student_id);
        if (empty($page_data['curso'])) {
            $page_data['curso'] = ($student['lastname'] ?? '') . ' ' . ($student['name'] ?? '');
        }

        $IncidenciaMod = new IncidenciaModel();
        $logs = $IncidenciaMod->getRegistroEstudiante($student_id, $page_data['phase_id'], $subject_id > 0 ? $subject_id : null);

        // Logística del día
        $page_data['logistics'] = $IncidenciaMod->getLogisticasHoy($student_id, date('Y-m-d'), $page_data['phase_id']);

        // Puntos del Ser
        $page_data['puntos_del_ser'] = $subject_id > 0
            ? $IncidenciaMod->calcularNota($student_id, $subject_id, $page_data['phase_id'])
            : 10;

        // Chart Data
        $behaviorCounts = [];
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;
        foreach ($logs as $log) {
            $bid = $log['incidencia_tipo_id'];
            if (!isset($behaviorCounts[$bid])) {
                $behaviorCounts[$bid] = [
                    'nombre' => $log['nombre'],
                    'icono'  => $log['icono'],
                    'count'  => 0,
                    'tipo'   => $log['tipo']
                ];
            }
            $behaviorCounts[$bid]['count']++;
            if ($log['tipo'] === 'positiva') {
                $positiveCount++;
            } elseif ($log['tipo'] === 'negativa') {
                $negativeCount++;
            } else {
                $neutralCount++;
            }
        }

        $page_data['positive_incidents'] = $positiveCount;
        $page_data['negative_incidents'] = $negativeCount;
        $page_data['neutral_incidents'] = $neutralCount;
        $page_data['behavior_counts'] = $behaviorCounts;
        $page_data['logs'] = $logs;
        $page_data['grave_incidents'] = 0;

        // Primer trimestre (behavior_log)
        $BehaviorMod2 = new BehaviorModel();
        $rawLogsT1b = $BehaviorMod2->getStudentLog($student_id, null, $subject_id > 0 ? $subject_id : null);
        $logsT1b = [];
        foreach ($rawLogsT1b as $log) {
            $logsT1b[] = [
                'id'                 => $log['id'],
                'nombre'             => html_entity_decode($log['name'], ENT_QUOTES, 'UTF-8'),
                'icono'              => html_entity_decode($log['icon'], ENT_QUOTES, 'UTF-8'),
                'tipo'               => $log['type'] === 'positive' ? 'positiva' : ($log['type'] === 'negative' ? 'negativa' : 'neutral'),
                'observacion'        => $log['observation'],
                'subject_name'       => $log['subject_name'],
                'created_at'         => $log['created_at'],
                'incidencia_tipo_id' => null,
                'source'             => 'behavior_log',
            ];
        }
        $t1Counts2 = [];
        $t1Pos2 = 0; $t1Neg2 = 0; $t1Neu2 = 0;
        foreach ($logsT1b as $log) {
            $key = $log['nombre'];
            if (!isset($t1Counts2[$key])) {
                $t1Counts2[$key] = ['nombre' => $log['nombre'], 'icono' => $log['icono'], 'count' => 0, 'tipo' => $log['tipo']];
            }
            $t1Counts2[$key]['count']++;
            if ($log['tipo'] === 'positiva') $t1Pos2++;
            elseif ($log['tipo'] === 'negativa') $t1Neg2++;
            else $t1Neu2++;
        }
        $page_data['logs_t1']            = $logsT1b;
        $page_data['t1_positive']        = $t1Pos2;
        $page_data['t1_negative']        = $t1Neg2;
        $page_data['t1_neutral']         = $t1Neu2;
        $page_data['t1_behavior_counts'] = $t1Counts2;
        $page_data['active_tab']         = $page_data['phase_id'] >= 2 ? 't2' : 't1';

        // Alertas activas
        $alertasB = [];
        if (!empty($student['section_id'])) {
            $SubjectModB = new SubjectModel();
            $materiasB = $SubjectModB->subjects_student($student['section_id'], $student['sex']);
            foreach ($materiasB as $mat) {
                $nota = $IncidenciaMod->calcularNota($student_id, $mat['subject_id'], $page_data['phase_id']);
                if ($nota <= 8) {
                    $nivel = $nota < 7 ? 'danger' : ($nota < 8 ? 'warning' : 'info');
                    $alertasB[] = ['materia' => $mat['name'], 'subject_id' => $mat['subject_id'], 'nota' => $nota, 'nivel' => $nivel];
                }
            }
            usort($alertasB, fn($a, $b) => $a['nota'] <=> $b['nota']);
        }
        $page_data['alertas']   = $alertasB;
        $page_data['logistics'] = $page_data['logistics'] ?? [];

        $page_data['student'] = $student;
        $page_data['student_id'] = $student_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'student_profile';
        $page_data['page_title'] = 'Análisis de Comportamiento';

        return view('backend/index', $page_data);
    }

    function register_behavior()
    {
        $studentId   = $this->request->getPost('student_id');
        $tipoId      = $this->request->getPost('behavior_id');
        $subjectId   = $this->request->getPost('subject_id');
        $observation = $this->request->getPost('observation');

        $fecha = $this->request->getPost('custom_date');
        if (!$fecha) {
            $dateId   = $this->request->getPost('date_id');
            $asistDb  = \Config\Database::connect('asistencia');
            $dateInfo = $dateId ? $asistDb->table('attendance_dates')->where('date_id', $dateId)->get()->getRowArray() : null;
            $fecha    = $dateInfo['date_class'] ?? date('Y-m-d');
        }

        if (!$studentId || !$tipoId || !$subjectId) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Faltan parámetros requeridos (Estudiante, Tipo o Materia)'
            ]);
        }

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        $session    = session();
        $teacher_id = $session->get('teacher_id');

        // Detectar si el alumno es de primaria 3ro-6to
        $asistDbReg   = \Config\Database::connect('asistencia');
        $studentInfo  = $asistDbReg->query(
            "SELECT s.section_id, sec.grade FROM t_student s JOIN section sec ON sec.section_id = s.section_id WHERE s.student_id = ?",
            [$studentId]
        )->getRowArray();
        $studentGrade     = $studentInfo['grade'] ?? '';
        $studentSectionId = (int)($studentInfo['section_id'] ?? 0);
        $esPrimaria36     = isPrimaria36($studentGrade);

        $IncidenciaMod = new IncidenciaModel();

        $tipoInfo = $IncidenciaMod->getTipoById($tipoId);
        if ($tipoInfo && $tipoInfo['tipo'] === 'negativa') {
            if ($esPrimaria36) {
                $conteos = $IncidenciaMod->getConteosByTeacher($studentId, $teacher_id, $studentSectionId, $phase_id);
                if ($conteos['nota'] <= 7 && !$IncidenciaMod->tieneCompromisoByTeacher($studentId, $teacher_id, $phase_id)) {
                    return $this->response->setJSON([
                        'status'    => 'needs_acta',
                        'nota'      => $conteos['nota'],
                        'message'   => 'El estudiante tiene ' . $conteos['nota'] . ' pts. Se requiere acta de reunión con el padre/tutor.',
                        'teacher_id'=> $teacher_id,
                    ]);
                }
            } else {
                $conteos = $IncidenciaMod->getConteos($studentId, $subjectId, $phase_id);
                if ($conteos['nota'] <= 7 && !$IncidenciaMod->tieneCompromiso($studentId, $subjectId, $phase_id)) {
                    return $this->response->setJSON([
                        'status'  => 'needs_acta',
                        'nota'    => $conteos['nota'],
                        'message' => 'El estudiante tiene ' . $conteos['nota'] . ' pts. Se requiere acta de reunión con el padre/tutor.',
                    ]);
                }
            }
        }

        // Capturar nota anterior para detectar cruce de umbral
        $nota_anterior = isset($conteos) ? $conteos['nota'] : null;

        $IncidenciaMod->registrar([
            'student_id'        => $studentId,
            'subject_id'        => $subjectId,
            'incidencia_tipo_id'=> $tipoId,
            'phase_id'          => $phase_id,
            'fecha'             => $fecha,
            'observacion'       => $observation,
            'registrado_por'    => $teacher_id,
        ]);

        if ($esPrimaria36) {
            $conteos = $IncidenciaMod->getConteosByTeacher($studentId, $teacher_id, $studentSectionId, $phase_id);
        } else {
            $conteos = $IncidenciaMod->getConteos($studentId, $subjectId, $phase_id);
        }

        // Envío de correo de advertencia (solo en producción, no en localhost)
        $host         = $_SERVER['HTTP_HOST'] ?? '';
        $isProduction = ($host !== 'localhost' && strpos($host, '127.') !== 0 && $host !== '::1');

        if ($isProduction && $nota_anterior !== null && $tipoInfo && $tipoInfo['tipo'] === 'negativa') {
            $nota_nueva   = $conteos['nota'];
            $templateFile = null;
            $asuntoEmail  = null;

            if ($nota_nueva <= 1 && $nota_anterior > 1) {
                $templateFile = 'incidencia1.html';
                $asuntoEmail  = 'Alerta Nivel 3 (Crítico) — Dimensión del Ser';
            } elseif ($nota_nueva <= 5 && $nota_anterior > 5) {
                $templateFile = 'incidencia5.html';
                $asuntoEmail  = 'Alerta Nivel 2 — Dimensión del Ser';
            } elseif ($nota_nueva <= 7 && $nota_anterior > 7) {
                $templateFile = 'incidencia7.html';
                $asuntoEmail  = 'Alerta Nivel 1 — Dimensión del Ser';
            }

            if ($templateFile) {
                $templatePath = APPPATH . 'Views/emails/' . $templateFile;
                if (file_exists($templatePath)) {
                    $StudentMod   = new StudentModel();
                    $students     = $StudentMod->datosStudent($studentId);
                    $student_name = $students[0]->nombre ?? 'Estudiante';
                    $section_id   = $students[0]->section_id ?? null;

                    $SubjectMod   = new SubjectModel();
                    $subjectInfo  = $SubjectMod->subject_docente_name($subjectId);
                    $materia_name = $subjectInfo[0]['materia'] ?? '';
                    $docente_name = $subjectInfo[0]['docente'] ?? '';

                    $FamilyMod = new FamilyModel();
                    $family    = $FamilyMod->get_family_emails($studentId);
                    $to_parent = '';
                    if (!empty($family)) {
                        $emails    = array_filter([$family[0]['email1'] ?? '', $family[0]['email2'] ?? '']);
                        $to_parent = implode(', ', $emails);
                    }

                    // Emails de copia según nivel de alerta
                    $emailDocente   = $session->get('email') ?? '';  // docente que registra
                    $emailConsejero = '';
                    $emailDirector  = '';
                    if ($section_id) {
                        $SectionMod    = new SectionModel();
                        $sectionEmails = $SectionMod->section_emails($section_id);
                        $emailConsejero = $sectionEmails[0]['emailDocente']  ?? '';
                        $emailDirector  = $sectionEmails[0]['emailDirector'] ?? '';
                    }

                    // incidencia7 → CC: emailDocente + seguimiento
                    // incidencia5 → CC: emailDocente + emailConsejero + seguimiento
                    // incidencia1 → CC: emailDocente + emailConsejero + emailDirector + seguimiento
                    $ccList = [$emailDocente, 'etorrico@tiquipaya.edu.bo'];
                    if ($templateFile === 'incidencia5.html' || $templateFile === 'incidencia1.html') {
                        $ccList[] = $emailConsejero;
                    }
                    if ($templateFile === 'incidencia1.html') {
                        $ccList[] = $emailDirector;
                    }
                    $cc = implode(', ', array_filter($ccList));

                    if ($to_parent) {
                        $html = file_get_contents($templatePath);
                        $html = str_replace(
                            ['[Nombre del Estudiante]', '[Nombre de la Materia]', '[Nombre del Docente]'],
                            [$student_name, $materia_name, $docente_name],
                            $html
                        );
                        $headers = implode("\r\n", [
                            'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>',
                            'Cc: ' . $cc,
                            'MIME-Version: 1.0',
                            'Content-type: text/html; charset=utf-8',
                            'X-Mailer: PHP/' . phpversion(),
                        ]);
                        mail($to_parent, $asuntoEmail, $html, $headers);
                    }
                }
            }
        }

        return $this->response->setJSON([
            'status'             => 'success',
            'nota_ser'           => $conteos['nota'],
            'new_score'          => $conteos['nota'],
            'new_negative_count' => $conteos['negativa'],
            'new_positive_count' => $conteos['positiva'],
        ]);
    }

    function delete_behavior_ajax()
    {
        $logId = $this->request->getPost('log_id');

        $IncidenciaMod = new IncidenciaModel();
        $registro = $IncidenciaMod->eliminar($logId);

        if (!$registro) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Registro no encontrado']);
        }

        $studentId = $registro['student_id'];
        $phase_id  = $registro['phase_id'];

        // Detectar si el alumno es de primaria 3ro-6to
        $asistDbDel  = \Config\Database::connect('asistencia');
        $stuInfoDel  = $asistDbDel->query(
            "SELECT s.section_id, sec.grade FROM t_student s JOIN section sec ON sec.section_id = s.section_id WHERE s.student_id = ?",
            [$studentId]
        )->getRowArray();
        $gradeDelDel    = $stuInfoDel['grade'] ?? '';
        $sectionIdDel   = (int)($stuInfoDel['section_id'] ?? 0);
        $teacherIdDel   = (int)($registro['registrado_por'] ?? 0);

        if (isPrimaria36($gradeDelDel) && $teacherIdDel) {
            $conteos = $IncidenciaMod->getConteosByTeacher($studentId, $teacherIdDel, $sectionIdDel, $phase_id);
        } else {
            $conteos = $IncidenciaMod->getConteos($studentId, $registro['subject_id'], $phase_id);
        }

        return $this->response->setJSON([
            'status'             => 'success',
            'nota_ser'           => $conteos['nota'],
            'new_score'          => $conteos['nota'],
            'new_negative_count' => $conteos['negativa'],
            'new_positive_count' => $conteos['positiva'],
            'student_id'         => $studentId,
        ]);
    }

    function get_student_score()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return $this->response->setJSON(['status' => 'error']);

        $student_id = $this->request->getGet('student_id');
        $subject_id = $this->request->getGet('subject_id');

        if (!$student_id || !$subject_id)
            return $this->response->setJSON(['status' => 'error', 'message' => 'Faltan parámetros']);

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        $IncidenciaMod = new IncidenciaModel();

        $asistDbScore = \Config\Database::connect('asistencia');
        $stuInfoScore = $asistDbScore->query(
            "SELECT s.section_id, sec.grade FROM t_student s JOIN section sec ON sec.section_id = s.section_id WHERE s.student_id = ?",
            [$student_id]
        )->getRowArray();
        $gradeScore     = $stuInfoScore['grade'] ?? '';
        $sectionIdScore = (int)($stuInfoScore['section_id'] ?? 0);
        $teacherIdScore = (int) session()->get('teacher_id');

        if (isPrimaria36($gradeScore) && $teacherIdScore) {
            $conteos         = $IncidenciaMod->getConteosByTeacher($student_id, $teacherIdScore, $sectionIdScore, $phase_id);
            $tieneCompromiso = $IncidenciaMod->tieneCompromisoByTeacher($student_id, $teacherIdScore, $phase_id);
        } else {
            $conteos         = $IncidenciaMod->getConteos($student_id, $subject_id, $phase_id);
            $tieneCompromiso = $IncidenciaMod->tieneCompromiso($student_id, $subject_id, $phase_id);
        }

        return $this->response->setJSON([
            'status'          => 'success',
            'nota'            => $conteos['nota'],
            'negativa'        => $conteos['negativa'],
            'positiva'        => $conteos['positiva'],
            'tiene_compromiso'=> $tieneCompromiso,
            'bloqueado'       => ($conteos['nota'] <= 7 && !$tieneCompromiso),
        ]);
    }

    function upload_acta()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sin autorización']);

        $student_id      = $this->request->getPost('student_id');
        $subject_id      = $this->request->getPost('subject_id');
        $teacher_id_acta = $this->request->getPost('teacher_id_acta'); // solo para primaria 3-6
        $fecha_reunion   = $this->request->getPost('fecha_reunion');
        $observacion     = $this->request->getPost('observacion');
        $teacher_id      = $session->get('teacher_id');

        if (!$student_id || !$fecha_reunion || (!$subject_id && !$teacher_id_acta))
            return $this->response->setJSON(['status' => 'error', 'message' => 'Faltan datos requeridos']);

        $file = $this->request->getFile('acta_file');
        if (!$file || !$file->isValid() || $file->hasMoved())
            return $this->response->setJSON(['status' => 'error', 'message' => 'Debes subir el acta de reunión']);

        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
        if (!in_array(strtolower($file->getExtension()), $allowedTypes))
            return $this->response->setJSON(['status' => 'error', 'message' => 'Formato no permitido. Usa PDF, JPG o PNG.']);

        if ($file->getSize() > 5 * 1024 * 1024)
            return $this->response->setJSON(['status' => 'error', 'message' => 'El archivo no debe supesar 5MB.']);

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        $uploadPath = FCPATH . 'uploads/actas/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
            file_put_contents($uploadPath . 'index.html', '');
        }

        // Determinar si es compromiso por maestro (primaria 3-6)
        $esActaMaestro = !empty($teacher_id_acta);
        $fileKey  = $esActaMaestro ? 'maestro' . $teacher_id_acta : $subject_id;
        $newName  = 'acta_' . $student_id . '_' . $fileKey . '_' . time() . '.' . $file->getExtension();
        $file->move($uploadPath, $newName);

        $IncidenciaMod = new IncidenciaModel();

        if ($esActaMaestro) {
            $IncidenciaMod->registrarCompromiso([
                'student_id'    => $student_id,
                'subject_id'    => null,
                'teacher_id'    => $teacher_id_acta,
                'phase_id'      => $phase_id,
                'fecha_reunion' => $fecha_reunion,
                'observacion'   => $observacion,
                'archivo'       => $newName,
            ]);
        } else {
            $IncidenciaMod->registrarCompromiso([
                'student_id'    => $student_id,
                'subject_id'    => $subject_id,
                'phase_id'      => $phase_id,
                'teacher_id'    => $teacher_id,
                'fecha_reunion' => $fecha_reunion,
                'observacion'   => $observacion,
                'archivo'       => $newName,
            ]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    function update_attendance_ajax()
    {
        $studentId = $this->request->getPost('student_id');
        $status = $this->request->getPost('status');
        $subjectId = $this->request->getPost('subject_id');
        $dateId = $this->request->getPost('date_id');
        // $period = $this->request->getPost('period'); // If needed later for attendance record

        // Find existing record or create
        $AssistanceMod = new AssistancesubjectModel();
        $existing = $AssistanceMod->get_assistance_subject([
            "date_id" => $dateId,
            "subject_id" => $subjectId,
            "student_id" => $studentId
        ]);

        if (!empty($existing)) {
            $id = $existing[0]['assistance_subject_id'];
            $AssistanceMod->update_assistance_subject(['status' => $status], $id);
        } else {
            $AssistanceMod->insert_assistance_subject([
                "date_id" => $dateId,
                "subject_id" => $subjectId,
                "student_id" => $studentId,
                "status" => $status,
                "periodos" => 1 // Default if absent
            ]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    function get_daily_log_ajax()
    {
        $student_id = (int) $this->request->getPost('student_id');
        $subject_id = (int) $this->request->getPost('subject_id');

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        $IncidenciaMod = new IncidenciaModel();
        $logs = $IncidenciaMod->getRegistroEstudiante($student_id, $phase_id, $subject_id ?: null);

        return $this->response->setJSON($logs);
    }
    function attendance_date()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $date = $this->request->getPost('fecha');
        $subject_id = $this->request->getPost('subject_id');
        $periodo = $this->request->getPost('periodo') ?? $this->request->getPost('periodos') ?? '';

        if (!$date || !$subject_id || !$periodo) {
            return redirect()->back()->with('error', 'Faltan parámetros requeridos (Fecha o Periodo)');
        }

        $page_data['date'] = $date;
        $teacher_id = $session->get('teacher_id');

        //SETTINGS
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //Subject logic
        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subject_section($subject_id);

        if (empty($subjects)) {
            return redirect()->back()->with('error', 'Materia no encontrada');
        }
        if ((int)$subjects[0]['teacher_id'] !== (int)$teacher_id) {
            return redirect()->to(base_url());
        }

        $page_data['curso'] = $subjects[0]['nick_name'] . " - " . $subjects[0]['name'];
        $page_data['section_id'] = $subjects[0]['section_id'];
        $page_data['subject_id'] = $subject_id;
        $page_data['date_display'] = $page_data['date'];
        $page_data['periodo'] = $periodo;

        //Assistance Date Logic — filter by phase_id to avoid picking up dates from other trimesters
        $DatesMod = new DatesModel();
        $respuesta = $DatesMod->get_attendance_dates([
            "date_class" => $page_data['date'],
            "phase_id"   => $page_data['phase_id'],
        ]);

        if (count($respuesta) >= 1) {
            $page_data['date_id'] = $respuesta[0]['date_id'];
        } else {
            $datos = [
                "date_class" => $page_data['date'],
                "phase_id"   => $page_data['phase_id'],
            ];
            $respuesta = $DatesMod->insert_attendance_dates($datos);
            $page_data['date_id'] = $respuesta;
        }

        // Students Logic (Using standard list for gamification)
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($page_data['section_id'], $teacher_id);

        $IncidenciaMod = new IncidenciaModel();
        $tiposGrouped = $IncidenciaMod->getTiposGrouped();
        $page_data['tipos']          = $tiposGrouped;
        $page_data['tipos_negativa'] = $tiposGrouped['negativa'];
        $page_data['tipos_positiva'] = $tiposGrouped['positiva'];
        $page_data['tipos_neutral']  = $tiposGrouped['neutral'];

        // Fetch all per-student data in bulk queries instead of N individual ones
        $student_ids = array_column($students, 'student_id');

        // Para primaria 3ro-6to, el puntaje se calcula por maestro (no por materia)
        $sectionGrade = \Config\Database::connect('asistencia')
            ->query("SELECT grade FROM section WHERE section_id = ?", [$page_data['section_id']])
            ->getRowArray()['grade'] ?? '';

        $esPrimaria36Att = isPrimaria36($sectionGrade);
        $page_data['is_primaria36'] = $esPrimaria36Att;

        if ($esPrimaria36Att) {
            $AssistanceMod = new PrimAssistancesubjectModel();
            $LicenciaMod   = new PrimLicenciaModel();
            $conteosBulk   = $IncidenciaMod->getConteosBulkByTeacher($student_ids, $teacher_id, $page_data['section_id'], $page_data['phase_id']);
        } else {
            $AssistanceMod = new AssistancesubjectModel();
            $LicenciaMod   = new LicenciaModel();
            $conteosBulk   = $IncidenciaMod->getConteosBulk($student_ids, $subject_id, $page_data['phase_id']);
        }
        $attendanceBulk = $AssistanceMod->get_assistance_subject_bulk(
            $page_data['date_id'], $subject_id, $student_ids, $periodo
        );

        $has_existing = false;
        foreach ($students as &$student) {
            $sid = $student['student_id'];

            $conteos = $conteosBulk[$sid] ?? ['nota' => 10, 'negativa' => 0, 'positiva' => 0];
            $student['daily_score']    = $conteos['nota'];
            $student['negative_count'] = $conteos['negativa'];
            $student['positive_count'] = $conteos['positiva'];

            // Status codes: 0=Ausente, 1=Presente, 2=Licencia, 3=Retraso, 4=M.Virtual
            $statusData = $attendanceBulk[$sid] ?? null;
            if ($statusData) {
                $student['attendance_status'] = $statusData['status'];
                $student['assistance_subject_id'] = $statusData['assistance_subject_id'];
                $has_existing = true;
            } else {
                $student['attendance_status'] = 1;
                $student['assistance_subject_id'] = 0;
            }
        }
        $page_data['students'] = $students;

        // Licencias Fechas ($LicenciaMod ya fue instanciado arriba según nivel)
        $page_data['licencias'] = $LicenciaMod->licencias_fecha($page_data['section_id'], $page_data['date']);

        // Convertir número de periodo (1-8) al periodo_id real de la BD
        $PeriodoMod = new \App\Models\PeriodoModel();
        $periodos_lista = $PeriodoMod->listar_periodos_section($page_data['section_id']);
        $periodo_real_id = $page_data['periodo']; // fallback al valor original
        foreach ($periodos_lista as $i => $p) {
            if (($i + 1) == (int)$page_data['periodo']) {
                $periodo_real_id = $p['periodo_id'];
                break;
            }
        }
        $page_data['licencias_periodo'] = $LicenciaMod->licencias_periodo($page_data['section_id'], $page_data['date'], $periodo_real_id);

        // Previous Attendance Logic
        $prev_attendance_raw = $AssistanceMod->assis_previous($page_data['section_id'], $page_data['date_id']);
        $prev_attendance = [];
        foreach ($prev_attendance_raw as $pa) {
            $prev_attendance[$pa['student_id']] = $pa['status'];
        }
        $page_data['prev_attendance'] = $prev_attendance;

        // Para primaria 3ro-6to: cargar cupo trimestral de cada alumno
        $page_data['cupos_map'] = [];
        if ($esPrimaria36Att) {
            $phaseRow = \Config\Database::connect('tiquipaya')
                ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$page_data['phase_id']])
                ->getRowArray();
            if ($phaseRow) {
                $CupoMod = new PrimCupoModel();
                $filas   = $CupoMod->resumenSeccion(
                    (int)$page_data['section_id'],
                    $phaseRow['inicio'],
                    $phaseRow['fin']
                );
                foreach ($filas as $f) {
                    $page_data['cupos_map'][$f['student_id']] = $f;
                }
            }
        }

        // View
        $page_data['has_existing_data'] = $has_existing;
        $page_data['page_name'] = 'attendance_gamified';
        $page_data['page_title'] = 'Asistencia y Conducta';

        return view('backend/index', $page_data);
    }

    function attendance_date_inicial()
    {
        $page_data['date'] = $_POST['fecha'];
        $subject_id = $_POST['subject_id'];
        $session = session();
        $teacher_id = $session->get('teacher_id');

        //SETTINGS
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //Subject
        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subject_section($subject_id);
        $page_data['curso'] = $subjects[0]['nick_name'] . " - " . $subjects[0]['name'];
        $page_data['section_id'] = $subjects[0]['section_id'];
        $page_data['subject_id'] = $subject_id;

        $DatesMod = new DatesModel();
        $respuesta = $DatesMod->get_attendance_dates([
            "date_class" => $page_data['date'],
            "phase_id"   => $page_data['phase_id'],
        ]);
        if (count($respuesta) >= 1) {
            $page_data['date_id'] = $respuesta[0]['date_id'];
        } else {
            $datos = [
                "date_class" => $page_data['date'],
                "phase_id"   => $page_data['phase_id'],
            ];
            $respuesta = $DatesMod->insert_attendance_dates($datos);
            $page_data['date_id'] = $respuesta;
        }

        //Students
        $AssistanceMod = new AssistanceModel();
        $students = $AssistanceMod->studentsAssis($subjects[0]['section_id'], $teacher_id, $page_data['date']);
        $page_data['students'] = $students;
        //Licecncias
        $LicenciaMod = new LicenciaModel();
        $licencias = $LicenciaMod->licencias_fecha($subjects[0]['section_id'], $page_data['date']);
        $page_data['licencias'] = $licencias;
        //Asistencia Anterior
        $AssistancesubjectMod = new AssistancesubjectModel();
        $Assistancesubject = $AssistancesubjectMod->assis_previous($subjects[0]['section_id'], $page_data['date_id']);
        $page_data['asistenciasAnt'] = $Assistancesubject;


        $page_data['page_name'] = 'attendance_inicial';
        $page_data['page_title'] = 'Asistencia';
        return view('backend/index', $page_data);
    }

    function attendance_save()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        //SETTINGS
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Parametros
        $page_data['teacher_id'] = $teacher_id;
        $page_data['subject_id'] = $_POST['subject_id'];
        $page_data['date_id'] = $_POST['date_id'];
        $periodo = $_POST['periodos'];
        //Subject
        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subject_section($page_data['subject_id']);
        if (empty($subjects) || (int)$subjects[0]['teacher_id'] !== (int)$teacher_id) {
            return redirect()->to(base_url());
        }
        // section_id se deriva de la materia verificada, no del POST directamente
        // (evita que se pueda enviar un section_id distinto al de esa materia).
        $page_data['section_id'] = $subjects[0]['section_id'];
        $page_data['curso'] = $subjects[0]['nick_name'] . " - " . $subjects[0]['name'];

        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($page_data['section_id'], $teacher_id);
        $page_data['students'] = $students;

        // Obtener la fecha real del día de clase para registrar en assistance
        $DatesMod = new DatesModel();
        $dateRow = $DatesMod->get_attendance_dates(['date_id' => $page_data['date_id']]);
        $dateClass = isset($dateRow[0]['date_class']) ? $dateRow[0]['date_class'] : date('Y-m-d');

        // Detectar si es primaria 3ro-6to para usar tablas prim_
        $sectionGradeSave = \Config\Database::connect('asistencia')
            ->query("SELECT grade FROM section WHERE section_id = ?", [$page_data['section_id']])
            ->getRowArray()['grade'] ?? '';
        $esPrimaria36Save = isPrimaria36($sectionGradeSave);

        $student_ids = array_column($students, 'student_id');

        if ($esPrimaria36Save) {
            // --- PRIMARIA 3ro-6to: tablas prim_, registro diario siempre upsert ---
            $PrimMod = new PrimAssistancesubjectModel();

            // Construir set de alumnos con licencia aprobada para este día/período
            // para que el docente no pueda cambiar su estado
            $LicSaveMod   = new PrimLicenciaModel();
            $PeriodoSave  = new \App\Models\PeriodoModel();
            $periodosSave = $PeriodoSave->listar_periodos_section($page_data['section_id']);
            $periodo_real_save = $periodo;
            foreach ($periodosSave as $i => $p) {
                if (($i + 1) == (int)$periodo) { $periodo_real_save = $p['periodo_id']; break; }
            }
            $lic_dia_save = $LicSaveMod->licencias_fecha($page_data['section_id'], $dateClass);
            $lic_per_save = $LicSaveMod->licencias_periodo($page_data['section_id'], $dateClass, $periodo_real_save);
            $licensed_save = [];
            foreach ($lic_dia_save as $l) $licensed_save[$l['student_id']] = true;
            foreach ($lic_per_save as $l) $licensed_save[$l['student_id']] = true;

            $existingSubject = $PrimMod->get_assistance_subject_bulk(
                $page_data['date_id'], $page_data['subject_id'], $student_ids, $periodo
            );

            // Una falta registrada por OTRA materia ese mismo día no debe borrarse
            // porque esta materia marque Presente — cada materia es una observación
            // independiente (ej. Educación Física puede marcar Ausente aunque el
            // alumno haya estado en el resto de las clases). Solo protegemos contra
            // materias DISTINTAS: si es la misma materia corrigiéndose a sí misma,
            // el cambio sí se aplica con normalidad.
            $yaAusenteOtraMateria = [];
            if (!empty($student_ids)) {
                $idsAlumnos    = implode(',', array_map('intval', $student_ids));
                $filasAusentes = \Config\Database::connect('asistencia')->query(
                    "SELECT DISTINCT student_id FROM prim_assistance_subject
                     WHERE date_id = ? AND status = 0 AND subject_id != ?
                       AND student_id IN ({$idsAlumnos})",
                    [$page_data['date_id'], $page_data['subject_id']]
                )->getResultArray();
                foreach ($filasAusentes as $fa) $yaAusenteOtraMateria[$fa['student_id']] = true;
            }

            $toUpdate = [];
            $toInsert = [];
            $dailyUpsert = [];

            foreach ($students as $row):
                $sid       = $row['student_id'];
                // Licencia aprobada: el servidor fuerza status=2 sin importar el POST
                $statusVal = isset($licensed_save[$sid]) ? 2 : (isset($_POST['check_' . $sid]) ? (int)$_POST['check_' . $sid] : 1);
                $textVal   = isset($_POST['text_' . $sid])  ? $_POST['text_' . $sid]  : '';

                if (isset($existingSubject[$sid])) {
                    $toUpdate[] = [
                        'assistance_subject_id' => $existingSubject[$sid]['assistance_subject_id'],
                        'status'                => $statusVal,
                        'indiscipline'          => $textVal,
                    ];
                } else {
                    $toInsert[] = [
                        'status'       => $statusVal,
                        'indiscipline' => $textVal,
                        'date_id'      => $page_data['date_id'],
                        'subject_id'   => $page_data['subject_id'],
                        'student_id'   => $sid,
                        'periodos'     => $periodo,
                    ];
                }
                // Diario consolidado: se actualiza con lo que se guardó, salvo que ya
                // exista una falta de otra materia y esta materia diga "Presente" —
                // en ese caso se respeta la falta.
                $dailyUpsert[$sid] = (isset($yaAusenteOtraMateria[$sid]) && $statusVal == 1) ? 0 : $statusVal;
            endforeach;

            if (!empty($toUpdate)) $PrimMod->update_assistance_subject_batch($toUpdate);
            if (!empty($toInsert)) $PrimMod->insert_assistance_subject_batch($toInsert);
            $PrimMod->upsert_daily($dailyUpsert, $dateClass, $teacher_id, $session->get('name'), 'Docente');

            // Réplica automática: si este mismo maestro dicta otras materias en este
            // curso, se les copia el mismo resultado para que no tenga que volver a
            // pasar lista por cada una. Si alguna materia hermana ya tiene su propio
            // registro para ese día/período (porque el maestro sí entró a tomarla a
            // mano), no se pisa — se respeta lo que ya haya quedado ahí.
            $dbAsisRep = \Config\Database::connect('asistencia');
            $materiasHermanas = $dbAsisRep->query(
                "SELECT subject_id FROM subject WHERE section_id = ? AND teacher_id = ? AND subject_id != ?",
                [$page_data['section_id'], $teacher_id, $page_data['subject_id']]
            )->getResultArray();

            foreach ($materiasHermanas as $mh) {
                $siblingId       = (int) $mh['subject_id'];
                $existingSibling = $PrimMod->get_assistance_subject_bulk(
                    $page_data['date_id'], $siblingId, $student_ids, $periodo
                );

                $siblingInsert = [];
                foreach ($dailyUpsert as $sidRep => $statusValRep) {
                    if (isset($existingSibling[$sidRep])) continue;
                    $siblingInsert[] = [
                        'status'       => $statusValRep,
                        'indiscipline' => '',
                        'date_id'      => $page_data['date_id'],
                        'subject_id'   => $siblingId,
                        'student_id'   => $sidRep,
                        'periodos'     => $periodo,
                    ];
                }
                if (!empty($siblingInsert)) $PrimMod->insert_assistance_subject_batch($siblingInsert);
            }

            // Verificar y generar alertas de cupo para todos los alumnos de la sección
            $phaseRow = \Config\Database::connect('tiquipaya')
                ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$page_data['phase_id']])
                ->getRowArray();
            if ($phaseRow) {
                $CupoMod = new PrimCupoModel();
                $CupoMod->verificarSeccion(
                    (int)$page_data['section_id'],
                    (int)$page_data['phase_id'],
                    $phaseRow['inicio'],
                    $phaseRow['fin']
                );
            }

        } else {
            // --- SECUNDARIA / INICIAL / 1ro-2do PRIM: comportamiento original ---
            $AssisMod      = new AssistanceModel();
            $AssistanceMod = new AssistancesubjectModel();

            $existingSubject    = $AssistanceMod->get_assistance_subject_bulk(
                $page_data['date_id'], $page_data['subject_id'], $student_ids, $periodo
            );
            $existingGeneralMap = $AssisMod->get_by_students_date($student_ids, $dateClass);

            $toUpdate        = [];
            $toInsert        = [];
            $toInsertGeneral = [];

            foreach ($students as $row):
                $sid       = $row['student_id'];
                $statusVal = isset($_POST['check_' . $sid]) ? $_POST['check_' . $sid] : 1;
                $textVal   = isset($_POST['text_' . $sid])  ? $_POST['text_' . $sid]  : '';

                if (isset($existingSubject[$sid])) {
                    $toUpdate[] = [
                        'assistance_subject_id' => $existingSubject[$sid]['assistance_subject_id'],
                        'status'                => $statusVal,
                        'indiscipline'          => $textVal,
                    ];
                } else {
                    $toInsert[] = [
                        'status'       => $statusVal,
                        'indiscipline' => $textVal,
                        'date_id'      => $page_data['date_id'],
                        'subject_id'   => $page_data['subject_id'],
                        'student_id'   => $sid,
                        'periodos'     => $periodo,
                    ];
                }
                // Secundaria: el primer registro del día manda, no se sobreescribe
                if (!isset($existingGeneralMap[$sid])) {
                    $toInsertGeneral[] = [
                        'student_id'    => $sid,
                        'date'          => $dateClass,
                        'status'        => $statusVal,
                        'observation'   => $textVal ?: null,
                        'registered_by' => $teacher_id,
                    ];
                }
            endforeach;

            if (!empty($toUpdate))        $AssistanceMod->update_assistance_subject_batch($toUpdate);
            if (!empty($toInsert))        $AssistanceMod->insert_assistance_subject_batch($toInsert);
            if (!empty($toInsertGeneral)) $AssisMod->db->table('assistance')->insertBatch($toInsertGeneral);
        }

        return redirect()->to(base_url('teacher/attendance_report/' . $page_data['subject_id']));

    }

    function attendance_report($subject_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //SETTINGS
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //CURSO-Materia
        $Subject = new SubjectModel();
        $curso = $Subject->subject_section($subject_id);
        if (empty($curso) || (int)$curso[0]['teacher_id'] !== (int)$teacher_id) {
            return redirect()->to(base_url());
        }
        $page_data['section_id'] = $curso[0]['section_id'];
        $page_data['curso'] = $curso[0]['completo'];
        $page_data['subject_id'] = $subject_id;
        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($page_data['section_id'], $teacher_id);
        $page_data['students'] = $students;

        // Detectar si es primaria 3ro-6to para leer la tabla correcta
        $sectionGrade = \Config\Database::connect('asistencia')
            ->query("SELECT grade FROM section WHERE section_id = ?", [$page_data['section_id']])
            ->getRowArray()['grade'] ?? '';
        $esPrimaria36 = isPrimaria36($sectionGrade);
        $page_data['is_primaria36'] = $esPrimaria36;

        if ($esPrimaria36) {
            // Primaria 3-6: el estado diario ya viene consolidado entre todas las
            // materias del maestro (prim_assistance, "última llamada manda"), así
            // que fechas y estados se leen de ahí en vez de por materia aislada.
            // Se filtra por teacher_id para que, si el mismo curso tiene otras
            // materias a cargo de OTRO maestro, sus llamadas no se mezclen aquí.
            $PrimMod = new PrimAssistancesubjectModel();
            $dias = $PrimMod->dias_diarios_section((int)$page_data['section_id'], (int)$page_data['phase_id'], (int)$teacher_id);
            $asis = $PrimMod->asis_diarios_section((int)$page_data['section_id'], (int)$page_data['phase_id'], (int)$teacher_id);
        } else {
            $DatesMod = new DatesModel();
            $dias = $DatesMod->dias_subject($subject_id, $Setting->get_phase_id());
            $asis = (new AssistancesubjectModel())->assis_subject($subject_id, $page_data['phase_id']);
        }
        $page_data['dias'] = $dias;
        $page_data['asis'] = $asis;

        $page_data['page_name'] = 'attendance_report';
        $page_data['page_title'] = 'Reporte de Asistencias';
        return view('backend/index', $page_data);
    }

    /**
     * Corrige/registra un día puntual de asistencia diaria (primaria 3-6) desde
     * el Reporte de Asistencia. Usa el mismo modelo/regla que Asistencia del Día
     * de secretaría (prim_assistance, bloqueado si el día ya tiene licencia
     * aprobada) — a propósito NO toca assistance_edit()/assistance_add(), que
     * son de secundaria y no deben mezclarse con las tablas prim_.
     */
    public function prim_attendance_report_save()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $teacher_id = $session->get('teacher_id');
        $subject_id = $this->request->getPost('subject_id');
        $student_id = (int) ($this->request->getPost('student_id') ?? 0);
        $date       = $this->request->getPost('date');
        $status     = (int) ($this->request->getPost('status') ?? -1);
        $obs        = trim($this->request->getPost('obs') ?? '') ?: null;

        // Verificar que este maestro efectivamente dicte alguna materia en la
        // sección del alumno antes de dejarlo corregir su asistencia diaria.
        $dbCheck = \Config\Database::connect('asistencia');
        $tieneMateriaAhi = $dbCheck->query(
            "SELECT 1 FROM t_student s
             INNER JOIN subject sub ON sub.section_id = s.section_id
             WHERE s.student_id = ? AND sub.teacher_id = ?
             LIMIT 1",
            [$student_id, $teacher_id]
        )->getRow();
        if (!$tieneMateriaAhi) {
            return redirect()->to(base_url());
        }

        $ok = (new PrimAssistancesubjectModel())->guardarAsistenciaDiaria(
            $student_id, (string) $date, $status, $obs,
            $session->get('name'), 'Docente', $teacher_id
        );

        if ($ok) {
            $Setting  = new SettingModel();
            $phase_id = $Setting->get_phase_id();
            $phaseRow = \Config\Database::connect('tiquipaya')
                ->query("SELECT inicio, fin FROM phase WHERE phase_id = ?", [$phase_id])
                ->getRowArray();
            if ($phaseRow) {
                (new PrimCupoModel())->verificarYGenerarAlertas(
                    $student_id, $phase_id, $phaseRow['inicio'], $phaseRow['fin']
                );
            }
            $session->set('flash_message', 'Asistencia actualizada correctamente.');
        } else {
            $session->set('flash_message_error', 'No se pudo actualizar: el día ya está cubierto por una licencia aprobada, o el estado no es válido.');
        }

        return redirect()->to(base_url() . 'teacher/attendance_report/' . $subject_id);
    }

    function assistance_edit($subject_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //SETTINGS
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        //Parametros
        $page_data['subject_id'] = $_POST['subject_id'];
        $assistance_subject_id = $_POST['assistance_subject_id'];
        $status = $_POST['chk_' . $assistance_subject_id];
        $obs = $_POST['obs'];
        //ACTUALIZAMOS
        $AssistanceMod = new AssistancesubjectModel();
        $datos = [
            "status" => $status,
            "indiscipline" => $obs,
        ];
        $respuesta = $AssistanceMod->update_assistance_subject($datos, $assistance_subject_id);
        if ($respuesta == 1) {
            $session->set('flash_message', 'Asistencia modificada correctamente');
            return redirect()->to(base_url() . 'teacher/attendance_report/' . $subject_id);
        } else {
            $session->set('flash_message_error', 'Error al modificar');
            return redirect()->to(base_url() . 'teacher/attendance_report/' . $subject_id);
        }

    }
    function assistance_del($subject_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //Parametros
        $subject_id = $_POST['subject_id'];
        $date_id = $_POST['date_id'];
        //ACTUALIZAMOS
        $AssistanceMod = new AssistancesubjectModel();
        $datos = [
            "date_id" => $date_id,
            "subject_id" => $subject_id,
        ];
        $respuesta = 1;
        $asistencias = $AssistanceMod->get_assistance_subject($datos);
        if (count($asistencias) < 30) {
            //Eliminamos las Asistencias de esa fecha
            $respuesta = $AssistanceMod->delete_assistance_subject($datos);
        }
        if (count($asistencias) < 30) {
            $session->set('flash_message', 'Asistencia modificada correctamente' . count($asistencias));
            return redirect()->to(base_url() . 'teacher/attendance_report/' . $subject_id);
        } else {
            $session->set('flash_message_error', 'Error al modificar');
            return redirect()->to(base_url() . 'teacher/attendance_report/' . $subject_id);
        }
    }

    function attendance_date_edit($subject_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //SETTINGS
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        //Parametros
        //$page_data['section_id'] = $param1;
        $page_data['teacher_id'] = $teacher_id;
        $page_data['subject_id'] = $subject_id;
        $date = $_POST['fecha'];
        $date_id_ant = $_POST['date_id'];
        $date_id_nue = 0;


        //Verificamos si existe DATE_ID para insertar
        $data = ["date_class" => $date];
        $DatesMod = new DatesModel();
        $respuesta = $DatesMod->get_attendance_dates($data);
        if (count($respuesta) >= 1) {
            $date_id_nue = $respuesta[0]['date_id'];
        } else {
            $datos = [
                "date_class" => $date,
                "phase_id" => $phase_id,
            ];
            $respuesta = $DatesMod->insert_attendance_dates($datos);
            $date_id_nue = $respuesta;
        }
        //ACTUALIZAMOS
        $AssistanceMod = new AssistancesubjectModel();
        $datos = [
            "date_id" => $date_id_nue,
        ];
        $respuesta = $AssistanceMod->update_assistance_date($datos, $subject_id, $date_id_ant);
        $respuesta = 1;
        if ($respuesta > 0) {
            $session->set('flash_message', 'Asistencia modificada correctamente');
            return redirect()->to(base_url() . 'teacher/attendance_report/' . $subject_id);
        } else {
            $session->set('flash_message_error', 'Error al modificar');
            return redirect()->to(base_url() . 'teacher/attendance_report/' . $subject_id);
        }
    }
    public function assists_excel($subject_id, $section_id, $phase_id)
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //instanciamos la libreria
        //$spreadsheet = new Spreadsheet();
        //$activeWorksheet = $spreadsheet->getActiveSheet();


        //Instanciamos la libreria
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        //**************ABRIMOS EXCEL DE ACUERDO A EL CURSO QUE CORRESPONDE
        $obj_PHPExcel = $obj_Reader->load('templates/assists.xlsx');
        $activeWorksheet = $obj_PHPExcel->getActiveSheet();


        //RELLENAMOS DATOS
        //NOMBRE PLANILLA
        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subject_section($subject_id);
        $fileName = $phase_id . "_" . $subjects[0]['name'] . "_" . $subjects[0]['nick_name'] . ".xlsx";
        //Rellenamos Datos
        $activeWorksheet->setCellValue('A5', $subjects[0]['completo']);
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($subjects[0]['section_id'], $teacher_id);

        // Bulk fetch: fechas y asistencias en 2 queries en lugar de N×D
        $AssistanceMod = new AssistancesubjectModel();
        $dias     = $AssistanceMod->assis_dates($subject_id, $phase_id);
        $date_ids = array_column($dias, 'date_id');

        $allAsistencias = [];
        if (!empty($date_ids)) {
            $student_ids    = array_column($students, 'student_id');
            $rows_bulk      = db_connect('asistencia')->table('assistance_subject')
                ->whereIn('student_id', $student_ids)
                ->whereIn('date_id', $date_ids)
                ->where('subject_id', $subject_id)
                ->get()->getResultArray();
            foreach ($rows_bulk as $r) {
                $allAsistencias[$r['student_id']][$r['date_id']] = $r['status'];
            }
        }

        $statusLabel = [0 => 'A', 1 => 'P', 2 => 'L', 3 => 'R'];
        $conter = 8;
        foreach ($students as $row):
            $activeWorksheet->SetCellValue('B' . $conter, $row['student']);
            $i = 0;
            foreach ($dias as $dia):
                if ($conter == 8) {
                    $newDate = date("d/m/Y", strtotime($dia['date_class']));
                    $activeWorksheet->setCellValueByColumnAndRow(3 + $i, 7, $newDate);
                }
                $status = $allAsistencias[$row['student_id']][$dia['date_id']] ?? null;
                if ($status !== null && isset($statusLabel[$status])) {
                    $activeWorksheet->setCellValueByColumnAndRow(3 + $i, $conter, $statusLabel[$status]);
                }
                $i++;
            endforeach;
            $conter++;
        endforeach;
        //$writer = new Xlsx($spreadsheet);
        //$writer->save($fileName);
        //return $this->response->download($fileName, null);



        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);


    }

    /******************* BEGIN DEPARTAMENTO PSICOPEDAGOGICO ************************/
    function curricular_adaptations()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //SETTINGS
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();

        /*
        if ($this->session->userdata('teacher_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'curricular_adaptations';
        $page_data['page_title'] = 'Adaptaciones Curriculares';
        $page_data['page_title_0'] = 'Dpto. PP';
        $this->load->view('backend/index', $page_data);
        */
        $AdaptationMod = new AdaptationsModel();
        $adaptations = $AdaptationMod->adaptations();
        $page_data['students'] = $adaptations;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'curricular_adaptations';
        $page_data['page_title'] = 'Adaptaciones Curriculares';
        return view('backend/index', $page_data);
    }

    /******************* END DEPARTAMENTO PSICOPEDAGOGICO ************************/

    /****REPORTE CONDUCTUAL****/

    function behaviors($section_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $Subject = new SubjectModel();
        $subjects = $Subject->subjects_teacher($session->get('teacher_id'));

        $Section = new SectionModel();
        $cursos = $Section->section_docente($session->get('teacher_id'));

        $page_data['sub_prim12'] = $Subject->subject_prim12($session->get('teacher_id'));
        $page_data['sub_prim36'] = $Subject->subject_prim36($session->get('teacher_id'));
        $page_data['sub_sec13'] = $Subject->subject_sec13($session->get('teacher_id'));
        $page_data['sub_sec46'] = $Subject->subject_sec46($session->get('teacher_id'));

        //$page_data['teacher_id'] = $this->session->userdata('teacher_id');
        $Setting = new SettingModel();

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['cursos'] = $cursos;
        $page_data['page_name'] = 'behaviors';
        $page_data['page_title'] = 'Reporte Conductual';
        return view('backend/index', $page_data);
    }

    function history()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $Section = new SectionModel();
        $cursos = $Section->section_docente($session->get('teacher_id'));

        $Subject = new SubjectModel();
        $page_data['subjects'] = $Subject->subjects_teacher($session->get('teacher_id'));

        // Categorize unique sections by level (based on section_id ranges often used in this app)
        $sub_prim12 = [];
        $sub_prim36 = [];
        $sub_sec13 = [];
        $sub_sec46 = [];

        foreach ($cursos as $curso) {
            $sid = $curso['section_id'];
            if ($sid < 231)
                $sub_prim12[] = $curso;
            elseif ($sid < 271)
                $sub_prim36[] = $curso;
            elseif ($sid < 321)
                $sub_sec13[] = $curso;
            else
                $sub_sec46[] = $curso;
        }

        $page_data['sub_prim12'] = $sub_prim12;
        $page_data['sub_prim36'] = $sub_prim36;
        $page_data['sub_sec13'] = $sub_sec13;
        $page_data['sub_sec46'] = $sub_sec46;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['page_name'] = 'history';
        $page_data['page_title'] = 'Historial de Comportamiento';
        return view('backend/index', $page_data);
    }

    function history_students($section_id = '', $subject_id = 0)
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $teacher_id = $session->get('teacher_id');
        $SectionMod = new SectionModel();
        $sections = $SectionMod->get_section(['section_id' => $section_id]);

        if (empty($sections)) {
            return redirect()->to(base_url('teacher/history'));
        }

        $page_data['subject_id'] = $subject_id;
        $page_data['curso'] = $sections[0]['completo'];
        $page_data['nick_name'] = $sections[0]['nick_name'];

        if ($subject_id > 0) {
            $SubjectMod = new SubjectModel();
            $sub = $SubjectMod->find($subject_id);
            $page_data['materia'] = $sub ? $sub['name'] : 'Historial de Materia';
        } else {
            $page_data['materia'] = 'Historial General';
        }

        $page_data['section_id'] = $section_id;

        $StudentMod = new StudentModel();
        $page_data['students'] = $StudentMod->studentsSection($section_id, $teacher_id);

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['page_name'] = 'history_students';
        $page_data['page_title'] = 'Lista de Estudiantes';
        return view('backend/index', $page_data);
    }

    function behavior_add($subject_id = '')
    {

        //$subject_id = $_POST['subject'];
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $Section = new SectionModel();
        $cursos = $Section->section_docente($teacher_id);

        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subject_section($subject_id);

        $page_data['teacher_id'] = $teacher_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['cursos'] = $cursos;
        $page_data['materias'] = $subjects;
        //$subjects[0]['nick_name']

        if ($subject_id == 0) {
            $page_data['section_id'] = $subjects[0]['section_id'];

        } else {
            //Subject

            $subjects = $SubjectMod->subject_section($subject_id);
            $page_data['curso'] = $subjects[0]['completo'];
            $page_data['nick_name'] = $subjects[0]['nick_name'];
            $page_data['materia'] = $subjects[0]['name'];

            //$page_data['curso']  = $subjects[0]['nick_name']." - ".$subjects[0]['name'];
            $page_data['section_id'] = $subjects[0]['section_id'];
        }

        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($page_data['section_id'], $teacher_id);
        $page_data['students'] = $students;

        $BehaviorsMod = new BehaviorsModel();
        $students = $BehaviorsMod->behaviors_subject($page_data['section_id'], $teacher_id);
        $page_data['behaviors'] = $students;


        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'behavior_add';
        $page_data['page_title'] = 'Reportar Conductas';
        return view('backend/index', $page_data);


    }

    function behavior_save()
    {
        //VERIFICAMOS SESION
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //PARAMETROS
        $Setting = new SettingModel();
        //$section_id = $_POST['section_id'];
        $behavior = $_POST['behavior'];
        $tipo = 2;
        $fecha = strtotime($_POST["fecha"]);
        $viewed = 0;
        $phase_id = $Setting->get_phase_id();
        $subject_id = $_POST['subject_id'];
        $students = $_POST['students'];

        //Guardamos los reportes;
        $BehaviorsMod = new BehaviorsModel();
        //Recorremos Estudiantes
        foreach ($students as $student_id):
            $datos = [
                "behavior" => $behavior,
                "type" => $tipo,
                "date" => $fecha,
                "viewed" => $viewed,
                "phase_id" => $phase_id,
                "student_id" => $student_id,
                "subject_id" => $subject_id,
            ];
            $respuesta = $BehaviorsMod->insert_behaviors($datos);
            //Estudiante
            $StudentMod = new StudentModel();
            $students = $StudentMod->datosStudent($student_id);
            $student_name = $students[0]->nombre;
            //Familia
            $data = ["family_id" => $students[0]->family_id];
            $fam = new FamilyModel();
            $family = $fam->get_family($data);
            $email1 = $family[0]['email1'];
            $email2 = $family[0]['email2'];
            //SUJECTS
            $SubjectMod = new SubjectModel();
            $subject = $SubjectMod->subject_section($subject_id);
            $materia = $subject[0]['name'];
            //PARENTS

            //Enviamos Notificacion
            //Enviamos Email
            $subject = "";
            $subject = 'Notificación SAAT Id:' . $student_id;
            $EmailMod = new EmailModel();
            $mensaje = $EmailMod->behavior_msg('Notificación SAAT Id:' . $student_id, $student_name, $materia, $behavior);
            $email = \Config\Services::email();
            $email->setFrom('saat@tiquipaya.edu.bo', 'Saat Tiquipaya');
            //$email->setTo('franz.condori.calderon@gmail.com');
            $to = '';

            if (isset($email2)) {
                $to = $email1 . ', ' . $email2;
            } else {
                $email->setTo($email1);
                $to = $email1;
            }

            $to = $to . ', ' . 'saat@tiquipaya.edu.bo';
            //$subject = 'Id:'.$licencia_id.' - Licencia U. E. Tiquipaya';
            //$headers  = "MIME-Version: 1.0" . "\r\n";
            //$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 

            // To send HTML mail, the Content-type header must be set
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';

            // Additional headers
            //$headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
            $headers[] = 'From: SAAT <saat@tiquipaya.edu.bo>';
            //$headers[] = 'Cc: birthdayarchive@example.com';
            //$headers[] = 'Bcc: birthdaycheck@example.com';
            //mail($email_to, $email_sub, $email_msg, $headers);
            mail($to, $subject, $mensaje, implode("\r\n", $headers));
            //$email1 = 'soportetecnico@tiquipaya.edu.bo';
            //$email2 = 'etorrico@tiquipaya.edu.bo';
            /*
            $EmailMod = new EmailModel();
            if (isset($email1)) {
                $send_email1 = $EmailMod->behavior_email($student_name, $materia, $behavior, $email1);
            }
            if (isset($email2)) {
                $send_email2 = $EmailMod->behavior_email($student_name, $materia, $behavior, $email2);
            }
            */
        endforeach;
        $session->set('flash_message', 'Reportes guardados correctamente ');
        return redirect()->to(base_url() . 'teacher/behavior_add/' . $subject_id);
    }


    // *************************** ENTREVISTAS ****************************** */
    function interviews($section_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //Section Data
        $Section = new SectionModel();

        // If no section provided, default to 'all'
        if ($section_id == '') {
            $section_id = 'all';
        }

        if ($section_id != 'all') {
            $curso = $Section->get_section(["section_id" => $section_id]);
            $page_data['curso'] = isset($curso[0]['completo']) ? $curso[0]['completo'] : '';
            $page_data['section_id'] = $section_id;

            // Get Students for selector
            $StudentMod = new StudentModel();
            $page_data['students'] = $StudentMod->studentsSection($section_id, $teacher_id);

            // Get Interviews for specific section
            $InterviewMod = new InterviewModel();
            $page_data['interviews'] = $InterviewMod->getInterviewsByTeacher($teacher_id, $section_id);
        } else {
            // Show All
            $page_data['curso'] = 'Todos los Cursos';
            $page_data['section_id'] = 'all';
            $page_data['students'] = [];

            // Get All Interviews
            $InterviewMod = new InterviewModel();
            $page_data['interviews'] = $InterviewMod->getInterviewsByTeacher($teacher_id, null);
        }

        // Get Teacher Sections for navigation
        $page_data['sections'] = $Section->section_docente($teacher_id);

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Entrevistas";
        $page_data['page_name'] = "interviews";
        return view('backend/index', $page_data);
    }

    function interview_create()
    {
        // This function might be redundant if we use a modal on the main page, 
        // but keeping it if we want a separate page or loaded via ajax.
    }

    function interview_save()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');

        $validationRule = [
            'student_id' => 'required',
            'student_id' => 'required'
        ];

        if (!$this->validate($validationRule)) {
            $session->set('flash_message_error', 'Faltan datos requeridos');
            return redirect()->back();
        }

        $data = [
            'student_id' => $this->request->getPost('student_id'),
            'teacher_id' => $teacher_id,
            'section_id' => $this->request->getPost('section_id'),
            'assistant' => $this->request->getPost('assistant'),
            'reason' => $this->request->getPost('reason'),
            'description' => $this->request->getPost('description'),
            'agreements' => $this->request->getPost('agreements'),
            'follow_up_date' => $this->request->getPost('follow_up_date') ? $this->request->getPost('follow_up_date') : null,
            'status' => 1
        ];

        // File Upload
        $file = $this->request->getFile('userfile');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'public/uploads/interviews/', $newName);
            $data['attachment'] = $newName;
        }

        $InterviewMod = new InterviewModel();
        $InterviewMod->save($data);

        $session->set('flash_message', 'Entrevista registrada correctamente');
        return redirect()->back();
    }

    function interview_delete($interview_id)
    {
        $InterviewMod = new InterviewModel();
        $InterviewMod->update($interview_id, ['status' => 0]);

        $session = session();
        $session->set('flash_message', 'Entrevista eliminada');
        return redirect()->back();
    }

    /***************************** REGISTRO DE NOTAS ****************************** */
    function subjects()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $Subject = new SubjectModel();

        $Section = new SectionModel();
        $cursos = $Section->section_docente($session->get('teacher_id'));
        $subjects = $Subject->subjects_teacher($session->get('teacher_id'));
        $page_data['sub_prim12'] = $Subject->subject_prim12($session->get('teacher_id'));
        $page_data['sub_prim36'] = $Subject->subject_prim36($session->get('teacher_id'));
        $page_data['sub_sec13'] = $Subject->subject_sec13($session->get('teacher_id'));
        $page_data['sub_sec46'] = $Subject->subject_sec46($session->get('teacher_id'));

        //$page_data['teacher_id'] = $this->session->userdata('teacher_id');
        $Setting = new SettingModel();

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['self_appraisal'] = $Setting->get_self_appraisal();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['cursos'] = $cursos;
        $page_data['subjects'] = $subjects;
        $page_data['page_name'] = 'subjects';
        $page_data['page_title'] = 'Registros de Notas';
        return view('backend/index', $page_data);
    }
    function subject_notes($subject_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        $page_data['section_id'] = $subject[0]['section_id'];
        $page_data['subject_id'] = $subject_id;
        $page_data['subject'] = $subject[0];
        $page_data['sheet_id'] = $subject[0]['sheet_id'];
        //SETTINGS
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'subject_notes';
        $page_data['page_title'] = 'Registros de Notas';
        return view('backend/index', $page_data);
    }
    function subject_sheet_create($subject_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        if ($subject[0]['sheet_id'] == 0) {
            //Verificamos si existe el Archivo
            $nombre_fichero = 'planilla.xlsx';
            if (file_exists($nombre_fichero)) {
                //********************NOMBRE DE ARCHIVO********************/
                $filename = $subject[0]['name'] . '_' . $subject[0]['nick_name'] . '.xlsx';
                //**************EMAIL**************************/
                $emailDocente = $subject[0]['emailDocente'];
                //$emailDocente = "soportetecnico@tiquipaya.edu.bo";
                /***************CARPETA ************** */
                $folder = $subject[0]['folder'];
                //Creamos google con API GOOGLE
                $ApigoogleMod = new ApigoogleModel();
                $apigoogle = $ApigoogleMod->createSheet($subject_id, $folder, $filename, $emailDocente);

            }

        } else {
            //Creamos CSAMARKS para STUDENTS
            $CsamarksMod = new CsamarksModel();
            $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
            if (count($csamarks) == 0) {
                //Students
                $StudentMod = new StudentModel();
                $students = $StudentMod->studentsSection($subject[0]['section_id'], $teacher_id);
                foreach ($students as $stu):
                    //Preguntamos si ya Tiene Notas
                    $data_csamarks['student_id'] = $stu['student_id'];
                    $data_csamarks['locked'] = 0;
                    $data_csamarks['phase_id'] = $page_data['phase_id'];
                    $data_csamarks['subject_id'] = $subject_id;
                    $CsamarksMod = new CsamarksModel();
                    $respuesta = $CsamarksMod->insert_csamarks($data_csamarks);
                endforeach;
            } else {
                $page_data['csamarks'] = $csamarks;
            }

        }
    }
    function subject_notes_anterior($subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $teacher_id = $session->get('teacher_id');
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);

        if ($subject[0]['sheet_id'] == 0) {
            //Verificamos si existe el Archivo
            $nombre_fichero = 'planilla.xlsx';
            if (file_exists($nombre_fichero)) {
                //********************NOMBRE DE ARCHIVO********************/
                $filename = $subject[0]['name'] . '_' . $subject[0]['nick_name'] . '.xlsx';
                //**************EMAIL**************************/
                $emailDocente = $subject[0]['emailDocente'];
                //$emailDocente = "soportetecnico@tiquipaya.edu.bo";
                /***************CARPETA ************** */
                $folder = $subject[0]['folder'];
                //Creamos google con API GOOGLE
                $ApigoogleMod = new ApigoogleModel();
                $apigoogle = $ApigoogleMod->createSheet($subject_id, $folder, $filename, $emailDocente);

            }

        } else {
            //Creamos CSAMARKS para STUDENTS
            $CsamarksMod = new CsamarksModel();
            $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
            if (count($csamarks) == 0) {
                //Students
                $StudentMod = new StudentModel();
                $students = $StudentMod->studentsSection($subject[0]['section_id'], $teacher_id);
                foreach ($students as $stu):
                    //Preguntamos si ya Tiene Notas
                    $data_csamarks['student_id'] = $stu['student_id'];
                    $data_csamarks['locked'] = 0;
                    $data_csamarks['phase_id'] = $page_data['phase_id'];
                    $data_csamarks['subject_id'] = $subject_id;
                    $CsamarksMod = new CsamarksModel();
                    $respuesta = $CsamarksMod->insert_csamarks($data_csamarks);
                endforeach;
            } else {
                $page_data['csamarks'] = $csamarks;
            }

        }


        $page_data['section_id'] = $subject[0]['section_id'];
        $page_data['subject_id'] = $subject_id;
        $page_data['subject'] = $subject[0];
        $page_data['sheet_id'] = $subject[0]['sheet_id'];
        $page_data['page_name'] = 'subject_notes';
        $page_data['page_title'] = 'Registros de Notas';
        return view('backend/index', $page_data);

    }
    function create_sheet($subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $teacher_id = $session->get('teacher_id');
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();

        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        $curso = "Curso:  " . $subject[0]['completo'];
        $materia = $subject[0]['name'];
        $docente = $subject[0]['docente'];

        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($subject[0]['section_id'], $teacher_id);

        //FORMULAMOS LA VARIABLE
        if ($subject[0]['sheet_id'] <> '0') {
            //Configuramos planilla GOOGLE
            $ApigoogleMod = new ApigoogleModel();
            $apigoogle = $ApigoogleMod->configSheet($subject_id, $subject[0]['sheet_id'], $students, $curso, $materia, $docente);
        }

        $session->set('flash_message', 'Planilla creada exitosamente.');
        return redirect()->to(base_url() . 'teacher/subjects');
    }
    function integrate_sheet($subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $rev = array();
        $teacher_id = $session->get('teacher_id');
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();
        $rev['Periodo Planilla'] = $phase_name;

        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);

        //FORMULAMOS LA VARIABLE

        if ($subject[0]['sheet_id'] <> '0') {
            $rev['Estado Planilla'] = "Planilla Protegida";
            //Configuramos planilla GOOGLE
            $ApigoogleMod = new ApigoogleModel();
            $apigoogle = $ApigoogleMod->protectedSheet($subject[0]['sheet_id'], $subject_id);
            $rev['Datos Planilla'] = "Datos Protegidos";
        } else {
            $rev['Estado Planilla'] = "ERROR, Planilla no Generada";
        }


        $rev['Estado Planilla'] = "Planilla ya Generada";
        $datos['rev'] = $rev;
        return view('sheet_check', $datos);
    }

    function link_sheet($subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $rev = array();
        $teacher_id = $session->get('teacher_id');
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();
        $rev['Periodo Planilla'] = $phase_name;

        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);

        //FORMULAMOS LA VARIABLE

        if ($subject[0]['sheet_id'] <> '0') {

            //Configuramos planilla GOOGLE
            $ApigoogleMod = new ApigoogleModel();
            //$apigoogle = $ApigoogleMod->protectedPhase($subject[0]['sheet_id'], $subject_id, $phase_id);
            $rev['Estado Planilla'] = "Planilla enlazada";
            $rev['LINK Planilla'] = "<a href='https://docs.google.com/spreadsheets/d/" . $subject[0]['sheet_id'] . "/edit' target='_blank' class='btn btn-light-success font-weight-bold mr-2'>Ir a la Planilla</a>";
        } else {
            $rev['Estado Planilla'] = "ERROR, Planilla no Generada";
        }

        $rev['Estado Planilla'] = "Planilla ya Generada";
        $datos['rev'] = $rev;
        return view('sheet_check', $datos);
    }
    function half_phase($subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $rev = array();
        $teacher_id = $session->get('teacher_id');
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();
        $phase = $Setting->get_phase();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        $page_data['subject'] = $subject[0]['name'];
        $page_data['curso'] = $subject[0]['completo'];
        $partial_locked = $subject[0]['partial_locked'];

        //Creamos CSAMARKS para STUDENTS
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
        if (count($csamarks) == 0) {
            //Students
            $StudentMod = new StudentModel();
            $students = $StudentMod->studentsSection($subject[0]['section_id'], $teacher_id);
            foreach ($students as $stu):
                $check = [
                    "phase_id"   => $page_data['phase_id'],
                    "subject_id" => $subject_id,
                    "student_id" => $stu['student_id'],
                ];
                $CsamarksMod = new CsamarksModel();
                $existe = $CsamarksMod->get_csamarks($check);
                if (count($existe) == 0) {
                    $data_csamarks['student_id'] = $stu['student_id'];
                    $data_csamarks['locked'] = 0;
                    $data_csamarks['phase_id'] = $page_data['phase_id'];
                    $data_csamarks['subject_id'] = $subject_id;
                    $CsamarksMod = new CsamarksModel();
                    $respuesta = $CsamarksMod->insert_csamarks($data_csamarks);
                }
            endforeach;
        } 
        //Actualizamos CSAMARKC desde planilla GOOGLE
        //if ($official_id==0) {
        $ApigoogleMod = new ApigoogleModel();
        $apigoogle = $ApigoogleMod->importNotes($subject[0]['sheet_id'], $subject_id, $phase_id, $phase);
        //}
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
        $page_data['csamarks'] = $csamarks;
        //Detalles
        $CsamarksdetailsMod = new CsamarksdetailsModel();
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "ser");
        $page_data['details_ser'] = $csamarksdetails;
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "saber");
        $page_data['details_saber'] = $csamarksdetails;
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "hacer");
        $page_data['details_hacer'] = $csamarksdetails;
        //$csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "decidir");
        //$page_data['details_decidir'] = $csamarksdetails;

        /*
        $page_data['section_id']  = $subject[0]['section_id'];
        $page_data['subject_id']  = $subject_id;
        $page_data['subject']  = $subject[0];
        $page_data['sheet_id']  = $subject[0]['sheet_id'];
        */
        $page_data['locked'] = $subject[0]['locked'];
        $page_data['partial_locked'] = $partial_locked;
        $page_data['official_id'] = $subject[0]['official_id'];
        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'half_phase';
        $page_data['page_title'] = 'Medio Trimestre';
        return view('backend/index', $page_data);
    }
    function half_phase_save($subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $SubjectMod = new SubjectModel();
        $datos = [
            "partial_locked" => 1,
        ];
        $respuesta = $SubjectMod->update_subject($datos, $subject_id);
        $session->set('flash_message', 'Notas Guardadas correctamente');
        return redirect()->to(base_url() . 'teacher/subjects');
    }
    function recover_self($section_id, $subject_id)
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $rev = array();
        $teacher_id = $session->get('teacher_id');
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();
        $phase_abrev = $Setting->get_phase();
        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);

        $rev['Periodo Planilla'] = $phase_name;
        $ApigoogleMod = new ApigoogleModel();
        $apigoogle = $ApigoogleMod->recoverSelf($subject[0]['sheet_id'], $subject_id, $phase_id, $teacher_id, $phase_abrev);
        $rev['Autoevaluaciones'] = "Recuperadas";

        //$CsamarksMod = new CsamarksModel();
        //$csamarks = $CsamarksMod->csamarks_subject_update($subject_id, $phase_id);
        //$rev['Notas'] = "Promedios finales actualizados";
        $datos['rev'] = $rev;
        return view('sheet_check', $datos);
    }
    function recover_score($section_id, $subject_id)
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $rev = array();
        $teacher_id = $session->get('teacher_id');
        $Setting     = new SettingModel();
        $phase_id    = $Setting->get_phase_id();
        $phase_name  = $Setting->get_phase_name();
        $phase_abrev = $Setting->get_phase();
        $SubjectMod  = new SubjectModel();
        $subject     = $SubjectMod->subject_section($subject_id);
        $rev['Periodo Planilla'] = $phase_name;
        $section_id = $subject[0]['section_id'];
        $ApigoogleMod = new ApigoogleModel();
        //$phase_id se usa para filtrar los puntos SER (behavior_log/daily_scores)
        //al trimestre ACTUAL vía attendance_dates.phase_id; $phase_abrev
        //("1erTRIM"/"2doTRIM"/"3erTRIM") sigue siendo el nombre de la hoja
        //de Google Sheets donde se escribe el resultado.
        $ApigoogleMod->recoverScore($subject[0]['sheet_id'], $subject_id, $phase_abrev, $section_id, $teacher_id, $phase_id);
        $rev['Puntos SER'] = 'Recuperados';
        $datos['rev'] = $rev;
        return view('sheet_check', $datos);
    }
    function deliver_notes($subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $rev = array();
        $teacher_id = $session->get('teacher_id');
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();
        $phase = $Setting->get_phase();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        $page_data['subject'] = $subject[0]['name'];
        $page_data['curso'] = $subject[0]['completo'];
        $official_id = $subject[0]['official_id'];

        //Creamos CSAMARKS para STUDENTS
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
        if (count($csamarks) == 0) {
            //Students
            $StudentMod = new StudentModel();
            $students = $StudentMod->studentsSection($subject[0]['section_id'], $teacher_id);
            foreach ($students as $stu):
                //Preguntamos si ya Tiene Notas
                $data_csamarks['student_id'] = $stu['student_id'];
                $data_csamarks['locked'] = 0;
                $data_csamarks['phase_id'] = $page_data['phase_id'];
                $data_csamarks['subject_id'] = $subject_id;
                $CsamarksMod = new CsamarksModel();
                $respuesta = $CsamarksMod->insert_csamarks($data_csamarks);
            endforeach;
        } else {
            //Actualizamos CSAMARKC desde planilla GOOGLE
            //if ($official_id==0) {
            $ApigoogleMod = new ApigoogleModel();
            $apigoogle = $ApigoogleMod->importNotes($subject[0]['sheet_id'], $subject_id, $phase_id, $phase);
            //}
        }
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
        $page_data['csamarks'] = $csamarks;
        //Detalles
        $CsamarksdetailsMod = new CsamarksdetailsModel();
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "ser");
        $page_data['details_ser'] = $csamarksdetails;
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "saber");
        $page_data['details_saber'] = $csamarksdetails;
        $csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "hacer");
        $page_data['details_hacer'] = $csamarksdetails;
        //$csamarksdetails = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "decidir");
        //$page_data['details_decidir'] = $csamarksdetails;

        $page_data['official_id'] = $official_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'deliver_notes';
        $page_data['page_title'] = 'Entrega de Notas';
        return view('backend/index', $page_data);
    }
    function review_notes($subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $teacher_id = $session->get('teacher_id');

        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $page_data['trim'] = $Setting->get_phase();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //SUJECT
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        $page_data['subject'] = $subject[0]['name'];
        $page_data['curso'] = $subject[0]['completo'];
        //VALIDAMOS INFORMACION
        $rev = array();
        $errors = 0;

        //Id del google SHEET

        //VERIFICAMOS CSAMARCK
        $autos = $SubjectMod->subject_errors($subject_id, $phase_id);
        if (count($autos) == 0) {
            //$rev['Revisión de Notas'] = "Notas correctas";
        } else {
            $resp = "<b>Pendientes - </b><br />";
            foreach ($autos as $aut):
                $errors += 1;
                $resp .= $aut['lastname'] . " " . $aut['lastname2'] . " " . $aut['name'] . " : ";
                if ($aut['ser_average'] == 0) {
                    $resp .= "Promedio SER = 0";
                } elseif ($aut['saber_average'] == 0) {
                    $resp .= "Promedio SABER = 0";
                } elseif ($aut['hacer_average'] == 0) {
                    $resp .= "Promedio HACER = 0";
                } elseif ($aut['autoevaluacion'] == 0) {
                    $resp .= "Promedio Autoevaluacion SER = 0";
                } elseif ($aut['total_average'] == 0) {
                    $resp .= "Promedio Trimestral = 0";
                }
                $resp .= "<br />";
            endforeach;
            $resp .= "<br /><b>Por favor completar o ponderar la nota de la dimensión a 1.</b>";
            $rev['Revisión de Notas'] = $resp;
        }
        //Notas
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
        $page_data['csamarks'] = $csamarks;

        //DESCARGO DE APLAZADOS: por cada estudiante con nota final < 51,
        //debe existir un registro en nota_descargos. Si falta alguno, se
        //agrega a $rev, lo que deshabilita el botón "Consolidar Notas"
        //(ver lógica ya existente en review_notes.php). También se valida
        //en el servidor dentro de consolidate_notes().
        $NotaDescargoMod = new NotaDescargoModel();
        $aplazados = [];
        $faltantes = [];
        foreach ($csamarks as $nota) {
            if ($this->_es_aplazado($nota['total_average'])) {
                $descargo = $NotaDescargoMod->getDescargo($nota['student_id'], $subject_id, $phase_id);
                $aplazados[] = [
                    'student_id'    => $nota['student_id'],
                    'student'       => $nota['student'],
                    'total_average' => $nota['total_average'],
                    'descargo'      => $descargo,
                ];
                if (!$descargo) {
                    $faltantes[] = $nota['student'];
                }
            }
        }
        if (count($faltantes) > 0) {
            $rev['Descargos de Aplazados'] = "<b>Pendientes - </b><br />" . implode('<br />', $faltantes) .
                "<br /><br /><b>Debe completar el descargo de cada estudiante aplazado antes de consolidar.</b>";
        }
        $page_data['rev'] = $rev;
        $page_data['aplazados'] = $aplazados;

        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'review_notes';
        $page_data['page_title'] = 'Revisión de Notas';
        return view('backend/index', $page_data);
    }

    /**
     * Un estudiante se considera "aplazado" en una materia/trimestre cuando
     * su nota final ya está calculada (no es 0/NULL, lo que indicaría notas
     * incompletas y ya está bloqueado aparte por subject_errors()) y es
     * menor al mínimo aprobatorio de 51 sobre 100. Mismo umbral usado en
     * Admin.php (pct_aprobados/pct_reprobados) y en review_notes.php para
     * pintar la nota en rojo; aplica por igual a todos los niveles/plantillas
     * (cp12, cp36, cs12, cs34, cs56), ya que csamarks.total_average siempre
     * está en escala 0-100.
     */
    private function _es_aplazado($total_average): bool
    {
        return $total_average !== null && (float) $total_average > 0 && (float) $total_average < 51;
    }

    /**
     * Guarda (o actualiza) el descargo de un estudiante aplazado en una
     * materia. Llamado vía AJAX desde review_notes.php.
     */
    function save_descargo()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sin autorización']);
        $teacher_id = $session->get('teacher_id');

        $student_id     = $this->request->getPost('student_id');
        $subject_id     = $this->request->getPost('subject_id');
        $reunion_padres = $this->request->getPost('reunion_padres');
        $nro_reuniones  = (int) $this->request->getPost('nro_reuniones');
        $estrategias    = trim((string) $this->request->getPost('estrategias_aplicadas'));
        $motivo         = trim((string) $this->request->getPost('motivo_aplazo'));

        if (!$student_id || !$subject_id || $reunion_padres === null || $estrategias === '' || $motivo === '')
            return $this->response->setJSON(['status' => 'error', 'message' => 'Faltan datos requeridos']);

        //Si dijo que SÍ se reunió, debe indicar cuántas veces (al menos 1).
        //Si dijo que NO, se guarda en 0 sin importar lo que llegue del form.
        if ($reunion_padres) {
            if ($nro_reuniones < 1)
                return $this->response->setJSON(['status' => 'error', 'message' => 'Indique cuántas veces se reunió con los padres de familia']);
        } else {
            $nro_reuniones = 0;
        }

        //Verificamos que la materia pertenezca al docente logueado
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        if (empty($subject) || $subject[0]['teacher_id'] != $teacher_id)
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sin autorización sobre esta materia']);

        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();

        //Verificamos que el estudiante esté realmente aplazado en esta materia/trimestre
        $CsamarksMod = new CsamarksModel();
        $marks = $CsamarksMod->get_csamarks([
            'student_id' => $student_id,
            'subject_id' => $subject_id,
            'phase_id'   => $phase_id,
        ]);
        if (empty($marks) || !$this->_es_aplazado($marks[0]['total_average']))
            return $this->response->setJSON(['status' => 'error', 'message' => 'El estudiante no está aplazado en esta materia']);

        $datos = [
            'student_id'            => $student_id,
            'subject_id'            => $subject_id,
            'teacher_id'            => $teacher_id,
            'section_id'            => $subject[0]['section_id'],
            'phase_id'              => $phase_id,
            'phase_name'            => $Setting->get_phase_name(),
            'gestion'               => $Setting->get_gestion(),
            'nota_final'            => $marks[0]['total_average'],
            'reunion_padres'        => $reunion_padres ? 1 : 0,
            'nro_reuniones'         => $nro_reuniones,
            'estrategias_aplicadas' => $estrategias,
            'motivo_aplazo'         => $motivo,
        ];

        $NotaDescargoMod = new NotaDescargoModel();
        $descargo_id = $NotaDescargoMod->saveDescargo($datos);

        return $this->response->setJSON([
            'status'      => 'success',
            'descargo_id' => $descargo_id,
            'pdf_url'     => base_url() . 'teacher/descargo_pdf/' . $descargo_id,
        ]);
    }

    /**
     * Genera el documento imprimible del descargo (FPDF, mismo patrón que
     * Secretary::kardex_student_pdf), para que el docente lo imprima y firme.
     */
    function descargo_pdf($descargo_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $teacher_id = $session->get('teacher_id');

        $NotaDescargoMod = new NotaDescargoModel();
        $descargo = $NotaDescargoMod->getForPdf($descargo_id);
        if (empty($descargo) || $descargo['teacher_id'] != $teacher_id)
            return redirect()->to(base_url());

        $pdf = $this->_buildDescargoPdf($descargo);
        $pdf->Output('I', 'descargo_' . $descargo_id . '.pdf');
        exit;
    }

    /**
     * Arma el FPDF de un descargo individual (sin hacer Output). Compartido
     * por descargo_pdf() (descarga suelta) y descargos_zip() (descarga
     * masiva), para no duplicar el layout del documento.
     */
    private function _buildDescargoPdf(array $descargo): \FPDF
    {
        require_once('fpdf184/fpdf.php');
        $pdf = new \FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, utf8_decode('DESCARGO DE ESTUDIANTE APLAZADO'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, utf8_decode('Gestión ' . $descargo['gestion'] . ' - ' . $descargo['phase_name']), 0, 1, 'C');
        $pdf->Ln(4);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 8, utf8_decode('Datos Generales'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(45, 7, 'Estudiante:', 0, 0);
        $pdf->Cell(0, 7, utf8_decode($descargo['student']), 0, 1);

        $pdf->Cell(45, 7, 'Materia:', 0, 0);
        $pdf->Cell(0, 7, utf8_decode($descargo['subject_name']), 0, 1);

        $pdf->Cell(45, 7, 'Curso:', 0, 0);
        $pdf->Cell(0, 7, utf8_decode($descargo['curso']), 0, 1);

        $pdf->Cell(45, 7, 'Docente:', 0, 0);
        $pdf->Cell(0, 7, utf8_decode($descargo['teacher_name']), 0, 1);

        $pdf->Cell(45, 7, utf8_decode('Nota Final Obtenida:'), 0, 0);
        $pdf->Cell(0, 7, utf8_decode($descargo['nota_final'] . ' / 100 (Mínimo aprobatorio: 51)'), 0, 1);

        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Descargo del Docente'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $reunionTexto = $descargo['reunion_padres'] ? 'Sí (' . (int) ($descargo['nro_reuniones'] ?? 0) . ' vez/veces)' : 'No';
        $pdf->Cell(0, 7, utf8_decode('¿Se reunió con los padres de familia?  ' . $reunionTexto), 0, 1);
        $pdf->Ln(2);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, utf8_decode('Estrategias aplicadas antes del aplazo:'), 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(0, 6, utf8_decode($descargo['estrategias_aplicadas']), 0);
        $pdf->Ln(2);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, utf8_decode('Motivo por el que considera que el estudiante se aplazó:'), 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(0, 6, utf8_decode($descargo['motivo_aplazo']), 0);

        $pdf->Ln(18);
        $y = $pdf->GetY();
        $pdf->Line(30, $y, 100, $y);
        $pdf->Line(120, $y, 190, $y);
        $pdf->SetXY(30, $y + 1);
        $pdf->Cell(70, 6, utf8_decode('Firma Docente'), 0, 0, 'C');
        $pdf->SetXY(120, $y + 1);
        $pdf->Cell(70, 6, 'Fecha: ___/___/______', 0, 1, 'C');

        return $pdf;
    }

    /**
     * Descarga masiva: arma un .zip con el PDF de cada descargo del docente
     * que coincida con los filtros de "Mis Descargos" (mismo phase_id /
     * subject_id que la lista que se está viendo) y lo entrega para
     * descargar. Requiere la extensión ZipArchive de PHP.
     */
    function descargos_zip()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $teacher_id = $session->get('teacher_id');

        if (!class_exists('ZipArchive')) {
            $session->set('flash_message_error', 'No se puede generar el ZIP: la extensión ZipArchive no está disponible en el servidor.');
            return redirect()->to(base_url() . 'teacher/mis_descargos');
        }

        $filtro_phase_id   = $this->request->getGet('phase_id');
        $filtro_subject_id = $this->request->getGet('subject_id');

        $NotaDescargoMod = new NotaDescargoModel();
        $descargos = $NotaDescargoMod->getHistorial($teacher_id, $filtro_phase_id, $filtro_subject_id);

        if (count($descargos) == 0) {
            $session->set('flash_message_error', 'No tiene descargos guardados con estos filtros.');
            return redirect()->to(base_url() . 'teacher/mis_descargos');
        }

        $zipPath = WRITEPATH . 'uploads/descargos_' . $teacher_id . '_' . time() . '.zip';
        if (!is_dir(WRITEPATH . 'uploads')) {
            mkdir(WRITEPATH . 'uploads', 0777, true);
        }

        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $usados = [];
        foreach ($descargos as $d) {
            $pdf = $this->_buildDescargoPdf($d);
            $contenido = $pdf->Output('S');

            //Nombre de archivo legible y sin choques si dos descargos generan
            //el mismo nombre (ej. mismo estudiante en dos materias/trimestres).
            $base = preg_replace('/[^A-Za-z0-9_\- ]/', '', $d['student'] . '_' . $d['subject_name']);
            $base = trim(preg_replace('/\s+/', '_', $base));
            $nombre = $base . '.pdf';
            $i = 1;
            while (in_array($nombre, $usados)) {
                $nombre = $base . '_' . (++$i) . '.pdf';
            }
            $usados[] = $nombre;

            $zip->addFromString($nombre, $contenido);
        }
        $zip->close();

        //El archivo temporal se borra apenas se termina de enviar la
        //respuesta, para no acumular zips en writable/uploads.
        register_shutdown_function(function () use ($zipPath) {
            if (is_file($zipPath)) {
                @unlink($zipPath);
            }
        });

        return $this->response->download($zipPath, null)->setFileName('mis_descargos.zip');
    }

    /**
     * Historial "Mis Descargos" del docente logueado, filtrable por
     * trimestre y materia. Permite re-descargar el PDF aunque la fase de
     * revisión ya haya pasado y el estudiante ya no aparezca como aplazado
     * pendiente en review_notes.
     */
    function mis_descargos()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $teacher_id = $session->get('teacher_id');

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $filtro_phase_id   = $this->request->getGet('phase_id');
        $filtro_subject_id = $this->request->getGet('subject_id');

        $NotaDescargoMod = new NotaDescargoModel();
        $page_data['descargos'] = $NotaDescargoMod->getHistorial($teacher_id, $filtro_phase_id, $filtro_subject_id);
        $page_data['fases'] = $NotaDescargoMod->getFasesByTeacher($teacher_id);

        $SubjectMod = new SubjectModel();
        $page_data['materias'] = $SubjectMod->subjects_teacher($teacher_id);

        $page_data['filtro_phase_id'] = $filtro_phase_id;
        $page_data['filtro_subject_id'] = $filtro_subject_id;

        $page_data['page_name'] = 'mis_descargos';
        $page_data['page_title'] = 'Mis Descargos';
        return view('backend/index', $page_data);
    }
    public function consolidate_notes()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        $teacher_id = $session->get('teacher_id');
        $subject_id = $_POST['subjectId'];
        //SUJECT
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);

        //VALIDACIÓN SERVER-SIDE: no se puede consolidar si hay estudiantes
        //aplazados sin su descargo guardado. No basta con deshabilitar el
        //botón en review_notes.php; se revalida acá.
        $Setting  = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_subject($subject_id, $phase_id);
        $NotaDescargoMod = new NotaDescargoModel();
        $faltantes = [];
        foreach ($csamarks as $nota) {
            if ($this->_es_aplazado($nota['total_average']) && !$NotaDescargoMod->tieneDescargo($nota['student_id'], $subject_id, $phase_id)) {
                $faltantes[] = $nota['student'];
            }
        }
        if (count($faltantes) > 0) {
            $session->set('flash_message_error', 'No se puede consolidar: faltan descargos de estudiantes aplazados: ' . implode(', ', $faltantes));
            return redirect()->to(base_url() . 'teacher/review_notes/' . $subject_id);
        }

        //Actualizamos Subjects
        $data = ["locked" => 1];
        $respuesta = $SubjectMod->update_subject($data, $subject_id);

        if ($respuesta > 0) {
            //Bloqueamos la hoja del trimestre ACTUAL (1erTRIM/2doTRIM/3erTRIM
            //según $phase_id) en Google Sheets, para que el docente ya no
            //pueda seguir editando esa hoja tras consolidar.
            $ApigoogleMod = new ApigoogleModel();
            $apigoogle = $ApigoogleMod->lockedSheetByPhase($subject[0]['sheet_id'], $phase_id);

            $msg = 'Notas consolidadas Correctamente: ' . $subject[0]['sheet_id'];
            if (!$apigoogle['success']) {
                //La consolidación en el sistema ya se guardó (no dependemos
                //de Google Sheets para eso); solo avisamos que la planilla
                //no quedó protegida, para que el administrador lo revise.
                $msg .= '. Aviso: no se pudo bloquear la hoja en Google Sheets (' . $apigoogle['message'] . ').';
            } elseif (!empty($apigoogle['warnings'])) {
                $msg .= '. La hoja quedó bloqueada, aunque Google Sheets reportó advertencias menores al proteger algunas celdas.';
            }
            $session->set('flash_message', $msg);
            return redirect()->to(base_url() . 'teacher/subjects');
        } else {
            $session->set('flash_message_error', 'Error al consolidar Notas');
            return redirect()->to(base_url() . 'teacher/subjects');
        }
    }
    /***********************************************CONSEJERO***********************************************************/
    function adviser()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Teacher
        $teachers = new TeacherModel();
        $data = ["teacher_id" => $teacher_id];
        $teacher = $teachers->get_teacher($data);
        $page_data['teacher_id'] = $teacher_id;
        $page_data['teacher'] = $teacher[0]['name'];
        //Cursos
        $data2 = ["teacher_id" => $teacher_id];
        $Section = new SectionModel();
        $cursos = $Section->get_section($data2);
        $page_data['cursos'] = $cursos;

        // Por cada sección: estudiantes, autoevaluaciones e incidencias desde behavior_log
        $StudentMod  = new StudentModel();
        $SelfMod     = new SelfappraisalModel();
        $BehaviorMod = new BehaviorModel();
        $db_t2_adv   = \Config\Database::connect('tiquipaya');

        $students_data               = [];
        $selfs_data                  = [];
        $selfs_data_by_phase         = [];
        $infractions_subject_data    = [];  // chips T1
        $behavior_by_subject         = [];  // T1: subject => [total, students]
        $infractions_subject_data_t2 = [];  // chips T2
        $behavior_by_subject_t2      = [];  // T2: subject => [total, students]
        $alertas_data                = [];  // alertas por sección

        foreach ($cursos as $curso) {
            $sid = $curso['section_id'];
            $students_data[$sid] = $StudentMod->studentsSection($sid, 0);

            // Autoevaluaciones indexadas por student_id (fase actual — retrocompatibilidad)
            $selfs_raw = $SelfMod->self_section($sid, $page_data['phase_id']);
            $selfs_indexed = [];
            foreach ($selfs_raw as $s) {
                $selfs_indexed[$s['student_id']] = $s;
            }
            $selfs_data[$sid] = $selfs_indexed;

            // Autoevaluaciones por trimestre (T1, T2, T3)
            $selfs_data_by_phase[$sid] = [];
            for ($ph = 1; $ph <= 3; $ph++) {
                $raw = $SelfMod->self_section($sid, $ph);
                $indexed = [];
                foreach ($raw as $s) {
                    $indexed[$s['student_id']] = $s;
                }
                $selfs_data_by_phase[$sid][$ph] = $indexed;
            }

            // T1: todos los registros negativos de behavior_log sin filtro de fase
            $db_bl = \Config\Database::connect('default');
            $logs  = $db_bl->query("
                SELECT bl.student_id, bt.name AS behavior_name, bt.type,
                       IFNULL(sub.name, '—') AS subject_name,
                       s.name AS student_name, s.lastname AS student_lastname,
                       IFNULL(s.lastname2,'') AS student_lastname2
                FROM tiqui0_tiquiweb26.behavior_log bl
                JOIN tiqui0_tiquiweb26.behavior_types bt ON bt.id = bl.behavior_type_id AND bt.type = 'negative'
                JOIN tiqui0_tiquiasis26.t_student s ON s.student_id = bl.student_id AND s.section_id = ?
                LEFT JOIN tiqui0_tiquiasis26.subject sub ON sub.subject_id = bl.subject_id
                ORDER BY sub.name, s.lastname, s.name
            ", [$sid])->getResultArray();

            $by_subject   = [];  // [subject_name => [total, students => [...]]]
            $subject_totals = [];

            foreach ($logs as $log) {
                if ($log['type'] !== 'negative') continue;

                $stid     = $log['student_id'];
                $mat      = $log['subject_name'];
                $behavior = $log['behavior_name'];
                $fullname = trim($log['student_lastname'] . ' ' . $log['student_lastname2'] . ' ' . $log['student_name']);

                // Inicializar estructuras
                if (!isset($by_subject[$mat])) {
                    $by_subject[$mat] = ['total' => 0, 'students' => []];
                }
                if (!isset($by_subject[$mat]['students'][$stid])) {
                    $by_subject[$mat]['students'][$stid] = [
                        'name'      => $fullname,
                        'student_id'=> $stid,
                        'total'     => 0,
                        'behaviors' => [],
                    ];
                }
                if (!isset($by_subject[$mat]['students'][$stid]['behaviors'][$behavior])) {
                    $by_subject[$mat]['students'][$stid]['behaviors'][$behavior] = 0;
                }

                $by_subject[$mat]['students'][$stid]['behaviors'][$behavior]++;
                $by_subject[$mat]['students'][$stid]['total']++;
                $by_subject[$mat]['total']++;
                $subject_totals[$mat] = ($subject_totals[$mat] ?? 0) + 1;
            }

            // Ordenar materias por total de incidencias desc
            arsort($subject_totals);
            $ordered_by_subject = [];
            foreach ($subject_totals as $mat => $tot) {
                $ordered_by_subject[$mat] = $by_subject[$mat];
                // Ordenar estudiantes por total desc
                uasort($ordered_by_subject[$mat]['students'], fn($a, $b) => $b['total'] - $a['total']);
            }

            // Chips resumen
            $chips = [];
            foreach ($subject_totals as $mat => $tot) {
                $chips[] = ['materia' => $mat, 'total' => $tot];
            }

            $infractions_subject_data[$sid] = $chips;
            $behavior_by_subject[$sid]       = $ordered_by_subject;

            // ── T2: incidencia_registro (sistema nuevo) ──
            $logs_t2 = $db_t2_adv->query("
                SELECT ir.student_id, it.nombre AS behavior_name, it.tipo,
                       IFNULL(sub.name, '—') AS subject_name,
                       s.name AS student_name, s.lastname AS student_lastname,
                       IFNULL(s.lastname2,'') AS student_lastname2
                FROM incidencia_registro ir
                JOIN incidencia_tipos it ON ir.incidencia_tipo_id = it.id AND it.tipo = 'negativa'
                JOIN tiqui0_tiquiasis26.t_student s ON s.student_id = ir.student_id
                LEFT JOIN tiqui0_tiquiasis26.subject sub ON sub.subject_id = ir.subject_id
                WHERE s.section_id = ?
                ORDER BY subject_name, s.lastname, s.name
            ", [$sid])->getResultArray();

            $by_subject_t2    = [];
            $subject_totals_t2 = [];

            foreach ($logs_t2 as $log2) {
                $stid2    = $log2['student_id'];
                $mat2     = $log2['subject_name'];
                $bname2   = $log2['behavior_name'];
                $fname2   = trim($log2['student_lastname'] . ' ' . $log2['student_lastname2'] . ' ' . $log2['student_name']);

                if (!isset($by_subject_t2[$mat2])) {
                    $by_subject_t2[$mat2] = ['total' => 0, 'students' => []];
                }
                if (!isset($by_subject_t2[$mat2]['students'][$stid2])) {
                    $by_subject_t2[$mat2]['students'][$stid2] = ['name' => $fname2, 'student_id' => $stid2, 'total' => 0, 'behaviors' => []];
                }
                if (!isset($by_subject_t2[$mat2]['students'][$stid2]['behaviors'][$bname2])) {
                    $by_subject_t2[$mat2]['students'][$stid2]['behaviors'][$bname2] = 0;
                }
                $by_subject_t2[$mat2]['students'][$stid2]['behaviors'][$bname2]++;
                $by_subject_t2[$mat2]['students'][$stid2]['total']++;
                $by_subject_t2[$mat2]['total']++;
                $subject_totals_t2[$mat2] = ($subject_totals_t2[$mat2] ?? 0) + 1;
            }

            arsort($subject_totals_t2);
            $ordered_t2 = [];
            foreach ($subject_totals_t2 as $mat2 => $tot2) {
                $ordered_t2[$mat2] = $by_subject_t2[$mat2];
                uasort($ordered_t2[$mat2]['students'], fn($a, $b) => $b['total'] - $a['total']);
            }

            $chips_t2 = [];
            foreach ($subject_totals_t2 as $mat2 => $tot2) {
                $chips_t2[] = ['materia' => $mat2, 'total' => $tot2];
            }

            $infractions_subject_data_t2[$sid] = $chips_t2;
            $behavior_by_subject_t2[$sid]       = $ordered_t2;

            // ── Alertas: estudiantes que superan los umbrales en T1 o T2 ──
            $levels_rank  = ['info' => 0, 'warning' => 1, 'danger' => 2];
            $alertas_flat = [];

            // T2: nota del ser aproximada (10 - neg*0.5); alerta si nota <= 8
            foreach ($ordered_t2 as $mat2n => $mat_data2n) {
                foreach ($mat_data2n['students'] as $stid2n => $stdata2n) {
                    $neg2n      = $stdata2n['total'];
                    $nota_aprox = max(0, round(10 - $neg2n * 0.5, 1));
                    if ($nota_aprox <= 8) {
                        $niv2 = $nota_aprox < 7 ? 'danger' : ($nota_aprox < 8 ? 'warning' : 'info');
                        if (!isset($alertas_flat[$stid2n])) {
                            $alertas_flat[$stid2n] = ['nombre' => $stdata2n['name'], 'nivel' => 'info', 'detalles' => []];
                        }
                        if ($levels_rank[$niv2] > $levels_rank[$alertas_flat[$stid2n]['nivel']]) {
                            $alertas_flat[$stid2n]['nivel'] = $niv2;
                        }
                        $alertas_flat[$stid2n]['detalles'][] = 'T2 · ' . $mat2n . ': ' . $nota_aprox . ' pts';
                    }
                }
            }

            // T1: total de negativos por estudiante; alerta si >= 5
            $totales_t1_alerta = [];
            foreach ($ordered_by_subject as $mat1n => $mat_data1n) {
                foreach ($mat_data1n['students'] as $stid1n => $stdata1n) {
                    if (!isset($totales_t1_alerta[$stid1n])) {
                        $totales_t1_alerta[$stid1n] = ['nombre' => $stdata1n['name'], 'total' => 0];
                    }
                    $totales_t1_alerta[$stid1n]['total'] += $stdata1n['total'];
                }
            }
            foreach ($totales_t1_alerta as $stid1n => $sta1n) {
                $t1n = $sta1n['total'];
                if ($t1n >= 5) {
                    $niv1 = $t1n >= 10 ? 'danger' : ($t1n >= 7 ? 'warning' : 'info');
                    if (!isset($alertas_flat[$stid1n])) {
                        $alertas_flat[$stid1n] = ['nombre' => $sta1n['nombre'], 'nivel' => 'info', 'detalles' => []];
                    }
                    if ($levels_rank[$niv1] > $levels_rank[$alertas_flat[$stid1n]['nivel']]) {
                        $alertas_flat[$stid1n]['nivel'] = $niv1;
                    }
                    $alertas_flat[$stid1n]['detalles'][] = 'T1 · ' . $t1n . ' incidencias negativas';
                }
            }

            uasort($alertas_flat, fn($a, $b) => $levels_rank[$b['nivel']] <=> $levels_rank[$a['nivel']]);
            $alertas_data[$sid] = array_values($alertas_flat);
        }

        $page_data['students_data']               = $students_data;
        $page_data['selfs_data']                  = $selfs_data;
        $page_data['selfs_data_by_phase']         = $selfs_data_by_phase;
        $page_data['infractions_subject_data']    = $infractions_subject_data;
        $page_data['behavior_by_subject']         = $behavior_by_subject;
        $page_data['infractions_subject_data_t2'] = $infractions_subject_data_t2;
        $page_data['alertas_data']                = $alertas_data ?? [];
        $page_data['behavior_by_subject_t2']      = $behavior_by_subject_t2;

        //Vista
        $page_data['page_name'] = 'adviser';
        $page_data['page_title'] = 'Consejeria';
        return view('backend/index', $page_data);
    }

    function adviser_behavior_log($section_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        // Section Info
        $Section = new SectionModel();
        $section_info = $Section->find($section_id);
        $page_data['section_name'] = $section_info ? $section_info['completo'] : 'Curso';

        $IncidenciaMod = new IncidenciaModel();
        $page_data['logs'] = $IncidenciaMod->getRegistroSeccion($section_id, $page_data['phase_id']);

        $page_data['section_id'] = $section_id;
        $page_data['page_name'] = 'adviser_behavior_log';
        $page_data['page_title'] = 'Log de Incidencias del Curso';

        return view('backend/index', $page_data);
    }

    function self_inicial($section_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Section
        $data = ["section_id" => $section_id];
        $Section = new SectionModel();
        $curso = $Section->get_section($data);
        $page_data['completo'] = $curso[0]['completo'];

        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($section_id, 0);
        $page_data['students'] = $students;
        //Autoevaluaciones
        $self = new SelfappraisalModel();
        $autos = $self->self_section($section_id, $page_data['phase_id']);
        $page_data['autos'] = $autos;

        $page_data['section_id'] = $section_id;
        $page_data['page_name'] = 'self_inicial';
        $page_data['page_title'] = 'Autoevaluaciones Primaria 1ro-2do';
        return view('backend/index', $page_data);
    }
    function autoeval($student_id = '', $section_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //Parametros
        $student_id = $_POST['student_id'];
        $auto_ser = $_POST['auto_ser2'];

        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //Autoevaluaciones
        $data = [
            "student_id" => $student_id,
            "phase_id" => $page_data['phase_id']
        ];
        $self = new SelfappraisalModel();
        $existe = $self->get_self_appraisal($data);
        if (count($existe) == 0) {
            $data['autoevaluacion'] = $auto_ser;
            $data['student_id'] = $student_id;
            $data['phase_id'] = $page_data['phase_id'];
            $respuesta = $self->insert_self_appraisal($data);
            $session->set('flash_message', 'Autoevaluación Guardada Correctamente');
            return redirect()->to(base_url() . 'teacher/self_inicial/' . $section_id);
        } else {
            $self_appraisal_id = $existe[0]['self_id'];
            $data['autoevaluacion'] = $auto_ser;
            $respuesta = $self->update_self_appraisal($data, $self_appraisal_id);
            $session->set('flash_message', 'Datos actualizados Correctamente');
            return redirect()->to(base_url() . 'teacher/self_inicial/' . $section_id);
        }

    }
    function student_search($user = '', $sel = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
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
            if ($user == 'teacher') {
                $SubjectMod = new SubjectModel();
                $subjects = $SubjectMod->subjects_docente($teacher_id);
                $section_ids = [];
                foreach ($subjects as $sub) {
                    $section_ids[] = $sub->section_id;
                }
                $section_ids = array_unique($section_ids);

                $StudentMod = new StudentModel();
                $students = $StudentMod->students_by_sections($section_ids, $sel);
                $page_data['resultado'] = $students;
            } else {
                $StudentMod = new StudentModel();
                $students = $StudentMod->students_user($user, $sel, $teacher_id);
                $page_data['resultado'] = $students;
            }
            $page_data['busqueda'] = $sel;
        }
        //Vista
        $page_data['page_name'] = 'student_search';
        $page_data['page_title'] = 'Registrar Datos';
        return view('backend/index', $page_data);
    }

    function student_licenses($student_id = '', $filter = 'all', $offset = 0)
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        // Load Licenses
        $LicenciaModel = new LicenciaModel();
        $page_data['licencias'] = $LicenciaModel->licenciasStudent($student_id);

        $page_data['student_id'] = $student_id;
        $page_data['page_name'] = 'student_licenses';
        $page_data['page_title'] = 'Licencias del Estudiante';
        return view('backend/index', $page_data);
    }
    function family_search($user = '', $sel = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
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
    public function family_info($family_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
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
    /*****************************BOLETIN DE NOTAS*********************************/
    function report_card($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
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
    function student_notes($student_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
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
    function self_appraisal($section_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Section
        $data = ["section_id" => $section_id];
        $Section = new SectionModel();
        $curso = $Section->get_section($data);
        $page_data['completo'] = $curso[0]['completo'];

        //Teacher
        $teachers = new TeacherModel();
        $data = ["teacher_id" => $teacher_id];
        $teacher = $teachers->get_teacher($data);
        $page_data['teacher_id'] = $teacher_id;
        $page_data['teacher'] = $teacher[0]['name'];

        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($section_id, $teacher_id);
        $page_data['students'] = $students;
        //Autoevaluaciones
        $self = new SelfappraisalModel();
        $autos = $self->self_section($section_id, $page_data['phase_id']);
        $page_data['autos'] = $autos;
        //Director

        $page_data['section_id'] = $section_id;
        $page_data['page_name'] = 'self_appraisal';
        $page_data['page_title'] = 'Autoevaluaciones';
        return view('backend/index', $page_data);
    }
    function sections()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //Section
        $Section = new SectionModel();
        $cursos = $Section->section_docente($session->get('teacher_id'));
        $page_data['sections'] = $cursos;
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'sections';
        $page_data['page_title'] = 'Cursos del Docente';
        return view('backend/index', $page_data);
    }
    /*************************************************************************BEGIN::DIRECTOR *************************************/
    function student_attendance($student_id = '')
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

    /*************************************************************************END::DIRECTOR *************************************/
    function enable_sheet_phase($phase_id, $subject_id)
    {


        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //Verificamos que existe planilla
        //SUJECTS
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        $curso_materia = $subject[0]['nick_name'] . " - " . $subject[0]['name'];

        if ($subject[0]['sheet_id'] == 0) {
            $session->set('flash_message_error', 'Error al habilitar Planilla, no existe: ' . $curso_materia);
            return redirect()->to(base_url() . 'teacher/subjects');

        } else {
            //Bloquemaos el Trimestre
            //Settings
            $Setting = new SettingModel();
            $phase = $Setting->get_phase();
            //$session->set('flash_message_error', 'Error al habilitar Planilla, no existe: '.$curso_materia);
            try {
                $ApigoogleMod = new ApigoogleModel();
                $apigoogle = $ApigoogleMod->enable_sheet_phase($phase_id, $phase, $subject_id, $subject[0]['sheet_id']);
                echo $apigoogle;
            } catch (\Throwable $th) {

            }


        }

        $Subject = new SubjectModel();
        $session = session();
        $Section = new SectionModel();
        $cursos = $Section->section_docente($session->get('teacher_id'));
        $subjects = $Subject->subjects_teacher($session->get('teacher_id'));
        $page_data['sub_prim12'] = $Subject->subject_prim12($session->get('teacher_id'));
        $page_data['sub_prim36'] = $Subject->subject_prim36($session->get('teacher_id'));
        $page_data['sub_sec13'] = $Subject->subject_sec13($session->get('teacher_id'));
        $page_data['sub_sec46'] = $Subject->subject_sec46($session->get('teacher_id'));

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['self_appraisal'] = $Setting->get_self_appraisal();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['cursos'] = $cursos;
        $page_data['subjects'] = $subjects;
        $page_data['page_name'] = 'subjects';
        $page_data['page_title'] = 'Registros de Notas';
        return view('backend/index', $page_data);
    }
    function infractions()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //Section
        $SectionMod = new SectionModel();
        $page_data['cursos'] = $SectionMod->section_docente($session->get('teacher_id'));
        /*
        $data = [ "director_id" => $teacher_id ];
        $Section = new SectionModel();
        $cursos = $Section->get_section($data);
        $page_data['sections'] = $cursos ;
        */

        //si es director
        $page_data['sections'] = array();
        if ($session->get('director')) {
            //Section
            $data = ["director_id" => $teacher_id];
            $Section = new SectionModel();
            $cursos = $Section->get_section($data);
            $page_data['sections'] = $cursos;
        }


        $Subject = new SubjectModel();
        $materias = $Subject->subjects_docente($session->get('teacher_id'));
        $page_data['materias'] = $materias;
        $page_data['adviser'] = $session->get('director');
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'infractions';
        $page_data['page_title'] = 'Indisciplina';
        return view('backend/index', $page_data);
    }

    function infractions_section($section_id)
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //SUJECT
        /*
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        $page_data['subject']  = $subject[0]['name'];
        $page_data['curso']  = $subject[0]['completo'];
*/
        //Section
        $data = ["section_id" => $section_id];
        $Section = new SectionModel();
        $curso = $Section->get_section($data);
        $page_data['section_id'] = $curso[0]['section_id'];
        $page_data['curso'] = $curso[0]['completo'];
        /*
        $data = [ "director_id" => $teacher_id ];
        $Section = new SectionModel();
        $cursos = $Section->get_section($data);
        $page_data['sections'] = $cursos ;
        */
        //Estudiantes del curso
        $StudentMod = new StudentModel();
        $page_data['students'] = $StudentMod->studentsSection($section_id, $teacher_id);
        //Indisciplinas
        $IinfractionMod = new IinfractionModel();
        $page_data['infractions'] = $IinfractionMod->infraction_section($section_id);
        $page_data['subjects'] = $IinfractionMod->infraction_subjects($section_id);


        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'infractions_section';
        $page_data['page_title'] = 'Planilla Indisciplina';
        return view('backend/index', $page_data);
    }

    function infraction_save()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //PARAMETROS
        $date = $_POST['fecha'];
        if (isset($_POST['detail'])) {
            $detail = $_POST['detail'];
        } else {
            $detail = NULL;
        }
        $student_id = $_POST['student_id'];
        $subject_id = $_POST['subject_id'];
        //Infracciones del Estudiante
        $data = [
            "student_id" => $student_id,
            "subject_id" => $subject_id
        ];
        $IinfractionMod = new IinfractionModel();
        $infractions = $IinfractionMod->get_infraction($data);
        $number = count($infractions) + 1;
        $datos = [
            'criterio_id' => $_POST['criteria'],
            'date' => $date,
            'number' => $number,
            'detail' => $detail,
            'registered' => $teacher_id,
            'section_id' => $_POST['section_id'],
            'student_id' => $_POST['student_id'],
            'subject_id' => $_POST['subject_id'],
        ];

        $respuesta = $IinfractionMod->insert_infraction($datos);
        //Datos Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $student_name = $students[0]->nombre;
        $completo = $students[0]->completo;
        $FamilyMod = new FamilyModel();
        $family = $FamilyMod->get_family_emails($student_id);
        $EmailMod = new EmailModel();
        //Cuentas
        $to_parent = '';
        $to_director = '';
        $to_teachers = '';
        if (isset($family[0]['email1'])) {
            $to_parent = $family[0]['email1'] . ', ' . $family[0]['email2'];
        } else {
            $to_parent = $family[0]['email1'];
        }

        if ($number >= 3 && $family[0]['section_id'] >= 321) {
            //Emails Familia


            //Emails Director
            $sectionMod = new SectionModel();
            $emailsSection = $sectionMod->section_emails($family[0]['section_id']);
            $to_director = $emailsSection[0]['emailDocente'] . ', ' . $emailsSection[0]['emailDirector'] . ', etorrico@tiquipaya.edu.bo, secretaria@tiquipaya.edu.bo, jtorrico@tiquipaya.edu.bo';
            //Emails docentes
            $docentes_mails = $IinfractionMod->infraction_emails($student_id, $subject_id);
            foreach ($docentes_mails as $inf):
                $to_teachers .= $inf['emailDocente'] . ', ';
            endforeach;
            $to_teachers = substr($to_teachers, 0, -2);
        }
        if ($number == 3 && $family[0]['section_id'] >= 271) {
            //Datos Email Padre de Familia
            $para1 = $to_parent . ", saat@tiquipaya.edu.bo, etorrico@tiquipaya.edu.bo";
            //$para1      = "saat@tiquipaya.edu.bo";
            $titulo1 = "Id:" . strval($student_id) . " - Notificación por 3ra Falta";
            $mensaje1 = $EmailMod->alert_3_parent($titulo1, $student_name, $date);
            $headers1[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers1[] = 'MIME-Version: 1.0';
            $headers1[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para1, $titulo1, $mensaje1, implode("\r\n", $headers1));
            //Datos Email Director
            /*
            $para2      = $to_director;
            $titulo2    = $student_name." - Notificación Faltas Leves de Indisciplina";
            $mensaje2 = $EmailMod->alert_3_director($titulo2, $student_name, $completo);
            $headers2[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers2[] = 'MIME-Version: 1.0';
            $headers2[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para2, $titulo2, $mensaje2, implode("\r\n", $headers2));
            */
        } elseif ($number == 4) {
            //Genera Carta
            $this->infraction_letter($student_id, $subject_id, '4');
            //Notificacion al PPFF
            $para1 = $to_parent . ", saat@tiquipaya.edu.bo, etorrico@tiquipaya.edu.bo, secretaria@tiquipaya.edu.bo, jtorrico@tiquipaya.edu.bo";
            //$para1      = "saat@tiquipaya.edu.bo";
            $titulo1 = "Id:" . strval($student_id) . " - Comunicado de detención por 4ta falta de Indisciplina";
            $mensaje1 = '';
            $mensaje1 = $EmailMod->alert_4_parent($titulo1, $student_name, $completo, $student_id);
            $headers1[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers1[] = 'MIME-Version: 1.0';
            $headers1[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para1, $titulo1, $mensaje1, implode("\r\n", $headers1));
            //Datos Email Director
            $para2 = $to_director;
            $titulo2 = $student_name . " - Acumulación 4 faltas leves.";
            $mensaje2 = $EmailMod->alert_4_director($titulo2, $student_name, $completo, $student_id);
            $headers2[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers2[] = 'MIME-Version: 1.0';
            $headers2[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para2, $titulo2, $mensaje2, implode("\r\n", $headers2));
            //Datos Email Docentes
            $para3 = $to_teachers;
            $titulo3 = $student_name . " - Acumulación 4 faltas leves.";
            $mensaje3 = $EmailMod->alert_4_docentes($titulo3, $student_name, $completo, $student_id);
            $headers3[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers3[] = 'MIME-Version: 1.0';
            $headers3[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para3, $titulo3, $mensaje3, implode("\r\n", $headers3));

        } elseif ($number == 5) {
            //Genera Carta
            $this->infraction_letter($student_id, $subject_id, '5');
            $para1 = $to_parent . ", saat@tiquipaya.edu.bo, etorrico@tiquipaya.edu.bo, secretaria@tiquipaya.edu.bo, jtorrico@tiquipaya.edu.bo";
            $titulo1 = "Id:" . strval($student_id) . " - Comunicado de detención por 5ta falta de Indisciplina";
            $mensaje1 = '';
            $mensaje1 = $EmailMod->alert_5_parent($titulo1, $student_name, $completo, $student_id);
            $headers1[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers1[] = 'MIME-Version: 1.0';
            $headers1[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para1, $titulo1, $mensaje1, implode("\r\n", $headers1));
        }
        /*elseif($number==7){
            //Notificacion al PPFF
            $para1      = $to_parent.", saat@tiquipaya.edu.bo";
            $titulo1    = "Id:".strval($student_id)." - Notificación de Boleta por acumulación de faltas leves.";
            $mensaje1   = '';
            $mensaje1 = $EmailMod->alert_7_parent($titulo1, $student_name, $completo);
            $headers1[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers1[] = 'MIME-Version: 1.0';
            $headers1[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para1, $titulo1, $mensaje1, implode("\r\n", $headers1));
            //Datos Email Director
            $para2      = $to_director;
            $titulo2    = $student_name." - Acumulación 7 faltas leves, generación de boleta.";
            $mensaje2 = $EmailMod->alert_7_director($titulo2, $student_name, $completo);
            $headers2[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers2[] = 'MIME-Version: 1.0';
            $headers2[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para2, $titulo2, $mensaje2, implode("\r\n", $headers2));
            //Datos Email Docentes
            $para3      = $to_teachers;
            $titulo3    = $student_name." - Acumulación 7 faltas leves.";
            $mensaje3 = $EmailMod->alert_7_docentes($titulo3, $student_name, $completo);
            $headers3[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers3[] = 'MIME-Version: 1.0';
            $headers3[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para3, $titulo3, $mensaje3, implode("\r\n", $headers3));
        }elseif($number>7){
            //Notificacion al PPFF
            $para1      = $to_parent.", saat@tiquipaya.edu.bo";
            $titulo1    = "Id:".strval($student_id)." - Reincidencia de Faltas e Incumplimiento del Compromiso";
            $mensaje1   = '';
            $mensaje1 = $EmailMod->alert_8_parent($titulo1, $student_name, $completo);
            $headers1[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers1[] = 'MIME-Version: 1.0';
            $headers1[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para1, $titulo1, $mensaje1, implode("\r\n", $headers1));
            //Datos Email Director
            $para2      = $to_director;
            $titulo2    = $student_name." - Reincidencia de Faltas e Incumplimiento del Compromiso";
            $mensaje2 = $EmailMod->alert_8_director($titulo2, $student_name, $completo);
            $headers2[] = 'From: Saat Tiquipaya <saat@tiquipaya.edu.bo>';
            $headers2[] = 'MIME-Version: 1.0';
            $headers2[] = 'Content-type: text/html; charset=iso-8859-1';
            mail($para2, $titulo2, $mensaje2, implode("\r\n", $headers2));
        }
        */

        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó la indisciplina Correctamente' . $to_parent . ' - ' . $to_director . ' - ' . $to_teachers);
            return redirect()->to(base_url() . 'teacher/infractions_section/' . $_POST['section_id']);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'teacher/infractions_section/' . $_POST['section_id']);
        }
    }
    function infraction_updated()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //PARAMETROS
        $infraction_id = $_POST['infraction_id'];
        $date = $_POST['fecha'];
        $criterio_id = $_POST['criteria'];
        if (isset($_POST['detail'])) {
            $detail = $_POST['detail'];
        } else {
            $detail = NULL;
        }
        $datos = [
            'criterio_id' => $criterio_id,
            'date' => $date,
            'detail' => $detail,
            'section_id' => $_POST['section_id'],
            'student_id' => $_POST['student_id'],
            'subject_id' => $_POST['subject_id'],
        ];
        $IinfractionMod = new IinfractionModel();
        $respuesta = $IinfractionMod->update_infraction($datos, $infraction_id);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se actualizó la indisciplina Correctamente');
            return redirect()->to(base_url() . 'teacher/infractions_section/' . $_POST['section_id']);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'teacher/infractions_section/' . $_POST['section_id']);
        }
    }
    function infraction_deleted()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //PARAMETROS
        $data = ["infraction_id" => $_POST['infraction_id']];
        $IinfractionMod = new IinfractionModel();
        $respuesta = $IinfractionMod->delete_infraction($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Falta eliminada Correctamente');
            return redirect()->to(base_url() . 'teacher/infractions_section/' . $_POST['section_id']);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'teacher/infractions_section/' . $_POST['section_id']);
        }
    }
    function infraction_notify()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //Parametros
        $student_id = $_POST['student_id'];

        return redirect()->to(base_url() . 'teacher/infraction_letter/' . $student_id);
    }
    function infraction_letter($student_id = '', $subject_id = '', $cantidad = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $student_name = $students[0]->nombre;
        $curso = $students[0]->completo;
        //Infracciones
        $IinfractionMod = new IinfractionModel();
        $infractions = $IinfractionMod->infraction_student($student_id, $subject_id);
        //Instanciamos la libreria EXCEL y Abrimos el Template
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        //Abrimos el Excel segun la Cantidad
        switch ($cantidad) {
            case '4':
                $obj_PHPExcel = $obj_Reader->load('templates/ic_s4.xlsx');
                break;
            case '5':
                $obj_PHPExcel = $obj_Reader->load('templates/ic_s5.xlsx');
                break;
        }
        //Escribimos en el EXCEL
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A6', date("d/m/Y"));
        $obj_PHPExcel->getActiveSheet()->SetCellValue('F6', $student_name);
        $obj_PHPExcel->getActiveSheet()->SetCellValue('J6', $curso);
        //Transcribimos infracciones
        $conter = 10;
        foreach ($infractions as $inf):
            $fecha = date("d/m/Y", strtotime($inf['date']));
            $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $inf['materia']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('E' . $conter, $inf['criteria']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('I' . $conter, $fecha);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('J' . $conter, $inf['detail']);
            $conter++;
            if ($conter == 10 + $cantidad) {
                break;
            }
        endforeach;
        //Docente
        $conter = 15 + $cantidad;
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_docente_name($subject_id);
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A' . $conter, 'Prof. ' . $subject[0]['docente']);
        $fileName = 'Carta_' . $cantidad . '_' . $student_id . '.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $ruta_guardado = FCPATH . 'infractions/' . $fileName;
        $writer->save($ruta_guardado);
    }
    function infractions_excel($section_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        //Instanciamos la libreria EXCEL y Abrimos el Template
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        //**************ABRIMOS EXCEL DE ACUERDO A EL CURSO QUE CORRESPONDE
        $obj_PHPExcel = $obj_Reader->load('templates/infractions.xlsx');

        //Escribimos en el EXCEL
        //Estudiantes del curso
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_active($section_id);
        $conter = 7;

        //******************RELLENAMOS LOS NOMBREs
        foreach ($students as $row):
            $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
            $obj_PHPExcel->getActiveSheet()->SetCellValue('A' . $conter, $conter - 3);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $est);
            //Indisciplinas
            $IinfractionMod = new IinfractionModel();
            $infractions = $IinfractionMod->infraction_section($section_id);
            //Infracciones
            //$IinfractionMod = new IinfractionModel();
            //$infractions = $IinfractionMod->infraction_student($row['student_id']);
            $col = 4;
            foreach ($infractions as $inf):
                $fecha = date("d/m/Y", strtotime($inf['date']));
                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $conter, $inf['materia'] . " - " . $inf['criteria'] . " - " . $fecha);
                $col++;
            endforeach;
            $obj_PHPExcel->getActiveSheet()->SetCellValue('C' . $conter, $col - 4);
            $conter++;
        endforeach;
        //Section
        $data = ["section_id" => $section_id];
        $SectionMod = new SectionModel();
        $section = $SectionMod->get_section($data);
        $fileName = $section[0]['completo'] . '.xlsx';


        $fileName = 'Indisciplinas_' . $fileName . '.xlsx';
        $obj_PHPExcel->getActiveSheet()->SetCellValue('B4', $section[0]['completo']);
        //$obj_PHPExcel->getActiveSheet()->SetCellValue('A5', strtoupper($section[0]['completo']));
        //$obj_PHPExcel->getActiveSheet()->SetCellValue('A5', count($alumnos));
        //$fecha_actual=date("d/m/Y");
        //$obj_PHPExcel->getActiveSheet()->SetCellValue('F42', 'Generado el : '.$fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);

    }

    function ranking_class2($class_id = '')
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        if ($session->get('adviser'))
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        //Estudiantes del curso
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_class($class_id);
        $conter = 8;
        //Instanciamos la libreria
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        //**************ABRIMOS EXCEL DE ACUERDO A EL CURSO QUE CORRESPONDE
        $obj_PHPExcel = $obj_Reader->load('templates/rnkgrade.xlsx');
        //GENERAMOS RANKING
        $notaBim = array(0, 0, 0, 0, 0);
        $alumnos = [];
        if ($class_id >= 21 And $class_id <= 22) {
            //***************1RO Y 2DO DE PRIMRIA*********************************************
            //Estudiantes del curso
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            //******************RELLENAMOS LOS NOMBRES
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'] . ' - ' . $row['nick_name'];
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($class_id >= 23 And $class_id <= 26) {
            /***********************************3ro a 6to DE SECUNDARIA *******************************/
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];

                //******************RELLENAMOS NOTAS*************************
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $cnat = 0;
                    $ing = 0;
                    $lening = 0;
                    $prom = 0;
                    //solo para educacion fisica
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        $prom += round($ef['total_average']);
                    }
                    //para las otras materias
                    foreach ($notas as $nota) {
                        if ($nota['obtained_mark'] !== null) {
                            // Realizar operaciones con $nota['obtained_mark']
                            switch ($nota['name']) {
                                case 'LENGUAJE':
                                    $lening += $nota['obtained_mark'];
                                    break;
                                case 'READING':
                                    $ing += $nota['obtained_mark'];
                                    break;
                                case 'GRAMMAR':
                                    $ing += $nota['obtained_mark'];
                                    break;
                                case 'SOCIALES':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'MÚSICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'ARTE':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'MATEMÁTICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'COMPUTACIÓN':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'SCIENCE':
                                    $cnat += $nota['obtained_mark'];
                                    break;
                                case 'C. NATURALES':
                                    $cnat += $nota['obtained_mark'];
                                    break;
                                case 'F. HUMANA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                            }
                        }
                    }
                    if ($ing != 0) {
                        $lening += round($ing / 2);
                    }
                    $prom += round($lening / 2) + round($cnat / 2);
                    if ($prom != 0) {
                        $notaBim[$b] = round($prom / 9, 5);
                    }
                }
                $final = ($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id;
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($class_id >= 27 And $class_id <= 28) {
            /************************************1ro y 2DO DE SECUNDARIA ************************/
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'] . ' - ' . $row['nick_name'];
                //******************RELLENAMOS NOTAS*************************
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $lenque = 0;
                    $ing = 0;
                    $nat = 0;
                    $prom = 0;
                    //solo para educacion fisica
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        if ($ef['total_average'] !== null) {
                            $prom += round($ef['total_average']);
                        }
                    }
                    //para las otras materias
                    foreach ($notas as $nota) {
                        if ($nota['obtained_mark'] !== null) {
                            switch ($nota['name']) {
                                case 'LITERATURA':
                                    $lenque += $nota['obtained_mark'];
                                    break;
                                case 'LENGUAJE':
                                    $lenque += $nota['obtained_mark'];
                                    break;
                                case 'QUECHUA':
                                    $lenque += $nota['obtained_mark'];
                                    break;
                                case 'LITERATURE':
                                    $ing += $nota['obtained_mark'];
                                    break;
                                case 'GRAMMAR':
                                    $ing += $nota['obtained_mark'];
                                    break;
                                case 'SOCIALES':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'MÚSICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'ART. PLAST.':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'MATEMÁTICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'TEC. TECNOLÓGICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'BIOLOGÍA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'FÍSICA':
                                    //$prom+=round($nota['obtained_mark']);
                                    $nat += $nota['obtained_mark'];
                                    break;
                                case 'QUÍMICA':
                                    //$prom+=round($nota['obtained_mark']);
                                    $nat += $nota['obtained_mark'];
                                    break;
                                case 'PSICOLOGÍA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'FILOSOFÍA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'VAL_ESP_REL':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                            }
                        }
                    }
                    $prom += round($lenque / 2) + round($ing / 2) + round($nat / 2);
                    if ($prom != 0) {
                        $notaBim[$b] = round($prom / 11, 2);
                    }
                }
                $final = ($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id;
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($class_id >= 31 And $class_id <= 32) {
            /***********************************3ro y 4to de SECUNDARIA *************************/
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'] . ' - ' . $row['nick_name'];
                //******************RELLENAMOS NOTAS*************************
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $lenque = 0;
                    $ing = 0;
                    $prom = 0;
                    //solo para educacion fisica
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        if ($ef['total_average'] !== null) {
                            $prom += round($ef['total_average']);
                        }
                    }
                    foreach ($notas as $nota) {
                        if ($nota['obtained_mark'] !== null) {
                            switch ($nota['name']) {
                                case 'LITERATURA':
                                    $lenque += $nota['obtained_mark'];
                                    break;
                                case 'LENGUAJE':
                                    $lenque += $nota['obtained_mark'];
                                    break;
                                case 'QUECHUA':
                                    $lenque += $nota['obtained_mark'];
                                    break;
                                case 'LITERATURE':
                                    $ing += $nota['obtained_mark'];
                                    break;
                                case 'GRAMMAR':
                                    $ing += $nota['obtained_mark'];
                                    break;
                                case 'SOCIALES':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'MÚSICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'ART. PLAST.':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'MATEMÁTICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'TEC. TECNOLÓGICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'BIOLOGÍA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'FÍSICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'QUÍMICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'PSICOLOGÍA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'FILOSOFÍA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'VAL_ESP_REL':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                            }
                        }

                    }
                    $prom += round($lenque / 2) + round($ing / 2);
                    if ($prom != 0) {
                        $notaBim[$b] = round($prom / 13, 2);
                    }
                }
                $final = ($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id;
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        } elseif ($class_id >= 33 And $class_id <= 34) {
            //*************** 5tO Y 6tO DE sECUNDARIA **************************
            $StudentMod = new StudentModel();
            $students = $StudentMod->student_class($class_id);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row) {
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'] . ' - ' . $row['nick_name'];
                //******************RELLENAMOS NOTAS*************************
                for ($i = 0; $i <= $phase_id; $i++) {
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $lenque = 0;
                    $ing = 0;
                    $prom = 0;
                    //solo para educacion fisica
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        if ($ef['total_average'] !== null) {
                            $prom += round($ef['total_average']);
                        }
                    }
                    //para las otras materias
                    foreach ($notas as $nota) {
                        if ($nota['obtained_mark'] !== null) {
                            switch ($nota['name']) {
                                case 'LITERATURA':
                                    $lenque += $nota['obtained_mark'];
                                    break;
                                case 'QUECHUA':
                                    $lenque += $nota['obtained_mark'];
                                    break;
                                case 'LITERATURE':
                                    $ing += $nota['obtained_mark'];
                                    break;
                                case 'GRAMMAR':
                                    $ing += $nota['obtained_mark'];
                                    break;
                                case 'SOCIALES':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'MÚSICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'ART. PLAST.':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'MATEMÁTICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'TEC. TECNOLÓGICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'BIOLOGÍA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'FÍSICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'QUÍMICA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'FILOSOFÍA':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                                case 'VAL_ESP_REL':
                                    $prom += round($nota['obtained_mark']);
                                    break;
                            }
                        }
                    }
                    $prom += round($lenque / 2) + round($ing / 2);
                    if ($prom != 0) {
                        $notaBim[$b] = round($prom / 13, 2);
                    }
                }
                $final = ($notaBim[1] + $notaBim[2] + $notaBim[3] + $notaBim[4]) / $phase_id;
                $alumnos[] = array('nombre' => $est, 'prom1' => $notaBim[1], 'prom2' => $notaBim[2], 'prom3' => $notaBim[3], 'prom4' => $notaBim[4], 'final' => $final);
            }
        }
        //ORDENAMOS de MAYOR a menor
        $fila = 8;
        foreach ($alumnos as $key => $row) {
            $aux[$key] = $row['final'];
        }
        array_multisort($aux, SORT_DESC, $alumnos);

        foreach ($alumnos as $key => $row) {
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $fila, $row['nombre']);
            if ($row['prom1'] != 0) {
                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $fila, $row['prom1']);
            }
            if ($row['prom2'] != 0) {
                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $fila, $row['prom2']);
            }
            if ($row['prom3'] != 0) {
                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $fila, $row['prom3']);
            }
            //if($row['prom4']!=0){$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $fila, $row['prom4']);}
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $fila, $row['final']);
            $fila++;
        }
        //Section
        $data = ["class_id" => $class_id];
        $SectionMod = new SectionModel();
        $section = $SectionMod->get_section($data);
        $fileName = 'RNK_' . $section[0]['grade'] . '.xlsx';
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A5', strtoupper($section[0]['grade']));
        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('F102', 'Generado el : ' . $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);
    }



    // Old interviews method removed

    /*********************************ENTREVISTAS  *************************** */


    public function update_behavior_observation_ajax()
    {
        $request = \Config\Services::request();
        $logId = $request->getPost('log_id');
        $observation = $request->getPost('observation');

        $IncidenciaMod = new IncidenciaModel();
        $IncidenciaMod->updateObservacion($logId, $observation);

        return $this->response->setJSON(['status' => 'success']);
    }

    function evaluation_planner()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $SubjectModel = new SubjectModel();
        // Fetch all subjects for the teacher to populate dropdowns
        $subjects = $SubjectModel->subjects_teacher($session->get('teacher_id'));

        // Extract unique sections
        $sections = [];
        foreach ($subjects as $sub) {
            $sections[$sub['section_id']] = $sub['completo']; // 'completo' seems to be the section name
        }
        // Remove duplicates/re-index if needed (array keys handle uniqueness here)
        $page_data['subjects'] = $subjects;
        $page_data['sections'] = $sections;

        $Setting = new SettingModel();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'evaluation_planner';
        $page_data['page_title'] = 'Planificador de Evaluaciones';
        return view('backend/index', $page_data);
    }

    function get_calendar_events()
    {
        $section_id = $this->request->getPost('section_id');
        $session = session();
        $current_teacher_id = $session->get('teacher_id');

        if (!$section_id) {
            return $this->response->setJSON([]);
        }

        $EvaluationModel = new EvaluationModel();
        $events = $EvaluationModel->getEvaluationsBySection($section_id);

        $calendarEvents = [];
        foreach ($events as $event) {
            // Determine color
            // If it's my exam: Green (#1BC5BD)
            // If it's others' exam: Blue (#3699FF)
            $color = ($event['teacher_id'] == $current_teacher_id) ? '#1BC5BD' : '#3699FF';
            // Alert color for saturated days could be handled here or in frontend, 
            // but let's keep it simple for now.

            $calendarEvents[] = [
                'id' => $event['id'], // Important for actions
                'title' => $event['subject_name'] . ': ' . $event['title'], // Full title for calendar
                'raw_title' => $event['title'], // Raw title for edit form
                'subject_name' => $event['subject_name'], // For table
                'subject_id' => $event['subject_id'], // For edit form
                'section_id' => $event['section_id'], // For logic
                'teacher_id' => $event['teacher_id'], // For permission check
                'start' => $event['date'],
                'allDay' => true,
                'color' => $color,
                'description' => $event['description']
            ];
        }
        return $this->response->setJSON($calendarEvents);
    }

    function evaluation_check_date()
    {
        $section_id = $this->request->getPost('section_id');
        $date = $this->request->getPost('date');

        $EvaluationModel = new EvaluationModel();
        $count = $EvaluationModel->getDailyCount($section_id, $date);

        echo $count;
    }

    function evaluation_save()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return redirect()->to(base_url());

        $data['section_id'] = $this->request->getPost('section_id');
        $data['subject_id'] = $this->request->getPost('subject_id');
        $data['title'] = $this->request->getPost('title');
        $data['description'] = $this->request->getPost('description');
        $data['date'] = $this->request->getPost('date');
        $data['type'] = 'exam';
        $data['teacher_id'] = $session->get('teacher_id');

        $EvaluationModel = new EvaluationModel();
        if (!empty($data['id'])) {
            $EvaluationModel->updateEvaluation($data['id'], $data);
            $session->setFlashdata('flash_message', 'Evaluación actualizada correctamente');
        } else {
            $count = $EvaluationModel->getDailyCount($data['section_id'], $data['date']);
            $EvaluationModel->addEvaluation($data);

            if ($count >= 2) {
                $session->setFlashdata('flash_message', 'Evaluación guardada. NOTA: Día saturado (3 o más exámenes).');
            } else {
                $session->setFlashdata('flash_message', 'Evaluación guardada correctamente');
            }
        }

        return redirect()->to(base_url('teacher/evaluation_planner'));
    }

    function get_my_evaluations()
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');

        $EvaluationModel = new EvaluationModel();
        $events = $EvaluationModel->getEvaluationsByTeacher($teacher_id);

        return $this->response->setJSON($events);
    }

    function evaluation_delete($id)
    {
        $session = session();
        $EvaluationModel = new EvaluationModel();

        // Optional: Check permissions (if teacher owns this evaluation)
        // $eval = $EvaluationModel->find($id);
        // if($eval['teacher_id'] != $session->get('teacher_id')) ...

        $EvaluationModel->deleteEvaluation($id);
        $session->setFlashdata('flash_message', 'Evaluación eliminada correctamente');

        return redirect()->to(base_url('teacher/evaluation_planner'));
    }

    public function session_keep_alive()
    {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Session kept alive']);
    }

    public function attendance_auto_save_ajax()
    {
        $session = session();
        if ($session->get('login_type') != 'teacher') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $teacher_id = $session->get('teacher_id');
        $subject_id = $this->request->getPost('subject_id');
        $section_id = $this->request->getPost('section_id');
        $date_id = $this->request->getPost('date_id');
        $periodo = $this->request->getPost('periodos');

        if (!$subject_id || !$section_id || !$date_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing parameters']);
        }

        // Student Data
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($section_id, $teacher_id);

        $AssistanceMod = new AssistancesubjectModel();
        $AbsenceMod = new AbsenceModel();
        $DatesMod = new DatesModel();

        // Safe date retrieval
        $dateData = ["date_id" => $date_id];
        $dateResp = $DatesMod->get_attendance_dates($dateData);
        $dateClass = isset($dateResp[0]['date_class']) ? $dateResp[0]['date_class'] : date('Y-m-d');

        foreach ($students as $row) {
            $checkKey = 'check_' . $row['student_id'];
            $textKey = 'text_' . $row['student_id'];

            $statusVal = $this->request->getPost($checkKey) ?? 1;
            $textVal = $this->request->getPost($textKey) ?? '';

            // Check if record exists
            $existing = $AssistanceMod->get_assistance_subject([
                "date_id" => $date_id,
                "subject_id" => $subject_id,
                "student_id" => $row['student_id'],
            ]);

            if (count($existing) > 0) {
                $assistance_subject_id = $existing[0]['assistance_subject_id'];
                $AssistanceMod->update_assistance_subject([
                    "status" => $statusVal,
                    "indiscipline" => $textVal,
                    "periodos" => $periodo,
                ], $assistance_subject_id);
            } else {
                $AssistanceMod->insert_assistance_subject([
                    "status" => $statusVal,
                    "indiscipline" => $textVal,
                    "date_id" => $date_id,
                    "subject_id" => $subject_id,
                    "student_id" => $row['student_id'],
                    "periodos" => $periodo,
                ]);
            }

            // Sync with Absences table if status is 0 (Absent)
            if ($statusVal == 0) {
                // Check if already registered in absences for this day/materia
                // (Avoiding duplication if auto-save runs multiple times)
                $db = \Config\Database::connect();
                $alreadyAbsent = $db->table('t_absences')
                    ->where('student_id', $row['student_id'])
                    ->where('subject_id', $subject_id)
                    ->where('fecha', $dateClass)
                    ->get()
                    ->getRow();

                if (!$alreadyAbsent) {
                    date_default_timezone_set('America/La_Paz');
                    $AbsenceMod->insert_absence([
                        "student_id" => $row['student_id'],
                        "subject_id" => $subject_id,
                        "fecha" => $dateClass,
                        "hora" => date("H:i:s"),
                        "obs" => $textVal . " - Guardado Automático",
                        "cantidad" => 1,
                        "enviado" => false,
                    ]);
                }
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Auto-save completed']);
    }
    public function adviser_search($sel = '')
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
            return $this->response->setJSON([]);
        if (strlen($sel) < 2)
            return $this->response->setJSON([]);
        $teacher_id = $session->get('teacher_id');
        $StudentMod = new StudentModel();
        $rows = $StudentMod->students_user('adviser', $sel, $teacher_id);
        $results = [];
        foreach ($rows as $r) {
            $results[] = [
                'student_id' => $r['student_id'],
                'nombre'     => $r['lastname'] . ' ' . $r['lastname2'] . ' ' . $r['name'],
                'completo'   => $r['nick_name'],
            ];
        }
        return $this->response->setJSON($results);
    }
    public function adviser_summary($student_id = 0)
    {
        $session = session();
        if ($session->get('login_type') != 'teacher')
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
            'info'        => $info,
            'absences'    => $absences,
            'delays'      => array_map(fn($d) => ['date_class' => $d['date_class'], 'tarde_con' => $d['tarde_con']], $delays),
            'licenses'    => array_map(fn($l) => (array)$l, (array)$licenses),
            'incidencias' => $incidencias,
            'grades'      => $grades,
            'phase_id'    => $phase_id,
        ]);
    }
}
