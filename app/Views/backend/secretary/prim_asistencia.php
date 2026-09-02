<?php
// Preparar mapa de alumnos por sección para JS
$js_sections = [];
foreach ($sections as $sec) {
    $alumnos = [];
    foreach ($grouped[$sec['section_id']] ?? [] as $stu) {
        $cupo = $cupos_map[$stu['student_id']] ?? null;
        $alumnos[] = [
            'id'            => $stu['student_id'],
            'name'          => $stu['name'],
            'cupo_total'    => $cupo ? round($cupo['total'], 1) : 0,
            'cupo_alerta6'  => $cupo ? ($cupo['alerta6'] ? 1 : 0) : 0,
            'cupo_limite9'  => $cupo ? ($cupo['limite9'] ? 1 : 0) : 0,
        ];
    }
    $js_sections[] = [
        'section_id' => (int)$sec['section_id'],
        'nick_name'  => $sec['nick_name'],
        'completo'   => $sec['completo'],
        'alumnos'    => $alumnos,
    ];
}
?>
<style>
.prim-header-card { background: linear-gradient(135deg, #1d6ec9 0%, #3699FF 100%); border-radius: 10px; }
.stat-strip { border-radius: 10px; }
.stat-pill  { text-align:center; padding:8px 6px; border-right:1px solid rgba(0,0,0,.05); }
.stat-pill:last-child { border-right:0; }
.stat-num  { font-size: 1.25rem; font-weight: 800; line-height: 1.1; }
.stat-lbl  { font-size: .68rem; text-transform: uppercase; letter-spacing:.04em; opacity:.7; }
.acc-header { width:100%; background:#fff; border:0; padding:14px 20px; display:flex;
              align-items:center; justify-content:space-between; cursor:pointer; transition:background .15s; }
.acc-header:hover { background:#f8f9ff; }
.acc-title  { font-weight:700; font-size:.92rem; color:#181c32; }
.acc-title .sec-badge { font-size:.68rem; padding:1px 7px; border-radius:9px; margin-left:8px; }
.acc-chevron { color:#3699FF; transition:transform .2s; }
.acc-chevron.acc-open { transform:rotate(90deg); }
.page-mode-btn { border-radius:8px; padding:7px 18px; font-weight:700; font-size:.85rem;
                 border:2px solid #e0e0e0; background:#fff; color:#7e8299; cursor:pointer; transition:all .2s; }
.page-mode-btn:hover { border-color:#3699FF; color:#3699FF; }
.page-mode-btn.active { border-color:#3699FF; background:#3699FF; color:#fff; }
.stu-row { transition: background .15s; }
.stu-row:hover { background: #f8f9ff; }
.stu-row td { padding-top:.45rem; padding-bottom:.45rem; }
.status-pill { display:inline-block; padding:3px 11px; border-radius:16px; font-weight:700; font-size:.74rem; }
.s-present  { background:#e8fff3; color:#50cd89; }
.s-absent   { background:#fff0f2; color:#f1416c; }
.s-license  { background:#e8f4ff; color:#009ef7; }
.s-salida   { background:#fff3e0; color:#e65100; }
.s-late     { background:#fff8dd; color:#ffc700; }
.s-none     { background:#f5f5f5; color:#a0a0a0; }
.cupo-badge { font-size:.65rem; font-weight:700; padding:1px 7px; border-radius:9px; margin-left:5px; vertical-align:middle; }
.cupo-ok     { background:#e8fff3; color:#50cd89; }
.cupo-alerta { background:#fff8dd; color:#ffa800; }
.cupo-limite { background:#fff0f2; color:#f1416c; }
.sec-count-ok      { background:#e8fff3; color:#50cd89; }
.sec-count-warning { background:#fff8dd; color:#ffa800; }
.sec-count-danger  { background:#fff0f2; color:#f1416c; }
.sec-count-none    { background:#f5f5f5; color:#a0a0a0; }
.cal-th { text-align:center; font-size:.68rem; padding:4px 2px !important; line-height:1.2; }
.cal-badge { display:inline-block; width:22px; height:22px; line-height:22px; border-radius:5px;
             font-weight:800; font-size:.68rem; text-align:center; }
.cal-p    { background:#e8fff3; color:#50cd89; }
.cal-a    { background:#fff0f2; color:#f1416c; }
.cal-l    { background:#e8f4ff; color:#009ef7; }
.cal-r    { background:#fff8dd; color:#b38f00; }
.cal-none { color:#c4c9d6; }
</style>

<div class="container-fluid pb-8">

    <!-- Header -->
    <div class="prim-header-card shadow-sm mb-3 px-4 py-3 d-flex align-items-center flex-wrap" style="gap:10px;">
        <div>
            <h5 class="text-white font-weight-bolder mb-0" id="titulo_modo">📋 Asistencia del Día — Primaria</h5>
            <span class="text-white opacity-75 font-size-xs">
                3ro a 6to &nbsp;·&nbsp; <strong><?= esc($phase_name) ?></strong>
                &nbsp;(<?= date('d-m-Y', strtotime($phase_ini)) ?> → <?= date('d-m-Y', strtotime($phase_fin)) ?>)
            </span>
        </div>
        <div class="ml-auto d-flex align-items-center flex-wrap" style="gap:8px;">
            <input type="date" id="fecha_sel" class="form-control form-control-sm"
                   style="width:150px; background:#ffffff22; border-color:#ffffff44; color:#fff;"
                   value="<?= date('Y-m-d') ?>"
                   min="<?= $phase_ini ?>"
                   max="<?= $phase_fin ?>">
            <span id="rango_texto" class="text-white font-weight-bold font-size-sm" style="display:none;"></span>
            <button id="btn_cargar" class="btn btn-light btn-sm font-weight-bold px-4">
                <i class="fas fa-sync-alt mr-1"></i>Cargar
            </button>
            <button id="btn_marcar_presente" class="btn btn-light-success btn-sm font-weight-bold px-4" onclick="marcarTodosPresente()">
                <i class="fas fa-check-double mr-1"></i>Marcar todos Presente
            </button>
            <button id="btn_guardar_asistencia" class="btn btn-success btn-sm font-weight-bold px-4" disabled onclick="guardarAsistenciaDia()">
                <i class="fas fa-save mr-1"></i>Guardar Asistencia
            </button>
        </div>
    </div>
    <div class="alert alert-light-primary py-2 px-3 mb-3 font-size-sm" id="ayuda_dia">
        <i class="fas fa-info-circle mr-1"></i>
        Haz clic sobre el estado de un alumno para alternar entre <strong>Presente / Ausente / Retraso</strong>,
        o usa <strong>"Marcar todos Presente"</strong> para hacerlo de una vez.
        Luego corrige solo las excepciones y presiona <strong>Guardar Asistencia</strong> una sola vez.
    </div>

    <!-- Selector de modo -->
    <div class="d-flex mb-3" style="gap:8px;">
        <button type="button" class="page-mode-btn active" id="modo_dia" onclick="setMode('dia')">Día</button>
        <button type="button" class="page-mode-btn" id="modo_mes" onclick="setMode('mes')">Mes</button>
        <button type="button" class="page-mode-btn" id="modo_trimestre" onclick="setMode('trimestre')">Trimestre</button>
    </div>

    <!-- Franja de resumen -->
    <div class="stat-strip shadow-sm mb-3 d-flex" id="stats_row" style="background:#fff;">
        <div class="stat-pill flex-fill">
            <div class="stat-num text-success" id="cnt_present">—</div>
            <div class="stat-lbl text-success">Presentes</div>
        </div>
        <div class="stat-pill flex-fill">
            <div class="stat-num text-danger" id="cnt_absent">—</div>
            <div class="stat-lbl text-danger">Ausentes</div>
        </div>
        <div class="stat-pill flex-fill">
            <div class="stat-num text-primary" id="cnt_license">—</div>
            <div class="stat-lbl text-primary">Con Licencia</div>
        </div>
        <div class="stat-pill flex-fill">
            <div class="stat-num text-muted" id="cnt_none">—</div>
            <div class="stat-lbl text-muted">Sin registro</div>
        </div>
    </div>

    <!-- Secciones (colapsadas por defecto) -->
    <div class="card card-custom shadow-sm">
        <div class="card-body p-0" id="sec_accordion"></div>
    </div>

</div>

<script>
const SECTIONS   = <?= json_encode($js_sections, JSON_UNESCAPED_UNICODE) ?>;
const PHASE_INI  = '<?= $phase_ini ?>';
const PHASE_FIN  = '<?= $phase_fin ?>';

let MODE = 'dia';

const S_LABELS = {
    0: { text:'Ausente',          cls:'s-absent'  },
    1: { text:'Presente',         cls:'s-present' },
    2: { text:'Licencia',         cls:'s-license' },
    3: { text:'Retraso',          cls:'s-late'    },
};

function cupoHtml(stu) {
    if (!stu.cupo_total) return '';
    if (stu.cupo_limite9) return `<span class="cupo-badge cupo-limite" title="Límite de cupo alcanzado">🚫 ${stu.cupo_total}/9</span>`;
    if (stu.cupo_alerta6) return `<span class="cupo-badge cupo-alerta" title="En alerta de cupo">⚠️ ${stu.cupo_total}/9</span>`;
    return `<span class="cupo-badge cupo-ok">${stu.cupo_total}/9</span>`;
}

function toggleSection(sectionId) {
    const body = document.getElementById(`sec_body_${sectionId}`);
    const chev = document.getElementById(`chev_${sectionId}`);
    const open = body.style.display !== 'none';
    body.style.display = open ? 'none' : '';
    chev.classList.toggle('acc-open', !open);
}

function buildTabs() {
    const accordion = document.getElementById('sec_accordion');
    accordion.innerHTML = '';
    const monthDays = (MODE === 'mes') ? computeMonthDays() : [];

    SECTIONS.forEach((sec, i) => {
        let rows, thead;

        if (MODE === 'trimestre') {
            rows = sec.alumnos.map((stu, j) => `
                <tr class="stu-row" id="row_${stu.id}">
                    <td class="pl-4 text-muted font-size-sm">${j+1}</td>
                    <td><span class="font-weight-bold">${stu.name}</span></td>
                    <td class="text-center text-success font-weight-bold" id="r_presente_${stu.id}">—</td>
                    <td class="text-center text-danger font-weight-bold" id="r_ausente_${stu.id}">—</td>
                    <td class="text-center text-primary font-weight-bold" id="r_licencia_${stu.id}">—</td>
                    <td class="text-center" style="color:#ffc700;font-weight:700;" id="r_retraso_${stu.id}">—</td>
                    <td class="text-center font-weight-bold" id="r_pct_${stu.id}">—</td>
                    <td class="text-center" id="r_cupo_${stu.id}">—</td>
                </tr>`).join('');

            thead = `
                <tr class="text-muted text-uppercase font-size-xs">
                    <th class="pl-4" width="40">#</th>
                    <th>Estudiante</th>
                    <th class="text-center" width="90">Presente</th>
                    <th class="text-center" width="90">Ausente</th>
                    <th class="text-center" width="90">Licencia</th>
                    <th class="text-center" width="90">Retraso</th>
                    <th class="text-center" width="100">% Asist.</th>
                    <th class="text-center" width="110">Cupo (rango)</th>
                </tr>`;

        } else if (MODE === 'mes') {
            rows = sec.alumnos.map((stu, j) => {
                const dayCells = monthDays.map(d => `<td class="text-center" id="cal_${stu.id}_${d}">—</td>`).join('');
                return `
                <tr class="stu-row" id="row_${stu.id}">
                    <td class="pl-4 text-muted font-size-sm">${j+1}</td>
                    <td><span class="font-weight-bold">${stu.name}</span></td>
                    ${dayCells}
                    <td class="text-center font-weight-bold" id="r_pct_${stu.id}">—</td>
                    <td class="text-center" id="r_cupo_${stu.id}">—</td>
                </tr>`;
            }).join('');

            const dayHeaders = monthDays.map(d => {
                const dt  = new Date(d + 'T00:00:00');
                const dow = ['Do','Lu','Ma','Mi','Ju','Vi','Sa'][dt.getDay()];
                return `<th class="cal-th">${dow}<br>${dt.getDate()}</th>`;
            }).join('');

            thead = `
                <tr class="text-muted text-uppercase font-size-xs">
                    <th class="pl-4" width="40">#</th>
                    <th>Estudiante</th>
                    ${dayHeaders}
                    <th class="text-center" width="90">% Asist.</th>
                    <th class="text-center" width="110">Cupo (rango)</th>
                </tr>`;

        } else {
            rows = sec.alumnos.map((stu, j) => `
                <tr class="stu-row" id="row_${stu.id}">
                    <td class="pl-4 text-muted font-size-sm">${j+1}</td>
                    <td>
                        <span class="font-weight-bold">${stu.name}</span>
                        ${cupoHtml(stu)}
                    </td>
                    <td><span class="status-pill s-none" id="status_${stu.id}">Sin registro</span></td>
                    <td>
                        <input type="text" class="form-control form-control-sm" id="obs_${stu.id}"
                            placeholder="Observación" oninput="marcarSucio(${stu.id})">
                    </td>
                    <td class="font-size-sm" id="lic_${stu.id}"></td>
                    <td class="text-muted font-size-xs" id="mod_${stu.id}">—</td>
                </tr>`).join('');

            thead = `
                <tr class="text-muted text-uppercase font-size-xs">
                    <th class="pl-4" width="40">#</th>
                    <th>Estudiante</th>
                    <th width="160">Estado</th>
                    <th width="220">Observación</th>
                    <th width="220">Licencia</th>
                    <th width="190">Últ. modificación</th>
                </tr>`;
        }

        const item = document.createElement('div');
        item.className = 'acc-item' + (i > 0 ? ' border-top' : '');
        item.innerHTML = `
            <button type="button" class="acc-header" onclick="toggleSection(${sec.section_id})">
                <span class="acc-title">
                    ${sec.nick_name}
                    <span class="sec-badge sec-count-none" id="badge_${sec.section_id}">0/${sec.alumnos.length}</span>
                </span>
                <i class="fas fa-chevron-right acc-chevron" id="chev_${sec.section_id}"></i>
            </button>
            <div class="acc-body" id="sec_body_${sec.section_id}" style="display:none;">
                ${MODE === 'dia' ? `
                <div class="d-flex justify-content-end px-3 pt-2">
                    <button type="button" class="btn btn-xs btn-light-success font-size-xs" onclick="marcarSeccionPresente(${sec.section_id})">
                        <i class="fas fa-check-double mr-1"></i>Marcar Presente (este curso)
                    </button>
                </div>` : ''}
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">${thead}</thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>`;
        accordion.appendChild(item);
    });
}

let _attMapActual = {};

function modificacionHtml(attRec) {
    if (!attRec || !attRec.registered_by_nombre) return '—';
    const quien = `${attRec.registered_by_rol ? attRec.registered_by_rol + ' — ' : ''}${attRec.registered_by_nombre}`;
    if (!attRec.registered_by_fecha) return quien;
    const [d, t] = attRec.registered_by_fecha.split(' ');
    const [y, m, dd] = d.split('-');
    return `${quien}<br><span class="text-muted" style="font-size:.7rem;">${dd}-${m}-${y} ${t ? t.substring(0,5) : ''}</span>`;
}

const STATUS_CYCLE = [1, 0, 3]; // Presente -> Ausente -> Retraso -> Presente...

function applyData(data) {
    const att    = data.attendance  || {};
    const licDia = data.lic_dia     || {};
    const licSal = data.lic_sal     || {};
    _attMapActual = att;
    _dirty = {};

    SECTIONS.forEach(sec => {
        sec.alumnos.forEach(stu => {
            const sid       = stu.id;
            const attRec    = att[sid];
            const licDiaRec = licDia[sid];
            const licSalRec = licSal[sid];

            const pillEl = document.getElementById(`status_${sid}`);
            const obsEl  = document.getElementById(`obs_${sid}`);
            let licHtml = '';

            if (licSalRec) {
                licHtml = `<span class="badge badge-warning font-size-xs">
                    Salida ${licSalRec.hora_salida ? licSalRec.hora_salida.substring(0,5) : ''}
                </span>`;
            }

            obsEl.value    = attRec ? (attRec.observation || '') : '';
            obsEl.disabled = false;

            if (licDiaRec) {
                // Licencia de día aprobada: el estado es automático, no editable a mano
                pillEl.className   = 'status-pill s-license';
                pillEl.textContent = 'Licencia día';
                pillEl.style.cursor = 'default';
                pillEl.onclick = null;
                obsEl.disabled = true;
                licHtml = `<span class="badge badge-${licDiaRec.es_excepcion=='1'?'success':'primary'} font-size-xs">
                    ${licDiaRec.es_excepcion=='1'?'⭐ ':''} ${licDiaRec.motivo}
                </span>`;
            } else if (attRec) {
                const statusActual = Number(attRec.status);
                const info = S_LABELS[statusActual] || S_LABELS[1];
                pillEl.dataset.status = statusActual;
                pillEl.className    = `status-pill ${info.cls}`;
                pillEl.textContent  = info.text;
                pillEl.style.cursor = 'pointer';
                pillEl.onclick = () => ciclarEstado(sid);
            } else {
                // Sin registro: no se asume ningún estado hasta que se haga clic
                pillEl.dataset.status = '';
                pillEl.className    = 'status-pill s-none';
                pillEl.textContent  = 'Sin registro';
                pillEl.style.cursor = 'pointer';
                pillEl.onclick = () => ciclarEstado(sid);
            }

            document.getElementById(`lic_${sid}`).innerHTML = licHtml;
            document.getElementById(`mod_${sid}`).innerHTML = modificacionHtml(attRec);
        });
    });

    recalcularResumenDia();
    actualizarBotonGuardar();
}

/* ─── Recalcula la franja de estadísticas y los badges de sección a partir
   del estado actual de los "pills" en pantalla (incluye cambios sin guardar) ─── */
function recalcularResumenDia() {
    let cntPresent=0, cntAbsent=0, cntLicense=0, cntNone=0;

    SECTIONS.forEach(sec => {
        let secPresent = 0;
        sec.alumnos.forEach(stu => {
            const pillEl = document.getElementById(`status_${stu.id}`);
            if (!pillEl) return;

            if (!pillEl.onclick) {
                // Licencia de día (no editable)
                cntLicense++;
                secPresent++;
                return;
            }

            if (pillEl.dataset.status === '') { cntNone++; return; }

            const status = Number(pillEl.dataset.status);
            if (status === 1 || status === 3) { cntPresent++; secPresent++; }
            else if (status === 0)            { cntAbsent++; }
        });

        const badge = document.getElementById(`badge_${sec.section_id}`);
        const total = sec.alumnos.length;
        const pct   = total > 0 ? secPresent/total : 0;
        badge.textContent = `${secPresent}/${total}`;
        badge.className   = 'sec-badge ' + (pct>=.9?'sec-count-ok':pct>=.7?'sec-count-warning':'sec-count-danger');
    });

    document.getElementById('cnt_present').textContent = cntPresent;
    document.getElementById('cnt_absent').textContent  = cntAbsent;
    document.getElementById('cnt_license').textContent = cntLicense;
    document.getElementById('cnt_none').textContent    = cntNone;
}

/* ─── Registrar/corregir asistencia en lote (respaldo del profesor) ───
   El estado de cada alumno es un "pill" clickeable que rota Presente → Ausente
   → Retraso. Los que no tienen registro se muestran como "Sin registro" y no
   se guardan hasta que se definan (a mano o con "Marcar todos Presente"). */
let _dirty = {};

function ciclarEstado(sid) {
    const pillEl = document.getElementById(`status_${sid}`);
    if (!pillEl || !pillEl.onclick) return;

    let siguiente;
    if (pillEl.dataset.status === '') {
        siguiente = 1; // primer clic desde "Sin registro" -> Presente
    } else {
        const actual = Number(pillEl.dataset.status);
        const idx    = STATUS_CYCLE.indexOf(actual);
        siguiente = STATUS_CYCLE[(idx + 1) % STATUS_CYCLE.length];
    }
    const info = S_LABELS[siguiente];

    pillEl.dataset.status = siguiente;
    pillEl.className   = `status-pill ${info.cls}`;
    pillEl.textContent = info.text;

    _dirty[sid] = true;
    recalcularResumenDia();
    actualizarBotonGuardar();
}

function marcarSucio(sid) {
    // Si escribe una observación sin haber definido el estado, se asume Presente
    const pillEl = document.getElementById(`status_${sid}`);
    if (pillEl && pillEl.onclick && pillEl.dataset.status === '') {
        const info = S_LABELS[1];
        pillEl.dataset.status = 1;
        pillEl.className   = `status-pill ${info.cls}`;
        pillEl.textContent  = info.text;
        recalcularResumenDia();
    }
    _dirty[sid] = true;
    actualizarBotonGuardar();
}

function _marcarPresentePills(alumnos) {
    alumnos.forEach(stu => {
        const pillEl = document.getElementById(`status_${stu.id}`);
        if (!pillEl || !pillEl.onclick) return; // saltar alumnos con licencia (no editable)

        const info = S_LABELS[1];
        pillEl.dataset.status = 1;
        pillEl.className   = `status-pill ${info.cls}`;
        pillEl.textContent = info.text;
        _dirty[stu.id] = true;
    });
}

function marcarTodosPresente() {
    SECTIONS.forEach(sec => _marcarPresentePills(sec.alumnos));
    recalcularResumenDia();
    actualizarBotonGuardar();
}

function marcarSeccionPresente(sectionId) {
    const sec = SECTIONS.find(s => Number(s.section_id) === Number(sectionId));
    if (!sec) return;
    _marcarPresentePills(sec.alumnos);
    recalcularResumenDia();
    actualizarBotonGuardar();
}

function actualizarBotonGuardar() {
    const n   = Object.keys(_dirty).length;
    const btn = document.getElementById('btn_guardar_asistencia');
    btn.innerHTML = n > 0
        ? `<i class="fas fa-save mr-1"></i>Guardar Asistencia (${n})`
        : `<i class="fas fa-save mr-1"></i>Guardar Asistencia`;
    btn.disabled = (n === 0);
}

function guardarAsistenciaDia() {
    const sids = Object.keys(_dirty);
    if (!sids.length) return;

    const params = new URLSearchParams();
    params.append('date', document.getElementById('fecha_sel').value);
    sids.forEach(sid => {
        const pillEl = document.getElementById(`status_${sid}`);
        const obsEl  = document.getElementById(`obs_${sid}`);
        params.append(`rows[${sid}][status]`, pillEl.dataset.status);
        params.append(`rows[${sid}][observation]`, obsEl ? obsEl.value : '');
    });

    const btn = document.getElementById('btn_guardar_asistencia');
    btn.disabled = true;
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Guardando...';

    fetch('<?= base_url('secretary/prim_asistencia_save_bulk') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: params.toString()
    })
    .then(r => r.json())
    .then(res => {
        if (res.ok) {
            _dirty = {};
            cargar();
        } else {
            alert(res.msg || 'No se pudo guardar la asistencia');
            btn.disabled = false;
            btn.innerHTML = original;
        }
    })
    .catch(() => {
        alert('Error al guardar');
        btn.disabled = false;
        btn.innerHTML = original;
    });
}

function pctClass(pct) {
    if (pct >= 90) return 'text-success';
    if (pct >= 75) return 'text-warning';
    return 'text-danger';
}

function applyResumen(data) {
    const asis = data.asistencia || {};
    const cupo = data.cupo       || {};

    SECTIONS.forEach(sec => {
        let secPresent = 0;
        sec.alumnos.forEach(stu => {
            const sid = stu.id;
            const r   = asis[sid];

            const presente  = r ? parseInt(r.dias_presente)   : 0;
            const ausente   = r ? parseInt(r.dias_ausente)    : 0;
            const licencia  = r ? parseInt(r.dias_licencia)   : 0;
            const retraso   = r ? parseInt(r.dias_retraso)    : 0;
            const total     = r ? parseInt(r.dias_registrados): 0;
            const pct       = total > 0 ? Math.round((presente + retraso) / total * 100) : 0;
            const cupoTotal = cupo[sid] !== undefined ? cupo[sid] : 0;

            document.getElementById(`r_presente_${sid}`).textContent = total ? presente : '—';
            document.getElementById(`r_ausente_${sid}`).textContent  = total ? ausente  : '—';
            document.getElementById(`r_licencia_${sid}`).textContent = total ? licencia : '—';
            document.getElementById(`r_retraso_${sid}`).textContent  = total ? retraso  : '—';

            const pctCell = document.getElementById(`r_pct_${sid}`);
            pctCell.textContent = total ? `${pct}%` : '—';
            pctCell.className   = 'text-center font-weight-bold ' + (total ? pctClass(pct) : 'text-muted');

            document.getElementById(`r_cupo_${sid}`).innerHTML = cupoHtml({
                cupo_total: cupoTotal, cupo_alerta6: cupoTotal >= 6 && cupoTotal < 9, cupo_limite9: cupoTotal >= 9
            }) || '<span class="text-muted">0/9</span>';

            if (total && pct >= 90) secPresent++;
        });

        const badge = document.getElementById(`badge_${sec.section_id}`);
        const total = sec.alumnos.length;
        const pct   = total > 0 ? secPresent/total : 0;
        badge.textContent = `${secPresent}/${total}`;
        badge.className   = 'sec-badge ' + (pct>=.9?'sec-count-ok':pct>=.7?'sec-count-warning':'sec-count-danger');
    });
}

/* ─── Vista "Mes": una columna por día (excluye domingos) ─── */
const CAL_BADGE = {
    0: { cls:'cal-a', txt:'A', title:'Ausente'  },
    1: { cls:'cal-p', txt:'P', title:'Presente' },
    2: { cls:'cal-l', txt:'L', title:'Licencia' },
    3: { cls:'cal-r', txt:'R', title:'Retraso'  },
};

function applyMesGrid(data) {
    const dias = data.dias || {};
    const cupo = data.cupo || {};
    const monthDays = computeMonthDays();

    SECTIONS.forEach(sec => {
        let secPresent = 0;
        sec.alumnos.forEach(stu => {
            const sid       = stu.id;
            const registros = dias[sid] || {};

            let presentes = 0, retrasos = 0, registrados = 0;

            monthDays.forEach(d => {
                const cell = document.getElementById(`cal_${sid}_${d}`);
                if (!cell) return;

                const status = registros[d];
                if (status === undefined) {
                    cell.innerHTML = '<span class="cal-none">—</span>';
                    return;
                }
                registrados++;
                if (status === 1) presentes++;
                else if (status === 3) retrasos++;

                const info = CAL_BADGE[status];
                cell.innerHTML = info
                    ? `<span class="cal-badge ${info.cls}" title="${info.title}">${info.txt}</span>`
                    : '<span class="cal-none">—</span>';
            });

            const pct = registrados > 0 ? Math.round((presentes + retrasos) / registrados * 100) : 0;
            const pctCell = document.getElementById(`r_pct_${sid}`);
            pctCell.textContent = registrados ? `${pct}%` : '—';
            pctCell.className   = 'text-center font-weight-bold ' + (registrados ? pctClass(pct) : 'text-muted');

            const cupoTotal = cupo[sid] !== undefined ? cupo[sid] : 0;
            document.getElementById(`r_cupo_${sid}`).innerHTML = cupoHtml({
                cupo_total: cupoTotal, cupo_alerta6: cupoTotal >= 6 && cupoTotal < 9, cupo_limite9: cupoTotal >= 9
            }) || '<span class="text-muted">0/9</span>';

            if (registrados && pct >= 90) secPresent++;
        });

        const badge = document.getElementById(`badge_${sec.section_id}`);
        const total = sec.alumnos.length;
        const pct   = total > 0 ? secPresent/total : 0;
        badge.textContent = `${secPresent}/${total}`;
        badge.className   = 'sec-badge ' + (pct>=.9?'sec-count-ok':pct>=.7?'sec-count-warning':'sec-count-danger');
    });
}

function computeRange() {
    if (MODE === 'trimestre') return { ini: PHASE_INI, fin: PHASE_FIN };
    if (MODE === 'mes') {
        const hoy = new Date();
        const ini = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
        const fin = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
        const fmt = d => d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
        return { ini: fmt(ini), fin: fmt(fin) };
    }
    return null;
}

function computeMonthDays() {
    const r = computeRange();
    if (!r) return [];
    const days = [];
    let cur = new Date(r.ini + 'T00:00:00');
    const end = new Date(r.fin + 'T00:00:00');
    const pad = n => String(n).padStart(2, '0');
    while (cur <= end) {
        if (cur.getDay() !== 0) { // excluir domingo
            days.push(`${cur.getFullYear()}-${pad(cur.getMonth()+1)}-${pad(cur.getDate())}`);
        }
        cur.setDate(cur.getDate() + 1);
    }
    return days;
}

function setMode(mode) {
    MODE = mode;
    document.querySelectorAll('.page-mode-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('modo_' + mode).classList.add('active');

    const esResumen = (mode !== 'dia');
    document.getElementById('fecha_sel').style.display             = esResumen ? 'none' : '';
    document.getElementById('rango_texto').style.display           = esResumen ? '' : 'none';
    document.getElementById('stats_row').style.display             = esResumen ? 'none' : '';
    document.getElementById('btn_guardar_asistencia').style.display = esResumen ? 'none' : '';
    document.getElementById('btn_marcar_presente').style.display    = esResumen ? 'none' : '';
    document.getElementById('ayuda_dia').style.display              = esResumen ? 'none' : '';

    const titulos = { dia: '📋 Asistencia del Día — Primaria', mes: '📊 Resumen del Mes — Primaria', trimestre: '📊 Resumen del Trimestre — Primaria' };
    document.getElementById('titulo_modo').textContent = titulos[mode];

    if (esResumen) {
        const r = computeRange();
        document.getElementById('rango_texto').textContent =
            `Del ${r.ini.split('-').reverse().join('-')} al ${r.fin.split('-').reverse().join('-')}`;
    }

    buildTabs();
    cargar();
}

function cargar() {
    const btn = document.getElementById('btn_cargar');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Cargando...';

    let url, body;
    if (MODE === 'dia') {
        const date = document.getElementById('fecha_sel').value;
        if (!date) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-sync-alt mr-1"></i>Cargar'; return; }
        url  = '<?= base_url('secretary/prim_asistencia_data') ?>';
        body = 'date=' + encodeURIComponent(date);
    } else if (MODE === 'mes') {
        const r = computeRange();
        url  = '<?= base_url('secretary/prim_asistencia_mes_data') ?>';
        body = 'fecha_ini=' + encodeURIComponent(r.ini) + '&fecha_fin=' + encodeURIComponent(r.fin);
    } else {
        const r = computeRange();
        url  = '<?= base_url('secretary/prim_asistencia_resumen') ?>';
        body = 'fecha_ini=' + encodeURIComponent(r.ini) + '&fecha_fin=' + encodeURIComponent(r.fin);
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: body
    })
    .then(r => r.json())
    .then(data => {
        if (MODE === 'dia') applyData(data);
        else if (MODE === 'mes') applyMesGrid(data);
        else applyResumen(data);
    })
    .catch(() => alert('Error al cargar datos'))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sync-alt mr-1"></i>Cargar';
    });
}

// Inicializar
buildTabs();
cargar(); // Auto-carga hoy

document.getElementById('btn_cargar').addEventListener('click', cargar);
document.getElementById('fecha_sel').addEventListener('change', cargar);
</script>
