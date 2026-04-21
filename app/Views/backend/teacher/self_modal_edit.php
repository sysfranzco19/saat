<script type="text/javascript">
    function validarnum(e) {
        tecla = e.which || e.keyCode;
        patron = /\d/;
        te = String.fromCharCode(tecla);
        return (patron.test(te) || tecla == 9 || tecla == 8);
    }

    function valida() {
        var oky = true;
        var msg = "";
        var formulario = document.getElementById("form_auto");
        var nota = parseInt(document.getElementById("auto_nota").value);

        if (isNaN(nota) || nota < 1 || nota > 100) {
            msg = "Ingrese una nota válida entre 1 y 100.";
            var obj = document.getElementById("auto_nota");
            oky = false;
        }

        if (oky) {
            formulario.submit();
            return true;
        } else {
            obj.style.backgroundColor = '#F78094';
            obj.focus();
            alert(msg);
            return false;
        }
    }
</script>

<?php
// Obtener el valor actual de autoevaluacion si ya existe
$self = new \App\Models\SelfappraisalModel();
$dataExiste = ['student_id' => $param1, 'phase_id' => $phase_id];
$registroActual = $self->get_self_appraisal($dataExiste);
$valorActual = (!empty($registroActual)) ? $registroActual[0]['autoevaluacion'] : '';
?>

<div class="modal-header">
    <h5 class="modal-title"><?php echo $system_title; ?> — Autoevaluación de: <?php echo urldecode($param2); ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>

<form action="<?php echo base_url(); ?>teacher/autoeval/<?php echo $param1; ?>/<?php echo $param3; ?>"
      class="form-horizontal validate"
      id="form_auto"
      target="_top"
      onsubmit="return valida();"
      method="post">

    <input type="hidden" name="student_id" value="<?php echo $param1; ?>">

    <div class="modal-body">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>Campo</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="align-middle">Autoevaluación</th>
                    <td>
                        <input type="number"
                               class="form-control"
                               id="auto_nota"
                               name="auto_ser2"
                               value="<?php echo $valorActual; ?>"
                               min="1" max="5"
                               onkeypress="return validarnum(event)"
                               autofocus
                               placeholder="1 - 5">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">
            <?php echo (!empty($registroActual)) ? 'Actualizar' : 'Guardar'; ?>
        </button>
    </div>
</form>
