<script type="text/javascript">
	function getLicencia()
	{
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax({
			url: "<?php echo base_url(); ?>index.php/secretary/licencia_get/<?php echo $param1; ?>",
			success: function(response)
			{
				document.getElementById('student').value = response.student;
                document.getElementById('student_id').value = response.student_id;
                document.getElementById('solicitante').value = response.solicitante;
                document.getElementById('fecha').value = response.fecha_solicitud;
                document.getElementById('licencia').value = response.motivo + ' - ' + response.detalle;
                if (response.tipo_id==2) {
                    document.getElementById('inicio').value = response.hora_inicio;
                    document.getElementById('fin').value = response.hora_fin;
                } else {
                    document.getElementById('inicio').value = response.fecha_inicio;
                    document.getElementById('fin').value = response.fecha_fin;
                }
			}
		});
	}
	getLicencia();
	</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Realmente desea rechazar la Licencia</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'secretary/licenses_noauth'; ?>" method="post" class="form" >
<div class="modal-body">
    <div class="form-group">
        <label for="student">Estudiante: </label>
        <input type="hidden" name="licenciaId" id="licenciaId" value="<?php echo $param1; ?>" >
        <input type="hidden" name="student_id" id="student_id" value="<?php echo $param1; ?>" >
        <input type="text" class="form-control" id="student" name="student" disabled >
    </div>
    <div class="form-group">
        <label for="solicitante">Solicitado por: </label>
        <input type="text" class="form-control" id="solicitante" name="solicitante" disabled >
    </div>
    <div class="form-group">
        <label for="fecha">Fecha solicitada: </label>
        <input type="text" class="form-control" id="fecha" name="fecha" disabled >
    </div>
    <div class="form-group">
        <label for="licencia"> Datos de la Licencia: </label>
        <input type="text" class="form-control" id="licencia" name="licencia" disabled >
    </div>
    <div class="form-group">
        <label for="inicio">Inicio: </label>
        <input type="text" class="form-control" id="inicio" name="inicio" disabled >
    </div>
    <div class="form-group">
        <label for="fin">Fin: </label>
        <input type="text" class="form-control" id="fin" name="fin" disabled >
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-danger font-weight-bold">Rechazar</button>
</div>
</form> 
<!--end::Modal-->