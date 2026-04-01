<script>
$(document).ready(function() {
    $.ajax({
        url: '<?php echo base_url(); ?>manager/student_all',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var $studentSelect = $('#student');
            $studentSelect.empty(); // Limpia el select
            // Añade una opción por defecto
            $studentSelect.append('<option value=""> ------ </option>');
            // Recorre los datos y añade opciones al select
            $.each(response.data, function(index, student) {
                $studentSelect.append('<option value="' + student.student_id + '">' + student.nombre + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error);
        }
    });
    $.ajax({
        url: '<?php echo base_url(); ?>manager/etipodatomedico_all',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var $tipoSelect = $('#tipo');
            $tipoSelect.empty(); // Limpia el select

            // Añade una opción por defecto
            $tipoSelect.append('<option value=""> ------ </option>');

            // Recorre los datos y añade opciones al select
            $.each(response.data, function(index, tipo) {
                $tipoSelect.append('<option value="' + tipo.id + '">' + tipo.nombre + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error);
        }
    });
});
</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Nuevo Dato Médico</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<?php //echo form_open(base_url() . 'teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form action="<?php echo base_url().'manager/edatomedico_create'; ?>" method="post" class="form-horizontal" >
<div class="modal-body">
    <div class="form-group row">
        <label for="student" class="col-3 col-form-label">Seleccione estudiante: </label>
        <div class="col-9">
            <select class="custom-select" name="student" id="student" required >
                <option value=""> ------ </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="tipo" class="col-3 col-form-label">Tipo: </label>
        <div class="col-9">
            <select class="custom-select" name="tipo" id="tipo" required >
                <option value=""> ------ </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
    	<label for="descripcion" class="col-3 col-form-label">Dato Médico: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Dato Médico" autofocus="autofocus" required >
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
</div>
</form> 
<!--end::Modal-->