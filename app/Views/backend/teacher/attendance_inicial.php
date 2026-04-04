<script type="text/javascript">
    function cargar() {
        var select = document.getElementById("fecha");
        if (select.value == "") {
            alert("Fecha Incorrecta");
            return false;
        } else {
            var today = new Date();
            document.form_date.submit();
        }
    }
    function guardar() {
        <?php
        if (isset($section_id)) {
            foreach ($students as $row):
                ?>
                opciones<?php echo $row['student_id']; ?> = document.form_assists.check_<?php echo $row['student_id']; ?>;
                var seleccionado = false;
                for (var i = 0; i < opciones<?php echo $row['student_id']; ?>.length; i++) {
                    if (opciones<?php echo $row['student_id']; ?>[i].checked) {
                        seleccionado = true;
                        break;
                    }
                }
                //alert(seleccionado)
                if (!seleccionado) {
                    alert("Asistencia Incompleta");
                    return false;
                }


                <?php
            endforeach;
        }
        ?>
        var selectElement = document.getElementById('periodos');
        var selectedValue = selectElement.value;

        if (selectedValue === "") {
            alert('Por favor, selecciona el periodo de la llamada de asistencia');
            // Puedes detener el flujo de ejecución aquí si es necesario
            return false;
        }

        document.form_assists.submit();
    }
</script>
<?php
date_default_timezone_set('America/La_Paz');
$fecha_actual = date('Y-m-d');
?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Registro de asistencia
                        <span class="d-block text-muted pt-2 font-size-sm">Formulario para tomar Asistencia</span>
                    </h3>
                </div>

                <form action="<?php echo base_url() . 'index.php/teacher/attendance_date_inicial'; ?>" method="POST" class="form"
                    name="form_date">
                    <div class="card-toolbar">
                        <!--begin::Dropdown-->
                        <div class="dropdown dropdown-inline mr-2">
                            <input type="text" id="curso" name="curso" value="<?php echo $curso; ?>"
                                class="form-control" disabled>
                            <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
                        </div>
                        <!--end::Dropdown-->
                        <!--begin::Dropdown-->
                        <div class="dropdown dropdown-inline mr-2">
                            <?php
                            if (isset($date_id)) {
                                ?><input type="text" id="fecha" name="fecha" step="1" min="01-01-2023" max="31-12-2023"
                                    value="<?php echo $date; ?>" class="form-control" disabled><?php
                            } else {
                                ?><input type="date" id="fecha" name="fecha" step="1" min="01-01-2023" max="31-12-2023"
                                    value="<?php echo $fecha_actual; ?>" class="form-control" required><?php
                            }
                            ?>

                        </div>
                        <!--end::Dropdown-->
                        <?php if (isset($date_id)): ?>
                        <!--begin::Dropdown-->
                        <div class="dropdown dropdown-inline mr-2">
                            <select class="form-control" id="periodos_toolbar" onchange="document.getElementById('periodos').value = this.value">
                                <option value="" disabled selected hidden>Elige periodo</option>
                                <option value="1">1er Periodo</option>
                                <option value="2">2do Periodo</option>
                                <option value="3">3er Periodo</option>
                                <option value="4">4to Periodo</option>
                                <option value="5">5to Periodo</option>
                                <option value="6">6to Periodo</option>
                                <option value="7">7mo Periodo</option>
                                <option value="8">8vo Periodo</option>
                            </select>
                        </div>
                        <!--end::Dropdown-->
                        <?php endif; ?>
                        <?php
                        if (isset($date_id)) {
                            ?>
                            <!--begin::Button-->
                            <a href="" onclick="guardar()" class="btn btn-primary font-weight-bolder" data-toggle="modal"
                                data-target="#modal_teacher_edit">
                                <span class="svg-icon svg-icon-md">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
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
                                    <!--end::Svg Icon-->
                                </span>Guardar Asistencia</a>
                            <!--end::Button-->
                            <?php
                        } else {
                            ?>
                            <!--begin::Button-->
                            <a href="" onclick="cargar()" class="btn btn-primary font-weight-bolder" data-toggle="modal"
                                data-target="#modal_teacher_edit">
                                <span class="svg-icon svg-icon-md">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
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
                                    <!--end::Svg Icon-->
                                </span>Tomar Asistencias</a>
                            <!--end::Button-->
                            <?php
                        }
                        ?>
                    </div>
                </form>

                <?php
                if (isset($students)) {
                    //echo form_open(base_url() . 'index.php/teacher/attendance_save/' , array('class' => 'form','name' => 'form_assists'));
                    ?>
                    <form action="<?php echo base_url() . 'index.php/teacher/attendance_save'; ?>" method="POST" class="form"
                        name="form_assists">
                        <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
                        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                        <input type="hidden" name="date_id" value="<?php echo $date_id; ?>">
                        <input type="hidden" id="periodos" name="periodos">
                        <div>
                            <p><strong>Licencias</strong></p>
                            <?php
                            if (isset($date_id)) {
                                foreach ($licencias as $lic):
                                    echo '<span class="label label-danger label-pill label-inline mr-2">' . $lic['student'] . ' - ' . $lic['detalle'] . '</span>';
                                endforeach;
                            }
                            ?>
                        </div>
                        <!--begin: Datatable-->
                        <table class="table">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Nombre</th>
                                    <!--<th>DDJJ</th>-->
                                    <th>Asis. Anterior</th>
                                    <th>Presente</th>
                                    <th>Retraso</th>
                                    <th>Ausente</th>
                                    <th>Licencia</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($students as $row):

                                    ?>
                                    <?php
                                    $lic = "";
                                    $dis = "";
                                    $obs = "";
                                    $ausente = "";
                                    $retraso = "";
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
                                        <!--<td><img src="<?php //echo $this->crud_model->get_image_url('student',$row['student_id']); ?>" class="rounded-circle" width="30" /></td>-->
                                        <td><?php echo $row['student']; ?></td>
                                        <!--<td><?php
                                        if ($row['ddjj'] == '1') {
                                            echo "<span class='label label-light-success font-weight-bold label-inline mr-1'>Entrego</span>";
                                        } else {
                                            echo "<span class='label label-light-danger font-weight-bold label-inline mr-1'>No</span>";
                                        }
                                        ?>
                                </td>-->
                                        <td>
                                            <?php
                                            foreach ($asistenciasAnt as $asi):
                                                if ($asi['student_id'] == $row['student_id']) {
                                                    if ($asi['status'] == 0) {
                                                        $ausente = "checked";
                                                        $presente = "";
                                                        ?>
                                                        <span class='label label-light-danger font-weight-bold label-inline'>ausente</span>
                                                        <?php
                                                    } elseif ($asi['status'] == 1) {
                                                        $presente = "checked";
                                                        ?>
                                                        <span
                                                            class='label label-light-success font-weight-bold label-inline'>presente</span>
                                                        <?php
                                                    } elseif ($asi['status'] == 2) {
                                                        ?>
                                                        <span
                                                            class='label label-light-primary font-weight-bold label-inline'>licencia</span>
                                                        <?php
                                                    } elseif ($asi['status'] == 3) {
                                                        $retraso = "checked";
                                                        $presente = "";
                                                        ?>
                                                        <span class='label label-light-warning font-weight-bold label-inline'>retraso</span>
                                                        <?php
                                                    }
                                                }
                                            endforeach;
                                            if (count($asistenciasAnt) == 0) {
                                                echo '--';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <label class="checkbox checkbox-success">
                                                <input type="radio" name="check_<?php echo $row['student_id']; ?>" value="1"
                                                    <?php echo $dis; ?>         <?php echo $presente; ?> required>
                                                <span></span></label>
                                        </td>

                                        <td>
                                            <label class="checkbox checkbox-warning">
                                                <input type="radio" name="check_<?php echo $row['student_id']; ?>" value="3"
                                                    <?php echo $dis; ?>         <?php echo $retraso; ?> required>
                                                <span></span></label>
                                        </td>
                                        <td>
                                            <label class="checkbox checkbox-danger">
                                                <input type="radio" name="check_<?php echo $row['student_id']; ?>" value="0"
                                                    <?php echo $dis; ?>         <?php echo $ausente; ?> required>
                                                <span></span></label>
                                        </td>
                                        <td>
                                            <label class="checkbox checkbox-primary">
                                                <input type="radio" name="check_<?php echo $row['student_id']; ?>" value="2"
                                                    <?php echo $lic; ?> readonly>
                                                <span></span>
                                            </label>
                                        </td>
                                        <td colspan="2">
                                            <textarea class="form-control" name="text_<?php echo $row['student_id']; ?>"
                                                rows="2" spellcheck="false" data-gramm="false"><?php echo $obs; ?></textarea>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                        <!--end: Datatable-->
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--begin::Entry-->