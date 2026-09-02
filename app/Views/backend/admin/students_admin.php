<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Estudiantes
                        <span class="text-muted pt-2 font-size-sm d-block">
                            Gestión de estudiantes &mdash; <?php echo number_format($total); ?> resultado<?php echo $total != 1 ? 's' : ''; ?>
                        </span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-light-success font-weight-bold"
                        onclick="showAjaxModal('<?php echo base_url(); ?>/modal/popup/students_admin_modal_add/0/0/0/0/0');">
                        Nuevo Estudiante
                    </button>
                </div>
            </div>

            <div class="card-body pt-4">
                <!--begin::Buscador-->
                <form method="GET" action="<?php echo base_url(); ?>admin/students" class="form-inline mb-6">
                    <div class="input-group" style="max-width:400px;">
                        <input type="text" name="buscar" class="form-control" placeholder="Buscar por apellidos..."
                            value="<?php echo htmlspecialchars($buscar); ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                            <?php if ($buscar !== ''): ?>
                            <a href="<?php echo base_url(); ?>admin/students" class="btn btn-light-secondary">Limpiar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
                <!--end::Buscador-->

                <?php if (empty($students)): ?>
                    <div class="d-flex flex-column align-items-center py-10 text-center">
                        <i class="flaticon2-search text-muted" style="font-size:3rem;"></i>
                        <p class="text-muted font-weight-bold mt-4 mb-0">
                            No se encontraron estudiantes<?php echo $buscar !== '' ? ' para "' . htmlspecialchars($buscar) . '"' : ''; ?>.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-head-bg-light mb-0" style="border-radius:8px;overflow:hidden;">
                            <thead>
                                <tr>
                                    <th class="text-muted font-size-sm font-weight-bold py-3">Apellidos y Nombres</th>
                                    <th class="text-muted font-size-sm font-weight-bold py-3">Código</th>
                                    <th class="text-muted font-size-sm font-weight-bold py-3">Curso</th>
                                    <th class="text-muted font-size-sm font-weight-bold py-3 text-center">Matrícula</th>
                                    <th class="text-muted font-size-sm font-weight-bold py-3 text-center">Estado</th>
                                    <th class="text-muted font-size-sm font-weight-bold py-3 text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $row): ?>
                                    <tr>
                                        <td class="font-weight-bold text-dark py-3">
                                            <?php echo htmlspecialchars($row['lastname'] . ' ' . $row['lastname2'] . ' ' . $row['name']); ?>
                                        </td>
                                        <td class="py-3"><?php echo htmlspecialchars($row['code']); ?></td>
                                        <td class="py-3"><?php echo htmlspecialchars($row['completo'] ?? '—'); ?></td>
                                        <td class="text-center py-3"><?php echo (int) $row['matricula']; ?></td>
                                        <td class="text-center py-3">
                                            <?php if ($row['activo'] == 1): ?>
                                                <span class="label label-success label-inline font-weight-bold">Activo</span>
                                            <?php else: ?>
                                                <span class="label label-light-danger label-inline font-weight-bold">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center py-3">
                                            <button type="button" class="btn btn-sm btn-warning font-weight-bold"
                                                onclick="showAjaxModal('<?php echo base_url(); ?>/modal/popup/students_admin_modal_edit/<?php echo $row['student_id']; ?>/0/0/0/0');">
                                                Editar
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger font-weight-bold"
                                                onclick="showAjaxModal('<?php echo base_url(); ?>/modal/popup/students_admin_modal_del/<?php echo $row['student_id']; ?>/0/0/0/0');">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($total_paginas > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-6">
                        <span class="text-muted">Página <?php echo $page; ?> de <?php echo $total_paginas; ?></span>
                        <div>
                            <?php
                            $qs = $buscar !== '' ? '&buscar=' . urlencode($buscar) : '';
                            ?>
                            <?php if ($page > 1): ?>
                                <a href="<?php echo base_url(); ?>admin/students?page=<?php echo ($page - 1) . $qs; ?>" class="btn btn-sm btn-light-primary font-weight-bold">« Anterior</a>
                            <?php endif; ?>
                            <?php if ($page < $total_paginas): ?>
                                <a href="<?php echo base_url(); ?>admin/students?page=<?php echo ($page + 1) . $qs; ?>" class="btn btn-sm btn-light-primary font-weight-bold">Siguiente »</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
