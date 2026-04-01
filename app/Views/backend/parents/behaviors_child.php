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
                            <h3 class="card-label">Reportes de comunicación - <?php echo $student; ?></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Example-->
                        <div class="example mb-10">
                            <p>Listados de los registros de comunicación de todas las materias.</p>
                            <div class="example-preview">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Materia</th>
                                            <th scope="col">Docente</th>
                                            <th scope="col">Reporte</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    if(count($behaviors)>0){
                                        $count=0;
                                        foreach ($behaviors as $row):
                                            setlocale(LC_ALL,"es_ES");
                                            $newDate = date("d/m/Y", $row['date']);
                                            $count+=1;                             
                                        ?>
                                        <tr class="<?php if($row['type']==1){echo 'table-info';}else{echo'table-danger';} ?>" >
                                            <th scope="row"><?php echo $count;?></th>
                                            <td><?php echo $newDate;?></th>
                                            <td><?php echo $row['materia'];?></td>
                                            <td><?php echo $row['docente'];?></td>
                                            <td><?php echo $row['behavior'];?></td>
                                        </tr>
                                        <?php
                                        endforeach;
                                    }else{
                                        ?>
                                        <tr><td colspan=5 >No existen registros.</td></tr>
                                        <?php 
                                    }
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