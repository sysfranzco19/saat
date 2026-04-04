<script type="text/javascript">
    function enviarLicencia(licencia_id, student_id, btn){
        var originalHtml = btn ? btn.innerHTML : '';
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/secretary/license_send/"+licencia_id+"/"+student_id,
            type: "get",
            beforeSend: function(){
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...';
                }
            },
            success: function(response){
                if (btn) {
                    btn.innerHTML = '<i class="flaticon2-check-mark icon-sm"></i> Enviado';
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-secondary');
                    btn.disabled = true;
                }
            },
            error: function(){
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            }
        });
    }
</script>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Autorizar Licencias solicitadas
                    <span class="text-muted pt-2 font-size-sm d-block">Muestra las Ultimas 30 licencias solicitadas</span></h3>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable" id="kt_datatable">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Detalle</th>
                            <th>Tipo</th>
                            <th>Fecha Solicitada</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($licencias as $key):?>
                        <tr>
                            <td><?php echo $key->student; ?> <b><?php echo $key->nick_name; ?></b></td>
                            <td><?php echo $key->detalle; ?></td>
                            <td><?php echo $key->tipo; ?></td>
                            <td><?php echo $key->fecha_solicitud; ?></td>
                            <td><?php echo $key->inicio; ?></td>
                            <td><?php echo $key->fin; ?></td>
                            <td>
                                <?php if ($key->documento): ?>
                                    <a href="<?php echo base_url('uploads/comprobantes_medicos/' . $key->documento); ?>" class="btn btn-secondary btn-sm">
                                    <i class="flaticon2-document icon-md"></i>
                                </a>
                                <?php endif; ?>
                                <button type="button" class="btn btn-primary btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/licenses_modal_auth/<?php echo $key->licencias_id ?>/0/0/0/0');" title="Autorizar" >
                                <i class="flaticon2-check-mark icon-md"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/licenses_modal_noauth/<?php echo $key->licencias_id ?>/0/0/0/0');" title="Rechazar" >
                                <i class="flaticon2-cancel icon-md"></i>
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
            order: [[3, 'asc']],
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