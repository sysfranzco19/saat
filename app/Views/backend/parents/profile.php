<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!-- Header del perfil -->
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-circle symbol-light-primary mr-5">
                            <span class="symbol-label font-size-h2 font-weight-bold">
                                <?= strtoupper(substr($parent['name'] ?? 'P', 0, 1)) ?>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-dark font-weight-bold font-size-h4 mb-0">
                                <?= htmlspecialchars(($parent['lastname1'] ?? '') . ' ' . ($parent['lastname2'] ?? '') . ' ' . ($parent['name'] ?? '')) ?>
                            </span>
                            <span class="text-muted font-weight-bold">
                                Padre / Madre &nbsp;|&nbsp; <?= htmlspecialchars($parent['email'] ?? '') ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Información Personal -->
            <div class="col-lg-8">
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder text-dark">Información Personal</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Datos de contacto y detalles personales</span>
                        </h3>
                    </div>
                    <form action="<?= base_url('parents/profile_update') ?>" method="POST">
                        <input type="hidden" name="parent_id" value="<?= $parent['parent_id'] ?? '' ?>">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input type="text" name="name" class="form-control"
                                            value="<?= htmlspecialchars($parent['name'] ?? '') ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Primer Apellido</label>
                                        <input type="text" name="lastname1" class="form-control"
                                            value="<?= htmlspecialchars($parent['lastname1'] ?? '') ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Segundo Apellido</label>
                                        <input type="text" name="lastname2" class="form-control"
                                            value="<?= htmlspecialchars($parent['lastname2'] ?? '') ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha de Nacimiento</label>
                                        <input type="date" name="birthday" class="form-control"
                                            value="<?= htmlspecialchars($parent['birthday'] ?? '') ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Lugar de Nacimiento</label>
                                        <select name="place_birth" class="form-control">
                                            <option value="">-- Seleccionar --</option>
                                            <?php foreach ($places as $pl): ?>
                                                <option value="<?= $pl['place_id'] ?>"
                                                    <?= (isset($parent['place_birth']) && $parent['place_birth'] == $pl['place_id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($pl['place']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>C.I.</label>
                                        <input type="text" name="card" class="form-control"
                                            value="<?= htmlspecialchars($parent['card'] ?? '') ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Teléfono</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="<?= htmlspecialchars($parent['phone'] ?? '') ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Celular</label>
                                        <input type="text" name="cellphone" class="form-control"
                                            value="<?= htmlspecialchars($parent['cellphone'] ?? '') ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tel. Trabajo</label>
                                        <input type="text" name="workphone" class="form-control"
                                            value="<?= htmlspecialchars($parent['workphone'] ?? '') ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Institucional</label>
                                        <input type="email" name="email" class="form-control"
                                            value="<?= htmlspecialchars($parent['email'] ?? '') ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email Personal</label>
                                        <input type="email" name="personal_email" class="form-control"
                                            value="<?= htmlspecialchars($parent['personal_email'] ?? '') ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Profesión</label>
                                        <input type="text" name="profession" class="form-control"
                                            value="<?= htmlspecialchars($parent['profession'] ?? '') ?>" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ocupación</label>
                                        <input type="text" name="occupation" class="form-control"
                                            value="<?= htmlspecialchars($parent['occupation'] ?? '') ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Empresa / Negocio</label>
                                        <input type="text" name="business" class="form-control"
                                            value="<?= htmlspecialchars($parent['business'] ?? '') ?>" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Idioma</label>
                                        <input type="text" name="idiom" class="form-control"
                                            value="<?= htmlspecialchars($parent['idiom'] ?? '') ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Dirección</label>
                                <textarea name="address" class="form-control" rows="2"><?= htmlspecialchars($parent['address'] ?? '') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Referencia de Dirección</label>
                                <textarea name="reference" class="form-control" rows="2"><?= htmlspecialchars($parent['reference'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="col-lg-4">
                <div class="card card-custom gutter-b">
                    <div class="card-header border-0 py-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bolder text-dark">Cambiar Contraseña</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Usa una contraseña segura</span>
                        </h3>
                    </div>
                    <form action="<?= base_url('parents/password_update') ?>" method="POST" id="password_form">
                        <input type="hidden" name="parent_id" value="<?= $parent['parent_id'] ?? '' ?>">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Contraseña Actual</label>
                                <input type="password" name="old_password" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Nueva Contraseña</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label>Confirmar Nueva Contraseña</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required />
                                <span class="form-text text-danger d-none" id="password_error">Las contraseñas no coinciden</span>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning font-weight-bold">Actualizar Contraseña</button>
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
