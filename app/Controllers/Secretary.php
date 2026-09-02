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
use App\Models\SuspensionsModel;
use App\Models\PeriodoModel;
use App\Models\SubjectModel;
use App\Models\BoletaModel;
use App\Models\PhaseModel;
use App\Models\ReprobadosModel;

//Libreria Plantillas
use App\Libraries\Libreria_pdf;

// Cartas de estudiantes reprobados (docx -> pdf)
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory as PhpWordIOFactory;
use PhpOffice\PhpWord\Settings as PhpWordSettings;

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
        $LicenciaMod = new LicenciaModel();

        $today = date('Y-m-d');

        // Fetch students for this secretary
        $students = $StudentMod->student_secretary($secretary_id);
        $page_data['total_students'] = count($students);

        // Fetch Attendance Today from assistance table (Present=1 or Late=3)
        $page_data['attendance_today'] = 0;
        if (!empty($students)) {
            $student_ids = array_column($students, 'student_id');
            $db_asistencia = \Config\Database::connect('asistencia');
            $builder = $db_asistencia->table('assistance');
            $builder->where('date', $today);
            $builder->whereIn('student_id', $student_ids);
            $builder->whereIn('status', [1, 3]); // 1=Present, 3=Late
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

        $Setting = new SettingModel();
        $StudentMod = new StudentModel();

        $students_raw = $StudentMod->student_secretary($secretary_id);

        // Agrupar estudiantes por sección (nick_name)
        $sections = [];
        foreach ($students_raw as $s) {
            $key = $s['nick_name'];
            if (!isset($sections[$key])) {
                $sections[$key] = [
                    'nick_name'  => $s['nick_name'],
                    'section_id' => $s['section_id'],
                    'students'   => [],
                ];
            }
            $sections[$key]['students'][] = [
                'student_id' => $s['student_id'],
                'name'       => trim($s['lastname'] . ' ' . $s['lastname2'] . ' ' . $s['name']),
            ];
        }
        ksort($sections);

        $page_data['sections']     = array_values($sections);
        $page_data['phase_id']     = $Setting->get_phase_id();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['page_name']    = 'assistance';
        $page_data['page_title']   = 'Asistencias';
        return view('backend/index', $page_data);
    }
    public function assistance_by_date()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);

        $date = $this->request->getPost('date');
        if (!$date)
            return $this->response->setJSON((object)[]);

        $secretary_id = $session->get('secretary_id');
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_secretary($secretary_id);
        $student_ids = array_column($students, 'student_id');

        if (empty($student_ids))
            return $this->response->setJSON((object)[]);

        $AssisMod = new AssistanceModel();
        $rows = $AssisMod->get_assistance_by_date($date, $student_ids);

        // Claves string para que json_encode produzca objeto {}, no array []
        $result = new \stdClass();
        foreach ($rows as $r) {
            $sid = (string)$r['student_id'];
            $result->$sid = [
                'status'       => (int)$r['status'],
                'observation'  => $r['observation'] ?? '',
                'arrival_time' => $r['arrival_time'] ?? '',
            ];
        }
        return $this->response->setJSON($result);
    }
        function assistance_center()
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

    function licenses_all_export()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $SecretaryMod = new SecretaryModel();
        $sections = $SecretaryMod->get_sections_by_secretary_id($secretary_id);

        $LicenciaMod = new LicenciaModel();
        if ($sections) {
            $result = $LicenciaMod->licencias_todas_data(
                $sections['section_ini'], $sections['section_fin'], null,
                '', 0, 99999, 4, 'desc'
            );
        } else {
            $result = $LicenciaMod->licencias_todas_data(
                null, null, $secretary_id,
                '', 0, 99999, 4, 'desc'
            );
        }

        ob_start();
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 para Excel
        fputcsv($out, ['Estudiante', 'Curso', 'Tipo', 'Detalle', 'Fecha Solicitud', 'Inicio', 'Fin / Período(s)', 'Días', 'Estado'], ';');
        foreach ($result['data'] as $row) {
            $estado = $row->enviado == 1 ? 'Enviado' : ($row->enviado == 2 ? 'Rechazado' : 'Pendiente');
            fputcsv($out, [
                $row->student,
                $row->nick_name,
                $row->tipo,
                $row->detalle,
                $row->fecha_solicitud,
                $row->inicio ?? '-',
                $row->fin ?? '-',
                $row->cantidad_dias ?? '-',
                $estado,
            ], ';');
        }
        fclose($out);
        $csv = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename=licencias_' . date('Y-m-d') . '.csv')
            ->setBody($csv);
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

    // ── Reporte PDF de Licencia — Primaria (prim_licencias / prim_licencias_dia / prim_licencias_periodo) ──
    public function license_report_prim($licencia_id = "")
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        //Datos de Licencia
        $PrimLicMod = new \App\Models\PrimLicenciaModel();
        $respuesta = $PrimLicMod->getLicenciaPrim((int)$licencia_id);
        if (empty($respuesta)) {
            return $this->response->setStatusCode(404);
        }
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
        $pdf->Cell(68, 5, utf8_decode($lic['student']), 1, 0, 'L', 0);
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
        if (!empty($lic['recoge_nombre'])) {
            $pdf->Cell(25, 5, 'Recoge :', 1, 0, 'R', 1);
            $pdf->Cell(68, 5, utf8_decode($lic['recoge_nombre']), 1, 0, 'L', 0);
            $pdf->Ln(5);
        }
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

    // ── Reporte PDF de Cambio de Recojo — Primaria (tabla prim_cambio_recojo) ──
    public function cambio_recojo_prim($licencia_id = "")
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        //Datos del Cambio de Recojo
        $RecojoMod = new \App\Models\PrimCambioRecojoModel();
        $respuesta = $RecojoMod->getCambioRecojo((int)$licencia_id);
        if (empty($respuesta)) {
            return $this->response->setStatusCode(404);
        }
        $rec = $respuesta[0];
        require('fpdf184/fpdf.php');//to be done in your controller
        // Set a filename
        $filename = 'recojo_' . $licencia_id . '.pdf';
        $pdf = new \FPDF('P', 'mm', array(108, 139));
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(88, 10, utf8_decode('Autorización de Cambio de Recojo'), 0, 0, 'C');
        $pdf->Ln(10);

        $tipoTexto = [
            1 => 'Recogerá otra persona',
            2 => 'No usará transporte escolar',
            3 => 'Otro',
        ][(int)$rec['tipo']] ?? 'Otro';

        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(25, 5, 'F. solicitud :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, $rec['fecha_solicitud'], 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Fecha :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, $rec['fecha'] ?? '', 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Alumno :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, $rec['student'], 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Curso :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, utf8_decode($rec['completo'] ?? $rec['nick_name'] ?? ''), 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Cambio :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, utf8_decode($tipoTexto), 1, 0, 'L', 0);
        $pdf->Ln(5);
        $pdf->Cell(25, 5, 'Solicitante :', 1, 0, 'R', 1);
        $pdf->Cell(68, 5, utf8_decode($rec['solicitante']) . ' (' . utf8_decode($rec['parentesco_solicitante'] ?? '') . ')', 1, 0, 'L', 0);
        $pdf->Ln(5);
        if ((int)$rec['tipo'] === 1) {
            $pdf->Cell(25, 5, 'Recogerá :', 1, 0, 'R', 1);
            $pdf->Cell(68, 5, utf8_decode($rec['persona_nombre'] ?? '') . ' (' . utf8_decode($rec['persona_parentesco'] ?? '') . ')', 1, 0, 'L', 0);
            $pdf->Ln(5);
        }
        $pdf->Cell(25, 10, 'Detalle :', 1, 0, 'R', 1);
        $pdf->MultiCell(68, 5, utf8_decode($rec['detalle'] ?? ''), 1);
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
    /****************************************SUSPENSIONES********************* */
    public function suspensions()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $SuspensionMod = new SuspensionsModel();
        $Setting = new SettingModel();
        $page_data['datos'] = $SuspensionMod->listarSuspensiones();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = 'Suspensiones';
        $page_data['page_name'] = 'suspensions';
        return view('backend/index', $page_data);
    }
    public function suspension_get($suspension_id)
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);
        $SuspensionMod = new SuspensionsModel();
        $suspension = $SuspensionMod->getSuspension((int)$suspension_id);
        return $this->response->setJSON($suspension);
    }
    public function suspension_get_students()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);
        $secretary_id = $session->get('secretary_id');
        $StudentMod = new StudentModel();
        $students = $StudentMod->student_secretary($secretary_id);
        $result = array_map(function ($s) {
            return [
                'student_id' => $s['student_id'],
                'student'    => trim($s['lastname'] . ' ' . $s['lastname2'] . ' ' . $s['name']),
                'section_id' => $s['section_id'],
            ];
        }, $students);
        return $this->response->setJSON($result);
    }
    public function suspension_get_periods()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);
        $PeriodoMod = new PeriodoModel();
        $periodos = $PeriodoMod->listar_periodos();
        return $this->response->setJSON($periodos);
    }
    public function suspension_get_periods_section($section_id)
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);
        $PeriodoMod = new PeriodoModel();
        $periodos = $PeriodoMod->listar_periodos_section($section_id);
        return $this->response->setJSON($periodos);
    }
    public function suspension_create()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $type = (int)$_POST['type'];
        $datos = [
            'student_id' => (int)$_POST['student_id'],
            'type'       => $type,
            'reason'     => trim($_POST['reason']),
            'created_by' => $secretary_id,
        ];
        if ($type === 1) {
            $datos['date_start'] = $_POST['date_start'];
            $datos['date_end']   = $_POST['date_end'];
        } else {
            $datos['date']      = $_POST['date'];
            $datos['period_id'] = (int)$_POST['period_id'];
        }
        $SuspensionMod = new SuspensionsModel();
        $id = $SuspensionMod->insertSuspension($datos);
        if ($id > 0) {
            $session->set('flash_message', 'Suspensión registrada correctamente.');
        } else {
            $session->set('flash_message_error', 'Error al registrar la suspensión.');
        }
        return redirect()->to(base_url() . 'secretary/suspensions');
    }
    public function suspension_update()
    {
        $session = session();
        $secretary_id = $session->get('secretary_id');
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $suspension_id = (int)$_POST['suspension_id'];
        $type = (int)$_POST['type'];
        $datos = [
            'student_id' => (int)$_POST['student_id'],
            'type'       => $type,
            'reason'     => trim($_POST['reason']),
            'date_start' => null,
            'date_end'   => null,
            'date'       => null,
            'period_id'  => null,
        ];
        if ($type === 1) {
            $datos['date_start'] = $_POST['date_start'];
            $datos['date_end']   = $_POST['date_end'];
        } else {
            $datos['date']      = $_POST['date'];
            $datos['period_id'] = (int)$_POST['period_id'];
        }
        $SuspensionMod = new SuspensionsModel();
        $ok = $SuspensionMod->updateSuspension($datos, $suspension_id);
        if ($ok) {
            $session->set('flash_message', 'Suspensión actualizada correctamente.');
        } else {
            $session->set('flash_message_error', 'Error al actualizar la suspensión.');
        }
        return redirect()->to(base_url() . 'secretary/suspensions');
    }
    public function suspension_delete()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());
        $suspension_id = (int)$_POST['suspension_id'];
        $SuspensionMod = new SuspensionsModel();
        $ok = $SuspensionMod->deleteSuspension($suspension_id);
        if ($ok) {
            $session->set('flash_message', 'Suspensión eliminada correctamente.');
        } else {
            $session->set('flash_message_error', 'Error al eliminar la suspensión.');
        }
        return redirect()->to(base_url() . 'secretary/suspensions');
    }

    // ─── BOLETAS ──────────────────────────────────────────────────────────────

    public function boletas()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $Setting   = new SettingModel();
        $Section   = new SectionModel();
        $BoletaMod = new BoletaModel();
        $cursos    = $Section->sections_range(271, 343);
        $phase_id  = $Setting->get_phase_id();

        $section_ids = array_column($cursos, 'section_id');
        $conteo      = $BoletaMod->getConteoPorSeccion($section_ids, $phase_id);
        foreach ($cursos as &$cur) {
            $cur['cantidad'] = $conteo[$cur['section_id']] ?? 0;
        }
        unset($cur);

        $page_data['cursos']       = $cursos;
        $page_data['phase_id']     = $phase_id;
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['page_title']   = 'Boletas Verde – Faltas Graves';
        $page_data['page_name']    = 'boletas';
        return view('backend/index', $page_data);
    }

    public function boletas_seccion($section_id = 0)
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $section_id = (int)$section_id;
        $Setting    = new SettingModel();
        $Section    = new SectionModel();
        $StudentMod = new StudentModel();

        $curso = $Section->get_section(['section_id' => $section_id]);
        if (empty($curso)) return redirect()->to(base_url('secretary/boletas'));

        $page_data['section']      = $curso[0];
        $page_data['section_id']   = $section_id;
        $page_data['students']     = $StudentMod->studentsSection($section_id, 0);
        $page_data['phase_id']     = $Setting->get_phase_id();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['page_title']   = 'Boletas – ' . $curso[0]['completo'];
        $page_data['page_name']    = 'boletas_seccion';
        return view('backend/index', $page_data);
    }

    public function boletas_get_data()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);

        $section_id = (int)($this->request->getGet('section_id') ?? 0);
        $phase_id   = (int)($this->request->getGet('phase_id')   ?? 0);

        $BoletaMod  = new BoletaModel();
        $SubjectMod = new SubjectModel();

        $boletas  = $BoletaMod->getBoletasSeccion($section_id, $phase_id);
        $subjects = $SubjectMod->subjects_section($section_id);

        return $this->response->setJSON([
            'boletas'  => $boletas,
            'subjects' => $subjects,
        ]);
    }

    public function boletas_guardar()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);

        $secretary_id = (int)$session->get('secretary_id');
        $tipo         = $this->request->getPost('tipo');
        $student_id   = (int)$this->request->getPost('student_id');
        $phase_id     = (int)$this->request->getPost('phase_id');
        $fecha        = $this->request->getPost('fecha');
        $descripcion  = trim($this->request->getPost('descripcion') ?? '');
        $dias         = (int)$this->request->getPost('dias_suspension');
        $medidas      = trim($this->request->getPost('medidas_restaurativas') ?? '');
        $subject_id   = $tipo === 'aula' ? (int)$this->request->getPost('subject_id') : null;

        if (!$student_id || !$phase_id || !$fecha || !in_array($tipo, ['aula', 'recreo'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Datos incompletos.']);
        }
        if ($tipo === 'aula' && !$subject_id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Selecciona la materia.']);
        }

        $BoletaMod = new BoletaModel();
        $datos = [
            'student_id'           => $student_id,
            'subject_id'           => $subject_id,
            'phase_id'             => $phase_id,
            'tipo'                 => $tipo,
            'descripcion'          => $descripcion ?: null,
            'fecha'                => $fecha,
            'dias_suspension'      => $dias,
            'medidas_restaurativas' => $medidas ?: null,
            'secretary_id'         => $secretary_id,
        ];
        $id = $BoletaMod->registrar($datos);

        if ($id > 0) {
            return $this->response->setJSON(['status' => 'ok', 'id' => $id]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Error al guardar la boleta.']);
    }

    public function boletas_eliminar()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);

        $id        = (int)$this->request->getPost('id');
        $BoletaMod = new BoletaModel();
        $row       = $BoletaMod->eliminar($id);

        if ($row) {
            return $this->response->setJSON(['status' => 'ok']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Boleta no encontrada.']);
    }

    // =========================================================================
    // ASISTENCIA SECUNDARIA POR CURSO Y FECHA
    // =========================================================================

    public function attendance_by_course()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return redirect()->to(base_url());

        $Setting = new SettingModel();
        $Section = new SectionModel();

        $page_data['cursos']       = $Section->sections_range(271, 343);
        $page_data['phase_id']     = $Setting->get_phase_id();
        $page_data['phase_name']   = $Setting->get_phase_name();
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['page_title']   = 'Asistencia Secundaria por Curso y Fecha';
        $page_data['page_name']    = 'attendance_by_course';
        return view('backend/index', $page_data);
    }

    public function attendance_by_course_data()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);

        $section_id = (int) ($this->request->getGet('section_id') ?? 0);
        $fecha      = trim((string) $this->request->getGet('fecha'));

        if ($section_id < 271 || $section_id > 343) {
            return $this->response->setJSON(['status' => false, 'message' => 'Seleccione un curso de Secundaria.']);
        }
        if ($fecha === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Seleccione una fecha válida.']);
        }

        $AssisMod = new AssistanceModel();
        $data     = $AssisMod->getAsistenciaCursoFecha($section_id, $fecha);

        return $this->response->setJSON(['status' => true, 'data' => $data]);
    }

    // =========================================================================
    // ESTUDIANTES REPROBADOS (Cartas)
    // =========================================================================

    /**
     * Trimestres habilitados para consultar reprobados: el trimestre actual
     * (activo) y todos los anteriores. Nunca trimestres futuros.
     */
    public function reprobados_phases()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);

        $Setting  = new SettingModel();
        $PhaseMod = new PhaseModel();

        $phase_actual = (int) $Setting->get_phase_id();
        $todas        = $PhaseMod->listar_phases();
        $permitidas   = array_values(array_filter($todas, function ($p) use ($phase_actual) {
            return (int) $p['phase_id'] <= $phase_actual;
        }));

        return $this->response->setJSON([
            'status'       => true,
            'phase_actual' => $phase_actual,
            'phases'       => $permitidas,
        ]);
    }

    public function reprobados_get_data()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);

        $phase_id = (int) ($this->request->getGet('phase_id') ?? 0);

        $error = $this->_reprobadosValidarPhase($phase_id);
        if ($error) {
            return $this->response->setJSON(['status' => false, 'message' => $error]);
        }

        $ReprobadosMod = new ReprobadosModel();
        $alumnos       = $ReprobadosMod->getReprobadosAgrupados($phase_id);

        foreach ($alumnos as &$al) {
            $al['carta_generada'] = file_exists($this->_cartaReprobadoPath($al['student_id']));
        }
        unset($al);

        return $this->response->setJSON([
            'status' => true,
            'data'   => $alumnos,
        ]);
    }

    public function reprobados_generar_carta()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);

        $student_id = (int) $this->request->getPost('student_id');
        $phase_id   = (int) $this->request->getPost('phase_id');

        $error = $this->_reprobadosValidarPhase($phase_id);
        if ($error) {
            return $this->response->setJSON(['status' => false, 'message' => $error]);
        }

        $ReprobadosMod = new ReprobadosModel();
        $alumnos       = $ReprobadosMod->getReprobadosAgrupados($phase_id);
        $alumno        = null;
        foreach ($alumnos as $al) {
            if ((int) $al['student_id'] === $student_id) {
                $alumno = $al;
                break;
            }
        }

        if (!$alumno) {
            return $this->response->setJSON(['status' => false, 'message' => 'El estudiante no figura como reprobado en ese trimestre.']);
        }

        try {
            $this->_generarCartaReprobadoPdf($alumno);
        } catch (\Throwable $e) {
            log_message('error', 'reprobados_generar_carta: ' . $e->getMessage());
            return $this->response->setJSON(['status' => false, 'message' => 'No se pudo generar la carta: ' . $e->getMessage()]);
        }

        return $this->response->setJSON([
            'status' => true,
            'url'    => base_url('uploads/cartas_reprobados/' . $student_id . '.pdf') . '?v=' . time(),
        ]);
    }

    public function reprobados_enviar_carta()
    {
        $session = session();
        if ($session->get('login_type') != 'secretary')
            return $this->response->setStatusCode(403);

        $phase_id    = (int) $this->request->getPost('phase_id');
        $student_ids = $this->request->getPost('student_ids'); // array
        $test_email  = trim((string) $this->request->getPost('test_email'));

        $error = $this->_reprobadosValidarPhase($phase_id);
        if ($error) {
            return $this->response->setJSON(['status' => false, 'message' => $error]);
        }

        if (empty($student_ids) || !is_array($student_ids)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Seleccione al menos un estudiante.']);
        }

        $ReprobadosMod = new ReprobadosModel();
        $alumnos       = $ReprobadosMod->getReprobadosAgrupados($phase_id);
        $porId         = [];
        foreach ($alumnos as $al) {
            $porId[(int) $al['student_id']] = $al;
        }

        $resultados = [];
        foreach ($student_ids as $sid) {
            $sid = (int) $sid;
            $alumno = $porId[$sid] ?? null;

            if (!$alumno) {
                $resultados[] = ['student_id' => $sid, 'ok' => false, 'message' => 'No figura como reprobado en el trimestre.'];
                continue;
            }

            $pdfPath = $this->_cartaReprobadoPath($sid);
            if (!file_exists($pdfPath)) {
                // Generamos la carta automáticamente si aún no existe.
                try {
                    $this->_generarCartaReprobadoPdf($alumno);
                } catch (\Throwable $e) {
                    $resultados[] = ['student_id' => $sid, 'ok' => false, 'message' => 'No se pudo generar la carta.'];
                    continue;
                }
            }

            $destinatarios = [];
            if ($test_email !== '') {
                $destinatarios[] = $test_email;
            } else {
                foreach ([$alumno['email1'], $alumno['email2']] as $em) {
                    $em = trim((string) $em);
                    if ($em !== '') $destinatarios[] = $em;
                }
            }

            if (empty($destinatarios)) {
                $resultados[] = ['student_id' => $sid, 'ok' => false, 'message' => 'La familia no tiene un email registrado.'];
                continue;
            }

            $asunto = 'Comunicado de Notas — ' . $alumno['student'];
            $cuerpo = $this->_reprobadosEmailBody($alumno, $test_email !== '');
            $nombreArchivo = 'Carta_' . preg_replace('/[^A-Za-z0-9_]+/', '_', $alumno['student']) . '.pdf';

            $enviado = true;
            foreach (array_unique($destinatarios) as $destino) {
                $enviado = $this->_enviarMailConAdjunto($destino, $asunto, $cuerpo, $pdfPath, $nombreArchivo) && $enviado;
            }

            $resultados[] = [
                'student_id' => $sid,
                'ok'         => $enviado,
                'message'    => $enviado ? ('Enviado a ' . implode(', ', array_unique($destinatarios))) : 'Error al enviar el correo.',
            ];
        }

        return $this->response->setJSON(['status' => true, 'resultados' => $resultados]);
    }

    /**
     * El trimestre pedido debe existir y no ser posterior al trimestre activo.
     * Devuelve un mensaje de error, o cadena vacía si es válido.
     */
    private function _reprobadosValidarPhase($phase_id)
    {
        if ($phase_id <= 0) return 'Seleccione un trimestre.';

        $Setting = new SettingModel();
        $phase_actual = (int) $Setting->get_phase_id();
        if ($phase_id > $phase_actual) {
            return 'Solo se pueden consultar reprobados del trimestre actual o trimestres anteriores.';
        }
        return '';
    }

    private function _cartaReprobadoPath($student_id): string
    {
        return FCPATH . 'uploads/cartas_reprobados/' . ((int) $student_id) . '.pdf';
    }

    /**
     * Combina las materias/notas reprobadas de un estudiante en los dos
     * placeholders del template ({{materia}}: {{nota}}), que en el .docx
     * es una única línea de texto.
     */
    private function _formatMateriaNota(array $materias): array
    {
        if (empty($materias)) return ['', ''];

        $n = count($materias);
        if ($n === 1) {
            return [$materias[0]['materia'], round($materias[0]['nota'])];
        }

        $pares = [];
        for ($i = 0; $i < $n - 1; $i++) {
            $pares[] = $materias[$i]['materia'] . ': ' . round($materias[$i]['nota']);
        }
        $materiaStr = implode('; ', $pares) . '; ' . $materias[$n - 1]['materia'];
        $notaStr    = round($materias[$n - 1]['nota']);

        return [$materiaStr, $notaStr];
    }

    /**
     * Rellena public/templates/carta.docx con los datos del estudiante y
     * genera el PDF final en public/uploads/cartas_reprobados/{student_id}.pdf
     */
    private function _generarCartaReprobadoPdf(array $alumno): string
    {
        $templatePath = FCPATH . 'templates/carta.docx';
        if (!file_exists($templatePath)) {
            throw new \RuntimeException('No se encontró la plantilla carta.docx.');
        }

        $outDir = FCPATH . 'uploads/cartas_reprobados/';
        if (!is_dir($outDir)) {
            mkdir($outDir, 0775, true);
        }

        [$materiaStr, $notaStr] = $this->_formatMateriaNota($alumno['materias']);

        PhpWordSettings::setOutputEscapingEnabled(true);

        $tp = new TemplateProcessor($templatePath);
        $tp->setMacroOpeningChars('{{');
        $tp->setMacroClosingChars('}}');
        $tp->setValue('lastname', (string) $alumno['lastname']);
        $tp->setValue('lastname2', (string) $alumno['lastname2']);
        $tp->setValue('student', (string) $alumno['student']);
        $tp->setValue('curso', (string) $alumno['curso']);
        $tp->setValue('materia', (string) $materiaStr);
        $tp->setValue('nota', (string) $notaStr);

        $tmpDocx = $outDir . $alumno['student_id'] . '_tmp_' . time() . '.docx';
        $tp->saveAs($tmpDocx);

        PhpWordSettings::setPdfRendererPath(VENDORPATH . 'dompdf/dompdf');
        PhpWordSettings::setPdfRendererName('DomPDF');

        $phpWord = PhpWordIOFactory::load($tmpDocx);
        $writer  = PhpWordIOFactory::createWriter($phpWord, 'PDF');

        $outPath = $outDir . $alumno['student_id'] . '.pdf';
        $writer->save($outPath);

        @unlink($tmpDocx);

        return $outPath;
    }

    private function _reprobadosEmailBody(array $alumno, bool $esPrueba = false): string
    {
        $EmailMod = new EmailModel();
        $msg = $EmailMod->header();
        if ($esPrueba) {
            $msg .= '<p style="margin:0;font-weight:bold;color:#f1416c;">⚠ Este es un envío de PRUEBA.</p>';
        }
        $msg .= '<p style="margin:0;">Estimados Padres de familia:</p><br />';
        $msg .= '<p style="margin:0;">Adjuntamos la carta con el detalle de las notas del trimestre correspondientes a su hijo/a:</p><br />';
        $msg .= '<p style="margin:0;">Estudiante: ' . esc($alumno['student']) . '</p>';
        $msg .= '<p style="margin:0;">Curso: ' . esc($alumno['curso']) . '</p>';
        $msg .= '<p style="margin:0;">Agradecemos su atención.</p>';
        $msg .= $EmailMod->footer();
        $msg = str_replace('@title@', 'Comunicado de Notas', $msg);
        return $msg;
    }

    /**
     * Envía un correo con un PDF adjunto usando mail() nativo (multipart/mixed
     * armado a mano, ya que mail() no soporta adjuntos de forma directa).
     * Solo funciona en el servidor de producción (mail() no está configurado
     * en ambientes locales).
     */
    private function _enviarMailConAdjunto(string $to, string $subject, string $htmlBody, string $attachmentPath, string $attachmentName): bool
    {
        if (!file_exists($attachmentPath)) return false;

        $boundary    = md5(uniqid((string) microtime(true), true));
        $fileContent = chunk_split(base64_encode(file_get_contents($attachmentPath)));

        $headers  = "From: Saat Tiquipaya <saat@tiquipaya.edu.bo>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

        $body  = "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $htmlBody . "\r\n\r\n";

        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: application/pdf; name=\"{$attachmentName}\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"{$attachmentName}\"\r\n\r\n";
        $body .= $fileContent . "\r\n";
        $body .= "--{$boundary}--";

        return @mail($to, $subject, $body, $headers);
    }

    // =========================================================================
    // MÓDULOS ASISTENCIA PRIMARIA 3ro–6to
    // =========================================================================

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

    // ── Reporte Bus Escolar — Primaria (licencias día/período + ausencias + cambios de recojo de una fecha) ──
    public function prim_reporte_bus_xlsx()
    {
        $session = session();
        if (!$this->_esPersonalPrimaria())
            return redirect()->to(base_url());

        $fecha = $this->request->getPost('fecha') ?: date('Y-m-d');
        $db    = \Config\Database::connect('asistencia');
        $estados_lic = [0 => 'Pendiente', 1 => 'Aprobada', 2 => 'Rechazada', 3 => 'Eliminada'];
        $tipos_recojo = [1 => 'Otra persona', 2 => 'Sin transporte', 3 => 'Otro'];

        // 1) Licencias por Día que cubren la fecha
        $licDia = $db->query("
            SELECT CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS alumno, sec.nick_name,
                mo.motivo, l.detalle, l.solicitante, par.parentesco, me.medio,
                l.enviado, l.es_excepcion,
                DATE_FORMAT(ld.fecha_inicio,'%d-%m-%Y') AS f_inicio,
                DATE_FORMAT(ld.fecha_fin,'%d-%m-%Y') AS f_fin
            FROM prim_licencias l
            INNER JOIN prim_licencias_dia ld ON ld.licencias_id = l.licencias_id
            INNER JOIN t_student s ON s.student_id = l.student_id
            INNER JOIN section sec ON sec.section_id = s.section_id
            LEFT JOIN t_motivos mo    ON mo.motivo_id = l.motivo_id
            LEFT JOIN t_parentesco par ON par.parentesco_id = l.parentesco_id
            LEFT JOIN t_medios me     ON me.medio_id = l.medio_id
            WHERE ? BETWEEN ld.fecha_inicio AND ld.fecha_fin
              AND sec.section_id BETWEEN 231 AND 263
            ORDER BY sec.section_id, s.lastname
        ", [$fecha])->getResultArray();

        // 2) Licencias por Período registradas para la fecha
        $licPeriodo = $db->query("
            SELECT CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS alumno, sec.nick_name,
                mo.motivo, l.detalle, l.solicitante, par.parentesco, me.medio,
                l.enviado, l.es_excepcion, l.hora_salida, l.recoge_nombre,
                GROUP_CONCAT(p.periodo ORDER BY lp.id SEPARATOR ', ') AS periodos
            FROM prim_licencias l
            INNER JOIN prim_licencias_periodo lp ON lp.licencias_id = l.licencias_id
            INNER JOIN t_student s ON s.student_id = l.student_id
            INNER JOIN section sec ON sec.section_id = s.section_id
            LEFT JOIN t_motivos mo    ON mo.motivo_id = l.motivo_id
            LEFT JOIN t_parentesco par ON par.parentesco_id = l.parentesco_id
            LEFT JOIN t_medios me     ON me.medio_id = l.medio_id
            LEFT JOIN periodo p       ON p.periodo_id = lp.periodo_id
            WHERE lp.fecha = ?
              AND sec.section_id BETWEEN 231 AND 263
            GROUP BY l.licencias_id
            ORDER BY sec.section_id, s.lastname
        ", [$fecha])->getResultArray();

        // 3) Ausencias sin licencia de la fecha (tabla prim_assistance, status=0)
        $ausencias = $db->query("
            SELECT CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS alumno, sec.nick_name
            FROM prim_assistance pa
            INNER JOIN t_student s ON s.student_id = pa.student_id
            INNER JOIN section sec ON sec.section_id = s.section_id
            WHERE pa.status = 0 AND pa.date = ?
              AND sec.section_id BETWEEN 231 AND 263
              AND " . $this->_sinLicenciaSubquery() . "
            ORDER BY sec.section_id, s.lastname
        ", [$fecha])->getResultArray();

        // 4) Cambios de recojo de la fecha
        $cambios = $db->query("
            SELECT CONCAT(s.lastname,' ',s.lastname2,' ',s.name) AS alumno, sec.nick_name,
                c.tipo, c.solicitante, p.parentesco AS parentesco_solicitante,
                c.persona_nombre, COALESCE(pp.parentesco, c.persona_parentesco_otro) AS persona_parentesco,
                c.detalle, c.enviado
            FROM prim_cambio_recojo c
            INNER JOIN t_student s ON s.student_id = c.student_id
            INNER JOIN section sec ON sec.section_id = s.section_id
            LEFT JOIN t_parentesco p  ON p.parentesco_id  = c.parentesco_id
            LEFT JOIN t_parentesco pp ON pp.parentesco_id = c.persona_parentesco_id
            WHERE c.fecha = ?
              AND sec.section_id BETWEEN 231 AND 263
            ORDER BY sec.section_id, s.lastname
        ", [$fecha])->getResultArray();

        $ss = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $hdrStyle = function ($sh, string $range) {
            $sh->getStyle($range)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
            $sh->getStyle($range)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('1A73E8');
        };
        $autosize = function ($sh, string $lastCol) {
            foreach (range('A', $lastCol) as $col) {
                $sh->getColumnDimension($col)->setAutoSize(true);
            }
        };

        // Hoja 1: Licencias (Día + Período)
        $sh1 = $ss->getActiveSheet();
        $sh1->setTitle('Licencias');
        $sh1->fromArray(['#', 'Alumno', 'Curso', 'Tipo', 'Motivo', 'Detalle', 'Período / Rango', 'Hora Salida',
            'Recoge', 'Solicitante', 'Parentesco', 'Medio', 'Estado'], null, 'A1');
        $hdrStyle($sh1, 'A1:M1');
        $r = 2;
        foreach ($licDia as $row) {
            $rango = $row['f_inicio'] . ($row['f_fin'] && $row['f_fin'] !== $row['f_inicio'] ? ' → ' . $row['f_fin'] : '');
            $sh1->fromArray([
                $r - 1, $row['alumno'], $row['nick_name'], 'Día(s)', $row['motivo'], $row['detalle'],
                $rango, '', '', $row['solicitante'], $row['parentesco'], $row['medio'],
                ($estados_lic[$row['enviado']] ?? '') . ($row['es_excepcion'] ? ' ⭐' : ''),
            ], null, "A{$r}");
            $r++;
        }
        foreach ($licPeriodo as $row) {
            $sh1->fromArray([
                $r - 1, $row['alumno'], $row['nick_name'], 'Período', $row['motivo'], $row['detalle'],
                $row['periodos'], substr($row['hora_salida'] ?? '', 0, 5), $row['recoge_nombre'],
                $row['solicitante'], $row['parentesco'], $row['medio'],
                ($estados_lic[$row['enviado']] ?? '') . ($row['es_excepcion'] ? ' ⭐' : ''),
            ], null, "A{$r}");
            $r++;
        }
        $autosize($sh1, 'M');

        // Hoja 2: Ausencias sin licencia
        $sh2 = $ss->createSheet();
        $sh2->setTitle('Ausencias');
        $sh2->fromArray(['#', 'Alumno', 'Curso'], null, 'A1');
        $hdrStyle($sh2, 'A1:C1');
        $r = 2;
        foreach ($ausencias as $row) {
            $sh2->fromArray([$r - 1, $row['alumno'], $row['nick_name']], null, "A{$r}");
            $r++;
        }
        $autosize($sh2, 'C');

        // Hoja 3: Cambios de Recojo
        $sh3 = $ss->createSheet();
        $sh3->setTitle('Cambio de Recojo');
        $sh3->fromArray(['#', 'Alumno', 'Curso', 'Cambio', 'Solicitante', 'Parentesco Solicitante',
            'Persona que recoge', 'Parentesco', 'Detalle', 'Estado'], null, 'A1');
        $hdrStyle($sh3, 'A1:J1');
        $r = 2;
        foreach ($cambios as $row) {
            $sh3->fromArray([
                $r - 1, $row['alumno'], $row['nick_name'], $tipos_recojo[(int)$row['tipo']] ?? 'Otro',
                $row['solicitante'], $row['parentesco_solicitante'], $row['persona_nombre'],
                $row['persona_parentesco'], $row['detalle'],
                $row['enviado'] == 1 ? 'Aprobado' : ($row['enviado'] == 2 ? 'Rechazado' : 'Pendiente'),
            ], null, "A{$r}");
            $r++;
        }
        $autosize($sh3, 'J');

        $ss->setActiveSheetIndex(0);

        $fileName = 'Reporte_Bus_' . date('Y-m-d', strtotime($fecha)) . '.xlsx';
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($ss, 'Xlsx');
        $writer->save($fileName);
        return $this->response->download($fileName, null);
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

