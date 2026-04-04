<script type="text/javascript">
function confirmar()
{
    var respuesta = confirm("¿Esta seguro de reportar notas de Medio Trimestre?");
    if (respuesta == true){
        window.location.assign('<?php echo base_url(); ?>index.php/teacher/half_phase_save/<?php echo $subject_id;?>')
    }else{
        return false;
    }
}
function confirmar2()
{
    var respuesta = confirm("¿Esta seguro de Actualizar sus notas?");
    if (respuesta == true){
        window.location.assign('<?php echo base_url(); ?>index.php/teacher/half_phase_save/<?php echo $subject_id;?>')
    }else{
        return false;
    }
}

</script>
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
                            <h3 class="card-label">Materia: <?php echo $subject; ?> &emsp; Curso: <?php echo $curso; ?>
                            <span class="d-block text-muted pt-2 font-size-sm">El reporte muestra todas las notas actualizadas del curso</span></h3>
                            <?php 
                            if ($partial_locked==0) {
                                ?>
                                <button type="button" class="btn btn-warning font-weight-bold" onclick="confirmar()" >Reportar Medio Trimestre</button>
                                <?php
                            }elseif($partial_locked==1 && $locked==0){
                                ?>
                                <button type="button" class="btn btn-secondary font-weight-bold" onclick="confirmar2()" >Entregar Notas</button>
                                <button type="button" class="btn btn-primary font-weight-bold" disabled >Consolidación no disponible</button>
                                <?php
                            }else{
                                ?>
                                <button type="button" class="btn btn-primary font-weight-bold" disabled >Consolidación no disponible</button>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Nombre</th>

                                    <!-- <?php
                                    foreach($details_ser as $det):
                                    ?>
                                    <th><?php echo $det['name'];?><br /><?php echo $det['record_date'];?></th>
                                    <?php
                                    endforeach; ?>
                                    <th class="text-warning">SER</th>
                                -->
                                    <?php
                                    foreach($details_saber as $det):
                                    ?>
                                    <th><?php echo $det['name'];?><br /><?php echo $det['record_date'];?></th>
                                    <?php
                                    endforeach; ?>
                                    <th class="text-warning">SABER</th>
                                    <?php
                                    foreach($details_hacer as $det):
                                    ?>
                                    <th><?php echo $det['name'];?><br /><?php echo $det['record_date'];?></th>
                                    <?php
                                    endforeach; ?>
                                    <th class="text-warning">HACER</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                            <?php foreach($csamarks as $row): ?>
                                <tr>
                                    <td><?php echo $row['student'];?></td>
                                    <!--
                                    <?php foreach($details_ser as $det): ?>
                                        <td><?php echo $row[$det['columna']];?></td>
                                    <?php endforeach; ?>
                                    <th class="text-warning" ><?php echo $row['ser_average'];?></th>
                                    -->

                                    <?php foreach($details_saber as $det): ?>
                                        <td><?php echo $row[$det['columna']];?></td>
                                    <?php endforeach; ?>
                                    <th class="text-warning" ><?php echo $row['saber_average'];?></th>

                                    <?php foreach($details_hacer as $det): ?>
                                        <td><?php echo $row[$det['columna']];?></td>
                                    <?php endforeach; ?>
                                    <th class="text-warning" ><?php echo $row['hacer_average'];?></th>
                                <tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <!--end: Datatable-->
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--begin::Entry-->
