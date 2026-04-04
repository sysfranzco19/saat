<!-- FullCalendar Styles -->
<link href="<?php echo base_url(); ?>public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet"
    type="text/css" />

<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">Planificador de Evaluaciones</h3>
        <div class="card-toolbar">
            <a href="javascript:;" class="btn btn-primary font-weight-bolder" onclick="openAddModal()">
                <i class="la la-plus"></i>Planificar Evaluación
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php
        $session = session();
        if ($session->getFlashdata('flash_message')): ?>
            <div class="alert alert-success">
                <?php echo $session->getFlashdata('flash_message'); ?>
            </div>
        <?php endif; ?>
        <?php if ($session->getFlashdata('error_message')): ?>
            <div class="alert alert-danger">
                <?php echo $session->getFlashdata('error_message'); ?>
            </div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="form-group row">
            <label class="col-lg-2 col-form-label text-right">Filtrar por Curso:</label>
            <div class="col-lg-4">
                <select class="form-control" id="filter_section_id" onchange="refreshCalendar()">
                    <option value="">Seleccione un curso para ver evaluaciones...</option>
                    <?php foreach ($sections as $sec_id => $sec_name): ?>
                        <option value="<?php echo $sec_id; ?>"><?php echo $sec_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Calendar Container -->
        <div id="kt_calendar"></div>

        <br><br>

        <!-- My Exams Table -->
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 py-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">Mis Exámenes Programados</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-sm">Lista de evaluaciones creadas por mí
                        para el curso seleccionado</span>
                </h3>
            </div>
            <div class="card-body py-0">
                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>
                            <tr class="text-left">
                                <th style="min-width: 150px">Curso</th>
                                <th style="min-width: 150px">Materia</th>
                                <th style="min-width: 150px">Título</th>
                                <th style="min-width: 150px">Fecha</th>
                                <th style="min-width: 150px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="my_exams_body">
                            <!-- Populated by JS -->
                            <tr>
                                <td colspan="5" class="text-center text-muted">Cargando evaluaciones...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal for New/Edit Evaluation -->
<div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog" aria-labelledby="evaluationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evaluationModalLabel">Planificar Evaluación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="evaluation_form" action="<?php echo base_url(); ?>index.php/teacher/evaluation_save" method="POST"
                    enctype="multipart/form-data">
                    <input type="hidden" name="teacher_id" value="<?php echo $session->get('teacher_id'); ?>">
                    <input type="hidden" name="id" id="evaluation_id"> <!-- For Edit -->

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Curso (Sección):</label>
                        <div class="col-lg-9">
                            <select class="form-control" name="section_id" id="section_id"
                                onchange="filterSubjects(); checkDateSaturation();" required>
                                <option value="">Seleccione un curso...</option>
                                <?php foreach ($sections as $sec_id => $sec_name): ?>
                                    <option value="<?php echo $sec_id; ?>"><?php echo $sec_name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Materia:</label>
                        <div class="col-lg-9">
                            <select class="form-control" name="subject_id" id="subject_id" required disabled>
                                <option value="">Primero seleccione un curso...</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Título:</label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="title" id="title" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Descripción:</label>
                        <div class="col-lg-9">
                            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Fecha:</label>
                        <div class="col-lg-6">
                            <input type="date" class="form-control" name="date" id="exam_date" required
                                onchange="checkDateSaturation()">
                        </div>
                        <div class="col-lg-3">
                            <div id="traffic_light"
                                style="width: 100%; height: 38px; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 5px; color: white; background-color: #eee;">
                                <!-- Indicator -->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div id="alert_message" class="alert" style="display:none"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="save_btn">Guardar Evaluación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar Scripts -->
<script src="<?php echo base_url(); ?>public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>

<script type="text/javascript">
    var calendar;
    var allSubjects = <?php echo json_encode($subjects); ?>;
    var currentTeacherId = <?php echo $session->get('teacher_id'); ?>;

    document.addEventListener('DOMContentLoaded', function () {
        loadMyEvaluations(); // Load table immediately

        var calendarEl = document.getElementById('kt_calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            height: 550, // More compact
            aspectRatio: 2,
            nowIndicator: true,
            views: {
                dayGridMonth: {
                    buttonText: 'month',
                    eventLimit: 3, // Limita a 3 eventos por día
                    eventLimitClick: 'popover' // Muestra popover al hacer clic en "más"
                },
                timeGridWeek: {
                    buttonText: 'week',
                    slotEventOverlap: false // Evita que se solapen
                },
                timeGridDay: {
                    buttonText: 'day',
                    slotEventOverlap: false
                },
                listMonth: {
                    buttonText: 'list',
                    listDayFormat: { weekday: 'long' },
                    listDayAltFormat: { month: 'long', day: 'numeric', year: 'numeric' }
                }
            },
            defaultView: 'dayGridMonth',
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            navLinks: true,
            events: function (info, successCallback, failureCallback) {
                var sectionId = document.getElementById('filter_section_id').value;
                if (!sectionId) {
                    successCallback([]);
                    return;
                }

                $.ajax({
                    url: '<?php echo base_url(); ?>index.php/teacher/get_calendar_events',
                    type: 'POST',
                    data: { section_id: sectionId },
                    success: function (response) {
                        console.log('Eventos recibidos del servidor:', response);

                        // Procesar eventos para FullCalendar
                        var processedEvents = response.map(function (event) {
                            // Añadir tooltip personalizado
                            event.title = event.subject_name + ': ' + event.raw_title;
                            event.extendedProps = event.extendedProps || {};
                            event.extendedProps.description = event.description || '';

                            return event;
                        });

                        console.log('Eventos procesados:', processedEvents);
                        successCallback(processedEvents);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error cargando eventos:', error);
                        failureCallback(error);
                    }
                });
            },
            // Configuración adicional para mejor visualización
            eventRender: function (info) {
                // Agregar tooltip con más información
                if (info.event.extendedProps.description) {
                    $(info.el).tooltip({
                        title: info.event.extendedProps.description,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body'
                    });
                }

                // Resaltar eventos del profesor actual
                if (info.event.extendedProps.teacher_id === currentTeacherId) {
                    info.el.style.border = '2px solid #fff';
                    info.el.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
                }
            },
            // Manejar clic en evento
            eventClick: function (info) {
                info.jsEvent.preventDefault();

                // Mostrar información del evento
                var event = info.event;
                // Formatear fecha para evitar el desfase de zona horaria al mostrarla
                var dateStr = event.start.toISOString().split('T')[0];
                var parts = dateStr.split('-');
                var formattedDate = parts[2] + '/' + parts[1] + '/' + parts[0];

                var html = '<div class="p-3">' +
                    '<h5 class="font-weight-bold">' + event.extendedProps.subject_name + '</h5>' +
                    '<p><strong>Evaluación:</strong> ' + event.extendedProps.raw_title + '</p>' +
                    '<p><strong>Fecha:</strong> ' + formattedDate + '</p>';

                if (event.extendedProps.description) {
                    html += '<p><strong>Descripción:</strong> ' + event.extendedProps.description + '</p>';
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
            // Estilos para eventos
            eventColor: '#3788d8',
            eventTextColor: '#ffffff',
            // Manejar límite de eventos por día
            eventLimitText: "más",
            dayPopoverFormat: { month: 'long', day: 'numeric', year: 'numeric' }
        });

        calendar.render();

        // Función para forzar recarga de eventos
        window.refreshCalendar = function () {
            calendar.refetchEvents();
        };
    });

    // Función auxiliar para formatear fechas
    function formatDateForCalendar(dateString) {
        if (!dateString) return null;

        var date = new Date(dateString);
        // Asegurar que sea a medianoche UTC para eventos de todo el día
        return new Date(Date.UTC(
            date.getFullYear(),
            date.getMonth(),
            date.getDate()
        ));
    }

    function refreshCalendar() {
        if (calendar) {
            calendar.refetchEvents();
        }
    }

    // Modal Functions
    function openAddModal() {
        // Reset form
        document.getElementById('evaluation_form').reset();
        document.getElementById('evaluation_id').value = '';
        document.getElementById('subject_id').disabled = true;
        document.getElementById('traffic_light').style.backgroundColor = '#eee';
        document.getElementById('traffic_light').innerHTML = '';
        document.getElementById('alert_message').style.display = 'none';

        // Auto-select section if filter is active
        var filterSection = document.getElementById('filter_section_id').value;
        if (filterSection) {
            document.getElementById('section_id').value = filterSection;
            filterSubjects();
        }

        $('#evaluationModal').modal('show');
    }

    function editEvaluation(id, title, description, date, section_id, subject_id) {
        // Populate form
        document.getElementById('evaluation_id').value = id;
        document.getElementById('section_id').value = section_id;
        document.getElementById('title').value = title;
        document.getElementById('description').value = description;
        document.getElementById('exam_date').value = date;

        // Handle subject dropdown dependent logic
        filterSubjects();
        document.getElementById('subject_id').value = subject_id;

        // Check saturation
        checkDateSaturation();

        $('#evaluationModal').modal('show');
    }

    function deleteEvaluation(id) {
        if (confirm('¿Está seguro de eliminar esta evaluación?')) {
            window.location.href = '<?php echo base_url(); ?>index.php/teacher/evaluation_delete/' + id;
        }
    }

    // Table Functions
    function loadMyEvaluations() {
        $.ajax({
            url: '<?php echo base_url(); ?>index.php/teacher/get_my_evaluations',
            type: 'GET',
            success: function (response) {
                populateMyExamsTable(response);
            },
            error: function () {
                console.error("Failed to load evaluations");
            }
        });
    }

    function populateMyExamsTable(events) {
        var tbody = document.getElementById('my_exams_body');
        tbody.innerHTML = '';

        if (events.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No tienes exámenes programados.</td></tr>';
            return;
        }

        events.forEach(function (e) {
            var row = `<tr>
                <td><span class="label label-inline label-light-success font-weight-bold">${e.nick_name || e.section_name}</span></td>
                <td>${e.subject_name}</td>
                <td>${e.title}</td>
                <td>${e.date}</td>
                <td nowrap>
                    <a href="javascript:;" onclick="editEvaluation(${e.id}, '${e.title}', '${e.description || ''}', '${e.date}', ${e.section_id}, ${e.subject_id})" class="btn btn-sm btn-light-primary font-weight-bolder mr-2">
                        <i class="la la-edit"></i> Editar
                    </a>
                    <a href="javascript:;" onclick="deleteEvaluation(${e.id})" class="btn btn-sm btn-light-danger font-weight-bolder">
                        <i class="la la-trash"></i> Eliminar
                    </a>
                </td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    // Re-used Logic
    function filterSubjects() {
        var sectionId = document.getElementById('section_id').value;
        var subjectSelect = document.getElementById('subject_id');

        // Store current value if any (for edit mode)
        var currentValue = subjectSelect.value;

        subjectSelect.innerHTML = '<option value="">Seleccione una materia...</option>';
        subjectSelect.disabled = true;

        if (sectionId) {
            subjectSelect.disabled = false;
            var filtered = allSubjects.filter(function (sub) {
                return sub.section_id == sectionId;
            });

            filtered.forEach(function (sub) {
                var option = document.createElement('option');
                option.value = sub.subject_id;
                option.text = sub.materia;
                subjectSelect.appendChild(option);
            });

            // Restore value if it exists in new options (needed for async edit load usually, but here synchronous)
            if (currentValue) {
                // Checking if the value is in the new options would be safer, but for now assuming consistency
            }
        }
    }

    function checkDateSaturation() {
        var date = document.getElementById('exam_date').value;
        var section_id = document.getElementById('section_id').value;
        var light = document.getElementById('traffic_light');
        var btn = document.getElementById('save_btn');
        var msg = document.getElementById('alert_message');

        if (date && section_id) {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/teacher/evaluation_check_date',
                type: 'POST',
                data: { date: date, section_id: section_id },
                success: function (response) {
                    var count = parseInt(response);

                    if (isNaN(count)) {
                        return;
                    }

                    if (count < 2) {
                        light.style.backgroundColor = '#1BC5BD'; // Green
                        light.innerHTML = count + ' Ex.';
                        msg.style.display = 'none';
                    } else if (count == 2) {
                        light.style.backgroundColor = '#FFA800'; // Yellow
                        light.innerHTML = count + ' Ex.';
                        msg.className = 'alert alert-custom alert-warning fade show';
                        msg.innerHTML = '<div class="alert-text">Advertencia: Este sería el 3er examen.</div>';
                        msg.style.display = 'block';
                    } else {
                        light.style.backgroundColor = '#F64E60'; // Red
                        light.innerHTML = count + ' Ex.';
                        msg.className = 'alert alert-custom alert-danger fade show';
                        msg.innerHTML = '<div class="alert-text">Advertencia: Día saturado (3+ exámenes).</div>';
                        msg.style.display = 'block';
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Ajax error:", error);
                }
            });
        } else {
            light.style.backgroundColor = '#eee';
            light.innerHTML = '';
            msg.style.display = 'none';
        }
    }
</script>