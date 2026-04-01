<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Asistencias por Cursos
                    <span class="text-muted pt-2 font-size-sm d-block">Todos los Cursos Activos</span></h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/licenses" class="btn btn-primary font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Licencias</a>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/absences" class="btn btn-primary font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Ausencias</a>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th><div>Curso</div></th>
                            <th><div>Opciones</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($cursos as $row):
                        ?>
                        <tr>
                            <td><?php echo $row['completo']; ?></td>

                            <td>
                                <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/licenses" target="_blank" class="btn btn-success btn-sm" >Ver Asistencias</a>
                            </td>
                            <!-- 
                            <td><a href="<?php echo base_url(); ?>index.php/teacher/low_averages/<?php echo $row['section_id']; ?>" class="btn btn-light-danger font-weight-bold mr-2" target="_blank">Promedios Bajos</a></td>
                            <td>

                            </td>
                            <td><a href="<?php echo base_url(); ?>index.php/teacher/generate_ranking/<?php echo $row['section_id']; ?>" class="btn btn-light-warning font-weight-bold mr-2">Ranking</a>
                            </td>
                            -->
                            
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->