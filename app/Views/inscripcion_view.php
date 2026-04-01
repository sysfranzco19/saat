<!DOCTYPE html>
<html lang="es">
<!--begin::Head-->
<head>
    <meta charset="utf-8" />
    <title>Acceso a Inscripciones | SAAT</title>
    <meta name="description" content="Wizard examples" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://keenthemes.com/metronic" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Page Custom Styles(used by this page)-->
    <link href="<?php echo base_url();?>/assets/css/pages/wizard/wizard-1.css" rel="stylesheet" type="text/css" />
    <!--end::Page Custom Styles-->
    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="<?php echo base_url();?>/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>/assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url();?>/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->
    <!--begin::Layout Themes(used by all pages)-->
    <!--end::Layout Themes-->
    <link rel="shortcut icon" href="<?php echo base_url();?>/assets/media/logos/favicon.ico" />
    <?php
    ?>
    <script language="javascript" type="text/javascript">
    function aMayusculas(obj,id){
        obj = obj.toUpperCase();
        document.getElementById(id).value = obj;
    }
    function enviar(){
        //alert('holas');
        //document.getElementById('form_datos').submit();
    }
    let form = 0;
    function validar() {
        //var nit = document.getElementById('nit').value;
        if (nit=="") {
            //alert('Por favor completa ');
            //document.getElementById('nit').focus();
            //return false;
        }
    }
    </script>
</head>
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex  flex-row flex-column-fluid page">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid " id="kt_wrapper">
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class="container-fluid">
                            <div class="card card-custom">
                                <div class="card-body p-0">
                                    <!--begin::Wizard-->
                                    <div class="wizard wizard-1" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="false">
                                        <!--begin::Wizard Nav-->
                                        <div class="wizard-nav border-bottom">
                                            <div class="wizard-steps p-8 p-lg-10">
                                                <!--begin::Wizard Step 1 Nav-->
                                                <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                                                    <div class="wizard-label">
                                                        <i class="wizard-icon flaticon-list"></i>
                                                        <h3 class="wizard-title">1. Actualización de datos Familia</h3>
                                                    </div>
                                                    <span class="svg-icon svg-icon-xl wizard-arrow">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </span>
                                                </div>
                                                <!--end::Wizard Step 1 Nav-->
                                                <!--begin::Wizard Step 2 Nav-->
                                                <div class="wizard-step" data-wizard-type="step">
                                                    <div class="wizard-label">
                                                        <i class="wizard-icon flaticon-analytics"></i>
                                                        <h3 class="wizard-title">2. Actualización de RUDE</h3>
                                                    </div>
                                                    <span class="svg-icon svg-icon-xl wizard-arrow">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </span>
                                                </div>
                                                <!--end::Wizard Step 2 Nav-->
                                                <!--begin::Wizard Step 3 Nav-->
                                                <div class="wizard-step" data-wizard-type="step">
                                                    <div class="wizard-label">
                                                        <i class="wizard-icon flaticon-coins"></i>
                                                        <h3 class="wizard-title">3. Métodos de Pago</h3>
                                                    </div>
                                                    <span class="svg-icon svg-icon-xl wizard-arrow">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </span>
                                                </div>
                                                <!--end::Wizard Step 3 Nav-->
                                                <!--begin::Wizard Step 4 Nav-->
                                                <div class="wizard-step" data-wizard-type="step">
                                                    <div class="wizard-label">
                                                        <i class="wizard-icon flaticon-clock-2"></i>
                                                        <h3 class="wizard-title">4. Horario de Inscripción</h3>
                                                    </div>
                                                    <span class="svg-icon svg-icon-xl wizard-arrow">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </span>
                                                </div>
                                                <!--end::Wizard Step 4 Nav-->
                                                <!--begin::Wizard Step 5 Nav-->
                                                <div class="wizard-step" data-wizard-type="step">
                                                    <div class="wizard-label">
                                                        <i class="wizard-icon flaticon-logout"></i>
                                                        <h3 class="wizard-title">5. Revisión y envío</h3>
                                                    </div>
                                                    <span class="svg-icon svg-icon-xl wizard-arrow last">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Arrow-right.svg-->
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                                                <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </span>
                                                </div>
                                                <!--end::Wizard Step 5 Nav-->
                                            </div>
                                        </div>
                                        <!--end::Wizard Nav-->
                                        <!--begin::Wizard Body-->
                                        <div class="row justify-content-center my-10 px-8 my-lg-15 px-lg-10">
                                            <div class="col-xl-12 col-xxl-7">
                                                <!--begin::Wizard Form-->
                                                <form action="<?php echo base_url('/inscripcion_family'); ?>" method="post" class="form" id="kt_form">
                                                    <!--begin::Wizard Step 1-->
                                                    <div class="pb-5" data-wizard-type="step-content" data-wizard-state="current">
                                                        


        <!-- /******************************************************** DATOS FAMILIA *************************************************************/ -->
            <div class="col-md-12">
                <!--begin:<form  method="post" id="form_datos">:Card-->
                
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Datos Familia: <?php echo $fam['lastname1'];?> <?php echo $fam['lastname2'];?></h3>
                            <input type="hidden" id="idFamilia" name="idFamilia" value='<?php echo $fam['family_id']; ?>' />
                        </div>
                        <!--begin::Form-->
                    
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Domicilio de los Estudiantes:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['home_address'];?>" id="home_address" name="home_address" placeholder="home_address" onblur="aMayusculas(this.value,this.id)" required />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Barrio :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['neighborhood'];?>" id="neighborhood" name="neighborhood" placeholder="neighborhood" onblur="aMayusculas(this.value,this.id)" required />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Referencia del Domicilio:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['reference'];?>" id="reference" name="reference" placeholder="reference" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Telefono Casa:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['home_phone'];?>" id="home_phone" name="home_phone" placeholder="home_phone" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label" for="relation_id"><span class="float-right">Relación: </span></label>
                                <div class="col-9">
                                    <select class="form-control" id="relation_id" name="relation_id" placeholder="relation_id" >
                                        <option value='1' <?php if($fam['relation_id']==1){echo 'selected';}?> >Casados</option>
                                        <option value='2' <?php if($fam['relation_id']==2){echo 'selected';}?> >Divorciados</option>
                                        <option value='3' <?php if($fam['relation_id']==3){echo 'selected';}?> >Padre Fallecido</option>
                                        <option value='4' <?php if($fam['relation_id']==4){echo 'selected';}?> >Madre Fallecida</option>
                                        <option value='5' <?php if($fam['relation_id']==5){echo 'selected';}?> >Separados</option>
                                        <option value='6' <?php if($fam['relation_id']==6){echo 'selected';}?> >Sin dato</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Email 1:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['email1'];?>" id="email1" name="email1" required />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Email 2:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['email2'];?>" id="email2" name="email2" placeholder="email2" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">NIT para Facturas:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['nit'];?>" id="nit" name="nit" placeholder="nit" onblur="aMayusculas(this.value,this.id)" required />
                                </div>
                            </div>
                            

                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Tipo de documento:</span></label>
                                <div class="col-9">
                                    <select class="form-control" name="tipo_documento_id" id="tipo_documento_id" placeholder="tipo_documento_id" required >
                                        <option value="" >Seleccione un tipo_documento_id</option>
                                        <?php foreach($tipos_doc as $tip):?>
                                        <option value="<?php echo $tip->tipo_documento_id; ?>" <?php if($tip->tipo_documento_id==$fam['tipo_documento']){ echo "selected"; } ?> ><?php echo $tip->tipo_documento; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Nombre para Facturas:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['nombre_factura'];?>" id="nombre_factura" name="nombre_factura" placeholder="nombre_factura" onblur="aMayusculas(this.value,this.id)" />
                                </div>
                            </div>
                        </div>



                        <div class="card-header">
                            <h3 class="card-title">Datos Padre</h3>
                            <input type="hidden" id="padre_id" name="padre_id" value='<?php echo $pad['parent_id']; ?>' />
                        </div>
                        <!--begin::Form-->
                    
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Nombres :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['name'];?>" id="namePadre" name="namePadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Apellido Paterno :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['lastname1'];?>" id="lastname1Padre" name="lastname1Padre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Apellido Materno :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['lastname2'];?>" id="lastname2Padre" name="lastname2Padre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">C.I. Padre :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['card'];?>" id="cardPadre" name="cardPadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label" for="place_cardPadre"><span class="float-right">Expedido: </span></label>
                                <div class="col-9">
                                    <select class="form-control" id="place_cardPadre" name="place_cardPadre" >
                                        <option value='0' <?php if($pad['place_card']==0){echo 'selected';}?> >C. EXTRANJERO</option>
                                        <option value='1' <?php if($pad['place_card']==1){echo 'selected';}?> >Cochabamba</option>
                                        <option value='2' <?php if($pad['place_card']==2){echo 'selected';}?> >La Paz</option>
                                        <option value='3' <?php if($pad['place_card']==3){echo 'selected';}?> >Santa Cruz</option>
                                        <option value='4' <?php if($pad['place_card']==4){echo 'selected';}?> >Tarija</option>
                                        <option value='5' <?php if($pad['place_card']==5){echo 'selected';}?> >Beni</option>
                                        <option value='6' <?php if($pad['place_card']==6){echo 'selected';}?> >Sucre</option>
                                        <option value='7' <?php if($pad['place_card']==7){echo 'selected';}?> >Potosi</option>
                                        <option value='8' <?php if($pad['place_card']==8){echo 'selected';}?> >Oruro</option>
                                        <option value='9' <?php if($pad['place_card']==9){echo 'selected';}?> >Pando</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Profesión/Ocupación del Padre:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['profession'];?>" id="professionPadre" name="professionPadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Empresa donde trabaja :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['business'];?>" id="businessPadre" name="businessPadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Telefono Oficina Padre :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['workphone'];?>" id="workphonePadre" name="workphonePadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Celular Padre :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['cellphone'];?>" id="cellphonePadre" name="cellphonePadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Idioma que habla frecuentemente el Padre:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['idiom'];?>" id="idiomPadre" name="idiomPadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Ocupacion del Padre :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['occupation'];?>" id="occupationPadre" name="occupationPadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Mayor grado de instrucción alcanzado :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $pad['degree_instruction'];?>" id="degree_instructionPadre" name="degree_instructionPadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                        </div>
                        

                        <div class="card-header">
                            <h3 class="card-title">Datos Madre</h3>
                            <input type="hidden" id="madre_id" name="madre_id" value='<?php echo $mad['parent_id']; ?>' />
                        </div>
                        <!--begin::Form-->
                    
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Nombres :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['name'];?>" id="nameMadre" name="nameMadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Apellido Paterno :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['lastname1'];?>" id="lastname1Madre" name="lastname1Madre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Apellido Materno :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['lastname2'];?>" id="lastname2Madre" name="lastname2Madre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">C.I. Madre :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['card'];?>" id="cardMadre" name="cardMadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label" for="place_cardMadre"><span class="float-right">Expedido: </span></label>
                                <div class="col-9">
                                    <select class="form-control" id="place_cardMadre" name="place_cardMadre" >
                                        <option value='0' <?php if($mad['place_card']==0){echo 'selected';}?> >C. EXTRANJERO</option>
                                        <option value='1' <?php if($mad['place_card']==1){echo 'selected';}?> >Cochabamba</option>
                                        <option value='2' <?php if($mad['place_card']==2){echo 'selected';}?> >La Paz</option>
                                        <option value='3' <?php if($mad['place_card']==3){echo 'selected';}?> >Santa Cruz</option>
                                        <option value='4' <?php if($mad['place_card']==4){echo 'selected';}?> >Tarija</option>
                                        <option value='5' <?php if($mad['place_card']==5){echo 'selected';}?> >Beni</option>
                                        <option value='6' <?php if($mad['place_card']==6){echo 'selected';}?> >Sucre</option>
                                        <option value='7' <?php if($mad['place_card']==7){echo 'selected';}?> >Potosi</option>
                                        <option value='8' <?php if($mad['place_card']==8){echo 'selected';}?> >Oruro</option>
                                        <option value='9' <?php if($mad['place_card']==9){echo 'selected';}?> >Pando</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Profesión/Ocupación de la Madre:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['profession'];?>" id="professionMadre" name="professionMadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Empresa donde trabaja :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['business'];?>" id="businessMadre" name="businessMadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Telefono Oficina Madre :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['workphone'];?>" id="workphoneMadre" name="workphoneMadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Celular Madre :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['cellphone'];?>" id="cellphoneMadre" name="cellphoneMadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Idioma que habla frecuentemente la Madre:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['idiom'];?>" id="idiomMadre" name="idiomMadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Ocupacion de la Madre :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['occupation'];?>" id="occupationMadre" name="occupationMadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Mayor grado de instrucción alcanzado :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $mad['degree_instruction'];?>" id="degree_instructionMadre" name="degree_instructionMadre" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--end::Card-->
            </div>
            <!-- /******************************************************** DATOS FAMILIA *************************************************************/ -->




                                                    </div>

                                                    <!--end::Wizard Step 1-->
                                                    <!--begin::Wizard Step 2-->
                                                    <div class="pb-5" data-wizard-type="step-content">
                                                        <!--begin::Input-->
    <!-- *****************************************************************DATOS ESUDIANTE *********************************************************** -->
    <div class="col-md-12">
                <!--begin::Card-->
                
                    <div class="card card-custom gutter-b example example-compact">
                        <!--begin::Form-->
                        <?php
                        $i = 0;
                        foreach ($alumnos as $row){
                            $id = $row->id_rude;
                            $i += 1;
                        ?>
                        <div class="card-header">
                            <h3 class="card-title">Estudiante: <?php echo $row->apat.' '.$row->amat.' '.$row->nombres; ?></h3>
                            <input type="hidden" id="madre_id" name="madre_id" value='<?php echo $mad['parent_id']; ?>' />
                            <input type='hidden' id='IdAlumno<?php echo $i; ?>' name='IdAlumno<?php echo $i; ?>' value='<?php echo $id; ?>' >
                        </div>
                        <div class="card-body">
                            <h5>Lugar de Nacimiento</h5>
                            <div class="form-row">
                                
                                <div class="form-group col-md-3" >
                                    <label for=''>País:</label>
                                    <select id='pais_<?php echo $id; ?>' name='pais_<?php echo $id; ?>' class="form-control" >
                                        <option>Alemania</option>
                                        <option>Argentina</option>
                                        <option>Australia</option>
                                        <option>Austria</option>
                                        <option>Belgica</option>
                                        <option selected >Bolivia</option>
                                        <option >Brasil</option>
                                        <option >Chile</option>
                                        <option >Colombia</option>
                                        <option >Costa Rica</option>
                                        <option>Ecuador</option>
                                        <option>España</option>
                                        <option>Italia</option>
                                        <option>Malawi</option>
                                        <option>Mexico</option>
                                        <option>Noruega</option>
                                        <option>Paraguay</option>
                                        <option>Perú</option>
                                        <option>Senegal</option>
                                        <option>Sud África</option>
                                        <option>Suecia</option>
                                        <option>Suiza</option>
                                        <option>U.S.A.</option>
                                        <option>Ucrania</option>
                                        <option>Uruguay</option>
                                        <option>Venezuela</option>
                                        <option>Otro…</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3" >
                                    <label for=''>Departamento:</label>
                                    <input type='text' id='dpto_<?php echo $id; ?>' name='dpto_<?php echo $id; ?>' class="form-control" value='<?php echo $row->dpto_nac; ?>' onblur="aMayusculas(this.value,this.id)" />
                                </div>
                                <div class="form-group col-md-3" >
                                    <label for='provincia_est'>Provincia:</label>
                                    <input type='text' id='provincia_<?php echo $id; ?>' name='provincia_<?php echo $id; ?>' class="form-control" value='<?php echo $row->provincia_nac; ?>' onblur="aMayusculas(this.value,this.id)" />
                                </div>
                                <div class="form-group col-md-3" >
                                    <label for='localidad_est'>Localidad:</label>
                                    <input type='text' id='localidad_<?php echo $id; ?>' name='localidad_<?php echo $id; ?>' class="form-control" value='<?php echo $row->localidad_nac; ?>' onblur="aMayusculas(this.value,this.id)" />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3" >
                                    <label for='rude_est'>Código RUDE:</label>
                                    <input type='text' id='rude_est' name='rude_est' class="form-control" value='<?php echo $row->rude; ?>' readonly />
                                </div>
                                <div class="form-group col-md-2" >
                                    <label for='nro_doc'>Número de doc.:</label>
                                    <input type='text' id='nro_doc' name='nro_doc' class="form-control" value='<?php echo $row->nro_doc; ?>' readonly />
                                </div>
                                <div class="form-group col-md-2" >
                                    <label for='tipo_doc'>Tipo de doc.:</label>
                                    <select id="tipo_doc_<?php echo $id; ?>" name="tipo_doc_<?php echo $id; ?>" class="form-control">
                                        <option value="1" selected>C.I.</option>
                                        <option value="2">Pasaporte</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-1" >
                                    <label for='fecha_nac'>Compl. :</label>
                                    <input type='text' id='complemento_doc_<?php echo $id; ?>' name='complemento_doc_<?php echo $id; ?>' class="form-control" value='<?php echo $row->complemento_doc; ?>' onblur="aMayusculas(this.value,this.id)" />
                                </div>
                                <div class="form-group col-md-1" >
                                    <label for='fecha_nac'>Expedido:</label>
                                    <input type='text' id='expedido_doc_<?php echo $id; ?>' name='expedido_doc_<?php echo $id; ?>' class="form-control" value='<?php echo $row->expedido_doc; ?>' onblur="aMayusculas(this.value,this.id)" />
                                </div>
                                <div class="form-group col-md-2" >
                                    <label for='fecha_nac'>Fecha de Nac.:</label>
                                    <input type='text' id='fecha_nac' name='fecha_nac' class="form-control" value='<?php echo $row->fecha_nac; ?>' readonly />
                                </div>
                                <div class="form-group col-md-1" >
                                    <label for='sexo'>Sexo:</label>
                                    <input type='text' id='sexo' name='sexo' class="form-control" value='<?php echo $row->sexo; ?>' readonly />
                                </div>
                            </div>

                            <h5>Discapacidad</h5>
                            <div class="form-row">
                                <div class="form-inline col-md-6" >
                                    ¿La o el estudiante presenta alguna discapacidad?
                                    <select id="discapacidad<?php echo $id; ?>" name="discapacidad<?php echo $id; ?>" class="form-control">
                                        <option value="2" selected>NO</option>
                                        <option value="1">SI</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3" >
                                    Nro. de Registro de discapacidad:
                                </div>
                                <div class="form-group col-md-3" >
                                    <input type='text' id='nro_dis<?php echo $id; ?>' name='nro_dis<?php echo $id; ?>' class="form-control" value='<?php echo $row->nro_discapacidad; ?>' />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4" >
                                    Tipo de discapacidad<br />
                                    <label><input type="radio" name="tipo_dis<?php echo $id; ?>" id="tipo_dis<?php echo $id; ?>" value="1" >Psíquica</label><br />
                                    <label><input type="radio" name="tipo_dis<?php echo $id; ?>" id="tipo_dis<?php echo $id; ?>" value="2" >Autismo</label><br />
                                    <label><input type="radio" name="tipo_dis<?php echo $id; ?>" id="tipo_dis<?php echo $id; ?>" value="3" >Síndrome de Down</label><br />
                                    <label><input type="radio" name="tipo_dis<?php echo $id; ?>" id="tipo_dis<?php echo $id; ?>" value="4" >Intelectual</label>
                                </div>
                                <div class="form-group col-md-4" >
                                    <br />
                                    <label><input type="radio" name="tipo_dis<?php echo $id; ?>" id="tipo_dis<?php echo $id; ?>" value="5" >Auditiva</label><br />
                                    <label><input type="radio" name="tipo_dis<?php echo $id; ?>" id="tipo_dis<?php echo $id; ?>" value="6" >Física-Motora</label><br />
                                    <label><input type="radio" name="tipo_dis<?php echo $id; ?>" id="tipo_dis<?php echo $id; ?>" value="7" >Sordoceguera</label><br />
                                    <label><input type="radio" name="tipo_dis<?php echo $id; ?>" id="tipo_dis<?php echo $id; ?>" value="8" >Multiple</label><br />
                                    <label><input type="radio" name="tipo_dis<?php echo $id; ?>" id="tipo_dis<?php echo $id; ?>" value="9" >Visual</label>
                                </div>
                                <div class="form-group col-md-4" >
                                    Su grado de discapacidad es :<br />
                                    <label><input type="radio" id="grado_dis<?php echo $id; ?>" name="grado_dis<?php echo $id; ?>" value="1" > Leve</label><br />
                                    <label><input type="radio" id="grado_dis<?php echo $id; ?>" name="grado_dis<?php echo $id; ?>" value="2" > Moderado</label><br />
                                    <label><input type="radio" id="grado_dis<?php echo $id; ?>" name="grado_dis<?php echo $id; ?>" value="3" > Grave</label><br />
                                    <label><input type="radio" id="grado_dis<?php echo $id; ?>" name="grado_dis<?php echo $id; ?>" value="4" > Muy grave</label><br />
                                    <label><input type="radio" id="grado_dis<?php echo $id; ?>" name="grado_dis<?php echo $id; ?>" value="5" > Ceguera total</label><br />
                                    <label><input type="radio" id="grado_dis<?php echo $id; ?>" name="grado_dis<?php echo $id; ?>" value="6" > Baja vision</label>
                                </div>

                            </div>
                            
                        <h5>Dirección actual del o la Estudiante</h5>
                            <div class="form-row">
                                <div class="form-group col-md-2" >
                                    <label for='provincia_dir'>Departamento:</label>
                                    <input type='text' id='departamento_dir<?php echo $id; ?>' name='departamento_dir<?php echo $id; ?>' class="form-control" value='<?php echo $row->departamento_dir; ?>' />
                                </div>
                                <div class="form-group col-md-2" >
                                    <label for='provincia_dir'>Provincia:</label>
                                    <input type='text' id='provincia_dir<?php echo $id; ?>' name='provincia_dir<?php echo $id; ?>' class="form-control" value='<?php echo $row->provincia_dir; ?>' />
                                </div>
                                <div class="form-group col-md-2" >
                                    <label for='municipio_dir'>Municipio:</label>
                                    <input type='text' id='municipio_dir<?php echo $id; ?>' name='municipio_dir<?php echo $id; ?>' class="form-control" value='<?php echo $row->municipio_dir; ?>' />
                                </div>
                                <div class="form-group col-md-3" >
                                    <label for='localidad_dir'>Localidad:</label>
                                    <input type='text' id='localidad_dir<?php echo $id; ?>' name='localidad_dir<?php echo $id; ?>' class="form-control" value='<?php echo $row->localidad_dir; ?>' />
                                </div>
                                <div class="form-group col-md-3" >
                                    <label for='zona_dir'>Zona / Villa:</label>
                                    <input type='text' id='zona_dir<?php echo $id; ?>' name='zona_dir<?php echo $id; ?>' class="form-control" value='<?php echo $row->zona_dir; ?>' />
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6" >
                                    <label for='avenida_dir'>Avenida / Calle:</label>
                                    <input type='text' id='avenida_dir<?php echo $id; ?>' name='avenida_dir<?php echo $id; ?>' class="form-control" value='<?php echo $row->avenida_dir; ?>' />
                                </div>
                                <div class="form-group col-md-2" >
                                    <label for='telefono_dir'>Telefono :</label>
                                    <input type='text' id='telefono_dir<?php echo $id; ?>' name='telefono_dir<?php echo $id; ?>' class="form-control" value='<?php echo $row->telefono_dir; ?>' />
                                </div>
                                <div class="form-group col-md-2" >
                                    <label for='celular_dir'>Celular:</label>
                                    <input type='text' id='celular_dir<?php echo $id; ?>' name='celular_dir<?php echo $id; ?>' class="form-control" value='<?php echo $row->celular_dir; ?>' />
                                </div>
                                <div class="form-group col-md-2" >
                                    <label for='nro_vivienda'>Nro. Vivienda:</label>
                                    <input type='text' id='nro_vivienda<?php echo $id; ?>' name='nro_vivienda<?php echo $id; ?>' class="form-control" value='<?php echo $row->nro_vivienda; ?>' />
                                </div>
                            </div>
                            <h5>Aspectos sociales</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6" >
                                    IDIOMA Y PERTENECIA CULTURAL<br />
                                    <label for=''>¿Cual es el idioma que aprendio a hablar en su niñez la o el estudiante?:</label>
                                    <input type='text' id='primer_idioma<?php echo $id; ?>' name='primer_idioma<?php echo $id; ?>' class="form-control" value='<?php echo $row->primer_idioma; ?>' onblur="aMayusculas(this.value,this.id)" />
                                    <label for=''>¿Que idiomas habla frecuentemente la o el estudiante?</label>
                                    <?php 
                                    $otros_idiomas = explode("-", $row->idiomas_frecuentes);
                                    if (!isset($otros_idiomas[0])) {$otros_idiomas[0]=" ";}
                                    if (!isset($otros_idiomas[1])) {$otros_idiomas[1]=" ";}
                                    if (!isset($otros_idiomas[2])) {$otros_idiomas[2]=" ";}
                                    ?>
                                    <input type='text' id='idioma1<?php echo $id; ?>' name='idioma1<?php echo $id; ?>' class="form-control" value='<?php echo $otros_idiomas[0]; ?>' onblur="aMayusculas(this.value,this.id)" />
                                    <input type='text' id='idioma2<?php echo $id; ?>' name='idioma2<?php echo $id; ?>' class="form-control" value='<?php echo $otros_idiomas[1]; ?>' onblur="aMayusculas(this.value,this.id)" />
                                    <input type='text' id='idioma3<?php echo $id; ?>' name='idioma3<?php echo $id; ?>' class="form-control" value='<?php echo $otros_idiomas[2]; ?>' onblur="aMayusculas(this.value,this.id)" />
                                    <br />
                                    ¿Pertenece a alguna nación, pueblo indígena originario campesino o afroboliviano?:<br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="nacion<?php echo $id; ?>[]" id="nacion<?php echo $id; ?>" value="1" checked > Ninguno</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="nacion<?php echo $id; ?>[]" id="nacion<?php echo $id; ?>" value="2"> Afroboliviano</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="nacion<?php echo $id; ?>[]" id="nacion<?php echo $id; ?>" value="3"> Aymara</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="nacion<?php echo $id; ?>[]" id="nacion<?php echo $id; ?>" value="4" > Guarani</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="nacion<?php echo $id; ?>[]" id="nacion<?php echo $id; ?>" value="5" > Quechua</label><br />
                                    <input type='text' id='nacion' name='nacion' class="form-control" value='' placeholder="Otro..." />
                                </div>
                        
                                <div class="form-inline col-md-6" >
                                    SALUD<br />
                                    <label for='centro_salud'>Existe un Centro de SAlud / Posta / Hospital en su comunidad:</label><br />
                                    <select id="centro_salud<?php echo $id; ?>" name="centro_salud<?php echo $id; ?>" class="form-control">
                                        <option value="1" selected >SI</option>
                                        <option value="2" >NO</option>
                                    </select><br /><br />
                                    <label for=''>El año pasado, por problemas de salud ¿acudió o se atendió en...?: </label>
                                    <label class="form-check-label">
                                        <input type="checkbox" name="tipo_cen[]" id="tipo_cen" value="1" <?php if ($row->asiste_centro_salud==1) {echo 'checked';} ?> >
                                            Caja o seguro de salud</label><br />
                                    <label class="form-check-label">
                                        <input type="checkbox" name="tipo_cen[]" id="tipo_cen" value="2" <?php if ($row->asiste_centro_salud==2) {echo 'checked';} ?> >
                                            Establecimientos de salud públicos</label><br />
                                    <label class="form-check-label">
                                        <input type="checkbox" name="tipo_cen[]" id="tipo_cen" value="3" <?php if ($row->asiste_centro_salud==3) {echo 'checked';} ?> >
                                            Establecimientos de salud privados</label><br />
                                    <label class="form-check-label">
                                        <input type="checkbox" name="tipo_cen[]" id="tipo_cen" value="4" <?php if ($row->asiste_centro_salud==4) {echo 'checked';} ?> >
                                            En su vivienda</label><br />
                                    <label class="form-check-label">
                                        <input type="checkbox" name="tipo_cen[]" id="tipo_cen" value="5" <?php if ($row->asiste_centro_salud==5) {echo 'checked';} ?> >
                                            Medicina Tradicional</label><br />
                                    <label class="form-check-label">
                                        <input type="checkbox" name="tipo_cen[]" id="tipo_cen" value="6" <?php if ($row->asiste_centro_salud==6) {echo 'checked';} ?> >
                                            La Farmacia sin receta médica (automedicación)</label>
                                    <br /><br />
                                </div>
                                <div class="form-group col-md-6" >
                                    ¿Cuantas veces fue la o el estudiante al centro de salud el año pasado?
                                    <label class="radio-inline">
                                        <input type="radio" id="asiste<?php echo $id; ?>" value="1" name="asiste<?php echo $id; ?>" >1 a 2 veces&nbsp;&nbsp;&nbsp;
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" id="asiste<?php echo $id; ?>" value="2" name="asiste<?php echo $id; ?>" >3 a 5 veces&nbsp&nbsp&nbsp
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" id="asiste<?php echo $id; ?>" value="3" name="asiste<?php echo $id; ?>" >6 o más veces&nbsp&nbsp&nbsp
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" id="asiste<?php echo $id; ?>" value="4" name="asiste<?php echo $id; ?>" checked >ninguna&nbsp&nbsp&nbsp
                                    </label>
                                    <br /><br />
                                    <label for='seguro_salud'>¿La o el estudiante tiene seguro de salud?</label><br />
                                    <select id="seguro_salud<?php echo $id; ?>" name="seguro_salud<?php echo $id; ?>" class="form-control">
                                        <option value="1" selected >SI</option>
                                        <option value="2" >NO</option>
                                    </select>
                                    <br />
                                    <br />
                                </div>
                            </div>



                            <h5>Acceso a servicios básicos</h5>
                            <div class="form-row">
                                <div class="form-group col-md-4" >
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="servicios<?php echo $id; ?>[]" id="servicios<?php echo $id; ?>" value="1" checked>¿Tiene acceso a agua por cañeria de red?</label><br /><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="servicios<?php echo $id; ?>[]" id="servicios<?php echo $id; ?>" value="2" checked>¿Tiene baño en su vivienda?</label><br /><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="servicios<?php echo $id; ?>[]" id="servicios<?php echo $id; ?>" value="3" checked>¿Tiene red de alcantarillado?</label><br /><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="servicios<?php echo $id; ?>[]" id="servicios<?php echo $id; ?>" value="4" checked>¿Usa energia electrica en su vivienda?</label><br /><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="servicios<?php echo $id; ?>[]" id="servicios<?php echo $id; ?>" value="5" checked>¿Cuenta con servicio de recojo de basura?</label><br />
                                </div>
                                <div class="form-group col-md-3" >
                                    ¿La vivienda que ocupa el lugar es? <br />
                                    <label>
                                        <input type="radio" id="vivienda" name="vivienda" value="1" <?php if ($row->tipo_vivienda==1) {echo 'checked';} ?>> Propia
                                    </label><br />
                                    <label>
                                        <input type="radio" id="vivienda" name="vivienda" value="2" <?php if ($row->tipo_vivienda==2) {echo 'checked';} ?>> Alquilada
                                    </label><br />
                                    <label>
                                        <input type="radio" id="vivienda" name="vivienda" value="3" <?php if ($row->tipo_vivienda==3) {echo 'checked';} ?>> Anticrético
                                    </label><br />
                                    <label>
                                        <input type="radio" id="vivienda" name="vivienda" value="4" <?php if ($row->tipo_vivienda==4) {echo 'checked';} ?>> Cedida por servicios
                                    </label><br />
                                    <label>
                                        <input type="radio" id="vivienda" name="vivienda" value="5" <?php if ($row->tipo_vivienda==5) {echo 'checked';} ?>> Prestada por parientes/amigos
                                    </label><br />
                                    <label>
                                        <input type="radio" id="vivienda" name="vivienda" value="6" <?php if ($row->tipo_vivienda==6) {echo 'checked';} ?>> Contrato mixto
                                    </label><br />
                                </div>
                                <div class="form-group col-md-3" >
                                    El estudiante accede a internet en:<br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="acceso_int<?php echo $id; ?>[]" id="acceso_int<?php echo $id; ?>" value="1" checked >Su vivienda</label><br /><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="acceso_int<?php echo $id; ?>[]" id="acceso_int<?php echo $id; ?>" value="2" checked >La Unidad Educativa</label><br /><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="acceso_int<?php echo $id; ?>[]" id="acceso_int<?php echo $id; ?>" value="3" checked >Lugares Públicos</label><br /><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="acceso_int<?php echo $id; ?>[]" id="acceso_int<?php echo $id; ?>" value="4" checked >Teléfono Celular</label><br /><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="acceso_int<?php echo $id; ?>[]" id="acceso_int<?php echo $id; ?>" value="5" >No accede a internet</label><br />
                                </div>
                                <div class="form-group col-md-2" >
                                    ¿Con que frecuencia usa internet?<br /><br />
                                    <label><input type="radio" id="frecuencia_int<?php echo $id; ?>" name="frecuencia_int<?php echo $id; ?>" value="1" checked > Diariamente</label><br />
                                    <label><input type="radio" id="frecuencia_int<?php echo $id; ?>" name="frecuencia_int<?php echo $id; ?>" value="2"> Una vez a la semana</label><br />
                                    <label><input type="radio" id="frecuencia_int<?php echo $id; ?>" name="frecuencia_int<?php echo $id; ?>" value="3"> Más de una vez a la semana</label><br />
                                    <label><input type="radio" id="frecuencia_int<?php echo $id; ?>" name="frecuencia_int<?php echo $id; ?>" value="4"> Una vez al mes</label><br />
                                </div>
                            </div>
                            <h5>Actividad Laboral</h5>
                            <div class="form-row">
                                <div class="form-inline col-md-6" >
                                    <label>¿La o el estudiante trabajó?</label><br />
                                    <select id="alumno_trabajo<?php echo $id; ?>" name="alumno_trabajo<?php echo $id; ?>" class="form-control">
                                        <option value="1" >SI</option>
                                        <option value="2" selected >NO</option>
                                    </select><br />
                                    Marque los meses que trabajo el estudiante :<br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="1" >Ene</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="2" >Feb</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="3" >Mar</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="4" >Abr</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="5" >May</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="6" >Jun</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="7" >Jul</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="8" >Ago</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="9" >Sep</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="10" >Oct</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="11" >Nov</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="meses_trabajo<?php echo $id; ?>[]" id="meses_trabajo<?php echo $id; ?>" value="12" >Dic</label><br /><br />
                                    ¿En que turno trabajó el estudiante? :<br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="turno_trabajo<?php echo $id; ?>[]" id="turno_trabajo<?php echo $id; ?>" value="1" >Mañana</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="turno_trabajo<?php echo $id; ?>[]" id="turno_trabajo<?php echo $id; ?>" value="2" >Tarde</label>
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="turno_trabajo<?php echo $id; ?>[]" id="turno_trabajo<?php echo $id; ?>" value="3" >Noche</label><br /><br />
                                    ¿Con qué frecuencia trabajó el estudiante? :<br />
                                    <label><input type="radio" id="fre_trabajo<?php echo $id; ?>" name="fre_trabajo<?php echo $id; ?>" value="1"> Todos los días</label><br />
                                    <label><input type="radio" id="fre_trabajo<?php echo $id; ?>" name="fre_trabajo<?php echo $id; ?>" value="2"> Días hábiles</label><br />
                                    <label><input type="radio" id="fre_trabajo<?php echo $id; ?>" name="fre_trabajo<?php echo $id; ?>" value="3"> Fines de semana</label><br />
                                    <label><input type="radio" id="fre_trabajo<?php echo $id; ?>" name="fre_trabajo<?php echo $id; ?>" value="4"> Eventual</label><br />
                                    <label><input type="radio" id="fre_trabajo<?php echo $id; ?>" name="fre_trabajo<?php echo $id; ?>" value="5"> Días Festivos</label><br />
                                    <label><input type="radio" id="fre_trabajo<?php echo $id; ?>" name="fre_trabajo<?php echo $id; ?>" value="6"> En vacaciones</label><br /><br />
                                </div>
                                <div class="form-inline col-md-6" >
                                    En la gestión pasada ¿En qué actividad trabajo el estudiante? :<br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="1"> Agricultura</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="2"> Ganaderia o pesca</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="3"> Minería</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="4"> Construcción</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="5"> Zafra</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="6"> Vendedor dependiente</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="7"> Vendedor por cuenta propia</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="8"> Transporte o mecánica</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="9"> Lustrabotas</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="10"> Niñera(o)</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="11"> Ayudante Familiar</label><br />
                                    <label><input type="radio" id="actividades<?php echo $id; ?>" name="actividades<?php echo $id; ?>" value="12"> Otro trabajo</label><br /><br />
                                    ¿Recibió algún pago por el trabajo realizado?<br />
                                    <select id="pago_trabajo<?php echo $id; ?>" name="pago_trabajo<?php echo $id; ?>" class="form-control">
                                        <option value="1" selected >NO</option>
                                        <option value="2" >SI - En especie</option>
                                        <option value="3" >SI - En dinero</option>
                                    </select>
                                </div>
                            </div>
                            
                            <h5>Medios de Transporte</h5>
                            <div class="form-row">
                                <div class="form-group col-md-6" >
                                    Generalmente, ¿Cómo llega el estudiante a la Unidad Educativa? :<br />
                                    <label>
                                        <input type="radio" id="transporte" name="transporte" value="1" <?php if ($row->transporte==1) {echo 'checked';} ?> > A pie
                                    </label><br />
                                    <label>
                                        <input type="radio" id="transporte" name="transporte" value="2" <?php if ($row->transporte==2) {echo 'checked';} ?> > En vehiculo
                                    </label><br />
                                    <label>
                                        <input type="radio" id="transporte" name="transporte" value="3" <?php if ($row->transporte==3) {echo 'checked';} ?> > Fluvial
                                    </label><br />
                                    <label>
                                        <input type="radio" id="transporte" name="transporte" value="4" <?php if ($row->transporte==4) {echo 'checked';} ?> > Otros
                                    </label><br /><br />
                                
                                    Según el transporte señalado, ¿Qué tiempo demora el estudiante en llegar a la Unidad Educativa? :<br />
                                    <label>
                                        <input type="radio" id="tiempo" name="tiempo" value="1" <?php if ($row->tiempo_casa_ue==1) {echo 'checked';} ?> > Menos de media hora
                                    </label><br />
                                    <label>
                                        <input type="radio" id="tiempo" name="tiempo" value="2" <?php if ($row->tiempo_casa_ue==2) {echo 'checked';} ?> > Entre media hora y una hora
                                    </label><br />
                                    <label>
                                        <input type="radio" id="tiempo" name="tiempo" value="3" <?php if ($row->tiempo_casa_ue==3) {echo 'checked';} ?> > Entre una a dos horas
                                    </label><br />
                                    <label>
                                        <input type="radio" id="tiempo" name="tiempo" value="4" <?php if ($row->tiempo_casa_ue==4) {echo 'checked';} ?> > Más de dos horas
                                    </label><br />
                                    <label>La o el estudiante vive habitualmente con :</label><br />
                                    <select id="vive_con" name="vive_con" class="form-control">
                                        <option value="1" selected >Padre y Madre</option>
                                        <option value="2" >Solo Padre</option>
                                        <option value="3" >Solo Madre</option>
                                        <option value="4" >Tutor(a)</option>
                                        <option value="5" >Solo(a)</option>
                                    </select><br />
                                </div>
                                <div class="form-group col-md-6" >
                                    <label>¿La o el estudiante abandonó la Unidad Educativa el año pasado?</label><br />
                                    <select id="alumno_trabajo<?php echo $id; ?>" name="alumno_trabajo<?php echo $id; ?>" class="form-control">
                                        <option value="1" >SI</option>
                                        <option value="2" selected >NO</option>
                                    </select><br />
                                    Cuales fueron las razones de abandono escolar:<br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="1" >Tuvo que ayudar a sus padres</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="2" >Tuvo trabajo remunerado</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="3" >Falta de dinero</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="4" >Edad temprana/Edad tardía</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="5" >La Unidad Educativa era distante</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="6" >Labores de casa/Cuidado de niños</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="7" >Embarazo o paternidad</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="8" >Enfermedad/Accidente/Discapacidad</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="9" >Viaje o traslado</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="10" >Falta de interés</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="11" >Bulling o discrimicacion</label><br />
                                    <label class="form-check-label"><input class="form-check-input" type="checkbox" name="abandono<?php echo $id; ?>[]" id="abandono<?php echo $id; ?>" value="12" >Otras razones</label><br /><br />
                                </div>
                        
                            </div>

                        



                        </div>
                        <?php
                        }
                        ?>
                        <!-- 
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-3"></div>
								<div class="col-3">
                                    <button type="button" class="btn btn-secondary btn-lg btn-block" onclick="window.location='<?php echo base_url(); ?>index.php/parents/dashboard'" >Cancelar</button>
                                </div>
								<div class="col-3">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Actualizar Datos</button>
								</div>
                                <div class="col-3"></div>
                            </div>
                        </div>
                         -->

                    </div>
                    <!--end::Card-->
            </div>
    <!-- *****************************************************************DATOS ESUDIANTE ***********************************************************-->
                                                        
                                                    </div>
                                                    <!--end::Wizard Step 2-->
                                                    <!--begin::Wizard Step 3-->
                                                    <div class="pb-5" data-wizard-type="step-content">
                                                        <h4 class="mb-10 font-weight-bold text-dark">Modalidad de pago</h4>
                                                        <!--begin::Select-->
                                                        <div class="text-dark-50 line-height-lg">
                                                            <div>
                                                                <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                                                                    <img src="https://tiquipaya.edu.bo/img/smartphone.png" width="115" alt="" style="width:115px;max-width:80%;margin-bottom:20px;">
                                                                </div>
                                                                <div class="col-lge" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                                                    <p style="margin-top:0;margin-bottom:12px;"><b> PENSIONES BNBNet+</b> </p>
                                                                    <p>Se ha puesto a su disposición el siguiente servicio para realizar los pagos de las responsabilidades económicas: “PAGO PENSIONES BNBNet+ por la WEB (Plataforma BNB y Móvil).</p>
                                                                </div>
                                                            </div>
                                                                <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                                                                    <img src="https://tiquipaya.edu.bo/img/bank.png" width="115" alt="" style="width:115px;max-width:80%;margin-bottom:20px;">
                                                                </div>
                            
                                                                <div class="col-lge" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                                                    <p style="margin-top:0;margin-bottom:12px;"><b> Pago en Agencias BNB</b> </p>
                                                                    <p>Apersonarse a cualquier agencia del Banco Nacional de Bolivia S.A., indicando el nombre completo del alumno y/o ID del estudiante en las ventanillas habilitadas por la Institución Financiera.</p>
                                                                </div>
                                                            <div><h5 class="mb-10 font-weight-bold text-dark">Como se informó con anticipacion, el Colegio ya no cuenta con las siguientes modalidades de pago:</h5></div>
                                                        </div>
                                                        <ul> 
                                                            <li><b>TRANSFERENCIA BANCARIA</b> 
                                                            <li><b>EL PAGO EN EFECTIVO OFICINAS DE ADMINISTRACIÓN</b>
                                                        </ul>
                                                    </div>
                                                    <!--end::Wizard Step 3-->
                                                    <!--begin::Wizard Step 4-->
                                                    <div class="pb-5" data-wizard-type="step-content">
															<h4 class="mb-10 font-weight-bold text-dark">CRONOGRAMA DE INSCRIPCIONES 2023</h4>
															<!--begin::Input-->
															<table class="table table-bordered mb-6">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">LETRAS</th>
                                                                        <th scope="col">DIA</th>
                                                                        <th scope="col">HORA</th>
                                                                
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <th scope="row">A-B-C-D-E-F </th>
                                                                        <td>MARTES 17</td>
                                                                        <td>Hrs. 8:30 – 15:00 </td>
                                                                    
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">G-H-I-J-K-L-M</th>
                                                                        <td>MIÉRCOLES 18</td>
                                                                        <td>Hrs. 8:30 – 15:00 </td>
                                                                        
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">N-O-P-Q-R-S</th>
                                                                        <td>JUEVES 19</td>
                                                                        <td>Hrs. 8:30 – 15:00 </td>
                                                                    
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">T-U-V-W-X-Y-Z </th>
                                                                        <td>VIERNES 20</td>
                                                                        <td>Hrs. 8:30 – 15:00 </td>
                                                                    
                                                                    </tr>
                                                                    <tr>
                                                                        <th scope="row">REZAGADOS</th>
                                                                        <td>24 Y 25</td>
                                                                        <td>Hrs. 8:30 – 13:00 </td>
                                                                
                                                                    </tr>
                                                                </tbody>
                                                            </table>
														</div>
                                                    <!--end::Wizard Step 4-->
                                                    <!--begin::Wizard Step 5-->
                                                    <div class="pb-5" data-wizard-type="step-content">
                                                        <!--begin::Section-->
                                                        <h4 class="mb-10 font-weight-bold text-dark">Revisión y envío</h4>

<!-- **************************************************************IMPRIMIR RUDE ******************************************-->
<div class="col-md-12">
                <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Impresión de Documentos:</h3>
                            <input type="hidden" id="idFamilia" name="idFamilia" value='<?php echo $fam['family_id']; ?>' />
                        </div>
                        <!--begin::Form-->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-4"><span class="float-right">Familia:</span></label>
                                <div class="col-8">
                                <span><?php echo $fam['lastname1'];?> <?php echo $fam['lastname2'];?></span>
                                </div>
                            </div>
                            <?php
                            foreach ($alumnos as $row){
                                ?>
                                <div class="form-group row">
                                    <label class="col-4"><span class="float-right">Rude de:</span></label>
                                    <div class="col-8">
                                        <!-- <a href="<?php echo base_url(); ?>index.php/parents/down_rudes/<?php echo $row->id_rude; ?>" target='_blank' class='btn btn-success btn-lg' >Impresión de documento</a> -->
                                        <?php echo $row->apat.' '.$row->amat.' '.$row->nombres; ?>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="form-group row">
                            </div>
                        </div>
                    </div>
                    <!--end::Card-->
            </div>
<!-- **************************************************************IMPRIMIR RUDE ******************************************-->


                                                        
                                                    </div>
                                                    <!--end::Wizard Step 5-->
                                                    <!--begin::Wizard Actions-->
                                                    <div class="d-flex justify-content-between border-top mt-5 pt-10">
                                                        <div class="mr-2">
                                                            <a href="<?php echo base_url('/logout_inscripcion'); ?>" class="btn btn-secondary font-weight-bolder text-uppercase px-9 py-4" >CANCELAR</a>
                                                            <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-prev" name="back" id="back">ATRÁS</button>
                                                        </div>
                                                        <div>

                                                            <button type="button" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-submit" >ENVIAR</button>
                                                            
                                                            <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" data-wizard-type="action-next" onclick="validar()" >SIGUIENTE</button>
                                                            <!-- data-wizard-type="action-next" -->
                                                        </div>
                                                    </div>
                                                    <!--end::Wizard Actions-->
                                                </form>
                                                <!--end::Wizard Form-->
                                                <!--  
                                                <a href="<?php echo base_url('/logout_inscripcion'); ?>" class="btn btn-danger font-weight-bolder text-uppercase px-9 py-4" >SALIR</a>
                                                <a href="<?php echo base_url('/inscripcion_error'); ?>" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4" >ERROR</a>
                                                -->
                                            </div>
                                        </div>
                                        <!--end::Wizard Body-->
                                    </div>
                                    <!--end::Wizard-->
                                </div>
                                <!--end::Wizard-->
                            </div>
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Aside Secondary-->
            
        </div>
        <!--end::Page-->
    </div>
    <!--end::Main-->
    <!-- begin::Notifications Panel-->
    <script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
    <!--begin::Global Config(global config for global JS scripts)-->
    <script>var KTAppSettings = { "breakpoints": { "sm": 100, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#663259", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#F4E1F0", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
    <!--end::Global Config-->
    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="<?php echo base_url();?>/assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url();?>/assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="<?php echo base_url();?>/assets/js/scripts.bundle.js"></script>
    <!--end::Global Theme Bundle-->
    <!--begin::Page Scripts(used by this page)-->
    <script src="<?php echo base_url();?>/assets/js/pages/custom/wizard/wizard-1.js"></script>
    <!--end::Page Scripts-->
</body>
<!--end::Body-->
</html>

