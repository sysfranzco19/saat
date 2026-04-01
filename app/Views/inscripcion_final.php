<!DOCTYPE html>
<html lang="es">
	<!--begin::Head-->
	<head>
		<meta charset="utf-8" />
		<title>Finalizar Actualizaccion | SAAT</title>
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="canonical" href="https://keenthemes.com/metronic" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Custom Styles(used by this page)-->
		<link href="<?php echo base_url();?>/assets/css/pages/error/error-5.css" rel="stylesheet" type="text/css" />
		<!--end::Page Custom Styles-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="<?php echo base_url();?>/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url();?>/assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url();?>/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->
		<link rel="shortcut icon" href="<?php echo base_url();?>/assets/media/logos/favicon.ico" />
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">


<?php
    $session = session();
    $family_id = session('family_id');
    $student_id = session('student_id');
    foreach ($alumnos as $row){
        //$family_id = $row->family_id;
        $student_id = $row->id_rude;
        $student = $row->apat.' '.$row->amat.' '.$row->nombres;
    }
    ?>
<div class="d-flex flex-column flex-root">
			


<div class="text-center pt-15">
    <h1 class="font-weight-bolder text-dark mb-6">
        Impresión de Documento
    </h1>
    <div class="h4 text-dark-50">
        
    </div>
  
</div>







<div class="card card-custom position-relative overflow-hidden">
    <!--begin::Shape-->
    <div class="position-absolute opacity-30"><span class="svg-icon svg-icon-10x svg-logo-white"><!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/shapes/abstract-8.svg--><svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">
<g clip-path="url(#clip0)">
<path d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z" fill="#AD84FF"></path>
<path d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z" fill="#AD84FF"></path>
<path d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z" fill="#AD84FF"></path>
<path d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z" fill="#AD84FF"></path>
<path d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z" fill="#AD84FF"></path>
<path d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z" fill="#AD84FF"></path>
<path d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z" fill="#AD84FF"></path>
</g>
</svg><!--end::Svg Icon--></span></div>
    <!--end::Shape-->

    <!--begin::Invoice header-->
    
    <!--end::Invoice header-->
    <div class="row justify-content-center py-8 px-8 py-md-30 px-md-0">
        <div class="col-md-9">
            <!--begin::Invoice body-->
            <div class="row pb-26">
                <div class="col-md-3 border-right-md pr-md-10 py-md-10">
                    <!--begin::Invoice To-->
                    <div class="text-dark-50 font-size-lg font-weight-bold mb-3">Colegio Tiquipaya</div>
                    <div class="font-size-lg font-weight-bold mb-10">Tiquipaya-Zona Trojes</div>
                    <!--end::Invoice To-->

                    <!--begin::Invoice No-->
                    <div class="text-dark-50 font-size-lg font-weight-bold mb-3">Telf. </div>
                    <div class="font-size-lg font-weight-bold mb-10"> 4313148 4310842</div>
                    <!--end::Invoice No-->

                    <!--begin::Invoice Date-->
                    <div class="text-dark-50 font-size-lg font-weight-bold mb-3">E-mail: </div>
                    <div class="font-size-lg font-weight-bold">colegio@tiquipaya.edu.bo</div>
                    <!--end::Invoice Date-->
                </div>
                <div class="col-md-9 py-10 pl-md-10">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="pt-1 pb-9 pl-0 pl-md-5 font-weight-bolder text-muted font-size-lg text-uppercase">Datos</th>
                                    <th class="pt-1 pb-9 text-right font-weight-bolder text-muted font-size-lg text-uppercase">Opciones</th>
                                    <th class="pt-1 pb-9 text-right font-weight-bolder text-muted font-size-lg text-uppercase"></th>
                                    <th class="pt-1 pb-9 text-right pr-0 font-weight-bolder text-muted font-size-lg text-uppercase"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="font-weight-bolder font-size-lg">
                                    <td class="border-top-0 pl-0 pl-md-5 pt-7 d-flex align-items-center">
                                        <span class="navi-icon mr-2">
                                            <i class="fa fa-genderless text-danger font-size-h2"></i>
                                        </span>
                                       Código Estudiante :
                                    </td>
                                    <td class="text-right pt-7"><?php echo $student_id; ?></td>
                                    <td class="text-right pt-7"></td>
                                    <td class="pr-0 pt-7 font-size-h6 font-weight-boldest text-right"></td>
                                </tr>
                                <tr class="font-weight-bolder font-size-lg">
                                    <td class="border-top-0 pl-0 pl-md-5 pt-7 d-flex align-items-center">
                                    Impresion de Rude
                                    </td>
                                    <td class="text-right pt-7"><a href="<?php echo base_url('/inscripcion_rude/'.$student_id); ?>" target="_blank" class="btn btn-success btn-lg">Impresión de documento</a></td>
                                    <td class="text-right pt-7"></td>
                                    <td class="pr-0 pt-7 font-size-h6 font-weight-boldest text-right"></td>
                                </tr>
                                <tr>
                                    <td colspan=4>
                                    Este botón le permitirá descargar el <b>FORMULARIO RUDE</b>, para luego ser impreso <b>ANVERSO Y REVERSO en hoja bond tamaño Oficio y firmado con bolígrafo azul</b> (Requisito indispensable para la inscripción).</p>
                                    </td>
                                </tr>
                                
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end::Invoice body-->

            <!--begin::Invoice footer-->
            <div class="row">
                <div class="col-md-5 border-top pt-14 pb-10 pb-md-18">
                    <div class="d-flex flex-column flex-md-row">
                        <div class="d-flex flex-column">
                            <div class="font-weight-bold font-size-h6 mb-3">Soporte Departamento Informático</div>

                            <div class="d-flex justify-content-between font-size-lg mb-3">
                                <span class="font-weight-bold mr-15">Whatsapp</span>
                                <span class="text-right">Solo mensajes</span>
                            </div>

                            <div class="d-flex justify-content-between font-size-lg mb-3">
                                <span class="font-weight-bold mr-15">Nro.</span>
                                <span class="text-right">76965562</span>
                            </div>

                          
                        </div>
                    </div>
                </div>
                <div class="col-md-7 pt-md-25">
                    <div class="bg-primary rounded d-flex align-items-center justify-content-between text-white max-w-350px position-relative ml-auto p-7">
                        <!--begin::Shape-->
                        <div class="position-absolute opacity-30 top-0 right-0"><span class="svg-icon svg-icon-2x svg-logo-white svg-icon-flip"><!--begin::Svg Icon | path:/metronic/theme/html/demo10/dist/assets/media/svg/shapes/abstract-8.svg--><svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">
<g clip-path="url(#clip0)">
<path d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z" fill="#AD84FF"></path>
<path d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z" fill="#AD84FF"></path>
<path d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z" fill="#AD84FF"></path>
<path d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z" fill="#AD84FF"></path>
<path d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z" fill="#AD84FF"></path>
<path d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z" fill="#AD84FF"></path>
<path d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z" fill="#AD84FF"></path>
</g>
</svg><!--end::Svg Icon--></span></div>
                        <!--end::Shape-->
                        <div class="font-weight-boldest font-size-h5">
                        <a href="<?php echo base_url('/logout_inscripcion'); ?>" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4">FINALIZAR</a>
                        </div>
                    </div>
            <!--end::Invoice footer-->
        </div>
    </div>

    <!-- begin: Invoice action-->
    

    <!-- end: Invoice action-->

</div>
		</div>

<!--end::Main-->
<script>

</script>
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#663259", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#F4E1F0", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Theme Bundle-->
        <script src="<?php echo base_url();?>/assets/js/pages/custom/wizard/wizard-1.js"></script>
	</body>
	<!--end::Body-->
</html>


