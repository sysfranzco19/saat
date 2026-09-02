<?php
use App\Models\StudentModel;
use App\Models\SelfappraisalModel;
use App\Models\PhaseModel;

$student_id = (int) $param1;
$phase_id   = (int) $param2;

$StudentMod = new StudentModel();
$students = $StudentMod->datosStudent($student_id);
$student_name = $students[0]->nombre ?? '';
$student_section = $students[0]->completo ?? '';

$PhaseMod = new PhaseModel();
$phases = $PhaseMod->listar_phases();
$phase_name = '';
foreach ($phases as $ph) {
    if ($ph['phase_id'] == $phase_id) { $phase_name = $ph['name']; break; }
}

$SelfMod = new SelfappraisalModel();
$existe = $SelfMod->get_self_appraisal(['student_id' => $student_id, 'phase_id' => $phase_id]);
$reg = count($existe) > 0;
$auto_vals = [];
$descripcion = '';
for ($i = 1; $i <= 10; $i++) $auto_vals[$i] = 0;
if ($reg) {
    $a = $existe[0];
    for ($i = 1; $i <= 10; $i++) $auto_vals[$i] = (int) $a['auto' . $i];
    $descripcion = $a['descripcion'] ?? '';
}

$questions = [
    1  => "Cumplo de manera correcta las normas y reglas del colegio.",
    2  => "Trabajo en todos los espacios y momentos con respeto para una convivencia sana.",
    3  => "Practico los valores del colegio: Confiabilidad, Respeto, Responsabilidad, Justicia, Bondad, Ciudadanía.",
    4  => "Asumo con responsabilidad el cumplimiento de mis tareas y trabajos, los mismos que entrego en fecha establecida.",
    5  => "Soy ordenado y disciplinado durante mis clases con todo mi material necesario a mi alcance.",
    6  => "Practico la equidad de género respetando la dignidad y los derechos de todas las personas que me rodean.",
    7  => "Aplico conceptos y habilidades aprendidas para la solución de problemas.",
    8  => "Soy sensible a las emociones de los demás y trato de comprender sus puntos de vista.",
    9  => "Comprendo el propósito y la importancia de la lectura en mi vida diaria y en mi aprendizaje.",
    10 => "Demuestro comportamientos adecuados a las reglas establecidas en el aula.",
];
?>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><?php echo $reg ? 'Editar' : 'Registrar'; ?> Autoevaluación</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>admin/self_appraisal_save" method="post" name="form_self_admin">
    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
    <input type="hidden" name="phase_id" value="<?php echo $phase_id; ?>">
    <div class="modal-body">
        <div class="alert alert-custom alert-light-primary mb-6" role="alert">
            <div class="alert-text">
                <strong><?php echo htmlspecialchars($student_name); ?></strong>
                &mdash; <?php echo htmlspecialchars($student_section); ?>
                &mdash; <span class="font-weight-bold"><?php echo htmlspecialchars($phase_name); ?></span>
            </div>
        </div>
        <?php foreach ($questions as $n => $q): $val = $auto_vals[$n]; ?>
        <div class="form-group row align-items-center py-2 border-bottom">
            <label class="col-8 col-form-label font-weight-bold text-dark">
                <span class="label label-sm label-light-success font-weight-bold mr-2"><?php echo $n; ?></span>
                <?php echo htmlspecialchars($q); ?>
            </label>
            <div class="col-4">
                <div class="radio-inline">
                    <label class="radio radio-success">
                        <input type="radio" name="auto<?php echo $n; ?>" value="1" onchange="sumarAutoAdmin();" required <?php echo ($reg && $val == 1) ? 'checked' : ''; ?>>
                        <span></span> Sí
                    </label>
                    <label class="radio radio-danger ml-3">
                        <input type="radio" name="auto<?php echo $n; ?>" value="0" onchange="sumarAutoAdmin();" required <?php echo ($reg && $val == 0) ? 'checked' : ''; ?>>
                        <span></span> No
                    </label>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="form-group row align-items-center mt-4">
            <label class="col-3 col-form-label font-weight-bolder text-dark">Total:</label>
            <div class="col-3">
                <input type="text" class="form-control form-control-solid font-weight-bolder text-center" id="totalAutoAdmin" readonly
                    value="<?php echo number_format(array_sum($auto_vals) * 0.5, 1); ?> / 5">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-3 col-form-label">Justificación:</label>
            <div class="col-9">
                <textarea class="form-control" name="descripcion" rows="3"
                    placeholder="Razones de la calificación."><?php echo htmlspecialchars($descripcion); ?></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
    </div>
</form>
<!--end::Modal-->

<script>
    function sumarAutoAdmin() {
        var total = 0;
        for (var i = 1; i <= 10; i++) {
            var radios = document.forms['form_self_admin']['auto' + i];
            for (var j = 0; j < radios.length; j++) {
                if (radios[j].checked) { total += parseFloat(radios[j].value); break; }
            }
        }
        document.getElementById('totalAutoAdmin').value = (total * 0.5).toFixed(1) + ' / 5';
    }
    sumarAutoAdmin();
</script>
