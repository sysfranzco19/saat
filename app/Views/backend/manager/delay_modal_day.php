<script type="text/javascript">   
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
	<h5 class="modal-title" id="exampleModalLabel">Fecha de los retrasos</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'manager/delays_day'; ?>" method="post" class="form-horizontal" >
<div class="modal-body">
    <div class="form-group row">
    	<label for="student" class="col-3 col-form-label">Ingrese la Fecha: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="date" class="form-control" id="fechaRetraso" name="fechaRetraso" >
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Mostrar</button>
</div>
</form> 
<!--end::Modal-->

