<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Asistencias
                        <span class="text-muted pt-2 font-size-sm d-block">Estado de asistencia por fecha</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <div class="d-flex align-items-center">
                        <label class="font-weight-bold mr-3 mb-0">Fecha:</label>
                        <input type="date" id="assistance_date" class="form-control form-control-sm mr-2" style="width:180px;">
                        <button id="btn_load_assistance" class="btn btn-primary btn-sm font-weight-bold">
                            <i class="la la-search"></i> Cargar
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Leyenda de estados -->
                <div class="mb-5 flex-wrap gap-2" id="legend_box" style="display:none;">
                    <span class="label label-xl label-light-success font-weight-bold mr-3 mb-2 px-4 py-3">Presente</span>
                    <span class="label label-xl label-light-danger font-weight-bold mr-3 mb-2 px-4 py-3">Ausente</span>
                    <span class="label label-xl label-light-primary font-weight-bold mr-3 mb-2 px-4 py-3">Licencia</span>
                    <span class="label label-xl label-light-warning font-weight-bold mr-3 mb-2 px-4 py-3">Retraso</span>
                    <span class="label label-xl label-light font-weight-bold mr-3 mb-2 px-4 py-3">Sin Asistencia</span>
                </div>

                <div id="no_date_msg" class="text-center text-muted py-10 font-size-h6">
                    Seleccione una fecha para ver las asistencias.
                </div>

                <!-- Nav tabs por sección -->
                <div id="sections_tabs" style="display:none;">
                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-brand nav-tabs-bold flex-wrap" id="section_tab_list" role="tablist">
                        <?php foreach ($sections as $i => $sec): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $i === 0 ? 'active' : ''; ?>"
                               data-toggle="tab"
                               href="#tab_sec_<?php echo $sec['section_id']; ?>"
                               role="tab">
                                <?php echo esc($sec['nick_name']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="tab-content mt-5">
                        <?php foreach ($sections as $i => $sec): ?>
                        <div class="tab-pane fade <?php echo $i === 0 ? 'show active' : ''; ?>"
                             id="tab_sec_<?php echo $sec['section_id']; ?>"
                             role="tabpanel">
                            <table class="table table-head-custom table-vertical-center">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Estudiante</th>
                                        <th width="160">Estado</th>
                                        <th width="100">Llegada</th>
                                        <th>Observación</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_<?php echo $sec['section_id']; ?>">
                                    <?php foreach ($sec['students'] as $j => $stu): ?>
                                    <tr>
                                        <td><?php echo $j + 1; ?></td>
                                        <td class="font-weight-bold"><?php echo esc($stu['name']); ?></td>
                                        <td>
                                            <span class="status-badge label label-lg label-light font-weight-bold label-inline"
                                                  data-student="<?php echo $stu['student_id']; ?>">
                                                Sin Asistencia
                                            </span>
                                        </td>
                                        <td>
                                            <span class="arrival-time text-muted font-size-sm"
                                                  data-student="<?php echo $stu['student_id']; ?>">
                                                —
                                            </span>
                                        </td>
                                        <td>
                                            <span class="obs-text text-muted font-size-sm"
                                                  data-student="<?php echo $stu['student_id']; ?>">
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--end::Entry-->

<script>
(function () {
    var STATUS_LABELS = {
        0: { text: 'Ausente',       cls: 'label-light-danger' },
        1: { text: 'Presente',      cls: 'label-light-success' },
        2: { text: 'Licencia',      cls: 'label-light-primary' },
        3: { text: 'Retraso',       cls: 'label-light-warning' },
    };
    var BASE_CLS = 'label label-lg font-weight-bold label-inline';

    function applyAttendance(data) {
        // Reset todos los badges primero
        $('[data-student]').filter('.status-badge').each(function () {
            $(this).attr('class', BASE_CLS + ' label-light').text('Sin Asistencia');
        });
        $('[data-student]').filter('.arrival-time').text('—');
        $('[data-student]').filter('.obs-text').text('');

        $.each(data, function (student_id, rec) {
            var info = STATUS_LABELS[rec.status] || { text: 'Desconocido', cls: 'label-light' };
            $('[data-student="' + student_id + '"].status-badge')
                .attr('class', BASE_CLS + ' ' + info.cls)
                .text(info.text);
            $('[data-student="' + student_id + '"].arrival-time')
                .text(rec.arrival_time || '—');
            $('[data-student="' + student_id + '"].obs-text')
                .text(rec.observation || '');
        });
    }

    function loadDate(date) {
        if (!date) return;
        var btn = $('#btn_load_assistance');
        btn.addClass('spinner spinner-white spinner-right').prop('disabled', true);
        $.ajax({
            url: '<?php echo site_url("secretary/assistance_by_date"); ?>',
            method: 'POST',
            dataType: 'json',
            data: { date: date },
            success: function (data) {
                $('#no_date_msg').hide();
                $('#legend_box').css('display', 'flex');
                $('#sections_tabs').show();
                applyAttendance(data);
            },
            error: function (xhr) {
                $('#no_date_msg').text('Error ' + xhr.status + ': ' + xhr.responseText.substring(0, 120)).show();
                $('#sections_tabs').hide();
            },
            complete: function () {
                btn.removeClass('spinner spinner-white spinner-right').prop('disabled', false);
            }
        });
    }

    // Fecha por defecto: hoy
    var today = new Date().toISOString().split('T')[0];
    $('#assistance_date').val(today);

    $('#btn_load_assistance').on('click', function () {
        loadDate($('#assistance_date').val());
    });
})();
</script>