<script type="text/javascript">
	function getLicencia()
	{
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax({
			url: "<?php echo base_url(); ?>index.php/secretary/absence_get/<?php echo $param1; ?>",
			success: function(response)
			{
				document.getElementById('abcense').value = response;
			}
		});
	}
	getLicencia();
	</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Realmente desea eliminar la Ausencia</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'secretary/absence_delete'; ?>" method="post" class="form" >
<div class="modal-body">
    <div class="form-group">
    <label for="abcense"> Estudiante: </label>
        <input type="hidden" name="ausenciaId" id="ausenciaId" value="<?php echo $param1; ?>" >
        <input type="text" class="form-control" id="abcense" name="abcense" disabled >
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Eliminar</button>
</div>
</form> 
<!--end::Modal-->