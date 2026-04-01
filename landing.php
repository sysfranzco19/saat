<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Admisiones</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="<?= base_url() ?>home/assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" type="text/css" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="<?= base_url() ?>home/css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-light bg-light static-top">
            <div class="container">
                <a class="navbar-brand" href="https://tiquipaya.edu.bo">Admisiones Colegio Tiquipaya</a>
                <a class="btn btn-primary" href="<?= base_url('login') ?>">Acceder</a>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <div class="text-center text-white">
                            <!-- Page heading-->
                            <h1 class="mb-5">Cuéntenos más sobre su familia y usted</h1>
                            <!-- Signup form-->
                            <!-- * * * * * * * * * * * * * * *-->
                            <!-- * * SB Forms Contact Form * *-->
                            <!-- * * * * * * * * * * * * * * *-->
                            <!-- This form is pre-integrated with SB Forms.-->
                            <!-- To make this form functional, sign up at-->
                            <!-- https://startbootstrap.com/solution/contact-forms-->
                            <!-- to get an API token!-->
                            <form class="form-subscribe" id="contactForm" data-sb-form-api-token="API_TOKEN">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <input class="form-control form-control-lg" id="nombre" type="text" placeholder="Nombre" data-sb-validations="required" />
                                        <div class="invalid-feedback text-white" data-sb-feedback="nombre:required">El nombre es obligatorio.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control form-control-lg" id="apellido1" type="text" placeholder="Apellido Paterno" data-sb-validations="required" />
                                        <div class="invalid-feedback text-white" data-sb-feedback="apellido1:required">El apellido paterno es obligatorio.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control form-control-lg" id="apellido2" type="text" placeholder="Apellido Materno" />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <select class="form-control form-control-lg" id="curso" data-sb-validations="required">
                                            <option value="">Seleccione un curso</option>
                                            <option value="Kinder">Kinder</option>
                                            <option value="1ro Primaria">1ro de Primaria</option>
                                            <option value="2do Primaria">2do de Primaria</option>
                                        </select>
                                        <div class="invalid-feedback text-white" data-sb-feedback="curso:required">Debe seleccionar un curso.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control form-control-lg" id="celular" type="tel" placeholder="Celular" data-sb-validations="required" />
                                        <div class="invalid-feedback text-white" data-sb-feedback="celular:required">El celular es obligatorio.</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <input class="form-control form-control-lg" id="emailAddress" type="email" placeholder="Email" data-sb-validations="required,email" />
                                        <div class="invalid-feedback text-white" data-sb-feedback="emailAddress:required">El email es obligatorio.</div>
                                        <div class="invalid-feedback text-white" data-sb-feedback="emailAddress:email">Formato de email inválido.</div>
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-primary btn-lg disabled" id="submitButton" type="submit">Siguiente</button>
                                    </div>
                                </div>

                                <!-- Submit success message-->
                                <div class="d-none" id="submitSuccessMessage">
                                    <div class="text-center mb-3">
                                        <div class="fw-bolder">¡Formulario enviado con éxito!</div>
                                        <p>Para activar este formulario, regístrate en</p>
                                        <a class="text-white" href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
                                    </div>
                                </div>

                                <!-- Submit error message-->
                                <div class="d-none" id="submitErrorMessage">
                                    <div class="text-center text-danger mb-3">¡Error al enviar el mensaje!</div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Footer-->
        <footer class="footer bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 h-100 text-center text-lg-start my-auto">
                        <ul class="list-inline mb-2">
                            <li class="list-inline-item"><a href="#!">About</a></li>
                            <li class="list-inline-item">⋅</li>
                            <li class="list-inline-item"><a href="#!">Contact</a></li>
                            <li class="list-inline-item">⋅</li>
                            <li class="list-inline-item"><a href="#!">Terms of Use</a></li>
                            <li class="list-inline-item">⋅</li>
                            <li class="list-inline-item"><a href="#!">Privacy Policy</a></li>
                        </ul>
                        <p class="text-muted small mb-4 mb-lg-0">&copy; Your Website 2025. All Rights Reserved.</p>
                    </div>
                    <div class="col-lg-6 h-100 text-center text-lg-end my-auto">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item me-4">
                                <a href="#!"><i class="bi-facebook fs-3"></i></a>
                            </li>
                            <li class="list-inline-item me-4">
                                <a href="#!"><i class="bi-twitter fs-3"></i></a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#!"><i class="bi-instagram fs-3"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="<?= base_url() ?>home/js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>
</html>
