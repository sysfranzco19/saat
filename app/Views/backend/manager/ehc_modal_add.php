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
        url: '<?php echo base_url(); ?>manager/esintoma_all',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var $sintomaSelect = $('#sintoma');
            $sintomaSelect.empty(); // Limpia el select
            // Añade una opción por defecto
            $sintomaSelect.append('<option value=""> ------ </option>');
            // Recorre los datos y añade opciones al select
            $.each(response.data, function(index, sintoma) {
                $sintomaSelect.append('<option value="' + sintoma.id + '">' + sintoma.nombre + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error);
        }
    });
    $.ajax({
        url: '<?php echo base_url(); ?>manager/emedicamento_all',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var $medicamentoSelect = $('#medicamento');
            $medicamentoSelect.empty(); // Limpia el select
            // Añade una opción por defecto
            $medicamentoSelect.append('<option value=""> ------ </option>');
            // Recorre los datos y añade opciones al select
            $.each(response.data, function(index, medicamento) {
                $medicamentoSelect.append('<option value="' + medicamento.id + '">' + medicamento.nombre + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error);
        }
    });
        // Obtener la fecha y hora actual
        let now = new Date();
    
    // Formatear la fecha como YYYY-MM-DD
    let year = now.getFullYear();
    let month = (now.getMonth() + 1).toString().padStart(2, '0');
    let day = now.getDate().toString().padStart(2, '0');
    let formattedDate = `${year}-${month}-${day}`;
    
    // Formatear la hora como HH:MM
    let hours = now.getHours().toString().padStart(2, '0');
    let minutes = now.getMinutes().toString().padStart(2, '0');
    let formattedTime = `${hours}:${minutes}`;
    
    // Calcular la hora de salida (5 minutos después)
    now.setMinutes(now.getMinutes() + 5);
    let exitHours = now.getHours().toString().padStart(2, '0');
    let exitMinutes = now.getMinutes().toString().padStart(2, '0');
    let formattedExitTime = `${exitHours}:${exitMinutes}`;
    
    // Asignar los valores a los campos del formulario
    document.getElementById('fecha').value = formattedDate;
    document.getElementById('hora_ingreso').value = formattedTime;
    document.getElementById('hora_salida').value = formattedExitTime;
});

</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Ingreso de Datos</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<?php //echo form_open(base_url() . 'teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form action="<?php echo base_url().'manager/ehc_create'; ?>" method="post" class="form-horizontal" >
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
    	<label for="fecha" class="col-3 col-form-label">Fecha: </label>
    	<div class="col-9">
            <input type="date" class="form-control" value="" id="fecha" name="fecha" required readonly >
        </div>
    </div>
    <div class="form-group row">
    	<label for="hora_ingreso" class="col-3 col-form-label">Hora de ingreso: </label>
    	<div class="col-9">
            <input type="time" class="form-control" value="" id="hora_ingreso" name="hora_ingreso" required >
        </div>
    </div>
    <div class="form-group row">
    	<label for="hora_salida" class="col-3 col-form-label">Hora de salida: </label>
    	<div class="col-9">
            <input type="time" class="form-control" value="" id="hora_salida" name="hora_salida" required >
        </div>
    </div>
    <div class="form-group row">
        <label for="sintoma" class="col-3 col-form-label">Síntoma: </label>
        <div class="col-9">
            <select class="custom-select" name="sintoma" id="sintoma" required >
                <option value=""> ------ </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="medicamento" class="col-3 col-form-label">Medicamento: </label>
        <div class="col-9">
            <select class="custom-select" name="medicamento" id="medicamento" required >
                <option value=""> ------ </option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
</div>
</form> 
<!--end::Modal-->