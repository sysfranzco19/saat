<?php
$total        = (int)$total_alumnos;
$asis         = $asis_hoy;
$registrados  = (int)$asis['registrados'];
$sin_registro = max(0, $total - $registrados);
?>
<style>
:root {
  --dash-radius: 12px;
  --dash-shadow: 0 2px 12px rgba(0,0,0,0.07);
  --dash-shadow-hover: 0 6px 24px rgba(0,0,0,0.13);
  --dash-transition: all .2s cubic-bezier(.4,0,.2,1);
  --dash-gap: 20px;
}

/* ── Welcome banner ─────────────────────────────── */
.dash-welcome {
  background: #2b3350;
  border-radius: var(--dash-radius);
  padding: 24px 30px;
  position: relative; overflow: hidden;
  margin-bottom: var(--dash-gap);
  box-shadow: var(--dash-shadow);
}
.dash-welcome-avatar {
  width:48px; height:48px; border-radius:12px; background:rgba(255,255,255,.12);
  display:flex; align-items:center; justify-content:center; flex-shrink:0;
}
.dash-welcome-title { font-size:1.25rem; font-weight:700; color:#fff; line-height:1.2; margin:0; }
.dash-welcome-sub   { font-size:.8rem; color:rgba(255,255,255,.65); margin-top:6px; }
.dash-welcome-badge { background:rgba(255,255,255,.12); color:#fff; border-radius:20px; padding:4px 14px; font-size:.75rem; font-weight:600; }

/* ── Section heading ────────────────────────────── */
.section-label {
  font-size:.68rem; font-weight:700; text-transform:uppercase; letter-spacing:.8px;
  color:#9198a8; margin:24px 0 12px; display:flex; align-items:center; gap:8px;
}
.section-label::after { content:''; flex:1; height:1px; background:#f0f1f7; }
.section-label:first-of-type { margin-top:0; }


/* ── Panel cards ────────────────────────────────── */
.panel-card   { background:#fff; border-radius:var(--dash-radius); box-shadow:var(--dash-shadow); border:1px solid rgba(0,0,0,.05); margin-bottom:var(--dash-gap); display:flex; flex-direction:column; height:100%; }
.panel-header { padding:18px 22px 12px; border-bottom:1px solid #f4f5f8; display:flex; align-items:center; justify-content:space-between; flex-shrink:0; }
.panel-title    { font-size:.95rem; font-weight:700; color:#1a1d2e; margin:0; }
.panel-subtitle { font-size:.75rem; color:#9198a8; margin-top:2px; }
.panel-body   { padding:14px 22px 18px; flex:1; }

.lic-row { display:flex; align-items:center; gap:12px; padding:9px 4px; border-radius:8px; transition:var(--dash-transition); }
.lic-row:hover { background:#f8f9fc; }
.lic-row + .lic-row { border-top:1px solid #f4f5f8; }
.lic-icon { width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.85rem; }
.lic-info { flex:1; min-width:0; }
.lic-name { font-size:.82rem; font-weight:600; color:#1a1d2e; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.lic-meta { font-size:.72rem; color:#9198a8; margin-top:1px; }
.lic-badge { display:inline-block; padding:3px 10px; border-radius:6px; font-size:.72rem; font-weight:600; flex-shrink:0; }

.empty-state { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:26px 20px; color:#9198a8; text-align:center; }
.empty-state i { font-size:2rem; margin-bottom:8px; opacity:.5; }
.empty-state p { font-size:.8rem; font-weight:500; margin:0; }

.panel-footer { padding:12px 22px; border-top:1px solid #f4f5f8; }
.panel-footer a { font-weight:700; font-size:.82rem; text-decoration:none; }
.panel-footer a:hover { text-decoration:underline; }

.toggle-chevron { transition:transform .2s; color:#9198a8; }
.toggle-chevron.open { transform:rotate(90deg); }
</style>

<div class="d-flex flex-column-fluid">
<div class="container-fluid">

    <!-- Welcome banner -->
    <div class="dash-welcome">
        <div class="d-flex align-items-center flex-wrap" style="position:relative;z-index:1;gap:16px;">
            <div class="dash-welcome-avatar">
                <i class="flaticon2-open-text-book text-white" style="font-size:1.4rem"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <h1 class="dash-welcome-title">📚 Panel General — Primaria</h1>
                <div class="dash-welcome-sub d-flex align-items-center flex-wrap" style="gap:10px;">
                    <span>3ro a 6to de Primaria</span>
                    <span class="dash-welcome-badge"><?= esc($phase_name) ?></span>
                    <span><?= date('d-m-Y', strtotime($phase_ini)) ?> → <?= date('d-m-Y', strtotime($phase_fin)) ?></span>
                    <span><?= $total ?> alumnos</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendientes de aprobar -->
    <div class="section-label">Pendientes de Aprobar</div>
    <div class="row">
        <div class="col-md-3 mb-4">
            <a href="<?= base_url('secretary/prim_licencias') ?>" class="d-flex align-items-center bg-light-warning rounded p-5 h-100 text-decoration-none">
                <i class="flaticon2-calendar-2 text-warning icon-xl mr-4"></i>
                <div class="d-flex flex-column">
                    <span class="font-weight-bolder text-dark font-size-h2 mb-1"><?= $pend_dia_corta ?></span>
                    <span class="text-warning font-weight-bold font-size-sm">Por Día (≤3d)</span>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-4">
            <a href="<?= base_url('secretary/prim_licencias') ?>" class="d-flex align-items-center bg-light-danger rounded p-5 h-100 text-decoration-none">
                <i class="flaticon2-file-2 text-danger icon-xl mr-4"></i>
                <div class="d-flex flex-column">
                    <span class="font-weight-bolder text-dark font-size-h2 mb-1"><?= $pend_dia_larga ?></span>
                    <span class="text-danger font-weight-bold font-size-sm">Por Día (&gt;3d)</span>
                    <span class="text-muted font-size-xs">Requieren carta de solicitud</span>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-4">
            <a href="<?= base_url('secretary/prim_licencias') ?>" class="d-flex align-items-center bg-light-info rounded p-5 h-100 text-decoration-none">
                <i class="flaticon-clock text-info icon-xl mr-4"></i>
                <div class="d-flex flex-column">
                    <span class="font-weight-bolder text-dark font-size-h2 mb-1"><?= $pend_periodo ?></span>
                    <span class="text-info font-weight-bold font-size-sm">Por Período</span>
                </div>
            </a>
        </div>
        <div class="col-md-3 mb-4">
            <a href="<?= base_url('secretary/prim_cambio_recojo') ?>" class="d-flex align-items-center rounded p-5 h-100 text-decoration-none" style="background:#FDEEE4;">
                <i class="fas fa-bus icon-xl mr-4" style="color:#c2600b;"></i>
                <div class="d-flex flex-column">
                    <span class="font-weight-bolder text-dark font-size-h2 mb-1"><?= $recojo_pend_count ?></span>
                    <span class="font-weight-bold font-size-sm" style="color:#c2600b;">Cambio de Recojo</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Estado general -->
    <div class="section-label">Estado General</div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <a href="<?= base_url('secretary/prim_ausencias?todos=1') ?>" class="d-flex align-items-center bg-light-primary rounded p-5 h-100 text-decoration-none">
                <i class="flaticon-warning-sign text-primary icon-xl mr-4"></i>
                <div class="d-flex flex-column">
                    <span class="font-weight-bolder text-dark font-size-h2 mb-1"><?= $cnt_alerta + $cnt_limite ?></span>
                    <span class="text-primary font-weight-bold font-size-sm">Alumnos en Alerta/Límite de Cupo</span>
                    <span class="text-muted font-size-xs"><?= $cnt_alerta ?> en alerta (6-8d) · <?= $cnt_limite ?> en límite (9+d)</span>
                </div>
            </a>
        </div>
        <div class="col-md-6 mb-4">
            <a href="<?= base_url('secretary/prim_ausencias') ?>" class="d-flex align-items-center bg-light-danger rounded p-5 h-100 text-decoration-none">
                <i class="flaticon2-cancel text-danger icon-xl mr-4"></i>
                <div class="d-flex flex-column">
                    <span class="font-weight-bolder text-dark font-size-h2 mb-1"><?= $sin_justificar ?></span>
                    <span class="text-danger font-weight-bold font-size-sm">Ausencias sin Justificar</span>
                    <span class="text-muted font-size-xs">Faltas del trimestre sin ninguna licencia</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Detalle del día -->
    <div class="section-label">Detalle del Día</div>
    <div class="row">

        <!-- Asistencia por curso -->
        <div class="col-md-6">
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Asistencia por Curso</h3>
                        <div class="panel-subtitle"><?= $registrados ?>/<?= $total ?> alumnos registrados hoy</div>
                    </div>
                    <div class="d-flex align-items-center" style="gap:6px;">
                        <span class="font-size-h4 font-weight-bolder" style="color:#1d6ec9;"><?= $cursos_completos ?></span>
                        <span class="text-muted font-size-sm">de <?= $cursos_total ?> cursos</span>
                        <?php if (!empty($cursos_pendientes)): ?>
                        <button type="button" class="btn btn-icon btn-sm" onclick="togglePanel('lista_cursos_pendientes','chev_cursos')">
                            <i class="fas fa-chevron-right toggle-chevron" id="chev_cursos"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="d-flex flex-wrap" style="gap:6px;">
                        <span class="lic-badge" style="background:#E8FFF9;color:#0a7c6d;"><?= (int)$asis['presentes'] ?> presentes</span>
                        <span class="lic-badge" style="background:#FFF5F8;color:#d63c52;"><?= (int)$asis['ausentes'] ?> ausentes</span>
                        <span class="lic-badge" style="background:#EEF6FF;color:#1d6ec9;"><?= (int)$asis['con_licencia'] ?> con licencia</span>
                        <span class="lic-badge" style="background:#FFFDE7;color:#b07b00;"><?= (int)$asis['retrasos'] ?> retrasos</span>
                        <span class="lic-badge" style="background:#f5f5f5;color:#9198a8;"><?= $sin_registro ?> sin registro</span>
                    </div>
                    <?php if (!empty($cursos_pendientes)): ?>
                    <div id="lista_cursos_pendientes" style="display:none; margin-top:12px;">
                        <?php foreach ($cursos_pendientes as $cp): ?>
                        <div class="lic-row">
                            <div class="lic-icon" style="background:#FFFDE7;color:#b07b00;"><i class="flaticon-clock"></i></div>
                            <div class="lic-info">
                                <div class="lic-name"><?= esc($cp['nick_name']) ?></div>
                                <div class="lic-meta"><?= (int)$cp['registrados'] ?>/<?= (int)$cp['total_alumnos'] ?> alumnos registrados</div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="panel-footer">
                    <a href="<?= base_url('secretary/prim_asistencia') ?>" style="color:#1d6ec9;">Ver Asistencia del Día →</a>
                </div>
            </div>
        </div>

        <!-- Cambios de recojo de hoy -->
        <div class="col-md-6">
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Cambios de Recojo Hoy</h3>
                        <div class="panel-subtitle">Avisos aprobados para la fecha actual</div>
                    </div>
                    <div class="d-flex align-items-center" style="gap:6px;">
                        <span class="font-size-h4 font-weight-bolder" style="color:#c2600b;"><?= $recojo_hoy_count ?></span>
                        <?php if (!empty($recojo_hoy)): ?>
                        <button type="button" class="btn btn-icon btn-sm" onclick="togglePanel('lista_recojo_hoy','chev_recojo')">
                            <i class="fas fa-chevron-right toggle-chevron" id="chev_recojo"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="panel-body">
                    <?php if (empty($recojo_hoy)): ?>
                        <div class="empty-state">
                            <i class="fas fa-bus"></i>
                            <p>Sin cambios de recojo registrados para hoy.</p>
                        </div>
                    <?php else: ?>
                        <div id="lista_recojo_hoy" style="display:none;">
                            <?php foreach ($recojo_hoy as $r): ?>
                            <div class="lic-row">
                                <div class="lic-icon" style="background:#FDEEE4;color:#c2600b;"><i class="fas fa-bus"></i></div>
                                <div class="lic-info">
                                    <div class="lic-name"><?= esc($r['student']) ?> <span class="text-muted font-weight-normal">(<?= esc($r['nick_name']) ?>)</span></div>
                                    <div class="lic-meta">
                                        <?php if ($r['tipo'] == 1): ?>
                                            Recoge: <strong><?= esc($r['persona_nombre']) ?></strong>
                                        <?php elseif ($r['tipo'] == 2): ?>
                                            Sin transporte escolar
                                        <?php else: ?>
                                            Otro<?= !empty($r['detalle']) ? ': ' . esc($r['detalle']) : '' ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="panel-footer">
                    <a href="<?= base_url('secretary/prim_cambio_recojo') ?>" style="color:#c2600b;">Ver Cambio de Recojo →</a>
                </div>
            </div>
        </div>

    </div>

</div>
</div>

<script>
function togglePanel(listId, chevId) {
    const list = document.getElementById(listId);
    const chev = document.getElementById(chevId);
    if (!list) return;
    const open = list.style.display !== 'none';
    list.style.display = open ? 'none' : '';
    if (chev) chev.classList.toggle('open', !open);
}
</script>
