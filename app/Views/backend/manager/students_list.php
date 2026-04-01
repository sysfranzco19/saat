<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-circle symbol-light-primary mr-5">
                            <span class="symbol-label">
                                <i class="flaticon-users-1 text-primary icon-2x"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <h3 class="text-dark font-weight-bold mb-0">Lista de Estudiantes (Psicopedagógico)</h3>
                            <span class="text-muted font-weight-bold">Visualización de estudiantes activos por curso
                                asignado.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->

        <?php if (!empty($grouped_students)): ?>
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
                                <h4 class="mb-5 text-primary font-weight-bolder">
                                    <?= $data['completo'] ?>
                                </h4>
                                <div class="table-responsive">
                                    <table class="table table-head-custom table-vertical-center datatable_students"
                                        id="table_<?= $data['section_id'] ?>"
                                        data-export-title="Lista de Estudiantes - <?= $data['completo'] ?>">
                                        <thead>
                                            <tr class="text-left">
                                                <th style="min-width: 300px">Nombre del Estudiante</th>
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
                                                                    <?= $stu['lastname'] ?> <?= $stu['lastname2'] ?> <?= $stu['name'] ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url('manager/student_search/manager/' . $stu['lastname']) ?>"
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
        <?php else: ?>
            <div class="card card-custom gutter-b">
                <div class="card-body text-center py-20">
                    <i class="flaticon-search-1 icon-10x text-muted mb-10"></i>
                    <h4 class="text-muted">No se encontraron estudiantes asignados a su rango de gestión.</h4>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.datatable_students').each(function () {
            var exportTitle = $(this).data('export-title');
            $(this).DataTable({
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: exportTitle,
                        className: 'btn btn-light-success font-weight-bold'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: exportTitle,
                        className: 'btn btn-light-danger font-weight-bold'
                    },
                    {
                        extend: 'print',
                        title: exportTitle,
                        className: 'btn btn-light-primary font-weight-bold'
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                order: [[0, "asc"]],
                lengthMenu: [ [10, 25, 30, 50, -1], [10, 25, 30, 50, "Todos"] ],
                pageLength: 30
            });
        });
    });
</script>