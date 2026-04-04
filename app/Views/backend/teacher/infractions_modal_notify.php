<script type="text/javascript">

    function fillDatos(){
        var parametros = {
			"student_id" : "<?php echo $param2; ?>",
		};
        var emailPadres ="";
        $.ajax({
			data: parametros,
			url: "<?php echo base_url('index.php/server/student_fill'); ?>",
			type: "post",
			success: function(response){
                //alert('hola');
                var content = JSON.parse(response);
                    if (content[0].email2=="") {
                        emailPadres = content[0].email1;
                    }else{
                        emailPadres = content[0].email1+', '+content[0].email2;
                    }
                    document.getElementById('emailPadres').value = emailPadres;

            },
		});
    }
    fillDatos();
    function getStudent()
    {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
			url: "<?php echo base_url(); ?>index.php/server/fill_infraction_emails/<?php echo $param2; ?>",
			success: function(response)
			{
                var content = JSON.parse(response);
                var eDocentes = "";
                var mensaje = "Estimados Padres de Familia, mediante la presente informar que su hij@ " + content[0].student + " ha cometido 4 faltas, según el siguiente detalle:\n\n";
                const emails = [];
                for(var i = 0;i < content.length; i++)
                {
                    emails[i]= content[i].emailDocente;
                    mensaje = mensaje + content[i].materia +' - '+ content[i].docente +' - '+ content[i].criteria + '\n';
                    //mensaje = mensaje + content[i].criteria + '\n';
                    eDocentes = eDocentes + content[i].emailDocente + ", ";
                }
                mensaje = mensaje + '\nPor lo que solicitamos que se apersone por el Colegio para firmar este comunicado, con el fin de mejorar su situación de su hij@ (' + content[0].student + ') y evitar sanciones posteriores de acuerdo al reglamento vigente\n';
                mensaje = mensaje + 'Atentamente,\n\n Docentes y Dirección Técnica de Nivel';
                document.getElementById('mensaje').innerHTML = mensaje;
                document.getElementById('eDocentes').innerHTML = eDocentes;

			}
		});
    }
    getStudent();
</script> 
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Notificar carta y notificar a los Padres</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<form action="<?php echo base_url().'teacher/infraction_notify'; ?>" method="post" class="form-horizontal" id="formEdit" >
<div class="modal-body">
    <div class="form-group row">
        <label class="col-2 col-form-label">Padres: <span class="text-danger">*</span></label>
        <div class="col-10">
            <input type="text" id="emailPadres" name="emailPadres" class="form-control" >
            <input type="hidden" id="student_id" name="student_id" value="<?php echo $param2; ?>" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-2 col-form-label">Docentes: <span class="text-danger">*</span></label>
        <div class="col-10">
            <textarea class="form-control" rows="2" id="eDocentes" name="eDocentes" ></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-2 col-form-label">Mensaje:</label>
        <div class="col-10">
            <textarea class="form-control" rows="12" id="mensaje" name="mensaje"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-2 col-form-label"></label>
        <div class="col-10">
            <span class="text-dark">Se procedera a generar la Carta y se notificara a todos los involucrados que procedan a firmar el compromiso</span><br />
            <span class="text-danger">*Datos Obligatorios</span>
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-warning font-weight-bold">Notificar y Descargar Carta</button>
</div>
</form> 
<!--end::Modal-->