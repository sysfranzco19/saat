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
            document.getElementById('fecha_solicitud').value = fecha;

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

    function fillPeriodos() {
        $.ajax({
            url: "<?php echo base_url(); ?>server/fill_periodos_section/<?php echo $param4; ?>",
            type: "get",
            success: function (response) {
                let container = document.getElementById('periodos_container');
                container.innerHTML = '';
                response.forEach(function (periodo) {
                    let wrapper = document.createElement('div');
                    wrapper.className = 'form-check form-check-inline mt-2';

                    let checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'periodos[]';
                    checkbox.value = periodo.periodo_id;
                    checkbox.id = 'periodo_' + periodo.periodo_id;
                    checkbox.className = 'form-check-input';

                    let label = document.createElement('label');
                    label.htmlFor = 'periodo_' + periodo.periodo_id;
                    let horaIn = periodo.hora_inicio ? periodo.hora_inicio.substring(0, 5) : '';
                    let horaOut = periodo.hora_fin ? periodo.hora_fin.substring(0, 5) : '';
                    label.textContent = periodo.periodo + ' (' + horaIn + ' - ' + horaOut + ')';
                    label.className = 'form-check-label mr-4';

                    wrapper.appendChild(checkbox);
                    wrapper.appendChild(label);

                    container.appendChild(wrapper);
                });
            }
        });
    }

    getParents();
    fillMotivos();
    fillPeriodos();
    fillFecha(2);
    updateParentText();
    // Evento cuando para no enviar el formulario
    const form = document.getElementById("fecha_solicitud");
    fechaSolicitud.addEventListener("change", function () {
        if (fechaSolicitud.value !== today) {
            alert("Solo puedes hacer solicitudes para hoy.");
            fechaSolicitud.value = today;
        }
    });
</script>
<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">Licencia por Periodo: <?php echo $param2; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<?php //echo form_open(base_url() . 'teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form action="<?php echo base_url() . 'parents/license_save_periodo'; ?>" id="formLicenciaHora" method="post"
    class="form-horizontal" enctype="multipart/form-data">
    <input type="hidden" id="student_id" name="student_id" value="<?php echo $param1; ?>">
    <input type="hidden" id="family_id" name="family_id" value="<?php echo $param3; ?>">
    <input type="hidden" id="gestion" name="gestion" value="">
    <input type="hidden" id="parent_text" name="parent_text" value="">
    <input type="hidden" id="tipo" name="tipo" value="2">
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
        <!-- FECHA SOLICITUD -->
        <div class="form-group">
            <label>Fecha <span class="text-danger">*</span></label>
            <input type="date" class="form-control" value="" id="fecha_solicitud" name="fecha" required>
        </div>
        <!-- PERIODOS -->
        <div class="form-group" id="div_periodos">
            <label>Seleccione el o los Periodos <span class="text-danger">*</span></label>
            <div id="periodos_container" class="mt-2"
                style="max-height: 200px; overflow-y: auto; border: 1px solid #ebedf3; padding: 10px; border-radius: 4px;">
                <!-- Checkboxes de periodos cargados por AJAX -->
            </div>
        </div>
        <!-- Campo para subir comprobante médico -->
        <div class="form-group">
            <label>Adjunte una fotografía o imagen del documento que respalde la solicitud de licencia.</label>
            <input type="file" class="form-control" id="comprobante_medico" name="comprobante_medico"
                accept=".jpg, .jpeg, .png, .pdf">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-secondary font-weight-bold">Guardar</button>
    </div>
</form>
<!--end::Modal-->
<script type="text/javascript">
    document.getElementById("formLicenciaHora").addEventListener("submit", function (event) {
        let fechaInput = document.getElementById("fecha_solicitud").value; // Obtener la fecha ingresada
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
            let despuesDe930 = (horaActual > 9 || (horaActual === 9 && minutosActuales >= 30));
            let antesDe1430  = (horaActual < 14 || (horaActual === 14 && minutosActuales < 30));
            if (despuesDe930 && antesDe1430) {
                alert("No puede enviar el formulario entre las 9:30 a.m. y las 2:30 p.m. en la fecha de hoy.");
                event.preventDefault(); // Evita el envío del formulario
            }
        }

        // Ensure at least one periodo is checked
        let checkedBoxes = document.querySelectorAll('input[name="periodos[]"]:checked');
        if (checkedBoxes.length === 0) {
            alert("Debe seleccionar al menos un periodo.");
            event.preventDefault(); // Evita el envío del formulario
        }

        //alert(añoIngresado + " - " + mesIngresado + " - " + diaIngresado);
        //alert(añoActual + " - " + mesActual + " - " + diaActual);
        //event.preventDefault(); // Evita el envío del formulario
    });
    // Obtener el input de fecha

    /*
// Función para obtener la fecha y hora actual en Bolivia (UTC-4)
function getBoliviaTime() {
    let now = new Date();
    now.setHours(now.getHours() - 4); // Ajustar la hora a Bolivia
    return now;
}

// Ejecutar cuando el modal se muestra
$('#modal_ajax').on('shown.bs.modal', function () {
    const fechaSolicitud = document.getElementById("fecha_solicitud");
    const btnEnviar = document.getElementById("btn_enviar");

    // Obtener la fecha y hora actual en Bolivia
    let now = getBoliviaTime();
    let today = now.toISOString().split("T")[0]; // Formato YYYY-MM-DD
    let horaActual = now.getHours();
    let minutosActuales = now.getMinutes();

    // Establecer la fecha mínima (hoy)
    fechaSolicitud.min = today;
    fechaSolicitud.value = today; // Se establece por defecto la fecha de hoy

    // Verificar si ya pasó las 9:30 a.m.
    if (horaActual > 9 || (horaActual === 9 && minutosActuales >= 30)) {
        alert("No puedes enviar solicitudes después de las 9:30 a.m.");
        btnEnviar.disabled = true;
    } else {
        btnEnviar.disabled = false;
    }

    // Evento cuando cambia la fecha de solicitud
    fechaSolicitud.addEventListener("change", function () {
        if (fechaSolicitud.value !== today) {
            alert("Solo puedes hacer solicitudes para hoy.");
            fechaSolicitud.value = today;
        }
    });
});

// Evitar el envío del formulario si la hora ya pasó
document.getElementById("btn_enviar").addEventListener("click", function (event) {
    let now = getBoliviaTime();
    let horaActual = now.getHours();
    let minutosActuales = now.getMinutes();

    if (horaActual > 9 || (horaActual === 9 && minutosActuales >= 30)) {
        alert("No puedes enviar la solicitud después de las 9:30 a.m.");
        event.preventDefault(); // Evita que el formulario se envíe
    }
});
*/
</script>
<!-- Agregar jQuery y Bootstrap si no están incluidos -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>