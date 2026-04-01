<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-lg-12">
                
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Cartas de Contenidos</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Accordion-->
                        <div class="accordion accordion-solid accordion-toggle-plus" id="accordionExample6">
                            <?php
                            $count = 0;
                            foreach($students as $stu):
                            ?>
                            <div class="card">
                                <div class="card-header" id="headingOne6<?php echo $stu['student_id'];?>">
                                    <div class="card-title<?php if($count!=0){echo " collapsed";} ?>" data-toggle="collapse" data-target="#collapseOne6<?php echo $stu['student_id'];?>">
                                    <i class="flaticon2-user"></i><?php echo $stu['name'];?> <?php echo $stu['lastname'];?> <?php echo $stu['lastname2'];?> - <?php echo $stu['completo'];?></div>
                                </div>
                                <div id="collapseOne6<?php echo $stu['student_id'];?>" class="collapse<?php if($count==0){echo " show";} ?>" data-parent="#accordionExample6">
                                    <div class="card-body">
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
                                                $count=0;
                                                if (isset($document['letter'.$stu['section_id']])) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $stu['completo'];?></td>
                                                        <td>
                                                            <a href="<?php echo $document['letter'.$stu['section_id']];?>" target="_blank" class="btn btn-info active">Visualizar</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }else{
                                                    foreach($document['materias'.$stu['section_id']] as $row):
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
                                                            <a href="<?php echo base_url();?>/uploads/content_letter/<?php echo $nomArchivo;?>" target="_blank" class="btn btn-info active">Visualizar</a>
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
                            </div>
                            <?php
                            $count+=1;
                            endforeach;
                            ?>
                        </div>
                        <!--end::Accordion-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->