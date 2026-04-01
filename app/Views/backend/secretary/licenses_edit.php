<?php $lic = $licencia[0]; ?>
<script type="text/javascript">

    $(document).ready(function() {
        $('#student').select2();
        cambiarTipo(<?php echo (int)$lic['tipo_id']; ?>);
    });

    var sectionIdActual = <?php echo (int)$lic['section_id']; ?>;
    var periodosPreseleccionados = <?php echo json_encode(array_map('intval', $periodos_ids)); ?>;

    function fillDatos(student_id) {
        if (!student_id) return;
        $.ajax({
            data: { "student_id": student_id },
            url: "<?php echo base_url('server/student_fill'); ?>",
            type: "post",
            success: function(response) {
                var content = JSON.parse(response);
                if (!content.length) return;
                var c = content[0];
                document.getElementById('student_id').value  = c.student_id;
                document.getElementById('cod_student').value = c.student_id;
                document.getElementById('family_id').value   = c.family_id;
                document.getElementById('email').value       = c.email;
                document.getElementById('nombre').value      = c.nombre;
                document.getElementById('section_id').value  = c.completo;
                sectionIdActual = c.section_id;
                periodosPreseleccionados = [];
                if (document.getElementById('tipo').value == '2') {
                    fillPeriodos(sectionIdActual);
                }
            }
        });
    }

    function cambiarTipo(tipo) {
        var divDia     = document.getElementById('div_dia');
        var divPeriodo = document.getElementById('div_periodo');
        var camposDia     = ['fecha_inicio', 'fecha_fin', 'cantidad', 'sabados'];
        var camposPeriodo = ['fecha_periodo'];

        if (tipo == 2 || tipo == '2') {
            divDia.style.display     = 'none';
            divPeriodo.style.display = '';
            camposDia.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.disabled = true;
            });
            camposPeriodo.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.disabled = false;
            });
            fillPeriodos(sectionIdActual);
        } else {
            divDia.style.display     = '';
            divPeriodo.style.display = 'none';
            camposDia.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.disabled = false;
            });
            camposPeriodo.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.disabled = true;
            });
        }
    }

    function fillPeriodos(section_id) {
        var container = document.getElementById('periodos_container');
        container.innerHTML = '<em>Cargando períodos...</em>';
        $.ajax({
            url: "<?php echo base_url('server/fill_periodos_section'); ?>/" + section_id,
            type: "get",
            success: function(response) {
                container.innerHTML = '';
                if (!response.length) {
                    container.innerHTML = '<span class="text-muted">No hay períodos para este curso.</span>';
                    return;
                }
                response.forEach(function(periodo) {
                    var wrapper = document.createElement('div');
                    wrapper.className = 'form-check form-check-inline mt-2';

                    var checkbox = document.createElement('input');
                    checkbox.type      = 'checkbox';
                    checkbox.name      = 'periodos[]';
                    checkbox.value     = periodo.periodo_id;
                    checkbox.id        = 'periodo_' + periodo.periodo_id;
                    checkbox.className = 'form-check-input';
                    if (periodosPreseleccionados.indexOf(parseInt(periodo.periodo_id)) >= 0) {
                        checkbox.checked = true;
                    }

                    var label = document.createElement('label');
                    label.htmlFor = 'periodo_' + periodo.periodo_id;
                    var horaIn  = periodo.hora_inicio ? periodo.hora_inicio.substring(0, 5) : '';
                    var horaOut = periodo.hora_fin    ? periodo.hora_fin.substring(0, 5)    : '';
                    label.textContent = periodo.periodo + ' (' + horaIn + ' - ' + horaOut + ')';
                    label.className   = 'form-check-label mr-4';

                    wrapper.appendChild(checkbox);
                    wrapper.appendChild(label);
                    container.appendChild(wrapper);
                });
            },
            error: function() {
                container.innerHTML = '<span class="text-danger">Error al cargar períodos.</span>';
            }
        });
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
        if (!document.getElementById('student_id').value) {
            alert('Debe seleccionar un Estudiante');
            document.getElementById("student").focus();
            return;
        }
        if (!document.getElementById('medio').value) {
            alert('Debe seleccionar el medio de comunicación');
            document.getElementById("medio").focus();
            return;
        }
        var tipo = document.getElementById('tipo').value;
        if (tipo == '2') {
            if (!document.getElementById('fecha_periodo').value) {
                alert('Debe ingresar la fecha del período');
                document.getElementById("fecha_periodo").focus();
                return;
            }
            var seleccionados = document.querySelectorAll('input[name="periodos[]"]:checked');
            if (seleccionados.length === 0) {
                alert('Debe seleccionar al menos un período');
                return;
            }
        } else {
            if (!document.getElementById('fecha_inicio').value || !document.getElementById('fecha_fin').value) {
                alert('Debe ingresar las fechas de la licencia');
                return;
            }
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
                    if (content.length) {
                        document.getElementById('solicitante').value =
                            content[0].name + ' ' + content[0].lastname1 + ' ' + content[0].lastname2;
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
                                    <option value="<?php echo $stu['student_id']; ?>" <?php if($stu['student_id']==$lic['student_id']) echo 'selected'; ?>>
                                        <?php echo $stu['lastname'].' '.$stu['lastname2'].' '.$stu['name']; ?> - <b><?php echo $stu['nick_name']; ?></b>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group row">
                            <div class="col-6">
                                <label>Id Alumno</label>
                                <input type="text" id="cod_student" class="form-control" value="<?php echo $lic['student_id']; ?>" disabled>
                            </div>
                            <div class="col-6">
                                <label>Id Familia</label>
                                <input type="text" id="family_id" class="form-control" value="<?php echo $lic['family_id']; ?>" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" id="nombre" class="form-control" value="<?php echo $lic['student']; ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Curso</label>
                            <input type="text" id="section_id" class="form-control" value="<?php echo $lic['completo']; ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label>Correo Institucional</label>
                            <input type="text" id="email" class="form-control" value="<?php echo $lic['email']; ?>" disabled>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Formulario de Edición -->
            <div class="col-xl-8">
                <form class="form-horizontal" method="POST"
                      action="<?php echo base_url($account_type . '/licenses_update'); ?>"
                      id="form_licencia">

                    <input type="hidden" id="student_id" name="student_id" value="<?php echo $lic['student_id']; ?>">
                    <input type="hidden" id="licencia_id" name="licencia_id" value="<?php echo $lic['licencias_id']; ?>">

                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">Editar Licencia</h3>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-group mb-8">

                                <!-- Tipo + Medio -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Tipo:</label>
                                    <div class="col-4">
                                        <select class="form-control" id="tipo" name="tipo" onChange="cambiarTipo(this.value);" required>
                                            <option value="">Seleccione el Tipo</option>
                                            <option value="1" <?php if($lic['tipo_id']==1) echo 'selected'; ?>>Por día(s)</option>
                                            <option value="2" <?php if($lic['tipo_id']==2) echo 'selected'; ?>>Por Período</option>
                                        </select>
                                    </div>
                                    <label class="col-2 col-form-label">Medio:</label>
                                    <div class="col-4">
                                        <select class="form-control" id="medio" name="medio" required>
                                            <option value="">Seleccione el Medio</option>
                                            <?php foreach($medios as $med): ?>
                                                <option value="<?php echo $med->medio_id; ?>" <?php if($med->medio_id==$lic['medio_id']) echo 'selected'; ?>><?php echo $med->medio; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Fecha Solicitud -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Fecha Solicitud:</label>
                                    <div class="col-10">
                                        <?php $fechaSolicitud = substr(str_replace(' ','T',$lic['fecha_solicitud']),0,-3); ?>
                                        <input type="datetime-local" class="form-control" id="fechaSolicita" name="fechaSolicita" value="<?php echo $fechaSolicitud; ?>" required>
                                    </div>
                                </div>

                                <!-- Parentesco + Solicitante -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Parentesco:</label>
                                    <div class="col-4">
                                        <select class="form-control" id="parentesco" name="parentesco" onChange="fillParent(this.value);" required>
                                            <option value="">Seleccione el Parentesco</option>
                                            <?php foreach($parentescos as $par): ?>
                                                <option value="<?php echo $par->parentesco_id; ?>" <?php if($par->parentesco_id==$lic['parentesco_id']) echo 'selected'; ?>><?php echo $par->parentesco; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <label class="col-2 col-form-label">Solicitante:</label>
                                    <div class="col-4">
                                        <input type="text" id="solicitante" name="solicitante" class="form-control" value="<?php echo $lic['solicitante']; ?>" required>
                                    </div>
                                </div>

                                <!-- Motivo -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Motivo:</label>
                                    <div class="col-10">
                                        <select class="form-control" id="motivo" name="motivo" required>
                                            <option value="">Seleccione el Motivo</option>
                                            <?php foreach($motivos as $mot): ?>
                                                <option value="<?php echo $mot->motivo_id; ?>" <?php if($mot->motivo_id==$lic['motivo_id']) echo 'selected'; ?>><?php echo $mot->motivo; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Detalle -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Detalle:</label>
                                    <div class="col-10">
                                        <input type="text" id="detalle" name="detalle" class="form-control" value="<?php echo $lic['detalle']; ?>" required>
                                    </div>
                                </div>

                                <!-- ===== SECCIÓN TIPO 1: Días ===== -->
                                <div id="div_dia">
                                    <div class="form-group row">
                                        <label class="col-2 col-form-label">Fecha Inicio:</label>
                                        <div class="col-4">
                                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                                   value="<?php echo $lic['fecha_inicio'] ?? ''; ?>" onChange="calcularDias();">
                                        </div>
                                        <label class="col-2 col-form-label">Fecha Fin:</label>
                                        <div class="col-4">
                                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                                   value="<?php echo $lic['fecha_fin'] ?? ''; ?>" onBlur="calcularDias();">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-2 col-form-label">Cantidad Días:</label>
                                        <div class="col-10">
                                            <label class="mb-2">
                                                <input type="checkbox" id="sabados" name="sabados" checked onChange="calcularDias();">
                                                Contar Sábados
                                            </label>
                                            <input type="number" class="form-control" id="cantidad" name="cantidad"
                                                   value="<?php echo $lic['cantidad_dias'] ?? 1; ?>" min="1">
                                        </div>
                                    </div>
                                </div>

                                <!-- ===== SECCIÓN TIPO 2: Período ===== -->
                                <div id="div_periodo" style="display:none;">
                                    <div class="form-group row">
                                        <label class="col-2 col-form-label">Fecha:</label>
                                        <div class="col-4">
                                            <input type="date" class="form-control" id="fecha_periodo" name="fecha_periodo"
                                                   value="<?php echo $lic['fecha_periodo'] ?? ''; ?>">
                                        </div>
                                        <div class="col-6 text-muted d-flex align-items-center">
                                            <small>Fecha en que ocurre la ausencia por período</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-2 col-form-label">Hora de Salida:</label>
                                        <div class="col-4">
                                            <input type="time" class="form-control" id="hora_salida" name="hora_salida"
                                                   value="<?php echo $lic['hora_salida'] ?? ''; ?>">
                                        </div>
                                        <div class="col-6 text-muted d-flex align-items-center">
                                            <small>Hora en que el estudiante se retira (opcional)</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-2 col-form-label">Períodos: <span class="text-danger">*</span></label>
                                        <div class="col-10">
                                            <div id="periodos_container" class="mt-2"
                                                 style="max-height:200px; overflow-y:auto; border:1px solid #ebedf3; padding:10px; border-radius:4px;">
                                                <em class="text-muted">Cargando períodos...</em>
                                            </div>
                                            <small class="text-muted mt-1 d-block">Puede seleccionar uno o más períodos.</small>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">Guardar cambios</button>
                            <a href="<?php echo base_url('secretary/licenses'); ?>" class="btn btn-secondary">Cancelar</a>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!--end::Entry-->