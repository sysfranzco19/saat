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
                            <h3 class="card-label">Registro de comunicación - <?php echo $phase_name; ?></h3>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!--begin::Example-->
                        <div class="example mb-10">
                        <p>Listados de hij@s inscritos.</p>
                            <div class="example-preview">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Estudiante</th>
                                            <th scope="col">Curso</th>
                                            <th scope="col">Reporte</th>
                                            <th scope="col">Reporte</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 1;
                                    foreach($behaviors as $stu):
                                    ?>
                                        <tr>
                                            <td><?php echo $count;?></td>
                                            <td><?php echo $stu['student'];?></td>
                                            <td><?php echo $stu['completo'];?></td>
                                            <td>Tiene <?php echo $stu['reportes'];?> reportes</td>
                                            <td>
                                                <a href="<?php echo base_url(); ?>parents/behaviors_child/<?php echo $stu['student_id'];?>" class="btn btn-danger active">Ver comunicación</a>
                                            </td>
                                        </tr>
                                    <?php
                                    $count+=1;
                                    endforeach;
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--end::Example-->
                    </div>
                    
                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->