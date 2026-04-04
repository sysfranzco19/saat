<?php
$StudentMod = new \App\Models\StudentModel();
$student_info = $StudentMod->get_student(['student_id' => $param1])[0];
?>

<div class="modal-header">
    <h5 class="modal-title">Editar Estudiante:
        <?php echo $student_info['name'] . ' ' . $student_info['lastname']; ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>

<form action="<?php echo base_url(); ?>index.php/secretary/student_update" method="POST">
    <input type="hidden" name="student_id" value="<?php echo $param1; ?>">
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $student_info['name']; ?>"
                        required />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Apellido Paterno:</label>
                    <input type="text" name="lastname" class="form-control"
                        value="<?php echo $student_info['lastname']; ?>" required />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Apellido Materno:</label>
                    <input type="text" name="lastname2" class="form-control"
                        value="<?php echo $student_info['lastname2']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>RUDE:</label>
                    <input type="text" name="rude" class="form-control" value="<?php echo $student_info['rude']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Fecha de Nacimiento:</label>
                    <input type="date" name="birthday" class="form-control"
                        value="<?php echo $student_info['birthday']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Género:</label>
                    <select name="sex" class="form-control">
                        <option value="M" <?php echo ($student_info['sex'] == 'M') ? 'selected' : ''; ?>>Masculino
                        </option>
                        <option value="F" <?php echo ($student_info['sex'] == 'F') ? 'selected' : ''; ?>>Femenino
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>C.I.:</label>
                    <input type="text" name="card" class="form-control" value="<?php echo $student_info['card']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Expira C.I.:</label>
                    <input type="date" name="expire_card" class="form-control"
                        value="<?php echo $student_info['expire_card']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Teléfono:</label>
                    <input type="text" name="phone" class="form-control"
                        value="<?php echo $student_info['phone']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Celular:</label>
                    <input type="text" name="cellphone" class="form-control"
                        value="<?php echo $student_info['cellphone']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Correo Personal:</label>
                    <input type="email" name="personal_email" class="form-control"
                        value="<?php echo $student_info['personal_email']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Correo Institucional:</label>
                    <input type="email" name="email" class="form-control"
                        value="<?php echo $student_info['email']; ?>" />
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" name="address" class="form-control"
                        value="<?php echo $student_info['address']; ?>" />
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Referencia:</label>
                    <input type="text" name="reference" class="form-control"
                        value="<?php echo $student_info['reference']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Colegio de Origen:</label>
                    <input type="text" name="origin_school" class="form-control"
                        value="<?php echo $student_info['origin_school']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Estado:</label>
                    <select name="activo" class="form-control">
                        <option value="1" <?php echo ($student_info['activo'] == 1) ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($student_info['activo'] == 0) ? 'selected' : ''; ?>>Inactivo
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
    </div>
</form>