<script type="text/javascript">

    $(document).ready(function() {
        $('#student').select2();
    });
    function fillDatos(student_id){
        var parametros = {
			"student_id" : student_id,
		};
        localStorage.setItem("selectedStudent", student_id);
        $.ajax({
			data: parametros,
			url: "<?php echo base_url('index.php/server/student_fill'); ?>",
			type: "post",
			success: function(response){
                var content = JSON.parse(response);
                var section_id = content[0].section_id;
                //document.getElementById('btnNuevo').onclick = showAjaxModal('<?php echo base_url();?>/modal/popup/delay_modal_add/'+student_id+'/0/0/0/0');
                document.getElementById('btnNuevo').setAttribute("onclick", "showAjaxModal('<?php echo base_url();?>/modal/popup/delay_modal_add/"+student_id+"/<?php echo $entry_time; ?>/0/0/0');");
                fillDelays(student_id);
                for(var i = 0;i < content.length; i++)
                {
                    document.getElementById('student_id').value = content[i].student_id;
                    document.getElementById('cod_student').value = content[i].student_id;
                    document.getElementById('family_id').value = content[i].family_id;
                    document.getElementById('email').value = content[i].email;
                    document.getElementById('nombre').value = content[i].nombre;
                    document.getElementById('section').value = content[i].completo;
                }
                
            },
		});
    }
    function fillDelays(student_id){
        $.ajax({
			url: "<?php echo base_url(); ?>index.php/server/fill_delays_student/" + student_id,
			success: function(response)
			{
                var content = JSON.parse(response);
                var tableBody = $("#retrasos tbody"); 
                tableBody.empty();
                for(var i = 0;i < content.length; i++)
                {
                    var delay = content[i];

                    // Crear una nueva fila y agregar celdas con los datos del retardo
                    var newRow = $("<tr>");
                    newRow.append("<td>" + delay.date_class + "</td>");
                    newRow.append("<td>" + delay.hora_ingreso + "</td>");
                    newRow.append("<td>" + delay.hora_llegada + "</td>");
                    newRow.append("<td>" + delay.motivo + "</td>");
                    newRow.append("<td>" + delay.tarde_con + " min.</td>");

                    // Crear el botón y adjuntarlo a la fila
                    var button = $("<button>").attr({
                        type: "button",
                        class: "btn btn-danger btn-sm",
                        onclick: "showAjaxModal('<?php echo base_url();?>index.php/modal/popup/delay_modal_del/"+delay.delay_id+"/"+student_id+"/0/0/0');"
                    }).html("<i class='flaticon-delete-1'></i>");
                    newRow.append(button);
                    // Crear el botón y adjuntarlo a la fila
                    var button2 = $("<button>").attr({
                        type: "button",
                        class: "btn btn-warning btn-sm",
                        onclick: "showAjaxModal('<?php echo base_url();?>index.php/modal/popup/delay_modal_edit/"+delay.delay_id+"/"+student_id+"/0/0/0');"
                    }).html("<i class='flaticon-edit'></i>");
                    // Agregar el botón a la fila
                    newRow.append(button2);
                    // Agregar la nueva fila al cuerpo de la tabla
                    tableBody.append(newRow);
                    var elemento = document.getElementById("down_1er");
                    var elemento = document.getElementById("down_2do");
                    var elemento = document.getElementById("down_3er");
                    var elemento = document.getElementById("down_all");
                    elemento.href = "<?php echo base_url(); ?>index.php/secretary/delay_xlsx/" + student_id + "/1";
                    elemento.href = "<?php echo base_url(); ?>index.php/secretary/delay_xlsx/" + student_id + "/2";
                    elemento.href = "<?php echo base_url(); ?>index.php/secretary/delay_xlsx/" + student_id + "/3";
                    elemento.href = "<?php echo base_url(); ?>index.php/secretary/delay_xlsx/" + student_id + "/0";
                }
			},
		});
    }
    function mostrarMensaje() {
      alert("Por favor, selecciona un estudiante.");
      document.getElementById("student").focus();
    }
    <?php
    if(isset($student_id)){
        echo "fillDatos(".$student_id.");";
    }
    ?>
</script>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Retrasos
                    <span class="text-muted pt-2 font-size-sm d-block">Muestra todos los retrasos del(la) Estudiante</span></h3>
                </div>
                <div class="card-toolbar">
                    
                </div>
            </div>
            <div class="card-body">
            <div class="row">
            <div class="col-md-4">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Datos Estudiante</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Estudiante:</label>
                            <select class="form-control select2" style="width: 100%;" id="student" name="student" onChange="fillDatos(this.value);">
                                <option value="">Seleccione al Estudiante</option>
                                <?php
                                foreach($students as $stu):
                                ?>
                                    <option value="<?php echo $stu['student_id'];?>" <?php if($stu['student_id']==$student_id){ echo "selected"; };?>><?php echo $stu['lastname']." ".$stu['lastname2']." ". $stu['name'];?> - <b><?php echo $stu['nick_name'];?></b></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                        
                            <div class="form-group row">
                        <div class="col-6">
                            
                            <label>Id Alumno</label>
                            <input type="text" id="cod_student" name="cod_student" class="form-control" disabled>
                            <input type="hidden" id="student_id" name="student_id">
                        </div>
                        <div class="col-6">
                            <label>Id Familia</label>
                            <input type="text" id="family_id" name="family_id" class="form-control" disabled>
                        </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <label>Estudiante</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Nombre Curso</label>
                            <input type="text" id="section" name="section" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Correo Institucional</label>
                            <input type="text" id="email" name="email" class="form-control" disabled>
                        </div>
                    </div>
                </div>
                <!--end::Example-->
            </div>
            
			<div class="col-xl-8">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">Retrasos</h3>
                            </div>
                            <div class="card-toolbar">
                                <button id="btnNuevo" class="btn btn-success mr-2" onclick="mostrarMensaje()" >Nuevo</button>
                                <div class="btn-group" role="group">
                                    <button id="btnGroupVerticalDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Descargar
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                                        <a id="down_1er" class="dropdown-item" href="<?php echo base_url(); ?>index.php/secretary/delay_xlsx/2660/1" target="_blank" >1er TRIM</a>
                                        <a id="down_2do" class="dropdown-item" href="<?php echo base_url(); ?>index.php/secretary/delay_xlsx/2660/2" target="_blank" >2do TRIM</a>
                                        <a id="down_3er" class="dropdown-item" href="<?php echo base_url(); ?>index.php/secretary/delay_xlsx/2660/3" target="_blank" >3er TRIM</a>
                                        <a id="down_all" class="dropdown-item" href="<?php echo base_url(); ?>index.php/secretary/delay_xlsx/2660/0" target="_blank" >Todos</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-8">
                                <table class="table" id="retrasos" >
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th><div>F. Retraso</div></th>
                                            <th><div>H. Ingreso</div></th>
                                            <th><div>H. Llegada</div></th>
                                            <th><div>Motivo del Retraso</div></th>
                                            <th><div>Tarde con:</div></th>
                                            <th><div>Acción</div></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php //foreach($datos as $key):?>
                                        <tr>
                                            
                                        </tr>
                                        <?php //endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                            </div>
                        </div>
                    </div>
                    <!--end::Card-->
            </div>
        </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
<script>

    </script>