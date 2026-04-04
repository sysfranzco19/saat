<?php
	//$system_title       =	$this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;
	//$gestion	    =	$this->db->get_where('settings' , array('type'=>'gestion'))->row()->description;
?>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel"><?php echo $system_title; ?> - Eliminar Asistencia</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<?php //echo form_open(base_url() . 'index.php/teacher/date_edit/'.$param4, array('class' => 'form','name' => 'form_date')); ?>
<form action="<?php echo base_url() . 'index.php/teacher/assistance_del/'.$param1; ?>" method="post" name="form_assistance" class="form" >
<div class="modal-body">
	

	<div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Fecha de Asistencia:</label>
        <input type="hidden" id="subject_id" name="subject_id" value="<?php echo $param1; ?>">
        <div class="col-lg-9 col-xl-6">
            <div class="input-group input-group-solid date" id="kt_datetimepicker_3" data-target-input="nearest">
                <input type="date" id="fecha" name="fecha" step="1" min="01-01-<?php echo $system_title; ?>" max="31-12-<?php echo $system_title; ?>" value="<?php echo $param3; ?>" class="form-control" required>
            </div>
        </div>
        <input type="hidden" id="date_id" name="date_id" value="<?php echo $param2; ?>">
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-danger font-weight-bold">Eliminar</button>
</div>
</form> 
<!--end::Modal-->