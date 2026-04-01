<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!-- Header -->
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-circle symbol-light-primary mr-5">
                            <span class="symbol-label font-size-h2 font-weight-bold">
                                <i class="flaticon-users text-primary"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="text-dark font-weight-bold mb-0">Lista de Estudiantes</h3>
                            <span class="text-muted font-weight-bold">
                                Visualización de estudiantes agrupados por curso.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-custom gutter-b">
            <div class="card-header card-header-tabs-line">
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-bold nav-tabs-line">
                        <?php
                        $first = true;
                        foreach ($grouped_students as $nick => $data): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $first ? 'active' : '' ?>" data-toggle="tab"
                                    href="#tab_<?= $data['section_id'] ?>">
                                    <span class="nav-text">
                                        <?= $nick ?>
                                    </span>
                                </a>
                            </li>
                            <?php
                            $first = false;
                        endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <?php
                    $first = true;
                    foreach ($grouped_students as $nick => $data): ?>
                        <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="tab_<?= $data['section_id'] ?>"
                            role="tabpanel">
                            <h4 class="mb-5 text-dark-75 font-weight-bolder">
                                <?= $data['completo'] ?>
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-head-custom table-vertical-center datatable_students"
                                    id="table_<?= $data['section_id'] ?>" data-export-title="Lista de Estudiantes - <?= $data['completo'] ?>">
                                    <thead>
                                        <tr class="text-left">
                                            <th style="min-width: 250px">Estudiante</th>
                                            <th style="min-width: 150px">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                                    <?php foreach ($data['students'] as $stu): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="d-flex flex-column">
                                                            <span
                                                                class="text-dark-75 font-weight-bolder font-size-lg text-hover-primary mb-1">
                                                                            <?= $stu['student'] ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('teacher/student_profile/' . $stu['student_id'] . '/0') ?>"
                                                        class="btn btn-sm btn-light-primary font-weight-bold"
                                                        title="Ver Perfil">
                                                        <i class="flaticon-eye"></i> Perfil
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                        $first = false;
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.datatable_students').each(function() {
            var exportTitle = $(this).data('export-title');
            $(this).DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: exportTitle
                    },
                    {
                        extend: 'pdfHtml5',
                        title: exportTitle
                    },
                    'print'
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                "order": [[0, "asc"]],
                "pageLength": 30
            });
        });
    });
</script>