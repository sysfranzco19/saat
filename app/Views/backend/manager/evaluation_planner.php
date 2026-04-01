<!-- FullCalendar Styles -->
<link href="<?php echo base_url(); ?>public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />

<style>
.ep-stat-card {
    border-radius: 10px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    margin-bottom: 20px;
}
.ep-stat-icon {
    width: 50px; height: 50px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.ep-stat-value { font-size: 26px; font-weight: 700; line-height: 1; }
.ep-stat-label { font-size: 12px; color: #7e8299; margin-top: 3px; }
.saturation-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
}
.sat-ok     { background: #1BC5BD; }
.sat-warn   { background: #FFA800; }
.sat-danger { background: #F64E60; }
</style>

<div class="card card-custom gutter-b">
    <div class="card-header">
        <h3 class="card-title">
            <span class="card-label font-weight-bolder text-dark">Planificador de Evaluaciones</span>
            <span class="text-muted mt-3 font-weight-bold font-size-sm d-block">Seguimiento de evaluaciones programadas por los docentes</span>
        </h3>
    </div>
    <div class="card-body">

        <?php if (session()->getFlashdata('flash_message')): ?>
            <div class="alert alert-success"><?php echo session()->getFlashdata('flash_message'); ?></div>
        <?php endif; ?>

        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="ep-stat-card bg-light-primary">
                    <div class="ep-stat-icon bg-primary text-white"><i class="la la-calendar-check-o"></i></div>
                    <div>
                        <div class="ep-stat-value text-primary"><?php echo $total_evaluations; ?></div>
                        <div class="ep-stat-label">Total de evaluaciones</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ep-stat-card bg-light-success">
                    <div class="ep-stat-icon bg-success text-white"><i class="la la-clock-o"></i></div>
                    <div>
                        <div class="ep-stat-value text-success"><?php echo $upcoming_count; ?></div>
                        <div class="ep-stat-label">Próximas (7 días)</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ep-stat-card bg-light-warning">
                    <div class="ep-stat-icon bg-warning text-white"><i class="la la-warning"></i></div>
                    <div>
                        <div class="ep-stat-value text-warning"><?php echo $saturated_days; ?></div>
                        <div class="ep-stat-label">Días saturados (3+ exámenes)</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="ep-stat-card bg-light-info">
                    <div class="ep-stat-icon bg-info text-white"><i class="la la-users"></i></div>
                    <div>
                        <div class="ep-stat-value text-info"><?php echo count($sections); ?></div>
                        <div class="ep-stat-label">Cursos con evaluaciones</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-5">
            <div class="col-md-4">
                <label class="font-weight-bold font-size-sm mb-1">Curso</label>
                <select class="form-control" id="filter_section_id" onchange="applyFilters()">
                    <option value="">— Todos los cursos —</option>
                    <?php foreach ($sections as $sec): ?>
                        <option value="<?php echo $sec['section_id']; ?>">
                            <?php echo htmlspecialchars($sec['completo'] ?? $sec['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="font-weight-bold font-size-sm mb-1">Docente</label>
                <select class="form-control" id="filter_teacher_id" onchange="applyFilters()">
                    <option value="">— Todos los docentes —</option>
                    <?php foreach ($teachers as $t): ?>
                        <option value="<?php echo $t['teacher_id']; ?>">
                            <?php echo htmlspecialchars($t['teacher_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="font-weight-bold font-size-sm mb-1">Materia</label>
                <select class="form-control" id="filter_subject_id" onchange="applyFilters()">
                    <option value="">— Todas las materias —</option>
                    <?php foreach ($subjects as $s): ?>
                        <option value="<?php echo $s['subject_id']; ?>">
                            <?php echo htmlspecialchars($s['subject_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="text-right mb-5">
            <button class="btn btn-sm btn-light-danger" onclick="clearFilters()">
                <i class="la la-times"></i> Limpiar filtros
            </button>
        </div>

        <!-- Calendar -->
        <div id="kt_calendar_manager"></div>

        <br>

        <!-- Evaluations Table -->
        <div class="card card-custom gutter-b mt-4">
            <div class="card-header border-0 py-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">Todas las Evaluaciones Programadas</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-sm">Registros de todos los docentes</span>
                </h3>
                <div class="card-toolbar">
                    <div class="input-group input-group-sm" style="width:280px">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="la la-search"></i></span>
                        </div>
                        <input type="text" id="ep_search" class="form-control"
                            placeholder="Buscar título, materia, docente..."
                            oninput="filterTable()">
                    </div>
                </div>
            </div>
            <div class="card-body py-0">
                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center" id="manager_exams_table">
                        <thead>
                            <tr class="text-left">
                                <th style="min-width:130px">Curso</th>
                                <th style="min-width:160px">Docente</th>
                                <th style="min-width:130px">Materia</th>
                                <th style="min-width:150px">Título</th>
                                <th style="min-width:110px">Fecha</th>
                                <th style="min-width:90px">Saturación</th>
                            </tr>
                        </thead>
                        <tbody id="manager_exams_body">
                            <tr><td colspan="6" class="text-center text-muted py-4">Cargando evaluaciones...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- FullCalendar Scripts -->
<script src="<?php echo base_url(); ?>public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>

<script type="text/javascript">
    var managerCalendar;

    function getFilters() {
        return {
            section_id: document.getElementById('filter_section_id').value,
            teacher_id: document.getElementById('filter_teacher_id').value,
            subject_id: document.getElementById('filter_subject_id').value
        };
    }

    function applyFilters() {
        refreshCalendar();
        loadEvaluationsTable();
    }

    function clearFilters() {
        document.getElementById('filter_section_id').value = '';
        document.getElementById('filter_teacher_id').value = '';
        document.getElementById('filter_subject_id').value = '';
        document.getElementById('ep_search').value = '';
        applyFilters();
    }

    function filterTable() {
        var q = document.getElementById('ep_search').value.toLowerCase().trim();
        var rows = document.querySelectorAll('#manager_exams_body tr[data-searchable]');
        var visible = 0;
        rows.forEach(function(row) {
            var text = row.getAttribute('data-searchable').toLowerCase();
            var match = !q || text.indexOf(q) !== -1;
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        var empty = document.getElementById('manager_table_empty');
        if (empty) empty.style.display = visible === 0 ? '' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadEvaluationsTable();

        var calendarEl = document.getElementById('kt_calendar_manager');

        managerCalendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
            header: {
                left:   'prev,next today',
                center: 'title',
                right:  'dayGridMonth,timeGridWeek,listMonth'
            },
            height: 560,
            aspectRatio: 2,
            nowIndicator: true,
            views: {
                dayGridMonth: {
                    buttonText: 'Mes',
                    eventLimit: 4,
                    eventLimitClick: 'popover'
                },
                timeGridWeek: { buttonText: 'Semana', slotEventOverlap: false },
                listMonth:    { buttonText: 'Lista' }
            },
            defaultView: 'listMonth',
            editable: false,
            navLinks: true,
            locale: 'es',
            eventLimit: true,
            eventLimitText: 'más',
            dayPopoverFormat: { month: 'long', day: 'numeric', year: 'numeric' },
            events: function (info, successCallback, failureCallback) {
                $.ajax({
                    url: '<?php echo base_url(); ?>manager/get_manager_calendar_events',
                    type: 'POST',
                    data: getFilters(),
                    success: function (response) {
                        successCallback(response);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error cargando eventos:', error);
                        failureCallback(error);
                    }
                });
            },
            eventRender: function (info) {
                if (info.event.extendedProps && info.event.extendedProps.description) {
                    $(info.el).tooltip({
                        title: info.event.extendedProps.description,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                }
            },
            eventClick: function (info) {
                info.jsEvent.preventDefault();
                var event = info.event;
                var dateStr = event.start.toISOString().split('T')[0];
                var parts   = dateStr.split('-');
                var fmtDate = parts[2] + '/' + parts[1] + '/' + parts[0];

                var html = '<div class="p-3 text-left">' +
                    '<h5 class="font-weight-bold mb-3">' + (event.extendedProps.subject_name || '') + '</h5>' +
                    '<p class="mb-1"><strong>Evaluación:</strong> ' + (event.extendedProps.raw_title || event.title) + '</p>' +
                    '<p class="mb-1"><strong>Docente:</strong> '    + (event.extendedProps.teacher_name || '—') + '</p>' +
                    '<p class="mb-1"><strong>Curso:</strong> '      + (event.extendedProps.section_name || '—') + '</p>' +
                    '<p class="mb-1"><strong>Fecha:</strong> '      + fmtDate + '</p>';

                if (event.extendedProps.description) {
                    html += '<p class="mb-0"><strong>Descripción:</strong> ' + event.extendedProps.description + '</p>';
                }
                html += '</div>';

                Swal.fire({
                    title: 'Detalles de la evaluación',
                    html: html,
                    icon: 'info',
                    confirmButtonText: 'Cerrar',
                    showCloseButton: true
                });
            },
            eventColor: '#3699FF',
            eventTextColor: '#ffffff'
        });

        managerCalendar.render();
    });

    function refreshCalendar() {
        if (managerCalendar) managerCalendar.refetchEvents();
    }

    function loadEvaluationsTable() {
        $.ajax({
            url: '<?php echo base_url(); ?>manager/get_all_evaluations_manager',
            type: 'POST',
            data: getFilters(),
            success: function (response) {
                populateTable(response);
            },
            error: function () {
                console.error('Error cargando tabla de evaluaciones');
            }
        });
    }

    function populateTable(data) {
        var tbody = document.getElementById('manager_exams_body');
        tbody.innerHTML = '';

        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">No hay evaluaciones registradas.</td></tr>';
            return;
        }

        // Group by date+section to calculate saturation
        var satCount = {};
        data.forEach(function(e) {
            var key = e.date + '_' + e.section_id;
            satCount[key] = (satCount[key] || 0) + 1;
        });

        data.forEach(function (e) {
            var key   = e.date + '_' + e.section_id;
            var count = satCount[key];
            var satClass, satLabel;
            if (count <= 1)      { satClass = 'sat-ok';     satLabel = count + ' Ex. OK'; }
            else if (count == 2) { satClass = 'sat-warn';   satLabel = count + ' Ex. Atención'; }
            else                 { satClass = 'sat-danger'; satLabel = count + ' Ex. Saturado'; }

            var searchable = [
                e.nick_name || e.section_name,
                e.teacher_name || '',
                e.subject_name,
                e.title,
                formatDate(e.date)
            ].join(' ');

            var row = '<tr data-searchable="' + escHtml(searchable) + '">' +
                '<td><span class="label label-inline label-light-primary font-weight-bold">' + escHtml(e.nick_name || e.section_name) + '</span></td>' +
                '<td>' + escHtml(e.teacher_name || '—') + '</td>' +
                '<td>' + escHtml(e.subject_name) + '</td>' +
                '<td>' + escHtml(e.title) + '</td>' +
                '<td>' + formatDate(e.date) + '</td>' +
                '<td><span class="saturation-badge ' + satClass + '">' + satLabel + '</span></td>' +
                '</tr>';
            tbody.insertAdjacentHTML('beforeend', row);
        });

        // Empty state row (hidden by default, shown by filterTable when no matches)
        tbody.insertAdjacentHTML('beforeend',
            '<tr id="manager_table_empty" style="display:none">' +
            '<td colspan="6" class="text-center text-muted py-4">Sin resultados para la búsqueda.</td>' +
            '</tr>');

        // Apply current search after reload
        filterTable();
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g,'&amp;')
            .replace(/</g,'&lt;')
            .replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;');
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        var p = dateStr.split('-');
        if (p.length === 3) return p[2] + '/' + p[1] + '/' + p[0];
        return dateStr;
    }
</script>
