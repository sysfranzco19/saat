<?php
$archivo ="RepT1".strval(60900045 + $student_id).".pdf";
?>
<div class="row mt-0 mt-lg-8">
    <div class="col-xl-12">
        <div class="card card-custom gutter-b">

            <!--begin::Card header-->
            <div class="card-header h-auto border-0">
                <div class="card-title py-5">
                    <h3 class="card-label">
                        <span class="d-block text-dark font-weight-bolder">Reporte Interno de Aprovechamiento de : <?php echo $student;?><br />Curso: <?php echo $completo;?></span>
                        <span class="d-block text-muted mt-2 font-size-sm"><?php echo $phase_name;?></span>
                    </h3>
                </div>
                
                <div class="card-toolbar">
                    <ul class="nav nav-pills nav-pills-sm nav-dark-75" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link py-2 px-4" data-toggle="tab"
                                href="#kt_charts_widget_2_chart_tab_1">
                                
                            </a>
                        </li>

                    </ul>
                </div>
                
            </div>
            <object data="https://tiquipaya.edu.bo/plataforma/public/uploads/t1/<?php echo $archivo;?>" type="application/pdf" width="100%" height="800px">
                <p>Su navegador no soporta el visualizador de PDF <a href="https://tiquipaya.edu.bo/plataforma/public/uploads/t1/<?php echo $archivo;?>">click aqui para descargar el Documento PDF.</a></p>
            </object>
        </div>
    </div>

</div>
<div class="d-flex flex-column-fluid">

    <!--begin::Container-->
    <div class="container">

        <div class="container-fluid">

        </div>
    </div>

    <!--end::Container-->
</div>

<!--end::Entry-->