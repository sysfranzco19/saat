<?php
    $DirectorMod = new \App\Models\DirectorModel();
    $directores = $DirectorMod->listar_director();
?>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Nuevo Nivel</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url().'/admin/nivel_create'; ?>" method="post" class="form-horizontal">
<div class="modal-body">
    <div class="form-group row">
        <label class="col-3 col-form-label">Nivel:</label>
        <div class="col-9">
            <input type="text" class="form-control" name="nivel" required placeholder="Nombre del Nivel" autofocus>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Abreviado:</label>
        <div class="col-9">
            <input type="text" class="form-control" name="abreviado" required placeholder="Abreviación">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Inicio:</label>
        <div class="col-9">
            <input type="number" class="form-control" name="inicio" required placeholder="Inicio (INT)">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Fin:</label>
        <div class="col-9">
            <input type="number" class="form-control" name="fin" required placeholder="Fin (INT)">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Director:</label>
        <div class="col-9">
            <select class="form-control" name="director_id">
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
    <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
</div>
</form>
<!--end::Modal-->
