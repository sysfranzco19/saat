<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label"> Síntomas
                    <span class="text-muted pt-2 font-size-sm d-block">Muestra los  Síntomas</span></h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-light-success font-weight-bold mr-2" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/esintoma_modal_add/0/0/0/0/0');" >
                        Nuevo Síntoma
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable" id="kt_datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><div> Síntomas</div></th>
                            <th><div>Categoría del Síntoma</div></th>
                            <th><div>Acción</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $key):?>
                        <tr>
                            <td><?php echo $key->id; ?></td>
                            <td><?php echo $key->nombre; ?></td>
                            <td><?php echo $key->categoria; ?></td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/esintoma_modal_edit/<?php echo $key->id ?>/0/0/0/0');" >
                                    Editar
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/esintoma_modal_del/<?php echo $key->id ?>/0/0/0/0');" >
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
<!-- Script para inicializar DataTable -->
<script>
    $(document).ready(function() {
        $('#kt_datatable').DataTable({
            dom: 'Bfrtip', // Define la posición de los elementos (B: Buttons, f: Filter, r: Processing, t: Table, i: Info, p: Pagination)
            buttons: [
                'excel', 'pdf', 'print' // Agrega los botones de exportación
            ],
            language: {
                search: "Buscar:", // Cambia el texto del campo de búsqueda
                lengthMenu: "Mostrar _MENU_ filas", // Cambia el texto del menú de longitud
                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas", // Cambia el texto de la información
                infoEmpty: "No hay entradas disponibles", // Cambia el texto cuando no hay entradas disponibles
                infoFiltered: "(filtrado de _MAX_ entradas totales)", // Cambia el texto del filtro
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