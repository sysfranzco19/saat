<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label"><?= esc($curso_nombre) ?></h3>
                        </div>
                        <div class="card-toolbar">
                            <a href="<?= base_url('admin/list_students') ?>" class="btn btn-light-primary font-weight-bold">
                                <i class="la la-arrow-left"></i> Volver a la lista
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover table-checkable" id="tabla_estudiantes">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Nombre completo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($students)) : ?>
                                    <?php foreach ($students as $i => $student) : ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= esc($student['student_id']) ?></td>
                                            <td><?= esc($student['lastname']) ?> <?= esc($student['lastname2']) ?> <?= esc($student['name']) ?></td>
                                            <td>
                                                <a href="<?= base_url('inscripcion_rude/' . $student['student_id']) ?>" target="_blank" class="btn btn-primary btn-sm">Rude</a>
                                                <a href="<?= base_url('informe_family/' . $student['family_id']) ?>" target="_blank" class="btn btn-secondary btn-sm">Informe Familia</a>
                                                <a href="<?= base_url('admin/student_notes/' . $student['student_id'] . '/' . $phase_id) ?>" target="_blank" class="btn btn-warning btn-sm">Notas</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="4" class="text-center">No hay estudiantes registrados en este curso.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->