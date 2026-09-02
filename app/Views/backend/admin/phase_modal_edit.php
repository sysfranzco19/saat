<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Modificar Trimestre</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>admin/phase_update" method="post" enctype="multipart/form-data">
    <div class="modal-body">
        <input type="hidden" name="phase_id" id="phase_id_input">
        <div class="form-group row">
            <label class="col-3 col-form-label">Nombre:</label>
            <div class="col-9">
                <input type="text" class="form-control" name="name" id="name_input" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Abreviado:</label>
            <div class="col-9">
                <input type="text" class="form-control" name="abreviado" id="abreviado_input" maxlength="10" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Inicio:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="inicio" id="inicio_input" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Fin:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="fin" id="fin_input" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Estado:</label>
            <div class="col-9">
                <select class="form-control" name="activo" id="activo_input">
                    <option value="0">Inactivo</option>
                    <option value="1">Activo (trimestre actual)</option>
                </select>
                <small class="form-text text-muted">Al marcar "Activo", los demás trimestres se desactivan automáticamente.</small>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Actualizar</button>
    </div>
</form>
<!--end::Modal-->

<script>
    $(document).ready(function() {
        var id = "<?php echo $param1; ?>";
        $.ajax({
            url: "<?php echo base_url(); ?>admin/phase_get/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#phase_id_input').val(data.phase_id);
                $('#name_input').val(data.name);
                $('#abreviado_input').val(data.abreviado);
                $('#inicio_input').val(data.inicio);
                $('#fin_input').val(data.fin);
                $('#activo_input').val(data.activo);
            }
        });
    });
</script>
