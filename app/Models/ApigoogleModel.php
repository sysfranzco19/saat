<?php

namespace App\Models;

use CodeIgniter\Model;

//namespace App\Controllers;
use Google\Client;
use Google\Service;

class ApigoogleModel extends Model
{
    protected $DBGroup = 'tiquipaya';

    function createSheet($subject_id = '', $folder = '', $filename = '', $emailDocente = '') {

        //Conectamos a google
        putenv('GOOGLE_APPLICATION_CREDENTIALS='.APPPATH.'/ThirdParty/api-google/cargararchivos-308920-03c3786c9ffa.json');
        $client = new \Google_Client();
        $client->useApplicationDefaultCredentials();
        $client->SetScopes(['https://www.googleapis.com/auth/drive']);
        $client->SetScopes(['https://www.googleapis.com/auth/drive.file']);
        try{
            $service = new \Google_Service_Drive($client);
            $file_path = "planilla.xlsx";
            //ARCHIVO NUEVO
            $file = new \Google_Service_Drive_DriveFile();
            $file->setName($filename);
            $file->setParents(array($folder));
            $file->setDescription("Archivo creado por SAAT Tiquipaya");
            $file->setMimeType("application/vnd.google-apps.spreadsheet");
            //CREAMOS
            $resultado = $service->files->create(
                $file,
                array(
                    'data' =>file_get_contents($file_path),
                    'mimeType' => "application/vnd.google-apps.spreadsheet",
                    'uploadType' => 'media'
                )
            );
            //ACTUALIZAMOS LA TABLA
            $datos = [ "sheet_id" => $resultado->id ];
            $subject = $this->db->table('subject');
            $subject->set($datos);
            $subject->where('subject_id', $subject_id);
            $subject->update();
            
        } catch(Google_Service_Exception $gs){
        } catch(Exception $e){
        }
        //PERMISOS PARA EDITAR LAS PLANILLAS
        $newPermission = new \Google_Service_Drive_Permission(array('type' => "user", 'role' => "writer", 'emailAddress' => $emailDocente));
        try {
            $service->permissions->create($resultado->id, $newPermission, array('sendNotificationEmail'=>false));
        } catch (Exception $e) {
        }
        return $subject_id;

    }
    function configSheet($subject_id = '', $sheet_id = '', $students = array(), $curso='', $materia = '', $docente = '') {
        //***************NOS CONECTAMOS A GOOGLE SHEETS*************
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;
        //$range = "General!B2";
        //$response = $service->spreadsheets_values->get($spreadsheetId,$range);
        //$values = $response->getValues();
        //if (empty($values)) {
            //$rev['Datos Planilla'] = "Planilla Vacia";
        $nro = 1;
        foreach($students as $row):
            $fila = 7 + $nro;
            $data = [];
            $range = "1erTRIM!A".$fila.":C".$fila;
            $values= [[$row['student_id'], $nro, $row['student'], ]];
            $data[] = new \Google_Service_Sheets_ValueRange([
            'range' => $range,
            'majorDimension' => 'ROWS',
            'values' => $values
            ]);
            $requestBody = new \Google_Service_Sheets_BatchUpdateValuesRequest([
            "valueInputOption" => "USER_ENTERED",
            "data" => $data
            ]);
            $response = $service->spreadsheets_values->batchUpdate($spreadsheetId, $requestBody);
            $nro += 1;
        endforeach;

        //Curso, Materia

        //ACTUALIZAMOS LA TABLA
        $datos = [ "hours" => '1' ];
        $subject = $this->db->table('subject');
        $subject->set($datos);
        $subject->where('subject_id', $subject_id);
        $subject->update();
        sleep(15);
    }
    function protectedSheet($sheet_id = '', $subject_id = '') {
        //***************NOS CONECTAMOS A GOOGLE SHEETS*************
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;
        // Recuperamos el ID de hojas
        $response = $service->spreadsheets->get($spreadsheetId);
        $spreadsheetProperties = $response->getProperties();
        foreach($response->getSheets() as $sheet):
            $trim = "";
            // Properties of sheet
            $sheetProperties = $sheet->getProperties();
            switch ($sheetProperties->title){
                case "General":
                    $trim = $sheetProperties->sheetId;
                    //PROTEGEMOS HOJA GENERAL
                    $requests = [ new \Google_Service_Sheets_Request(["addProtectedRange" => ["protectedRange" => [
                                    "protectedRangeId" => "5000",
                                    "range" => [ "sheetId" => $trim, "startRowIndex" => 0, "endRowIndex" => 24, "startColumnIndex" => 0, "endColumnIndex" => 24 ],
                                    "description" => "Hoja General ",
                                    "warningOnly" => true ] ] ]) ];
                    try{
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
                    }catch (Exception $e) {}
                    //ADD RANGO en GENERAL
                    $requests = [ new \Google_Service_Sheets_Request(["addNamedRange" => ["namedRange" => [
                                    "namedRangeId" => "500",
                                    "name" => "general",
                                    "range" => [ "sheetId" => $trim, "startRowIndex" => 0, "endRowIndex" => 24, "startColumnIndex" => 0, "endColumnIndex" => 24 ] ] ] ]) ];
                    try{
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                    }catch (Exception $e) {}
                    //DAMOS PERMISOS A ADMINISTRADORES
                    $requests = [ new \Google_Service_Sheets_Request(["updateProtectedRange" => ["protectedRange" => [
                                "protectedRangeId" => "5000",
                                "namedRangeId" => "500",
                                "warningOnly" => false,
                                "editors" => [
                                    "users" => [ "saat@tiquipaya.edu.bo",
                                        "prueba-cargas@cargararchivos-308920.iam.gserviceaccount.com",
                                        "sheetssaat@saat-sheets.iam.gserviceaccount.com" ]
                                    ] ], "fields" => "namedRangeId,warningOnly,editors"
                            ] ]) ];
                    try{
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                    }catch (Exception $e) {}
                    break;
                case "Finales":
                    /*
                    $trim = $sheetProperties->sheetId;
                    //PROTEGEMOS HOJA FINAL
                    $requests = [ new \Google_Service_Sheets_Request(["addProtectedRange" => ["protectedRange" => [
                                    "protectedRangeId" => "6000",
                                    "range" => [ "sheetId" => $trim, "startRowIndex" => 0, "endRowIndex" => 24, "startColumnIndex" => 0, "endColumnIndex" => 24 ],
                                    "description" => "Hoja final",
                                    "warningOnly" => true
                                ] ] ]) ];
                    try{
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
                    }catch (Exception $e) {}
                    //ADD RANGO en FINAL
                    $requests = [ new \Google_Service_Sheets_Request(["addNamedRange" => ["namedRange" => [
                                    "namedRangeId" => "600",
                                    "name" => "final",
                                    "range" => [ "sheetId" => $trim, "startRowIndex" => 0, "endRowIndex" => 24, "startColumnIndex" => 0, "endColumnIndex" => 24 ]
                                ] ] ]) ];
                    try{
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                    }catch (Exception $e) {}
                    //DAMOS PERMISOS A ADMINISTRADORES
                    $requests = [ new \Google_Service_Sheets_Request(["updateProtectedRange" => ["protectedRange" => [
                                "protectedRangeId" => "6000",
                                "namedRangeId" => "600",
                                "warningOnly" => false,
                                "editors" => [
                                    "users" => [ "saat@tiquipaya.edu.bo",
                                        "prueba-cargas@cargararchivos-308920.iam.gserviceaccount.com",
                                        "sheetssaat@saat-sheets.iam.gserviceaccount.com" ]
                                    ] ], "fields" => "namedRangeId,warningOnly,editors"
                            ] ]) ];
                    try{
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                    }catch (Exception $e) {}
                    break;
                    */
            }
        endforeach;
        //ACTUALIZAMOS LA TABLA
        $datos = [ "partial_locked" => "1" ];
        $subject = $this->db->table('subject');
        $subject->set($datos);
        $subject->where('subject_id', $subject_id);
        $subject->update();
        sleep(15);
    }
    function protectedPhase($sheet_id = '', $subject_id = '', $phase_id = '') {
        //***************NOS CONECTAMOS A GOOGLE SHEETS*************
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;
        $trim = "";
        //RANGOS de DATOS a PROTEGER
        $listas = array( 
            0 => array(0,42,0,5), 1 => array(0,1,6,42), 2 => array(3,37,10,11), 3 => array(3,37,21,22), 
            4 => array(3,37,32,33), 5 => array(3,37,39,42), 6 => array(37,41,6,42), 7 => array(5,7,6,42)
        );
        // Recuperamos el ID de hojas
        $response = $service->spreadsheets->get($spreadsheetId);
        $spreadsheetProperties = $response->getProperties();
        foreach($response->getSheets() as $sheet):
            $trim = "";
            // Properties of sheet
            $sheetProperties = $sheet->getProperties();
            switch ($sheetProperties->title){
                case "1erTRIM":
                    $trim = $sheetProperties->sheetId;
                    $i = 0;
                    foreach($listas as $lista => $detalles)
                    {
                        //PROTEGEMOS 1erTRIM
                        $requests = [ new \Google_Service_Sheets_Request([ "addProtectedRange" => [ "protectedRange" => [
                                        "protectedRangeId" => "1100".$lista,
                                        "range" => [ "sheetId" => $trim, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ],
                                        "description" => "1er Trimestre ".$lista,
                                        "warningOnly" => true
                                    ] ] ]) ];
                        try{
                            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
                        }catch (Exception $e) {
                        }
                        //ADD RANGO en 1erTRIM
                        $requests = [ new \Google_Service_Sheets_Request([ "addNamedRange" => [ "namedRange" => [
                                        "namedRangeId" => "110".$lista,
                                        "name" => "primero".$lista,
                                        "range" => [ "sheetId" => $trim, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ]
                                    ] ] ]) ];
                        try{
                            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                        }catch (Exception $e) {
                        }
                        //DAMOS PERMISOS A ADMINISTRADORES
                        $requests = [ new \Google_Service_Sheets_Request([ "updateProtectedRange" => [ "protectedRange" => [
                                    "protectedRangeId" => "1100".$lista,
                                    "namedRangeId" => "110".$lista,
                                    "warningOnly" => false,
                                    "editors" => [
                                        "users" => [ "saat@tiquipaya.edu.bo",
                                            "prueba-cargas@cargararchivos-308920.iam.gserviceaccount.com",
                                            "sheetssaat@saat-sheets.iam.gserviceaccount.com" ]
                                        ]
                                    ], "fields" => "namedRangeId,warningOnly,editors"
                                ] ]) ];
                        try{
                            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                        }catch (Exception $e) {
                        }
                        $i += 1;
                    }
                    break;
                case "2doTRIMkk":
                    $trim = $sheetProperties->sheetId;
                    $i = 0;
                    foreach($listas as $lista => $detalles)
                    {
                        //PROTEGEMOS 1erTRIM
                        $requests = [ new \Google_Service_Sheets_Request([ "addProtectedRange" => [ "protectedRange" => [
                                        "protectedRangeId" => "1200".$lista,
                                        "range" => [ "sheetId" => $trim, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ],
                                        "description" => "2do Trimestre ".$lista,
                                        "warningOnly" => true
                                    ] ] ]) ];
                        try{
                            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
                        }catch (Exception $e) {
                        }
                        //ADD RANGO en 1erTRIM
                        $requests = [ new \Google_Service_Sheets_Request([ "addNamedRange" => [ "namedRange" => [
                                        "namedRangeId" => "120".$lista,
                                        "name" => "segundo".$lista,
                                        "range" => [ "sheetId" => $trim, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ]
                                    ] ] ]) ];
                        try{
                            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                        }catch (Exception $e) {
                        }
                        //DAMOS PERMISOS A ADMINISTRADORES
                        $requests = [ new \Google_Service_Sheets_Request([ "updateProtectedRange" => [ "protectedRange" => [
                                    "protectedRangeId" => "1200".$lista,
                                    "namedRangeId" => "120".$lista,
                                    "warningOnly" => false,
                                    "editors" => [
                                        "users" => [ "saat@tiquipaya.edu.bo",
                                            "prueba-cargas@cargararchivos-308920.iam.gserviceaccount.com",
                                            "sheetssaat@saat-sheets.iam.gserviceaccount.com" ]
                                        ]
                                    ], "fields" => "namedRangeId,warningOnly,editors"
                                ] ]) ];
                        try{
                            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                        }catch (Exception $e) {
                        }
                        $i += 1;
                    }
                    break;
                case "3erTRIMkk":
                    $trim = $sheetProperties->sheetId;
                    $i = 0;
                    foreach($listas as $lista => $detalles)
                    {
                        //PROTEGEMOS 1erTRIM
                        $requests = [ new \Google_Service_Sheets_Request([ "addProtectedRange" => [ "protectedRange" => [
                                        "protectedRangeId" => "1300".$lista,
                                        "range" => [ "sheetId" => $trim, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ],
                                        "description" => "3er Trimestre ".$lista,
                                        "warningOnly" => true
                                    ] ] ]) ];
                        try{
                            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
                        }catch (Exception $e) {
                        }
                        //ADD RANGO en 1erTRIM
                        $requests = [ new \Google_Service_Sheets_Request([ "addNamedRange" => [ "namedRange" => [
                                        "namedRangeId" => "130".$lista,
                                        "name" => "tercero".$lista,
                                        "range" => [ "sheetId" => $trim, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ]
                                    ] ] ]) ];
                        try{
                            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                        }catch (Exception $e) {
                        }
                        //DAMOS PERMISOS A ADMINISTRADORES
                        $requests = [ new \Google_Service_Sheets_Request([ "updateProtectedRange" => [ "protectedRange" => [
                                    "protectedRangeId" => "1300".$lista,
                                    "namedRangeId" => "130".$lista,
                                    "warningOnly" => false,
                                    "editors" => [
                                        "users" => [ "saat@tiquipaya.edu.bo",
                                            "prueba-cargas@cargararchivos-308920.iam.gserviceaccount.com",
                                            "sheetssaat@saat-sheets.iam.gserviceaccount.com" ]
                                        ]
                                    ], "fields" => "namedRangeId,warningOnly,editors"
                                ] ]) ];
                        try{
                            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                            $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                        }catch (Exception $e) {
                        }
                        $i += 1;
                    }
                    break;
            }
        endforeach;
        //ACTUALIZAMOS LA TABLA
        $datos = [ "locked" => "1" ];
        $subject = $this->db->table('subject');
        $subject->set($datos);
        $subject->where('subject_id', $subject_id);
        $subject->update();
    }
    function importNotes($sheet_id = '', $subject_id = '', $phase_id = '', $phase = '') {
        //***************NOS CONECTAMOS A GOOGLE SHEETS*************/
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;

        //LIMPIAMOS NOTAS
        $data = array();
        $dimensiones = array("ser", "saber", "hacer");
        foreach ($dimensiones as $dim) {
            switch ($dim) {
                case 'ser':   $columnas = array("1","2","3","4","5"); break;
                case 'saber': $columnas = array("1","2","3","4","5","6","7","8","9","10"); break;
                case 'hacer': $columnas = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15"); break;
                default:      $columnas = array();
            }
            foreach ($columnas as $ae) {
                $data[$dim.'_ae'.$ae] = 0;
            }
        }
        $data['ser_average']  = 0;
        $data['saber_average'] = 0;
        $data['hacer_average'] = 0;
        $data['total_average'] = 0;
        $data['total_vc'] = "";

        $csamarks = $this->db->table('csamarks');
        $csamarks->set($data);
        $csamarks->where('subject_id', $subject_id);
        $csamarks->where('phase_id', $phase_id);
        $csamarks->update();

        try {
            //Actualizamos promedios Trimestrales
            $range1 = $phase."!A8:AN37";
            $response = $service->spreadsheets_values->get($spreadsheetId, $range1);
            $values = $response->getValues();
            foreach ($values as $row) {
                if (isset($row[0])) {
                    $student_id = $row[0];
                    $data = array();
                    $dimensiones = array("ser", "saber", "hacer");
                    foreach ($dimensiones as $dim) {
                        switch ($dim) {
                            case 'ser':   $columnas = array("1","2","3","4","5"); break;
                            case 'saber': $columnas = array("1","2","3","4","5","6","7","8","9","10"); break;
                            case 'hacer': $columnas = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15"); break;
                            default:      $columnas = array();
                        }
                        foreach ($columnas as $ae) {
                            switch ($dim) {
                                case 'ser':   $celda = $ae + 4;  break;
                                case 'saber': $celda = $ae + 10; break;
                                case 'hacer': $celda = $ae + 21; break;
                                default:      $celda = 0;
                            }
                            if (isset($row[$celda])) {
                                $data[$dim.'_ae'.$ae] = ($row[$celda] === '') ? NULL : $row[$celda];
                            } else {
                                $data[$dim.'_ae'.$ae] = NULL;
                            }
                        }
                    }
                    if (isset($row[10]))  { $data['ser_average']   = $row[10]; }
                    if (isset($row[21]))  { $data['saber_average']  = $row[21]; }
                    if (isset($row[37]))  { $data['hacer_average']  = $row[37]; }
                    if (isset($row[38]))  { $data['autoevaluacion'] = $row[38]; }
                    if (isset($row[39]))  { $data['total_average']  = $row[39]; }
                    if (isset($row[3]))   { $data['total_vc']       = $row[3];  }
                    $data['saved_on'] = date("Y-m-d");
                    $csamarks->set($data);
                    $csamarks->where('subject_id', $subject_id);
                    $csamarks->where('student_id', $student_id);
                    $csamarks->where('phase_id', $phase_id);
                    $csamarks->update();
                    unset($data);
                    $data = array();
                }
            }
            //Actualizamos Detalles
            $csamarks_details = $this->db->table('csamarks_details');
            $csamarks_details->where('subject_id', $subject_id);
            $csamarks_details->where('phase_id', $phase_id);
            $csamarks_details->delete();

            $range1 = $phase."!A3:AT4";
            $response = $service->spreadsheets_values->get($spreadsheetId, $range1);
            $values = $response->getValues();
            $c = 0;
            foreach ($values as $row) {
                $dimensiones = array("ser", "saber", "hacer");
                foreach ($dimensiones as $dim) {
                    switch ($dim) {
                        case 'ser':   $columnas = array("1","2","3","4","5"); break;
                        case 'saber': $columnas = array("1","2","3","4","5","6","7","8","9","10"); break;
                        case 'hacer': $columnas = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15"); break;
                        default:      $columnas = array();
                    }
                    foreach ($columnas as $ae) {
                        switch ($dim) {
                            case 'ser':   $celda = $ae + 4;  break;
                            case 'saber': $celda = $ae + 10; break;
                            case 'hacer': $celda = $ae + 21; break;
                            default:      $celda = 0;
                        }
                        if (isset($row[$celda]) && $row[$celda] !== "" && $row[$celda] != 0) {
                            $data2 = array();
                            $csamarks_details->where('columna', $dim.'_ae'.$ae);
                            $csamarks_details->where('subject_id', $subject_id);
                            $csamarks_details->where('phase_id', $phase_id);
                            $result = $csamarks_details->get()->getResultArray();
                            if (count($result) == 1) {
                                $data2 = ($c == 0) ? ['record_date' => $row[$celda]] : ['name' => $row[$celda]];
                                $csamarks_details->set($data2);
                                $csamarks_details->where('columna', $dim.'_ae'.$ae);
                                $csamarks_details->where('subject_id', $subject_id);
                                $csamarks_details->where('phase_id', $phase_id);
                                $csamarks_details->update();
                            } else {
                                $data2['columna']    = $dim.'_ae'.$ae;
                                $data2['locked']     = 0;
                                $data2['phase_id']   = $phase_id;
                                $data2['subject_id'] = $subject_id;
                                if ($c == 0) {
                                    $data2['record_date'] = $row[$celda];
                                } else {
                                    $data2['name'] = $row[$celda];
                                }
                                $csamarks_details->insert($data2);
                            }
                        }
                    }
                }
                $c += 1;
            }
        } catch (\Google\Service\Exception $e) {
            return ['error' => $e->getMessage(), 'code' => $e->getCode()];
        }
    }
    function recoverSelf($sheet_id, $subject_id, $phase_id, $teacher_id, $abreviado)
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;

        // 1. Leer solo la columna A con los student_ids (filas 8 a 37, máx 30 estudiantes)
        $rangeA = $abreviado . '!A8:A37';
        $respA = $service->spreadsheets_values->get($spreadsheetId, $rangeA);
        $colA  = $respA->getValues() ?? [];

        // Extraer student_ids válidos y su fila en el sheet
        $studentRows = []; // [student_id => numero_fila]
        foreach ($colA as $i => $row) {
            if (!empty($row[0]) && is_numeric($row[0])) {
                $studentRows[(int)$row[0]] = 8 + $i; // fila real en el sheet
            }
        }

        if (empty($studentRows)) {
            return;
        }

        // 2. Una sola consulta MySQL para todas las autoevaluaciones
        $ids = implode(',', array_keys($studentRows));
        $autos = $this->db->query(
            'SELECT student_id, autoevaluacion
             FROM self_appraisal
             WHERE phase_id = ' . (int)$phase_id . '
               AND student_id IN (' . $ids . ')'
        )->getResultArray();

        if (empty($autos)) {
            return;
        }

        // Indexar por student_id para acceso rápido
        $autoMap = [];
        foreach ($autos as $a) {
            $autoMap[(int)$a['student_id']] = $a['autoevaluacion'];
        }

        // 3. Actualizar MySQL en batch y construir rangos para Google Sheets
        $sheetData = [];
        foreach ($autoMap as $student_id => $valor) {
            if (!isset($studentRows[$student_id])) continue;

            // Actualizar csamarks en MySQL
            $this->db->table('csamarks')
                ->set(['autoevaluacion' => $valor])
                ->where('subject_id', $subject_id)
                ->where('student_id', $student_id)
                ->where('phase_id', $phase_id)
                ->update();

            // Agregar rango para batchUpdate de Sheets
            $fila = $studentRows[$student_id];
            $sheetData[] = new \Google_Service_Sheets_ValueRange([
                'range'          => $abreviado . '!AM' . $fila,
                'majorDimension' => 'ROWS',
                'values'         => [[$valor]],
            ]);
        }

        // 4. Un solo batchUpdate para todas las celdas AM
        if (!empty($sheetData)) {
            $requestBody = new \Google_Service_Sheets_BatchUpdateValuesRequest([
                'valueInputOption' => 'USER_ENTERED',
                'data'             => $sheetData,
            ]);
            $service->spreadsheets_values->batchUpdate($spreadsheetId, $requestBody);
        }
    }
    /**
     * $phase_id: trimestre ACTUAL (según Settings), usado para que los
     * puntos SER se calculen solo con datos de behavior_log/daily_scores de
     * ese trimestre (vía join con asistencia.attendance_dates.phase_id).
     * Antes no se filtraba por trimestre, así que si el docente no había
     * cargado nada todavía en el trimestre actual, se recuperaba el último
     * dato disponible (de un trimestre anterior) en vez de partir de cero.
     */
    function recoverScore($sheet_id, $subject_id, $abreviado, $section_id, $teacher_id, $phase_id = null)
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service   = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;

        // 1. Leer columna A (filas 8-37, máx 30 estudiantes) para obtener student_ids y su fila
        $respA      = $service->spreadsheets_values->get($spreadsheetId, $abreviado . '!A8:A37');
        $colA       = $respA->getValues() ?? [];
        $studentRows = [];
        foreach ($colA as $i => $row) {
            if (!empty($row[0]) && is_numeric($row[0])) {
                $studentRows[(int)$row[0]] = 8 + $i;
            }
        }

        if (empty($studentRows)) {
            return;
        }

        // 2. Query según nivel: Primaria usa behavior_log filtrado por trimestre
        // actual (join con asistencia.attendance_dates.phase_id); Secundaria usa
        // IncidenciaModel::calcularNota(), el mismo cálculo de "Puntos del Ser"
        // que Teacher::student_profile(), que ya filtra por phase_id.
        //
        // El nivel se determina por section.grade (¿contiene "Secundaria"?), NO
        // por un umbral numérico de section_id: los section_id de 1ro/2do de
        // Secundaria (271-290) caen en el mismo rango "2xx" que Primaria, así
        // que cualquier corte numérico (< 271, < 400, etc.) clasifica mal
        // alguna sección tarde o temprano. Esto es justamente lo que pasaba:
        // la sección 311 ("3ro de Secundaria") caía en la rama de Primaria
        // (que no tiene nada en behavior_log para esa materia) y por eso
        // TODOS los estudiantes recuperaban el valor por defecto (10).
        $ids = implode(',', array_keys($studentRows));
        $phaseFilter = $phase_id !== null ? (int) $phase_id : null;
        $sectionInfo = $this->db->table('section')->select('grade')->where('section_id', $section_id)->get()->getRowArray();
        $esSecundaria = $sectionInfo && stripos($sectionInfo['grade'], 'secundaria') !== false;

        helper('grade');
        $esPrimaria36 = $sectionInfo && isPrimaria36($sectionInfo['grade']);

        if ($esPrimaria36) {
            // Primaria 3ro-6to: MISMO cálculo que Teacher::student_profile()
            // usa para "Puntos del Ser" en el historial de comportamiento de
            // primaria (IncidenciaModel::getConteosByTeacher), en vez del
            // conteo por behavior_log (que es el que usan 1ro/2do de
            // Primaria e Inicial, sin tocar). Se usa la versión bulk para
            // no hacer una consulta por estudiante.
            $scores = [];
            if ($phaseFilter !== null) {
                $IncidenciaMod = new IncidenciaModel();
                $conteos = $IncidenciaMod->getConteosBulkByTeacher(array_keys($studentRows), $teacher_id, $section_id, $phaseFilter);
                foreach ($conteos as $student_id => $c) {
                    $scores[] = [
                        'student_id' => $student_id,
                        'score'      => max(1, (int) round($c['nota'])),
                    ];
                }
            }
        } elseif (!$esSecundaria) {
            // Primaria 1ro/2do e Inicial: score = 100 - puntos negativos de
            // comportamiento del trimestre actual, ponderado a 10
            $sql = 'SELECT bl.student_id,
                        GREATEST(1, ROUND(LEAST(100, 100 - COALESCE(SUM(bt.points), 0)) / 10)) AS score
                 FROM tiqui0_tiquiweb26.behavior_log bl
                 INNER JOIN tiqui0_tiquiweb26.behavior_types bt ON bl.behavior_type_id = bt.id
                 INNER JOIN subject s ON bl.subject_id = s.subject_id
                 INNER JOIN tiqui0_tiquiasis26.attendance_dates ad ON ad.date_id = bl.date_id
                 WHERE s.teacher_id = ' . (int)$teacher_id . '
                   AND bl.subject_id = ' . (int)$subject_id . '
                   AND bl.student_id IN (' . $ids . ')';
            if ($phaseFilter !== null) {
                $sql .= ' AND ad.phase_id = ' . $phaseFilter;
            }
            $sql .= ' GROUP BY bl.student_id, s.teacher_id';
            $scores = $this->db->query($sql)->getResultArray();
        } else {
            // Secundaria: mismo cálculo de "Puntos del Ser" que usa
            // Teacher::student_profile() (IncidenciaModel::calcularNota), en
            // vez de leer directo de daily_scores (que no se podía acotar de
            // forma confiable al trimestre actual). calcularNota ya
            // considera solo incidencias/boletas del $phase_id indicado y
            // devuelve la nota en la misma escala 1-10 que espera la hoja.
            $scores = [];
            if ($phaseFilter !== null) {
                $IncidenciaMod = new IncidenciaModel();
                foreach (array_keys($studentRows) as $student_id) {
                    //calcularNota() devuelve con 1 decimal (ej. 9.5); la
                    //planilla de Google Sheets solo debe recibir enteros.
                    $nota = round($IncidenciaMod->calcularNota($student_id, $subject_id, $phaseFilter));
                    $scores[] = [
                        'student_id' => $student_id,
                        'score'      => max(1, (int) $nota),
                    ];
                }
            }
        }

        // 3. Construir mapa de scores reales por student_id
        $scoreMap = [];
        foreach ($scores as $s) {
            $scoreMap[(int)$s['student_id']] = $s['score'];
        }

        // 4. Un solo batchUpdate: cabeceras + score por estudiante (real o default 10)
        $today = date('d/m/Y');
        $sheetData = [
            new \Google_Service_Sheets_ValueRange([
                'range'          => $abreviado . '!F4',
                'majorDimension' => 'ROWS',
                'values'         => [['RÚBRICA DEL SER']],
            ]),
            new \Google_Service_Sheets_ValueRange([
                'range'          => $abreviado . '!F5',
                'majorDimension' => 'ROWS',
                'values'         => [['RÚBRICA']],
            ]),
            new \Google_Service_Sheets_ValueRange([
                'range'          => $abreviado . '!F6',
                'majorDimension' => 'ROWS',
                'values'         => [[$today]],
            ]),
        ];
        foreach ($studentRows as $student_id => $fila) {
            $sheetData[] = new \Google_Service_Sheets_ValueRange([
                'range'          => $abreviado . '!F' . $fila,
                'majorDimension' => 'ROWS',
                'values'         => [[isset($scoreMap[$student_id]) ? $scoreMap[$student_id] : 10]],
            ]);
        }
        $requestBody = new \Google_Service_Sheets_BatchUpdateValuesRequest([
            'valueInputOption' => 'USER_ENTERED',
            'data'             => $sheetData,
        ]);
        $service->spreadsheets_values->batchUpdate($spreadsheetId, $requestBody);
    }
    /**
     * Bloquea (protege) la hoja de un trimestre en el Google Sheet de una
     * materia al consolidar notas, para que el docente ya no pueda editarla.
     * Reemplaza a lockedSheet()/lockedSheet3() (que dejaban abajo, se
     * mantienen como wrappers de compatibilidad), que solo cubrían el 1er y
     * 3er trimestre y tenían la hoja hardcodeada ("1erTRIM"/"3erTRIM") sin
     * importar qué trimestre se estuviera consolidando. Ahora recibe
     * $phase_id (1, 2 o 3) y usa la hoja correspondiente:
     * 1 => "1erTRIM", 2 => "2doTRIM", 3 => "3erTRIM".
     *
     * IMPORTANTE sobre manejo de errores: los catch(Exception $e) originales
     * de este archivo NUNCA atrapaban nada, porque este archivo tiene
     * "namespace App\Models;" sin "use Exception;" — "Exception" a secas se
     * resuelve como "App\Models\Exception" (que no existe), así que la
     * excepción real de Google (Google\Service\Exception, p. ej. el 400
     * "You are trying to edit a protected cell or object" cuando dos
     * consolidaciones pisan el mismo rango protegido) pasaba de largo sin
     * capturarse. Acá se captura \Google\Service\Exception explícitamente
     * (con \Throwable de respaldo) en _batchUpdateSafe(), se registra con
     * log_message() y se sigue con el resto de las operaciones en vez de
     * tumbar toda la consolidación.
     *
     * @return array{success:bool,message:string,warnings:array<int,string>}
     */
    function lockedSheetByPhase($sheet_id = '', $phase_id = 1)
    {
        $sheetTitles = [1 => '1erTRIM', 2 => '2doTRIM', 3 => '3erTRIM'];
        $prefixes    = [1 => '1', 2 => '2', 3 => '3'];
        $labels      = [1 => '1er Trimestre', 2 => '2do Trimestre', 3 => '3er Trimestre'];
        $names       = [1 => 'Primero', 2 => 'Segundo', 3 => 'Tercero'];

        $phase_id = (int) $phase_id;
        if (!isset($sheetTitles[$phase_id])) {
            return ['success' => false, 'message' => 'Trimestre inválido: ' . $phase_id, 'warnings' => []];
        }
        $sheetTitle = $sheetTitles[$phase_id];
        $prefix     = $prefixes[$phase_id];
        $label      = $labels[$phase_id];
        $name       = $names[$phase_id];

        //***************NOS CONECTAMOS A GOOGLE SHEETS*************
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH . '/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;

        try {
            $response = $service->spreadsheets->get($spreadsheetId);
        } catch (\Google\Service\Exception $e) {
            log_message('error', 'lockedSheetByPhase: no se pudo leer la planilla ' . $spreadsheetId . ': ' . $e->getMessage());
            return ['success' => false, 'message' => 'No se pudo conectar con la planilla de Google Sheets: ' . $e->getMessage(), 'warnings' => []];
        }

        //PROCESO DE BLOQUEO DE PERMISOS
        // Recuperamos el ID de la hoja del trimestre correspondiente
        $pri      = null;
        $rangesId = [];
        $namesId  = [];
        foreach ($response->getSheets() as $sheet) {
            $sheetProperties = $sheet->getProperties();
            if ($sheetProperties->title === $sheetTitle) {
                $pri = $sheetProperties->sheetId;
                foreach ($sheet->getProtectedRanges() as $r) {
                    if ($r->protectedRangeId != null) {
                        $rangesId[] = $r->protectedRangeId;
                    }
                    if ($r->namedRangeId != null) {
                        $namesId[] = $r->namedRangeId;
                    }
                }
                break;
            }
        }

        if ($pri === null) {
            return ['success' => false, 'message' => 'No se encontró la hoja "' . $sheetTitle . '" en la planilla.', 'warnings' => []];
        }

        $errores = [];

        //ELIMINAMOS RANGOS Y NOMBRES YA EXISTENTES (de una consolidación
        //previa, por ejemplo) antes de volver a protegerlos
        foreach ($rangesId as $r) {
            $this->_batchUpdateSafe($service, $spreadsheetId, [
                new \Google_Service_Sheets_Request(["deleteProtectedRange" => ["protectedRangeId" => $r]]),
            ], $errores);
        }
        foreach ($namesId as $n) {
            $this->_batchUpdateSafe($service, $spreadsheetId, [
                new \Google_Service_Sheets_Request(["deleteNamedRange" => ["namedRangeId" => $n]]),
            ], $errores);
        }

        //PROTEGEMOS LA HOJA DEL TRIMESTRE
        $listas = array(0 => array(0, 42, 0, 59));
        foreach ($listas as $lista => $detalles) {
            //PROTEGEMOS FINALES
            $this->_batchUpdateSafe($service, $spreadsheetId, [
                new \Google_Service_Sheets_Request([
                    "addProtectedRange" => [
                        "protectedRange" => [
                            "protectedRangeId" => $prefix . $lista,
                            "range" => ["sheetId" => $pri, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3]],
                            "description" => $label . ' ' . $lista,
                            "warningOnly" => true,
                        ],
                    ],
                ]),
            ], $errores);

            //ADD RANGO
            $this->_batchUpdateSafe($service, $spreadsheetId, [
                new \Google_Service_Sheets_Request([
                    "addNamedRange" => [
                        "namedRange" => [
                            "namedRangeId" => $prefix . $prefix . $prefix . $lista,
                            "name" => $name . $lista,
                            "range" => ["sheetId" => $pri, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3]],
                        ],
                    ],
                ]),
            ], $errores);

            //DAMOS PERMISOS A ADMINISTRADORES
            $this->_batchUpdateSafe($service, $spreadsheetId, [
                new \Google_Service_Sheets_Request([
                    "updateProtectedRange" => [
                        "protectedRange" => [
                            "protectedRangeId" => $prefix . $lista,
                            "namedRangeId" => $prefix . $prefix . $prefix . $lista,
                            "warningOnly" => false,
                            "editors" => [
                                "users" => [
                                    "saat@tiquipaya.edu.bo",
                                    "prueba-cargas@cargararchivos-308920.iam.gserviceaccount.com",
                                    "sheetssaat@saat-sheets.iam.gserviceaccount.com",
                                ],
                            ],
                        ],
                        "fields" => "namedRangeId,warningOnly,editors",
                    ],
                ]),
            ], $errores);
        }

        if (count($errores) > 0) {
            log_message('error', 'lockedSheetByPhase (' . $sheetTitle . ', planilla ' . $spreadsheetId . '): ' . implode(' | ', $errores));
        }

        return ['success' => true, 'message' => 'Hoja "' . $sheetTitle . '" bloqueada.', 'warnings' => $errores];
    }

    /**
     * Ejecuta un batchUpdate individual "a prueba de fallos": si Google
     * responde 400 porque el rango/celda ya está protegido por otro objeto
     * (p. ej. "You are trying to edit a protected cell or object"), o
     * cualquier otro error de la API, lo registramos en $errores y seguimos
     * con las demás operaciones en vez de dejar que tumbe todo el proceso.
     */
    private function _batchUpdateSafe($service, $spreadsheetId, array $requests, array &$errores): void
    {
        try {
            $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest(['requests' => $requests]);
            $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
        } catch (\Google\Service\Exception $e) {
            $errores[] = $e->getMessage();
        } catch (\Throwable $e) {
            $errores[] = $e->getMessage();
        }
    }

    /**
     * @deprecated usar lockedSheetByPhase($sheet_id, 1). Se mantiene por si
     * queda algún otro llamador directo a lockedSheet().
     */
    function lockedSheet($sheet_id = '')
    {
        return $this->lockedSheetByPhase($sheet_id, 1);
    }

    /**
     * @deprecated usar lockedSheetByPhase($sheet_id, 3). Se mantiene por si
     * queda algún otro llamador directo a lockedSheet3().
     */
    function lockedSheet3($sheet_id = '', $emailDocente = '')
    {
        return $this->lockedSheetByPhase($sheet_id, 3);
    }
    function enable_sheet_phase($phase_id='', $phase='', $subject_id='', $sheet_id = ''){
        //Habilitamos la Planilla
        //ACTUALIZAMOS LA TABLA
        $datos = [ "hours" => "1" ];
        $subject = $this->db->table('subject');
        $subject->set($datos);
        $subject->where('subject_id', $subject_id);
        $subject->update();
        //***************NOS CONECTAMOS A GOOGLE SHEETS*************
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;

        $response = $service->spreadsheets->get($spreadsheetId);
        //$spreadsheetProperties = $response->getProperties();
        $trim = "";
        foreach($response->getSheets() as $sheet) {
            // Properties of sheet SOLO DEL TRIMESTRE ACTUAL
            $sheetProperties = $sheet->getProperties();
            $sheetRangos = $sheet->getProtectedRanges();
            if ($sheetProperties->title==$phase){
                $trim = $sheetProperties->sheetId;
                //RANGOS de DATOS a PROTEGER
                $listas = array( 
                    1 => array(0,42,0,5), 2 => array(0,1,6,42), 3 => array(3,37,10,11), 4 => array(3,37,21,22), 
                    5 => array(3,37,32,33), 6 => array(3,37,39,42), 7 => array(37,41,6,42), 8 => array(5,7,6,42)
                );
                $i = 0;
                foreach($listas as $lista => $detalles)
                {
                    //PROTEGEMOS 1erTRIM
                    $requests = [ new \Google_Service_Sheets_Request([ "addProtectedRange" => [ "protectedRange" => [
                                    "protectedRangeId" => "1300".$lista,
                                    "range" => [ "sheetId" => $trim, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ],
                                    "description" => "3er Trimestre ".$lista,
                                    "warningOnly" => true
                                ] ] ]) ];
                    try{
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
                    }catch (Exception $e) {
                    }
                    //ADD RANGO en 1erTRIM
                    $requests = [ new \Google_Service_Sheets_Request([ "addNamedRange" => [ "namedRange" => [
                                    "namedRangeId" => "130".$lista,
                                    "name" => "tercero".$lista,
                                    "range" => [ "sheetId" => $trim, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ]
                                ] ] ]) ];
                    try{
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                    }catch (Exception $e) {
                    }
                    //DAMOS PERMISOS A ADMINISTRADORES
                    $requests = [ new \Google_Service_Sheets_Request([ "updateProtectedRange" => [ "protectedRange" => [
                                "protectedRangeId" => "1300".$lista,
                                "namedRangeId" => "130".$lista,
                                "warningOnly" => false,
                                "editors" => [
                                    "users" => [ "saat@tiquipaya.edu.bo",
                                        "prueba-cargas@cargararchivos-308920.iam.gserviceaccount.com",
                                        "sheetssaat@saat-sheets.iam.gserviceaccount.com" ]
                                    ]
                                ], "fields" => "namedRangeId,warningOnly,editors"
                            ] ]) ];
                    try{
                        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                        $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
                    }catch (Exception $e) {
                    }
                    $i += 1;
                }
            }
        }


/*

        //PROCESO DE BLOQUEO DE PERMISOS
        // Recuperamos el ID de hojas
        $response = $service->spreadsheets->get($spreadsheetId);
        $spreadsheetProperties = $response->getProperties();
        //$sheet =$response->getSheets();
        
        $rangesId=[];
        $namesId=[];
        foreach($response->getSheets() as $sheet) {
            // Properties of sheet SOLO DEL 1ER TRIMESTRE
            $sheetProperties = $sheet->getProperties();
            $sheetRangos = $sheet->getProtectedRanges();
            if ($sheetProperties->title=="2doTRIM"){
                $trim = $sheetProperties->sheetId;
                if (!empty($sheetRangos)) {
                    foreach ($sheetRangos as $r){
                        if($r->protectedRangeId!=NULL){
                            $rangesId[] = $r->protectedRangeId;
                        }
                        if($r->namedRangeId!=NULL){
                            $namesId[] = $r->namedRangeId;
                        }
                    }
                }
            }
        }
        foreach ($rangesId as $r) {
            //ELIMINAMOS RANGOS
            $requests = [ 
                new \Google_Service_Sheets_Request([
                    "deleteProtectedRange" => [
                        "protectedRangeId" => $r
                    ]
                ])
            ];
            try{
                $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
            }catch (Exception $e) {
            }
            
        }
        foreach ($namesId as $n) {
            //ELIMINAMOS NAMESID
            $requests = [ 
                new \Google_Service_Sheets_Request([
                    "deleteNamedRange" => [
                        "namedRangeId" => $n
                    ]
                ])
            ];
            try{
                $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
            }catch (Exception $e) {
            }
            
        }
        */
        

        //sleep(5);
        
        //return $m;
    }
    function low_update($sheet_id='')
    {
            
            //****************NOS CONECTAMOS A GOOGLE SHEETs*******************************************************
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;
        /*
        $range = $abreviado."!A8:A37";
        $response = $service->spreadsheets_values->get($spreadsheetId,$range);
        $values = $response->getValues();
        $nro = 1;
        foreach ($values as $row) {
            $fila = 7 + $nro;
            
            if (is_numeric($row[0])) {
                $student_id = $row[0];
                $sql2 = 'SELECT csamarks_id FROM csamarks WHERE  phase_id='.$phase_id.' AND subject_id = '.$subject_id;
                $csamarks = $this->db->query($sql2)->getResultArray();
                if (count($csamarks)!=0) {
                        $consulta2 = 'SELECT ser100 FROM self_appraisal WHERE student_id='.$student_id.' AND phase_id='.$phase_id;
                        $autos = $this->db->query($consulta2)->getResultArray();
                        foreach($autos as $auto):
                            $csamarks = $this->db->table('csamarks');
                            //ACTUALIZAMOS MYSQL
                            $dataMysql['autoevaluacion']=$auto['ser100'];
                            //$dataMysql['auto_decidir']=$auto['dec5'];
                            $csamarks->set($dataMysql);
                            $csamarks->where('subject_id', $subject_id);
                            $csamarks->where('student_id', $student_id);
                            $csamarks->where('phase_id', $phase_id);
                            $csamarks->update();
                            //ACTUALIZAMOS GOOGLE SHEET
                            $data = [];
                            $range = $abreviado."!AS".$fila;
                            $values= [[$auto['ser100'], ]];
                            $data[] = new \Google_Service_Sheets_ValueRange([
                                'range' => $range,
                                'majorDimension' => 'ROWS',
                                'values' => $values
                            ]);
                            $requestBody = new \Google_Service_Sheets_BatchUpdateValuesRequest([
                                "valueInputOption" => "USER_ENTERED",
                                "data" => $data
                            ]);
                            $response = $service->spreadsheets_values->batchUpdate($spreadsheetId, $requestBody);
                        endforeach;
                }
            }
            $nro += 1;
            
        }
        */
        // Rango que quieres limpiar
        $range = 'Hoja1!B6:T35'; // Reemplaza 'Hoja1' con el nombre de tu hoja si es necesario

        // Valores vacíos para limpiar el rango
        $values = array_fill(0, 30, array_fill(0, 19, ''));

        // Configurar la petición para limpiar los datos
        $data = [];
        $data[] = new \Google_Service_Sheets_ValueRange([
            'range' => $range,
            'majorDimension' => 'ROWS',
            'values' => $values
        ]);

        $requestBody = new \Google_Service_Sheets_BatchUpdateValuesRequest([
            'valueInputOption' => 'RAW',
            'data' => $data
        ]);

        $response = $service->spreadsheets_values->batchUpdate($spreadsheetId, $requestBody);

    }
    function centralize_especialidad($sheet_id, $subject_id, $phase_id, $abreviado, $esp)
    {
        //****************NOS CONECTAMOS A GOOGLE SHEETs*******************************************************
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;
        $range = $abreviado."!A8:AN37";
        $response = $service->spreadsheets_values->get($spreadsheetId,$range);
        $values = $response->getValues();
        $nro = 1;
        foreach ($values as $row) {
            $fila = 7 + $nro;
            if (is_numeric($row[0])) {
                $student_id = $row[0];
                if (is_numeric($row[39])) {
                    $nota = $row[39];
                    //Verificamos que el estudiante no tenga notas
                    $sql1 = 'SELECT total_average FROM csamarks WHERE student_id='.$student_id.' AND subject_id='.$subject_id.' AND phase_id='.$phase_id;
                    $filas = $this->db->query($sql1)->getResultArray();
                    $csamarks = $this->db->table('csamarks');
                    if (count($filas)==0) {
                        //Insertamos nueva nota
                        
                        $data1['student_id'] = $student_id;
                        $data1['total_average'] = $nota;
                        $data1['total_vc'] = $esp;
                        $data1['subject_id'] = $subject_id;
                        $data1['locked'] = 0;
                        $data1['phase_id'] = $phase_id;
                        $csamarks->insert($data1);
                    }else{
                        //Actualiuzamos nota
                        $data2['total_average'] = $nota;
                        $csamarks->set($data2);
                        $csamarks->where('student_id', $student_id);
                        $csamarks->where('subject_id', $subject_id);
                        $csamarks->where('phase_id', $phase_id);
                        $csamarks->update();
                    }

                }
            }
            $nro += 1;
        }
    }
}