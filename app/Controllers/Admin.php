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
                        //$update_csamarks['autoevaluacion'] = $bth['ser5'];
                        //$update_csamarks['auto_decidir'] = $bth['dec5'];
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
        $obj_PHPExcel->getActiveSheet()->SetCellValue('B4', "GESTIÓN 2025 NOTAS OFICIALES " . strtoupper($phase_name));
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
    function section_students()
    {
        $session = session();
        if ($session->get('login_type') != 'admin')
            return redirect()->to(base_url());


        //Section
        $data1 = ["active" => TRUE];
        $Section = new SectionModel();
        $page_data['cursos'] = $Section->get_section($data1);

        //Students
        $data2 = ["activo" => TRUE];
        $StudentMod = new StudentModel();
        $students = $StudentMod->get_student($data2);
        $page_data['students'] = $students;


        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //$page_data['cursos']  = $cursos;

        $page_data['page_name'] = 'section_students';
        $page_data['page_title'] = 'Lista de Estudiantes';
        return view('backend/index', $page_data);
    }
    function section_students222($section_id = '')
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
        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_active($section_id);
        $page_data['students'] = $students;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //$page_data['cursos']  = $cursos;

        $page_data['page_name'] = 'section_students';
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
            'decidir_average' => $_POST['decidir_average'],
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
}
