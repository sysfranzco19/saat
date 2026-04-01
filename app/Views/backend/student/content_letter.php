<script type="text/javascript">
    function confirmar()
    {
        var respuesta = confirm("¿Esta seguro de realizar los Cambios?");
        if (respuesta == true){
            document.form_teacher.submit(); 
        }else{
            return false;
        }
    }
    function imprim1(imp1){
        var printContents = document.getElementById('imp1').innerHTML;
        w = window.open();
        w.document.write(printContents);
        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10
        w.print();
        w.close();
        return true;
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
                    <h3 class="card-label">Cartas de Contenidos
                    <span class="d-block text-muted pt-2 font-size-sm">Listado de Cartas de Contenido</span></h3>
                </div>
                <div class="card-toolbar">

                </div>
            </div>
            <div class="card-body" id="imp1">
                                        
                <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Materia</th>
                            <th>Carta de Contenidos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //RECUPERAMOS EL BIMESTRE ACTUAL
                        
                        $count=0;
                        /*
                        $csl1 = 'SELECT t1.subject_id, t2.completo as curso, t1.name as materia FROM subject AS t1 
                                    INNER JOIN section AS t2 ON(t1.section_id=t2.section_id) WHERE t1.teacher_id='.$teacher_id;
                                    */
                        //$students = $this->db->order_by('lastname', 'ASC')->get_where('student' , array('class_id'=>$class_id , 'section_id' => $row['section_id']))->result_array();
                        //$students = array();
                        if (isset($letter)) {
                            ?>
                            <tr>
                                <td><?php echo $curso;?></td>
                                <td>
                                    <a href="<?php echo $letter;?>" target="_blank" class="btn btn-success active">Visualizar</a>
                                </td>
                            </tr>
                            <?php
                            
                        }else{
                            foreach($materias as $row):
                                $count+=1;
                            ?>
                            <tr>
                                <td><?php echo $row['name'];?></td>
                                <td>
                                <?php
                                $nomArchivo = "CC_".$row['subject_id']."_".$phase_id.".pdf";
                                $nombre_fichero = $_SERVER['DOCUMENT_ROOT'] ."/saat2023/public/uploads/content_letter/".$nomArchivo;
                                if (file_exists($nombre_fichero)) {
                                    ?>
                                    <a href="<?php echo base_url();?>/uploads/content_letter/<?php echo $nomArchivo;?>" target="_blank" class="btn btn-success active">Visualizar</a>
                                    <?php
                                }else{
                                    echo "Archivo no subido, consulte con el Docente";
                                }
                                ?>
                                </td>
                            </tr>
                            <?php 
                            endforeach;

                        }
                        ?>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>



           