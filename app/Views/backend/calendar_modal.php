<?php
	//$system_title       =	$this->db->get_where('settings' , array('type'=>'system_title'))->row()->description;
	//$gestion	    =	$this->db->get_where('settings' , array('type'=>'gestion'))->row()->description;
?>
<!--begin::Modal-->
<div class="modal-header">
	<h5 class="modal-title" id="exampleModalLabel"><?php echo $system_title; ?> - Calendario</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<i aria-hidden="true" class="ki ki-close"></i>
	</button>
</div>
<div class="modal-body">
    <div style="width: 100%; text-align: center;">
        <div style="position: relative; padding-bottom: 73.75%; padding-top: 0; height: 0;"><iframe title="CalendarioMesaCT-ImprentaO copia (2)" frameborder="0" width="718.6666666666666" height="530" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" src="https://view.genial.ly/65d38c793004f10014ba43f2" type="text/html" allowscriptaccess="always" allowfullscreen="true" scrolling="yes" allownetworking="all"></iframe>
        </div>
    </div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Salir</button>
</div>
<!--end::Modal-->