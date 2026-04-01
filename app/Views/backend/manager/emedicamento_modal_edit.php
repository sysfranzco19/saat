<script type="text/javascript">
	function getEmedicamento()
	{
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax({
			url: "<?php echo base_url(); ?>manager/emedicamento_get/<?php echo $param1; ?>",
			success: function(response)
			{
				document.getElementById('nombre').value = response;
			}
		});
	}
	getEmedicamento();
	</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Modificar Medicamento</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'manager/emedicamento_update'; ?>" method="post" class="form" >
<div class="modal-body">
    <div class="form-group row">
    <label for="nombre" class="col-3 col-form-label">Medicamento: </label>
    	<div class="col-9">
            <input type="hidden" name="id" id="id" value="<?php echo $param1; ?>" >
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Medicamentos" >
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-warning font-weight-bold">Guardar Cambios</button>
</div>
</form> 
<!--end::Modal-->