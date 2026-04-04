<script type="text/javascript">
	function getLicencia()
	{
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
        /*
		$.ajax({
			url: "<?php echo base_url(); ?>index.php/secretary/licencia_get/<?php echo $param1; ?>",
			success: function(response)
			{
				document.getElementById('licencia').value = response;
			}
		});
        */
	}
	getLicencia();
	</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">¿Esta seguro de consolidar sus notas?</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'teacher/consolidate_notes'; ?>" method="post" class="form" >
<input type="hidden" name="subjectId" id="subjectId" value="<?php echo $param1; ?>" >
<!-- 
<div class="modal-body">
    <div class="form-group">
    <label for="licencia"> Estudiantes Reprobados: </label>
        
        <input type="text" class="form-control" id="licencia" name="licencia" disabled >
    </div>
</div>
 -->
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">No, Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Si, Consolidar Notas</button>
</div>
</form> 
<!--end::Modal-->