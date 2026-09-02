<style>
.rec-header  { background: linear-gradient(135deg, #e65100 0%, #ad3d00 100%); border-radius: 12px; }
.stat-pill   { border-radius: 10px; padding: 14px 20px; text-align: center; min-width: 110px; }
.stat-pill .num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
.stat-pill .lbl { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; opacity: .75; }
.filter-bar  { background: #f8f9fc; border-radius: 10px; padding: 14px 18px; }
.tab-filter  { border: none; border-radius: 20px; padding: 6px 18px; font-weight: 700;
               font-size: .85rem; cursor: pointer; transition: all .2s; margin-right: 6px; }
.tab-filter.active        { background: #e65100; color: #fff; }
.tab-filter.tf-pending    { background: #fff8dd; color: #ffa800; }
.tab-filter.tf-pending.active { background: #ffa800; color: #fff; }
.tab-filter.tf-approved   { background: #e8fff3; color: #50cd89; }
.tab-filter.tf-approved.active{ background: #50cd89; color: #fff; }
.tab-filter.tf-rejected   { background: #fff0f2; color: #f1416c; }
.tab-filter.tf-rejected.active{ background: #f1416c; color: #fff; }
.tab-filter.tf-all        { background: #fff3e0; color: #e65100; }
.estado-pill { padding: 4px 12px; border-radius: 14px; font-size: .8rem; font-weight: 700; }
.ep-pending  { background: #fff8dd; color: #ffa800; }
.ep-approved { background: #e8fff3; color: #50cd89; }
.ep-rejected { background: #fff0f2; color: #f1416c; }
.btn-aprobar { background: #e8fff3; color: #50cd89; border: 1px solid #50cd89;
               border-radius: 8px; padding: 5px 12px; font-weight: 700; font-size: .82rem;
               cursor: pointer; transition: all .15s; }
.btn-aprobar:hover  { background: #50cd89; color: #fff; }
.btn-rechazar { background: #fff0f2; color: #f1416c; border: 1px solid #f1416c;
               border-radius: 8px; padding: 5px 12px; font-weight: 700; font-size: .82rem;
               cursor: pointer; transition: all .15s; }
.btn-rechazar:hover { background: #f1416c; color: #fff; }
#rec_table td { vertical-align: middle; }
.loading-row td { text-align: center; color: #aaa; padding: 40px; }
.tipo-opt  { border:2px solid #e0e0e0; border-radius:10px; padding:14px 16px; cursor:pointer;
             transition:all .2s; display:block; }
.tipo-opt:hover { border-color:#e65100; }
.tipo-opt input { margin-right:8px; }
.tipo-opt.checked { border-color:#e65100; background:#fff8f0; }
</style>

<div class="container-fluid pb-8">

    <!-- Header -->
    <div class="rec-header shadow mb-6 p-5 d-flex align-items-center">
        <div>
            <h3 class="text-white font-weight-bolder mb-1">🚪 Cambio de Recojo — Primaria</h3>
            <span class="text-white opacity-75 font-size-sm">
                3ro a 6to de Primaria &nbsp;·&nbsp; Avisos de los padres sobre quién/cómo recoge al estudiante
            </span>
        </div>
        <div class="ml-auto">
            <button type="button" class="btn btn-light font-weight-bold" data-toggle="modal" data-target="#modalNuevo">
                <i class="fas fa-phone-alt mr-1"></i> Registrar por Llamada
            </button>
        </div>
    </div>

    <!-- Tarjetas de estado -->
    <div class="d-flex flex-wrap mb-5 gap-3" id="stats_rec">
        <div class="stat-pill shadow-sm" style="background:#fff3e0;">
            <div class="num" style="color:#e65100;" id="cnt_total">—</div>
            <div class="lbl" style="color:#e65100;">Total</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#fff8dd;">
            <div class="num text-warning" id="cnt_pending">—</div>
            <div class="lbl text-warning">Pendientes</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#e8fff3;">
            <div class="num text-success" id="cnt_approved">—</div>
            <div class="lbl text-success">Aprobados</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#fff0f2;">
            <div class="num text-danger" id="cnt_rejected">—</div>
            <div class="lbl text-danger">Rechazados</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card card-custom shadow-sm">
        <div class="card-body py-4">

            <div class="filter-bar mb-4">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div>
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Desde</label>
                        <input type="date" id="fi_ini" class="form-control form-control-sm" style="width:145px;"
                               value="<?= date('Y-m-d', strtotime('-7 days')) ?>">
                    </div>
                    <div>
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Hasta</label>
                        <input type="date" id="fi_fin" class="form-control form-control-sm" style="width:145px;"
                               value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="flex-grow-1">
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Buscar</label>
                        <input type="text" id="fi_search" class="form-control form-control-sm"
                               placeholder="Nombre, curso, persona..." style="min-width:200px;">
                    </div>
                    <div class="align-self-end">
                        <button id="btn_buscar" class="btn btn-primary btn-sm font-weight-bold px-5">
                            <i class="fas fa-search mr-1"></i>Buscar
                        </button>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <button class="tab-filter tf-pending active" data-estado="pending">
                    ⏳ Pendientes <span id="tbadge_pending" class="badge badge-warning ml-1"></span>
                </button>
                <button class="tab-filter tf-all" data-estado="all">Todos</button>
                <button class="tab-filter tf-approved" data-estado="approved">✓ Aprobados</button>
                <button class="tab-filter tf-rejected" data-estado="rejected">✗ Rechazados</button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="rec_table">
                    <thead class="bg-light">
                        <tr class="text-muted text-uppercase font-size-xs">
                            <th>Fecha y hora</th>
                            <th>Alumno / Curso</th>
                            <th>Tipo</th>
                            <th>Detalle</th>
                            <th>Solicitante</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="rec_tbody">
                        <tr class="loading-row"><td colspan="7">Cargando...</td></tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<!-- Modal confirmar aprobación / rechazo -->
<div class="modal fade" id="modalAccion" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-3" id="modalAccionHeader">
                <h6 class="modal-title font-weight-bolder mb-0" id="modalAccionTitle"></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body py-3">
                <p class="font-size-sm mb-2" id="modalAccionDesc"></p>
                <textarea id="modalAccionObs" class="form-control form-control-sm"
                    rows="3" placeholder="Nota interna (opcional)..." style="resize:none;"></textarea>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-sm font-weight-bold" id="modalAccionConfirm">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal registrar por llamada -->
<div class="modal fade" id="modalNuevo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_rec">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">🚪 Registrar Cambio de Recojo — Llamada</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="alert alert-light-primary font-size-sm mb-4">
                        <i class="fas fa-phone-alt mr-1"></i>
                        Use este formulario cuando un padre/madre <strong>llame por teléfono</strong> para avisar un
                        cambio de recojo. Queda registrado como <strong>aprobado</strong> directamente, sin pasar por revisión.
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Estudiante <span class="text-danger">*</span></label>
                        <select class="form-control select2" style="width:100%;" id="rec_student_sel" onchange="recSeleccionarAlumno(this.value)">
                            <option value="">Seleccione un estudiante...</option>
                            <?php foreach ($students_flat as $stu): ?>
                                <option value="<?= $stu['student_id'] ?>" data-section="<?= esc($stu['nick_name']) ?>">
                                    <?= esc($stu['name']) ?> — <?= esc($stu['nick_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">¿Qué cambia hoy? <span class="text-danger">*</span></label>

                        <label class="tipo-opt mb-2">
                            <input type="radio" name="tipo" value="1" onchange="recTipoChange(1, event)" disabled required>
                            <strong>Recogerá otra persona</strong>
                            <div class="text-muted font-size-sm ml-4">Distinta al padre/madre/tutor habitual</div>
                        </label>

                        <label class="tipo-opt mb-2">
                            <input type="radio" name="tipo" value="2" onchange="recTipoChange(2, event)" disabled>
                            <strong>No usará transporte escolar</strong>
                            <div class="text-muted font-size-sm ml-4">El estudiante se irá con sus papás</div>
                        </label>

                        <label class="tipo-opt mb-2">
                            <input type="radio" name="tipo" value="3" onchange="recTipoChange(3, event)" disabled>
                            <strong>Otro</strong>
                            <div class="text-muted font-size-sm ml-4">Especifique el detalle</div>
                        </label>
                    </div>

                    <div id="rec_bloque_persona" style="display:none;">
                        <div class="form-group row">
                            <label class="col-3 col-form-label font-weight-bold">Nombre de la persona <span class="text-danger">*</span></label>
                            <div class="col-9">
                                <input type="text" class="form-control" name="persona_nombre" id="rec_persona_nombre" placeholder="Nombre completo">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3 col-form-label font-weight-bold">Parentesco con el estudiante <span class="text-danger">*</span></label>
                            <div class="col-9">
                                <select class="form-control" name="persona_parentesco_id" id="rec_persona_parentesco_id" onchange="toggleRecPersonaOtro()">
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($parentescos as $p): ?>
                                        <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                    <?php endforeach; ?>
                                    <option value="0">Otro (especifique)</option>
                                </select>
                                <input type="text" class="form-control mt-2" name="persona_parentesco_otro"
                                    id="rec_persona_parentesco_otro" placeholder="Especifique el parentesco" style="display:none;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">
                            Detalle <span class="text-danger" id="rec_detalle_req" style="display:none;">*</span>
                        </label>
                        <div class="col-9">
                            <textarea class="form-control" name="detalle" id="rec_detalle" rows="2" placeholder="Información adicional" disabled></textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Parentesco de quien llama <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <select class="form-control" name="parentesco" id="rec_parentesco" onchange="recFillParent(this.value)" disabled required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($parentescos as $p): ?>
                                    <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Nombre de quien llama <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <input type="text" id="rec_solicitante" name="solicitante" class="form-control" disabled required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm font-weight-bold" id="rec_btn_registrar" disabled>
                        <i class="fas fa-save mr-1"></i> Registrar Cambio de Recojo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const BASE   = '<?= base_url() ?>';
let _estado  = 'pending';
let _allData = [];

function post(url, body) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type':'application/x-www-form-urlencoded', 'X-Requested-With':'XMLHttpRequest' },
        body: Object.entries(body).map(([k,v])=>encodeURIComponent(k)+'='+encodeURIComponent(v)).join('&')
    }).then(r => r.json());
}

function estadoHtml(enviado) {
    if (enviado == 1) return '<span class="estado-pill ep-approved">✓ Aprobado</span>';
    if (enviado == 2) return '<span class="estado-pill ep-rejected">✗ Rechazado</span>';
    return '<span class="estado-pill ep-pending">⏳ Pendiente</span>';
}

function tipoHtml(row) {
    if (row.tipo == 1) {
        return `<span class="badge badge-light-warning">Otra persona</span><br>
                <small class="text-muted">${row.persona_nombre || ''}${row.persona_parentesco ? ' ('+row.persona_parentesco+')' : ''}</small>`;
    }
    if (row.tipo == 2) return '<span class="badge badge-light-info">Sin transporte</span>';
    return '<span class="badge badge-light-secondary">Otro</span>';
}

function accionesHtml(row) {
    let html = '';
    if (row.enviado != 1) {
        html += `<button class="btn-aprobar mr-1" onclick="accion(${row.cambio_id},1)">✓ Aprobar</button>`;
    }
    if (row.enviado != 2) {
        html += `<button class="btn-rechazar mr-1" onclick="accion(${row.cambio_id},2)">✗ Rechazar</button>`;
    }
    html += `<button class="mr-1" style="background:#e8f4ff;color:#3699ff;border:1px solid #3699ff;border-radius:6px;padding:4px 10px;font-size:.85rem;"
        onclick="window.open(BASE + 'secretary/cambio_recojo_prim/' + ${row.cambio_id}, '_blank')"
        title="Imprimir autorización">🖨 Imprimir</button>`;
    return html;
}

function renderTable(data) {
    const tbody = document.getElementById('rec_tbody');
    if (!data.length) {
        tbody.innerHTML = '<tr class="loading-row"><td colspan="7">Sin registros para los filtros seleccionados</td></tr>';
        document.getElementById('cnt_total').textContent = 0;
        document.getElementById('cnt_pending').textContent = 0;
        document.getElementById('cnt_approved').textContent = 0;
        document.getElementById('cnt_rejected').textContent = 0;
        document.getElementById('tbadge_pending').textContent = '';
        return;
    }

    let cntPend=0, cntApp=0, cntRej=0;
    const rows = data.map(row => {
        if (row.enviado==0||row.enviado==null) cntPend++;
        if (row.enviado==1) cntApp++;
        if (row.enviado==2) cntRej++;

        return `<tr id="rec_row_${row.cambio_id}">
            <td class="font-size-sm">
                ${row.fecha_solicitud ? (()=>{
                    const [d,t] = row.fecha_solicitud.split(' ');
                    const [y,m,dd] = d.split('-');
                    return `<span class="font-weight-bold">${dd}-${m}-${y}</span><br>
                            <span class="text-muted">${t ? t.substring(0,5) : ''}</span>`;
                })() : '—'}
            </td>
            <td>
                <span class="font-weight-bold">${row.student}</span>
                <span class="text-muted font-size-sm ml-1">${row.nick_name}</span>
            </td>
            <td>${tipoHtml(row)}</td>
            <td class="font-size-sm">${row.detalle || '—'}</td>
            <td class="font-size-sm">${row.solicitante}<br><small class="text-muted">${row.parentesco_solicitante || ''}</small></td>
            <td class="text-center" id="estado_${row.cambio_id}">${estadoHtml(row.enviado)}</td>
            <td class="text-center" id="accs_${row.cambio_id}">${accionesHtml(row)}</td>
        </tr>`;
    }).join('');

    tbody.innerHTML = rows;

    document.getElementById('cnt_total').textContent    = data.length;
    document.getElementById('cnt_pending').textContent  = cntPend;
    document.getElementById('cnt_approved').textContent = cntApp;
    document.getElementById('cnt_rejected').textContent = cntRej;
    document.getElementById('tbadge_pending').textContent = cntPend || '';
}

function cargar() {
    const tbody = document.getElementById('rec_tbody');
    tbody.innerHTML = '<tr class="loading-row"><td colspan="7"><i class="fas fa-spinner fa-spin mr-2"></i>Cargando...</td></tr>';

    post(BASE + 'secretary/prim_cambio_recojo_data', {
        fecha_ini: document.getElementById('fi_ini').value,
        fecha_fin: document.getElementById('fi_fin').value,
        search:    document.getElementById('fi_search').value,
        estado:    _estado,
    }).then(data => {
        _allData = data;
        renderTable(data);
    }).catch(() => {
        tbody.innerHTML = '<tr class="loading-row"><td colspan="7">Error al cargar datos</td></tr>';
    });
}

let _accionPendiente = null;

function accion(id, tipo) {
    _accionPendiente = { id, tipo };

    const esAprobar = tipo === 1;
    const header  = document.getElementById('modalAccionHeader');
    const title   = document.getElementById('modalAccionTitle');
    const desc    = document.getElementById('modalAccionDesc');
    const obs     = document.getElementById('modalAccionObs');
    const confirm = document.getElementById('modalAccionConfirm');

    const row = _allData.find(r => r.cambio_id == id);
    desc.textContent = row ? row.student : '';

    if (esAprobar) {
        header.style.background = '#e8fff3';
        title.textContent   = '✓ Aprobar aviso';
        confirm.className   = 'btn btn-success btn-sm font-weight-bold';
        confirm.textContent = 'Aprobar';
    } else {
        header.style.background = '#fff0f2';
        title.textContent   = '✗ Rechazar aviso';
        confirm.className   = 'btn btn-danger btn-sm font-weight-bold';
        confirm.textContent = 'Rechazar';
    }

    obs.value = '';
    $('#modalAccion').modal('show');
}

document.getElementById('modalAccionConfirm').addEventListener('click', function() {
    if (!_accionPendiente) return;
    const { id, tipo } = _accionPendiente;
    const obs = document.getElementById('modalAccionObs').value.trim();

    this.disabled = true;

    const url = tipo === 1
        ? BASE + 'secretary/prim_cambio_recojo_auth'
        : BASE + 'secretary/prim_cambio_recojo_noauth';

    post(url, { cambio_id: id, obs_secretaria: obs })
    .then(res => {
        $('#modalAccion').modal('hide');
        if (res.ok) {
            const idx = _allData.findIndex(r => r.cambio_id == id);
            if (idx !== -1) {
                _allData[idx].enviado = tipo;
                document.getElementById(`estado_${id}`).innerHTML = estadoHtml(tipo);
                document.getElementById(`accs_${id}`).innerHTML   = accionesHtml(_allData[idx]);
            }
            const cntPend = _allData.filter(r => !r.enviado || r.enviado == 0).length;
            const cntApp  = _allData.filter(r => r.enviado == 1).length;
            const cntRej  = _allData.filter(r => r.enviado == 2).length;
            document.getElementById('cnt_pending').textContent    = cntPend;
            document.getElementById('cnt_approved').textContent   = cntApp;
            document.getElementById('cnt_rejected').textContent   = cntRej;
            document.getElementById('tbadge_pending').textContent = cntPend || '';

            if (res.msg) alert('⚠️ ' + res.msg);
        } else {
            alert(res.msg || 'Error al procesar');
        }
    })
    .finally(() => {
        this.disabled = false;
        _accionPendiente = null;
    });
});

document.querySelectorAll('.tab-filter').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-filter').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        _estado = this.dataset.estado;
        cargar();
    });
});

document.getElementById('btn_buscar').addEventListener('click', cargar);
document.getElementById('fi_search').addEventListener('keydown', e => { if(e.key==='Enter') cargar(); });

cargar();

// ── Modal "Registrar por Llamada" ──────────────────────────────────────────

$(document).ready(function() { $('#rec_student_sel').select2({ dropdownParent: $('#modalNuevo') }); });

var _recFamilyId  = null;
var _recTipoActual = 0;

function recResetForm() {
    document.getElementById('form_rec').reset();
    $('#rec_student_sel').val('').trigger('change');
    document.querySelectorAll('#modalNuevo .tipo-opt').forEach(el => el.classList.remove('checked'));
    document.getElementById('rec_bloque_persona').style.display = 'none';
    document.getElementById('rec_persona_parentesco_otro').style.display = 'none';
    document.getElementById('rec_detalle_req').style.display = 'none';
    document.querySelectorAll('#modalNuevo input[name="tipo"]').forEach(el => el.disabled = true);
    ['rec_parentesco','rec_solicitante','rec_detalle'].forEach(id => document.getElementById(id).disabled = true);
    document.getElementById('rec_btn_registrar').disabled = true;
    _recFamilyId = null;
    _recTipoActual = 0;
}

function recSeleccionarAlumno(student_id) {
    if (!student_id) return;

    document.querySelectorAll('#modalNuevo input[name="tipo"]').forEach(el => el.disabled = false);
    ['rec_parentesco', 'rec_solicitante'].forEach(id => document.getElementById(id).disabled = false);
    document.getElementById('rec_btn_registrar').disabled = false;

    $.post('<?= base_url('server/student_fill') ?>', { student_id: student_id }, function(r) {
        var c = JSON.parse(r);
        if (c.length) _recFamilyId = c[0].family_id;
    });
}

function recTipoChange(tipo, evt) {
    _recTipoActual = tipo;
    document.querySelectorAll('#modalNuevo .tipo-opt').forEach(el => el.classList.remove('checked'));
    if (evt && evt.currentTarget) evt.currentTarget.classList.add('checked');

    document.getElementById('rec_bloque_persona').style.display = (tipo === 1) ? '' : 'none';
    document.getElementById('rec_persona_nombre').required       = (tipo === 1);
    document.getElementById('rec_persona_parentesco_id').required = (tipo === 1);
    toggleRecPersonaOtro();

    var detalle = document.getElementById('rec_detalle');
    detalle.disabled = false;
    document.getElementById('rec_detalle_req').style.display = (tipo === 3) ? '' : 'none';
    detalle.required = (tipo === 3);
}

function toggleRecPersonaOtro() {
    var sel  = document.getElementById('rec_persona_parentesco_id');
    var otro = document.getElementById('rec_persona_parentesco_otro');
    var esOtro = sel.value === '0';
    otro.style.display = esOtro ? '' : 'none';
    otro.required = esOtro && sel.required;
    if (!esOtro) otro.value = '';
}

function recFillParent(relationship) {
    if (!_recFamilyId) return;
    if (relationship <= 2) {
        $.get('<?= base_url('server/fill_parent_relationship') ?>/' + _recFamilyId + '/' + relationship, function(r) {
            var c = JSON.parse(r);
            if (c.length) {
                document.getElementById('rec_solicitante').value =
                    c[0].name + ' ' + c[0].lastname1 + ' ' + c[0].lastname2;
            }
        });
    } else {
        document.getElementById('rec_solicitante').value = '';
        document.getElementById('rec_solicitante').focus();
    }
}

document.getElementById('form_rec').addEventListener('submit', function(e) {
    e.preventDefault();

    var student_id = document.getElementById('rec_student_sel').value;
    if (!student_id) { alert('Seleccione un estudiante'); return; }
    if (!_recTipoActual) { alert('Seleccione qué cambia hoy'); return; }
    if (!document.getElementById('rec_solicitante').value.trim()) {
        alert('Ingrese el nombre de quien llama'); return;
    }

    var btn = document.getElementById('rec_btn_registrar');
    btn.disabled = true;
    btn.textContent = 'Guardando...';

    post(BASE + 'secretary/prim_cambio_recojo_create', {
        student_id:            student_id,
        tipo:                  _recTipoActual,
        parentesco:             document.getElementById('rec_parentesco').value,
        solicitante:           document.getElementById('rec_solicitante').value,
        persona_nombre:         document.getElementById('rec_persona_nombre').value,
        persona_parentesco_id:  document.getElementById('rec_persona_parentesco_id').value,
        persona_parentesco_otro:document.getElementById('rec_persona_parentesco_otro').value,
        detalle:                document.getElementById('rec_detalle').value,
    }).then(res => {
        if (res.ok) {
            $('#modalNuevo').modal('hide');
            recResetForm();
            cargar();
        } else {
            alert(res.msg || 'Error al registrar');
        }
    })
    .catch(() => alert('Error al registrar'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save mr-1"></i> Registrar Cambio de Recojo';
    });
});

$('#modalNuevo').on('hidden.bs.modal', recResetForm);
</script>
