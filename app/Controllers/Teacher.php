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
use App\Models\ScoreModel;
use App\Models\BehaviorModel;
use App\Models\InterviewModel;
use App\Models\EvaluationModel;

class Teacher extends BaseController
{
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
        $materias = $Subject->subjects_docente($session->get('teacher_id'));

        //$page_data['teacher_id'] = $this->session->userdata('teacher_id');
        $Setting = new SettingModel();

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['materias'] = $materias;
        $page_data['page_name'] = 'content_letter';
        $page_data['page_title'] = 'Cartas de Contenidos';
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

        // Subject Data
        $SubjectMod = new SubjectModel();
        if ($subject_id > 0) {
            $subjects = $SubjectMod->subject_section($subject_id);
            $page_data['subject_name'] = $subjects[0]['name'];
            $page_data['curso'] = $subjects[0]['nick_name'] . " - " . $subjects[0]['name'];
        } else {
            $page_data['subject_name'] = 'Historial General';
            $page_data['curso'] = $student['lastname'] . ' ' . $student['name']; // Fallback label
        }

        $BehaviorMod = new BehaviorModel();
        // Filter logs by the current subject to show only this teacher's materia
        $logs = $BehaviorMod->getStudentLog($student_id, null, $subject_id > 0 ? $subject_id : null, $page_data['phase_id']);
        $page_data['logs'] = $logs;

        // Daily Logistics (Nurse/Bathroom from all subjects)
        $currentDate = $this->request->getGet('date') ?: date('Y-m-d');
        $page_data['logistics'] = $BehaviorMod->getDailyLogistics($student_id, $currentDate);

        // --- Puntos del Ser Logic ---
        $ScoreMod = new ScoreModel();
        $puntosDelSer = 10; // Default
        if ($subject_id > 0) {
            // Get CUMULATIVE score until "now" (passing no date_id? or the latest date?)
            // Actually, we want the score for the current state of the phase.
            // Let's use getDailyScore with the "current" date or the most recent record.

            // To get the absolute current score in the phase:
            $currentDateId = $this->request->getGet('date_id'); // If coming from attendance
            if (!$currentDateId) {
                // If not provided, find the most recent attendance date in this phase
                $DatesMod = new DatesModel();
                $recentDate = \Config\Database::connect('asistencia')->table('attendance_dates')
                    ->where('phase_id', $page_data['phase_id'])
                    ->orderBy('date_class', 'DESC')
                    ->limit(1)
                    ->get()
                    ->getRowArray();
                $currentDateId = $recentDate ? $recentDate['date_id'] : null;
            }

            if ($currentDateId) {
                $cumulativeScore = $ScoreMod->getDailyScore($student_id, $currentDateId, $subject_id);
                $scaled = ($cumulativeScore / 100) * 10;
                $puntosDelSer = round($scaled, 1); // Maybe keep one decimal or round? User said "1-10"
                if ($puntosDelSer > 10)
                    $puntosDelSer = 10;
            }
        }
        $page_data['puntos_del_ser'] = $puntosDelSer;

        // --- Chart Data Logic (built directly from logs) ---
        $behaviorCounts = [];
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;
        foreach ($logs as $log) {
            $bid = $log['behavior_type_id'];
            if (!isset($behaviorCounts[$bid])) {
                $behaviorCounts[$bid] = [
                    'name'   => $log['name'],
                    'icon'   => $log['icon'],
                    'count'  => 0,
                    'points' => $log['points'],
                    'type'   => $log['type']
                ];
            }
            $behaviorCounts[$bid]['count']++;
            if ($log['type'] == 'positive') {
                $positiveCount++;
            } elseif ($log['type'] == 'negative') {
                $negativeCount++;
            } else {
                $neutralCount++;
            }
        }

        $page_data['positive_incidents'] = $positiveCount;
        $page_data['negative_incidents'] = $negativeCount;
        $page_data['neutral_incidents'] = $neutralCount;
        $page_data['behavior_counts'] = $behaviorCounts;

        $page_data['student'] = $student;
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

        $BehaviorMod = new BehaviorModel();
        $behaviors   = $BehaviorMod->getBehaviors();

        $Setting = new SettingModel();
        $page_data['phase_id']    = $Setting->get_phase_id();
        $page_data['phase_name']  = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['subjects']    = $subjects;
        $page_data['behaviors']   = $behaviors;
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

        if (!$subject_id || !$date)
            return $this->response->setJSON(['status' => 'error', 'message' => 'Faltan datos']);

        try {
            $Setting  = new SettingModel();
            $phase_id = $Setting->get_phase_id();

            $asistDb  = \Config\Database::connect('asistencia');

            // Solo busca — nunca crea
            $existing = $asistDb->table('attendance_dates')
                ->where('date_class', $date)
                ->where('phase_id', $phase_id)
                ->get()->getRowArray();

            return $this->response->setJSON([
                'status'  => 'success',
                'date_id' => $existing ? $existing['date_id'] : 0,
                'found'   => (bool) $existing,
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
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

        $BehaviorMod = new BehaviorModel();
        $logs = $BehaviorMod->getStudentLog($student_id, null, $subject_id > 0 ? $subject_id : null);

        // Logística del día
        $page_data['logistics'] = $BehaviorMod->getDailyLogistics($student_id, date('Y-m-d'));

        // Puntos del Ser
        $page_data['puntos_del_ser'] = 10;

        // --- Chart Data Logic (built directly from logs, no getBehaviors() needed) ---
        $behaviorCounts = [];
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;
        foreach ($logs as $log) {
            $bid = $log['behavior_type_id'];
            if (!isset($behaviorCounts[$bid])) {
                $behaviorCounts[$bid] = [
                    'name'   => $log['name'],
                    'icon'   => $log['icon'],
                    'count'  => 0,
                    'points' => $log['points'],
                    'type'   => $log['type']
                ];
            }
            $behaviorCounts[$bid]['count']++;
            if ($log['type'] == 'positive') {
                $positiveCount++;
            } elseif ($log['type'] == 'negative') {
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

        $page_data['student'] = $student;
        $page_data['student_id'] = $student_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'student_profile';
        $page_data['page_title'] = 'Análisis de Comportamiento';

        return view('backend/index', $page_data);
    }

    function register_behavior()
    {
        /*try {*/
            $studentId = $this->request->getPost('student_id');
            $behaviorId = $this->request->getPost('behavior_id');
            $points = $this->request->getPost('points');
            $subjectId = $this->request->getPost('subject_id');
            $dateId = $this->request->getPost('date_id');
            $period = $this->request->getPost('period') ?: 1;
            $observation = $this->request->getPost('observation');

            if (!$studentId || !$behaviorId || !$dateId || !$subjectId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Faltan parámetros requeridos (Estudiante, Comportamiento, Fecha o Materia)'
                ]);
            }

            // 1. Log the behavior first
            $BehaviorMod = new BehaviorModel();
            $logResult = $BehaviorMod->logBehavior($studentId, $behaviorId, $subjectId, $dateId, $observation, $period);

            /*if ($logResult) {*/
                // 2. Now calculate the new score
                $ScoreMod = new ScoreModel();
                $newScore = $ScoreMod->getDailyScore($studentId, $dateId, $subjectId, $period);

                $logs = $BehaviorMod->getStudentLog($studentId, null, $subjectId);
                $newNegativeCount = count(array_filter($logs, function ($log) {
                    return $log['type'] == 'negative';
                }));
                $newPositiveCount = count(array_filter($logs, function ($log) {
                    return $log['type'] == 'positive';
                }));

                return $this->response->setJSON([
                    'status' => 'success',
                    'new_score' => $newScore,
                    'new_negative_count' => $newNegativeCount,
                    'new_positive_count' => $newPositiveCount
                ]);
                /*
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'No se pudo guardar el registro en la base de datos.'
                ]);
            }
            
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al procesar el registro.'
            ]);
        }*/
    }

    function delete_behavior_ajax()
    {
        $logId = $this->request->getPost('log_id');
        $db = \Config\Database::connect();

        // Fetch log with behavior details
        $builder = $db->table('behavior_log');
        $builder->select('behavior_log.*, behavior_types.points');
        $builder->join('behavior_types', 'behavior_types.id = behavior_log.behavior_type_id');
        $builder->where('behavior_log.id', $logId);
        $log = $builder->get()->getRowArray();

        if ($log) {
            $points = (int) $log['points'];
            $studentId = $log['student_id'];
            $dateId = $log['date_id'];
            $subjectId = $log['subject_id'];
            $period = isset($log['period']) ? $log['period'] : 1;

            // 1. Delete the log first
            $BehaviorMod = new BehaviorModel();
            $BehaviorMod->deleteLog($logId);

            // 2. Now calculate the new score (cumulative sum from ScoreModel)
            $ScoreMod = new ScoreModel();
            $newScore = $ScoreMod->getDailyScore($studentId, $dateId, $subjectId, $period);

            $logs = $BehaviorMod->getStudentLog($studentId, null, $subjectId);
            $newNegativeCount = count(array_filter($logs, function ($log) {
                return $log['type'] == 'negative';
            }));
            $newPositiveCount = count(array_filter($logs, function ($log) {
                return $log['type'] == 'positive';
            }));

            return $this->response->setJSON([
                'status' => 'success',
                'new_score' => $newScore,
                'student_id' => $studentId,
                'new_negative_count' => $newNegativeCount,
                'new_positive_count' => $newPositiveCount
            ]);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Log not found']);
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
        $date_id = (int) $this->request->getPost('date_id');
        $subject_id = (int) $this->request->getPost('subject_id');

        $BehaviorMod = new BehaviorModel();
        $logs = $BehaviorMod->getStudentLog($student_id, $date_id, $subject_id);

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

        $page_data['curso'] = $subjects[0]['nick_name'] . " - " . $subjects[0]['name'];
        $page_data['section_id'] = $subjects[0]['section_id'];
        $page_data['subject_id'] = $subject_id;
        $page_data['date_display'] = $page_data['date'];
        $page_data['periodo'] = $periodo;

        //Assistance Date Logic
        $DatesMod = new DatesModel();
        $respuesta = $DatesMod->get_attendance_dates(["date_class" => $page_data['date']]);

        if (count($respuesta) >= 1) {
            $page_data['date_id'] = $respuesta[0]['date_id'];
        } else {
            $datos = [
                "date_class" => $page_data['date'],
                "phase_id" => $page_data['phase_id'],
            ];
            $respuesta = $DatesMod->insert_attendance_dates($datos);
            $page_data['date_id'] = $respuesta;
        }

        // Students Logic (Using standard list for gamification)
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($page_data['section_id'], $teacher_id);

        // Gamification Logic
        $BehaviorMod = new BehaviorModel();
        $page_data['behaviors'] = $BehaviorMod->getBehaviors();

        $ScoreMod = new ScoreModel();
        $AssistanceMod = new AssistancesubjectModel();

        foreach ($students as &$student) {
            // Get Daily Score
            $student['daily_score'] = $ScoreMod->getDailyScore($student['student_id'], $page_data['date_id'], $subject_id, $page_data['periodo']);

            // Get Attendance Status
            $statusData = $AssistanceMod->get_assistance_subject([
                "date_id" => $page_data['date_id'],
                "subject_id" => $subject_id,
                "student_id" => $student['student_id']
            ]);

            // Status codes: 0=Ausente, 1=Presente, 2=Licencia, 3=Retraso, 4=M.Virtual
            $student['attendance_status'] = !empty($statusData) ? $statusData[0]['status'] : 1;

            // Incident count filtered by subject
            $logs = $BehaviorMod->getStudentLog($student['student_id'], null, $subject_id);
            $student['negative_count'] = count(array_filter($logs, function ($log) {
                return $log['type'] == 'negative';
            }));
            $student['positive_count'] = count(array_filter($logs, function ($log) {
                return $log['type'] == 'positive';
            }));
        }
        $page_data['students'] = $students;

        // Licencias Fechas
        $LicenciaMod = new LicenciaModel();
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

        // View
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

        //Assistance
        $AssisMod = new AssistanceModel();
        $data = ["date_class" => $page_data['date']];
        $DatesMod = new DatesModel();
        $respuesta = $DatesMod->get_attendance_dates($data);
        if (count($respuesta) >= 1) {
            $page_data['date_id'] = $respuesta[0]['date_id'];
        } else {
            $datos = [
                "date_class" => $page_data['date'],
                "phase_id" => $page_data['phase_id'],
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
        $page_data['section_id'] = $_POST['section_id'];
        $page_data['date_id'] = $_POST['date_id'];
        $periodo = $_POST['periodos'];
        //Subject
        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subject_section($page_data['subject_id']);
        $page_data['curso'] = $subjects[0]['nick_name'] . " - " . $subjects[0]['name'];

        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($page_data['section_id'], $teacher_id);
        $page_data['students'] = $students;
        foreach ($students as $row):
            // Safe POST retrieval
            $checkKey = 'check_' . $row['student_id'];
            $textKey = 'text_' . $row['student_id'];

            $statusVal = isset($_POST[$checkKey]) ? $_POST[$checkKey] : 1;
            $textVal = isset($_POST[$textKey]) ? $_POST[$textKey] : '';

            //Consultamos si existe Asistencia
            $assistance_subject_id = 0;
            $data = [
                "date_id" => $page_data['date_id'],
                "subject_id" => $page_data['subject_id'],
                "student_id" => $row['student_id'],
            ];
            $AssistanceMod = new AssistancesubjectModel();
            $dates = $AssistanceMod->get_assistance_subject($data);
            if (count($dates) == 1) {
                foreach ($dates as $fila) {
                    $assistance_subject_id = $fila['assistance_subject_id'];
                }
                $datos = [
                    "status" => $statusVal,
                    "indiscipline" => $textVal,
                    "periodos" => $periodo,
                ];
                $respuesta = $AssistanceMod->update_assistance_subject($datos, $assistance_subject_id);
            } else {
                $data_attendance_subject = [
                    "status" => $statusVal,
                    "indiscipline" => $textVal,
                    "date_id" => $page_data['date_id'],
                    "subject_id" => $page_data['subject_id'],
                    "student_id" => $row['student_id'],
                    "periodos" => $periodo,
                ];
                $respuesta = $AssistanceMod->insert_assistance_subject($data_attendance_subject);
            }
            //Si es ausencia Registramos
            if ($statusVal == 0) {
                date_default_timezone_set('America/La_Paz');
                $hora_ausencia = date("H:i:s");
                //Date
                $data = ["date_id" => $page_data['date_id']];
                $DatesMod = new DatesModel();
                $respuesta = $DatesMod->get_attendance_dates($data);

                $dateClass = isset($respuesta[0]['date_class']) ? $respuesta[0]['date_class'] : date('Y-m-d'); // Safe fallback

                $datos = [
                    "student_id" => $row['student_id'],
                    "subject_id" => $page_data['subject_id'],
                    "fecha" => $dateClass,
                    "hora" => $hora_ausencia,
                    "obs" => $textVal . "- Registrado por el Docente",
                    "cantidad" => 1,
                    "enviado" => False,
                ];
                $AbsenceMod = new AbsenceModel();
                $respuesta = $AbsenceMod->insert_absence($datos);
            }
        endforeach;
        //DIAS
        $DatesMod = new DatesModel();
        $dias = $DatesMod->dias_subject($page_data['subject_id'], $page_data['phase_id']);
        $page_data['dias'] = $dias;
        //Asistencias
        $AssistanceMod = new AssistancesubjectModel();
        $asis = $AssistanceMod->assis_subject($page_data['subject_id'], $page_data['phase_id']);
        $page_data['asis'] = $asis;
        //Vista
        $page_data['page_name'] = 'attendance_report';
        $page_data['page_title'] = 'Asistencia';
        //$session->set('flash_message', var_dump($periods));
        return view('backend/index', $page_data);

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
        $page_data['section_id'] = $curso[0]['section_id'];
        $page_data['curso'] = $curso[0]['completo'];
        $page_data['subject_id'] = $subject_id;
        //DIAS de materia
        $DatesMod = new DatesModel();
        $dias = $DatesMod->dias_subject($subject_id, $Setting->get_phase_id());
        $page_data['dias'] = $dias;
        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->studentsSection($page_data['section_id'], $teacher_id);
        $page_data['students'] = $students;

        //Asistencias
        $AssistanceMod = new AssistancesubjectModel();
        $asis = $AssistanceMod->assis_subject($subject_id, $page_data['phase_id']);
        $page_data['asis'] = $asis;

        $page_data['page_name'] = 'attendance_report';
        $page_data['page_title'] = 'Reporte de Asistencias';
        return view('backend/index', $page_data);
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
        $conter = 8;
        foreach ($students as $row):
            //Rellenamos estudiantes
            //$est=$row['student'].' '.$row['lastname2'].' '.$row['name'];
            $activeWorksheet->SetCellValue('B' . $conter, $row['student']);
            //$activeWorksheet->getColumnDimension('A')->setAutoSize(true);
            //Rellenamos FECHAS
            $AssistanceMod = new AssistancesubjectModel();
            $dias = $AssistanceMod->assis_dates($subject_id, $phase_id);
            $i = 0;
            foreach ($dias as $dia):
                if ($conter == 8) {
                    $newDate = date("d/m/Y", strtotime($dia['date_class']));
                    $activeWorksheet->setCellValueByColumnAndRow(3 + $i, 7, $newDate);
                }
                $data = [
                    "date_id" => $dia['date_id'],
                    "subject_id" => $subject_id,
                    "student_id" => $row['student_id']
                ];
                $asis = new AssistancesubjectModel();
                $asistencias = $asis->get_assistance_subject($data);
                foreach ($asistencias as $asi):

                    $valor = "";
                    switch ($asi['status']) {
                        case 0:
                            $valor = "A";
                            break;
                        case 1:
                            $valor = "P";
                            break;
                        case 2:
                            $valor = "L";
                            break;
                        case 3:
                            $valor = "R";
                            break;
                    }

                    $activeWorksheet->setCellValueByColumnAndRow(3 + $i, $conter, $valor);
                endforeach;
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
        $page_data['rev'] = $rev;
        //Notas
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
        $page_data['csamarks'] = $csamarks;

        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'review_notes';
        $page_data['page_title'] = 'Revisión de Notas';
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
        //Actualizamos Subjects
        $data = ["locked" => 1];
        $respuesta = $SubjectMod->update_subject($data, $subject_id);

        if ($respuesta > 0) {
            //Quitamos permisos DEL LA hOJA 3ER tRIMESTRE EN GOOGLER SHEET
            $ApigoogleMod = new ApigoogleModel();
            $apigoogle = $ApigoogleMod->lockedSheet($subject[0]['sheet_id']);
            $session->set('flash_message', 'Notas consolidadas Correctamente: ' . $subject[0]['sheet_id']);
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

        // Behavior Logs for the whole section
        $BehaviorMod = new BehaviorModel();
        $page_data['logs'] = $BehaviorMod->getSectionBehaviorLog($section_id, $page_data['phase_id']);

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
        $auto_decidir = $_POST['auto_decidir2'];

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
            $data['ser'] = $auto_ser;
            $data['ser100'] = round(($auto_ser + $auto_decidir) / 2);
            $data['ser5'] = $auto_ser;
            $data['dec'] = $auto_decidir;
            $data['dec100'] = round(($auto_ser + $auto_decidir) / 2);
            $data['dec5'] = $auto_decidir;
            $data['student_id'] = $student_id;
            $data['phase_id'] = $page_data['phase_id'];
            $respuesta = $self->insert_self_appraisal($data);
            $session->set('flash_message', 'Autoevaluación Guardada Correctamente');
            return redirect()->to(base_url() . 'teacher/self_inicial/' . $section_id);
        } else {
            $self_appraisal_id = $existe[0]['self_id'];
            $data['ser'] = $auto_ser;
            $data['ser100'] = round(($auto_ser + $auto_decidir) / 2);
            $data['ser5'] = $auto_ser;
            $data['dec'] = $auto_decidir;
            $data['dec100'] = round(($auto_ser + $auto_decidir) / 2);
            $data['dec5'] = $auto_decidir;
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

        $BehaviorMod = new BehaviorModel();
        $BehaviorMod->updateObservation($logId, $observation);

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
}
