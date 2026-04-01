<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Planillas de faltas leves
                    <span class="d-block text-muted pt-2 font-size-sm">Listado de Cursos</span></h3>
                </div>
                <div class="card-toolbar">

                </div>
            </div>
            <div class="card-body" id="imp1">
                <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" >Cursos</th>
                                    <th scope="col" >Planilla</th>
                                    <th scope="col" >Descargas</th>
                                </tr>
                            </thead>
                        <tbody>
                    <?php 
                    if (count($sections)>0) {
                        foreach($sections as $cur){
                            ?>
                            <tr>
                                <th scope="row" ><?php echo $cur['completo'];?></th>
                                <td>
                                    <a href="<?php echo base_url(); ?>secretary/infractions_section/<?php echo $cur['section_id'];?>" class="btn btn-danger btn-sm" >
                                    Ingresar a la Planilla</a>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="<?php echo base_url(); ?>secretary/infractions_excel/<?php echo $cur['section_id'];?>" class="btn btn-secondary btn-sm" >Descargar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            
                        }
                    }else{
                        foreach($cursos as $cur){
                            ?>
                            <tr>
                                <th scope="row" ><?php echo $cur['completo'];?></th>
                                <td>
                                    <a href="<?php echo base_url(); ?>secretary/infractions_section/<?php echo $cur['section_id'];?>" class="btn btn-danger btn-sm" >
                                    Ingresar a la Planilla</a>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="<?php echo base_url(); ?>secretary/infractions_excel/<?php echo $cur['section_id'];?>" class="btn btn-secondary btn-sm" >Descargar</a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            
                        }
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
