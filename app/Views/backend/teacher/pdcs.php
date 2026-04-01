<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Plan de Desarrollo Curricular
                    <span class="d-block text-muted pt-2 font-size-sm">Listado de Planes</span></h3>
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
                            <th>Plan de Desarrollo Curricular</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($materias as $row):
                        ?>
                        <tr>
                            <td><?php echo $row->curso;?></td>
                            <td><?php 
                            $nomArchivo = "PDC_".$row->subject_id."_".$phase_id.".pdf";
                            $nombre_fichero = $_SERVER['DOCUMENT_ROOT'] ."/plataforma/public/uploads/PDCs/".$nomArchivo;
                            if (file_exists($nombre_fichero)) {
                                ?>
                                <a href="<?php echo base_url();?>uploads/PDCs/<?php echo $nomArchivo;?>" target="_blank" class="btn btn-text-warning btn-hover-light-warning font-weight-bold mr-2">Ver PDC <?php echo $row->materia;?></a>
                                <!--<a href="http://tiquipaya.edu.bo/saat2023/uploads/PDCs/<?php echo $nomArchivo;?>" target="_blank" class="btn btn-text-warning btn-hover-light-warning font-weight-bold mr-2">Ver PDC <?php echo $row->materia;?></a>-->
                                <a onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/modal_upfile/<?php echo $row->curso;?>/<?php echo $row->materia;?>/<?php echo $row->subject_id;?>/pdcs/0');" class="btn btn-text-primary btn-hover-light-primary font-weight-bold mr-2">Cambiar</a>
                                <?php
                            } else {
                                ?>
                            <button type="button" class="btn btn-warning" 
                            onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/modal_upfile/<?php echo $row->curso;?>/<?php echo $row->materia;?>/<?php echo $row->subject_id;?>/pdcs/0');" >Subir PDC de <?php echo $row->materia;?></button>
                                <?php
                            }
                            ?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>