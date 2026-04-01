<?php
    $DirectorMod = new \App\Models\DirectorModel();
    $directores = $DirectorMod->listar_director();
?>
<script type="text/javascript">
    function getNivel() {
        $.ajax({
            url: "<?php echo base_url(); ?>/admin/nivel_get/<?php echo $param1; ?>",
            success: function(response) {
                if (response) {
                    $('#nivel_input').val(response.nivel);
                    $('#abreviado_input').val(response.abreviado);
                    $('#inicio_input').val(response.inicio);
                    $('#fin_input').val(response.fin);
                    $('#director_select').val(response.director_id);
                }
            }
        });
    }
    getNivel();
</script>

<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Modificar Nivel</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url().'/admin/nivel_update'; ?>" method="post" class="form-horizontal">
<div class="modal-body">
    <input type="hidden" name="id" value="<?php echo $param1; ?>">
    <div class="form-group row">
        <label class="col-3 col-form-label">Nivel:</label>
        <div class="col-9">
            <input type="text" class="form-control" id="nivel_input" name="nivel" required placeholder="Nombre del Nivel">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Abreviado:</label>
        <div class="col-9">
            <input type="text" class="form-control" id="abreviado_input" name="abreviado" required placeholder="Abreviación">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Inicio:</label>
        <div class="col-9">
            <input type="number" class="form-control" id="inicio_input" name="inicio" required placeholder="Inicio (INT)">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Fin:</label>
        <div class="col-9">
            <input type="number" class="form-control" id="fin_input" name="fin" required placeholder="Fin (INT)">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Director:</label>
        <div class="col-9">
            <select class="form-control" id="director_select" name="director_id">
                <option value="">Seleccione Director</option>
                <?php foreach($directores as $dir): ?>
                    <option value="<?php echo $dir->director_id; ?>"><?php echo $dir->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
</div>
</form>
<!--end::Modal-->
