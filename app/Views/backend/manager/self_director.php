<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="card-label">
                        Autoevaluaciones
                        <span class="d-block text-muted pt-2 font-size-sm">
                            Estado de autoevaluaciones por curso &mdash; <?php echo htmlspecialchars($phase_name); ?>
                        </span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <?php
                    $total_pendientes = 0;
                    $total_general    = 0;
                    foreach ($por_curso as $curso_data) {
                        $total_general    += $curso_data['total'];
                        $total_pendientes += ($curso_data['total'] - $curso_data['con_auto']);
                    }
                    ?>
                    <span class="badge badge-pill badge-<?php echo $total_pendientes > 0 ? 'danger' : 'success'; ?> font-size-sm px-4 py-2 mr-2">
                        <?php echo $total_pendientes; ?> pendiente<?php echo $total_pendientes != 1 ? 's' : ''; ?>
                    </span>
                    <span class="badge badge-pill badge-light font-size-sm px-4 py-2">
                        <?php echo $total_general; ?> total
                    </span>
                </div>
            </div>

            <div class="card-body pt-4">
                <?php if (empty($por_curso)): ?>
                    <div class="d-flex flex-column align-items-center py-10 text-center">
                        <i class="flaticon2-check-mark text-success" style="font-size:3rem;"></i>
                        <p class="text-success font-weight-bold mt-4 mb-0">
                            No hay estudiantes registrados para este director en <?php echo htmlspecialchars($phase_name); ?>.
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach ($por_curso as $curso_data): ?>
                        <?php
                        $estudiantes = $curso_data['estudiantes'];
                        $pendientes  = $curso_data['total'] - $curso_data['con_auto'];
                        ?>
                        <div class="mb-8">
                            <!-- Encabezado del curso -->
                            <div class="d-flex align-items-center mb-3">
                                <span class="bullet bullet-bar <?php echo $pendientes > 0 ? 'bg-warning' : 'bg-success'; ?> align-self-stretch mr-3" style="width:4px;border-radius:4px;"></span>
                                <div class="d-flex flex-column flex-grow-1">
                                    <span class="text-dark font-weight-bold font-size-lg">
                                        <?php echo htmlspecialchars($curso_data['completo']); ?>
                                    </span>
                                </div>
                                <span class="label label-lg label-light-<?php echo $pendientes > 0 ? 'warning' : 'success'; ?> label-inline font-weight-bold mr-2">
                                    <?php echo $curso_data['con_auto']; ?>/<?php echo $curso_data['total']; ?> registradas
                                </span>
                                <?php if ($pendientes > 0): ?>
                                <span class="label label-lg label-light-danger label-inline font-weight-bold">
                                    <?php echo $pendientes; ?> pendiente<?php echo $pendientes != 1 ? 's' : ''; ?>
                                </span>
                                <?php endif; ?>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-head-bg-light mb-0" style="border-radius:8px;overflow:hidden;">
                                    <thead>
                                        <tr>
                                            <th class="text-muted font-size-sm font-weight-bold py-3" style="width:50px;">#</th>
                                            <th class="text-muted font-size-sm font-weight-bold py-3">Estudiante</th>
                                            <th class="text-muted font-size-sm font-weight-bold py-3 text-center" style="width:160px;">Autoevaluación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($estudiantes as $i => $row): ?>
                                            <tr>
                                                <td class="text-muted font-size-sm py-3"><?php echo $i + 1; ?></td>
                                                <td class="font-weight-bold text-dark py-3">
                                                    <?php echo htmlspecialchars($row['student']); ?>
                                                    <?php if (!is_null($row['retirement_date'])): ?>
                                                        <span class="label label-sm label-light-danger label-inline ml-2">Retirado</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center py-3">
                                                    <?php if ($row['tiene_auto']): ?>
                                                        <span class="label label-success label-inline font-weight-bold">
                                                            <?php echo $row['autoevaluacion']; ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="label label-danger label-inline font-weight-bold">
                                                            Pendiente
                                                        </span>
                                                    <?php endif; ?>
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
