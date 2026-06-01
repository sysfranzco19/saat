<?php
$todos             = array_merge($primaria, $secundaria);
$total_global      = array_sum(array_column($todos, 'total_incidencias'));
$total_prim        = array_sum(array_column($primaria,   'total_incidencias'));
$total_sec         = array_sum(array_column($secundaria, 'total_incidencias'));
$total_alumnos     = array_sum(array_column($todos, 'alumnos_con_incidencias'));

// Los grupos se determinan por el máximo de incidencias en UNA sola materia
$grupo_critico     = array_values(array_filter($top_estudiantes, fn($e) => $e['max_en_materia'] >= 18));
$grupo_alto        = array_values(array_filter($top_estudiantes, fn($e) => $e['max_en_materia'] >= 10 && $e['max_en_materia'] < 18));
$grupo_moderado    = array_values(array_filter($top_estudiantes, fn($e) => $e['max_en_materia'] >= 5  && $e['max_en_materia'] < 10));
$max_inc           = count($top_estudiantes) > 0 ? max(array_column($top_estudiantes, 'max_en_materia')) : 1;
?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div class="d-flex flex-column-fluid">
<div class="container-fluid">

    <!--begin::Wave banner-->
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom wave wave-animate-slow wave-danger mb-8">
                <div class="card-body">
                    <div class="d-flex align-items-center p-5">
                        <div class="mr-6">
                            <span class="svg-icon svg-icon-danger svg-icon-4x">
                                <i class="flaticon-warning-sign text-danger icon-4x"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="text-dark font-weight-bold mb-1">Control de Incidencias</h2>
                            <div class="text-muted font-size-lg">
                                <?php echo htmlspecialchars($phase_name); ?> &nbsp;·&nbsp;
                                Seguimiento de comportamiento por curso
                            </div>
                        </div>
                        <div class="ml-auto">
                            <a href="<?php echo base_url(); ?>manager/dashboard"
                               class="btn btn-light-danger font-weight-bold">
                                <i class="flaticon2-left-arrow-1 icon-sm mr-1"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Wave banner-->

    <!--begin::Stats-->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-custom bg-danger gutter-b" style="height:150px">
                <div class="card-body">
                    <span class="svg-icon svg-icon-white svg-icon-3x ml-n1">
                        <i class="flaticon-warning-sign text-white icon-3x"></i>
                    </span>
                    <div class="text-inverse-danger font-weight-bolder font-size-h2 mt-3"><?php echo $total_global; ?></div>
                    <div class="text-inverse-danger font-weight-bold font-size-lg mt-1">Total Incidencias</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-custom bg-warning gutter-b" style="height:150px">
                <div class="card-body">
                    <span class="svg-icon svg-icon-white svg-icon-3x ml-n1">
                        <i class="flaticon-open-box text-white icon-3x"></i>
                    </span>
                    <div class="text-inverse-warning font-weight-bolder font-size-h2 mt-3"><?php echo $total_prim; ?></div>
                    <div class="text-inverse-warning font-weight-bold font-size-lg mt-1">Primaria</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-custom bg-primary gutter-b" style="height:150px">
                <div class="card-body">
                    <span class="svg-icon svg-icon-white svg-icon-3x ml-n1">
                        <i class="flaticon-open-box text-white icon-3x"></i>
                    </span>
                    <div class="text-inverse-primary font-weight-bolder font-size-h2 mt-3"><?php echo $total_sec; ?></div>
                    <div class="text-inverse-primary font-weight-bold font-size-lg mt-1">Secundaria</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-custom bg-info gutter-b" style="height:150px">
                <div class="card-body">
                    <span class="svg-icon svg-icon-white svg-icon-3x ml-n1">
                        <i class="flaticon-users-1 text-white icon-3x"></i>
                    </span>
                    <div class="text-inverse-info font-weight-bolder font-size-h2 mt-3"><?php echo $total_alumnos; ?></div>
                    <div class="text-inverse-info font-weight-bold font-size-lg mt-1">Alumnos Involucrados</div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Stats-->

    <!--begin::T2 Stats-->
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom gutter-b border-left-primary" style="border-left:4px solid #3699FF">
                <div class="card-header border-0 pt-5 pb-2">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">
                            <i class="flaticon-warning-sign text-primary mr-2"></i>
                            Segundo Trimestre — Sistema Nuevo
                        </span>
                        <span class="text-muted mt-1 font-weight-bold font-size-sm">incidencia_registro · tiqui0_tiquisaat26</span>
                    </h3>
                </div>
                <div class="card-body pt-3 pb-5">
                    <div class="d-flex flex-wrap align-items-center" style="gap:16px">
                        <!--begin::Stat negativas-->
                        <div class="d-flex align-items-center bg-light-danger rounded p-4" style="min-width:160px">
                            <span class="font-size-h3 mr-3">⚠️</span>
                            <div>
                                <div class="font-weight-bolder font-size-h4 text-danger"><?php echo $t2_total_neg; ?></div>
                                <div class="text-muted font-size-sm font-weight-bold">Negativas</div>
                            </div>
                        </div>
                        <!--begin::Stat positivas-->
                        <div class="d-flex align-items-center bg-light-success rounded p-4" style="min-width:160px">
                            <span class="font-size-h3 mr-3">✅</span>
                            <div>
                                <div class="font-weight-bolder font-size-h4 text-success"><?php echo $t2_total_pos; ?></div>
                                <div class="text-muted font-size-sm font-weight-bold">Positivas</div>
                            </div>
                        </div>
                        <!--begin::Por tipo list-->
                        <div class="flex-grow-1 d-flex flex-wrap" style="gap:8px">
                            <?php foreach ($t2_por_tipo as $t2t):
                                $t2cl = $t2t['type'] === 'negativa' ? 'danger' : ($t2t['type'] === 'positiva' ? 'success' : 'info');
                            ?>
                            <span class="label label-inline label-light-<?php echo $t2cl; ?> font-weight-bold py-3 px-4">
                                <?php echo html_entity_decode($t2t['icon']); ?>
                                <?php echo htmlspecialchars(html_entity_decode($t2t['name'])); ?>
                                <strong class="ml-1"><?php echo $t2t['total']; ?></strong>
                            </span>
                            <?php endforeach; ?>
                            <?php if (empty($t2_por_tipo)): ?>
                                <span class="text-muted font-size-sm">Sin registros en el sistema nuevo.</span>
                            <?php endif; ?>
                        </div>
                        <!--begin::Top negativos-->
                        <?php if (!empty($t2_top_negativos)): ?>
                        <div class="ml-auto">
                            <div class="font-weight-bold font-size-sm text-dark mb-2">Top negativos (T2):</div>
                            <?php foreach (array_slice($t2_top_negativos, 0, 5) as $tn): ?>
                            <div class="d-flex align-items-center mb-1">
                                <span class="label label-danger label-dot mr-2"></span>
                                <span class="font-size-sm font-weight-bold text-dark-75 mr-2"><?php echo htmlspecialchars($tn['alumno']); ?></span>
                                <span class="label label-light-danger label-inline font-size-xs font-weight-bolder"><?php echo $tn['total_neg']; ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::T2 Stats-->

    <!--begin::Search-->
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">
                            <i class="flaticon-search text-primary mr-2"></i>
                            Buscar Estudiante
                        </h3>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="d-flex align-items-center" style="position:relative">
                        <div class="input-group" style="max-width:460px">
                            <div class="input-group-prepend">
                                <span class="input-group-text border-0 bg-light">
                                    <i class="flaticon-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" id="buscarEstudiante"
                                   class="form-control border-0 bg-light font-weight-bold font-size-lg"
                                   placeholder="Escribí el nombre del estudiante..."
                                   autocomplete="off">
                        </div>
                        <button id="btnLimpiarBusqueda" class="btn btn-light-danger font-weight-bold ml-3 d-none">
                            <i class="flaticon2-cross icon-xs mr-1"></i> Limpiar
                        </button>
                        <div id="sugerenciasEstudiante" class="dropdown-menu show shadow"
                             style="display:none!important; position:absolute; top:46px; left:0; z-index:999; min-width:420px; max-height:260px; overflow-y:auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Search-->

    <!--begin::Resultado búsqueda-->
    <div id="resultadoEstudiante" class="d-none"></div>
    <!--end::Resultado búsqueda-->

    <!--begin::Tables-->
    <div class="row">
        <div class="col-xl-12">

            <style>
            th.th-vertical {
                writing-mode: vertical-rl;
                transform: rotate(180deg);
                white-space: nowrap;
                height: 130px;
                vertical-align: bottom;
                padding-bottom: 8px;
                font-size: 11px;
            }
            </style>
            <?php
            $tablas = [];
            if (!empty($primaria))   $tablas[] = ['titulo'=>'Primaria e Inicial',  'lista'=>$primaria,   'color'=>'warning', 'total'=>$total_prim, 'teachers'=>$teachers_prim];
            if (!empty($secundaria)) $tablas[] = ['titulo'=>'Secundaria',           'lista'=>$secundaria, 'color'=>'primary', 'total'=>$total_sec,  'teachers'=>$teachers_sec];
            foreach ($tablas as $tabla):
            ?>
            <div class="card card-custom gutter-b">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">
                            <span class="label label-<?php echo $tabla['color']; ?> label-dot mr-2"></span>
                            <?php echo $tabla['titulo']; ?>
                        </span>
                        <span class="text-muted mt-2 font-weight-bold font-size-sm">
                            <?php echo $tabla['total']; ?> incidencias en <?php echo count($tabla['lista']); ?> cursos
                        </span>
                    </h3>
                </div>
                <div class="card-body pt-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr class="text-muted font-weight-bold">
                                    <th>Curso</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Alumnos</th>
                                    <?php foreach ($tabla['teachers'] as $tc): ?>
                                        <th class="th-vertical text-center"><?php echo htmlspecialchars($tc['teacher_name']); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($tabla['lista'] as $c):
                                $badge = $c['total_incidencias'] >= 10 ? 'danger' : ($c['total_incidencias'] >= 5 ? 'warning' : ($c['total_incidencias'] > 0 ? 'info' : 'secondary'));
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo base_url('manager/incidencias/seccion/' . $c['section_id']); ?>"
                                       class="font-weight-bold text-dark text-hover-primary" style="white-space:nowrap">
                                        <?php echo htmlspecialchars($c['grado']); ?>
                                        <span class="text-muted font-size-xs ml-1"><?php echo htmlspecialchars($c['seccion']); ?></span>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="label label-<?php echo $badge; ?> label-inline font-weight-bolder">
                                        <?php echo $c['total_incidencias']; ?>
                                    </span>
                                </td>
                                <td class="text-center text-muted font-size-sm"><?php echo $c['alumnos_con_incidencias']; ?></td>
                                <?php foreach ($tabla['teachers'] as $tc):
                                    $cnt = $matriz_teachers[$c['section_id']][$tc['teacher_id']] ?? 0;
                                    $col = $cnt >= 10 ? 'danger' : ($cnt >= 5 ? 'warning' : ($cnt > 0 ? 'dark' : 'muted'));
                                ?>
                                <td class="text-center" style="vertical-align:middle">
                                    <span class="font-weight-bold text-<?php echo $col; ?> font-size-sm"><?php echo $cnt; ?></span>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if (empty($primaria) && empty($secundaria)): ?>
            <div class="card card-custom gutter-b">
                <div class="card-body text-center py-12">
                    <i class="flaticon2-check-mark text-success icon-4x mb-4"></i>
                    <h4 class="text-muted">Sin incidencias registradas en la gestión actual.</h4>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
    <!--end::Tables-->

    <!--begin::Charts-->
    <div class="row">
        <div class="col-xl-5">
            <!--begin::Donut-->
            <div class="card card-custom gutter-b">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">Por tipo</span>
                        <span class="text-muted mt-2 font-weight-bold font-size-sm">Distribución de incidencias</span>
                    </h3>
                </div>
                <div class="card-body pt-2 d-flex align-items-center" style="gap:30px">
                    <canvas id="chartTipos" style="max-width:200px; max-height:200px"></canvas>
                    <div class="flex-grow-1">
                        <?php foreach ($por_tipo as $t): ?>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="font-weight-bold font-size-sm text-dark-75">
                                <?php echo html_entity_decode($t['icon']); ?>
                                <?php echo htmlspecialchars(html_entity_decode($t['name'])); ?>
                            </span>
                            <span class="label label-danger label-inline font-weight-bolder">
                                <?php echo $t['total']; ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <!--end::Donut-->
        </div>

        <?php if (!empty($evolucion)): ?>
        <div class="col-xl-7">
            <!--begin::Evolución-->
            <div class="card card-custom gutter-b">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">Evolución</span>
                        <span class="text-muted mt-2 font-weight-bold font-size-sm">Incidencias en el tiempo</span>
                    </h3>
                </div>
                <div class="card-body pt-2">
                    <canvas id="chartEvolucion" height="100"></canvas>
                </div>
            </div>
            <!--end::Evolución-->
        </div>
        <?php endif; ?>
    </div>
    <!--end::Charts-->

    <!--begin::Alertas-->
    <?php
    $grupos = [
        ['lista'=>$grupo_critico,  'titulo'=>'Alerta Crítica',  'desde'=>18, 'color'=>'danger',  'icono'=>'flaticon-warning-sign'],
        ['lista'=>$grupo_alto,     'titulo'=>'Alerta Alta',     'desde'=>10, 'color'=>'warning', 'icono'=>'flaticon-exclamation-1'],
        ['lista'=>$grupo_moderado, 'titulo'=>'Alerta Moderada', 'desde'=>5,  'color'=>'info',    'icono'=>'flaticon-bell'],
    ];
    ?>
    <div class="row">
        <?php foreach ($grupos as $g): ?>
        <div class="col-xl-4">
            <div class="card card-custom gutter-b">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-<?php echo $g['color']; ?>">
                            <i class="<?php echo $g['icono']; ?> text-<?php echo $g['color']; ?> mr-2"></i>
                            <?php echo $g['titulo']; ?>
                        </span>
                        <span class="text-muted mt-2 font-weight-bold font-size-sm">
                            <?php echo $g['desde']; ?>+ incidencias &nbsp;·&nbsp;
                            <span class="label label-<?php echo $g['color']; ?> label-inline font-weight-bolder">
                                <?php echo count($g['lista']); ?>
                            </span> estudiante<?php echo count($g['lista']) !== 1 ? 's' : ''; ?>
                        </span>
                    </h3>
                </div>
                <div class="card-body pt-2" style="min-height:120px">
                    <?php if (empty($g['lista'])): ?>
                        <div class="d-flex flex-column align-items-center py-6 text-muted">
                            <i class="flaticon2-check-mark text-success icon-2x mb-3"></i>
                            <span class="font-weight-bold font-size-sm">Sin alumnos en este nivel</span>
                        </div>
                    <?php else: ?>
                    <div style="max-height:340px; overflow-y:auto; padding-right:4px">
                        <?php foreach ($g['lista'] as $i => $est):
                            $pct   = round(($est['max_en_materia'] / $max_inc) * 100);
                            $badge = strpos(strtolower($est['grado']), 'secundaria') !== false ? 'primary' : 'warning';
                        ?>
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-35 symbol-light-<?php echo $g['color']; ?> flex-shrink-0 mr-3">
                                <span class="symbol-label font-weight-bold">
                                    <?php echo strtoupper(substr($est['alumno'], 0, 1)); ?>
                                </span>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="font-weight-bolder text-dark font-size-sm text-truncate" style="max-width:140px">
                                        <?php echo htmlspecialchars($est['alumno']); ?>
                                    </span>
                                    <span class="label label-<?php echo $g['color']; ?> label-inline font-weight-bolder ml-2 flex-shrink-0"
                                          title="<?php echo $est['max_en_materia']; ?> en <?php echo htmlspecialchars($est['peor_materia']); ?>">
                                        <?php echo $est['max_en_materia']; ?>/mat.
                                    </span>
                                </div>
                                <div class="progress progress-xs mb-1">
                                    <div class="progress-bar bg-<?php echo $g['color']; ?>"
                                         style="width:<?php echo $pct; ?>%"></div>
                                </div>
                                <div class="text-muted font-size-xs d-flex align-items-center flex-wrap" style="gap:4px">
                                    <span class="label label-xs label-<?php echo $badge; ?> label-inline font-weight-bold">
                                        <?php echo htmlspecialchars($est['grado']); ?>
                                    </span>
                                    <span class="text-truncate" style="max-width:100px"><?php echo htmlspecialchars($est['seccion']); ?></span>
                                    <span class="text-dark-50 font-weight-bold text-truncate" style="max-width:110px" title="<?php echo htmlspecialchars($est['peor_materia']); ?>">
                                        · <?php echo htmlspecialchars($est['peor_materia']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <!--end::Alertas-->

</div><!--end::container-fluid-->
</div><!--end::d-flex-->
</div><!--end::content-->

<!--begin::Scripts-->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
(function () {
    /* ---- Donut por tipo ---- */
    const palette = ['#F64E60','#FFA800','#8950FC','#3699FF','#1BC5BD','#181C32','#E4E6EF'];
    const ctxT = document.getElementById('chartTipos');
    if (ctxT) {
        new Chart(ctxT, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_map(fn($t) => html_entity_decode($t['name']), $por_tipo)); ?>,
                datasets: [{ data: <?php echo json_encode(array_column($por_tipo, 'total')); ?>, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
            },
            options: { plugins: { legend: { display: false } }, cutout: '65%' }
        });
    }

    <?php if (!empty($evolucion)): ?>
    /* ---- Línea evolución ---- */
    const ctxE = document.getElementById('chartEvolucion');
    if (ctxE) {
        new Chart(ctxE, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($evolucion, 'fecha')); ?>,
                datasets: [{ label: 'Incidencias', data: <?php echo json_encode(array_column($evolucion, 'total')); ?>,
                    borderColor: '#F64E60', backgroundColor: 'rgba(246,78,96,0.1)',
                    fill: true, tension: 0.4, pointRadius: 4, pointBackgroundColor: '#F64E60' }]
            },
            options: { plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { ticks: { maxRotation: 45, font: { size: 10 } } } } }
        });
    }
    <?php endif; ?>

    /* ---- Buscador de estudiante ---- */
    const baseUrl    = '<?php echo base_url(); ?>';
    const inp        = document.getElementById('buscarEstudiante');
    const drop       = document.getElementById('sugerenciasEstudiante');
    const resultado  = document.getElementById('resultadoEstudiante');
    const btnLimpiar = document.getElementById('btnLimpiarBusqueda');
    let timer = null;

    inp.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();
        if (q.length < 2) { cerrarDrop(); return; }
        timer = setTimeout(() => buscar(q), 300);
    });

    document.addEventListener('click', e => { if (!inp.contains(e.target) && !drop.contains(e.target)) cerrarDrop(); });

    btnLimpiar.addEventListener('click', () => {
        inp.value = '';
        resultado.innerHTML = '';
        resultado.classList.add('d-none');
        btnLimpiar.classList.add('d-none');
        cerrarDrop();
    });

    function cerrarDrop() { drop.style.display = 'none'; drop.innerHTML = ''; }

    function buscar(q) {
        fetch(baseUrl + 'manager/incidencias/search_students?q=' + encodeURIComponent(q))
            .then(r => r.json())
            .then(data => {
                if (!data.length) { cerrarDrop(); return; }
                drop.innerHTML = data.map(s =>
                    `<a class="dropdown-item py-3 border-bottom" href="#" data-id="${s.student_id}" data-nombre="${s.nombre}">
                        <span class="font-weight-bold text-dark d-block">${s.nombre}</span>
                        <small class="text-muted">${s.grado} · ${s.seccion}</small>
                    </a>`
                ).join('');
                drop.style.display = 'block';
                drop.querySelectorAll('a').forEach(a => a.addEventListener('click', e => {
                    e.preventDefault();
                    inp.value = a.dataset.nombre;
                    cerrarDrop();
                    cargarResumen(a.dataset.id);
                }));
            });
    }

    function cargarResumen(id) {
        resultado.innerHTML = `<div class="card card-custom gutter-b"><div class="card-body text-center py-8">
            <div class="spinner-border text-danger" role="status"></div>
            <p class="mt-3 text-muted font-weight-bold">Cargando resumen...</p></div></div>`;
        resultado.classList.remove('d-none');
        btnLimpiar.classList.remove('d-none');

        fetch(baseUrl + 'manager/incidencias/student/' + id)
            .then(r => r.json())
            .then(data => { resultado.innerHTML = data.error ? '<p class="text-danger p-4">Error al cargar datos.</p>' : renderResumen(data); });
    }

    function renderResumen(data) {
        const s     = data.student;
        const nivel = s.grado.toLowerCase().includes('secundaria') ? 'primary' : 'warning';

        const totalT1 = data.total || 0;
        const totalT2 = data.total_t2 || 0;
        const negT1   = (data.por_tipo || []).filter(t => t.tipo_clase === 'negative').reduce((acc, t) => acc + parseInt(t.total), 0);
        const negT2   = (data.por_tipo_t2 || []).filter(t => t.tipo_clase === 'negativa').reduce((acc, t) => acc + parseInt(t.total), 0);
        const colorT1 = negT1 >= 18 ? 'danger' : (negT1 >= 10 ? 'warning' : (negT1 >= 5 ? 'info' : 'success'));
        const colorT2 = negT2 >= 18 ? 'danger' : (negT2 >= 10 ? 'warning' : (negT2 >= 5 ? 'info' : 'success'));

        // T1 badges
        const badgesT1 = (data.por_tipo || []).map(t => {
            const cl = t.tipo_clase === 'negative' ? 'danger' : (t.tipo_clase === 'positive' ? 'success' : 'info');
            return `<span class="label label-inline label-light-${cl} font-weight-bold mr-2 mb-2 py-3 px-4">
                ${dec(t.icon)} ${dec(t.tipo)} <strong class="ml-1">${t.total}</strong>
            </span>`;
        }).join('');

        // T2 badges
        const badgesT2 = (data.por_tipo_t2 || []).map(t => {
            const cl = t.tipo_clase === 'negativa' ? 'danger' : (t.tipo_clase === 'positiva' ? 'success' : 'info');
            return `<span class="label label-inline label-light-${cl} font-weight-bold mr-2 mb-2 py-3 px-4">
                ${dec(t.icon)} ${dec(t.tipo)} <strong class="ml-1">${t.total}</strong>
            </span>`;
        }).join('');

        // T1 rows
        const filasT1 = totalT1
            ? (data.detalle || []).map(d => {
                const cl = d.tipo_clase === 'negative' ? 'danger' : (d.tipo_clase === 'positive' ? 'success' : 'secondary');
                return `<tr>
                    <td class="font-size-sm text-muted">${d.fecha || '—'}</td>
                    <td class="font-weight-bold text-${cl}">${dec(d.icon)} ${dec(d.tipo)}</td>
                    <td class="text-muted font-size-sm">${dec(d.materia)}</td>
                    <td class="text-muted font-size-sm">${d.observation && d.observation !== 'NULL' ? d.observation : '—'}</td>
                </tr>`;
              }).join('')
            : `<tr><td colspan="4" class="text-center text-muted py-5">Sin incidencias en Primer Trimestre.</td></tr>`;

        // T2 rows
        const filasT2 = totalT2
            ? (data.detalle_t2 || []).map(d => {
                const cl = d.tipo_clase === 'negativa' ? 'danger' : (d.tipo_clase === 'positiva' ? 'success' : 'info');
                return `<tr>
                    <td class="font-size-sm text-muted">${d.fecha || '—'}</td>
                    <td class="font-weight-bold text-${cl}">${dec(d.icon)} ${dec(d.tipo)}</td>
                    <td class="text-muted font-size-sm">${dec(d.materia)}</td>
                    <td class="text-muted font-size-sm">${d.observation && d.observation !== 'NULL' ? d.observation : '—'}</td>
                </tr>`;
              }).join('')
            : `<tr><td colspan="4" class="text-center text-muted py-5">Sin incidencias en Segundo Trimestre.</td></tr>`;

        const uid = 'st' + Math.random().toString(36).substr(2, 8);

        return `
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark d-flex align-items-center">
                        <div class="symbol symbol-40 symbol-light-primary mr-3">
                            <span class="symbol-label font-weight-bolder font-size-h5 text-primary">${s.nombre.charAt(0).toUpperCase()}</span>
                        </div>
                        ${s.nombre.trim()}
                    </span>
                    <span class="mt-2">
                        <span class="label label-${nivel} label-inline font-weight-bold mr-2">${s.grado}</span>
                        <span class="text-muted font-size-sm">${s.seccion}</span>
                    </span>
                </h3>
                <div class="card-toolbar">
                    <span class="label label-xl label-light-warning label-inline font-weight-bolder mr-2"
                          style="font-size:1rem; padding:.7rem 1.2rem" title="Primer Trimestre (sistema antiguo)">
                        T1: ${totalT1}
                    </span>
                    <span class="label label-xl label-light-primary label-inline font-weight-bolder"
                          style="font-size:1rem; padding:.7rem 1.2rem" title="Segundo Trimestre (sistema nuevo)">
                        T2: ${totalT2}
                    </span>
                </div>
            </div>
            <div class="card-body pt-3">
                <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x mb-5" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link font-weight-bolder active" data-toggle="tab" href="#${uid}_t1">
                            Primer Trimestre
                            <span class="label label-light-warning label-inline ml-2">${totalT1}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bolder" data-toggle="tab" href="#${uid}_t2">
                            Segundo Trimestre
                            <span class="label label-light-primary label-inline ml-2">${totalT2}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="${uid}_t1">
                        ${badgesT1 ? `<div class="mb-4">${badgesT1}</div>` : ''}
                        <div class="table-responsive" style="max-height:320px; overflow-y:auto">
                            <table class="table table-hover table-sm">
                                <thead class="text-muted font-size-xs text-uppercase">
                                    <tr><th>Fecha</th><th>Tipo</th><th>Materia</th><th>Observación</th></tr>
                                </thead>
                                <tbody>${filasT1}</tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="${uid}_t2">
                        ${badgesT2 ? `<div class="mb-4">${badgesT2}</div>` : ''}
                        <div class="table-responsive" style="max-height:320px; overflow-y:auto">
                            <table class="table table-hover table-sm">
                                <thead class="text-muted font-size-xs text-uppercase">
                                    <tr><th>Fecha</th><th>Tipo</th><th>Materia</th><th>Observación</th></tr>
                                </thead>
                                <tbody>${filasT2}</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    function dec(s) {
        if (!s) return '';
        const t = document.createElement('textarea');
        t.innerHTML = s;
        return t.value;
    }
})();
</script>
<!--end::Scripts-->
