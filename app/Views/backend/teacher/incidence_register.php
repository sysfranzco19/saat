<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <!-- Header -->
        <div class="card card-custom gutter-b">
            <div class="card-body py-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="card-label font-weight-bolder text-dark mb-1">Registrar Incidencia</h3>
                        <span class="text-muted font-weight-bold font-size-sm">Selecciona estudiante(s), materia, fecha y tipo</span>
                    </div>
                    <a href="<?= base_url('index.php/teacher/dashboard') ?>" class="btn btn-light-primary font-weight-bold">
                        <i class="fa fa-arrow-left mr-2"></i> Volver
                    </a>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- CARD 1: Buscar estudiante -->
            <div class="col-lg-4">
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder">
                            <span class="card-label">1. Estudiante(s)</span>
                        </h3>
                        <div class="card-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-dark-75">
                                <li class="nav-item">
                                    <a class="nav-link py-2 px-4 active" data-toggle="tab" href="#tab_nombre">Nombre</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link py-2 px-4" data-toggle="tab" href="#tab_curso">Curso</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="tab-content">

                            <!-- Tab: Buscar por nombre (selección individual) -->
                            <div class="tab-pane fade show active" id="tab_nombre">
                                <div class="input-icon">
                                    <input type="text" id="student_search_input" class="form-control form-control-solid"
                                        placeholder="Buscar por nombre..." autocomplete="off">
                                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                                </div>
                                <div id="student_results" class="mt-3" style="max-height: 260px; overflow-y: auto;"></div>
                            </div>

                            <!-- Tab: Buscar por curso (multi-selección con checkboxes) -->
                            <div class="tab-pane fade" id="tab_curso">
                                <select id="course_select" class="form-control form-control-solid mb-3">
                                    <option value="">— Selecciona curso —</option>
                                    <?php
                                    $seen = [];
                                    foreach ($subjects as $sub):
                                        if (in_array($sub['section_id'], $seen)) continue;
                                        $seen[] = $sub['section_id'];
                                    ?>
                                        <option value="<?= $sub['section_id'] ?>">
                                            <?= $sub['completo'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="course_student_list" style="max-height: 260px; overflow-y: auto;"></div>
                                <div id="course_actions" class="d-none mt-3">
                                    <button type="button" class="btn btn-light btn-sm font-weight-bold" id="btn_select_all">
                                        Seleccionar todos
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- Resumen de seleccionados -->
                        <div id="student_selected_card" class="d-none mt-4 p-4 rounded bg-light-primary border border-primary">
                            <div class="d-flex align-items-center">
                                <div id="student_selected_single" class="d-flex align-items-center flex-grow-1">
                                    <div class="symbol symbol-45 symbol-light-primary mr-4">
                                        <span class="symbol-label font-size-h4 font-weight-bold" id="student_initial">A</span>
                                    </div>
                                    <div>
                                        <div class="font-weight-bolder text-dark font-size-lg" id="student_name_display">—</div>
                                        <div class="text-muted font-size-sm" id="student_curso_display">—</div>
                                    </div>
                                </div>
                                <div id="student_selected_multi" class="d-none flex-grow-1">
                                    <div class="font-weight-bolder text-dark font-size-lg">
                                        <span class="label label-xl label-primary label-inline mr-2" id="multi_count">0</span>
                                        estudiantes seleccionados
                                    </div>
                                    <div class="text-muted font-size-sm" id="multi_curso_display">—</div>
                                </div>
                                <button type="button" class="btn btn-icon btn-sm btn-light ml-auto" onclick="clearStudent()">
                                    <i class="ki ki-close icon-xs"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="selected_student_id">
                    </div>
                </div>
            </div>

            <!-- CARD 2: Materia y Fecha -->
            <div class="col-lg-4">
                <div class="card card-custom gutter-b" id="card_step2" style="opacity: 0.4; pointer-events: none;">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder">
                            <span class="card-label">2. Materia y Fecha</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div class="form-group">
                            <label class="font-weight-bold">Materia</label>
                            <select id="subject_select" class="form-control form-control-solid">
                                <option value="">— Selecciona materia —</option>
                                <?php foreach ($subjects as $sub): ?>
                                    <option value="<?= $sub['subject_id'] ?>" data-section="<?= $sub['section_id'] ?>">
                                        <?= $sub['materia'] ?> (<?= $sub['completo'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Fecha</label>
                            <input type="date" id="date_select" class="form-control form-control-solid"
                                max="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>">
                        </div>
                        <input type="hidden" id="selected_period" value="1">
                        <input type="hidden" id="resolved_date_id" value="">
                    </div>
                </div>
            </div>

            <!-- CARD 3: Tipo de incidencia -->
            <div class="col-lg-4">
                <div class="card card-custom gutter-b" id="card_step3" style="opacity: 0.4; pointer-events: none;">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder">
                            <span class="card-label">3. Tipo de Incidencia</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div class="d-flex flex-wrap" id="behavior_grid">
                            <?php foreach ($tipos as $t): ?>
                                <button type="button"
                                    class="btn btn-light-danger font-weight-bold mr-2 mb-2 behavior-btn"
                                    data-id="<?= $t['id'] ?>"
                                    data-name="<?= $t['nombre'] ?>"
                                    data-tipo="<?= $t['tipo'] ?>"
                                    title="<?= $t['nombre'] ?>"
                                    style="font-size: 1.6rem; padding: 8px 12px; line-height: 1;">
                                    <?= $t['icono'] ?>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <div id="selected_behavior_display" class="d-none mt-3 p-3 rounded bg-light-secondary">
                            <span class="font-weight-bolder text-dark" id="selected_behavior_name">—</span>
                        </div>
                        <input type="hidden" id="selected_behavior_id">

                        <div class="form-group mt-4">
                            <label class="font-weight-bold">Observación <span class="text-muted">(opcional)</span></label>
                            <textarea id="observation_input" class="form-control form-control-solid" rows="3"
                                placeholder="Detalles adicionales..."></textarea>
                        </div>

                        <button type="button" id="btn_registrar" class="btn btn-primary font-weight-bolder w-100 py-4 d-none">
                            <i class="fa fa-check mr-2"></i> Registrar Incidencia
                        </button>
                    </div>
                </div>
            </div>

        </div><!-- end row -->

    </div>
</div>

<!-- Modal: Acta de Reunión con el Padre/Tutor -->
<div class="modal fade" id="modalActa" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#f64e60;">
                <h5 class="modal-title text-white font-weight-bolder">
                    <i class="fa fa-exclamation-triangle mr-2 text-white"></i>
                    Acta de Reunión Requerida
                </h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-start mb-5">
                    <i class="fa fa-info-circle mr-3 mt-1 fa-lg text-warning"></i>
                    <div>
                        El estudiante <strong id="modal_student_name">—</strong> tiene
                        <strong id="modal_score">—</strong>/10 pts en esta materia.<br>
                        Para registrar más incidencias negativas debes acreditar la reunión
                        con el padre o tutor.
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Fecha de reunión <span class="text-danger">*</span></label>
                    <input type="date" id="acta_fecha_reunion" class="form-control form-control-solid"
                        max="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold">Acuerdos / Observaciones</label>
                    <textarea id="acta_observacion" class="form-control form-control-solid" rows="3"
                        placeholder="Compromisos establecidos con el padre o tutor..."></textarea>
                </div>
                <div class="form-group mb-0">
                    <label class="font-weight-bold">Acta / Constancia <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="acta_file" accept=".pdf,.jpg,.jpeg,.png">
                        <label class="custom-file-label" for="acta_file">Seleccionar archivo (PDF o imagen)</label>
                    </div>
                    <small class="text-muted">Formatos aceptados: PDF, JPG, PNG. Máx. 5 MB.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="button" id="btn_subir_acta" class="btn btn-danger font-weight-bolder">
                    <i class="fa fa-upload mr-2"></i> Subir Acta y Continuar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    // selectedStudents = array de {id, name, curso, section}
    var selectedStudents    = [];
    var selectedBehaviorId  = null;
    var searchTimeout       = null;
    var currentStudentScore = null;
    var estaBloqueado         = false;
    var pendingBehaviorData   = null;
    var pendingTeacherIdActa  = null;

    // ── Helpers ────────────────────────────────────────────────────
    function enableCard(id) {
        var el = document.getElementById(id);
        el.style.opacity = '1';
        el.style.pointerEvents = 'auto';
    }
    function disableCard(id) {
        var el = document.getElementById(id);
        el.style.opacity = '0.4';
        el.style.pointerEvents = 'none';
    }

    function updateSelectedCard() {
        var card = document.getElementById('student_selected_card');
        if (!selectedStudents.length) {
            card.classList.add('d-none');
            disableCard('card_step2');
            disableCard('card_step3');
            return;
        }
        card.classList.remove('d-none');
        enableCard('card_step2');

        if (selectedStudents.length === 1) {
            var s = selectedStudents[0];
            document.getElementById('student_selected_single').classList.remove('d-none');
            document.getElementById('student_selected_multi').classList.add('d-none');
            document.getElementById('student_initial').textContent = s.name.charAt(0);
            document.getElementById('student_name_display').textContent = s.name;
            document.getElementById('student_curso_display').textContent = s.curso;
        } else {
            document.getElementById('student_selected_single').classList.add('d-none');
            document.getElementById('student_selected_multi').classList.remove('d-none');
            document.getElementById('multi_count').textContent = selectedStudents.length;
            document.getElementById('multi_curso_display').textContent = selectedStudents[0].curso;
        }

        // Filtrar materias por sección
        var sectionId = String(selectedStudents[0].section);
        var select = document.getElementById('subject_select');
        select.value = '';
        Array.from(select.options).forEach(function (opt) {
            if (!opt.value) return;
            opt.hidden = opt.dataset.section !== sectionId;
        });

        checkStep2();
    }

    function addStudent(data) {
        // Evitar duplicados
        if (selectedStudents.find(function(s){ return s.id === data.id; })) return;
        // Si ya hay seleccionados de otra sección, no mezclar
        if (selectedStudents.length && String(selectedStudents[0].section) !== String(data.section)) {
            toastr.warning('Solo puedes seleccionar estudiantes del mismo curso.');
            return;
        }
        selectedStudents.push(data);
        updateSelectedCard();
    }

    window.clearStudent = function () {
        selectedStudents    = [];
        currentStudentScore = null;
        estaBloqueado        = false;
        pendingBehaviorData  = null;
        pendingTeacherIdActa = null;
        var select = document.getElementById('subject_select');
        select.value = '';
        Array.from(select.options).forEach(function (opt) { opt.hidden = false; });
        // Desmarcar checkboxes
        document.querySelectorAll('.student-checkbox').forEach(function(cb){ cb.checked = false; });
        document.getElementById('student_selected_card').classList.add('d-none');
        disableCard('card_step2');
        disableCard('card_step3');
    };

    function fetchScore() {
        if (selectedStudents.length !== 1) {
            currentStudentScore = null;
            estaBloqueado = false;
            return;
        }
        var studentId = selectedStudents[0].id;
        var subjectId = document.getElementById('subject_select').value;
        if (!studentId || !subjectId) { estaBloqueado = false; return; }
        $.getJSON('<?= base_url('index.php/teacher/get_student_score') ?>', {
            student_id: studentId, subject_id: subjectId
        }, function(data) {
            if (data.status === 'success') {
                currentStudentScore = data.nota;
                estaBloqueado       = data.bloqueado;
            }
        });
    }

    // ── Tab Nombre: selección individual ──────────────────────────
    document.getElementById('student_search_input').addEventListener('input', function () {
        clearTimeout(searchTimeout);
        var q = this.value.trim();
        if (q.length < 2) { document.getElementById('student_results').innerHTML = ''; return; }
        searchTimeout = setTimeout(function () {
            $.getJSON('<?= base_url('index.php/teacher/search_students_incidence') ?>', { q: q }, function (data) {
                var html = '';
                if (!data.length) { html = '<p class="text-muted text-center mt-3">Sin resultados</p>'; }
                data.forEach(function (s) {
                    html += '<div class="d-flex align-items-center p-3 mb-1 rounded bg-hover-light cursor-pointer student-name-item" ' +
                        'data-id="' + s.student_id + '" data-name="' + s.nombre + '" data-curso="' + s.completo + '" data-section="' + s.section_id + '">' +
                        '<div class="symbol symbol-35 symbol-light-primary mr-3"><span class="symbol-label font-weight-bold">' + s.nombre.charAt(0) + '</span></div>' +
                        '<div><div class="font-weight-bold text-dark">' + s.nombre + '</div>' +
                        '<div class="text-muted font-size-sm">' + s.completo + '</div></div>' +
                        '</div>';
                });
                document.getElementById('student_results').innerHTML = html;
                document.querySelectorAll('.student-name-item').forEach(function (el) {
                    el.addEventListener('click', function () {
                        clearStudent();
                        addStudent({ id: this.dataset.id, name: this.dataset.name, curso: this.dataset.curso, section: this.dataset.section });
                        document.getElementById('student_results').innerHTML = '';
                        document.getElementById('student_search_input').value = '';
                    });
                });
            });
        }, 300);
    });

    // ── Tab Curso: checkboxes multi-selección ─────────────────────
    document.getElementById('course_select').addEventListener('change', function () {
        var sectionId = this.value;
        var container = document.getElementById('course_student_list');
        var actions   = document.getElementById('course_actions');
        clearStudent();
        if (!sectionId) { container.innerHTML = ''; actions.classList.add('d-none'); return; }

        container.innerHTML = '<p class="text-muted text-center mt-3">Cargando...</p>';

        $.getJSON('<?= base_url('index.php/teacher/search_students_incidence') ?>', { section_id: sectionId }, function (data) {
            if (!data.length) { container.innerHTML = '<p class="text-muted text-center mt-3">Sin estudiantes</p>'; return; }
            var html = '<table class="table table-hover table-sm mb-0"><tbody>';
            data.forEach(function (s) {
                html += '<tr class="cursor-pointer student-row" ' +
                    'data-id="' + s.student_id + '" data-name="' + s.nombre + '" data-curso="' + s.completo + '" data-section="' + s.section_id + '">' +
                    '<td style="width:36px" class="pl-3">' +
                    '<input type="checkbox" class="student-checkbox" style="width:18px;height:18px;cursor:pointer;" ' +
                    'data-id="' + s.student_id + '" data-name="' + s.nombre + '" data-curso="' + s.completo + '" data-section="' + s.section_id + '"></td>' +
                    '<td class="font-weight-bold text-dark py-3">' + s.nombre + '</td>' +
                    '</tr>';
            });
            html += '</tbody></table>';
            container.innerHTML = html;
            actions.classList.remove('d-none');

            function toggleRow(cb) {
                if (cb.checked) {
                    addStudent({ id: cb.dataset.id, name: cb.dataset.name, curso: cb.dataset.curso, section: cb.dataset.section });
                } else {
                    selectedStudents = selectedStudents.filter(function(s){ return s.id !== cb.dataset.id; });
                    updateSelectedCard();
                }
            }

            // Click en fila (excepto en el propio checkbox para no doble-toggle)
            container.querySelectorAll('.student-row').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.classList.contains('student-checkbox')) return;
                    var cb = row.querySelector('.student-checkbox');
                    cb.checked = !cb.checked;
                    toggleRow(cb);
                });
            });

            // Click directo en checkbox
            container.querySelectorAll('.student-checkbox').forEach(function (cb) {
                cb.addEventListener('change', function () { toggleRow(this); });
            });
        });
    });

    // Seleccionar / deseleccionar todos
    document.getElementById('btn_select_all').addEventListener('click', function () {
        var checkboxes = document.querySelectorAll('.student-checkbox');
        var allChecked = Array.from(checkboxes).every(function(cb){ return cb.checked; });
        checkboxes.forEach(function(cb) {
            cb.checked = !allChecked;
            cb.dispatchEvent(new Event('change'));
        });
        this.textContent = allChecked ? 'Seleccionar todos' : 'Deseleccionar todos';
    });

    // ── Materia / Fecha → habilita step 3 ─────────────────────────
    function checkStep2() {
        var subjectId = document.getElementById('subject_select').value;
        var date      = document.getElementById('date_select').value;
        if (subjectId && date && selectedStudents.length) {
            enableCard('card_step3');
            fetchScore();
        } else {
            disableCard('card_step3');
            currentStudentScore = null;
            estaBloqueado = false;
        }
    }
    document.getElementById('subject_select').addEventListener('change', function () {
        currentStudentScore = null;
        estaBloqueado = false;
        checkStep2();
    });
    document.getElementById('date_select').addEventListener('change', checkStep2);

    // ── Selección de comportamiento ────────────────────────────────
    function seleccionarComportamiento(id, name) {
        document.querySelectorAll('.behavior-btn').forEach(function (b) { b.style.outline = ''; });
        var btn = document.querySelector('.behavior-btn[data-id="' + id + '"]');
        if (btn) btn.style.outline = '3px solid #663259';
        selectedBehaviorId = id;
        document.getElementById('selected_behavior_id').value = id;
        document.getElementById('selected_behavior_name').textContent = name;
        document.getElementById('selected_behavior_display').classList.remove('d-none');
        document.getElementById('btn_registrar').classList.remove('d-none');
        var n = selectedStudents.length;
        document.getElementById('btn_registrar').innerHTML =
            '<i class="fa fa-check mr-2"></i> Registrar Incidencia' + (n > 1 ? ' (' + n + ' estudiantes)' : '');
    }

    document.querySelectorAll('.behavior-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tipo = this.dataset.tipo;
            if (tipo === 'negativa' && estaBloqueado && selectedStudents.length === 1) {
                pendingBehaviorData = { id: this.dataset.id, name: this.dataset.name };
                document.getElementById('modal_student_name').textContent = selectedStudents[0].name;
                document.getElementById('modal_score').textContent = currentStudentScore;
                $('#modalActa').modal('show');
                return;
            }
            seleccionarComportamiento(this.dataset.id, this.dataset.name);
        });
    });

    // ── Registrar ──────────────────────────────────────────────────
    document.getElementById('btn_registrar').addEventListener('click', function () {
        if (!selectedStudents.length || !document.getElementById('subject_select').value ||
            !document.getElementById('date_select').value || !selectedBehaviorId) {
            toastr.warning('Completa todos los campos requeridos.');
            return;
        }

        var subjectId   = document.getElementById('subject_select').value;
        var date        = document.getElementById('date_select').value;
        var period      = document.getElementById('selected_period').value;
        var behaviorId  = selectedBehaviorId;
        var observation = document.getElementById('observation_input').value;

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Guardando...';

        // Resolver date_id primero
        $.post('<?= base_url('index.php/teacher/resolve_date_id') ?>', {
            subject_id: subjectId,
            date: date
        }, function (res) {
            if (res.status !== 'success') {
                toastr.error('Error al resolver la fecha.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-check mr-2"></i> Registrar Incidencia';
                return;
            }

            // Registrar para cada estudiante en serie
            var studentIds = selectedStudents.map(function(s){ return s.id; });
            var total   = studentIds.length;
            var done    = 0;
            var errors  = 0;
            var blocked = 0;

            function registrarSiguiente() {
                if (done >= total) {
                    var ok = total - errors - blocked;
                    if (ok > 0) toastr.success('Incidencia registrada para ' + ok + ' estudiante(s).');
                    if (blocked > 0) toastr.warning(blocked + ' estudiante(s) tienen ≤7 pts y requieren acta de reunión con el padre. Regístralos individualmente.');
                    if (errors > 0) toastr.error(errors + ' error(es) al registrar.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-check mr-2"></i> Registrar Incidencia';
                    // Reset
                    document.querySelectorAll('.behavior-btn').forEach(function (b) { b.style.outline = ''; });
                    selectedBehaviorId = null;
                    document.getElementById('selected_behavior_id').value = '';
                    document.getElementById('selected_behavior_display').classList.add('d-none');
                    document.getElementById('btn_registrar').classList.add('d-none');
                    document.getElementById('observation_input').value = '';
                    return;
                }

                btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Guardando ' + (done + 1) + '/' + total + '...';

                $.post('<?= base_url('teacher/register_behavior') ?>', {
                    student_id:  studentIds[done],
                    behavior_id: behaviorId,
                    subject_id:  subjectId,
                    date_id:     res.date_id,
                    custom_date: date,
                    period:      period,
                    observation: observation
                }, function (res2) {
                    if (res2.status === 'needs_acta') {
                        blocked++;
                        if (total === 1) {
                            estaBloqueado = true;
                            currentStudentScore = res2.nota;
                            pendingTeacherIdActa = res2.teacher_id || null;
                            pendingBehaviorData = {
                                id:   document.getElementById('selected_behavior_id').value,
                                name: document.getElementById('selected_behavior_name').textContent
                            };
                            document.getElementById('modal_student_name').textContent = selectedStudents[0].name;
                            document.getElementById('modal_score').textContent = res2.nota;
                            $('#modalActa').modal('show');
                        }
                    } else if (res2.status !== 'success') {
                        errors++;
                    }
                    done++;
                    registrarSiguiente();
                }, 'json').fail(function () {
                    errors++;
                    done++;
                    registrarSiguiente();
                });
            }

            registrarSiguiente();

        }, 'json').fail(function (xhr) {
            toastr.error('Error: ' + xhr.status + ' ' + xhr.responseText.substring(0, 100));
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check mr-2"></i> Registrar Incidencia';
        });
    });

    // ── Modal Acta ─────────────────────────────────────────────────
    document.getElementById('acta_file').addEventListener('change', function () {
        var label = this.nextElementSibling;
        label.textContent = this.files[0] ? this.files[0].name : 'Seleccionar archivo (PDF o imagen)';
    });

    document.getElementById('btn_subir_acta').addEventListener('click', function () {
        var fecha = document.getElementById('acta_fecha_reunion').value;
        var file  = document.getElementById('acta_file').files[0];
        if (!fecha) { toastr.warning('Selecciona la fecha de reunión.'); return; }
        if (!file)  { toastr.warning('Debes adjuntar el acta de reunión.'); return; }
        if (file.size > 5 * 1024 * 1024) { toastr.warning('El archivo no debe superar 5MB.'); return; }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-2"></i> Subiendo...';

        var formData = new FormData();
        formData.append('student_id', selectedStudents[0].id);
        if (pendingTeacherIdActa) {
            formData.append('teacher_id_acta', pendingTeacherIdActa);
        } else {
            formData.append('subject_id', document.getElementById('subject_select').value);
        }
        formData.append('fecha_reunion', fecha);
        formData.append('observacion',   document.getElementById('acta_observacion').value);
        formData.append('acta_file',     file);

        $.ajax({
            url: '<?= base_url('index.php/teacher/upload_acta') ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    estaBloqueado = false;
                    $('#modalActa').modal('hide');
                    document.getElementById('acta_fecha_reunion').value = '';
                    document.getElementById('acta_observacion').value   = '';
                    document.getElementById('acta_file').value          = '';
                    document.querySelector('label[for="acta_file"]').textContent = 'Seleccionar archivo (PDF o imagen)';
                    toastr.success('Acta subida correctamente. Ya puede registrar la incidencia.');
                    pendingTeacherIdActa = null;
                    if (pendingBehaviorData) {
                        seleccionarComportamiento(pendingBehaviorData.id, pendingBehaviorData.name);
                        pendingBehaviorData = null;
                    }
                } else {
                    toastr.error(res.message || 'Error al subir el acta.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-upload mr-2"></i> Subir Acta y Continuar';
                }
            },
            error: function () {
                toastr.error('Error de conexión al subir el acta.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-upload mr-2"></i> Subir Acta y Continuar';
            }
        });
    });

})();
</script>
