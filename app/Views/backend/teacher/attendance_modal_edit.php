<?php
	//$system_title       =	$this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;
?>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Modificar asistencia de : <?php echo urldecode($param5); ?></h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<?php //echo form_open(base_url() . 'teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form action="<?php echo base_url() . 'teacher/assistance_edit/'.$param1; ?>" method="post" name="form_assistance" class="form" >
<div class="modal-body">
	

	<div class="form-group row">
        <input type="hidden" id="subject_id" name="subject_id" value="<?php echo $param1; ?>">
        <input type="hidden" id="assistance_subject_id" name="assistance_subject_id" value="<?php echo $param2; ?>">
		        <label class="col-xl-3 col-lg-3 col-form-label">Estado de Asistencia</label>
		        <div class="col-9 col-form-label">
		            <div class="checkbox-inline">
		                <label class="checkbox checkbox-success">
		                    <input type="radio" name="chk_<?php echo $param2; ?>" <?php if ($param3==1) {echo "checked"; }?> value="1" />
		                    <span></span>
		                    Presente
		                </label>
						<label class="checkbox checkbox-info">
		                    <input type="radio" name="chk_<?php echo $param2; ?>" <?php if ($param3==4) {echo "checked"; }?> value="4" />
		                    <span></span>
		                    Virtual
		                </label>
		                <label class="checkbox checkbox-warning">
		                    <input type="radio" name="chk_<?php echo $param2; ?>" <?php if ($param3==3) {echo "checked"; }?> value="3" />
		                    <span></span>
		                    Retraso
		                </label>
		                <label class="checkbox checkbox-danger">
		                    <input type="radio" name="chk_<?php echo $param2; ?>" <?php if ($param3==0) {echo "checked"; }?> value="0" />
		                    <span></span>
		                    Ausente
		                </label>
		                <label class="checkbox checkbox-primary">
		                    <input type="radio" name="chk_<?php echo $param2; ?>" <?php if ($param3==2) {echo "checked"; }?> value="2" />
		                    <span></span>
		                    Licencia
		                </label>
		            </div>
		        </div>
    </div>
    <div class="form-group row">
    	<label class="col-xl-3 col-lg-3 col-form-label">Observación</label>
    	<div class="col-9 col-form-label">
        <textarea class="form-control" name="obs" rows="2" spellcheck="false" data-gramm="false" ></textarea>
        </div>
    </div>
	
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Guardar Cambios</button>
</div>
</form> 
<!--end::Modal-->