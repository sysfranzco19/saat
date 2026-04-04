<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!-- Header -->
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-circle symbol-light-primary mr-5">
                            <span class="symbol-label font-size-h2 font-weight-bold">
                                <i class="flaticon-list text-primary"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="text-dark font-weight-bold mb-0">Log de Incidencias del Curso</h3>
                            <span class="text-muted font-weight-bold">
                                <?= $section_name ?> | Fase:
                                <?= $phase_name ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <a href="<?= base_url('index.php/teacher/adviser') ?>" class="btn btn-light-primary font-weight-bold">
                            <i class="fa fa-arrow-left"></i> Volver a Consejería
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 py-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">Historial Conductual del Curso</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-sm">Todas las incidencias registradas en
                        todas las materias</span>
                </h3>
            </div>
            <div class="card-body py-0">
                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center" id="kt_adviser_behavior_table">
                        <thead>
                            <tr class="text-left">
                                <th style="min-width: 150px">Fecha</th>
                                <th style="min-width: 200px">Estudiante</th>
                                <th style="min-width: 150px">Materia</th>
                                <th style="min-width: 200px">Incidencia</th>
                                <th style="min-width: 100px">Tipo</th>
                                <th style="min-width: 100px" class="text-right">Puntos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted p-5">Sin registros de comportamiento aún
                                        para este curso.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td>
                                            <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
                                                <?= date('d/m/Y', strtotime($log['created_at'])) ?>
                                            </span>
                                            <span class="text-muted font-weight-bold font-size-sm">
                                                <?= date('H:i', strtotime($log['created_at'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark-75 font-weight-bolder d-block font-size-lg">
                                                <?= $log['student_lastname'] . ' ' . $log['student_lastname2'] . ' ' . $log['student_name'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-primary font-weight-bold">
                                                <?= $log['subject_name'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-30 symbol-light mr-3">
                                                    <span class="symbol-label font-size-h5">
                                                        <?= $log['icon'] ?>
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark-75 font-weight-bolder">
                                                        <?= $log['behavior_name'] ?>
                                                    </span>
                                                    <?php if (!empty($log['observation'])): ?>
                                                        <span class="text-muted font-size-xs italic">
                                                            <?= $log['observation'] ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="label label-lg label-inline <?= $log['type'] == 'positive' ? 'label-light-success' : ($log['type'] == 'negative' ? 'label-light-danger' : 'label-light-primary') ?> font-weight-bold">
                                                <?= ucfirst($log['type']) ?>
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span
                                                class="font-weight-bolder font-size-h5 <?= $log['points'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                <?= $log['points'] > 0 ? '+' . $log['points'] : $log['points'] ?> pts
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
    </div>
</div>