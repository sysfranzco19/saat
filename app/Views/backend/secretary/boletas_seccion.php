<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <!-- Header card -->
        <div class="card card-custom gutter-b">
            <div class="card-body py-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="font-weight-bolder mb-1">
                            <span class="label label-warning label-inline mr-2">BOLETA VERDE</span>
                            <?= $section['completo'] ?>
                        </h4>
                        <span class="text-muted font-size-sm">Faltas graves · <?= $phase_name ?></span>
                    </div>
                    <a href="<?= base_url('secretary/boletas') ?>" class="btn btn-light-warning btn-sm font-weight-bold">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Formulario nueva boleta -->
            <div class="col-lg-4">
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder text-dark">
                            <i class="fas fa-file-alt text-warning mr-2"></i>Nueva Boleta
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div class="form-group">
                            <label class="font-weight-bold">Estudiante <span class="text-danger">*</span></label>
                            <select id="sel_student" class="form-control select2">
                                <option value="">-- Seleccionar estudiante --</option>
                                <?php foreach ($students as $s): ?>
                                <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['student']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tipo de falta <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <label class="option flex-grow-1 mr-2">
                                    <span class="option-control"><span class="radio"><input type="radio" name="tipo_boleta" value="aula" checked><span></span></span></span>
                                    <span class="option-label"><span class="option-head"><span class="option-title">Dentro del aula</span></span><span class="option-body text-muted font-size-sm">Afecta solo la materia seleccionada (−3 pts)</span></span>
                                </label>
                                <label class="option flex-grow-1">
                                    <span class="option-control"><span class="radio"><input type="radio" name="tipo_boleta" value="recreo"><span></span></span></span>
                                    <span class="option-label"><span class="option-head"><span class="option-title">Recreo / fuera del aula</span></span><span class="option-body text-muted font-size-sm">Afecta <strong>todas</strong> las materias (−3 pts c/u)</span></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="row_subject">
                            <label class="font-weight-bold">Materia afectada <span class="text-danger">*</span></label>
                            <select id="sel_subject" class="form-control select2">
                                <option value="">-- Seleccionar materia --</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Fecha de la falta <span class="text-danger">*</span></label>
                            <input type="date" id="inp_fecha" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Descripción de la falta</label>
                            <textarea id="inp_descripcion" class="form-control" rows="3" placeholder="Describe brevemente la conducta..."></textarea>
                        </div>

                        <div class="separator separator-dashed my-4"></div>

                        <div class="form-group">
                            <label class="font-weight-bold">Días de suspensión</label>
                            <div class="input-group">
                                <input type="number" id="inp_dias" class="form-control" min="0" max="30" value="0">
                                <div class="input-group-append"><span class="input-group-text">días</span></div>
                            </div>
                            <span class="form-text text-muted">0 = sin suspensión</span>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Medidas restaurativas / Acción correctiva</label>
                            <textarea id="inp_medidas" class="form-control" rows="3" placeholder="Ej: Disculpa pública, servicio comunitario, reparación del daño..."></textarea>
                        </div>

                        <button id="btn_guardar_boleta" class="btn btn-warning btn-block font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Emitir Boleta
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de boletas -->
            <div class="col-lg-8">
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder text-dark">
                            <i class="fas fa-list text-warning mr-2"></i>Boletas registradas
                        </h3>
                        <div class="card-toolbar">
                            <span class="text-muted font-size-sm" id="lbl_total"></span>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div id="tabla_boletas_wrap">
                            <div class="text-center text-muted py-10">
                                <i class="fas fa-spinner fa-spin fa-2x"></i>
                                <p class="mt-3">Cargando...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
var SECTION_ID  = <?= $section_id ?>;
var PHASE_ID    = <?= $phase_id ?>;
var BASE_URL    = '<?= base_url() ?>';
var allSubjects = [];
var allBoletas  = [];

$(document).ready(function () {
    if ($.fn.select2) {
        $('#sel_student, #sel_subject').select2({ width: '100%' });
    }

    // Load data
    loadData();

    // Toggle materia row based on tipo
    $('input[name="tipo_boleta"]').on('change', function () {
        if ($(this).val() === 'aula') {
            $('#row_subject').show();
        } else {
            $('#row_subject').hide();
        }
    });

    // Save boleta
    $('#btn_guardar_boleta').on('click', function () {
        var student_id  = $('#sel_student').val();
        var tipo        = $('input[name="tipo_boleta"]:checked').val();
        var subject_id  = tipo === 'aula' ? $('#sel_subject').val() : null;
        var fecha       = $('#inp_fecha').val();
        var descripcion = $('#inp_descripcion').val();
        var dias        = $('#inp_dias').val();
        var medidas     = $('#inp_medidas').val();

        if (!student_id) { toastr.warning('Selecciona un estudiante.'); return; }
        if (!fecha)       { toastr.warning('Ingresa la fecha de la falta.'); return; }
        if (tipo === 'aula' && !subject_id) { toastr.warning('Selecciona la materia afectada.'); return; }

        var btn = $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...');

        $.ajax({
            url: BASE_URL + 'secretary/boletas_guardar',
            method: 'POST',
            data: {
                student_id:            student_id,
                subject_id:            subject_id || '',
                phase_id:              PHASE_ID,
                tipo:                  tipo,
                fecha:                 fecha,
                descripcion:           descripcion,
                dias_suspension:       dias,
                medidas_restaurativas: medidas,
            },
            dataType: 'json',
            success: function (res) {
                if (res.status === 'ok') {
                    toastr.success('Boleta registrada correctamente.');
                    $('#inp_descripcion').val('');
                    $('#inp_dias').val('0');
                    $('#inp_medidas').val('');
                    loadData();
                } else {
                    toastr.error(res.message || 'Error al guardar.');
                }
            },
            error: function () { toastr.error('Error de conexión.'); },
            complete: function () {
                $('#btn_guardar_boleta').prop('disabled', false)
                    .html('<i class="fas fa-save mr-1"></i> Emitir Boleta');
            }
        });
    });
});

function loadData() {
    $.ajax({
        url: BASE_URL + 'secretary/boletas_get_data',
        method: 'GET',
        data: { section_id: SECTION_ID, phase_id: PHASE_ID },
        dataType: 'json',
        success: function (res) {
            allSubjects = res.subjects || [];
            allBoletas  = res.boletas  || [];
            populateSubjects();
            renderTable();
        },
        error: function () {
            $('#tabla_boletas_wrap').html('<div class="alert alert-danger">Error al cargar los datos.</div>');
        }
    });
}

function populateSubjects() {
    var $sel = $('#sel_subject').empty().append('<option value="">-- Seleccionar materia --</option>');
    allSubjects.forEach(function (s) {
        $sel.append('<option value="' + s.subject_id + '">' + escHtml(s.name) + '</option>');
    });
    if ($.fn.select2) $sel.trigger('change');
}

function renderTable() {
    $('#lbl_total').text(allBoletas.length + ' boleta(s)');
    if (allBoletas.length === 0) {
        $('#tabla_boletas_wrap').html('<div class="text-center text-muted py-10"><i class="fas fa-check-circle fa-2x text-success"></i><p class="mt-3">No hay boletas registradas en este período.</p></div>');
        return;
    }

    var html = '<div class="table-responsive"><table class="table table-hover table-bordered" id="tbl_boletas"><thead class="thead-light"><tr>'
        + '<th>Estudiante</th><th>Tipo</th><th>Materia</th><th>Fecha</th>'
        + '<th>Descripción</th><th>Suspensión</th><th>Medidas restaurativas</th><th></th>'
        + '</tr></thead><tbody>';

    allBoletas.forEach(function (b) {
        var tipoBadge = b.tipo === 'aula'
            ? '<span class="label label-light-primary label-inline">Aula</span>'
            : '<span class="label label-light-danger label-inline">Recreo</span>';
        var materia = b.subject_name ? escHtml(b.subject_name) : '<span class="text-muted">Todas</span>';
        var dias    = parseInt(b.dias_suspension) > 0
            ? '<span class="label label-light-warning label-inline">' + b.dias_suspension + ' día(s)</span>'
            : '<span class="text-muted">—</span>';
        var medidas = b.medidas_restaurativas ? escHtml(b.medidas_restaurativas) : '<span class="text-muted">—</span>';

        html += '<tr>'
            + '<td class="font-weight-bold">' + escHtml(b.student_name) + '</td>'
            + '<td>' + tipoBadge + '</td>'
            + '<td>' + materia + '</td>'
            + '<td>' + formatDate(b.fecha) + '</td>'
            + '<td>' + (b.descripcion ? escHtml(b.descripcion) : '<span class="text-muted">—</span>') + '</td>'
            + '<td>' + dias + '</td>'
            + '<td class="font-size-sm">' + medidas + '</td>'
            + '<td><button class="btn btn-icon btn-sm btn-light-danger btn-delete-boleta" data-id="' + b.id + '" title="Eliminar"><i class="fas fa-trash-alt"></i></button></td>'
            + '</tr>';
    });

    html += '</tbody></table></div>';
    $('#tabla_boletas_wrap').html(html);

    if ($.fn.DataTable) {
        $('#tbl_boletas').DataTable({
            responsive: true,
            order: [[3, 'desc']],
            language: { url: BASE_URL + 'assets/plugins/i18n/Spanish.json' },
            pageLength: 25,
        });
    }

    $(document).off('click', '.btn-delete-boleta').on('click', '.btn-delete-boleta', function () {
        var id = $(this).data('id');
        if (!confirm('¿Eliminar esta boleta? La acción no se puede deshacer.')) return;
        $.ajax({
            url: BASE_URL + 'secretary/boletas_eliminar',
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function (res) {
                if (res.status === 'ok') {
                    toastr.success('Boleta eliminada.');
                    loadData();
                } else {
                    toastr.error(res.message || 'Error al eliminar.');
                }
            },
            error: function () { toastr.error('Error de conexión.'); }
        });
    });
}

function formatDate(d) {
    if (!d) return '—';
    var parts = d.split('-');
    return parts.length === 3 ? parts[2] + '/' + parts[1] + '/' + parts[0] : d;
}

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
