<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Autoevaluaciones <?php echo $phase_name; ?>
                                <span class="d-block text-muted pt-2 font-size-sm">Listado de las autoevaluaciones registradas por los estudiantes</span>
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-head-custom table-vertical-center table-bordered">
                                <thead>
                                    <tr class="text-uppercase text-muted font-weight-bolder font-size-xs">
                                        <th class="pl-4">Estudiante</th>
                                        <th class="text-center">C1</th>
                                        <th class="text-center">C2</th>
                                        <th class="text-center">C3</th>
                                        <th class="text-center">C4</th>
                                        <th class="text-center">C5</th>
                                        <th class="text-center">C6</th>
                                        <th class="text-center">C7</th>
                                        <th class="text-center">C8</th>
                                        <th class="text-center">C9</th>
                                        <th class="text-center">C10</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $row): ?>
                                    <?php
                                        $existe = 0;
                                        $auto_row = null;
                                        foreach ($autos as $auto) {
                                            if ($row['student_id'] == $auto['student_id']) {
                                                $existe = 1;
                                                $auto_row = $auto;
                                                break;
                                            }
                                        }
                                    ?>
                                    <tr>
                                        <td class="pl-4 font-weight-bold text-dark-75">
                                            <?php echo htmlspecialchars($row['student']); ?>
                                        </td>
                                        <?php if ($existe && $auto_row): ?>
                                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                                <?php $val = (int) $auto_row['auto' . $i]; ?>
                                                <td class="text-center">
                                                    <?php if ($val === 1): ?>
                                                        <span class="label label-lg label-light-success label-inline font-weight-bold">Sí</span>
                                                    <?php else: ?>
                                                        <span class="label label-lg label-light-danger label-inline font-weight-bold">No</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endfor; ?>
                                            <td class="text-center">
                                                <span class="label label-lg label-light-primary label-inline font-weight-bolder">
                                                    <?php echo htmlspecialchars($auto_row['autoevaluacion']); ?>
                                                </span>
                                            </td>
                                        <?php else: ?>
                                            <td colspan="11" class="text-center text-muted font-italic">
                                                <span class="label label-lg label-light-warning label-inline">Sin autoevaluación</span>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
