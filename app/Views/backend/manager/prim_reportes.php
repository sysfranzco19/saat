<div class="d-flex flex-column-fluid">
<div class="container-fluid pb-8">

    <!-- Header -->
    <div class="card card-custom mb-6"
         style="background:linear-gradient(135deg,#1bc5bd,#0a8c86);border-radius:12px;">
        <div class="card-body p-5 d-flex align-items-center">
            <div>
                <h3 class="text-white font-weight-bolder mb-1">📁 Reportes — Primaria</h3>
                <span class="text-white font-size-sm" style="opacity:.85;">
                    3ro a 6to &nbsp;·&nbsp; <strong><?= esc($phase_name) ?></strong>
                    &nbsp;(<?= date('d-m-Y', strtotime($phase_ini)) ?> → <?= date('d-m-Y', strtotime($phase_fin)) ?>)
                </span>
            </div>
            <div class="ml-auto">
                <span class="svg-icon svg-icon-white svg-icon-4x">
                    <i class="fas fa-file-excel fa-3x text-white" style="opacity:.6;"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- Reporte 1: Asistencia por Alumno -->
        <div class="col-md-6 mb-6">
            <div class="card card-custom shadow-sm h-100">
                <div class="card-header border-0 pt-5" style="border-left:4px solid #3699ff;">
                    <h5 class="card-label font-weight-bolder">
                        <i class="fas fa-user-check text-primary mr-2"></i>Asistencia por Alumno
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted font-size-sm mb-4">
                        Historial de asistencia diaria de un alumno en el rango seleccionado.
                    </p>
                    <form method="POST" action="<?= base_url('manager/prim_reportes_xlsx') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="tipo" value="asistencia_alumno">
                        <div class="form-group">
                            <label class="font-weight-bold font-size-sm">Alumno</label>
                            <select name="student_id" class="form-control" required>
                                <option value="">— Seleccionar alumno —</option>
                                <?php foreach ($sections as $sec): ?>
                                    <optgroup label="<?= esc($sec['nick_name']) ?>">
                                    <?php foreach ($grouped[$sec['section_id']] ?? [] as $stu): ?>
                                        <option value="<?= $stu['student_id'] ?>"><?= esc($stu['name']) ?></option>
                                    <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="font-weight-bold font-size-sm">Desde</label>
                                <input type="date" name="fecha_ini" class="form-control"
                                       value="<?= $phase_ini ?>" min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                            </div>
                            <div class="col-6">
                                <label class="font-weight-bold font-size-sm">Hasta</label>
                                <input type="date" name="fecha_fin" class="form-control"
                                       value="<?= date('Y-m-d') ?>" min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-5 font-weight-bold">
                            <i class="fas fa-download mr-2"></i>Descargar Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte 2: Licencias del Trimestre -->
        <div class="col-md-6 mb-6">
            <div class="card card-custom shadow-sm h-100">
                <div class="card-header border-0 pt-5" style="border-left:4px solid #6f42c1;">
                    <h5 class="card-label font-weight-bolder">
                        <i class="fas fa-file-alt mr-2" style="color:#6f42c1;"></i>Licencias del Trimestre
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted font-size-sm mb-4">
                        Todas las licencias registradas: días, licencias por periodos, excepciones y cupo.
                    </p>
                    <form method="POST" action="<?= base_url('manager/prim_reportes_xlsx') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="tipo" value="licencias">
                        <div class="form-group">
                            <label class="font-weight-bold font-size-sm">Estado</label>
                            <select name="estado" class="form-control">
                                <option value="all">Todas</option>
                                <option value="pending">Solo pendientes</option>
                                <option value="approved">Solo aprobadas</option>
                                <option value="rejected">Solo rechazadas</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label class="font-weight-bold font-size-sm">Desde</label>
                                <input type="date" name="fecha_ini" class="form-control"
                                       value="<?= $phase_ini ?>" min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                            </div>
                            <div class="col-6">
                                <label class="font-weight-bold font-size-sm">Hasta</label>
                                <input type="date" name="fecha_fin" class="form-control"
                                       value="<?= date('Y-m-d') ?>" min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-block mt-5 font-weight-bold"
                                style="background:#6f42c1;color:#fff;">
                            <i class="fas fa-download mr-2"></i>Descargar Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte 3: Ausencias sin Licencia -->
        <div class="col-md-6 mb-6">
            <div class="card card-custom shadow-sm h-100">
                <div class="card-header border-0 pt-5" style="border-left:4px solid #f1416c;">
                    <h5 class="card-label font-weight-bolder">
                        <i class="fas fa-calendar-times text-danger mr-2"></i>Ausencias sin Licencia
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted font-size-sm mb-4">
                        Alumnos con días ausentes sin ninguna licencia registrada en el período.
                    </p>
                    <form method="POST" action="<?= base_url('manager/prim_reportes_xlsx') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="tipo" value="ausencias">
                        <div class="row">
                            <div class="col-6">
                                <label class="font-weight-bold font-size-sm">Desde</label>
                                <input type="date" name="fecha_ini" class="form-control"
                                       value="<?= $phase_ini ?>" min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                            </div>
                            <div class="col-6">
                                <label class="font-weight-bold font-size-sm">Hasta</label>
                                <input type="date" name="fecha_fin" class="form-control"
                                       value="<?= date('Y-m-d') ?>" min="<?= $phase_ini ?>" max="<?= $phase_fin ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block mt-5 font-weight-bold">
                            <i class="fas fa-download mr-2"></i>Descargar Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte 4: Cupo Trimestral Completo -->
        <div class="col-md-6 mb-6">
            <div class="card card-custom shadow-sm h-100">
                <div class="card-header border-0 pt-5" style="border-left:4px solid #ffa800;">
                    <h5 class="card-label font-weight-bolder">
                        <i class="fas fa-chart-bar text-warning mr-2"></i>Cupo Trimestral Completo
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted font-size-sm mb-4">
                        Estado de cupo de todos los alumnos: ausencias, licencias, licencias por periodos
                        y días restantes. Incluye alertas.
                    </p>
                    <form method="POST" action="<?= base_url('manager/prim_reportes_xlsx') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="tipo" value="cupo">
                        <input type="hidden" name="fecha_ini" value="<?= $phase_ini ?>">
                        <input type="hidden" name="fecha_fin" value="<?= $phase_fin ?>">
                        <div class="alert alert-light-warning font-size-sm py-3 mb-4">
                            <i class="fas fa-info-circle text-warning mr-1"></i>
                            El reporte toma el trimestre completo:
                            <?= date('d-m-Y', strtotime($phase_ini)) ?> → <?= date('d-m-Y', strtotime($phase_fin)) ?>
                        </div>
                        <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                            <i class="fas fa-download mr-2"></i>Descargar Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
