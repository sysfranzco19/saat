<script type="text/javascript">
    function getParents() {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        var parametros = {
			"family_id" : "<?php echo $param4; ?>",
		};
        $.ajax({
            data: parametros,
			url: "<?php echo base_url(); ?>server/parents_fill",
            type: "post",
			success: function(response)
			{
                document.getElementById('parents').innerHTML = response;
			}
		});
        
    }
    function fillFecha(tipo_licencia){
        //Verificamos que eexiste un Valor en IdFamilia
        if (document.getElementById('student_id').value > 0) {
           
            /*
             document.getElementById("parentesco").disabled = false;
            document.getElementById("fechaSolicita").disabled = false;
            
            
            document.getElementById("motivo").disabled = false;
            document.getElementById("detalle").disabled = false;
            document.getElementById("medio").disabled = false;
            */
            var hoy = new Date();
            
            //var fecha = hoy.getDate() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getFullYear();
            var fecha = hoy.getFullYear() + '-' + (("0" + (hoy.getMonth() + 1)).slice(-2)) + '-' + (("0" + hoy.getDate()).slice(-2));
            //var hora = hoy.getHours() + '-' + hoy.getMinutes() + '-' + hoy.getSeconds();
            var hora = (("0" + (hoy.getHours())).slice(-2)) + ':' + (("0" + (hoy.getMinutes())).slice(-2));

            var fecha_hora = fecha + 'T' + hora;
            document.getElementById('fecha_inicio').value = fecha;
            document.getElementById('fecha_fin').value = fecha;
            document.getElementById('hora_inicio').value = hora;
            document.getElementById('hora_fin').value = hora;
            /*
            document.getElementById('fechaSolicita').value = fecha_hora;
            */
            if (tipo_licencia==2) {
                //Licencia por Horas
                document.getElementById("fecha_inicio").disabled = true;
                document.getElementById("fecha_fin").disabled = true;
                document.getElementById("hora_inicio").disabled = false;
                document.getElementById("hora_fin").disabled = false;
                div_fecha_inicio = document.getElementById('div_fecha_inicio');
                div_fecha_inicio.style.display = 'none';
                div_fecha_fin = document.getElementById('div_fecha_fin');
                div_fecha_fin.style.display = 'none';
                div_hora_inicio = document.getElementById('div_hora_inicio');
                div_hora_inicio.style.display = '';
                div_hora_fin = document.getElementById('div_hora_fin');
                div_hora_fin.style.display = '';
                //div_cantidad = document.getElementById('div_cantidad');
                //div_cantidad.style.display = 'none';
            }else{
                //Licencia por DIAS
                document.getElementById("fecha_inicio").disabled = false;
                document.getElementById("fecha_fin").disabled = false;
                document.getElementById("hora_inicio").disabled = true;
                document.getElementById("hora_fin").disabled = true;
                div_fecha_inicio = document.getElementById('div_fecha_inicio');
                div_fecha_inicio.style.display = '';
                div_fecha_fin = document.getElementById('div_fecha_fin');
                div_fecha_fin.style.display = '';
                div_hora_inicio = document.getElementById('div_hora_inicio');
                div_hora_inicio.style.display = 'none';
                div_hora_fin = document.getElementById('div_hora_fin');
                div_hora_fin.style.display = 'none';
                //div_cantidad = document.getElementById('div_cantidad');
                //div_cantidad.style.display = '';
            }
        }else{
            alert('Seleccione un Estudiante o complete los datos');
        }
        getParents();
        fillMotivos();
    }
    function fillMotivos() {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: "<?php echo base_url(); ?>server/fill_motivos",
            type: "get",
            success: function(response) {
                // Asumiendo que la respuesta es un JSON con los motivos
                let select = document.getElementById('motivo_id');
                select.innerHTML = ''; // Limpiar el select
                response.forEach(function(motivo) {
                    let option = document.createElement('option');
                    option.value = motivo.motivo_id; // Ajustar esto según el nombre de la columna en la tabla
                    option.textContent = motivo.motivo; // Ajustar esto según el nombre de la columna en la tabla
                    select.appendChild(option);
                });
            }
        });
    }
    // JavaScript para actualizar el campo oculto con el texto del select
    function updateParentText() {
        var select = document.getElementById('parents');
        var selectedOption = select.options[select.selectedIndex];
        document.getElementById('parent_text').value = selectedOption.text;
    }

    // Llama a updateParentText al cargar la página si hay una opción seleccionada
    document.addEventListener('DOMContentLoaded', function() {
        updateParentText();
    });
</script>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Licencia: <?php echo $param2;?></h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<?php //echo form_open(base_url() . 'teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form action="<?php echo base_url().'parents/license_save'; ?>" method="post" class="form-horizontal" enctype="multipart/form-data" >
<input type="hidden" id="student_id" name="student_id" value="<?php echo $param1;?>" >
<input type="hidden" id="family_id" name="family_id" value="<?php echo $param4;?>" >
<input type="hidden" id="gestion" name="gestion" value="" >
<input type="hidden" id="parent_text" name="parent_text" value="">
<div class="modal-body">
    <div class="form-group">
        <label>Tipo de Licencia<span class="text-danger">*</span></label>
        <select class="form-control" id="tipo" name="tipo" onChange="fillFecha(this.value);" required>
            <option value="" disabled selected>Seleccione una opción</option>
            <option value="1">Por día(s)</option>
            <option value="2">Por hora(s)</option>
            <!-- Añade más opciones según sea necesario -->
        </select>
    </div>
    <div class="form-group">
        <label>Solicitada por:<span class="text-danger">*</span></label>
        <select class="form-control" id="parents" name="parents" required onChange="updateParentText()">
        </select>
    </div>
    <div class="form-group">
        <label>Motivo Licencia<span class="text-danger">*</span></label>
        <select class="form-control" id="motivo_id" name="motivo_id" required>

            <!-- Añade más opciones según sea necesario -->
        </select>
    </div>
    <div class="form-group">
        <label>Detalle de la Licencia<span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="detalle" name="detalle" placeholder="Detalle de la licencia" required >
    </div>
    <!-- RANGO FECHAS -->
    <div class="form-group" id="div_fecha_inicio" style="display:none;">
        <label>Fecha Inicio <span class="text-danger">*</span></label>
        <input type="date" class="form-control" value="" id="fecha_inicio" name="fecha_inicio" required disabled >
    </div>
    <div class="form-group" id="div_fecha_fin" style="display:none;">
        <label>Fecha Fin <span class="text-danger">*</span></label>
        <input type="date" class="form-control" value="" id="fecha_fin" name="fecha_fin" required disabled >
    </div> 
    <!-- RANGO HORAS -->
    <div class="form-group" id="div_hora_inicio" style="display:none;">
        <label>Hora Inicio <span class="text-danger">*</span></label>
        <input type="time" class="form-control" value="" id="hora_inicio" name="hora_inicio" required disabled >
    </div>
    <div class="form-group" id="div_hora_fin" style="display:none;">
        <label>Hora Fin <span class="text-danger">*</span></label>
        <input type="time" class="form-control" value="" id="hora_fin" name="hora_fin" required disabled >
    </div>
    <!-- Campo para subir comprobante médico -->
    <div class="form-group">
        <label>Comprobante Médico</label>
        <input type="file" class="form-control" id="comprobante_medico" name="comprobante_medico" accept=".jpg, .jpeg, .png, .pdf">
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
</div>
</form> 
<!--end::Modal-->