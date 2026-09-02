<?php
// param1=student_id, param2=date (Y-m-d), param3=status actual, param4=subject_id, param5=nombre alumno (urlencoded)
?>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Corregir asistencia de: <?php echo urldecode($param5); ?></h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url() . 'teacher/prim_attendance_report_save'; ?>" method="post" name="form_prim_attendance" class="form">
<div class="modal-body">
	<input type="hidden" name="student_id" value="<?php echo $param1; ?>">
	<input type="hidden" name="date" value="<?php echo $param2; ?>">
	<input type="hidden" name="subject_id" value="<?php echo $param4; ?>">

	<div class="form-group row">
		<label class="col-xl-3 col-lg-3 col-form-label">Fecha</label>
		<div class="col-9 col-form-label"><?php echo date('d/m/Y', strtotime($param2)); ?></div>
	</div>

	<div class="form-group row">
		<label class="col-xl-3 col-lg-3 col-form-label">Estado</label>
		<div class="col-9 col-form-label">
			<div class="checkbox-inline">
				<label class="checkbox checkbox-success">
					<input type="radio" name="status" <?php if ($param3 == 1) echo 'checked'; ?> value="1" />
					<span></span>
					Presente
				</label>
				<label class="checkbox checkbox-warning">
					<input type="radio" name="status" <?php if ($param3 == 3) echo 'checked'; ?> value="3" />
					<span></span>
					Retraso
				</label>
				<label class="checkbox checkbox-danger">
					<input type="radio" name="status" <?php if ($param3 == 0) echo 'checked'; ?> value="0" />
					<span></span>
					Ausente
				</label>
			</div>
			<?php if ($param3 == 2): ?>
			<small class="text-muted d-block mt-2">
				Este día está marcado como <strong>Licencia</strong> por una solicitud aprobada.
				Para cambiarlo, hazlo desde Gestión de Licencias.
			</small>
			<?php endif; ?>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-xl-3 col-lg-3 col-form-label">Observación</label>
		<div class="col-9 col-form-label">
			<textarea class="form-control" name="obs" rows="2" spellcheck="false" data-gramm="false"></textarea>
		</div>
	</div>

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
</div>
</form>
<!--end::Modal-->
