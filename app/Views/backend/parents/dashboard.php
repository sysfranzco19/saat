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

                            <!-- Button 1: Solicitud de Licencias -->
                            <div class="mb-6">
                                <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/licenses"
                                    class="btn btn-light-primary font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <!-- Chat6 Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path opacity="0.3" fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M14.4862 18L12.7975 21.0566C12.5304 21.54 11.922 21.7153 11.4386 21.4483C11.2977 21.3704 11.1777 21.2597 11.0887 21.1255L9.01653 18H5C3.34315 18 2 16.6569 2 15V6C2 4.34315 3.34315 3 5 3H19C20.6569 3 22 4.34315 22 6V15C22 16.6569 20.6569 18 19 18H14.4862Z"
                                                    fill="#5d4099" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6 7H15C15.5523 7 16 7.44772 16 8C16 8.55228 15.5523 9 15 9H6C5.44772 9 5 8.55228 5 8C5 7.44772 5.44772 7 6 7ZM6 11H11C11.5523 11 12 11.4477 12 12C12 12.5523 11.5523 13 11 13H6C5.44772 13 5 12.5523 5 12C5 11.4477 5.44772 11 6 11Z"
                                                    fill="#5d4099" />
                                            </g>
                                        </svg>
                                    </span>
                                    Solicitud de Licencias
                                </a>
                            </div>

                            <!-- Button 2: Mis Hijos -->
                            <div class="mb-6">
                                <a href="<?= base_url('parents/enrolled_children') ?>"
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
                                    Mis Hijos
                                </a>
                            </div>

                            <!-- Button 3: Horario de Entrevistas -->
                            <div class="mb-6">
                                <a href="<?= base_url('parents/interviews') ?>"
                                    class="btn btn-light-warning font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <!-- Time-schedule Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M10.9630156,7.5 L11.0475062,7.5 C11.3043819,7.5 11.5194647,7.69464724 11.5450248,7.95024814 L12,12.5 L15.2480695,14.3560397 C15.403857,14.4450611 15.5,14.6107328 15.5,14.7901613 L15.5,15 C15.5,15.2109164 15.3290185,15.3818979 15.1181021,15.3818979 C15.0841582,15.3818979 15.0503659,15.3773725 15.0176181,15.3684413 L10.3986612,14.1087258 C10.1672824,14.0456225 10.0132986,13.8271186 10.0316926,13.5879956 L10.4644883,7.96165175 C10.4845267,7.70115317 10.7017474,7.5 10.9630156,7.5 Z"
                                                    fill="#ffa800" />
                                                <path
                                                    d="M7.38979581,2.8349582 C8.65216735,2.29743306 10.0413491,2 11.5,2 C17.2989899,2 22,6.70101013 22,12.5 C22,18.2989899 17.2989899,23 11.5,23 C5.70101013,23 1,18.2989899 1,12.5 C1,11.5151324 1.13559454,10.5619345 1.38913364,9.65805651 L3.31481075,10.1982117 C3.10672013,10.940064 3,11.7119264 3,12.5 C3,17.1944204 6.80557963,21 11.5,21 C16.1944204,21 20,17.1944204 20,12.5 C20,7.80557963 16.1944204,4 11.5,4 C10.54876,4 9.62236069,4.15592757 8.74872191,4.45446326 L9.93948308,5.87355717 C10.0088058,5.95617272 10.0495583,6.05898805 10.05566,6.16666224 C10.0712834,6.4423623 9.86044965,6.67852665 9.5847496,6.69415008 L4.71777931,6.96995273 C4.66931162,6.97269931 4.62070229,6.96837279 4.57348157,6.95710938 C4.30487471,6.89303938 4.13906482,6.62335149 4.20313482,6.35474463 L5.33163823,1.62361064 C5.35654118,1.51920756 5.41437908,1.4255891 5.49660017,1.35659741 C5.7081375,1.17909652 6.0235153,1.2066885 6.2010162,1.41822583 L7.38979581,2.8349582 Z"
                                                    fill="#ffa800" opacity="0.3" />
                                            </g>
                                        </svg>
                                    </span>
                                    Horario de Entrevistas
                                </a>
                            </div>

                            <!-- Button 4: Registro de Evaluaciones -->
                            <div class="mb-6">
                                <a href="<?= base_url('parents/enrolled_children') ?>"
                                    class="btn btn-light-danger font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <!-- Layout-4-blocks Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <rect fill="#a13d46" x="4" y="4" width="7" height="7" rx="1.5"></rect>
                                                <path
                                                    d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
                                                    fill="#a13d46" opacity="0.3"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    Registro de Evaluaciones
                                </a>
                            </div>

                            <!-- Button 5: Difusión de Logro Estudiantil -->
                            <div class="mb-6">
                                <a href="<?= base_url('parents/achievement_diffusion') ?>"
                                    class="btn btn-light-success font-weight-bolder font-size-lg py-5 w-100 d-flex align-items-center px-6 shadow-xs hover-elevate-up">
                                    <span class="svg-icon svg-icon-2x mr-4">
                                        <!-- Trophy/Star Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.3103522 6.46467018,19.0938861 L7.24393994,14.5528376 L3.94232432,11.3360847 C3.54625485,10.9501848 3.53872114,10.3170102 3.92462101,9.92094078 C4.07689512,9.76676293 4.27502429,9.66634981 4.48994827,9.63366262 L9.0487052,8.97079063 L11.0913378,4.85587763 C11.3148276,4.40604326 11.8607277,4.22301898 12.3105621,4.44650876 C12.4937682,4.53505104 12.6431552,4.68443799 12.7317175,4.86764111 L14.7743501,8.97079063 L19.3331069,9.63366262 C19.8254439,9.70701441 20.1668933,10.1637669 20.0935415,10.6561039 C20.0608543,10.8710279 19.9604412,11.0691571 19.8062633,11.2214312 L16.5046477,14.5528376 L17.2839175,19.0938861 C17.3699457,19.5850587 17.0355419,20.0496379 16.5443693,20.1356661 C16.3279032,20.1728429 16.1054203,20.1375283 15.9107599,20.0351892 L12,18 Z"
                                                    fill="#1bc5bd" />
                                            </g>
                                        </svg>
                                    </span>
                                    Difusión de Logro Estudiantil
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
                                        Vista de Padres de Familia
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--begin::Section Row-->
                <div class="row">

                    <!-- Section 2: Información Administrativa -->
                    <div class="col-xl-6">
                        <div class="card card-custom gutter-b h-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Información
                                        Administrativa</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-0 mt-n3">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-vertical-center">
                                        <tbody>
                                            <!-- Métodos de Pago -->
                                            <tr>
                                                <td class="pl-0 py-5" style="width: 60px;">
                                                    <div class="symbol symbol-45 symbol-light-success mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-success">
                                                                <!-- Wallet Icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <rect fill="currentColor" opacity="0.3" x="2"
                                                                            y="2" width="10" height="12" rx="2" />
                                                                        <path
                                                                            d="M4,6 L20,6 C21.1045695,6 22,6.8954305 22,8 L22,20 C22,21.1045695 21.1045695,22 20,22 L4,22 C2.8954305,22 2,21.1045695 2,20 L2,8 C2,6.8954305 2.8954305,6 4,6 Z M18,16 C19.1045695,16 20,15.1045695 20,14 C20,12.8954305 19.1045695,12 18,12 C16.8954305,12 16,12.8954305 16,14 C16,15.1045695 16.8954305,16 18,16 Z"
                                                                            fill="currentColor" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('parents/payment_methods') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Métodos
                                                        de Pago</a>
                                                    <span
                                                        class="text-muted font-weight-bold d-block">Administración</span>
                                                </td>
                                            </tr>
                                            <!-- Contactos -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-warning mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-warning">
                                                                <!-- Call Icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M13.0799676,14.7839934 L15.2839934,12.5799676 C15.8927139,11.9712471 16.0436229,11.0413042 15.6586342,10.2713269 L15.5337539,10.0215663 C15.1487653,9.25158901 15.2996742,8.3216461 15.9083948,7.71292558 L18.6411989,4.98012149 C18.836461,4.78485934 19.1530435,4.78485934 19.3483056,4.98012149 C19.3863063,5.01812215 19.4179321,5.06200062 19.4419658,5.11006808 L20.5459415,7.31801948 C21.3904962,9.0071287 21.0594452,11.0471565 19.7240871,12.3825146 L13.7252616,18.3813401 C12.2717221,19.8348796 10.1217008,20.3424308 8.17157288,19.6923882 L5.75709327,18.8875616 C5.49512161,18.8002377 5.35354162,18.5170777 5.4408655,18.2551061 C5.46541191,18.1814669 5.50676633,18.114554 5.56165376,18.0596666 L8.21292558,15.4083948 C8.8216461,14.7996742 9.75158901,14.6487653 10.5215663,15.0337539 L10.7713269,15.1586342 C11.5413042,15.5436229 12.4712471,15.3927139 13.0799676,14.7839934 Z"
                                                                            fill="currentColor" />
                                                                        <path
                                                                            d="M14.1480759,6.00715131 L13.9566988,7.99797396 C12.4781389,7.8558405 11.0097207,8.36895892 9.93933983,9.43933983 C8.8724631,10.5062166 8.35911588,11.9685602 8.49664195,13.4426352 L6.50528978,13.6284215 C6.31304559,11.5678496 7.03283934,9.51741319 8.52512627,8.02512627 C10.0223249,6.52792766 12.0812426,5.80846733 14.1480759,6.00715131 Z M14.4980938,2.02230302 L14.313049,4.01372424 C11.6618299,3.76737046 9.03000738,4.69181803 7.1109127,6.6109127 C5.19447112,8.52735429 4.26985715,11.1545872 4.51274152,13.802405 L2.52110319,13.985098 C2.22450978,10.7517681 3.35562581,7.53777247 5.69669914,5.19669914 C8.04101739,2.85238089 11.2606138,1.72147333 14.4980938,2.02230302 Z"
                                                                            fill="currentColor" fill-rule="nonzero"
                                                                            opacity="0.3" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('parents/contacts') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Números
                                                        de Contacto</a>
                                                    <span class="text-muted font-weight-bold d-block">Administración y
                                                        Secretarias</span>
                                                </td>
                                            </tr>
                                            <!-- Soporte -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-primary mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-primary">
                                                                <!-- Macbook Icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M5,6 L19,6 C19.5522847,6 20,6.44771525 20,7 L20,17 L4,17 L4,7 C4,6.44771525 4.44771525,6 5,6 Z"
                                                                            fill="currentColor" />
                                                                        <rect fill="currentColor" opacity="0.3" x="1"
                                                                            y="18" width="22" height="1" rx="0.5" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="https://wa.me/59176965562" target="_blank"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Soporte
                                                        Técnico</a>
                                                    <span class="text-muted font-weight-bold d-block">Contacto Soporte
                                                        Informático</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Varios -->
                    <div class="col-xl-6">
                        <div class="card card-custom gutter-b h-100">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Varios</span>
                                </h3>
                            </div>
                            <div class="card-body pt-2 pb-0 mt-n3">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-vertical-center">
                                        <tbody>
                                            <!-- Reportes Conductuales -->
                                            <tr>
                                                <td class="pl-0 py-5" style="width: 60px;">
                                                    <div class="symbol symbol-45 symbol-light-success mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-success">
                                                                <!-- Clipboard-list icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
                                                                            fill="currentColor" opacity="0.3" />
                                                                        <path
                                                                            d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
                                                                            fill="currentColor" />
                                                                        <rect fill="currentColor" opacity="0.3" x="10"
                                                                            y="9" width="7" height="2" rx="1" />
                                                                        <rect fill="currentColor" opacity="0.3" x="7"
                                                                            y="9" width="2" height="2" rx="1" />
                                                                        <rect fill="currentColor" opacity="0.3" x="7"
                                                                            y="13" width="2" height="2" rx="1" />
                                                                        <rect fill="currentColor" opacity="0.3" x="10"
                                                                            y="13" width="7" height="2" rx="1" />
                                                                        <rect fill="currentColor" opacity="0.3" x="7"
                                                                            y="17" width="2" height="2" rx="1" />
                                                                        <rect fill="currentColor" opacity="0.3" x="10"
                                                                            y="17" width="7" height="2" rx="1" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('parents/behaviors') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Historial
                                                        de Comportamiento</a>
                                                    <span class="text-muted font-weight-bold d-block">Registro de
                                                        convivencia y disciplina</span>
                                                </td>
                                            </tr>
                                            <!-- Mensajes de Maestros -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-danger mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-danger">
                                                                <!-- Mail-opened Icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z"
                                                                            fill="currentColor" opacity="0.3" />
                                                                        <path
                                                                            d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z"
                                                                            fill="currentColor" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/behaviors"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Mensajes
                                                        de Maestros</a>
                                                    <span class="text-muted font-weight-bold d-block">Comunicacion con
                                                        el Maestro</span>
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
                                            
                                            <!-- Cartas de Contenidos -->
                                            <tr>
                                                <td class="pl-0 py-5">
                                                    <div class="symbol symbol-45 symbol-light-primary mr-2">
                                                        <span class="symbol-label">
                                                            <span class="svg-icon svg-icon-2x svg-icon-primary">
                                                                <!-- Layout-Left-Panel-2 icon -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                    width="24px" height="24px" viewBox="0 0 24 24"
                                                                    version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                        fill-rule="evenodd">
                                                                        <rect x="0" y="0" width="24" height="24" />
                                                                        <path
                                                                            d="M10,4 L21,4 C21.5522847,4 22,4.44771525 22,5 L22,7 C22,7.55228475 21.5522847,8 21,8 L10,8 C9.44771525,8 9,7.55228475 9,7 L9,5 C9,4.44771525 9.44771525,4 10,4 Z M10,10 L21,10 C21.5522847,10 22,10.4477153 22,11 L22,13 C22,13.5522847 21.5522847,14 21,14 L10,14 C9.44771525,14 9,13.5522847 9,13 L9,11 C9,10.4477153 9.44771525,10 10,10 Z M10,16 L21,16 C21.5522847,16 22,16.4477153 22,17 L22,19 C22,19.5522847 21.5522847,20 21,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,17 C9,16.4477153 9.44771525,16 10,16 Z"
                                                                            fill="currentColor" />
                                                                        <rect fill="currentColor" opacity="0.3" x="2"
                                                                            y="4" width="5" height="16" rx="1" />
                                                                    </g>
                                                                </svg>
                                                            </span>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="<?= base_url('parents/content_letter') ?>"
                                                        class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">Cartas
                                                        de Contenidos</a>
                                                    <span class="text-muted font-weight-bold d-block">Contenidos de
                                                        avance</span>
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