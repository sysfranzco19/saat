<?php
/**
 * Campos de formulario reutilizados por students_admin_modal_add.php y students_admin_modal_edit.php.
 * Espera en el scope: $s (array con los datos del estudiante, vacío para "Nuevo"), $secciones, $familias, $lugares.
 */
$val = function ($campo) use ($s) {
    return htmlspecialchars($s[$campo] ?? '');
};
?>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <label>Nombres:</label>
            <input type="text" name="name" class="form-control" value="<?php echo $val('name'); ?>" required>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label>Apellido Paterno:</label>
            <input type="text" name="lastname" class="form-control" value="<?php echo $val('lastname'); ?>" required>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label>Apellido Materno:</label>
            <input type="text" name="lastname2" class="form-control" value="<?php echo $val('lastname2'); ?>">
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label>Código:</label>
            <input type="text" name="code" class="form-control" value="<?php echo $val('code'); ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Roll:</label>
            <input type="number" name="roll" class="form-control" value="<?php echo $val('roll'); ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Género:</label>
            <select name="sex" class="form-control">
                <option value="M" <?php echo ($s['sex'] ?? '') == 'M' ? 'selected' : ''; ?>>Masculino</option>
                <option value="F" <?php echo ($s['sex'] ?? '') == 'F' ? 'selected' : ''; ?>>Femenino</option>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Fecha de Nacimiento:</label>
            <input type="date" name="birthday" class="form-control" value="<?php echo $val('birthday'); ?>">
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label>C.I.:</label>
            <input type="text" name="card" class="form-control" value="<?php echo $val('card'); ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Lugar Expedición C.I.:</label>
            <select name="place_card" class="form-control">
                <option value="">Seleccione</option>
                <?php foreach ($lugares as $l): ?>
                    <option value="<?php echo $l['place_id']; ?>" <?php echo ($s['place_card'] ?? '') == $l['place_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($l['place']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Expira C.I.:</label>
            <input type="date" name="expire_card" class="form-control" value="<?php echo $val('expire_card'); ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Lugar de Nacimiento:</label>
            <select name="place_birth" class="form-control">
                <option value="">Seleccione</option>
                <?php foreach ($lugares as $l): ?>
                    <option value="<?php echo $l['place_id']; ?>" <?php echo ($s['place_birth'] ?? '') == $l['place_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($l['place']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label>RUDE:</label>
            <input type="text" name="rude" class="form-control" value="<?php echo $val('rude'); ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Teléfono:</label>
            <input type="text" name="phone" class="form-control" value="<?php echo $val('phone'); ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Celular:</label>
            <input type="text" name="cellphone" class="form-control" value="<?php echo $val('cellphone'); ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Colegio de Origen:</label>
            <input type="text" name="origin_school" class="form-control" value="<?php echo $val('origin_school'); ?>">
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <label>Correo Personal:</label>
            <input type="email" name="personal_email" class="form-control" value="<?php echo $val('personal_email'); ?>">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Correo Institucional:</label>
            <input type="email" name="email" class="form-control" value="<?php echo $val('email'); ?>">
        </div>
    </div>

    <div class="col-lg-6">
        <div class="form-group">
            <label>Dirección:</label>
            <input type="text" name="address" class="form-control" value="<?php echo $val('address'); ?>">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Referencia:</label>
            <input type="text" name="reference" class="form-control" value="<?php echo $val('reference'); ?>">
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label>Curso:</label>
            <select name="section_id" class="form-control" required>
                <option value="">Seleccione</option>
                <?php foreach ($secciones as $sec): ?>
                    <option value="<?php echo $sec->section_id; ?>" <?php echo ($s['section_id'] ?? '') == $sec->section_id ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($sec->completo); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Familia:</label>
            <select name="family_id" class="form-control">
                <option value="0">Sin asignar</option>
                <?php foreach ($familias as $f): ?>
                    <option value="<?php echo $f->family_id; ?>" <?php echo ($s['family_id'] ?? '') == $f->family_id ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($f->family); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Matrícula:</label>
            <input type="number" name="matricula" class="form-control" value="<?php echo $val('matricula'); ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>NIT:</label>
            <input type="number" name="nit_id" class="form-control" value="<?php echo $val('nit_id') ?: '0'; ?>">
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label>Fecha de Registro:</label>
            <input type="date" name="registration_date" class="form-control" value="<?php echo $val('registration_date'); ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label>Fecha de Retiro:</label>
            <input type="date" name="retirement_date" class="form-control" value="<?php echo $val('retirement_date'); ?>">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label>Estado:</label>
            <select name="activo" class="form-control">
                <option value="1" <?php echo ($s['activo'] ?? 1) == 1 ? 'selected' : ''; ?>>Activo</option>
                <option value="0" <?php echo ($s['activo'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label>Activo Admin.:</label>
            <select name="activo_administracion" class="form-control">
                <option value="1" <?php echo ($s['activo_administracion'] ?? 1) == 1 ? 'selected' : ''; ?>>Sí</option>
                <option value="0" <?php echo ($s['activo_administracion'] ?? 1) == 0 ? 'selected' : ''; ?>>No</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label>DDJJ:</label>
            <select name="ddjj" class="form-control">
                <option value="1" <?php echo ($s['ddjj'] ?? 0) == 1 ? 'selected' : ''; ?>>Sí</option>
                <option value="0" <?php echo ($s['ddjj'] ?? 0) == 0 ? 'selected' : ''; ?>>No</option>
            </select>
        </div>
    </div>
</div>
