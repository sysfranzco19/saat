

<script>
    // Una vez que el documento esté cargado
    $(document).ready(function() {
        // Selecciona el elemento y llama a la función select2()
        $('#students').select2();
    });
</script>

<script type="text/javascript">
    function getSubjects() {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        var parametros = {
			"subject_id" : "<?php echo $param1; ?>",
		};
        $.ajax({
            data: parametros,
			url: "<?php echo base_url(); ?>index.php/server/fill_section_subject",
            type: "post",
			success: function(response)
			{
				//document.getElementById('subject_id').innerHTML = response;
                //alert('Hola');
                var content = JSON.parse(response);
                document.getElementById('curso_materia').value = content[0].nick_name + ' - ' + content[0].name;
                document.getElementById('subject_id').value = content[0].subject_id;
                getStudents(content[0].section_id);
			}
		});
        
    }
    getSubjects();

    function getStudents(section_id) {
        $.ajax({
			url: "<?php echo base_url(); ?>index.php/server/fill_students/" + section_id,
            type: "get",
			success: function(response)
			{
				document.getElementById('students').innerHTML = response;
                document.getElementById("submitButton").disabled = false;
                // Selecciona el elemento <select> por su ID
                var selectElement = document.getElementById('students');
                // Remueve el atributo 'selected' de la primera opción
                selectElement.options[0].removeAttribute('selected');
			}
		});
    }

    $( document ).ready(function() {
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
        $("#fecha").val(today);
    });
    function confirmar()
    {
        //alert($("#students :selected").length);
        
        var subject_id = document.getElementById("subject_id").value;
        //alert(subject_id);
        var student = $("#students :selected").length;
        //alert(student);
        var behavior = document.getElementById("behavior").value;
        //alert(behavior);
        //alert(cod);
        
        if (subject_id == '') {
            alert('Por favor debe seleccionar la Materia');
            document.getElementById("subject").focus();
        } else if (student == 0) {
            alert('Por favor debe seleccionar un Estudiante');
            document.getElementById("students").focus();
        } else if (behavior == '') {
            alert('Por favor debe ingresar la descripción del reporte');
            document.getElementById("behavior").focus();
        } else {
            document.getElementById("submitButton").disabled = true;
            document.myForm.submit();
            getSubjects()
        }
        
    
    }
</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Nuevo Comunicado</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<?php //echo form_open(base_url() . 'index.php/teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form id="myForm" name="myForm" action="<?php echo base_url().'teacher/behavior_save'; ?>" method="post" class="form-horizontal" >
<div class="modal-body">
    <div class="form-group row">
    	<label for="" class="col-3 col-form-label">Curso / Materia: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="text" id="curso_materia" name="curso_materia" class="form-control" value="" >
            <input type="hidden" id="subject_id" name="subject_id"  >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Estudiante(s):</label>
        <div class="col-9">
            <select class="form-control select2 " id="students" name="students[]" multiple="multiple">

            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Fecha : <span class="text-danger">*</span></label>
        <div class="col-9">
            <input type="date" class="form-control" value="" id="fecha" name="fecha" required >
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-3 col-form-label">Detalle: <span class="text-danger">*</span></label>
        <div class="col-9">
            <textarea class="form-control" id="behavior" name="behavior" rows="7" spellcheck="false" data-gramm="false" required ></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-3 col-form-label"></label>
        <div class="col-9">
        <span class="text-danger">*Datos Obligatorios</span>
        </div>
    </div>

    
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold" id="submitButton" name="submitButton" onclick="confirmar()" >Guardar</button>
</div>
</form> 
<!--end::Modal-->