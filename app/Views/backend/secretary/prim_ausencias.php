<style>
.aus-header { background: linear-gradient(135deg, #f1416c 0%, #a3003a 100%); border-radius: 12px; }
.stat-pill  { border-radius: 10px; padding: 14px 18px; text-align: center; min-width: 110px; }
.stat-pill .num { font-size: 1.8rem; font-weight: 800; line-height: 1; }
.stat-pill .lbl { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; opacity: .75; margin-top: 4px; }
.filter-bar { background: #f8f9fc; border-radius: 10px; padding: 14px 18px; }
.fecha-badge {
    display: inline-block; padding: 3px 9px; margin: 2px;
    border-radius: 12px; font-size: .75rem; font-weight: 700;
    background: #fff0f2; color: #f1416c; border: 1px solid #f1416c;
}
.cupo-chip { padding: 4px 12px; border-radius: 14px; font-size: .8rem; font-weight: 700; }
.cc-ok      { background: #e8fff3; color: #50cd89; }
.cc-alerta  { background: #fff8dd; color: #ffa800; }
.cc-limite  { background: #fff0f2; color: #f1416c; }
.aus-row:hover { background: #fff8f9; }
.aus-row.alerta-row { background: #fffde7; }
.aus-row.limite-row { background: #fff0f2; }
.empty-state { text-align: center; padding: 60px 20px; color: #b5b5c3; }
.progress-cupo { height: 6px; border-radius: 3px; background: #f0f0f0; margin-top: 4px; }
.progress-cupo .bar { height: 100%; border-radius: 3px; transition: width .3s; }
.btn-rapido {
    border: 2px solid #f1416c; background: #fff; color: #f1416c;
    border-radius: 20px; padding: 5px 16px; font-weight: 700;
    font-size: .82rem; cursor: pointer; transition: all .15s; margin-right: 6px;
}
.btn-rapido:hover  { background: #fff0f2; }
.btn-rapido.active { background: #f1416c; color: #fff; }
</style>

<div class="container-fluid pb-8">

    <!-- Header -->
    <div class="aus-header shadow mb-6 p-5 d-flex align-items-center">
        <div>
            <h3 class="text-white font-weight-bolder mb-1">⚠️ Ausencias sin Licencia — Primaria</h3>
            <span class="text-white opacity-75 font-size-sm">
                Alumnos ausentes sin ninguna licencia registrada &nbsp;·&nbsp;
                <strong><?= esc($phase_name) ?></strong>
                &nbsp;(<?= date('d-m-Y', strtotime($phase_ini)) ?> → <?= date('d-m-Y', strtotime($phase_fin)) ?>)
            </span>
        </div>
    </div>

    <!-- Stats -->
    <div class="d-flex flex-wrap mb-5 gap-3" id="stats_aus">
        <div class="stat-pill shadow-sm" style="background:#fff0f2;">
            <div class="num text-danger"  id="cnt_dias">—</div>
            <div class="lbl text-danger">Días sin licencia</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#fff8dd;">
            <div class="num text-warning" id="cnt_alumnos">—</div>
            <div class="lbl text-warning">Alumnos</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#fff8dd;">
            <div class="num text-warning" id="cnt_alerta">—</div>
            <div class="lbl text-warning">En alerta (6+ días)</div>
        </div>
        <div class="stat-pill shadow-sm" style="background:#fff0f2;">
            <div class="num text-danger"  id="cnt_limite">—</div>
            <div class="lbl text-danger">En límite (9+ días)</div>
        </div>
    </div>

    <!-- Filtros + tabla -->
    <div class="card card-custom shadow-sm">
        <div class="card-body py-4">

            <div class="filter-bar mb-5">

                <!-- Vista -->
                <div class="mb-3">
                    <label class="font-weight-bold font-size-sm mr-3">Vista:</label>
                    <button class="btn-rapido active" id="btn_vista_ausencias" data-vista="ausencias">Ausencias sin licencia</button>
                    <button class="btn-rapido" id="btn_vista_todos" data-vista="todos">Todos los alumnos (cupo)</button>
                </div>

                <!-- Filtros rápidos (solo vista "Ausencias sin licencia") -->
                <div class="mb-3" id="bloque_filtros_fecha">
                    <label class="font-weight-bold font-size-sm mr-3">Período rápido:</label>
                    <button class="btn-rapido active" data-periodo="hoy">Hoy</button>
                    <button class="btn-rapido" data-periodo="semana">Esta semana</button>
                    <button class="btn-rapido" data-periodo="trimestre">Este trimestre</button>
                </div>

                <div class="d-flex flex-wrap align-items-end gap-3">
                    <div id="bloque_desde_hasta" class="d-flex flex-wrap align-items-end gap-3">
                        <div>
                            <label class="font-weight-bold font-size-sm mb-1 d-block">Desde</label>
                            <input type="date" id="f_ini" class="form-control form-control-sm"
                                   style="width:145px;"
                                   value="<?= date('Y-m-d') ?>"
                                   min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                        </div>
                        <div>
                            <label class="font-weight-bold font-size-sm mb-1 d-block">Hasta</label>
                            <input type="date" id="f_fin" class="form-control form-control-sm"
                                   style="width:145px;"
                                   value="<?= date('Y-m-d') ?>"
                                   min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <label class="font-weight-bold font-size-sm mb-1 d-block">Buscar alumno o curso</label>
                        <input type="text" id="f_search" class="form-control form-control-sm"
                               placeholder="Nombre o curso..." style="min-width:200px;">
                    </div>
                    <div>
                        <button id="btn_buscar" class="btn btn-danger btn-sm font-weight-bold px-5">
                            <i class="fas fa-search mr-1"></i>Buscar
                        </button>
                    </div>
                    <div>
                        <button id="btn_exportar_excel" class="btn btn-outline-success btn-sm font-weight-bold px-4" onclick="exportarExcel()">
                            <i class="fas fa-file-excel mr-1"></i>Exportar a Excel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla -->
            <div class="table-responsive">
                <table class="table" id="aus_table">
                    <thead class="bg-light">
                        <tr class="text-muted text-uppercase font-size-xs">
                            <th>Alumno / Curso</th>
                            <th>Días sin licencia</th>
                            <th>Fechas ausentes</th>
                            <th class="text-center">Cupo trimestral</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="aus_tbody">
                        <tr><td colspan="5" class="empty-state">
                            <i class="fas fa-calendar-times fa-3x mb-3 d-block opacity-40"></i>
                            Selecciona un rango de fechas y haz clic en Buscar
                        </td></tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<script>
const BASE_URL    = '<?= base_url() ?>';
const CSRF_TOKEN  = '<?= csrf_hash() ?>';
const PHASE_INI   = '<?= $phase_ini ?>';
const PHASE_FIN   = '<?= $phase_fin ?>';
let _vista = 'ausencias';

function aplicarVista(vista) {
    _vista = vista;
    document.getElementById('btn_vista_ausencias').classList.toggle('active', vista === 'ausencias');
    document.getElementById('btn_vista_todos').classList.toggle('active', vista === 'todos');
    document.getElementById('bloque_filtros_fecha').style.display  = vista === 'todos' ? 'none' : '';
    document.getElementById('bloque_desde_hasta').style.display    = vista === 'todos' ? 'none' : '';
    cargar();
}
document.getElementById('btn_vista_ausencias').addEventListener('click', () => aplicarVista('ausencias'));
document.getElementById('btn_vista_todos').addEventListener('click', () => aplicarVista('todos'));

// ── Filtros rápidos ────────────────────────────────────────────────────────
function lunes(d) {
    const day = d.getDay(); // 0=Dom, 1=Lun...
    const diff = (day === 0) ? -6 : 1 - day;
    const lun = new Date(d);
    lun.setDate(d.getDate() + diff);
    return lun.toISOString().split('T')[0];
}
function toYMD(d) { return d.toISOString().split('T')[0]; }
function clampPhase(f) {
    if (f < PHASE_INI) return PHASE_INI;
    if (f > PHASE_FIN) return PHASE_FIN;
    return f;
}

document.querySelectorAll('#bloque_filtros_fecha .btn-rapido').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('#bloque_filtros_fecha .btn-rapido').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const today = new Date();
        const todayStr = toYMD(today);
        let ini, fin;

        switch (this.dataset.periodo) {
            case 'hoy':
                ini = fin = todayStr;
                break;
            case 'semana':
                ini = clampPhase(lunes(today));
                fin = clampPhase(todayStr);
                break;
            case 'trimestre':
                ini = PHASE_INI;
                fin = clampPhase(todayStr);
                break;
        }

        document.getElementById('f_ini').value = ini;
        document.getElementById('f_fin').value = fin;
        cargar();
    });
});

function cupoChip(row) {
    const t = parseFloat(row.cupo_total) || 0;
    const pct = Math.min(100, Math.round(t / 9 * 100));
    let cls = 'cc-ok', txt = `${t}/9 días`;
    if (row.limite9) { cls = 'cc-limite'; txt = `🚫 ${t}/9 LÍMITE`; }
    else if (row.alerta6) { cls = 'cc-alerta'; txt = `⚠️ ${t}/9 Alerta`; }

    const barColor = row.limite9 ? '#f1416c' : (row.alerta6 ? '#ffa800' : '#50cd89');
    return `<span class="cupo-chip ${cls}">${txt}</span>
            <div class="progress-cupo mt-1" style="width:80px;">
                <div class="bar" style="width:${pct}%;background:${barColor};"></div>
            </div>`;
}

function fechaBadges(fechasStr) {
    if (!fechasStr) return '—';
    return fechasStr.split(',').map(f => {
        const parts = f.trim().split('-');
        const display = parts.length === 3 ? `${parts[2]}-${parts[1]}-${parts[0]}` : f;
        return `<span class="fecha-badge">${display}</span>`;
    }).join('');
}

function renderTable(data) {
    const tbody = document.getElementById('aus_tbody');
    if (!data.length) {
        const msg = _vista === 'todos'
            ? 'Sin alumnos para la búsqueda realizada'
            : 'Sin ausencias sin licencia en el período seleccionado';
        tbody.innerHTML = `<tr><td colspan="5" class="empty-state">
            <i class="fas fa-check-circle fa-3x mb-3 d-block" style="color:#50cd89;opacity:.5;"></i>
            <strong style="color:#50cd89;">${msg}</strong>
        </td></tr>`;
        return;
    }

    let totalDias = 0, cntAlerta = 0, cntLimite = 0;

    const rows = data.map(row => {
        totalDias += parseInt(row.total_ausencias);
        if (row.limite9) cntLimite++;
        else if (row.alerta6) cntAlerta++;

        const rowCls = row.limite9 ? 'aus-row limite-row' : (row.alerta6 ? 'aus-row alerta-row' : 'aus-row');
        const diasBadgeCls = row.total_ausencias >= 5 ? 'badge-danger' : (row.total_ausencias >= 3 ? 'badge-warning' : 'badge-secondary');

        return `<tr class="${rowCls}">
            <td>
                <span class="font-weight-bold">${row.student}</span>
                <span class="text-muted font-size-sm ml-2">${row.nick_name}</span>
            </td>
            <td class="text-center">
                <span class="badge badge-${diasBadgeCls} font-size-sm px-3 py-2">
                    ${row.total_ausencias} día${row.total_ausencias != 1 ? 's' : ''}
                </span>
            </td>
            <td>${fechaBadges(row.fechas)}</td>
            <td class="text-center">${cupoChip(row)}</td>
            <td class="text-center">
                <a href="${BASE_URL}secretary/prim_licencias?nueva_licencia=1&student_id=${row.student_id}"
                   class="btn btn-sm btn-light-primary font-weight-bold"
                   title="Registrar licencia para este alumno">
                    <i class="fas fa-file-medical mr-1"></i>Registrar licencia
                </a>
            </td>
        </tr>`;
    }).join('');

    tbody.innerHTML = rows;

    // Stats
    document.getElementById('cnt_dias').textContent    = totalDias;
    document.getElementById('cnt_alumnos').textContent = data.length;
    document.getElementById('cnt_alerta').textContent  = cntAlerta;
    document.getElementById('cnt_limite').textContent  = cntLimite;
}

function cargar() {
    const tbody = document.getElementById('aus_tbody');
    tbody.innerHTML = `<tr><td colspan="5" class="empty-state">
        <i class="fas fa-spinner fa-spin fa-2x mb-2 d-block"></i>Cargando...
    </td></tr>`;

    fetch(BASE_URL + 'secretary/prim_ausencias_data', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: [
            'fecha_ini=' + encodeURIComponent(document.getElementById('f_ini').value),
            'fecha_fin='  + encodeURIComponent(document.getElementById('f_fin').value),
            'search='     + encodeURIComponent(document.getElementById('f_search').value),
            'todos='      + (_vista === 'todos' ? '1' : '0'),
        ].join('&')
    })
    .then(r => r.json())
    .then(data => renderTable(data))
    .catch(() => {
        tbody.innerHTML = `<tr><td colspan="5" class="empty-state text-danger">Error al cargar datos</td></tr>`;
    });
}

function exportarExcel() {
    const params = new URLSearchParams({
        fecha_ini: document.getElementById('f_ini').value,
        fecha_fin: document.getElementById('f_fin').value,
        search:    document.getElementById('f_search').value,
        todos:     _vista === 'todos' ? '1' : '0',
    });
    window.location.href = BASE_URL + 'secretary/prim_ausencias_xlsx?' + params.toString();
}

document.getElementById('btn_buscar').addEventListener('click', function() {
    // Al buscar manualmente, quitar el botón de período rápido activo
    document.querySelectorAll('#bloque_filtros_fecha .btn-rapido').forEach(b => b.classList.remove('active'));
    cargar();
});
document.getElementById('f_search').addEventListener('keydown', e => { if (e.key === 'Enter') cargar(); });
document.getElementById('f_ini').addEventListener('change', () => {
    document.querySelectorAll('#bloque_filtros_fecha .btn-rapido').forEach(b => b.classList.remove('active'));
});
document.getElementById('f_fin').addEventListener('change', () => {
    document.querySelectorAll('#bloque_filtros_fecha .btn-rapido').forEach(b => b.classList.remove('active'));
});

// Vista inicial: soporta ?todos=1 (ej. desde el Panel General o el menú "Cupo Trimestral")
(function () {
    const params = new URLSearchParams(window.location.search);
    if (params.get('todos') === '1') {
        aplicarVista('todos');
    } else {
        cargar();
    }
})();
</script>
