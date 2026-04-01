<script>
$(document).ready(function() {
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
                $categoriaSelect.append('<option value="' + categoria.id + '">' + categoria.nombre + '</option>');
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
	<h5 class="modal-title" id="exampleModalLabel">Nuevo Síntoma</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<?php //echo form_open(base_url() . 'teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form action="<?php echo base_url().'manager/esintoma_create'; ?>" method="post" class="form-horizontal" >
<div class="modal-body">
    <div class="form-group row">
    	<label for="nombre" class="col-3 col-form-label">Síntoma: </label>
    	<div class="col-9">
            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Síntoma" autofocus="autofocus" required >
        </div>
    </div>
    <div class="form-group row">
        <label for="nombre" class="col-3 col-form-label">Categoria: </label>
        <div class="col-9">
            <select class="custom-select" name="categoria" id="categoria" required >
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