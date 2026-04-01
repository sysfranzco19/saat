<script type="text/javascript">
    function enviarLicencia(licencia_id, student_id){
        $.ajax({
            url: "<?php echo base_url(); ?>secretary/license_send/"+licencia_id+"/"+student_id,
            type: "get",
            beforeSend: function(){
                document.getElementById('mostrar_loading').style.display="block"
            },
            success: function(response){
                document.getElementById('mostrar_loading').style.display="none"
                var uno = document.getElementById('btnEnviar');
                if (uno.innerHTML == 'Enviar') uno.innerHTML = 'Volver a Enviar';
            },
        });
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
                    <h3 class="card-label">Planilla de Indisciplina - <?php echo $curso; ?>
                    <span class="text-muted pt-2 font-size-sm d-block">Muestra todas las faltas cometidas por los estudiantes.</span></h3>
                </div>
                <div class="card-toolbar">
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Estudiante</th>
                            <th>Faltas</th>
                            <th>Seguimiento</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        
                        foreach($students as $row):
                                $faltas = 0;
                                $supervised = 0;
                                foreach($infractions as $inf):
                                    if ($inf['student_id']==$row['student_id']) {
                                        $faltas += 1;
                                        if ($inf['number']==4) {
                                            # code...
                                            $supervised = $inf['supervised'];
                                        }
                                    }
                                endforeach;
                            ?>
                        <tr>
                            <td><?php echo $row['student']; ?> <b>[<?php echo $faltas; ?>]</b></td>
                            <td>
                                <?php 
                                foreach($infractions as $inf):
                                    if ($inf['student_id']==$row['student_id']) {
                                ?>
                                        <span class="label label-lg label-light-<?php echo $inf['style']; ?> label-inline"><?php echo $inf['materia']; ?> - <?php echo $inf['criteria']; ?></span>
                                        <button type="button" class="btn btn-icon btn-primary btn-xs" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/infractions_modal_edit/<?php echo $section_id; ?>/<?php echo $row['student_id'] ?>/<?php echo $inf['infraction_id'] ?>/0/0');" >
                                            <i class="flaticon-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-danger btn-xs" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/infractions_modal_del/<?php echo $section_id; ?>/<?php echo $row['student_id'] ?>/<?php echo $inf['infraction_id'] ?>/0/0');" >
                                            <i class="flaticon-delete-1"></i>
                                        </button>
                                        <br />
                                        
                                <?php 
                                    }
                                endforeach; ?>
                            </td>
                            <td>
                                <?php
                                switch($faltas){
                                    case 0:
                                        echo "";
                                        break;
                                    case 1:                                        
                                        echo '<button class="btn font-weight-bold btn-outline-dark btn-sm" disabled>Falta(s) <span class="label label-secondary ml-2">'.$faltas.'</span></button>';
                                        break;
                                    case 2:                                        
                                        echo '<button class="btn font-weight-bold btn-outline-dark btn-sm" disabled>Falta(s) <span class="label label-secondary ml-2">'.$faltas.'</span></button>';
                                        break;
                                    case 3:                                        
                                        echo '<button class="btn font-weight-bold btn-outline-dark btn-sm" disabled>Falta(s) <span class="label label-secondary ml-2">'.$faltas.'</span></button>';
                                        break;
                                    case 4:
                                        echo '<a class="btn font-weight-bold btn-light-warning btn-sm" href="'.base_url().'teacher/infraction_letter/'.$row['student_id'].'" >Carta Generada<span class="label label-warning ml-2">'.$faltas.'</span></a>';
                                        break;
                                    case 5:
                                        echo '<button class="btn font-weight-bold btn-light-warning btn-sm" >Carta Generada<span class="label label-warning ml-2">'.$faltas.'</span></button>';
                                        break;
                                    case 6:
                                        echo '<button class="btn font-weight-bold btn-light-warning btn-sm" >Carta Generada<span class="label label-warning ml-2">'.$faltas.'</span></button>';
                                        break;
                                    case 7:                                        
                                        echo '<button type="button" class="btn btn-light-danger font-weight-bold mr-2" disabled>BOLETA <span class="label label-danger ml-2">'.$faltas.'</span></button>';
                                        break;
                                    default:
                                        echo '<button type="button" class="btn btn-danger" disabled>BOLETA</button><br />';
                                        echo '<button type="button" class="btn btn-light-danger font-weight-bold mr-2" disabled>Reincidencia <span class="label label-danger ml-2">'.$faltas.'</span></button>';
                                }
                                ?>

                            </td>
                            <td>
                            <button type="button" class="btn btn-success btn-sm mr-2" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/infractions_modal_add/<?php echo $section_id; ?>/<?php echo $row['student_id'] ?>/<?php echo $faltas; ?>/0/0');" >
                                <i class="flaticon-add"></i>
                                Añadir
                            </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->