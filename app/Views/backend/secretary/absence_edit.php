<script type="text/javascript">
    $(document).ready(function() {
        $('#student').select2();
    });
    function fillDatos(student_id){
        var parametros = {
			"student_id" : student_id,
		};
        $.ajax({
			data: parametros,
			url: "<?php echo base_url('server/student_fill'); ?>",
			type: "post",
			success: function(response){
                var content = JSON.parse(response);
                var section_id = content[0].section_id
                fill_subject_section(section_id);
                for(var i = 0;i < content.length; i++)
                    {
                    document.getElementById('student_id').value = content[i].student_id;
                    document.getElementById('cod_student').value = content[i].student_id;
                    document.getElementById('family_id').value = content[i].family_id;
                    document.getElementById('email').value = content[i].email;
                    document.getElementById('nombre').value = content[i].nombre;
                    document.getElementById('section').value = content[i].completo;
                    document.getElementById('section_id').value = content[i].section_id;
                    document.getElementById("tipo").disabled = false;
                    }
            },
		});
    }
    function fill_subject_section(section_id){
        for (let i = document.form_absence.subjects.options.length; i >= 0; i--) {
            document.form_absence.subjects.remove(i);
        }
        var parametros2 = {
			"section_id" : section_id,
		};
        $.ajax({
			data: parametros2,
			url: "<?php echo base_url('server/fill_subject_section'); ?>",
			type: "post",
			success: function(response){
                var content2 = JSON.parse(response);
                var objetoCombo = document.form_absence.subjects;
                for(var i = 0;i < content2.length; i++) 
                    {
                        var valor = content2[i].docente + ' - ' + content2[i].name;
                        if (content2[i].subject_id==<?php echo $ausencias[0]['subject_id']; ?>) {
                            objetoCombo.options[objetoCombo.options.length] = new Option(valor, content2[i].subject_id, true, true);
                        }else{
                            objetoCombo.options[objetoCombo.options.length] = new Option(valor, content2[i].subject_id);
                        }
                        
                    }
            },
		});
    }
    section_id.oninput = function() {
        alert(section_id.value);
        //result.innerHTML = input.value;
    };

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("form_ausencia").addEventListener('submit', validarFormulario); 
    });
    function validarFormulario(evento) {
        var student_id = document.getElementById('student').value;
        var medio = document.getElementById('medio').value;
        //alert(student_id);
        evento.preventDefault();
        if(student_id.length == 0) {
            alert('Debe seleccionar un Estudiante');
            document.getElementById("student").focus();
            return;
        }
        if(medio.length == 0) {
            alert('Debe seleccionar el medio de comunicación');
            document.getElementById("medio").focus();
            return;
        }
        this.submit();
    }
    $( document ).ready(function() {

        var now = new Date();

        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);

        var today = (day)+"/"+(month)+"/"+now.getFullYear() ;
        $("#fecha").val(today);
    });
</script>
<?php
$fecha_actual = date("Y-m-d");
$hora_actual = date("H:i");
?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <?php foreach($ausencias as $aus):
            ?>
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
                                    $sel_student = "";
                                    if ($stu['student_id']==$aus['student_id']) { $sel_student = "selected";}
                                ?>
                                    <option value=<?php echo $stu['student_id'];?> <?php echo $sel_student;?> ><?php echo $stu['lastname']." ".$stu['lastname2']." ". $stu['name'];?> - 
                                    <b><?php echo $stu['nick_name'];?></b></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <div class="form-group row">
                        <div class="col-6">
                            <label>Id Alumno</label>
                            <input type="text" id="cod_student" name="cod_student" class="form-control" value="<?php echo $aus['student_id'];?>" disabled>
                            </div>
                            <div class="col-6">
                            <label>Id Familia</label>
                            <input type="text" id="family_id" name="family_id" class="form-control" value="<?php echo $aus['family_id'];?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Estudiante</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $aus['student'];?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Nombre Curso</label>
                            <input type="text" id="section_id" name="section_id" class="form-control" value="<?php echo $aus['completo'];?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Correo Institucional</label>
                            <input type="text" id="email" name="email" class="form-control" value="<?php echo $aus['email'];?>" disabled>
                        </div>
                    </div>
                </div>
                <!--end::Example-->
            </div>
            
			<div class="col-xl-8">
                <form class="form-horizontal" method="POST" action="<?php echo base_url().$account_type.'/absence_update'; ?>" id="form_absence" name="form_absence" >
                    <input type="hidden" id="student_id" name="student_id" value="<?php echo $aus['student_id']; ?>">
                    <input type="hidden" id="ausencia_id" name="ausencia_id" value="<?php echo $aus['ausencia_id']; ?>">
                    <!--begin::Card-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">Datos de la ausencia</h3>
                            </div>
                            <div class="card-toolbar">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-8">
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">Docente - Materia:<?php echo $ausencias[0]['subject_id']; ?></label>
                                    <div class="col-10">
                                        <select class="form-control" style="width: 100%;" id="subjects" name="subjects" required >
                                            <option value="">Seleccione al docente <?php echo $aus['subject_id'];?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">Periodo:</label>
                                    <div class="col-10">
                                        <select class="form-control" style="width: 100%;" id="periodo" name="periodo" required  >
                                            <option value="">Seleccione el Periodo</option>
                                            <?php for ($i = 1; $i <= 9; $i++) {?>
                                                <option value="<?php echo $i; ?>" <?php if($aus['periodo']==$i){echo "selected";} ?> >Periodo <?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Date -->
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Fecha Ausencia:</label>
                                    <div class="col-10">
                                        <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $aus['fecha'];?>" required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label">Hora Ausencia:</label>
                                    <div class="col-10">
                                        <input type="time" id="hora" name="hora" class="form-control" value="<?php echo $aus['hora'];?>" required >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="obs" class="col-2 col-form-label">Obs:</label>
                                    <div class="col-10">
                                        <input type="text" id="obs" name="obs" class="form-control" value="<?php echo $aus['obs'];?>" required  >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-2 col-form-label"><br />Cantidad Periodos: </label>
                                    <div class="col-10">
                                    <input type="number" class="form-control" value="<?php echo $aus['cantidad'];?>" id="cantidad" name="cantidad" required  >
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary mr-2">Guardar Cambios</button>
                                <a href="<?php echo base_url('secretary/absences');?>" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </div>
                    </div>
                    <!--end::Card-->
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
<script type="text/javascript">
fill_subject_section(<?php echo $ausencias[0]['section_id']; ?>);
</script>