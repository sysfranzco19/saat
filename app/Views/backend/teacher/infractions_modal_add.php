<script type="text/javascript">
    function getStudent()
    {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
			url: "<?php echo base_url(); ?>index.php/server/student_get/<?php echo $param2; ?>",
			success: function(response)
			{
				document.getElementById('student').value = response;
			}
		});
    }
    getStudent();
    function getSubjects() {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
			url: "<?php echo base_url(); ?>index.php/server/fill_subjects/<?php echo $param1; ?>",
			success: function(response)
			{
				document.getElementById('subject_id').innerHTML = response;
			}
		});
        
    }
    getSubjects();
    function getCriteria() {
        
        $.ajax({
			url: "<?php echo base_url(); ?>index.php/server/fill_criteria/1",
			success: function(response)
			{
				document.getElementById('criteria').innerHTML = response;
			}
		});
        document.getElementById('criteria').focus();
    }
    getCriteria();

    $( document ).ready(function() {
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
        $("#fecha").val(today);
    });
</script> 
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Nueva Falta Leve Nro.: <?php echo $param3+1;?></h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<?php //echo form_open(base_url() . 'index.php/teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form action="<?php echo base_url().'teacher/infraction_save'; ?>" method="post" class="form-horizontal" >
<div class="modal-body">
    <div class="form-group row">
    	<label for="student" class="col-3 col-form-label">Estudiante: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="text" class="form-control" id="student" name="student" placeholder="Estudiante" autofocus="autofocus" disabled>
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $param2; ?>" >
            <input type="hidden" id="section_id" name="section_id" value="<?php echo $param1; ?>" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Fecha de Registro: <span class="text-danger">*</span></label>
        <div class="col-9">
            <input type="date" class="form-control" value="" id="fecha" name="fecha" required >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Selecciona la Materia: <span class="text-danger">*</span></label>
        <div class="col-9">
            <select class="form-control" id="subject_id" name="subject_id" required >
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Selecciona un Criterio: <span class="text-danger">*</span></label>
        <div class="col-9">
            <select class="form-control" id="criteria" name="criteria" required>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-3 col-form-label">Detalle:</label>
        <div class="col-9">
            <input type="text" id="detail" name="detail" class="form-control" value="Ninguna">
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-3 col-form-label"></label>
        <div class="col-9">
        <span class="text-danger">*Datos Obligatorios</span>
        </div>
    </div>
    <?php 
    if ($param3==2) {
    ?>
    <div class="form-group row">
        <label for="" class="col-3 col-form-label"></label>
        <div class="col-9">
        <span class="text-warning">**El estudiante ya tiene 3 faltas leves, se notificará a los Padres de Familia y al Director</span>
        </div>
    </div>
    <?php
    }elseif ($param3==3) {
        ?>
        <div class="form-group row">
            <label for="" class="col-3 col-form-label"></label>
            <div class="col-9">
            <span class="text-danger">**El estudiante ya tiene 4 faltas leves, se invitara a una entrevista a los Padres de Familia y al Director</span>
            </div>
        </div>
        <?php
        }
        ?>

    
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
</div>
</form> 
<!--end::Modal-->