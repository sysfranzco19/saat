<?php
date_default_timezone_set('America/La_Paz');
$fecha_actual = date('Y-m-d');
?>

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom gutter-b shadow-sm">
            <div class="card-header border-0 pt-6 pb-0">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="fa fa-calendar-check text-primary icon-xl"></i>
                    </span>
                    <h3 class="card-label font-weight-bolder text-dark">Registro de Asistencia
                        <span class="d-block text-muted pt-2 font-size-sm">Curso: <span class="text-primary font-weight-bold"><?= $curso ?></span></span>
                    </h3>
                </div>
            </div>

            <div class="card-body pt-4">
                <!-- Form Date and Period Selection -->
                <form action="<?= base_url() . '/index.php/teacher/attendance_date' ?>" method="POST" id="form_date_period" name="form_date">
                    <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                    <input type="hidden" name="curso" value="<?= $curso ?>">

                    <div class="row align-items-center mb-6 bg-light-primary rounded p-6">
                        <!-- Fecha Selection -->
                        <div class="col-lg-5 col-xl-4 mb-4 mb-lg-0">
                            <label class="font-weight-bold text-dark">Fecha de la clase <span class="text-danger">*</span></label>
                            <div class="input-group input-group-solid input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-calendar-alt icon-lg"></i>
                                    </span>
                                </div>
                                <?php if (isset($date_id)): ?>
                                    <input type="date" id="fecha" name="fecha" value="<?= $date ?>" class="form-control form-control-solid" readonly>
                                <?php else: ?>
                                    <input type="date" id="fecha" name="fecha" value="<?= $fecha_actual ?>" class="form-control form-control-solid" required>
                                <?php endif; ?>
                            </div>
                            <span class="form-text text-muted">Día de la clase.</span>
                        </div>

                        <!-- Periodo Selection -->
                        <div class="col-lg-5 col-xl-4 mb-4 mb-lg-0">
                            <label class="font-weight-bold text-dark">Periodo <span class="text-danger">*</span></label>
                            <div class="input-group input-group-solid input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="la la-clock icon-lg"></i>
                                    </span>
                                </div>
                                <select name="periodo" id="periodo" class="form-control form-control-solid selectpicker" required <?= isset($date_id) ? 'disabled' : '' ?>>
                                    <option value="" disabled <?= empty($periodo) ? 'selected' : '' ?>>Seleccione un periodo...</option>
                                    <?php for ($p = 1; $p <= 8; $p++): ?>
                                        <option value="<?= $p ?>" <?= (isset($periodo) && $periodo == $p) ? 'selected' : '' ?>>Periodo <?= $p ?></option>
                                    <?php endfor; ?>
                                </select>
                                <?php if (isset($date_id)): ?>
                                    <input type="hidden" name="periodo" value="<?= $periodo ?? '' ?>">
                                <?php endif; ?>
                            </div>
                            <span class="form-text text-muted">Periodo en el que dictó la materia.</span>
                        </div>

                        <!-- Action Button -->
                        <div class="col-lg-2 col-xl-4 text-lg-right mt-6">
                            <?php if (!isset($date_id)): ?>
                                <button type="button" onclick="cargar()" class="btn btn-primary font-weight-bolder btn-lg px-8 shadow">
                                    <i class="fa fa-list-ul mr-2"></i> Llamar Asistencia
                                </button>
                            <?php else: ?>
                                <button type="button" onclick="guardar()" class="btn btn-success font-weight-bolder btn-lg px-8 shadow">
                                    <i class="fa fa-save mr-2"></i> Guardar Todo
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

                <?php if (isset($students) && isset($date_id)): ?>
                    <!-- Students List Table -->
                    <hr>
                    <form action="<?= base_url() . '/index.php/teacher/attendance_save' ?>" method="POST" id="form_assists" name="form_assists">
                        <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                        <input type="hidden" name="section_id" value="<?= $section_id ?>">
                        <input type="hidden" name="periodos" value="<?= $periodo ?? '' ?>">
                        <input type="hidden" name="date_id" value="<?= $date_id ?>">

                        <!-- Licencias -->
                        <?php if (!empty($licencias)): ?>
                            <div class="mb-4">
                                <p class="mb-2"><strong>Licencias Activas:</strong></p>
                                <?php foreach ($licencias as $lic): ?>
                                    <span class="label label-danger label-pill label-inline mr-2 py-3 px-3 mb-1">
                                        <i class="fa fa-notes-medical text-white mr-2 icon-sm"></i> <?= $lic['student'] ?> - <?= $lic['detalle'] ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-head-custom table-vertical-center table-striped" id="kt_advance_table_widget_1">
                                <thead>
                                    <tr class="text-left bg-light-secondary">
                                        <th style="min-width: 250px" class="pl-4 rounded-left">Nombre de Estudiante</th>
                                        <th style="min-width: 100px" class="text-center text-success"><i class="fa fa-check text-success"></i> Presente</th>
                                        <th style="min-width: 100px" class="text-center text-info"><i class="fa fa-laptop text-info"></i> Virtual</th>
                                        <th style="min-width: 100px" class="text-center text-warning"><i class="fa fa-clock text-warning"></i> Retraso</th>
                                        <th style="min-width: 100px" class="text-center text-danger"><i class="fa fa-times text-danger"></i> Ausente</th>
                                        <th style="min-width: 100px" class="text-center text-primary"><i class="fa fa-file-medical text-primary"></i> Licencia</th>
                                        <th style="min-width: 200px" class="rounded-right">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $row): ?>
                                        <?php
                                        $lic = "";
                                        $dis = "";
                                        $obs = "";
                                        $presente = "checked";

                                        if ($row['estado'] == '1') {
                                            $lic = "checked";
                                            $dis = "disabled";
                                            $presente = "";
                                        } elseif ($row['estado'] == '0') {
                                            $lic = "";
                                            $presente = "checked";
                                        }
                                        ?>
                                        <tr>
                                            <td class="pl-4 font-weight-bolder text-dark">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-40 symbol-light-primary mr-3">
                                                        <span class="symbol-label font-size-h5 font-weight-bold"><?= substr($row['student'], 0, 1) ?></span>
                                                    </div>
                                                    <div>
                                                        <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg"><?= $row['student'] ?></a>
                                                        <span class="text-muted font-weight-bold d-block">ID: <?= $row['student_id'] ?></span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Presente = 1 -->
                                            <td class="text-center">
                                                <label class="radio radio-lg radio-outline radio-success justify-content-center">
                                                    <input type="radio" name="check_<?= $row['student_id'] ?>" value="1" <?= $dis ?> <?= $presente ?> required>
                                                    <span></span>
                                                </label>
                                            </td>

                                            <!-- Virtual = 2 -->
                                            <td class="text-center">
                                                <label class="radio radio-lg radio-outline radio-info justify-content-center">
                                                    <input type="radio" name="check_<?= $row['student_id'] ?>" value="2" <?= $dis ?> required>
                                                    <span></span>
                                                </label>
                                            </td>

                                            <!-- Retraso = 3 -->
                                            <td class="text-center">
                                                <label class="radio radio-lg radio-outline radio-warning justify-content-center">
                                                    <input type="radio" name="check_<?= $row['student_id'] ?>" value="3" <?= $dis ?> required>
                                                    <span></span>
                                                </label>
                                            </td>

                                            <!-- Ausente = 0 -->
                                            <td class="text-center">
                                                <label class="radio radio-lg radio-outline radio-danger justify-content-center">
                                                    <input type="radio" name="check_<?= $row['student_id'] ?>" value="0" <?= $dis ?> required>
                                                    <span></span>
                                                </label>
                                            </td>

                                            <!-- Licencia = 2 (disabled radio, visual only) -->
                                            <td class="text-center">
                                                <label class="radio radio-lg radio-outline radio-primary justify-content-center opacity-50">
                                                    <input type="radio" name="check_<?= $row['student_id'] ?>" value="2" <?= $lic ?> disabled>
                                                    <span></span>
                                                </label>
                                            </td>

                                            <td>
                                                <input type="text" class="form-control form-control-solid form-control-sm" name="text_<?= $row['student_id'] ?>" value="<?= $obs ?>" placeholder="Notas...">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                <?php else: ?>
                    <!-- Empty State before selection -->
                    <div class="alert alert-custom alert-light-primary fade show mb-5" role="alert">
                        <div class="alert-icon"><i class="flaticon-info"></i></div>
                        <div class="alert-text">Por favor, seleccione la <strong>Fecha</strong> y el <strong>Periodo</strong> de clases, y luego presione "Llamar Asistencia" para desplegar la lista de estudiantes.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--begin::Entry-->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function cargar() {
    var fecha = document.getElementById("fecha").value;
    var periodo = document.getElementById("periodo").value;

    if (!fecha) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha Requerida',
            text: 'Por favor, selecciona una fecha válida.',
            confirmButtonText: 'Entendido'
        });
        return false;
    }
    
    if (!periodo) {
        Swal.fire({
            icon: 'warning',
            title: 'Falta Periodo',
            text: 'Debes seleccionar el Periodo de la clase antes de llamar la lista.',
            confirmButtonText: 'Entendido'
        });
        return false;
    }

    document.getElementById("form_date_period").submit();
}

function guardar() {
    <?php if (isset($section_id)): ?>
        var faltante = false;
        
        <?php foreach ($students as $row): ?>
            var opciones<?= $row['student_id'] ?> = document.form_assists.elements["check_<?= $row['student_id'] ?>"];
            var seleccionado<?= $row['student_id'] ?> = false;
            
            // Loop for radios (if NodeList) or check directly (if single node)
            if (opciones<?= $row['student_id'] ?> && opciones<?= $row['student_id'] ?>.length) {
                for(var i=0; i<opciones<?= $row['student_id'] ?>.length; i++) {
                    if(opciones<?= $row['student_id'] ?>[i].checked) seleccionado<?= $row['student_id'] ?> = true;
                }
            } else if (opciones<?= $row['student_id'] ?>) {
                if(opciones<?= $row['student_id'] ?>.checked) seleccionado<?= $row['student_id'] ?> = true;
            }

            if (!seleccionado<?= $row['student_id'] ?>) {
                faltante = true;
            }
        <?php endforeach; ?>

        if (faltante) {
             Swal.fire({
                icon: 'error',
                title: 'Asistencia Incompleta',
                text: 'Aún hay estudiantes sin su asistencia marcada. Revisa la lista por favor.',
                confirmButtonText: 'Revisar'
            });
            return false;
        }
    <?php endif; ?>

    document.form_assists.submit();
}
</script>