<?php
// Ensure arrays are never null
$kpi_notas        = $kpi_notas        ?? [];
$kpi_asistencia   = $kpi_asistencia   ?? [];
$promedio_por_nivel  = $promedio_por_nivel  ?? [];
$evolucion_periodos  = $evolucion_periodos  ?? [];
$top_materias        = $top_materias        ?? [];
$bottom_materias     = $bottom_materias     ?? [];
$alumnos_riesgo      = $alumnos_riesgo      ?? [];
$asistencia_por_nivel = $asistencia_por_nivel ?? [];
$asistencia_por_mes  = $asistencia_por_mes  ?? [];
$ausencias_criticas  = $ausencias_criticas  ?? [];
$carga_docente       = $carga_docente       ?? [];
$distribucion_nivel  = $distribucion_nivel  ?? [];

$jsonFlags = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<div class="container-fluid">

    <!-- =====================================================================
         SECTION 1: RENDIMIENTO ACADÉMICO
    ===================================================================== -->
    <h3 class="text-primary font-weight-bolder mb-5 mt-5">
        &#128202; RENDIMIENTO ACADÉMICO
    </h3>

    <!-- KPI Cards - Rendimiento -->
    <div class="row gutter-b">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card card-custom bg-primary text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">
                        <?php echo isset($kpi_notas['promedio_global']) ? number_format((float)$kpi_notas['promedio_global'], 1) : '—'; ?>
                    </span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">Promedio Global</span>
                    <span class="text-white opacity-50 font-size-sm">Sobre 100 puntos</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card card-custom bg-success text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">
                        <?php echo isset($kpi_notas['pct_aprobados']) ? $kpi_notas['pct_aprobados'] . '%' : '—'; ?>
                    </span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">% Aprobados</span>
                    <span class="text-white opacity-50 font-size-sm">Score &ge; 51</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card card-custom bg-danger text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">
                        <?php echo isset($kpi_notas['pct_reprobados']) ? $kpi_notas['pct_reprobados'] . '%' : '—'; ?>
                    </span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">% Reprobados</span>
                    <span class="text-white opacity-50 font-size-sm">Score &lt; 51</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card card-custom bg-info text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">
                        <?php echo isset($kpi_notas['total_notas']) ? number_format((int)$kpi_notas['total_notas']) : '—'; ?>
                    </span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">Total Evaluaciones</span>
                    <span class="text-white opacity-50 font-size-sm">Registros de notas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1: Promedio por Nivel + Evolución por Período -->
    <div class="row gutter-b">
        <div class="col-xl-6 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Promedio por Nivel Educativo</h3>
                </div>
                <div class="card-body pt-2 pb-5">
                    <canvas id="chartPromedioNivel" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Evolución por Período / Trimestre</h3>
                </div>
                <div class="card-body pt-2 pb-5">
                    <canvas id="chartEvolucion" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2: Top & Bottom Materias -->
    <div class="row gutter-b">
        <div class="col-xl-6 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Top 8 Materias por Promedio</h3>
                </div>
                <div class="card-body pt-2 pb-5">
                    <canvas id="chartTopMaterias" height="260"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Bottom 8 Materias por Promedio</h3>
                </div>
                <div class="card-body pt-2 pb-5">
                    <canvas id="chartBottomMaterias" height="260"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Table: Alumnos en Riesgo Académico -->
    <div class="row gutter-b">
        <div class="col-12 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Alumnos en Riesgo Académico
                        <small class="text-muted ml-2">(promedio &lt; 60)</small>
                    </h3>
                </div>
                <div class="card-body pt-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Alumno</th>
                                    <th>Nivel</th>
                                    <th>Sección</th>
                                    <th>Promedio</th>
                                    <th>Materias Eval.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($alumnos_riesgo)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Sin datos</td>
                                </tr>
                                <?php else: ?>
                                <?php $i = 1; foreach ($alumnos_riesgo as $row): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($row['alumno']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nivel']); ?></td>
                                    <td><?php echo htmlspecialchars($row['seccion']); ?></td>
                                    <td>
                                        <?php
                                        $prom = (float)$row['promedio'];
                                        $badgeClass = $prom < 51 ? 'badge-danger' : 'badge-warning';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?> font-size-sm">
                                            <?php echo number_format($prom, 1); ?>
                                        </span>
                                    </td>
                                    <td><?php echo (int)$row['materias_evaluadas']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="separator separator-dashed separator-border-2 mb-8 mt-8"></div>

    <!-- =====================================================================
         SECTION 2: ASISTENCIA
    ===================================================================== -->
    <h3 class="text-success font-weight-bolder mb-5 mt-5">
        &#128197; ASISTENCIA
    </h3>

    <!-- KPI Cards - Asistencia -->
    <div class="row gutter-b">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card card-custom bg-success text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">
                        <?php echo isset($kpi_asistencia['pct_asistencia']) ? $kpi_asistencia['pct_asistencia'] . '%' : '—'; ?>
                    </span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">Tasa de Asistencia Global</span>
                    <span class="text-white opacity-50 font-size-sm">Presentes / Total</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card card-custom bg-primary text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">
                        <?php echo isset($kpi_asistencia['presentes']) ? number_format((int)$kpi_asistencia['presentes']) : '—'; ?>
                    </span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">Total Presentes</span>
                    <span class="text-white opacity-50 font-size-sm">Registros status = 1</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card card-custom bg-danger text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">
                        <?php echo isset($kpi_asistencia['ausentes']) ? number_format((int)$kpi_asistencia['ausentes']) : '—'; ?>
                    </span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">Total Ausentes</span>
                    <span class="text-white opacity-50 font-size-sm">Registros status = 0</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 mb-5">
            <div class="card card-custom bg-warning text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">
                        <?php echo count($ausencias_criticas); ?>
                    </span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">Total Licencias</span>
                    <span class="text-white opacity-50 font-size-sm">Alumnos con ausencias críticas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row: Asistencia por Nivel + Asistencia por Mes -->
    <div class="row gutter-b">
        <div class="col-xl-6 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Asistencia por Nivel (%)</h3>
                </div>
                <div class="card-body pt-2 pb-5">
                    <canvas id="chartAsistenciaNivel" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Asistencia por Mes</h3>
                </div>
                <div class="card-body pt-2 pb-5">
                    <canvas id="chartAsistenciaMes" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Table: Alumnos con Inasistencias Críticas -->
    <div class="row gutter-b">
        <div class="col-12 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Alumnos con Inasistencias Críticas
                        <small class="text-muted ml-2">(&ge; 3 ausencias)</small>
                    </h3>
                </div>
                <div class="card-body pt-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light-success">
                                <tr>
                                    <th>#</th>
                                    <th>Alumno</th>
                                    <th>Nivel</th>
                                    <th>Sección</th>
                                    <th>Total Ausencias</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($ausencias_criticas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Sin datos</td>
                                </tr>
                                <?php else: ?>
                                <?php $i = 1; foreach ($ausencias_criticas as $row): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($row['alumno']); ?></td>
                                    <td><?php echo htmlspecialchars($row['nivel']); ?></td>
                                    <td><?php echo htmlspecialchars($row['seccion']); ?></td>
                                    <td>
                                        <?php
                                        $aus = (int)$row['total_ausencias'];
                                        $badgeClass = $aus >= 10 ? 'badge-danger' : ($aus >= 5 ? 'badge-warning' : 'badge-info');
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?> font-size-sm"><?php echo $aus; ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="separator separator-dashed separator-border-2 mb-8 mt-8"></div>

    <!-- =====================================================================
         SECTION 3: DOCENTES Y CARGA HORARIA
    ===================================================================== -->
    <h3 class="text-warning font-weight-bolder mb-5 mt-5">
        &#128104;&#8205;&#127979; DOCENTES Y CARGA HORARIA
    </h3>

    <!-- KPI Cards - Docentes -->
    <div class="row gutter-b">
        <div class="col-xl-4 col-lg-4 col-md-6 mb-5">
            <div class="card card-custom bg-primary text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">83</span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">Total Docentes Activos</span>
                    <span class="text-white opacity-50 font-size-sm">Profesores registrados</span>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 mb-5">
            <div class="card card-custom bg-success text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">530</span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">Total Materias</span>
                    <span class="text-white opacity-50 font-size-sm">Asignaturas registradas</span>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 mb-5">
            <div class="card card-custom bg-warning text-white h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center py-8">
                    <span class="font-size-h2 font-weight-bolder text-white">40</span>
                    <span class="font-weight-bold text-white opacity-75 mt-2">Total Secciones</span>
                    <span class="text-white opacity-50 font-size-sm">Cursos activos</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row: Distribución materias por nivel + Top 20 docentes -->
    <div class="row gutter-b">
        <div class="col-xl-6 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Distribución de Materias por Nivel</h3>
                </div>
                <div class="card-body pt-2 pb-5">
                    <canvas id="chartDistribucionNivel" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Top 20 Docentes por N° de Materias</h3>
                </div>
                <div class="card-body pt-2 pb-5">
                    <canvas id="chartCargaDocente" height="350"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Table: Detalle de Carga Docente -->
    <div class="row gutter-b">
        <div class="col-12 mb-5">
            <div class="card card-custom card-shadowless">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title font-weight-bolder text-dark">Detalle de Carga Docente</h3>
                </div>
                <div class="card-body pt-2">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light-warning">
                                <tr>
                                    <th>#</th>
                                    <th>Docente</th>
                                    <th>Materias</th>
                                    <th>Niveles</th>
                                    <th>Horas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($carga_docente)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Sin datos</td>
                                </tr>
                                <?php else: ?>
                                <?php $i = 1; foreach ($carga_docente as $row): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo htmlspecialchars($row['docente']); ?></td>
                                    <td>
                                        <?php
                                        $mat = (int)$row['total_materias'];
                                        $matClass = $mat >= 10 ? 'badge-warning' : 'badge-primary';
                                        ?>
                                        <span class="badge <?php echo $matClass; ?>"><?php echo $mat; ?></span>
                                    </td>
                                    <td><?php echo (int)$row['niveles']; ?></td>
                                    <td><?php echo (int)$row['total_horas']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- /container-fluid -->

<?php
// ---- Prepare data for Chart.js ----

// Chart 1: Promedio por Nivel
$nivelLabels  = json_encode(array_column($promedio_por_nivel, 'nivel'), $jsonFlags);
$nivelPromed  = json_encode(array_column($promedio_por_nivel, 'promedio'), $jsonFlags);
$nivelAprobados = json_encode(array_column($promedio_por_nivel, 'pct_aprobados'), $jsonFlags);

// Chart 2: Evolución por período
$periodoLabels = json_encode(array_column($evolucion_periodos, 'period'), $jsonFlags);
$periodoPromed = json_encode(array_column($evolucion_periodos, 'promedio'), $jsonFlags);
$periodoAprobPct = json_encode(array_column($evolucion_periodos, 'pct_aprobados'), $jsonFlags);

// Chart 3: Top materias
$topLabels  = json_encode(array_column($top_materias, 'materia'), $jsonFlags);
$topValues  = json_encode(array_column($top_materias, 'promedio'), $jsonFlags);

// Chart 4: Bottom materias
$botLabels  = json_encode(array_column($bottom_materias, 'materia'), $jsonFlags);
$botValues  = json_encode(array_column($bottom_materias, 'promedio'), $jsonFlags);

// Chart 5: Asistencia por nivel
$astNivelLabels = json_encode(array_column($asistencia_por_nivel, 'nivel'), $jsonFlags);
$astNivelPct    = json_encode(array_column($asistencia_por_nivel, 'pct'), $jsonFlags);

// Chart 6: Asistencia por mes
$astMesLabels = json_encode(array_column($asistencia_por_mes, 'mes'), $jsonFlags);
$astMesPct    = json_encode(array_column($asistencia_por_mes, 'pct'), $jsonFlags);

// Chart 7: Distribución materias por nivel
$distLabels    = json_encode(array_column($distribucion_nivel, 'nivel'), $jsonFlags);
$distMaterias  = json_encode(array_column($distribucion_nivel, 'total_materias'), $jsonFlags);
$distDocentes  = json_encode(array_column($distribucion_nivel, 'total_docentes'), $jsonFlags);

// Chart 8: Carga docente (top 20)
$docLabels   = json_encode(array_column($carga_docente, 'docente'), $jsonFlags);
$docMaterias = json_encode(array_column($carga_docente, 'total_materias'), $jsonFlags);
?>

<script>
(function() {
    // ---- Chart 1: Promedio por Nivel (Bar) ----
    var ctxNivel = document.getElementById('chartPromedioNivel');
    if (ctxNivel) {
        new Chart(ctxNivel, {
            type: 'bar',
            data: {
                labels: <?php echo $nivelLabels; ?>,
                datasets: [{
                    label: 'Promedio',
                    data: <?php echo $nivelPromed; ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }, {
                    label: '% Aprobados',
                    data: <?php echo $nivelAprobados; ?>,
                    backgroundColor: 'rgba(75, 192, 100, 0.5)',
                    borderColor: 'rgba(75, 192, 100, 1)',
                    borderWidth: 1,
                    type: 'line',
                    yAxisID: 'yPct'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100, title: { display: true, text: 'Promedio' } },
                    yPct: { beginAtZero: true, max: 100, position: 'right', title: { display: true, text: '% Aprobados' }, grid: { drawOnChartArea: false } }
                },
                plugins: { legend: { position: 'top' } }
            }
        });
    }

    // ---- Chart 2: Evolución por Período (Line) ----
    var ctxEvo = document.getElementById('chartEvolucion');
    if (ctxEvo) {
        new Chart(ctxEvo, {
            type: 'line',
            data: {
                labels: <?php echo $periodoLabels; ?>,
                datasets: [{
                    label: 'Promedio',
                    data: <?php echo $periodoPromed; ?>,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.15)',
                    tension: 0.3,
                    fill: true
                }, {
                    label: '% Aprobados',
                    data: <?php echo $periodoAprobPct; ?>,
                    borderColor: 'rgba(75, 192, 100, 1)',
                    backgroundColor: 'rgba(75, 192, 100, 0.10)',
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                },
                plugins: { legend: { position: 'top' } }
            }
        });
    }

    // ---- Chart 3: Top 8 Materias (Horizontal Bar) ----
    var ctxTop = document.getElementById('chartTopMaterias');
    if (ctxTop) {
        new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: <?php echo $topLabels; ?>,
                datasets: [{
                    label: 'Promedio',
                    data: <?php echo $topValues; ?>,
                    backgroundColor: 'rgba(40, 167, 69, 0.75)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: { beginAtZero: true, max: 100 }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    // ---- Chart 4: Bottom 8 Materias (Horizontal Bar) ----
    var ctxBot = document.getElementById('chartBottomMaterias');
    if (ctxBot) {
        new Chart(ctxBot, {
            type: 'bar',
            data: {
                labels: <?php echo $botLabels; ?>,
                datasets: [{
                    label: 'Promedio',
                    data: <?php echo $botValues; ?>,
                    backgroundColor: 'rgba(220, 53, 69, 0.75)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: { beginAtZero: true, max: 100 }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    // ---- Chart 5: Asistencia por Nivel (Bar) ----
    var ctxAstNivel = document.getElementById('chartAsistenciaNivel');
    if (ctxAstNivel) {
        new Chart(ctxAstNivel, {
            type: 'bar',
            data: {
                labels: <?php echo $astNivelLabels; ?>,
                datasets: [{
                    label: '% Asistencia',
                    data: <?php echo $astNivelPct; ?>,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100, title: { display: true, text: '% Asistencia' } }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    // ---- Chart 6: Asistencia por Mes (Line) ----
    var ctxAstMes = document.getElementById('chartAsistenciaMes');
    if (ctxAstMes) {
        new Chart(ctxAstMes, {
            type: 'line',
            data: {
                labels: <?php echo $astMesLabels; ?>,
                datasets: [{
                    label: '% Asistencia',
                    data: <?php echo $astMesPct; ?>,
                    borderColor: 'rgba(40, 167, 69, 1)',
                    backgroundColor: 'rgba(40, 167, 69, 0.15)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                },
                plugins: { legend: { display: false } }
            }
        });
    }

    // ---- Chart 7: Distribución Materias por Nivel (Bar) ----
    var ctxDist = document.getElementById('chartDistribucionNivel');
    if (ctxDist) {
        new Chart(ctxDist, {
            type: 'bar',
            data: {
                labels: <?php echo $distLabels; ?>,
                datasets: [{
                    label: 'Total Materias',
                    data: <?php echo $distMaterias; ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.75)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }, {
                    label: 'Total Docentes',
                    data: <?php echo $distDocentes; ?>,
                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: { legend: { position: 'top' } }
            }
        });
    }

    // ---- Chart 8: Carga Docente Top 20 (Horizontal Bar) ----
    var ctxCarga = document.getElementById('chartCargaDocente');
    if (ctxCarga) {
        new Chart(ctxCarga, {
            type: 'bar',
            data: {
                labels: <?php echo $docLabels; ?>,
                datasets: [{
                    label: 'N° de Materias',
                    data: <?php echo $docMaterias; ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.75)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                scales: {
                    x: { beginAtZero: true }
                },
                plugins: { legend: { display: false } }
            }
        });
    }
})();
</script>
