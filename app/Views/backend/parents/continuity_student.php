<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Consulta Continuidad <?php echo $gestion;?>
                    <span class="d-block text-muted pt-2 font-size-sm"></span></h3>
                </div>
                <div class="card-toolbar">
                    <p>
                    Estimado Padre de Familia:<br /><br />
                    Solicitamos el llenado de la siguiente "Consulta de Continuidad 2023" hasta fines de Octubre. Se solicita llenar una sola vez,  por Familia, ya sea  por la Madre, Padre o Tutor Legal . 
                    <br />Gracias<br /><br />Dirección General &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Administración 
                    </p>
                </div>
            </div>
            <div class="card-body">
                <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Continuidad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($students as $row):
                            if ($row['section_id']<341) {
                            
                        ?>
                        <tr>
                            <td><?php echo $row['student'];?></td>
                            <td><?php echo $row['completo'];?></td>
                            <td><?php 
                            if (isset($row['respuesta'])) {
                                echo "<b>".$row['respuesta']."</b>";
                            }else{
                                echo "Sin Responder";
                            }
                            ?></td>
                            <td>
                                <?php
                                if (isset($row['respuesta'])) {
                                ?>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/continuity_modal_add/<?php echo $row['student_id']; ?>/<?php echo $row['student'];?>/<?php echo $gestion;?>/0/0');" >
                                        Modificar Respuesta
                                    </button>
                                <?php
                                }else{
                                    ?>
                                    <button type="button" class="btn btn-success btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/continuity_modal_add/<?php echo $row['student_id']; ?>/<?php echo $row['student'];?>/<?php echo $gestion;?>/0/0');" >
                                        Registrar Continuidad 2024
                                    </button>
                                    <?php
                                }
                                ?>
                                
                            </td>
                        </tr>
                        <?php 
                            }else{
                                ?>
                                <tr>
                                <td colspan=5>
                                    El estudiante esta en la Promoción
                                </td>
                        </tr>
                        <?php 
                            }
                            
                        endforeach;
                        if (count($students)==0) {
                            ?>
                                <tr>
                                <td colspan=5>
                                    El estudiante esta en la Promoción o esta retirado.
                                </td>
                        </tr>
                        <?php
                        }
                    ?>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>