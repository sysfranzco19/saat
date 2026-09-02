<style>
.tab-pill { border-radius:20px; padding:6px 18px; font-weight:700; margin-right:6px; cursor:pointer;
            background:#f3e8ff; color:#6d28d9; border:none; transition:background .15s; }
.tab-pill.active { background:#6d28d9; color:#fff; }
.tab-content-pane { display:none; }
.tab-content-pane.active { display:block; }
.badge-retraso { background:#ede9fe; color:#6d28d9; border-radius:12px;
                 padding:3px 10px; font-size:.8rem; font-weight:700; }
.filter-bar { background: #f8f9fc; border-radius: 10px; padding: 14px 18px; }
.btn-rapido {
    border: 2px solid #6d28d9; background: #fff; color: #6d28d9;
    border-radius: 20px; padding: 5px 16px; font-weight: 700;
    font-size: .82rem; cursor: pointer; transition: all .15s; margin-right: 6px;
}
.btn-rapido:hover  { background: #f3e8ff; }
.btn-rapido.active { background: #6d28d9; color: #fff; }
.retraso-counter { background:#f3e8ff; border-radius:10px; padding:14px 18px; }
.retraso-num     { font-size:2.4rem; font-weight:800; color:#6d28d9; line-height:1; }
.retraso-label   { font-size:.8rem; color:#7e57c2; }
.tipo-opt  { border:2px solid #e0e0e0; border-radius:10px; padding:14px 16px; cursor:pointer;
             transition:all .2s; display:block; }
</style>

<div class="d-flex flex-column-fluid">
<div class="container-fluid">

<div class="card card-custom">
    <div class="card-header flex-wrap">
        <div class="card-title">
            <h3 class="card-label">
                <i class="fas fa-clock text-purple mr-2"></i> Retrasos — Primaria
                <small class="text-muted d-block mt-1"><?= esc($phase_name) ?></small>
            </h3>
        </div>
        <div class="card-toolbar">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalNuevoRetraso">
                <i class="fas fa-plus mr-1"></i> Registrar Retraso
            </button>
        </div>
    </div>

    <div class="card-body">

        <!-- Tabs -->
        <div class="mb-5">
            <button class="tab-pill active" onclick="showTab('detalle', this)">Detalle</button>
            <button class="tab-pill" onclick="showTab('trimestre', this)">
                Resumen Trimestre
                <span class="badge badge-light ml-1"><?= count($retrasos_trimestre) ?></span>
            </button>
        </div>

        <!-- Tab: Detalle (AJAX) -->
        <div class="tab-content-pane active" id="tab_detalle">

            <div class="filter-bar mb-4">
                <div class="mb-3">
                    <label class="font-weight-bold font-size-sm mr-3">Período rápido:</label>
                    <button class="btn-rapido active" data-periodo="hoy">Hoy</button>
                    <button class="btn-rapido" data-periodo="semana">Esta semana</button>
                </div>
                <div class="d-flex flex-wrap align-items-end gap-3">
                    <div>
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Desde</label>
                        <input type="date" id="r_ini" class="form-control form-control-sm" style="width:145px;" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div>
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Hasta</label>
                        <input type="date" id="r_fin" class="form-control form-control-sm" style="width:145px;" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="flex-grow-1">
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Buscar</label>
                        <input type="text" id="r_search" class="form-control form-control-sm" placeholder="Nombre, curso, motivo..." style="min-width:200px;">
                    </div>
                    <div>
                        <button id="r_btn_buscar" class="btn btn-primary btn-sm font-weight-bold px-5">
                            <i class="fas fa-search mr-1"></i>Buscar
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Hora Entrada</th>
                            <th>Alumno</th>
                            <th>Curso</th>
                            <th>Motivo</th>
                            <th>Observación</th>
                        </tr>
                    </thead>
                    <tbody id="r_tbody">
                        <tr><td colspan="6" class="text-center text-muted py-8">Cargando...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Resumen Trimestre -->
        <div class="tab-content-pane" id="tab_trimestre">
            <p class="text-muted font-size-sm mb-3">
                <i class="fas fa-calendar-alt mr-1"></i>
                Resumen por alumno — <?= esc($phase_name) ?>
                &nbsp;·&nbsp; <?= count($retrasos_trimestre) ?> alumno(s) con retrasos
            </p>
            <?php if (empty($retrasos_trimestre)): ?>
                <div class="text-center text-muted py-8">
                    <i class="fas fa-check-circle fa-2x mb-2" style="color:#6d28d9; opacity:.3;"></i>
                    <p>Sin retrasos registrados este trimestre</p>
                </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="tbl_trimestre">
                    <thead class="thead-light">
                        <tr>
                            <th>Alumno</th>
                            <th>Curso</th>
                            <th class="text-center">Total Retrasos</th>
                            <th>Primer Retraso</th>
                            <th>Último Retraso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($retrasos_trimestre as $r): ?>
                        <tr>
                            <td class="font-weight-bold"><?= esc($r['alumno']) ?></td>
                            <td><span class="badge badge-light-primary"><?= esc($r['curso']) ?></span></td>
                            <td class="text-center">
                                <span class="badge-retraso"><?= (int)$r['total_retrasos'] ?></span>
                            </td>
                            <td class="text-muted"><?= date('d/m/Y', strtotime($r['primera_fecha'])) ?></td>
                            <td class="text-muted"><?= date('d/m/Y', strtotime($r['ultima_fecha'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>

</div>
</div>

<!-- Modal registrar retraso -->
<div class="modal fade" id="modalNuevoRetraso" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_ret">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">⏱ Registrar Retraso — Primaria</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="font-weight-bold">Estudiante <span class="text-danger">*</span></label>
                                <select class="form-control select2" style="width:100%;" id="ret_student_sel" onchange="retSeleccionarAlumno(this.value)">
                                    <option value="">Seleccione un estudiante...</option>
                                    <?php foreach ($students_flat as $stu): ?>
                                        <option value="<?= $stu['student_id'] ?>"
                                                data-section="<?= esc($stu['nick_name']) ?>"
                                                data-section-id="<?= $stu['section_id'] ?>">
                                            <?= esc($stu['name']) ?> — <?= esc($stu['nick_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-size-sm text-muted">Curso</label>
                                <input type="text" id="ret_disp_curso" class="form-control form-control-sm" disabled>
                            </div>
                            <div id="ret_panel" style="display:none;">
                                <div class="retraso-counter text-center">
                                    <div class="retraso-num" id="ret_num">0</div>
                                    <div class="retraso-label mt-1">retrasos este trimestre</div>
                                    <div id="ret_alerta" class="mt-2" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <input type="hidden" id="ret_student_id" name="student_id">
                            <input type="hidden" id="ret_section_id" name="section_id">

                            <div class="form-group row">
                                <label class="col-4 col-form-label font-weight-bold">Fecha <span class="text-danger">*</span></label>
                                <div class="col-8">
                                    <input type="date" class="form-control" id="ret_fecha" name="fecha" disabled required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label font-weight-bold">Hora Entrada</label>
                                <div class="col-8">
                                    <input type="time" class="form-control" id="ret_hora_entrada" name="hora_entrada" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label font-weight-bold">Motivo</label>
                                <div class="col-8">
                                    <input type="text" id="ret_motivo" name="motivo" class="form-control" value="Sin información" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-4 col-form-label font-weight-bold">Observación</label>
                                <div class="col-8">
                                    <input type="text" id="ret_detalle" name="detalle" class="form-control" placeholder="Opcional" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold" id="ret_btn_registrar" disabled>
                        <i class="fas fa-clock mr-1"></i> Registrar Retraso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const BASE = '<?= base_url() ?>';
var _dtTrimestre = null;

function post(url, body) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type':'application/x-www-form-urlencoded', 'X-Requested-With':'XMLHttpRequest' },
        body: Object.entries(body).map(([k,v])=>encodeURIComponent(k)+'='+encodeURIComponent(v)).join('&')
    }).then(r => r.json());
}

function showTab(name, btn) {
    document.querySelectorAll('.tab-content-pane').forEach(function(el) {
        el.classList.remove('active');
    });
    document.querySelectorAll('.tab-pill').forEach(function(el) {
        el.classList.remove('active');
    });
    document.getElementById('tab_' + name).classList.add('active');
    btn.classList.add('active');

    if (name === 'trimestre' && _dtTrimestre) {
        _dtTrimestre.columns.adjust().draw(false);
    }
}

$(document).ready(function() {
    if ($.fn.DataTable && $('#tbl_trimestre').length) {
        _dtTrimestre = $('#tbl_trimestre').DataTable({
            language: { url: '<?= base_url('assets/plugins/datatables/spanish.json') ?>' },
            order: [[2, 'desc']],
            pageLength: 25,
        });
    }
});

// ── Tab Detalle (AJAX) ──────────────────────────────────────────────────────

function renderRetrasos(data) {
    const tbody = document.getElementById('r_tbody');
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-8">Sin retrasos para el período seleccionado</td></tr>';
        return;
    }
    tbody.innerHTML = data.map(r => `<tr>
        <td>${r.fecha ? r.fecha.split('-').reverse().join('/') : '—'}</td>
        <td><span class="font-weight-bold text-purple">${r.hora_entrada ? r.hora_entrada.substring(0,5) : '—'}</span></td>
        <td>${r.alumno}</td>
        <td><span class="badge badge-light-primary">${r.curso}</span></td>
        <td>${r.motivo || '—'}</td>
        <td class="text-muted">${r.detalle || ''}</td>
    </tr>`).join('');
}

function cargarRetrasos() {
    const tbody = document.getElementById('r_tbody');
    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-8"><i class="fas fa-spinner fa-spin mr-2"></i>Cargando...</td></tr>';

    post(BASE + 'secretary/prim_retrasos_data', {
        fecha_ini: document.getElementById('r_ini').value,
        fecha_fin: document.getElementById('r_fin').value,
        search:    document.getElementById('r_search').value,
    }).then(renderRetrasos)
    .catch(() => { tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-8">Error al cargar datos</td></tr>'; });
}

document.querySelectorAll('.btn-rapido').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.btn-rapido').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const today = new Date();
        const pad = n => ('0'+n).slice(-2);
        const toYMD = d => d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate());

        let ini, fin = toYMD(today);
        if (this.dataset.periodo === 'hoy') {
            ini = fin;
        } else {
            const dow = today.getDay() === 0 ? 7 : today.getDay(); // 1=lun..7=dom
            const lunes = new Date(today);
            lunes.setDate(today.getDate() - (dow - 1));
            ini = toYMD(lunes);
        }

        document.getElementById('r_ini').value = ini;
        document.getElementById('r_fin').value = fin;
        cargarRetrasos();
    });
});

document.getElementById('r_btn_buscar').addEventListener('click', function() {
    document.querySelectorAll('.btn-rapido').forEach(b => b.classList.remove('active'));
    cargarRetrasos();
});
document.getElementById('r_search').addEventListener('keydown', e => { if (e.key === 'Enter') cargarRetrasos(); });

cargarRetrasos();

// ── Modal "Registrar Retraso" ───────────────────────────────────────────────

$(document).ready(function() { $('#ret_student_sel').select2({ dropdownParent: $('#modalNuevoRetraso') }); });

function retResetForm() {
    document.getElementById('form_ret').reset();
    $('#ret_student_sel').val('').trigger('change');
    document.getElementById('ret_disp_curso').value = '';
    document.getElementById('ret_panel').style.display = 'none';
    ['ret_fecha','ret_hora_entrada','ret_motivo','ret_detalle'].forEach(id => document.getElementById(id).disabled = true);
    document.getElementById('ret_btn_registrar').disabled = true;
}

function retSeleccionarAlumno(student_id) {
    if (!student_id) return;

    var opt = document.querySelector('#ret_student_sel option[value="' + student_id + '"]');
    document.getElementById('ret_disp_curso').value = opt ? opt.dataset.section   : '';
    document.getElementById('ret_student_id').value = student_id;
    document.getElementById('ret_section_id').value = opt ? opt.dataset.sectionId : '';

    var hoy = new Date();
    var pad = n => ('0'+n).slice(-2);
    document.getElementById('ret_fecha').value        = hoy.getFullYear()+'-'+pad(hoy.getMonth()+1)+'-'+pad(hoy.getDate());
    document.getElementById('ret_hora_entrada').value = pad(hoy.getHours())+':'+pad(hoy.getMinutes());
    document.getElementById('ret_motivo').value = 'Sin información';

    ['ret_fecha','ret_hora_entrada','ret_motivo','ret_detalle'].forEach(id => document.getElementById(id).disabled = false);
    document.getElementById('ret_btn_registrar').disabled = false;

    post(BASE + 'secretary/prim_retraso_count', { student_id: student_id }).then(function(r) {
        document.getElementById('ret_panel').style.display = '';
        document.getElementById('ret_num').textContent = r.count;

        var alerta = document.getElementById('ret_alerta');
        if (r.count >= 5) {
            alerta.innerHTML = '<div class="alert alert-warning py-2 mb-0 font-size-sm">'
                + '<i class="fas fa-exclamation-circle mr-1"></i> '
                + '<strong>' + r.count + ' retrasos</strong> este trimestre'
                + '</div>';
            alerta.style.display = '';
        } else {
            alerta.style.display = 'none';
        }
    });
}

document.getElementById('form_ret').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!document.getElementById('ret_student_id').value) { alert('Seleccione un estudiante'); return; }
    if (!document.getElementById('ret_fecha').value) { alert('Ingrese la fecha del retraso'); return; }

    var btn = document.getElementById('ret_btn_registrar');
    btn.disabled = true;
    btn.textContent = 'Guardando...';

    post(BASE + 'secretary/prim_retrasos_create', {
        student_id:   document.getElementById('ret_student_id').value,
        section_id:   document.getElementById('ret_section_id').value,
        fecha:        document.getElementById('ret_fecha').value,
        hora_entrada: document.getElementById('ret_hora_entrada').value,
        motivo:       document.getElementById('ret_motivo').value,
        detalle:      document.getElementById('ret_detalle').value,
    }).then(res => {
        if (res.ok) {
            $('#modalNuevoRetraso').modal('hide');
            retResetForm();
            cargarRetrasos();
        } else {
            alert(res.msg || 'Error al registrar');
        }
    })
    .catch(() => alert('Error al registrar'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-clock mr-1"></i> Registrar Retraso';
    });
});

$('#modalNuevoRetraso').on('hidden.bs.modal', retResetForm);
</script>
