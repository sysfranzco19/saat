<?php
$alertas6_pendientes = array_values(array_filter($alertas ?? [], fn($a) => $a['tipo'] === 'alerta6' && !$a['consejero_notificado']));
$alertas9_pendientes = array_values(array_filter($alertas ?? [], fn($a) => $a['tipo'] === 'limite9' && !$a['consejero_notificado']));
$alertas6_all        = array_values(array_filter($alertas ?? [], fn($a) => $a['tipo'] === 'alerta6'));
$alertas9_all        = array_values(array_filter($alertas ?? [], fn($a) => $a['tipo'] === 'limite9'));
?>

<div class="d-flex flex-column-fluid">
<div class="container-fluid">

    <!-- Banner -->
    <div class="card card-custom wave wave-animate-slow wave-warning mb-7">
        <div class="card-body d-flex align-items-center p-5">
            <span class="svg-icon svg-icon-warning svg-icon-3x mr-5">
                <i class="fas fa-bell fa-2x text-warning"></i>
            </span>
            <div class="flex-grow-1">
                <h3 class="text-dark font-weight-bold mb-1">Alertas de Cupo Trimestral</h3>
                <div class="text-muted">
                    <?= esc($phase_name) ?> &nbsp;·&nbsp; Primaria 3ro–6to &nbsp;·&nbsp;
                    <strong>Reglamento de Asistencia 2026 — Art. 8 y 9</strong>
                </div>
            </div>
            <a href="<?= base_url('manager/dashboard') ?>" class="btn btn-light-warning font-weight-bold ml-auto">
                <i class="flaticon2-left-arrow-1 icon-sm mr-1"></i> Volver
            </a>
        </div>
    </div>

    <?php if (!empty($sin_primaria)): ?>
    <div class="alert alert-info">Tu sección asignada no incluye cursos de primaria 3ro–6to.</div>
    <?php return; endif; ?>

    <?php if ($message = session()->getFlashdata('flash_message')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-5" role="alert">
        <?= esc($message) ?> <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php endif; ?>

    <!-- Tarjetas de resumen rápido -->
    <div class="row mb-6">
        <div class="col-md-3">
            <div class="card card-custom bg-light-warning shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="font-size-h1 font-weight-bolder text-warning"><?= count($alertas6_pendientes) ?></div>
                    <div class="text-muted font-size-sm mt-1">Alertas de 6 días<br>sin notificar</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom bg-light-danger shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="font-size-h1 font-weight-bolder text-danger"><?= count($alertas9_pendientes) ?></div>
                    <div class="text-muted font-size-sm mt-1">Límites de 9 días<br>sin notificar</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom bg-light-primary shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="font-size-h1 font-weight-bolder text-primary"><?= count($alertas6_all) ?></div>
                    <div class="text-muted font-size-sm mt-1">Total alertas<br>de 6 días</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom bg-light-dark shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="font-size-h1 font-weight-bolder"><?= count($alertas9_all) ?></div>
                    <div class="text-muted font-size-sm mt-1">Total límites<br>de 9 días</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas pendientes de notificación -->
    <?php if (!empty($alertas6_pendientes) || !empty($alertas9_pendientes)): ?>
    <div class="card card-custom shadow-sm mb-7">
        <div class="card-header border-0 pt-6">
            <h5 class="card-label font-weight-bolder">
                <i class="fas fa-exclamation-circle text-warning mr-2"></i>Pendientes de notificación al consejero/a
            </h5>
        </div>
        <div class="card-body pt-0">
            <p class="text-muted font-size-sm mb-4">
                Estos estudiantes alcanzaron el umbral pero aún no se registró la convocatoria.
                <strong>Plazo reglamentario: 2 días hábiles desde la fecha de alerta (Art. 8).</strong>
            </p>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="text-muted text-uppercase font-size-xs">
                        <tr>
                            <th>Umbral</th><th>Estudiante</th><th>Curso</th>
                            <th>Días</th><th>Fecha alerta</th><th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $pendientes = array_merge($alertas9_pendientes, $alertas6_pendientes);
                    foreach ($pendientes as $a): ?>
                        <tr>
                            <td>
                                <?php if ($a['tipo'] === 'limite9'): ?>
                                    <span class="badge badge-danger px-3 py-2">Límite 9 días</span>
                                <?php else: ?>
                                    <span class="badge badge-warning px-3 py-2">Alerta 6 días</span>
                                <?php endif; ?>
                            </td>
                            <td class="font-weight-bold"><?= esc($a['student']) ?></td>
                            <td><?= esc($a['nick_name']) ?></td>
                            <td class="font-weight-bold text-<?= $a['tipo'] === 'limite9' ? 'danger' : 'warning' ?>">
                                <?= number_format($a['dias_consumidos'], 1) ?>
                            </td>
                            <td class="text-muted font-size-sm">
                                <?= date('d-m-Y H:i', strtotime($a['fecha_alerta'])) ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-light-primary font-weight-bold"
                                    onclick="abrirActa(<?= $a['alerta_id'] ?>, <?= $a['student_id'] ?>, '<?= esc($a['student'], 'js') ?>', '<?= esc($a['nick_name'], 'js') ?>')">
                                    <i class="fas fa-file-signature mr-1"></i>Registrar acta
                                </button>
                                <button class="btn btn-sm btn-light-warning ml-1"
                                    onclick="marcarNotificado(<?= $a['alerta_id'] ?>, this)"
                                    title="Marcar como notificado sin acta">
                                    <i class="fas fa-check"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Resumen por sección -->
    <?php foreach ($resumen as $grupo): ?>
    <div class="card card-custom shadow-sm mb-5">
        <div class="card-header border-0 pt-5 pb-0">
            <h6 class="card-label font-weight-bolder">
                <?= esc($grupo['seccion']['completo']) ?>
                <?php if ($grupo['en_limite'] > 0): ?>
                    <span class="badge badge-danger ml-2"><?= $grupo['en_limite'] ?> en límite</span>
                <?php endif; ?>
                <?php if ($grupo['en_alerta'] > 0): ?>
                    <span class="badge badge-warning ml-1"><?= $grupo['en_alerta'] ?> en alerta</span>
                <?php endif; ?>
            </h6>
        </div>
        <div class="card-body pt-2">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="text-muted text-uppercase font-size-xs">
                        <tr>
                            <th>Estudiante</th>
                            <th>Ausencias</th><th>Licencias</th><th>Salidas</th>
                            <th>Total</th><th>Cupo restante</th><th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($grupo['alumnos'] as $al): ?>
                        <?php
                        $cls = $al['total'] >= 9 ? 'danger' : ($al['total'] >= 6 ? 'warning' : 'success');
                        ?>
                        <tr class="<?= $al['total'] >= 9 ? 'table-danger' : ($al['total'] >= 6 ? 'table-warning' : '') ?>">
                            <td class="font-weight-bold font-size-sm"><?= esc($al['student']) ?></td>
                            <td><?= $al['ausencias_puras'] ?></td>
                            <td><?= $al['dias_licencia'] ?></td>
                            <td><?= number_format($al['salidas_anticipadas'], 1) ?></td>
                            <td class="font-weight-bolder text-<?= $cls ?>"><?= number_format($al['total'], 1) ?>/9</td>
                            <td>
                                <div class="progress" style="height:8px; width:80px;">
                                    <div class="progress-bar bg-<?= $cls ?>"
                                         style="width:<?= min(100, round($al['total']/9*100)) ?>%"></div>
                                </div>
                            </td>
                            <td>
                                <?php if ($al['total'] >= 9): ?>
                                    <span class="badge badge-danger">Límite</span>
                                <?php elseif ($al['total'] >= 6): ?>
                                    <span class="badge badge-warning">Alerta</span>
                                <?php else: ?>
                                    <span class="badge badge-success">OK</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</div>
</div>

<!-- Modal: Registrar acta -->
<div class="modal fade" id="modalActa" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('manager/acta_save') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="student_id" id="acta_student_id">
                <input type="hidden" name="alerta_id"  id="acta_alerta_id">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-file-signature mr-2 text-primary"></i>Registrar Acta de Convocatoria
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="mb-4 p-3 bg-light-primary rounded">
                        <strong id="acta_nombre"></strong><br>
                        <span class="text-muted font-size-sm" id="acta_curso"></span>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha de reunión realizada</label>
                        <input type="date" name="fecha_reunion" class="form-control"
                               max="<?= date('Y-m-d') ?>">
                        <small class="text-muted">Dejar vacío si la reunión aún no se realizó.</small>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">¿El padre/tutor asistió?</label>
                        <select name="asistio" class="form-control">
                            <option value="0">No asistió</option>
                            <option value="1">Sí asistió</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="3"
                                  placeholder="Compromisos asumidos, acuerdos, situación del estudiante..."></textarea>
                    </div>
                    <div class="alert alert-warning font-size-sm mb-0">
                        <i class="fas fa-info-circle mr-1"></i>
                        La inasistencia del padre/tutor no detiene el cómputo del cupo ni modifica las consecuencias (Art. 8.III).
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-save mr-1"></i>Guardar acta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirActa(alertaId, studentId, nombre, curso) {
    document.getElementById('acta_alerta_id').value  = alertaId;
    document.getElementById('acta_student_id').value = studentId;
    document.getElementById('acta_nombre').textContent = nombre;
    document.getElementById('acta_curso').textContent  = curso;
    $('#modalActa').modal('show');
}

function marcarNotificado(alertaId, btn) {
    if (!confirm('¿Marcar esta alerta como notificada sin registrar acta?')) return;
    fetch('<?= base_url('manager/acta_notificar/') ?>' + alertaId, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest',
                   'X-CSRF-TOKEN': '<?= csrf_hash() ?>' }
    })
    .then(r => r.json())
    .then(d => { if (d.ok) btn.closest('tr').remove(); });
}
</script>
