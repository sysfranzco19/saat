<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Gestión de Suspensiones
                    <span class="text-muted pt-2 font-size-sm d-block">Administra las suspensiones de estudiantes</span></h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-light-success font-weight-bold mr-2" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/suspension_modal_add/0/0/0/0/0');">
                        Nueva Suspensión
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover" id="tbl_suspensions">
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th><div>Estudiante</div></th>
                            <th><div>Sección</div></th>
                            <th><div>Tipo</div></th>
                            <th><div>Motivo</div></th>
                            <th><div>Fecha / Período</div></th>
                            <th><div>Registrado</div></th>
                            <th><div>Acción</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $key): ?>
                        <tr>
                            <td><?php echo $key['suspension_id']; ?></td>
                            <td><?php echo $key['student']; ?></td>
                            <td><?php echo $key['seccion']; ?></td>
                            <td>
                                <?php if($key['type'] == 1): ?>
                                    <span class="label label-light-danger label-inline font-weight-bold">Por Día</span>
                                <?php else: ?>
                                    <span class="label label-light-warning label-inline font-weight-bold">Por Período</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($key['reason']); ?></td>
                            <td>
                                <?php if($key['type'] == 1): ?>
                                    <?php echo date('d/m/Y', strtotime($key['date_start'])); ?>
                                    <?php if($key['date_end'] && $key['date_end'] !== $key['date_start']): ?>
                                        &rarr; <?php echo date('d/m/Y', strtotime($key['date_end'])); ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo date('d/m/Y', strtotime($key['date'])); ?>
                                    &nbsp;<span class="text-muted"><?php echo htmlspecialchars($key['periodo_nombre'] ?? ''); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($key['created_at'])); ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/suspension_modal_edit/<?php echo $key['suspension_id']; ?>/0/0/0/0');">
                                    Editar
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/suspension_modal_del/<?php echo $key['suspension_id']; ?>/0/0/0/0');">
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
    $('#tbl_suspensions').DataTable({
        responsive: true,
        language: {
            url: '<?php echo base_url(); ?>assets/plugins/datatables/Spanish.json'
        },
        order: [[0, 'desc']]
    });
});
</script>