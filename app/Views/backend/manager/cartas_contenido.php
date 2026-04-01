<?php $session = session(); ?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
<div class="d-flex flex-column-fluid">
<div class="container-fluid">

    <!-- Encabezado -->
    <div class="row mb-6">
        <div class="col-12">
            <div class="card card-custom wave wave-animate-slow wave-info">
                <div class="card-body py-5">
                    <div class="d-flex align-items-center">
                        <div class="mr-5">
                            <i class="flaticon2-file-2 text-info icon-4x"></i>
                        </div>
                        <div>
                            <h2 class="text-dark font-weight-bolder mb-1">Cartas de Contenido — Secundaria</h2>
                            <div class="text-muted font-size-lg"><?php echo htmlspecialchars($phase_name); ?> &nbsp;·&nbsp; Estado de entregas por materia y docente</div>
                        </div>
                        <div class="ml-auto">
                            <a href="<?php echo base_url(); ?>manager/dashboard" class="btn btn-light font-weight-bold">
                                <i class="flaticon2-back mr-1"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-6">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-custom bg-success" style="min-height:110px">
                <div class="card-body d-flex align-items-center">
                    <i class="flaticon2-checkmark text-white icon-3x mr-4"></i>
                    <div>
                        <div class="text-white font-weight-bolder font-size-h2"><?php echo $total_uploaded; ?></div>
                        <div class="text-white font-size-sm font-weight-bold">Cartas subidas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-custom bg-danger" style="min-height:110px">
                <div class="card-body d-flex align-items-center">
                    <i class="flaticon-warning-sign text-white icon-3x mr-4"></i>
                    <div>
                        <div class="text-white font-weight-bolder font-size-h2"><?php echo $total_pending; ?></div>
                        <div class="text-white font-size-sm font-weight-bold">Cartas pendientes</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-custom bg-primary" style="min-height:110px">
                <div class="card-body d-flex align-items-center">
                    <i class="flaticon-users-1 text-white icon-3x mr-4"></i>
                    <div>
                        <div class="text-white font-weight-bolder font-size-h2"><?php echo count($teachers_uploaded); ?></div>
                        <div class="text-white font-size-sm font-weight-bold">Docentes al día</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card card-custom bg-warning" style="min-height:110px">
                <div class="card-body d-flex align-items-center">
                    <i class="flaticon-clock text-white icon-3x mr-4"></i>
                    <div>
                        <div class="text-white font-weight-bolder font-size-h2"><?php echo count($teachers_pending); ?></div>
                        <div class="text-white font-size-sm font-weight-bold">Docentes pendientes</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="row">

        <!-- Columna: tabs por grado + cards de materias -->
        <div class="col-xl-8 mb-6">
            <div class="card card-custom">
                <div class="card-header border-0 pt-5 pb-0">
                    <h3 class="card-title font-weight-bolder text-dark">Lista de Materias por Grado</h3>
                </div>
                <div class="card-body pt-4">

                    <!-- Pills por grado -->
                    <ul class="nav nav-pills nav-pills-sm flex-wrap mb-6" id="gradeTabs" role="tablist">
                        <?php $first = true; foreach ($by_grade as $grade => $subjects): ?>
                        <li class="nav-item mb-2 mr-2">
                            <a class="nav-link font-weight-bold <?php echo $first ? 'active' : ''; ?>"
                               data-toggle="pill"
                               href="#grade-<?php echo htmlspecialchars(preg_replace('/[\s]+/', '-', $grade)); ?>">
                                <?php echo htmlspecialchars($grade); ?>
                                <?php
                                    $uploaded_count = count(array_filter($subjects, fn($s) => $s['has_any_pdf']));
                                    $total_count    = count($subjects);
                                ?>
                                <span class="label label-inline ml-1 <?php echo $uploaded_count === $total_count ? 'label-success' : 'label-danger'; ?>">
                                    <?php echo $uploaded_count; ?>/<?php echo $total_count; ?>
                                </span>
                            </a>
                        </li>
                        <?php $first = false; endforeach; ?>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content">
                        <?php $first = true; foreach ($by_grade as $grade => $subjects): ?>
                        <div class="tab-pane fade <?php echo $first ? 'show active' : ''; ?>"
                             id="grade-<?php echo htmlspecialchars(preg_replace('/[\s]+/', '-', $grade)); ?>">
                            <div class="row">
                            <?php foreach ($subjects as $sub):
                                $uploaded_secs = array_filter($sub['secciones'], fn($s) => $s['has_pdf']);
                                $first_pdf     = current($uploaded_secs) ?: null;
                                $color_bg      = $sub['has_any_pdf'] ? '#e8f5e9' : '#fdecea';
                                $color_text    = $sub['has_any_pdf'] ? '#2e7d32'  : '#c62828';
                            ?>
                            <div class="col-xl-6 col-lg-6 mb-4">
                                <div class="card border <?php echo $sub['has_any_pdf'] ? 'border-success' : 'border-danger'; ?>"
                                     style="border-width:2px!important; border-radius:10px;">
                                    <div class="card-body p-4">
                                        <!-- Cabecera: ícono + nombre + estado -->
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="flex-shrink-0 mr-3 d-flex align-items-center justify-content-center"
                                                 style="width:42px; height:42px; border-radius:8px; background:<?php echo $color_bg; ?>;">
                                                <span class="font-weight-bolder font-size-h5" style="color:<?php echo $color_text; ?>">
                                                    <?php echo strtoupper(mb_substr(trim($sub['materia']), 0, 1)); ?>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="font-weight-bolder text-dark text-truncate">
                                                    <?php echo htmlspecialchars($sub['materia']); ?>
                                                </div>
                                                <div class="text-muted font-size-xs text-truncate">
                                                    <?php echo htmlspecialchars($sub['docente']); ?>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0 ml-2">
                                                <?php if ($sub['has_any_pdf']): ?>
                                                    <span class="label label-light-success label-inline font-weight-bold">✓ Subida</span>
                                                <?php else: ?>
                                                    <span class="label label-light-danger label-inline font-weight-bold">⚠ Pendiente</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <!-- Badges de secciones -->
                                        <div class="d-flex flex-wrap mb-3">
                                            <?php foreach ($sub['secciones'] as $sec): ?>
                                            <span class="label label-inline mr-1 mb-1 <?php echo $sec['has_pdf'] ? 'label-success' : 'label-danger'; ?>">
                                                <?php echo htmlspecialchars($sec['seccion']); ?>
                                            </span>
                                            <?php endforeach; ?>
                                        </div>
                                        <!-- Botón ver PDF -->
                                        <?php if ($first_pdf): ?>
                                        <button type="button"
                                                class="btn btn-sm btn-light-primary btn-block font-weight-bold"
                                                onclick="verPDF(
                                                    <?php echo json_encode($sub['docente']); ?>,
                                                    <?php echo json_encode($sub['materia']); ?>,
                                                    <?php echo json_encode($grade); ?>,
                                                    <?php echo json_encode($sub['personal_email'] ?? ''); ?>,
                                                    <?php echo json_encode(base_url('uploads/content_letter/' . $first_pdf['pdf_file'])); ?>
                                                )">
                                            <i class="flaticon-eye mr-1"></i> Ver Carta PDF
                                        </button>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-light btn-block font-weight-bold" disabled>
                                            <i class="flaticon-upload mr-1"></i> Sin carta subida
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $first = false; endforeach; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Columna lateral: docentes -->
        <div class="col-xl-4">

            <!-- Docentes que subieron -->
            <div class="card card-custom mb-5">
                <div class="card-header border-0 pt-4 pb-2"
                     style="background:#e8f5e9; border-radius:10px 10px 0 0;">
                    <h5 class="card-title font-weight-bolder mb-0" style="color:#2e7d32;">
                        <i class="flaticon2-checkmark mr-2" style="color:#2e7d32;"></i>
                        Docentes al día
                        <span class="label label-success label-inline ml-2"><?php echo count($teachers_uploaded); ?></span>
                    </h5>
                </div>
                <div class="card-body pt-4 pb-4" style="max-height:320px; overflow-y:auto;">
                    <?php if (empty($teachers_uploaded)): ?>
                        <p class="text-muted font-size-sm mb-0">Ningún docente ha subido aún.</p>
                    <?php else: ?>
                        <?php foreach ($teachers_uploaded as $tid => $tname): ?>
                        <div class="d-flex align-items-center mb-4">
                            <div class="symbol symbol-35 symbol-light-success mr-3 flex-shrink-0">
                                <span class="symbol-label font-weight-bolder">
                                    <?php echo strtoupper(mb_substr($tname, 0, 1)); ?>
                                </span>
                            </div>
                            <div class="font-weight-bold text-dark-75 font-size-sm">
                                <?php echo htmlspecialchars($tname); ?>
                            </div>
                            <div class="ml-auto">
                                <span class="label label-dot label-success"></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Docentes que faltan -->
            <div class="card card-custom">
                <div class="card-header border-0 pt-4 pb-2"
                     style="background:#fdecea; border-radius:10px 10px 0 0;">
                    <h5 class="card-title font-weight-bolder mb-0" style="color:#c62828;">
                        <i class="flaticon-warning-sign mr-2" style="color:#c62828;"></i>
                        Docentes pendientes
                        <span class="label label-danger label-inline ml-2"><?php echo count($teachers_pending); ?></span>
                    </h5>
                </div>
                <div class="card-body pt-4 pb-4" style="max-height:320px; overflow-y:auto;">
                    <?php if (empty($teachers_pending)): ?>
                        <p class="text-muted font-size-sm mb-0">¡Todos los docentes están al día!</p>
                    <?php else: ?>
                        <?php foreach ($teachers_pending as $tid => $tname): ?>
                        <div class="d-flex align-items-center mb-4">
                            <div class="symbol symbol-35 symbol-light-danger mr-3 flex-shrink-0">
                                <span class="symbol-label font-weight-bolder">
                                    <?php echo strtoupper(mb_substr($tname, 0, 1)); ?>
                                </span>
                            </div>
                            <div class="font-weight-bold text-dark-75 font-size-sm">
                                <?php echo htmlspecialchars($tname); ?>
                            </div>
                            <div class="ml-auto">
                                <span class="label label-dot label-danger"></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</div>
</div>
</div>

<!-- ============================================================
     MODAL: PDF VIEWER EN FORMATO CONTACTO
     ============================================================ -->
<div class="modal fade" id="modalVerPDF" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header border-0 pb-0 pt-4 px-6">
                <h5 class="modal-title font-weight-bolder text-dark">
                    <i class="flaticon2-file-2 text-info mr-2"></i>Carta de Contenido
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body px-6 pb-6 pt-3">
                <div class="row">

                    <!-- Tarjeta contacto docente -->
                    <div class="col-xl-4 col-lg-4 mb-4 mb-lg-0">
                        <div class="h-100 d-flex flex-column align-items-center justify-content-center text-center p-8"
                             style="background: linear-gradient(145deg, #1a237e 0%, #1976d2 60%, #42a5f5 100%);
                                    border-radius:14px; min-height:300px;">
                            <!-- Avatar -->
                            <div id="modal-avatar"
                                 style="width:88px; height:88px; border-radius:50%;
                                        background:rgba(255,255,255,0.18);
                                        border:3px solid rgba(255,255,255,0.45);
                                        display:flex; align-items:center; justify-content:center;
                                        font-size:2.4rem; font-weight:700; color:#fff;
                                        margin-bottom:14px;">
                                ?
                            </div>
                            <div id="modal-docente" class="font-size-h5 font-weight-bolder text-white mb-1 px-2">—</div>
                            <div id="modal-materia" class="font-size-sm text-white mb-3 px-2" style="opacity:.8;">—</div>
                            <span id="modal-grado"
                                  style="background:rgba(255,255,255,0.18); color:#fff;
                                         padding:5px 14px; border-radius:20px; font-size:.82rem;
                                         font-weight:600; margin-bottom:14px; display:inline-block;">
                                —
                            </span>
                            <div id="modal-email" class="font-size-xs px-2" style="color:rgba(255,255,255,.65);">
                                <i class="flaticon-email text-white mr-1" style="opacity:.6;"></i>
                                <span>—</span>
                            </div>
                            <hr style="border-color:rgba(255,255,255,.2); width:80%; margin:18px auto;">
                            <a id="modal-download"
                               href="#" target="_blank"
                               class="btn btn-sm font-weight-bold"
                               style="background:rgba(255,255,255,.15); color:#fff;
                                      border:1px solid rgba(255,255,255,.4); border-radius:8px;">
                                <i class="flaticon-download mr-1"></i> Descargar PDF
                            </a>
                        </div>
                    </div>

                    <!-- Visor PDF -->
                    <div class="col-xl-8 col-lg-8">
                        <div style="border-radius:12px; overflow:hidden; border:2px solid #e9ecef;
                                    height:72vh; background:#f4f6f9;">
                            <iframe id="modal-iframe"
                                    src=""
                                    style="width:100%; height:100%; border:none;"
                                    title="Carta de Contenido PDF">
                            </iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function verPDF(docente, materia, grado, email, pdfUrl) {
    document.getElementById('modal-avatar').textContent = docente.charAt(0).toUpperCase();
    document.getElementById('modal-docente').textContent = docente;
    document.getElementById('modal-materia').textContent = materia;
    document.getElementById('modal-grado').textContent   = grado;
    document.getElementById('modal-email').querySelector('span').textContent = email || '(sin correo)';
    document.getElementById('modal-download').href = pdfUrl;
    document.getElementById('modal-iframe').src    = pdfUrl;
    $('#modalVerPDF').modal('show');
}
$('#modalVerPDF').on('hidden.bs.modal', function () {
    document.getElementById('modal-iframe').src = '';
});
</script>
