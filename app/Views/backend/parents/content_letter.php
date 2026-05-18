<?php $session = session(); ?>

<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <!-- Header -->
        <div class="card card-custom wave wave-animate-slow wave-primary gutter-b">
            <div class="card-body py-4">
                <div class="d-flex align-items-center">
                    <i class="flaticon2-file-2 text-primary icon-3x mr-4"></i>
                    <div>
                        <h3 class="font-weight-bolder text-dark mb-1">Cartas de Contenido</h3>
                        <span class="text-muted font-size-sm">Contenidos de avance por trimestre — <?= htmlspecialchars($phase_name) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $trim_labels = [1 => '1er Trimestre', 2 => '2do Trimestre', 3 => '3er Trimestre'];

        foreach ($students as $stu):
            $sid      = $stu['student_id'];
            $data     = $student_data[$sid] ?? null;
            $fullname = trim($stu['lastname'] . ' ' . $stu['lastname2'] . ' ' . $stu['name']);
        ?>

        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title font-weight-bolder text-dark">
                    <i class="flaticon2-user text-primary mr-2"></i>
                    <?= htmlspecialchars($fullname) ?>
                    <small class="text-muted font-weight-normal font-size-sm ml-2">
                        — <?= htmlspecialchars($stu['completo']) ?>
                    </small>
                </h3>
            </div>
            <div class="card-body pt-3">

                <?php if (!$data): ?>
                    <p class="text-muted">No se encontró información para este estudiante.</p>

                <?php elseif ($data['type'] === 'primary'): ?>
                    <!-- ========= PRIMARIA / INICIAL : botones a Drive ========= -->
                    <?php if ($data['drive_links']): ?>
                    <p class="text-muted font-size-sm mb-4">
                        Las cartas de Primaria/Inicial se publican en Google Drive.
                        Haz clic en el trimestre para abrirlas.
                    </p>
                    <div class="row">
                        <?php foreach ($trim_labels as $t => $label): ?>
                        <div class="col-xl-4 col-md-4 mb-3">
                            <a href="<?= $data['drive_links'][$t] ?>" target="_blank"
                               class="btn btn-light-primary btn-block font-weight-bolder py-5 d-flex align-items-center justify-content-center">
                                <i class="fab fa-google-drive font-size-h4 mr-3"></i>
                                <?= $label ?>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No hay carpetas de Drive configuradas para este grado. Consulte con secretaría.
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- ========= SECUNDARIA : tabla materia × trimestre ========= -->
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Materia</th>
                                    <?php foreach ($trim_labels as $label): ?>
                                    <th class="text-center" style="min-width:110px;"><?= $label ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['subjects'] as $sub): ?>
                                <tr>
                                    <td class="font-weight-bold align-middle">
                                        <?= htmlspecialchars($sub['name']) ?>
                                    </td>
                                    <?php for ($t = 1; $t <= 3; $t++): ?>
                                    <td class="text-center align-middle">
                                        <?php if (!empty($sub['trims'][$t])): ?>
                                            <a href="<?= base_url('uploads/content_letter/' . $sub['trims'][$t]) ?>"
                                               target="_blank"
                                               class="btn btn-sm btn-light-success font-weight-bold">
                                                <i class="fas fa-file-pdf mr-1"></i>Ver
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted font-size-sm">Sin información</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php endfor; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php endif; ?>

            </div>
        </div>

        <?php endforeach; ?>

    </div>
</div>
