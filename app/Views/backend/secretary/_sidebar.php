<?php
$session = session();
$name = strtoupper($session->get('name'));
?>
<!--begin::Aside Secondary-->
<div class="sidebar sidebar-right d-flex flex-row-auto flex-column" id="kt_sidebar">
    <!--begin::Aside Secondary Content-->
    <div class="sidebar-content flex-column-fluid pb-10 pt-9 px-5 px-lg-10">
        <!--begin::Stats Widget 13-->
        <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/dashboard"
            class="card card-custom bg-danger bg-hover-state-danger card-shadowless gutter-b">
            <!--begin::Body-->
            <div class="card-body">
                <span class="svg-icon svg-icon-white svg-icon-3x ml-n1"><svg xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24"
                        version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path
                                d="M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z M10,13 C9.44771525,13 9,13.4477153 9,14 L9,17 C9,17.5522847 9.44771525,18 10,18 L14,18 C14.5522847,18 15,17.5522847 15,17 L15,14 C15,13.4477153 14.5522847,13 14,13 L10,13 Z"
                                fill="#000000" />
                        </g>
                    </svg><!--end::Svg Icon--></span>
                <div class="text-inverse-danger font-weight-bolder font-size-h3 mb-2 mt-5">Bienvenid@
                    <?php echo $name; ?>!
                </div>
                <div class="font-weight-bold text-inverse-danger font-size-sm">Plataforma de Gestión Académica
                    Estudiantil</div>
                <div class="font-weight-bold text-inverse-danger font-size-sm">Unidad Educativa Tiquipaya</div>
            </div>

            <!--end::Body-->
        </a>
        <!--end::Stats Widget 13-->


        <div class="card card-custom card-shadowless bg-white gutter-b">
            <!--begin::Header-->
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">Material y Ayuda</span>
                    <span class="text-muted mt-3 font-weight-bold font-size-sm">Pending 10 tasks</span>
                </h3>
                <div class="card-toolbar">

                </div>
            </div>

            <!--end::Header-->

            <!--begin::Body-->
            <div class="card-body pt-8">

                <!--begin::Item-->
                <div class="d-flex align-items-center mb-10">

                    <!--begin::Symbol-->
                    <div class="symbol symbol-40 symbol-light-primary mr-5">
                        <span class="symbol-label">
                            <span class="svg-icon svg-icon-xl svg-icon-primary">

                                <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
                                            fill="#000000" />
                                        <rect fill="#000000" opacity="0.3"
                                            transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)"
                                            x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
                                    </g>
                                </svg>

                                <!--end::Svg Icon-->
                            </span>
                        </span>
                    </div>

                    <!--end::Symbol-->

                    <!--begin::Text-->
                    <div class="d-flex flex-column font-weight-bold">
                        <a href="<?php echo base_url(); ?>index.php/admin/subir_notas"
                            class="text-dark text-hover-primary mb-1 font-size-lg">Registrar Notas</a>
                        <span class="text-muted">Planillas</span>
                    </div>

                    <!--end::Text-->
                </div>

                <!--end::Item-->

                <!--begin::Item-->
                <div class="d-flex align-items-center mb-10">

                    <!--begin::Symbol-->
                    <div class="symbol symbol-40 symbol-light-warning mr-5">
                        <span class="symbol-label">
                            <span class="svg-icon svg-icon-lg svg-icon-warning">

                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Write.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z"
                                            fill="#000000" fill-rule="nonzero"
                                            transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
                                        <path
                                            d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                    </g>
                                </svg>

                                <!--end::Svg Icon-->
                            </span>
                        </span>
                    </div>

                    <!--end::Symbol-->

                    <!--begin::Text-->
                    <div class="d-flex flex-column font-weight-bold">
                        <a href="<?php echo base_url(); ?>index.php/admin/process_testing"
                            class="text-dark-75 text-hover-primary mb-1 font-size-lg">Pruebas administrador</a>
                        <span class="text-muted">Google Drive y google sheet</span>
                    </div>

                    <!--end::Text-->
                </div>

                <!--end::Item-->

                <!--begin::Item-->
                <div class="d-flex align-items-center mb-10">
                    <!--begin::Symbol-->
                    <div class="symbol symbol-40 symbol-light-primary mr-5">
                        <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path
                                            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path
                                            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                        </span>
                    </div>
                    <!--end::Symbol-->

                    <!--begin::Text-->
                    <div class="d-flex flex-column font-weight-bold">
                        <a href="http://colegiotiquipaya.edu.bo/" target="_blank"
                            class="text-dark-75 text-hover-primary mb-1 font-size-lg">Página Web</a>
                        <span class="text-muted">Página Oficial Colegio Tiquipaya</span>
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Item-->


            </div>

            <!--end::Body-->
        </div>

        <!--end::List Widget 1-->

        <!--[html-partial:end:{"id":"demo1/dist/inc/view/partials/content/widgets/lists/widget-1","page":"index"}]/-->

        <!--[html-partial:begin:{"id":"demo1/dist/inc/view/partials/content/widgets/lists/widget-9","page":"index"}]/-->

        <!--begin::List Widget 9-->


        <!--end: List Widget 9-->

        <!--[html-partial:end:{"id":"demo1/dist/inc/view/partials/content/widgets/lists/widget-9","page":"index"}]/-->
    </div>

    <!--end::Aside Secondary Content-->
</div>

<!--end::Aside Secondary-->