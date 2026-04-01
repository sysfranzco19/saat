<?php $alumnos_total = count($estudiantes); ?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div class="d-flex flex-column-fluid">
<div class="container-fluid">

    <!--begin::Header banner-->
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom wave wave-animate-slow wave-danger mb-8">
                <div class="card-body">
                    <div class="d-flex align-items-center p-5">
                        <div class="mr-6">
                            <span class="svg-icon svg-icon-danger svg-icon-4x">
                                <i class="flaticon-warning-sign text-danger icon-4x"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="text-dark font-weight-bold mb-1">
                                <?php echo htmlspecialchars($seccion['completo'] ?? $seccion['grade'] . ' ' . $seccion['name']); ?>
                            </h2>
                            <div class="text-muted font-size-lg">
                                <?php echo htmlspecialchars($phase_name); ?> &nbsp;·&nbsp; Perfil de incidencias por estudiante
                            </div>
                        </div>
                        <div class="ml-auto">
                            <a href="<?php echo base_url(); ?>manager/incidencias"
                               class="btn btn-light-danger font-weight-bold">
                                <i class="flaticon2-left-arrow-1 icon-sm mr-1"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Header banner-->

    <!--begin::Stats-->
    <div class="row mb-6">
        <div class="col-md-3">
            <div class="card card-custom bg-danger" style="height:130px">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-white font-weight-bolder font-size-h2"><?php echo $total_inc; ?></div>
                    <div class="text-white font-weight-bold font-size-lg">Total incidencias</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom bg-warning" style="height:130px">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-white font-weight-bolder font-size-h2"><?php echo $alumnos_con; ?></div>
                    <div class="text-white font-weight-bold font-size-lg">Alumnos con incidencias</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom bg-primary" style="height:130px">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-white font-weight-bolder font-size-h2"><?php echo $alumnos_total; ?></div>
                    <div class="text-white font-weight-bold font-size-lg">Total estudiantes</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom bg-info" style="height:130px">
                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="text-white font-weight-bolder font-size-h2"><?php echo count($materias); ?></div>
                    <div class="text-white font-weight-bold font-size-lg">Materias</div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Stats-->

    <!--begin::Table-->
    <div class="card card-custom gutter-b">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label font-weight-bolder text-dark">Incidencias por estudiante y materia</span>
                <span class="text-muted mt-2 font-weight-bold font-size-sm">
                    <?php echo $alumnos_con; ?> de <?php echo $alumnos_total; ?> alumnos con alguna incidencia
                </span>
            </h3>
            <div class="card-toolbar">
                <div class="input-group input-group-sm" style="width:260px">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="la la-search"></i></span>
                    </div>
                    <input type="text" id="buscarAlumno" class="form-control"
                           placeholder="Buscar alumno..." oninput="filtrarAlumnos()">
                </div>
            </div>
        </div>
        <div class="card-body pt-2">
            <style>
            th.th-vert {
                writing-mode: vertical-rl;
                transform: rotate(180deg);
                white-space: nowrap;
                height: 130px;
                vertical-align: bottom;
                padding-bottom: 8px;
                font-size: 11px;
                font-weight: 600;
            }
            #tablaSeccion tbody tr:hover { background: #f3f6f9; }
            </style>
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="tablaSeccion">
                    <thead>
                        <tr class="text-muted font-weight-bold">
                            <th style="min-width:180px">Estudiante</th>
                            <th class="text-center" style="min-width:60px">Total</th>
                            <?php foreach ($materias as $m): ?>
                                <th class="th-vert text-center" title="<?php echo htmlspecialchars($m['teacher_name'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($m['materia']); ?>
                                    <?php if (!empty($m['teacher_name'])): ?>
                                        <span class="text-primary"> · <?php echo htmlspecialchars($m['teacher_name']); ?></span>
                                    <?php endif; ?>
                                </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($estudiantes as $est):
                        $total_est = $totales[$est['student_id']] ?? 0;
                        $badge     = $total_est >= 18 ? 'danger' : ($total_est >= 10 ? 'warning' : ($total_est >= 5 ? 'info' : ($total_est > 0 ? 'secondary' : 'light')));
                    ?>
                    <tr data-nombre="<?php echo strtolower($est['alumno']); ?>">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-30 symbol-light-<?php echo $badge; ?> mr-3 flex-shrink-0">
                                    <span class="symbol-label font-weight-bold font-size-sm">
                                        <?php echo strtoupper(substr($est['alumno'], 0, 1)); ?>
                                    </span>
                                </div>
                                <span class="font-weight-bold text-dark font-size-sm"><?php echo htmlspecialchars($est['alumno']); ?></span>
                            </div>
                        </td>
                        <td class="text-center">
                            <?php if ($total_est > 0): ?>
                                <span class="label label-<?php echo $badge; ?> label-inline font-weight-bolder"><?php echo $total_est; ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <?php foreach ($materias as $m):
                            $cnt = $matriz[$est['student_id']][$m['subject_id']] ?? 0;
                            $col = $cnt >= 10 ? 'danger' : ($cnt >= 5 ? 'warning' : ($cnt > 0 ? 'dark' : 'muted'));
                        ?>
                        <td class="text-center">
                            <span class="font-weight-bold text-<?php echo $col; ?> font-size-sm"><?php echo $cnt; ?></span>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($estudiantes)): ?>
                    <tr>
                        <td colspan="<?php echo 2 + count($materias); ?>" class="text-center text-muted py-8">
                            No hay estudiantes registrados en este curso.
                        </td>
                    </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Table-->

</div>
</div>
</div>

<script>
function filtrarAlumnos() {
    var q = document.getElementById('buscarAlumno').value.toLowerCase().trim();
    document.querySelectorAll('#tablaSeccion tbody tr[data-nombre]').forEach(function(tr) {
        tr.style.display = (!q || tr.dataset.nombre.indexOf(q) !== -1) ? '' : 'none';
    });
}
</script>
