<script type="text/javascript">
    function getNivel() {
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/admin/nivel_get/<?php echo $param1; ?>",
            success: function(response) {
                if (response) {
                    $('#nivel_name').text(response.nivel);
                }
            }
        });
    }
    getNivel();
</script>

<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title">¿Realmente desea eliminar este Nivel?</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url().'/admin/nivel_delete'; ?>" method="post" class="form-horizontal">
<div class="modal-body">
    <input type="hidden" name="id" value="<?php echo $param1; ?>">
    <div class="form-group row">
        <label class="col-3 col-form-label">Nivel:</label>
        <div class="col-9">
            <p class="form-control-plaintext" id="nivel_name">Cargando...</p>
        </div>
    </div>
    <p class="text-danger">Esta acción no se puede deshacer.</p>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-danger font-weight-bold">Eliminar</button>
</div>
</form>
<!--end::Modal-->
