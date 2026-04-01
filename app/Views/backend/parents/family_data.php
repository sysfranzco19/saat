<script language="javascript" type="text/javascript">
    function aMayusculas(obj,id){
        obj = obj.toUpperCase();
        document.getElementById(id).value = obj;
    }
</script>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!--begin::Card-->
                <form action="<?php echo base_url();?>index.php/parents/family_update/" method="post" >
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <h3 class="card-title">Datos Familia: <?php echo $fam['lastname1'];?> <?php echo $fam['lastname2'];?></h3>
                            <input type="hidden" id="idFamilia" name="idFamilia" value='<?php echo $fam['family_id']; ?>' />
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Domicilio de los Estudiantes:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['home_address'];?>" id="home_address" name="home_address" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Barrio :</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['neighborhood'];?>" id="neighborhood" name="neighborhood" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Referencia del Domicilio:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['reference'];?>" id="reference" name="reference" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Telefono Casa:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['home_phone'];?>" id="home_phone" name="home_phone" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label" for="relation_id"><span class="float-right">Relación: </span></label>
                                <div class="col-9">
                                    <select class="form-control" id="relation_id" name="relation_id" >
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
                                    <input class="form-control" type="text" value="<?php echo $fam['email1'];?>" id="email1" name="email1" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Email 2:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['email2'];?>" id="email2" name="email2" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">NIT para Facturas:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['nit'];?>" id="nit" name="nit" onblur="aMayusculas(this.value,this.id)" required="required" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3 col-form-label"><span class="float-right">Nombre para Facturas:</span></label>
                                <div class="col-9">
                                    <input class="form-control" type="text" value="<?php echo $fam['nombre_factura'];?>" id="nombre_factura" name="nombre_factura" onblur="aMayusculas(this.value,this.id)" />
                                </div>
                            </div>
                        </div>
                    </div>
                <!--end::Card-->
                </form>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->