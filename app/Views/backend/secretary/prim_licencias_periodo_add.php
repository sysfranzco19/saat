<style>
.cupo-bar-wrap { background:#f3f0ff; border-radius:10px; padding:14px 18px; }
.cupo-bar-bg   { background:#e0e0e0; border-radius:6px; height:14px; overflow:hidden; margin:8px 0; }
.cupo-bar-fill { height:14px; border-radius:6px; transition:width .4s; }
.cupo-ok    { background:#50cd89; }
.cupo-warn  { background:#ffa800; }
.cupo-full  { background:#f1416c; }
.cupo-label { font-size:.8rem; }
.periodo-check { border:1px solid #e0e0e0; border-radius:8px; padding:8px 14px; margin-bottom:6px;
                 cursor:pointer; transition:background .15s; }
.periodo-check:hover { background:#f8f5ff; }
.periodo-check input[type=checkbox]:checked + span { color:#6f42c1; font-weight:700; }
</style>

<div class="d-flex flex-column-fluid">
<div class="container-fluid">

<?php if ($session->getFlashdata('flash_message')): ?>
    <div class="alert alert-success alert-dismissible mb-4">
        <?= esc($session->getFlashdata('flash_message')) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>
<?php if ($session->getFlashdata('flash_message_error')): ?>
    <div class="alert alert-danger alert-dismissible mb-4">
        <?= esc($session->getFlashdata('flash_message_error')) ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php endif; ?>

<div class="row">

    <!-- Panel izquierdo: alumno + cupo -->
    <div class="col-md-4">
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title"><h3 class="card-label">Alumno — Primaria</h3></div>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label class="font-weight-bold">Estudiante <span class="text-danger">*</span></label>
                    <select class="form-control select2" style="width:100%;" id="student_sel" onchange="seleccionarAlumno(this.value)">
                        <option value="">Seleccione un estudiante...</option>
                        <?php foreach ($students_flat as $stu): ?>
                            <option value="<?= $stu['student_id'] ?>"
                                    data-section="<?= esc($stu['nick_name']) ?>"
                                    data-section-id="<?= $stu['section_id'] ?>">
                                <?= esc($stu['name']) ?> — <?= esc($stu['nick_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-size-sm text-muted">Curso</label>
                    <input type="text" id="disp_curso" class="form-control form-control-sm" disabled>
                </div>

                <!-- Cupo trimestral -->
                <div id="cupo_panel" style="display:none;" class="mt-4">
                    <div class="cupo-bar-wrap">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="font-weight-bold font-size-sm">Cupo Trimestral</span>
                            <span class="font-size-sm" id="cupo_texto">— / 9 días</span>
                        </div>
                        <div class="cupo-bar-bg">
                            <div class="cupo-bar-fill cupo-ok" id="cupo_barra" style="width:0%"></div>
                        </div>
                        <div class="d-flex justify-content-between cupo-label text-muted mt-1">
                            <span>Consumido: <strong id="cupo_consumido">0</strong></span>
                            <span>Restante: <strong id="cupo_restante">9</strong> día(s)</span>
                        </div>
                        <div class="cupo-label text-muted mt-1">
                            <small>Salida anticipada consume <strong>½ día</strong> del cupo</small>
                        </div>
                        <div id="cupo_alerta" class="mt-2" style="display:none;"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Panel derecho: formulario -->
    <div class="col-xl-8">
        <form method="POST" action="<?= base_url('secretary/prim_licencias_periodo_create') ?>" id="form_lic">
            <?= csrf_field() ?>
            <input type="hidden" id="student_id" name="student_id">

            <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Nueva Licencia <small class="text-muted">Por Período — Primaria</small></h3>
                    </div>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Medio <span class="text-danger">*</span></label>
                        <div class="col-4">
                            <select class="form-control" id="medio" name="medio" disabled required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($medios as $m): ?>
                                    <option value="<?= $m->medio_id ?>"><?= esc($m->medio) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <label class="col-2 col-form-label font-weight-bold">Fecha Solicitud</label>
                        <div class="col-3">
                            <input type="datetime-local" class="form-control" id="fechaSolicita" name="fechaSolicita" disabled required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Parentesco <span class="text-danger">*</span></label>
                        <div class="col-4">
                            <select class="form-control" id="parentesco" name="parentesco" onchange="fillParent(this.value)" disabled required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($parentescos as $p): ?>
                                    <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <label class="col-2 col-form-label font-weight-bold">Solicitante</label>
                        <div class="col-3">
                            <input type="text" id="solicitante" name="solicitante" class="form-control" disabled required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Motivo <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <select class="form-control" id="motivo" name="motivo" disabled required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($motivos as $mo): ?>
                                    <option value="<?= $mo->motivo_id ?>"><?= esc($mo->motivo) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Detalle</label>
                        <div class="col-9">
                            <input type="text" id="detalle" name="detalle" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Fecha <span class="text-danger">*</span></label>
                        <div class="col-4">
                            <input type="date" class="form-control" id="fecha" name="fecha" disabled required>
                        </div>
                        <label class="col-2 col-form-label font-weight-bold">Hora Salida</label>
                        <div class="col-3">
                            <input type="time" class="form-control" id="hora_salida" name="hora_salida" disabled>
                        </div>
                    </div>

                    <!-- Selección de períodos -->
                    <div class="form-group row" id="div_periodos" style="display:none;">
                        <label class="col-3 col-form-label font-weight-bold">Períodos <span class="text-danger">*</span></label>
                        <div class="col-9">
                            <div id="periodos_container" style="border:1px solid #ebedf3; border-radius:8px; padding:12px; max-height:220px; overflow-y:auto;">
                                <em class="text-muted">Seleccione un estudiante para ver los períodos.</em>
                            </div>
                            <small class="text-muted d-block mt-1">Puede marcar uno o más períodos.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Excepción</label>
                        <div class="col-9 d-flex align-items-center">
                            <label class="mb-0">
                                <input type="checkbox" id="chk_excepcion" name="es_excepcion" value="1" disabled>
                                <span class="ml-2">Marcar como excepción — no consume cupo</span>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="card-footer d-flex align-items-center">
                    <button type="submit" class="btn btn-warning mr-3" id="btn_registrar" disabled>
                        <i class="fas fa-save mr-1"></i> Registrar Licencia por Período
                    </button>
                    <a href="<?= base_url('secretary/prim_licencias') ?>" class="btn btn-secondary">Cancelar</a>
                    <div id="advertencia_cupo" class="ml-auto" style="display:none;">
                        <span class="badge badge-danger px-3 py-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Alumno en límite de 9 días
                        </span>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
</div>
</div>

<script>
$(document).ready(function() { $('#student_sel').select2(); });

var _familyId   = null;
var _sectionId  = null;

function seleccionarAlumno(student_id) {
    if (!student_id) return;

    var opt = document.querySelector('#student_sel option[value="' + student_id + '"]');
    _sectionId = opt ? opt.dataset.sectionId : null;
    document.getElementById('disp_curso').value = opt ? opt.dataset.section : '';
    document.getElementById('student_id').value = student_id;

    var hoy  = new Date();
    var pad  = n => ('0'+n).slice(-2);
    var fecha = hoy.getFullYear()+'-'+pad(hoy.getMonth()+1)+'-'+pad(hoy.getDate());
    var hora  = pad(hoy.getHours())+':'+pad(hoy.getMinutes());
    document.getElementById('fechaSolicita').value = fecha+'T'+hora;
    document.getElementById('fecha').value         = fecha;

    ['medio','fechaSolicita','parentesco','solicitante','motivo','detalle',
     'fecha','hora_salida','chk_excepcion'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.disabled = false;
    });
    document.getElementById('btn_registrar').disabled = false;
    document.getElementById('div_periodos').style.display = '';

    cargarCupo(student_id);
    cargarPeriodos(_sectionId);

    $.post('<?= base_url('server/student_fill') ?>', { student_id: student_id }, function(r) {
        var c = JSON.parse(r);
        if (c.length) _familyId = c[0].family_id;
    });
}

function cargarCupo(student_id) {
    $.post('<?= base_url('secretary/prim_cupo_estudiante') ?>', {
        student_id: student_id,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    }, function(cupo) {
        mostrarCupo(cupo);
    });
}

function mostrarCupo(cupo) {
    var panel = document.getElementById('cupo_panel');
    panel.style.display = '';

    var total    = parseFloat(cupo.total) || 0;
    var restante = parseFloat(cupo.cupo_restante) || 0;
    var pct      = Math.min((total / 9) * 100, 100);

    document.getElementById('cupo_texto').textContent     = total.toFixed(1) + ' / 9 días';
    document.getElementById('cupo_consumido').textContent = total.toFixed(1);
    document.getElementById('cupo_restante').textContent  = restante.toFixed(1);

    var barra = document.getElementById('cupo_barra');
    barra.style.width = pct + '%';
    barra.className   = 'cupo-bar-fill ' + (cupo.limite9 ? 'cupo-full' : (cupo.alerta6 ? 'cupo-warn' : 'cupo-ok'));

    var alerta = document.getElementById('cupo_alerta');
    var adv    = document.getElementById('advertencia_cupo');
    if (cupo.limite9) {
        alerta.innerHTML = '<div class="alert alert-danger py-2 mb-0 font-size-sm"><i class="fas fa-exclamation-triangle mr-1"></i> Alumno en límite de <strong>9 días</strong></div>';
        alerta.style.display = '';
        adv.style.display = '';
    } else if (cupo.alerta6) {
        alerta.innerHTML = '<div class="alert alert-warning py-2 mb-0 font-size-sm"><i class="fas fa-exclamation-circle mr-1"></i> Alumno superó los <strong>6 días</strong> de cupo</div>';
        alerta.style.display = '';
        adv.style.display = 'none';
    } else {
        alerta.style.display = 'none';
        adv.style.display = 'none';
    }
}

function cargarPeriodos(section_id) {
    var cont = document.getElementById('periodos_container');
    cont.innerHTML = '<em class="text-muted">Cargando períodos...</em>';
    $.get('<?= base_url('server/fill_periodos_section') ?>/' + section_id, function(r) {
        cont.innerHTML = '';
        if (!r.length) {
            cont.innerHTML = '<span class="text-muted">No hay períodos para este curso.</span>';
            return;
        }
        r.forEach(function(p) {
            var hi = p.hora_inicio ? p.hora_inicio.substring(0,5) : '';
            var hf = p.hora_fin    ? p.hora_fin.substring(0,5)    : '';
            var div = document.createElement('label');
            div.className = 'periodo-check d-flex align-items-center w-100 mb-1';
            div.innerHTML = '<input type="checkbox" name="periodos[]" value="'+p.periodo_id+'" class="mr-3"> '
                          + '<span>'+p.periodo+' <span class="text-muted font-size-sm">('+hi+' – '+hf+')</span></span>';
            cont.appendChild(div);
        });
    }).fail(function() {
        cont.innerHTML = '<span class="text-danger">Error al cargar períodos.</span>';
    });
}

function fillParent(relationship) {
    if (!_familyId) return;
    if (relationship <= 2) {
        $.get('<?= base_url('server/fill_parent_relationship') ?>/' + _familyId + '/' + relationship, function(r) {
            var c = JSON.parse(r);
            if (c.length) {
                document.getElementById('solicitante').value =
                    c[0].name + ' ' + c[0].lastname1 + ' ' + c[0].lastname2;
            }
        });
    } else {
        document.getElementById('solicitante').value = '';
        document.getElementById('solicitante').focus();
    }
}

document.getElementById('form_lic').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!document.getElementById('student_id').value) {
        alert('Seleccione un estudiante'); return;
    }
    if (!document.getElementById('medio').value) {
        alert('Seleccione el medio de comunicación'); return;
    }
    if (!document.getElementById('fecha').value) {
        alert('Ingrese la fecha de la licencia'); return;
    }
    var sel = document.querySelectorAll('input[name="periodos[]"]:checked');
    if (sel.length === 0) {
        alert('Seleccione al menos un período'); return;
    }

    var btn = document.getElementById('btn_registrar');
    btn.disabled = true;

    var form = document.getElementById('form_lic');
    $.post(form.action, $(form).serialize(), function(res) {
        var r = typeof res === 'string' ? JSON.parse(res) : res;
        if (r.ok) {
            window.location.href = '<?= base_url('secretary/prim_licencias') ?>';
        } else {
            alert(r.msg || 'Error al registrar la licencia por período.');
            btn.disabled = false;
        }
    }).fail(function() {
        alert('Error al comunicarse con el servidor.');
        btn.disabled = false;
    });
});
</script>
