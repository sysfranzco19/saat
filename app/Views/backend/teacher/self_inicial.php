<script type="text/javascript">
    function vnum(e) {
        tecla = e.which || e.keyCode;
        patron = /\d/; // Solo acepta números
        te = String.fromCharCode(tecla);
        return (patron.test(te) || tecla == 9 || tecla == 8);
    }
</script>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                AUTOEVALUACIÓN (Primaria 1ro-2do) - <?php echo $completo;?> 
                
                </h3>
                <span><?php echo $phase_name;?> </span>
            </div>
            <div class="card-body">
            <table id='tabla' class="table">
                <thead class="thead-inverse">
                    <tr>
                        <th class='col-sm-6'>Estudiantes<br /></th>
                        <th class='col-sm-2'><a id="auto_ser" class="negro">Autoevaluacion SER</a><br /></th>
                        <th class='col-sm-2'><a id="auto_decidir" class="negro">Autoevaluacion DECIDIR</a><br /></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //$consulta='SELECT student_id, name, lastname, lastname2, section_id, retirement_date FROM student WHERE section_id='.$section_id.' AND activo=1 ORDER BY lastname, lastname2';
                    //$students = $this->db->query($consulta)->result_array();
                    foreach($students as $row){
                    ?>
                    <tr>
                        <td>
                            <?php 
                                //
                                    if ($section_id<231){
                            ?>
                            
                            <a  onclick="showAjaxModal('<?php echo base_url();?>index.php/modal/popup/self_modal_edit/<?php echo $row['student_id'];?>/<?php echo $row['student'];?>/<?php echo $section_id;?>');">
                            <?php 
                                    }
                                
                            ?>
                            <?php echo $row['student'];?>
                            </a>

                        </td>

                        <?php
                        $existe=0;
                            foreach ($autos as $auto):
                            //$autos=$this->db->query('SELECT self_id, ser5, dec5 FROM self_appraisal WHERE phase_id='.$phase_id.' and student_id='.$row['student_id'])->result_array();
                            if ($auto['student_id']==$row['student_id']){
                                $existe=1;
                                    ?>
                                    <td><div class="alert alert-info">
                                        <strong><?php echo $auto['ser5']; ?></strong>
                                    </div></td>
                                    <td><div class="alert alert-info">
                                        <strong><?php echo $auto['dec5']; ?></strong>
                                    </div></td>
                                    
                                <?php
                                //echo "<td class='negro'>$total_auto</td>";
                                break;
                            }
                            
                            
                        endforeach;
                        if ($existe==0) {
                            $estado='<td><div class="alert alert-warning"><strong>P</strong></div></td>';
                            if (!is_null($row['retirement_date'])){
                                $estado='<td><div class="alert alert-danger"><strong>R</strong></div></td>';
                            }
                            //autoevaluacion del SER
                            echo $estado;
                            //autoevaluacion del SABER
                            echo $estado;
                        }
                        ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
            