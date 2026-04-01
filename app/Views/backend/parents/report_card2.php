<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Notice-->
        <div class="alert alert-custom alert-white alert-shadow fade show gutter-b" role="alert">
            <h3 class="card-label">Evaluaciones del Estudiante: <?php echo $student;?><br />Curso: <?php echo $completo;?></h3>
        </div>
        <!--end::Notice-->
        <div class="row">
            <div class="col-xl-6">
            <?php 
            $m = floor(count($subjects)/2);
            $c = 1;
            foreach($subjects as $row): 
                
            ?>
            
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Materia: <?php echo $row['name'];?><br />Docente: <?php echo $row['profe'];?></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Example-->
                        <div class="example mb-12">
                            <div class="example-preview">
                                
                        
                                <table class="table table-hover mb-12">
                                    <thead>
                                        <tr>
                                            <th scope="col">Evaluación</th>
                                            <th scope="col">NOTA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach($csamarks as $csa): 
                                            if ($csa['subject_id']==$row['subject_id']) {
                                            foreach($details_saber as $det):
                                                if ($det['subject_id']==$row['subject_id']) {
                                            ?>
                                            <tr>
                                                <td><?php echo $det['name'];?><br /><?php echo $det['record_date'];?></td>
                                                <td><?php echo $csa[$det['columna']];?></td>
                                            </tr>
                                            <?php 
                                                }
                                            endforeach; 
                                        ?>

                                        <tr>
                                            <td class="text-warning"><b>SABER Ponderado 35%</b></td>
                                            <td class="text-warning" ><b><?php echo $csa['saber_average'];?></b></td>
                                        </tr>
                                        <?php 
                                        foreach($details_hacer as $det):
                                            if ($det['subject_id']==$row['subject_id']) {
                                        ?>
                                        <tr>
                                            <td><?php echo $det['name'];?><br /><?php echo $det['record_date'];?></td>
                                            <td><?php echo $csa[$det['columna']];?></td>
                                        </tr>
                                        
                                        <?php 
                                            }
                                        endforeach;  
                                        ?>
                                        <tr>
                                            <td class="text-warning"><b>HACER Ponderado 35%</b></td>
                                            <td class="text-warning" ><b><?php echo $csa['hacer_average'];?></b></td>
                                        </tr>
                                        <?php
                                            }
                                        endforeach; 
                                        ?>
                                        
                                    </tbody>
                                </table>
                                <!--end::Example-->
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card-->
            
            <?php 
                if ($m==$c) {
                    ?>
                    </div>
                    <div class="col-xl-6">
                    <?php
                }
                $c += 1;
            endforeach; 
            ?>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->