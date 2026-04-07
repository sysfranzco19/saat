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
                            <?php
                            $allBehaviors = array_merge(
                                $behaviors['negative'] ?? [],
                                $behaviors['positive'] ?? [],
                                $behaviors['neutral']  ?? []
                            );
                            foreach ($allBehaviors as $b):
                                $colorClass = $b['type'] === 'positive' ? 'btn-light-success' : ($b['type'] === 'negative' ? 'btn-light-danger' : 'btn-light-info');
                            ?>
                                <button type="button"
                                    class="btn <?= $colorClass ?> font-weight-bold mr-2 mb-2 behavior-btn"
                                    data-id="<?= $b['id'] ?>"
                                    data-name="<?= htmlspecialchars($b['name'], ENT_QUOTES) ?>"
                                    title="<?= htmlspecialchars($b['name'], ENT_QUOTES) ?>"
                                    style="font-size: 1.6rem; padding: 8px 12px; line-height: 1;">
                                    <?= $b['icon'] ?>
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

<script>
(function () {
    // selectedStudents = array de {id, name, curso, section}
    var selectedStudents   = [];
    var selectedBehaviorId = null;
    var searchTimeout      = null;

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
        selectedStudents = [];
        var select = document.getElementById('subject_select');
        select.value = '';
        Array.from(select.options).forEach(function (opt) { opt.hidden = false; });
        // Desmarcar checkboxes
        document.querySelectorAll('.student-checkbox').forEach(function(cb){ cb.checked = false; });
        document.getElementById('student_selected_card').classList.add('d-none');
        disableCard('card_step2');
        disableCard('card_step3');
    };

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
        } else {
            disableCard('card_step3');
        }
    }
    document.getElementById('subject_select').addEventListener('change', checkStep2);
    document.getElementById('date_select').addEventListener('change', checkStep2);

    // ── Selección de comportamiento ────────────────────────────────
    document.querySelectorAll('.behavior-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.behavior-btn').forEach(function (b) { b.style.outline = ''; });
            this.style.outline = '3px solid #663259';
            selectedBehaviorId = this.dataset.id;
            document.getElementById('selected_behavior_id').value = selectedBehaviorId;
            document.getElementById('selected_behavior_name').textContent = this.dataset.name;
            document.getElementById('selected_behavior_display').classList.remove('d-none');
            document.getElementById('btn_registrar').classList.remove('d-none');
            // Actualizar texto del botón
            var n = selectedStudents.length;
            document.getElementById('btn_registrar').innerHTML =
                '<i class="fa fa-check mr-2"></i> Registrar Incidencia' + (n > 1 ? ' (' + n + ' estudiantes)' : '');
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
            var total = studentIds.length;
            var done  = 0;
            var errors = 0;

            function registrarSiguiente() {
                if (done >= total) {
                    if (errors === 0) {
                        toastr.success('Incidencia registrada para ' + total + ' estudiante(s).');
                    } else {
                        toastr.warning('Registrado con ' + errors + ' error(es).');
                    }
                    btn.disabled = false;
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

                $.post('<?= base_url('index.php/teacher/register_behavior') ?>', {
                    student_id:  studentIds[done],
                    behavior_id: behaviorId,
                    subject_id:  subjectId,
                    date_id:     res.date_id,
                    custom_date: date,
                    period:      period,
                    observation: observation
                }, function (res2) {
                    if (res2.status !== 'success') errors++;
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

})();
</script>
