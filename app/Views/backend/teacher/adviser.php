<?php

//$teacher = $this->crud_model->get_student_section($page_data['student_id']);

?>

<!--begin::Entry-->

<div class="d-flex flex-column-fluid">

    <!--begin::Container-->

    <div class="container-fluid">

        <!--begin::Invoice-->

        <div class="card card-custom position-relative overflow-hidden">

            <!--begin::Shape-->

            <div class="position-absolute opacity-30">

                <span class="svg-icon svg-icon-10x svg-logo-white">

                    <!--begin::Svg Icon | path:assets/media/svg/shapes/abstract-8.svg-->

                    <svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">

                        <g clip-path="url(#clip0)">

                            <path
                                d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z"
                                fill="#AD84FF" />

                            <path
                                d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z"
                                fill="#AD84FF" />

                            <path
                                d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z"
                                fill="#AD84FF" />

                            <path
                                d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z"
                                fill="#AD84FF" />

                            <path
                                d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z"
                                fill="#AD84FF" />

                            <path
                                d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z"
                                fill="#AD84FF" />

                            <path
                                d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z"
                                fill="#AD84FF" />

                        </g>

                    </svg>

                    <!--end::Svg Icon-->

                </span>

            </div>

            <!--end::Shape-->

            <!--begin::Invoice header-->

            <div class="row justify-content-center py-8 px-8 py-md-36 px-md-0 bg-primary">

                <div class="col-md-9">

                    <div class="d-flex justify-content-between align-items-md-center flex-column flex-md-row">

                        <div class="d-flex flex-column px-0 order-2 order-md-1">

                            <!--begin::Logo-->

                            <a href="#" class="mb-5 max-w-115px">

                                <span class="svg-icon svg-icon-full svg-logo-white">



                                </span>

                            </a>

                            <!--end::Logo-->
                            <span class="d-flex flex-column font-size-h5 font-weight-bold text-white">
                                <span>Docente : <?php echo $teacher; ?></span>
                            </span>

                        </div>

                        <h1 class="display-3 font-weight-boldest text-white order-1 order-md-2">CONSEJERIA</h1>

                    </div>

                </div>

            </div>

            <!--end::Invoice header-->

            <div class="row justify-content-center py-8 px-8 py-md-30 px-md-0">

                <div class="col-md-9">

                    <!--begin::Invoice body-->

                    <div class="row pb-26">

                        <div class="col-md-3 border-right-md pr-md-10 py-md-10">

                            <!--begin::Invoice To-->

                            <div class="text-dark-50 font-size-lg font-weight-bold mb-3">CURSO.</div>

                            <div class="font-size-lg font-weight-bold mb-10">1ro Sec.

                                <br />Nivel
                            </div>

                            <!--end::Invoice To-->

                            <!--begin::Invoice No-->

                            <div class="text-dark-50 font-size-lg font-weight-bold mb-3">CÓDIGOS</div>

                            <div class="font-size-lg font-weight-bold mb-10">
                                <?php
                                // Extraer todos los section_id en un array
                                $section_ids = array_column($cursos, 'section_id');
                                echo implode(' | ', $section_ids);
                                ?>
                            </div>

                            <!--end::Invoice No-->

                            <!--begin::Invoice Date-->

                            <div class="text-dark-50 font-size-lg font-weight-bold mb-3">ESTUDIANTES</div>

                            <div class="font-size-lg font-weight-bold">55</div>

                            <!--end::Invoice Date-->

                        </div>

                        <div class="col-md-9 py-10 pl-md-10">

                            <div class="table-responsive">

                                <table class="table">

                                    <thead>

                                        <tr>

                                            <th
                                                class="pt-1 pb-9 pl-0 pl-md-5 font-weight-bolder text-muted font-size-lg text-uppercase">
                                                Cursos
                                            </th>
                                            <th
                                                class="pt-1 pb-9 text-right pr-0 font-weight-bolder text-muted font-size-lg text-uppercase">
                                                Opciones
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        //Consejeria 
                                        
                                        $color = ["Guindo" => "primary", "Plomo" => "secondary", "Azul" => "info", "Blanco" => "light"];

                                        //$csl4 = 'SELECT * FROM section WHERE teacher_id='.$teacher_id;
                                        
                                        //$cursos = $this->db->query($csl4)->result_array();
                                        
                                        foreach ($cursos as $curso) {

                                            ?>

                                            <tr class="font-weight-bolder font-size-lg">



                                                <td class="border-top-0 pl-0 pl-md-5 pt-7 d-flex align-items-center">

                                                    <span class="navi-icon mr-2">

                                                        <i
                                                            class="fa fa-genderless text-<?php echo $color[$curso['name']]; ?> font-size-h2"></i>

                                                    </span><?php echo $curso['completo']; ?>
                                                </td>

                                                <!--

                                            <td class="pr-0 pt-7 font-size-h6 font-weight-boldest text-right">

                                                <div class="btn-group dropup">

                                                    <button button="" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Acción</button>

                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                        

                                                        <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/notes_director/<?php echo $curso['section_id']; ?>" >Ver Planillas de Notas</a>

                                                    </div>

                                                </div>

                                            </td>-->

                                                <td class="pr-0 pt-7 font-size-h6 font-weight-boldest text-right">

                                                    <?php if ($curso['section_id'] < 231) { ?>

                                                        <a href="<?php echo base_url(); ?>index.php/teacher/self_inicial/<?php echo $curso['section_id']; ?>"
                                                            class="btn btn-outline-success font-weight-bold">Llenar
                                                            Autoevaluaciones</a>

                                                    <?php } else { ?>

                                                        <div class="btn-group dropup">

                                                            <button button="" type="button"
                                                                class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                                                aria-haspopup="true" aria-expanded="false">Acción</button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item"
                                                                    href="<?php echo base_url(); ?>index.php/teacher/self_appraisal/<?php echo $curso['section_id']; ?>">Ver
                                                                    Autoevaluaciones
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    href="<?php echo base_url(); ?>index.php/teacher/behaviors">Ver
                                                                    Reportes Conductuales
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    href="<?php echo base_url(); ?>index.php/teacher/adviser_behavior_log/<?php echo $curso['section_id']; ?>">Ver
                                                                    Log de Incidencias del Curso
                                                                </a>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                    <!--end::Invoice body-->

                </div>

            </div>

        </div>

        <!--end::Invoice-->

    </div>

    <!--end::Container-->

</div>

<!--end::Entry-->