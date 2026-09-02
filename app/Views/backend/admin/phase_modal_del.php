<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Eliminar Trimestre</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>admin/phase_delete" method="post" class="form-horizontal">
    <div class="modal-body">
        <input type="hidden" name="phase_id" id="phase_id_del">
        <div class="text-center">
            <h4 class="mb-5">¿Realmente desea eliminar este trimestre?</h4>
            <p id="phase_name_del" class="font-weight-bold text-danger"></p>
        </div>
        <p class="text-danger">Esta acción no se puede deshacer y también se eliminará en la base de datos espejo (tiquisaat26).</p>
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
            url: "<?php echo base_url(); ?>admin/phase_get/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#phase_id_del').val(data.phase_id);
                $('#phase_name_del').text(data.name);
            }
        });
    });
</script>
