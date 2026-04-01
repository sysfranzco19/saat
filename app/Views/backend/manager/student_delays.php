<script type="text/javascript">
    function cargar(sel){
        window.location.assign('<?php echo base_url(); ?>index.php/secretary/licensed/' + sel )
    }
    function confirmar()
    {
        var respuesta = confirm("¿Esta seguro de realizar los Cambios?");
        if (respuesta == true){
            document.form_license.submit(); 
        }else{
            return false;
        }
    }
</script>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Retrasos del estudiante: <?php echo $student;?>
                    <span class="d-block text-muted pt-2 font-size-sm">Curso: <?php echo $completo;?></span></h3>
                </div>
            </div>
            <div class="card-body">
                    <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th><div>Nº</div></th>
                            <th><div>F. Retraso</div></th>
                            <th><div>H. Ingreso</div></th>
                            <th><div>H. Llegada</div></th>
                            <th><div>Motivo del Retraso</div></th>
                            <th><div>Tarde con:</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count=0;
                        if (count($delays)==0) {
                            echo "<tr><td colspan=6 >No tiene retrasos</td></tr>";
                        }
                        foreach($delays as $row):
                            $count+=1;
                        ?>
                        <tr>
                            <td><?php echo $count;?></td>
                            <td><?php echo $row['date_class'];?></td>
                            <td><?php echo $row['hora_ingreso'];?></td>
                            <td><?php echo $row['hora_llegada'];?></td>
                            <td><?php echo $row['motivo'];?></td>
                            <td><?php echo $row['tarde_con'];?> min.</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    <!--end: Datatable-->
            </div>
                <?php
                
                ?>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>