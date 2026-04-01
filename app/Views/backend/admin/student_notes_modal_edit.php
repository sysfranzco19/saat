<script type="text/javascript">
	function getCsamarksStudent() {
    $.ajax({
        url: "<?php echo base_url(); ?>admin/student_notes_get/<?php echo $param1; ?>",
        method: "GET",
        dataType: "json",  // Aseguramos que la respuesta sea JSON
        success: function(response) {
            if (response.length > 0) {
                const data = response[0]; // Accedemos al primer (y único) resultado
                document.getElementById('student').value = data.student;
                document.getElementById('materia').value = data.materia;
                document.getElementById('ser_average').value = data.ser_average;
                document.getElementById('saber_average').value = data.saber_average;
                document.getElementById('hacer_average').value = data.hacer_average;
                document.getElementById('decidir_average').value = data.decidir_average;
                document.getElementById('autoevaluacion').value = data.autoevaluacion;
                document.getElementById('total_average').value = data.total_average;
                document.getElementById('total_vc').value = data.total_vc;
            } else {
                alert('No se encontraron datos.');
            }
        },
        error: function() {
            alert('Ocurrió un error al recuperar los datos.');
        }
    });
}
	getCsamarksStudent();
	</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Modificar Notas <?php echo $param1; ?></h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'admin/student_notes_update'; ?>" method="post" class="form" >
<div class="modal-body">
<div class="form-group row">
    <label for="student" class="col-3 col-form-label">Estudiante: </label>
    	<div class="col-9">
            <input type="hidden" name="csamarks_id" id="csamarks_id" value="<?php echo $param1; ?>" >
            <input type="hidden" name="student_id" id="student_id" value="<?php echo $param2; ?>" >
            <input type="hidden" name="phase_id" id="phase_id" value="<?php echo $param3; ?>" >
            <input type="text" class="form-control" id="student" name="student" >
        </div>
    </div>
    <div class="form-group row">
    <label for="materia" class="col-3 col-form-label">MATERIA: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="materia" name="materia" >
        </div>
    </div>
    <div class="form-group row">
    <label for="ser_average" class="col-3 col-form-label">SER: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="ser_average" name="ser_average" >
        </div>
    </div>
    <div class="form-group row">
    <label for="serAverage" class="col-3 col-form-label">SABER: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="saber_average" name="saber_average" >
        </div>
    </div>
    <div class="form-group row">
    <label for="hacer_average" class="col-3 col-form-label">HACER: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="hacer_average" name="hacer_average" >
        </div>
    </div>
    <div class="form-group row">
    <label for="decidir_average" class="col-3 col-form-label">DECIDIR: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="decidir_average" name="decidir_average" >
        </div>
    </div>
    <div class="form-group row">
    <label for="autoevaluacion" class="col-3 col-form-label">AUTOEVALUACION: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="autoevaluacion" name="autoevaluacion" >
        </div>
    </div>
    <div class="form-group row">
    <label for="total_average" class="col-3 col-form-label">TOTAL: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="total_average" name="total_average" >
        </div>
    </div>
    <div class="form-group row">
    <label for="total_vc" class="col-3 col-form-label">VC: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="total_vc" name="total_vc" >
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
</div>
</form> 
<!--end::Modal-->