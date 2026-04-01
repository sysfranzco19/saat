<script type="text/javascript">
    function enviarAusencia(ausencia_id, student_id){
        $.ajax({
            url: "<?php echo base_url(); ?>secretary/absence_send/"+ausencia_id+"/"+student_id,
            type: "get",
            beforeSend: function(){
                document.getElementById('mostrar_loading').style.display="block"
            },
            success: function(response){
                document.getElementById('mostrar_loading').style.display="none"
                var uno = document.getElementById('btnEnviar');
                if (uno.innerHTML == 'Enviar') uno.innerHTML = 'Volver a Enviar';
                //document.getElementById('mostrar_tabla').innerHTML=response;
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
                    <h3 class="card-label">Estudiantes sin licencia
                    <span class="text-muted pt-2 font-size-sm d-block">Muestra datos de Estudiantes sin Licencia</span></h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/absence_add" class="btn btn-danger font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Nueva ausencia</a>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/lisences_reports" class="btn btn-success font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <path d="M4.85714286,1 L11.7364114,1 C12.0910962,1 12.4343066,1.12568431 12.7051108,1.35473959 L17.4686994,5.3839416 C17.8056532,5.66894833 18,6.08787823 18,6.52920201 L18,19.0833333 C18,20.8738751 17.9795521,21 16.1428571,21 L4.85714286,21 C3.02044787,21 3,20.8738751 3,19.0833333 L3,2.91666667 C3,1.12612489 3.02044787,1 4.85714286,1 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                <path d="M6.85714286,3 L14.7364114,3 C15.0910962,3 15.4343066,3.12568431 15.7051108,3.35473959 L20.4686994,7.3839416 C20.8056532,7.66894833 21,8.08787823 21,8.52920201 L21,21.0833333 C21,22.8738751 20.9795521,23 19.1428571,23 L6.85714286,23 C5.02044787,23 5,22.8738751 5,21.0833333 L5,4.91666667 C5,3.12612489 5.02044787,3 6.85714286,3 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fill-rule="nonzero"/>
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Reportes</a>
                    <!--end::Button-->



                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                        <th>Estudiante</th>
                        <th>Docente/Materia</th>
                        <th>Detalle</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach($ausencias as $key):
                        ?>
                        <tr>
                            <td><?php echo $key->student; ?> <b><?php echo $key->nick_name; ?></b></td>
                            <td><?php echo $key->doc_materia; ?></td>
                            <td><?php echo $key->obs; ?></td>
                            <td><?php echo $key->fecha; ?></td>
                            <td><?php echo $key->hora; ?></td>
                            <td>
                            <div class="btn-group dropright">
                                <button type="button" class="btn btn-secondary btn-sm font-weight-bold mr-2 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Acción
                                </button>
                                <div class="dropdown-menu">
                                    <ul class="navi flex-column navi-hover py-2">

                                        <li class="navi-item">
                                            <a href="<?php echo base_url('secretary/absence_edit/'.$key->ausencia_id);?>" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="far fa-edit text-warning mr-5"></i>
                                                </span>
                                                <span class="navi-text">Modificar</span>
                                            </a>
                                        </li>
                                        <li class="navi-item">
                                            <a href="<?php echo base_url('secretary/absence_report/'.$key->ausencia_id);?>" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="la la-file-pdf-o text-primary mr-5"></i>
                                                </span>
                                                <span class="navi-text">Imprimir</span>
                                            </a>
                                        </li>
                                        <?php
                                        $env = "Volver a Enviar";
                                        if ($key->enviado==0) {
                                            $env = "Enviar";
                                        }
                                        ?>
                                        <li class="navi-item">
                                            <a onclick="enviarAusencia('<?php echo $key->ausencia_id ?>', '<?php echo $key->student_id ?>');" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="fa fa-envelope-open-text text-success mr-5"></i>
                                                </span>
                                                <span class="navi-text"><?php echo $env; ?></span>
                                            </a>
                                        </li>
                                        <li class="navi-item">
                                            <a onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/absence_modal_del/<?php echo $key->ausencia_id ?>/0/0/0/0');" class="navi-link">
                                                <span class="navi-icon">
                                                    <i class="far fa-window-close text-danger mr-5"></i>
                                                </span>
                                                <span class="navi-text">Eliminar

                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <!--end::Navigation-->
                                </div>
                            </div>





                                <!-- 
                                <a href="<?php echo base_url('secretary/absence_edit/'.$key->ausencia_id);?>" class="btn btn-warning btn-sm" >Modificar</a>
                                <a href="<?php echo base_url('secretary/absence_report/'.$key->ausencia_id);?>" target="_blank" class="btn btn-secondary btn-sm" >Imprimir</a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/absence_modal_del/<?php echo $key->ausencia_id ?>/0/0/0/0');" >
                                Eliminar
                                </button>
                                <?php
                                if ($key->enviado==0) {
                                ?>
                                <button type="button" id="btnEnviar" class="btn btn-success btn-sm" onclick="enviarAusencia('<?php echo $key->ausencia_id ?>', '<?php echo $key->student_id ?>');" >Enviar</button>
                                <div id="mostrar_loading" class="spinner spinner-primary spinner-lg mr-15 spinner-center" style="display:none;"></div>
                                <?php
                                }else{
                                ?>
                                <button type="button" id="btnEnviar" class="btn btn-success btn-sm" onclick="enviarAusencia('<?php echo $key->ausencia_id ?>', '<?php echo $key->student_id ?>');" >Volver a Enviar</button>
                                <div id="mostrar_loading" class="spinner spinner-primary spinner-lg mr-15 spinner-center" style="display:none;"></div>
                                <?php
                                }
                                ?>

                                 -->

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