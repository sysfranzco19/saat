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
                            <td class="text-nowrap">
                                <!-- <a href="<?php echo base_url(); ?>parents/report_half/<?php echo $row['student_id'];?>" class="btn btn-warning btn-sm">Ver evaluaciones</a> -->
                                <div class="btn-group mr-1 mb-1" role="group">
                                    <a href="<?php echo base_url(); ?>parents/report_card/<?php echo $row['student_id'];?>" class="btn btn-light-success btn-sm font-weight-bold" title="Boletín de Notas">
                                        <i class="fas fa-file-alt mr-1"></i>Boletín
                                    </a>
                                    <a href="<?php echo base_url(); ?>parents/evaluation_report/<?php echo $row['student_id'];?>" class="btn btn-light-success btn-sm font-weight-bold" title="Reporte de evaluaciones">
                                        <i class="fas fa-chart-bar mr-1"></i>Evaluaciones
                                    </a>
                                </div>
                                <div class="btn-group mb-1" role="group">
                                    <button class="btn btn-light-secondary font-weight-bold btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-calendar-check mr-1"></i>Asistencias
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>parents/student_attendance/<?php echo $row['student_id'];?>" target="_blank"><i class="fas fa-clipboard-list mr-2 text-muted"></i>Asistencias</a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>parents/student_licenses/<?php echo $row['student_id'];?>" target="_blank"><i class="fas fa-file-medical-alt mr-2 text-muted"></i>Licencias</a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>parents/student_absences/<?php echo $row['student_id'];?>" target="_blank"><i class="fas fa-user-times mr-2 text-muted"></i>Ausencias</a>
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>parents/student_delays/<?php echo $row['student_id'];?>" target="_blank"><i class="fas fa-clock mr-2 text-muted"></i>Retrasos al Ingreso</a>
                                    </div>
                                </div>
                                <?php if (!isPrimaria36($row['grade'])): ?>
                                    <!--  
                                <button type="button" id="licenseButton" class="btn btn-primary btn-sm font-weight-bold mb-1" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/student_license_modal/<?php echo $row['student_id']; ?>/<?php echo $row['student']; ?>/<?php echo $row['completo'];?>/<?php echo $row['family_id'];?>/0');" title="Solicitar Licencia">
                                    <i class="fas fa-file-medical mr-1"></i>Solicitar Licencia
                                </button>
                                -->
                                <?php endif; ?>
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
