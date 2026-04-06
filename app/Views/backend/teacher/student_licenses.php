<?php
$today = date('Y-m-d');
$activas = [];
$pasadas = [];

foreach ($licencias as $lic) {
    $cmp = !empty($lic->fecha_fin_cmp) ? $lic->fecha_fin_cmp : null;
    if ($cmp && $cmp >= $today) {
        $activas[] = $lic;
    } else {
        $pasadas[] = $lic;
    }
}

function renderLicRow($lic, $baseUrl) {
    $modalidadLabel = ($lic->modalidad === 'dia') ? 'Por Día' : 'Por Hora';
    $modalidadClass = ($lic->modalidad === 'dia') ? 'badge-primary' : 'badge-warning';
    $tipoLabel      = esc($lic->tipo);

    $detalle = esc($lic->detalle);
    $inicio  = esc($lic->inicio);
    $fin     = esc($lic->fin);
    $fecha   = esc($lic->fecha);
    $lid     = (int)$lic->licencias_id;

    $extra = '';
    if ($lic->modalidad === 'dia' && !empty($lic->cantidad_dias)) {
        $extra = '<br><small class="text-muted">' . (int)$lic->cantidad_dias . ' día(s)</small>';
    }

    $modalUrl = $baseUrl . 'index.php/modal/popup/license_view/' . $lid . '/0/0/0/0';

    echo '<tr>';
    echo '<td class="align-middle">';
    echo '<span class="badge ' . $modalidadClass . ' mr-1">' . $modalidadLabel . '</span>';
    echo '<span class="badge badge-light text-dark">' . $tipoLabel . '</span>';
    echo '</td>';
    echo '<td class="align-middle">' . $detalle . '</td>';
    echo '<td class="align-middle">' . $inicio . $extra . '</td>';
    echo '<td class="align-middle">' . $fin . '</td>';
    echo '<td class="align-middle text-muted font-size-sm">' . $fecha . '</td>';
    echo '<td class="align-middle text-right">';
    echo '<button type="button" class="btn btn-sm btn-light-info font-weight-bold" onclick="showAjaxModal(\'' . $modalUrl . '\');">';
    echo '<i class="flaticon2-document pr-1"></i>Ver</button>';
    echo '</td>';
    echo '</tr>';
}
?>

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">

        <!-- Header -->
        <div class="d-flex align-items-center mb-5">
            <a href="javascript:history.back()" class="btn btn-light-primary btn-sm mr-3">
                <i class="flaticon2-left-arrow-1 pr-0"></i> Volver
            </a>
            <div>
                <h4 class="mb-0 font-weight-bolder text-dark">Licencias del Estudiante</h4>
                <?php if (!empty($student_name)): ?>
                    <span class="text-muted font-size-sm"><?php echo esc($student_name); ?></span>
                <?php endif; ?>
            </div>
            <div class="ml-auto">
                <span class="badge badge-success badge-lg px-4 py-2 mr-2 font-size-sm">
                    <i class="flaticon2-check-mark text-white mr-1"></i>
                    Activas: <?php echo count($activas); ?>
                </span>
                <span class="badge badge-secondary badge-lg px-4 py-2 font-size-sm">
                    Pasadas: <?php echo count($pasadas); ?>
                </span>
            </div>
        </div>

        <?php if (empty($licencias)): ?>
            <div class="card card-custom">
                <div class="card-body text-center py-10">
                    <i class="flaticon2-document icon-4x text-muted d-block mb-3"></i>
                    <p class="text-muted">Este estudiante no tiene licencias registradas.</p>
                </div>
            </div>
        <?php else: ?>

        <!-- LICENCIAS ACTIVAS -->
        <div class="card card-custom mb-5">
            <div class="card-header border-0 pt-5 pb-0">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-check-mark text-success"></i>
                    </span>
                    <h3 class="card-label">Licencias Activas
                        <span class="d-block text-muted pt-1 font-size-sm">Licencias vigentes a la fecha</span>
                    </h3>
                </div>
            </div>
            <div class="card-body pt-3">
                <?php if (empty($activas)): ?>
                    <p class="text-muted text-center py-4">No hay licencias activas actualmente.</p>
                <?php else: ?>
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="font-weight-bolder text-dark-50" style="width:180px">Tipo</th>
                            <th class="font-weight-bolder text-dark-50">Motivo / Detalle</th>
                            <th class="font-weight-bolder text-dark-50">Inicio / Fecha</th>
                            <th class="font-weight-bolder text-dark-50">Fin / Período</th>
                            <th class="font-weight-bolder text-dark-50">Solicitada</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activas as $lic): renderLicRow($lic, base_url()); endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- LICENCIAS PASADAS -->
        <div class="card card-custom">
            <div class="card-header border-0 pt-5 pb-0">
                <div class="card-title">
                    <span class="card-icon">
                        <i class="flaticon2-time text-muted"></i>
                    </span>
                    <h3 class="card-label">Licencias Anteriores
                        <span class="d-block text-muted pt-1 font-size-sm">Historial de licencias pasadas</span>
                    </h3>
                </div>
            </div>
            <div class="card-body pt-3">
                <?php if (empty($pasadas)): ?>
                    <p class="text-muted text-center py-4">No hay licencias anteriores registradas.</p>
                <?php else: ?>
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="font-weight-bolder text-dark-50" style="width:180px">Tipo</th>
                            <th class="font-weight-bolder text-dark-50">Motivo / Detalle</th>
                            <th class="font-weight-bolder text-dark-50">Inicio / Fecha</th>
                            <th class="font-weight-bolder text-dark-50">Fin / Período</th>
                            <th class="font-weight-bolder text-dark-50">Solicitada</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-muted">
                        <?php foreach ($pasadas as $lic): renderLicRow($lic, base_url()); endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>

        <?php endif; ?>
    </div>
</div>
<!--end::Entry-->
