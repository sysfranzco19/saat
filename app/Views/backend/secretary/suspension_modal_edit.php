<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title">Editar Suspensión</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>/secretary/suspension_update" method="post" class="form">
<input type="hidden" name="suspension_id" value="<?php echo $param1; ?>">
<div class="modal-body">
    <div class="form-group row">
        <label class="col-3 col-form-label">Estudiante:</label>
        <div class="col-9">
            <select class="form-control" name="student_id" id="sus_edit_student" required>
                <option value="">-- Seleccionar --</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Tipo:</label>
        <div class="col-9">
            <select class="form-control" name="type" id="sus_edit_type" required onchange="toggleSuspensionFields('edit')">
                <option value="">-- Seleccionar --</option>
                <option value="1">Por Día</option>
                <option value="2">Por Período</option>
            </select>
        </div>
    </div>
    <!-- Campos tipo Día -->
    <div id="sus_edit_day_fields" style="display:none;">
        <div class="form-group row">
            <label class="col-3 col-form-label">Fecha Inicio:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="date_start" id="sus_edit_date_start">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Fecha Fin:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="date_end" id="sus_edit_date_end">
            </div>
        </div>
    </div>
    <!-- Campos tipo Período -->
    <div id="sus_edit_period_fields" style="display:none;">
        <div class="form-group row">
            <label class="col-3 col-form-label">Fecha:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="date" id="sus_edit_date">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Período:</label>
            <div class="col-9">
                <select class="form-control" name="period_id" id="sus_edit_period_id">
                    <option value="">-- Seleccionar --</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Motivo:</label>
        <div class="col-9">
            <textarea class="form-control" name="reason" id="sus_edit_reason" rows="3" required></textarea>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
</div>
</form>
<script>
(function () {
    window.toggleSuspensionFields = function (prefix) {
        var val = $('#sus_' + prefix + '_type').val();
        $('#sus_' + prefix + '_day_fields').toggle(val === '1');
        $('#sus_' + prefix + '_period_fields').toggle(val === '2');
    };
    // Cargar listas y luego pre-llenar con datos actuales
    var studentsLoaded = false, periodsLoaded = false;
    var susData = null;
    function tryFill() {
        if (studentsLoaded && periodsLoaded && susData) {
            $('#sus_edit_student').val(susData.student_id);
            $('#sus_edit_type').val(susData.type).trigger('change');
            $('#sus_edit_reason').val(susData.reason);
            if (susData.type == 1) {
                $('#sus_edit_date_start').val(susData.date_start || '');
                $('#sus_edit_date_end').val(susData.date_end || '');
            } else {
                $('#sus_edit_date').val(susData.date || '');
                $('#sus_edit_period_id').val(susData.period_id || '');
            }
        }
    }
    $.ajax({
        url: "<?php echo base_url(); ?>/secretary/suspension_get_students",
        success: function (data) {
            var sel = $('#sus_edit_student');
            $.each(data, function (i, s) {
                sel.append('<option value="' + s.student_id + '">' + s.student + '</option>');
            });
            studentsLoaded = true;
            tryFill();
        }
    });
    $.ajax({
        url: "<?php echo base_url(); ?>/secretary/suspension_get_periods",
        success: function (data) {
            var sel = $('#sus_edit_period_id');
            $.each(data, function (i, p) {
                sel.append('<option value="' + p.periodo_id + '">' + p.periodo + '</option>');
            });
            periodsLoaded = true;
            tryFill();
        }
    });
    $.ajax({
        url: "<?php echo base_url(); ?>/secretary/suspension_get/<?php echo $param1; ?>",
        success: function (data) {
            susData = data;
            tryFill();
        }
    });
})();
</script>
<!--end::Modal-->