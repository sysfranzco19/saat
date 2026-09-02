<?php
$cupo_secciones = [];
foreach ($sections as $sec) {
    $alumnos = $grouped[$sec['section_id']] ?? [];
    $filas = [];
    $cnt_ok = $cnt_alerta = $cnt_limite = 0;
    foreach ($alumnos as $stu) {
        $c = $cupos_map[$stu['student_id']] ?? null;
        $total    = $c ? round((float)$c['total'], 1) : 0;
        $restante = max(0, 9 - $total);
        $alerta6  = $total >= 6 && $total < 9;
        $limite9  = $total >= 9;
        if ($limite9)     $cnt_limite++;
        elseif ($alerta6) $cnt_alerta++;
        else              $cnt_ok++;
        $filas[] = [
            'student_id' => $stu['student_id'],
            'name'       => $stu['name'],
            'total'      => $total,
            'restante'   => $restante,
            'alerta6'    => $alerta6,
            'limite9'    => $limite9,
        ];
    }
    usort($filas, fn($a,$b) =>
        ($b['limite9'] <=> $a['limite9']) ?: ($b['alerta6'] <=> $a['alerta6']) ?: ($b['total'] <=> $a['total'])
    );
    $cupo_secciones[] = compact('cnt_ok','cnt_alerta','cnt_limite','filas') + [
        'section_id' => $sec['section_id'],
        'nick_name'  => $sec['nick_name'],
        'completo'   => $sec['completo'],
    ];
}
$total_alumnos = array_sum(array_map(fn($s) => count($s['filas']), $cupo_secciones));
$total_ok      = array_sum(array_column($cupo_secciones, 'cnt_ok'));
$total_alerta  = array_sum(array_column($cupo_secciones, 'cnt_alerta'));
$total_limite  = array_sum(array_column($cupo_secciones, 'cnt_limite'));
?>

<div class="d-flex flex-column-fluid">
<div class="container-fluid pb-8">

    <!-- Header -->
    <div class="card card-custom mb-6" style="background:linear-gradient(135deg,#ffa800,#e65100);border-radius:12px;">
        <div class="card-body p-5 d-flex align-items-center">
            <div>
                <h3 class="text-white font-weight-bolder mb-1">📊 Cupo Trimestral — Primaria</h3>
                <span class="text-white font-size-sm" style="opacity:.85;">
                    3ro a 6to &nbsp;·&nbsp; <strong><?= esc($phase_name) ?></strong>
                    &nbsp;(<?= date('d-m-Y', strtotime($phase_ini)) ?> → <?= date('d-m-Y', strtotime($phase_fin)) ?>)
                    &nbsp;·&nbsp; Máximo 9 días por trimestre
                </span>
            </div>
            <div class="ml-auto text-right text-white">
                <div class="font-size-h4 font-weight-bolder"><?= $total_alumnos ?> alumnos</div>
                <div class="font-size-sm" style="opacity:.75;"><?= count($sections) ?> secciones</div>
            </div>
        </div>
    </div>

    <!-- Stats globales -->
    <div class="row mb-6">
        <div class="col-4">
            <div class="card card-custom" style="background:#e8fff3;">
                <div class="card-body text-center py-4">
                    <div class="font-size-h1 font-weight-bolder text-success"><?= $total_ok ?></div>
                    <div class="text-success font-weight-bold font-size-sm text-uppercase">✓ Dentro del cupo</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-custom" style="background:#fff8dd;">
                <div class="card-body text-center py-4">
                    <div class="font-size-h1 font-weight-bolder text-warning"><?= $total_alerta ?></div>
                    <div class="text-warning font-weight-bold font-size-sm text-uppercase">⚠️ En alerta (6+ días)</div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-custom" style="background:#fff0f2;">
                <div class="card-body text-center py-4">
                    <div class="font-size-h1 font-weight-bolder text-danger"><?= $total_limite ?></div>
                    <div class="text-danger font-weight-bold font-size-sm text-uppercase">🚫 En límite (9+ días)</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro rápido -->
    <div class="mb-5">
        <button class="btn btn-warning btn-sm font-weight-bold mr-2 active" id="f_todos">Todos</button>
        <button class="btn btn-light-warning btn-sm font-weight-bold mr-2" id="f_alerta">Solo en alerta ⚠️</button>
        <button class="btn btn-light-danger btn-sm font-weight-bold" id="f_limite">Solo en límite 🚫</button>
    </div>

    <!-- Una card por sección -->
    <?php foreach ($cupo_secciones as $sec): ?>
    <div class="card card-custom shadow-sm mb-5"
         id="sec_<?= $sec['section_id'] ?>"
         data-limite="<?= $sec['cnt_limite'] ?>"
         data-alerta="<?= $sec['cnt_alerta'] ?>">

        <!-- Header colapsable -->
        <div class="card-header border-0 py-4"
             style="cursor:pointer;<?= $sec['cnt_limite'] > 0 ? 'background:#fff0f2;border-left:4px solid #f1416c;' : ($sec['cnt_alerta'] > 0 ? 'background:#fffde7;border-left:4px solid #ffa800;' : 'border-left:4px solid #50cd89;') ?>"
             data-toggle="collapse"
             data-target="#col_<?= $sec['section_id'] ?>"
             aria-expanded="false">
            <div class="card-title mb-0">
                <i class="fas fa-chevron-right mr-2 text-muted toggle-icon" style="transition:transform .2s;font-size:.8rem;"></i>
                <h5 class="font-weight-bolder mb-0 d-inline"><?= esc($sec['nick_name']) ?></h5>
                <span class="text-muted font-size-sm ml-2"><?= esc($sec['completo']) ?></span>
            </div>
            <div class="card-toolbar">
                <span class="badge badge-success mr-2 px-3 py-2">✓ <?= $sec['cnt_ok'] ?> OK</span>
                <?php if ($sec['cnt_alerta']): ?>
                <span class="badge badge-warning mr-2 px-3 py-2">⚠️ <?= $sec['cnt_alerta'] ?> alerta</span>
                <?php endif; ?>
                <?php if ($sec['cnt_limite']): ?>
                <span class="badge badge-danger px-3 py-2">🚫 <?= $sec['cnt_limite'] ?> límite</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabla colapsable (cerrada por defecto) -->
        <div class="collapse" id="col_<?= $sec['section_id'] ?>">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted text-uppercase font-size-xs">
                            <th class="pl-6" width="40">#</th>
                            <th>Estudiante</th>
                            <th class="text-center" width="180">Días consumidos</th>
                            <th class="text-center" width="130">Estado</th>
                            <th class="text-center" width="120">Restantes</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($sec['filas'] as $j => $al): ?>
                    <?php
                        $pct      = min(100, round($al['total'] / 9 * 100));
                        $bar_clr  = $al['limite9'] ? '#f1416c' : ($al['alerta6'] ? '#ffa800' : '#50cd89');
                        $row_bg   = $al['limite9'] ? 'background:#fff8f9;' : ($al['alerta6'] ? 'background:#fffde7;' : '');
                        $tipo     = $al['limite9'] ? 'limite' : ($al['alerta6'] ? 'alerta' : 'ok');
                        $badge_cls = $al['limite9'] ? 'badge-danger' : ($al['alerta6'] ? 'badge-warning' : 'badge-success');
                        $estado   = $al['limite9'] ? '🚫 Límite' : ($al['alerta6'] ? '⚠️ Alerta' : '✓ OK');
                        $rest_cls = $al['limite9'] ? 'text-danger' : ($al['alerta6'] ? 'text-warning' : 'text-success');
                    ?>
                    <tr style="<?= $row_bg ?>" data-tipo="<?= $tipo ?>">
                        <td class="pl-6 text-muted font-size-sm"><?= $j + 1 ?></td>
                        <td class="font-weight-bold font-size-sm"><?= esc($al['name']) ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="font-weight-bolder mr-2 font-size-sm" style="min-width:40px;">
                                    <?= number_format($al['total'], 1) ?>/9
                                </span>
                                <div class="flex-grow-1" style="background:#f0f0f0;border-radius:4px;height:8px;">
                                    <div style="width:<?= $pct ?>%;background:<?= $bar_clr ?>;height:8px;border-radius:4px;"></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge <?= $badge_cls ?> px-3 py-2"><?= $estado ?></span>
                        </td>
                        <td class="text-center font-weight-bolder <?= $rest_cls ?>">
                            <?= number_format($al['restante'], 1) ?> día<?= $al['restante'] != 1 ? 's' : '' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div><!-- /.collapse -->
    </div>
    <?php endforeach; ?>

</div>
</div>

<script>
function aplicarFiltro(tipo) {
    document.querySelectorAll('[id^="sec_"]').forEach(card => {
        const limite = parseInt(card.dataset.limite || 0);
        const alerta = parseInt(card.dataset.alerta || 0);

        if (tipo === 'todos') {
            card.style.display = '';
            card.querySelectorAll('tbody tr').forEach(r => r.style.display = '');
        } else if (tipo === 'alerta') {
            if (alerta > 0) {
                card.style.display = '';
                card.querySelectorAll('tbody tr').forEach(r =>
                    r.style.display = r.dataset.tipo === 'alerta' ? '' : 'none');
            } else {
                card.style.display = 'none';
            }
        } else if (tipo === 'limite') {
            if (limite > 0) {
                card.style.display = '';
                card.querySelectorAll('tbody tr').forEach(r =>
                    r.style.display = r.dataset.tipo === 'limite' ? '' : 'none');
            } else {
                card.style.display = 'none';
            }
        }
    });
    ['f_todos','f_alerta','f_limite'].forEach(id => {
        document.getElementById(id).className = 'btn btn-light-warning btn-sm font-weight-bold mr-2';
    });
    const mapa = { todos:'btn-warning', alerta:'btn-light-warning', limite:'btn-light-danger' };
    document.getElementById('f_' + tipo).className = `btn ${mapa[tipo]} btn-sm font-weight-bold mr-2`;
}

document.getElementById('f_todos').addEventListener('click',  () => aplicarFiltro('todos'));
document.getElementById('f_alerta').addEventListener('click', () => aplicarFiltro('alerta'));
document.getElementById('f_limite').addEventListener('click', () => aplicarFiltro('limite'));

// Rotar flecha al abrir/cerrar
document.querySelectorAll('.collapse').forEach(col => {
    col.addEventListener('show.bs.collapse', () => {
        const icon = col.previousElementSibling.querySelector('.toggle-icon');
        if (icon) icon.style.transform = 'rotate(90deg)';
    });
    col.addEventListener('hide.bs.collapse', () => {
        const icon = col.previousElementSibling.querySelector('.toggle-icon');
        if (icon) icon.style.transform = 'rotate(0deg)';
    });
});
</script>
