<script type="text/javascript">
    function getStudent()
    {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
			url: "<?php echo base_url(); ?>server/student_get/<?php echo $param1; ?>",
			success: function(response)
			{
				document.getElementById('nombreEstudiante').value = response;
			}
		});
        //document.getElementById('horaLlegada').focus();
    }
    getStudent();
    
    function getInicio()
    {
        document.getElementById('horaLlegada').focus();
    }
    
    // Obtener el elemento con el id "fechaRetraso"
    var fechaRetrasoElemento = document.getElementById("fechaRetraso");
    var fechaActualUTC = new Date();

    // Ajustar la fecha y hora a la zona horaria de 'America/La_Paz'
    var fechaActualLaPaz = new Date(fechaActualUTC.toLocaleString("en-US", {timeZone: "America/La_Paz"}));
    var fechaFormateada = fechaActualLaPaz.toISOString().split('T')[0];
    fechaRetrasoElemento.value = fechaFormateada;
    getInicio();
</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Nuevo Retraso</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'manager/delay_create'; ?>" method="post" class="form-horizontal" >
<div class="modal-body">
    <div class="form-group row">
    	<label for="student" class="col-3 col-form-label">Estudiante: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $param1; ?>" >
            <input type="text" class="form-control" id="nombreEstudiante" name="nombreEstudiante" onchange="getInicio();" >
        </div>
    </div>
    <div class="form-group row">
    	<label for="student" class="col-3 col-form-label">Fecha Retraso: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="date" class="form-control" id="fechaRetraso" name="fechaRetraso" onchange="getInicio();" >
        </div>
    </div>
    <div class="form-group row">
    	<label for="horaIngreso" class="col-3 col-form-label">Hora de Ingreso: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="time" class="form-control" id="horaIngreso" name="horaIngreso" value="<?php echo $param2; ?>" >
        </div>
    </div>
    <div class="form-group row">
    	<label for="horaLlegada" class="col-3 col-form-label">Hora que llego: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="time" class="form-control" id="horaLlegada" name="horaLlegada" >
        </div>
    </div>
    <div class="form-group row">
    	<label for="horaLlegada" class="col-3 col-form-label">Motivo del Retraso: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="text" class="form-control" id="motivo" name="motivo" value= "INJUSTIFICADO" >
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
</div>
</form> 
<!--end::Modal-->

