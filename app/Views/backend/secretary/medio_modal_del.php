<script type="text/javascript">
	function getMedioComunicacion()
	{
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax({
			url: "<?php echo base_url(); ?>/secretary/medio_get/<?php echo $param1; ?>",
			success: function(response)
			{
				document.getElementById('medioComunicacion').value = response;
			}
		});
	}
	getMedioComunicacion();
	</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Realmente desea eliminar el Medio de comunicación</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'secretary/medio_delete'; ?>" method="post" class="form" >
<div class="modal-body">
    <div class="form-group row">
    <label for="medioComunicacion" class="col-3 col-form-label">Medio de Comunicación: </label>
    	<div class="col-9">
            <input type="hidden" name="medioId" id="medioId" value="<?php echo $param1; ?>" >
            <input type="text" class="form-control" id="medioComunicacion" name="medioComunicacion" disabled >
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Eliminar</button>
</div>
</form> 
<!--end::Modal-->