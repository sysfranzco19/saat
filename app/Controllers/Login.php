<?php
/* 	
 * 	@author : Franz Condori Calderon
 * 	Febrero, 2024
 * 	Sysfranzco
 * 	www.sysfranzco.com
 * 	http://sysfranzco.net/user/fcondori
 */
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\LoginModel;
use App\Models\StudentModel;
use App\Models\FamilyModel;
use App\Models\TeacherModel;
use App\Models\DirectorModel;
use App\Models\SectionModel;
use App\Models\FeedbackModel;

class Login extends BaseController
{
    // Feedback registration
    public function save_feedback()
    {
        $session = session();
        if (!$session->get('login_type')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Sesión expirada']);
        }

        $userId = $session->get($session->get('login_type') . '_id');
        $userType = $session->get('login_type');
        $comment = $this->request->getPost('comment');
        $url = $this->request->getPost('url');

        if (empty($comment)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'El comentario no puede estar vacío']);
        }

        $FeedbackMod = new FeedbackModel();
        $result = $FeedbackMod->registerFeedback($userId, $userType, $comment, $url);

        if ($result) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Comentario enviado correctamente']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al guardar el comentario']);
        }
    }
    //protected $session = null;
    public function index()
    {
        //ModeloSettings
        $Setting = new SettingModel();

        $mensaje = session('mensaje');
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['mensaje'] = session('mensaje');
        return view('login_view', $page_data);
    }

    //Ajax login function 
    function login()
    {
        //$response = array();

        //Recieving post input of email, password from ajax request
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        //$response['submitted_data'] = $_POST;
/*
        $Usuario = new LoginModel();

        $datosUsuario = $Usuario->obtenerUsuario(['email' => $email]);

        if (count($datosUsuario)>0 && md5($password)==$datosUsuario[0]['password']) {
            $data = [
                "admin_id" => $datosUsuario[0]['admin_id'],
                "name" => $datosUsuario[0]['name'],
                "email" => $datosUsuario[0]['email'],
                "login_type" => 'admin',
            ];
            $session = session();
            $session->set($data);
            return redirect()->to(base_url('admin/dashboard'))->with('mensaje','1');

        }else{
            return redirect()->to(base_url('/'))->with('mensaje','Credenciales Inválidas '.$password.' = '.$datosUsuario[0]['password']);
        }
        */
        $login_status = $this->validate_login($email, $password);
        if ($login_status == 'success') {
            //$response['redirect_url'] = '';
            //redirect(base_url() . 'index.php/login', 'refresh');
            $session = session();
            return redirect()->to(base_url($session->get('login_type') . '/dashboard'))->with('mensaje', '4');
        } else {
            return redirect()->to(base_url('/'))->with('mensaje', '2');
        }
    }
    function validate_login($email = '', $password = '')
    {

        $Usuario = new LoginModel();
        //INGRESO ADMIN
        $datosAdmin = $Usuario->obtenerAdmin(['email' => $email]);
        if (count($datosAdmin) > 0 && md5($password) == $datosAdmin[0]['password']) {
            $data = [
                "admin_id" => $datosAdmin[0]['admin_id'],
                "name" => $datosAdmin[0]['name'],
                "email" => $datosAdmin[0]['email'],
                "login_type" => 'admin',
                "cuenta" => 'Administrador',
            ];
            $session = session();
            $session->set($data);
            return 'success';
        }
        //INGRESO SECRETARY
        $datosSecretary = $Usuario->obtenerSecretary(['email' => $email]);
        if (count($datosSecretary) > 0 && md5($password) == $datosSecretary[0]['password']) {
            $data = [
                "secretary_id" => $datosSecretary[0]['secretary_id'],
                "name" => $datosSecretary[0]['name'],
                "email" => $datosSecretary[0]['email'],
                "login_type" => 'secretary',
                "cuenta" => 'Secretaria',
            ];
            $session = session();
            $session->set($data);
            return 'success';
        }
        //INGRESO MANAGER

        $datosManager = $Usuario->obtenerManager(['email' => $email]);
        if (count($datosManager) > 0 && md5($password) == $datosManager[0]['password']) {
            $data = [
                "manager_id" => $datosManager[0]['manager_id'],
                "name" => $datosManager[0]['name'],
                "email" => $datosManager[0]['email'],
                "level" => $datosManager[0]['level'],
                "login_type" => 'manager',
                "cuenta" => 'Administrativo',
            ];
            $session = session();
            $session->set($data);
            return 'success';
        }

        //INGRESO PARENT
        $datosParent = $Usuario->obtenerParent(['email' => $email]);
        if (count($datosParent) > 0 && md5($password) == $datosParent[0]['password']) {
            $data = [
                "parent_id" => $datosParent[0]['parent_id'],
                "family_id" => $datosParent[0]['family_id'],
                "name" => $datosParent[0]['name'],
                "email" => $datosParent[0]['email'],
                "login_type" => 'parents',
                "cuenta" => 'Padre de Familia',
            ];
            $session = session();
            $session->set($data);
            return 'success';
        }

        //INGRESO STUDENT
        $Student = new LoginModel();
        $datosStudent = $Student->obtenerStudent(['email' => $email]);
        if (count($datosStudent) > 0 && md5($password) == $datosStudent[0]['password']) {
            $data = [
                "student_id" => $datosStudent[0]['student_id'],
                "section_id" => $datosStudent[0]['section_id'],
                "name" => $datosStudent[0]['name'],
                "email" => $datosStudent[0]['email'],
                "login_type" => 'student',
                "cuenta" => 'Estudiante',
            ];
            $session = session();
            $session->set($data);
            return 'success';
        }

        $Usuario = new TeacherModel();
        //INGRESO TEACHER
        $datosTeacher = $Usuario->obtenerTeacher(['email' => $email]);
        if (count($datosTeacher) > 0 && md5($password) == $datosTeacher[0]['password']) {
            //Es Consejero
            $data = ["teacher_id" => $datosTeacher[0]['teacher_id']];
            $Section = new SectionModel();
            $cursos = $Section->get_section($data);
            $adviser = false;
            if (count($cursos) >= 1) {
                $adviser = true;
            }
            //Es Director
            $data2 = ["teacher_id" => $datosTeacher[0]['teacher_id']];
            $directorMod = new DirectorModel();
            $dir = $directorMod->get_director($data2);
            $director = false;
            if (count($dir) >= 1) {
                $director = true;
            }
            $data = [
                "teacher_id" => $datosTeacher[0]['teacher_id'],
                "name" => $datosTeacher[0]['name'],
                "email" => $datosTeacher[0]['email'],
                "login_type" => 'teacher',
                "cuenta" => 'Docente',
                "adviser" => $adviser,
                "director" => $director,
            ];
            $session = session();
            $session->set($data);
            return 'success';
        }

        return 'invalid';
    }
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('/'));
    }
    function login_inscripcion()
    {
        $email = $_POST['email'];
        $ciest = $_POST['ci_estudiante'];
        $f_nac = $_POST['anio'] . "-" . $_POST['mes'] . "-" . $_POST['dia'];
        $data1 = [
            "card" => $ciest,
            "birthday" => $f_nac,
        ];
        $student = new StudentModel();
        $respuesta = $student->get_student($data1);

        $student_id = "";
        //VERIFICAMOS SI EXISTE EL ESTUDIANTE
        if (!empty($respuesta)) {
            $student_id = $respuesta[0]['student_id'];
            $family_id = $respuesta[0]['family_id'];
            //VERIFICAMOS SI el CORREO EXISTE y ESTA REGISTRADO EN LA FAMILIA
            $existe_email = false;
            $data2 = [
                "family_id" => $family_id,
                "email1" => $email,
            ];
            $family2 = new FamilyModel();
            $res_email2 = $family2->get_family($data2);
            if (count($res_email2) == 1) {
                $existe_email = true;
            } else {
                $data3 = [
                    "family_id" => $family_id,
                    "email2" => $email,
                ];
                $family3 = new FamilyModel();
                $res_email3 = $family3->get_family($data3);
                if (count($res_email3) == 1) {
                    $existe_email = true;
                }
            }

            if ($existe_email) {
                $data = [
                    "email" => $email,
                    "ciest" => $ciest,
                    "f_nac" => $f_nac,
                    "student_id" => $student_id,
                    "family_id" => $family_id,
                    "estado" => 0,
                    "login_type" => 'inscripcion',
                ];
                $session = session();
                $session->set($data);
                return redirect()->to(base_url('/inscripcion_inicio'))->with('mensaje', '0');
            } else {
                return redirect()->to(base_url('/inscripcion'))->with('mensaje', '2');
            }

        } else {
            return redirect()->to(base_url('/inscripcion'))->with('mensaje', '1');
        }

    }
    public function logout_inscripcion()
    {
        $session = session();
        //unset($session['login_type']);
        $session->destroy();
        return redirect()->to(base_url('/inscripcion'));
    }
    public function forgot_password($found = '')
    {
        //ModeloSettings
        $Setting = new SettingModel();

        $page_data['found'] = $found;
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['mensaje'] = session('mensaje');
        return view('forgot_password', $page_data);
    }
    public function restore_password()
    {
        $existe = false;
        //Si existe email
        if (isset($_POST['email'])) {
            $Usuario = new LoginModel();
            $Setting = new SettingModel();
            $system_name = $Setting->get_system_name();

            //INGRESO PARENT
            $datosParent = $Usuario->obtenerParent(['email' => $_POST['email'], 'card' => $_POST['ci']]);
            if (count($datosParent) == 1) {
                $new_password = substr(md5(rand()), 0, 8);
                $Usuario->updateParent($datosParent[0]['parent_id'], ['password' => md5($new_password), 'code' => $new_password]);

                $to = $_POST['email'];
                $subject = "Restablecimiento de Contraseña - SAAT Tiquipaya";
                $mensaje = "Hola " . $datosParent[0]['name'] . ",<br><br>";
                $mensaje .= "Tu contraseña ha sido restablecida en la plataforma <b>SAAT Tiquipaya</b>.<br><br>";
                $mensaje .= "<b>Email:</b> " . $to . "<br>";
                $mensaje .= "<b>Nueva Contraseña:</b> " . $new_password . "<br><br>";
                $mensaje .= "Por favor, cambia tu contraseña después de ingresar.";

                $headers = array();
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=utf-8';
                $headers[] = 'From: SAAT Tiquipaya <noreply@' . $_SERVER['HTTP_HOST'] . '>';

                mail($to, $subject, $mensaje, implode("\r\n", $headers));

                $existe = true;
                return redirect()->to(base_url())->with('mensaje', '<p class="text-info">Se ha restablecido su contraseña, revise su correo electrónico.</p>');
            }

            //INGRESO STUDENT
            $datosStudent = $Usuario->obtenerStudent(['email' => $_POST['email'], 'card' => $_POST['ci']]);
            if (count($datosStudent) == 1) {
                $new_password = substr(md5(rand()), 0, 8);
                $Usuario->updateStudent($datosStudent[0]['student_id'], ['password' => md5($new_password), 'code' => $new_password]);

                $to = $_POST['email'];
                $subject = "Restablecimiento de Contraseña - SAAT Tiquipaya";
                $mensaje = "Hola " . $datosStudent[0]['name'] . ",<br><br>";
                $mensaje .= "Tu contraseña ha sido restablecida en la plataforma <b>SAAT Tiquipaya</b>.<br><br>";
                $mensaje .= "<b>Email:</b> " . $to . "<br>";
                $mensaje .= "<b>Nueva Contraseña:</b> " . $new_password . "<br><br>";
                $mensaje .= "Por favor, cambia tu contraseña después de ingresar.";

                $headers = array();
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=utf-8';
                $headers[] = 'From: SAAT Tiquipaya <noreply@' . $_SERVER['HTTP_HOST'] . '>';

                mail($to, $subject, $mensaje, implode("\r\n", $headers));

                $existe = true;
                return redirect()->to(base_url())->with('mensaje', '<p class="text-info">Se ha restablecido su contraseña, revise su correo electrónico.</p>');
            }
        }
        if ($existe) {

        } else {

            return redirect()->to(base_url('forgot_password/0'));
        }

    }
}
