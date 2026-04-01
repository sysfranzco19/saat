<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="card-label">
                        Autoevaluaciones Pendientes
                        <span class="d-block text-muted pt-2 font-size-sm">
                            Estudiantes que aún no registran su autoevaluación &mdash; <?php echo htmlspecialchars($phase_name); ?>
                        </span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <?php
                    $total_pending = 0;
                    foreach ($por_curso as $estudiantes) $total_pending += count($estudiantes);
                    ?>
                    <span class="badge badge-pill badge-<?php echo $total_pending > 0 ? 'danger' : 'success'; ?> font-size-sm px-4 py-2">
                        <?php echo $total_pending; ?> pendiente<?php echo $total_pending != 1 ? 's' : ''; ?>
                    </span>
                </div>
            </div>

            <div class="card-body pt-4">
                <?php if (empty($por_curso)): ?>
                    <div class="d-flex flex-column align-items-center py-10 text-center">
                        <i class="flaticon2-check-mark text-success" style="font-size:3rem;"></i>
                        <p class="text-success font-weight-bold mt-4 mb-0">
                            Todos los estudiantes registraron su autoevaluación en <?php echo htmlspecialchars($phase_name); ?>.
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach ($por_curso as $curso => $estudiantes): ?>
                        <div class="mb-8">
                            <!-- Course header -->
                            <div class="d-flex align-items-center mb-3">
                                <span class="bullet bullet-bar bg-warning align-self-stretch mr-3" style="width:4px;border-radius:4px;"></span>
                                <div class="d-flex flex-column flex-grow-1">
                                    <span class="text-dark font-weight-bold font-size-lg">
                                        <?php echo htmlspecialchars($curso); ?>
                                    </span>
                                </div>
                                <span class="label label-lg label-light-warning label-inline font-weight-bold">
                                    <?php echo count($estudiantes); ?> pendiente<?php echo count($estudiantes) != 1 ? 's' : ''; ?>
                                </span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-head-bg-light-warning mb-0" style="border-radius:8px;overflow:hidden;">
                                    <thead>
                                        <tr>
                                            <th class="text-muted font-size-sm font-weight-bold py-3" style="width:50px;">#</th>
                                            <th class="text-muted font-size-sm font-weight-bold py-3">Estudiante</th>
                                            <th class="text-muted font-size-sm font-weight-bold py-3 text-center" style="width:160px;">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($estudiantes as $i => $row): ?>
                                            <tr>
                                                <td class="text-muted font-size-sm py-3"><?php echo $i + 1; ?></td>
                                                <td class="font-weight-bold text-dark py-3">
                                                    <?php echo htmlspecialchars($row['student']); ?>
                                                </td>
                                                <td class="text-center py-3">
                                                    <span class="label label-danger label-inline font-weight-bold">
                                                        No registrada
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<!--end::Entry-->
