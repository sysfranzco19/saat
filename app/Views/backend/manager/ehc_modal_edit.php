<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Modificar Registros Enfermeria</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'manager/ehc_update'; ?>" method="post" class="form" >
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
            <input type="hidden" name="id" id="id" value="<?php echo $param1; ?>" >
            <input type="date" class="form-control" value="" id="fecha" name="fecha" required >
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
	<button type="submit" class="btn btn-warning font-weight-bold">Guardar Cambios</button>
</div>
</form> 
<!--end::Modal-->

<script type="text/javascript">
    $(document).ready(function() {
        var hcId = '<?php echo $param1; ?>';

        // Obtener los detalles del Registro
        $.ajax({
            url: '<?php echo base_url(); ?>manager/ehc_get/' + hcId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#fecha').val(response.fecha);
                $('#hora_ingreso').val(response.hora_ingreso);
                $('#hora_salida').val(response.hora_salida);
                var selectedSintomaId = response.sintoma_id;
                // Obtener sintoma
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
                            var option = $('<option>', {
                                value: sintoma.id,
                                text: sintoma.nombre
                            });
                            // Establecer la opción seleccionada
                            if (sintoma.id == selectedSintomaId) {
                                option.attr('selected', 'selected');
                            }

                            $sintomaSelect.append(option);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la solicitud para obtener categorías:', error);
                    }
                });
                var selectedMedicamentoId = response.medicamento_id;
                // Obtener medicamento
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
                            var option = $('<option>', {
                                value: medicamento.id,
                                text: medicamento.nombre
                            });

                            // Establecer la opción seleccionada
                            if (medicamento.id == selectedMedicamentoId) {
                                option.attr('selected', 'selected');
                            }

                            $medicamentoSelect.append(option);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la solicitud para obtener categorías:', error);
                    }
                });
                var selectedStudentId = response.student_id;
                // Obtener Estudiante
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
                            var option = $('<option>', {
                                value: student.student_id,
                                text: student.nombre
                            });

                            // Establecer la opción seleccionada
                            if (student.student_id == selectedStudentId) {
                                option.attr('selected', 'selected');
                            }

                            $studentSelect.append(option);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la solicitud para obtener categorías:', error);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud para obtener el síntoma:', error);
            }
        });
    });
</script>