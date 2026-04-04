
<!--begin::Modal
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel"> - Subir Archivo</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>

<form action="" method="post" enctype="multipart/form-data" >

<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
	<button type="submit" class="btn btn-primary font-weight-bold">Subir Archivo</button>
</div>
</form> 
end::Modal-->
<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel"><?php echo $system_title; ?> - Subir Archivo de <?php echo $param2; ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<?php //echo form_open(base_url() . 'index.php/teacher/upfile_letter/', array('class' => 'form','name' => 'form_date')); 
    $action = "";
    switch ($param4) {
        case '0':
            $action = base_url('index.php/teacher/upfile_letter/'.$param3);
            break;
        case 'pdcs':
            $action = base_url('index.php/teacher/upfile_pdcs/'.$param3);
            break;
        default:
            $action = base_url('index.php/teacher/upfile_letter/'.$param3);
            break;
    }
?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" >
<div class="modal-body">
    <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Trimestre:</label>
        <div class="col-lg-9 col-xl-6">
            <div class="input-group input-group-solid date" data-target-input="nearest">
                <input type="text" id="phase" name="phase" value="<?php echo $phase_name; ?>" class="form-control" disabled>
                <input type="hidden" id="phase_id" name="phase_id" value="<?php echo $phase_id;?>" class="form-control" disabled>
            </div>

        </div>
    </div>
	<div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Curso:</label>
        <div class="col-lg-9 col-xl-6">
            <div class="input-group input-group-solid date" data-target-input="nearest">
                <input type="text" id="curso" name="curso" value="<?php echo urldecode($param1); ?>" class="form-control" disabled>
                <input type="hidden" id="subject_id" name="subject_id" value="<?php echo $param3; ?>" disabled>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-xl-3 col-lg-3 col-form-label">Selecciona el Archivo:</label>
        <div class="col-lg-9 col-xl-6">
			<div class="custom-file">
				<input type="file" class="custom-file-input" id="file_id" name="userfile" accept=".pdf"/>
				<label class="custom-file-label" for="userfile">Archivo...</label>
			</div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-primary font-weight-bold">Subir Archivo</button>
</div>
</form> 
