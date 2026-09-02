<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Nuevo Trimestre</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>admin/phase_create" method="post" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="form-group row">
            <label class="col-3 col-form-label">Nombre:</label>
            <div class="col-9">
                <input type="text" class="form-control" name="name" required placeholder="Ej. Primer Trimestre">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Abreviado:</label>
            <div class="col-9">
                <input type="text" class="form-control" name="abreviado" maxlength="10" required placeholder="Ej. 1erTRIM">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Inicio:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="inicio" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Fin:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="fin" required>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Estado:</label>
            <div class="col-9">
                <select class="form-control" name="activo">
                    <option value="0">Inactivo</option>
                    <option value="1">Activo (trimestre actual)</option>
                </select>
                <small class="form-text text-muted">Al marcar "Activo", los demás trimestres se desactivan automáticamente.</small>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
    </div>
</form>
<!--end::Modal-->
