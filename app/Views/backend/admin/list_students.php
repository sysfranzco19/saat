<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom gutter-b example-hover">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Lista de Cursos</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Accordion-->
                        <div class="accordion accordion-toggle-arrow" id="accordionExample1">
                            <?php if (!empty($cursos)) : ?>
                                <?php foreach ($cursos as $index => $curso) : ?>
                                    <?php $sid = $curso['section_id']; ?>
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="card-title" data-toggle="collapse" data-target="#collapse<?= $index ?>">
                                                <?= esc($curso['completo']) ?>
                                            </div>
                                        </div>
                                        <div id="collapse<?= $index ?>" class="collapse <?= $index === 0 ? 'show' : '' ?>" data-parent="#accordionExample1">
                                            <div class="card-body">
                                                <table class="table table-bordered table-checkable">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>ID</th>
                                                            <th>Nombre</th>
                                                            <th>Acción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($students_by_section[$sid])) : ?>
                                                            <?php foreach ($students_by_section[$sid] as $i => $student) : ?>
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
                                                            <tr><td colspan="4" class="text-center">No hay estudiantes en este curso.</td></tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>No hay cursos disponibles.</p>
                            <?php endif; ?>
                        </div>
                        <!--end::Accordion-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->