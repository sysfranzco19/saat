<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Boletines de Notas - <?php echo $phase_name; ?></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($students_pdf)): ?>
                            <p class="text-muted">No se encontraron estudiantes inscritos.</p>
                        <?php else: ?>
                            <?php foreach ($students_pdf as $stu): ?>
                                <div class="card card-custom gutter-b mb-6">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h5 class="card-label mb-0">
                                                <?php echo htmlspecialchars($stu['student']); ?>
                                                <small class="text-muted ml-2"><?php echo htmlspecialchars($stu['completo']); ?></small>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php for ($t = 1; $t <= 3; $t++): ?>
                                                <div class="col-md-4 mb-4">
                                                    <h6 class="font-weight-bold mb-2">Trimestre <?php echo $t; ?></h6>
                                                    <?php if ($stu['pdfs'][$t]['exists']): ?>
                                                        <div class="border rounded overflow-hidden" style="height:280px;">
                                                            <iframe
                                                                src="<?php echo $stu['pdfs'][$t]['url']; ?>"
                                                                width="100%"
                                                                height="280"
                                                                style="border:none; display:block;"
                                                                title="Boletín T<?php echo $t; ?> - <?php echo htmlspecialchars($stu['student']); ?>">
                                                            </iframe>
                                                        </div>
                                                        <div class="mt-2 text-center">
                                                            <a href="<?php echo $stu['pdfs'][$t]['url']; ?>" target="_blank" class="btn btn-sm btn-primary">
                                                                <i class="la la-file-pdf-o"></i> Ver PDF
                                                            </a>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height:280px;">
                                                            <span class="text-muted font-size-sm">No disponible</span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
