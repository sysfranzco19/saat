<style>
.lic-header { background: linear-gradient(135deg, #6f42c1 0%, #4b2e9e 100%); border-radius: 12px; }
.stat-pill  { border-radius: 10px; padding: 14px 20px; text-align: center; min-width: 110px; }
.stat-pill .num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
.stat-pill .lbl { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; opacity: .75; }
.filter-bar { background: #f8f9fc; border-radius: 10px; padding: 14px 18px; }
.tab-filter { border: none; border-radius: 20px; padding: 6px 18px; font-weight: 700;
              font-size: .85rem; cursor: pointer; transition: all .2s; margin-right: 6px; }
.tab-filter.active       { background: #6f42c1; color: #fff; }
.tab-filter.tf-pending   { background: #fff8dd; color: #ffa800; }
.tab-filter.tf-pending.active { background: #ffa800; color: #fff; }
.tab-filter.tf-approved  { background: #e8fff3; color: #50cd89; }
.tab-filter.tf-approved.active{ background: #50cd89; color: #fff; }
.tab-filter.tf-rejected  { background: #fff0f2; color: #f1416c; }
.tab-filter.tf-rejected.active{ background: #f1416c; color: #fff; }
.tab-filter.tf-all       { background: #eef0ff; color: #6f42c1; }
.badge-excep  { background: #e8fff3; color: #1bc5bd; font-weight: 700; padding: 3px 10px;
                border-radius: 12px; font-size: .75rem; }
.badge-cupo   { padding: 3px 10px; border-radius: 12px; font-size: .78rem; font-weight: 700; }
.bc-consume   { background: #fff0f2; color: #f1416c; }
.bc-excep     { background: #e8fff3; color: #1bc5bd; }
.bc-half      { background: #fff3e0; color: #e65100; }
.estado-pill  { padding: 4px 12px; border-radius: 14px; font-size: .8rem; font-weight: 700; }
.ep-pending   { background: #fff8dd; color: #ffa800; }
.ep-approved  { background: #e8fff3; color: #50cd89; }
.ep-rejected  { background: #fff0f2; color: #f1416c; }
.ep-deleted   { background: #f0f0f0; color: #7e8299; }
.btn-aprobar  { background: #e8fff3; color: #50cd89; border: 1px solid #50cd89;
                border-radius: 8px; padding: 5px 12px; font-weight: 700; font-size: .82rem;
                cursor: pointer; transition: all .15s; }
.btn-aprobar:hover  { background: #50cd89; color: #fff; }
.btn-rechazar { background: #fff0f2; color: #f1416c; border: 1px solid #f1416c;
                border-radius: 8px; padding: 5px 12px; font-weight: 700; font-size: .82rem;
                cursor: pointer; transition: all .15s; }
.btn-rechazar:hover { background: #f1416c; color: #fff; }
.btn-eliminar { background: #f0f0f0; color: #7e8299; border: 1px solid #c4c9d6;
                border-radius: 8px; padding: 5px 12px; font-weight: 700; font-size: .82rem;
                cursor: pointer; transition: all .15s; }
.btn-eliminar:hover { background: #7e8299; color: #fff; }
.tab-filter.tf-deleted   { background: #f0f0f0; color: #7e8299; }
.tab-filter.tf-deleted.active { background: #7e8299; color: #fff; }
.btn-doc      { background: #eef0ff; color: #6f42c1; border: 1px solid #6f42c1;
                border-radius: 8px; padding: 5px 10px; font-size: .82rem; cursor: pointer; }
.btn-doc:hover { background: #6f42c1; color: #fff; }
.lic-row.is-excep { background: #f0fff8; }
.lic-row:hover    { background: #f8f5ff; }
#lic_table td     { vertical-align: middle; }
.loading-row td   { text-align: center; color: #aaa; padding: 40px; }
.nl-tab { border: none; border-radius: 20px; padding: 6px 18px; font-weight: 700;
          font-size: .85rem; cursor: pointer; transition: all .2s; margin-right: 6px;
          background: #eef0ff; color: #6f42c1; }
.nl-tab.active { background: #6f42c1; color: #fff; }
.nl-pane { display: none; }
.nl-pane.active { display: block; }
.cupo-bar-wrap { background:#f3f0ff; border-radius:10px; padding:14px 18px; }
.cupo-bar-bg   { background:#e0e0e0; border-radius:6px; height:14px; overflow:hidden; margin:8px 0; }
.cupo-bar-fill { height:14px; border-radius:6px; transition:width .4s; }
.cupo-ok    { background:#50cd89; }
.cupo-warn  { background:#ffa800; }
.cupo-full  { background:#f1416c; }
.cupo-label { font-size:.8rem; }
.periodo-check { border:1px solid #e0e0e0; border-radius:8px; padding:8px 14px; margin-bottom:6px;
                 cursor:pointer; transition:background .15s; }
.periodo-check:hover { background:#f8f5ff; }
.periodo-check input[type=checkbox]:checked + span { color:#6f42c1; font-weight:700; }
</style>

<div class="container-fluid pb-8">

    <!-- Header -->
    <div class="lic-header shadow mb-6 p-5 d-flex align-items-center">
        <div>
            <h3 class="text-white font-weight-bolder mb-1">📄 Gestión de Licencias — Primaria</h3>
            <span class="text-white opacity-75 font-size-sm">
                3ro a 6to de Primaria &nbsp;·&nbsp;
                <strong><?= esc($phase_name) ?></strong>
                &nbsp;(<?= date('d-m-Y', strtotime($phase_ini)) ?> → <?= date('d-m-Y', strtotime($phase_fin)) ?>)
            </span>
        </div>
        <div class="ml-auto">
            <button type="button" class="btn btn-light font-weight-bold" data-toggle="modal" data-target="#modalNuevaLicencia">
                <i class="fas fa-plus mr-1"></i> Nueva Licencia
            </button>
        </div>
    </div>

    <!-- Tarjetas de estado -->
    <div class="d-flex flex-wrap mb-5 gap-3" id="stats_lic">
        <div class="stat-pill shadow-sm" style="background:#eef0ff;">
            <div class="num text-primary" id="cnt_total">—</div>
            <div class="lbl text-primary">Total</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#fff8dd;">
            <div class="num text-warning" id="cnt_pending">—</div>
            <div class="lbl text-warning">Pendientes</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#e8fff3;">
            <div class="num text-success" id="cnt_approved">—</div>
            <div class="lbl text-success">Aprobadas</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#fff0f2;">
            <div class="num text-danger" id="cnt_rejected">—</div>
            <div class="lbl text-danger">Rechazadas</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#e8fff3; margin-left:auto">
            <div class="num text-success" id="cnt_excep">—</div>
            <div class="lbl text-success">Excepciones</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card card-custom shadow-sm">
        <div class="card-body py-4">

            <!-- Barra de filtros -->
            <div class="filter-bar mb-4">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div>
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Desde</label>
                        <input type="date" id="fi_ini" class="form-control form-control-sm" style="width:145px;"
                               value="<?= $phase_ini ?>"
                               min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                    </div>
                    <div>
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Hasta</label>
                        <input type="date" id="fi_fin" class="form-control form-control-sm" style="width:145px;"
                               value="<?= min($phase_fin, date('Y-m-d')) ?>"
                               min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                    </div>
                    <div class="flex-grow-1">
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Buscar</label>
                        <input type="text" id="fi_search" class="form-control form-control-sm"
                               placeholder="Nombre, curso, motivo..." style="min-width:200px;">
                    </div>
                    <div class="align-self-end">
                        <button id="btn_buscar" class="btn btn-primary btn-sm font-weight-bold px-5">
                            <i class="fas fa-search mr-1"></i>Buscar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabs de estado -->
            <div class="mb-4">
                <button class="tab-filter tf-pending active" data-estado="pending">
                    ⏳ Pendientes <span id="tbadge_pending" class="badge badge-warning ml-1"></span>
                </button>
                <button class="tab-filter tf-all" data-estado="all">Todas</button>
                <button class="tab-filter tf-approved" data-estado="approved">✓ Aprobadas</button>
                <button class="tab-filter tf-rejected" data-estado="rejected">✗ Rechazadas</button>
                <button class="tab-filter tf-deleted" data-estado="deleted">🗑 Eliminadas</button>
            </div>

            <!-- Tabla -->
            <div class="table-responsive">
                <table class="table table-hover" id="lic_table">
                    <thead class="bg-light">
                        <tr class="text-muted text-uppercase font-size-xs">
                            <th>Fecha y hora</th>
                            <th>Alumno / Curso</th>
                            <th>Tipo</th>
                            <th>Motivo</th>
                            <th>Período</th>
                            <th class="text-center">Cupo</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lic_tbody">
                        <tr class="loading-row"><td colspan="8">Selecciona un filtro y haz clic en Buscar</td></tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<!-- Modal confirmar autorización / rechazo -->
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
                    rows="3" id="modal_obs_sec"
                    placeholder="Escribe una nota para la familia (opcional)..."
                    style="resize:none;"></textarea>
                <small class="text-muted">La nota se incluirá en el correo enviado a la familia.</small>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-sm font-weight-bold" id="modalAccionConfirm">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de aviso (reemplaza a los alert() nativos) -->
<div class="modal fade" id="modalInfo" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3" id="modalInfoHeader">
                <h6 class="modal-title font-weight-bolder mb-0" id="modalInfoTitle">Aviso</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body py-3">
                <p class="font-size-sm mb-0" id="modalInfoBody"></p>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Continuar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal documento -->
<div class="modal fade" id="modalDoc" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Comprobante / Documento</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center" id="modalDocBody" style="min-height:300px;"></div>
        </div>
    </div>
</div>

<!-- Modal Nueva Licencia (Días / Período) -->
<div class="modal fade" id="modalNuevaLicencia" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">📄 Nueva Licencia — Primaria</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">

                <div class="mb-4">
                    <button type="button" class="nl-tab active" data-nl-tipo="dia" onclick="nlCambiarTipo('dia', this)">Por Día(s)</button>
                    <button type="button" class="nl-tab" data-nl-tipo="periodo" onclick="nlCambiarTipo('periodo', this)">Por Período</button>
                </div>

                <div class="row">
                    <!-- Panel izquierdo: alumno + cupo -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Estudiante <span class="text-danger">*</span></label>
                            <select class="form-control select2" style="width:100%;" id="nl_student_sel" onchange="nlSeleccionarAlumno(this.value)">
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
                            <input type="text" id="nl_disp_curso" class="form-control form-control-sm" disabled>
                        </div>

                        <div id="nl_cupo_panel" style="display:none;" class="mt-4">
                            <div class="cupo-bar-wrap">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="font-weight-bold font-size-sm">Cupo Trimestral</span>
                                    <span class="font-size-sm" id="nl_cupo_texto">— / 9 días</span>
                                </div>
                                <div class="cupo-bar-bg">
                                    <div class="cupo-bar-fill cupo-ok" id="nl_cupo_barra" style="width:0%"></div>
                                </div>
                                <div class="d-flex justify-content-between cupo-label text-muted mt-1">
                                    <span>Consumido: <strong id="nl_cupo_consumido">0</strong></span>
                                    <span>Restante: <strong id="nl_cupo_restante">9</strong> día(s)</span>
                                </div>
                                <div id="nl_cupo_alerta" class="mt-2" style="display:none;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel derecho: formulario -->
                    <div class="col-md-8">
                        <form id="nl_form">
                            <input type="hidden" id="nl_student_id" name="student_id">

                            <div class="form-group row">
                                <label class="col-3 col-form-label font-weight-bold">Medio <span class="text-danger">*</span></label>
                                <div class="col-4">
                                    <select class="form-control" id="nl_medio" name="medio" disabled required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($medios as $m): ?>
                                            <option value="<?= $m->medio_id ?>"><?= esc($m->medio) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <label class="col-2 col-form-label font-weight-bold">Fecha Solicitud</label>
                                <div class="col-3">
                                    <input type="datetime-local" class="form-control" id="nl_fechaSolicita" name="fechaSolicita" disabled required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label font-weight-bold">Parentesco <span class="text-danger">*</span></label>
                                <div class="col-4">
                                    <select class="form-control" id="nl_parentesco" name="parentesco" onchange="nlFillParent(this.value)" disabled required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($parentescos as $p): ?>
                                            <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <label class="col-2 col-form-label font-weight-bold">Solicitante</label>
                                <div class="col-3">
                                    <input type="text" id="nl_solicitante" name="solicitante" class="form-control" disabled required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label font-weight-bold">Motivo <span class="text-danger">*</span></label>
                                <div class="col-9">
                                    <select class="form-control" id="nl_motivo" name="motivo" disabled required>
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($motivos as $mo): ?>
                                            <option value="<?= $mo->motivo_id ?>"><?= esc($mo->motivo) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label font-weight-bold">Detalle</label>
                                <div class="col-9">
                                    <input type="text" id="nl_detalle" name="detalle" class="form-control" disabled>
                                </div>
                            </div>

                            <!-- ── Pestaña: Por Día(s) ── -->
                            <div class="nl-pane active" id="nl_pane_dia">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">Fecha Inicio <span class="text-danger">*</span></label>
                                    <div class="col-3">
                                        <input type="date" class="form-control" id="nl_fecha_inicio" name="fecha_inicio" onchange="nlCalcularDias()" disabled>
                                    </div>
                                    <label class="col-2 col-form-label font-weight-bold">Fecha Fin</label>
                                    <div class="col-3">
                                        <input type="date" class="form-control" id="nl_fecha_fin" name="fecha_fin" onblur="nlCalcularDias()" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">Días</label>
                                    <div class="col-9 d-flex align-items-center flex-wrap gap-3">
                                        <label class="mb-0 mr-4">
                                            <input type="checkbox" id="nl_sabados" checked onchange="nlCalcularDias()" disabled>
                                            Contar Sábados
                                        </label>
                                        <input type="number" class="form-control" id="nl_cantidad" name="cantidad" value="1" min="1" style="width:100px;" disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- ── Pestaña: Por Período ── -->
                            <div class="nl-pane" id="nl_pane_periodo">
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">Fecha <span class="text-danger">*</span></label>
                                    <div class="col-4">
                                        <input type="date" class="form-control" id="nl_fecha" name="fecha" disabled>
                                    </div>
                                    <label class="col-2 col-form-label font-weight-bold">Hora Salida</label>
                                    <div class="col-3">
                                        <input type="time" class="form-control" id="nl_hora_salida" name="hora_salida" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">Períodos <span class="text-danger">*</span></label>
                                    <div class="col-9">
                                        <div id="nl_periodos_container" style="border:1px solid #ebedf3; border-radius:8px; padding:12px; max-height:180px; overflow-y:auto;">
                                            <em class="text-muted">Seleccione un estudiante para ver los períodos.</em>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label font-weight-bold">¿Quién recoge?</label>
                                    <div class="col-4">
                                        <input type="text" class="form-control" id="nl_recoge_nombre" name="recoge_nombre" placeholder="Nombre completo" disabled>
                                    </div>
                                    <div class="col-5">
                                        <select class="form-control" id="nl_recoge_parentesco" name="recoge_parentesco_id" disabled>
                                            <option value="">Parentesco...</option>
                                            <?php foreach ($parentescos as $p): ?>
                                                <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-3"></div>
                                    <div class="col-9">
                                        <label class="mb-0">
                                            <input type="checkbox" id="nl_se_reincorpora" name="se_reincorpora" value="1" disabled>
                                            <span class="ml-2">El estudiante se reincorporará (volverá a clases)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3 col-form-label font-weight-bold">Excepción</label>
                                <div class="col-9 d-flex align-items-center">
                                    <label class="mb-0">
                                        <input type="checkbox" id="nl_chk_excepcion" name="es_excepcion" value="1" disabled>
                                        <span class="ml-2">Marcar como excepción — no consume cupo</span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" id="nl_btn_registrar" disabled onclick="nlSubmit()">
                    <i class="fas fa-save mr-1"></i> Registrar Licencia
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = '<?= csrf_hash() ?>';
const BASE       = '<?= base_url() ?>';
const MI_ROL     = '<?= session()->get('login_type') === 'manager' ? 'Dirección Técnica' : 'Secretaría' ?>';
const MI_NOMBRE  = '<?= esc(session()->get('name')) ?>';
let _estado      = 'pending';
let _allData     = [];

function post(url, body) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type':'application/x-www-form-urlencoded',
                   'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN },
        body: Object.entries(body).flatMap(([k, v]) =>
            Array.isArray(v)
                ? v.map(vv => encodeURIComponent(k) + '=' + encodeURIComponent(vv))
                : [encodeURIComponent(k) + '=' + encodeURIComponent(v)]
        ).join('&')
    }).then(r => r.json());
}

function escHtml(str) {
    return String(str).replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function mostrarInfoModal(mensaje, esError = true) {
    document.getElementById('modalInfoHeader').style.background = esError ? '#fff8dd' : '#e8fff3';
    document.getElementById('modalInfoTitle').textContent = esError ? '⚠️ Aviso' : '✓ Aviso';
    document.getElementById('modalInfoBody').textContent = mensaje;
    $('#modalInfo').modal('show');
}
// Si modalInfo se muestra encima de otro modal (ej. Nueva Licencia), al cerrarlo
// Bootstrap puede quitar el backdrop del modal de abajo — lo restauramos.
$('#modalInfo').on('hidden.bs.modal', function () {
    if ($('.modal.show').length) $('body').addClass('modal-open');
});

function cupoHtml(row) {
    if (row.es_excepcion == 1) return '<span class="badge-cupo bc-excep">⭐ Excepción</span>';
    const f = parseFloat(row.fraccion_cupo) || 0;
    if (f === 0) return '<span class="badge-cupo bc-excep">0 días</span>';
    if (f === 0.5) return '<span class="badge-cupo bc-half">½ día</span>';
    return `<span class="badge-cupo bc-consume">${f} día${f!=1?'s':''}</span>`;
}

function estadoHtml(enviado, obs, aprobadoRol, aprobadoNombre) {
    let pill;
    if (enviado == 1) pill = '<span class="estado-pill ep-approved">✓ Aprobada</span>';
    else if (enviado == 2) pill = '<span class="estado-pill ep-rejected">✗ Rechazada</span>';
    else if (enviado == 3) pill = '<span class="estado-pill ep-deleted">🗑 Eliminada</span>';
    else pill = '<span class="estado-pill ep-pending">⏳ Pendiente</span>';

    if (aprobadoRol) {
        const quien = aprobadoNombre ? `${aprobadoRol} — ${aprobadoNombre}` : aprobadoRol;
        pill += `<br><small class="text-muted d-block mt-1" style="max-width:170px;white-space:normal;">👤 ${escHtml(quien)}</small>`;
    }
    if (obs) {
        pill += `<br><small class="text-muted d-block mt-1" style="max-width:170px;white-space:normal;">📝 ${escHtml(obs)}</small>`;
    }
    return pill;
}

function accionesHtml(row) {
    let html = '';
    if (row.enviado != 3) {
        if (row.enviado != 1) {
            html += `<label class="d-block font-size-xs mb-1 text-nowrap" style="cursor:pointer;">
                <input type="checkbox" id="excep_${row.licencias_id}" ${row.es_excepcion == 1 ? 'checked' : ''}> No consume cupo
            </label>`;
            html += `<button class="btn-aprobar mr-1"
                onclick="accion(${row.licencias_id},${row.student_id},1,this)">✓ Aprobar</button>`;
        }
        if (row.enviado != 2) {
            html += `<button class="btn-rechazar mr-1"
                onclick="accion(${row.licencias_id},${row.student_id},2,this)">✗ Rechazar</button>`;
        }
        html += `<button class="btn-eliminar mr-1"
            onclick="accion(${row.licencias_id},${row.student_id},3,this)">🗑 Eliminar</button>`;
    }
    if (row.comprobante_medico) {
        const compEsc = JSON.stringify(row.comprobante_medico).replace(/"/g, '&quot;');
        const studEsc = JSON.stringify(row.student).replace(/"/g, '&quot;');
        html += `<button class="btn-doc mr-1" onclick="verDoc(${compEsc},${studEsc},'comprobantes_medicos')"
            title="Ver comprobante">📎</button>`;
    }
    if (row.carta_solicitud) {
        const cartaEsc = JSON.stringify(row.carta_solicitud).replace(/"/g, '&quot;');
        const studEsc2 = JSON.stringify(row.student).replace(/"/g, '&quot;');
        html += `<button class="btn-doc mr-1" style="background:#eef0ff;color:#6f42c1;border-color:#6f42c1;"
            onclick="verDoc(${cartaEsc},${studEsc2},'cartas_solicitud')" title="Ver carta de solicitud">✉️</button>`;
    }
    if (row.doc_pendiente == 1 && !row.comprobante_medico) {
        html += `<button class="btn-doc" style="background:#fff8dd;color:#ffa800;border-color:#ffa800;"
            onclick="liberarDoc(${row.licencias_id})" title="Eximir de comprobante">
            📋 Sin doc.</button>`;
    }
    html += `<button class="btn-doc mr-1" style="background:#e8f4ff;color:#3699ff;border-color:#3699ff;"
        onclick="window.open(BASE + 'secretary/license_report_prim/' + ${row.licencias_id}, '_blank')"
        title="Imprimir autorización">🖨 Imprimir</button>`;
    return html;
}

function liberarDoc(licId) {
    if (!confirm('¿Confirmas que esta licencia no requiere comprobante? Se quitará la alerta pendiente.')) return;
    post(BASE + 'manager/prim_licencias_waive_doc', { licencias_id: licId })
    .then(res => {
        if (res.ok) {
            const idx = _allData.findIndex(r => r.licencias_id == licId);
            if (idx !== -1) {
                _allData[idx].doc_pendiente = 0;
                document.getElementById(`accs_${licId}`).innerHTML = accionesHtml(_allData[idx]);
            }
        } else {
            mostrarInfoModal(res.msg || 'Error al procesar');
        }
    });
}

function periodoHtml(row) {
    if (row.tipo_id == 1 && row.inicio) {
        const dias = row.cantidad_dias ? `(${row.cantidad_dias}d)` : '';
        return row.inicio + (row.fin && row.fin !== row.inicio ? ` → ${row.fin}` : '') + ` <small class="text-muted">${dias}</small>`;
    }
    if (row.tipo_id == 2 && row.inicio) {
        const hora = row.hora_salida ? ` <small class="text-muted">🕐 ${row.hora_salida.substring(0, 5)}</small>` : '';
        const periodos = row.periodos_nombre
            ? `<br><small class="text-muted">📋 ${escHtml(row.periodos_nombre)}</small>`
            : '';
        const recoge = row.recoge_nombre
            ? `<br><small class="text-muted">🚶 Recoge: ${row.recoge_nombre}${row.recoge_parentesco ? ' (' + row.recoge_parentesco + ')' : ''}</small>`
            : '';
        const reincorpora = row.se_reincorpora == 1
            ? `<br><span class="badge badge-light-success font-size-xs">🔄 Se reincorpora</span>`
            : `<br><span class="badge badge-light-secondary font-size-xs">No regresa hoy</span>`;
        return row.inicio + hora + periodos + recoge + reincorpora;
    }
    return '—';
}

function renderTable(data) {
    const tbody = document.getElementById('lic_tbody');
    if (!data.length) {
        tbody.innerHTML = '<tr class="loading-row"><td colspan="8">Sin registros para los filtros seleccionados</td></tr>';
        return;
    }

    let cntPend=0, cntApp=0, cntRej=0, cntExcep=0;
    const rows = data.map(row => {
        if (row.enviado==0||row.enviado==null) cntPend++;
        if (row.enviado==1) cntApp++;
        if (row.enviado==2) cntRej++;
        if (row.es_excepcion==1) cntExcep++;

        const excepBadge = row.es_excepcion==1
            ? '<span class="badge-excep ml-1">⭐ Excepción</span>' : '';
        const rowCls = row.es_excepcion==1 ? 'lic-row is-excep' : 'lic-row';
        const tipoBadge = row.tipo_id==1
            ? '<span class="badge badge-light-primary">Día(s)</span>'
            : '<span class="badge badge-light-warning">Periodos</span>';

        return `<tr class="${rowCls}" id="lic_row_${row.licencias_id}">
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
            <td>${tipoBadge}</td>
            <td class="font-size-sm">
                ${row.motivo}${excepBadge}
                ${row.detalle ? `<br><small class="text-muted">${escHtml(row.detalle)}</small>` : ''}
            </td>
            <td class="font-size-sm">${periodoHtml(row)}</td>
            <td class="text-center">${cupoHtml(row)}</td>
            <td class="text-center" id="estado_${row.licencias_id}">${estadoHtml(row.enviado, row.obs_secretaria, row.aprobado_por_rol, row.aprobado_por_nombre)}</td>
            <td class="text-center" id="accs_${row.licencias_id}">${accionesHtml(row)}</td>
        </tr>`;
    }).join('');

    tbody.innerHTML = rows;

    // Stats
    document.getElementById('cnt_total').textContent    = data.length;
    document.getElementById('cnt_pending').textContent  = cntPend;
    document.getElementById('cnt_approved').textContent = cntApp;
    document.getElementById('cnt_rejected').textContent = cntRej;
    document.getElementById('cnt_excep').textContent    = cntExcep;
    document.getElementById('tbadge_pending').textContent = cntPend || '';
}

function cargar() {
    const tbody = document.getElementById('lic_tbody');
    tbody.innerHTML = '<tr class="loading-row"><td colspan="8"><i class="fas fa-spinner fa-spin mr-2"></i>Cargando...</td></tr>';

    post(BASE + 'manager/prim_licencias_data', {
        fecha_ini: document.getElementById('fi_ini').value,
        fecha_fin: document.getElementById('fi_fin').value,
        search:    document.getElementById('fi_search').value,
        estado:    _estado,
    }).then(data => {
        _allData = data;
        renderTable(data);
    }).catch(() => {
        tbody.innerHTML = '<tr class="loading-row"><td colspan="8">Error al cargar datos</td></tr>';
    });
}

let _accionPendiente = null; // { licId, studentId, tipo }

function accion(licId, studentId, tipo, btn) {
    _accionPendiente = { licId, studentId, tipo };

    const header    = document.getElementById('modalAccionHeader');
    const title     = document.getElementById('modalAccionTitle');
    const desc      = document.getElementById('modalAccionDesc');
    const obs       = document.getElementById('modalAccionObs');
    const confirm   = document.getElementById('modalAccionConfirm');

    // Buscar nombre del alumno
    const row = _allData.find(r => r.licencias_id == licId);
    const nombre = row ? row.student : '';
    const sinDoc = row && !row.comprobante_medico;

    if (tipo === 1) {
        header.style.background = '#e8fff3';
        title.textContent       = '✓ Aprobar licencia';
        desc.innerHTML          = escHtml(nombre) + (sinDoc
            ? '<br><span class="badge badge-light-warning font-size-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>Sin documento adjunto</span>'
            : '');
        obs.placeholder         = 'Nota adicional para la familia (opcional)...';
        confirm.className       = 'btn btn-success btn-sm font-weight-bold';
        confirm.textContent     = 'Aprobar y enviar correo';
    } else if (tipo === 2) {
        header.style.background = '#fff0f2';
        title.textContent       = '✗ Rechazar licencia';
        desc.textContent        = nombre;
        obs.placeholder         = 'Motivo del rechazo (se enviará a la familia)...';
        confirm.className       = 'btn btn-danger btn-sm font-weight-bold';
        confirm.textContent     = 'Rechazar y enviar correo';
    } else {
        header.style.background = '#f0f0f0';
        title.textContent       = '🗑 Eliminar licencia';
        desc.textContent        = nombre;
        obs.placeholder         = 'Motivo de la eliminación (uso interno, opcional)...';
        confirm.className       = 'btn btn-secondary btn-sm font-weight-bold';
        confirm.textContent     = 'Eliminar';
    }

    obs.value = '';
    $('#modalAccion').modal('show');
}

document.getElementById('modalAccionConfirm').addEventListener('click', function() {
    if (!_accionPendiente) return;
    const { licId, studentId, tipo } = _accionPendiente;
    const obs = document.getElementById('modalAccionObs').value.trim();
    const excepChk = document.getElementById(`excep_${licId}`);
    const esExcepcion = tipo === 1 && excepChk && excepChk.checked ? 1 : 0;

    this.disabled    = true;
    this.textContent = 'Enviando...';

    const url = tipo === 1 ? BASE + 'manager/prim_licencias_auth'
              : tipo === 2 ? BASE + 'manager/prim_licencias_noauth'
              : BASE + 'manager/prim_licencias_delete';

    const payload = { licencias_id: licId, student_id: studentId, obs_secretaria: obs };
    if (tipo === 1) payload.es_excepcion = esExcepcion;

    post(url, payload)
    .then(res => {
        $('#modalAccion').modal('hide');
        if (res.ok) {
            const idx = _allData.findIndex(r => r.licencias_id == licId);
            if (idx !== -1) {
                _allData[idx].enviado = tipo;
                if (obs) _allData[idx].obs_secretaria = obs;
                if (tipo === 1) _allData[idx].es_excepcion = esExcepcion;
                _allData[idx].aprobado_por_rol    = MI_ROL;
                _allData[idx].aprobado_por_nombre = MI_NOMBRE;
                document.getElementById(`estado_${licId}`).innerHTML = estadoHtml(
                    tipo, _allData[idx].obs_secretaria, MI_ROL, MI_NOMBRE
                );
                document.getElementById(`accs_${licId}`).innerHTML   = accionesHtml(_allData[idx]);
                const cupoCell = document.querySelector(`#lic_row_${licId} td:nth-child(6)`);
                if (cupoCell) cupoCell.innerHTML = cupoHtml(_allData[idx]);
            }
            const cntPend = _allData.filter(r => !r.enviado || r.enviado == 0).length;
            const cntApp  = _allData.filter(r => r.enviado == 1).length;
            const cntRej  = _allData.filter(r => r.enviado == 2).length;
            document.getElementById('cnt_pending').textContent    = cntPend;
            document.getElementById('cnt_approved').textContent   = cntApp;
            document.getElementById('cnt_rejected').textContent   = cntRej;
            document.getElementById('tbadge_pending').textContent = cntPend || '';

            if (res.msg) mostrarInfoModal(res.msg);
        } else {
            mostrarInfoModal(res.msg || 'Error al procesar');
        }
    })
    .finally(() => {
        this.disabled = false;
        this.textContent = tipo === 1 ? 'Aprobar y enviar correo' : tipo === 2 ? 'Rechazar y enviar correo' : 'Eliminar';
        _accionPendiente = null;
    });
});

function verDoc(filename, student, folder) {
    const ext = filename.split('.').pop().toLowerCase();
    const url = BASE + 'uploads/' + (folder || 'comprobantes_medicos') + '/' + filename;
    document.getElementById('modalDocBody').innerHTML = ext === 'pdf'
        ? `<iframe src="${url}" style="width:100%;height:500px;border:none;"></iframe>`
        : `<img src="${url}" class="img-fluid" alt="Comprobante de ${student}">`;
    $('#modalDoc').modal('show');
}

// Tabs de estado
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

// Auto-carga al entrar con pendientes
cargar();

// ── Modal "Nueva Licencia" (Día(s) / Período) ───────────────────────────────

$(document).ready(function() { $('#nl_student_sel').select2({ dropdownParent: $('#modalNuevaLicencia') }); });

var _nlTipo      = 'dia';
var _nlFamilyId  = null;
var _nlSectionId = null;

function nlCambiarTipo(tipo, btn) {
    _nlTipo = tipo;
    document.querySelectorAll('.nl-tab').forEach(el => el.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.nl-pane').forEach(el => el.classList.remove('active'));
    document.getElementById('nl_pane_' + tipo).classList.add('active');

    const diaFields = ['nl_fecha_inicio', 'nl_fecha_fin'];
    const perFields = ['nl_fecha', 'nl_recoge_nombre', 'nl_recoge_parentesco', 'nl_se_reincorpora'];
    const habilitado = !!document.getElementById('nl_student_id').value;
    diaFields.forEach(id => document.getElementById(id).disabled = !(habilitado && tipo === 'dia'));
    perFields.forEach(id => document.getElementById(id).disabled = !(habilitado && tipo === 'periodo'));
}

function nlResetForm() {
    document.getElementById('nl_form').reset();
    $('#nl_student_sel').val('').trigger('change');
    document.getElementById('nl_disp_curso').value = '';
    document.getElementById('nl_cupo_panel').style.display = 'none';
    document.getElementById('nl_periodos_container').innerHTML = '<em class="text-muted">Seleccione un estudiante para ver los períodos.</em>';
    ['nl_medio','nl_fechaSolicita','nl_parentesco','nl_solicitante','nl_motivo','nl_detalle',
     'nl_fecha_inicio','nl_fecha_fin','nl_sabados','nl_cantidad','nl_chk_excepcion',
     'nl_fecha','nl_hora_salida','nl_recoge_nombre','nl_recoge_parentesco','nl_se_reincorpora'].forEach(id => {
        document.getElementById(id).disabled = true;
    });
    document.getElementById('nl_btn_registrar').disabled = true;
    _nlFamilyId = null;
    _nlSectionId = null;
    nlCambiarTipo('dia', document.querySelector('.nl-tab[data-nl-tipo="dia"]'));
}

function nlSeleccionarAlumno(student_id) {
    if (!student_id) return;

    var opt = document.querySelector('#nl_student_sel option[value="' + student_id + '"]');
    _nlSectionId = opt ? opt.dataset.sectionId : null;
    document.getElementById('nl_disp_curso').value = opt ? opt.dataset.section : '';
    document.getElementById('nl_student_id').value = student_id;

    var hoy  = new Date();
    var pad  = n => ('0'+n).slice(-2);
    var fecha = hoy.getFullYear()+'-'+pad(hoy.getMonth()+1)+'-'+pad(hoy.getDate());
    var hora  = pad(hoy.getHours())+':'+pad(hoy.getMinutes());
    document.getElementById('nl_fechaSolicita').value = fecha+'T'+hora;
    document.getElementById('nl_fecha_inicio').value  = fecha;
    document.getElementById('nl_fecha_fin').value     = fecha;
    document.getElementById('nl_fecha').value         = fecha;

    ['nl_medio','nl_fechaSolicita','nl_parentesco','nl_solicitante','nl_motivo','nl_detalle',
     'nl_sabados','nl_cantidad','nl_chk_excepcion','nl_hora_salida'].forEach(id => {
        document.getElementById(id).disabled = false;
    });
    nlCambiarTipo(_nlTipo, document.querySelector('.nl-tab[data-nl-tipo="' + _nlTipo + '"]'));
    document.getElementById('nl_btn_registrar').disabled = false;

    nlCargarCupo(student_id);
    nlCargarPeriodos(_nlSectionId);

    $.post('<?= base_url('server/student_fill') ?>', { student_id: student_id }, function(r) {
        var c = JSON.parse(r);
        if (c.length) _nlFamilyId = c[0].family_id;
    });
}

function nlCargarCupo(student_id) {
    post(BASE + 'manager/prim_cupo_estudiante', { student_id: student_id }).then(nlMostrarCupo);
}

function nlMostrarCupo(cupo) {
    document.getElementById('nl_cupo_panel').style.display = '';

    var total    = parseFloat(cupo.total) || 0;
    var restante = parseFloat(cupo.cupo_restante) || 0;
    var pct      = Math.min((total / 9) * 100, 100);

    document.getElementById('nl_cupo_texto').textContent     = total.toFixed(1) + ' / 9 días';
    document.getElementById('nl_cupo_consumido').textContent = total.toFixed(1);
    document.getElementById('nl_cupo_restante').textContent  = restante.toFixed(1);

    var barra = document.getElementById('nl_cupo_barra');
    barra.style.width = pct + '%';
    barra.className   = 'cupo-bar-fill ' + (cupo.limite9 ? 'cupo-full' : (cupo.alerta6 ? 'cupo-warn' : 'cupo-ok'));

    var alerta = document.getElementById('nl_cupo_alerta');
    if (cupo.limite9) {
        alerta.innerHTML = '<div class="alert alert-danger py-2 mb-0 font-size-sm"><i class="fas fa-exclamation-triangle mr-1"></i> Alumno en límite de <strong>9 días</strong></div>';
        alerta.style.display = '';
    } else if (cupo.alerta6) {
        alerta.innerHTML = '<div class="alert alert-warning py-2 mb-0 font-size-sm"><i class="fas fa-exclamation-circle mr-1"></i> Alumno superó los <strong>6 días</strong> de cupo</div>';
        alerta.style.display = '';
    } else {
        alerta.style.display = 'none';
    }
}

function nlCalcularDias() {
    var fi = new Date(document.getElementById('nl_fecha_inicio').value);
    var ff = new Date(document.getElementById('nl_fecha_fin').value);
    if (!document.getElementById('nl_fecha_inicio').value || !document.getElementById('nl_fecha_fin').value) return;
    if (ff < fi) { alert('La fecha fin no puede ser anterior a la fecha inicio'); return; }
    var contarSab = document.getElementById('nl_sabados').checked;
    var dias = 0, cur = new Date(fi);
    while (cur <= ff) {
        var dow = cur.getDay();
        if (dow !== 0 && (contarSab || dow !== 6)) dias++;
        cur.setDate(cur.getDate() + 1);
    }
    document.getElementById('nl_cantidad').value = dias > 0 ? dias : 1;
}

function nlFillParent(relationship) {
    if (!_nlFamilyId) return;
    if (relationship <= 2) {
        $.get('<?= base_url('server/fill_parent_relationship') ?>/' + _nlFamilyId + '/' + relationship, function(r) {
            var c = JSON.parse(r);
            if (c.length) {
                document.getElementById('nl_solicitante').value =
                    c[0].name + ' ' + c[0].lastname1 + ' ' + c[0].lastname2;
            }
        });
    } else {
        document.getElementById('nl_solicitante').value = '';
        document.getElementById('nl_solicitante').focus();
    }
}

function nlCargarPeriodos(section_id) {
    var cont = document.getElementById('nl_periodos_container');
    if (!section_id) return;
    cont.innerHTML = '<em class="text-muted">Cargando períodos...</em>';
    $.get('<?= base_url('server/fill_periodos_section') ?>/' + section_id, function(r) {
        cont.innerHTML = '';
        if (!r.length) {
            cont.innerHTML = '<span class="text-muted">No hay períodos para este curso.</span>';
            return;
        }
        r.forEach(function(p) {
            var hi = p.hora_inicio ? p.hora_inicio.substring(0,5) : '';
            var hf = p.hora_fin    ? p.hora_fin.substring(0,5)    : '';
            var div = document.createElement('label');
            div.className = 'periodo-check d-flex align-items-center w-100 mb-1';
            div.innerHTML = '<input type="checkbox" name="periodos[]" value="'+p.periodo_id+'" class="mr-3"> '
                          + '<span>'+p.periodo+' <span class="text-muted font-size-sm">('+hi+' – '+hf+')</span></span>';
            cont.appendChild(div);
        });
    }).fail(function() {
        cont.innerHTML = '<span class="text-danger">Error al cargar períodos.</span>';
    });
}

function nlSubmit() {
    var student_id = document.getElementById('nl_student_id').value;
    if (!student_id) { alert('Seleccione un estudiante'); return; }
    if (!document.getElementById('nl_medio').value) { alert('Seleccione el medio de comunicación'); return; }

    var btn = document.getElementById('nl_btn_registrar');
    var payload, url;

    if (_nlTipo === 'dia') {
        if (!document.getElementById('nl_fecha_inicio').value) { alert('Ingrese la fecha de inicio'); return; }
        url = BASE + 'manager/prim_licencias_create';
        payload = {
            student_id:     student_id,
            medio:          document.getElementById('nl_medio').value,
            fechaSolicita:  document.getElementById('nl_fechaSolicita').value,
            parentesco:     document.getElementById('nl_parentesco').value,
            solicitante:    document.getElementById('nl_solicitante').value,
            motivo:         document.getElementById('nl_motivo').value,
            detalle:        document.getElementById('nl_detalle').value,
            fecha_inicio:   document.getElementById('nl_fecha_inicio').value,
            fecha_fin:      document.getElementById('nl_fecha_fin').value,
            cantidad:       document.getElementById('nl_cantidad').value,
            es_excepcion:   document.getElementById('nl_chk_excepcion').checked ? 1 : 0,
        };
    } else {
        var periodosSel = Array.from(document.querySelectorAll('#nl_periodos_container input[name="periodos[]"]:checked')).map(el => el.value);
        if (!document.getElementById('nl_fecha').value) { alert('Ingrese la fecha de la licencia'); return; }
        if (!periodosSel.length) { alert('Seleccione al menos un período'); return; }
        if (!document.getElementById('nl_recoge_nombre').value.trim()) { alert('Indique quién recogerá al estudiante'); return; }
        if (!document.getElementById('nl_recoge_parentesco').value) { alert('Seleccione el parentesco de quien recoge'); return; }

        url = BASE + 'manager/prim_licencias_periodo_create';
        payload = {
            student_id:            student_id,
            medio:                 document.getElementById('nl_medio').value,
            fechaSolicita:         document.getElementById('nl_fechaSolicita').value,
            parentesco:            document.getElementById('nl_parentesco').value,
            solicitante:           document.getElementById('nl_solicitante').value,
            motivo:                document.getElementById('nl_motivo').value,
            detalle:               document.getElementById('nl_detalle').value,
            fecha:                 document.getElementById('nl_fecha').value,
            hora_salida:           document.getElementById('nl_hora_salida').value,
            'periodos[]':          periodosSel,
            recoge_nombre:         document.getElementById('nl_recoge_nombre').value,
            recoge_parentesco_id:  document.getElementById('nl_recoge_parentesco').value,
            se_reincorpora:        document.getElementById('nl_se_reincorpora').checked ? 1 : 0,
            es_excepcion:          document.getElementById('nl_chk_excepcion').checked ? 1 : 0,
        };
    }

    btn.disabled = true;
    btn.textContent = 'Guardando...';

    post(url, payload).then(res => {
        if (res.ok) {
            $('#modalNuevaLicencia').modal('hide');
            nlResetForm();
            cargar();
        } else {
            mostrarInfoModal(res.msg || 'Error al registrar la licencia');
        }
    })
    .catch(() => mostrarInfoModal('Error al registrar la licencia'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save mr-1"></i> Registrar Licencia';
    });
}

$('#modalNuevaLicencia').on('hidden.bs.modal', nlResetForm);

// Si se llega con ?nueva_licencia=1&student_id=X (ej. desde Ausencias), abrir el modal y preseleccionar
(function () {
    var params = new URLSearchParams(window.location.search);
    if (params.get('nueva_licencia') === '1') {
        var sid = params.get('student_id');
        $('#modalNuevaLicencia').on('shown.bs.modal', function onShown() {
            if (sid) {
                $('#nl_student_sel').val(sid).trigger('change');
                nlSeleccionarAlumno(sid);
            }
            $('#modalNuevaLicencia').off('shown.bs.modal', onShown);
        });
        $('#modalNuevaLicencia').modal('show');
    }
})();
</script>
