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
<?php $session = session(); ?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Cartas de Contenidos - <?php echo $phase_name; ?>
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
                            <th>Curso</th>
                            <th>Carta de Contenidos</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        //RECUPERAMOS EL BIMESTRE ACTUAL
                        //$students = $this->db->order_by('lastname', 'ASC')->get_where('student' , array('class_id'=>$class_id , 'section_id' => $row['section_id']))->result_array();
                        //$students = $this->db->query($csl1)->result_array();
                    foreach($materias as $row):
                        if (is_null($row->link)) {
                            ?>
                            <tr>
                                <td><?php echo $row->curso;?></td>
                                <td><?php //echo $row['subject_id'];
                                //Verificamos si el archivo Existe
                                $nomArchivo = "CC_".$row->subject_id."_".$phase_id.".pdf";
                                $nombre_fichero = $_SERVER['DOCUMENT_ROOT'] ."/plataforma/public/uploads/content_letter/".$nomArchivo;
                                if (file_exists($nombre_fichero)) {
                                    ?>
                                    <a href="<?php echo base_url();?>/uploads/content_letter/<?php echo $nomArchivo;?>" target="_blank" class="btn btn-text-info btn-hover-light-info font-weight-bold mr-2">Ver C.C. <?php echo $row->materia;?></a>
                                    <!--<a href="http://tiquipaya.edu.bo/saat2023/uploads/content_letter/<?php echo $nomArchivo;?>" target="_blank" class="btn btn-text-info btn-hover-light-info font-weight-bold mr-2">Ver C.C. <?php echo $row->materia;?></a>-->
                                    <a onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/modal_upfile/<?php echo $row->curso;?>/<?php echo $row->materia;?>/<?php echo $row->subject_id;?>/0/0');" class="btn btn-text-primary btn-hover-light-primary font-weight-bold mr-2">Cambiar</a>
                                    <?php
                                } else {
                                    ?>
                                <button type="button" class="btn btn-info" 
                                onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/modal_upfile/<?php echo $row->curso;?>/<?php echo $row->materia;?>/<?php echo $row->subject_id;?>/0/0');" >Subir C.C. de <?php echo $row->materia;?></button>
                                    <?php
                                }
                                ?>
                                
                                </td>
                            </tr>
                            <?php 
                        }else{
                            ?>
                            <tr>
                                <td><?php echo $row->curso;?></td>
                                <td>
                                    <a href="<?php echo $row->link;?>" target="_blank" class="btn btn-text-info btn-hover-light-info font-weight-bold mr-2">Ver C.C. <?php echo $row->materia;?></a>
                                </td>
                            </tr>
                            <?php 
                        }
                        
                    endforeach;
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



           