<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!-- Header Profile -->
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-circle symbol-light-primary mr-5">
                            <span class="symbol-label font-size-h2 font-weight-bold">
                                <?= substr($student['name'], 0, 1) ?>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0">
                                <?= $student['name'] . ' ' . $student['lastname'] . ' ' . $student['lastname2'] ?>
                            </a>
                            <span class="text-muted font-weight-bold">
                                <?= $curso ?> |
                                <?= $subject_name ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <?php
                        $backDate = isset($_GET['date']) ? $_GET['date'] : '';
                        $backPeriod = isset($_GET['periodo']) ? $_GET['periodo'] : '';
                        if ($backDate):
                            ?>
                            <form action="<?= base_url('index.php/teacher/attendance_date') ?>" method="POST" style="display:inline;">
                                <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                                <input type="hidden" name="fecha" value="<?= $backDate ?>">
                                <input type="hidden" name="periodo" value="<?= $backPeriod ?>">
                                <button type="submit" class="btn btn-light-primary font-weight-bold">
                                    <i class="fa fa-arrow-left"></i> Volver a Asistencia
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?= base_url('index.php/teacher/attendance/' . $subject_id) ?>"
                                class="btn btn-light-primary font-weight-bold">
                                <i class="fa fa-arrow-left"></i> Volver a Asistencia
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logística del Día -->
        <?php if (!empty($logistics)): ?>
            <div class="card card-custom gutter-b shadow-sm border-0 bg-light-primary">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">Logística del Día</span>
                        <span class="text-muted mt-3 font-weight-bold font-size-sm">Seguimiento de Enfermería y Baño hoy</span>
                    </h3>
                </div>
                <div class="card-body pb-5">
                    <div class="d-flex flex-wrap">
                        <?php foreach ($logistics as $log): ?>
                            <?php
                            $isDifferentSubject = ($subject_id > 0 && $log['subject_id'] != $subject_id);
                            $bgColor = $isDifferentSubject ? 'bg-light-danger' : 'bg-white';
                            $borderColor = $isDifferentSubject ? 'border-danger' : 'border-primary';
                            $subName = $isDifferentSubject ? 'Otra Materia' : $log['subject_name'];
                            $time = date('H:i', strtotime($log['created_at']));
                            ?>
                            <div class="d-flex align-items-center <?= $bgColor ?> border <?= $borderColor ?> rounded p-4 mr-4 mb-4"
                                style="min-width: 200px; border-width: 2px !important;">
                                <span class="font-size-h2 mr-3"><?= $log['icono'] ?></span>
                                <div class="d-flex flex-column">
                                    <span class="text-dark-75 font-weight-bolder font-size-lg"><?= $log['nombre'] ?></span>
                                    <span class="text-muted font-weight-bold font-size-sm"><?= $subName ?> | <?= $time ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Alertas Activas -->
        <?php if (!empty($alertas)): ?>
        <div class="card card-custom gutter-b border-0">
            <div class="card-header border-0 pt-5 pb-0">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">
                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Alertas Activas — 2do Trimestre
                    </span>
                    <span class="text-muted mt-2 font-weight-bold font-size-sm">Materias con Puntos del Ser ≤ 8</span>
                </h3>
            </div>
            <div class="card-body pt-4 pb-5">
                <div class="d-flex flex-wrap">
                    <?php foreach ($alertas as $alerta): ?>
                        <?php
                        $bgMap  = ['danger' => 'bg-light-danger',  'warning' => 'bg-light-warning',  'info' => 'bg-light-info'];
                        $txtMap = ['danger' => 'text-danger',       'warning' => 'text-warning',       'info' => 'text-info'];
                        $icoMap = ['danger' => '🔴',                'warning' => '🟡',                 'info' => '🔵'];
                        $bg  = $bgMap[$alerta['nivel']];
                        $txt = $txtMap[$alerta['nivel']];
                        $ico = $icoMap[$alerta['nivel']];
                        ?>
                        <div class="d-flex align-items-center <?= $bg ?> rounded p-4 mr-4 mb-4" style="min-width:200px;">
                            <span class="font-size-h3 mr-3"><?= $ico ?></span>
                            <div class="d-flex flex-column">
                                <span class="font-weight-bolder text-dark-75 font-size-lg"><?= htmlspecialchars($alerta['materia']) ?></span>
                                <span class="<?= $txt ?> font-weight-bolder font-size-h4"><?= $alerta['nota'] ?> / 10 pts</span>
                                <span class="text-muted font-size-xs">
                                    <?= $alerta['nivel'] === 'danger' ? 'Crítico — requiere acción' : ($alerta['nivel'] === 'warning' ? 'En observación' : 'Atención') ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Nav Tabs -->
        <?php $isT2 = ($active_tab === 't2'); ?>
        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x mb-5" role="tablist">
            <li class="nav-item">
                <a class="nav-link font-weight-bolder <?= !$isT2 ? 'active' : '' ?>" data-toggle="tab" href="#tab-t1" role="tab">
                    <i class="fa fa-history mr-2"></i> Primer Trimestre
                    <span class="label label-light-warning label-inline ml-2"><?= count($logs_t1) ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bolder <?= $isT2 ? 'active' : '' ?>" data-toggle="tab" href="#tab-t2" role="tab">
                    <i class="fa fa-chart-bar mr-2"></i> Segundo Trimestre
                    <span class="label label-light-primary label-inline ml-2"><?= count($logs) ?></span>
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <!-- ===================== PRIMER TRIMESTRE ===================== -->
            <div class="tab-pane fade <?= !$isT2 ? 'show active' : '' ?>" id="tab-t1" role="tabpanel">

                <!-- Stats T1 -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card card-custom wave wave-animate-slow wave-primary mb-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-4">
                                    <div class="d-flex flex-column mr-2">
                                        <a href="#" class="h4 text-dark text-hover-primary mb-1">Incidencias</a>
                                        <span class="text-muted font-weight-bold">Negativas</span>
                                    </div>
                                    <span class="label label-xl label-light-primary label-inline font-weight-bold py-4 font-size-h3">
                                        <?= $t1_negative ?? 0 ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card card-custom wave wave-animate-slow wave-warning mb-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-4">
                                    <div class="d-flex flex-column mr-2">
                                        <a href="#" class="h4 text-dark text-hover-primary mb-1">Positivas</a>
                                        <span class="text-muted font-weight-bold">Participación</span>
                                    </div>
                                    <span class="label label-xl label-light-warning label-inline font-weight-bold py-4 font-size-h3">
                                        <?= $t1_positive ?? 0 ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart T1 -->
                <?php if (!empty($t1_behavior_counts)): ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-custom gutter-b">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title font-weight-bolder">Resumen 1er Trimestre</h3>
                            </div>
                            <div class="card-body d-flex flex-column flex-md-row">
                                <div class="flex-grow-1 mr-md-8" style="min-width: 0; overflow: hidden;">
                                    <div id="chart_t1"></div>
                                </div>
                                <div class="mt-8 mt-md-0" style="min-width: 300px;">
                                    <h5 class="font-weight-bold mb-4">Detalle</h5>
                                    <div class="d-flex flex-column" style="max-height: 300px; overflow-y: auto;">
                                        <?php foreach ($t1_behavior_counts as $b): ?>
                                            <?php if ($b['count'] > 0): ?>
                                                <div class="d-flex align-items-center mb-2 p-2 rounded bg-light-secondary">
                                                    <span class="symbol-label font-size-h2 mr-3"><?= $b['icono'] ?></span>
                                                    <div class="d-flex flex-column flex-grow-1">
                                                        <span class="text-dark-75 font-weight-bolder font-size-lg"><?= $b['nombre'] ?></span>
                                                        <span class="text-muted font-size-xs"><?= $b['tipo'] === 'positiva' ? 'Positivo' : ($b['tipo'] === 'neutral' ? 'Logística' : 'Negativo') ?></span>
                                                    </div>
                                                    <span class="font-weight-bolder font-size-h4 text-primary"><?= $b['count'] ?></span>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Tabla T1 -->
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder text-dark">Historial 1er Trimestre</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Datos del sistema anterior</span>
                        </h3>
                    </div>
                    <div class="card-body pb-4 pt-3">
                        <!-- Filtros T1 -->
                        <div class="d-flex flex-wrap mb-4">
                            <div class="mr-3 mb-2">
                                <select id="filtro-t1-motivo" class="form-control form-control-sm" style="min-width:180px;">
                                    <option value="">Todos los motivos</option>
                                    <?php
                                    $motivosT1 = array_unique(array_column($logs_t1, 'nombre'));
                                    sort($motivosT1);
                                    foreach ($motivosT1 as $m): ?>
                                        <option value="<?= htmlspecialchars($m) ?>"><?= htmlspecialchars($m) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if ($subject_id == 0): ?>
                            <div class="mb-2">
                                <select id="filtro-t1-materia" class="form-control form-control-sm" style="min-width:180px;">
                                    <option value="">Todas las materias</option>
                                    <?php
                                    $materiasT1 = array_unique(array_filter(array_column($logs_t1, 'subject_name')));
                                    sort($materiasT1);
                                    foreach ($materiasT1 as $mat): ?>
                                        <option value="<?= htmlspecialchars($mat) ?>"><?= htmlspecialchars($mat) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-head-custom table-vertical-center" id="tabla-t1">
                                <thead>
                                    <tr class="text-left">
                                        <th style="min-width: 150px">Fecha</th>
                                        <th style="min-width: 200px">Motivo</th>
                                        <?php if ($subject_id == 0): ?>
                                        <th style="min-width: 140px">Materia</th>
                                        <?php endif; ?>
                                        <th style="min-width: 120px">Tipo</th>
                                        <th style="min-width: 100px" class="text-right">Puntos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($logs_t1)): ?>
                                        <tr>
                                            <td colspan="<?= $subject_id == 0 ? 5 : 4 ?>" class="text-center text-muted p-5">Sin registros del primer trimestre.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($logs_t1 as $log): ?>
                                            <tr class="fila-t1"
                                                data-motivo="<?= htmlspecialchars($log['nombre']) ?>"
                                                data-materia="<?= htmlspecialchars($log['subject_name'] ?? '') ?>">
                                                <td>
                                                    <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
                                                        <?= date('d/m/Y', strtotime($log['created_at'])) ?>
                                                    </span>
                                                    <span class="text-muted font-weight-bold font-size-sm">
                                                        <?= date('H:i', strtotime($log['created_at'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-start">
                                                        <div class="symbol symbol-30 symbol-light mr-3">
                                                            <span class="symbol-label font-size-h5"><?= $log['icono'] ?></span>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-dark-75 font-weight-bolder font-size-lg"><?= $log['nombre'] ?></span>
                                                            <?php if (!empty($log['observacion'])): ?>
                                                                <span class="text-muted font-size-sm">
                                                                    <i class="flaticon2-information small mr-1"></i><?= htmlspecialchars($log['observacion']) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php if ($subject_id == 0): ?>
                                                <td>
                                                    <span class="text-dark-75 font-weight-bold font-size-sm">
                                                        <?= !empty($log['subject_name']) ? htmlspecialchars($log['subject_name']) : '<span class="text-muted">—</span>' ?>
                                                    </span>
                                                </td>
                                                <?php endif; ?>
                                                <td>
                                                    <?php if ($log['tipo'] === 'neutral'): ?>
                                                        <span class="label label-lg label-inline label-light-info font-weight-bold py-4">Logística</span>
                                                    <?php elseif ($log['tipo'] === 'positiva'): ?>
                                                        <span class="label label-lg label-inline label-light-success font-weight-bold py-4">Positivo</span>
                                                    <?php else: ?>
                                                        <span class="label label-lg label-inline label-light-danger font-weight-bold py-4">Negativo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php $pts = $log['tipo'] === 'negativa' ? -0.5 : ($log['tipo'] === 'positiva' ? 0.5 : 0); ?>
                                                    <span class="font-weight-bolder font-size-h5 <?= $log['tipo'] === 'negativa' ? 'text-danger' : 'text-success' ?>">
                                                        <?= $pts > 0 ? '+' . $pts : $pts ?> pts
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div><!-- /tab-t1 -->


            <!-- ===================== SEGUNDO TRIMESTRE ===================== -->
            <div class="tab-pane fade <?= $isT2 ? 'show active' : '' ?>" id="tab-t2" role="tabpanel">

                <!-- Stats T2 -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-custom wave wave-animate-slow wave-primary mb-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-4">
                                    <div class="d-flex flex-column mr-2">
                                        <a href="#" class="h4 text-dark text-hover-primary mb-1">Incidencias</a>
                                        <span class="text-muted font-weight-bold">Negativas</span>
                                    </div>
                                    <span class="label label-xl label-light-primary label-inline font-weight-bold py-4 font-size-h3">
                                        <?= $negative_incidents ?? 0 ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-custom wave wave-animate-slow wave-warning mb-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-4">
                                    <div class="d-flex flex-column mr-2">
                                        <a href="#" class="h4 text-dark text-hover-primary mb-1">Positivas</a>
                                        <span class="text-muted font-weight-bold">Participación</span>
                                    </div>
                                    <span class="label label-xl label-light-warning label-inline font-weight-bold py-4 font-size-h3">
                                        <?= $positive_incidents ?? 0 ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-custom wave wave-animate-slow wave-success mb-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-4">
                                    <div class="d-flex flex-column mr-2">
                                        <a href="#" class="h4 text-dark text-hover-primary mb-1">Puntos del Ser</a>
                                        <span class="text-muted font-weight-bold">Puntaje Actual (1-10)</span>
                                    </div>
                                    <span id="puntos-del-ser-badge"
                                        class="label label-xl label-light-success label-inline font-weight-bold py-4 font-size-h3">
                                        <?= $puntos_del_ser ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($grave_incidents)): ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-custom alert-light-danger d-flex align-items-center p-4 mb-5" role="alert">
                            <span class="font-size-h3 mr-4">🟢</span>
                            <div>
                                <span class="font-weight-bolder text-danger font-size-lg">
                                    <?= $grave_incidents ?> Boleta(s) Verde Registrada(s)
                                </span>
                                <span class="d-block text-muted font-size-sm">
                                    Cada boleta descuenta −3 puntos del Ser en la(s) materia(s) afectada(s)
                                </span>
                            </div>
                            <span class="ml-auto font-weight-bolder text-danger font-size-h4">
                                −<?= $grave_incidents * 3 ?> pts
                            </span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Chart T2 -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-custom gutter-b">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title font-weight-bolder">Resumen 2do Trimestre</h3>
                            </div>
                            <div class="card-body d-flex flex-column flex-md-row">
                                <div class="flex-grow-1 mr-md-8" style="min-width: 0; overflow: hidden;">
                                    <div id="chart_t2"></div>
                                </div>
                                <div class="mt-8 mt-md-0" style="min-width: 300px;">
                                    <h5 class="font-weight-bold mb-4">Detalle</h5>
                                    <div class="d-flex flex-column" style="max-height: 300px; overflow-y: auto;">
                                        <?php foreach ($behavior_counts as $b): ?>
                                            <?php if ($b['count'] > 0): ?>
                                                <div class="d-flex align-items-center mb-2 p-2 rounded bg-light-secondary">
                                                    <span class="symbol-label font-size-h2 mr-3"><?= $b['icono'] ?></span>
                                                    <div class="d-flex flex-column flex-grow-1">
                                                        <span class="text-dark-75 font-weight-bolder font-size-lg"><?= $b['nombre'] ?></span>
                                                        <span class="text-muted font-size-xs"><?= $b['tipo'] === 'positiva' ? 'Positivo' : ($b['tipo'] === 'neutral' ? 'Logística' : 'Negativo') ?></span>
                                                    </div>
                                                    <span class="font-weight-bolder font-size-h4 text-primary"><?= $b['count'] ?></span>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php if (empty($logs)): ?>
                                            <span class="text-muted">Sin datos aún.</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla T2 -->
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder text-dark">Historial 2do Trimestre</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Detalle de todas las incidencias registradas</span>
                        </h3>
                    </div>
                    <div class="card-body pb-4 pt-3">
                        <!-- Filtros T2 -->
                        <div class="d-flex flex-wrap mb-4">
                            <div class="mr-3 mb-2">
                                <select id="filtro-t2-motivo" class="form-control form-control-sm" style="min-width:180px;">
                                    <option value="">Todos los motivos</option>
                                    <?php
                                    $motivosT2 = array_unique(array_column($logs, 'nombre'));
                                    sort($motivosT2);
                                    foreach ($motivosT2 as $m): ?>
                                        <option value="<?= htmlspecialchars($m) ?>"><?= htmlspecialchars($m) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php if ($subject_id == 0): ?>
                            <div class="mb-2">
                                <select id="filtro-t2-materia" class="form-control form-control-sm" style="min-width:180px;">
                                    <option value="">Todas las materias</option>
                                    <?php
                                    $materiasT2 = array_unique(array_filter(array_column($logs, 'subject_name')));
                                    sort($materiasT2);
                                    foreach ($materiasT2 as $mat): ?>
                                        <option value="<?= htmlspecialchars($mat) ?>"><?= htmlspecialchars($mat) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_history">
                                <thead>
                                    <tr class="text-left">
                                        <th style="min-width: 150px">Fecha</th>
                                        <th style="min-width: 200px">Motivo</th>
                                        <?php if ($subject_id == 0): ?>
                                        <th style="min-width: 140px">Materia</th>
                                        <?php endif; ?>
                                        <th style="min-width: 120px">Tipo</th>
                                        <th style="min-width: 100px" class="text-right">Puntos</th>
                                        <th style="min-width: 100px" class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($logs)): ?>
                                        <tr>
                                            <td colspan="<?= $subject_id == 0 ? 6 : 5 ?>" class="text-center text-muted p-5">Sin registros del segundo trimestre.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($logs as $log): ?>
                                            <?php $isGrave = ($log['tipo'] ?? '') === 'grave'; ?>
                                            <tr id="log-row-<?= $log['id'] ?>"
                                                class="fila-t2 <?= $isGrave ? 'table-warning' : '' ?>"
                                                data-motivo="<?= htmlspecialchars($log['nombre'] ?? '') ?>"
                                                data-materia="<?= htmlspecialchars($log['subject_name'] ?? '') ?>">
                                                <td>
                                                    <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
                                                        <?= date('d/m/Y', strtotime($log['created_at'])) ?>
                                                    </span>
                                                    <?php if (!$isGrave): ?>
                                                    <span class="text-muted font-weight-bold font-size-sm">
                                                        <?= date('H:i', strtotime($log['created_at'])) ?>
                                                    </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-start">
                                                        <div class="symbol symbol-30 symbol-light mr-3">
                                                            <span class="symbol-label font-size-h5"><?= $log['icono'] ?></span>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="text-dark-75 font-weight-bolder font-size-lg">
                                                                <?= $log['nombre'] ?>
                                                            </span>
                                                            <?php if (!empty($log['observacion'])): ?>
                                                                <span class="text-muted font-size-sm">
                                                                    <i class="flaticon2-information small mr-1"></i><?= htmlspecialchars($log['observacion']) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if ($isGrave && !empty($log['dias_suspension'])): ?>
                                                                <span class="label label-light-danger label-inline mt-1" style="width:fit-content">
                                                                    Suspensión: <?= $log['dias_suspension'] ?> día(s)
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if ($isGrave && !empty($log['medidas_restaurativas'])): ?>
                                                                <span class="text-info font-size-sm mt-1">
                                                                    <i class="fas fa-hands-helping small mr-1"></i><?= htmlspecialchars($log['medidas_restaurativas']) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <?php if ($subject_id == 0): ?>
                                                <td>
                                                    <span class="text-dark-75 font-weight-bold font-size-sm">
                                                        <?= !empty($log['subject_name']) ? htmlspecialchars($log['subject_name']) : '<span class="text-muted">—</span>' ?>
                                                    </span>
                                                </td>
                                                <?php endif; ?>
                                                <td>
                                                    <?php if ($isGrave): ?>
                                                        <span class="label label-lg label-inline label-danger font-weight-bold py-4">Falta Grave</span>
                                                    <?php elseif ($log['tipo'] === 'neutral'): ?>
                                                        <span class="label label-lg label-inline label-light-info font-weight-bold py-4">Logística</span>
                                                    <?php elseif ($log['tipo'] === 'positiva'): ?>
                                                        <span class="label label-lg label-inline label-light-success font-weight-bold py-4">Positivo</span>
                                                    <?php else: ?>
                                                        <span class="label label-lg label-inline label-light-danger font-weight-bold py-4">Negativo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php if ($isGrave): ?>
                                                        <span class="font-weight-bolder font-size-h5 text-danger">−3 pts</span>
                                                    <?php else: ?>
                                                        <?php $pts = $log['tipo'] === 'negativa' ? -0.5 : ($log['tipo'] === 'positiva' ? 0.5 : 0); ?>
                                                        <span class="font-weight-bolder font-size-h5 <?= $log['tipo'] === 'negativa' ? 'text-danger' : 'text-success' ?>">
                                                            <?= $pts > 0 ? '+' . $pts : $pts ?> pts
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php if ($isGrave): ?>
                                                        <span class="text-muted font-size-xs">Sec. académica</span>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-light-primary font-weight-bold mr-2"
                                                            onclick="editObservation(<?= $log['id'] ?>, '<?= htmlspecialchars($log['observacion'] ?? '', ENT_QUOTES) ?>')">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-light-danger font-weight-bold"
                                                            onclick="deleteBehavior(<?= $log['id'] ?>)">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div><!-- /tab-t2 -->

        </div><!-- /tab-content -->
    </div>
</div>


<!-- Modal Edit Observation -->
<div class="modal fade" id="modalEditObs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Editar Observación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-log-id">
                <div class="form-group">
                    <label>Observaciones</label>
                    <textarea class="form-control" id="edit-observation" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary font-weight-bold" onclick="saveObservation()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Charts
    <?php
    $t1Data = [];
    $t1Categories = [];
    foreach ($t1_behavior_counts as $b) {
        $t1Data[] = (int) $b['count'];
        $t1Categories[] = $b['nombre'];
    }
    $t2Data = [];
    $t2Categories = [];
    foreach ($behavior_counts as $b) {
        $t2Data[] = (int) $b['count'];
        $t2Categories[] = html_entity_decode($b['nombre'], ENT_QUOTES, 'UTF-8');
    }
    $chartColors = "['#663259','#1BC5BD','#FFA800','#F64E60','#8950FC','#6993FF','#3699FF','#0BB783','#EE2D41','#8833FF']";
    ?>

    <?php if (!empty($t1_behavior_counts)): ?>
    (function () {
        var options = {
            series: [{ name: 'Incidencias', data: <?= json_encode($t1Data) ?> }],
            chart: { type: 'bar', height: <?= count($t1_behavior_counts) * 38 + 40 ?>, toolbar: { show: false } },
            plotOptions: { bar: { horizontal: true, borderRadius: 3, barHeight: '55%', distributed: true } },
            dataLabels: { enabled: true, formatter: function(val) { return val > 0 ? val : ''; }, style: { fontSize: '11px' } },
            legend: { show: false },
            xaxis: { categories: <?= json_encode($t1Categories) ?>, labels: { formatter: function(val) { return Math.floor(val); } }, tickAmount: <?= max($t1Data ?: [1]) ?> },
            yaxis: { labels: { style: { fontSize: '12px' } } },
            grid: { borderColor: '#f1f1f1' },
            colors: <?= $chartColors ?>
        };
        var chartT1 = new ApexCharts(document.querySelector("#chart_t1"), options);
        var t1Rendered = false;
        function renderT1() { if (!t1Rendered) { chartT1.render(); t1Rendered = true; } }
        $('a[href="#tab-t1"]').on('shown.bs.tab', renderT1);
        $(document).ready(function () {
            if ($('#tab-t1').hasClass('active')) { renderT1(); }
        });
    })();
    <?php endif; ?>

    <?php if (!empty($behavior_counts)): ?>
    (function () {
        var options = {
            series: [{ name: 'Incidencias', data: <?= json_encode($t2Data) ?> }],
            chart: { type: 'bar', height: <?= count($behavior_counts) * 38 + 40 ?>, toolbar: { show: false } },
            plotOptions: { bar: { horizontal: true, borderRadius: 3, barHeight: '55%', distributed: true } },
            dataLabels: { enabled: true, formatter: function(val) { return val > 0 ? val : ''; }, style: { fontSize: '11px' } },
            legend: { show: false },
            xaxis: { categories: <?= json_encode($t2Categories) ?>, labels: { formatter: function(val) { return Math.floor(val); } }, tickAmount: <?= max($t2Data ?: [1]) ?> },
            yaxis: { labels: { style: { fontSize: '12px' } } },
            grid: { borderColor: '#f1f1f1' },
            colors: <?= $chartColors ?>
        };
        var chartT2 = new ApexCharts(document.querySelector("#chart_t2"), options);
        var t2Rendered = false;
        function renderT2() { if (!t2Rendered) { chartT2.render(); t2Rendered = true; } }
        $('a[href="#tab-t2"]').on('shown.bs.tab', renderT2);
        $(document).ready(function () {
            if ($('#tab-t2').hasClass('active')) { renderT2(); }
        });
    })();
    <?php else: ?>
    document.querySelector("#chart_t2").innerHTML = "<div class='d-flex align-items-center justify-content-center h-100 text-muted pt-8'>Sin datos para graficar</div>";
    <?php endif; ?>

    // T2 actions
    function deleteBehavior(logId) {
        if (!confirm('¿Estás seguro de que deseas eliminar este registro?')) return;
        $.post('<?= base_url('index.php/teacher/delete_behavior_ajax') ?>', { log_id: logId }, function (response) {
            if (response.status === 'success') {
                $('#log-row-' + logId).fadeOut(300, function () { $(this).remove(); });
                if (response.new_score !== undefined) {
                    $('#puntos-del-ser-badge').text(response.new_score);
                }
            } else {
                alert('Error al eliminar');
            }
        }, 'json');
    }
    function editObservation(logId, currentObs) {
        $('#edit-log-id').val(logId);
        $('#edit-observation').val(currentObs);
        $('#modalEditObs').modal('show');
    }
    function saveObservation() {
        const logId = $('#edit-log-id').val();
        const obs = $('#edit-observation').val();
        $.post('<?= base_url('index.php/teacher/update_behavior_observation_ajax') ?>', { log_id: logId, observation: obs }, function (response) {
            if (response.status === 'success') {
                $('#modalEditObs').modal('hide');
                location.reload();
            } else {
                alert('Error al guardar');
            }
        }, 'json');
    }

    // Filtros de historial
    function aplicarFiltro(filaSelector, motivoSelector, materiaSelector) {
        var motivo  = $(motivoSelector).val();
        var materia = materiaSelector ? $(materiaSelector).val() : '';
        $(filaSelector).each(function () {
            var okMotivo  = !motivo  || $(this).data('motivo')  === motivo;
            var okMateria = !materia || $(this).data('materia') === materia;
            $(this).toggle(okMotivo && okMateria);
        });
    }

    $('#filtro-t1-motivo, #filtro-t1-materia').on('change', function () {
        aplicarFiltro('.fila-t1', '#filtro-t1-motivo', '#filtro-t1-materia');
    });
    $('#filtro-t2-motivo, #filtro-t2-materia').on('change', function () {
        aplicarFiltro('.fila-t2', '#filtro-t2-motivo', '#filtro-t2-materia');
    });
</script>
