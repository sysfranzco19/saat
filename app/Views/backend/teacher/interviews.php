<?php $session = session(); ?>
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Registro de Entrevistas
                        <?php if (isset($curso) && $curso != ''): ?>
                            - <span class="text-muted small">
                                <?php echo $curso; ?>
                            </span>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <?php if (isset($section_id) && $section_id != 'all' && $section_id > 0): ?>
                        <a href="javascript:;"
                            onclick="showAjaxModal('<?php echo base_url('index.php/modal/popup/modal_interview_add/' . $section_id); ?>');"
                            class="btn btn-primary font-weight-bolder">
                            <span class="svg-icon svg-icon-md">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path
                                            d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>Nueva Entrevista
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <!-- Filter by Section -->
                <div class="mb-7">
                    <div class="row align-items-center">
                        <div class="col-lg-4 col-xl-4">
                            <div class="row align-items-center">
                                <div class="col-md-12 my-2 my-md-0">
                                    <div class="d-flex align-items-center">
                                        <label class="mr-3 mb-0 d-none d-md-block">Curso:</label>
                                        <select class="form-control" id="section_selector"
                                            onchange="window.location.href='<?php echo base_url('index.php/teacher/interviews/'); ?>' + this.value">
                                            <option value="all" <?php if ($section_id == 'all')
                                                echo 'selected'; ?>>Todos
                                            </option>
                                            <option value="">Seleccionar Curso...</option>
                                            <?php foreach ($sections as $s): ?>
                                                <option value="<?php echo $s['section_id']; ?>" <?php if (isset($section_id) && $section_id == $s['section_id'])
                                                       echo 'selected'; ?>>
                                                    <?php echo $s['completo']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="kt_datatable">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Alumno</th>
                                <th>Asistente (Tutor)</th>
                                <th>Motivo</th>
                                <th>Situación</th>
                                <th>Acuerdos</th>
                                <th>Adjunto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($interviews as $row): ?>
                                <tr>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($row['date'])); ?>
                                    </td>
                                    <td>
                                        <?php echo $row['name'] . ' ' . $row['lastname']; ?>
                                    </td>
                                    <td>
                                        <?php echo $row['assistant']; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $badge = 'label-light-primary';
                                        if ($row['reason'] == 'Conducta')
                                            $badge = 'label-light-danger';
                                        if ($row['reason'] == 'Rutinario')
                                            $badge = 'label-light-success';
                                        ?>
                                        <span class="label label-lg font-weight-bold <?php echo $badge; ?> label-inline">
                                            <?php echo $row['reason']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light-info" data-toggle="popover"
                                            data-trigger="hover" title="Situación"
                                            data-content="<?php echo htmlspecialchars($row['description']); ?>">Ver</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-light-warning" data-toggle="popover"
                                            data-trigger="hover" title="Acuerdos"
                                            data-content="<?php echo htmlspecialchars($row['agreements']); ?>">Ver</button>
                                    </td>
                                    <td>
                                        <?php if ($row['attachment']): ?>
                                            <a href="<?php echo base_url('public/uploads/interviews/' . $row['attachment']); ?>"
                                                target="_blank" class="btn btn-icon btn-sm btn-light-success">
                                                <i class="flaticon2-clip-symbol"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url('index.php/teacher/interview_delete/' . $row['interview_id']); ?>"
                                            class="btn btn-icon btn-sm btn-light-danger"
                                            onclick="return confirm('¿Está seguro de eliminar este registro?');">
                                            <i class="flaticon2-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>