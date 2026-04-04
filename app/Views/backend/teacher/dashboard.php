<?php $session = session(); ?>

<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class=" container-fluid ">
        <!--begin::Education-->
        <div class="d-flex flex-column flex-md-row">
            <!--begin::Aside-->
            <div class="flex-md-row-auto w-md-275px w-xl-325px">
                <div class="card card-custom gutter-b border-0 shadow-sm h-100">
                    <!--begin::Body-->
                    <div class="card-body p-0 d-flex flex-column">
                        <!--begin::Header-->
                        <div class="bgi-no-repeat bgi-size-cover rounded-top w-100 d-flex flex-column justify-content-end align-items-center mb-8"
                            style="background-image: url(https://tiquipaya.edu.bo/download/coltiqui.jpg); height: 250px;">
                            <div class="d-flex flex-column align-items-center mb-5">
                                <a href="https://colegiotiquipaya.edu.bo/" target="_blank"
                                    class="text-white font-weight-bolder font-size-h3 m-0 pb-1"
                                    style="text-shadow: 0 2px 4px rgba(0,0,0,0.6);">Colegio Tiquipaya</a>
                                <div class="font-weight-bold text-white font-size-lg bg-dark-o-40 px-3 py-1 rounded-pill"
                                    style="backdrop-filter: blur(2px);">
                                    <?= isset($phase_name) ? $phase_name : '' ?>
                                </div>
                            </div>
                        </div>
                        <!--end::Header-->

                        <!--begin::Nav-->
                        <div class="px-6 flex-grow-1 d-flex flex-column justify-content-start">
                            <!-- Button 1: Carpeta -->
                            <div class="mb-6">
                                <a href="<?php echo $carpeta; ?>" target="_blank"
                                    class="btn btn-light-primary font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z"
                                                fill="#5d4099" />
                                            <path
                                                d="M9.2 3H3C2.4 3 2 3.4 2 4V19C2 19.6 2.4 20 3 20H21C21.6 20 22 19.6 22 19V7C22 6.4 21.6 6 21 6H12L10.4 3.60001C10.2 3.20001 9.7 3 9.2 3Z"
                                                fill="#5d4099" />
                                        </svg>
                                    </span>
                                    Carpeta Pedagógica
                                </a>
                            </div>

                            <!-- Button 2: Licencias -->
                            <div class="mb-6">
                                <a href="<?= base_url('index.php/teacher/student_search/teacher/0') ?>"
                                    class="btn btn-light-info font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                                fill="#3a6999" />
                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="#3a6999" />
                                            <rect x="6" y="11" width="12" height="2" rx="1" fill="#3a6999" />
                                            <rect x="6" y="15" width="12" height="2" rx="1" fill="#3a6999" />
                                        </svg>
                                    </span>
                                    Licencias
                                </a>
                            </div>

                            <!-- Button 3: Horarios -->
                            <div class="mb-6">
                                <a href="<?php echo $horario; ?>" target="_blank"
                                    class="btn btn-light-success font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z"
                                                fill="#2c7a6e" />
                                            <path d="M6 6C5.4 6 5 6.4 5 7V21H19V7C19 6.4 18.6 6 18 6H6Z"
                                                fill="#2c7a6e" />
                                            <rect x="8" y="9" width="8" height="2" rx="1" fill="white" />
                                            <rect x="8" y="13" width="8" height="2" rx="1" fill="white" />
                                            <rect x="8" y="17" width="8" height="2" rx="1" fill="white" />
                                        </svg>
                                    </span>
                                    Horarios
                                </a>
                            </div>

                            <!-- Button 4: Asistencias (Unified) -->
                            <div class="mb-6">
                                <a href="<?= base_url('index.php/teacher/assistance') ?>"
                                    class="btn btn-light-danger font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <rect fill="#a13d46" opacity="0.3" x="12" y="4" width="3" height="13"
                                                    rx="1.5" />
                                                <rect fill="#a13d46" opacity="0.3" x="7" y="9" width="3" height="8"
                                                    rx="1.5" />
                                                <path
                                                    d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z"
                                                    fill="#a13d46" fill-rule="nonzero" />
                                                <rect fill="#a13d46" opacity="0.3" x="17" y="11" width="3" height="6"
                                                    rx="1.5" />
                                            </g>
                                        </svg>
                                    </span>
                                    Asistencias
                                </a>
                            </div>
                        </div>
                        <!--end::Nav-->
                    </div>
                </div>
            </div>
            <!--end::Aside-->


            <!--begin::Content-->
            <div class="flex-md-row-fluid ml-md-6 ml-lg-8">
                <div class="card card-custom mb-12">
                    <div class="card card-custom wave wave-animate-slow wave-primary mb-8 mb-lg-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center p-5">
                                <div class="mr-6">
                                    <div class="symbol shadow-sm"
                                        style="width: 70px; height: 70px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.8); overflow: hidden; display: flex; align-items: center; justify-content: center; background: #fff;">
                                        <img src="https://tiquipaya.edu.bo/ctdocs/logodash.png" alt="Logo"
                                            style="width: 100%; height: 100%; object-fit: contain; transform: scale(1.3);">
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="text-dark font-weight-bold font-size-h3 mb-3">
                                        Plataforma Saat 2026
                                    </span>
                                    <div class="text-dark-75">
                                        Vista del Maestro
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--begin::Section Row-->
                <div class="row">
                    <!-- Section 1: Gestión Académica y Planificación -->
                    <div class="col-xl-6">
                        <div class="card card-custom gutter-b h-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Gestión Académica y
                                        Planificación</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-0 mt-n3">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-vertical-center">
                                        <tbody>
                                            <!-- Cartas de Contenidos -->
                                            <tr>
                                                <td class="pl-0 py-5" style="width: 60px;">
                                                    <div class="symbol symbol-45 symbol-light-primary mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3"
                                                                        d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                                                        fill="currentColor" />
                                                                    <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z"
                                                                        fill="currentColor" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('index.php/teacher/content_letter') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Cartas
                                                        de Contenidos</a>
                                                    <span class="text-muted font-weight-bold d-block">Avance y objetivos
                                                        curriculares</span>
                                                </td>
                                            </tr>
                                            <!-- Planificador de Evaluaciones -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-warning mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-warning">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3"
                                                                        d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z"
                                                                        fill="currentColor" />
                                                                    <path
                                                                        d="M6 6C5.4 6 5 6.4 5 7V21H19V7C19 6.4 18.6 6 18 6H6Z"
                                                                        fill="currentColor" />
                                                                    <rect x="8" y="9" width="8" height="2" rx="1"
                                                                        fill="white" />
                                                                    <rect x="8" y="13" width="8" height="2" rx="1"
                                                                        fill="white" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('index.php/teacher/evaluation_planner') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Planificador
                                                        de Evaluaciones</a>
                                                    <span class="text-muted font-weight-bold d-block">Carga
                                                        académica</span>
                                                </td>
                                            </tr>
                                            <!-- Infografías -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-info mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-info">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3"
                                                                        d="M22 5V19C22 19.6 21.6 20 21 20H19.5L11.9 12.4C11.5 12 10.9 12 10.5 12.4L3 20C2.5 20 2 19.5 2 19V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5Z"
                                                                        fill="currentColor" />
                                                                    <path
                                                                        d="M21 20H3C2.4 20 2 19.6 2 19V19.1L9.8 11.3C10.2 10.9 10.8 10.9 11.2 11.3L19 19.1V19C19 19.6 18.6 20 18 20H21Z"
                                                                        fill="currentColor" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="https://tiquipaya.edu.bo/ctdocs/infografias.pdf"
                                                        target="_blank"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">REGULACIONES INSTITUCIONALES</a>
                                                    <span class="text-muted font-weight-bold d-block">2026</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Seguimiento y Vinculación -->
                    <div class="col-xl-6">
                        <div class="card card-custom gutter-b h-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Seguimiento y
                                        Vinculación</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-0 mt-n3">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-vertical-center">
                                        <tbody>
                                            <!-- Historial de Comportamiento -->
                                            <tr>
                                                <td class="pl-0 py-5" style="width: 60px;">
                                                    <div class="symbol symbol-45 symbol-light-danger mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-danger">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3"
                                                                        d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V15C10 15.6 10.4 16 11 16H13C13.6 16 14 15.6 14 15V13C14 12.4 13.6 12 13 12Z"
                                                                        fill="currentColor" />
                                                                    <path
                                                                        d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V13C14 12.4 13.6 12 13 12H11C10.5 12 10 12.4 10 13V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z"
                                                                        fill="currentColor" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('index.php/teacher/history') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Historial
                                                        de Comportamiento</a>
                                                    <span class="text-muted font-weight-bold d-block">Registro de
                                                        convivencia y disciplina</span>
                                                </td>
                                            </tr>
                                            <!-- Comunicación con Padres -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-success mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-success">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3"
                                                                        d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z"
                                                                        fill="currentColor" />
                                                                    <path
                                                                        d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z"
                                                                        fill="currentColor" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('index.php/teacher/behaviors') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Comunicación
                                                        con Padres</a>
                                                    <span class="text-muted font-weight-bold d-block">Mensajería</span>
                                                </td>
                                            </tr>
                                            <!-- Registro de Entrevistas -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-primary mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-primary">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none">
                                                                    <path opacity="0.3"
                                                                        d="M20 18.3C18.9 17.5 17.6 17 16.1 17H7.89996C6.39996 17 5.09996 17.5 3.99996 18.3C3.39996 18.7 3.39996 19.6 4.09996 20.1C5.19996 20.8 6.49996 21.2 7.89996 21.2H16.1C17.5 21.2 18.8 20.8 19.9 20.1C20.6 19.6 20.6 18.7 20 18.3Z"
                                                                        fill="currentColor" />
                                                                    <path
                                                                        d="M12 15C15.3137 15 18 12.3137 18 9C18 5.68629 15.3137 3 12 3C8.68629 3 6 5.68629 6 9C6 12.3137 8.68629 15 12 15Z"
                                                                        fill="currentColor" />
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('index.php/teacher/interviews') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Registro
                                                        de Entrevistas</a>
                                                    <span class="text-muted font-weight-bold d-block">Bitácora de
                                                        reuniones y acuerdos</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Content-->
        </div>
        <!--end::Education-->
    </div>
    <!--end::Container-->
</div>