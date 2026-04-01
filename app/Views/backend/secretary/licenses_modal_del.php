<script type="text/javascript">
	function getLicencia()
	{
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax({
			url: "<?php echo base_url(); ?>secretary/licencia_get/<?php echo $param1; ?>",
			success: function(response)
			{
				document.getElementById('licencia').value = response.student + ' (' + response.nick_name + ') - ' + response.detalle;
			}
		});
	}
	getLicencia();
	</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Realmente desea eliminar la Licencia</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'secretary/licencia_delete'; ?>" method="post" class="form" >
<div class="modal-body">
    <div class="form-group">
    <label for="licencia"> Datos de la Licencia: </label>
        <input type="hidden" name="licenciaId" id="licenciaId" value="<?php echo $param1; ?>" >
        <textarea class="form-control" id="licencia" name="licencia" rows="3" disabled></textarea>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Eliminar</button>
</div>
</form> 
<!--end::Modal-->