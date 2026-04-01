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
                            <h4 class="text-dark font-weight-bolder mb-5">Mi Información</h4>
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

                <!-- Resumen Global -->
                <div class="row mb-5">
                    <div class="col-lg-6">
                        <div class="card card-custom bg-light-success card-stretch gutter-b">
                            <div class="card-body">
                                <h3 class="card-title font-weight-bolder text-success">
                                    <i class="flaticon-star text-success icon-xl mr-2"></i> Puntos Positivos
                                </h3>
                                <div class="text-dark font-weight-bold font-size-h1">
                                    <?= $global_positive ?>
                                </div>
                                <div class="text-muted font-weight-bold font-size-lg mt-1">Total de buenas acciones
                                    registradas</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-custom bg-light-danger card-stretch gutter-b">
                            <div class="card-body">
                                <h3 class="card-title font-weight-bolder text-danger">
                                    <i class="flaticon2-warning text-danger icon-xl mr-2"></i> Llamadas de Atención
                                </h3>
                                <div class="text-dark font-weight-bold font-size-h1">
                                    <?= $global_negative ?>
                                </div>
                                <div class="text-muted font-weight-bold font-size-lg mt-1">Total de incidencias
                                    acumuladas</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desglose por Materia como Lista Compacta -->
                <h4 class="font-weight-bold text-dark mb-4 mt-10">Desglose por Materias</h4>
                <div class="card card-custom gutter-b shadow-sm border-0">
                    <div class="card-body p-4">
                        <?php if (!empty($subject_stats)): ?>
                            <?php foreach ($subject_stats as $key => $ss): ?>
                                <?php
                                // Color del puntaje
                                $scoreColor = ($ss['ser_score'] >= 8) ? 'success' : (($ss['ser_score'] >= 5) ? 'warning' : 'danger');
                                $isLast = ($key === array_key_last($subject_stats));
                                ?>
                                <div
                                    class="d-flex flex-wrap align-items-center justify-content-between <?= !$isLast ? 'mb-4 pb-4 border-bottom' : '' ?>">
                                    <!-- Info Materia y Nota -->
                                    <div class="d-flex align-items-center w-100 w-md-50 mb-3 mb-md-0">
                                        <div class="symbol symbol-40 symbol-light-<?= $scoreColor ?> mr-3">
                                            <span class="symbol-label font-size-h5 font-weight-boldest text-<?= $scoreColor ?>">
                                                <?= $ss['ser_score'] ?>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span
                                                class="text-dark-75 font-weight-bold font-size-lg mb-0"><?= $ss['name'] ?></span>
                                            <span class="text-muted font-size-sm">Prof. <?= $ss['teacher'] ?></span>
                                        </div>
                                    </div>

                                    <!-- Estadísticas e Interacción -->
                                    <div
                                        class="d-flex align-items-center justify-content-between justify-content-md-end w-100 w-md-50">
                                        <div class="d-flex mr-5">
                                            <div class="d-flex align-items-center mr-4"
                                                title="Estrellas: <?= $ss['positive_count'] ?>">
                                                <i class="flaticon-star text-success icon-md mr-1"></i>
                                                <span
                                                    class="font-weight-bolder text-dark-75 font-size-lg"><?= $ss['positive_count'] ?></span>
                                            </div>
                                            <div class="d-flex align-items-center"
                                                title="Incidencias: <?= $ss['negative_count'] ?>">
                                                <i class="flaticon2-warning text-danger icon-md mr-1"></i>
                                                <span class="font-weight-bolder text-dark-75 font-size-lg mr-1"><?= $ss['negative_count'] ?></span>
                                                <span class="text-muted font-size-sm font-weight-bold">Incidencias</span>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-light-primary font-weight-bolder px-3 py-1"
                                            data-toggle="modal" data-target="#modal_details_<?= $ss['subject_id'] ?>">
                                            <i class="flaticon-eye icon-sm mr-1"></i> Detalles
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal de Detalles por Materia -->
                                <div class="modal fade" id="modal_details_<?= $ss['subject_id'] ?>" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div
                                                class="modal-header d-flex justify-content-between align-items-center border-0 pb-0">
                                                <h4 class="modal-title font-weight-bolder text-dark">Detalles:
                                                    <?= $ss['name'] ?>
                                                </h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true" class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="timeline timeline-6 mt-3 p-5">
                                                    <?php
                                                    $hasLogs = false;
                                                    if (!empty($timeline_logs)) {
                                                        foreach ($timeline_logs as $log) {
                                                            if ($log['subject_name'] == $ss['name']) {
                                                                $hasLogs = true;
                                                                $iconClass = 'text-primary';
                                                                $bgClass = 'bg-light-primary';
                                                                if ($log['type'] == 'positive') {
                                                                    $iconClass = 'text-success';
                                                                    $bgClass = 'bg-light-success';
                                                                } else if ($log['type'] == 'negative') {
                                                                    $iconClass = 'text-danger';
                                                                    $bgClass = 'bg-light-danger';
                                                                }
                                                                ?>
                                                                <div class="timeline-item align-items-start">
                                                                    <div
                                                                        class="timeline-label font-weight-bolder text-dark-75 font-size-lg">
                                                                        <?= date('d M', strtotime($log['created_at'])) ?>
                                                                    </div>
                                                                    <div class="timeline-badge">
                                                                        <i class="fa fa-genderless <?= $iconClass ?> icon-xl"></i>
                                                                    </div>
                                                                    <div class="timeline-content d-flex">
                                                                        <span
                                                                            class="mr-3 <?= $iconClass ?> font-size-h3 font-weight-bolder">
                                                                            <?= $log['type'] == 'positive' ? '+' : ($log['type'] == 'negative' ? '-' : '') ?>
                                                                            <?= abs($log['points']) ?>
                                                                        </span>
                                                                        <div class="d-flex flex-column w-100">
                                                                            <span class="font-weight-bolder text-dark-75 font-size-lg">
                                                                                <?= $log['name'] ?>
                                                                            </span>
                                                                            <p class="text-dark-50 p-3 <?= $bgClass ?> rounded mt-2 mb-0">
                                                                                <?= !empty($log['observation']) ? $log['observation'] : 'Sin observación adicional.' ?>
                                                                            </p>
                                                                            <span class="text-muted font-size-sm mt-1">Registrado por:
                                                                                <?= $log['teacher_name'] ?> -
                                                                                <?= date('H:i', strtotime($log['created_at'])) ?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    if (!$hasLogs): ?>
                                                        <div class="text-center text-muted p-10 font-weight-bold">No hay incidencias
                                                            registradas en esta materia.</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light-primary font-weight-bold"
                                                    data-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted p-5">
                                No tienes materias habilitadas para el control de comportamiento.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Linea de Tiempo (Muro) -->
                <div class="card card-custom gutter-b">
                    <div class="card-header align-items-center border-0 mt-4">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="font-weight-bolder text-dark">Muro de Actividad Reciente</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Registro cronológico de tu
                                comportamiento</span>
                        </h3>
                    </div>
                    <div class="card-body pt-4">
                        <div class="timeline timeline-6 mt-3">
                            <?php if (!empty($timeline_logs)): ?>
                                <?php foreach ($timeline_logs as $log): ?>
                                    <?php
                                    $iconClass = 'text-primary';
                                    $bgClass = 'bg-light-primary';
                                    if ($log['type'] == 'positive') {
                                        $iconClass = 'text-success';
                                        $bgClass = 'bg-light-success';
                                    } else if ($log['type'] == 'negative') {
                                        $iconClass = 'text-danger';
                                        $bgClass = 'bg-light-danger';
                                    }
                                    ?>
                                    <!--begin::Item-->
                                    <div class="timeline-item align-items-start">
                                        <!--begin::Label-->
                                        <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">
                                            <?= date('H:i', strtotime($log['created_at'])) ?>
                                        </div>
                                        <!--end::Label-->
                                        <!--begin::Badge-->
                                        <div class="timeline-badge">
                                            <i class="fa fa-genderless <?= $iconClass ?> icon-xl"></i>
                                        </div>
                                        <!--end::Badge-->
                                        <!--begin::Content-->
                                        <div class="timeline-content d-flex">
                                            <span class="mr-2 <?= $iconClass ?> font-size-h3 font-weight-bolder">
                                                <?= $log['type'] == 'positive' ? '+' : ($log['type'] == 'negative' ? '-' : '') ?>
                                                <?= abs($log['points']) ?>
                                            </span>
                                            <div class="d-flex flex-column">
                                                <span class="font-weight-bolder text-dark-75 font-size-lg">
                                                    <?= $log['subject_name'] ?>
                                                    <span class="text-muted font-size-sm font-weight-normal ml-2">
                                                        <?= date('d M Y', strtotime($log['created_at'])) ?>
                                                    </span>
                                                </span>
                                                <p class="text-dark-50 font-weight-normal mb-1">
                                                    Registrado por:
                                                    <?= $log['teacher_name'] ?>
                                                </p>
                                                <p class="text-dark-50 p-2 <?= $bgClass ?> rounded">
                                                    <strong>
                                                        <?= $log['name'] ?>:
                                                    </strong>
                                                    <?= !empty($log['observation']) ? $log['observation'] : 'Sin observación adicional.' ?>
                                                </p>
                                            </div>
                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Item-->
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center text-muted p-10">No hay actividad registrada en tu historial.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
            <!--end::Content-->
        </div>
        <!--end::Education-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->