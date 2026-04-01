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

                            <!-- Button 1: Autoevaluación -->
                            <div class="mb-6">
                                <a href="<?= base_url('student/self_appraisal') ?>"
                                    class="btn btn-light-primary font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <!-- User-folder Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z"
                                                    fill="#5d4099" opacity="0.3" />
                                                <path
                                                    d="M12,13 C10.8954305,13 10,12.1045695 10,11 C10,9.8954305 10.8954305,9 12,9 C13.1045695,9 14,9.8954305 14,11 C14,12.1045695 13.1045695,13 12,13 Z"
                                                    fill="#5d4099" opacity="0.3" />
                                                <path
                                                    d="M7.00036205,18.4995035 C7.21569918,15.5165724 9.36772908,14 11.9907452,14 C14.6506758,14 16.8360465,15.4332455 16.9988413,18.5 C17.0053266,18.6221713 16.9988413,19 16.5815,19 C14.5228466,19 11.463736,19 7.4041679,19 C7.26484009,19 6.98863236,18.6619875 7.00036205,18.4995035 Z"
                                                    fill="#5d4099" opacity="0.3" />
                                            </g>
                                        </svg>
                                    </span>
                                    Autoevaluación
                                </a>
                            </div>

                            <!-- Button 2: Cartas de Contenidos -->
                            <div class="mb-6">
                                <a href="<?= base_url('student/content_letter') ?>"
                                    class="btn btn-light-info font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <!-- Group Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                <path
                                                    d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                                    fill="#3a6999" fill-rule="nonzero" opacity="0.3" />
                                                <path
                                                    d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                                    fill="#3a6999" fill-rule="nonzero" />
                                            </g>
                                        </svg>
                                    </span>
                                    Cartas de Contenidos
                                </a>
                            </div>

                            <!-- Button 3: Horario de Clases (Modal) -->
                            <div class="mb-6">
                                <button
                                    class="btn btn-light-success font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up"
                                    data-toggle="modal" data-target="#kt_chat_modal">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <!-- Time-schedule Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M10.9630156,7.5 L11.0475062,7.5 C11.3043819,7.5 11.5194647,7.69464724 11.5450248,7.95024814 L12,12.5 L15.2480695,14.3560397 C15.403857,14.4450611 15.5,14.6107328 15.5,14.7901613 L15.5,15 C15.5,15.2109164 15.3290185,15.3818979 15.1181021,15.3818979 C15.0841582,15.3818979 15.0503659,15.3773725 15.0176181,15.3684413 L10.3986612,14.1087258 C10.1672824,14.0456225 10.0132986,13.8271186 10.0316926,13.5879956 L10.4644883,7.96165175 C10.4845267,7.70115317 10.7017474,7.5 10.9630156,7.5 Z"
                                                    fill="#2c7a6e" />
                                                <path
                                                    d="M7.38979581,2.8349582 C8.65216735,2.29743306 10.0413491,2 11.5,2 C17.2989899,2 22,6.70101013 22,12.5 C22,18.2989899 17.2989899,23 11.5,23 C5.70101013,23 1,18.2989899 1,12.5 C1,11.5151324 1.13559454,10.5619345 1.38913364,9.65805651 L3.31481075,10.1982117 C3.10672013,10.940064 3,11.7119264 3,12.5 C3,17.1944204 6.80557963,21 11.5,21 C16.1944204,21 20,17.1944204 20,12.5 C20,7.80557963 16.1944204,4 11.5,4 C10.54876,4 9.62236069,4.15592757 8.74872191,4.45446326 L9.93948308,5.87355717 C10.0088058,5.95617272 10.0495583,6.05898805 10.05566,6.16666224 C10.0712834,6.4423623 9.86044965,6.67852665 9.5847496,6.69415008 L4.71777931,6.96995273 C4.66931162,6.97269931 4.62070229,6.96837279 4.57348157,6.95710938 C4.30487471,6.89303938 4.13906482,6.62335149 4.20313482,6.35474463 L5.33163823,1.62361064 C5.35654118,1.51920756 5.41437908,1.4255891 5.49660017,1.35659741 C5.7081375,1.17909652 6.0235153,1.2066885 6.2010162,1.41822583 L7.38979581,2.8349582 Z"
                                                    fill="#2c7a6e" opacity="0.3" />
                                            </g>
                                        </svg>
                                    </span>
                                    Horario de Clases
                                </button>
                            </div>

                            <!-- Button 4: Historial de Comportamiento -->
                            <div class="mb-6">
                                <a href="<?= base_url('student/gamified_behavior') ?>"
                                    class="btn btn-light-warning font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <!-- Layout-4-blocks Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <rect fill="#ffa800" x="4" y="4" width="7" height="7" rx="1.5"></rect>
                                                <path
                                                    d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
                                                    fill="#ffa800" opacity="0.3"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    Historial de Comportamiento
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
                                        Vista del Estudiante
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--begin::Section Row-->
                <div class="row">

                    <!-- Section 1: Información Académica -->
                    <div class="col-xl-6">
                        <div class="card card-custom gutter-b h-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Información Académica</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-0 mt-n3">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-vertical-center">
                                        <tbody>
                                            <!-- Boletín de Notas -->
                                            <tr>
                                                <td class="pl-0 py-5" style="width: 60px;">
                                                    <div class="symbol symbol-45 symbol-light-primary mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-primary">
                                                                <!-- Github Icon (reused from original) -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M16.5428932,17.4571068 L11,11.9142136 L11,4 C11,3.44771525 11.4477153,3 12,3 C12.5522847,3 13,3.44771525 13,4 L13,11.0857864 L17.9571068,16.0428932 L20.1464466,13.8535534 C20.3417088,13.6582912 20.6582912,13.6582912 20.8535534,13.8535534 C20.9473216,13.9473216 21,14.0744985 21,14.2071068 L21,19.5 C21,19.7761424 20.7761424,20 20.5,20 L15.2071068,20 C14.9309644,20 14.7071068,19.7761424 14.7071068,19.5 C14.7071068,19.3673918 14.7597852,19.2402148 14.8535534,19.1464466 L16.5428932,17.4571068 Z"
                                                                            fill="currentColor" fill-rule="nonzero" />
                                                                        <path
                                                                            d="M7.24478854,17.1447885 L9.2464466,19.1464466 C9.34021479,19.2402148 9.39289321,19.3673918 9.39289321,19.5 C9.39289321,19.7761424 9.16903559,20 8.89289321,20 L3.52893218,20 C3.25278981,20 3.02893218,19.7761424 3.02893218,19.5 L3.02893218,14.136039 C3.02893218,14.0034307 3.0816106,13.8762538 3.17537879,13.7824856 C3.37064094,13.5872234 3.68722343,13.5872234 3.88248557,13.7824856 L5.82567301,15.725673 L8.85405776,13.1631936 L10.1459422,14.6899662 L7.24478854,17.1447885 Z"
                                                                            fill="currentColor" fill-rule="nonzero"
                                                                            opacity="0.3" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('student/report_card') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Boletín
                                                        de Notas</a>
                                                    <span
                                                        class="text-muted font-weight-bold d-block">Calificaciones</span>
                                                </td>
                                            </tr>
                                            <!-- Reporte de Evaluaciones -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-danger mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-danger">
                                                                <!-- Layout-4-blocks Icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <rect fill="currentColor" x="4" y="4" width="7"
                                                                            height="7" rx="1.5" />
                                                                        <path
                                                                            d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
                                                                            fill="currentColor" opacity="0.3" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('student/evaluation_report') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Reporte
                                                        de Evaluaciones</a>
                                                    <span class="text-muted font-weight-bold d-block">Saber y
                                                        Hacer</span>
                                                </td>
                                            </tr>
                                            <!-- Reglamento -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-success mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-success">
                                                                <!-- Lock-overturning Icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M7.38979581,2.8349582 C8.65216735,2.29743306 10.0413491,2 11.5,2 C17.2989899,2 22,6.70101013 22,12.5 C22,18.2989899 17.2989899,23 11.5,23 C5.70101013,23 1,18.2989899 1,12.5 C1,11.5151324 1.13559454,10.5619345 1.38913364,9.65805651 L3.31481075,10.1982117 C3.10672013,10.940064 3,11.7119264 3,12.5 C3,17.1944204 6.80557963,21 11.5,21 C16.1944204,21 20,17.1944204 20,12.5 C20,7.80557963 16.1944204,4 11.5,4 C10.54876,4 9.62236069,4.15592757 8.74872191,4.45446326 L9.93948308,5.87355717 C10.0088058,5.95617272 10.0495583,6.05898805 10.05566,6.16666224 C10.0712834,6.4423623 9.86044965,6.67852665 9.5847496,6.69415008 L4.71777931,6.96995273 C4.66931162,6.97269931 4.62070229,6.96837279 4.57348157,6.95710938 C4.30487471,6.89303938 4.13906482,6.62335149 4.20313482,6.35474463 L5.33163823,1.62361064 C5.35654118,1.51920756 5.41437908,1.4255891 5.49660017,1.35659741 C5.7081375,1.17909652 6.0235153,1.2066885 6.2010162,1.41822583 L7.38979581,2.8349582 Z"
                                                                            fill="currentColor" opacity="0.3" />
                                                                        <path
                                                                            d="M14.5,11 C15.0522847,11 15.5,11.4477153 15.5,12 L15.5,15 C15.5,15.5522847 15.0522847,16 14.5,16 L9.5,16 C8.94771525,16 8.5,15.5522847 8.5,15 L8.5,12 C8.5,11.4477153 8.94771525,11 9.5,11 L9.5,10.5 C9.5,9.11928813 10.6192881,8 12,8 C13.3807119,8 14.5,9.11928813 14.5,10.5 L14.5,11 Z M12,9 C11.1715729,9 10.5,9.67157288 10.5,10.5 L10.5,11 L13.5,11 L13.5,10.5 C13.5,9.67157288 12.8284271,9 12,9 Z"
                                                                            fill="currentColor" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="https://tiquipaya.edu.bo/ctdocs/reglamento.pdf"
                                                        target="_blank"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Reglamento</a>
                                                    <span class="text-muted font-weight-bold d-block">Normas</span>
                                                </td>
                                            </tr>
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

                    <!-- Section 2: Comunidad -->
                    <div class="col-xl-6">
                        <div class="card card-custom gutter-b h-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Comunidad</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-0 mt-n3">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-vertical-center">
                                        <tbody>
                                            <!-- RULER -->
                                            <tr>
                                                <td class="pl-0 py-5" style="width: 60px;">
                                                    <div class="symbol symbol-45 symbol-light-success mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-success">
                                                                <!-- Git3 Icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M7,11 L15,11 C16.1045695,11 17,10.1045695 17,9 L17,8 L19,8 L19,9 C19,11.209139 17.209139,13 15,13 L7,13 L7,15 C7,15.5522847 6.55228475,16 6,16 C5.44771525,16 5,15.5522847 5,15 L5,9 C5,8.44771525 5.44771525,8 6,8 C6.55228475,8 7,8.44771525 7,9 L7,11 Z"
                                                                            fill="currentColor" opacity="0.3" />
                                                                        <path
                                                                            d="M6,21 C7.1045695,21 8,20.1045695 8,19 C8,17.8954305 7.1045695,17 6,17 C4.8954305,17 4,17.8954305 4,19 C4,20.1045695 4.8954305,21 6,21 Z M6,23 C3.790861,23 2,21.209139 2,19 C2,16.790861 3.790861,15 6,15 C8.209139,15 10,16.790861 10,19 C10,21.209139 8.209139,23 6,23 Z"
                                                                            fill="currentColor" fill-rule="nonzero" />
                                                                        <path
                                                                            d="M18,7 C19.1045695,7 20,6.1045695 20,5 C20,3.8954305 19.1045695,3 18,3 C16.8954305,3 16,3.8954305 16,5 C16,6.1045695 16.8954305,7 18,7 Z M18,9 C15.790861,9 14,7.209139 14,5 C14,2.790861 15.790861,1 18,1 C20.209139,1 22,2.790861 22,5 C22,7.209139 20.209139,9 18,9 Z"
                                                                            fill="currentColor" fill-rule="nonzero" />
                                                                        <path
                                                                            d="M6,7 C7.1045695,7 8,6.1045695 8,5 C8,3.8954305 7.1045695,3 6,3 C4.8954305,3 4,3.8954305 4,5 C4,6.1045695 4.8954305,7 6,7 Z M6,9 C3.790861,9 2,7.209139 2,5 C2,2.790861 3.790861,1 6,1 C8.209139,1 10,2.790861 10,5 C10,7.209139 8.209139,9 6,9 Z"
                                                                            fill="currentColor" fill-rule="nonzero" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="https://colegiotiquipaya.edu.bo/#/ruler"
                                                        target="_blank"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">RULER</a>
                                                    <span class="text-muted font-weight-bold d-block">SEL</span>
                                                </td>
                                            </tr>
                                            <!-- Character Counts -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-danger mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-danger">
                                                                <!-- Home-heart Icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 C2.99998155,19.0000663 2.99998155,19.0000652 2.99998155,19.0000642 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z"
                                                                            fill="currentColor" opacity="0.3" />
                                                                        <path
                                                                            d="M13.8,12 C13.1562,12 12.4033,12.7298529 12,13.2 C11.5967,12.7298529 10.8438,12 10.2,12 C9.0604,12 8.4,12.8888719 8.4,14.0201635 C8.4,15.2733878 9.6,16.6 12,18 C14.4,16.6 15.6,15.3 15.6,14.1 C15.6,12.9687084 14.9396,12 13.8,12 Z"
                                                                            fill="currentColor" opacity="0.3" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="https://colegiotiquipaya.edu.bo/#/character-counts"
                                                        target="_blank"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Character
                                                        Counts</a>
                                                    <span class="text-muted font-weight-bold d-block">Valores</span>
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