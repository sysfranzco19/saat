<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-3">
                <div class="card-title">
                    <h3 class="card-label">Notas - <?php echo $student;?>
                    <span class="d-block text-muted pt-2 font-size-sm">Lista de de estudiantes del: <?php echo $completo;?></span></h3>
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table table-bordered table-checkable" id="kt_datatable">
                    <thead>
                        <tr>
                            <th>Materia</th>
                            <th>ser</th>
                            <th>saber</th>
                            <th>hacer</th>
                            <th>decidir</th>
                            <th>autoevaluacion</th>
                            <th>total</th>
                            <th>total_vc</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($notes as $row):
                        ?>
                        <tr>
                            <td><?php echo $row['materia']; ?></td>
                            <td><?php echo $row['ser_average']; ?></td>
                            <td><?php echo $row['saber_average']; ?></td>
                            <td><?php echo $row['hacer_average']; ?></td>
                            <td><?php echo $row['decidir_average']; ?></td>
                            <td><?php echo $row['autoevaluacion']; ?></td>
                            <td><?php echo $row['total_average']; ?></td>
                            <td><?php echo $row['total_vc']; ?></td>
                            <td>
                            <button type="button" class="btn btn-warning btn-sm" 
                            onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/student_notes_modal_edit/<?php echo $row['csamarks_id'] ?>/<?php echo $row['student_id'] ?>/<?php echo $row['phase_id'] ?>/0/0');" >
                                    Editar
                                </button>
                            </td>
                        </tr>
                        <?php
                        endforeach;
                        ?>
                        
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