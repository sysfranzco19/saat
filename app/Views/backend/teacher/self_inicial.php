<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                    AUTOEVALUACIÓN (Nivel Primaria 1ro y 2do) - <?php echo $completo; ?>
                </h3>
                <span><?php echo $phase_name; ?></span>
            </div>
            <div class="card-body">
                <?php $session = session(); ?>
                <?php if ($session->getFlashdata('flash_message')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $session->getFlashdata('flash_message'); ?>
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                <?php endif; ?>

                <table id="tabla" class="table table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th class="col-sm-8">Estudiantes</th>
                            <th class="col-sm-4 text-center">Autoevaluación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $row): ?>
                        <tr>
                            <td>
                                <?php if ($section_id < 231): ?>
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/self_modal_edit/<?php echo $row['student_id']; ?>/<?php echo urlencode($row['student']); ?>/<?php echo $section_id; ?>'); return false;">
                                        <?php echo $row['student']; ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo $row['student']; ?>
                                <?php endif; ?>
                            </td>

                            <?php
                            $existe = 0;
                            foreach ($autos as $auto):
                                if ($auto['student_id'] == $row['student_id']):
                                    $existe = 1;
                            ?>
                                    <td class="text-center">
                                        <span class="badge badge-info badge-pill" style="font-size:1rem;">
                                            <?php echo $auto['autoevaluacion']; ?>
                                        </span>
                                    </td>
                            <?php
                                    break;
                                endif;
                            endforeach;

                            if ($existe == 0):
                                if (!is_null($row['retirement_date'])):
                            ?>
                                <td class="text-center">
                                    <span class="badge badge-danger badge-pill" title="Retirado" style="font-size:1rem;">R</span>
                                </td>
                            <?php else: ?>
                                <td class="text-center">
                                    <span class="badge badge-warning badge-pill" title="Pendiente" style="font-size:1rem;">P</span>
                                </td>
                            <?php
                                endif;
                            endif;
                            ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
