<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <!-- Header -->
        <div class="card card-custom gutter-b">
            <div class="card-body py-4">
                <h3 class="card-label font-weight-bolder text-dark mb-1">Historial de Comportamiento</h3>
                <span class="text-muted font-weight-bold font-size-sm">Accede por estudiante o por materia</span>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-primary nav-tabs-line-2x mb-5" role="tablist">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold font-size-lg" data-toggle="tab" href="#tab_estudiante">
                    <i class="fa fa-user mr-2"></i> Por Estudiante
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold font-size-lg" data-toggle="tab" href="#tab_materia">
                    <i class="fa fa-book mr-2"></i> Por Materia
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <!-- TAB 1: Por Estudiante -->
            <div class="tab-pane fade show active" id="tab_estudiante">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="row">
                            <!-- Historial General -->
                            <div class="col-md-6 border-right">
                                <h5 class="font-weight-bolder mb-4">Historial general acumulado de todas las materias</h5>
                                <p class="text-muted font-size-sm mb-4">Todas las incidencias del estudiante sin filtrar por materia.</p>
                                <div class="input-icon" style="position:relative;">
                                    <input type="text" id="search_general" class="form-control form-control-solid"
                                        placeholder="Buscar por nombre o apellido..." autocomplete="off">
                                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                                </div>
                                <div id="results_general" class="shadow"
                                    style="display:none; position:absolute; z-index:200; width:calc(100% - 48px); background:white; border-radius:6px; max-height:280px; overflow-y:auto;"></div>
                            </div>
                            <!-- Historial por Materia -->
                            <div class="col-md-6 mt-6 mt-md-0">
                                <h5 class="font-weight-bolder mb-4">Historial por Materia</h5>
                                <p class="text-muted font-size-sm mb-4">Incidencias del estudiante filtradas por la materia que dictas.</p>
                                <div class="form-group mb-3">
                                    <select id="materia_select_estudiante" class="form-control form-control-solid">
                                        <option value="">— Primero elige materia —</option>
                                        <?php foreach ($subjects as $sub): ?>
                                            <option value="<?= $sub['subject_id'] ?>" data-section="<?= $sub['section_id'] ?>">
                                                <?= $sub['materia'] ?> (<?= $sub['completo'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div id="search_materia_wrapper" style="position:relative; opacity:0.4; pointer-events:none;">
                                    <div class="input-icon">
                                        <input type="text" id="search_materia" class="form-control form-control-solid"
                                            placeholder="Buscar estudiante..." autocomplete="off">
                                        <span><i class="flaticon2-search-1 text-muted"></i></span>
                                    </div>
                                </div>
                                <div id="results_materia" class="shadow"
                                    style="display:none; position:absolute; z-index:200; width:calc(100% - 48px); background:white; border-radius:6px; max-height:280px; overflow-y:auto;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: Por Materia -->
            <div class="tab-pane fade" id="tab_materia">
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title font-weight-bolder">Historial por Materia</h3>
                    </div>
                    <div class="card-body pt-2">
                        <p class="text-muted font-size-sm mb-4">Incidencias del estudiante filtradas por la materia que dictas.</p>
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <select id="materia_tab_select" class="form-control form-control-solid form-control-lg">
                                    <option value="">— Selecciona una materia —</option>
                                    <?php foreach ($subjects as $sub): ?>
                                        <option value="<?= base_url('index.php/teacher/history_students/' . $sub['section_id'] . '/' . $sub['subject_id']) ?>">
                                            <?= $sub['materia'] ?> (<?= $sub['completo'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- end tab-content -->

    </div>
</div>

<script>
(function () {
    var searchTimer = null;

    function makeSearchBox(inputId, resultsId, buildUrl) {
        var input   = document.getElementById(inputId);
        var results = document.getElementById(resultsId);
        if (!input) return;

        input.addEventListener('input', function () {
            clearTimeout(searchTimer);
            var q = this.value.trim();
            if (q.length < 2) { results.style.display = 'none'; results.innerHTML = ''; return; }
            searchTimer = setTimeout(function () {
                $.getJSON('<?= base_url('index.php/teacher/search_students_incidence') ?>', { q: q }, function (data) {
                    if (!data.length) {
                        results.innerHTML = '<div class="p-4 text-muted text-center">Sin resultados</div>';
                        results.style.display = 'block';
                        return;
                    }
                    var html = '';
                    data.forEach(function (s) {
                        var url = buildUrl(s);
                        if (!url) return;
                        html += '<div class="d-flex align-items-center px-5 py-3 border-bottom search-item" style="cursor:pointer;" data-url="' + url + '">' +
                            '<div class="symbol symbol-35 symbol-light-primary mr-4 flex-shrink-0">' +
                            '<span class="symbol-label font-weight-bold">' + s.nombre.charAt(0) + '</span></div>' +
                            '<div><div class="font-weight-bold text-dark">' + s.nombre + '</div>' +
                            '<div class="text-muted font-size-sm">' + s.completo + '</div></div>' +
                            '</div>';
                    });
                    results.innerHTML = html;
                    results.style.display = 'block';
                    results.querySelectorAll('.search-item').forEach(function (el) {
                        el.addEventListener('mouseenter', function () { this.style.background = '#f3f6f9'; });
                        el.addEventListener('mouseleave', function () { this.style.background = ''; });
                        el.addEventListener('click', function () { window.location.href = this.dataset.url; });
                    });
                });
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !results.contains(e.target)) results.style.display = 'none';
        });
    }

    // Historial general → subject_id = 0
    makeSearchBox('search_general', 'results_general', function (s) {
        return '<?= base_url('index.php/teacher/student_profile/') ?>' + s.student_id + '/0';
    });

    // Habilitar buscador por materia solo al elegir materia
    var materiaSelect = document.getElementById('materia_select_estudiante');
    var searchWrapper = document.getElementById('search_materia_wrapper');
    materiaSelect.addEventListener('change', function () {
        if (this.value) {
            searchWrapper.style.opacity = '1';
            searchWrapper.style.pointerEvents = 'auto';
        } else {
            searchWrapper.style.opacity = '0.4';
            searchWrapper.style.pointerEvents = 'none';
            document.getElementById('results_materia').style.display = 'none';
        }
    });

    // Historial por materia → filtra por section_id de la materia elegida
    makeSearchBox('search_materia', 'results_materia', function (s) {
        var subjectId = materiaSelect.value;
        var sectionId = materiaSelect.options[materiaSelect.selectedIndex].dataset.section;
        if (!subjectId || String(s.section_id) !== String(sectionId)) return null;
        return '<?= base_url('index.php/teacher/student_profile/') ?>' + s.student_id + '/' + subjectId;
    });

    // TAB 2: navegar al seleccionar materia
    var materiaTabSelect = document.getElementById('materia_tab_select');
    if (materiaTabSelect) {
        materiaTabSelect.addEventListener('change', function () {
            if (this.value) window.location.href = this.value;
        });
    }

})();
</script>
