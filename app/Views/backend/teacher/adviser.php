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
            $sid              = $curso['section_id'];
            $color_badge      = $color_map[$curso['name']] ?? 'primary';
            $students         = $students_data[$sid] ?? [];
            $selfs            = $selfs_data[$sid] ?? [];
            $infr_subjects    = $infractions_subject_data[$sid] ?? [];
            $by_subject       = $behavior_by_subject[$sid] ?? [];
            $infr_subjects_t2 = $infractions_subject_data_t2[$sid] ?? [];
            $by_subject_t2    = $behavior_by_subject_t2[$sid] ?? [];

            $alertas_curso = $alertas_data[$sid] ?? [];

            $entregaron    = [];
            $no_entregaron = [];
            foreach ($students as $st) {
                if (isset($selfs[$st['student_id']])) $entregaron[] = $st;
                else $no_entregaron[] = $st;
            }
            $total_infracciones    = array_sum(array_column($infr_subjects, 'total'));
            $total_infracciones_t2 = array_sum(array_column($infr_subjects_t2, 'total'));
            $total_alertas_curso   = count($alertas_curso);

            $selfs_by_phase = $selfs_data_by_phase[$sid] ?? [1 => [], 2 => [], 3 => []];
            $id_inci    = 'tab_inci_' . $sid;
            $id_auto    = 'tab_auto_' . $sid;
            $id_alert   = 'tab_alert_' . $sid;
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
                                <span class="badge badge-warning ml-1" title="T1"><?php echo $total_infracciones; ?></span>
                                <span class="badge badge-primary ml-1" title="T2"><?php echo $total_infracciones_t2; ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab"
                               href="#<?php echo $id_alert; ?>" role="tab">
                                <i class="fa fa-bell mr-2"></i> Alertas
                                <?php if ($total_alertas_curso > 0): ?>
                                    <span class="badge badge-danger ml-1"><?php echo $total_alertas_curso; ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success ml-1">0</span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php
                            $total_auto_entregaron = 0;
                            for ($ph = 1; $ph <= 3; $ph++) {
                                $total_auto_entregaron += count($selfs_by_phase[$ph] ?? []);
                            }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab"
                               href="#<?php echo $id_auto; ?>" role="tab">
                                <i class="fa fa-clipboard-check mr-2"></i> Autoevaluaciones
                                <span class="badge badge-success ml-1" title="Total entregados T1+T2+T3"><?php echo $total_auto_entregaron; ?></span>
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
                        <?php
                        $id_t1 = 'inci_t1_' . $sid;
                        $id_t2 = 'inci_t2_' . $sid;
                        ?>

                        <!-- Sub-pestañas T1 / T2 -->
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x mb-5" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link font-weight-bolder active" data-toggle="tab" href="#<?= $id_t1 ?>">
                                    Primer Trimestre
                                    <span class="label label-light-warning label-inline ml-2"><?= $total_infracciones ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link font-weight-bolder" data-toggle="tab" href="#<?= $id_t2 ?>">
                                    Segundo Trimestre
                                    <span class="label label-light-primary label-inline ml-2"><?= $total_infracciones_t2 ?></span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">

                        <!-- ── T1 ── -->
                        <div class="tab-pane show active" id="<?= $id_t1 ?>">
                            <div class="d-flex align-items-start justify-content-between mb-5">
                                <div class="d-flex flex-wrap align-items-center">
                                    <?php if (!empty($infr_subjects)): ?>
                                        <?php foreach ($infr_subjects as $infr):
                                            $chip_color = $infr['total'] >= 5 ? 'danger' : ($infr['total'] >= 3 ? 'warning' : 'primary');
                                        ?>
                                            <span class="label label-<?= $chip_color ?> label-inline font-weight-bold px-4 py-3 mr-2 mb-2">
                                                <?= $infr['materia'] ?>:&nbsp;<strong><?= $infr['total'] ?></strong>
                                            </span>
                                        <?php endforeach; ?>
                                        <span class="label label-dark label-inline font-weight-boldest px-4 py-3 mb-2">
                                            Total:&nbsp;<strong><?= $total_infracciones ?></strong>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-success font-weight-bold">
                                            <i class="fa fa-thumbs-up mr-1"></i> Sin incidencias en T1
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex align-items-center ml-3 flex-shrink-0">
                                    <button type="button"
                                            class="btn btn-sm btn-light-primary font-weight-bold mr-2 btn-collapse-all"
                                            data-section="<?= $sid ?>_t1"
                                            data-state="expanded">
                                        <i class="fa fa-compress-alt mr-1"></i> Colapsar todo
                                    </button>
                                    <a href="<?= base_url() ?>index.php/teacher/adviser_behavior_log/<?= $sid ?>"
                                       class="btn btn-sm btn-light-warning font-weight-bold">
                                        <i class="fa fa-list mr-1"></i> Ver Log T1
                                    </a>
                                </div>
                            </div>

                            <?php if (empty($by_subject)): ?>
                                <div class="text-center text-muted py-8">
                                    <i class="fa fa-thumbs-up fa-3x text-success mb-3 d-block"></i>
                                    <p class="font-weight-bold mb-0">Sin incidencias T1 para este curso.</p>
                                </div>
                            <?php else: ?>
                                <?php $mat_idx = 0; foreach ($by_subject as $mat_name => $mat_data): $mat_idx++;
                                    $mat_total   = $mat_data['total'];
                                    $mat_color   = $mat_total >= 5 ? 'danger' : ($mat_total >= 3 ? 'warning' : 'primary');
                                    $collapse_id = 'collapse_' . $sid . '_t1_' . $mat_idx;
                                ?>
                                <div class="mb-4">
                                    <div class="d-flex align-items-center justify-content-between bg-light-<?= $mat_color ?> rounded px-5 py-3"
                                         data-toggle="collapse" data-target="#<?= $collapse_id ?>"
                                         aria-expanded="true" style="cursor:pointer;">
                                        <div class="d-flex align-items-center">
                                            <span class="label label-<?= $mat_color ?> label-dot mr-3"></span>
                                            <span class="font-weight-boldest font-size-h6 text-dark"><?= $mat_name ?></span>
                                            <span class="label label-<?= $mat_color ?> label-inline font-weight-bold ml-3">
                                                <?= $mat_total ?> incidencia<?= $mat_total !== 1 ? 's' : '' ?>
                                            </span>
                                        </div>
                                        <i class="fa fa-chevron-down text-muted font-size-xs"></i>
                                    </div>
                                    <div class="collapse show" id="<?= $collapse_id ?>">
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
                                                        <td class="text-muted"><?= $row_n ?></td>
                                                        <td class="font-weight-bold text-dark-75"><?= $stdata['name'] ?></td>
                                                        <td>
                                                            <?php foreach ($stdata['behaviors'] as $bname => $bcnt): ?>
                                                                <span class="label label-light-warning label-inline font-weight-bold mr-1 mb-1">
                                                                    <?= $bname ?>&nbsp;<strong><?= $bcnt ?></strong>
                                                                </span>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="label label-inline font-weight-boldest <?= $stdata['total'] >= 5 ? 'label-danger' : ($stdata['total'] >= 3 ? 'label-warning' : 'label-primary') ?>">
                                                                <?= $stdata['total'] ?>
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
                        <!-- ── FIN T1 ── -->

                        <!-- ── T2 ── -->
                        <div class="tab-pane" id="<?= $id_t2 ?>">
                            <div class="d-flex align-items-start justify-content-between mb-5">
                                <div class="d-flex flex-wrap align-items-center">
                                    <?php if (!empty($infr_subjects_t2)): ?>
                                        <?php foreach ($infr_subjects_t2 as $infr2):
                                            $chip_color2 = $infr2['total'] >= 5 ? 'danger' : ($infr2['total'] >= 3 ? 'warning' : 'primary');
                                        ?>
                                            <span class="label label-<?= $chip_color2 ?> label-inline font-weight-bold px-4 py-3 mr-2 mb-2">
                                                <?= $infr2['materia'] ?>:&nbsp;<strong><?= $infr2['total'] ?></strong>
                                            </span>
                                        <?php endforeach; ?>
                                        <span class="label label-dark label-inline font-weight-boldest px-4 py-3 mb-2">
                                            Total:&nbsp;<strong><?= $total_infracciones_t2 ?></strong>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-success font-weight-bold">
                                            <i class="fa fa-thumbs-up mr-1"></i> Sin incidencias en T2
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex align-items-center ml-3 flex-shrink-0">
                                    <button type="button"
                                            class="btn btn-sm btn-light-primary font-weight-bold btn-collapse-all"
                                            data-section="<?= $sid ?>_t2"
                                            data-state="expanded">
                                        <i class="fa fa-compress-alt mr-1"></i> Colapsar todo
                                    </button>
                                </div>
                            </div>

                            <?php if (empty($by_subject_t2)): ?>
                                <div class="text-center text-muted py-8">
                                    <i class="fa fa-thumbs-up fa-3x text-success mb-3 d-block"></i>
                                    <p class="font-weight-bold mb-0">Sin incidencias T2 para este curso.</p>
                                </div>
                            <?php else: ?>
                                <?php $mat_idx2 = 0; foreach ($by_subject_t2 as $mat_name2 => $mat_data2): $mat_idx2++;
                                    $mat_total2   = $mat_data2['total'];
                                    $mat_color2   = $mat_total2 >= 5 ? 'danger' : ($mat_total2 >= 3 ? 'warning' : 'primary');
                                    $collapse_id2 = 'collapse_' . $sid . '_t2_' . $mat_idx2;
                                ?>
                                <div class="mb-4">
                                    <div class="d-flex align-items-center justify-content-between bg-light-<?= $mat_color2 ?> rounded px-5 py-3"
                                         data-toggle="collapse" data-target="#<?= $collapse_id2 ?>"
                                         aria-expanded="true" style="cursor:pointer;">
                                        <div class="d-flex align-items-center">
                                            <span class="label label-<?= $mat_color2 ?> label-dot mr-3"></span>
                                            <span class="font-weight-boldest font-size-h6 text-dark"><?= $mat_name2 ?></span>
                                            <span class="label label-<?= $mat_color2 ?> label-inline font-weight-bold ml-3">
                                                <?= $mat_total2 ?> incidencia<?= $mat_total2 !== 1 ? 's' : '' ?>
                                            </span>
                                        </div>
                                        <i class="fa fa-chevron-down text-muted font-size-xs"></i>
                                    </div>
                                    <div class="collapse show" id="<?= $collapse_id2 ?>">
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
                                                    <?php $row_n2 = 0; foreach ($mat_data2['students'] as $stid2 => $stdata2): $row_n2++; ?>
                                                    <tr>
                                                        <td class="text-muted"><?= $row_n2 ?></td>
                                                        <td class="font-weight-bold text-dark-75"><?= $stdata2['name'] ?></td>
                                                        <td>
                                                            <?php foreach ($stdata2['behaviors'] as $bname2 => $bcnt2): ?>
                                                                <span class="label label-light-primary label-inline font-weight-bold mr-1 mb-1">
                                                                    <?= $bname2 ?>&nbsp;<strong><?= $bcnt2 ?></strong>
                                                                </span>
                                                            <?php endforeach; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="label label-inline font-weight-boldest <?= $stdata2['total'] >= 5 ? 'label-danger' : ($stdata2['total'] >= 3 ? 'label-warning' : 'label-primary') ?>">
                                                                <?= $stdata2['total'] ?>
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
                        <!-- ── FIN T2 ── -->

                        </div><!-- /tab-content incidencias -->
                    </div>
                    <!-- ══ FIN INCIDENCIAS ══ -->

                    <!-- ══ PESTAÑA: ALERTAS ══ -->
                    <div class="tab-pane fade" id="<?php echo $id_alert; ?>" role="tabpanel">
                        <?php if (empty($alertas_curso)): ?>
                            <div class="text-center py-12">
                                <i class="fa fa-check-circle fa-4x text-success mb-4 d-block"></i>
                                <h5 class="text-success font-weight-bold">Sin alertas activas</h5>
                                <p class="text-muted">Ningún estudiante supera los umbrales de incidencias.</p>
                            </div>
                        <?php else: ?>
                            <p class="text-muted font-size-sm mb-5">
                                Umbrales: T2 nota &le; 8 &middot; T1 &ge; 5 negativos acumulados
                            </p>
                            <div class="row">
                            <?php foreach ($alertas_curso as $alerta):
                                $al_niv = $alerta['nivel'];
                                $al_ico = $al_niv === 'danger' ? '🔴' : ($al_niv === 'warning' ? '🟡' : '🔵');
                                $al_bg  = 'bg-light-' . $al_niv;
                                $al_txt = 'text-' . $al_niv;
                                $al_lbl = 'label-' . $al_niv;
                            ?>
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-0 <?php echo $al_bg; ?> h-100">
                                    <div class="card-body py-4 px-5">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="font-size-h4 mr-3"><?php echo $al_ico; ?></span>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="font-weight-bolder text-dark font-size-sm text-truncate">
                                                    <?php echo htmlspecialchars($alerta['nombre']); ?>
                                                </div>
                                                <span class="label <?php echo $al_lbl; ?> label-inline font-size-xs font-weight-bold mt-1">
                                                    <?php echo strtoupper($al_niv); ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php foreach ($alerta['detalles'] as $det): ?>
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="label <?php echo $al_lbl; ?> label-dot mr-2 flex-shrink-0"></span>
                                                <span class="<?php echo $al_txt; ?> font-weight-bold font-size-xs">
                                                    <?php echo htmlspecialchars($det); ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- ══ FIN ALERTAS ══ -->

                    <!-- ══ PESTAÑA: AUTOEVALUACIONES ══ -->
                    <div class="tab-pane fade" id="<?php echo $id_auto; ?>" role="tabpanel">
                        <?php
                        $auto_phase_labels = [1 => 'Primer Trimestre', 2 => 'Segundo Trimestre', 3 => 'Tercer Trimestre'];
                        $auto_phase_colors = [1 => 'warning', 2 => 'primary', 3 => 'success'];
                        $id_auto_t = [];
                        for ($ph = 1; $ph <= 3; $ph++) {
                            $id_auto_t[$ph] = 'auto_t' . $ph . '_' . $sid;
                        }
                        ?>

                        <!-- Sub-pestañas T1 / T2 / T3 -->
                        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x mb-5" role="tablist">
                            <?php for ($ph = 1; $ph <= 3; $ph++):
                                $cnt_ent = count($selfs_by_phase[$ph] ?? []);
                                $cnt_pen = count($students) - $cnt_ent;
                                $ph_col  = $auto_phase_colors[$ph];
                            ?>
                            <li class="nav-item">
                                <a class="nav-link font-weight-bolder <?= $ph === 1 ? 'active' : '' ?>"
                                   data-toggle="tab" href="#<?= $id_auto_t[$ph] ?>">
                                    <?= $auto_phase_labels[$ph] ?>
                                    <span class="label label-light-success label-inline ml-2" title="Entregados"><?= $cnt_ent ?></span>
                                    <?php if ($cnt_pen > 0): ?>
                                        <span class="label label-light-danger label-inline ml-1" title="Pendientes"><?= $cnt_pen ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php endfor; ?>
                        </ul>

                        <div class="tab-content">
                        <?php for ($ph = 1; $ph <= 3; $ph++):
                            $selfs_ph  = $selfs_by_phase[$ph] ?? [];
                            $cnt_ent   = count($selfs_ph);
                            $cnt_pen   = count($students) - $cnt_ent;
                            $ph_col    = $auto_phase_colors[$ph];
                        ?>
                        <div class="tab-pane <?= $ph === 1 ? 'show active' : '' ?>" id="<?= $id_auto_t[$ph] ?>">

                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <div>
                                    <span class="label label-success label-inline font-weight-bold px-4 py-3 mr-3">
                                        <i class="fa fa-check mr-1"></i> Entregaron: <?= $cnt_ent ?>
                                    </span>
                                    <span class="label label-danger label-inline font-weight-bold px-4 py-3">
                                        <i class="fa fa-clock mr-1"></i> Pendientes: <?= $cnt_pen ?>
                                    </span>
                                </div>
                                <?php if ($sid < 231): ?>
                                    <a href="<?= base_url() ?>index.php/teacher/self_inicial/<?= $sid ?>"
                                       class="btn btn-sm btn-primary font-weight-bold">
                                        <i class="fa fa-edit mr-1"></i> Llenar Autoevaluaciones
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url() ?>index.php/teacher/self_appraisal/<?= $sid ?>"
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
                                            <th class="text-center" style="width:160px">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $i => $st):
                                            $has_self = isset($selfs_ph[$st['student_id']]);
                                        ?>
                                        <tr>
                                            <td class="text-muted"><?= $i + 1 ?></td>
                                            <td class="font-weight-bold text-dark-75"><?= esc($st['student']) ?></td>
                                            <td class="text-center">
                                                <?php if ($has_self): ?>
                                                    <span class="label label-light-success label-inline font-weight-bold">
                                                        <i class="fa fa-check mr-1"></i> Entregado
                                                    </span>
                                                <?php else: ?>
                                                    <span class="label label-light-danger label-inline font-weight-bold">
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
                        <?php endfor; ?>
                        </div><!-- /tab-content autoevaluaciones -->

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
            // sid puede ser "123_t1" o "123_t2" — buscamos los collapsibles con ese prefijo exacto
            var collapses = document.querySelectorAll('[id^="collapse_' + sid + '_"]');

            if (state === 'expanded') {
                collapses.forEach(function (el) { el.classList.remove('show'); });
                btn.setAttribute('data-state', 'collapsed');
                btn.innerHTML = '<i class="fa fa-expand-alt mr-1"></i> Expandir todo';
            } else {
                collapses.forEach(function (el) { el.classList.add('show'); });
                btn.setAttribute('data-state', 'expanded');
                btn.innerHTML = '<i class="fa fa-compress-alt mr-1"></i> Colapsar todo';
            }
        });
    });
})();
</script>
