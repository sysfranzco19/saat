<script type="text/javascript">
    function guardar()
    {
    <?php
        for ($v = 1; $v <= 10; $v++):
    ?>
        var radiosAuto<?php echo $v; ?> = document.form_self['chk_auto_<?php echo $v; ?>'];
        var seleccionado<?php echo $v; ?> = false;
        for (var i = 0; i < radiosAuto<?php echo $v; ?>.length; i++) {
            if (radiosAuto<?php echo $v; ?>[i].checked) { seleccionado<?php echo $v; ?> = true; break; }
        }
        if (!seleccionado<?php echo $v; ?>) {
            alert("Autoevaluación Incompleta: responde la pregunta <?php echo $v; ?>.");
            return false;
        }
    <?php endfor; ?>
        var txt = document.form_self['descripcion'];
        if (!txt || txt.value.trim() === "") {
            alert("Escribe las razones por las cuales mereces esta calificación.");
            if (txt) document.getElementById('descripcion').focus();
            return false;
        }
        document.form_self.submit();
    }
    function SumarAutomatico()
    {
        var total = 0;
        for (var i = 1; i <= 10; i++) {
            var radios = document.form_self['chk_auto_' + i];
            for (var j = 0; j < radios.length; j++) {
                if (radios[j].checked) { total += parseFloat(radios[j].value); break; }
            }
        }
        var decimal = (total * 0.5).toFixed(1);
        document.getElementById('TotalAuto').value = decimal;
        document.getElementById('autoevaluacion').value = Math.round(total * 0.5);
    }
</script>
<?php
    $reg = false;
    $autos_vals = [];
    for ($i = 1; $i <= 10; $i++) $autos_vals[$i] = 0;
    $total_display = '0.0';
    $descripcion = '';
    if (count($autos) >= 1) {
        $reg = true;
        $a = $autos[0];
        $sum = 0;
        for ($i = 1; $i <= 10; $i++) {
            $autos_vals[$i] = intval($a['auto' . $i]);
            $sum += $autos_vals[$i];
        }
        $total_display = number_format($sum * 0.5, 1);
        $descripcion = $a['descripcion'] ?? '';
    }
    $d = $reg ? "disabled" : "";
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
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <h3 class="card-title font-weight-bolder text-dark">
                    <span class="card-icon"><i class="flaticon-clipboard-1 text-primary"></i></span>
                    AUTOEVALUACIÓN <span class="text-muted font-weight-normal">(Secundaria)</span>
                </h3>
                <div class="card-toolbar">
                    <span class="label label-xl label-light-primary label-inline font-weight-bold mr-2">
                        <?php echo esc($phase_name); ?>
                    </span>
                </div>
            </div>
            <form action="<?php echo base_url('student/self_appraisal_save'); ?>" method="post" class="form" name="form_self">
            <input type="hidden" name="autoevaluacion" id="autoevaluacion" value="<?php echo $reg ? $autos[0]['autoevaluacion'] : 0; ?>">
            <div class="card-body pt-0">
                <div class="alert alert-custom alert-light-primary alert-dismissible mb-8" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        Lee con mucha atención cada criterio, analiza, reflexiona y evalúate honestamente.
                        <strong>Estudiante: <?php echo esc($student); ?></strong>
                    </div>
                </div>
                <?php if ($reg): ?>
                <div class="alert alert-custom alert-warning mb-6" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning-2"></i></div>
                    <div class="alert-text font-weight-bold">Tu autoevaluación ya está registrada y no puede modificarse.</div>
                </div>
                <?php endif; ?>
                <div class="separator separator-dashed separator-border-2 mb-6"></div>
                <div class="d-flex align-items-center mb-5">
                    <div class="symbol symbol-40 symbol-light-success mr-4">
                        <span class="symbol-label font-weight-bolder font-size-h4">A</span>
                    </div>
                    <div>
                        <h4 class="font-weight-bolder text-dark mb-0">AUTOEVALUACIÓN</h4>
                        <span class="text-muted font-size-sm">Preguntas 1 – 10 · cada respuesta vale 0.5 pts.</span>
                    </div>
                </div>
                <?php foreach ($questions as $n => $q):
                    $val = $autos_vals[$n];
                ?>
                <div class="form-group row align-items-center py-3 border-bottom">
                    <label class="col-xl-9 col-lg-8 col-form-label font-weight-bold text-dark">
                        <span class="label label-sm label-light-success font-weight-bold mr-2"><?php echo $n; ?></span>
                        <?php echo esc($q); ?>
                    </label>
                    <div class="col-xl-3 col-lg-4">
                        <div class="radio-inline">
                            <label class="radio radio-success">
                                <input type="radio" name="chk_auto_<?php echo $n; ?>" value="1"
                                    onchange="SumarAutomatico();"
                                    <?php echo $d; ?>
                                    <?php if ($reg && $val == 1) echo "checked"; ?>>
                                <span></span> Sí
                            </label>
                            <label class="radio radio-danger ml-4">
                                <input type="radio" name="chk_auto_<?php echo $n; ?>" value="0"
                                    onchange="SumarAutomatico();"
                                    <?php echo $d; ?>
                                    <?php if ($reg && $val == 0) echo "checked"; ?>>
                                <span></span> No
                            </label>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="separator separator-dashed separator-border-2 mt-8 mb-6"></div>
                <div class="form-group row align-items-center mb-6">
                    <label class="col-xl-3 col-lg-3 col-form-label font-weight-bolder text-dark text-right font-size-h5">
                        Total Autoevaluación:
                    </label>
                    <div class="col-xl-3 col-lg-4">
                        <div class="input-group">
                            <input class="form-control form-control-solid font-weight-bolder font-size-h5 text-center"
                                id="TotalAuto" name="TotalAuto" type="text"
                                value="<?php echo $total_display; ?>" readonly>
                            <div class="input-group-append">
                                <span class="input-group-text font-weight-bold">/ 5 pts.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-xl-3 col-lg-3 col-form-label font-weight-bold text-dark text-right">
                        Justificación de la autoevaluación:
                    </label>
                    <div class="col-xl-9 col-lg-9">
                        <textarea class="form-control form-control-solid" rows="4"
                            name="descripcion" id="descripcion"
                            placeholder="Escribe las razones por las cuales mereces esta calificación."
                            <?php echo $d; ?>><?php echo esc($descripcion); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end py-6 px-9">
                <a href="<?php echo base_url('student/dashboard'); ?>" class="btn btn-light font-weight-bold mr-3">
                    Cancelar
                </a>
                <?php if (!$reg): ?>
                <button type="button" class="btn btn-primary font-weight-bold px-9" onclick="guardar()">
                    <i class="flaticon2-check-mark"></i> Registrar Autoevaluación
                </button>
                <?php endif; ?>
            </div>
            </form>
        </div>
    </div>
</div>
