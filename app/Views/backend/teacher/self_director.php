<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
		    <div class="col-md-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">Autoevaluaciones Faltantes</h3>
                    </div>
                    <div class="card-body">
                        <!--begin: Datatable-->
                        <table class="table">
                            <thead class="thead-inverse">
                                <tr>
                                    <th>Nro</th>
                                    <th>Nombre</th>
                                    <th>CURSO</th>
                                    <th>ESTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //$csl='SELECT t1.student_id, CONCAT(t1.lastname, " ", t1.lastname2," ", t1.name) as student, t2.completo  FROM student as t1 
                                //INNER JOIN section as t2 ON(t1.section_id=t2.section_id) WHERE t1.activo=1 AND t2.director_id='.$teacher_id.' AND t1.student_id NOT IN(SELECT student_id FROM self_appraisal WHERE phase_id='.$phase_id.')';
                                //$students = $this->db->order_by('lastname', 'ASC')->get_where('student' , array('class_id'=>$class_id , 'section_id' => $row['section_id']))->result_array();
                                //$students = $this->db->query($csl)->result_array();
                                if (count($students)==0) {
                                    ?>
                                    <tr>
                                        <td>Todos sus estudiantes registraron Autoevaluación</td>
                                    </tr>
                                <?php
                                }else{
                                    $count = 1;
                                    foreach($students as $row):
                                ?>
                                    <tr>
                                        <td><?php echo $count;?></td>
                                        <td><?php echo $row['student'];?></td>
                                        <td><?php echo $row['completo'];?></td>
                                        <td>No ha registrado su Autoevaluación</td>
                                    </tr>
                                <?php 
                                    $count += 1;
                                    endforeach;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Card-->

            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--begin::Entry-->