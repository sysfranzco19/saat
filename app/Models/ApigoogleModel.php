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
        /*
        $data1 = [];
        $range1 = "General!B1:B2";
        $value1 = [[$curso, $materia, ]];
        $data1[] = new \Google_Service_Sheets_ValueRange([ 'range' => $range1,'majorDimension' => 'COLUMNS', 'values' => $value1]);
        $requestBody1 = new \Google_Service_Sheets_BatchUpdateValuesRequest(["valueInputOption" => "USER_ENTERED", "data" => $data1]);
        $response1 = $service->spreadsheets_values->batchUpdate($spreadsheetId, $requestBody1);
        $data2 = [];
        $range2 = "General!K1";
        $value2 = [[$docente]];
        $data2[] = new \Google_Service_Sheets_ValueRange([ 'range' => $range2,'majorDimension' => 'ROWS', 'values' => $value2]);
        $requestBody2 = new \Google_Service_Sheets_BatchUpdateValuesRequest(["valueInputOption" => "USER_ENTERED", "data" => $data2]);
        $response2 = $service->spreadsheets_values->batchUpdate($spreadsheetId, $requestBody2);
        */
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

            //****************NOS CONECTAMOS A GOOGLE SHEETs*******************************************************
            $client = new \Google_Client();
            $client->setApplicationName('Google Sheets and PHP');
            $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
            $client->setAccessType('offline');
            $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
            $service = new \Google_Service_Sheets($client);
            $spreadsheetId = $sheet_id;
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
            /*
            SOLO ACTUALIZA EL NOMBRE DE LA NOTA FINAL
            //ACTUALIZAMOS TRIM
            $data = [];
            $range = $abreviado."!AP3";
            $values= [["NOTA ".$abreviado ]];
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
            */
    }
    function lockedSheet($sheet_id = ''){
        //***************NOS CONECTAMOS A GOOGLE SHEETS*************
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;
        //PROCESO DE BLOQUEO DE PERMISOS
        // Recuperamos el ID de hojas
        $response = $service->spreadsheets->get($spreadsheetId);
        $spreadsheetProperties = $response->getProperties();
        //$sheet =$response->getSheets();
        //$rev['Conexión con la Planilla'] = '<pre>'.var_export($sheet , true).'</pre>'."\ n";
        $rangesId=[];
        $namesId=[];
        foreach($response->getSheets() as $sheet) {
            // Properties of sheet SOLO DEL 1ER TRIMESTRE
            $sheetProperties = $sheet->getProperties();
            $sheetRangos = $sheet->getProtectedRanges();
            switch ($sheetProperties->title){
                case "1erTRIM":
                    $pri = $sheetProperties->sheetId;
                    foreach ($sheetRangos as $r){
                        if($r->protectedRangeId!=NULL){
                            $rangesId[] = $r->protectedRangeId;
                        }
                        if($r->namedRangeId!=NULL){
                            $namesId[] = $r->namedRangeId;
                        }
                    }
                    break;
            }
        }
        foreach ($rangesId as $r) {
            //$rev['Conexión con la Planilla Ranges'] = '<pre>'.$r.'</pre>'."\ n";
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
            //$rev['Conexión con la Planilla Names'] = '<pre>'.$n.'</pre>'."\ n";
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
        //PROTEGEMOS 1ER TRIMESTRE
        $listas = array(0 => array(0,42,0,59));
        $i=0;
        foreach($listas as $lista => $detalles)
            {
            //PROTEGEMOS FINALES
            $requests = [ 
                new \Google_Service_Sheets_Request([
                    "addProtectedRange" => [
                        "protectedRange" => [
                            "protectedRangeId" => "1".$lista,
                            "range" => [ "sheetId" => $pri, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ],
                            "description" => "1er Trimestre ".$lista,
                            "warningOnly" => true
                        ]
                    ]
                ])
            ];
            try{
                $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
            }catch (Exception $e) {
                //$rev['Excepcion Permiso 1.1']= $e->getMessage();
            }
            //ADD RANGO en 1ER
            $requests = [ 
                new \Google_Service_Sheets_Request([
                    "addNamedRange" => [
                        "namedRange" => [
                            "namedRangeId" => "111".$lista,
                            "name" => "Primero".$lista,
                            "range" => [ "sheetId" => $pri, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ]
                        ]
                    ]
                ])
            ];
            try{
                $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
            }catch (Exception $e) {
                //$rev['Excepcion Permiso 1.2']= $e->getMessage();
            }
            //DAMOS PERMISOS A ADMINISTRADORES
            $requests = [ 
            new \Google_Service_Sheets_Request([
                "updateProtectedRange" => [
                    "protectedRange" => [
                        "protectedRangeId" => "1".$lista,
                        "namedRangeId" => "111".$lista,
                        "warningOnly" => false,
                        "editors" => [
                            "users" => [ "saat@tiquipaya.edu.bo",
                                "prueba-cargas@cargararchivos-308920.iam.gserviceaccount.com",
                                "sheetssaat@saat-sheets.iam.gserviceaccount.com" ]
                            ]
                        ],
                        "fields" => "namedRangeId,warningOnly,editors"
                    ]
                ])
            ];
            try{
                $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
            }catch (Exception $e) {
                
            }
        }
        

    }
    function lockedSheet3($sheet_id = '', $emailDocente = ''){
        //***************NOS CONECTAMOS A GOOGLE SHEETS 3er trimestre*************
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        $client->setAccessType('offline');
        $client->setAuthConfig(APPPATH.'/ThirdParty/api-sheet/Saat-Sheets-f0cf6437dbb7.json');
        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $sheet_id;
        //PROCESO DE BLOQUEO DE PERMISOS
        // Recuperamos el ID de hojas
        $response = $service->spreadsheets->get($spreadsheetId);
        $spreadsheetProperties = $response->getProperties();
        //$sheet =$response->getSheets();
        //$rev['Conexión con la Planilla'] = '<pre>'.var_export($sheet , true).'</pre>'."\ n";
        $rangesId=[];
        $namesId=[];
        foreach($response->getSheets() as $sheet) {
            // Properties of sheet SOLO DEL 1ER TRIMESTRE
            $sheetProperties = $sheet->getProperties();
            $sheetRangos = $sheet->getProtectedRanges();
            switch ($sheetProperties->title){
                case "3erTRIM":
                    $pri = $sheetProperties->sheetId;
                    foreach ($sheetRangos as $r){
                        if($r->protectedRangeId!=NULL){
                            $rangesId[] = $r->protectedRangeId;
                        }
                        if($r->namedRangeId!=NULL){
                            $namesId[] = $r->namedRangeId;
                        }
                    }
                    break;
            }
        }
        foreach ($rangesId as $r) {
            //$rev['Conexión con la Planilla Ranges'] = '<pre>'.$r.'</pre>'."\ n";
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
            //$rev['Conexión con la Planilla Names'] = '<pre>'.$n.'</pre>'."\ n";
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
        
        //PROTEGEMOS 3ER TRIMESTRE
        $listas = array(0 => array(0,42,0,59));
        $i=0;
        foreach($listas as $lista => $detalles)
            {
            //PROTEGEMOS FINALES
            $requests = [ 
                new \Google_Service_Sheets_Request([
                    "addProtectedRange" => [
                        "protectedRange" => [
                            "protectedRangeId" => "3".$lista,
                            "range" => [ "sheetId" => $pri, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ],
                            "description" => "3ro Trimestre ".$lista,
                            "warningOnly" => true
                        ]
                    ]
                ])
            ];
            try{
                $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);                    
            }catch (Exception $e) {
                //$rev['Excepcion Permiso 1.1']= $e->getMessage();
            }
            //ADD RANGO en 3ER
            $requests = [ 
                new \Google_Service_Sheets_Request([
                    "addNamedRange" => [
                        "namedRange" => [
                            "namedRangeId" => "333".$lista,
                            "name" => "Tercero".$lista,
                            "range" => [ "sheetId" => $pri, "startRowIndex" => $detalles[0], "endRowIndex" => $detalles[1], "startColumnIndex" => $detalles[2], "endColumnIndex" => $detalles[3] ]
                        ]
                    ]
                ])
            ];
            try{
                $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
            }catch (Exception $e) {
                //$rev['Excepcion Permiso 1.2']= $e->getMessage();
            }
            //DAMOS PERMISOS A ADMINISTRADORES
            $requests = [ 
            new \Google_Service_Sheets_Request([
                "updateProtectedRange" => [
                    "protectedRange" => [
                        "protectedRangeId" => "3".$lista,
                        "namedRangeId" => "333".$lista,
                        "warningOnly" => false,
                        "editors" => [
                            "users" => [ "saat@tiquipaya.edu.bo",
                                "prueba-cargas@cargararchivos-308920.iam.gserviceaccount.com",
                                "sheetssaat@saat-sheets.iam.gserviceaccount.com" ]
                            ]
                        ],
                        "fields" => "namedRangeId,warningOnly,editors"
                    ]
                ])
            ];
            try{
                $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([ 'requests' => $requests ]);
                $response = $service->spreadsheets->batchUpdate($spreadsheetId, $batchUpdateRequest);
            }catch (Exception $e) {
                
            }
        }
        
        

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
}