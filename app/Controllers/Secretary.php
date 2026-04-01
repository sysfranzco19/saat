<?php
namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
use App\Models\LicenciaperiodoModel;
use App\Models\AbsenceModel;
use App\Models\EmailModel;
use App\Models\AssistancesubjectModel;
use App\Models\ParentModel;
use App\Models\DelayModel;
use App\Models\IinfractionModel;
use App\Models\DatesModel;
use App\Models\SecretaryModel;
use App\Models\EhcModel;

//Libreria Plantillas
use App\Libraries\Libreria_pdf;

class Secretary extends BaseController
{
    public function index()
    {
        //
    }
    public function dashboard()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $StudentMod = new StudentModel();
        $SecretaryMod = new SecretaryModel();
        $DatesMod = new DatesModel();
        $LicenciaMod = new LicenciaModel();

        $today = date('Y-m-d');

        // Fetch students for this secretary
        $students = $StudentMod->student_secretary($secretary_id);
        $page_data['total_students'] = count($students);

        // Fetch Attendance Today (Present or Late)
        $date_info = $DatesMod->get_attendance_dates(['date_class' => $today]);
        $page_data['attendance_today'] = 0;
        if (!empty($date_info) && !empty($students)) {
            $date_id = $date_info[0]['date_id'];
            $student_ids = array_column($students, 'student_id');

            $db_asistencia = \Config\Database::connect('asistencia');
            $builder = $db_asistencia->table('assistance_subject');
            $builder->select('student_id');
            $builder->where('date_id', $date_id);
            $builder->whereIn('student_id', $student_ids);
            $builder->whereIn('status', [1, 3]); // 1=Presente, 3=Retraso
            $builder->distinct();
            $page_data['attendance_today'] = $builder->countAllResults();
        }

        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Dashboard";
        $page_data['page_name'] = "dashboard";
        return view('backend/index', $page_data);
    }
    public function enrolled_students()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $StudentMod = new StudentModel();

        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        // Fetch students for this secretary
        $page_data['students'] = $StudentMod->student_secretary($secretary_id);

        $page_data['page_title'] = "Listado de Estudiantes";
        $page_data['page_name'] = "enrolled_students";
        return view('backend/index', $page_data);
    }
    /*********************************************ESTUDIANTE **************************************/

    public function kardex($family_id)
    {

        $family = new FamilyModel();
        $datos = $family->activesFamily();
        $page_data['familias'] = $datos;

        $Setting = new SettingModel();

        if ($family_id <> 0) {
            //DatosFamilia
            $family = new FamilyModel();
            $data = ["family_id" => $family_id];
            $datos2 = $family->get_family_datas($data);
            $page_data['familia'] = $datos2[0];
            //Hijos
            $students = new StudentModel();
            $datos3 = $students->studentFamily($family_id);
            $page_data['students'] = $datos3;

        }
        $session = session();
        $page_data['family_id'] = $family_id;
        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Kardex";
        $page_data['page_name'] = "kardex";
        return view('backend/index', $page_data);
    }
    public function kardex_student($student_id)
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Datos Estudiantes
        $StudentMod = new StudentModel();
        $datos = $StudentMod->activesStudent();
        $page_data['students'] = $datos;

        //Detalle Estudiante
        if ($student_id > 0) {
            $data_search = ["student_id" => $student_id];
            $student_data = $StudentMod->get_student($data_search);
            $page_data['student_info'] = !empty($student_data) ? $student_data[0] : null;
        } else {
            $page_data['student_info'] = null;
        }

        $Setting = new SettingModel();
        $page_data['student_id'] = $student_id;
        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Kardex Estudiante";
        $page_data['page_name'] = "kardex_student";
        return view('backend/index', $page_data);
    }

    public function student_update()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $student_id = $this->request->getPost('student_id');
        $data = [
            'name' => $this->request->getPost('name'),
            'lastname' => $this->request->getPost('lastname'),
            'lastname2' => $this->request->getPost('lastname2'),
            'rude' => $this->request->getPost('rude'),
            'birthday' => $this->request->getPost('birthday'),
            'sex' => $this->request->getPost('sex'),
            'card' => $this->request->getPost('card'),
            'expire_card' => $this->request->getPost('expire_card'),
            'phone' => $this->request->getPost('phone'),
            'cellphone' => $this->request->getPost('cellphone'),
            'personal_email' => $this->request->getPost('personal_email'),
            'email' => $this->request->getPost('email'),
            'address' => $this->request->getPost('address'),
            'reference' => $this->request->getPost('reference'),
            'origin_school' => $this->request->getPost('origin_school'),
            'activo' => $this->request->getPost('activo')
        ];

        $StudentMod = new \App\Models\StudentModel();
        $StudentMod->updateStudent($student_id, $data);

        $session->set('flash_message', 'Datos del estudiante actualizados correctamente');
        return redirect()->to(base_url() . 'secretary/kardex_student/' . $student_id);
    }
    public function family_update()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $family_id = $this->request->getPost('family_id');
        $data = [
            'lastname1' => $this->request->getPost('lastname1'),
            'lastname2' => $this->request->getPost('lastname2'),
            'email1' => $this->request->getPost('email1'),
            'email2' => $this->request->getPost('email2'),
            'home_phone' => $this->request->getPost('home_phone'),
            'contact_cell' => $this->request->getPost('contact_cell'),
            'home_address' => $this->request->getPost('home_address'),
            'neighborhood' => $this->request->getPost('neighborhood'),
            'reference' => $this->request->getPost('reference'),
            'nombre_factura' => $this->request->getPost('nombre_factura'),
            'nit' => $this->request->getPost('nit'),
            'status' => $this->request->getPost('status'),
            'relation_id' => $this->request->getPost('relation_id')
        ];

        $FamilyMod = new FamilyModel();
        $FamilyMod->update_family($data, $family_id);

        $session->set('flash_message', 'Datos de la familia actualizados correctamente');
        return redirect()->to(base_url() . 'secretary/kardex_family/' . $family_id);
    }

    public function kardex_family($family_id)
    {

        $family = new FamilyModel();
        $datos = $family->activesFamily();
        $page_data['familias'] = $datos;

        $Setting = new SettingModel();

        if ($family_id <> 0) {
            //DatosFamilia
            $family = new FamilyModel();
            $data = ["family_id" => $family_id];
            $datos2 = $family->get_family_datas($data);
            $page_data['familia'] = $datos2[0];
            //Hijos
            $students = new StudentModel();
            $datos3 = $students->studentFamily($family_id);
            $page_data['students'] = $datos3;
            //PARENTS
            $ParentMod = new ParentModel();
            $parents = $ParentMod->get_parent_info($family_id);
            $page_data['parents'] = $parents;
            /*
            //Familia
            $data = ["family_id" => $family_id];
            $FamilyMod = new FamilyModel();
            $family = $FamilyMod->get_family_datas($data);
            $page_data['fam'] = $family[0];
            //HIJOS
            $StudentMod = new StudentModel();
            $students = $StudentMod->students_family($family_id);
            $page_data['students'] = $students;
            */


        }
        $session = session();
        $page_data['family_id'] = $family_id;
        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Kardex";
        $page_data['page_name'] = "kardex_family";
        return view('backend/index', $page_data);
    }

    public function kardex_student_pdf($student_id)
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        // Datos Estudiante
        $StudentMod = new StudentModel();
        $data_search = ["student_id" => $student_id];
        $student_data = $StudentMod->get_student($data_search);
        if (empty($student_data))
            return redirect()->to(base_url());
        $student = $student_data[0];

        require('fpdf184/fpdf.php');
        $pdf = new \FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();

        // Header
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('KARDEX DE ESTUDIANTE'), 0, 1, 'C');
        $pdf->Ln(5);

        // Student Info Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 8, utf8_decode('Información Personal'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(40, 7, 'Nombre Completo:', 0, 0);
        $pdf->Cell(0, 7, utf8_decode($student['name'] . ' ' . $student['lastname'] . ' ' . $student['lastname2']), 0, 1);

        $pdf->Cell(40, 7, 'RUDE:', 0, 0);
        $pdf->Cell(0, 7, $student['rude'] ?: 'N/A', 0, 1);

        $pdf->Cell(40, 7, utf8_decode('C.I.:'), 0, 0);
        $pdf->Cell(0, 7, $student['card'] ?: 'N/A', 0, 1);

        $pdf->Cell(40, 7, 'Fecha Nacimiento:', 0, 0);
        $pdf->Cell(0, 7, $student['birthday'] ?: 'N/A', 0, 1);

        $pdf->Cell(40, 7, utf8_decode('Género:'), 0, 0);
        $pdf->Cell(0, 7, ($student['sex'] == 'M' ? 'Masculino' : 'Femenino'), 0, 1);

        $pdf->Ln(5);

        // Contact Info Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Contacto y Ubicación'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(40, 7, utf8_decode('Teléfonos:'), 0, 0);
        $pdf->Cell(0, 7, ($student['phone'] ?: 'N/A') . ' / ' . ($student['cellphone'] ?: 'N/A'), 0, 1);

        $pdf->Cell(40, 7, 'Email Personal:', 0, 0);
        $pdf->Cell(0, 7, $student['personal_email'] ?: 'N/A', 0, 1);

        $pdf->Cell(40, 7, 'Email Institucional:', 0, 0);
        $pdf->Cell(0, 7, $student['email'] ?: 'N/A', 0, 1);

        $pdf->Cell(40, 7, utf8_decode('Dirección:'), 0, 0);
        $pdf->MultiCell(0, 7, utf8_decode($student['address'] ?: 'N/A'), 0);

        $pdf->Cell(40, 7, 'Referencia:', 0, 0);
        $pdf->MultiCell(0, 7, utf8_decode($student['reference'] ?: 'N/A'), 0);

        $pdf->Ln(5);

        // Academic Info Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Información Académica'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(40, 7, 'Colegio Origen:', 0, 0);
        $pdf->Cell(0, 7, utf8_decode($student['origin_school'] ?: 'N/A'), 0, 1);

        $pdf->Cell(40, 7, 'Estado:', 0, 0);
        $pdf->Cell(0, 7, ($student['activo'] == 1 ? 'Activo' : 'Inactivo'), 0, 1);

        $pdf->Output('I', 'kardex_estudiante_' . $student_id . '.pdf');
        exit;
    }

    public function kardex_family_pdf($family_id)
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        // Datos Familia
        $familyMod = new FamilyModel();
        $data = ["family_id" => $family_id];
        $family_data = $familyMod->get_family_datas($data);
        if (empty($family_data))
            return redirect()->to(base_url());
        $familia = $family_data[0];

        // Hijos
        $studentMod = new StudentModel();
        $students = $studentMod->studentFamily($family_id);

        // Padres
        $parentMod = new ParentModel();
        $parents = $parentMod->get_parent_info($family_id);

        require('fpdf184/fpdf.php');
        $pdf = new \FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();

        // Header
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode('KARDEX DE FAMILIA'), 0, 1, 'C');
        $pdf->Ln(5);

        // Family Info Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(0, 8, utf8_decode('Información de la Familia'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(40, 7, 'Apellidos:', 0, 0);
        $pdf->Cell(0, 7, utf8_decode($familia['lastname1'] . ' ' . $familia['lastname2']), 0, 1);
        $pdf->Cell(40, 7, utf8_decode('Relación:'), 0, 0);
        $pdf->Cell(0, 7, utf8_decode($familia['relation'] ?: 'N/A'), 0, 1);
        $pdf->Cell(40, 7, 'Estado:', 0, 0);
        $pdf->Cell(0, 7, ($familia['status'] == 1 ? 'Activo' : 'Inactivo'), 0, 1);
        $pdf->Ln(5);

        // Contact Info Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Contacto y Ubicación'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(40, 7, 'Correo Principal:', 0, 0);
        $pdf->Cell(0, 7, $familia['email1'], 0, 1);
        $pdf->Cell(40, 7, 'Correo Secundario:', 0, 0);
        $pdf->Cell(0, 7, $familia['email2'] ?: 'N/A', 0, 1);
        $pdf->Cell(40, 7, utf8_decode('Teléfonos:'), 0, 0);
        $pdf->Cell(0, 7, ($familia['home_phone'] ?: 'N/A') . ' / ' . ($familia['contact_cell'] ?: 'N/A'), 0, 1);
        $pdf->Cell(40, 7, utf8_decode('Dirección:'), 0, 0);
        $pdf->MultiCell(0, 7, utf8_decode($familia['home_address'] ?: 'N/A'), 0);
        $pdf->Cell(40, 7, 'Barrio/Zona:', 0, 0);
        $pdf->Cell(0, 7, utf8_decode($familia['neighborhood'] ?: 'N/A'), 0, 1);
        $pdf->Ln(5);

        // Parent Data Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Datos de los Padres'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(70, 7, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Parentesco', 1, 0, 'C', true);
        $pdf->Cell(40, 7, utf8_decode('Profesión'), 1, 0, 'C', true);
        $pdf->Cell(25, 7, 'Celular', 1, 0, 'C', true);
        $pdf->Cell(31, 7, 'Email', 1, 1, 'C', true);
        $pdf->SetFont('Arial', '', 8);
        foreach ($parents as $p) {
            $pdf->Cell(70, 7, utf8_decode($p['nombre']), 1);
            $pdf->Cell(30, 7, utf8_decode($p['relationship']), 1, 0, 'C');
            $pdf->Cell(40, 7, utf8_decode($p['profession'] ?: 'N/A'), 1);
            $pdf->Cell(25, 7, $p['cellphone'] ?: 'N/A', 1, 0, 'C');
            $pdf->Cell(31, 7, $p['email'] ?: 'N/A', 1, 1);
        }
        $pdf->Ln(5);

        // Billing Info Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Información de Facturación'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(40, 7, 'Nombre Factura:', 0, 0);
        $pdf->Cell(0, 7, utf8_decode($familia['nombre_factura'] ?: 'N/A'), 0, 1);
        $pdf->Cell(40, 7, 'NIT:', 0, 0);
        $pdf->Cell(0, 7, $familia['nit'] ?: 'N/A', 0, 1);
        $pdf->Ln(5);

        // Students Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, utf8_decode('Estudiantes Registrados'), 1, 1, 'L', true);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(100, 7, 'Estudiante', 1, 0, 'C', true);
        $pdf->Cell(40, 7, utf8_decode('Código'), 1, 0, 'C', true);
        $pdf->Cell(40, 7, 'Estado', 1, 1, 'C', true);
        $pdf->SetFont('Arial', '', 10);
        foreach ($students as $student) {
            $pdf->Cell(100, 7, utf8_decode($student->lastname . ' ' . $student->lastname2 . ' ' . $student->name), 1);
            $pdf->Cell(40, 7, $student->code, 1, 0, 'C');
            $pdf->Cell(40, 7, ($student->activo == 1 ? 'Activo' : 'Inactivo'), 1, 1, 'C');
        }

        $pdf->Output('I', 'kardex_familia_' . $family_id . '.pdf');
        exit;
    }
    public function applicant($family_id)
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $family = new FamilyModel();
        $datos = $family->activesFamily();
        $page_data['familias'] = $datos;

        $Setting = new SettingModel();
        $page_data['family_id'] = $family_id;
        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Solicitud Aspirantes";
        $page_data['page_name'] = "applicant";
        return view('backend/index', $page_data);
    }
    function student_search($user = '', $sel = '')
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
    /*******************CURSOS ************************/
    function sections()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //Section
        $Section = new SectionModel();
        $cursos = $Section->section_secretary($secretary_id);
        $page_data['sections'] = $cursos;
        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'sections';
        $page_data['page_title'] = 'Cursos';
        return view('backend/index', $page_data);
    }
    function section_rudes($section_id)
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Section
        $data = ["section_id" => $section_id];
        $Section = new SectionModel();
        $curso = $Section->get_section($data);
        $page_data['section_id'] = $curso[0]['section_id'];
        $page_data['curso'] = $curso[0]['completo'];
        //Estudiantes del curso
        $StudentMod = new StudentModel();
        $page_data['students'] = $StudentMod->studentsSection($section_id, 0);

        //Settings
        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'section_rudes';
        $page_data['page_title'] = 'Planilla de Rudes';
        return view('backend/index', $page_data);
    }
    /***************CONSEJEROS******************/
    function counselors()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Teachers para seleccionar
        $data = ["active" => '1'];
        $teacherMod = new TeacherModel();
        $teachers = $teacherMod->get_teacher($data);
        $page_data['teachers'] = $teachers;
        //Cursos
        $sectionMod = new SectionModel();
        $cursos = $sectionMod->section_secretary($secretary_id);
        $page_data['cursos'] = $cursos;
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //Vista
        $page_data['page_name'] = 'counselors';
        $page_data['page_title'] = 'Consejerias';
        return view('backend/index', $page_data);
    }
    function counselors_update($section_id = '', $teacher_id = '')
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //Verificamos que el curso no sea 0
        if ($section_id != 0) {
            $sectionMod = new SectionModel();
            $data['teacher_id'] = $teacher_id;
            $respuesta = $sectionMod->update_section($data, $section_id);
            $session->set('flash_message', 'Se cambio de consejería Correctamente');
        }
        return redirect()->to(base_url() . 'secretary/counselors');
    }
    /****************************************ASSISTENCE ***************************************/
    function assistance()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //$Subject = new SubjectModel();

        $Section = new SectionModel();
        $cursos = $Section->section_secretary($secretary_id);
        //$subjects = $Subject->subjects_secretary($session->get('teacher_id'));

        //$page_data['teacher_id'] = $this->session->userdata('teacher_id');
        $Setting = new SettingModel();

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['cursos'] = $cursos;
        //$page_data['subjects']  = $subjects;
        $page_data['page_name'] = 'assistance';
        $page_data['page_title'] = 'Asistencias';
        return view('backend/index', $page_data);
    }

    public function attendance_reports()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Estudiantes
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_secretary($secretary_id);
        $page_data['students'] = $students;

        $Section = new SectionModel();
        $cursos = $Section->section_secretary($secretary_id);
        //$subjects = $Subject->subjects_secretary($session->get('teacher_id'));

        //$page_data['teacher_id'] = $this->session->userdata('teacher_id');
        $Setting = new SettingModel();

        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['cursos'] = $cursos;
        //$page_data['subjects']  = $subjects;
        $page_data['page_name'] = 'attendance_reports';
        $page_data['page_title'] = 'Reporte de Asistencias';
        return view('backend/index', $page_data);
    }
    /**********************************MEDIOS DE COMUNICACION ***********************/
    public function medios()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $Medio = new MedioModel();
        $datos = $Medio->listarMedios();

        $page_data = ["datos" => $datos];

        $Setting = new SettingModel();
        $mensaje = session('mensaje');
        $page_data['mensaje'] = $mensaje;
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Medios";
        $page_data['page_name'] = "medio";
        return view('backend/index', $page_data);
    }
    public function medio_create()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $datos = ["medio" => $_POST['medioComunicacion'],];
        $Medio = new MedioModel();
        $respuesta = $Medio->insertMedio($datos);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó el medio de comunicación Correctamente');
            return redirect()->to(base_url() . '/secretary/medio');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/medio');
        }
    }
    public function medio_get($medio_id)
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $data = ["medio_id" => $medio_id];
        $Medio = new MedioModel();
        $respuesta = $Medio->getMedio($data);
        return $respuesta[0]['medio'];
        //return print_r($respuesta);
    }
    public function medio_update()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $datos = [
            "medio" => $_POST['medioComunicacion'],
        ];
        $medio_id = $_POST['medioId'];
        $Medio = new MedioModel();
        $respuesta = $Medio->updateMedio($datos, $medio_id);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Medio de Comunicacion actualizado Correctamente');
            return redirect()->to(base_url() . 'secretary/medio');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/medio');
        }
    }
    public function medio_delete()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $medio_id = $_POST['medioId'];
        $Medio = new MedioModel();
        $data = ["medio_id" => $medio_id];
        $respuesta = $Medio->deleteMedio($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se elimino el medio de comunicación Correctamente');
            return redirect()->to(base_url() . 'secretary/medio');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/medio');
        }
    }
    /****************************************LICENCIAS...********************* */
    function licenses()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Cursos
        $SecretaryMod = new SecretaryModel();
        $sections = $SecretaryMod->get_sections_by_secretary_id($secretary_id);
        //Licencias
        if ($sections) {
            $LicenciaMod = new LicenciaModel();
            $licencias = $LicenciaMod->vistaLicencias($sections['section_ini'], $sections['section_fin']);
            $page_data['licencias'] = $licencias;
        } else {
            $LicenciaMod = new LicenciaModel();
            $licencias = $LicenciaMod->vistaLicencias2($secretary_id);
            $page_data['licencias'] = $licencias;
        }

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //$page_data['cursos']  = $cursos;
        //$page_data['subjects']  = $subjects;
        $page_data['page_name'] = 'licenses';
        $page_data['page_title'] = 'Licencias';
        return view('backend/index', $page_data);
    }
    function licenses_all()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'licenses_all';
        $page_data['page_title'] = 'Todas las Licencias';
        return view('backend/index', $page_data);
    }

    function licenses_all_data()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary') {
            return $this->response->setJSON(['error' => 'unauthorized'])->setStatusCode(403);
        }

        $request = $this->request;
        $draw    = (int)$request->getGet('draw');
        $start   = (int)$request->getGet('start');
        $length  = (int)$request->getGet('length');
        $search  = $request->getGet('search')['value'] ?? '';
        $order   = $request->getGet('order')[0] ?? ['column' => 4, 'dir' => 'desc'];
        $order_col = (int)$order['column'];
        $order_dir = $order['dir'];

        $SecretaryMod = new SecretaryModel();
        $sections = $SecretaryMod->get_sections_by_secretary_id($secretary_id);

        $LicenciaMod = new LicenciaModel();
        if ($sections) {
            $result = $LicenciaMod->licencias_todas_data(
                $sections['section_ini'], $sections['section_fin'], null,
                $search, $start, $length, $order_col, $order_dir
            );
        } else {
            $result = $LicenciaMod->licencias_todas_data(
                null, null, $secretary_id,
                $search, $start, $length, $order_col, $order_dir
            );
        }

        $licencias = $result['data'];
        foreach ($licencias as &$licencia) {
            $filePattern = 'uploads/comprobantes_medicos/comprobante_' . $licencia->licencias_id . '.*';
            $files = glob($filePattern);
            if (!empty($files)) {
                $licencia->documento = basename($files[0]);
            } else {
                $licencia->documento = null;
            }
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data'            => $licencias,
        ]);
    }
    function licenses_received()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Cursos
        $SecretaryMod = new SecretaryModel();
        $sections = $SecretaryMod->get_sections_by_secretary_id($secretary_id);
        //Licencias
        $LicenciaMod = new LicenciaModel();
        $licencias = $LicenciaMod->licencias_auth($sections['section_ini'], $sections['section_fin']);

        // Añadir columna 'documento' con el nombre del archivo si existe
        foreach ($licencias as &$licencia) {
            $filePattern = 'uploads/comprobantes_medicos/comprobante_' . $licencia->licencias_id . '.*';
            $files = glob($filePattern);

            if (!empty($files)) {
                $licencia->documento = basename($files[0]);
            } else {
                $licencia->documento = null; // o cualquier valor que prefieras cuando no exista el archivo
            }
        }
        unset($licencia); // Rompe la referencia con el último elemento
        $page_data['licencias'] = $licencias;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //$page_data['cursos']  = $cursos;
        //$page_data['subjects']  = $subjects;
        $page_data['page_name'] = 'licenses_received';
        $page_data['page_title'] = 'Licencias';
        return view('backend/index', $page_data);
    }
    public function licenses_auth()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        $emailSecre = $session->get('email');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //Licencia
        $licencia_id = $_POST['licenciaId'];
        $LicenciaMod = new LicenciaModel();
        $licencia = $LicenciaMod->getLicencia($licencia_id);
        //Enviamos la ausencia
        $FamilyMod = new FamilyModel();
        $family = $FamilyMod->get_family_emails($_POST['student_id']);
        $email1 = $family[0]['email1'];
        $email2 = $family[0]['email2'];
        //Cursos
        $sectionMod = new SectionModel();
        $emailsSection = $sectionMod->section_emails($family[0]['section_id']);
        $emailConsejero = $emailsSection[0]['emailDocente'];
        $emailDirector = $emailsSection[0]['emailDirector'];
        $subject = 'Id:' . $licencia_id . ' - ' . $licencia[0]['motivo'] . ' U. E. Tiquipaya';
        if ($licencia[0]['tipo_id'] == '2') {
            $inicio = $licencia[0]['fecha_periodo'] ?? '';
            $fin    = $licencia[0]['periodos_nombre'] ?? '';
        } else {
            $inicio = $licencia[0]['fecha_inicio'] ?? '';
            $fin    = $licencia[0]['fecha_fin'] ?? '';
        }
        //Enviamos Email
        $EmailMod = new EmailModel();
        $mensaje = $EmailMod->license_auth_email($licencia[0]['student'], $licencia[0]['tipo_id'], $inicio, $fin, $licencia[0]['detalle'], $licencia[0]['motivo'], $licencia[0]['solicitante'], $licencia[0]['fecha_solicitud']);
        $email = \Config\Services::email();
        $email->setFrom('saat@tiquipaya.edu.bo', 'Saat Tiquipaya');
        //$email->setTo('franz.condori.calderon@gmail.com');
        $to = '';
        
        if (isset($email2)) {
            $to = $email1.', '.$email2;
        }else{
            $email->setTo($email1);
            $to = $email1;
        }
        

        $to = $to.', '.$emailConsejero.', '.$emailSecre.', '.'saat@tiquipaya.edu.bo';
        //$to = $to . 'franz.condori.calderon@gmail.com, etorrico@tiquipaya.edu.bo';

        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        //$headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
        $headers[] = 'From: Secretaria <' . $emailSecre . '>';
        $mailSent = @mail($to, $subject, $mensaje, implode("\r\n", $headers));

        //Actualizamos DB solo si se envió el correo
        if ($mailSent) {
            $datos = ["enviado" => True];
            $LicenciaMod = new LicenciaModel();
            $LicenciaMod->updateLicencia($datos, $licencia_id);
        }

        $session->set('flash_message', 'Licencia autorizada Correctamente');
        return redirect()->to(base_url() . 'secretary/licenses_received');
    }
    public function licenses_noauth()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        $emailSecre = $session->get('email');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //Licencia
        $licencia_id = $_POST['licenciaId'];
        $LicenciaMod = new LicenciaModel();
        $licencia = $LicenciaMod->getLicencia($licencia_id);
        //Enviamos la ausencia
        $FamilyMod = new FamilyModel();
        $family = $FamilyMod->get_family_emails($_POST['student_id']);
        $email1 = $family[0]['email1'];
        $email2 = $family[0]['email2'];
        //Cursos
        $sectionMod = new SectionModel();
        $emailsSection = $sectionMod->section_emails($family[0]['section_id']);
        $emailConsejero = $emailsSection[0]['emailDocente'];
        $emailDirector = $emailsSection[0]['emailDirector'];
        $subject = 'Id:' . $licencia_id . ' - ' . $licencia[0]['motivo'] . ' U. E. Tiquipaya';
        if ($licencia[0]['tipo_id'] == '2') {
            $inicio = $licencia[0]['fecha_periodo'] ?? '';
            $fin    = $licencia[0]['periodos_nombre'] ?? '';
        } else {
            $inicio = $licencia[0]['fecha_inicio'] ?? '';
            $fin    = $licencia[0]['fecha_fin'] ?? '';
        }
        //Enviamos Email
        $EmailMod = new EmailModel();
        $mensaje = $EmailMod->license_noauth_email($licencia[0]['student'], $licencia[0]['tipo_id'], $inicio, $fin, $licencia[0]['detalle'], $licencia[0]['motivo'], $licencia[0]['solicitante'], $licencia[0]['fecha_solicitud']);
        $email = \Config\Services::email();
        $email->setFrom('saat@tiquipaya.edu.bo', 'Saat Tiquipaya');
        //$email->setTo('franz.condori.calderon@gmail.com');
        $to = '';
        if (isset($email2)) {
            $to = $email1.', '.$email2;
        }else{
            $email->setTo($email1);
            $to = $email1;
        }

        $to = $to.', '.$emailConsejero.', '.$emailSecre.', '.'saat@tiquipaya.edu.bo';
        //$to = $to . 'franz.condori.calderon@gmail.com, etorrico@tiquipaya.edu.bo';

        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        //$headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
        $headers[] = 'From: Secretaria <' . $emailSecre . '>';
        $mailSent = @mail($to, $subject, $mensaje, implode("\r\n", $headers));

        //Revertir asistencias a Ausente (0)
        $student_id = $licencia[0]['student_id'];
        $AssisSubMod = new AssistancesubjectModel();
        if ($licencia[0]['tipo_id'] == '1') {
            // Licencia por dias: ausente en todo el rango de fechas
            $AssisSubMod->noauth_licencia_dia($student_id, $licencia[0]['fecha_inicio'], $licencia[0]['fecha_fin']);
        } else {
            // Licencia por horas: ausente solo en los periodos especificos
            $LicPeriodoMod = new LicenciaperiodoModel();
            $periodos = $LicPeriodoMod->get_licencia_periodo(['licencias_id' => $licencia_id]);
            foreach ($periodos as $periodo) {
                $AssisSubMod->noauth_licencia_periodo($student_id, $periodo['fecha'], $periodo['periodo_id']);
            }
        }

        //Actualizamos DB solo si se envió el correo
        if ($mailSent) {
            $datos = ["enviado" => True];
            $LicenciaMod = new LicenciaModel();
            $LicenciaMod->updateLicencia($datos, $licencia_id);
        }

        $session->set('flash_message', 'Licencia no autorizada. Asistencias actualizadas a Ausente');
        return redirect()->to(base_url() . 'secretary/licenses_received');
    }
    function licenses_add()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //$Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_secretary($secretary_id);
        $page_data['students'] = $students;
        //Assistance_obs
        $MotivoMod = new MotivoModel();
        $motivos = $MotivoMod->listarMotivos();
        $page_data['motivos'] = $motivos;
        //Medios
        $MedioMod = new MedioModel();
        $medios = $MedioMod->listarMedios();
        $page_data['medios'] = $medios;
        //Parentesco
        $Parentesco = new ParentescoModel();
        $parentescos = $Parentesco->listarParentescos();
        $page_data['parentescos'] = $parentescos;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'licenses_add';
        $page_data['page_title'] = 'Nueva Licencias';
        return view('backend/index', $page_data);
    }

    function licenses_periodo_add()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $StudentMod = new StudentModel();
        $page_data['students'] = $StudentMod->student_secretary($secretary_id);

        $MotivoMod = new MotivoModel();
        $page_data['motivos'] = $MotivoMod->listarMotivos();

        $MedioMod = new MedioModel();
        $page_data['medios'] = $MedioMod->listarMedios();

        $Parentesco = new ParentescoModel();
        $page_data['parentescos'] = $Parentesco->listarParentescos();

        $Setting = new SettingModel();
        $page_data['phase_id']     = $Setting->get_phase_id();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['page_name']    = 'licenses_periodo_add';
        $page_data['page_title']   = 'Nueva Licencia por Período';
        return view('backend/index', $page_data);
    }

    public function licencias_periodo_create()
    {
        date_default_timezone_set('America/La_Paz');
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $periodos = $_POST['periodos'] ?? [];
        if (empty($periodos)) {
            $session->set('flash_message_error', 'Debe seleccionar al menos un período.');
            return redirect()->to(base_url() . 'secretary/licenses_periodo_add');
        }

        $fecha_solicitud = date("Y-m-d H:i:s", strtotime(str_replace('T', ' ', $_POST['fechaSolicita']) . ':00'));
        $fecha           = date("Y-m-d", strtotime($_POST['fecha']));
        $hora_salida     = !empty($_POST['hora_salida']) ? $_POST['hora_salida'] : null;

        $datos = [
            "student_id"      => $_POST['student_id'],
            "tipo_id"         => 2,
            "fecha_solicitud" => $fecha_solicitud,
            "solicitante"     => $_POST['solicitante'],
            "parentesco_id"   => $_POST['parentesco'],
            "motivo_id"       => $_POST['motivo'],
            "detalle"         => $_POST['detalle'],
            "medio_id"        => $_POST['medio'],
            "hora_salida"     => $hora_salida,
            "enviado"         => false,
        ];

        $Licencia     = new LicenciaModel();
        $licencias_id = $Licencia->insertLicencia($datos);

        if ($licencias_id) {
            $db_asis = db_connect('asistencia');
            foreach ($periodos as $periodo_id) {
                $db_asis->table('t_licencias_periodo')->insert([
                    "licencias_id" => $licencias_id,
                    "fecha"        => $fecha,
                    "periodo_id"   => (int)$periodo_id,
                ]);
            }
            $session->set('flash_message', 'Licencia por período registrada correctamente.');
        } else {
            $session->set('flash_message_error', 'Error al registrar la licencia.');
        }

        return redirect()->to(base_url() . 'secretary/licenses');
    }

    public function licencias_create()
    {
        date_default_timezone_set('America/La_Paz');
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //Verificamos Ausentes en Materias con Licencia
        $student_id = $_POST['student_id'];
        $fecha_ini = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];

        $AssistancesubjectMod = new AssistancesubjectModel();
        $ausencias = $AssistancesubjectMod->assis_licencia($student_id, $fecha_ini, $fecha_fin);

        //FECHA SOLICITUD
        $fecha = str_replace('T', ' ', $_POST['fechaSolicita']) . ":00";
        $fecha_php = date("Y-m-d H:i:s", strtotime($fecha));
        $fecha_solicitud = $fecha_php;

        $datos = [
            "student_id"    => $_POST['student_id'],
            "tipo_id"       => 1,
            "fecha_solicitud" => $fecha_solicitud,
            "solicitante"   => $_POST['solicitante'],
            "parentesco_id" => $_POST['parentesco'],
            "motivo_id"     => $_POST['motivo'],
            "detalle"       => $_POST['detalle'],
            "medio_id"      => $_POST['medio'],
            "enviado"       => false,
        ];

        $Licencia = new LicenciaModel();
        $licencias_id = $Licencia->insertLicencia($datos);

        if ($licencias_id) {
            $inicio = date("Y-m-d", strtotime($_POST['fecha_inicio']));
            $fin    = date("Y-m-d", strtotime($_POST['fecha_fin']));
            db_connect('asistencia')->table('t_licencias_dia')->insert([
                "licencias_id"  => $licencias_id,
                "fecha_inicio"  => $inicio,
                "fecha_fin"     => $fin,
                "cantidad_dias" => $_POST['cantidad'] ?? 1,
            ]);
        }
        $respuesta = $licencias_id ? 1 : 0;
        if ($respuesta > 0) {
            //Estudiante
            if (count($ausencias) > 0) {
                $page_data['ausencias'] = $ausencias;
                $StudentMod = new StudentModel();
                $students = $StudentMod->datosStudent($_POST['student_id']);
                $page_data['student_name'] = $students[0]->nombre;
                $Setting = new SettingModel();
                $page_data['phase_id'] = $Setting->get_phase_id();
                $page_data['phase_name'] = $Setting->get_phase_name();
                $page_data['system_title'] = $Setting->get_system_title();
                $page_data['system_name'] = $Setting->get_system_name();
                //$page_data['cursos']  = $cursos;
                //$page_data['subjects']  = $subjects;
                $page_data['page_name'] = 'licenses_report_absence';
                $page_data['page_title'] = 'Ausencias Encontradas';
                return view('backend/index', $page_data);
            } else {

                $session->set('flash_message', 'Se guardó la licencia Correctamente ');
                return redirect()->to(base_url() . 'secretary/licenses');
            }

        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/licenses');
        }

    }
    public function licencia_get($licencia_id)
    {
        $Licencia = new LicenciaModel();
        $respuesta = $Licencia->getLicencia($licencia_id);
        //return $respuesta[0]['nick_name'].' - '.$respuesta[0]['student'].' - '.$respuesta[0]['detalle'];
        return $this->response->setJSON($respuesta[0]);
    }
    public function licenses_report_teacher()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        foreach ($_POST['assistance_subject_id'] as $value) {
            $AssistancesubjectMod = new AssistancesubjectModel();
            $datos = [
                "status" => 2,
                "indiscipline" => "Modificado por Secretaria",
            ];
            $respuesta = $AssistancesubjectMod->update_assistance_subject($datos, $value);
        }
        if ($respuesta > 0) {
            return redirect()->to(base_url() . 'secretary/licenses');
        } else {
            return redirect()->to(base_url() . 'secretary/licenses');
        }
    }
    public function licencia_delete()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $licencia_id = $_POST['licenciaId'];
        $Licencia = new LicenciaModel();
        $data = ["licencias_id" => $licencia_id];
        $respuesta = $Licencia->deleteLicencia($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'La licencia fue eliminada correctamente.');
        } else {
            $session->set('flash_message_error', 'No se pudo eliminar la licencia.');
        }
        return redirect()->to(base_url() . 'secretary/licenses');
    }
    public function licenses_edit($licencia_id = "")
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_secretary($secretary_id);
        $page_data['students'] = $students;
        //Assistance_obs
        $MotivoMod = new MotivoModel();
        $motivos = $MotivoMod->listarMotivos();
        $page_data['motivos'] = $motivos;
        //Medios
        $MedioMod = new MedioModel();
        $medios = $MedioMod->listarMedios();
        $page_data['medios'] = $medios;
        //Parentesco
        $Parentesco = new ParentescoModel();
        $parentescos = $Parentesco->listarParentescos();
        $page_data['parentescos'] = $parentescos;
        //Licencia
        $LicenciaMod = new LicenciaModel();
        $licencia = $LicenciaMod->getLicencia($licencia_id);
        $page_data['licencia'] = $licencia;
        // IDs de períodos ya seleccionados (para pre-marcar checkboxes en tipo=2)
        $db_asis = db_connect('asistencia');
        $page_data['periodos_ids'] = array_column(
            $db_asis->table('t_licencias_periodo')
                    ->select('periodo_id')
                    ->where('licencias_id', $licencia_id)
                    ->get()->getResultArray(),
            'periodo_id'
        );

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Editar Licencia";
        $page_data['page_name'] = "licenses_edit";
        return view('backend/index', $page_data);
    }
    public function licenses_update()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $licencia_id = $_POST['licencia_id'];
        $tipo_id     = (int)($_POST['tipo'] ?? 1);

        $hora_salida = !empty($_POST['hora_salida']) ? $_POST['hora_salida'] : null;

        $datos = [
            "tipo_id"         => $tipo_id,
            "fecha_solicitud" => $_POST['fechaSolicita'],
            "solicitante"     => $_POST['solicitante'],
            "parentesco_id"   => $_POST['parentesco'],
            "motivo_id"       => $_POST['motivo'],
            "detalle"         => $_POST['detalle'],
            "medio_id"        => $_POST['medio'],
            "hora_salida"     => $hora_salida,
        ];
        $Licencia = new LicenciaModel();
        $Licencia->updateLicencia($datos, $licencia_id);

        $db = db_connect('asistencia');

        if ($tipo_id == 2) {
            // Cambió a Período: eliminar subtabla día si existía
            $db->table('t_licencias_dia')->where('licencias_id', $licencia_id)->delete();
            // Eliminar períodos anteriores y reinsertar los nuevos
            $db->table('t_licencias_periodo')->where('licencias_id', $licencia_id)->delete();
            $periodos = $_POST['periodos'] ?? [];
            $fecha    = $_POST['fecha_periodo'] ?? date('Y-m-d');
            foreach ($periodos as $periodo_id) {
                $db->table('t_licencias_periodo')->insert([
                    'licencias_id' => $licencia_id,
                    'fecha'        => $fecha,
                    'periodo_id'   => (int)$periodo_id,
                ]);
            }
        } else {
            // Cambió a Días: eliminar subtabla período si existía
            $db->table('t_licencias_periodo')->where('licencias_id', $licencia_id)->delete();
            // Actualizar o insertar en t_licencias_dia
            $fecha_inicio  = date("Y-m-d", strtotime($_POST['fecha_inicio']));
            $fecha_fin     = date("Y-m-d", strtotime($_POST['fecha_fin']));
            $cantidad_dias = $_POST['cantidad'] ?? 1;
            $existing = $db->table('t_licencias_dia')->where('licencias_id', $licencia_id)->get()->getRow();
            if ($existing) {
                $db->table('t_licencias_dia')->where('licencias_id', $licencia_id)->update([
                    'fecha_inicio'  => $fecha_inicio,
                    'fecha_fin'     => $fecha_fin,
                    'cantidad_dias' => $cantidad_dias,
                ]);
            } else {
                $db->table('t_licencias_dia')->insert([
                    'licencias_id'  => $licencia_id,
                    'fecha_inicio'  => $fecha_inicio,
                    'fecha_fin'     => $fecha_fin,
                    'cantidad_dias' => $cantidad_dias,
                ]);
            }
        }

        $session->set('flash_message', 'Licencia actualizada Correctamente');
        return redirect()->to(base_url() . 'secretary/licenses');
    }
    public function license_report($licencia_id = "")
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //Datos de Licencia
        $LicenciaMod = new LicenciaModel();
        $respuesta = $LicenciaMod->getLicencia($licencia_id);
        $lic = $respuesta[0];
        require('fpdf184/fpdf.php');//to be done in your controller
        // Set a filename
        $filename = 'lic_' . $licencia_id . '.pdf';
        $pdf = new \FPDF('P', 'mm', array(108, 139));
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        //$pdf->Cell(5);
        $pdf->Cell(88, 10, utf8_decode('Autorización de Ingreso / Salida'), 0, 0, 'C');
        $pdf->Ln(10);

        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 5, 'F. solicitud :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, $lic['fecha_solicitud'], 1, 0, 'L', 0);
        $pdf->Ln(5);
        if ($lic['tipo_id'] == 1) {
            $pdf->Cell(25, 5, 'Fecha Inicio :', 1, 0, 'R', 1);
            $pdf->Cell(68, 5, $lic['fecha_inicio'] ?? '', 1, 0, 'L', 0);
            $pdf->Ln(5);
            $pdf->Cell(25, 5, 'Fecha Fin :', 1, 0, 'R', 1);
            $pdf->Cell(68, 5, $lic['fecha_fin'] ?? '', 1, 0, 'L', 0);
            $pdf->Ln(5);
        } else {
            $pdf->Cell(25, 5, 'Fecha :', 1, 0, 'R', 1);
            $pdf->Cell(68, 5, $lic['fecha_periodo'] ?? '', 1, 0, 'L', 0);
            $pdf->Ln(5);
            $pdf->Cell(25, 5, 'Periodo(s) :', 1, 0, 'R', 1);
            $pdf->Cell(68, 5, utf8_decode($lic['periodos_nombre'] ?? ''), 1, 0, 'L', 0);
            $pdf->Ln(5);
            if (!empty($lic['hora_salida'])) {
                $pdf->Cell(25, 5, 'Hora Salida :', 1, 0, 'R', 1);
                $pdf->Cell(68, 5, substr($lic['hora_salida'], 0, 5), 1, 0, 'L', 0);
                $pdf->Ln(5);
            }
        }

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 5, 'Alumno :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, $lic['student'], 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Curso :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, $lic['completo'], 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Medio Solicitado :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, utf8_decode($lic['medio']), 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Solicitante :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, utf8_decode($lic['solicitante']), 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 10, 'Detalle :', 1, 0, 'R', 1);
        $pdf->MultiCell(68, 5, utf8_decode($lic['motivo'] . ":\n" . $lic['detalle']), 1);
        $pdf->Ln(25);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 5, '----------------------------', 0, 0, 'C');
        $pdf->Cell(10);
        $pdf->Cell(35, 5, '----------------------------', 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 5, utf8_decode('Secretaria Dir. Técnica'), 0, 0, 'C');
        $pdf->Cell(10);
        $pdf->Cell(35, 5, utf8_decode('Dirección Técnica'), 0, 0, 'C');
        $pdf->Ln(10);
        $pdf->Output();
        $this->response->setHeader('Content-Type', 'application/pdf');

    }
    public function license_send($licencia_id, $student_id)
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        $emailSecre = $session->get('email');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Licencia
        $LicenciaMod = new LicenciaModel();
        $licencia = $LicenciaMod->getLicencia($licencia_id);
        //Enviamos la ausencia
        $FamilyMod = new FamilyModel();
        $family = $FamilyMod->get_family_emails($student_id);
        $email1 = $family[0]['email1'];
        $email2 = $family[0]['email2'];
        //Cursos
        $sectionMod = new SectionModel();
        $emailsSection = $sectionMod->section_emails($family[0]['section_id']);
        $emailConsejero = $emailsSection[0]['emailDocente'];
        $emailDirector = $emailsSection[0]['emailDirector'];
        $subject = 'Id:' . $licencia_id . ' - ' . $licencia[0]['motivo'] . ' U. E. Tiquipaya';
        if ($licencia[0]['tipo_id'] == '2') {
            $inicio = $licencia[0]['fecha_periodo'] ?? '';
            $fin    = $licencia[0]['periodos_nombre'] ?? '';
        } else {
            $inicio = $licencia[0]['fecha_inicio'] ?? '';
            $fin    = $licencia[0]['fecha_fin'] ?? '';
        }
        //Enviamos Email
        $EmailMod = new EmailModel();
        $mensaje = $EmailMod->assistance_email($licencia[0]['student'], $licencia[0]['tipo_id'], $inicio, $fin, $licencia[0]['detalle'], $licencia[0]['motivo']);
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

        $to = $to . ', ' . $emailConsejero . ', ' . $emailSecre . ', ' . 'saat@tiquipaya.edu.bo';
        //$to = $to . ', ' . 'saat@tiquipaya.edu.bo';
        //$subject = 'Id:'.$licencia_id.' - Licencia U. E. Tiquipaya';
        //$headers  = "MIME-Version: 1.0" . "\r\n";
        //$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n"; 

        // To send HTML mail, the Content-type header must be set
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // Additional headers
        //$headers[] = 'To: Mary <mary@example.com>, Kelly <kelly@example.com>';
        $headers[] = 'From: Secretaria <' . $emailSecre . '>';
        //$headers[] = 'Cc: birthdayarchive@example.com';
        //$headers[] = 'Bcc: birthdaycheck@example.com';
        //mail($email_to, $email_sub, $email_msg, $headers);
        $mailSent = @mail($to, $subject, $mensaje, implode("\r\n", $headers));

        //Actualizamos DB solo si se envió el correo
        if ($mailSent) {
            $datos = ["enviado" => True];
            $LicenciaMod = new LicenciaModel();
            $respuesta = $LicenciaMod->updateLicencia($datos, $licencia_id);
        } else {
            $respuesta = false;
        }

        return $respuesta;
    }
    function licenses_reports()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //Estudiantes
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_secretary($secretary_id);
        $page_data['students'] = $students;

        //Cursos
        $data = ["active" => 1];
        $SectionMod = new SectionModel();
        $cursos = $SectionMod->get_section($data);
        $page_data['cursos'] = $cursos;
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //$page_data['cursos']  = $cursos;
        //$page_data['subjects']  = $subjects;
        $page_data['page_name'] = 'licenses_reports';
        $page_data['page_title'] = 'Reportes Licencias';
        return view('backend/index', $page_data);
    }
    function licenses_report_xlsx()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Parametro
        $fecha = $_POST['fechaReporte'];
        //Cursos
        $SecretaryMod = new SecretaryModel();
        $sections = $SecretaryMod->get_sections_by_secretary_id($secretary_id);
        //Filtramos Licencias
        $LicenciaMod = new LicenciaModel();
        $licencias = $LicenciaMod->licencias_tipo_fecha($fecha, $sections['section_ini'], $sections['section_fin']);

        //instanciamos la libreria
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $obj_PHPExcel = $obj_Reader->load('licenses/licencias.xlsx');
        $fileName = "Lic_" . $fecha . ".xlsx";
        //Rellenamos Datos
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A1', "Reportes de Licencias por Días y Horas");
        //Recorremos Licencias
        $c = 7;
        foreach ($licencias as $lic):
            if ($lic['tipo_id'] == '2') {
                $inicio = $lic['inicio'] ?? '';
                $fin    = $lic['fin'] ?? '';
                $tipo   = "Período";
            } else {
                $inicio = $lic['inicio'] ?? '';
                $fin    = $lic['fin'] ?? '';
                $tipo   = "Día(s)";
            }
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $c, $c - 6);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $c, $lic['student']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $c, $lic['completo']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $c, $tipo);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $c, $lic['fecha_solicitud']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $c, $lic['detalle']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $c, $lic['solicitante']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $c, $lic['parentesco']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $c, $lic['medio']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $c, $inicio);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $c, $fin);
            $c += 1;
        endforeach;

        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A3', 'Generado el : ' . $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);

        $session->set('flash_message', 'Reporte descargado Correctamente ' . count($licencias) . ' - ' . $fecha);
        return redirect()->to(base_url() . 'secretary/licenses_reports');
    }
    function licenses_report_dates_xlsx()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Parametro
        //$fecha = $_POST['fechaReporte'];
        $fechaIni = $_POST['fechaIni'];
        $fechaFin = $_POST['fechaFin'];
        $cursoIni = $_POST['cursoIni'];
        $cursoFin = $_POST['cursoFin'];
        $fecha = date("d-m-Y");
        //Filtramos Licencias
        $LicenciaMod = new LicenciaModel();
        $licencias = $LicenciaMod->licencias_curso_fechas($fechaIni, $fechaFin, $cursoIni, $cursoFin);

        //instanciamos la libreria
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $obj_PHPExcel = $obj_Reader->load('licenses/licencias.xlsx');
        $fileName = "Licencias_" . $fecha . ".xlsx";
        //Rellenamos Datos
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A1', "Reportes de Licencias por Cursos");
        //Recorremos Licencias
        $c = 7;
        foreach ($licencias as $lic):
            if ($lic['tipo_id'] == '2') {
                $inicio = $lic['inicio'] ?? '';
                $fin    = $lic['fin'] ?? '';
                $tipo   = "Período";
            } else {
                $inicio = $lic['inicio'] ?? '';
                $fin    = $lic['fin'] ?? '';
                $tipo = "Día(s)";
            }
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $c, $c - 6);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $c, $lic['student']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $c, $lic['completo']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $c, $tipo);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $c, $lic['fecha_solicitud']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $c, $lic['detalle']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $c, $lic['solicitante']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $c, $lic['parentesco']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $c, $lic['medio']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $c, $inicio);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $c, $fin);
            $c += 1;
        endforeach;

        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A3', 'Generado del : ' . $fechaIni . ' al ' . $fechaFin);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);

        $session->set('flash_message', 'Reporte descargado Correctamente ' . count($licencias) . ' - ' . $fecha);
        return redirect()->to(base_url() . 'secretary/licenses_reports');
    }
    /**********************************************AUSENCIAS... ESTUDIANTES SIN LICENCIA ****************/
    function absences()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //$Subject = new SubjectModel();
        //Cursos
        $AbsenceMod = new AbsenceModel();
        $ausencias = $AbsenceMod->listar_absence($secretary_id);
        $page_data['ausencias'] = $ausencias;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        //$page_data['cursos']  = $cursos;
        //$page_data['subjects']  = $subjects;
        $page_data['page_name'] = 'absences';
        $page_data['page_title'] = 'Ausencias';
        return view('backend/index', $page_data);
    }
    function absence_add()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //$Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_secretary($secretary_id);
        $page_data['students'] = $students;

        //Assistance_obs
        $MotivoMod = new MotivoModel();
        $motivos = $MotivoMod->listarMotivos();
        $page_data['motivos'] = $motivos;
        //Medios
        $MedioMod = new MedioModel();
        $medios = $MedioMod->listarMedios();
        $page_data['medios'] = $medios;
        //Parentesco
        $Parentesco = new ParentescoModel();
        $parentescos = $Parentesco->listarParentescos();
        $page_data['parentescos'] = $parentescos;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_name'] = 'absence_add';
        $page_data['page_title'] = 'Nueva Ausencias';
        return view('backend/index', $page_data);
    }
    public function absence_create()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //FECHA SOLICITUD
        $hora_ausencia = date("H:i:s", strtotime($_POST['hora']));
        $datos = [
            "student_id" => $_POST['student_id'],
            "subject_id" => $_POST['subjects'],
            "periodo" => $_POST['periodo'],
            "fecha" => $_POST['fecha'],
            "hora" => $_POST['hora'],
            "obs" => $_POST['obs'],
            "cantidad" => $_POST['cantidad'],
            "enviado" => False,
        ];
        $AbsenceMod = new AbsenceModel();
        $respuesta = $AbsenceMod->insert_absence($datos);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Se guardó la Ausencia Correctamente');
            return redirect()->to(base_url() . 'secretary/absences');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/absences');
        }
    }
    public function absence_edit($ausencia_id = "")
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_secretary($secretary_id);
        $page_data['students'] = $students;
        //Ausencia
        $AbsenceMod = new AbsenceModel();
        $ausencia = $AbsenceMod->get_absence($ausencia_id);
        $page_data['ausencias'] = $ausencia;

        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Editar Ausencia";
        $page_data['page_name'] = "absence_edit";
        return view('backend/index', $page_data);
    }
    public function absence_get($ausencia_id)
    {
        $AbsenceMod = new AbsenceModel();
        $respuesta = $AbsenceMod->get_absence($ausencia_id);
        return $respuesta[0]['nick_name'] . ' - ' . $respuesta[0]['student'] . ' - ' . $respuesta[0]['obs'];
    }
    public function absence_update()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Parametros
        $hora_ausencia = date("H:i:s", strtotime($_POST['hora']));
        $datos = [
            "student_id" => $_POST['student_id'],
            "subject_id" => $_POST['subjects'],
            "periodo" => $_POST['periodo'],
            "fecha" => $_POST['fecha'],
            "hora" => $_POST['hora'],
            "obs" => $_POST['obs'],
            "cantidad" => $_POST['cantidad'],
        ];
        $ausencia_id = $_POST['ausencia_id'];
        $AbsenceMod = new AbsenceModel();
        $respuesta = $AbsenceMod->update_absence($datos, $ausencia_id);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Ausencia actualizada Correctamente');
            return redirect()->to(base_url() . 'secretary/absences');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/absences');
        }
    }
    public function absence_delete()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $absence_id = $_POST['ausenciaId'];
        $AbsenceMod = new AbsenceModel();
        $data = ["ausencia_id" => $absence_id];
        $respuesta = $AbsenceMod->delete_absence($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Ausencia eliminada Correctamente');
            return redirect()->to(base_url() . 'secretary/absences');
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/absences');
        }
    }
    public function absence_send($ausencia_id, $student_id)
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Ausencia
        $AbsenceMod = new AbsenceModel();
        $ausencia = $AbsenceMod->get_absence($ausencia_id);
        //Enviamos la ausencia
        $FamilyMod = new FamilyModel();
        $family = $FamilyMod->get_family_emails($student_id);
        $email1 = $family[0]['email1'];
        $email2 = $family[0]['email2'];
        //Cursos
        $sectionMod = new SectionModel();
        $emailsSection = $sectionMod->section_emails($family[0]['section_id']);
        $emailConsejero = $emailsSection[0]['emailDocente'];
        $emailDirector = $emailsSection[0]['emailDirector'];
        //Enviamos Email
        $EmailMod = new EmailModel();
        $mensaje = $EmailMod->absence_email($ausencia[0]['student'], $ausencia[0]['fecha'], $ausencia[0]['hora'], $ausencia[0]['obs']);
        $email = \Config\Services::email();
        $email->setFrom('saat@tiquipaya.edu.bo', 'Saat Tiquipaya');
        if (isset($email2)) {
            $email->setTo($email1, $email2);
        } else {
            $email->setTo($email1);
        }
        $email->setCC($emailConsejero, $emailDirector);
        $email->setSubject('Id:' . $ausencia_id . ' - Ausencia U. E. Tiquipaya');
        $email->setHeader('Header1', "MIME-Version: 1.0" . "\r\n");
        $email->setHeader('Header2', "Content-type: text/html; charset=iso-8859-1" . "\r\n");
        $email->setMessage($mensaje);
        $email->send();
        //Actualizamos DB
        $datos = ["enviado" => True];
        $AbsenceMod = new AbsenceModel();
        $respuesta = $AbsenceMod->update_absence($datos, $ausencia_id);
        return $respuesta;
    }
    public function absence_report($ausencia_id = "")
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //Datos de Ausencia
        $AbsenceMod = new AbsenceModel();
        $respuesta = $AbsenceMod->get_absence($ausencia_id);
        $aus = $respuesta[0];
        require('fpdf184/fpdf.php');//to be done in your controller
        // Set a filename
        $filename = 'Aus_' . $ausencia_id . '.pdf';

        // Send headers
        header("Content-Type: application/pdf");
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Transfer-Encoding: binary ");

        //$pdf = new \FPDF();
        $pdf = new \FPDF('P', 'mm', array(108, 139));
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 10);
        //$pdf->Cell(5);
        $pdf->Cell(88, 10, utf8_decode('Boleta de Ausencia'), 0, 0, 'C');
        $pdf->Ln(10);

        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 5, 'Fecha Ausencia :', 1, 0, 'R', 1);
        $pdf->Cell(67, 5, $aus['fecha'], 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Hora Ausencia :', 1, 0, 'R', 1);
        $pdf->Cell(67, 5, $aus['hora'], 1, 0, 'L', 0);
        $pdf->Ln(5);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 5, 'Alumno :', 1, 0, 'R', 1);
        $pdf->Cell(67, 5, $aus['student'], 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Curso :', 1, 0, 'R', 1);
        $pdf->Cell(67, 5, $aus['completo'], 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Docente-Materia :', 1, 0, 'R', 1);
        $pdf->Cell(67, 5, utf8_decode($aus['doc_materia']), 1, 0, 'L', 0);
        $pdf->Ln(25);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 5, '----------------------------', 0, 0, 'C');
        $pdf->Cell(10);
        $pdf->Cell(35, 5, '----------------------------', 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(35, 5, utf8_decode('Secretaria Dir. Técnica'), 0, 0, 'C');
        $pdf->Cell(10);
        $pdf->Cell(35, 5, utf8_decode('Dirección Técnica'), 0, 0, 'C');
        $pdf->Ln(10);
        $pdf->Output();
        $this->response->setHeader('Content-Type', 'application/pdf');
    }
    public function ausencias_suma_xlsx()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Parametro
        $fechaIni = $_POST['fechaIni'];
        $fechaFin = $_POST['fechaFin'];
        $cursoIni = $_POST['cursoIni'];
        $cursoFin = $_POST['cursoFin'];
        $fecha = date("d-m-Y");
        //Filtramos Licencias
        $LicenciaMod = new LicenciaModel();
        $licencias = $LicenciaMod->licencias_curso_suma($fechaIni, $fechaFin, $cursoIni, $cursoFin);
        //instanciamos la libreria
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $obj_PHPExcel = $obj_Reader->load('licenses/ausencias_suma.xlsx');
        $fileName = "AusenciasCurso_" . $fecha . ".xlsx";
        //Rellenamos Datos
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A3', "Desde: " . date("d/m/Y", strtotime($fechaIni)) . "   Hasta : " . date("d/m/Y", strtotime($fechaFin)));
        //Recorremos Licencias
        $c = 7;
        foreach ($licencias as $lic):
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $c, $c - 6);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $c, $lic['student']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $c, $lic['completo']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $c, $lic['licencias']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $c, $lic['ausencias']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $c, $lic['total']);
            $c += 1;
        endforeach;

        $fecha_actual = date("d/m/Y");
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A4', 'Generado el : ' . $fecha_actual);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);

        $session->set('flash_message', 'Reporte descargado Correctamente ' . count($licencias) . ' - ' . $fecha);
        return redirect()->to(base_url() . 'secretary/licenses_reports');
    }
    function atte_report_student_xlsx()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Parametros Asistencias del Estudiantes
        $student_id = $_POST['student'];
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($_POST['student']);
        $student_name = $students[0]->nombre;
        $curso = $students[0]->completo;
        $fecha_ini = $_POST['fechaIni'];
        $fecha_fin = $_POST['fechaFin'];
        $AssistancesubjectMod = new AssistancesubjectModel();
        $ausencias = $AssistancesubjectMod->atte_student_range($student_id, $fecha_ini, $fecha_fin);
        //instanciamos la libreria
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $obj_PHPExcel = $obj_Reader->load('attendance/attendance_student.xlsx');
        $fileName = "Atte_" . $student_name . ".xlsx";
        //Rellenamos Datos
        $obj_PHPExcel->getActiveSheet()->SetCellValue('B2', $student_name);
        $obj_PHPExcel->getActiveSheet()->SetCellValue('B3', $curso);
        //Recorremos Licencias
        $c = 5;
        $estados = array(0 => "Ausencia", 1 => "Presente", 2 => "Licencia", 3 => "Retraso", 4 => "Virtual");
        foreach ($ausencias as $row):
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $c, $row['assistance_subject_id']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $c, $row['date_class']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $c, $estados[$row['status']]);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $c, $row['materia']);
            $obj_PHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $c, $row['docente']);
            $c += 1;
        endforeach;
        $fecha_actual = date("d/m/Y");
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);
        $session->set('flash_message', 'Reporte descargado Correctamente');
        return redirect()->to(base_url() . 'secretary/attendance_reports');
    }

    /**********************************INFRACCIONES***********************/
    public function infractions2()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //CURSOS
        $Section = new SectionModel();
        $page_data['cursos'] = $Section->sections_range(271, 343);

        //INFRACCIONES CON CARTA
        //Indisciplinas
        $IinfractionMod = new IinfractionModel();
        $page_data['infractions'] = $IinfractionMod->infraction_letter();

        $Setting = new SettingModel();
        //$page_data['mensaje'] = $mensaje;
        $page_data['entry_time'] = $Setting->get_entry_time();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Indisciplinas";
        $page_data['page_name'] = "infractions";
        return view('backend/index', $page_data);
    }

    function infractions()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //Section
        //$SectionMod = new SectionModel();
        //$page_data['cursos'] = $SectionMod->section_docente($session->get('teacher_id'));
        //CURSOS
        $Section = new SectionModel();
        $page_data['cursos'] = $Section->sections_range(271, 343);
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
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
        $page_data['students'] = $StudentMod->studentsSection($section_id, 0);
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
    function infraction_letter($student_id = '')
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $student_name = $students[0]->nombre;
        $curso = $students[0]->completo;
        //Instanciamos la libreria EXCEL y Abrimos el Template
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        //**************ABRIMOS EXCEL DE ACUERDO A EL CURSO QUE CORRESPONDE
        $obj_PHPExcel = $obj_Reader->load('templates/ic_s46.xlsx');

        //Escribimos en el EXCEL
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A6', date("d/m/Y"));
        $obj_PHPExcel->getActiveSheet()->SetCellValue('F6', $student_name);
        $obj_PHPExcel->getActiveSheet()->SetCellValue('J6', $curso);
        //Infracciones
        $IinfractionMod = new IinfractionModel();
        $infractions = $IinfractionMod->infraction_student($student_id);
        $conter = 10;
        foreach ($infractions as $inf):
            $fecha = date("d/m/Y", strtotime($inf['date']));
            $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $inf['materia']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('E' . $conter, $inf['criteria']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('I' . $conter, $fecha);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('J' . $conter, $inf['detail']);
            $conter++;
            if ($conter == 14) {
                break;
            }
        endforeach;

        //Section

        $fileName = 'Carta_' . $student_name . '.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);

    }
    function infractions_student($student_id = '')
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Estudiante
        $StudentMod = new StudentModel();
        $students = $StudentMod->datosStudent($student_id);
        $student_name = $students[0]->nombre;
        $curso = $students[0]->completo;
        //Instanciamos la libreria EXCEL y Abrimos el Template
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        //**************ABRIMOS EXCEL DE ACUERDO A EL CURSO QUE CORRESPONDE
        $obj_PHPExcel = $obj_Reader->load('templates/infractions_student.xlsx');
        //Escribimos en el EXCEL
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A5', date("d/m/Y"));
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A3', $student_name);
        $obj_PHPExcel->getActiveSheet()->SetCellValue('A4', $curso);

        //Infracciones
        $IinfractionMod = new IinfractionModel();
        $infractions = $IinfractionMod->infraction_student($student_id);
        $conter = 8;
        foreach ($infractions as $inf):
            $fecha = date("d/m/Y", strtotime($inf['date']));
            $obj_PHPExcel->getActiveSheet()->SetCellValue('A' . $conter, $conter - 7);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('B' . $conter, $inf['materia']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('C' . $conter, $inf['docente']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('D' . $conter, $inf['criteria']);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('E' . $conter, $fecha);
            $obj_PHPExcel->getActiveSheet()->SetCellValue('F' . $conter, $inf['detail']);
            $conter++;
        endforeach;

        $fileName = 'Faltas_' . $student_name . '.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($obj_PHPExcel, "Xlsx");
        $writer->save($fileName);
        return $this->response->download($fileName, null);
    }
    /**********************************BEGIN RETRASOS***********************/
    public function delays($student_id = '')
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        //$Students
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_secretary($secretary_id);
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
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
            $session->set('flash_message', 'Se guardó el medio de comunicación Correctamente');
            return redirect()->to(base_url() . 'secretary/delays/' . $_POST['student_id']);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/delays/' . $_POST['student_id']);
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
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $delay_id = $_POST['delay_id'];
        $DelayMod = new DelayModel();
        $data = ["delay_id" => $delay_id];
        $respuesta = $DelayMod->delete_delay($data);
        if ($respuesta > 0) {
            $session->set('flash_message', 'Retraso eliminado.');
            return redirect()->to(base_url() . 'secretary/delays/' . $_POST['student_id']);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/delays/' . $_POST['student_id']);
        }
    }
    public function delay_update()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
            return redirect()->to(base_url() . 'secretary/delays/' . $_POST['student_id']);
        } else {
            $session->set('flash_message_error', 'Error al procesar');
            return redirect()->to(base_url() . 'secretary/delays/' . $_POST['student_id']);
        }
    }
    public function delay_xlsx($student_id = '', $phase_id = '')
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Retrasos
        $DelayMod = new DelayModel();
        $delays = $DelayMod->delay_student($student_id, $phase_id);
        //Instanciamos la libreria EXCEL y Abrimos el Template
        $obj_Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        //**************ABRIMOS EXCEL DE ACUERDO A EL CURSO QUE CORRESPONDE
        $obj_PHPExcel = $obj_Reader->load('templates/delays_student.xlsx');

        $student_name = "";
        $conter = 7;
        foreach ($delays as $del):
            if ($conter == 7) {
                //Escribimos en el EXCEL
                $student_name = $del['student'];
                $obj_PHPExcel->getActiveSheet()->SetCellValue('A3', date("d/m/Y"));
                $obj_PHPExcel->getActiveSheet()->SetCellValue('A4', $del['student']);
                $obj_PHPExcel->getActiveSheet()->SetCellValue('A5', $del['completo']);
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
                $obj_PHPExcel->getActiveSheet()->SetCellValue('A2', $titulo);
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
    /**********************************END RETRASOS***********************/


    /**********************************BEGIN DIRECCIÓN***********************/
    function student_attendance($student_id = '')
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
    /**********************************END DIRECCIÓN***********************/
    /**********************************ENFERMERIA HISTORIAL CLINICO ***********************/
    public function ehc()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
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
    /**************************************** PROFILE ********************* */
    public function profile()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $secretary_id = $session->get('secretary_id');
        $SecretaryMod = new SecretaryModel();
        $secretary_data = $SecretaryMod->get_secretary(['secretary_id' => $secretary_id]);

        $Setting = new SettingModel();
        $page_data['login_type'] = $session->get('login_type');
        $page_data['cuenta'] = $session->get('cuenta');
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Mi Perfil";
        $page_data['page_name'] = "profile";
        $page_data['secretary'] = $secretary_data[0];

        return view('backend/index', $page_data);
    }

    public function profile_update()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $secretary_id = $session->get('secretary_id');
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

        $SecretaryMod = new SecretaryModel();
        if ($SecretaryMod->update_secretary($data, $secretary_id)) {
            $session->set('flash_message', 'Perfil actualizado correctamente');
        } else {
            $session->set('flash_message_error', 'Error al actualizar el perfil');
        }

        return redirect()->to(base_url() . 'secretary/profile');
    }

    public function password_update()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $secretary_id = $session->get('secretary_id');
        $old_password = $this->request->getPost('old_password');
        $new_password = $this->request->getPost('new_password');
        $confirm_password = $this->request->getPost('confirm_password');

        $SecretaryMod = new SecretaryModel();
        $secretary = $SecretaryMod->get_secretary(['secretary_id' => $secretary_id]);

        if (md5($old_password) === $secretary[0]['password']) {
            if ($new_password === $confirm_password) {
                $data = ['password' => md5($new_password)];
                if ($SecretaryMod->update_secretary($data, $secretary_id)) {
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

        return redirect()->to(base_url() . 'secretary/profile');
    }
    /***************************CONTACTOS DE ADM*****************/
    function contacts()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['page_name'] = 'contacts';
        $page_data['page_title'] = 'Contactos';
        return view('backend/index', $page_data);
    }
    /***************************METODOS DE PAGO*****************/
    function payment_methods()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['page_name'] = 'payment_methods';
        $page_data['page_title'] = 'Metodos de Pago';
        return view('backend/index', $page_data);
    }
    /***************************ENTREVISTAS*****************/
    function interviews()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        //Settings
        $Setting = new SettingModel();
        $page_data['phase_id'] = $Setting->get_phase_id();
        $page_data['phase_name'] = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();

        //VISTA
        $page_data['login_type'] = $session->get('login_type');
        $page_data['page_name'] = 'interviews';
        $page_data['page_title'] = 'Horario Entrevistas';
        return view('backend/index', $page_data);
    }
}

