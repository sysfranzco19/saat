<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <div class="text-center pt-15">
            <h1 class="h2 font-weight-bolder text-dark mb-6">Docentes sin cosolidar Notas</h1>
        </div>
        <p class="bg-primary text-white py-5 px-5">Docentes</p>
        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th><div>Docente</div></th>
                    <th><div>Estado</div></th>
                    <th><div>Planillas</div></th>
                </tr>
            </thead>
            <tbody>
                <?php
                //$sql1 = 'SELECT t1.teacher_id, t3.name FROM subject as t1 INNER JOIN section as t2 ON(t1.section_id=t2.section_id) 
                //        INNER JOIN teacher as t3 ON(t1.teacher_id=t3.teacher_id) WHERE t2.director_id='.$dir_id.' GROUP BY t1.teacher_id, t3.name ORDER BY t3.name';
                //$teachers = $this->db->query($sql1)->result_array();
                foreach($teachers as $row):
                    
                ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <?php
                        //$sql2 = "SELECT CONCAT(t1.name, '-', t2.nick_name) as name, t1.sheet_id FROM subject as t1 
                        //            INNER JOIN section as t2 ON(t1.section_id=t2.section_id) WHERE locked=0 AND t1.teacher_id=".$row['teacher_id']." AND t2.director_id=".$dir_id;
                        //$materias = $this->db->query($sql2)->result_array();
                        if (count($subjects)==0) {
                            echo "<span class='label label-success label-pill label-inline mr-2'>Notas Consolidas</span>";
                        }else{
                            $popover="";
                            foreach($subjects as $mat):
                                if ($mat['teacher_id']==$row['teacher_id']) {
                                    $popover.=$mat['name']."<br />";
                                }
                                
                            endforeach;
                            //echo "<span class='label label-danger label-pill label-inline mr-2'>Notas sin consolidar</span>";
                        ?>
                            <button type="button" class="btn btn-danger  btn-sm" data-container="body" data-toggle="popover" data-html="true" data-placement="top" 
                            title="Materias sin consolidar:" data-content="<?php echo $popover;?>">Notas sin consolidar</button>
                        <?php
                        }
                        ?>
                        <!--<a href="<?php echo base_url(); ?>index.php/teacher/teacher_sheet/<?php echo $row['teacher_id']; ?>" class="btn btn-light-success font-weight-bold mr-2">Ver Planillas</a>-->
                    </td>
                    <td><a href="<?php echo base_url(); ?>index.php/teacher/teacher_sheet/<?php echo $row['teacher_id']; ?>" class="btn btn-light-success font-weight-bold mr-2">Ver Planillas</a></td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>