<?php $session = session(); ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Dashboard-->
            <!--begin::Row-->
            <div class="row">
                <!--begin::Col1-->
                <div class="col-xl-5 col-lg-7 col-md-5">
                    <!--begin::Mixed Widget 2-->
                    <div class="card card-custom bg-gray-100 gutter-b card-stretch">
                        <!--begin::Header-->
                        <div class="card-header border-0 bg-danger py-5">
                            <h3 class="card-title font-weight-bolder text-white">Acceso Rápido <?php echo $session->get('login_type');?></h3>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body p-0 bg-warning-o-40 position-relative overflow-hidden">
                            <!--begin::Chart-->
                            <div id="" class="card-rounded-bottom bg-danger" style="height: 160px; min-height: 160px;">
                            </div>
                            <!--end::Chart-->
                            <!--begin::Stats-->
                            <div class="card-spacer mt-n25">
                                <!--begin::Row-->
                                <div class="row"><!--m-0--><!--end::Chart--><!--end::Chart--><!--end::Chart--><!--end::Chart-->
                                    <div class="col bg-white px-4 py-6 rounded-xl mr-7 mb-7">
                                        <span class="svg-icon svg-icon-2x svg-icon-info d-block my-2">
                                            <!--begin::Svg Icon |
                                            path:/metronic/theme/html/demo7/dist/assets/media/svg/icons/Media/Equalizer.svg-->
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="24px"
                                                height="24px"
                                                viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5"></rect>
                                                    <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5"></rect>
                                                    <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5"></rect>
                                                    <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5"></rect>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <a href="<?php echo base_url(); ?>index.php/teacher/pdcs" class="text-info font-weight-bold font-size-h">PDC</a>
                                    </div>
                                    <div class="col bg-white px-4 py-6 rounded-xl mb-7">
                                        <span class="svg-icon svg-icon-2x svg-icon-warning d-block my-2">
                                            <!--begin::Svg Icon |
                                            path:/metronic/theme/html/demo7/dist/assets/media/svg/icons/Communication/Add-user.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"></path>
                                                            <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"></path>
                                                        </g>
                                                        </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <a href="<?php echo base_url(); ?>index.php/teacher/content_letter" class="text-warning font-weight-bold font-size-h7    mt-2">C.Contenidos</a>
                                    </div>
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row"><!--m-0--><!--end::Chart--><!--end::Chart--><!--end::Chart--><!--end::Chart-->

                                    <div class="col bg-white px-4 py-6 rounded-xl mr-7">
                                        <span class="svg-icon svg-icon-2x svg-icon-danger d-block my-2">
                                            <!--begin::Svg Icon |
                                            path:/metronic/theme/html/demo7/dist/assets/media/svg/icons/Design/Layers.svg-->
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="24px"
                                                height="24px" 
                                                viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <path
                                                        d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z"
                                                        fill="#000000"
                                                        fill-rule="nonzero"></path>
                                                    <path
                                                        d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z"
                                                        fill="#000000"
                                                        opacity="0.3"></path>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <a href="<?php echo base_url(); ?>index.php/teacher/assistance" class="text-danger font-weight-bold font-size-h7 mt-2">Asistencia</a>
                                    </div>
                                    <div class="col bg-white px-4 py-6 rounded-xl">
                                        <span class="svg-icon svg-icon-2x svg-icon-success d-block my-2">
                                            <!--begin::Svg Icon |
                                            path:/metronic/theme/html/demo7/dist/assets/media/svg/icons/Communication/Urgent-mail.svg-->
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="24px"
                                                height="24px"
                                                viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                    <path
                                                        d="M12.7037037,14 L15.6666667,10 L13.4444444,10 L13.4444444,6 L9,12 L11.2222222,12 L11.2222222,14 L6,14 C5.44771525,14 5,13.5522847 5,13 L5,3 C5,2.44771525 5.44771525,2 6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,13 C19,13.5522847 18.5522847,14 18,14 L12.7037037,14 Z"
                                                        fill="#000000"
                                                        opacity="0.3"></path>
                                                    <path
                                                        d="M9.80428954,10.9142091 L9,12 L11.2222222,12 L11.2222222,16 L15.6666667,10 L15.4615385,10 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 L9.80428954,10.9142091 Z"
                                                        fill="#000000"></path>
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <a href="#" class="text-success font-weight-bold font-size-h7 mt-2">Reglamento</a>
                                    </div>
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Stats-->
                            <div class="resize-triggers">
                                <div class="expand-trigger">
                                    <div style="width: 314px; height: 349px;"></div>
                                </div>
                                <div class="contract-trigger"></div>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Mixed Widget 2-->
                </div>
                <!--end::Col1-->
                <!--begin::Col2-->
                <div class="col-xl-7  col-lg-5 col-md-7"> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18--> <!--end::Mixed Widget 18-->
                    <!--begin::Mixed Widget 18-->
                    <div class="card card-custom gutter-b card-stretch bg-warning-o-70">
                        <div class="container-wrapper-genially" style="position: relative; min-height: 400px; max-width: 100%;"><video class="loader-genially" autoplay="autoplay" loop="loop" playsinline="playsInline" muted="muted" style="position: absolute;top: 45%;left: 50%;transform: translate(-50%, -50%);width: 80px;height: 80px;margin-bottom: 10%"><source src="https://static.genial.ly/resources/loader-default.mp4" type="video/mp4" />Your browser does not support the video tag.</video><div id="65c6165adc741a0014710796" class="genially-embed" style="margin: 0px auto; position: relative; height: auto; width: 100%;"></div></div><script>(function (d) { var js, id = "genially-embed-js", ref = d.getElementsByTagName("script")[0]; if (d.getElementById(id)) { return; } js = d.createElement("script"); js.id = id; js.async = true; js.src = "https://view.genial.ly/static/embed/embed.js"; ref.parentNode.insertBefore(js, ref); }(document));</script>
                    </div>
                    <!--end::Mixed Widget 18-->
                </div>
                <!--end::Col2-->
                <!--end::Row-->
            </div>
            <!--end::Row-->
            <!--end::Dashboard-->





            <!--end::Row-->
                    
            <!--begin::Row-->
                    <div class="row">
                        <div class="col-xl-4">
                            <!--begin::Stats Widget 1-->
                            <div class="card card-custom wave wave-animate-slow wave-danger card card-custom bgi-no-repeat gutter-b card-stretch"
                                style="background-position: right top; background-size: 30% auto; background-image: url(/metronic/theme/html/demo7/dist/assets/media/svg/shapes/abstract-4.svg)">
                                <!--begin::Body-->
                                <div class="card-body">
									<div class="d-flex align-items-center p-5">
												<!--begin::Icon-->
														<div class="mr-6">
															<span class="svg-icon svg-icon-primary svg-icon-4x">
                                                                    <!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Home/Mirror.svg-->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3"></path>
                                            <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000"></path>
                                        </g>
                                    </svg>
                                                                    <!--end::Svg Icon-->
															</span>
														</div>
												<!--end::Icon-->
												<!--begin::Content-->
														<div class="d-flex flex-column">
															<a href="<?php echo base_url(); ?>index.php/admin/test_email" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Prueba de Emails</a>
															<div class="text-dark-75">Prueba de envios de correos Electrónicos</div>
														</div>
												<!--end::Content-->
									</div>
											
                                </div> 
							</div>
                                <!--end::Stats Widget 1-->
                            </div>
                            <div class="col-xl-4">
                                <!--begin::Stats Widget 2-->
                                <div
                                    class="card card-custom wave wave-animate-slow wave-danger card card-custom bgi-no-repeat gutter-b card-stretch"
                                    style="background-position: right top; background-size: 30% auto; background-image: url(/metronic/theme/html/demo7/dist/assets/media/svg/shapes/abstract-2.svg)">
                                    <!--begin::Body-->
                                    <div class="card-body">
                                    <div class="d-flex align-items-center p-5">
												<!--begin::Icon-->
														<div class="mr-6">
															<span class="svg-icon svg-icon-primary svg-icon-4x">
                                                                    <!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Home/Mirror.svg-->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"></path>
                                            <path d="M10.875,15.75 C10.6354167,15.75 10.3958333,15.6541667 10.2041667,15.4625 L8.2875,13.5458333 C7.90416667,13.1625 7.90416667,12.5875 8.2875,12.2041667 C8.67083333,11.8208333 9.29375,11.8208333 9.62916667,12.2041667 L10.875,13.45 L14.0375,10.2875 C14.4208333,9.90416667 14.9958333,9.90416667 15.3791667,10.2875 C15.7625,10.6708333 15.7625,11.2458333 15.3791667,11.6291667 L11.5458333,15.4625 C11.3541667,15.6541667 11.1145833,15.75 10.875,15.75 Z" fill="#000000"></path>
                                            <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"></path>
                                        </g>
                                    </svg>
                                                                    <!--end::Svg Icon-->
															</span>
														</div>
												<!--end::Icon-->
												<!--begin::Content-->
														<div class="d-flex flex-column">
															<a href="<?php echo base_url(); ?>index.php/admin/update_tables" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Actualizar Tablas</a>
															<div class="text-dark-75">Actualizar Base de Datos</div>
														</div>
												<!--end::Content-->
									</div>
                                        
                                        </div>
                                        <!--end::Body-->
                                </div>
                                    <!--end::Stats Widget 2-->
                                </div>
                                <div class="col-xl-4">
                                    <!--begin::Stats Widget 3-->
                                    <div
                                        class=" card card-custom wave wave-animate-slow wave-danger card card-custom bgi-no-repeat gutter-b card-stretch"
                                        style="background-position: right top; background-size: 30% auto; background-image: url(/metronic/theme/html/demo7/dist/assets/media/svg/shapes/abstract-1.svg)">
                                        <!--begin::body-->
                                        <div class="card-body">
                                            <div class="d-flex align-items-center p-5">
												<!--begin::Icon-->
														<div class="mr-6">
															<span class="svg-icon svg-icon-primary svg-icon-4x">
                                                                    <!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/icons/Home/Mirror.svg-->
                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                                            <path d="M13,17.0484323 L13,18 L14,18 C15.1045695,18 16,18.8954305 16,20 L8,20 C8,18.8954305 8.8954305,18 10,18 L11,18 L11,17.0482312 C6.89844817,16.5925472 3.58685702,13.3691811 3.07555009,9.22038742 C3.00799634,8.67224972 3.3975866,8.17313318 3.94572429,8.10557943 C4.49386199,8.03802567 4.99297853,8.42761593 5.06053229,8.97575363 C5.4896663,12.4577884 8.46049164,15.1035129 12.0008191,15.1035129 C15.577644,15.1035129 18.5681939,12.4043008 18.9524872,8.87772126 C19.0123158,8.32868667 19.505897,7.93210686 20.0549316,7.99193546 C20.6039661,8.05176407 21.000546,8.54534521 20.9407173,9.09437981 C20.4824216,13.3000638 17.1471597,16.5885839 13,17.0484323 Z" fill="#000000" fill-rule="nonzero"></path>
                                                                            <path d="M12,14 C8.6862915,14 6,11.3137085 6,8 C6,4.6862915 8.6862915,2 12,2 C15.3137085,2 18,4.6862915 18,8 C18,11.3137085 15.3137085,14 12,14 Z M8.81595773,7.80077353 C8.79067542,7.43921955 8.47708263,7.16661749 8.11552864,7.19189981 C7.75397465,7.21718213 7.4813726,7.53077492 7.50665492,7.89232891 C7.62279197,9.55316612 8.39667037,10.8635466 9.79502238,11.7671393 C10.099435,11.9638458 10.5056723,11.8765328 10.7023788,11.5721203 C10.8990854,11.2677077 10.8117724,10.8614704 10.5073598,10.6647638 C9.4559885,9.98538454 8.90327706,9.04949813 8.81595773,7.80077353 Z" fill="#000000" opacity="0.3"></path>
                                                                        </g>
                                                                    </svg>
                                                                    <!--end::Svg Icon-->
															</span>
														</div>
												<!--end::Icon-->
												<!--begin::Content-->
														<div class="d-flex flex-column">
															<!-- <a href="https://docs.google.com/forms/d/e/1FAIpQLScwDOFZRA6LBCrUN9NR3sbNhTAoV-Dgz7oYnTAd_D7ufRrdXA/viewform?usp=sf_link" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Soporte</a> -->
                                                            <a href="<?php echo base_url(); ?>index.php/teacher/infractions" class="text-dark text-hover-primary font-weight-bold font-size-h4 mb-3">Indisciplina</a>
															<div class="text-dark-75">Planillas de indisciplina por cursos.</div>
														</div>
												<!--end::Content-->
									            </div>
                                            </div>
                                            <!--end::Body-->
                                        </div>
                                        <!--end::Stats Widget 3-->
                                    </div>
                                </div>
                                <!--end::Row-->







        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
