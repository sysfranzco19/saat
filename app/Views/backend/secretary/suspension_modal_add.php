<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title">Nueva Suspensión</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>/secretary/suspension_create" method="post" class="form">
<div class="modal-body">
    <div class="form-group row">
        <label class="col-3 col-form-label">Estudiante:</label>
        <div class="col-9">
            <select class="form-control" name="student_id" id="sus_add_student" required>
                <option value="">-- Seleccionar --</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Tipo:</label>
        <div class="col-9">
            <select class="form-control" name="type" id="sus_add_type" required onchange="toggleSuspensionFields('add')">
                <option value="">-- Seleccionar --</option>
                <option value="1">Por Día</option>
                <option value="2">Por Período</option>
            </select>
        </div>
    </div>
    <!-- Campos tipo Día -->
    <div id="sus_add_day_fields" style="display:none;">
        <div class="form-group row">
            <label class="col-3 col-form-label">Fecha Inicio:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="date_start" id="sus_add_date_start">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Fecha Fin:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="date_end" id="sus_add_date_end">
            </div>
        </div>
    </div>
    <!-- Campos tipo Período -->
    <div id="sus_add_period_fields" style="display:none;">
        <div class="form-group row">
            <label class="col-3 col-form-label">Fecha:</label>
            <div class="col-9">
                <input type="date" class="form-control" name="date" id="sus_add_date">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Período:</label>
            <div class="col-9">
                <select class="form-control" name="period_id" id="sus_add_period_id">
                    <option value="">-- Seleccionar --</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Motivo:</label>
        <div class="col-9">
            <textarea class="form-control" name="reason" id="sus_add_reason" rows="3" placeholder="Detalle el motivo de la suspensión" required></textarea>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
</div>
</form>
<script>
(function () {
    function loadPeriodsBySection(section_id) {
        var sel = $('#sus_add_period_id');
        sel.html('<option value="">-- Seleccionar --</option>');
        if (!section_id) return;
        $.ajax({
            url: "<?php echo base_url(); ?>/secretary/suspension_get_periods_section/" + section_id,
            success: function (data) {
                $.each(data, function (i, p) {
                    sel.append('<option value="' + p.periodo_id + '">' + p.periodo + '</option>');
                });
            }
        });
    }
    $.ajax({
        url: "<?php echo base_url(); ?>/secretary/suspension_get_students",
        success: function (data) {
            var sel = $('#sus_add_student');
            $.each(data, function (i, s) {
                sel.append('<option value="' + s.student_id + '" data-section="' + s.section_id + '">' + s.student + '</option>');
            });
        }
    });
    $('#sus_add_student').on('change', function () {
        var section_id = $('option:selected', this).data('section');
        loadPeriodsBySection(section_id);
    });
    window.toggleSuspensionFields = function (prefix) {
        var val = $('#sus_' + prefix + '_type').val();
        $('#sus_' + prefix + '_day_fields').toggle(val === '1');
        $('#sus_' + prefix + '_period_fields').toggle(val === '2');
    };
})();
</script>
<!--end::Modal-->