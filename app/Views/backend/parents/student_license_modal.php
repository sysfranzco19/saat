<style>
.motivo-excepcion { background:#e8fff3; color:#1bc5bd; font-weight:600; }
.cupo-impacto { padding:10px 14px; border-radius:8px; font-size:.9rem; font-weight:600; margin-top:8px; display:none; }
.cupo-impacto.consume  { background:#fff0f0; color:#f64e60; border:1px solid #f64e60; }
.cupo-impacto.excepcion{ background:#e8fff3; color:#1bc5bd; border:1px solid #1bc5bd; }
</style>

<script>
var _tipoLicencia = 0;

function fillFecha(tipo_licencia) {
    _tipoLicencia = parseInt(tipo_licencia);
    if (document.getElementById('student_id').value <= 0) {
        alert('Seleccione un Estudiante o complete los datos');
        return;
    }
    var hoy   = new Date();
    var fecha = hoy.getFullYear() + '-' + (("0"+(hoy.getMonth()+1)).slice(-2)) + '-' + (("0"+hoy.getDate()).slice(-2));
    var hora  = (("0"+hoy.getHours()).slice(-2)) + ':' + (("0"+hoy.getMinutes()).slice(-2));

    document.getElementById('fecha_inicio').value = fecha;
    document.getElementById('fecha_fin').value    = fecha;
    document.getElementById('hora_salida').value  = hora;

    if (_tipoLicencia === 2) {
        document.getElementById('div_fecha_inicio').style.display = 'none';
        document.getElementById('div_fecha_fin').style.display    = 'none';
        document.getElementById('div_hora_salida').style.display  = '';
        document.getElementById('div_hora_fin_clases').style.display = '';
        document.getElementById('fecha_inicio').disabled = true;
        document.getElementById('fecha_fin').disabled    = true;
        document.getElementById('hora_salida').disabled  = false;
        document.getElementById('hora_fin_clases').disabled = false;
    } else {
        document.getElementById('div_fecha_inicio').style.display = '';
        document.getElementById('div_fecha_fin').style.display    = '';
        document.getElementById('div_hora_salida').style.display  = 'none';
        document.getElementById('div_hora_fin_clases').style.display = 'none';
        document.getElementById('fecha_inicio').disabled = false;
        document.getElementById('fecha_fin').disabled    = false;
        document.getElementById('hora_salida').disabled  = true;
        document.getElementById('hora_fin_clases').disabled = true;
    }

    getParents();
    fillMotivos(_tipoLicencia);
}

function fillMotivos(tipo_id) {
    $.ajax({
        url: "<?= base_url('server/fill_motivos') ?>",
        type: "GET",
        data: { tipo_id: tipo_id },
        success: function(resp) {
            var select = document.getElementById('motivo_id');
            select.innerHTML = '<option value="" disabled selected>Seleccione un motivo</option>';
            resp.forEach(function(m) {
                var opt = document.createElement('option');
                opt.value = m.motivo_id;
                opt.textContent = (m.es_excepcion == 1 ? '⭐ ' : '') + m.motivo;
                opt.dataset.excepcion = m.es_excepcion;
                if (m.es_excepcion == 1) opt.className = 'motivo-excepcion';
                select.appendChild(opt);
            });
            actualizarImpactoCupo();
        }
    });
}

function actualizarImpactoCupo() {
    var sel = document.getElementById('motivo_id');
    var box = document.getElementById('cupo_impacto');
    if (!sel || sel.value === '') { box.style.display='none'; return; }
    var opt = sel.options[sel.selectedIndex];
    var esExcep = opt ? opt.dataset.excepcion == 1 : false;

    box.style.display = '';
    if (esExcep) {
        box.className = 'cupo-impacto excepcion';
        box.innerHTML = '<i class="fas fa-shield-alt mr-2"></i>Esta ausencia <strong>NO consumirá cupo</strong> trimestral (Art. 10 — Excepción).';
    } else if (_tipoLicencia === 2) {
        box.className = 'cupo-impacto consume';
        box.innerHTML = '<i class="fas fa-calendar-minus mr-2"></i>Esta licencia por periodos consumirá <strong>½ día o 1 día de cupo</strong> según la duración (Art. 6).';
    } else {
        var ini = document.getElementById('fecha_inicio').value;
        var fin = document.getElementById('fecha_fin').value;
        var dias = 1;
        if (ini && fin) {
            var d1 = new Date(ini), d2 = new Date(fin);
            dias = Math.round((d2-d1)/(1000*60*60*24)) + 1;
        }
        box.className = 'cupo-impacto consume';
        box.innerHTML = '<i class="fas fa-calendar-minus mr-2"></i>Esta ausencia consumirá <strong>' + dias + ' día(s) de cupo</strong> trimestral (Art. 6).';
    }
}

function getParents() {
    $.ajax({
        data: { family_id: "<?= $param4 ?>" },
        url: "<?= base_url('server/parents_fill') ?>",
        type: "post",
        success: function(response) {
            document.getElementById('parents').innerHTML = response;
        }
    });
}

function updateParentText() {
    var select = document.getElementById('parents');
    var opt = select.options[select.selectedIndex];
    document.getElementById('parent_text').value = opt ? opt.text : '';
}

document.addEventListener('DOMContentLoaded', function() { updateParentText(); });
</script>

<!--begin::Modal-->
<div class="modal-header">
    <h5 class="modal-title">Licencia: <?= esc($param2) ?></h5>
    <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
</div>

<form action="<?= base_url('parents/license_save') ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" id="student_id"  name="student_id"  value="<?= $param1 ?>">
    <input type="hidden" id="family_id"   name="family_id"   value="<?= $param4 ?>">
    <input type="hidden" id="parent_text" name="parent_text" value="">

    <div class="modal-body">

        <!-- Tipo de licencia -->
        <div class="form-group">
            <label class="font-weight-bold">Tipo de Licencia <span class="text-danger">*</span></label>
            <select class="form-control" id="tipo" name="tipo" onchange="fillFecha(this.value)" required>
                <option value="" disabled selected>Seleccione una opción</option>
                <option value="1">Por día(s) — ausencia completa</option>
                <option value="2">Licencia por periodos — por horas</option>
            </select>
        </div>

        <!-- Solicitante -->
        <div class="form-group">
            <label class="font-weight-bold">Solicitada por <span class="text-danger">*</span></label>
            <select class="form-control" id="parents" name="parents" required onchange="updateParentText()"></select>
        </div>

        <!-- Motivo -->
        <div class="form-group">
            <label class="font-weight-bold">Motivo <span class="text-danger">*</span></label>
            <select class="form-control" id="motivo_id" name="motivo_id" required onchange="actualizarImpactoCupo()">
                <option value="" disabled selected>Primero seleccione el tipo de licencia</option>
            </select>
            <div id="cupo_impacto" class="cupo-impacto"></div>
        </div>

        <!-- Detalle -->
        <div class="form-group">
            <label class="font-weight-bold">Detalle <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="detalle" placeholder="Descripción breve" required>
        </div>

        <!-- Rango de fechas (tipo_id=1) -->
        <div class="row">
            <div class="col-6 form-group" id="div_fecha_inicio" style="display:none;">
                <label class="font-weight-bold">Fecha inicio <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" disabled onchange="actualizarImpactoCupo()">
            </div>
            <div class="col-6 form-group" id="div_fecha_fin" style="display:none;">
                <label class="font-weight-bold">Fecha fin <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" disabled onchange="actualizarImpactoCupo()">
            </div>
        </div>

        <!-- Licencia por periodos (tipo_id=2) -->
        <div class="row">
            <div class="col-6 form-group" id="div_hora_salida" style="display:none;">
                <label class="font-weight-bold">Hora de salida <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="hora_salida" name="hora_salida" disabled>
                <small class="text-muted">Hora en que el alumno se retira</small>
            </div>
            <div class="col-6 form-group" id="div_hora_fin_clases" style="display:none;">
                <label class="font-weight-bold">Hora fin de clases <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="hora_fin_clases" name="hora_fin_clases" disabled>
                <small class="text-muted">Hora normal de término del día</small>
            </div>
        </div>

        <!-- Nota sobre cupo -->
        <div class="alert alert-light-warning font-size-sm py-2 mb-3">
            <i class="fas fa-info-circle text-warning mr-1"></i>
            Registrar la licencia no justifica la ausencia ni la exime del cupo (Art. 3). Solo se exceptúan
            internaciones hospitalarias, accidentes graves, fallecimiento familiar y embarazo (Art. 10 — marcados con ⭐).
        </div>

        <!-- Comprobante -->
        <div class="form-group">
            <label class="font-weight-bold">Comprobante médico / oficial</label>
            <input type="file" class="form-control" name="comprobante_medico" accept=".jpg,.jpeg,.png,.pdf">
            <small class="text-muted">Requerido para excepciones. Máx. 5 MB.</small>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
    </div>
</form>
<!--end::Modal-->
