<?php
$session = session();
?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Listado de Estudiantes
                        <span class="d-block text-muted pt-2 font-size-sm">Listado completo de estudiantes bajo su
                            supervisión</span>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-separate table-head-custom table-checkable" id="kt_datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Código</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $row): ?>
                            <tr>
                                <td><?php echo $row['student_id']; ?></td>
                                <td><?php echo $row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name']; ?></td>
                                <td><?php echo $row['nick_name']; ?></td>
                                <td><?php echo $row['code']; ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-secondary font-weight-bold btn-sm dropdown-toggle"
                                            type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Asistencias
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="<?php echo base_url(); ?>secretary/student_attendance/<?php echo $row['student_id']; ?>/all/0"
                                                target="_blank">Asistencias</a>
                                            <a class="dropdown-item"
                                                href="<?php echo base_url(); ?>secretary/student_licenses/<?php echo $row['student_id']; ?>/all/0"
                                                target="_blank">Licencias</a>
                                            <a class="dropdown-item"
                                                href="<?php echo base_url(); ?>secretary/student_absences/<?php echo $row['student_id']; ?>"
                                                target="_blank">Ausencias</a>
                                            <a class="dropdown-item"
                                                href="<?php echo base_url(); ?>secretary/student_delays/<?php echo $row['student_id']; ?>"
                                                target="_blank">Retrasos</a>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-danger font-weight-bold btn-sm dropdown-toggle" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Conductuales
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item"
                                                href="<?php echo base_url(); ?>secretary/kardex_student/<?php echo $row['student_id']; ?>"
                                                target="_blank">Ver Kardex</a>
                                            <a class="dropdown-item"
                                                href="<?php echo base_url(); ?>secretary/infractions_student/<?php echo $row['student_id']; ?>"
                                                target="_blank">Infracciones</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->

<script>
    $(document).ready(function () {
        $('#kt_datatable').DataTable({
            responsive: true,
            paging: true,
            ordering: true,
            info: true,
            dom: "<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
            buttons: [
                'print',
                'excelHtml5',
                'pdfHtml5',
            ],
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
            }
        });
    });
</script>