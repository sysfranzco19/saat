
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Planilla de Rudes - <?php echo $curso; ?>
                    <span class="text-muted pt-2 font-size-sm d-block">Muestra todos los Rudes de los estudiantes.</span></h3>
                </div>
                <div class="card-toolbar">
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Estudiante</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        
                        foreach($students as $row):
                            ?>
                        <tr>
                            <td><?php echo $row['student']; ?></td>
                            <td>
                                <a href="<?php echo base_url(); ?>inscripcion_rude/<?php echo $row['student_id'];?>" class="btn btn-success btn-sm" target="_blank" >Ver Rude</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->