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

        <!-- Logística del Día (Cross-Subject Tracking) -->
        <?php if (!empty($logistics)): ?>
            <div class="card card-custom gutter-b shadow-sm border-0 bg-light-primary">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">Logística del Día</span>
                        <span class="text-muted mt-3 font-weight-bold font-size-sm">Seguimiento de Enfermería y Baño
                            hoy</span>
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
                                    <span
                                        class="text-dark-75 font-weight-bolder font-size-lg"><?= $log['nombre'] ?></span>
                                    <span class="text-muted font-weight-bold font-size-sm"><?= $subName ?> | <?= $time ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Stats Row -->
        <div class="row">
            <!-- Puntos del Ser -->
            <div class="col-lg-12 col-xxl-12">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-custom wave wave-animate-slow wave-primary mb-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between p-4">
                                    <div class="d-flex flex-column mr-2">
                                        <a href="#" class="h4 text-dark text-hover-primary mb-1">Incidencias</a>
                                        <span class="text-muted font-weight-bold">Negativas</span>
                                    </div>
                                    <span
                                        class="label label-xl label-light-primary label-inline font-weight-bold py-4 font-size-h3">
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
                                    <span
                                        class="label label-xl label-light-warning label-inline font-weight-bold py-4 font-size-h3">
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
            </div>

            <!-- Charts & Counters -->
            <div class="col-lg-12">
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder">Resumen de Comportamiento</h3>
                    </div>
                    <div class="card-body d-flex flex-column flex-md-row">
                        <!-- Chart Container -->
                        <div class="flex-grow-1 mr-md-8" style="min-width: 0; overflow: hidden;">
                            <div id="chart_behavior"></div>
                        </div>

                        <!-- Counters List -->
                        <div class="mt-8 mt-md-0" style="min-width: 300px;">
                            <h5 class="font-weight-bold mb-4">Detalle</h5>
                            <div class="d-flex flex-column" style="max-height: 300px; overflow-y: auto;">
                                <?php foreach ($behavior_counts as $b): ?>
                                    <?php if ($b['count'] > 0): ?>
                                        <div class="d-flex align-items-center mb-2 p-2 rounded bg-light-secondary">
                                            <span class="symbol-label font-size-h2 mr-3"><?= $b['icono'] ?></span>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span
                                                    class="text-dark-75 font-weight-bolder font-size-lg"><?= $b['nombre'] ?></span>
                                                <span
                                                    class="text-muted font-size-xs"><?= $b['tipo'] === 'positiva' ? 'Positivo' : ($b['tipo'] === 'neutral' ? 'Logística' : 'Negativo') ?></span>
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

        <script>
            <?php
            $chartData = [];
            $chartCategories = [];
            foreach ($behavior_counts as $b) {
                $chartData[] = (int) $b['count'];
                $chartCategories[] = html_entity_decode($b['nombre'], ENT_QUOTES, 'UTF-8');
            }
            ?>

            <?php if (!empty($behavior_counts)): ?>
            (function () {
                var options = {
                    series: [{
                        name: 'Incidencias',
                        data: <?= json_encode($chartData) ?>
                    }],
                    chart: {
                        type: 'bar',
                        height: <?= count($behavior_counts) * 38 + 40 ?>,
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            borderRadius: 3,
                            barHeight: '55%',
                            distributed: true
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) { return val > 0 ? val : ''; },
                        style: { fontSize: '11px' }
                    },
                    legend: { show: false },
                    xaxis: {
                        categories: <?= json_encode($chartCategories) ?>,
                        labels: {
                            formatter: function(val) { return Math.floor(val); }
                        },
                        tickAmount: <?= max($chartData) ?: 1 ?>
                    },
                    yaxis: {
                        labels: { style: { fontSize: '12px' } }
                    },
                    grid: { borderColor: '#f1f1f1' },
                    colors: ['#663259', '#1BC5BD', '#FFA800', '#F64E60', '#8950FC', '#6993FF', '#3699FF', '#0BB783', '#EE2D41', '#8833FF']
                };

                var chart = new ApexCharts(document.querySelector("#chart_behavior"), options);
                chart.render();
            })();
            <?php else: ?>
            document.querySelector("#chart_behavior").innerHTML = "<div class='d-flex align-items-center justify-content-center h-100 text-muted pt-8'>Sin datos para graficar</div>";
            <?php endif; ?>
        </script>


        <!-- History Table -->
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 py-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">Historial de Comportamiento</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-sm">Detalle de todas las incidencias
                        registradas</span>
                </h3>
            </div>
            <div class="card-body py-0">
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
                                    <td colspan="<?= $subject_id == 0 ? 6 : 5 ?>" class="text-center text-muted p-5">Sin registros de comportamiento aún.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <?php $isGrave = ($log['tipo'] ?? '') === 'grave'; ?>
                                    <tr id="log-row-<?= $log['id'] ?>" <?= $isGrave ? 'class="table-warning"' : '' ?>>
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
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary font-weight-bold"
                    onclick="saveObservation()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteBehavior(logId) {
        if (!confirm('¿Estás seguro de que deseas eliminar este registro?')) return;

        $.post('<?= base_url('index.php/teacher/delete_behavior_ajax') ?>', {
            log_id: logId
        }, function (response) {
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

        $.post('<?= base_url('index.php/teacher/update_behavior_observation_ajax') ?>', {
            log_id: logId,
            observation: obs
        }, function (response) {
            if (response.status === 'success') {
                $('#modalEditObs').modal('hide');
                location.reload(); // Reload to show updated observation
            } else {
                alert('Error al guardar');
            }
        }, 'json');
    }
</script>