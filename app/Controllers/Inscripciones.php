<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\StudentModel;
use App\Models\FamilyModel;
use App\Models\ParentModel;
use App\Models\RudesModel;
use App\Models\MoraModel;
use App\Models\TipodocumentoModel;

class Inscripciones extends BaseController
{
    /*
    public function index()
    {
        return view('welcome_message');
    }*/
    public function index()
    {
         //ModeloSettings
         $Setting = new SettingModel();

         $mensaje = session('mensaje');
         $page_data['system_title']  = $Setting->get_system_title();
         $page_data['system_name']  = $Setting->get_system_name();
         $page_data['mensaje']  = session('mensaje');
         return view('acceso_view', $page_data);
    }
    public function inscripcion_inicio()
    {
        $session = session();
		if ($session->get('login_type') != 'inscripcion')
            return redirect()->to(base_url('/inscripcion')); 

        $student_id = session('student_id');
        $family_id = session('family_id');
        if (!empty(session('student_id'))) {
            //Familia
            $data = [ "family_id" => $family_id ];
            $fam = new FamilyModel();
            $family = $fam->get_family($data);
            //Tipos de Documentos
            $tipo_documento = new TipodocumentoModel();
            $datos2 = $tipo_documento->listar_tipo_documento();
            $page_data['tipos_doc'] = $datos2;
            //PADRE
            $data_padre = [
                "family_id" => $family_id,
                "relationship_id" => 1,
            ];
            $parent = new ParentModel();
            $padre = $parent->get_parent($data_padre);
            //MADRE
            $data_madre = [
                "family_id" => $family_id,
                "relationship_id" => 2,
            ];
            $parent = new ParentModel();
            $madre = $parent->get_parent($data_madre);
            //ESTUDIANTE
            $rudes = new RudesModel();
            $stu = $rudes->get_rudes_info($student_id);
            //PREGUNTAMOS SI EXISTE EL RUDE
            if (count($stu)==0) {
                
                $data1 = [ "student_id" => $student_id ];
                $student = new StudentModel();
                $res = $student->get_student($data1);

                $datos_rude = [
                    "id_rude" => $student_id,
                    "id_familia" => $family_id,
                    "apat" => $res[0]['lastname'],
                    "amat" => $res[0]['lastname2'],
                    "nombres" => $res[0]['name'],
                    "pais_nac" => "BOLIVIA",
                    "tipo_doc" => "1",
                    "nro_doc" => $res[0]['card'],
                    "expedido_doc" => "",
                    "fecha_nac" => $res[0]['birthday'],
                    "sexo" => $res[0]['sex'],
                    "rude" => $res[0]['rude'],
                    "oficialia" => " ",
                    "libro" => " ",
                    "partida" => " ",
                    "folio" => " ",
                    "tipo_centro_salud" => "3-",
                    "primer_idioma" => "CASTELLANO",
                    "idiomas_frecuentes" => "CASTELLANO- ",
                    "nacion_indigena" => "1-",
                    "abandono_escolar" => "1"
                ];
                $rudes = new RudesModel();
                $respuesta = $rudes->insert_rudes($datos_rude);
                
                $rudes = new RudesModel();
                $stu = $rudes->get_rudes_info($student_id);
            }
            $page_data['fam']  = $family[0];
            $page_data['pad']  = $padre[0];
            $page_data['mad']  = $madre[0];
            $page_data['alumnos'] = $stu;
            //MORA
			/*
            $data_mora = [ "mora_id" => $student_id ];
            $mo = new MoraModel();
            $mora = $mo->get_mora($data_mora);
            if (count($mora)==1) {
                //ModeloSettings
                $Setting = new SettingModel();

                $mensaje = session('mensaje');
                $page_data['system_title']  = $Setting->get_system_title();
                $page_data['system_name']  = $Setting->get_system_name();
                $page_data['mensaje']  = session('mensaje');
                return view('inscripcion_error', $page_data);
            }else{
                //ModeloSettings
                $Setting = new SettingModel();
                $mensaje = session('mensaje');
                $page_data['system_title']  = $Setting->get_system_title();
                $page_data['system_name']  = $Setting->get_system_name();
                $page_data['mensaje']  = session('mensaje');
                return view('inscripcion_view', $page_data);
            }
			*/
			$Setting = new SettingModel();
			$mensaje = session('mensaje');
			$page_data['system_title']  = $Setting->get_system_title();
			$page_data['system_name']  = $Setting->get_system_name();
			$page_data['mensaje']  = session('mensaje');
			return view('inscripcion_view', $page_data);
        }else{
            return redirect()->to(base_url('/inscripcion'))->with('mensaje','1');
        }

    }
    public function inscripcion_error()
    {
		$session = session();
		if ($session->get('login_type') != 'inscripcion')
            return redirect()->to(base_url('/inscripcion')); 
        //ModeloSettings
        $Setting = new SettingModel();

        $mensaje = session('mensaje');
        $page_data['system_title']  = $Setting->get_system_title();
        $page_data['system_name']  = $Setting->get_system_name();
        $page_data['mensaje']  = session('mensaje');
        return view('inscripcion_error', $page_data);
    }
    public function inscripcion_family(){
        $session = session();
		if ($session->get('login_type') != 'inscripcion')
            return redirect()->to(base_url('/inscripcion')); 

        $student_id = session('student_id');
        $family_id = session('family_id');

        if (!empty(session('student_id')) && $family_id==$_POST['idFamilia']) {
            //Actualizamos Familia
            $data['home_address'] = $_POST['home_address'];
            $data['neighborhood'] = $_POST['neighborhood'];
            $data['reference'] = $_POST['reference'];
            $data['home_phone'] = $_POST['home_phone'];
            $data['relation_id'] = $_POST['relation_id'];
            $data['email1'] = $_POST['email1'];
            $data['email2'] = $_POST['email2'];
            $data['nit'] = $_POST['nit'];
            $data['tipo_documento'] = $_POST['tipo_documento_id'];
            $data['nombre_factura'] = $_POST['nombre_factura'];
            $data['contact_cell'] = $_POST['contact_cell'];
            $data['updated_data'] = 1;
            $fam = new FamilyModel();
            $family = $fam->update_family($data, $family_id);

            //Actualizamos Padre
            $padre_id = $_POST['padre_id'];
            $dataPadre['name'] = $_POST['namePadre'];
            $dataPadre['lastname1'] = $_POST['lastname1Padre'];
            $dataPadre['lastname2'] = $_POST['lastname2Padre'];
            //$fechaPadre = DateTime::createFromFormat('d/m/Y', $_POST['fechaNacPadre']);
            //$dataPadre['birthday'] = $fechaPadre ? $fechaPadre->format('Y-m-d') : null;
			//$dataPadre['birthday'] = $_POST['fechaNacPadre'];
            $dataPadre['card'] = $_POST['cardPadre'];
            $dataPadre['place_card'] = $_POST['place_cardPadre'];
            $dataPadre['profession'] = $_POST['professionPadre'];
            $dataPadre['occupation'] = $_POST['occupationPadre'];
            $dataPadre['business'] = $_POST['businessPadre'];
            $dataPadre['workphone'] = $_POST['workphonePadre'];
            $dataPadre['cellphone'] = $_POST['cellphonePadre'];
            $dataPadre['idiom'] = $_POST['idiomPadre'];
            $dataPadre['degree_instruction'] = $_POST['degree_instructionPadre'];
            $dataPadre['ex_student'] = $_POST['ex_studentPadre'];
            //$this->db->where('parent_id', $padre_id);
            //$this->db->update('parent', $dataPadre);
            $parent = new ParentModel();
            $padre = $parent->update_parent($dataPadre, $padre_id);

            //Actualizamos Madre
            $madre_id = $_POST['madre_id'];
            $dataMadre['name'] = $_POST['nameMadre'];
            $dataMadre['lastname1'] = $_POST['lastname1Madre'];
            $dataMadre['lastname2'] = $_POST['lastname2Madre'];
            //$fechaMadre = DateTime::createFromFormat('d/m/Y', $_POST['fechaNacMadre']);
            //$dataMadre['birthday'] = $dataMadre ? $dataMadre->format('Y-m-d') : null;
			//$dataMadre['birthday'] = $_POST['fechaNacMadre'];
            $dataMadre['card'] = $_POST['cardMadre'];
            $dataMadre['place_card'] = $_POST['place_cardMadre'];
            $dataMadre['profession'] = $_POST['professionMadre'];
            $dataMadre['occupation'] = $_POST['occupationMadre'];
            $dataMadre['business'] = $_POST['businessMadre'];
            $dataMadre['workphone'] = $_POST['workphoneMadre'];
            $dataMadre['cellphone'] = $_POST['cellphoneMadre'];
            $dataMadre['idiom'] = $_POST['idiomMadre'];
            $dataMadre['degree_instruction'] = $_POST['degree_instructionMadre'];
            $dataMadre['ex_student'] = $_POST['ex_studentMadre'];
            //$this->db->where('parent_id', $madre_id);
            //$this->db->update('parent', $dataMadre, $madre_id);

            $parent = new ParentModel();
            $madre = $parent->update_parent($dataMadre, $madre_id);

            //Creamos XML
            $crearXML = $this->familias_xml();

            



            //ACTUALIZAMOS OBSERVACIONES
            $id_alumno = $student_id;

            
            //parametros
            date_default_timezone_set('America/La_Paz');
            $fechaActual = date('d-m-Y H:i:s');
            $idioma1="";
            $idioma2="";
            $idioma3="";
            if(empty($_POST['idioma1'.$id_alumno])){$idioma1=" ";}else{$idioma1 = $_POST['idioma1'.$id_alumno];}
            if(empty($_POST['idioma2'.$id_alumno])){$idioma2=" ";}else{$idioma2 = $_POST['idioma2'.$id_alumno];}
            if(empty($_POST['idioma3'.$id_alumno])){$idioma3=" ";}else{$idioma3 = $_POST['idioma3'.$id_alumno];}
            //ACTUALIZAMOS DATOS DEL RUDE
            $pais_nac = strtoupper($_POST['pais_'.$id_alumno]);
            $dpto_nac = strtoupper($_POST['dpto_'.$id_alumno]);

            $idiomas = strtoupper($idioma1).'-'.strtoupper($idioma2).'-'.strtoupper($idioma3);
            $naciones="";
            if (null!=$_POST['nacion'.$id_alumno]) {
                foreach($_POST['nacion'.$id_alumno] as $valor){ 
                    $naciones = $naciones.$valor.'-';
                }
            }

            $tipo_centro_salud="";
            if(!empty($_POST['tipo_cen'])){
                //$tipo_centro_salud=$_POST['tipo_cen'];
                foreach($_POST['tipo_cen'] as $valor){ 
                    $tipo_centro_salud = $tipo_centro_salud.$valor.'-';
                }
            }
            $tipo_vivienda=1;
            if(!empty($_POST['vivienda'])){
                $tipo_vivienda=$_POST['vivienda'];

            }
            $transporte=2;
            if(!empty($_POST['transporte'])){
                $transporte=$_POST['transporte'];
            }
            $servicios="";
            if(null!=$_POST['servicios'.$id_alumno]){
                foreach($_POST['servicios'.$id_alumno] as $valor){ 
                    $servicios = $servicios.$valor.'-';
                }
            }
            if($_POST['discapacidad'.$id_alumno]==1){
                $nro_discapacidad = $_POST['nro_dis'.$id_alumno];
                $tipo_discapacidad = $_POST['tipo_dis'.$id_alumno];
                $grado_discapacidad = $_POST['grado_dis'.$id_alumno];
            }else{
                $nro_discapacidad = 0;
                    $tipo_discapacidad = 0;
                $grado_discapacidad = 0;
            }
            $acceso_internet="";
            if(null!=$_POST['acceso_int'.$id_alumno]){
                foreach($_POST['acceso_int'.$id_alumno] as $valor){ 
                    $acceso_internet = $acceso_internet.$valor.'-';
                }
            }
            $tiempo_casa_ue=2;
            if(!empty($_POST['tiempo'])){
                $tiempo_casa_ue=$_POST['tiempo'];
            }
            $vive_con=2;
            if(!empty($_POST['vive_con'])){
                $vive_con=$_POST['vive_con'];
            }

            /*
            //NO Trabajan
            $meses_trabajo="";
            if(null!=$_POST['meses_trabajo'.$id_alumno]){
                foreach($_POST['meses_trabajo'.$id_alumno] as $valor){ 
                    $meses_trabajo = $meses_trabajo.$valor.'-';
                }
            }
            
            $turno_trabajo="";
            if(null!=$_POST['turno_trabajo'.$id_alumno]){
                foreach($_POST['turno_trabajo'.$id_alumno] as $valor){ 
                    $turno_trabajo = $turno_trabajo.$valor.'-';
                }
            }
            $abandono="";
            if(null!=$_POST['abandono'.$id_alumno]){
                foreach($_POST['abandono'.$id_alumno] as $valor){ 
                    $abandono = $abandono.$valor.'-';
                }
            }
            */
            $actividades="";
            $meses_trabajo="";
            $turno_trabajo="";
            $abandono="";
            $data = array(
                'pais_nac' => strtoupper($_POST['pais_'.$id_alumno]),
                'dpto_nac' => strtoupper($_POST['dpto_'.$id_alumno]),
                'provincia_nac' => strtoupper($_POST['provincia_'.$id_alumno]),
                'localidad_nac' => strtoupper($_POST['localidad_'.$id_alumno]),
                'tipo_doc' => $_POST['tipo_doc_'.$id_alumno],
                'complemento_doc' => $_POST['complemento_doc_'.$id_alumno],
                'expedido_doc' => $_POST['expedido_doc_'.$id_alumno],
                'departamento_dir' => strtoupper($_POST['departamento_dir'.$id_alumno]),
                'provincia_dir' => strtoupper($_POST['provincia_dir'.$id_alumno]),
                'municipio_dir' => strtoupper($_POST['municipio_dir'.$id_alumno]),
                'localidad_dir' => strtoupper($_POST['localidad_dir'.$id_alumno]),
                'zona_dir' => strtoupper($_POST['zona_dir'.$id_alumno]),
                'avenida_dir' => strtoupper($_POST['avenida_dir'.$id_alumno]),
                'telefono_dir' => strtoupper($_POST['telefono_dir'.$id_alumno]),
                'celular_dir' => strtoupper($_POST['celular_dir'.$id_alumno]),
                'nro_vivienda' => strtoupper($_POST['nro_vivienda'.$id_alumno]),
                'primer_idioma' => strtoupper($_POST['primer_idioma'.$id_alumno]),
                'idiomas_frecuentes' => $idiomas,
                'nacion_indigena' => $naciones,
                'centro_salud' => $_POST['centro_salud'.$id_alumno],
                'tipo_centro_salud' => $tipo_centro_salud,
                'asiste_centro_salud' => $_POST['asiste'.$id_alumno],
                'tiene_seguro' => $_POST['seguro_salud'.$id_alumno],
                'discapacidad' => $_POST['discapacidad'.$id_alumno],
                'nro_discapacidad' => $nro_discapacidad,
                'tipo_discapacidad' => $tipo_discapacidad,
                'grado_discapacidad' => $grado_discapacidad,
                'servicios' => $servicios,
                'tipo_vivienda' => $tipo_vivienda,
                'acceso_internet' => $acceso_internet,
                'frecuencia_internet' => $_POST['frecuencia_int'.$id_alumno],
                'alumno_trabaja' => $_POST['alumno_trabajo'.$id_alumno],
                'meses_trabajo' => $meses_trabajo,
                'actividades' => $actividades,
                'turno_trabajo' => $turno_trabajo,
                'frecuencia_trabajo' => null,
                'pago_trabajo' => null,
                'transporte' => $transporte,
                'tiempo_casa_ue' => $tiempo_casa_ue,
                'abandono_escolar' => $abandono,
                'vive_con' => $vive_con,
                'fecha_registro' => $fechaActual
            );
            //$this->db->where('id_rude', $id_alumno);
            //return 
            //$this ->db->update('rudes', $data);
            $rudes = new RudesModel();
            $rude = $rudes->update_rudes($data, $student_id);

            //ESTUDIANTE
            $student = new RudesModel();
            $stu = $student->get_rudes($student_id);

            //ModeloSettings
            $page_data['alumnos'] = $stu;
            $Setting = new SettingModel();

            $mensaje = session('mensaje');
            $page_data['system_title']  = $Setting->get_system_title();
            $page_data['system_name']  = $Setting->get_system_name();
            $page_data['mensaje']  = session('mensaje');
            return view('inscripcion_final', $page_data);
        }else{
            return redirect()->to(base_url('/inscripcion'))->with('mensaje','1');
        }







    }




    /*************************************FUNCIONES EXTRAS ********************************************/
    function familias_xml()
    {
		$session = session();
		if ($session->get('login_type') != 'inscripcion')
            return redirect()->to(base_url('/inscripcion')); 
        $student_id = session('student_id');
        $family_id = session('family_id');
        /*
        if ($this->session->userdata('parent_login') != 1)
            redirect(base_url(), 'refresh');

        $family_id = $this->session->userdata('family_id');


        $consulta=$this->db->query("SELECT t1.family_id as id_familia, t1.relation_id, t1.home_address, t1.home_phone, t1.email1, t1.email2, nit, nombre_factura FROM family as t1 WHERE t1.family_id=". $family_id );
        $consulta1=$this->db->query("SELECT p.card, l.shortened, p.cellphone as CelularPadre FROM parent as p INNER JOIN place as l ON(p.place_card=l.place_id) WHERE p.family_id = ". $family_id . " AND p.relationship_id ='1'");
        $consulta2=$this->db->query("SELECT p.card, l.shortened, p.cellphone as CelularMadre FROM parent as p INNER JOIN place as l ON(p.place_card=l.place_id) WHERE p.family_id = ". $family_id . " AND p.relationship_id ='2'");

        //CREAR XML
        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;

        $familia = $doc->createElement("FAMILIA");
        $familia = $doc->appendChild($familia);
        foreach ($consulta ->result() as $obj)
        {
            foreach ($consulta1 ->result() as $obj1){

                foreach ($consulta2 ->result() as $obj2){
                    $id_familia = $doc->createElement("IDFAMILIA");
                    $id_familia = $familia->appendChild($id_familia);
                    $textId = $doc->createTextNode($obj->id_familia);
                    $textId = $id_familia->appendChild($textId);

                    $id_relacion = $doc->createElement("IDRELACION");
                    $id_relacion = $familia->appendChild($id_relacion);
                    $textIdRelacion = $doc->createTextNode($obj->relation_id);
                    $textIdRelacion = $id_relacion->appendChild($textIdRelacion);

                    $direccion_casa = $doc->createElement("DIRECCIONCASA");
                    $direccion_casa = $familia->appendChild($direccion_casa);
                    $textDireccion = $doc->createTextNode($obj->home_address);
                    $textDireccion = $direccion_casa->appendChild($textDireccion);

                    $telefono_casa = $doc->createElement("TELEFONOCASA");
                    $telefono_casa = $familia->appendChild($telefono_casa);
                    $textTelefono = $doc->createTextNode($obj->home_phone);
                    $textTelefono = $telefono_casa->appendChild($textTelefono);

                    $email1 = $doc->createElement("EMAIL1");
                    $email1 = $familia->appendChild($email1);
                    $textEmail1 = $doc->createTextNode($obj->email1);
                    $textEmail1 = $email1->appendChild($textEmail1);

                    $email2 = $doc->createElement("EMAIL2");
                    $email2 = $familia->appendChild($email2);
                    $textEmail2 = $doc->createTextNode($obj->email2);
                    $textEmail2 = $email2->appendChild($textEmail2);

                    $celular_padre = $doc->createElement("CELULARPADRE");
                    $celular_padre = $familia->appendChild($celular_padre);
                    $textCelPadre = $doc->createTextNode($obj1->CelularPadre);
                    $textCelPadre = $celular_padre->appendChild($textCelPadre);

                    $celular_madre = $doc->createElement("CELULARMADRE");
                    $celular_madre = $familia->appendChild($celular_madre);
                    $textCelMadre = $doc->createTextNode($obj2->CelularMadre);
                    $textCelMadre = $celular_madre->appendChild($textCelMadre);
                }
            }
        }
        $doc->save("uploads/family/".$family_id.".xml");
*/
    }
    /*************************************FUNCIONES EXTRAS ********************************************/
    function down_rude($student_id = '')
	{
		$session = session();
		if (!in_array($session->get('login_type'), ['inscripcion', 'admin'])) {
            return redirect()->to(base_url('/inscripcion'));
        }
        //Familia
        $FamilyMod = new FamilyModel();
        $family = $FamilyMod->get_family_student($student_id);
        $family_id = $family[0]['family_id'];
        $nom_archivo = "";
        
        require('fpdf184/fpdf.php');
        $pdf = new \FPDF('P','mm','Legal');

        //PAGINA RUDES
        $data_student = [
            "id_rude" => $student_id
        ];
        $student = new RudesModel();
        $alumnos = $student->get_rudes($data_student);
        $f1 = 0;
    	//$alumnos=$this->db->query("SELECT * FROM rudes WHERE id_rude = ".$student_id)->result_array();
    	foreach ($alumnos as $row){
            $nom_archivo = "RUDE_".trim($row['nombres']).".pdf";
			$pdf->AddPage();
			$pdf->SetFont('Courier', 'B', 10);
			//Color te texto Azul
			$pdf->SetTextColor(0,0,255);
			//AGREGAMOS IMAGEN
			$pdf->Image('0001.jpg',0,0,-300);
			//LLENAMOS VALORES PREDETERMINADOS
            $pdf->SetTextColor(0,0,255);
            $pdf->SetFont('Courier', 'B', 10);
			//1.1 datos de la unidad educativa
            $f1 += 48; //54
			$pdf->SetXY(162,$f1);
			$pdf->Cell(35,6,'60900045');
            
			//2.1 apellidos y nombres
            $f1 += 13;
			$pdf->SetXY(32,$f1);
			$pdf->Cell(6,6,utf8_decode($row['apat']));
            //2.6 Numero de Rude

			$pdf->SetXY(32,$f1+4);
			$pdf->Cell(6,6,utf8_decode($row['amat']));
			$pdf->SetXY(32,$f1+8);
			$pdf->Cell(6,6,utf8_decode($row['nombres']));
			//2.2 lugar y feha de nacimiento
            $f1 += 16;
			$pdf->SetXY(22,$f1);
			$pdf->Cell(6,6,utf8_decode($row['pais_nac']));
			$pdf->SetXY(70,$f1);
			$pdf->Cell(6,6,utf8_decode($row['dpto_nac']));
			$pdf->SetXY(22,$f1+4);
			$pdf->Cell(6,6,utf8_decode($row['provincia_nac']));
			$pdf->SetXY(75,$f1+4);
			$pdf->Cell(6,6,utf8_decode($row['localidad_nac']));
			//Cambiamos tamaño de letra
			$pdf->SetFont('Courier', 'B', 9);
            $f1 += 12;
			//2.3 certificado de nacimiento
            if(intval(preg_replace('/[^0-9]+/', '', $row['oficialia']), 10)==0){
                $pdf->SetXY(8,$f1);
                $pdf->Cell(95,6,$row['oficialia']);
            }else{
                $pdf->SetXY(8,$f1);
                $pdf->Cell(95,6,intval(preg_replace('/[^0-9]+/', '', $row['oficialia']), 10));
            }    
            if(intval(preg_replace('/[^0-9]+/', '', $row['libro']), 10)==0){
                $pdf->SetXY(30,$f1);
                $pdf->Cell(95,6,$row['libro']);
            }else{
                $pdf->SetXY(30,$f1);
                $pdf->Cell(95,6,intval(preg_replace('/[^0-9]+/', '', $row['libro']), 10));
            }
            if(intval(preg_replace('/[^0-9]+/', '', $row['partida']), 10)==0){
                $pdf->SetXY(48,$f1);
                $pdf->Cell(95,6,$row['partida']);
            }else{
                $pdf->SetXY(48,$f1);
                $pdf->Cell(95,6,intval(preg_replace('/[^0-9]+/', '', $row['partida']), 10));
            }
            if(intval(preg_replace('/[^0-9]+/', '', $row['folio']), 10)==0){
                $pdf->SetXY(63,$f1);
                $pdf->Cell(95,6,$row['folio']);
            }else{
                $pdf->SetXY(63,$f1);
                $pdf->Cell(95,6,intval(preg_replace('/[^0-9]+/', '', $row['folio']), 10));
            }    

			//Cambiamos tamaño de letra
			$pdf->SetFont('Courier', 'B', 10);
			//2.4 Fecha de nacimiento
			$pdf->SetXY(75,$f1);
			$pdf->Cell(95,6,substr($row['fecha_nac'],8,2));
			$pdf->SetXY(88,$f1);
			$pdf->Cell(95,6,substr($row['fecha_nac'],5,2));
			$pdf->SetXY(99,$f1);
			$pdf->Cell(95,6,substr($row['fecha_nac'],0,4));
                        //2.6 RUDE
            $pdf->SetXY(115,$f1+4);
			$pdf->Cell(95,6,$row['rude']);
            $f1+=16;
			//2.5 docoumento de identificacion
			$pdf->SetXY(15,$f1);
			$pdf->Cell(90,6,$row['nro_doc']);
			$pdf->SetXY(56,$f1);
			$pdf->Cell(95,6,$row['complemento_doc']);
			$pdf->SetXY(76,$f1);
			$pdf->Cell(95,6,$row['expedido_doc']);


			//2.7 Sexo
            
			if($row['sexo']=="M"){
                $pdf->SetXY(125,$f1-4);
                $pdf->Cell(95,6,"x");
            }else{
                if($row['sexo']=="F"){
                    $pdf->SetXY(146,$f1-4);
                    $pdf->Cell(95,6,"x");
                }
            }
                

			//2.8 El estudiante presenta discapacidad
            $j=0;
			if($row['discapacidad']==1){$j=113;}else{$j=138;}
			$pdf->SetXY($j, $f1+5);
			$pdf->Cell(95,6,"x");

			//3.0 Direccion actual del estudiante
            $f1 = 241;
            $pdf->SetFont('Courier', 'B', 9);
			$pdf->SetXY(36,$f1);
			$pdf->Cell(6,6,utf8_decode($row['departamento_dir']));
			$pdf->SetXY(36,$f1+4);
			$pdf->Cell(6,6,utf8_decode($row['provincia_dir']));
			$pdf->SetXY(36,$f1+8);
			$pdf->Cell(6,6,utf8_decode($row['municipio_dir']));
			$pdf->SetXY(36,$f1+12);
			$pdf->Cell(6,6,utf8_decode($row['localidad_dir']));
			$pdf->SetXY(36,$f1+16);
			$pdf->Cell(6,6,utf8_decode($row['zona_dir']));
			$pdf->SetXY(36,$f1+20);
			$pdf->Cell(6,6,utf8_decode($row['avenida_dir']));
			$pdf->SetXY(36,$f1+24);
			$pdf->Cell(6,6,utf8_decode($row['nro_vivienda']));
			$pdf->SetXY(102,$f1+24);
			$pdf->Cell(6,6,utf8_decode($row['telefono_dir']));
			$pdf->SetXY(165,$f1+24);
			$pdf->Cell(6,6,utf8_decode($row['celular_dir']));

            //*************************************** PAGINA 2 ************************************
			$pdf->AddPage();
			$pdf->SetFont('Courier', 'B', 10);
			//Color te texto Azul
			$pdf->SetTextColor(0,0,255);
			//AGREGAMOS IMAGEN
			$pdf->Image('0002.jpg',0,0,-300);


            
			//Cambiamos tamaño de letra
			$pdf->SetFont('Courier', 'B', 9);
            $f1 = 33;
			//4.1.1 Idiomas que habla el estudiante
			$pdf->SetXY(11,$f1);
			$pdf->Cell(95,6,utf8_decode($row['primer_idioma']));
			//4.1.2 Frecuencia Idiomas que habla el estudiante
			$otros_idiomas = explode("-", $row['idiomas_frecuentes']);
			$i=0;
			$j=$f1+17;
			foreach ($otros_idiomas as $otro){
				if(strlen($otros_idiomas[$i])<13){
					$pdf->SetXY(13,$j);
					$pdf->Cell(95,6,utf8_decode($otros_idiomas[$i]));					
				}
				$i+=1;
				$j+=4;
			}
            
			//4.1.3 Nacion indigena cultural
			$naciones = explode("-", $row['nacion_indigena']);
			$i=0;
			foreach ($naciones as $nacion){
				switch ($naciones[$i]) {
					case 1:
						$pdf->SetXY(49,$f1-7);
						$pdf->Cell(6,6,'x');
						break;
					case 2:
						$pdf->SetXY(49,$f1-4);
						$pdf->Cell(6,6,'x');
						break;
					case 3:
						$pdf->SetXY(49,$f1+3);
			    		$pdf->Cell(6,6,'x');
						break;
					case 4:
						$pdf->SetXY(76,$f1+3);
						$pdf->Cell(6,6,'x');
						break;
					case 5:
						$pdf->SetXY(99,$f1+20);
						$pdf->Cell(6,6,'x');
						break;
				}
				$i+=1;
			}

			//4.2.1 Existe Centro de salud
			$j=0;
			if($row['centro_salud']==1){$j=19;}else{$j=23;}
			$pdf->SetXY(198,$j);
			$pdf->Cell(6,6,"x");
			//4.2.2 tipo de Centro de Salud
            //$f1=176;
			$tipos = explode("-", $row['tipo_centro_salud']);
			$i=0;
			foreach ($tipos as $tipo){
				switch ($tipo) {
					case 1:
						$pdf->SetXY(167,$f1);
			            $pdf->Cell(6,6,'x');
						break;
					case 2:
						$pdf->SetXY(167,$f1+4);
			            $pdf->Cell(6,6,'x');
						break;
					case 3:
						$pdf->SetXY(167,$f1+7);
                        $pdf->Cell(6,6,'x');
						break;
					case 4:
						$pdf->SetXY(167,$f1+10);
                        $pdf->Cell(6,6,'x');
						break;
					case 5:
						$pdf->SetXY(198,$f1);
                        $pdf->Cell(6,6,'x');
						break;
					case 6:
						$pdf->SetXY(198,$f1+3);
						$pdf->Cell(6,6,'x');
						break;
                    case 7:
                        $pdf->SetXY(198,$f1+7);
			            $pdf->Cell(6,6,'x');
                        break;
				}
				$i+=1;
			}
            
			//4.2.3 Asiste centro de Salud
            $f1+=20;
			switch ($row['asiste_centro_salud']){
				case 1:
					$pdf->SetXY(144,$f1);
					$pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(161,$f1);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(183,$f1);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(198,$f1);
					$pdf->Cell(6,6,'x');
					break;
			}
            
			//4.2.4 Tirnr seguro de salud
            $f1 += 4;
			if($row['tiene_seguro']==1){$j=173;}else{$j=191;}
			$pdf->SetXY($j,$f1);
			$pdf->Cell(6,6,"x");
            
			//4.3 Servicios Basicos
            $f1+=10;
			$servicios = explode("-", $row['servicios']);
			$i=0;
			foreach ($servicios as $servicio){
				switch ($servicios[$i]) {
					case 1:
						$pdf->SetXY(24,$f1+1);
						$pdf->Cell(6,6,"x");
						break;
					case 2:
						$pdf->SetXY(24,$f1+7);
						$pdf->Cell(6,6,"x");
						break;
					case 3:
						$pdf->SetXY(24,$f1+14);
						$pdf->Cell(6,6,"x");
						break;
					case 4:
						$pdf->SetXY(93,$f1+1);
						$pdf->Cell(6,6,"x");
						break;
					case 5:
						$pdf->SetXY(93,$f1+11);
						$pdf->Cell(6,6,"x");
						break;
				}
				$i+=1;
			}
            
                
			//4.3.6 La vivienda es:
			switch ($row['tipo_vivienda']){
				case 1:
					$pdf->SetXY(155,$f1+4);
                    $pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(155,$f1+7);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(155,$f1+10);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(200,$f1+4);
					$pdf->Cell(6,6,'x');
					break;
				case 5:
					$pdf->SetXY(200,$f1+7);
					$pdf->Cell(6,6,'x');
					break;
				case 6:
					$pdf->SetXY(200,$f1+10);
					$pdf->Cell(6,6,'x');
					break;
			}
            
			//4.4.1 Accede a internet
			$accesos = explode("-", $row['acceso_internet']);
            $f1+=24;
			$i=0;
			foreach ($accesos as $acceso){
				switch ($accesos[$i]) {
					case 1:
						$pdf->SetXY(34,$f1);
						$pdf->Cell(6,6,'x');
						break;
					case 2:
						$pdf->SetXY(34,$f1+3);
						$pdf->Cell(6,6,'x');
						break;
					case 3:
						$pdf->SetXY(62,$f1);
						$pdf->Cell(6,6,'x');
						break;
					case 4:
						$pdf->SetXY(62,$f1+3);
						$pdf->Cell(6,6,'x');
						break;
					case 5:
						$pdf->SetXY(95,$f1);
						$pdf->Cell(6,6,'x');
						break;
				}
				$i+=1;
			}
            
			//4.4.2 Fracuencia internet
			$frecuencias = explode("-", $row['frecuencia_internet']);
			$i=0;
			foreach ($frecuencias as $frecuencia){
				switch ($frecuencias[$i]) {
					case 1:
						$pdf->SetXY(135,$f1);
						$pdf->Cell(6,6,'x');
						break;
					case 2:
						$pdf->SetXY(135,$f1+4);
						$pdf->Cell(6,6,'x');
						break;
					case 3:
						$pdf->SetXY(174,$f1);
						$pdf->Cell(6,6,'x');
						break;
					case 4:
						$pdf->SetXY(174,$f1+4);
						$pdf->Cell(6,6,'x');
						break;
				}
				$i+=1;
			}
            
            //4.5.1 El estudiante Trabajo
            $f1+=16;
			$j=15;
			if($row['alumno_trabaja']==2){$j=19;}else{$j=28;}
			$pdf->SetXY($j,$f1);
			$pdf->Cell(6,6,'x');
			//4.6.1 Como llega a la unidad
            $f1+=40;
			switch ($row['transporte']){
				case 1:
					$pdf->SetXY(47,$f1);
					$pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(47,$f1+3);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(47,$f1+6);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(47,$f1+9);
					$pdf->Cell(6,6,'x');
					break;
			}
                    
			//4.6.2 Cuanto tiempo tarda
            $f1+=6;
			switch ($row['tiempo_casa_ue']){
				case 1:
					$pdf->SetXY(93,$f1);
					$pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(93,$f1+3);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(93,$f1+7);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(93,$f1+10);
					$pdf->Cell(6,6,'x');
					break;
			}
            
			//4.7.1 abandono escolar
			$j=138;
			if($row['abandono_escolar']==1){$j=134;}else{$j=138;}
			$pdf->SetXY(180,$j);
			$pdf->Cell(6,6,'x');
            

			//5.1 Vive con
            
            $f1+=22;
			switch ($row['vive_con']){
				case 1:
					$pdf->SetXY(109,$f1);
					$pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(130,$f1);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(155,$f1);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(175,$f1);
					$pdf->Cell(6,6,'x');
					break;
				case 5:
					$pdf->SetXY(198,$f1);
					$pdf->Cell(6,6,'x');
					break;
			}
            

            //PADRE            
            $data_padre = [
                "family_id" => $family_id,
                "relationship_id" => 1,
            ];
            $parent = new ParentModel();
            $padre = $parent->get_padre_info($family_id);   
            $f1=188;
			foreach ($padre as $obj){
				$pdf->SetXY(44,$f1);
				$pdf->Cell(6,6,$obj->card);
                $pdf->SetXY(98,$f1);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->shortened)));
				$pdf->SetXY(44,$f1+4);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->lastname1)));
				$pdf->SetXY(44,$f1+8);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->lastname2)));
				$pdf->SetXY(44,$f1+12);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->name)));
				$pdf->SetXY(44,$f1+17);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->idiom)));
				$pdf->SetXY(44,$f1+21);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->occupation)));
				$pdf->SetXY(44,$f1+27);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->degree_instruction)));
                if (isset($obj->birthday)) {
                    $fechas = explode("-", $obj->birthday);
                    $pdf->SetXY(46,$f1+32);
                    $pdf->Cell(6,6,$fechas[2]);
                    $pdf->SetXY(59,$f1+32);
                    $pdf->Cell(6,6,$fechas[1]);
                    $pdf->SetXY(72,$f1+32);
                    $pdf->Cell(6,6,$fechas[0]);
                }


			}

            //MADRE
            $data_madre = [
                "family_id" => $family_id,
                "relationship_id" => 2,
            ];
            $parent = new ParentModel();
            $madre = $parent->get_madre_info($family_id);

			foreach ($madre as $obj){
				$pdf->SetXY(141,$f1);
				$pdf->Cell(6,6,$obj->card);
                $pdf->SetXY(196,$f1);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->shortened)));
				$pdf->SetXY(141,$f1+4);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->lastname1)));
				$pdf->SetXY(141,$f1+8);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->lastname2)));
				$pdf->SetXY(141,$f1+12);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->name)));
				$pdf->SetXY(141,$f1+17);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->idiom)));
				$pdf->SetXY(141,$f1+21);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->occupation)));
				$pdf->SetXY(141,$f1+27);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->degree_instruction)));
                if (isset($obj->birthday)) {
                    $fechas = explode("-", $obj->birthday);
                    $pdf->SetXY(144,$f1+32);
                    $pdf->Cell(6,6,$fechas[2]);
                    $pdf->SetXY(156,$f1+32);
                    $pdf->Cell(6,6,$fechas[1]);
                    $pdf->SetXY(167,$f1+32);
                    $pdf->Cell(6,6,$fechas[0]);
                }
			}

			//Pie de rude
            $f1=256;
            //Sin fecha
            $dia='';
			$pdf->Cell(95,6,$dia);
			$pdf->SetXY(161,$f1);
			$pdf->Cell(95,6,'0 1');
			$pdf->SetXY(180,$f1);
            $year = date("Y");
			$pdf->Cell(95,6,$year);
            //*************************************** PAGINA 3 ************************************
			$pdf->AddPage();
			$pdf->SetFont('Courier', 'B', 10);
			//Color te texto Azul
			$pdf->SetTextColor(0,0,255);
			//AGREGAMOS IMAGEN
			$pdf->Image('0003.jpg',0,0,-300);


    	}

        //Actualizamos Familia
        $data['actualizado'] = '1';
        $RudesMod = new RudesModel();
        $Rude = $RudesMod->update_rudes($data, $student_id);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $modo="I";
        $pdf->Output($nom_archivo,$modo); 
    }
    function informe_family($family_id = '')
	{
		$session = session();
		if (!in_array($session->get('login_type'), ['inscripcion', 'admin'])) {
			return redirect()->to(base_url('/inicio'));
		}
        //RECUPERAMOS PARAMETROS
        $IdFamilia = $family_id;
        //Familia
        $data = [ "family_id" => $family_id ];
        $fam = new FamilyModel();
        $consulta = $fam->get_family_datas($data);
        //PADRE
        $data_padre = [
            "family_id" => $family_id,
            "relationship_id" => 1,
        ];
        $parent = new ParentModel();
        $consulta1 = $parent->get_padre_info($family_id);  
        //MADRE
        $data_madre = [
            "family_id" => $family_id,
            "relationship_id" => 2,
        ];
        $parent = new ParentModel();
        $consulta2 = $parent->get_madre_info($family_id);

        $nom_archivo = "";
        
        require('fpdf184/fpdf.php');
        $pdf = new \FPDF('P','mm','Legal');

        //PAGINA INFORME DE FAMILIA
		$pdf->AddPage();
		//fORMATO DE tEXTO
		$pdf->SetFont('Arial','B', 12);
 
		//Imagen izquierda
		$pdf->Image('logo.png', 15, 10, 25, 15, 'PNG');
 
		//Texto de Título
		$pdf->SetXY(50, 10);
		$pdf->MultiCell(140, 6, utf8_decode('INFORME DE FAMILIA'), 0, 'C');
 
		//Texto Explicativo 1
		$pdf->SetFont('Arial','B', 10);
		$pdf->SetXY(50, 16);
		$pdf->MultiCell(140, 5, utf8_decode('Colegio Tiquipaya Ltda.'), 0, 'C');
		
		//Texto Explicativo 2
		$pdf->SetFont('Arial','', 8);
		$pdf->SetXY(50, 21);
		$pdf->MultiCell(140, 4, utf8_decode('Cochabamba - Bolivia'), 0, 'C');



        //RECORREMOS TODAS LAS CONSULTAS
		foreach ($consulta as $obj)
		{
	        foreach ($consulta1  as $obj1){

	            foreach ($consulta2 as $obj2){

	                		$IdFamilia = $obj['family_id'];
	                        $Familia = $obj['lastname1'].' '.$obj['lastname2'];
	                        $Apellido1 = $obj['lastname1'];
	                        $Apellido2 = $obj['lastname2'];
	                        $DireccionCasa = $obj['home_address'];
	                        $Barrio = $obj['neighborhood'];
	                        $Referencia = $obj['reference'];
	                        $TelefonoCasa = $obj['home_phone'];
	                        $Email1 = $obj['email1'];
	                        $Email2 = $obj['email2'];
	                        $Relacion = $obj['relation'];
	                        
	                        $IdPadre = $obj1->parent_id;
	                        $NombresPadre = $obj1->name;
	                        $ApellidosPadre = $obj1->lastname1.' '.$obj1->lastname2;
	                        $CIPadre =$obj1->card;
							$EmisionPadre = $obj1->shortened;
							$FechaNacPadre = $obj1->birthday;
							$ExstudentPadre = $obj1->ex_student;
	                        $ProfesionPadre = $obj1->profession;
	                        $EmpresaPadre = $obj1->business;
	                        $TelefonoOficinaPadre = $obj1->workphone;
	                        $CelularPadre = $obj1->cellphone;

	                        $IdMadre = $obj2->parent_id;
	                        $NombresMadre = $obj2->name;
	                        $ApellidosMadre = $obj2->lastname1.' '.$obj2->lastname2;
	                        $CIMadre =$obj2->card;
							$EmisionMadre = $obj2->shortened;
							$FechaNacMadre = $obj2->birthday;
							$ExstudentMadre = $obj2->ex_student;
	                        $ProfesionMadre = $obj2->profession;
	                        $EmpresaMadre = $obj2->business;
	                        $TelefonoOficinaMadre = $obj2->workphone;
	                        $CelularMadre = $obj2->cellphone;

	            }
	        }
		}
		$pdf->SetFont('Arial','B', 10);
		$pdf->SetXY(15, 30);
		$pdf->MultiCell(180, 5, utf8_decode('DATOS DE FAMILIA :'.$Familia), 0, 'J');
		//FILA 1 ENCABEZADOS
		$f = 38;
		$pdf->SetFont('Arial','B', 8);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(20, 4, utf8_decode('IdFamilia'), 1, 'J');
		$pdf->SetXY(35, $f);
		$pdf->MultiCell(40, 4, utf8_decode('Primer Apellido'), 1, 'J');
		$pdf->SetXY(75, $f);
		$pdf->MultiCell(40, 4, utf8_decode('Segundo Apellido'), 1, 'J');
		$pdf->SetXY(115, $f);
		$pdf->MultiCell(95, 4, utf8_decode('Direccion Casa'), 1, 'J');
		//FILA 1 DATOS
		$f += 4;
		$pdf->SetFont('Arial','', 10);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(20, 6, $IdFamilia, 1, 'J');
		$pdf->SetXY(35, $f);
		$pdf->MultiCell(40, 6, utf8_decode($Apellido1), 1, 'J');
		$pdf->SetXY(75, $f);
		$pdf->MultiCell(40, 6, utf8_decode($Apellido2), 1, 'J');
		$pdf->SetXY(115, $f);
		$pdf->MultiCell(95, 6, utf8_decode($DireccionCasa), 1, 'J');
		//FILA 2 ENCABEZADOS
		$f += 10;
		$pdf->SetFont('Arial','B', 8);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(30, 4, utf8_decode('Barrio/Zona'), 1, 'J');
		$pdf->SetXY(45, $f);
		$pdf->MultiCell(105, 4, utf8_decode('Referencia Pública (Cercana al Domilicio)'), 1, 'J');
		$pdf->SetXY(150, $f);
		$pdf->MultiCell(30, 4, utf8_decode('Teléfonos Casa'), 1, 'J');
		$pdf->SetXY(180, $f);
		$pdf->MultiCell(30, 4, utf8_decode('Relación'), 1, 'J');
		//FILA 2 DATOS
		$f += 4;
		$pdf->SetFont('Arial','', 9);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(30, 5, $Barrio, 1, 'J');
		$pdf->SetXY(45, $f);
		$pdf->MultiCell(105, 5, utf8_decode($Referencia), 1, 'J');
		$pdf->SetXY(150, $f);
		$pdf->MultiCell(30, 5, $TelefonoCasa, 1, 'J');
		$pdf->SetXY(180, $f);
		$pdf->MultiCell(30, 5, $Relacion, 1, 'J');
		//FILA 3 ENCABEZADOS
		$f += 8;
		$pdf->SetFont('Arial','B', 8);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(100, 4, utf8_decode('Correo Electrónico Principal'), 1, 'J');
		$pdf->SetXY(115, $f);
		$pdf->MultiCell(95, 4, utf8_decode('Correo Electrónico Secundario'), 1, 'J');
		//FILA 3 DATOS
		$f += 4;
		$pdf->SetFont('Arial','', 9);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(100, 5, $Email1, 1, 'J');
		$pdf->SetXY(115, $f);
		$pdf->MultiCell(95, 5, $Email2, 1, 'J');
		//DATOS PADRE
		$f += 10;
		$pdf->SetFont('Arial','B', 10);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(180, 5, utf8_decode('DATOS DEL PADRE :'), 0, 'J');
		//FILA 5 ENCABEZADOS
		$f += 5;
		$pdf->SetFont('Arial','B', 8);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(45, 4, utf8_decode('Nombres'), 1, 'J');
		$pdf->SetXY(60, $f);
		$pdf->MultiCell(55, 4, utf8_decode('Apellidos'), 1, 'J');
		$pdf->SetXY(115, $f);
		$pdf->MultiCell(25, 4, utf8_decode('Nro. CI'), 1, 'J');
		$pdf->SetXY(140, $f);
		$pdf->MultiCell(15, 4, utf8_decode('Emisión'), 1, 'J');
		$pdf->SetXY(155, $f);
		$pdf->MultiCell(55, 4, utf8_decode('Profesión/Ocupación'), 1, 'J');
		//FILA 5 DATOS
		$f += 4;
		$pdf->SetFont('Arial','', 9);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(45, 5, $NombresPadre, 1, 'J');
		$pdf->SetXY(60, $f);
		$pdf->MultiCell(55, 5, utf8_decode($ApellidosPadre), 1, 'J');
		$pdf->SetXY(115, $f);
		$pdf->MultiCell(25, 5, $CIPadre, 1, 'J');
		$pdf->SetXY(140, $f);
		$pdf->MultiCell(15, 5, $EmisionPadre, 1, 'J');
		$pdf->SetXY(155, $f);
		$pdf->MultiCell(55, 5, utf8_decode($ProfesionPadre), 1, 'J');
		//FILA 6 ENCABEZADOS
		$f += 8;
		$pdf->SetFont('Arial','B', 8);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(75, 4, utf8_decode('Empresa donde trabaja'), 1, 'J');
		$pdf->SetXY(90, $f);
		$pdf->MultiCell(30, 4, utf8_decode('Telefono Oficina'), 1, 'J');
		$pdf->SetXY(120, $f);
		$pdf->MultiCell(25, 4, utf8_decode('Celular'), 1, 'J');
		$pdf->SetXY(145, $f);
		$pdf->MultiCell(30, 4, utf8_decode('Fecha Nac.'), 1, 'J');
		$pdf->SetXY(175, $f);
		$pdf->MultiCell(35, 4, utf8_decode('Es Exalumno:'), 1, 'J');
		//FILA 6 DATOS
		$f += 4;
		$pdf->SetFont('Arial','', 9);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(75, 5, $EmpresaPadre, 1, 'J');
		$pdf->SetXY(90, $f);
		$pdf->MultiCell(30, 5, $TelefonoOficinaPadre, 1, 'J');
		$pdf->SetXY(120, $f);
		$pdf->MultiCell(25, 5, $CelularPadre, 1, 'J');
		$pdf->SetXY(145, $f);
		$pdf->MultiCell(30, 5, $FechaNacPadre, 1, 'J');
		$pdf->SetXY(175, $f);
		$pdf->MultiCell(35, 5, $ExstudentPadre, 1, 'J');
		//DATOS MADRE
		$f += 8;
		$pdf->SetFont('Arial','B', 10);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(180, 5, utf8_decode('DATOS DE LA MADRE :'), 0, 'J');
		//FILA 7 ENCABEZADOS
		$f += 5;
		$pdf->SetFont('Arial','B', 8);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(45, 4, utf8_decode('Nombres'), 1, 'J');
		$pdf->SetXY(60, $f);
		$pdf->MultiCell(55, 4, utf8_decode('Apellidos'), 1, 'J');
		$pdf->SetXY(115, $f);
		$pdf->MultiCell(25, 4, utf8_decode('Nro. CI'), 1, 'J');
		$pdf->SetXY(140, $f);
		$pdf->MultiCell(15, 4, utf8_decode('Emisión'), 1, 'J');
		$pdf->SetXY(155, $f);
		$pdf->MultiCell(55, 4, utf8_decode('Profesión/Ocupación'), 1, 'J');
		//FILA 7 DATOS
		$f += 4;
		$pdf->SetFont('Arial','', 9);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(45, 5, utf8_decode($NombresMadre), 1, 'J');
		$pdf->SetXY(60, $f);
		$pdf->MultiCell(55, 5, utf8_decode($ApellidosMadre), 1, 'J');
		$pdf->SetXY(115, $f);
		$pdf->MultiCell(25, 5, utf8_decode($CIMadre), 1, 'J');
		$pdf->SetXY(140, $f);
		$pdf->MultiCell(15, 5, utf8_decode($EmisionMadre), 1, 'J');
		$pdf->SetXY(155, $f);
		$pdf->MultiCell(55, 5, utf8_decode($ProfesionMadre), 1, 'J');
		//FILA 8 ENCABEZADOS
		$f += 8;
		$pdf->SetFont('Arial','B', 8);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(75, 4, utf8_decode('Empresa donde trabaja'), 1, 'J');
		$pdf->SetXY(90, $f);
		$pdf->MultiCell(30, 4, utf8_decode('Telefono Oficina'), 1, 'J');
		$pdf->SetXY(120, $f);
		$pdf->MultiCell(25, 4, utf8_decode('Celular'), 1, 'J');
		$pdf->SetXY(145, $f);
		$pdf->MultiCell(30, 4, utf8_decode('Fecha Nac.'), 1, 'J');
		$pdf->SetXY(175, $f);
		$pdf->MultiCell(35, 4, utf8_decode('Es Exalumno:'), 1, 'J');
		//FILA 8 DAT18
		$f += 4;
		$pdf->SetFont('Arial','', 9);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(75, 5, $EmpresaMadre, 1, 'J');
		$pdf->SetXY(90, $f);
		$pdf->MultiCell(30, 5, $TelefonoOficinaMadre, 1, 'J');
		$pdf->SetXY(120, $f);
		$pdf->MultiCell(25, 5, $CelularMadre, 1, 'J');
		$pdf->SetXY(145, $f);
		$pdf->MultiCell(30, 5, $FechaNacMadre, 1, 'J');
		$pdf->SetXY(175, $f);
		$pdf->MultiCell(35, 5, $ExstudentMadre, 1, 'J');
		//FILA 9
		$f += 12;
		$pdf->SetFont('Arial','B', 10);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(180, 5, utf8_decode('Nómina de hijos a ser inscritos Gestión 2026 :'), 0, 'J');
		$f += 5;
		$pdf->SetFont('Arial','B', 8);
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(10, 5, utf8_decode('Cód.'), 1, 'J');
		$pdf->SetXY(25, $f);
		$pdf->MultiCell(75, 5, utf8_decode('Apellidos y Nombres'), 1, 'J');
		$pdf->SetXY(100, $f);
		$pdf->MultiCell(20, 5, utf8_decode('Fecha Nac.'), 1, 'J');
		$pdf->SetXY(120, $f);
		$pdf->MultiCell(20, 5, utf8_decode('Carnet'), 1, 'J');
		$pdf->SetXY(140, $f);
		$pdf->MultiCell(10, 5, utf8_decode('Exp.'), 1, 'J');
		$pdf->SetXY(150, $f);
		$pdf->MultiCell(35, 5, utf8_decode('Número Rude'), 1, 'J');
		$pdf->SetXY(185, $f);
		$pdf->MultiCell(25, 5, utf8_decode('Curso'), 1, 'J');
		$f += 5;
		$pdf->SetFont('Arial','', 9);

		//RECUPERAMOS LA LISTA DE HIJOS
		$student = new StudentModel();
        $estudiantes = $student->getStudentsFamily($family_id);
		foreach ($estudiantes as $efila) {
			
			$pdf->SetXY(15, $f);
			$pdf->MultiCell(10, 5, $efila["student_id"], 1, 'J');
			$pdf->SetXY(25, $f);
			$pdf->MultiCell(75, 5, utf8_decode($efila["student"]), 1, 'J');
			$pdf->SetXY(100, $f);
			$pdf->MultiCell(20, 5, $efila["birthday"], 1, 'J');
			$pdf->SetXY(120, $f);
			$pdf->MultiCell(20, 5, $efila["card"], 1, 'J');
			$pdf->SetXY(140, $f);
			$pdf->MultiCell(10, 5, $efila["emision"], 1, 'J');
			$pdf->SetXY(150, $f);
			$pdf->MultiCell(35, 5, $efila["rude"], 1, 'J');
			$pdf->SetXY(185, $f);
			$pdf->MultiCell(25, 5, $efila["grade"], 1, 'J');
			$f+=5;
			
    	}
    	$f+=20;
    	$pdf->SetXY(90, $f);
		$pdf->MultiCell(70, 4, '----------------------------------------', 0, 'J');
		$f+=4;
    	$pdf->SetXY(90, $f);
		$pdf->MultiCell(70, 4, 'Firma del padre/madre o tutor', 0, 'J');
		$f+=7;
    	$pdf->SetXY(65, $f);
		$pdf->MultiCell(95, 4, 'Nombre : ..................................................................................', 0, 'J');
		$f+=8;
    	$pdf->SetXY(90, $f);
		$pdf->MultiCell(70, 4, 'C.I. : ........................................', 0, 'J');
    	//Ultima Fila Fecha
		$arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		$arrayDias = array( 'Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
		$fechaActual = $arrayDias[date('w')].", ".date('d')." de ".$arrayMeses[date('m')-1]." de ".date('Y');
		$pdf->SetFont('Arial','', 9);
		$f+=8;
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(70, 4, $fechaActual, 0, 'J');
		$f+=5;
		$pdf->SetXY(15, $f);
		$pdf->MultiCell(180, 4, utf8_decode('El presente formulario representa una declaración jurada.'), 0, 'J');
		$f+=5;
		$pdf->SetXY(15, $f);
		//$pdf->MultiCell(180, 4, utf8_decode('Nota.- Los Rudes fueron llenados correctamente, este documento debe firmarlo en el OPEN HOUSE.'), 0, 'J');

        //Actualizamos Familia
		$data['status'] = 1;
		$fam = new FamilyModel();
		$family = $fam->update_family($data, $family_id);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $modo="I";
        $pdf->Output($nom_archivo,$modo); 
    }
    /*************************************FUNCIONES EXTRAS ********************************************/
}