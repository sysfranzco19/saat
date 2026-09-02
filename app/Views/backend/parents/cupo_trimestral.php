<?php
$total        = $cupo['total'];
$restante     = $cupo['cupo_restante'];
$pct          = min(100, round($total / 9 * 100));

if ($total >= 9)      { $color = 'danger';  $colorHex = '#f64e60'; $label = 'LÍMITE ALCANZADO'; }
elseif ($total >= 6)  { $color = 'warning'; $colorHex = '#ffa800'; $label = 'EN ALERTA';        }
else                  { $color = 'success'; $colorHex = '#1bc5bd'; $label = 'DENTRO DEL CUPO';  }

$tiene_alerta6  = false;
$tiene_limite9  = false;
foreach ($alertas as $a) {
    if ($a['tipo'] === 'alerta6')  $tiene_alerta6 = true;
    if ($a['tipo'] === 'limite9')  $tiene_limite9 = true;
}
?>

<style>
.cupo-circle {
    width: 150px; height: 150px;
    border-radius: 50%;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    font-weight: 800; margin: 0 auto;
    border: 8px solid;
}
.cupo-circle.success { border-color: #1bc5bd; color: #1bc5bd; }
.cupo-circle.warning { border-color: #ffa800; color: #ffa800; }
.cupo-circle.danger  { border-color: #f64e60; color: #f64e60; }
.cupo-number { font-size: 2.8rem; line-height: 1; }
.cupo-denom  { font-size: 1rem; color: #b5b5c3; font-weight: 500; }
.breakdown-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 20px;
    font-weight: 600; font-size: 0.9rem; margin: 4px;
}
.pill-absent  { background: #fff0f0; color: #f64e60; }
.pill-license { background: #fff4de; color: #ffa800; }
.pill-salida  { background: #f0f0ff; color: #6610f2; }
.pill-excep   { background: #e8fff3; color: #1bc5bd; }
</style>

<div class="d-flex flex-column-fluid">
<div class="container-fluid">

    <!-- Encabezado del estudiante -->
    <div class="card card-custom shadow-sm mb-6">
        <div class="card-body d-flex align-items-center py-5">
            <div class="symbol symbol-60 symbol-circle symbol-light-primary mr-5">
                <span class="symbol-label font-size-h2 font-weight-bold text-primary">
                    <?= strtoupper(mb_substr($student['name'], 0, 1)) ?>
                </span>
            </div>
            <div>
                <h4 class="font-weight-bolder mb-1"><?= esc($student['student']) ?></h4>
                <span class="text-muted font-size-sm"><?= esc($student['completo']) ?> &mdash; <?= esc($phase_name) ?></span>
            </div>
            <a href="<?= base_url('parents/enrolled_children') ?>" class="btn btn-light btn-sm ml-auto">
                &larr; Mis Hijos
            </a>
        </div>
    </div>

    <div class="row">

        <!-- Panel principal de cupo -->
        <div class="col-md-4">
            <div class="card card-custom shadow-sm mb-6">
                <div class="card-header border-0 pt-6 pb-0">
                    <h5 class="card-label font-weight-bolder">Cupo Trimestral</h5>
                </div>
                <div class="card-body text-center pt-4">

                    <div class="cupo-circle <?= $color ?> mb-5">
                        <span class="cupo-number"><?= number_format($total, 1) ?></span>
                        <span class="cupo-denom">de 9 días</span>
                    </div>

                    <div class="progress mb-3" style="height:10px; border-radius:5px;">
                        <div class="progress-bar bg-<?= $color ?>"
                             style="width:<?= $pct ?>%; border-radius:5px;" role="progressbar"></div>
                    </div>

                    <span class="badge badge-<?= $color ?> badge-pill px-4 py-2 font-size-sm font-weight-bolder">
                        <?= $label ?>
                    </span>

                    <div class="mt-5 text-center">
                        <span class="font-size-lg font-weight-bold text-<?= $color ?>">
                            <?= number_format($restante, 1) ?> día(s) restante(s)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Alertas activas -->
            <?php if ($tiene_limite9): ?>
            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-ban fa-lg mr-3 text-danger"></i>
                <div>
                    <strong>Límite alcanzado (Art. 9)</strong><br>
                    <small>Las evaluaciones no rendidas a partir del día 10 quedan con nota 1 sin recuperación.</small>
                </div>
            </div>
            <?php elseif ($tiene_alerta6): ?>
            <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-exclamation-triangle fa-lg mr-3 text-warning"></i>
                <div>
                    <strong>Alerta activada (Art. 8)</strong><br>
                    <small>Se convocó al consejero/a para reunión informativa con la familia.</small>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Desglose y umbrales -->
        <div class="col-md-8">
            <div class="card card-custom shadow-sm mb-6">
                <div class="card-header border-0 pt-6 pb-0">
                    <h5 class="card-label font-weight-bolder">Desglose de días consumidos</h5>
                </div>
                <div class="card-body pt-4">
                    <div class="mb-5">
                        <span class="breakdown-pill pill-absent">
                            <i class="fas fa-user-times"></i> Ausencias sin licencia: <?= $cupo['ausencias_puras'] ?>
                        </span>
                        <span class="breakdown-pill pill-license">
                            <i class="fas fa-file-alt"></i> Licencias por días: <?= $cupo['dias_licencia'] ?>
                        </span>
                        <span class="breakdown-pill pill-salida">
                            <i class="fas fa-door-open"></i> Salidas anticipadas: <?= number_format($cupo['salidas_anticipadas'], 1) ?>
                        </span>
                        <?php if ($cupo['excepciones'] > 0): ?>
                        <span class="breakdown-pill pill-excep">
                            <i class="fas fa-shield-alt"></i> Excepciones (no consumen): <?= $cupo['excepciones'] ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Umbrales -->
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr class="text-muted font-size-sm text-uppercase">
                                    <th>Umbral</th><th>Días</th><th>Efecto</th><th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-bell text-warning mr-2"></i><strong>Alerta</strong></td>
                                    <td>6 días</td>
                                    <td class="text-muted font-size-sm">Convocatoria consejero/a + reunión familia</td>
                                    <td>
                                        <?php if ($total >= 6): ?>
                                            <span class="badge badge-warning">Activada</span>
                                        <?php else: ?>
                                            <span class="badge badge-light text-muted">Pendiente (faltan <?= max(0, 6-$total) ?> días)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-ban text-danger mr-2"></i><strong>Límite máximo</strong></td>
                                    <td>9 días</td>
                                    <td class="text-muted font-size-sm">Pierde derecho a recuperar evaluaciones</td>
                                    <td>
                                        <?php if ($total >= 9): ?>
                                            <span class="badge badge-danger">Alcanzado</span>
                                        <?php else: ?>
                                            <span class="badge badge-light text-muted">Pendiente (faltan <?= max(0, 9-$total) ?> días)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-light-primary rounded p-4 mt-3">
                        <p class="mb-1 font-weight-bold text-primary">
                            <i class="fas fa-info-circle mr-2 text-primary"></i>Recuerde (Art. 3 y 6)
                        </p>
                        <p class="mb-0 text-muted font-size-sm">
                            Toda ausencia consume cupo, <strong>independientemente del motivo</strong>. Solo se exceptúan
                            internaciones hospitalarias, accidentes graves, fallecimiento de familiar, embarazo/maternidad
                            y suspensiones decretadas por autoridad educativa.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Historial de licencias -->
            <?php if (!empty($licencias)): ?>
            <div class="card card-custom shadow-sm mb-6">
                <div class="card-header border-0 pt-6 pb-2">
                    <h5 class="card-label font-weight-bolder">Licencias del trimestre</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="text-muted font-size-xs text-uppercase">
                                <tr>
                                    <th>Fecha</th><th>Tipo</th><th>Motivo</th>
                                    <th>Período</th><th>Cupo</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($licencias as $lic): ?>
                                <tr>
                                    <td class="font-size-sm"><?= date('d-m-Y', strtotime($lic['fecha_solicitud'])) ?></td>
                                    <td><span class="badge badge-light-primary"><?= esc($lic['tipo']) ?></span></td>
                                    <td class="font-size-sm"><?= esc($lic['motivo']) ?></td>
                                    <td class="font-size-sm">
                                        <?php if ($lic['inicio']): ?>
                                            <?= $lic['inicio'] ?> → <?= $lic['fin'] ?>
                                            (<?= $lic['cantidad_dias'] ?> día<?= $lic['cantidad_dias'] != 1 ? 's' : '' ?>)
                                        <?php elseif ($lic['fecha_periodo']): ?>
                                            <?= date('d-m-Y', strtotime($lic['fecha_periodo'])) ?>
                                            <?php if ($lic['hora_salida']): ?>
                                                — Salida: <?= substr($lic['hora_salida'], 0, 5) ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($lic['es_excepcion']): ?>
                                            <span class="badge badge-success"><i class="fas fa-shield-alt mr-1"></i>Excepción</span>
                                        <?php else: ?>
                                            <span class="text-<?= $color ?> font-weight-bold">
                                                <?= number_format($lic['fraccion_cupo'], 1) ?> día(s)
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Ausencias sin licencia -->
            <?php if (!empty($ausencias)): ?>
            <div class="card card-custom shadow-sm mb-6">
                <div class="card-header border-0 pt-6 pb-2">
                    <h5 class="card-label font-weight-bolder text-danger">
                        <i class="fas fa-user-times text-danger mr-2"></i>Ausencias sin licencia registrada
                    </h5>
                </div>
                <div class="card-body pt-0">
                    <p class="text-muted font-size-sm mb-3">
                        Cada una de estas ausencias consume 1 día completo de cupo (Art. 6).
                        Registre la licencia en el SAAT para que quede documentada (Art. 7).
                    </p>
                    <div class="d-flex flex-wrap">
                        <?php foreach ($ausencias as $aus): ?>
                            <span class="badge badge-light-danger m-1 px-3 py-2 font-size-sm">
                                <i class="fas fa-calendar-times text-danger mr-1"></i><?= $aus['fecha'] ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div><!-- col-md-8 -->
    </div><!-- row -->
</div>
</div>
