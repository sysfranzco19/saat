<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Mis Hijos
                    <span class="d-block text-muted pt-2 font-size-sm">Hijos inscritos en el Colegio</span></h3>
                </div>
                <div class="card-toolbar">
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Código</th>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($students as $row):
                    ?>
                        <tr>
                            <td><?php echo $row['student_id'];?> </td>
                            <td><?php echo $row['student'];?></td>
                            <td><?php echo $row['completo'];?></td>
                            <td>
                                <a href="<?php echo base_url(); ?>index.php/parents/report_half/<?php echo $row['student_id'];?>" class="btn btn-warning btn-sm">Ver evaluaciones</a>
                                <div class="btn-group">
                                    <button class="btn btn-secondary font-weight-bold btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Asistencias
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/parents/student_attendance/<?php echo $row['student_id'];?>" target="_blank">Asistencias</a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/parents/student_licenses/<?php echo $row['student_id'];?>" target="_blank">Licencias</a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/parents/student_absences/<?php echo $row['student_id'];?>" target="_blank">Ausencias</a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/parents/student_delays/<?php echo $row['student_id'];?>" target="_blank">Retrasos al Ingreso</a>
                                    </div>
                                </div>
                                <button type="button" id="licenseButton" class="btn btn-primary btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/student_license_modal/<?php echo $row['student_id']; ?>/<?php echo $row['student']; ?>/<?php echo $row['completo'];?>/<?php echo $row['family_id'];?>/0');" >
                                    Solicitar Licencia
                                </button>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<script>
    function checkTimeAndEnableButton() {
        const button = document.getElementById('licenseButton');
        const now = new Date();
        const hours = now.getHours();

        // Habilitar el botón si la hora actual está entre las 5 p.m. (17:00) y las 11 a.m. (11:00)
        if (hours >= 12 || hours < 10) {
            button.disabled = false;
        } else {
            button.disabled = true;
        }
    }

    // Ejecutar la función al cargar la página
    checkTimeAndEnableButton();

    // Comprobar la hora cada minuto para actualizar el estado del botón
    setInterval(checkTimeAndEnableButton, 60000);
</script>