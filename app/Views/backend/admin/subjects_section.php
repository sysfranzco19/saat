<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--end::Notice-->
        <div class="row">
            <div class="col-xl-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Materias del: <?php echo $completo; ?></h3>
                        </div>
                    </div>
                    <div class="card-body">
                    <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" >Materias</th>
                                    <th scope="col" >Docente</th>
                                    <th scope="col" >Acción</th>
                                </tr>
                            </thead>
                        <tbody>
                    <?php 
                        foreach($subjects as $subject){
                            ?>
                            <tr>
                                <th scope="row" ><?php echo $subject['name'];?></th>
                                <td><?php echo $subject['docente'];?></td>
                                <td>
                                <a href="https://docs.google.com/spreadsheets/d/<?php echo $subject['sheet_id']; ?>/edit" target="_blank" class="btn btn-success btn-sm" >Ir a la Planilla</a>
                                <a href="<?php echo base_url(); ?>admin/update_notes/<?php echo $subject['subject_id']; ?>/1" target="_blank" class="btn btn-secondary btn-sm"
                                    onclick="return confirm('¿Está seguro de actualizar las notas del Trimestre 1 de <?php echo esc($subject['name'], 'js'); ?>?');">Actualizar Notas T1</a>
                                <a href="<?php echo base_url(); ?>admin/update_notes/<?php echo $subject['subject_id']; ?>/2" target="_blank" class="btn btn-secondary btn-sm"
                                    onclick="return confirm('¿Está seguro de actualizar las notas del Trimestre 2 de <?php echo esc($subject['name'], 'js'); ?>?');">Actualizar Notas T2</a>
                                <a href="<?php echo base_url(); ?>admin/update_notes/<?php echo $subject['subject_id']; ?>/3" target="_blank" class="btn btn-secondary btn-sm"
                                    onclick="return confirm('¿Está seguro de actualizar las notas del Trimestre 3 de <?php echo esc($subject['name'], 'js'); ?>?');">Actualizar Notas T3</a>
                                </td>
                            </tr>
                        <?php
                            //}
                        }
                    ?>
                        </tbody>
                    </table>






                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->