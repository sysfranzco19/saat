<script type="text/javascript">
// Cuántos descargos de aplazados faltan por registrar todavía, y si hay
// OTROS pendientes ajenos a los descargos (ej. promedios en 0) que también
// bloquean la consolidación. Con esto, al completar/editar un descargo
// sabemos si ya se puede habilitar "Consolidar Notas" sin recargar la
// página.
var pendientesDescargos = <?php echo count(array_filter($aplazados, function ($a) { return !$a['descargo']; })); ?>;
var hayOtrosPendientes = <?php echo (isset($rev['Descargos de Aplazados']) ? count($rev) - 1 : count($rev)) > 0 ? 'true' : 'false'; ?>;
var consolidarModalUrl = '<?php echo base_url(); ?>/modal/popup/consolidate_modal_confirm/<?php echo $subject_id; ?>/0/0/0/0';

function verificarHabilitarConsolidar() {
    if (pendientesDescargos > 0 || hayOtrosPendientes) {
        return;
    }
    var wrapper = document.getElementById('consolidar_btn_wrapper');
    if (!wrapper || !wrapper.querySelector('button[disabled]')) {
        return; // ya está habilitado, o no existe el wrapper
    }
    wrapper.innerHTML = '<button type="button" class="btn btn-danger" onclick="showAjaxModal(\'' + consolidarModalUrl + '\');">Consolidar Notas</button>';
}

function toggleNroReuniones(radio) {
    var form = radio.closest('form');
    var group = form.querySelector('.nro-reuniones-group');
    var input = group.querySelector('input[name="nro_reuniones"]');
    if (radio.value === '1') {
        group.style.display = '';
    } else {
        group.style.display = 'none';
        input.value = '';
    }
}

function guardarDescargo(btn, studentId, subjectId) {
    // Se navega desde el botón clickeado en vez de reconstruir ids con
    // getElementById: si por datos duplicados llegara a haber más de un
    // modal/formulario con el mismo id en la página, esto siempre toma
    // el que el usuario realmente tiene abierto y llenó.
    var modalContent = btn.closest('.modal-content');
    var modalEl = btn.closest('.modal');
    var form = modalContent.querySelector('form');

    var reunion = form.querySelector('input[name="reunion_padres"]:checked');
    if (!reunion) {
        alert('Por favor indique si se reunió con los padres de familia.');
        return;
    }
    var nroReuniones = 0;
    if (reunion.value === '1') {
        var nroInput = form.querySelector('input[name="nro_reuniones"]');
        nroReuniones = parseInt(nroInput.value, 10);
        if (!nroReuniones || nroReuniones < 1) {
            alert('Indique cuántas veces se reunió con los padres de familia.');
            return;
        }
    }
    var estrategias = form.querySelector('textarea[name="estrategias_aplicadas"]').value.trim();
    var motivo = form.querySelector('textarea[name="motivo_aplazo"]').value.trim();
    if (estrategias === '' || motivo === '') {
        alert('Por favor complete las estrategias aplicadas y el motivo del aplazo.');
        return;
    }
    btn.disabled = true;
    btn.innerText = 'Guardando...';

    var fd = new FormData();
    fd.append('student_id', studentId);
    fd.append('subject_id', subjectId);
    fd.append('reunion_padres', reunion.value);
    fd.append('nro_reuniones', nroReuniones);
    fd.append('estrategias_aplicadas', estrategias);
    fd.append('motivo_aplazo', motivo);

    fetch('<?php echo base_url(); ?>teacher/save_descargo', {
        method: 'POST',
        body: fd
    })
    .then(function(r){ return r.json(); })
    .then(function(res){
        if (res.status === 'success') {
            // No se borra el formulario (a diferencia de antes): así el
            // modal sirve tanto para completar como para editar un
            // descargo ya guardado, mostrando siempre lo último ingresado.
            var pdfLink = modalContent.querySelector('.descargo-pdf-link');
            if (pdfLink) {
                pdfLink.href = res.pdf_url;
                pdfLink.classList.remove('d-none');
            }
            var rowBtn = document.getElementById('row_btn_descargo_' + studentId);
            if (rowBtn) {
                if (rowBtn.dataset.tieneDescargo !== '1') {
                    rowBtn.dataset.tieneDescargo = '1';
                    pendientesDescargos--;
                }
                rowBtn.classList.remove('btn-light-primary');
                rowBtn.classList.add('btn-light-success');
                rowBtn.innerHTML = '<i class="fa fa-check mr-1"></i>Ver / Editar descargo';
            }
            btn.disabled = false;
            btn.innerText = 'Guardar Descargo';
            $(modalEl).modal('hide');
            verificarHabilitarConsolidar();
        } else {
            alert(res.message || 'Ocurrió un error al guardar el descargo.');
            btn.disabled = false;
            btn.innerText = 'Guardar Descargo';
        }
    })
    .catch(function(){
        alert('Ocurrió un error al guardar el descargo.');
        btn.disabled = false;
        btn.innerText = 'Guardar Descargo';
    });
}
</script>

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container-fluid">
		<!--begin::Card-->
        <div class="card card-custom">
        	<!--begin::Header-->
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
            	<div class="card-title">
                    <h3 class="card-label">Notas de la Materia: <?php echo $subject; ?> &emsp; Curso: <?php echo $curso; ?>
                    <span class="d-block text-muted pt-2 font-size-sm">Una vez consolidadas las notas no podra modificar sus notas <?php echo count($rev); ?> </span></h3>
                </div>
                <div class="card-toolbar">
                	<div id="consolidar_btn_wrapper">
                	<?php
                    if (count($rev)==0) {
                        ?>
                        <button type="button" class="btn btn-danger" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/consolidate_modal_confirm/<?php echo $subject_id; ?>/0/0/0/0');">
					    Consolidar Notas
						</button>
                        <?php
                    }else{
                        ?>
                        <button type="button" class="btn btn-danger" disabled >
					    Consolidación No disponible
						</button>
                        <?php
                    }
                    ?>
                    </div>

                </div>
            </div>
            <!--end::Header-->
            <!--begin::Section-->
            <div class="card-body" id="mostrar_tabla">
                <table class='table'>
                    <thead class='thead-inverse'>
                        <tr>
                            <th>Tipo Revisión</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($rev)>0) {
                            foreach ($rev as $clave => $valor) {
                                ?>
                                <tr>
                                    <td><?php echo $clave;?></td>
                                    <td><?php echo $valor;?></td>
                                </tr>
                                <?php
                                }
                        }else{
                            ?>
                            <tr>
                                <td>Revisión de Notas</td>
                                <td>Notas Correctas, habilitado para consolidar</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

		</div>
        <?php if (count($aplazados) > 0): ?>
			<!--begin::Descargos de Aplazados-->
			<div class="card-body border-top py-4">
				<div class="d-flex align-items-center mb-3">
					<h5 class="mb-0 mr-3">Descargos de Aplazados</h5>
					<span class="text-muted font-size-sm">Nota final &lt; 51: complete el descargo de cada estudiante antes de consolidar.</span>
				</div>
				<table class="table table-sm mb-0">
					<tbody>
						<?php foreach ($aplazados as $apl): ?>
							<tr>
								<td class="align-middle"><?php echo $apl['student']; ?></td>
								<td class="align-middle text-danger font-weight-bolder" style="width:90px;">Nota: <?php echo $apl['total_average']; ?></td>
								<td class="align-middle text-right" style="width:220px;">
									<?php if ($apl['descargo']): ?>
										<button type="button" id="row_btn_descargo_<?php echo $apl['student_id']; ?>" class="btn btn-light-success btn-sm font-weight-bold" data-toggle="modal" data-target="#modal_descargo_<?php echo $apl['student_id']; ?>" data-tiene-descargo="1">
											<i class="fa fa-check mr-1"></i>Ver / Editar descargo
										</button>
									<?php else: ?>
										<button type="button" id="row_btn_descargo_<?php echo $apl['student_id']; ?>" class="btn btn-light-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#modal_descargo_<?php echo $apl['student_id']; ?>" data-tiene-descargo="0">
											Completar descargo
										</button>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<!--begin::Modales Descargo (fuera de la tabla: un <div> dentro de <tbody> es HTML inválido y el navegador "foster-parentea" su contenido, vaciando el <form>) -->
			<?php foreach ($aplazados as $apl): ?>
				<?php $d = $apl['descargo']; ?>
				<div class="modal fade" id="modal_descargo_<?php echo $apl['student_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header py-3">
								<h5 class="modal-title font-weight-bold">
									Descargo: <?php echo $apl['student']; ?>
									<span class="text-danger font-size-sm d-block">Nota final: <?php echo $apl['total_average']; ?></span>
								</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<i aria-hidden="true" class="ki ki-close"></i>
								</button>
							</div>
							<div class="modal-body">
								<?php if ($d): ?>
									<div class="alert alert-light-primary font-weight-bold py-2 mb-4">
										Este descargo ya está guardado. Puede editarlo y volver a guardarlo las veces que necesite.
									</div>
								<?php endif; ?>
								<form onsubmit="return false;">
									<div class="form-group">
										<label class="font-weight-bold">¿Se reunió con los padres de familia?</label><br>
										<label class="radio-inline mr-4">
											<input type="radio" name="reunion_padres" value="1" onchange="toggleNroReuniones(this)" <?php echo ($d && $d['reunion_padres'] == 1) ? 'checked' : ''; ?>> Sí
										</label>
										<label class="radio-inline">
											<input type="radio" name="reunion_padres" value="0" onchange="toggleNroReuniones(this)" <?php echo ($d && $d['reunion_padres'] == 0) ? 'checked' : ''; ?>> No
										</label>
									</div>
									<div class="form-group nro-reuniones-group" style="<?php echo ($d && $d['reunion_padres'] == 1) ? '' : 'display:none;'; ?>">
										<label class="font-weight-bold">¿Cuántas veces se reunió con los padres de familia?</label>
										<input type="number" class="form-control" name="nro_reuniones" min="1" step="1" style="max-width:120px;" value="<?php echo ($d && $d['nro_reuniones']) ? (int) $d['nro_reuniones'] : ''; ?>">
									</div>
									<div class="form-group">
										<label class="font-weight-bold">¿Qué estrategias aplicó antes del aplazo?</label>
										<textarea class="form-control" name="estrategias_aplicadas" rows="3"><?php echo $d ? esc($d['estrategias_aplicadas']) : ''; ?></textarea>
									</div>
									<div class="form-group mb-0">
										<label class="font-weight-bold">¿Por qué considera que el estudiante se aplazó?</label>
										<textarea class="form-control" name="motivo_aplazo" rows="3"><?php echo $d ? esc($d['motivo_aplazo']) : ''; ?></textarea>
									</div>
								</form>
							</div>
							<div class="modal-footer py-3 d-flex justify-content-between">
								<a href="<?php echo $d ? base_url() . 'teacher/descargo_pdf/' . $d['id'] : '#'; ?>" target="_blank" class="btn btn-success btn-sm font-weight-bold descargo-pdf-link <?php echo $d ? '' : 'd-none'; ?>">
									<i class="fa fa-file-pdf mr-1"></i>Descargar PDF
								</a>
								<div>
									<button type="button" class="btn btn-light-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
									<button type="button" class="btn btn-primary font-weight-bold" onclick="guardarDescargo(this, <?php echo $apl['student_id']; ?>, <?php echo $subject_id; ?>)">Guardar Descargo</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			<!--end::Modales Descargo-->
			<!--end::Descargos de Aplazados-->
			<?php endif; ?>
		<!--end::Section-->
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>ESTUDIANTE</th>
                            <th>SER</th>
                            <th>SABER</th>
                            <th>HACER</th>
                            <th>Autoevaluación</th>
                            <th>NOTA <?php echo $trim; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($csamarks as $notas){
                                $text = "dark";
                                if ($notas['total_average']<51) {
                                    $text = "danger";
                                }
                                ?>
                            <tr>
                                <td><?php echo $notas['student']; ?></td>
                                <td><?php echo $notas['ser_average']; ?></td>
                                <td><?php echo $notas['saber_average']; ?></td>
                                <td><?php echo $notas['hacer_average']; ?></td>
                                <td><?php echo $notas['autoevaluacion']; ?></td>
                                <td><span class="text-<?php echo $text; ?> font-weight-bolder d-block font-size-lg"><?php echo $notas['total_average']; ?></span></td>
                            </tr>
                                <?php
                            }
                            ?>

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
