<script type="text/javascript">
    var subject_id = 0;
    var type_foul = 0;
    var criterio_id = 0;
    function getInfraction()
    {
        var parametros = {
			"infraction_id" : "<?php echo $param3; ?>",
		};
        $.ajax({
            data: parametros,
			url: "<?php echo base_url(); ?>server/fill_infraction",
            type: "post",
			success: function(response)
			{
                var content = JSON.parse(response);
                subject_id = content[0].subject_id;
                getSubjects();
                type_foul = content[0].type_foul_id;
                getTypeFoul();
                getCriteria(type_foul);
                criterio_id = content[0].criterio_id;
                document.getElementById('detail').value = content[0].detail;
                document.getElementById('student').value = content[0].student;
                document.getElementById('fecha').value = content[0].date;
			}
		});
    }
    getInfraction();
    function getSubjects() {
        $.ajax({
			url: "<?php echo base_url(); ?>server/fill_subjects/<?php echo $param1; ?>",
			success: function(response)
			{
                var newstr = response.replace("value='"+subject_id+"'", "value='"+subject_id+"' selected");
				document.getElementById('subject_id').innerHTML = newstr;
			}
		});
        
    }
    function getTypeFoul()
    {
        $.ajax({
			url: "<?php echo base_url(); ?>server/fill_type_foul",
			success: function(response)
			{
                var newstr = response.replace("value='"+type_foul+"'", "value='"+type_foul+"' selected");
				document.getElementById('type_foul').innerHTML = newstr;
			}
		});
        
    }
    function getCriteria(type_foul_id) {
            $.ajax({
                url: "<?php echo base_url(); ?>server/fill_criteria/"+ type_foul_id,
                success: function(response)
                {
                    var newstr = response.replace("value='"+criterio_id+"'", "value='"+criterio_id+"' selected");
                    document.getElementById('criteria').innerHTML = newstr;
                }
            });    
    }
</script> 
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Esta seguro de Eliminar la siguiente Falta?</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'teacher/infraction_deleted'; ?>" method="post" class="form-horizontal" id="formEdit" >
<div class="modal-body">
    <div class="form-group row">
    	<label for="student" class="col-3 col-form-label">Estudiante: <span class="text-danger">*</span></label>
    	<div class="col-9">
            <input type="text" class="form-control" id="student" name="student" placeholder="Estudiante"  disabled>
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $param2; ?>" >
            <input type="hidden" id="section_id" name="section_id" value="<?php echo $param1; ?>" >
            <input type="hidden" id="infraction_id" name="infraction_id" value="<?php echo $param3; ?>" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Fecha Solicitud: <span class="text-danger">*</span></label>
        <div class="col-9">
            <input type="date" class="form-control" value="" id="fecha" name="fecha" disabled >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Selecciona la Meteria: <span class="text-danger">*</span></label>
        <div class="col-9">
            <select class="form-control" id="subject_id" name="subject_id" disabled >
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Tipo de Falta: <span class="text-danger">*</span></label>
        <div class="col-9">
            <select class="form-control" id="type_foul" name="type_foul" onChange="getCriteria(this.value);" disabled>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-3 col-form-label">Selecciona un Criterio: <span class="text-danger">*</span></label>
        <div class="col-9">
            <select class="form-control" id="criteria" name="criteria" disabled>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-3 col-form-label">Detalle:</label>
        <div class="col-9">
            <input type="text" id="detail" name="detail" class="form-control" disabled>
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
	<button type="submit" class="btn btn-danger font-weight-bold">Eliminar</button>
</div>
</form> 
<!--end::Modal-->