<script type="text/javascript">

</script>

<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container-fluid">
		<!--begin::Card-->
        <div class="card card-custom">
        	<!--begin::Header-->
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
            	<div class="card-title">
                    <h3 class="card-label">Notas de la Materia: <?php echo $subject; ?> &emsp; Curso: <?php echo $curso; ?>
                    <span class="d-block text-muted pt-2 font-size-sm">Una vez consolidadas las notas no podra modificar sus notas <?php echo count($rev); ?> </span></h3>
                </div>
                <div class="card-toolbar">
                	<?php
                    if (count($rev)==0) {
                        ?>
                        <button type="button" class="btn btn-danger" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/consolidate_modal_confirm/<?php echo $subject_id; ?>/0/0/0/0');">
					    Consolidar Notas
						</button>
                        <?php
                    }else{
                        ?>
                        <button type="button" class="btn btn-danger" disabled >
					    Consolidación No disponible
						</button>
                        <?php
                    }
                    ?>


                </div>
            </div>
            <!--end::Header-->
            <!--begin::Section-->
            <div class="card-body" id="mostrar_tabla">
                <table class='table'>
                    <thead class='thead-inverse'>
                        <tr>
                            <th>Tipo Revisión</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($rev)>0) {
                            foreach ($rev as $clave => $valor) {
                                ?>
                                <tr>
                                    <td><?php echo $clave;?></td>
                                    <td><?php echo $valor;?></td>
                                </tr>
                                <?php
                                }
                        }else{
                            ?>
                            <tr>
                                <td>Revisión de Notas</td>
                                <td>Notas Correctas, habilitado para consolidar</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

		</div>
		<!--end::Section-->
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>ESTUDIANTE</th>
                            <th>SER</th>
                            <th>SABER</th>
                            <th>HACER</th>
                            <th>Autoevaluación</th>
                            <th>NOTA <?php echo $trim; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($csamarks as $notas){
                                $text = "dark";
                                if ($notas['total_average']<51) {
                                    $text = "danger";
                                }
                                ?>
                            <tr>
                                <td><?php echo $notas['student']; ?></td>
                                <td><?php echo $notas['ser_average']; ?></td>
                                <td><?php echo $notas['saber_average']; ?></td>
                                <td><?php echo $notas['hacer_average']; ?></td>
                                <td><?php echo $notas['autoevaluacion']; ?></td>
                                <td><span class="text-<?php echo $text; ?> font-weight-bolder d-block font-size-lg"><?php echo $notas['total_average']; ?></span></td>
                            </tr>
                                <?php
                            }
                            ?>

                    </tbody>
                </table>
            </div>
		</div>
		<!--end::Card-->
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->
