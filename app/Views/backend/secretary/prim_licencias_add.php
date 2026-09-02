<style>
.cupo-bar-wrap { background:#f3f0ff; border-radius:10px; padding:14px 18px; }
.cupo-bar-bg   { background:#e0e0e0; border-radius:6px; height:14px; overflow:hidden; margin:8px 0; }
.cupo-bar-fill { height:14px; border-radius:6px; transition:width .4s; }
.cupo-ok    { background:#50cd89; }
.cupo-warn  { background:#ffa800; }
.cupo-full  { background:#f1416c; }
.cupo-label { font-size:.8rem; }
.badge-excep-form { background:#e8fff3; color:#1bc5bd; border-radius:10px; padding:3px 10px; font-size:.78rem; font-weight:700; }
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
                                    data-name="<?= esc($stu['name']) ?>">
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
                            <span>Consumido: <strong id="cupo_consumido">0</strong> día(s)</span>
                            <span>Restante: <strong id="cupo_restante">9</strong> día(s)</span>
                        </div>
                        <div id="cupo_alerta" class="mt-2" style="display:none;"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Panel derecho: formulario -->
    <div class="col-xl-8">
        <form method="POST" action="<?= base_url('secretary/prim_licencias_create') ?>" id="form_lic">
            <?= csrf_field() ?>
            <input type="hidden" id="student_id" name="student_id">

            <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Nueva Licencia <small class="text-muted">Por día(s) — Primaria</small></h3>
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
                        <label class="col-3 col-form-label font-weight-bold">Fecha Inicio <span class="text-danger">*</span></label>
                        <div class="col-3">
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" onchange="calcularDias()" disabled required>
                        </div>
                        <label class="col-2 col-form-label font-weight-bold">Fecha Fin</label>
                        <div class="col-3">
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" onblur="calcularDias()" disabled required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Días</label>
                        <div class="col-9 d-flex align-items-center flex-wrap gap-3">
                            <label class="mb-0 mr-4">
                                <input type="checkbox" id="sabados" checked onchange="calcularDias()" disabled>
                                Contar Sábados
                            </label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" value="1" min="1" style="width:100px;" disabled required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Excepción</label>
                        <div class="col-9 d-flex align-items-center">
                            <label class="mb-0">
                                <input type="checkbox" id="chk_excepcion" name="es_excepcion" value="1" disabled onchange="toggleExcepcion(this)">
                                <span class="ml-2">Marcar como excepción (internación, accidente, etc.) — no consume cupo</span>
                            </label>
                        </div>
                    </div>

                </div>

                <div class="card-footer d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-3" id="btn_registrar" disabled>
                        <i class="fas fa-save mr-1"></i> Registrar Licencia
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

var _familyId = null;

function seleccionarAlumno(student_id) {
    if (!student_id) return;

    var opt = document.querySelector('#student_sel option[value="' + student_id + '"]');
    document.getElementById('disp_curso').value = opt ? opt.dataset.section : '';
    document.getElementById('student_id').value = student_id;

    // Fecha/hora actual
    var hoy  = new Date();
    var pad  = n => ('0'+n).slice(-2);
    var fecha = hoy.getFullYear()+'-'+pad(hoy.getMonth()+1)+'-'+pad(hoy.getDate());
    var hora  = pad(hoy.getHours())+':'+pad(hoy.getMinutes());
    document.getElementById('fechaSolicita').value = fecha+'T'+hora;
    document.getElementById('fecha_inicio').value  = fecha;
    document.getElementById('fecha_fin').value     = fecha;

    // Habilitar campos
    ['medio','fechaSolicita','parentesco','solicitante','motivo','detalle',
     'fecha_inicio','fecha_fin','sabados','cantidad','chk_excepcion'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.disabled = false;
    });
    document.getElementById('btn_registrar').disabled = false;

    // Cargar cupo vía AJAX
    cargarCupo(student_id);

    // Cargar datos de familia para autocompletar solicitante
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

    var total     = parseFloat(cupo.total) || 0;
    var restante  = parseFloat(cupo.cupo_restante) || 0;
    var pct       = Math.min((total / 9) * 100, 100);

    document.getElementById('cupo_texto').textContent    = total.toFixed(1) + ' / 9 días';
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

function calcularDias() {
    var fi = new Date(document.getElementById('fecha_inicio').value);
    var ff = new Date(document.getElementById('fecha_fin').value);
    if (ff < fi) { alert('La fecha fin no puede ser anterior a la fecha inicio'); return; }
    var contarSab = document.getElementById('sabados').checked;
    var dias = 0, cur = new Date(fi);
    while (cur <= ff) {
        var dow = cur.getDay();
        if (dow !== 0 && (contarSab || dow !== 6)) dias++;
        cur.setDate(cur.getDate() + 1);
    }
    document.getElementById('cantidad').value = dias > 0 ? dias : 1;
}

function toggleExcepcion(chk) {
    document.getElementById('advertencia_cupo').style.display = 'none';
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
    if (!document.getElementById('student_id').value) {
        e.preventDefault(); alert('Seleccione un estudiante'); return;
    }
    if (!document.getElementById('medio').value) {
        e.preventDefault(); alert('Seleccione el medio de comunicación'); return;
    }
    if (!document.getElementById('fecha_inicio').value) {
        e.preventDefault(); alert('Ingrese la fecha de inicio'); return;
    }
});
</script>
