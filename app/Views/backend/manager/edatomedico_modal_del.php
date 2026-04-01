<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Eliminar Dato Médico</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'manager/edatomedico_delete'; ?>" method="post" class="form" >
<div class="modal-body">
    <div class="form-group row">
        <label for="student" class="col-3 col-form-label">Estudiante: </label>
        <div class="col-9">
            <select class="custom-select" name="student" id="student" disabled >
                <option value=""> ------ </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="tipo" class="col-3 col-form-label">Tipo: </label>
        <div class="col-9">
            <select class="custom-select" name="tipo" id="tipo" disabled >
                <option value=""> ------ </option>
            </select>
        </div>
    </div>
    <div class="form-group row">
    	<label for="descripcion" class="col-3 col-form-label">Dato Médico: </label>
    	<div class="col-9">
            <input type="hidden" name="id" id="id" value="<?php echo $param1; ?>" >
            <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Dato Médico" disabled >
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-danger font-weight-bold">Eliminar</button>
</div>
</form> 
<!--end::Modal-->

<script type="text/javascript">
    $(document).ready(function() {
        var datomedicoId = '<?php echo $param1; ?>';

        // Obtener los detalles del Dato Médico
        $.ajax({
            url: '<?php echo base_url(); ?>manager/edatomedico_get/' + datomedicoId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#nombre').val(response.nombre);
                var selectedtipoId = response.tipo_id;

                // Obtener todas las categorías
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
                            var option = $('<option>', {
                                value: tipo.id,
                                text: tipo.nombre
                            });

                            // Establecer la opción seleccionada
                            if (tipo.id == selectedtipoId) {
                                option.attr('selected', 'selected');
                            }

                            $tipoSelect.append(option);
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
                console.error('Error en la solicitud para obtener el Dato Médico:', error);
            }
        });
    });
</script>