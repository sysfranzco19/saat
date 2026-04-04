<?php
$FamilyMod = new \App\Models\FamilyModel();
$family_info = $FamilyMod->get_family(['family_id' => $param1])[0];

// Get relations for the dropdown
$db = \Config\Database::connect();
$relations = $db->table('relation_parent')->get()->getResultArray();
?>

<div class="modal-header">
    <h5 class="modal-title">Editar Familia:
        <?php echo $family_info['lastname1'] . ' ' . $family_info['lastname2']; ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>

<form action="<?php echo base_url(); ?>index.php/secretary/family_update" method="POST">
    <input type="hidden" name="family_id" value="<?php echo $param1; ?>">
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Apellido Paterno:</label>
                    <input type="text" name="lastname1" class="form-control"
                        value="<?php echo $family_info['lastname1']; ?>" required />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Apellido Materno:</label>
                    <input type="text" name="lastname2" class="form-control"
                        value="<?php echo $family_info['lastname2']; ?>" required />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Correo Principal:</label>
                    <input type="email" name="email1" class="form-control" value="<?php echo $family_info['email1']; ?>"
                        required />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Correo Secundario:</label>
                    <input type="email" name="email2" class="form-control"
                        value="<?php echo $family_info['email2']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Teléfono Domicilio:</label>
                    <input type="text" name="home_phone" class="form-control"
                        value="<?php echo $family_info['home_phone']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Celular de Contacto:</label>
                    <input type="text" name="contact_cell" class="form-control"
                        value="<?php echo $family_info['contact_cell']; ?>" />
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label>Dirección:</label>
                    <input type="text" name="home_address" class="form-control"
                        value="<?php echo $family_info['home_address']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Barrio/Zona:</label>
                    <input type="text" name="neighborhood" class="form-control"
                        value="<?php echo $family_info['neighborhood']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Referencia:</label>
                    <input type="text" name="reference" class="form-control"
                        value="<?php echo $family_info['reference']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Nombre Factura:</label>
                    <input type="text" name="nombre_factura" class="form-control"
                        value="<?php echo $family_info['nombre_factura']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>NIT:</label>
                    <input type="text" name="nit" class="form-control" value="<?php echo $family_info['nit']; ?>" />
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Relación:</label>
                    <select name="relation_id" class="form-control">
                        <?php foreach ($relations as $rel): ?>
                            <option value="<?php echo $rel['relation_parent_id']; ?>" <?php echo ($family_info['relation_id'] == $rel['relation_parent_id']) ? 'selected' : ''; ?>>
                                <?php echo $rel['relation']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>Estado:</label>
                    <select name="status" class="form-control">
                        <option value="1" <?php echo ($family_info['status'] == 1) ? 'selected' : ''; ?>>Activo</option>
                        <option value="0" <?php echo ($family_info['status'] == 0) ? 'selected' : ''; ?>>Inactivo
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