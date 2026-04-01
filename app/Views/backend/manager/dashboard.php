<?php
$session     = session();
$n_lic       = count($today_licenses);
$dias_semana = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
$meses       = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$hoy         = $dias_semana[date('w')] . ', ' . date('j') . ' de ' . $meses[(int)date('n')] . ' de ' . date('Y');
$username    = htmlspecialchars($session->get('cuenta') ?? 'Director');

// Severity color for absences
function ausencia_severity($n) {
    if ($n >= 18) return ['color'=>'#F64E60','label'=>'Crítico','bg'=>'#FFF5F8'];
    if ($n >= 10) return ['color'=>'#FFA800','label'=>'Alto','bg'=>'#FFFDE7'];
    if ($n >= 5)  return ['color'=>'#8950FC','label'=>'Moderado','bg'=>'#F3E5FF'];
    return ['color'=>'#3699FF','label'=>'Bajo','bg'=>'#EEF6FF'];
}
?>
<style>
/* ── Design tokens ──────────────────────────────── */
:root {
  --dash-radius: 12px;
  --dash-shadow: 0 2px 12px rgba(0,0,0,0.07);
  --dash-shadow-hover: 0 6px 24px rgba(0,0,0,0.13);
  --dash-transition: all .2s cubic-bezier(.4,0,.2,1);
  --dash-gap: 20px;
}

/* ── Base overrides ─────────────────────────────── */
#kt_content .container-fluid { padding: 20px 28px; }

/* ── Welcome banner ─────────────────────────────── */
.dash-welcome {
  background: linear-gradient(135deg, #1a3a6b 0%, #2d6fd4 60%, #4facfe 100%);
  border-radius: var(--dash-radius);
  padding: 28px 32px;
  position: relative;
  overflow: hidden;
  margin-bottom: var(--dash-gap);
  box-shadow: 0 4px 20px rgba(45,111,212,.35);
}
.dash-welcome::before {
  content:''; position:absolute; right:-60px; top:-60px;
  width:260px; height:260px; border-radius:50%;
  background:rgba(255,255,255,.06);
}
.dash-welcome::after {
  content:''; position:absolute; right:100px; bottom:-80px;
  width:180px; height:180px; border-radius:50%;
  background:rgba(255,255,255,.04);
}
.dash-welcome-avatar {
  width:52px; height:52px; border-radius:14px;
  background:rgba(255,255,255,.18);
  display:flex; align-items:center; justify-content:center;
  flex-shrink:0;
}
.dash-welcome-title {
  font-size:1.4rem; font-weight:700; color:#fff; line-height:1.2; margin:0;
}
.dash-welcome-sub {
  font-size:.82rem; color:rgba(255,255,255,.75); margin-top:4px;
}
.dash-welcome-badge {
  background:rgba(255,255,255,.18); color:#fff;
  border-radius:20px; padding:4px 14px;
  font-size:.75rem; font-weight:600;
  border:1px solid rgba(255,255,255,.25);
}
.dash-welcome-action {
  background:rgba(255,255,255,.15); color:#fff;
  border:1px solid rgba(255,255,255,.3); border-radius:8px;
  padding:8px 18px; font-size:.8rem; font-weight:600;
  text-decoration:none; transition:var(--dash-transition);
  white-space:nowrap;
}
.dash-welcome-action:hover {
  background:rgba(255,255,255,.28); color:#fff; text-decoration:none;
  transform:translateY(-1px);
}

/* ── KPI cards ──────────────────────────────────── */
.kpi-card {
  border-radius: var(--dash-radius);
  padding: 20px 22px;
  height: 112px;
  display: flex; flex-direction: column; justify-content: space-between;
  text-decoration: none;
  transition: var(--dash-transition);
  box-shadow: var(--dash-shadow);
  border: none; overflow: hidden; position: relative;
  margin-bottom: var(--dash-gap);
}
.kpi-card::after {
  content:''; position:absolute; right:-16px; top:-16px;
  width:80px; height:80px; border-radius:50%;
  background:rgba(255,255,255,.1);
  transition: var(--dash-transition);
}
.kpi-card:hover { transform:translateY(-3px); box-shadow:var(--dash-shadow-hover); text-decoration:none; }
.kpi-card:hover::after { transform:scale(1.4); }
.kpi-icon { font-size:1.5rem; opacity:.65; }
.kpi-value { font-size:1.9rem; font-weight:800; color:#fff; line-height:1; }
.kpi-label { font-size:.72rem; font-weight:600; color:rgba(255,255,255,.8); text-transform:uppercase; letter-spacing:.5px; }

/* KPI color themes */
.kpi-blue   { background:linear-gradient(135deg,#1d6ec9,#3699FF); }
.kpi-green  { background:linear-gradient(135deg,#0d9d8a,#1BC5BD); }
.kpi-red    { background:linear-gradient(135deg,#d63c52,#F64E60); }
.kpi-amber  { background:linear-gradient(135deg,#d98a00,#FFA800); }

/* ── Quick access card ──────────────────────────── */
.quick-card {
  border-radius: var(--dash-radius);
  background:#fff;
  box-shadow: var(--dash-shadow);
  height:112px;
  margin-bottom: var(--dash-gap);
  display:flex; flex-direction:column; justify-content:center;
  padding:16px 20px;
  border:1px solid rgba(0,0,0,.04);
}
.quick-btn {
  display:inline-flex; align-items:center; gap:5px;
  padding:5px 12px; border-radius:20px;
  font-size:.74rem; font-weight:600;
  text-decoration:none; transition:var(--dash-transition);
  border:1px solid transparent;
}
.quick-btn:hover { transform:translateY(-1px); text-decoration:none; }
.quick-btn-primary   { background:#EEF6FF; color:#1d6ec9; border-color:#c9e0fa; }
.quick-btn-primary:hover   { background:#1d6ec9; color:#fff; }
.quick-btn-danger    { background:#FFF5F8; color:#d63c52; border-color:#f9c4cc; }
.quick-btn-danger:hover    { background:#d63c52; color:#fff; }
.quick-btn-warning   { background:#FFFDE7; color:#b07b00; border-color:#f5d87a; }
.quick-btn-warning:hover   { background:#FFA800; color:#fff; }
.quick-btn-info      { background:#F3E5FF; color:#6b3bbf; border-color:#d9b8f7; }
.quick-btn-info:hover      { background:#8950FC; color:#fff; }
.quick-btn-success   { background:#E8FFF9; color:#0a7c6d; border-color:#a3e6db; }
.quick-btn-success:hover   { background:#1BC5BD; color:#fff; }

/* ── Panel cards ────────────────────────────────── */
.panel-card {
  background:#fff;
  border-radius: var(--dash-radius);
  box-shadow: var(--dash-shadow);
  border:1px solid rgba(0,0,0,.05);
  margin-bottom: var(--dash-gap);
  display:flex; flex-direction:column;
}
.panel-header {
  padding:20px 24px 14px;
  border-bottom:1px solid #f4f5f8;
  display:flex; align-items:center; justify-content:space-between;
  flex-shrink:0;
}
.panel-title {
  font-size:.95rem; font-weight:700; color:#1a1d2e; margin:0;
}
.panel-subtitle {
  font-size:.75rem; color:#9198a8; margin-top:2px;
}
.panel-body { padding:16px 24px 20px; flex:1; overflow:hidden; }
.panel-body-scroll { overflow-y:auto; padding-right:6px; }
.panel-body-scroll::-webkit-scrollbar { width:4px; }
.panel-body-scroll::-webkit-scrollbar-track { background:transparent; }
.panel-body-scroll::-webkit-scrollbar-thumb { background:#dde0e8; border-radius:4px; }

/* ── Student rows (absences + licenses) ─────────── */
.student-row {
  display:flex; align-items:center; gap:12px;
  padding:10px 8px; border-radius:8px;
  transition:var(--dash-transition); cursor:default;
}
.student-row:hover { background:#f8f9fc; }
.student-row + .student-row { border-top:1px solid #f4f5f8; }

.s-rank {
  width:20px; text-align:center; flex-shrink:0;
  font-size:.78rem; font-weight:700;
}
.s-avatar {
  width:34px; height:34px; border-radius:10px;
  display:flex; align-items:center; justify-content:center;
  font-size:.75rem; font-weight:700; flex-shrink:0;
}
.s-info { flex:1; min-width:0; }
.s-name {
  font-size:.82rem; font-weight:600; color:#1a1d2e;
  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.s-meta { font-size:.72rem; color:#9198a8; margin-top:2px; }
.s-badge {
  flex-shrink:0; text-align:right;
}
.s-badge-num {
  display:inline-block; min-width:36px;
  padding:3px 8px; border-radius:6px;
  font-size:.8rem; font-weight:700; text-align:center;
}
.s-severity {
  font-size:.65rem; font-weight:600; text-transform:uppercase;
  letter-spacing:.4px; display:block; margin-top:2px; text-align:center;
}

/* ── License rows ───────────────────────────────── */
.lic-row {
  display:flex; align-items:center; gap:12px;
  padding:10px 8px; border-radius:8px;
  transition:var(--dash-transition);
}
.lic-row:hover { background:#f8f9fc; }
.lic-row + .lic-row { border-top:1px solid #f4f5f8; }

.lic-icon {
  width:34px; height:34px; border-radius:10px;
  display:flex; align-items:center; justify-content:center;
  flex-shrink:0;
}
.lic-info { flex:1; min-width:0; }
.lic-name {
  font-size:.82rem; font-weight:600; color:#1a1d2e;
  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.lic-meta { font-size:.72rem; color:#9198a8; margin-top:2px; }
.lic-type {
  flex-shrink:0; text-align:right;
}
.lic-badge {
  display:inline-block; padding:3px 10px; border-radius:6px;
  font-size:.72rem; font-weight:600;
}
.lic-badge-dias   { background:#EEF6FF; color:#1d6ec9; }
.lic-badge-horas  { background:#FFFDE7; color:#b07b00; }
.lic-time { font-size:.7rem; color:#9198a8; display:block; margin-top:2px; }

/* ── Filter tabs ────────────────────────────────── */
.filter-tabs { display:flex; gap:4px; }
.filter-tab {
  padding:4px 12px; border-radius:20px;
  font-size:.72rem; font-weight:600;
  background:transparent; border:1px solid #e4e6ef;
  color:#7e8299; cursor:pointer; transition:var(--dash-transition);
}
.filter-tab.active,
.filter-tab:hover { background:#1d6ec9; color:#fff; border-color:#1d6ec9; }

/* ── Herramienta buttons (compact + colorful) ───── */
.tool-btn {
  display:flex; align-items:center; gap:10px;
  border-radius:10px;
  padding:10px 14px;
  text-decoration:none;
  transition:var(--dash-transition);
  margin-bottom:10px;
  overflow:hidden; position:relative;
  border:none;
}
.tool-btn::after {
  content:''; position:absolute; inset:0;
  background:rgba(0,0,0,0);
  transition:var(--dash-transition);
}
.tool-btn:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,0,0,.18); text-decoration:none; }
.tool-btn:hover::after { background:rgba(0,0,0,.08); }
.tool-btn-icon {
  width:28px; height:28px; border-radius:7px;
  background:rgba(255,255,255,.22);
  display:flex; align-items:center; justify-content:center;
  flex-shrink:0; font-size:.8rem; color:#fff;
}
.tool-btn-label {
  font-size:.78rem; font-weight:700; color:#fff;
  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
  flex:1; min-width:0;
}

/* ── Section heading ────────────────────────────── */
.section-label {
  font-size:.68rem; font-weight:700; text-transform:uppercase;
  letter-spacing:.8px; color:#9198a8; margin-bottom:12px;
  display:flex; align-items:center; gap:8px;
}
.section-label::after { content:''; flex:1; height:1px; background:#f0f1f7; }

/* ── Empty state ────────────────────────────────── */
.empty-state {
  display:flex; flex-direction:column; align-items:center;
  justify-content:center; padding:36px 20px; color:#9198a8;
  text-align:center;
}
.empty-state i { font-size:2.4rem; margin-bottom:10px; opacity:.5; }
.empty-state p { font-size:.82rem; font-weight:500; margin:0; }

/* ── Hide subheader breadcrumb on dashboard ─────── */
#kt_subheader { display:none !important; }

/* ── Responsive ─────────────────────────────────── */
@media (max-width:991px) {
  #kt_content .container-fluid { padding:14px 16px; }
  .dash-welcome { padding:20px 22px; }
  .dash-welcome-title { font-size:1.1rem; }
}
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div class="d-flex flex-column-fluid">
<div class="container-fluid">

<!-- ═══════════════════════════════════════════════ -->
<!-- WELCOME BANNER                                  -->
<!-- ═══════════════════════════════════════════════ -->
<div class="dash-welcome">
    <div class="d-flex align-items-center" style="position:relative;z-index:1">
        <div class="dash-welcome-avatar mr-4">
            <i class="flaticon2-user text-white" style="font-size:1.5rem"></i>
        </div>
        <div class="flex-grow-1 min-w-0">
            <h1 class="dash-welcome-title">Bienvenido, <?php echo $username; ?></h1>
            <div class="dash-welcome-sub d-flex align-items-center flex-wrap" style="gap:10px;margin-top:6px">
                <span><i class="flaticon2-calendar-5 mr-1" style="opacity:.6;font-size:.85rem"></i><?php echo $hoy; ?></span>
                <span class="dash-welcome-badge"><?php echo htmlspecialchars($phase_name); ?></span>
            </div>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════ -->
<!-- HERRAMIENTAS DE GESTIÓN                         -->
<!-- ═══════════════════════════════════════════════ -->
<div class="section-label">Herramientas de Gestión</div>
<div class="row">
    <?php
    // [url, icon, color (hex), label]
    $herramientas = [
        ['manager/student_search/manager/0',   'flaticon-users-1',        '#0284C7','Estudiantes'],
        ['manager/ehc',                        'flaticon2-hospital',       '#DC2626','Enfermería'],
        ["manager/delays/$phase_id",           'flaticon-clock',           '#D97706','Retrasos'],
        ['manager/cartas_contenido',           'flaticon2-file-2',         '#7C3AED','Cartas Contenido'],
        ['manager/incidencias',                'flaticon-warning-sign',    '#B91C1C','Incidencias'],
        ['manager/class_dir',                 'flaticon2-open-text-book', '#4338CA','Ranking de Notas'],
        ['teacher/sections_dir',              'flaticon2-list-3',         '#059669','Listado Cursos'],
        ['manager/self_director',             'flaticon2-box-1',          '#D97706','Autoevaluaciones'],
        ['teacher/teacher_notes',             'flaticon2-writing',        '#DC2626','Centr. Notas'],
        ['manager/directivo',                 'flaticon2-chart2',         '#059669','Estadísticas Académicas'],
        ['manager/evaluation_planner',        'flaticon2-calendar-5',     '#0E7490','Planificador Evaluaciones'],
    ];
    foreach ($herramientas as [$url,$icon,$color,$label]): ?>
    <div class="col-xl-2 col-md-3 col-4">
        <a href="<?php echo base_url().$url; ?>" class="tool-btn" style="background:<?php echo $color; ?>">
            <div class="tool-btn-icon">
                <i class="<?php echo $icon; ?>"></i>
            </div>
            <span class="tool-btn-label"><?php echo $label; ?></span>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!-- ═══════════════════════════════════════════════ -->
<!-- BUSCADOR DE ESTUDIANTES                         -->
<!-- ═══════════════════════════════════════════════ -->
<div class="section-label">Búsqueda de Estudiantes</div>
<div class="panel-card" style="margin-bottom:var(--dash-gap)">
    <!-- Search bar -->
    <div style="padding:16px 20px;border-bottom:1px solid #f0f1f7;position:relative">
        <div style="display:flex;align-items:center;gap:10px;background:#f5f6fa;border-radius:10px;padding:10px 16px">
            <i class="flaticon-search-1" style="color:#9097a8;font-size:.95rem;flex-shrink:0"></i>
            <input id="qs-input" type="text" placeholder="Buscar por apellido paterno..."
                autocomplete="off"
                style="border:none;background:transparent;outline:none;flex:1;font-size:.88rem;color:#3f4254">
            <span id="qs-spinner" style="display:none">
                <i class="flaticon2-reload" style="color:#9097a8;font-size:.8rem"></i>
            </span>
        </div>
        <!-- Dropdown results -->
        <div id="qs-dropdown" style="display:none;position:absolute;left:20px;right:20px;top:calc(100% - 4px);
            background:#fff;border-radius:0 0 10px 10px;box-shadow:0 8px 24px rgba(0,0,0,.1);
            z-index:200;max-height:240px;overflow-y:auto;border:1px solid #f0f1f7;border-top:none"></div>
    </div>

    <!-- Summary card (hidden until student selected) -->
    <div id="qs-card" style="display:none;padding:20px">

        <!-- Student header -->
        <div id="qs-header" style="display:flex;align-items:center;gap:14px;margin-bottom:18px"></div>

        <!-- Stat pills -->
        <div id="qs-stats" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:18px"></div>

        <!-- Tabs nav -->
        <div style="border-bottom:2px solid #f0f1f7;margin-bottom:14px">
            <div style="display:flex;gap:0">
                <?php foreach(['notas'=>'Notas','absences'=>'Inasistencias','delays'=>'Retrasos','licenses'=>'Licencias','incidencias'=>'Incidencias'] as $k=>$label): ?>
                <button class="qs-tab" data-tab="<?php echo $k; ?>"
                    style="border:none;background:none;padding:8px 14px;font-size:.78rem;font-weight:700;
                    color:#9097a8;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:all .15s">
                    <?php echo $label; ?> <span class="qs-tab-cnt" id="qs-cnt-<?php echo $k; ?>" style="font-size:.7rem;background:#f0f1f7;border-radius:10px;padding:1px 6px;margin-left:3px">0</span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tab panels -->
        <div id="qs-panel-notas" class="qs-panel" style="display:none"></div>
        <div id="qs-panel-absences" class="qs-panel" style="display:none"></div>
        <div id="qs-panel-delays" class="qs-panel" style="display:none"></div>
        <div id="qs-panel-licenses" class="qs-panel" style="display:none"></div>
        <div id="qs-panel-incidencias" class="qs-panel" style="display:none"></div>
    </div>
</div>

<script>
(function(){
    const BASE = '<?php echo base_url(); ?>';
    const input = document.getElementById('qs-input');
    const dropdown = document.getElementById('qs-dropdown');
    const spinner  = document.getElementById('qs-spinner');
    const card     = document.getElementById('qs-card');
    let debounce;

    // ── Search debounce ──────────────────────────────
    input.addEventListener('input', function(){
        clearTimeout(debounce);
        const q = this.value.trim();
        if (q.length < 2) { dropdown.style.display='none'; return; }
        spinner.style.display='inline';
        debounce = setTimeout(() => {
            fetch(BASE+'manager/student_search_ajax/'+encodeURIComponent(q))
                .then(r=>r.json()).then(renderDropdown).finally(()=>spinner.style.display='none');
        }, 350);
    });

    document.addEventListener('click', function(e){
        if (!e.target.closest('#qs-input') && !e.target.closest('#qs-dropdown'))
            dropdown.style.display = 'none';
    });

    function renderDropdown(data){
        dropdown.innerHTML = '';
        if (!data.length) {
            dropdown.innerHTML = '<div style="padding:12px 16px;color:#9097a8;font-size:.82rem">Sin resultados</div>';
        } else {
            data.forEach(s => {
                const d = document.createElement('div');
                d.style.cssText = 'padding:10px 16px;cursor:pointer;border-bottom:1px solid #f8f8f8;font-size:.83rem;display:flex;justify-content:space-between;align-items:center';
                d.innerHTML = '<span style="font-weight:600;color:#3f4254">'+s.nombre+'</span>'
                            + '<span style="color:#9097a8;font-size:.76rem">'+s.completo+'</span>';
                d.addEventListener('mouseenter',()=>d.style.background='#f5f6fa');
                d.addEventListener('mouseleave',()=>d.style.background='');
                d.addEventListener('click',()=>{ input.value=s.nombre; dropdown.style.display='none'; loadSummary(s.student_id); });
                dropdown.appendChild(d);
            });
        }
        dropdown.style.display = 'block';
    }

    // ── Load full summary ────────────────────────────
    function loadSummary(id){
        card.style.display = 'none';
        fetch(BASE+'manager/student_summary/'+id)
            .then(r=>r.json()).then(renderCard);
    }

    function grade_color(v){ return v>=51?'#1BC5BD':v>=35?'#FFA800':'#F64E60'; }

    function small_table(headers, rows, emptyMsg){
        if (!rows.length) return '<p style="color:#9097a8;font-size:.8rem;padding:6px 0">'+emptyMsg+'</p>';
        let h = '<table style="width:100%;font-size:.78rem;border-collapse:collapse">'
              + '<thead><tr>'+headers.map(h=>'<th style="text-align:left;padding:5px 8px;color:#9097a8;font-weight:600;border-bottom:1px solid #f0f1f7">'+h+'</th>').join('')+'</tr></thead><tbody>';
        rows.forEach(r=>{ h+='<tr>'+r.map(c=>'<td style="padding:5px 8px;border-bottom:1px solid #f8f9fa;color:#3f4254">'+c+'</td>').join('')+'</tr>'; });
        return h+'</tbody></table>';
    }

    function renderCard(d){
        if (d.error) return;
        const i = d.info;
        const sexLabel = i.sex==='M'?'Varón':'Mujer';
        const sexColor = i.sex==='M'?'#1d6ec9':'#c026d3';
        const ini = i.nombre.trim()[0].toUpperCase();

        // Header
        document.getElementById('qs-header').innerHTML =
            '<div style="width:46px;height:46px;border-radius:12px;background:#EEF6FF;color:#1d6ec9;'
          + 'display:flex;align-items:center;justify-content:center;font-size:1.3rem;font-weight:800;flex-shrink:0">'+ini+'</div>'
          + '<div style="flex:1;min-width:0">'
          +   '<div style="font-size:1rem;font-weight:700;color:#3f4254;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">'+i.nombre+'</div>'
          +   '<div style="font-size:.78rem;color:#9097a8;margin-top:2px">'+i.completo
          +     ' &nbsp;·&nbsp; <span style="color:'+sexColor+';font-weight:600">'+sexLabel+'</span>'
          +     (i.email ? ' &nbsp;·&nbsp; '+i.email : '')
          +   '</div>'
          + '</div>'
          + '<a href="'+BASE+'manager/student_search/manager/0" style="font-size:.75rem;color:#3699FF;font-weight:600;white-space:nowrap">Ver perfil</a>';

        // Stats
        const stats = [
            { label:'Inasistencias', val:d.absences.length, color:'#F64E60', bg:'#FFF5F8' },
            { label:'Retrasos',      val:d.delays.length,   color:'#D97706', bg:'#FFFDE7' },
            { label:'Licencias',     val:d.licenses.length, color:'#8950FC', bg:'#F3E5FF' },
            { label:'Incidencias',   val:d.incidencias.length, color:'#0284C7', bg:'#EFF8FF' },
        ];
        document.getElementById('qs-stats').innerHTML = stats.map(s=>
            '<div style="background:'+s.bg+';border-radius:8px;padding:8px 14px;display:flex;align-items:center;gap:8px">'
          + '<span style="font-size:1.2rem;font-weight:800;color:'+s.color+'">'+s.val+'</span>'
          + '<span style="font-size:.72rem;font-weight:600;color:'+s.color+'">'+s.label+'</span>'
          + '</div>'
        ).join('');

        // Tab counts
        const counts = { notas:d.grades.length, absences:d.absences.length, delays:d.delays.length, licenses:d.licenses.length, incidencias:d.incidencias.length };
        Object.keys(counts).forEach(k=>{ document.getElementById('qs-cnt-'+k).textContent=counts[k]; });

        // Panels content
        document.getElementById('qs-panel-notas').innerHTML = small_table(
            ['Materia','Nota'],
            d.grades.map(g=>[g.name, '<span style="font-weight:700;color:'+grade_color(+g.obtained_mark)+'">'+g.obtained_mark+'</span>']),
            'Sin notas registradas'
        );
        document.getElementById('qs-panel-absences').innerHTML = small_table(
            ['Materia / Docente','Fecha'],
            d.absences.map(a=>[a.doc_materia, a.fecha||a.ausencia_id]),
            'Sin inasistencias registradas'
        );
        document.getElementById('qs-panel-delays').innerHTML = small_table(
            ['Fecha','Minutos tarde'],
            d.delays.map(dl=>[dl.date_class, dl.tarde_con+' min']),
            'Sin retrasos registrados'
        );
        document.getElementById('qs-panel-licenses').innerHTML = small_table(
            ['Tipo','Motivo','Inicio','Fin'],
            d.licenses.map(l=>[l.tipo, l.detalle, l.inicio, l.fin]),
            'Sin licencias registradas'
        );
        document.getElementById('qs-panel-incidencias').innerHTML = small_table(
            ['Tipo','Materia','Fecha','Observación'],
            d.incidencias.map(f=>[f.tipo, f.materia, f.fecha, f.observation||'—']),
            'Sin incidencias registradas'
        );

        card.style.display = 'block';
        openTab('notas');
    }

    // ── Tabs ─────────────────────────────────────────
    document.querySelectorAll('.qs-tab').forEach(btn=>{
        btn.addEventListener('click',()=>openTab(btn.dataset.tab));
    });
    function openTab(name){
        document.querySelectorAll('.qs-tab').forEach(b=>{
            const active = b.dataset.tab===name;
            b.style.color  = active?'#3699FF':'#9097a8';
            b.style.borderBottomColor = active?'#3699FF':'transparent';
        });
        document.querySelectorAll('.qs-panel').forEach(p=>p.style.display='none');
        document.getElementById('qs-panel-'+name).style.display='block';
    }
})();
</script>

<!-- ═══════════════════════════════════════════════ -->
<!-- TOP INASISTENCIAS  +  LICENCIAS HOY             -->
<!-- ═══════════════════════════════════════════════ -->
<div class="row">

    <!-- ── Inasistencias de Hoy ── -->
    <div class="col-xl-6">
        <div class="panel-card">
            <div class="panel-header">
                <div>
                    <p class="panel-title">
                        <i class="flaticon2-user-outline-symbol mr-2" style="color:#F64E60;font-size:.95rem"></i>
                        Inasistencias de Hoy
                    </p>
                    <p class="panel-subtitle"><?php echo $hoy; ?></p>
                </div>
                <?php if (!empty($top_ausencias)): ?>
                <span style="background:#FFF5F8;color:#F64E60;border-radius:8px;padding:4px 10px;font-size:.72rem;font-weight:700;">
                    <?php echo count($top_ausencias); ?> ausente<?php echo count($top_ausencias)!=1?'s':''; ?>
                </span>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <?php if (empty($top_ausencias)): ?>
                <div class="empty-state">
                    <i class="flaticon2-check-mark text-success"></i>
                    <p>Sin inasistencias registradas hoy</p>
                </div>
                <?php else: ?>
                <div class="panel-body-scroll" style="max-height:340px">
                    <?php foreach ($top_ausencias as $est):
                        $ini   = strtoupper(substr(trim($est['alumno']),0,1));
                        $nivel = strpos(strtolower($est['grado']),'secundaria')!==false;
                    ?>
                    <div class="student-row">
                        <!-- Avatar -->
                        <div class="s-avatar" style="background:#FFF5F8;color:#F64E60">
                            <?php echo $ini; ?>
                        </div>
                        <!-- Info -->
                        <div class="s-info">
                            <div class="s-name"><?php echo htmlspecialchars($est['alumno']); ?></div>
                            <div class="s-meta">
                                <span style="background:<?php echo $nivel?'#EEF6FF':'#E8FFF9';?>;color:<?php echo $nivel?'#1d6ec9':'#0a7c6d';?>;
                                    padding:1px 7px;border-radius:4px;font-weight:600;font-size:.68rem">
                                    <?php echo $nivel?'Sec.':'Prim.'; ?>
                                </span>
                                <span style="margin-left:4px"><?php echo htmlspecialchars($est['grado']); ?> · <?php echo htmlspecialchars($est['seccion']); ?></span>
                                <?php if ($est['materias_afectadas'] > 0): ?>
                                <span style="margin-left:4px;color:#c0c4d4"><?php echo $est['materias_afectadas']; ?> materia<?php echo $est['materias_afectadas']>1?'s':''; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Badge -->
                        <div class="s-badge">
                            <div class="s-badge-num" style="background:#FFF5F8;color:#F64E60">
                                <?php echo $est['total_ausencias']; ?>
                            </div>
                            <span class="s-severity" style="color:#F64E60">Ausente</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ── Licencias Hoy ── -->
    <div class="col-xl-6">
        <div class="panel-card">
            <div class="panel-header">
                <div>
                    <p class="panel-title">
                        <i class="flaticon2-calendar-2 mr-2" style="color:#FFA800;font-size:.95rem"></i>
                        Licencias Hoy
                    </p>
                    <p class="panel-subtitle">
                        <?php if ($n_lic > 0): ?>
                            <span style="background:#FFFDE7;color:#b07b00;padding:2px 8px;border-radius:5px;font-weight:700">
                                <?php echo $n_lic; ?> licencia<?php echo $n_lic>1?'s':''; ?>
                            </span>
                            &nbsp;<?php echo date('d/m/Y'); ?>
                        <?php else: ?>
                            <span style="background:#E8FFF9;color:#0a7c6d;padding:2px 8px;border-radius:5px;font-weight:700">Sin licencias hoy</span>
                        <?php endif; ?>
                    </p>
                </div>
                <?php if (!empty($today_licenses)): ?>
                <div class="filter-tabs" id="lic-filter-tabs">
                    <button class="filter-tab active" onclick="filtrarLicencias('todos',this)">Todos</button>
                    <button class="filter-tab" onclick="filtrarLicencias('primaria',this)">Primaria</button>
                    <button class="filter-tab" onclick="filtrarLicencias('secundaria',this)">Secundaria</button>
                </div>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <?php if (empty($today_licenses)): ?>
                <div class="empty-state">
                    <i class="flaticon2-check-mark text-success"></i>
                    <p>No hay licencias activas hoy</p>
                </div>
                <?php else: ?>
                <div id="lic-empty" style="display:none">
                    <div class="empty-state">
                        <i class="flaticon2-check-mark text-success"></i>
                        <p>Sin licencias en este nivel</p>
                    </div>
                </div>
                <div id="lic-list" class="panel-body-scroll" style="max-height:340px">
                    <?php foreach ($today_licenses as $i => $lic):
                        $nivel_lic = stripos($lic['grado'],'Secundaria')!==false ? 'secundaria' : 'primaria';
                        $es_hora   = $lic['tipo'] === 'Horas';
                    ?>
                    <div class="lic-row" data-nivel="<?php echo $nivel_lic; ?>">
                        <!-- Icon -->
                        <div class="lic-icon" style="background:<?php echo $es_hora?'#FFFDE7':'#EEF6FF'; ?>">
                            <i class="<?php echo $es_hora?'flaticon-clock':'flaticon2-calendar-2'; ?>"
                               style="font-size:1rem;color:<?php echo $es_hora?'#FFA800':'#1d6ec9'; ?>"></i>
                        </div>
                        <!-- Info -->
                        <div class="lic-info">
                            <div class="lic-name"><?php echo htmlspecialchars($lic['alumno']); ?></div>
                            <div class="lic-meta">
                                <span style="background:<?php echo $nivel_lic==='secundaria'?'#EEF6FF':'#E8FFF9';?>;
                                    color:<?php echo $nivel_lic==='secundaria'?'#1d6ec9':'#0a7c6d';?>;
                                    padding:1px 6px;border-radius:4px;font-size:.68rem;font-weight:600">
                                    <?php echo $nivel_lic==='secundaria'?'Sec.':'Prim.'; ?>
                                </span>
                                <span style="margin-left:4px"><?php echo htmlspecialchars($lic['grado']); ?> · <?php echo htmlspecialchars($lic['seccion']); ?></span>
                            </div>
                        </div>
                        <!-- Reason (hidden on xs) -->
                        <div class="d-none d-lg-block flex-shrink-0 mr-2" style="max-width:100px">
                            <span style="font-size:.72rem;color:#7e8299;font-weight:500" class="text-truncate d-block">
                                <?php echo htmlspecialchars($lic['motivo']); ?>
                            </span>
                        </div>
                        <!-- Type + time -->
                        <div class="lic-type">
                            <span class="lic-badge <?php echo $es_hora?'lic-badge-horas':'lic-badge-dias'; ?>">
                                <?php echo $lic['tipo']; ?>
                            </span>
                            <span class="lic-time">
                                <?php if ($es_hora): ?>
                                    <?php echo substr($lic['hora_inicio'],0,5); ?>–<?php echo substr($lic['hora_fin'],0,5); ?>
                                <?php else: ?>
                                    <?php echo date('d/m', strtotime($lic['fecha_inicio'])); ?>
                                    <?php if ($lic['fecha_fin'] !== $lic['fecha_inicio']): ?>
                                        –<?php echo date('d/m', strtotime($lic['fecha_fin'])); ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>


</div><!-- /container-fluid -->
</div><!-- /d-flex -->
</div><!-- /content -->

<?php if (!empty($today_licenses)): ?>
<script>
function filtrarLicencias(nivel, btn) {
    document.querySelectorAll('#lic-filter-tabs .filter-tab').forEach(function(b) {
        b.classList.remove('active');
    });
    btn.classList.add('active');

    var shown = 0;
    document.querySelectorAll('#lic-list .lic-row').forEach(function(row) {
        var match = nivel === 'todos' || row.dataset.nivel === nivel;
        row.style.display = match ? '' : 'none';
        if (match) shown++;
    });

    var empty = document.getElementById('lic-empty');
    var list  = document.getElementById('lic-list');
    if (shown === 0) {
        empty.style.display = '';
        list.style.display  = 'none';
    } else {
        empty.style.display = 'none';
        list.style.display  = '';
    }
}
</script>
<?php endif; ?>
