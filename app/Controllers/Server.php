<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\ServerModel;
use App\Models\StudentModel;
use App\Models\SectionModel;
use App\Models\FamilyModel;
use App\Models\ParentModel;
use App\Models\SubjectModel;
use App\Models\IinfractionModel;
use App\Models\IcriteriaModel;
use App\Models\ItypesfoulsModel;
use App\Models\DelayModel;
use App\Models\MotivoModel;

class Server extends BaseController
{
    /*
    public function index()
    {
        return view('welcome_message');
    }*/
    public function serverUpdate()
    {
        $Tablas = new StudentModel();
        $datos = $Tablas->listarTablas();

        $page_data = [
            "datos" => $datos
        ];

        $Setting = new SettingModel();
        $mensaje = session('mensaje');

        $Server = new ServerModel();
        $datos = $Server->sectionServer();
        $page_data['cantidad'] = count($datos);

        $page_data['mensaje'] = $mensaje;
        $page_data['system_title'] = $Setting->get_system_title();
        $page_data['system_name'] = $Setting->get_system_name();
        $page_data['page_title'] = "Actualizar Tablas";
        $page_data['page_name'] = "actualizar";
        $page_data['page_name0'] = "config";
        return view('home/server_update', $page_data);
    }
    public function updateStudent()
    {
        $Student = new StudentModel();
        //Vaciamos base de Datos
        $respuesta = $Student->truncateStudent();
        //Recorremos Students de Tiquipaya
        $Server = new ServerModel();
        $datos = $Server->studentServer();
        $resp = 1;
        foreach ($datos as $key):
            $datos = [
                "student_id" => $key->student_id,
                "name" => $key->name,
                "lastname" => $key->lastname,
                "lastname2" => $key->lastname2,
                "activo" => $key->activo,
                "section_id" => $key->section_id,
                "family_id" => $key->family_id,
            ];
            $estudiante = new StudentModel();
            $respuesta = $estudiante->insertStudent($datos);
            if ($respuesta == 0) {
                $resp = 0;
            }
        endforeach;
        //Redireccionamos
        if ($respuesta > 0) {
            return redirect()->to(base_url() . '/home/server_update')->with('mensaje', '2');
        } else {
            return redirect()->to(base_url() . '/home/server_update')->with('mensaje', '3');
        }
    }
    public function updateSection()
    {
        $Section = new SectionModel();
        //Vaciamos base de Datos
        $respuesta = $Section->truncateSection();
        //Recorremos Students de Tiquipaya
        $Server = new ServerModel();
        $datos = $Server->sectionServer();
        $resp = 1;
        foreach ($datos as $key):
            $datos = [
                "section_id" => $key->section_id,
                "name" => $key->name,
                "nick_name" => $key->nick_name,
                "completo" => $key->completo,
                "grade" => $key->grade,
                "schedule" => $key->schedule,
                "class_id" => $key->class_id,
                "teacher_id" => $key->teacher_id,
                "secretary_id" => $key->secretary_id,
                "director_id" => $key->director_id,
                "active" => $key->active,
            ];
            $section = new SectionModel();
            $respuesta = $section->insertSection($datos);
            if ($respuesta == 0) {
                $resp = 0;
            }
        endforeach;
        //Redireccionamos
        if ($respuesta > 0) {
            return redirect()->to(base_url() . '/home/server_update')->with('mensaje', '2');
        } else {
            return redirect()->to(base_url() . '/home/server_update')->with('mensaje', '3');
        }
    }
    public function fillDatosStudent()
    {
        $student_id = $_POST['student_id'];
        $Student = new StudentModel();
        $respuesta = $Student->datosStudent($student_id);
        return json_encode($respuesta);
    }
    public function fill_student_section()
    {
        $section_id = $_POST['section_id'];
        $Subject = new StudentModel();
        $respuesta = $Subject->materia_curso($section_id);
        return json_encode($respuesta);
    }
    public function fillDatosParent()
    {
        $family_id = $_POST['family_id'];
        $Parent = new ParentModel();
        $respuesta = $Parent->get_parent_info($family_id);

        $res = "<option value='' selected >Seleccione Padre/Madre</option>";
        foreach ($respuesta as $row):
            $res = $res . "<option value='" . $row['relationship_id'] . "' >" . $row['nombre'] . "</option>";
        endforeach;

        //return json_encode($respuesta);
        return $res;
    }
    public function fill_parent_relationship($family_id, $relationship_id)
    {
        $Parent = new ParentModel();
        $respuesta = $Parent->get_parent_relationship($family_id, $relationship_id);
        return json_encode($respuesta);
    }
    public function updateFamily()
    {
        //Vaciamos base de Datos
        $Family = new FamilyModel();
        $respuesta = $Family->truncateFamily();
        //Recorremos Students de Tiquipaya
        $Family = new ServerModel();
        $datos = $Family->familyServer();
        $resp = 1;
        foreach ($datos as $key):
            $datos = [
                "family_id" => $key->family_id,
                "lastname1" => $key->lastname1,
                "lastname2" => $key->lastname2,
                "home_address" => $key->home_address,
                "neighborhood" => $key->neighborhood,
                "reference" => $key->reference,
                "home_phone" => $key->home_phone,
                "email1" => $key->email1,
                "email2" => $key->email2,
                "relation_id" => $key->relation_id,
                "declared" => $key->declared,
                "nit" => $key->nit,
                "nombre_factura" => $key->nombre_factura,
                "updated_data" => $key->updated_data,
            ];
            $family = new FamilyModel();
            $respuesta = $family->insertFamily($datos);
            if ($respuesta == 0) {
                $resp = 0;
            }
        endforeach;
        //Redireccionamos
        if ($respuesta > 0) {
            return redirect()->to(base_url() . '/home/server_update')->with('mensaje', '2');
        } else {
            return redirect()->to(base_url() . '/home/server_update')->with('mensaje', '3');
        }
    }
    public function updateParent()
    {
        //Vaciamos base de Datos
        $Parent = new ParentModel();
        $respuesta = $Parent->truncateParent();
        //Recorremos Students de Tiquipaya
        $Parent = new ServerModel();
        $datos = $Parent->parentServer();
        $resp = 1;
        foreach ($datos as $key):
            $datos = [
                "parent_id" => $key->parent_id,
                "name" => $key->name,
                "lastname1" => $key->lastname1,
                "lastname2" => $key->lastname2,
                "birthday" => $key->birthday,
                "place_birth" => $key->place_birth,
                "card" => $key->card,
                "place_card" => $key->place_card,
                "profession" => $key->profession,
                "occupation" => $key->occupation,
                "business" => $key->business,
                "workphone" => $key->workphone,
                "cellphone" => $key->cellphone,
                "idiom" => $key->idiom,
                "degree_instruction" => $key->degree_instruction,
                "relationship_id" => $key->relationship_id,
                "email" => $key->email,
                "password" => $key->password,
                "active" => $key->active,
                "family_id" => $key->family_id,
                "address" => $key->address,
                "reference" => $key->reference,
                "phone" => $key->phone,
                "personal_email" => $key->personal_email,
                "code" => $key->code,
            ];
            $Parent = new ParentModel();
            $respuesta = $Parent->insertParent($datos);
            if ($respuesta == 0) {
                $resp = 0;
            }
        endforeach;
        //Redireccionamos
        if ($respuesta > 0) {
            return redirect()->to(base_url() . '/home/server_update')->with('mensaje', '2');
        } else {
            return redirect()->to(base_url() . '/home/server_update')->with('mensaje', '3');
        }
    }
    public function student_get($student_id)
    {
        $data = ["student_id" => $student_id];
        $StudentMod = new StudentModel();
        $respuesta = $StudentMod->get_student($data);
        return $respuesta[0]['lastname'] . ' ' . $respuesta[0]['lastname2'] . ' ' . $respuesta[0]['name'];
    }
    public function fill_type_foul()
    {
        $ItypesfoulsMod = new ItypesfoulsModel();
        $respuesta = $ItypesfoulsMod->listar_types_fouls();
        $res = "<option value='' selected >Seleccione el Tipo de Falta</option>";
        foreach ($respuesta as $row):
            $res = $res . "<option value='" . $row['type_foul_id'] . "' >" . $row['type_foul'] . "</option>";
        endforeach;

        //return json_encode($respuesta);
        return $res;
    }
    public function fill_criteria($type_foul_id)
    {
        $data = ["type_foul_id" => $type_foul_id];
        $IcriteriaMod = new IcriteriaModel();
        $respuesta = $IcriteriaMod->get_criteria($data);
        $res = "<option value='' selected >Seleccione un Criterio</option>";
        foreach ($respuesta as $row):
            $res = $res . "<option value='" . $row['criteria_id'] . "' >" . $row['criteria'] . "</option>";
        endforeach;

        //return json_encode($respuesta);
        return $res;
    }
    public function fill_section_subject()
    {
        $subject_id = $_POST['subject_id'];
        $SubjectMod = new SubjectModel();
        $respuesta = $SubjectMod->subject_section($subject_id);
        return json_encode($respuesta);
    }
    public function fill_motivos()
    {
        $MotivoMod = new MotivoModel();
        $respuesta = $MotivoMod->get_motivos();
        return $this->response->setJSON($respuesta);
    }
    public function fill_periodos()
    {
        $PeriodoMod = new \App\Models\PeriodoModel();
        $respuesta = $PeriodoMod->listar_periodos();
        return $this->response->setJSON($respuesta);
    }
    public function fill_periodos_section($section_id)
    {
        $PeriodoMod = new \App\Models\PeriodoModel();
        $respuesta = $PeriodoMod->listar_periodos_section($section_id);
        return $this->response->setJSON($respuesta);
    }
    public function fill_subjects($section_id)
    {
        $session = session();
        $teacher_id = $session->get('teacher_id');
        $data = ["section_id" => $section_id, "teacher_id" => $teacher_id];
        $SubjectMod = new SubjectModel();
        $respuesta = $SubjectMod->get_subject($data);
        $res = "<option value='' selected >Seleccione una Materia</option>";
        foreach ($respuesta as $row):
            $res = $res . "<option value='" . $row['subject_id'] . "' >" . $row['name'] . "</option>";
        endforeach;
        return $res;
    }
    public function fill_students($section_id)
    {
        $data = ["section_id" => $section_id];
        $StudentMod = new StudentModel();
        $respuesta = $StudentMod->get_student($data);
        $res = "<option value='' selected >Seleccione un Estudiante</option>";
        foreach ($respuesta as $row):
            $res = $res . "<option value='" . $row['student_id'] . "' >" . $row['lastname'] . " " . $row['lastname2'] . " " . $row['name'] . "</option>";
        endforeach;
        return $res;
    }
    public function fill_infraction()
    {
        $infraction_id = $_POST['infraction_id'];
        $IinfractionMod = new IinfractionModel();
        $respuesta = $IinfractionMod->infraction_edit($infraction_id);
        return json_encode($respuesta);
    }
    public function fill_infraction_emails($student_id)
    {
        $IinfractionMod = new IinfractionModel();
        $respuesta = $IinfractionMod->infraction_emails($student_id);
        return json_encode($respuesta);
    }
    public function fill_delays_student($student_id)
    {
        $Setting = new SettingModel();
        $phase_id = $Setting->get_phase_id();
        $DelayMod = new DelayModel();
        $respuesta = $DelayMod->delay_student($student_id, $phase_id);
        return json_encode($respuesta);
    }
    public function descargar_carta($student_id, $cantidad)
    {
        $archivo = 'infractions/Carta_' . $cantidad . '_' . $student_id . '.xlsx';
        return $this->response->download($archivo, null);
    }
}
