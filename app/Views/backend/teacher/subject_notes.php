<script type="text/javascript">
    function create_sheet(){
		$.ajax({
			//data: parametros,
			url: "<?php echo base_url(); ?>index.php/teacher/create_sheet/<?php echo $subject_id; ?>",
			type: "get",
			beforeSend: function(){
				document.getElementById('mostrar_loading').style.display="block"
			},
			success: function(response){
				document.getElementById('mostrar_loading').style.display="none"
				document.getElementById('mostrar_tabla').innerHTML=response;
                document.getElementById('estado1').innerHTML="<span class='label label-inline label-light-success font-weight-bold'>Completado</span>";
			},
		});
    }
    function integrate_sheet(){

		$.ajax({
			//data: parametros,
			url: "<?php echo base_url(); ?>index.php/teacher/integrate_sheet/<?php echo $subject_id; ?>",
			type: "get",
			beforeSend: function(){
				document.getElementById('mostrar_loading').style.display="block"
			},
			success: function(response){
				document.getElementById('mostrar_loading').style.display="none"
				document.getElementById('mostrar_tabla').innerHTML=response;
                document.getElementById('estado2').innerHTML="<span class='label label-inline label-light-success font-weight-bold'>Completado</span>";
			},
		});
    }
    function link_sheet(){
		$.ajax({
			url: "<?php echo base_url(); ?>index.php/teacher/link_sheet/<?php echo $subject_id; ?>",
			type: "get",
			beforeSend: function(){
				document.getElementById('mostrar_loading').style.display="block"
			},
			success: function(response){
				document.getElementById('mostrar_loading').style.display="none"
				document.getElementById('mostrar_tabla').innerHTML=response;
                document.getElementById('estado3').innerHTML="<span class='label label-inline label-light-success font-weight-bold'>Completado</span>";
			},
		});
    }
	function check_sheet_des(){
		var parametros = {
			"plistar" : "listado",
		};

		$.ajax({
			data: parametros,
			url: "<?php echo base_url(); ?>index.php/teacher/unlock_notes/<?php echo $section_id; ?>/<?php echo $subject_id; ?>",
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
	function check_sheet(){
		var parametros = {
			"plistar" : "listado",
		};

		$.ajax({
			data: parametros,
			url: "<?php echo base_url(); ?>index.php/teacher/review_spreadsheet/<?php echo $section_id; ?>/<?php echo $subject_id; ?>",
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
    function recover_self(){
        $.ajax({
            url: "<?php echo base_url(); ?>index.php/teacher/recover_self/<?php echo $section_id; ?>/<?php echo $subject_id; ?>",
            type: "get",
            beforeSend: function(){
                document.getElementById('mostrar_loading').style.display="block"
            },
            success: function(response){
                document.getElementById('mostrar_loading').style.display="none"
                document.getElementById('mostrar_tabla').innerHTML=response;
            },
        });
        
    }
    function recover_bolmun(){
        var parametros = {
            "plistar" : "listado",
        };

        $.ajax({
            data: parametros,
            url: "<?php echo base_url(); ?>index.php/teacher/recover_bolmun/<?php echo $section_id; ?>/<?php echo $subject_id; ?>",
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
	<div class="container-fluid">
		<!--begin::Card-->
        <div class="card card-custom">
        	<!--begin::Header-->
            <div class="card-header flex-wrap border-0 pt-12 pb-0">
            	<div class="card-title">
                    <h3 class="card-label">Notas: <?php echo " ".$subject['name']." - ".$subject['completo']; ?>
                    <span class="d-block text-muted pt-2 font-size-sm">Revisión, consolidación y estado de Notas</span></h3>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Section-->
		</div>
		<!--end::Card-->
        <!--begin::Row-->
        <div class="row">
            <div class="col-lg-12">
                <!--begin::Example-->
                <div class="bg-white rounded p-10">
                    <!--begin::Card-->
                    <div class="card card-custom card-fit card-border">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="card-icon">
                                    <i class="flaticon2-pin text-primary"></i>
                                </span>
                                <h3 class="card-label">Planilla <?php echo $phase_name; ?></h3>
                            </div>
                            <div class="card-toolbar">
                            <?php
                                //Preguntamos si ya realizaron los pasos
                                if ($subject['hours']==1 && $subject['partial_locked']==1 && $subject['locked']==1) {
                                ?>
                                    <a href='https://docs.google.com/spreadsheets/d/<?php echo $sheet_id; ?>/edit' target='_blank' class='btn btn-light-success font-weight-bold mr-2'>Ir a la Planilla</a>
                                    
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        <div class="card-body pt-12">

                            <table class="table" >
                                <thead>
                                    <tr>
                                        <th scope="col">Paso</th>
                                        <th scope="col">Proceso</th>
                                        <!--<th scope="col">Acción</th>-->
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">Paso 1</th>
                                        <td>Creación de planilla</td>
                                        <td><a onclick="create_sheet();" class="btn btn-sm btn-primary font-weight-bold"></i>Crear Planilla</a></td>
                                        <td id="estado1" >
                                        <?php
                                            //$hours = $this->db->get_where('subject' , array('subject_id'=>$subject_id))->row()->hours;
                                            if ($subject['hours']==0) {
                                                echo "<span class='label label-inline label-light-danger font-weight-bold'>Pendiente</span>";
                                            }else{
                                                echo "<span class='label label-inline label-light-success font-weight-bold'>Realizado</span>";
                                            }
                                            ?>
                                            

                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Paso 2</th>
                                        <td>Integrar planilla al SAAT</td>
                                        <td><a onclick="integrate_sheet();" class="btn btn-sm btn-primary font-weight-bold"></i>Integrar Planilla</a></td>
                                        <td id="estado2" >
                                            <?php
                                            //$locked = $this->db->get_where('subject' , array('subject_id'=>$subject_id))->row()->locked;
                                            if ($subject['partial_locked']==0) {
                                                echo "<span class='label label-inline label-light-danger font-weight-bold'>Pendiente</span>";
                                            }else{
                                                echo "<span class='label label-inline label-light-success font-weight-bold'>Realizado</span>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Paso 3</th>
                                        <td>Crear enlace Planilla</td>
                                        <td><a onclick="link_sheet();" class="btn btn-sm btn-primary font-weight-bold"></i>Crear Enlace</a></td>
                                        <td id="estado3" >
                                            <?php
                                            //$locked = $this->db->get_where('subject' , array('subject_id'=>$subject_id))->row()->locked;
                                            if ($subject['locked']==0) {
                                                echo "<span class='label label-inline label-light-danger font-weight-bold'>Pendiente</span>";
                                            }else{
                                                echo "<span class='label label-inline label-light-success font-weight-bold'>Realizado</span>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="mostrar_loading" class="spinner spinner-primary spinner-lg mr-15 spinner-center" style="display:none;"></div>
                            <div class="card-body" id="mostrar_tabla"></div>
                        </div>
                        <div>
                            <div>
                            <a onclick="recover_self();" class="btn btn-light-warning font-weight-bold mr-2"></i>Recuperar Autoevaluaciones</a>

                            </div>
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
            </div>
        </div>
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->