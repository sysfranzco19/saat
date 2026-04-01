<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Notas de Medio Trimestre de: <?php echo $student; ?>
                    <span class="text-muted pt-2 font-size-sm d-block">Curso: <?php echo $completo; ?></span></h3>
                </div>
                <div class="card-toolbar">
                    <a href="<?php echo base_url(); ?>teacher/notes_half_student_xls/<?php echo $student_id;?>" class="btn btn-success font-weight-bold mr-2">
                        <i class="la la-file-excel-o"></i> Descargar
                    </a>
                </div>
            </div>
            <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Materia</th>
                        <th scope="col">Docente</th>
                        <th scope="col">Saber /45</th>
                        <th scope="col">Hacer /40</th>
                        <th scope="col">Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 0;
                    $suma = 0;
                    foreach($csamarks as $row): 
                        $saber = 0;
                        $hacer = 0;
                        if(isset($row['saber'])) {$saber = round($row['saber']);}
                        if(isset($row['hacer'])) {$hacer = round($row['hacer']);}
                        $prom = round(($saber+$hacer)/2);
                    ?>
                    <tr>
                        <td><?php echo $row['materia'];?></td>
                        <td><?php echo $row['docente'];?></td>
                        <td><?php echo $saber;?></td>
                        <td><?php echo $hacer;?></td>
                        <td class="text-warning"><b><?php echo $prom;?></b></td>
                    </tr>
                    <?php
                        $count += 1;
                        $suma += $prom;
                    endforeach; 
                    $final = 0;
                    if ($count<>0) {
                        $final = round($suma/$count);
                    }
                    ?>
                    <tr>
                        <th colspan="4" scope="col">PROMEDIO FINAL</th>
                        <td class="text-warning"><b><?php echo $final;?></b></td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->