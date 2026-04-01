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
        }else{
            return redirect()->to(base_url('/inscripcion'))->with('mensaje','1');
        }

    }
    public function inscripcion_error()
    {
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
            $dataMadre['card'] = $_POST['cardMadre'];
            $dataMadre['place_card'] = $_POST['place_cardMadre'];
            $dataMadre['profession'] = $_POST['professionMadre'];
            $dataMadre['occupation'] = $_POST['occupationMadre'];
            $dataMadre['business'] = $_POST['businessMadre'];
            $dataMadre['workphone'] = $_POST['workphoneMadre'];
            $dataMadre['cellphone'] = $_POST['cellphoneMadre'];
            $dataMadre['idiom'] = $_POST['idiomMadre'];
            $dataMadre['degree_instruction'] = $_POST['degree_instructionMadre'];
            $dataPadre['ex_student'] = $_POST['ex_studentMadre'];
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

    	//$alumnos=$this->db->query("SELECT * FROM rudes WHERE id_rude = ".$student_id)->result_array();
    	foreach ($alumnos as $row){
            $nom_archivo = "RUDE_".trim($row['nombres']).".pdf";
			$pdf->AddPage();
			$pdf->SetFont('Courier', 'B', 11);
			//Color te texto Azul
			$pdf->SetTextColor(0,0,255);
			//AGREGAMOS IMAGEN
			$pdf->Image('0001.jpg',0,0,-300);
			//LLENAMOS VALORES PREDETERMINADOS
            $pdf->SetTextColor(0,0,255);
            $pdf->SetFont('Courier', 'B', 11);
			//1.1 datos de la unidad educativa
			$pdf->SetXY(170,39);
			$pdf->Cell(35,6,'60900045');
			//2.1 apellidos y nombres
			$pdf->SetXY(30,52);
			$pdf->Cell(6,6,utf8_decode($row['apat']));
			$pdf->SetXY(30,57);
			$pdf->Cell(6,6,utf8_decode($row['amat']));
			$pdf->SetXY(30,62);
			$pdf->Cell(6,6,utf8_decode($row['nombres']));
			//2.2 lugar y feha de nacimiento
			$pdf->SetXY(19,70);
			$pdf->Cell(6,6,utf8_decode($row['pais_nac']));
			$pdf->SetXY(80,70);
			$pdf->Cell(6,6,utf8_decode($row['dpto_nac']));
			$pdf->SetXY(19,74);
			$pdf->Cell(6,6,utf8_decode($row['provincia_nac']));
			$pdf->SetXY(80,74);
			$pdf->Cell(6,6,utf8_decode($row['localidad_nac']));
			//Cambiamos tamaño de letra
			$pdf->SetFont('Courier', 'B', 9);
			//2.3 certificado de nacimiento
            if(intval(preg_replace('/[^0-9]+/', '', $row['oficialia']), 10)==0){
                $pdf->SetXY(8,87);
                $pdf->Cell(95,6,$row['oficialia']);
            }else{
                $pdf->SetXY(8,87);
                $pdf->Cell(95,6,intval(preg_replace('/[^0-9]+/', '', $row['oficialia']), 10));
            }    
            if(intval(preg_replace('/[^0-9]+/', '', $row['libro']), 10)==0){
                $pdf->SetXY(30,87);
                $pdf->Cell(95,6,$row['libro']);
            }else{
                $pdf->SetXY(30,87);
                $pdf->Cell(95,6,intval(preg_replace('/[^0-9]+/', '', $row['libro']), 10));
            }
            if(intval(preg_replace('/[^0-9]+/', '', $row['partida']), 10)==0){
                $pdf->SetXY(48,87);
                $pdf->Cell(95,6,$row['partida']);
            }else{
                $pdf->SetXY(48,87);
                $pdf->Cell(95,6,intval(preg_replace('/[^0-9]+/', '', $row['partida']), 10));
            }
            if(intval(preg_replace('/[^0-9]+/', '', $row['folio']), 10)==0){
                $pdf->SetXY(63,87);
                $pdf->Cell(95,6,$row['folio']);
            }else{
                $pdf->SetXY(63,87);
                $pdf->Cell(95,6,intval(preg_replace('/[^0-9]+/', '', $row['folio']), 10));
            }    

			//Cambiamos tamaño de letra
			$pdf->SetFont('Courier', 'B', 11);
			//2.4 Fecha de nacimiento
            //$dia=date("dd", $row['fecha_nac']);
            //$mes=date("mm", $row['fecha_nac']);
            //$anio=date("YYYY", $row['fecha_nac']);
			$pdf->SetXY(80,82);
			$pdf->Cell(95,6,substr($row['fecha_nac'],8,2));
			$pdf->SetXY(93,82);
			$pdf->Cell(95,6,substr($row['fecha_nac'],5,2));
			$pdf->SetXY(104,82);
			$pdf->Cell(95,6,substr($row['fecha_nac'],0,4));
			//2.5 docoumento de identificacion
			$pdf->SetXY(37,94);
			$pdf->Cell(90,6,$row['nro_doc']);
			$pdf->SetXY(89,94);
			$pdf->Cell(95,6,$row['complemento_doc']);
			$pdf->SetXY(110,94);
			$pdf->Cell(95,6,$row['expedido_doc']);
			//2.6 Numero de Rude
			$pdf->SetXY(125,53);
			$pdf->Cell(95,6,$row['rude']);
			//2.7 Sexo
			$j=80;
			if($row['sexo']=="F"){
                $j=61;
                $pdf->SetXY(146,$j);
                $pdf->Cell(95,6,"x");
            }else{
                if($row['sexo']=="M"){
                    $j=57;
                    $pdf->SetXY(146,$j);
                    $pdf->Cell(95,6,"x");
                }
            }

			//2.8 El estudiante presenta discapacidad
			$j=78;
			if($row['discapacidad']==2){$j=61;}else{$j=57;}
			$pdf->SetXY(199,$j);
			$pdf->Cell(95,6,"x");
			//3.0 Direccion actual del estudiante
			$pdf->SetXY(35,116);
			$pdf->Cell(6,6,utf8_decode($row['departamento_dir']));
			$pdf->SetXY(35,120);
			$pdf->Cell(6,6,utf8_decode($row['provincia_dir']));
			$pdf->SetXY(35,124);
			$pdf->Cell(6,6,utf8_decode($row['municipio_dir']));
			$pdf->SetXY(35,128);
			$pdf->Cell(6,6,utf8_decode($row['localidad_dir']));
			$pdf->SetXY(35,133);
			$pdf->Cell(6,6,utf8_decode($row['zona_dir']));
			$pdf->SetXY(35,137);
			$pdf->Cell(6,6,utf8_decode($row['avenida_dir']));
			$pdf->SetXY(35,141);
			$pdf->Cell(6,6,utf8_decode($row['nro_vivienda']));
			$pdf->SetXY(105,141);
			$pdf->Cell(6,6,utf8_decode($row['telefono_dir']));
			$pdf->SetXY(171,141);
			$pdf->Cell(6,6,utf8_decode($row['celular_dir']));
			//Cambiamos tamaño de letra
			$pdf->SetFont('Courier', 'B', 9);
			//4.1.1 Idiomas que habla el estudiante
			$pdf->SetXY(7,166);
			$pdf->Cell(95,6,utf8_decode($row['primer_idioma']));
			//4.1.2 Frecuencia Idiomas que habla el estudiante
			$otros_idiomas = explode("-", $row['idiomas_frecuentes']);
			$i=0;
			$j=184;
			foreach ($otros_idiomas as $otro){
				if(strlen($otros_idiomas[$i])<13){
					$pdf->SetXY(9,$j);
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
						$pdf->SetXY(46,159);
						$pdf->Cell(6,6,'x');
						break;
					case 2:
						$pdf->SetXY(46,163);
						$pdf->Cell(6,6,'x');
						break;
					case 3:
						$pdf->SetXY(46,171);
						$pdf->Cell(6,6,'x');
						break;
					case 4:
						$pdf->SetXY(74,171);
						$pdf->Cell(6,6,'x');
						break;
					case 5:
						$pdf->SetXY(99,190);
						$pdf->Cell(6,6,'x');
						break;
				}
				$i+=1;
			}
			//Cambiamos tamaño de letra
			$pdf->SetFont('Courier', 'B', 11);
			//4.2.1 Existe Centro de salud
			$j=152;
			if($row['centro_salud']==1){$j=152;}else{$j=155;}
			$pdf->SetXY(199,$j);
			$pdf->Cell(6,6,"x");
			//4.2.2 tipo de Centro de Salud
			$tipos = explode("-", $row['tipo_centro_salud']);
			$i=0;
			foreach ($tipos as $tipo){
				switch ($tipo) {
					case 1:
						$pdf->SetXY(168,166);
			            $pdf->Cell(6,6,'x');
						break;
					case 2:
						$pdf->SetXY(168,170);
			            $pdf->Cell(6,6,'x');
						break;
					case 3:
						$pdf->SetXY(168,173);
                        $pdf->Cell(6,6,'x');
						break;
					case 4:
						$pdf->SetXY(168,176);
                        $pdf->Cell(6,6,'x');
						break;
					case 5:
						$pdf->SetXY(200,166);
                        $pdf->Cell(6,6,'x');
						break;
					case 6:
						$pdf->SetXY(202,175);
						$pdf->Cell(6,6,'x');
						break;
                    case 7:
                        $pdf->SetXY(200,173);
			            $pdf->Cell(6,6,'x');
                        break;
				}
				$i+=1;
			}
			//4.2.3 Asiste centro de Salud
			switch ($row['asiste_centro_salud']){
				case 1:
					$pdf->SetXY(143,187);
					$pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(161,187);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(183,187);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(199,187);
					$pdf->Cell(6,6,'x');
					break;
			}
			//4.2.4 Tirnr seguro de salud
			$j=175;
			if($row['tiene_seguro']==1){$j=175;}else{$j=193;}
			$pdf->SetXY($j,191);
			$pdf->Cell(6,6,"x");
			//4.3 Servicios Basicos
			$servicios = explode("-", $row['servicios']);
			$i=0;
			foreach ($servicios as $servicio){
				switch ($servicios[$i]) {
					case 1:
						$pdf->SetXY(22,202);
						$pdf->Cell(6,6,"x");
						break;
					case 2:
						$pdf->SetXY(22,208);
						$pdf->Cell(6,6,"x");
						break;
					case 3:
						$pdf->SetXY(22,215);
						$pdf->Cell(6,6,"x");
						break;
					case 4:
						$pdf->SetXY(92,202);
						$pdf->Cell(6,6,"x");
						break;
					case 5:
						$pdf->SetXY(92,212);
						$pdf->Cell(6,6,"x");
						break;
				}
				$i+=1;
			}
			//4.3.6 La vivienda es:
			switch ($row['tipo_vivienda']){
				case 1:
					$pdf->SetXY(155,205);
                    $pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(155,208);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(155,212);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(202,205);
					$pdf->Cell(6,6,'x');
					break;
				case 5:
					$pdf->SetXY(202,208);
					$pdf->Cell(6,6,'x');
					break;
				case 6:
					$pdf->SetXY(202,212);
					$pdf->Cell(6,6,'x');
					break;
			}
                
			//4.4.1 Accede a internet
			$accesos = explode("-", $row['acceso_internet']);
			$i=0;
			foreach ($accesos as $acceso){
				switch ($accesos[$i]) {
					case 1:
						$pdf->SetXY(31,226);
						$pdf->Cell(6,6,'x');
						break;
					case 2:
						$pdf->SetXY(31,230);
						$pdf->Cell(6,6,'x');
						break;
					case 3:
						$pdf->SetXY(60,226);
						$pdf->Cell(6,6,'x');
						break;
					case 4:
						$pdf->SetXY(60,230);
						$pdf->Cell(6,6,'x');
						break;
					case 5:
						$pdf->SetXY(94,230);
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
						$pdf->SetXY(135,226);
						$pdf->Cell(6,6,'x');
						break;
					case 2:
						$pdf->SetXY(135,230);
						$pdf->Cell(6,6,'x');
						break;
					case 3:
						$pdf->SetXY(174,226);
						$pdf->Cell(6,6,'x');
						break;
					case 4:
						$pdf->SetXY(174,230);
						$pdf->Cell(6,6,'x');
						break;
				}
				$i+=1;
			}
            //4.5.1 El estudiante Trabajo
			$j=15;
			if($row['alumno_trabaja']==2){$j=15;}else{$j=24;}
			$pdf->SetXY($j,242);
			$pdf->Cell(6,6,'x');
			//4.6.1 Como llega a la unidad
			switch ($row['transporte']){
				case 1:
					$pdf->SetXY(44,281);
					$pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(44,285);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(44,288);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(44,291);
					$pdf->Cell(6,6,'x');
					break;
			}
			//4.6.2 Cuanto tiempo tarda
			switch ($row['tiempo_casa_ue']){
				case 1:
					$pdf->SetXY(91,288);
					$pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(91,291);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(91,295);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(91,298);
					$pdf->Cell(6,6,'x');
					break;
			}
			//4.7.1 abandono escolar
			$j=275;
			if($row['abandono_escolar']==1){$j=270;}else{$j=273;}
			$pdf->SetXY(182,$j);
			$pdf->Cell(6,6,'x');
			//*************************************** PAGINA 2 ************************************
			$pdf->AddPage();
			$pdf->SetFont('Courier', 'B', 11);
			//Color te texto Azul
			$pdf->SetTextColor(0,0,255);
			//AGREGAMOS IMAGEN
			$pdf->Image('0002.jpg',0,0,-300);
			//5.1 Vive con
			switch ($row['vive_con']){
				case 1:
					$pdf->SetXY(102,13);
					$pdf->Cell(6,6,'x');
					break;
				case 2:
					$pdf->SetXY(125,13);
					$pdf->Cell(6,6,'x');
					break;
				case 3:
					$pdf->SetXY(150,13);
					$pdf->Cell(6,6,'x');
					break;
				case 4:
					$pdf->SetXY(172,13);
					$pdf->Cell(6,6,'x');
					break;
				case 5:
					$pdf->SetXY(195,13);
					$pdf->Cell(6,6,'x');
					break;
			}
                
			//$IdFamilia=$row['id_familia'];

			//$padre=$this->db->query("SELECT p.parent_id, p.card, l.shortened, p.lastname1, p.lastname2, p.name, p.idiom, p.occupation, p.degree_instruction, birthday
            //FROM parent as p INNER JOIN place as l ON(p.place_card=l.place_id) WHERE p.relationship_id=1 AND p.family_id=".$family_id);
                /*"SELECT tblpadres.id_padre, tblpadres.ci, tblpadres.apat, tblpadres.amat, tblpadres.nombres, idioma, ocupacion, grado_instruccion, fecha_nac 
	                                FROM tblpadres INNER JOIN tblfamiliares ON tblpadres.id_padre = tblfamiliares.id_padre 
	                                WHERE tblfamiliares.id_familia = ". $row['id_familia'] . " AND tblfamiliares.parentesco ='Padre'");*/
            //PADRE
            $data_padre = [
                "family_id" => $family_id,
                "relationship_id" => 1,
            ];
            $parent = new ParentModel();
            $padre = $parent->get_padre_info($family_id);    
			foreach ($padre as $obj){
				$pdf->SetXY(42,28);
				$pdf->Cell(6,6,$obj->card);
                $pdf->SetXY(98,28);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->shortened)));
				$pdf->SetXY(42,34);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->lastname1)));
				$pdf->SetXY(42,39);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->lastname2)));
				$pdf->SetXY(42,45);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->name)));
				$pdf->SetXY(42,51);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->idiom)));
				$pdf->SetXY(42,57);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->occupation)));
				$pdf->SetXY(42,63);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->degree_instruction)));
                if (isset($obj->birthday)) {
                    $fechas = explode("-", $obj->birthday);
                    $pdf->SetXY(44,69);
                    $pdf->Cell(6,6,$fechas[2]);
                    $pdf->SetXY(57,69);
                    $pdf->Cell(6,6,$fechas[1]);
                    $pdf->SetXY(72,69);
                    $pdf->Cell(6,6,$fechas[0]);
                }


			}
	        //$IdFamilia = $obj->id_familia;

	    	//$madre=$this->db->query("SELECT p.parent_id, p.card, l.shortened, p.lastname1, p.lastname2, p.name, p.idiom, p.occupation, p.degree_instruction, birthday
            //FROM parent as p INNER JOIN place as l ON(p.place_card=l.place_id) WHERE p.relationship_id=2 AND p.family_id=".$family_id);
            //MADRE
            $data_madre = [
                "family_id" => $family_id,
                "relationship_id" => 2,
            ];
            $parent = new ParentModel();
            $madre = $parent->get_madre_info($family_id);

			foreach ($madre as $obj){
				$pdf->SetXY(141,28);
				$pdf->Cell(6,6,$obj->card);
                $pdf->SetXY(198,28);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->shortened)));
				$pdf->SetXY(141,34);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->lastname1)));
				$pdf->SetXY(141,39);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->lastname2)));
				$pdf->SetXY(141,45);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->name)));
				$pdf->SetXY(141,51);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->idiom)));
				$pdf->SetXY(141,57);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->occupation)));
				$pdf->SetXY(141,63);
				$pdf->Cell(6,6,utf8_decode(strtoupper($obj->degree_instruction)));
                if (isset($obj->birthday)) {
                    $fechas = explode("-", $obj->birthday);
                    $pdf->SetXY(144,69);
                    $pdf->Cell(6,6,$fechas[2]);
                    $pdf->SetXY(156,69);
                    $pdf->Cell(6,6,$fechas[1]);
                    $pdf->SetXY(167,69);
                    $pdf->Cell(6,6,$fechas[0]);
                }
			}

			//Pie de rude

            //Sin fecha
            $dia='';
			$pdf->Cell(95,6,$dia);
			$pdf->SetXY(161,115);
			$pdf->Cell(95,6,'0 1');
			$pdf->SetXY(180,115);
            $year = date("Y");
			$pdf->Cell(95,6,$year);


    	}

        //Actualizamos Familia
        $data['actualizado'] = '1';
        $RudesMod = new RudesModel();
        $Rude = $RudesMod->update_rudes($data, $student_id);

        $this->response->setHeader('Content-Type', 'application/pdf');
        $modo="I";
        $pdf->Output($nom_archivo,$modo); 
    }
    /*************************************FUNCIONES EXTRAS ********************************************/
}