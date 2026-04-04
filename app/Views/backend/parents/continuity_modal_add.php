<?php
	//$system_title       =	$this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;
?>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel">Registrar Consulta de Continuidad <?php echo $param3;?></h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<?php //echo form_open(base_url() . 'index.php/teacher/assistance_edit/'.$param4, array('class' => 'form','name' => 'form_assistance')); ?>
<form action="<?php echo base_url().'parents/continuity_save'; ?>" method="post" class="form-horizontal" >
<input type="hidden" id="student_id" name="student_id" value="<?php echo $param1;?>" >
<input type="hidden" id="gestion" name="gestion" value="<?php echo $param3;?>" >
<div class="modal-body">
    <div class="form-group">
    	<label>¿Tiene intención de que su hij@: <?php echo $param2; ?> continúen en el colegio la próxima gestión escolar <?php echo $param3;?>?</label>
    	<div class="checkbox-inline">
                <label class="checkbox checkbox-success">
                    <input type="radio" name="continuidad" value="SI" checked="checked" />
                    <span></span>
                    SI
                </label>
                <label class="checkbox checkbox-success">
                    <input type="radio" name="continuidad" value="NO"/>
                    <span></span>
                    NO
                </label>
                <label class="checkbox checkbox-success">
                    <input type="radio" name="continuidad" value="INDECISO"/>
                    <span></span>
                    INDECISO
                </label>
            </div>
        </div>
    </div>    
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-success font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-success font-weight-bold">Guardar</button>
</div>
</form> 
<!--end::Modal-->