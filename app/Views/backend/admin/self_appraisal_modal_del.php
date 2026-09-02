<?php
use App\Models\SelfappraisalModel;

$self_id  = (int) $param1;
$phase_id = (int) $param2;

$SelfMod = new SelfappraisalModel();
$existe = $SelfMod->get_self_appraisal(['self_id' => $self_id]);
$row = $existe[0] ?? null;

$nombre = '';
if ($row) {
    $StudentMod = new \App\Models\StudentModel();
    $students = $StudentMod->datosStudent($row['student_id']);
    $nombre = $students[0]->nombre ?? '';
}
?>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Eliminar Autoevaluación</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>admin/self_appraisal_delete" method="post" class="form-horizontal">
    <div class="modal-body">
        <input type="hidden" name="self_id" value="<?php echo $self_id; ?>">
        <input type="hidden" name="phase_id" value="<?php echo $phase_id; ?>">
        <div class="text-center">
            <h4 class="mb-5">¿Realmente desea eliminar esta autoevaluación?</h4>
            <p class="font-weight-bold text-danger"><?php echo htmlspecialchars($nombre); ?></p>
        </div>
        <p class="text-danger">Esta acción no se puede deshacer.</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger font-weight-bold">Eliminar</button>
    </div>
</form>
<!--end::Modal-->
