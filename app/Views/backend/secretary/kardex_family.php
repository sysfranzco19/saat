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
                                    <select name="family" id="family" class="form-control"
                                        onChange="cargar(this.value);">
                                        <option value="" <?php if ($family_id == 0) {
                                            echo 'selected';
                                        } ?>>Seleccione una
                                            Familia</option>
                                        <?php foreach ($familias as $key): ?>
                                            <option value="<?php echo $key->family_id; ?>" <?php if ($key->family_id == $family_id) {
                                                   echo 'selected';
                                               } ?>>
                                         <?php echo $key->family; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if ($family_id != 0): ?>
                                        <button type="button" class="btn btn-primary font-weight-bolder btn-sm ml-2" 
                                            onclick="showAjaxModal('<?php echo base_url(); ?>index.php/modal/popup/modal_family_edit/<?php echo $family_id; ?>');">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <a href="<?php echo base_url(); ?>index.php/secretary/kardex_family_pdf/<?php echo $family_id; ?>"
                                            target="_blank" class="btn btn-danger font-weight-bolder btn-sm ml-2">
                                            <i class="far fa-file-pdf"></i> PDF
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if ($family_id != 0): ?>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="mb-5 font-weight-bold text-dark">Información de la Familia</h4>
                                    </div>
                                    <div class="col-lg-6 mb-5">
                                        <label class="text-muted font-weight-bold">Apellidos:</label>
                                        <div class="font-weight-bolder">
                                            <?php echo $familia['lastname1'] . ' ' . $familia['lastname2']; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 mb-5">
                                        <label class="text-muted font-weight-bold">Relación:</label>
                                        <div class="font-weight-bolder text-primary">
                                            <?php echo $familia['relation'] ?: 'N/A'; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 mb-5">
                                        <label class="text-muted font-weight-bold">Estado:</label>
                                        <div class="font-weight-bolder">
                                            <?php if ($familia['status'] == 1): ?>
                                                <span
                                                    class="label label-inline label-light-success font-weight-bold">Activo</span>
                                            <?php else: ?>
                                                <span
                                                    class="label label-inline label-light-danger font-weight-bold">Inactivo</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="mb-5 font-weight-bold text-dark">Contacto y Ubicación</h4>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Correo Principal:</label>
                                        <div class="font-weight-bolder text-lowercase"><?php echo $familia['email1']; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Correo Secundario:</label>
                                        <div class="font-weight-bolder text-lowercase">
                                            <?php echo $familia['email2'] ?: 'N/A'; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Teléfonos:</label>
                                        <div class="font-weight-bolder"><?php echo $familia['home_phone'] ?: 'N/A'; ?> /
                                            <?php echo $familia['contact_cell'] ?: 'N/A'; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 mb-5">
                                        <label class="text-muted font-weight-bold">Dirección de Domicilio:</label>
                                        <div class="font-weight-bolder"><?php echo $familia['home_address'] ?: 'N/A'; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 mb-5">
                                        <label class="text-muted font-weight-bold">Barrio/Zona:</label>
                                        <div class="font-weight-bolder"><?php echo $familia['neighborhood'] ?: 'N/A'; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-5">
                                        <label class="text-muted font-weight-bold">Referencia:</label>
                                        <div class="font-weight-bolder"><?php echo $familia['reference'] ?: 'N/A'; ?></div>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="mb-5 font-weight-bold text-dark">Datos de los Padres</h4>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-vertical-center">
                                                <thead>
                                                    <tr class="text-left text-muted text-uppercase">
                                                        <th class="pl-0" style="min-width: 150px">Nombre</th>
                                                        <th style="min-width: 100px">Parentesco</th>
                                                        <th style="min-width: 120px">Profesión</th>
                                                        <th style="min-width: 100px">Celular</th>
                                                        <th style="min-width: 150px">Email</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($parents as $p): ?>
                                                        <tr>
                                                            <td class="pl-0">
                                                                <span
                                                                    class="text-dark-75 font-weight-bolder d-block font-size-lg"><?php echo $p['nombre']; ?></span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="text-primary font-weight-bold"><?php echo $p['relationship']; ?></span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="text-muted font-weight-bold"><?php echo $p['profession'] ?: 'N/A'; ?></span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="text-muted font-weight-bold"><?php echo $p['cellphone'] ?: 'N/A'; ?></span>
                                                            </td>
                                                            <td class="text-lowercase">
                                                                <span
                                                                    class="text-muted font-weight-bold"><?php echo $p['email'] ?: 'N/A'; ?></span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="mb-5 font-weight-bold text-dark">Información de Facturación</h4>
                                    </div>
                                    <div class="col-lg-6 mb-5">
                                        <label class="text-muted font-weight-bold">Nombre Factura:</label>
                                        <div class="font-weight-bolder"><?php echo $familia['nombre_factura'] ?: 'N/A'; ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-5">
                                        <label class="text-muted font-weight-bold">NIT:</label>
                                        <div class="font-weight-bolder"><?php echo $familia['nit'] ?: 'N/A'; ?></div>
                                    </div>
                                </div>

                                <div class="separator separator-dashed my-5"></div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="mb-5 font-weight-bold text-dark">Estudiantes Registrados</h4>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-head-custom table-vertical-center"
                                                id="kt_advance_table_widget_1">
                                                <thead>
                                                    <tr class="text-left text-uppercase">
                                                        <th style="min-width: 150px">Estudiante</th>
                                                        <th style="min-width: 100px">Código</th>
                                                        <th style="min-width: 100px">Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($students as $student): ?>
                                                        <tr>
                                                            <td class="pl-0">
                                                                <a href="<?php echo base_url(); ?>index.php/secretary/kardex_student/<?php echo $student->student_id; ?>"
                                                                    class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                                                    <?php echo $student->lastname . ' ' . $student->lastname2 . ' ' . $student->name; ?>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="text-muted font-weight-bold"><?php echo $student->code; ?></span>
                                                            </td>
                                                            <td>
                                                                <?php if ($student->activo == 1): ?>
                                                                    <span
                                                                        class="label label-lg label-light-success label-inline font-weight-bold">Activo</span>
                                                                <?php else: ?>
                                                                    <span
                                                                        class="label label-lg label-light-danger label-inline font-weight-bold">Inactivo</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-10">
                                    <p class="text-muted">Por favor, seleccione una familia para ver su información
                                        detallada.</p>
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
    function cargar(family_id) {
        if (family_id != "") {
            window.location.replace("<?php echo base_url(); ?>index.php/secretary/kardex_family/" + family_id);
        }
    }
</script>