<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Trimestres
                        <span class="text-muted pt-2 font-size-sm d-block">Gestión de trimestres (phase)</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-light-success font-weight-bold mr-2"
                        onclick="showAjaxModal('<?php echo base_url(); ?>/modal/popup/phase_modal_add/0/0/0/0/0');">
                        Nuevo Trimestre
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable" id="kt_datatable">
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Abreviado</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos as $row): ?>
                        <tr>
                            <td><?php echo $row['phase_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['abreviado']); ?></td>
                            <td><?php echo htmlspecialchars($row['inicio']); ?></td>
                            <td><?php echo htmlspecialchars($row['fin']); ?></td>
                            <td>
                                <?php if ($row['activo'] == 1): ?>
                                    <span class="label label-success label-inline font-weight-bold">Activo</span>
                                <?php else: ?>
                                    <span class="label label-light-secondary label-inline font-weight-bold">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm"
                                    onclick="showAjaxModal('<?php echo base_url(); ?>/modal/popup/phase_modal_edit/<?php echo $row['phase_id']; ?>/0/0/0/0');">
                                    Editar
                                </button>
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="showAjaxModal('<?php echo base_url(); ?>/modal/popup/phase_modal_del/<?php echo $row['phase_id']; ?>/0/0/0/0');">
                                    Eliminar
                                </button>
                            </td>
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
    $('#kt_datatable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel"></i> Excel',
                title: 'Trimestres',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf"></i> PDF',
                title: 'Trimestres',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Imprimir',
                title: 'Trimestres',
                exportOptions: { columns: ':not(:last-child)' }
            }
        ],
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ filas",
            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            infoEmpty: "No hay entradas disponibles",
            infoFiltered: "(filtrado de _MAX_ entradas totales)",
            zeroRecords: "No se encontraron trimestres",
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
