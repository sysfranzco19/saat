<?php
use App\Models\SectionModel;
use App\Models\FamilyModel;
use App\Models\PlaceModel;

$SectionMod = new SectionModel();
$secciones = $SectionMod->listar_section();

$FamilyMod = new FamilyModel();
$familias = $FamilyMod->activesFamily();

$PlaceMod = new PlaceModel();
$lugares = $PlaceMod->get_places();

$s = []; // sin datos previos
?>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Nuevo Estudiante</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>admin/students_create" method="post">
    <div class="modal-body">
        <?php include 'students_admin_form_fields.php'; ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
    </div>
</form>
<!--end::Modal-->
