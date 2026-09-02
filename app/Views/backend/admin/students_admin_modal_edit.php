<?php
use App\Models\StudentModel;
use App\Models\SectionModel;
use App\Models\FamilyModel;
use App\Models\PlaceModel;

$student_id = (int) $param1;

$StudentMod = new StudentModel();
$existe = $StudentMod->get_student(['student_id' => $student_id]);
$s = $existe[0] ?? [];

$SectionMod = new SectionModel();
$secciones = $SectionMod->listar_section();

$FamilyMod = new FamilyModel();
$familias = $FamilyMod->activesFamily();

$PlaceMod = new PlaceModel();
$lugares = $PlaceMod->get_places();
?>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        Editar Estudiante: <?php echo htmlspecialchars(($s['lastname'] ?? '') . ' ' . ($s['lastname2'] ?? '') . ' ' . ($s['name'] ?? '')); ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>admin/students_update" method="post">
    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
    <div class="modal-body">
        <?php include 'students_admin_form_fields.php'; ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
    </div>
</form>
<!--end::Modal-->
