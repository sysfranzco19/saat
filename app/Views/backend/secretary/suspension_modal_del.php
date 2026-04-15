<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title">Eliminar Suspensión</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>/secretary/suspension_delete" method="post" class="form">
<input type="hidden" name="suspension_id" value="<?php echo $param1; ?>">
<div class="modal-body">
    <div class="alert alert-custom alert-light-danger mb-5">
        <div class="alert-text">¿Está seguro de que desea eliminar esta suspensión? Esta acción no se puede deshacer.</div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Estudiante:</label>
        <div class="col-9">
            <input type="text" class="form-control" id="sus_del_student" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Tipo:</label>
        <div class="col-9">
            <input type="text" class="form-control" id="sus_del_type" disabled>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Motivo:</label>
        <div class="col-9">
            <textarea class="form-control" id="sus_del_reason" rows="2" disabled></textarea>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-danger font-weight-bold">Eliminar</button>
</div>
</form>
<script>
(function () {
    $.ajax({
        url: "<?php echo base_url(); ?>/secretary/suspension_get/<?php echo $param1; ?>",
        success: function (data) {
            $('#sus_del_student').val(data.student || data.student_id);
            $('#sus_del_type').val(data.type == 1 ? 'Por Día' : 'Por Período');
            $('#sus_del_reason').val(data.reason);
        }
    });
})();
</script>
<!--end::Modal-->