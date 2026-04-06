<script>
(function () {
    var licenciaId = '<?php echo (int)$param1; ?>';
    var baseUrl    = '<?php echo base_url(); ?>';

    $.getJSON(baseUrl + 'index.php/teacher/licencia_get/' + licenciaId, function (d) {
        if (!d || !d.student) return;

        $('#lv-student').text(d.student);
        $('#lv-section').text(d.completo || d.nick_name || '');
        $('#lv-solicitante').text(d.solicitante || '—');
        $('#lv-fecha').text(d.fecha_solicitud || '—');
        $('#lv-medio').text(d.medio || '—');
        $('#lv-motivo').text((d.motivo || '') + (d.detalle ? ' — ' + d.detalle : ''));

        var estado = d.enviado == 1
            ? '<span class="label label-inline label-light-success font-weight-bold px-3">Autorizada</span>'
            : '<span class="label label-inline label-light-warning font-weight-bold px-3">Pendiente</span>';
        $('#lv-estado').html(estado);

        if (d.tipo_id == 1) {
            // Licencia por Día
            $('#lv-tipo').html('<span class="badge badge-primary px-3 py-2">Por Día</span>');
            $('#lv-inicio-label').text('Fecha inicio');
            $('#lv-fin-label').text('Fecha fin');
            $('#lv-inicio').text(d.fecha_inicio || '—');
            $('#lv-fin').text(d.fecha_fin || '—');
            if (d.cantidad_dias) {
                $('#lv-extra').html('<span class="font-weight-bold">' + d.cantidad_dias + '</span> día(s)');
                $('#lv-extra-row').show();
            }
        } else {
            // Licencia por Hora / Período
            $('#lv-tipo').html('<span class="badge badge-warning px-3 py-2">Por Hora</span>');
            $('#lv-inicio-label').text('Fecha');
            $('#lv-fin-label').text('Período(s)');
            $('#lv-inicio').text(d.fecha_periodo || '—');
            $('#lv-fin').text(d.periodos_nombre || '—');
            if (d.hora_salida && d.hora_salida !== '00:00:00') {
                $('#lv-extra').text(d.hora_salida.substring(0, 5));
                $('#lv-extra-row').show();
                $('#lv-extra-label').text('Hora de salida');
            }
        }

        $('#lv-loading').hide();
        $('#lv-body').show();
    }).fail(function () {
        $('#lv-loading').text('Error al cargar la licencia.');
    });
})();
</script>

<div class="modal-header border-0 pb-0">
    <div>
        <h5 class="modal-title font-weight-bolder text-dark">Detalle de Licencia</h5>
        <span class="text-muted font-size-sm">Información completa de la solicitud</span>
    </div>
    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>

<div class="modal-body pt-4 pb-6 px-8">

    <div id="lv-loading" class="text-center py-6">
        <span class="spinner-border text-primary" role="status"></span>
        <p class="text-muted mt-3 mb-0">Cargando...</p>
    </div>

    <div id="lv-body" style="display:none;">

        <!-- Encabezado estudiante -->
        <div class="d-flex align-items-center bg-light-primary rounded p-5 mb-6">
            <span class="svg-icon svg-icon-primary svg-icon-3x mr-4">
                <i class="flaticon2-user text-primary icon-2x"></i>
            </span>
            <div>
                <div class="font-weight-bolder text-dark font-size-h6" id="lv-student"></div>
                <div class="text-muted font-size-sm" id="lv-section"></div>
            </div>
            <div class="ml-auto" id="lv-estado"></div>
        </div>

        <!-- Grid de datos -->
        <div class="row">
            <div class="col-6 mb-4">
                <div class="text-muted font-size-xs text-uppercase font-weight-bold mb-1">Tipo de licencia</div>
                <div id="lv-tipo"></div>
            </div>
            <div class="col-6 mb-4">
                <div class="text-muted font-size-xs text-uppercase font-weight-bold mb-1">Fecha de solicitud</div>
                <div class="font-weight-bold text-dark" id="lv-fecha"></div>
            </div>

            <div class="col-6 mb-4">
                <div class="text-muted font-size-xs text-uppercase font-weight-bold mb-1" id="lv-inicio-label">Inicio</div>
                <div class="font-weight-bold text-dark" id="lv-inicio"></div>
            </div>
            <div class="col-6 mb-4">
                <div class="text-muted font-size-xs text-uppercase font-weight-bold mb-1" id="lv-fin-label">Fin / Período</div>
                <div class="font-weight-bold text-dark" id="lv-fin"></div>
            </div>

            <div class="col-6 mb-4" id="lv-extra-row" style="display:none;">
                <div class="text-muted font-size-xs text-uppercase font-weight-bold mb-1" id="lv-extra-label">Días</div>
                <div class="font-weight-bold text-dark" id="lv-extra"></div>
            </div>

            <div class="col-6 mb-4">
                <div class="text-muted font-size-xs text-uppercase font-weight-bold mb-1">Solicitante</div>
                <div class="font-weight-bold text-dark" id="lv-solicitante"></div>
            </div>

            <div class="col-6 mb-4">
                <div class="text-muted font-size-xs text-uppercase font-weight-bold mb-1">Medio de envío</div>
                <div class="font-weight-bold text-dark" id="lv-medio"></div>
            </div>

            <div class="col-12 mb-2">
                <div class="text-muted font-size-xs text-uppercase font-weight-bold mb-1">Motivo / Detalle</div>
                <div class="font-weight-bold text-dark" id="lv-motivo"></div>
            </div>
        </div>

    </div>
</div>

<div class="modal-footer border-0">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
</div>
