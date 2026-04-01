<?php 
use App\Models\NivelModel;
$NivelMod = new NivelModel();
$niveles = $NivelMod->listar_niveles();
?>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Nuevo Periodo</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>admin/periodo_create" method="post" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-3 col-form-label">Periodo:</label>
            <div class="col-9">
                <input type="text" class="form-control" name="periodo" required placeholder="Ej. 1er Periodo">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Hora Inicio:</label>
            <div class="col-9">
                <input type="time" class="form-control" name="hora_inicio" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Hora Fin:</label>
            <div class="col-9">
                <input type="time" class="form-control" name="hora_fin" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Nivel:</label>
            <div class="col-9">
                <select class="form-control" name="nivel_id" required>
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
        <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
    </div>
</form>
<!--end::Modal-->
