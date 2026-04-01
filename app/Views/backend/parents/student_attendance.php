<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Asistencias de: <?php echo $student;?>
                    <span class="d-block text-muted pt-2 font-size-sm">Curso: <?php echo $completo;?></span></h3>
                </div>
            </div>
            <div class="card-body">
                    <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Materia</th>
                            <th>Docente</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $tipo = ['danger','success','primary','warning','info'];
                        $estado = ['ausente','presente','licencia','retraso','virtual'];
                        $count=0;
                        /*$consulta='SELECT t2.assistance_subject_id, t3.subject_id, t3.name as subject, t5.name as teacher, t4.date_class, t2.status
                                    FROM assistance_subject as t2
                                    INNER JOIN subject as t3 ON(t2.subject_id=t3.subject_id)
                                    INNER JOIN attendance_dates as t4 ON(t2.date_id=t4.date_id)                                    
                                    INNER JOIN teacher as t5 ON(t3.teacher_id=t5.teacher_id) WHERE t2.student_id='.$student_id.' AND t4.phase_id='.$phase_id.' ORDER BY t4.date_class DESC';*/
                        //$asiss = $this->db->query($consulta)->result_array();
                        //$asiss = array();
                        foreach($asis as $row):
                            $newDate = date_create_from_format('d-M', $row['date_class']);
                            $count+=1;
                                                                ?>
                        <tr>
                            <td><?php echo $row['subject'];?></td>
                            <td><?php echo $row['teacher'];?></td>
                            <td ><?php echo date("d-M", strtotime($row['date_class'])); ?></td>
                            <td><span class='label label-light-<?php echo $tipo[$row['status']];?> font-weight-bold label-inline' style="cursor: hand"> <?php echo $estado[$row['status']];?></span>

                            </td>
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



           