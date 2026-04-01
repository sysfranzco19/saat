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
                            <h3 class="card-title">Kardex de Estudiante</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">
                                    <select name="student" id="student" class="form-control" onChange="cargar(this.value);">
                                        <option value="" <?php if ($student_id == 0) {
                                            echo 'selected';
                                        } ?>>Seleccione un Estudiante</option>
                                        <?php foreach ($students as $key): ?>
                                            <option value="<?php echo $key->student_id; ?>" <?php if ($key->student_id == $student_id) {
                                                   echo 'selected';
                                               } ?>>
                                            <?php echo $key->nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if ($student_id > 0): ?>
                                        <button type="button" class="btn btn-primary font-weight-bolder btn-sm ml-2" 
                                            onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/modal_student_edit/<?php echo $student_id; ?>');">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <a href="<?php echo base_url(); ?>secretary/kardex_student_pdf/<?php echo $student_id; ?>"
                                            target="_blank" class="btn btn-danger font-weight-bolder btn-sm ml-2">
                                            <i class="far fa-file-pdf"></i> PDF
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($student_info): ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="mb-5 font-weight-bold text-dark">Información Personal</h4>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Nombre Completo:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['name'] . ' ' . $student_info['lastname'] . ' ' . $student_info['lastname2']; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Código:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['code']; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">RUDE:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['rude']; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Fecha de Nacimiento:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['birthday']; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Género:</label>
                                        <div class="font-weight-bolder"><?php echo ($student_info['sex'] == 'M') ? 'Masculino' : 'Femenino'; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">C.I.:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['card']; ?> <?php echo $student_info['expire_card'] ? '(Expira: '.$student_info['expire_card'].')' : ''; ?></div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-5"></div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="mb-5 font-weight-bold text-dark">Contacto y Ubicación</h4>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Teléfono:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['phone'] ?: 'N/A'; ?> / <?php echo $student_info['cellphone'] ?: 'N/A'; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Correo Personal:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['personal_email'] ?: 'N/A'; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Correo Institucional:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['email'] ?: 'N/A'; ?></div>
                                    </div>
                                    <div class="col-lg-8 mb-5">
                                        <label class="text-muted font-weight-bold">Dirección:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['address'] ?: 'N/A'; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Referencia:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['reference'] ?: 'N/A'; ?></div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-5"></div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="mb-5 font-weight-bold text-dark">Información Académica</h4>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Colegio de Origen:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['origin_school'] ?: 'N/A'; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Fecha de Registro:</label>
                                        <div class="font-weight-bolder"><?php echo $student_info['registration_date'] ?: 'N/A'; ?></div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Estado:</label>
                                        <div class="font-weight-bolder">
                                            <?php if ($student_info['activo']): ?>
                                                <span class="label label-inline label-light-success font-weight-bold">Activo</span>
                                            <?php else: ?>
                                                <span class="label label-inline label-light-danger font-weight-bold">Inactivo</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-10">
                                    <p class="text-muted">Por favor, seleccione un estudiante para ver su información detallada.</p>
                                </div>
                            <?php endif; ?>
                        </div>



                    </div>

                </div>

            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

<script type="text/javascript">
    function cargar(student_id) {
        if (student_id != "") {
            window.location.replace("<?php echo base_url(); ?>secretary/kardex_student/" + student_id);
        }
    }
</script>