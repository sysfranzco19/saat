<?php
$session = session();
?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Education-->
        <div class="d-flex flex-column flex-md-row">
            <!--begin::Aside-->
            <div class="flex-md-row-auto w-md-275px w-xl-325px">
                <div class="card card-custom gutter-b border-0 shadow-sm">
                    <!--begin::Body-->
                    <div class="card-body p-0 d-flex flex-column">
                        <!--begin::Header-->
                        <div class="bgi-no-repeat bgi-size-cover rounded-top w-100 d-flex flex-column justify-content-end align-items-center mb-8"
                            style="background-image: url(https://tiquipaya.edu.bo/download/coltiqui.jpg); height: 250px;">
                            <div class="d-flex flex-column align-items-center mb-5">
                                <a href="https://colegiotiquipaya.edu.bo/" target="_blank"
                                    class="text-white font-weight-bolder font-size-h3 m-0 pb-1"
                                    style="text-shadow: 0 2px 4px rgba(0,0,0,0.6);">Colegio Tiquipaya</a>
                                <div class="font-weight-bold text-white font-size-lg bg-dark-o-40 px-3 py-1 rounded-pill"
                                    style="backdrop-filter: blur(2px);">
                                    <?= isset($phase_name) ? $phase_name : '' ?>
                                </div>
                            </div>
                        </div>
                        <!--end::Header-->

                        <!-- Nav -->
                        <div class="px-6 pb-10 flex-grow-1 d-flex flex-column justify-content-start">
                            <h4 class="text-dark font-weight-bolder mb-5">Información del Estudiante</h4>
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40 symbol-light-primary mr-3">
                                    <span class="symbol-label">
                                        <i class="flaticon-user text-primary"></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-dark-75 font-weight-bolder font-size-sm">Estudiante</span>
                                    <span class="text-muted font-weight-bold font-size-xs"><?= $student_name ?></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40 symbol-light-success mr-3">
                                    <span class="symbol-label">
                                        <i class="flaticon-map text-success"></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-dark-75 font-weight-bolder font-size-sm">Curso</span>
                                    <span class="text-muted font-weight-bold font-size-xs"><?= $curso ?></span>
                                </div>
                            </div>
                            <a href="<?= base_url() . $login_type ?>/dashboard"
                                class="btn btn-primary font-weight-bolder mt-5">
                                <i class="ki ki-long-arrow-back icon-sm"></i> Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Aside-->

            <!--begin::Content-->
            <div class="flex-row-fluid ml-md-8">
                <h4 class="mb-5 text-dark font-weight-bold">Historial de Comportamiento</h4>

                <!-- Guía del Sistema de Evaluación Conductual -->
                <div class="card card-custom gutter-b mb-8" style="border: 2px dashed #3699FF; background: #f0f8ff;">
                    <div class="card-body py-4 px-5">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center">
                                <i class="flaticon2-information text-primary icon-2x mr-4"></i>
                                <div>
                                    <div class="font-weight-bolder text-dark font-size-h6 mb-1">Sistema de Evaluación Conductual</div>
                                    <div class="text-muted font-weight-bold font-size-sm">Conoce cómo se calcula la nota del Ser, qué acciones suman o restan puntos y los niveles de alerta.</div>
                                </div>
                            </div>
                            <button class="btn btn-primary font-weight-bolder mt-3 mt-md-0" data-toggle="collapse" data-target="#guia_sistema" aria-expanded="false">
                                <i class="flaticon2-down icon-sm mr-1" id="guia_icon"></i> Ver reglas
                            </button>
                        </div>
                    </div>
                    <div id="guia_sistema" class="collapse">
                        <div class="separator separator-solid mx-5"></div>
                        <div class="card-body pt-0">

                            <!-- Fórmula -->
                            <div class="alert alert-custom alert-light-primary border border-primary rounded p-5 mb-6">
                                <div class="alert-text text-center">
                                    <span class="font-weight-bold font-size-lg text-dark">Cada estudiante inicia el trimestre con </span>
                                    <span class="font-weight-boldest font-size-h4 text-primary">10 puntos</span>
                                    <span class="font-weight-bold font-size-lg text-dark"> por materia.</span>
                                    <div class="mt-3 font-size-h6 font-weight-bold text-dark-75">
                                        Puntaje Inicial (10) + Pts. Ganados &minus; Pts. Restados = <span class="text-primary">Nota del Ser</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <!-- Acciones que SUMAN -->
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <div class="bg-light-success rounded p-5 h-100">
                                        <div class="d-flex align-items-center mb-4">
                                            <i class="flaticon-star text-success icon-xl mr-3"></i>
                                            <div>
                                                <div class="font-weight-boldest text-success font-size-lg">Acciones que SUMAN</div>
                                                <div class="font-weight-bold text-success font-size-sm">+0.5 pts cada una</div>
                                            </div>
                                        </div>
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-check-mark text-success mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Participación proactiva y destacada en clase</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-check-mark text-success mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Demostrar honestidad e integridad</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-check-mark text-success mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Liderazgo o resiliencia ante desafíos</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-check-mark text-success mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Solidaridad, empatía o apoyo a compañeros</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-check-mark text-success mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Esfuerzo notable en el aprendizaje</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-check-mark text-success mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Trabajo en equipo efectivo y escucha respetuosa</span></li>
                                            <li class="d-flex align-items-start mb-0"><i class="flaticon2-check-mark text-success mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Admitir responsabilidad y demostrar cambio de actitud</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Acciones que RESTAN -->
                                <div class="col-md-6">
                                    <div class="bg-light-danger rounded p-5 h-100">
                                        <div class="d-flex align-items-center mb-4">
                                            <i class="flaticon2-warning text-danger icon-xl mr-3"></i>
                                            <div>
                                                <div class="font-weight-boldest text-danger font-size-lg">Acciones que RESTAN</div>
                                                <div class="font-weight-bold text-danger font-size-sm">-0.5 pts cada una</div>
                                            </div>
                                        </div>
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-delete text-danger mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Comer en clases</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-delete text-danger mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Uso no autorizado de celular u otros dispositivos</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-delete text-danger mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Uso incorrecto del uniforme</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-delete text-danger mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Asistir sin el material escolar requerido</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-delete text-danger mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Llegada tarde al aula</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-delete text-danger mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Indisciplina</span></li>
                                            <li class="d-flex align-items-start mb-2"><i class="flaticon2-delete text-danger mr-2 mt-1" style="font-size:10px;"></i><span class="font-weight-bold text-dark-75 font-size-sm">Otros que falten a los valores institucionales</span></li>
                                        </ul>
                                        <div class="separator separator-dashed my-4"></div>
                                        <div class="font-weight-boldest text-danger font-size-sm mb-2">Faltas Graves: -3 pts</div>
                                        <p class="text-dark-75 font-size-sm mb-0">Las faltas graves según el reglamento descuentan 3 pts en la materia afectada. Si ocurren fuera del aula, descuentan 3 pts <strong>en todas las materias</strong>.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Niveles de Alerta -->
                            <h5 class="font-weight-bolder text-dark mb-4">Niveles de Alerta por Materia</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="card bg-light-warning border border-warning rounded p-4 h-100">
                                        <div class="text-center mb-3">
                                            <span class="font-weight-boldest font-size-h3 text-warning">7 pts o menos</span>
                                        </div>
                                        <p class="text-dark-75 font-weight-bold font-size-sm text-center mb-0">Se solicita al padre revisar el SAAT y apersonarse al colegio en horario de entrevista docente.</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="card bg-light-danger border border-danger rounded p-4 h-100">
                                        <div class="text-center mb-3">
                                            <span class="font-weight-boldest font-size-h3 text-danger">5 pts o menos</span>
                                        </div>
                                        <p class="text-dark-75 font-weight-bold font-size-sm text-center mb-0">Es obligatorio apersonarse al colegio. Se notifica también al consejero para programar una reunión.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border rounded p-4 h-100" style="background:#3f0000; border-color:#7a0000 !important;">
                                        <div class="text-center mb-3">
                                            <span class="font-weight-boldest font-size-h3 text-white">1 pt o menos</span>
                                        </div>
                                        <p class="font-weight-bold font-size-sm text-center mb-0 text-white">Reunión urgente con Dirección Técnica y Comisión Disciplinaria. Suspensión de un día.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Fin Guía -->
                <script>
                    $('#guia_sistema').on('show.bs.collapse', function () {
                        $('[data-target="#guia_sistema"]').html('<i class="flaticon2-up icon-sm mr-1"></i> Ocultar reglas');
                    }).on('hide.bs.collapse', function () {
                        $('[data-target="#guia_sistema"]').html('<i class="flaticon2-down icon-sm mr-1"></i> Ver reglas');
                    });
                </script>

                <?php if (isset($students) && count($students) > 1): ?>
                    <!-- Selector de Hijos -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <form action="" method="get">
                                <label class="font-weight-bolder text-dark">Seleccionar Estudiante:</label>
                                <select class="form-control" onchange="location = this.value;">
                                    <?php foreach ($students as $stu): ?>
                                        <option value="<?= base_url(); ?>parents/gamified_behavior/<?= $stu['student_id'] ?>"
                                            <?= ($stu['student_id'] == $student_id) ? 'selected' : '' ?>>
                                            <?= $stu['student'] ?> (<?= $stu['completo'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <h4 class="mb-5 text-dark font-weight-bold">Historial de: <?= $student_name ?></h4>

                <!-- ══ TABS T1 / T2 / T3 ══ -->
                <?php
                $gb_neg1 = $global_negative_t1 ?? 0; $gb_pos1 = $global_positive_t1 ?? 0;
                $gb_neg2 = $global_negativa_t2 ?? 0; $gb_pos2 = $global_positiva_t2 ?? 0;
                $gb_neg3 = $global_negativa_t3 ?? 0; $gb_pos3 = $global_positiva_t3 ?? 0;
                ?>
                <div class="card card-custom gutter-b">
                    <div class="card-header card-header-tabs-line">
                        <div class="card-toolbar w-100">
                            <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-primary nav-tabs-line-2x w-100" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active font-weight-bold" data-toggle="tab" href="#gb_t1_<?= $student_id ?>">
                                        <i class="fa fa-history mr-2"></i> Primer Trimestre
                                        <?php if ($gb_pos1 > 0): ?><span class="badge badge-success ml-1"><?= $gb_pos1 ?></span><?php endif; ?>
                                        <?php if ($gb_neg1 > 0): ?><span class="badge badge-warning ml-1"><?= $gb_neg1 ?></span><?php endif; ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold" data-toggle="tab" href="#gb_t2_<?= $student_id ?>">
                                        <i class="fa fa-history mr-2"></i> Segundo Trimestre
                                        <?php if ($gb_pos2 > 0): ?><span class="badge badge-success ml-1"><?= $gb_pos2 ?></span><?php endif; ?>
                                        <?php if ($gb_neg2 > 0): ?><span class="badge badge-warning ml-1"><?= $gb_neg2 ?></span><?php endif; ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link font-weight-bold" data-toggle="tab" href="#gb_t3_<?= $student_id ?>">
                                        <i class="fa fa-history mr-2"></i> Tercer Trimestre
                                        <?php if ($gb_pos3 > 0): ?><span class="badge badge-success ml-1"><?= $gb_pos3 ?></span><?php endif; ?>
                                        <?php if ($gb_neg3 > 0): ?><span class="badge badge-warning ml-1"><?= $gb_neg3 ?></span><?php endif; ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">

                        <!-- ══ T1: behavior_log — por materia ══ -->
                        <div class="tab-pane fade show active" id="gb_t1_<?= $student_id ?>">

                <!-- ── Resumen Global T1 ── -->
                <div class="row mb-5">
                    <div class="col-lg-6">
                        <div class="card card-custom bg-light-success card-stretch gutter-b">
                            <div class="card-body">
                                <h3 class="card-title font-weight-bolder text-success">
                                    <i class="flaticon-star text-success icon-xl mr-2"></i> Acciones Positivas
                                </h3>
                                <div class="text-dark font-weight-bold font-size-h1"><?= $global_positive_t1 ?? 0 ?></div>
                                <div class="text-muted font-weight-bold font-size-lg mt-1">Total de buenas acciones registradas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-custom bg-light-danger card-stretch gutter-b">
                            <div class="card-body">
                                <h3 class="card-title font-weight-bolder text-danger">
                                    <i class="flaticon2-warning text-danger icon-xl mr-2"></i> Llamadas de Atención
                                </h3>
                                <div class="text-dark font-weight-bold font-size-h1"><?= $global_negative_t1 ?? 0 ?></div>
                                <div class="text-muted font-weight-bold font-size-lg mt-1">Total de incidencias acumuladas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desglose por Materia T1 -->
                <h4 class="font-weight-bold text-dark mb-4 mt-6">Desglose por Materias</h4>
                <div class="card card-custom gutter-b shadow-sm border-0">
                    <div class="card-body p-4">
                        <?php if (!empty($subject_stats_t1)): ?>
                            <?php foreach ($subject_stats_t1 as $key => $ss): ?>
                                <?php
                                $scoreColor = ($ss['ser_score'] >= 8) ? 'success' : (($ss['ser_score'] >= 5) ? 'warning' : 'danger');
                                $isLast     = ($key === array_key_last($subject_stats_t1));
                                if ($ss['ser_score'] <= 1)      $alertBadge = '<span class="badge badge-danger font-weight-bold px-3 py-1 mt-1"><i class="flaticon2-warning text-white icon-xs mr-1"></i> Alerta Crítica</span>';
                                elseif ($ss['ser_score'] <= 5)  $alertBadge = '<span class="badge badge-danger font-weight-bold px-3 py-1 mt-1" style="background:#c0392b;"><i class="flaticon2-warning text-white icon-xs mr-1"></i> Alerta 2 enviada</span>';
                                elseif ($ss['ser_score'] <= 7)  $alertBadge = '<span class="badge badge-warning font-weight-bold px-3 py-1 mt-1"><i class="flaticon2-warning text-dark icon-xs mr-1"></i> Alerta 1 enviada</span>';
                                else $alertBadge = '';
                                ?>
                                <div class="d-flex flex-wrap align-items-center justify-content-between <?= !$isLast ? 'mb-4 pb-4 border-bottom' : '' ?>">
                                    <div class="d-flex align-items-center w-100 w-md-50 mb-3 mb-md-0">
                                        <div class="symbol symbol-40 symbol-light-<?= $scoreColor ?> mr-3">
                                            <span class="symbol-label font-size-h5 font-weight-boldest text-<?= $scoreColor ?>"><?= $ss['ser_score'] ?></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark-75 font-weight-bold font-size-lg mb-0"><?= $ss['name'] ?></span>
                                            <span class="text-muted font-size-sm">Prof. <?= $ss['teacher'] ?></span>
                                            <?= $alertBadge ?>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between justify-content-md-end w-100 w-md-50">
                                        <div class="d-flex mr-5">
                                            <div class="d-flex align-items-center mr-4">
                                                <i class="flaticon-star text-success icon-md mr-1"></i>
                                                <span class="font-weight-bolder text-dark-75 font-size-lg"><?= $ss['positive_count'] ?></span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="flaticon2-warning text-danger icon-md mr-1"></i>
                                                <span class="font-weight-bolder text-dark-75 font-size-lg mr-1"><?= $ss['negative_count'] ?></span>
                                                <span class="text-muted font-size-sm font-weight-bold">Incidencias</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light-primary font-weight-bolder px-3 py-1"
                                            data-toggle="modal" data-target="#modal_t1_<?= $ss['subject_id'] ?>">
                                            <i class="flaticon-eye icon-sm mr-1"></i> Detalles
                                        </button>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_t1_<?= $ss['subject_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-between align-items-center border-0 pb-0">
                                                <h4 class="modal-title font-weight-bolder text-dark">T1 — <?= $ss['name'] ?></h4>
                                                <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="timeline timeline-6 mt-3 p-5">
                                                    <?php $hasLogs = false; foreach ($timeline_t1 as $log): if ($log['subject_name'] != $ss['name']) continue; $hasLogs = true;
                                                        $iconClass = $log['type'] == 'positive' ? 'text-success' : 'text-danger';
                                                        $bgClass   = $log['type'] == 'positive' ? 'bg-light-success' : 'bg-light-danger'; ?>
                                                        <div class="timeline-item align-items-start">
                                                            <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg"><?= date('d M', strtotime($log['created_at'])) ?></div>
                                                            <div class="timeline-badge"><i class="fa fa-genderless <?= $iconClass ?> icon-xl"></i></div>
                                                            <div class="timeline-content d-flex">
                                                                <span class="mr-3 <?= $iconClass ?> font-size-h3 font-weight-bolder"><?= $log['type'] == 'positive' ? '+' : '-' ?><?= abs($log['points']) ?></span>
                                                                <div class="d-flex flex-column w-100">
                                                                    <span class="font-weight-bolder text-dark-75"><?= $log['name'] ?></span>
                                                                    <span class="text-muted font-size-sm">Prof. <?= $log['teacher_name'] ?> &bull; <?= date('H:i', strtotime($log['created_at'])) ?></span>
                                                                    <p class="text-dark-50 p-3 <?= $bgClass ?> rounded mt-1 mb-0"><?= !empty($log['observation']) ? htmlspecialchars($log['observation']) : 'Sin observación.' ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; if (!$hasLogs): ?>
                                                        <div class="text-center text-muted p-10 font-weight-bold">Sin incidencias en esta materia.</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted p-5">Sin incidencias registradas en T1.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Muro T1 -->
                <div class="card card-custom gutter-b">
                    <div class="card-header align-items-center border-0 mt-4">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="font-weight-bolder text-dark">Muro de Actividad — T1</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Registro cronológico behavior_log</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        <div class="timeline timeline-6 mt-3">
                            <?php if (!empty($timeline_t1)): foreach ($timeline_t1 as $log):
                                $iconClass = $log['type'] == 'positive' ? 'text-success' : 'text-danger';
                                $bgClass   = $log['type'] == 'positive' ? 'bg-light-success' : 'bg-light-danger'; ?>
                                <div class="timeline-item align-items-start">
                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg"><?= date('H:i', strtotime($log['created_at'])) ?></div>
                                    <div class="timeline-badge"><i class="fa fa-genderless <?= $iconClass ?> icon-xl"></i></div>
                                    <div class="timeline-content d-flex">
                                        <span class="mr-2 <?= $iconClass ?> font-size-h3 font-weight-bolder"><?= $log['type'] == 'positive' ? '+' : '-' ?><?= abs($log['points']) ?></span>
                                        <div class="d-flex flex-column">
                                            <span class="font-weight-bolder text-dark-75 font-size-lg"><?= $log['subject_name'] ?> <span class="text-muted font-size-sm font-weight-normal ml-2"><?= date('d M Y', strtotime($log['created_at'])) ?></span></span>
                                            <p class="text-dark-50 font-weight-normal mb-1">Prof. <?= $log['teacher_name'] ?></p>
                                            <p class="text-dark-50 p-2 <?= $bgClass ?> rounded"><strong><?= $log['name'] ?>:</strong> <?= !empty($log['observation']) ? htmlspecialchars($log['observation']) : 'Sin observación.' ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="text-center text-muted p-10">Sin actividad registrada en T1.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                </div><!-- /tab-pane T1 -->

                <!-- ══ T2: incidencia_registro phase 2 — por maestro ══ -->
                <div class="tab-pane fade" id="gb_t2_<?= $student_id ?>">
                <?php
                $ts_t2  = $teacher_stats_t2 ?? [];
                $tl_t2  = $timeline_t2 ?? [];
                $gpos2  = $global_positiva_t2 ?? 0;
                $gneg2  = $global_negativa_t2 ?? 0;
                ?>
                <!-- Resumen Global T2 -->
                <div class="row mb-5">
                    <div class="col-lg-6">
                        <div class="card card-custom bg-light-success card-stretch gutter-b">
                            <div class="card-body">
                                <h3 class="card-title font-weight-bolder text-success">
                                    <i class="flaticon-star text-success icon-xl mr-2"></i> Acciones Positivas
                                </h3>
                                <div class="text-dark font-weight-bold font-size-h1"><?= $gpos2 ?></div>
                                <div class="text-muted font-weight-bold font-size-lg mt-1">Total de buenas acciones registradas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-custom bg-light-danger card-stretch gutter-b">
                            <div class="card-body">
                                <h3 class="card-title font-weight-bolder text-danger">
                                    <i class="flaticon2-warning text-danger icon-xl mr-2"></i> Llamadas de Atención
                                </h3>
                                <div class="text-dark font-weight-bold font-size-h1"><?= $gneg2 ?></div>
                                <div class="text-muted font-weight-bold font-size-lg mt-1">Total de incidencias acumuladas</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Desglose por Maestro T2 -->
                <h4 class="font-weight-bold text-dark mb-4 mt-6">Desglose por Maestros</h4>
                <div class="card card-custom gutter-b shadow-sm border-0">
                    <div class="card-body p-4">
                        <?php if (!empty($ts_t2)): ?>
                            <?php foreach ($ts_t2 as $tkey => $ts): ?>
                                <?php
                                $nota2      = (float) $ts['nota'];
                                $sc2        = ($nota2 >= 8) ? 'success' : (($nota2 >= 5) ? 'warning' : 'danger');
                                $isLast2    = ($tkey === array_key_last($ts_t2));
                                if ($nota2 <= 1)      $ab2 = '<span class="badge badge-danger font-weight-bold px-3 py-1 mt-1"><i class="flaticon2-warning text-white icon-xs mr-1"></i> Alerta Crítica</span>';
                                elseif ($nota2 <= 5)  $ab2 = '<span class="badge badge-danger font-weight-bold px-3 py-1 mt-1" style="background:#c0392b;"><i class="flaticon2-warning text-white icon-xs mr-1"></i> Alerta 2 enviada</span>';
                                elseif ($nota2 <= 7)  $ab2 = '<span class="badge badge-warning font-weight-bold px-3 py-1 mt-1"><i class="flaticon2-warning text-dark icon-xs mr-1"></i> Alerta 1 enviada</span>';
                                else $ab2 = '';
                                ?>
                                <div class="d-flex flex-wrap align-items-center justify-content-between <?= !$isLast2 ? 'mb-4 pb-4 border-bottom' : '' ?>">
                                    <div class="d-flex align-items-center w-100 w-md-50 mb-3 mb-md-0">
                                        <div class="symbol symbol-40 symbol-light-<?= $sc2 ?> mr-3">
                                            <span class="symbol-label font-size-h5 font-weight-boldest text-<?= $sc2 ?>"><?= $nota2 ?></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark-75 font-weight-bold font-size-lg mb-0"><?= htmlspecialchars($ts['teacher_name']) ?></span>
                                            <span class="text-muted font-size-sm"><?= htmlspecialchars($ts['subjects']) ?></span>
                                            <?= $ab2 ?>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between justify-content-md-end w-100 w-md-50">
                                        <div class="d-flex mr-5">
                                            <div class="d-flex align-items-center mr-4">
                                                <i class="flaticon-star text-success icon-md mr-1"></i>
                                                <span class="font-weight-bolder text-dark-75 font-size-lg"><?= $ts['positiva'] ?></span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="flaticon2-warning text-danger icon-md mr-1"></i>
                                                <span class="font-weight-bolder text-dark-75 font-size-lg mr-1"><?= $ts['negativa'] ?></span>
                                                <span class="text-muted font-size-sm font-weight-bold">Incidencias</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light-primary font-weight-bolder px-3 py-1"
                                            data-toggle="modal" data-target="#modal_t2_<?= $ts['teacher_id'] ?>">
                                            <i class="flaticon-eye icon-sm mr-1"></i> Detalles
                                        </button>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_t2_<?= $ts['teacher_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-between align-items-center border-0 pb-0">
                                                <h4 class="modal-title font-weight-bolder text-dark">T2 — <?= htmlspecialchars($ts['teacher_name']) ?></h4>
                                                <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="timeline timeline-6 mt-3 p-5">
                                                    <?php $hasLogs2 = false; foreach ($tl_t2 as $log): if ((int)$log['teacher_id'] !== (int)$ts['teacher_id']) continue; $hasLogs2 = true;
                                                        $ic2 = $log['tipo'] == 'positiva' ? 'text-success' : 'text-danger';
                                                        $bg2 = $log['tipo'] == 'positiva' ? 'bg-light-success' : 'bg-light-danger'; ?>
                                                        <div class="timeline-item align-items-start">
                                                            <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg"><?= date('d M', strtotime($log['created_at'])) ?></div>
                                                            <div class="timeline-badge"><i class="fa fa-genderless <?= $ic2 ?> icon-xl"></i></div>
                                                            <div class="timeline-content d-flex">
                                                                <span class="mr-3 <?= $ic2 ?> font-size-h3 font-weight-bolder"><?= $log['tipo'] == 'positiva' ? '+' : '-' ?></span>
                                                                <div class="d-flex flex-column w-100">
                                                                    <span class="font-weight-bolder text-dark-75"><?= htmlspecialchars($log['nombre']) ?></span>
                                                                    <span class="text-muted font-size-sm"><?= htmlspecialchars($log['subject_name']) ?> &bull; <?= date('H:i', strtotime($log['created_at'])) ?></span>
                                                                    <p class="text-dark-50 p-3 <?= $bg2 ?> rounded mt-1 mb-0"><?= !empty($log['observacion']) ? htmlspecialchars($log['observacion']) : 'Sin observación.' ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; if (!$hasLogs2): ?>
                                                        <div class="text-center text-muted p-10 font-weight-bold">Sin incidencias con este maestro.</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted p-5">Sin incidencias registradas en T2.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Muro T2 -->
                <div class="card card-custom gutter-b">
                    <div class="card-header align-items-center border-0 mt-4">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="font-weight-bolder text-dark">Muro de Actividad — T2</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Registro cronológico incidencia_registro</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        <div class="timeline timeline-6 mt-3">
                            <?php if (!empty($tl_t2)): foreach ($tl_t2 as $log):
                                $ic2 = $log['tipo'] == 'positiva' ? 'text-success' : 'text-danger';
                                $bg2 = $log['tipo'] == 'positiva' ? 'bg-light-success' : 'bg-light-danger'; ?>
                                <div class="timeline-item align-items-start">
                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg"><?= date('H:i', strtotime($log['created_at'])) ?></div>
                                    <div class="timeline-badge"><i class="fa fa-genderless <?= $ic2 ?> icon-xl"></i></div>
                                    <div class="timeline-content d-flex">
                                        <span class="mr-2 <?= $ic2 ?> font-size-h3 font-weight-bolder"><?= $log['tipo'] == 'positiva' ? '+' : '-' ?></span>
                                        <div class="d-flex flex-column">
                                            <span class="font-weight-bolder text-dark-75 font-size-lg"><?= htmlspecialchars($log['subject_name']) ?> <span class="text-muted font-size-sm font-weight-normal ml-2"><?= date('d M Y', strtotime($log['created_at'])) ?></span></span>
                                            <p class="text-dark-50 font-weight-normal mb-1">Prof. <?= htmlspecialchars($log['teacher_name']) ?></p>
                                            <p class="text-dark-50 p-2 <?= $bg2 ?> rounded"><strong><?= htmlspecialchars($log['nombre']) ?>:</strong> <?= !empty($log['observacion']) ? htmlspecialchars($log['observacion']) : 'Sin observación.' ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="text-center text-muted p-10">Sin actividad registrada en T2.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                </div><!-- /tab-pane T2 -->

                <!-- ══ T3: incidencia_registro phase 3 — por maestro ══ -->
                <div class="tab-pane fade" id="gb_t3_<?= $student_id ?>">
                <?php
                $ts_t3  = $teacher_stats_t3 ?? [];
                $tl_t3  = $timeline_t3 ?? [];
                $gpos3  = $global_positiva_t3 ?? 0;
                $gneg3  = $global_negativa_t3 ?? 0;
                ?>
                <!-- Resumen Global T3 -->
                <div class="row mb-5">
                    <div class="col-lg-6">
                        <div class="card card-custom bg-light-success card-stretch gutter-b">
                            <div class="card-body">
                                <h3 class="card-title font-weight-bolder text-success">
                                    <i class="flaticon-star text-success icon-xl mr-2"></i> Acciones Positivas
                                </h3>
                                <div class="text-dark font-weight-bold font-size-h1"><?= $gpos3 ?></div>
                                <div class="text-muted font-weight-bold font-size-lg mt-1">Total de buenas acciones registradas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-custom bg-light-danger card-stretch gutter-b">
                            <div class="card-body">
                                <h3 class="card-title font-weight-bolder text-danger">
                                    <i class="flaticon2-warning text-danger icon-xl mr-2"></i> Llamadas de Atención
                                </h3>
                                <div class="text-dark font-weight-bold font-size-h1"><?= $gneg3 ?></div>
                                <div class="text-muted font-weight-bold font-size-lg mt-1">Total de incidencias acumuladas</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Desglose por Maestro T3 -->
                <h4 class="font-weight-bold text-dark mb-4 mt-6">Desglose por Maestros</h4>
                <div class="card card-custom gutter-b shadow-sm border-0">
                    <div class="card-body p-4">
                        <?php if (!empty($ts_t3)): ?>
                            <?php foreach ($ts_t3 as $tkey => $ts): ?>
                                <?php
                                $nota3      = (float) $ts['nota'];
                                $sc3        = ($nota3 >= 8) ? 'success' : (($nota3 >= 5) ? 'warning' : 'danger');
                                $isLast3    = ($tkey === array_key_last($ts_t3));
                                if ($nota3 <= 1)      $ab3 = '<span class="badge badge-danger font-weight-bold px-3 py-1 mt-1"><i class="flaticon2-warning text-white icon-xs mr-1"></i> Alerta Crítica</span>';
                                elseif ($nota3 <= 5)  $ab3 = '<span class="badge badge-danger font-weight-bold px-3 py-1 mt-1" style="background:#c0392b;"><i class="flaticon2-warning text-white icon-xs mr-1"></i> Alerta 2 enviada</span>';
                                elseif ($nota3 <= 7)  $ab3 = '<span class="badge badge-warning font-weight-bold px-3 py-1 mt-1"><i class="flaticon2-warning text-dark icon-xs mr-1"></i> Alerta 1 enviada</span>';
                                else $ab3 = '';
                                ?>
                                <div class="d-flex flex-wrap align-items-center justify-content-between <?= !$isLast3 ? 'mb-4 pb-4 border-bottom' : '' ?>">
                                    <div class="d-flex align-items-center w-100 w-md-50 mb-3 mb-md-0">
                                        <div class="symbol symbol-40 symbol-light-<?= $sc3 ?> mr-3">
                                            <span class="symbol-label font-size-h5 font-weight-boldest text-<?= $sc3 ?>"><?= $nota3 ?></span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-dark-75 font-weight-bold font-size-lg mb-0"><?= htmlspecialchars($ts['teacher_name']) ?></span>
                                            <span class="text-muted font-size-sm"><?= htmlspecialchars($ts['subjects']) ?></span>
                                            <?= $ab3 ?>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between justify-content-md-end w-100 w-md-50">
                                        <div class="d-flex mr-5">
                                            <div class="d-flex align-items-center mr-4">
                                                <i class="flaticon-star text-success icon-md mr-1"></i>
                                                <span class="font-weight-bolder text-dark-75 font-size-lg"><?= $ts['positiva'] ?></span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <i class="flaticon2-warning text-danger icon-md mr-1"></i>
                                                <span class="font-weight-bolder text-dark-75 font-size-lg mr-1"><?= $ts['negativa'] ?></span>
                                                <span class="text-muted font-size-sm font-weight-bold">Incidencias</span>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light-primary font-weight-bolder px-3 py-1"
                                            data-toggle="modal" data-target="#modal_t3_<?= $ts['teacher_id'] ?>">
                                            <i class="flaticon-eye icon-sm mr-1"></i> Detalles
                                        </button>
                                    </div>
                                </div>
                                <div class="modal fade" id="modal_t3_<?= $ts['teacher_id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-between align-items-center border-0 pb-0">
                                                <h4 class="modal-title font-weight-bolder text-dark">T3 — <?= htmlspecialchars($ts['teacher_name']) ?></h4>
                                                <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="timeline timeline-6 mt-3 p-5">
                                                    <?php $hasLogs3 = false; foreach ($tl_t3 as $log): if ((int)$log['teacher_id'] !== (int)$ts['teacher_id']) continue; $hasLogs3 = true;
                                                        $ic3 = $log['tipo'] == 'positiva' ? 'text-success' : 'text-danger';
                                                        $bg3 = $log['tipo'] == 'positiva' ? 'bg-light-success' : 'bg-light-danger'; ?>
                                                        <div class="timeline-item align-items-start">
                                                            <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg"><?= date('d M', strtotime($log['created_at'])) ?></div>
                                                            <div class="timeline-badge"><i class="fa fa-genderless <?= $ic3 ?> icon-xl"></i></div>
                                                            <div class="timeline-content d-flex">
                                                                <span class="mr-3 <?= $ic3 ?> font-size-h3 font-weight-bolder"><?= $log['tipo'] == 'positiva' ? '+' : '-' ?></span>
                                                                <div class="d-flex flex-column w-100">
                                                                    <span class="font-weight-bolder text-dark-75"><?= htmlspecialchars($log['nombre']) ?></span>
                                                                    <span class="text-muted font-size-sm"><?= htmlspecialchars($log['subject_name']) ?> &bull; <?= date('H:i', strtotime($log['created_at'])) ?></span>
                                                                    <p class="text-dark-50 p-3 <?= $bg3 ?> rounded mt-1 mb-0"><?= !empty($log['observacion']) ? htmlspecialchars($log['observacion']) : 'Sin observación.' ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; if (!$hasLogs3): ?>
                                                        <div class="text-center text-muted p-10 font-weight-bold">Sin incidencias con este maestro.</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted p-5">Sin incidencias registradas en T3.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Muro T3 -->
                <div class="card card-custom gutter-b">
                    <div class="card-header align-items-center border-0 mt-4">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="font-weight-bolder text-dark">Muro de Actividad — T3</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Registro cronológico incidencia_registro</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        <div class="timeline timeline-6 mt-3">
                            <?php if (!empty($tl_t3)): foreach ($tl_t3 as $log):
                                $ic3 = $log['tipo'] == 'positiva' ? 'text-success' : 'text-danger';
                                $bg3 = $log['tipo'] == 'positiva' ? 'bg-light-success' : 'bg-light-danger'; ?>
                                <div class="timeline-item align-items-start">
                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg"><?= date('H:i', strtotime($log['created_at'])) ?></div>
                                    <div class="timeline-badge"><i class="fa fa-genderless <?= $ic3 ?> icon-xl"></i></div>
                                    <div class="timeline-content d-flex">
                                        <span class="mr-2 <?= $ic3 ?> font-size-h3 font-weight-bolder"><?= $log['tipo'] == 'positiva' ? '+' : '-' ?></span>
                                        <div class="d-flex flex-column">
                                            <span class="font-weight-bolder text-dark-75 font-size-lg"><?= htmlspecialchars($log['subject_name']) ?> <span class="text-muted font-size-sm font-weight-normal ml-2"><?= date('d M Y', strtotime($log['created_at'])) ?></span></span>
                                            <p class="text-dark-50 font-weight-normal mb-1">Prof. <?= htmlspecialchars($log['teacher_name']) ?></p>
                                            <p class="text-dark-50 p-2 <?= $bg3 ?> rounded"><strong><?= htmlspecialchars($log['nombre']) ?>:</strong> <?= !empty($log['observacion']) ? htmlspecialchars($log['observacion']) : 'Sin observación.' ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="text-center text-muted p-10">Sin actividad registrada en T3.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                </div><!-- /tab-pane T3 -->

                        </div><!-- /tab-content -->
                    </div><!-- /card-body -->
                </div><!-- /card tabs -->

            </div>
            <!--end::Content-->
        </div>
        <!--end::Education-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->