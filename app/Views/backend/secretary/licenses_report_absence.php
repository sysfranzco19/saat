<script type="text/javascript">
    function enviarLicencia(licencia_id, student_id){
        $.ajax({
            url: "<?php echo base_url(); ?>server/report_license/"+licencia_id+"/"+student_id,
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
    function eliminarFila(index) {
        $("#" + index).remove();
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
                    <h3 class="card-label">Se encontraron Ausencias al registrar Licencia <br />
                    Estudiante: <?php echo $student_name;?>
                    <span class="text-muted pt-2 font-size-sm d-block">Muestras las ausencias registradas por el Docente</span></h3>
                </div>
                
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="POST" action="<?php echo base_url().$account_type.'/licenses_report_teacher'; ?>" id="form_licencia" >
                    <input type="hidden" name="student_name" value="<?php echo $student_name; ?>">
                    <table class="table">
                        <thead class="thead-inverse">
                            <tr>
                            <th>Materia</th>
                            <th>Docente</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($ausencias as $key):?>
                            <tr id="<?php echo $key['assistance_subject_id']; ?>">
                                <td><?php echo $key['materia']; ?></td>
                                <td><?php echo $key['docente']; ?></td>
                                <td><?php echo $key['date_class']; ?></td>
                                <td>
                                    <input type="hidden" name="assistance_subject_id[]" value="<?php echo $key['assistance_subject_id']; ?>">
                                    <input type="hidden" name="email[]" value="<?php echo $key['email']; ?>">
                                    <input type="hidden" name="materia[]" value="<?php echo $key['materia']; ?>">
                                    <input type="hidden" name="docente[]" value="<?php echo $key['docente']; ?>">
                                    <input type="hidden" name="date_class[]" value="<?php echo $key['date_class']; ?>">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(<?php echo $key['assistance_subject_id']; ?>)">Quitar</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning mr-2">Reportar</button>
                        <a href="<?php echo base_url('secretary/licenses');?>" class="btn btn-secondary">Salir</a>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->