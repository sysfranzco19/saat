<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Docentes sin consolidar Notas
                        <span class="text-muted pt-2 font-size-sm d-block">Trimestre: <?php echo htmlspecialchars($phase_name); ?></span>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable" id="kt_datatable">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Docente</th>
                            <th>Estado</th>
                            <th>Planillas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $row): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td>
                                <?php
                                if (count($subjects) == 0) {
                                    echo "<span class='label label-success label-pill label-inline mr-2'>Notas Consolidas</span>";
                                } else {
                                    $popover = "";
                                    foreach ($subjects as $mat):
                                        if ($mat['teacher_id'] == $row['teacher_id']) {
                                            $popover .= $mat['name'] . "-" . $mat['nick_name'] . "<br />";
                                        }
                                    endforeach;
                                    ?>
                                    <button type="button" class="btn btn-danger btn-sm" data-container="body" data-toggle="popover" data-html="true" data-placement="top"
                                        title="Materias sin consolidar:" data-content="<?php echo $popover; ?>">Notas sin consolidar</button>
                                    <?php
                                }
                                ?>
                            </td>
                            <td><a href="<?php echo base_url(); ?>admin/delivery_notes?estado=abierta&buscar=<?php echo urlencode($row['name']); ?>" class="btn btn-light-success btn-sm font-weight-bold mr-2">Ver Planillas</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->

<script>
$(document).ready(function () {
    $('[data-toggle="popover"]').popover();

    $('#kt_datatable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel"></i> Excel',
                title: 'Docentes sin consolidar Notas'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf"></i> PDF',
                title: 'Docentes sin consolidar Notas',
                orientation: 'landscape'
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Imprimir',
                title: 'Docentes sin consolidar Notas'
            }
        ],
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ filas",
            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            infoEmpty: "No hay entradas disponibles",
            infoFiltered: "(filtrado de _MAX_ entradas totales)",
            zeroRecords: "No se encontraron docentes con notas pendientes",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });
});
</script>
