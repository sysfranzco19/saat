<script type="text/javascript">
    function getDelay()
    {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
			url: "<?php echo base_url(); ?>secretary/delay_get/<?php echo $param1; ?>",
			success: function(response)
			{
				//document.getElementById('nombreEstudiante').value = response;
                var content = JSON.parse(response);
                for(var i = 0;i < content.length; i++)
                     {
                        document.getElementById('nombreEstudiante').value = content[i].student;
                        document.getElementById('fechaRetraso').value = content[i].date_class;
                        document.getElementById('horaIngreso').value = content[i].hora_ingreso;
                        document.getElementById('horaLlegada').value = content[i].hora_llegada;
                        document.getElementById('motivo').value = content[i].motivo;
                     }
			}
		});
        //document.getElementById('horaLlegada').focus();
    }
    getDelay();
</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Eliminar Retraso</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'secretary/delay_delete'; ?>" method="post" class="form-horizontal" >
<div class="modal-body">
    <div class="form-group row">
    	<label for="student" class="col-3 col-form-label">Estudiante: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="hidden" id="delay_id" name="delay_id" value="<?php echo $param1; ?>" >
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $param2; ?>">
            <input type="text" class="form-control" id="nombreEstudiante" name="nombreEstudiante" disabled >
        </div>
    </div>
    <div class="form-group row">
    	<label for="student" class="col-3 col-form-label">Fecha Retraso: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="text" class="form-control" id="fechaRetraso" name="fechaRetraso" disabled >
        </div>
    </div>
    <div class="form-group row">
    	<label for="horaIngreso" class="col-3 col-form-label">Hora de Ingreso: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="text" class="form-control" id="horaIngreso" name="horaIngreso" disabled >
        </div>
    </div>
    <div class="form-group row">
    	<label for="horaLlegada" class="col-3 col-form-label">Hora que llego: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="text" class="form-control" id="horaLlegada" name="horaLlegada" disabled >
        </div>
    </div>
    <div class="form-group row">
    	<label for="horaLlegada" class="col-3 col-form-label">Motivo del Retraso: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="text" class="form-control" id="motivo" name="motivo" disabled >
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-danger font-weight-bold">Eliminar</button>
</div>
</form> 
<!--end::Modal-->

