<style>
.retraso-counter { background:#f3e8ff; border-radius:10px; padding:14px 18px; }
.retraso-num     { font-size:2.4rem; font-weight:800; color:#6d28d9; line-height:1; }
.retraso-label   { font-size:.8rem; color:#7e57c2; }
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

    <!-- Panel izquierdo: alumno + contador retrasos -->
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

                <!-- Contador retrasos trimestre -->
                <div id="retraso_panel" style="display:none;" class="mt-4">
                    <div class="retraso-counter text-center">
                        <div class="retraso-num" id="retraso_num">0</div>
                        <div class="retraso-label mt-1">retrasos este trimestre</div>
                        <div id="retraso_alerta" class="mt-2" style="display:none;"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Panel derecho: formulario -->
    <div class="col-xl-8">
        <form method="POST" action="<?= base_url('secretary/prim_retrasos_create') ?>" id="form_ret">
            <?= csrf_field() ?>
            <input type="hidden" id="student_id" name="student_id">
            <input type="hidden" id="section_id" name="section_id">

            <div class="card card-custom">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">Registrar Retraso <small class="text-muted">Registro interno — Primaria</small></h3>
                    </div>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Fecha <span class="text-danger">*</span></label>
                        <div class="col-4">
                            <input type="date" class="form-control" id="fecha" name="fecha" disabled required>
                        </div>
                        <label class="col-2 col-form-label font-weight-bold">Hora Entrada</label>
                        <div class="col-3">
                            <input type="time" class="form-control" id="hora_entrada" name="hora_entrada" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Motivo</label>
                        <div class="col-9">
                            <input type="text" id="motivo" name="motivo" class="form-control"
                                   value="Sin información" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-3 col-form-label font-weight-bold">Observación</label>
                        <div class="col-9">
                            <input type="text" id="detalle" name="detalle" class="form-control" placeholder="Opcional" disabled>
                        </div>
                    </div>

                </div>

                <div class="card-footer d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mr-3" id="btn_registrar" disabled>
                        <i class="fas fa-clock mr-1"></i> Registrar Retraso
                    </button>
                    <a href="<?= base_url('secretary/prim_retrasos') ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>

</div>
</div>
</div>

<script>
$(document).ready(function() { $('#student_sel').select2(); });

function seleccionarAlumno(student_id) {
    if (!student_id) return;

    var opt = document.querySelector('#student_sel option[value="' + student_id + '"]');
    document.getElementById('disp_curso').value = opt ? opt.dataset.section   : '';
    document.getElementById('student_id').value = student_id;
    document.getElementById('section_id').value = opt ? opt.dataset.sectionId : '';

    var hoy = new Date();
    var pad = n => ('0'+n).slice(-2);
    document.getElementById('fecha').value        = hoy.getFullYear()+'-'+pad(hoy.getMonth()+1)+'-'+pad(hoy.getDate());
    document.getElementById('hora_entrada').value = pad(hoy.getHours())+':'+pad(hoy.getMinutes());

    document.getElementById('motivo').value = 'Sin información';
    ['fecha','hora_entrada','motivo','detalle'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.disabled = false;
    });
    document.getElementById('btn_registrar').disabled = false;

    cargarContador(student_id);
}

function cargarContador(student_id) {
    $.post('<?= base_url('secretary/prim_retraso_count') ?>', {
        student_id: student_id,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    }, function(r) {
        document.getElementById('retraso_panel').style.display = '';
        document.getElementById('retraso_num').textContent = r.count;

        var alerta = document.getElementById('retraso_alerta');
        if (r.count >= 5) {
            alerta.innerHTML = '<div class="alert alert-warning py-2 mb-0 font-size-sm">'
                + '<i class="fas fa-exclamation-circle mr-1"></i> '
                + '<strong>' + r.count + ' retrasos</strong> este trimestre'
                + '</div>';
            alerta.style.display = '';
        } else {
            alerta.style.display = 'none';
        }
    });
}

document.getElementById('form_ret').addEventListener('submit', function(e) {
    if (!document.getElementById('student_id').value) {
        e.preventDefault(); alert('Seleccione un estudiante'); return;
    }
    if (!document.getElementById('fecha').value) {
        e.preventDefault(); alert('Ingrese la fecha del retraso'); return;
    }
});
</script>
