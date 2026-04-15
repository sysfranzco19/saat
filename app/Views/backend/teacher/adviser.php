<?php
$color_map = ["Guindo" => "danger", "Plomo" => "secondary", "Azul" => "primary", "Blanco" => "light", "Verde" => "success", "Amarillo" => "warning"];
$first_sid = !empty($cursos) ? $cursos[0]['section_id'] : null;
?>

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <!-- ENCABEZADO -->
        <div class="card card-custom bg-primary gutter-b">
            <div class="card-body py-8 px-10">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-white mr-5">
                            <span class="symbol-label bg-white-o-20">
                                <i class="flaticon2-user text-white icon-2x"></i>
                            </span>
                        </div>
                        <div>
                            <h2 class="text-white font-weight-boldest mb-1">CONSEJERÍA</h2>
                            <span class="text-white opacity-75 font-weight-bold font-size-lg">
                                Docente: <?php echo esc($teacher); ?> &nbsp;|&nbsp; Fase: <?php echo esc($phase_name); ?>
                            </span>
                        </div>
                    </div>

                    <?php if (count($cursos) > 1): ?>
                    <!-- SELECTOR DE CURSO -->
                    <div class="d-flex flex-wrap mt-3 mt-md-0" id="course-selector">
                        <?php foreach ($cursos as $idx => $c):
                            $cb      = $color_map[$c['name']] ?? 'primary';
                            $ti      = array_sum(array_column($infractions_subject_data[$c['section_id']] ?? [], 'total'));
                            $active  = $idx === 0 ? 'btn-white text-primary' : 'btn-white-o-20 text-white';
                        ?>
                            <button type="button"
                                    class="btn btn-sm font-weight-bold mr-2 mb-2 course-btn <?php echo $active; ?>"
                                    data-target="course_card_<?php echo $c['section_id']; ?>">
                                <span class="label label-xs label-<?php echo $cb; ?> label-dot mr-2"></span>
                                <?php echo esc($c['completo']); ?>
                                <?php if ($ti > 0): ?>
                                    <span class="badge badge-danger ml-1"><?php echo $ti; ?></span>
                                <?php endif; ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (empty($cursos)): ?>
            <div class="card card-custom gutter-b">
                <div class="card-body text-center py-20">
                    <i class="flaticon2-folder text-muted icon-5x mb-5"></i>
                    <h4 class="text-muted">No tiene cursos asignados como consejero.</h4>
                </div>
            </div>
        <?php endif; ?>

        <?php foreach ($cursos as $idx => $curso):
            $sid           = $curso['section_id'];
            $color_badge   = $color_map[$curso['name']] ?? 'primary';
            $students      = $students_data[$sid] ?? [];
            $selfs         = $selfs_data[$sid] ?? [];
            $infr_subjects = $infractions_subject_data[$sid] ?? [];
            $by_subject    = $behavior_by_subject[$sid] ?? [];

            $entregaron    = [];
            $no_entregaron = [];
            foreach ($students as $st) {
                if (isset($selfs[$st['student_id']])) $entregaron[] = $st;
                else $no_entregaron[] = $st;
            }
            $total_infracciones = array_sum(array_column($infr_subjects, 'total'));

            $id_inci    = 'tab_inci_' . $sid;
            $id_auto    = 'tab_auto_' . $sid;
            $card_style = $idx === 0 ? '' : 'display:none;';
        ?>

        <!-- TARJETA POR CURSO -->
        <div class="card card-custom card-stretch gutter-b course-card"
             id="course_card_<?php echo $sid; ?>"
             style="<?php echo $card_style; ?>">

            <!-- Card header con tabs -->
            <div class="card-header card-header-tabs-line">
                <div class="card-title">
                    <span class="label label-xl label-<?php echo $color_badge; ?> label-dot mr-3"></span>
                    <h3 class="card-label font-weight-boldest text-dark mb-0">
                        <?php echo esc($curso['completo']); ?>
                        <small class="text-muted font-weight-normal font-size-sm ml-2">Cod. <?php echo $sid; ?></small>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-primary nav-tabs-line-2x" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold" data-toggle="tab"
                               href="#<?php echo $id_inci; ?>" role="tab">
                                <i class="fa fa-exclamation-circle mr-2"></i> Incidencias
                                <?php if ($total_infracciones > 0): ?>
                                    <span class="badge badge-danger ml-1"><?php echo $total_infracciones; ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success ml-1">0</span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab"
                               href="#<?php echo $id_auto; ?>" role="tab">
                                <i class="fa fa-clipboard-check mr-2"></i> Autoevaluaciones
                                <span class="badge badge-success ml-1"><?php echo count($entregaron); ?></span>
                                <?php if (count($no_entregaron) > 0): ?>
                                    <span class="badge badge-danger ml-1"><?php echo count($no_entregaron); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Card body con contenido de pestañas -->
            <div class="card-body">
                <div class="tab-content">

                    <!-- ══ PESTAÑA: INCIDENCIAS ══ -->
                    <div class="tab-pane fade show active" id="<?php echo $id_inci; ?>" role="tabpanel">

                        <!-- Encabezado: resumen chips + botón log -->
                        <div class="d-flex align-items-start justify-content-between mb-6">
                            <div class="d-flex flex-wrap align-items-center">
                                <?php if (!empty($infr_subjects)): ?>
                                    <?php foreach ($infr_subjects as $infr):
                                        $chip_color = $infr['total'] >= 5 ? 'danger' : ($infr['total'] >= 3 ? 'warning' : 'primary');
                                    ?>
                                        <span class="label label-<?php echo $chip_color; ?> label-inline font-weight-bold px-4 py-3 mr-2 mb-2">
                                            <?= $infr['materia'] ?>:&nbsp;<strong><?= $infr['total'] ?></strong>
                                        </span>
                                    <?php endforeach; ?>
                                    <span class="label label-dark label-inline font-weight-boldest px-4 py-3 mb-2">
                                        Total:&nbsp;<strong><?= $total_infracciones ?></strong>
                                    </span>
                                <?php else: ?>
                                    <span class="text-success font-weight-bold">
                                        <i class="fa fa-thumbs-up mr-1"></i> Sin incidencias registradas
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center ml-3 flex-shrink-0">
                                <button type="button"
                                        class="btn btn-sm btn-light-primary font-weight-bold mr-2 btn-collapse-all"
                                        data-section="<?= $sid ?>"
                                        data-state="expanded">
                                    <i class="fa fa-compress-alt mr-1"></i> Colapsar todo
                                </button>
                                <a href="<?php echo base_url(); ?>index.php/teacher/adviser_behavior_log/<?php echo $sid; ?>"
                                   class="btn btn-sm btn-light-danger font-weight-bold">
                                    <i class="fa fa-list mr-1"></i> Ver Log
                                </a>
                            </div>
                        </div>

                        <?php if (empty($students)): ?>
                            <p class="text-muted">Sin estudiantes registrados.</p>
                        <?php elseif (empty($by_subject)): ?>
                            <div class="text-center text-muted py-10">
                                <i class="fa fa-thumbs-up fa-3x text-success mb-3 d-block"></i>
                                <p class="font-weight-bold mb-0">Sin incidencias registradas para este curso.</p>
                            </div>
                        <?php else: ?>

                        <!-- Bloque por materia -->
                        <?php $mat_idx = 0; foreach ($by_subject as $mat_name => $mat_data): $mat_idx++;
                            $mat_total      = $mat_data['total'];
                            $mat_color      = $mat_total >= 5 ? 'danger' : ($mat_total >= 3 ? 'warning' : 'primary');
                            $collapse_id    = 'collapse_' . $sid . '_' . $mat_idx;
                        ?>
                        <div class="mb-4">
                            <!-- Encabezado de materia (clickable para colapsar) -->
                            <div class="d-flex align-items-center justify-content-between bg-light-<?php echo $mat_color; ?> rounded px-5 py-3 cursor-pointer"
                                 data-toggle="collapse" data-target="#<?php echo $collapse_id; ?>"
                                 aria-expanded="true" style="cursor:pointer;">
                                <div class="d-flex align-items-center">
                                    <span class="label label-<?php echo $mat_color; ?> label-dot mr-3"></span>
                                    <span class="font-weight-boldest font-size-h6 text-dark">
                                        <?= $mat_name ?>
                                    </span>
                                    <span class="label label-<?php echo $mat_color; ?> label-inline font-weight-bold ml-3">
                                        <?= $mat_total ?> incidencia<?= $mat_total !== 1 ? 's' : '' ?>
                                    </span>
                                </div>
                                <i class="fa fa-chevron-down text-muted font-size-xs"></i>
                            </div>

                            <!-- Tabla de estudiantes de esa materia -->
                            <div class="collapse show" id="<?php echo $collapse_id; ?>">
                                <div class="table-responsive border border-top-0 rounded-bottom">
                                    <table class="table table-head-custom table-vertical-center table-hover font-size-sm mb-0">
                                        <thead>
                                            <tr class="text-uppercase text-muted font-size-xs">
                                                <th style="width:40px">#</th>
                                                <th>Estudiante</th>
                                                <th>Tipos de incidencia</th>
                                                <th class="text-center" style="width:80px">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $row_n = 0; foreach ($mat_data['students'] as $stid => $stdata): $row_n++; ?>
                                            <tr>
                                                <td class="text-muted"><?php echo $row_n; ?></td>
                                                <td class="font-weight-bold text-dark-75">
                                                    <?= $stdata['name'] ?>
                                                </td>
                                                <td>
                                                    <?php foreach ($stdata['behaviors'] as $bname => $bcnt): ?>
                                                        <span class="label label-light-warning label-inline font-weight-bold mr-1 mb-1">
                                                            <?= $bname ?>&nbsp;<strong><?= $bcnt ?></strong>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="label label-inline font-weight-boldest
                                                        <?php echo $stdata['total'] >= 5 ? 'label-danger' : ($stdata['total'] >= 3 ? 'label-warning' : 'label-primary'); ?>">
                                                        <?php echo $stdata['total']; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>

                        <?php endif; ?>
                    </div>
                    <!-- ══ FIN INCIDENCIAS ══ -->

                    <!-- ══ PESTAÑA: AUTOEVALUACIONES ══ -->
                    <div class="tab-pane fade" id="<?php echo $id_auto; ?>" role="tabpanel">

                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <div>
                                <span class="label label-success label-inline font-weight-bold px-4 py-3 mr-3">
                                    <i class="fa fa-check mr-1"></i> Entregaron: <?php echo count($entregaron); ?>
                                </span>
                                <span class="label label-danger label-inline font-weight-bold px-4 py-3">
                                    <i class="fa fa-clock mr-1"></i> Pendientes: <?php echo count($no_entregaron); ?>
                                </span>
                            </div>
                            <?php if ($sid < 231): ?>
                                <a href="<?php echo base_url(); ?>index.php/teacher/self_inicial/<?php echo $sid; ?>"
                                   class="btn btn-sm btn-primary font-weight-bold">
                                    <i class="fa fa-edit mr-1"></i> Llenar Autoevaluaciones
                                </a>
                            <?php else: ?>
                                <a href="<?php echo base_url(); ?>index.php/teacher/self_appraisal/<?php echo $sid; ?>"
                                   class="btn btn-sm btn-light-primary font-weight-bold">
                                    <i class="fa fa-clipboard-list mr-1"></i> Ver Autoevaluaciones
                                </a>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($students)): ?>
                            <p class="text-muted">Sin estudiantes registrados.</p>
                        <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-head-custom table-vertical-center table-hover font-size-sm">
                                <thead>
                                    <tr class="text-uppercase text-muted font-size-xs">
                                        <th style="width:50px">#</th>
                                        <th>Estudiante</th>
                                        <th class="text-center" style="width:140px">Autoevaluación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $i => $st):
                                        $has_self = isset($selfs[$st['student_id']]);
                                    ?>
                                    <tr>
                                        <td class="text-muted"><?php echo $i + 1; ?></td>
                                        <td class="font-weight-bold text-dark-75"><?php echo esc($st['student']); ?></td>
                                        <td class="text-center">
                                            <?php if ($has_self): ?>
                                                <span class="label label-success label-inline font-weight-bold">
                                                    <i class="fa fa-check mr-1"></i> Entregado
                                                </span>
                                            <?php else: ?>
                                                <span class="label label-danger label-inline font-weight-bold">
                                                    <i class="fa fa-clock mr-1"></i> Pendiente
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </div>
                    <!-- ══ FIN AUTOEVALUACIONES ══ -->

                </div><!-- /tab-content -->
            </div><!-- /card-body -->
        </div><!-- /card -->

        <?php endforeach; ?>

    </div>
</div>

<script>
(function () {
    // ── Selector de curso ──────────────────────────────────────────
    var btns  = document.querySelectorAll('.course-btn');
    var cards = document.querySelectorAll('.course-card');

    btns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var target = this.getAttribute('data-target');

            // Ocultar todas las tarjetas
            cards.forEach(function (c) { c.style.display = 'none'; });

            // Mostrar la tarjeta seleccionada
            var card = document.getElementById(target);
            if (card) card.style.display = '';

            // Actualizar estilos de botones
            btns.forEach(function (b) {
                b.classList.remove('btn-white', 'text-primary');
                b.classList.add('btn-white-o-20', 'text-white');
            });
            this.classList.remove('btn-white-o-20', 'text-white');
            this.classList.add('btn-white', 'text-primary');
        });
    });

    // ── Colapsar / Expandir todo ───────────────────────────────────
    document.querySelectorAll('.btn-collapse-all').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var sid      = this.getAttribute('data-section');
            var state    = this.getAttribute('data-state');
            var collapses = document.querySelectorAll('[id^="collapse_' + sid + '_"]');

            if (state === 'expanded') {
                collapses.forEach(function (el) {
                    el.classList.remove('show');
                });
                btn.setAttribute('data-state', 'collapsed');
                btn.innerHTML = '<i class="fa fa-expand-alt mr-1"></i> Expandir todo';
            } else {
                collapses.forEach(function (el) {
                    el.classList.add('show');
                });
                btn.setAttribute('data-state', 'expanded');
                btn.innerHTML = '<i class="fa fa-compress-alt mr-1"></i> Colapsar todo';
            }
        });
    });
})();
</script>
