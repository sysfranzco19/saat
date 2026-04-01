<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!-- Subheader -->
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-circle symbol-light-primary mr-5">
                            <span class="symbol-label font-size-h2 font-weight-bold">
                                <?= substr($secretary['name'], 0, 1) ?>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="#" class="text-dark font-weight-bold text-hover-primary font-size-h4 mb-0">
                                <?= $secretary['name'] ?>
                            </a>
                            <span class="text-muted font-weight-bold">
                                Secretaria |
                                <?= $secretary['email'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Personal Information -->
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder text-dark">Información Personal</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Actualiza tus datos de contacto
                                y detalles personales</span>
                        </h3>
                    </div>
                    <form action="<?= base_url('secretary/profile_update') ?>" method="POST">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre Completo</label>
                                        <input type="text" name="name" class="form-control"
                                            value="<?= $secretary['name'] ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fecha de Nacimiento</label>
                                        <input type="date" name="birthday" class="form-control"
                                            value="<?= $secretary['birthday'] ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Institucional</label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?= $secretary['email'] ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Personal</label>
                                        <input type="email" name="personal_email" class="form-control"
                                            value="<?= $secretary['personal_email'] ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Teléfono</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="<?= $secretary['phone'] ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Celular</label>
                                        <input type="text" name="cellphone" class="form-control"
                                            value="<?= $secretary['cellphone'] ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Religión</label>
                                        <input type="text" name="religion" class="form-control"
                                            value="<?= $secretary['religion'] ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <textarea name="address" class="form-control"
                                    rows="2"><?= $secretary['address'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Referencia Dirección</label>
                                <textarea name="reference" class="form-control"
                                    rows="2"><?= $secretary['reference'] ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Change Password -->
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder text-dark">Cambiar Contraseña</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Asegúrate de usar una contraseña
                                segura</span>
                        </h3>
                    </div>
                    <form action="<?= base_url('secretary/password_update') ?>" method="POST" id="password_form">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Contraseña Actual</label>
                                <input type="password" name="old_password" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Nueva Contraseña</label>
                                <input type="password" name="new_password" id="new_password" class="form-control"
                                    required />
                            </div>
                            <div class="form-group">
                                <label>Confirmar nueva contraseña</label>
                                <input type="password" name="confirm_password" id="confirm_password"
                                    class="form-control" required />
                                <span class="form-text text-danger d-none" id="password_error">Las contraseñas no
                                    coinciden</span>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning font-weight-bold">Actualizar
                                Contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('password_form').addEventListener('submit', function (e) {
        const newPass = document.getElementById('new_password').value;
        const confirmPass = document.getElementById('confirm_password').value;
        const errorSpan = document.getElementById('password_error');

        if (newPass !== confirmPass) {
            e.preventDefault();
            errorSpan.classList.remove('d-none');
        } else {
            errorSpan.classList.add('d-none');
        }
    });
</script>