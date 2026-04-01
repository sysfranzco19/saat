<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Planillas de Indisciplina
                    <span class="d-block text-muted pt-2 font-size-sm">Listado de Materias - Cursos</span></h3>
                </div>
            </div>
            <div class="card-body" id="imp1">
            <span class="label label-lg label-light-dark label-inline">Falta Leve</span><br />
                    <span class="label label-lg label-light-warning label-inline">Falta grave</span><br />
                    <span class="label label-lg label-light-danger label-inline">Falta muy grave</span>
                <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" >Estudiante</th>
                                    <th scope="col" >Curso</th>
                                    <th scope="col" >Faltas Cometidas</th>
                                </tr>
                            </thead>
                        <tbody>
                    <?php 
                        foreach($students as $stu):
                            ?>
                            <tr>
                                <th scope="row" ><?php echo $stu->nombre;?></th>
                                <td><?php echo $stu->completo;?></td>
                                <td>
                                    <?php 
                                    $numFaltas = 0;
                                    foreach($infractions as $inf):
                                        if ($inf['student_id']==$stu->student_id) {
                                    ?>
                                            <span class="label label-lg label-light-<?php echo $inf['style']; ?> label-inline"><?php echo $inf['materia']; ?> - <?php echo $inf['docente']; ?> - <?php echo $inf['criteria']; ?></span>
                                            <br />
                                    <?php 
                                        $numFaltas += 1;
                                        }
                                    endforeach; 
                                    if ($numFaltas ==0) {
                                        echo "Ninguna";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php
                            
                        endforeach;
                    ?>
                        </tbody>
                    </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
