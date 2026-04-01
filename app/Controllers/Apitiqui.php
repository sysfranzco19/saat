<?php

namespace App\Controllers;

use App\Models\FamilyModel;
use App\Models\StudentModel;

class Apitiqui extends BaseController
{
    public function index()
    {
        return;
    }
    public function student()
    {
        return "hola";
    }
    public function family($family_id)
    {
        //Datos de Familia
        $FamilyMod = new FamilyModel();
        $respuesta = $FamilyMod->get_apifamily($family_id);
        return $this->response->setJson($respuesta);
    }
    public function enroll($student_id)
    {
        //Verificamos que su matricula sea 0
        $StudentMod = new StudentModel();
        $data = [ "student_id" => $student_id ];
        $respuesta1 = $StudentMod->get_student($data);
        $matriculado = $respuesta1[0]['matricula'];
        if ($matriculado==0) {
            //Generamos su Matricula
            $StudentMod = new StudentModel();
            $respuesta2 = $StudentMod->getapiMatricula($student_id);
            //adjuntamos su nueva Matricula
            $respuesta3 = $StudentMod->get_student($data);
            $matriculado = $respuesta3[0]['matricula'];
            $datos = [
                'matriculado' => 1,
                'mat' => $matriculado
            ];
        }else{
            $datos = [
                'matriculado' => 0,
                'mat' => $matriculado
            ];
        }
        return $this->response->setJson($datos);
    }
    public function student_exists($student_id){
        //Verificamos que su matricula sea 0
        $StudentMod = new StudentModel();
        $data = [ "student_id" => $student_id ];
        $respuesta = $StudentMod->get_student($data);
        $datos = [
            'existeStudent' => count($respuesta)
        ];
        return $this->response->setJson($datos);
    }
    public function family_exists($family_id)
    {
        //Datos de Familia
        $FamilyMod = new FamilyModel();
        $data = [ "family_id" => $family_id ];
        $respuesta = $FamilyMod->get_family($data);
        $datos = [
            'existeFamily' => count($respuesta)
        ];
        return $this->response->setJson($datos);
    }
}
