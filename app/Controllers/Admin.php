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
use App\Models\ContinuityModel;
use App\Models\FeedbackModel;
use App\Models\NivelModel;
use App\Models\DirectorModel;
use App\Models\PeriodoModel;
use App\Models\PhaseModel;
use App\Models\PlaceModel;

class Admin extends BaseController
{
    public function dashboard()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        //$mensaje = session('mensaje');
        //$page_data['mensaje'] = $mensaje;

        // We retrieve the Session class
        //$this->session = Services::session();
        // We set some data
        //$this->session->item = 'Pouet';

        // We pass (only) session data to the View
        //echo view('folder/template', $this->session->get());
        //$session = \Config\Services::session($config);
        //$session->start();

        //$page_data['tipo']  = $session->get('login_type');
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Dashboard";
        $page_data['page_name'] = "dashboard";
        return view('backend/index', $page_data);
    }
    function section_bth()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Cursos BTH
        $Section = new SectionModel();
        $page_data['sections'] = $Section->section_bth($page_data['phase_id']);
        //Especialidad BTH
        $Subject = new SubjectModel();
        $page_data['subjects'] = $Subject->subjects_especialidad();
        //Vista
        $page_data['page_title'] = "Cursos BTH";
        $page_data['page_name'] = "section_bth";
        return view('backend/index', $page_data);
    }

    function create_notes_bth($section_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        //Parametros
        $n = 0;
        $phase_id = $Setting->get_phase_id();
        //Recorremos las Materias
        $data = [
            "teacher_id" => 61,
            "name" => "TEC. TECNOLÓGICA",
            "section_id" => $section_id,
        ];
        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->get_subject($data);
        foreach ($subjects as $sub):
            $subject_id = $sub['subject_id'];
            //Recorremos Estudiantes
            $StudentMod = new StudentModel();
            $students = $StudentMod->studentsSection($section_id, 61);
            foreach ($students as $stu):
                //Verificamos que el estudiante no tenga notas
                $CsamarksMod = new CsamarksModel();
                $data = [
                    "phase_id" => $phase_id,
                    "subject_id" => $subject_id,
                    "student_id" => $stu['student_id'],
                ];
                $csamarks = $CsamarksMod->get_csamarks($data);
                if (count($csamarks) == 0) {
                    $data_csamarks['student_id'] = $stu['student_id'];
                    $data_csamarks['locked'] = 0;
                    $data_csamarks['phase_id'] = $phase_id;
                    $data_csamarks['subject_id'] = $subject_id;
                    $CsamarksMod = new CsamarksModel();
                    $respuesta = $CsamarksMod->insert_csamarks($data_csamarks);
                    $n += 1;
                }
            endforeach;
        endforeach;
        $session->set('flash_message', 'Total Notas Creadas Correctamente : ' . $n);
        return redirect()->to(base_url() . 'admin/section_bth');
    }
    function centralize_notes_bth($section_id = '', $subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $n = 0;
        $phase_id = $Setting->get_phase_id();
        if ($section_id > 0) {
            //Recorremos Estudiantes
            $StudentMod = new StudentModel();
            $students = $StudentMod->studentsSection($section_id, 61);
            foreach ($students as $stu):
                //Verificamos que el estudiante no tenga notas
                $CsamarksMod = new CsamarksModel();
                $data = [
                    "phase_id" => $phase_id,
                    "subject_id" => $subject_id,
                    "student_id" => $stu['student_id'],
                ];
                $csamarks = $CsamarksMod->get_csamarks($data);
                if (count($csamarks) == 1) {
                    $CsamarksMod = new CsamarksModel();
                    $notas_bth = $CsamarksMod->csamarks_centralize_bth($stu['student_id'], $phase_id);
                    foreach ($notas_bth as $bth):
                        $update_csamarks['total_average'] = $bth['nota_bth'];
                        $update_csamarks['saved_on'] = date("Y-m-d");
                        $CsamarksMod = new CsamarksModel();
                        $respuesta = $CsamarksMod->update_csamarks($update_csamarks, $csamarks[0]['csamarks_id']);
                        if ($respuesta >= 1) {
                            $n += 1;
                        }
                    endforeach;
                }
            endforeach;
            //Actualizamos Subjects
            $SubjectMod = new SubjectModel();
            $data = ["locked" => 1, "official_id" => 1];
            $respuesta = $SubjectMod->update_subject($data, $subject_id);
        }
        $session->set('flash_message', 'Total Notas Centralizadas Correctamente : ' . $n);
        return redirect()->to(base_url() . '/admin/section_bth');
    }
    function centralizador_bth($section_id = '', $subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();
        $phase = $Setting->get_phase();
        //Materias
        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subjects_section_bth($section_id);
        //Nro de materias
        $plantilla = strval(count($subjects));
        if ($section_id > 340) {
            $plantilla = '6to';
        }
        if ($section_id >= 321 && $section_id <= 323) {
            $plantilla = '4';
        }
        if ($section_id >= 331 && $section_id <= 333) {
            $plantilla = '5to';
        }
        //Instanciamos la libreria EXCEL
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");

        $obj_PHPExcel = $obj_Reader->load("templates/CenBth" . $plantilla . ".xlsx");
        $obj_PHPExcel->setActiveSheetIndex(0);

        //Estudiantes del curso
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_active($section_id);
        $conter = 8;
        $col = array("D", "F", "H", "J", "L");
        $col2 = array("E", "G", "I", "K", "M");
        //******************RELLENAMOS LOS NOMBREs
        foreach ($students as $row):
            //Lista de Estudiantes
            $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
            $obj_PHPExcel->getActiveSheet()->SetCellValue('C' . $conter, $est);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('A' . $conter, $row['student_id']);
            //Notas del Estudiante
            $CsamarksMod = new CsamarksModel();
            $csamarks_bth = $CsamarksMod->csamarks_bth_especialidad($row['student_id'], $phase_id);
            $j = 0;
            foreach ($csamarks_bth as $not):
                $ponderado = 0;
                if ($not['hours'] == 1) {
                    $ponderado = $not['total_average'];
                } else {
                    $ponderado = round($not['total_average'] * ($not['hours'] / 100), 0);
                }

                $obj_PHPExcel->getActiveSheet()->SetCellValue($col[$j] . $conter, $not['total_average']);
                $obj_PHPExcel->getActiveSheet()->SetCellValue($col2[$j] . $conter, $ponderado);
                // Especialidad
                if (strpos($not['name'], '_') !== false) {
                    $obj_PHPExcel->getActiveSheet()->SetCellValue("O" . $conter, $not['name']);
                }
                $j++;
            endforeach;

            $conter++;
        endforeach;
        //*************RELLENAMOS MATERIAS */
        $i = 0;
        foreach ($subjects as $mat):
            $hours = $mat['hours'];
            $obj_PHPExcel->getActiveSheet()->SetCellValue($col[$i] . "6", $mat['materia'] . " - " . $mat['docente']);
            $i++;
        endforeach;


        //Estudiantes del curso
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_active($section_id);
        $conter = 8;

        //Section
        $data = ["section_id" => $section_id];
        $SectionMod = new SectionModel();
        $section = $SectionMod->get_section($data);
        $fileName = 'BTH_' . $section[0]['completo'] . '.xlsx';
        $obj_PHPExcel->getActiveSheet()->SetCellValue('B4', "GESTIÓN 2026 NOTAS OFICIALES " . strtoupper($phase_name));
        $obj_PHPExcel->getActiveSheet()->SetCellValue('B5', strtoupper($section[0]['completo']));
        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A43', 'Generado el : ' . $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);

    }
    function test_email()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "test_email";
        $page_data['page_name'] = "test_email";
        return view('backend/index', $page_data);
    }
    function send_mail_smtp()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());


        $asunto = $_POST['asunto'];
        $mensaje = $_POST['mensaje'];
        $correo = $_POST['correo'];

        $email = \Config\Services::email();

        $email->setFrom('saat@tiquipaya.edu.bo', 'Saat Tiquipaya');
        $email->setTo($correo);
        $email->setSubject($asunto);
        $email->setMessage($mensaje);

        if (!$email->send()) {
            $session->set('flash_message_error', 'Error al enviar');
            $page_data['errores'] = $email->printDebugger(['headers']);
        } else {
            $session->set('flash_message', 'Correo enviado correctamente');
        }

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "test_email";
        $page_data['page_name'] = "test_email";
        //$session->set('flash_message_error', 'Error al cargar');
        $session->set('flash_message', 'Correo enviado correctamente');
        return view('backend/index', $page_data);
    }
    function send_mail_php()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());




        $asunto = $_POST['asunto'];
        $mensaje = $_POST['mensaje'];
        $correo = $_POST['correo'];

        $para = $correo;
        $titulo = $asunto;
        $mensaje = 'Hola';
        $EmailMod = new EmailModel();
        $mensaje = $EmailMod->generic_message($titulo, $mensaje, 'saat@tiquipaya.edu.bo');

        $headers[] = 'From: Franz Condori <soportetecnico@tiquipaya.edu.bo>';

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        //$headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
        //$headers[] = 'From: Secretaria <'.$emailSecre.'>';
        //$headers[] = 'Cc: birthdayarchive@example.com';
        //$headers[] = 'Bcc: birthdaycheck@example.com';
        //mail($email_to, $email_sub, $email_msg, $headers);
        //mail($to, $subject, $mensaje, implode("\r\n", $headers));
        mail($para, $titulo, $mensaje, implode("\r\n", $headers));

        //$session->set('flash_message_error', 'Error al cargar');
        $session->set('flash_message', 'Correo enviado correctamente');
        //return view('backend/index', $page_data);
        return redirect()->to(base_url() . 'admin/test_email');
    }
    /*********************************************REPORTES ESTADISTICOS ****************************/
    public function dg_continuity_results()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());
        //Total students
        $StudentMod = new StudentModel();
        $page_data['students_prospective'] = $StudentMod->students_prospective();
        //Students continuity values
        $ContinuityMod = new ContinuityModel();
        $data = ["respuesta" => 'SI'];
        $page_data['students_si'] = $ContinuityMod->get_continuity($data);
        $data = ["respuesta" => 'NO'];
        $page_data['students_no'] = $ContinuityMod->get_continuity($data);
        $data = ["respuesta" => 'INDECISO'];
        $page_data['students_in'] = $ContinuityMod->get_continuity($data);
        //Students continuity
        $page_data['continuity_students_10'] = $ContinuityMod->continuity_students_10();
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'dg_continuity_results';
        $page_data['page_title'] = 'Continuidad 2024';
        return view('backend/index', $page_data);
    }

    function sections_dir()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        //Section
        $Section = new SectionModel();
        $cursos = $Section->sections_range(211, 343);
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
    function subjects_section($section_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        //Section
        $data = ["section_id" => $section_id];
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
        //$page_data['cursos']  = $cursos;

        $page_data['page_name'] = 'subjects_section';
        $page_data['page_title'] = 'Planillas Curso';
        return view('backend/index', $page_data);
    }
    public function update_tables()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'update_tables';
        $page_data['page_title'] = 'Actualizar Tablas';
        return view('backend/index', $page_data);
    }
    public function update_student()
    {
        $session = session();
        $StudentMod = new StudentModel();
        $respuesta = $StudentMod->updateTStudent();
        $session->set('flash_message', 'Tabla estudiantes actualizada correctamente');
        return redirect()->to(base_url() . 'admin/update_tables');
    }
    public function update_section()
    {
        $session = session();
        $SectionMod = new SectionModel();
        $respuesta = $SectionMod->updateTSection();
        $session->set('flash_message', 'Tabla Cursos actualizada correctamente');
        return redirect()->to(base_url() . 'admin/update_tables');
    }
    /*************************MODIFICACIONES DE NOTAS******************* */
    function list_students()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        $Section = new SectionModel();
        $page_data['cursos'] = $Section->get_section(['active' => 1]);

        $StudentMod = new StudentModel();
        $page_data['students_by_section'] = $StudentMod->students_by_section();

        $Setting = new SettingModel();
        $page_data['phase_id']     = $Setting->get_phase_id();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();

        $page_data['page_name']  = 'list_students';
        $page_data['page_title'] = 'Lista de Estudiantes';
        return view('backend/index', $page_data);
    }
    function section_students($section_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        $Section = new SectionModel();
        $cursos = $Section->get_section(['active' => 1]);
        $curso_actual = array_values(array_filter($cursos, fn($c) => $c['section_id'] == $section_id));

        $StudentMod = new StudentModel();
        $students = $StudentMod->get_student(['activo' => 1, 'section_id' => $section_id]);
        usort($students, fn($a, $b) => strcmp(
            $a['lastname'] . ' ' . $a['lastname2'] . ' ' . $a['name'],
            $b['lastname'] . ' ' . $b['lastname2'] . ' ' . $b['name']
        ));
        $page_data['students'] = $students;

        $Setting = new SettingModel();
        $page_data['phase_id']     = $Setting->get_phase_id();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['curso_nombre'] = !empty($curso_actual) ? $curso_actual[0]['completo'] : 'Curso';
        $page_data['section_id']   = $section_id;

        $page_data['page_name']  = 'section_students';
        $page_data['page_title'] = 'Lista de Estudiantes';
        return view('backend/index', $page_data);
    }
    function student_notes($student_id = '', $phase_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $page_data['student'] = $students[0]->nombre;
        $page_data['completo'] = $students[0]->completo;

        //Notas
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->student_csamarks($student_id, $phase_id);
        $page_data['notes'] = $csamarks;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //$page_data['cursos']  = $cursos;

        $page_data['page_name'] = 'student_notes';
        $page_data['page_title'] = 'Notas Estudiante';
        return view('backend/index', $page_data);
    }
    function student_notes_get($csamarks_id)
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());
        // Llamar al modelo para obtener las notas
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks($csamarks_id);

        // Verificar si se encontró la nota
        if (empty($csamarks)) {
            return $this->response->setStatusCode(404)
                ->setJSON(['message' => 'No se encontraron registros']);
        }

        // Devolver la respuesta en formato JSON
        return $this->response->setJSON($csamarks);
    }
    function student_notes_update()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());
        //parametros
        $csamarks_id = $_POST['csamarks_id'];
        $student_id = $_POST['student_id'];
        $phase_id = $_POST['phase_id'];
        //ACTUALIZAMOS NOTAS
        $datos = [
            'ser_average' => $_POST['ser_average'],
            'saber_average' => $_POST['saber_average'],
            'hacer_average' => $_POST['hacer_average'],
            'autoevaluacion' => $_POST['autoevaluacion'],
            'total_average' => $_POST['total_average'],
            'total_vc' => $_POST['total_vc'],
        ];
        $CsamarksMod = new CsamarksModel();
        $respuesta = $CsamarksMod->update_csamarks($datos, $csamarks_id);


        if ($respuesta > 0) {
            $session->set('flash_message', 'Notas modificadas Correctamente');
            return redirect()->to(base_url() . 'admin/student_notes/' . $student_id . '/' . $phase_id);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'admin/student_notes/' . $student_id . '/' . $phase_id);
        }
    }
    function send_alerta3_php()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        // Obtener datos del POST
        $correo = $_POST['correo'];
        $notificacion_id = $_POST['notificacion_id'];
        $student = $_POST['student'];
        $fecha = date('Y-m-d'); // Corregido: usar date() en lugar de today()

        // Cargar la plantilla HTML
        $templatePath = APPPATH . 'Views/emails/alerta3.html'; // Ajusta la ruta según tu estructura

        if (!file_exists($templatePath)) {
            $session->set('flash_message', 'Error: No se encontró la plantilla HTML');
            return redirect()->to(base_url() . 'admin/test_email');
        }

        $html = file_get_contents($templatePath);

        // Reemplazar placeholders
        $html = str_replace(
            ['{{notificacion_id}}', '{{student}}', '{{fecha}}'],
            [$notificacion_id, $student, $fecha],
            $html
        );

        // Configurar el correo
        $para = $correo;
        $asunto = "Notificación Disciplinaria";

        // Cabeceras para correo HTML
        $headers = [
            'From: Franz Condori <soportetecnico@tiquipaya.edu.bo>',
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8', // Cambiado a utf-8
            'X-Mailer: PHP/' . phpversion()
        ];

        // Enviar el correo
        $enviado = mail($para, $asunto, $html, implode("\r\n", $headers));

        if ($enviado) {
            $session->set('flash_message', 'Correo enviado correctamente');
        } else {
            $session->set('flash_message', 'Error al enviar el correo');
        }

        return redirect()->to(base_url() . 'admin/test_email');
    }
    function recover_self_esp($subject_id, $sheet_id)
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }
        $rev = array();
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();
        $phase_abrev = $Setting->get_phase();
        //SUJECTS
        //$SubjectMod = new SubjectModel();
        //$subject = $SubjectMod->subject_section($subject_id);

        $rev['Periodo Planilla'] = $phase_name;
        $ApigoogleMod = new ApigoogleModel();
        $apigoogle = $ApigoogleMod->recoverSelf($sheet_id, $subject_id, $phase_id, 61, $phase_abrev);
        $rev['Autoevaluaciones'] = "Recuperadas";

        //$CsamarksMod = new CsamarksModel();
        //$csamarks = $CsamarksMod->csamarks_subject_update($subject_id, $phase_id);
        //$rev['Notas'] = "Promedios finales actualizados";
        $datos['rev'] = $rev;
        return view('sheet_check', $datos);
    }
    function centralizer_notes_esp($subject_id, $sheet_id, $name)
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }
        $rev = array();
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $phase_name = $Setting->get_phase_name();
        $phase_abrev = $Setting->get_phase();
        //SUJECTS
        //$SubjectMod = new SubjectModel();
        //$subject = $SubjectMod->subject_section($subject_id);

        $rev['Periodo Planilla'] = $phase_name;
        $ApigoogleMod = new ApigoogleModel();
        $apigoogle = $ApigoogleMod->centralize_especialidad($sheet_id, $subject_id, $phase_id, $phase_abrev, $name);
        $rev['Notas Especialidad'] = "Centralizadas";

        //$CsamarksMod = new CsamarksModel();
        //$csamarks = $CsamarksMod->csamarks_subject_update($subject_id, $phase_id);
        //$rev['Notas'] = "Promedios finales actualizados";
        $datos['rev'] = $rev;
        return view('sheet_check', $datos);
    }

    public function feedback_manager()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $Setting = new SettingModel();
        $FeedbackMod = new FeedbackModel();

        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['feedbacks'] = $FeedbackMod->getAllFeedback();

        $page_data['page_name'] = 'feedback_manager';
        $page_data['page_title'] = 'Gestor de Comentarios';

        return view('backend/index', $page_data);
    }

    public function nivel()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $Setting = new SettingModel();
        $NivelMod = new NivelModel();

        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        
        $page_data['datos'] = $NivelMod->listar_niveles();

        $page_data['page_name'] = 'nivel';
        $page_data['page_title'] = 'Gestión de Niveles';

        return view('backend/index', $page_data);
    }

    public function nivel_create()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $data = [
            'nivel' => $this->request->getPost('nivel'),
            'abreviado' => $this->request->getPost('abreviado'),
            'inicio' => $this->request->getPost('inicio'),
            'fin' => $this->request->getPost('fin'),
            'director_id' => $this->request->getPost('director_id')
        ];

        $NivelMod = new NivelModel();
        $NivelMod->insert_nivel($data);

        $session->set('flash_message', 'Nivel creado correctamente');
        return redirect()->to(base_url() . '/admin/nivel');
    }

    public function nivel_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return $this->response->setStatusCode(403);
        }

        $NivelMod = new NivelModel();
        $data = ['id' => $id];
        $nivel = $NivelMod->get_nivel($data);

        return $this->response->setJSON($nivel[0] ?? []);
    }

    public function nivel_update()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $id = $this->request->getPost('id');
        $data = [
            'nivel' => $this->request->getPost('nivel'),
            'abreviado' => $this->request->getPost('abreviado'),
            'inicio' => $this->request->getPost('inicio'),
            'fin' => $this->request->getPost('fin'),
            'director_id' => $this->request->getPost('director_id')
        ];

        $NivelMod = new NivelModel();
        $NivelMod->update_nivel($data, $id);

        $session->set('flash_message', 'Nivel actualizado correctamente');
        return redirect()->to(base_url() . '/admin/nivel');
    }

    public function nivel_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $id = $this->request->getPost('id');
        $NivelMod = new NivelModel();
        $NivelMod->delete_nivel($id);

        $session->set('flash_message', 'Nivel eliminado correctamente');
        return redirect()->to(base_url() . '/admin/nivel');
    }

    // Periodo CRUD
    public function periodo()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $Setting = new SettingModel();
        $PeriodoMod = new PeriodoModel();

        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        
        $page_data['datos'] = $PeriodoMod->listar_periodos();

        $page_data['page_name'] = 'periodo';
        $page_data['page_title'] = 'Gestión de Periodos';

        return view('backend/index', $page_data);
    }

    public function periodo_create()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $data = [
            'periodo' => $this->request->getPost('periodo'),
            'hora_inicio' => $this->request->getPost('hora_inicio'),
            'hora_fin' => $this->request->getPost('hora_fin'),
            'nivel_id' => $this->request->getPost('nivel_id')
        ];

        $PeriodoMod = new PeriodoModel();
        $PeriodoMod->insert_periodo($data);

        $session->set('flash_message', 'Periodo creado correctamente');
        return redirect()->to(base_url() . '/admin/periodo');
    }

    public function periodo_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return $this->response->setStatusCode(403);
        }

        $PeriodoMod = new PeriodoModel();
        $data = ['periodo_id' => $id];
        $periodo = $PeriodoMod->get_periodo($data);

        return $this->response->setJSON($periodo[0] ?? []);
    }

    public function periodo_update()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $id = $this->request->getPost('periodo_id');
        $data = [
            'periodo' => $this->request->getPost('periodo'),
            'hora_inicio' => $this->request->getPost('hora_inicio'),
            'hora_fin' => $this->request->getPost('hora_fin'),
            'nivel_id' => $this->request->getPost('nivel_id')
        ];

        $PeriodoMod = new PeriodoModel();
        $PeriodoMod->update_periodo($data, $id);

        $session->set('flash_message', 'Periodo actualizado correctamente');
        return redirect()->to(base_url() . '/admin/periodo');
    }

    public function periodo_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $id = $this->request->getPost('periodo_id');
        $PeriodoMod = new PeriodoModel();
        $PeriodoMod->delete_periodo($id);

        $session->set('flash_message', 'Periodo eliminado correctamente');
        return redirect()->to(base_url() . '/admin/periodo');
    }

    // Trimestres (phase) CRUD — se replica hacia tiqui0_tiquisaat26 (espejo)
    public function phase()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $Setting = new SettingModel();
        $PhaseMod = new PhaseModel();

        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $page_data['datos'] = $PhaseMod->listar_phases();

        $page_data['page_name'] = 'phase';
        $page_data['page_title'] = 'Gestión de Trimestres';

        return view('backend/index', $page_data);
    }

    public function phase_create()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $PhaseMod = new PhaseModel();
        $data = [
            'phase_id'  => $PhaseMod->next_phase_id(),
            'name'      => $this->request->getPost('name'),
            'inicio'    => $this->request->getPost('inicio'),
            'fin'       => $this->request->getPost('fin'),
            'abreviado' => $this->request->getPost('abreviado'),
            'activo'    => $this->request->getPost('activo') ? 1 : 0,
        ];
        $PhaseMod->insert_phase($data);
        $PhaseMod->updateTPhase();

        $session->set('flash_message', 'Trimestre creado correctamente');
        return redirect()->to(base_url() . '/admin/phase');
    }

    public function phase_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return $this->response->setStatusCode(403);
        }

        $PhaseMod = new PhaseModel();
        $data = ['phase_id' => $id];
        $phase = $PhaseMod->get_phase($data);

        return $this->response->setJSON($phase[0] ?? []);
    }

    public function phase_update()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $id = $this->request->getPost('phase_id');
        $data = [
            'name'      => $this->request->getPost('name'),
            'inicio'    => $this->request->getPost('inicio'),
            'fin'       => $this->request->getPost('fin'),
            'abreviado' => $this->request->getPost('abreviado'),
            'activo'    => $this->request->getPost('activo') ? 1 : 0,
        ];

        $PhaseMod = new PhaseModel();
        $PhaseMod->update_phase($data, $id);
        $PhaseMod->updateTPhase();

        $session->set('flash_message', 'Trimestre actualizado correctamente');
        return redirect()->to(base_url() . '/admin/phase');
    }

    public function phase_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $id = $this->request->getPost('phase_id');
        $PhaseMod = new PhaseModel();
        $PhaseMod->delete_phase($id);
        $PhaseMod->updateTPhase();

        $session->set('flash_message', 'Trimestre eliminado correctamente');
        return redirect()->to(base_url() . '/admin/phase');
    }

    // Autoevaluaciones CRUD
    public function self_appraisal($phase_id = 0)
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $PhaseMod = new PhaseModel();
        $phases = $PhaseMod->listar_phases();
        $page_data['phases'] = $phases;

        // Pestaña activa: la enviada por parámetro, o la fase actual por defecto
        $phase_id = (int) $phase_id;
        if ($phase_id <= 0) {
            $phase_id = (int) $page_data['phase_id'];
        }
        $page_data['active_phase_id'] = $phase_id;

        // Filtro de pendientes: por defecto solo se muestran los estudiantes sin autoevaluación
        $solo_pendientes = $this->request->getGet('ver') !== 'todos';
        $page_data['solo_pendientes'] = $solo_pendientes;

        $Self = new SelfappraisalModel();

        // Conteos livianos (SUM/COUNT) para el badge de cada pestaña, sin traer el detalle de estudiantes
        $phase_counts = [];
        foreach ($phases as $phase) {
            $phase_counts[$phase['phase_id']] = $Self->self_admin_counts($phase['phase_id']);
        }
        $page_data['phase_counts'] = $phase_counts;

        // Detalle completo (agrupado por curso) únicamente de la pestaña activa
        $rows = $Self->self_admin($phase_id, $solo_pendientes);
        $por_curso = [];
        foreach ($rows as $row) {
            $key = $row['section_id'];
            if (!isset($por_curso[$key])) {
                $por_curso[$key] = [
                    'completo'    => $row['completo'],
                    'section_id'  => $row['section_id'],
                    'total'       => 0,
                    'con_auto'    => 0,
                    'estudiantes' => []
                ];
            }
            $por_curso[$key]['total']++;
            if ($row['tiene_auto']) $por_curso[$key]['con_auto']++;
            $por_curso[$key]['estudiantes'][] = $row;
        }
        $page_data['por_curso'] = $por_curso;

        $page_data['page_name'] = 'self_appraisal';
        $page_data['page_title'] = 'Autoevaluaciones';

        return view('backend/index', $page_data);
    }

    public function self_appraisal_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return $this->response->setStatusCode(403);
        }

        $Self = new SelfappraisalModel();
        $data = ['self_id' => $id];
        $self_appraisal = $Self->get_self_appraisal($data);

        return $this->response->setJSON($self_appraisal[0] ?? []);
    }

    public function self_appraisal_save()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $student_id = (int) $this->request->getPost('student_id');
        $phase_id = (int) $this->request->getPost('phase_id');

        $suma = 0;
        $data = [
            'student_id' => $student_id,
            'phase_id'   => $phase_id,
            'descripcion' => $this->request->getPost('descripcion'),
        ];
        for ($i = 1; $i <= 10; $i++) {
            $valor = $this->request->getPost('auto' . $i) ? 1 : 0;
            $data['auto' . $i] = $valor;
            $suma += $valor;
        }
        // Cada criterio vale 0.5 pts, total sobre 5 pts (mismo criterio usado en la autoevaluación del estudiante)
        $data['autoevaluacion'] = (int) round($suma * 0.5);

        $Self = new SelfappraisalModel();
        $existe = $Self->get_self_appraisal([
            'student_id' => $student_id,
            'phase_id'   => $phase_id
        ]);

        if (count($existe) > 0) {
            $Self->update_self_appraisal($data, $existe[0]['self_id']);
            $session->set('flash_message', 'Autoevaluación actualizada correctamente');
        } else {
            $Self->insert_self_appraisal($data);
            $session->set('flash_message', 'Autoevaluación registrada correctamente');
        }

        return redirect()->to(base_url() . '/admin/self_appraisal/' . $phase_id);
    }

    public function self_appraisal_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $self_id = $this->request->getPost('self_id');
        $phase_id = (int) $this->request->getPost('phase_id');

        $Self = new SelfappraisalModel();
        $Self->delete_self_appraisal(['self_id' => $self_id]);

        $session->set('flash_message', 'Autoevaluación eliminada correctamente');
        return redirect()->to(base_url() . '/admin/self_appraisal/' . $phase_id);
    }

    // Estudiantes CRUD
    public function students()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $buscar = trim((string) $this->request->getGet('buscar'));
        $page_data['buscar'] = $buscar;

        $page = max(1, (int) $this->request->getGet('page'));
        $por_pagina = 50;
        $offset = ($page - 1) * $por_pagina;

        $StudentMod = new StudentModel();
        $total = $StudentMod->count_students_admin($buscar);
        $page_data['students'] = $StudentMod->list_students_admin($buscar, $por_pagina, $offset);

        $page_data['page'] = $page;
        $page_data['total'] = $total;
        $page_data['total_paginas'] = (int) ceil($total / $por_pagina);

        $page_data['page_name'] = 'students_admin';
        $page_data['page_title'] = 'Estudiantes';

        return view('backend/index', $page_data);
    }

    public function students_get($id)
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return $this->response->setStatusCode(403);
        }

        $StudentMod = new StudentModel();
        $student = $StudentMod->get_student(['student_id' => $id]);

        return $this->response->setJSON($student[0] ?? []);
    }

    private function student_post_data()
    {
        return [
            'roll'                  => $this->request->getPost('roll') ?: 0,
            'code'                  => $this->request->getPost('code'),
            'name'                  => $this->request->getPost('name'),
            'lastname'              => $this->request->getPost('lastname'),
            'lastname2'             => $this->request->getPost('lastname2'),
            'birthday'              => $this->request->getPost('birthday'),
            'place_birth'           => $this->request->getPost('place_birth') ?: null,
            'card'                  => $this->request->getPost('card'),
            'place_card'            => $this->request->getPost('place_card') ?: null,
            'expire_card'           => $this->request->getPost('expire_card'),
            'sex'                   => $this->request->getPost('sex'),
            'rude'                  => $this->request->getPost('rude'),
            'address'               => $this->request->getPost('address'),
            'reference'             => $this->request->getPost('reference'),
            'phone'                 => $this->request->getPost('phone'),
            'cellphone'             => $this->request->getPost('cellphone'),
            'personal_email'        => $this->request->getPost('personal_email'),
            'origin_school'         => $this->request->getPost('origin_school'),
            'email'                 => $this->request->getPost('email'),
            'registration_date'     => $this->request->getPost('registration_date'),
            'retirement_date'       => $this->request->getPost('retirement_date') ?: null,
            'activo'                => $this->request->getPost('activo') ?: 0,
            'activo_administracion' => $this->request->getPost('activo_administracion') ?: 0,
            'matricula'             => $this->request->getPost('matricula') ?: 0,
            'section_id'            => $this->request->getPost('section_id'),
            'family_id'             => $this->request->getPost('family_id') ?: 0,
            'nit_id'                => $this->request->getPost('nit_id') ?: 0,
            'ddjj'                  => $this->request->getPost('ddjj') ?: 0,
        ];
    }

    public function students_create()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $StudentMod = new StudentModel();
        $data = $this->student_post_data();
        $data['student_id'] = $StudentMod->next_student_id();
        $StudentMod->insertStudent($data);

        // Replicamos t_student completa hacia las bases tiquipaya y asistencia
        $StudentMod->updateTStudent();

        $session->set('flash_message', 'Estudiante registrado correctamente');
        return redirect()->to(base_url() . '/admin/students');
    }

    public function students_update()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $student_id = $this->request->getPost('student_id');
        $StudentMod = new StudentModel();
        $StudentMod->updateStudent($student_id, $this->student_post_data());

        // Replicamos t_student completa hacia las bases tiquipaya y asistencia
        $StudentMod->updateTStudent();

        $session->set('flash_message', 'Estudiante actualizado correctamente');
        return redirect()->to(base_url() . '/admin/students');
    }

    public function students_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'admin') {
            return redirect()->to(base_url());
        }

        $student_id = $this->request->getPost('student_id');
        $StudentMod = new StudentModel();
        $StudentMod->delete_student($student_id);

        // Replicamos t_student completa hacia las bases tiquipaya y asistencia
        $StudentMod->updateTStudent();

        $session->set('flash_message', 'Estudiante eliminado correctamente');
        return redirect()->to(base_url() . '/admin/students');
    }

    // Entrega de Notas (todos los docentes, todos los niveles, todas las materias)
    public function delivery_notes()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        $buscar = trim((string) $this->request->getGet('buscar'));
        $page_data['buscar'] = $buscar;

        // Filtro de estado: por defecto solo materias Abiertas (no consolidadas)
        $estado = $this->request->getGet('estado') !== null ? $this->request->getGet('estado') : 'abierta';
        $page_data['estado'] = $estado;
        $locked = null;
        if ($estado === 'abierta') {
            $locked = 0;
        } elseif ($estado === 'consolidada') {
            $locked = 1;
        }

        $SubjectMod = new SubjectModel();
        $subjects = $SubjectMod->subjects_admin($buscar, $locked);

        // Agrupamos por docente, manteniendo el orden alfabético devuelto por la consulta
        $por_docente = [];
        foreach ($subjects as $row) {
            $key = $row['teacher_id'];
            if (!isset($por_docente[$key])) {
                $por_docente[$key] = [
                    'docente'  => $row['docente'],
                    'materias' => []
                ];
            }
            $por_docente[$key]['materias'][] = $row;
        }
        $page_data['por_docente'] = $por_docente;

        $page_data['page_name'] = 'delivery_notes_list';
        $page_data['page_title'] = 'Entrega de Notas';

        return view('backend/index', $page_data);
    }

    public function deliver_notes($subject_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $phase = $Setting->get_phase();

        // Materia
        $SubjectMod = new SubjectModel();
        $subject = $SubjectMod->subject_section($subject_id);
        if (count($subject) == 0) {
            $session->set('flash_message', 'La materia solicitada no existe');
            return redirect()->to(base_url() . 'admin/delivery_notes');
        }
        $page_data['subject'] = $subject[0]['name'];
        $page_data['curso'] = $subject[0]['completo'];
        $page_data['docente'] = $subject[0]['docente'];
        $official_id = $subject[0]['official_id'];
        $teacher_id = $subject[0]['teacher_id'];

        // Creamos CSAMARKS para STUDENTS (si aún no existen) o actualizamos desde la planilla oficial
        $CsamarksMod = new CsamarksModel();
        $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
        if (count($csamarks) == 0) {
            $StudentMod = new StudentModel();
            $students = $StudentMod->studentsSection($subject[0]['section_id'], $teacher_id);
            foreach ($students as $stu) {
                $data_csamarks = [
                    'student_id' => $stu['student_id'],
                    'locked'     => 0,
                    'phase_id'   => $page_data['phase_id'],
                    'subject_id' => $subject_id,
                ];
                $CsamarksMod->insert_csamarks($data_csamarks);
            }
        } else {
            $ApigoogleMod = new ApigoogleModel();
            $ApigoogleMod->importNotes($subject[0]['sheet_id'], $subject_id, $page_data['phase_id'], $phase);
        }
        $csamarks = $CsamarksMod->csamarks_subject($subject_id, $page_data['phase_id']);
        $page_data['csamarks'] = $csamarks;

        // Detalles
        $CsamarksdetailsMod = new CsamarksdetailsModel();
        $page_data['details_ser'] = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "ser");
        $page_data['details_saber'] = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "saber");
        $page_data['details_hacer'] = $CsamarksdetailsMod->csamarks_details_dim($subject_id, $page_data['phase_id'], "hacer");

        $page_data['official_id'] = $official_id;
        $page_data['subject_id'] = $subject_id;
        $page_data['page_name'] = 'deliver_notes';
        $page_data['page_title'] = 'Entrega de Notas';

        return view('backend/index', $page_data);
    }

    // Docentes sin consolidar Notas (todos los niveles, sin filtro por director)
    public function teacher_notes()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());

        $Subject = new SubjectModel();
        $page_data['teachers'] = $Subject->notes_teacher_all();
        $page_data['subjects'] = $Subject->notes_subject_all();

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

    function generate_centralizer($section_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
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
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row):
                $est = $row['lastname'].' '.$row['lastname2'].' '.$row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B'.$conter, $est);
                //******************RELLENAMOS NOTAS*************************
                for ($i=0; $i < $phase_id; $i++) { 
                    list($cnat, $ing, $lening, $prom, $lenque, $fisqui) = array(0,0,0,0,0,0);
                    $b=1 + $i;
                    //Notas
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    foreach ($notas as $nota) {
                        if (!isset($nota['obtained_mark'])) { $nota['obtained_mark'] = '0'; }
                        switch($nota['name']){
                            case 'LENGUAJE':
                                $lening+=round($nota['obtained_mark']*0.45);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(44 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'QUECHUA':
                                $lening+=round($nota['obtained_mark']*0.05);
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
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'E. FÍSICA':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MÚSICA':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'ARTE':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MATEMÁTICA':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'COMPUTACIÓN':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'SCIENCE':
                                $cnat+=$nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(68 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'C. NATURALES':
                                $cnat+=$nota['obtained_mark'];
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
                    if($cnat!=0){$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(76 + $b, $conter, round($cnat/2));}
                    $prom+=round($lening)+round($cnat/2);
                    if($prom!=0){$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(38 + $b, $conter, round($prom/9));}
                }
                $conter++;
            endforeach;

        }elseif ($section_id >= 231 And $section_id <= 263) {
            $obj_PHPExcel = $obj_Reader->load('templates/cp36.xlsx');
            $obj_PHPExcel->setActiveSheetIndex(0);
            //******************RELLENAMOS LOS NOMBREs
            foreach ($students as $row):
                $est = $row['lastname'].' '.$row['lastname2'].' '.$row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B'.$conter, $est);
                //******************RELLENAMOS NOTAS*************************
                for ($i=0; $i < $phase_id; $i++) { 
                    list($cnat, $ing, $lening, $prom, $lenque, $val) = array(0,0,0,0,0,0);
                    $b=1 + $i;
                    //Notas
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    foreach ($notas as $nota) {
                        if (!isset($nota['obtained_mark'])) { $nota['obtained_mark'] = '0'; }
                        switch($nota['name']){
                            case 'LENGUAJE':
                                $lening+=round($nota['obtained_mark']*0.45);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(44 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'QUECHUA':
                                $lening+=round($nota['obtained_mark']*0.05);
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
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'E. FÍSICA':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MÚSICA':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'ARTE':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'MATEMÁTICA':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'COMPUTACIÓN':
                                $prom+=round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'SCIENCE':
                                $cnat+=$nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(68 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'C. NATURALES':
                                $cnat+=$nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(72 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'RULER':
                                $val+=$nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(80 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'CHARACTER':
                                $val+=$nota['obtained_mark'];
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(84 + $b, $conter, $nota['obtained_mark']);
                                break;
                        }
                    }
                    if($ing!=0){
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(60 + $b, $conter, round($ing/2));
                        $lening+=round(round($ing/2)*0.5);
                    }
                    if($lening!=0){$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(64 + $b, $conter, round($lening));}
                    if($cnat!=0){$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(76 + $b, $conter, round($cnat/2));}
                    if($val!=0){$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(88 + $b, $conter, round($val/2));}
                    $prom+=round($lening)+round($cnat/2)+round($val/2);
                    if($prom!=0){$obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(38 + $b, $conter, round($prom/9));}
                }
                $conter++;
            endforeach;
        }elseif ($section_id >= 271 And $section_id <= 283) {
            $obj_PHPExcel = $obj_Reader->load('templates/cs12.xlsx');
            $obj_PHPExcel->setActiveSheetIndex(0);
            foreach ($students as $row):
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $est);
                for ($i = 0; $i < $phase_id; $i++) {
                    list($cnat, $ing, $lening, $prom, $lenque, $fisqui) = array(0, 0, 0, 0, 0, 0);
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        $prom += round($ef['total_average']);
                        if ($ef['total_average'] != 0) {
                            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $ef['total_average']);
                        }
                    }
                    foreach ($notas as $nota) {
                        if (!isset($nota['obtained_mark'])) {
                            $nota['obtained_mark'] = '0';
                        }
                        switch ($nota['name']) {
                            case 'LENGUAJE':
                                $lenque += $nota['obtained_mark'];
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
                    }
                    if ($fisqui != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(76 + $b, $conter, $fisqui);
                    }
                    $prom += round($lenque / 2) + round($ing / 2) + $fisqui;
                    if ($prom != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(46 + $b, $conter, round($prom / 11));
                    }
                }
                $conter++;
            endforeach;
        } elseif ($section_id >= 311 And $section_id <= 323) {
            $obj_PHPExcel = $obj_Reader->load('templates/cs34.xlsx');
            $obj_PHPExcel->setActiveSheetIndex(0);
            foreach ($students as $row):
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $est);
                for ($i = 0; $i < $phase_id; $i++) {
                    list($cnat, $ing, $lening, $prom, $lenque, $fisqui) = array(0, 0, 0, 0, 0, 0);
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        $prom += round($ef['total_average']);
                        if ($ef['total_average'] != 0) {
                            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $ef['total_average']);
                        }
                    }
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
                                $fisqui += round($nota['obtained_mark']);
                                $prom += round($nota['obtained_mark']);
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(38 + $b, $conter, $nota['obtained_mark']);
                                break;
                            case 'QUÍMICA':
                                $fisqui += round($nota['obtained_mark']);
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
                    }
                    $prom += round($ing / 2) + $fisqui;
                    if ($prom != 0) {
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(54 + $b, $conter, round($prom / 11));
                    }
                }
                $conter++;
            endforeach;
        } elseif ($section_id >= 331 And $section_id <= 343) {
            $obj_PHPExcel = $obj_Reader->load('templates/cs56.xlsx');
            $obj_PHPExcel->setActiveSheetIndex(0);
            foreach ($students as $row):
                $est = $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $est);
                for ($i = 0; $i < $phase_id; $i++) {
                    list($cnat, $ing, $lening, $prom, $lenque, $fisqui) = array(0, 0, 0, 0, 0, 0);
                    $b = 1 + $i;
                    $CsamarksMod = new CsamarksModel();
                    $notas = $CsamarksMod->csamarks_centralizer($row['student_id'], $b);
                    $ed_fisica = $CsamarksMod->csamarks_ed_fisica($row['student_id'], $b);
                    foreach ($ed_fisica as $ef) {
                        $prom += round($ef['total_average']);
                        if ($ef['total_average'] != 0) {
                            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14 + $b, $conter, $ef['total_average']);
                        }
                    }
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
                                $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(46 + $b, $conter, $nota['obtained_mark']);
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
                        $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(68 + $b, $conter, round($ing / 2));
                    }
                    $prom += round($ing / 2);
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
    function update_notes($subject_id = '', $phase_id = '')
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());
        $rev = array();
        $teacher_id = $session->get('teacher_id');
        $Setting = new SettingModel();

        // Si no se especifica phase_id (Trimestre 1, 2 o 3), usamos el trimestre activo por defecto
        $phase_id = $phase_id !== '' ? (int) $phase_id : (int) $Setting->get_phase_id();

        $PhaseMod = new PhaseModel();
        $phase_row = $PhaseMod->get_phase(['phase_id' => $phase_id]);
        $phase_name = $phase_row[0]['name'] ?? $Setting->get_phase_name();
        $phase = $phase_row[0]['abreviado'] ?? $Setting->get_phase();

        $page_data['phase_id'] = $phase_id;
        $page_data['phase_name'] = $phase_name;
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
        $page_data['page_name'] = 'update_notes';
        $page_data['page_title'] = 'Notas Actualizadas';
        return view('backend/index', $page_data);
    }
}
