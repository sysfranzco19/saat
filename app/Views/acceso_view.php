<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso a Inscripciones | SAAT</title>

    <meta name="description" content="Plataforma Colegio Tiquipaya" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://keenthemes.com/metronic" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="<?php echo base_url();?>/assets/css/pages/login/login-4.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css">
    <!--end::Page Custom Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="<?php echo base_url();?>/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>/assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->

    <link rel="shortcut icon" href="<?php echo base_url();?>/assets/media/logos/favicon.ico" />
    <script type="text/javascript">
	var baseurl = '<?php echo base_url();?>';
        function enviar(){
            window.location = "<?php echo base_url();?>/inscripcion_inicio";
        }
        //Función que permite solo Números
        function ValidaSoloNumeros() {
            if ((event.keyCode < 48) || (event.keyCode > 57)) 
            event.returnValue = false;
            }
	</script>
</head>
    <!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Login-->
			<div class="login login-4 wizard d-flex flex-column flex-lg-row flex-column-fluid wizard" id="kt_login">
				<!--begin::Content-->
				<div class="login-container d-flex flex-center flex-row flex-row-fluid order-2 order-lg-1 flex-row-fluid bg-white py-lg-0 pb-lg-0 pt-15 pb-12">
					<!--begin::Container-->
					<div class="login-content login-content-signup d-flex flex-column">
						<!--begin::Aside Top-->
						<div class="d-flex flex-column-auto flex-column px-10">
							<!--begin::Aside header-->
							<a href="#" class="login-logo pb-lg-4 pb-10">
								<img src="assets/media/logos/logo-4.png" class="max-h-70px" alt="" />
							</a>
							<!--end::Aside header-->
						</div>
                        <br />
                        <br />
                        <br />
						<!--end::Aside Top-->
						
                        <!--begin::Signin-->
						<div class="login-form">
                            <!--begin::Form-->
							<form class="form px-10" method="post" action="<?php echo base_url('index.php/login_inscripcion'); ?>" id="kt_login_signup_form">
                                    <!--begin::Title-->
									<div class="pb-10 pb-lg-12">
										<h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Verificación de Datos</h3>
										<div class="text-muted font-weight-bold font-size-h4">Olvidó sus datos ?
										<a href="custom/pages/login/login-4/signin.html" class="text-primary font-weight-bolder">Solicitar Datos</a></div>
									</div>
									<!--begin::Title-->
									<!--begin::Form Group-->
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">Email Padre, Madre o Tutor</label>
										<input type="text" class="form-control form-control-solid h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="email" placeholder="Email Padre, Madre o Tutor" autocomplete="off" value="" required />
									</div>
									<!--end::Form Group-->
									<!--begin::Form Group-->
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">CI del Estudiante</label>
										<input type="text" class="form-control form-control-solid h-auto py-7 px-6 border-0 rounded-lg font-size-h6" name="ci_estudiante" placeholder="Carnet de Identidad del Estudiante" autocomplete="off" value="" required />
                                        <span></span>
									</div>
									<!--end::Form Group-->
                                    <!--begin::Form Group-->
									<div class="form-group">
										<label class="font-size-h6 font-weight-bolder text-dark">Fecha de Nacimiento del Estudiante</label><div class="row">
                                            <div class="col-sm">
                                                <select class="form-control form-control-solid h-auto py-6 px-6 rounded-lg font-size-h6" name="dia" id="dia" required >
                                                    <option value=''>Día</option>    
                                                    <option value='01'>1</option>
                                                    <option value='02'>2</option>
                                                    <option value='03'>3</option>
                                                    <option value='04'>4</option>
                                                    <option value='05'>5</option>
                                                    <option value='06'>6</option>
                                                    <option value='07'>7</option>
                                                    <option value='08'>8</option>
                                                    <option value='09'>9</option>
                                                    <option value='10'>10</option>
                                                    <option value='11'>11</option>
                                                    <option value='12'>12</option>
                                                    <option value='13'>13</option>
                                                    <option value='14'>14</option>
                                                    <option value='15'>15</option>
                                                    <option value='16'>16</option>
                                                    <option value='17'>17</option>
                                                    <option value='18'>18</option>
                                                    <option value='19'>19</option>
                                                    <option value='20'>20</option>
                                                    <option value='21'>21</option>
                                                    <option value='22'>22</option>
                                                    <option value='23'>23</option>
                                                    <option value='24'>24</option>
                                                    <option value='25'>25</option>
                                                    <option value='26'>26</option>
                                                    <option value='27'>27</option>
                                                    <option value='28'>28</option>
                                                    <option value='29'>29</option>
                                                    <option value='30'>30</option>
                                                    <option value='31'>31</option>
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <select class="form-control form-control-solid h-auto py-6 px-6 rounded-lg font-size-h6" name="mes" id="mes" required >
                                                    <option value=''>Mes</option>
                                                    <option value='01'>Enero</option>
                                                    <option value='02'>Febrero</option>
                                                    <option value='03'>Marzo</option>
                                                    <option value='04'>Abril</option>
                                                    <option value='05'>Mayo</option>
                                                    <option value='06'>Junio</option>
                                                    <option value='07'>Julio</option>
                                                    <option value='08'>Agosto</option>
                                                    <option value='09'>Septiembre</option>
                                                    <option value='10'>Octubre</option>
                                                    <option value='11'>Noviembre</option>
                                                    <option value='12'>Diciembre</option>
                                                </select>
                                            </div>
                                            <div class="col-sm">
                                                <select class="form-control form-control-solid h-auto py-6 px-6 rounded-lg font-size-h6" name="anio" id="anio" autocomplete="off" required>
                                                    <option value=''>Año</option>
                                                    <option value='2018'>2018</option>
                                                    <option value='2017'>2017</option>
                                                    <option value='2016'>2016</option>
                                                    <option value='2015'>2015</option>
                                                    <option value='2014'>2014</option>
                                                    <option value='2013'>2013</option>
                                                    <option value='2012'>2012</option>
                                                    <option value='2011'>2011</option>
                                                    <option value='2010'>2010</option>
                                                    <option value='2009'>2009</option>
                                                    <option value='2008'>2008</option>
                                                    <option value='2007'>2007</option>
                                                    <option value='2006'>2006</option>
                                                    <option value='2005'>2005</option>
                                                    <option value='2004'>2004</option>
                                                    <option value='2003'>2003</option>
                                                </select>
                                            </div>
                                            
                                            
                                        </div>
									</div>
									<!--end::Form Group-->
                                    <div class="d-flex justify-content-between pt-7">
                                        <button type="submit" class="btn btn-primary font-weight-bolder font-size-h6 pl-8 pr-4 py-4 my-3" >Acceder
										<span class="svg-icon svg-icon-md ml-2">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Right-2.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000)" x="7.5" y="7.5" width="2" height="9" rx="1" />
													<path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span></button>
                                    </div>
                            </form>
							<!--end::Form-->
						</div>
						<!--end::Signin-->
					</div>
					<!--end::Container-->
				</div>
				<!--begin::Content-->
				<!--begin::Aside-->
				<div class="login-aside order-1 order-lg-2 bgi-no-repeat bgi-position-x-right">
					<div class="login-conteiner bgi-no-repeat bgi-position-x-right bgi-position-y-bottom" style="background-image: url(assets/media/svg/illustrations/login-visual-4.svg);">
						<!--begin::Aside title-->
						<h3 class="pt-lg-40 pl-lg-20 pb-lg-0 pl-10 py-20 m-0 d-flex justify-content-lg-start font-weight-boldest display5 display1-lg text-white">Acceso
						<br />Proceso de
						<br />Inscripciones</h3>
						<!--end::Aside title-->
					</div>
				</div>
				<!--end::Aside-->
			</div>
			<!--end::Login-->
		</div>
		<!--end::Main-->
		<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#663259", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#F4E1F0", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Scripts(used by this page)-->
		<script src="assets/js/pages/custom/login/login-4.js"></script>
		<!--end::Page Scripts-->
        <script type="text/javascript">
            <?php 
            if (isset($mensaje)) {
                ?>
                let mensaje = '<?php echo $mensaje; ?>';
                if (mensaje=='0') {
                    Swal.fire(':)','Dato ingresados verificados correctamente','success');
                } else if(mensaje=='1'){
                    Swal.fire(':(','Datos inválidos','error');
                } else if(mensaje=='2'){
                    Swal.fire(':(','Datos inválidos','error');
                } else if(mensaje=='3'){
                    Swal.fire(':(','Fallo al Actualizar','error');
                } else if(mensaje=='4'){
                    Swal.fire(':)','Eliminado con éxito','success');
                } else if(mensaje=='5'){
                    Swal.fire(':(','Fallo al eliminar','error');
                };
                <?php
            }
            ?>
    </script>
	</body>
	<!--end::Body-->
</html>