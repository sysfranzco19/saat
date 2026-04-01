<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Ausencias a clases del estudiante: <?php echo $student;?>
                    <span class="d-block text-muted pt-2 font-size-sm">Curso: <?php echo $completo;?></span></h3>
                </div>
            </div>
            <div class="card-body">
                    <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th><div>Nº</div></th>
                            <th>Docente/Materia</th>
                            <th>Detalle</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        //absence_add
                        $count=0;
                        if (count($absences)==0) {
                            echo "<tr><td colspan=6 >No tiene ausencias registradas</td></tr>";
                        }
                        foreach($absences as $row):
                            $count+=1;
                        ?>
                        <tr>
                            <td><?php echo $count;?></td>
                            <td><?php echo $row['doc_materia']; ?></td>
                            <td><?php echo $row['obs']; ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                            <td><?php echo $row['hora']; ?></td>
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



           