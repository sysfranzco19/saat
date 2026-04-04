<script type="text/javascript">

    $(document).ready(function() {
        $('#student').select2();
    });

    var sectionIdActual = null;

    function fillDatos(student_id) {
        if (!student_id) return;
        $.ajax({
            data: { "student_id": student_id },
            url: "<?php echo base_url('index.php/server/student_fill'); ?>",
            type: "post",
            success: function(response) {
                var content = JSON.parse(response);
                if (!content.length) return;
                var c = content[0];
                document.getElementById('student_id').value  = c.student_id;
                document.getElementById('cod_student').value = c.student_id;
                document.getElementById('family_id').value   = c.family_id;
                document.getElementById('cod_family').value  = c.family_id;
                document.getElementById('email').value       = c.email;
                document.getElementById('nombre').value      = c.nombre;
                document.getElementById('section_id').value  = c.completo;
                sectionIdActual = c.section_id;
                habilitarFormulario();
                fillPeriodos(c.section_id);
            }
        });
    }

    function habilitarFormulario() {
        var hoy   = new Date();
        var fecha = hoy.getFullYear() + '-'
            + (("0" + (hoy.getMonth() + 1)).slice(-2)) + '-'
            + (("0" + hoy.getDate()).slice(-2));
        var hora  = (("0" + hoy.getHours()).slice(-2)) + ':' + (("0" + hoy.getMinutes()).slice(-2));

        document.getElementById('fechaSolicita').value = fecha + 'T' + hora;
        document.getElementById('fecha').value = fecha;

        ['fechaSolicita','parentesco','solicitante','motivo','detalle','medio','fecha','hora_salida']
            .forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.disabled = false;
            });

        document.getElementById('div_periodos').style.display = '';
    }

    function fillPeriodos(section_id) {
        var container = document.getElementById('periodos_container');
        container.innerHTML = '<em>Cargando períodos...</em>';
        $.ajax({
            url: "<?php echo base_url('index.php/server/fill_periodos_section'); ?>/" + section_id,
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

                    var label = document.createElement('label');
                    label.htmlFor  = 'periodo_' + periodo.periodo_id;
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
        if (!document.getElementById('fecha').value) {
            alert('Debe ingresar la fecha de la licencia');
            document.getElementById("fecha").focus();
            return;
        }

        var seleccionados = document.querySelectorAll('input[name="periodos[]"]:checked');
        if (seleccionados.length === 0) {
            alert('Debe seleccionar al menos un período');
            return;
        }

        this.submit();
    }

    function fillParent(relationship) {
        var family_id = document.getElementById('family_id').value;
        if (relationship <= 2) {
            $.ajax({
                url: "<?php echo base_url('index.php/server/fill_parent_relationship'); ?>/" + family_id + '/' + relationship,
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

            <!-- Formulario de Licencia por Período -->
            <div class="col-xl-8">
                <form class="form-horizontal" method="POST"
                      action="<?php echo base_url('index.php/secretary/licencias_periodo_create'); ?>"
                      id="form_licencia">

                    <input type="hidden" id="student_id" name="student_id">
                    <input type="hidden" id="family_id"  name="family_id">

                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">Datos de la Licencia <small class="text-muted">Por Período</small></h3>
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

                                <!-- Fecha del período -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Fecha:</label>
                                    <div class="col-4">
                                        <input type="date" class="form-control" id="fecha" name="fecha" disabled required>
                                    </div>
                                    <div class="col-6 text-muted d-flex align-items-center">
                                        <small>Fecha en que ocurre la ausencia por período</small>
                                    </div>
                                </div>

                                <!-- Hora de Salida -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Hora de Salida:</label>
                                    <div class="col-4">
                                        <input type="time" class="form-control" id="hora_salida" name="hora_salida" disabled>
                                    </div>
                                    <div class="col-6 text-muted d-flex align-items-center">
                                        <small>Hora en que el estudiante se retira (opcional)</small>
                                    </div>
                                </div>

                                <!-- Selección de Períodos -->
                                <div class="form-group row" id="div_periodos" style="display:none;">
                                    <label class="col-2 col-form-label">Períodos: <span class="text-danger">*</span></label>
                                    <div class="col-10">
                                        <div id="periodos_container" class="mt-2"
                                             style="max-height:200px; overflow-y:auto; border:1px solid #ebedf3; padding:10px; border-radius:4px;">
                                            <em class="text-muted">Seleccione un estudiante para ver los períodos disponibles.</em>
                                        </div>
                                        <small class="text-muted mt-1 d-block">Puede seleccionar uno o más períodos.</small>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning mr-2">
                                <i class="fa fa-clock"></i> Registrar Licencia por Período
                            </button>
                            <a href="<?php echo base_url('index.php/secretary/licenses'); ?>" class="btn btn-secondary">Cancelar</a>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<!--end::Entry-->