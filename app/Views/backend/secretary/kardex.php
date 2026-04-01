<script type="text/javascript">
    function cargar(sel){
        window.location.assign('<?php echo base_url(); ?>/secretary/kardex/' + sel )
    }
</script>
<?php
$tipo = array('', 'CI ', 'CEX ', 'PAS ', 'OD ', 'NIT ');
if (isset($familia)) {
    $relation = $familia["relation"];
    $lastname1 = $familia["lastname1"];
    $lastname2 = $familia["lastname2"];
    $home_phone = $familia["home_phone"];
    $home_address = $familia["home_address"];
    $neighborhood = $familia["neighborhood"];
    $reference = $familia["reference"];
    $email1 = $familia["email1"];
    $email2 = $familia["email2"];
}else{
    $relation = "";
    $lastname1 = "";
    $lastname2 = "";
    $home_phone = "";
    $home_address = "";
    $neighborhood = "";
    $reference = "";
    $email1 = "";
    $email2 = "";
}
?>
<?php $session = session(); ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Kardex de Familia</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <select name="family" id="family" class="form-control" onChange="cargar(this.value);">
                                        <option value="" <?php if ($family_id==0) {echo 'selected';} ?>>Seleccione una Familia</option>
                                        <?php foreach($familias as $key):?>
                                        <option value="<?php echo $key->family_id; ?>" <?php if ($key->family_id==$family_id) {echo 'selected';} ?>><?php echo $key->family; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form class="form">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label>Nombre Padre:</label>
                                    <input type="text" class="form-control" value="<?php echo $family_id; ?>" readonly />
                                </div>
                                <div class="col-lg-3">
                                    <label>C.I. Padre:</label>
                                    <input type="text" class="form-control" value="<?php echo $lastname2; ?>" readonly />
                                </div>
                                <div class="col-lg-3">
                                    <label>Nacimiento:</label>
                                    <input type="text" class="form-control" value="<?php echo $home_phone; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-3">
                                    <label>Profesión:</label>
                                    <input type="text" class="form-control" value="<?php echo $home_phone; ?>" readonly />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-3">
                                    <label>Nombre Madre:</label>
                                    <input type="text" class="form-control" value="<?php echo $family_id; ?>" readonly />
                                </div>
                                <div class="col-lg-3">
                                    <label>C.I. Madre:</label>
                                    <input type="text" class="form-control" value="<?php echo $lastname2; ?>" readonly />
                                </div>
                                <div class="col-lg-3">
                                    <label>Nacimiento:</label>
                                    <input type="text" class="form-control" value="<?php echo $home_phone; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-3">
                                    <label>Profesión:</label>
                                    <input type="text" class="form-control" value="<?php echo $home_phone; ?>" readonly />
                                </div>
                            </div>
                            <div class="card-footer">
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-2">
                                    <label>IdFamilia:</label>
                                    <input type="text" class="form-control" value="<?php echo $family_id; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-2">
                                    <label>Relación Padres:</label>
                                    <input type="text" class="form-control" value="<?php echo $relation; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-2">
                                    <label>Primer Apellido:</label>
                                    <input type="text" class="form-control" value="<?php echo $lastname1; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-2">
                                    <label>Segundo Apellido:</label>
                                    <input type="text" class="form-control" value="<?php echo $lastname2; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-4">
                                    <label>Teléfono Casa:</label>
                                    <input type="text" class="form-control" value="<?php echo $home_phone; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label>Domicilio Familiar222:</label>
                                    <input type="text" class="form-control" value="<?php echo $lastname2; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-4">
                                    <label>Barrio/Zona:</label>
                                    <input type="text" class="form-control" value="<?php echo $neighborhood; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-4">
                                    <label>Referencia:</label>
                                    <input type="text" class="form-control" value="<?php echo $reference; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-lg-6">
                                    <label>Correo electrónico Nro. 1:</label>
                                    <input type="text" class="form-control" value="<?php echo $email1; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                                <div class="col-lg-6">
                                    <label>Correo electrónico Nro. 2:</label>
                                    <input type="text" class="form-control" value="<?php echo $email2; ?>" readonly />
                                    <span class="form-text text-muted"></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                        </div>
                        <div class="card-body">
                            <h5>Estudiantes</h5>
                            <?php if (isset($familia)) { ?>
                            <div class="example-preview">
                                <ul class="nav nav-tabs nav-tabs-line">
                                    <?php 
                                    $c = 1;
                                    foreach($students as $stu):
                                    ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php if($c==1){echo " active";} ?>" data-toggle="tab" href="#kt_tab_pane_<?php echo $c; ?>">
                                            <?php echo $stu->lastname; ?> <?php echo $stu->lastname2; ?> <?php echo $stu->name; ?></a>
                                        </li>
                                        
                                    <?php 
                                        $c += 1;
                                    endforeach; 
                                    ?>
                                </ul>
                                <div class="tab-content mt-5" id="myTabContent">
                                    <?php 
                                    $c = 1;
                                    foreach($students as $stu):
                                    ?>
                                        <div class="tab-pane fade<?php if($c==1){echo " show active";} ?>" id="kt_tab_pane_<?php echo $c; ?>" role="tabpanel" aria-labelledby="kt_tab_pane_2">
                                        Tab content <?php echo $c; ?> <?php echo $stu->lastname; ?> <?php echo $stu->lastname2; ?> <?php echo $stu->name; ?> 
                                        </div>
                                    <?php 
                                        $c += 1;
                                    endforeach; 
                                    ?>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </form>
                    <!--end::Form-->
                    
                    </div>
                </div>
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
