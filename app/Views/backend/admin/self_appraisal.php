<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Autoevaluaciones
                        <span class="text-muted pt-2 font-size-sm d-block">Gestión de autoevaluaciones de todos los estudiantes matriculados</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <?php if ($solo_pendientes): ?>
                        <a href="<?php echo base_url(); ?>admin/self_appraisal/<?php echo $active_phase_id; ?>?ver=todos"
                           class="btn btn-sm btn-light-primary font-weight-bold">
                            Ver todos
                        </a>
                    <?php else: ?>
                        <a href="<?php echo base_url(); ?>admin/self_appraisal/<?php echo $active_phase_id; ?>"
                           class="btn btn-sm btn-light-warning font-weight-bold">
                            Ver solo pendientes
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body pt-4">
                <!--begin::Nav Tabs (navegación por enlace, sin recargar todas las fases en el DOM)-->
                <ul class="nav nav-tabs nav-tabs-line mb-6" role="tablist">
                    <?php foreach ($phases as $phase): ?>
                        <?php
                        $ph_id = $phase['phase_id'];
                        $counts = $phase_counts[$ph_id] ?? ['total' => 0, 'con_auto' => 0];
                        $total_pend = $counts['total'] - $counts['con_auto'];
                        $is_active = ($ph_id == $active_phase_id);
                        $tab_href = base_url() . 'admin/self_appraisal/' . $ph_id . ($solo_pendientes ? '' : '?ver=todos');
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $is_active ? 'active' : ''; ?> font-weight-bold"
                               href="<?php echo $tab_href; ?>">
                                <?php echo htmlspecialchars($phase['name']); ?>
                                <?php if ($total_pend > 0): ?>
                                    <span class="badge badge-danger badge-pill ml-2"><?php echo $total_pend; ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success badge-pill ml-2"><?php echo $counts['total']; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <!--end::Nav Tabs-->

                <p class="text-muted mb-6">
                    <?php if ($solo_pendientes): ?>
                        Mostrando solo estudiantes <strong>sin</strong> autoevaluación registrada.
                    <?php else: ?>
                        Mostrando <strong>todos</strong> los estudiantes (registrados y pendientes).
                    <?php endif; ?>
                </p>

                <?php if (empty($por_curso)): ?>
                    <div class="d-flex flex-column align-items-center py-10 text-center">
                        <i class="flaticon2-check-mark text-success" style="font-size:3rem;"></i>
                        <p class="text-success font-weight-bold mt-4 mb-0">
                            <?php echo $solo_pendientes
                                ? 'No hay pendientes &mdash; todos los estudiantes tienen su autoevaluación registrada.'
                                : 'No hay estudiantes matriculados y activos registrados.'; ?>
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach ($por_curso as $curso_data): ?>
                        <?php
                        $estudiantes = $curso_data['estudiantes'];
                        $pendientes  = $curso_data['total'] - $curso_data['con_auto'];
                        ?>
                        <div class="mb-8 curso-block" data-pendientes="<?php echo $pendientes; ?>">
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
                                            <th class="text-muted font-size-sm font-weight-bold py-3 text-center" style="width:140px;">Autoevaluación</th>
                                            <th class="text-muted font-size-sm font-weight-bold py-3 text-center" style="width:180px;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($estudiantes as $i => $row): ?>
                                            <tr class="fila-estudiante" data-pendiente="<?php echo $row['tiene_auto'] ? '0' : '1'; ?>">
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
                                                            <?php echo $row['autoevaluacion']; ?>/5 pts.
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="label label-danger label-inline font-weight-bold">
                                                            Pendiente
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center py-3">
                                                    <button type="button" class="btn btn-sm btn-<?php echo $row['tiene_auto'] ? 'warning' : 'light-success'; ?> font-weight-bold"
                                                        onclick="showAjaxModal('<?php echo base_url(); ?>/modal/popup/self_appraisal_modal_edit/<?php echo $row['student_id']; ?>/<?php echo $active_phase_id; ?>/0/0/0');">
                                                        <?php echo $row['tiene_auto'] ? 'Editar' : 'Registrar'; ?>
                                                    </button>
                                                    <?php if ($row['tiene_auto']): ?>
                                                    <button type="button" class="btn btn-sm btn-danger font-weight-bold"
                                                        onclick="showAjaxModal('<?php echo base_url(); ?>/modal/popup/self_appraisal_modal_del/<?php echo $row['self_id']; ?>/<?php echo $active_phase_id; ?>/0/0/0');">
                                                        Eliminar
                                                    </button>
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
    <!--end::Container-->
</div>
<!--end::Entry-->
