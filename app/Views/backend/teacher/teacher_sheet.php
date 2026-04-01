<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--end::Notice-->
        <div class="row">
            <div class="col-xl-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Planillas del Docente: <?php echo $teacher; ?></h3>
                        </div>
                    </div>
                    <div class="card-body">
                    <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" >Materias</th>
                                    <th scope="col" >Curso</th>
                                    <th scope="col" >Acción</th>
                                </tr>
                            </thead>
                        <tbody>
                    <?php 
                        foreach($subjects as $subject){
                            ?>
                            <tr>
                                <th scope="row" ><?php echo $subject->materia;?></th>
                                <td><?php echo $subject->curso;?></td>
                                <td>
                                <a href="https://docs.google.com/spreadsheets/d/<?php echo $subject->sheet_id; ?>/edit" target="_blank" class="btn btn-success btn-sm" >Ir a la Planilla</a>
                                </td>
                            </tr>
                        <?php
                            //}
                        }
                    ?>
                        </tbody>
                    </table>






                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->