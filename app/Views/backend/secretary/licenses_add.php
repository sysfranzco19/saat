<script type="text/javascript">

    $(document).ready(function() {
        $('#student').select2();
    });

    function fillDatos(student_id) {
        var parametros = { "student_id": student_id };
        $.ajax({
            data: parametros,
            url: "<?php echo base_url('server/student_fill'); ?>",
            type: "post",
            success: function(response) {
                var content = JSON.parse(response);
                for (var i = 0; i < content.length; i++) {
                    document.getElementById('student_id').value   = content[i].student_id;
                    document.getElementById('cod_student').value  = content[i].student_id;
                    document.getElementById('family_id').value    = content[i].family_id;
                    document.getElementById('cod_family').value   = content[i].family_id;
                    document.getElementById('email').value        = content[i].email;
                    document.getElementById('nombre').value       = content[i].nombre;
                    document.getElementById('section_id').value  = content[i].completo;
                }
                habilitarFormulario();
            }
        });
    }

    function habilitarFormulario() {
        if (document.getElementById('family_id').value > 0) {
            var hoy = new Date();
            var fecha = hoy.getFullYear() + '-'
                + (("0" + (hoy.getMonth() + 1)).slice(-2)) + '-'
                + (("0" + hoy.getDate()).slice(-2));
            var hora = (("0" + hoy.getHours()).slice(-2)) + ':' + (("0" + hoy.getMinutes()).slice(-2));

            document.getElementById('fechaSolicita').value = fecha + 'T' + hora;
            document.getElementById('fecha_inicio').value  = fecha;
            document.getElementById('fecha_fin').value     = fecha;

            // Habilitar todos los campos
            ['fechaSolicita','solicitante','parentesco','motivo','detalle','medio',
             'fecha_inicio','fecha_fin','sabados','cantidad'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.disabled = false;
            });

            document.getElementById('div_fechas').style.display   = '';
            document.getElementById('div_cantidad').style.display  = '';
        } else {
            alert('Seleccione un Estudiante o complete los datos');
        }
    }

    function calcularDias() {
        var fi = new Date(document.getElementById("fecha_inicio").value);
        var ff = new Date(document.getElementById("fecha_fin").value);
        if (ff < fi) {
            alert("La fecha final debe ser mayor o igual a la fecha inicial");
            document.getElementById("cantidad").value = 0;
            return;
        }
        var contarSabados = document.getElementById("sabados").checked;
        var dias = 0;
        var cur = new Date(fi);
        while (cur <= ff) {
            var dow = cur.getDay();
            if (dow !== 0 && (contarSabados || dow !== 6)) dias++;
            cur.setDate(cur.getDate() + 1);
        }
        document.getElementById("cantidad").value = dias > 0 ? dias : 1;
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("form_licencia").addEventListener('submit', validarFormulario);
    });

    function validarFormulario(evento) {
        evento.preventDefault();
        var student_id = document.getElementById('student_id').value;
        var medio      = document.getElementById('medio').value;
        var fi         = document.getElementById('fecha_inicio').value;
        var ff         = document.getElementById('fecha_fin').value;

        if (!student_id) {
            alert('Debe seleccionar un Estudiante');
            document.getElementById("student").focus();
            return;
        }
        if (!medio) {
            alert('Debe seleccionar el medio de comunicación');
            document.getElementById("medio").focus();
            return;
        }
        if (!fi || !ff) {
            alert('Debe ingresar las fechas de la licencia');
            return;
        }
        if (new Date(ff) < new Date(fi)) {
            alert('La fecha fin no puede ser anterior a la fecha inicio');
            return;
        }
        this.submit();
    }

    function fillParent(relationship) {
        var family_id = document.getElementById('family_id').value;
        if (relationship <= 2) {
            $.ajax({
                url: "<?php echo base_url('server/fill_parent_relationship'); ?>/" + family_id + '/' + relationship,
                type: "get",
                success: function(response) {
                    var content = JSON.parse(response);
                    for (var i = 0; i < content.length; i++) {
                        document.getElementById('solicitante').value =
                            content[i].name + ' ' + content[i].lastname1 + ' ' + content[i].lastname2;
                    }
                }
            });
        } else {
            document.getElementById('solicitante').value = "";
            document.getElementById("solicitante").focus();
        }
    }

</script>

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="row">

            <!-- Datos del Estudiante -->
            <div class="col-md-4">
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Datos Estudiante</h3>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label>Estudiante:</label>
                            <select class="form-control select2" style="width:100%;" id="student" name="student" onChange="fillDatos(this.value);">
                                <option value="">Seleccione al Estudiante</option>
                                <?php foreach($students as $stu): ?>
                                    <option value="<?php echo $stu['student_id']; ?>">
                                        <?php echo $stu['lastname'] . ' ' . $stu['lastname2'] . ' ' . $stu['name']; ?> - <b><?php echo $stu['nick_name']; ?></b>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group row">
                            <div class="col-6">
                                <label>Id Alumno</label>
                                <input type="text" id="cod_student" class="form-control" disabled>
                            </div>
                            <div class="col-6">
                                <label>Id Familia</label>
                                <input type="text" id="cod_family" class="form-control" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" id="nombre" class="form-control" disabled>
                        </div>

                        <div class="form-group">
                            <label>Curso</label>
                            <input type="text" id="section_id" class="form-control" disabled>
                        </div>

                        <div class="form-group">
                            <label>Correo Institucional</label>
                            <input type="text" id="email" class="form-control" disabled>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Formulario de Licencia -->
            <div class="col-xl-8">
                <form class="form-horizontal" method="POST"
                      action="<?php echo base_url() . $account_type . '/licencias_create'; ?>"
                      id="form_licencia">

                    <input type="hidden" id="student_id" name="student_id">
                    <input type="hidden" id="family_id"  name="family_id">

                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">Datos de la Licencia <small class="text-muted">Por día(s)</small></h3>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group mb-8">

                                <!-- Medio + Fecha Solicitud -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Medio:</label>
                                    <div class="col-4">
                                        <select class="form-control" id="medio" name="medio" disabled required>
                                            <option value="">Seleccione el Medio</option>
                                            <?php foreach($medios as $med): ?>
                                                <option value="<?php echo $med->medio_id; ?>"><?php echo $med->medio; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <label class="col-2 col-form-label">Fecha Solicitud:</label>
                                    <div class="col-4">
                                        <input type="datetime-local" class="form-control" id="fechaSolicita" name="fechaSolicita" disabled required>
                                    </div>
                                </div>

                                <!-- Parentesco + Solicitante -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Parentesco:</label>
                                    <div class="col-4">
                                        <select class="form-control" id="parentesco" name="parentesco" onChange="fillParent(this.value);" disabled required>
                                            <option value="">Seleccione el Parentesco</option>
                                            <?php foreach($parentescos as $par): ?>
                                                <option value="<?php echo $par->parentesco_id; ?>"><?php echo $par->parentesco; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <label class="col-2 col-form-label">Solicitante:</label>
                                    <div class="col-4">
                                        <input type="text" id="solicitante" name="solicitante" class="form-control" disabled required>
                                    </div>
                                </div>

                                <!-- Motivo -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Motivo:</label>
                                    <div class="col-10">
                                        <select class="form-control" id="motivo" name="motivo" disabled required>
                                            <option value="">Seleccione el Motivo</option>
                                            <?php foreach($motivos as $mot): ?>
                                                <option value="<?php echo $mot->motivo_id; ?>"><?php echo $mot->motivo; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Detalle -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Detalle:</label>
                                    <div class="col-10">
                                        <input type="text" id="detalle" name="detalle" class="form-control" disabled required>
                                    </div>
                                </div>

                                <!-- Rango de Fechas -->
                                <div class="form-group row" id="div_fechas" style="display:none;">
                                    <label class="col-2 col-form-label">Fecha Inicio:</label>
                                    <div class="col-4">
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                               onChange="calcularDias();" disabled required>
                                    </div>
                                    <label class="col-2 col-form-label">Fecha Fin:</label>
                                    <div class="col-4">
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                               onBlur="calcularDias();" disabled required>
                                    </div>
                                </div>

                                <!-- Cantidad de días -->
                                <div class="form-group row" id="div_cantidad" style="display:none;">
                                    <label class="col-2 col-form-label">Cantidad Días:</label>
                                    <div class="col-10">
                                        <label class="mb-2">
                                            <input type="checkbox" id="sabados" name="sabados" checked disabled onChange="calcularDias();">
                                            Contar Sábados
                                        </label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad" value="1" min="1" disabled required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">Registrar</button>
                            <a href="<?php echo base_url('secretary/licenses'); ?>" class="btn btn-secondary">Cancelar</a>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!--end::Entry-->