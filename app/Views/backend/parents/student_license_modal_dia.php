<script type="text/javascript">
    function getParents() {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        var parametros = {
            "family_id": "<?php echo $param3; ?>",
        };
        $.ajax({
            data: parametros,
            url: "<?php echo base_url(); ?>server/parents_fill",
            type: "post",
            success: function (response) {
                document.getElementById('parents').innerHTML = response;
            }
        });

    }
    function fillFecha(tipo_licencia) {
        //Verificamos que eexiste un Valor en IdFamilia
        if (document.getElementById('student_id').value > 0) {
            var hoy = new Date();

            //var fecha = hoy.getDate() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getFullYear();
            var fecha = hoy.getFullYear() + '-' + (("0" + (hoy.getMonth() + 1)).slice(-2)) + '-' + (("0" + hoy.getDate()).slice(-2));
            //var hora = hoy.getHours() + '-' + hoy.getMinutes() + '-' + hoy.getSeconds();
            var hora = (("0" + (hoy.getHours())).slice(-2)) + ':' + (("0" + (hoy.getMinutes())).slice(-2));

            var fecha_hora = fecha + 'T' + hora;
            document.getElementById('fecha_inicio').value = fecha;
            document.getElementById('fecha_fin').value = fecha;
            /*
            document.getElementById('fechaSolicita').value = fecha_hora;
            */
            if (tipo_licencia == 2) {
                //Licencia por Horas
                document.getElementById("fecha_inicio").disabled = true;
                document.getElementById("fecha_fin").disabled = true;
                document.getElementById("hora_inicio").disabled = false;
                document.getElementById("hora_fin").disabled = false;
                div_fecha_inicio = document.getElementById('div_fecha_inicio');
                div_fecha_inicio.style.display = 'none';
                div_fecha_fin = document.getElementById('div_fecha_fin');
                div_fecha_fin.style.display = 'none';
                //div_cantidad = document.getElementById('div_cantidad');
                //div_cantidad.style.display = 'none';
            } else {
                //Licencia por DIAS
                document.getElementById("fecha_inicio").disabled = false;
                document.getElementById("fecha_fin").disabled = false;
                div_fecha_inicio = document.getElementById('div_fecha_inicio');
                div_fecha_inicio.style.display = '';
                div_fecha_fin = document.getElementById('div_fecha_fin');
                div_fecha_fin.style.display = '';
                //div_cantidad = document.getElementById('div_cantidad');
                //div_cantidad.style.display = '';
            }
        } else {
            alert('Seleccione un Estudiante o complete los datos');
        }
    }
    function fillMotivos() {
        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: "<?php echo base_url(); ?>server/fill_motivos",
            type: "get",
            success: function (response) {
                // Asumiendo que la respuesta es un JSON con los motivos
                let select = document.getElementById('motivo_id');
                select.innerHTML = ''; // Limpiar el select
                response.forEach(function (motivo) {
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
    getParents();
    fillMotivos();
    fillFecha(1);
    updateParentText();


</script>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Licencia por día: <?php echo $param2; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<?php //echo form_open(base_url() . 'teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form action="<?php echo base_url() . 'parents/license_save_dia'; ?>" id="formLicenciaDia" method="post"
    class="form-horizontal" enctype="multipart/form-data">
    <input type="hidden" id="student_id" name="student_id" value="<?php echo $param1; ?>">
    <input type="hidden" id="family_id" name="family_id" value="<?php echo $param3; ?>">
    <input type="hidden" id="gestion" name="gestion" value="">
    <input type="hidden" id="parent_text" name="parent_text" value="">
    <input type="hidden" id="tipo" name="tipo" value="1">
    <div class="modal-body">
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
            <input type="text" class="form-control" id="detalle" name="detalle" placeholder="Detalle de la licencia"
                required>
        </div>
        <!-- RANGO FECHAS -->
        <div class="form-group" id="div_fecha_inicio" style="display:none;">
            <label>Fecha Inicio <span class="text-danger">*</span></label>
            <input type="date" class="form-control" value="" id="fecha_inicio" name="fecha_inicio" required disabled>
        </div>
        <div class="form-group" id="div_fecha_fin" style="display:none;">
            <label>Fecha Fin <span class="text-danger">*</span></label>
            <input type="date" class="form-control" value="" id="fecha_fin" name="fecha_fin" required disabled>
        </div>
        <!-- Campo para subir comprobante médico -->
        <div class="form-group" id="div_comprobante_medico">
            <label>Adjunte una fotografía o imagen del documento que respalde la solicitud de licencia. <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="comprobante_medico" name="comprobante_medico"
                accept=".jpg, .jpeg, .png, .pdf" required>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
    </div>
</form>
<!--end::Modal-->
<script type="text/javascript">

    // Función para obtener la fecha actual en Bolivia (UTC-4)
    function getBoliviaDate() {
        let now = new Date();
        now.setHours(now.getHours() - 4); // Ajustar la hora a Bolivia
        return now.toISOString().split("T")[0]; // Formato YYYY-MM-DD
    }

    // Ejecutar cuando el modal se muestra
    $('#modal_ajax').on('shown.bs.modal', function () {
        const fechaInicio = document.getElementById("fecha_inicio");
        const fechaFin = document.getElementById("fecha_fin");

        // Establecer la fecha mínima para la fecha de inicio
        const today = getBoliviaDate();
        fechaInicio.min = today;
        fechaInicio.value = today;
        fechaFin.value = "";
        fechaFin.min = today;
        fechaFin.max = "";

        // Calcula la fecha máxima sumando N días hábiles (excluye sábado y domingo)
        function sumarDiasHabiles(fechaStr, dias) {
            let partes = fechaStr.split("-");
            let fecha = new Date(parseInt(partes[0]), parseInt(partes[1]) - 1, parseInt(partes[2]));
            let agregados = 0;
            while (agregados < dias) {
                fecha.setDate(fecha.getDate() + 1);
                let dow = fecha.getDay(); // 0=Dom, 6=Sáb
                if (dow !== 0 && dow !== 6) {
                    agregados++;
                }
            }
            let y = fecha.getFullYear();
            let m = ("0" + (fecha.getMonth() + 1)).slice(-2);
            let d = ("0" + fecha.getDate()).slice(-2);
            return y + "-" + m + "-" + d;
        }

        // Cuenta los días hábiles entre dos fechas (inclusivo en ambos extremos)
        function contarDiasHabiles(inicioStr, finStr) {
            let partes = inicioStr.split("-");
            let inicio = new Date(parseInt(partes[0]), parseInt(partes[1]) - 1, parseInt(partes[2]));
            partes = finStr.split("-");
            let fin = new Date(parseInt(partes[0]), parseInt(partes[1]) - 1, parseInt(partes[2]));
            let count = 0;
            let cur = new Date(inicio);
            while (cur <= fin) {
                let dow = cur.getDay();
                if (dow !== 0 && dow !== 6) count++;
                cur.setDate(cur.getDate() + 1);
            }
            return count;
        }

        // Evento cuando cambia la fecha de inicio
        fechaInicio.addEventListener("change", function () {
            if (fechaInicio.value) {
                let dow = new Date(fechaInicio.value + "T00:00:00").getDay();
                if (dow === 0 || dow === 6) {
                    alert("La fecha de inicio no puede ser sábado ni domingo.");
                    fechaInicio.value = "";
                    fechaFin.value = "";
                    fechaFin.min = "";
                    fechaFin.max = "";
                    return;
                }
                let fechaMax = sumarDiasHabiles(fechaInicio.value, 3);
                fechaFin.min = fechaInicio.value;
                fechaFin.max = fechaMax;
                fechaFin.value = "";
            }
        });

        // Evento cuando cambia la fecha de fin
        fechaFin.addEventListener("change", function () {
            if (!fechaFin.value) return;
            let dow = new Date(fechaFin.value + "T00:00:00").getDay();
            if (dow === 0 || dow === 6) {
                alert("La fecha de fin no puede ser sábado ni domingo.");
                fechaFin.value = "";
                return;
            }
            if (fechaFin.value < fechaInicio.value) {
                alert("La fecha de fin no puede ser anterior a la fecha de inicio.");
                fechaFin.value = "";
                return;
            }
            if (contarDiasHabiles(fechaInicio.value, fechaFin.value) > 3) {
                alert("El rango no puede superar 3 días hábiles (lunes a viernes).");
                fechaFin.value = "";
            }
        });
    });

    document.getElementById("formLicenciaDia").addEventListener("submit", function (event) {
        let fechaInput = document.getElementById("fecha_inicio").value; // Obtener la fecha ingresada
        if (!fechaInput) return; // Si no hay fecha, salir

        // Convertir la fecha ingresada manualmente para evitar problemas de zona horaria
        let partesFecha = fechaInput.split("-"); // Separar en [YYYY, MM, DD]
        let añoIngresado = parseInt(partesFecha[0], 10);
        let mesIngresado = parseInt(partesFecha[1], 10) - 1; // Restar 1 porque los meses van de 0-11 en JS
        let diaIngresado = parseInt(partesFecha[2], 10);

        let fechaIngresada = new Date(añoIngresado, mesIngresado, diaIngresado); // Crear objeto Date sin ajustes de zona horaria

        // Obtener la fecha y hora actual en Bolivia (Zona horaria: America/La_Paz)
        let ahora = new Date().toLocaleString("en-US", { timeZone: "America/La_Paz" });
        let fechaActual = new Date(ahora);

        // Extraer solo el año, mes y día para comparación
        let añoActual = fechaActual.getFullYear();
        let mesActual = fechaActual.getMonth();
        let diaActual = fechaActual.getDate();

        // Obtener la hora y minutos actuales en Bolivia
        let horaActual = fechaActual.getHours();
        let minutosActuales = fechaActual.getMinutes();

        console.log(`Fecha ingresada: ${fechaIngresada.toISOString().slice(0, 10)}`);
        console.log(`Fecha actual: ${fechaActual.toISOString().slice(0, 10)}`);
        console.log(`Hora actual en Bolivia: ${horaActual}:${minutosActuales}`);

        // Comparar si la fecha ingresada es igual a la actual (sin la hora)
        if (añoIngresado === añoActual && mesIngresado === mesActual && diaIngresado === diaActual) {
            // Bloquear entre 9:30 y 14:30
            let despuesDe930  = (horaActual > 9 || (horaActual === 9 && minutosActuales >= 30));
            let antesDe1430   = (horaActual < 14 || (horaActual === 14 && minutosActuales < 30));
            if (despuesDe930 && antesDe1430) {
                alert("No puede enviar el formulario entre las 9:30 a.m. y las 2:30 p.m. en la fecha de inicio de hoy.");
                event.preventDefault(); // Evita el envío del formulario
            }
        }
        //alert(añoIngresado + " - " + mesIngresado + " - " + diaIngresado);
        //alert(añoActual + " - " + mesActual + " - " + diaActual);
        //event.preventDefault(); // Evita el envío del formulario
    });
    //ncluir jQuery y Bootstrap si aún no están en la página
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>