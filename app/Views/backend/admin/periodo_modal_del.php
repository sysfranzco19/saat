<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Eliminar Periodo</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>index.php/admin/periodo_delete" method="post" class="form-horizontal">
    <div class="modal-body">
        <input type="hidden" name="periodo_id" id="periodo_id_del">
        <div class="text-center">
            <h4 class="mb-5">¿Realmente desea eliminar este periodo?</h4>
            <p id="periodo_name_del" class="font-weight-bold text-danger"></p>
        </div>
        <p class="text-danger">Esta acción no se puede deshacer.</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger font-weight-bold">Eliminar</button>
    </div>
</form>
<!--end::Modal-->

<script>
    $(document).ready(function() {
        var id = "<?php echo $param1; ?>";
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/admin/periodo_get/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#periodo_id_del').val(data.periodo_id);
                $('#periodo_name_del').text(data.periodo);
            }
        });
    });
</script>
