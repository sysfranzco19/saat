<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Modificar Síntoma</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'manager/esintoma_update'; ?>" method="post" class="form" >
<div class="modal-body">
    <div class="form-group row">
    	<label for="nombre" class="col-3 col-form-label">Síntoma: </label>
    	<div class="col-9">
            <input type="hidden" name="id" id="id" value="<?php echo $param1; ?>" >
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Síntoma" required >
        </div>
    </div>
	<div class="form-group row">
        <label for="nombre" class="col-3 col-form-label">Categoria de Síntoma: </label>
        <div class="col-9">
            <select class="custom-select" name="categoria" id="categoria" required >
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
        var sintomaId = '<?php echo $param1; ?>';

        // Obtener los detalles del síntoma
        $.ajax({
            url: '<?php echo base_url(); ?>manager/esintoma_get/' + sintomaId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#nombre').val(response.nombre);
                var selectedCategoriaId = response.categoria_id;

                // Obtener todas las categorías
                $.ajax({
                    url: '<?php echo base_url(); ?>manager/ecategoria_all',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        var $categoriaSelect = $('#categoria');
                        $categoriaSelect.empty(); // Limpia el select

                        // Añade una opción por defecto
                        $categoriaSelect.append('<option value=""> ------ </option>');

                        // Recorre los datos y añade opciones al select
                        $.each(response.data, function(index, categoria) {
                            var option = $('<option>', {
                                value: categoria.id,
                                text: categoria.nombre
                            });

                            // Establecer la opción seleccionada
                            if (categoria.id == selectedCategoriaId) {
                                option.attr('selected', 'selected');
                            }

                            $categoriaSelect.append(option);
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