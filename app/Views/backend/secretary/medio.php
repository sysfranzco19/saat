<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Medios de Comunicación
                    <span class="text-muted pt-2 font-size-sm d-block">Muestra los Medios de Comunicación</span></h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-light-success font-weight-bold mr-2" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/medio_modal_add/0/0/0/0/0');" >
                        Nuevo Medio de Comunicación
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>#</th>
                            <th><div>Medios de Comunicación</div></th>
                            <th><div>Acción</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $key):?>
                        <tr>
                            <td><?php echo $key->medio_id; ?></td>
                            <td><?php echo $key->medio; ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/medio_modal_edit/<?php echo $key->medio_id ?>/0/0/0/0');" >
                                    Editar
                                </button>
                                <!-- <?php echo base_url();?>/home/medio_delete/<?php echo $key->medio_id ?> -->
                                <button type="button" class="btn btn-danger btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/medio_modal_del/<?php echo $key->medio_id ?>/0/0/0/0');" >
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