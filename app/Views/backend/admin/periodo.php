<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Periodos
                        <span class="text-muted pt-2 font-size-sm d-block">Gestión de periodos de clase</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-light-success font-weight-bold mr-2"
                        onclick="showAjaxModal('<?php echo base_url(); ?>index.php/modal/popup/periodo_modal_add/0/0/0/0/0');">
                        Nuevo Periodo
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th>Periodo</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Nivel</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $row): ?>
                        <tr>
                            <td><?php echo $row['periodo_id']; ?></td>
                            <td><?php echo $row['periodo']; ?></td>
                            <td><?php echo $row['hora_inicio']; ?></td>
                            <td><?php echo $row['hora_fin']; ?></td>
                            <td><?php echo $row['nombre_nivel']; ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" 
                                    onclick="showAjaxModal('<?php echo base_url(); ?>index.php/modal/popup/periodo_modal_edit/<?php echo $row['periodo_id']; ?>/0/0/0/0');">
                                    Editar
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="showAjaxModal('<?php echo base_url(); ?>index.php/modal/popup/periodo_modal_del/<?php echo $row['periodo_id']; ?>/0/0/0/0');">
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
