<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Entrega de Notas
                        <span class="text-muted pt-2 font-size-sm d-block">
                            Reporte de notas por materia &mdash; todos los docentes, todos los niveles &mdash; <?php echo htmlspecialchars($phase_name); ?>
                        </span>
                    </h3>
                </div>
            </div>

            <div class="card-body pt-4">
                <!--begin::Buscador-->
                <form method="GET" action="<?php echo base_url(); ?>admin/delivery_notes" class="form-inline mb-4">
                    <input type="hidden" name="estado" value="<?php echo htmlspecialchars($estado); ?>">
                    <div class="input-group" style="max-width:420px;">
                        <input type="text" name="buscar" class="form-control"
                            placeholder="Buscar por docente, materia o curso..."
                            value="<?php echo htmlspecialchars($buscar); ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                            <?php if ($buscar !== ''): ?>
                            <a href="<?php echo base_url(); ?>admin/delivery_notes?estado=<?php echo urlencode($estado); ?>" class="btn btn-light-secondary">Limpiar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
                <!--end::Buscador-->

                <!--begin::Filtro Estado-->
                <?php
                $qs_buscar = $buscar !== '' ? '&buscar=' . urlencode($buscar) : '';
                ?>
                <div class="btn-group mb-6" role="group">
                    <a href="<?php echo base_url(); ?>admin/delivery_notes?estado=abierta<?php echo $qs_buscar; ?>"
                       class="btn btn-sm font-weight-bold <?php echo $estado === 'abierta' ? 'btn-success' : 'btn-light-success'; ?>">
                        Abierta
                    </a>
                    <a href="<?php echo base_url(); ?>admin/delivery_notes?estado=consolidada<?php echo $qs_buscar; ?>"
                       class="btn btn-sm font-weight-bold <?php echo $estado === 'consolidada' ? 'btn-secondary' : 'btn-light-secondary'; ?>">
                        Consolidada
                    </a>
                    <a href="<?php echo base_url(); ?>admin/delivery_notes?estado=todas<?php echo $qs_buscar; ?>"
                       class="btn btn-sm font-weight-bold <?php echo $estado === 'todas' ? 'btn-primary' : 'btn-light-primary'; ?>">
                        Todas
                    </a>
                </div>
                <!--end::Filtro Estado-->

                <?php if (empty($por_docente)): ?>
                    <div class="d-flex flex-column align-items-center py-10 text-center">
                        <i class="flaticon2-search text-muted" style="font-size:3rem;"></i>
                        <p class="text-muted font-weight-bold mt-4 mb-0">
                            No se encontraron materias<?php echo $buscar !== '' ? ' para "' . htmlspecialchars($buscar) . '"' : ''; ?>.
                        </p>
                    </div>
                <?php else: ?>
                    <?php foreach ($por_docente as $docente_data): ?>
                        <?php $materias = $docente_data['materias']; ?>
                        <div class="mb-8">
                            <div class="d-flex align-items-center mb-3">
                                <span class="bullet bullet-bar bg-primary align-self-stretch mr-3" style="width:4px;border-radius:4px;"></span>
                                <div class="d-flex flex-column flex-grow-1">
                                    <span class="text-dark font-weight-bold font-size-lg">
                                        <?php echo htmlspecialchars($docente_data['docente']); ?>
                                    </span>
                                </div>
                                <span class="label label-lg label-light-primary label-inline font-weight-bold">
                                    <?php echo count($materias); ?> materia<?php echo count($materias) != 1 ? 's' : ''; ?>
                                </span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover table-head-bg-light mb-0" style="border-radius:8px;overflow:hidden;">
                                    <thead>
                                        <tr>
                                            <th class="text-muted font-size-sm font-weight-bold py-3">Materia</th>
                                            <th class="text-muted font-size-sm font-weight-bold py-3">Curso</th>
                                            <th class="text-muted font-size-sm font-weight-bold py-3 text-center" style="width:140px;">Estado</th>
                                            <th class="text-muted font-size-sm font-weight-bold py-3 text-center" style="width:140px;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($materias as $row): ?>
                                            <tr>
                                                <td class="font-weight-bold text-dark py-3"><?php echo htmlspecialchars($row['materia']); ?></td>
                                                <td class="py-3"><?php echo htmlspecialchars($row['completo']); ?></td>
                                                <td class="text-center py-3">
                                                    <?php if ($row['locked']): ?>
                                                        <span class="label label-secondary label-inline font-weight-bold">Consolidada</span>
                                                    <?php else: ?>
                                                        <span class="label label-success label-inline font-weight-bold">Abierta</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center py-3">
                                                    <a href="<?php echo base_url(); ?>admin/deliver_notes/<?php echo $row['subject_id']; ?>"
                                                       class="btn btn-sm btn-light-primary font-weight-bold">
                                                        Ver Reporte
                                                    </a>
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
