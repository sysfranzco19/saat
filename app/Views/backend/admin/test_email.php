<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">Prueba de Envio de email</h3>
                    </div>
                    <!--begin::Form-->
                    <form class="form" method="post" action="<?php echo base_url(); ?>index.php/admin/send_mail_smtp">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Input</label>
                                <input type="email" class="form-control form-control-solid" name="correo" id="correo" value="franz.condori.calderon@gmail.com" placeholder="" />
                            </div>
                            <div class="form-group">
                                <label>Asunto</label>
                                <input type="text" class="form-control form-control-solid" name="asunto" id="asunto" value="Mensaje de Prueba">
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea">Mensaje</label>
                                <textarea class="form-control form-control-solid" name="mensaje" id="mensaje" rows="3">Mensaje de prueba de envios de gmail</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                            <a href="<?php echo base_url(); ?>index.php/admin/copiarTStudent">copiarTStudent</a>
                        </div>
                    </form>
                    
                    <!--end::Form-->
                </div>
                <!--end::Card-->
                <?php
                if (isset($errores)) {
                    echo $errores;
                }
                ?>
            </div>
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">Prueba de Envio de email po PHP</h3>
                    </div>
                    <!--begin::Form-->
                    <form class="form" method="post" action="<?php echo base_url(); ?>index.php/admin/send_mail_php">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Input</label>
                                <input type="email" class="form-control form-control-solid" name="correo" id="correo" value="franz.condori.calderon@gmail.com" placeholder="" />
                            </div>
                            <div class="form-group">
                                <label>Asunto</label>
                                <input type="text" class="form-control form-control-solid" name="asunto" id="asunto" value="Mensaje de Prueba">
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea">Mensaje</label>
                                <textarea class="form-control form-control-solid" name="mensaje" id="mensaje" rows="3">Mensaje de prueba de envios de gmail</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                    
                    <!--end::Form-->
                </div>
                <!--end::Card-->
                <?php
                if (isset($errores)) {
                    echo $errores;
                }
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">ALERTA NRO 3</h3>
                    </div>
                    <!--begin::Form-->
                    <form class="form" method="post" action="<?php echo base_url(); ?>index.php/admin/send_alerta3_php">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Input</label>
                                <input type="email" class="form-control form-control-solid" name="correo" id="correo" value="franz.condori.calderon@gmail.com" placeholder="" />
                            </div>
                            <div class="form-group">
                                <label>notificacion_id</label>
                                <input type="text" class="form-control form-control-solid" name="notificacion_id" id="notificacion_id" value="1">
                            </div>
                            <div class="form-group">
                                <label for="student">Estudiante</label>
                                <input type="text" class="form-control form-control-solid" name="student" id="student" value="JUAN PEREZ PEREZ">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">Enviar prueba</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                    
                    <!--end::Form-->
                </div>
                <!--end::Card-->
                <?php
                if (isset($errores)) {
                    echo $errores;
                }
                ?>
            </div>
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">ALERTA NRO 4</h3>
                    </div>
                    <!--begin::Form-->
                    <form class="form" method="post" action="<?php echo base_url(); ?>index.php/admin/send_alerta4_php">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Input</label>
                                <input type="email" class="form-control form-control-solid" name="correo" id="correo" value="franz.condori.calderon@gmail.com" placeholder="" />
                            </div>
                            <div class="form-group">
                                <label>Asunto</label>
                                <input type="text" class="form-control form-control-solid" name="asunto" id="asunto" value="Mensaje de Prueba">
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea">Mensaje</label>
                                <textarea class="form-control form-control-solid" name="mensaje" id="mensaje" rows="3">Mensaje de prueba de envios de gmail</textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                    
                    <!--end::Form-->
                </div>
                <!--end::Card-->
                <?php
                if (isset($errores)) {
                    echo $errores;
                }
                ?>
            </div>
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->