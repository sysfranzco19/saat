<?php 
use App\Models\NivelModel;
$NivelMod = new NivelModel();
$niveles = $NivelMod->listar_niveles();
?>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Modificar Periodo</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>index.php/admin/periodo_update" method="post" enctype="multipart/form-data">
    <div class="modal-body">
        <input type="hidden" name="periodo_id" id="periodo_id_input">
        <div class="form-group row">
            <label class="col-3 col-form-label">Periodo:</label>
            <div class="col-9">
                <input type="text" class="form-control" name="periodo" id="periodo_input" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Hora Inicio:</label>
            <div class="col-9">
                <input type="time" class="form-control" name="hora_inicio" id="hora_inicio_input" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Hora Fin:</label>
            <div class="col-9">
                <input type="time" class="form-control" name="hora_fin" id="hora_fin_input" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Nivel:</label>
            <div class="col-9">
                <select class="form-control" name="nivel_id" id="nivel_id_input" required>
                    <option value="">Seleccione un nivel</option>
                    <?php foreach($niveles as $nivel): ?>
                        <option value="<?php echo $nivel['id']; ?>"><?php echo $nivel['nivel']; ?></option>
                    <?php endforeach; ?>
                </select>
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
            url: "<?php echo base_url(); ?>index.php/admin/periodo_get/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#periodo_id_input').val(data.periodo_id);
                $('#periodo_input').val(data.periodo);
                $('#hora_inicio_input').val(data.hora_inicio);
                $('#hora_fin_input').val(data.hora_fin);
                $('#nivel_id_input').val(data.nivel_id);
            }
        });
    });
</script>
