<?php $session = session(); ?>

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Cartas de Contenidos
                        <span class="d-block text-muted pt-2 font-size-sm">Listado de Cartas de Contenido</span>
                    </h3>
                </div>
            </div>
            <div class="card-body">

                <!-- Flash messages -->
                <?php if ($session->get('flash_message')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i><?= htmlspecialchars($session->get('flash_message')) ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                <?php $session->remove('flash_message'); endif; ?>
                <?php if ($session->get('flash_message_error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($session->get('flash_message_error')) ?>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
                <?php $session->remove('flash_message_error'); endif; ?>

                <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Materia</th>
                            <th>Nivel</th>
                            <th class="text-center">1er Trimestre</th>
                            <th class="text-center">2do Trimestre</th>
                            <th class="text-center">3er Trimestre</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($materias as $item):
                        $sid   = $item['canonical_id'];
                        $trims = [];
                        for ($t = 1; $t <= 3; $t++) {
                            $fname = "CC_{$sid}_T{$t}.pdf";
                            if (!file_exists(FCPATH . 'uploads/content_letter/' . $fname)) {
                                $alt = "CC_{$sid}_{$t}.pdf";
                                $fname = file_exists(FCPATH . 'uploads/content_letter/' . $alt) ? $alt : null;
                            }
                            $trims[$t] = $fname;
                        }
                    ?>
                    <tr>
                        <td class="font-weight-bold align-middle">
                            <?= htmlspecialchars($item['materia']) ?>
                        </td>
                        <td class="align-middle text-muted">
                            <?= htmlspecialchars($item['nivel']) ?>
                        </td>
                        <?php for ($t = 1; $t <= 3; $t++): ?>
                        <td class="text-center align-middle">
                            <?php if ($trims[$t]): ?>
                                <a href="<?= base_url('uploads/content_letter/' . $trims[$t]) ?>"
                                   target="_blank"
                                   class="btn btn-text-info btn-hover-light-info font-weight-bold mr-1">
                                   Ver C.C.
                                </a>
                                <label class="btn btn-text-primary btn-hover-light-primary font-weight-bold mb-0"
                                       style="cursor:pointer;" title="Reemplazar PDF">
                                    Cambiar
                                    <form action="<?= base_url("teacher/upfile_letter_trim/{$sid}/{$t}") ?>"
                                          method="post" enctype="multipart/form-data" style="display:none;" id="form_<?= $sid ?>_<?= $t ?>">
                                        <input type="file" name="userfile" accept="application/pdf"
                                               onchange="document.getElementById('form_<?= $sid ?>_<?= $t ?>').submit()">
                                    </form>
                                </label>
                            <?php else: ?>
                                <label class="btn btn-info font-weight-bold mb-0" style="cursor:pointer;">
                                    Subir C.C.
                                    <form action="<?= base_url("teacher/upfile_letter_trim/{$sid}/{$t}") ?>"
                                          method="post" enctype="multipart/form-data" style="display:none;" id="form_<?= $sid ?>_<?= $t ?>">
                                        <input type="file" name="userfile" accept="application/pdf"
                                               onchange="document.getElementById('form_<?= $sid ?>_<?= $t ?>').submit()">
                                    </form>
                                </label>
                            <?php endif; ?>
                        </td>
                        <?php endfor; ?>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <!--end: Datatable-->

            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->



           