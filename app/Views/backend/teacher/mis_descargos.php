<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container-fluid">
		<!--begin::Card-->
        <div class="card card-custom">
        	<!--begin::Header-->
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
            	<div class="card-title">
                    <h3 class="card-label">Mis Descargos
                    <span class="d-block text-muted pt-2 font-size-sm">Historial de descargos de estudiantes aplazados generados por usted</span></h3>
                </div>
                <div class="card-toolbar">
                    <?php if (count($descargos) > 0): ?>
                        <a href="<?php echo base_url(); ?>teacher/descargos_zip?phase_id=<?php echo urlencode($filtro_phase_id); ?>&subject_id=<?php echo urlencode($filtro_subject_id); ?>" class="btn btn-light-primary font-weight-bold">
                            <i class="fa fa-file-archive mr-1"></i>Descargar Todo (ZIP)
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Filtros-->
            <div class="card-body border-bottom">
                <form method="get" action="<?php echo base_url(); ?>teacher/mis_descargos" class="form-inline">
                    <div class="form-group mr-4">
                        <label class="mr-2">Trimestre:</label>
                        <select name="phase_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Todos</option>
                            <?php foreach ($fases as $f): ?>
                                <option value="<?php echo $f['phase_id']; ?>" <?php echo ($filtro_phase_id == $f['phase_id']) ? 'selected' : ''; ?>><?php echo $f['phase_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mr-4">
                        <label class="mr-2">Materia:</label>
                        <select name="subject_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Todas</option>
                            <?php foreach ($materias as $m): ?>
                                <option value="<?php echo $m['subject_id']; ?>" <?php echo ($filtro_subject_id == $m['subject_id']) ? 'selected' : ''; ?>><?php echo $m['materia']; ?> (<?php echo $m['completo']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <!--end::Filtros-->
            <!--begin::Section-->
            <div class="card-body">
                <table class='table table-hover'>
                    <thead class='thead-inverse'>
                        <tr>
                            <th>Estudiante</th>
                            <th>Materia</th>
                            <th>Curso</th>
                            <th>Trimestre</th>
                            <th>Nota Final</th>
                            <th>Reunión Padres</th>
                            <th>Fecha</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($descargos) == 0): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No tiene descargos guardados con estos filtros.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($descargos as $d): ?>
                                <tr>
                                    <td><?php echo $d['student']; ?></td>
                                    <td><?php echo $d['subject_name']; ?></td>
                                    <td><?php echo $d['curso']; ?></td>
                                    <td><?php echo $d['phase_name']; ?></td>
                                    <td><span class="text-danger font-weight-bold"><?php echo $d['nota_final']; ?></span></td>
                                    <td><?php echo $d['reunion_padres'] ? 'Sí (' . (int) $d['nro_reuniones'] . ')' : 'No'; ?></td>
                                    <td><?php echo $d['created_at']; ?></td>
                                    <td>
                                        <a href="<?php echo base_url(); ?>teacher/descargo_pdf/<?php echo $d['id']; ?>" target="_blank" class="btn btn-success btn-sm">Descargar PDF</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
			</div>
			<!--end::Section-->
		</div>
		<!--end::Card-->
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->
