<?php
use App\Models\StudentModel;

$student_id = (int) $param1;

$StudentMod = new StudentModel();
$existe = $StudentMod->get_student(['student_id' => $student_id]);
$s = $existe[0] ?? [];
$nombre = trim(($s['lastname'] ?? '') . ' ' . ($s['lastname2'] ?? '') . ' ' . ($s['name'] ?? ''));
?>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Eliminar Estudiante</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>admin/students_delete" method="post" class="form-horizontal">
    <div class="modal-body">
        <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
        <div class="text-center">
            <h4 class="mb-5">¿Realmente desea eliminar a este estudiante?</h4>
            <p class="font-weight-bold text-danger"><?php echo htmlspecialchars($nombre); ?></p>
        </div>
        <p class="text-danger">Esta acción no se puede deshacer y eliminará también sus datos en las bases replicadas (tiquipaya, asistencia).</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger font-weight-bold">Eliminar</button>
    </div>
</form>
<!--end::Modal-->
