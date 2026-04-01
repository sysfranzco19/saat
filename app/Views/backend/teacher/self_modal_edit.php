<script type="text/javascript">
    //Solo permite introducir numeros.
    function validarnum(e) {
        tecla = e.which || e.keyCode;
        patron = /\d/; // Solo acepta números
        te = String.fromCharCode(tecla);
        return (patron.test(te) || tecla == 9 || tecla == 8);
    }
    //Suma la autoevaluacion del Ser y el Decidir

    //Valida los datos antes de enviarlos
    function valida() {
        //Parametros
        var oky = true;
        var msg = "";

        var formulario = document.getElementById("form_auto");
        var ser = document.getElementById("auto_ser2").value;
        var decidir = document.getElementById("auto_decidir2").value;
        //Verificamos que las autoevaluaciones sean validas
        if (ser>5 || ser<1){
            msg="Verifique que la autoevaluación del SER cumpla con la nota mínima (1) y máxima (5).";
            var obj = document.getElementById("auto_ser2");
            oky=false;
        };
        if (decidir>5 || decidir<1){
            msg="Verifique que la autoevaluación del DECIDIR cumpla con la nota mínima (1) y máxima (5).";
            var obj = document.getElementById("auto_decidir2");
            oky=false;
        };
 
        if (oky==true){
            formulario.submit();
            return true;
        } else {
            obj.style.backgroundColor = '#F78094';
            obj.focus();
            alert(msg);
            return false;
        };
        
    }

</script>
<?php

?>
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><?php echo $system_title; ?> - Autoevaluación de: <?php echo $param2; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url(); ?>teacher/autoeval/<?php echo $param1; ?>/<?php echo $param3; ?>" class='form-horizontal form-groups-bordered validate' target='_top' name='form_auto' onsubmit='return valida();' method="post">
<div class="modal-body">
            <table class="table">
                <thead class="thead-inverse">
                    <tr>
                        <th>DIMENSIÓN</th>
                        <th>NOTA</th>
                        <input type="hidden" id="student_id" name="student_id" value="<?php echo $param1; ?>" >
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Autoevaluación SER</th>
                        <td>
                               <input type="text" class="form-control" id="auto_ser2" name="auto_ser2" value="" onkeypress="return validarnum(event)" onChange="suma()" autofocus >
                        </td>

                    </tr>
                    <tr>
                        <th>Autoevaluación DECIDIR</th>
                        <td>
                            <input type="text" class="form-control" id="auto_decidir2" name="auto_decidir2" value="" onkeypress="return validarnum(event)" onChange="suma()" >
                        </td>
                    </tr>
                </tbody>
            </table>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
</div>
</form> 