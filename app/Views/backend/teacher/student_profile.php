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
                            <form action="<?= base_url('teacher/attendance_date') ?>" method="POST" style="display:inline;">
                                <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                                <input type="hidden" name="fecha" value="<?= $backDate ?>">
                                <input type="hidden" name="periodo" value="<?= $backPeriod ?>">
                                <button type="submit" class="btn btn-light-primary font-weight-bold">
                                    <i class="fa fa-arrow-left"></i> Volver a Asistencia
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?= base_url('teacher/attendance/' . $subject_id) ?>"
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
                                <span class="font-size-h2 mr-3"><?= $log['icon'] ?></span>
                                <div class="d-flex flex-column">
                                    <span
                                        class="text-dark-75 font-weight-bolder font-size-lg"><?= $log['behavior_name'] ?></span>
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
                                        <span class="text-muted font-weight-bold">Total</span>
                                    </div>
                                    <span
                                        class="label label-xl label-light-primary label-inline font-weight-bold py-4 font-size-h3">
                                        <?= count($logs) ?>
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
                                    <span
                                        class="label label-xl label-light-success label-inline font-weight-bold py-4 font-size-h3">
                                        <?= $puntos_del_ser ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts & Counters -->
            <div class="col-lg-12">
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder">Resumen de Comportamiento</h3>
                    </div>
                    <div class="card-body d-flex flex-column flex-md-row">
                        <!-- Chart Container (Compact & Vertical) -->
                        <div class="flex-grow-1 mr-md-8" style="min-height: 250px;">
                            <div id="chart_behavior"></div>
                        </div>

                        <!-- Counters List -->
                        <div class="mt-8 mt-md-0" style="min-width: 300px;">
                            <h5 class="font-weight-bold mb-4">Detalle</h5>
                            <div class="d-flex flex-column" style="max-height: 300px; overflow-y: auto;">
                                <?php foreach ($behavior_counts as $b): ?>
                                    <?php if ($b['count'] > 0): ?>
                                        <div class="d-flex align-items-center mb-2 p-2 rounded bg-light-secondary">
                                            <span class="symbol-label font-size-h2 mr-3"><?= $b['icon'] ?></span>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <span
                                                    class="text-dark-75 font-weight-bolder font-size-lg"><?= $b['name'] ?></span>
                                                <span
                                                    class="text-muted font-size-xs"><?= $b['type'] == 'positive' ? 'Positivo' : 'Negativo' ?></span>
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

        <!-- Add ApexCharts CDN if not available -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var options = {
                    series: [{
                        name: 'Incidencias',
                        data: [
                            <?php
                            foreach ($behavior_counts as $b) {
                                if ($b['count'] > 0)
                                    echo $b['count'] . ",";
                            }
                            ?>
                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 250, // Compact height
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '45%', // Thinner bars for vertical look
                            distributed: true,  // Different colors per bar
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: false
                    },
                    xaxis: {
                        categories: [
                            <?php
                            foreach ($behavior_counts as $b) {
                                if ($b['count'] > 0) {
                                    $decodedName = html_entity_decode($b['name'], ENT_QUOTES, 'UTF-8');
                                    echo "[" . json_encode($decodedName) . "],";
                                }
                            }
                            ?>
                        ],
                        labels: {
                            style: {
                                fontSize: '10px'
                            },
                            rotate: -45
                        }
                    },
                    grid: {
                        borderColor: '#f1f1f1',
                    }
                };

                // Only render if data exists
                <?php if (!empty($logs)): ?>
                    var chart = new ApexCharts(document.querySelector("#chart_behavior"), options);
                    chart.render();
                <?php else: ?>
                    document.querySelector("#chart_behavior").innerHTML = "<div class='d-flex align-items-center justify-content-center h-100 text-muted'>Sin datos para graficar</div>";
                <?php endif; ?>
            });
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
                                <th style="min-width: 120px">Tipo</th>
                                <th style="min-width: 100px" class="text-right">Puntos</th>
                                <th style="min-width: 100px" class="text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted p-5">Sin registros de comportamiento aún.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr id="log-row-<?= $log['id'] ?>">
                                        <td>
                                            <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
                                                <!-- Assuming date_id creates a relation, but logs usually have created_at -->
                                                <?= date('d/m/Y', strtotime($log['created_at'])) ?>
                                            </span>
                                            <span class="text-muted font-weight-bold font-size-sm">
                                                <?= date('H:i', strtotime($log['created_at'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-30 symbol-light mr-3">
                                                    <!-- Simple icon mapping if needed, or generic -->
                                                    <span class="symbol-label font-size-h5"><?= $log['icon'] ?></span>
                                                </div>
                                                <span class="text-dark-75 font-weight-bolder font-size-lg">
                                                    <?= $log['name'] ?>
                                                </span>
                                                <?php if (!empty($log['observation'])): ?>
                                                    <div class="text-muted font-size-sm mt-1 d-block w-100"
                                                        style="margin-left: 46px;">
                                                        <i class="flaticon2-information small mr-1"></i>
                                                        <?= $log['observation'] ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="label label-lg label-inline <?= $log['points'] >= 0 ? 'label-light-success' : 'label-light-danger' ?> font-weight-bold py-4">
                                                <?= $log['points'] >= 0 ? 'Positivo' : 'Negativo' ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span
                                                class="font-weight-bolder font-size-h5 <?= $log['points'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= $log['points'] > 0 ? '+' . $log['points'] : $log['points'] ?> pts
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm btn-light-primary font-weight-bold mr-2"
                                                onclick="editObservation(<?= $log['id'] ?>, '<?= htmlspecialchars($log['observation'] ?? '', ENT_QUOTES) ?>')">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light-danger font-weight-bold"
                                                onclick="deleteBehavior(<?= $log['id'] ?>)">
                                                <i class="fa fa-trash"></i>
                                            </button>
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

        $.post('<?= base_url('teacher/delete_behavior_ajax') ?>', {
            log_id: logId
        }, function (response) {
            if (response.status === 'success') {
                // Remove row
                $('#log-row-' + logId).fadeOut(300, function () {
                    $(this).remove();
                });
                // Ideally reload to update stats, or update DOM manually. 
                // For simplicity, we just remove the row.
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

        $.post('<?= base_url('teacher/update_behavior_observation_ajax') ?>', {
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