<style>
    .btr {
        background-color: #f44c64;
        color: white;
        border: 0;
        font-size: 0.8em;
        border-radius: 5px;
    }

    .btx {
        background-color: #e4e4ec;
        color: black;
        border: 0;
        font-size: 0.8em;
        border-radius: 5px;
    }

    @media print {
        @page { size: letter landscape; margin: 3mm; }
        body { 
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
            background: white !important;
            zoom: 65% !important; /* Force browser to scale down the entire page */
            font-size: 10px !important;
        }
        /* Hide layout navigation */
        .card-toolbar, .header, .aside, .footer, #kt_header, #kt_aside, #kt_subheader { 
            display: none !important; 
        }
        /* Hide specific action buttons but keep the cell badges (.btn-square) */
        button.btn:not(.btn-square), a.btn:not(.btn-square), .flaticon2-pen, .flaticon2-trash {
            display: none !important;
        }
        .card { border: none !important; box-shadow: none !important; padding: 0 !important; }
        .card-body { padding: 0 !important;}
        .container-fluid { padding: 0 !important; margin: 0 !important; }
        
        /* Force table scaling to fit */
        .table { width: 100% !important; margin-bottom: 0 !important; table-layout: fixed; }
        .table th, .table td { padding: 1px !important; font-size: 9px !important; line-height: 1 !important;}
        
        /* Shrink the attendance square button for printing */
        .btn-square { 
            height: 20px !important; 
            font-size: 10px !important;
            padding: 2px !important;
        }
        /* Shrink vertical header */
        .font-weight-bolder[style*="writing-mode"] {
            height: 35px !important;
            font-size: 9px !important;
        }
    }
</style>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Asistencia :
                        <?php echo $curso; ?>
                        <span class="d-block text-muted pt-2 font-size-sm">El reporte muestra los ultimas 7 fechas que
                            se tomo la asistencia de los estudiantes</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Dropdown-->
                    <div class="dropdown dropdown-inline mr-2">
                        <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                            fill="#000000" opacity="0.3" />
                                        <path
                                            d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                            fill="#000000" />
                                    </g>
                                </svg>
                            </span>Acción</button>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                            <ul class="navi flex-column navi-hover py-2">
                                <li
                                    class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                    Seleccione una opción:</li>
                                <li class="navi-item">
                                    <a href="javascript:imprim1(imp1);" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="la la-print"></i>
                                        </span>
                                        <span class="navi-text">Imprimir</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="<?php echo base_url(); ?>index.php/teacher/assists_excel/<?php echo $subject_id; ?>/<?php echo $section_id; ?>"
                                        class="navi-link">
                                        <span class="navi-icon">
                                            <i class="la la-file-excel-o"></i>
                                        </span>
                                        <span class="navi-text">Descargar Excel</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="<?php echo base_url(); ?>index.php/teacher/assists_pdf/<?php echo $subject_id; ?>"
                                        class="navi-link" target="_blank">
                                        <span class="navi-icon">
                                            <i class="la la-file-pdf-o"></i>
                                        </span>
                                        <span class="navi-text">Descargar PDF</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <button onclick="window.print()" class="btn btn-warning font-weight-bolder mr-2" title="Descargar reporte formateado en PDF (Tamaño Carta)">
                        <span class="svg-icon svg-icon-md">
                            <i class="la la-file-pdf-o"></i>
                        </span>Imprimir PDF (Carta)
                    </button>

                    <a href="<?php echo base_url(); ?>index.php/teacher/attendance/<?php echo $subject_id; ?>"
                        class="btn btn-primary font-weight-bolder">
                        <span class="svg-icon svg-icon-md">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <circle fill="#000000" cx="9" cy="15" r="6" />
                                    <path
                                        d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                        fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                        </span>Tomar Asistencias</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-vertical-center" style="width: auto !important;">
                        <thead class="thead-light">
                            <tr>
                                <th style="min-width: 200px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Nombre</span>
                                        <span class="font-weight-bold text-muted" style="font-size: 0.8rem;">% Asistencia</span>
                                    </div>
                                </th>
                                <?php
                                foreach ($dias as $dia):
                                    $date_id = $dia['date_id'];
                                    $date = $dia['date_class'];
                                    $subject_id = $dia['subject_id'];
                                    $newDate = date("d/m", strtotime($dia['date_class']));
                                    ?>
                                    <th class="text-center p-0 align-middle" style="min-width: 38px; width: 38px; border-bottom: 2px solid #ebedf3;">
                                        <div class="d-flex flex-column align-items-center justify-content-end py-1">
                                            <span class="font-weight-bolder font-size-sm mb-2 text-dark" style="writing-mode: vertical-rl; transform: rotate(180deg); letter-spacing: 1px; height: 50px;"><?php echo $newDate; ?></span>
                                            <div class="d-flex flex-column align-items-center justify-content-center">
                                                <button class="btn btn-icon btn-xs btn-light-warning mb-1" title="Editar Fecha"
                                                    style="height: 18px; width: 18px;"
                                                    onclick="showAjaxModal('<?php echo base_url(); ?>index.php/modal/popup/attendance_date_modal_edit/<?php echo $subject_id; ?>/<?php echo $date_id; ?>/<?php echo $date; ?>/<?php echo $section_id; ?>/0');">
                                                    <i class="flaticon2-pen" style="font-size: 0.6rem;"></i>
                                                </button>
                                                <button class="btn btn-icon btn-xs btn-light-danger" title="Eliminar Fecha"
                                                    style="height: 18px; width: 18px;"
                                                    onclick="showAjaxModal('<?php echo base_url(); ?>index.php/modal/popup/attendance_date_modal_del/<?php echo $subject_id; ?>/<?php echo $date_id; ?>/<?php echo $date; ?>/<?php echo $section_id; ?>/0');">
                                                    <i class="flaticon2-trash" style="font-size: 0.6rem;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </th>
                                    <?php
                                endforeach;
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($students as $row):
                                ?>
                                <tr>
                                    <?php
                                    $total_dias = count($dias);
                                    $asistencias_validas = 0;
                                    if ($total_dias > 0) {
                                        foreach ($dias as $d) {
                                            $final_for_calc = null;
                                            foreach ($asis as $a) {
                                                if ($a['student_id'] == $row['student_id'] && $a['date_id'] == $d['date_id']) {
                                                    $final_for_calc = $a;
                                                }
                                            }
                                            // Present (1), Retraso (3), Virtual (4) count as presence.
                                            if ($final_for_calc && in_array($final_for_calc['status'], [1, 3, 4])) {
                                                $asistencias_validas++;
                                            }
                                        }
                                        $porcentaje = round(($asistencias_validas / $total_dias) * 100);
                                    } else {
                                        $porcentaje = 0;
                                    }

                                    $badgeColor = 'badge-success';
                                    if ($porcentaje < 60) {
                                        $badgeColor = 'badge-danger';
                                    } elseif ($porcentaje < 85) {
                                        $badgeColor = 'badge-warning';
                                    }
                                    ?>
                                    <td class="font-weight-bold text-dark-75 py-2 align-middle">
                                        <div class="d-flex justify-content-between align-items-center" style="min-width: 180px;">
                                            <span class="mr-2 text-truncate"><?php echo $row['student']; ?></span>
                                            <span class="label label-lg label-light-<?php echo str_replace('badge-', '', $badgeColor); ?> label-inline font-weight-bolder" 
                                                  title="Asistió a <?php echo $asistencias_validas; ?> de <?php echo $total_dias; ?> clases evaluadas">
                                                <?php echo $porcentaje; ?>%
                                            </span>
                                        </div>
                                    </td>
                                    <?php
                                    foreach ($dias as $dia):

                                        ?>
                                        <td class="text-center p-0 align-middle"><?php
                                        $final_asi = null;
                                        foreach ($asis as $asi):
                                            if ($asi['student_id'] == $row['student_id'] && $asi['date_id'] == $dia['date_id']) {
                                                $final_asi = $asi; // Retaining the last registered record
                                            }
                                        endforeach;

                                        if ($final_asi) {
                                            $asi = $final_asi;
                                            $statusClass = 'btn-light';
                                            $statusText = '?';
                                            $statusTitle = 'Desconocido';
                                            $customStyle = '';

                                            if ($asi['status'] == 0) {
                                                $statusClass = 'btn-danger'; // Ausente
                                                $statusText = 'F';
                                                $statusTitle = 'Falta';
                                            } elseif ($asi['status'] == 1) {
                                                $statusClass = '';
                                                $customStyle = 'background-color: #80e041; color: white; border-color: #80e041;'; // Verde Lechuga
                                                $statusText = 'P';
                                                $statusTitle = 'Presente';
                                            } elseif ($asi['status'] == 2) {
                                                $statusClass = 'btn-primary'; // Licencia
                                                $statusText = 'L';
                                                $statusTitle = 'Licencia';
                                            } elseif ($asi['status'] == 3) {
                                                $statusClass = 'btn-warning'; // Retraso
                                                $statusText = 'R';
                                                $statusTitle = 'Retraso';
                                            } elseif ($asi['status'] == 4) {
                                                $statusClass = 'btn-info'; // Virtual
                                                $statusText = 'V';
                                                $statusTitle = 'Virtual';
                                            }
                                            ?>
                                                <div class="btn btn-icon btn-square <?php echo $statusClass; ?> btn-sm w-100"
                                                    style="cursor: pointer; height: 35px; font-weight: bold; font-size: 1.1em; <?php echo $customStyle; ?>"
                                                    title="<?php echo $statusTitle; ?>"
                                                    onclick="showAjaxModal('<?php echo base_url(); ?>index.php/modal/popup/attendance_modal_edit/<?php echo $subject_id; ?>/<?php echo $asi['assistance_subject_id']; ?>/<?php echo $asi['status']; ?>/<?php echo $section_id; ?>/<?php echo $row['student']; ?>');">
                                                    <?php echo $statusText; ?>
                                                </div>
                                            <?php
                                        } else {
                                            ?>
                                                <div class="btn btn-icon btn-square btn-light btn-sm w-100 opacity-50"
                                                    style="cursor: pointer; height: 35px; font-weight: bold; font-size: 1.1em; color: #a1a1aa;" 
                                                    title="Registrar falta/asistencia"
                                                    onclick="showAjaxModal('<?php echo base_url(); ?>index.php/modal/popup/attendance_modal_add/<?php echo $subject_id; ?>/<?php echo $dia['date_id']; ?>/<?php echo $row['student_id']; ?>/<?php echo $section_id; ?>/<?php echo urlencode($row['student']); ?>');">
                                                    -
                                                </div>
                                            <?php
                                        }
                                        ?>
                                        </td>
                                        <?php

                                    endforeach;
                                    ?>

                                </tr>
                                <?php
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>