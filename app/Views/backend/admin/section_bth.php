<script type="text/javascript">
    function recover_self(subject_id, sheet_id){
        // Codificar los parámetros para la URL
        const encodedSubjectId = encodeURIComponent(subject_id);
        const encodedSheetId = encodeURIComponent(sheet_id);
        
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/admin/recover_self_esp/" + encodedSubjectId + "/" + encodedSheetId,
            type: "get",
            beforeSend: function(){
                document.getElementById('mostrar_loading').style.display = "block";
            },
            success: function(response){
                document.getElementById('mostrar_loading').style.display = "none";
                document.getElementById('mostrar_tabla').innerHTML = response;
            },
        });
    }
    function centralizer_esp(subject_id, sheet_id, materia){
        // Codificar los parámetros para la URL
        const encodedSubjectId = encodeURIComponent(subject_id);
        const encodedSheetId = encodeURIComponent(sheet_id);
        const encodedMateria = encodeURIComponent(materia);
        
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/admin/centralizer_notes_esp/" + encodedSubjectId + "/" + encodedSheetId+ "/" + encodedMateria,
            type: "get",
            beforeSend: function(){
                document.getElementById('mostrar_loading').style.display = "block";
            },
            success: function(response){
                document.getElementById('mostrar_loading').style.display = "none";
                document.getElementById('mostrar_tabla').innerHTML = response;
            },
        });
    }
    function notes_tecnologia(section_id){
        var parametros = {
            "plistar" : "listado",
        };

        $.ajax({
            data: parametros,
            url: "<?php echo base_url(); ?>index.php/admin/centralize_notes_bth/" + section_id,
            type: "post",
            beforeSend: function(){
                document.getElementById('mostrar_loading').style.display="block"
            },
            success: function(response){
                document.getElementById('mostrar_loading').style.display="none"
                document.getElementById('mostrar_tabla').innerHTML=response;
            },
        });
        
    }
    function centralizador_bth(section_id){
        var parametros = {
            "plistar" : "listado",
        };

        $.ajax({
            data: parametros,
            url: "<?php echo base_url(); ?>index.php/admin/centralizador_bth/" + section_id,
            type: "post",
            beforeSend: function(){
                document.getElementById('mostrar_loading').style.display="block"
            },
            success: function(response){
                document.getElementById('mostrar_loading').style.display="none"
                document.getElementById('mostrar_tabla').innerHTML=response;
            },
        });
        
    }
</script>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        <div class="text-center pt-15">
            <h1 class="h2 font-weight-bolder text-dark mb-6">Cursos BTH</h1>
        </div>
        <p class="bg-primary text-white py-5 px-5">Lista de Cursos BTH</p>
        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th><div>Curso</div></th>
                    <th><div>Materias</div></th>
                    <th><div>Crear Notas</div></th>
                    <th><div>Notas</div></th>
                    <th><div>Centralizador</div></th>
                </tr>
            </thead>
            <tbody>
                <?php
                //$sql1 = 'SELECT * FROM section WHERE active=1 AND section_id>270 ORDER BY section_id';
                //$sections = $this->db->query($sql1)->result_array();
                foreach($sections as $row):
                ?>
                <tr>
                    <td><?php echo $row['completo']; ?></td>
                    <td><a href="<?php echo base_url(); ?>index.php/admin/subject_bth/<?php echo $row['section_id']; ?>" class='btn btn-light-success font-weight-bold mr-2'>Ir a las Planillas</a>
                    </td>
                    <td><a href="<?php echo base_url(); ?>index.php/admin/create_notes_bth/<?php echo $row['section_id']; ?>" class="btn btn-light-warning font-weight-bold mr-2">Crear</a>
                    </td>
                    <td>
                    <a href="<?php echo base_url(); ?>index.php/admin/centralize_notes_bth/<?php echo $row['section_id']; ?>/<?php echo $row['subject_id']; ?>" class='btn btn-secondary font-weight-bold mr-2' >Centralizar Notas</a>
                    </td>
                    <td>
                        <a href="<?php echo base_url(); ?>index.php/admin/centralizador_bth/<?php echo $row['section_id']; ?>/<?php echo $row['subject_id']; ?>" class='btn btn-light-primary font-weight-bold mr-2' target="_blank" >Descargar</a>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>




        <div class="text-center pt-15">
            <h1 class="h2 font-weight-bolder text-dark mb-6">Especialidades BTH</h1>
        </div>
        <p class="bg-primary text-white py-5 px-5">Lista de Especialidad BTH</p>
        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th><div>Código</div></th>
                    <th><div>Especialidad</div></th>
                    <th><div>Docente</div></th>
                    <th><div>Planillas</div></th>
                    <th><div>Autoevaluación</div></th>
                    <th><div>Centralizar</div></th>
                </tr>
            </thead>
            <tbody>
                <?php
                //$sql1 = 'SELECT * FROM section WHERE active=1 AND section_id>270 ORDER BY section_id';
                //$sections = $this->db->query($sql1)->result_array();
                foreach($subjects as $sub):
                ?>
                <tr>
                    <td><?php echo $sub['subject_id']; ?></td>
                    <td><?php echo $sub['materia']; ?></td>
                    <td><?php echo $sub['docente']; ?></td>
                    <td>
                        <a href="https://docs.google.com/spreadsheets/d/<?php echo $sub['sheet_id']; ?>/edit" target="_blank" class="btn btn-success btn-sm" >Ir a la Planilla</a>
                    </td>
                    <td>
                        <a onclick='recover_self(<?= $sub['subject_id'] ?>, <?= json_encode($sub['sheet_id']) ?>);' class='btn btn-light-warning font-weight-bold mr-2'>Recuperar Autoevaluaciones</a>
                    </td>
                    <td>
                        <a onclick='centralizer_esp(<?= $sub['subject_id'] ?>, <?= json_encode($sub['sheet_id']) ?>, <?= json_encode($sub['materia']) ?>);' class='btn btn-light-primary font-weight-bold mr-2'>Centralizar notas</a>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
        <div id="mostrar_loading" class="spinner spinner-primary spinner-lg mr-15 spinner-center" style="display:none;"></div>
        <div class="card-body" id="mostrar_tabla"></div>
    </div>
</div>