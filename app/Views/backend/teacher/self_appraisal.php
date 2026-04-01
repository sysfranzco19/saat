
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
                        <h3 class="card-label">Autoevaluaciones <?php echo $phase_name;?>
                        <span class="d-block text-muted pt-2 font-size-sm">Listado de las autoevaluaciones registradas por los estudiantes</span></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>SER (100)</th>
                                    <th>SER (5)</th>
                                    <th>DECIDIR (100)</th>
                                    <th>DECIDIR (5)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($students as $row):
                                ?>
                                <tr>
                                    <td><img src="<?php echo get_image_url('student',$row['student_id']);?>" class="rounded-circle" width="30" /></td>
                                    <td><?php echo $row['student'];?></td>
                                    <?php
                                    $existe=0;
                                    foreach($autos as $auto):
                                    if ($row['student_id']==$auto['student_id']) {
                                        $existe=1;
                                        ?>
                                        <td><?php echo $auto['ser100'];?></td>
                                        <td><?php echo $auto['ser5'];?></td>
                                        <td><?php echo $auto['dec100'];?></td>
                                        <td><?php echo $auto['dec5'];?></td>
                                        <?php    
                                        break;
                                    }
                                    endforeach; 
                                    if ($existe==0) {
                                    ?>
                                        <td colspan='5'>No ha registrado su Autoevaluación</td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
           	    </div>
            </div>
        </div>
    </div>
</div>