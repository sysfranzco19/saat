<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Profile Personal Information-->
        <div class="d-flex flex-row">
            <!--begin::Content-->
            <div class="flex-row-fluid ml-lg-12">
                <!--begin::Card-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Información de Familia</h3>
                            <span class="text-muted font-weight-bold font-size-sm mt-1">Datos proporcionados por la Familia al momento de la inscripción</span>
                        </div>
                        <div class="card-toolbar">
                            <!-- 
                            <button type="reset" class="btn btn-success mr-2">Save Changes</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                             -->
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Form-->
                    <form class="form">
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-xl-12">
                                    <h5 class="font-weight-bold mb-6">Datos Familia</h5>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12 col-xl-12">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th scope="row">IdFamilia : </th>
                                                <td><?php echo $fam['family_id']?></td>
                                                <th scope="row">Familia : </th>
                                                <td><?php echo $fam['lastname1']?> <?php echo $fam['lastname2']?></td>
                                                <th scope="row">Relación : </th>
                                                <td><?php echo $fam['relation']?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Dirección : </th>
                                                <td><?php echo $fam['home_address']?></td>
                                                <th scope="row">Dirección : </th>
                                                <td><?php echo $fam['neighborhood']?></td>
                                                <th scope="row">Teléfono Casa : </th>
                                                <td><?php echo $fam['home_phone']?></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Email1 : </th>
                                                <td><?php echo $fam['email1']?></td>
                                                <th scope="row">Email2 : </th>
                                                <td><?php echo $fam['email2']?></td>
                                                <th scope="row">Celular de Contacto : </th>
                                                <td><?php echo $fam['contact_cell']?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-xl-12">
                                    <h5 class="font-weight-bold mt-10 mb-6">Datos Padres</h5>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12 col-xl-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Id</th>
                                                <th scope="col">Padre</th>
                                                <th scope="col">Relación</th>
                                                <th scope="col">Profesión</th>
                                                <th scope="col">Celular</th>
                                                <th scope="col">Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            foreach($parents as $pad):
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $pad['parent_id'];?></th>
                                                <td><?php echo $pad['nombre'];?></td>
                                                <td><?php echo $pad['relationship'];?></td>
                                                <td><?php echo $pad['profession'];?></td>
                                                <td><?php echo $pad['cellphone'];?></td>
                                                <td><?php echo $pad['email'];?></td>
                                            </tr>
                                        <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-xl-12">
                                    <h5 class="font-weight-bold mt-10 mb-6">Datos Hijos</h5>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-12 col-xl-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Id</th>
                                                <th scope="col">Estudiante</th>
                                                <th scope="col">Curso</th>
                                                <th scope="col">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            foreach($students as $row):
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $row['student_id'];?></th>
                                                <td><?php echo $row['student'];?></td>
                                                <td><?php echo $row['completo'];?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-primary font-weight-bold btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Notas
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/notes_half_student/<?php echo $row['student_id'];?>" target="_blank" >Medio Trimestre</a>
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/student_notes/<?php echo $row['student_id'];?>" target="_blank" >Trimestreles</a>
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/report_card/<?php echo $row['student_id'];?>" target="_blank" >Reporte Académico Trimestral</a>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button class="btn btn-secondary font-weight-bold btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Asistencias
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/student_attendance/<?php echo $row['student_id'];?>/all/0" target="_blank" >Asistencias</a>
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/student_licenses/<?php echo $row['student_id'];?>/all/0" target="_blank" >Licencias</a>
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/absences_student/<?php echo $row['student_id'];?>" target="_blank">Ausencias</a>
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/delays_student/<?php echo $row['student_id'];?>" target="_blank">Retrasos</a>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group">
                                                        <button class="btn btn-danger font-weight-bold btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Conductuales
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/dashboard" target="_blank" >Comunicación</a>
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/dashboard" target="_blank" >Entrevistas</a>
                                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/dashboard" target="_blank" >Infracciones</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Profile Personal Information-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->