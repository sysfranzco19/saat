<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
            	<div class="card-title">
                    <h3 class="card-label">Asistencia <?php echo $curso; ?>
                    <span class="d-block text-muted pt-2 font-size-sm">Formulario para tomar Asistencia</span></h3>
                </div>
                <?php //echo form_open(base_url() . 'index.php/teacher/attendance_date/' , array('class' => 'form','name' => 'form_date')); ?>
                <div class="card-toolbar">
                    <!--begin::Dropdown-->
                    <div class="dropdown dropdown-inline mr-2">
                        <input type="text" id="curso" name="curso"  value="<?php echo $curso; ?>" class="form-control" disabled>
                    </div>
                    <!--end::Dropdown-->
                    <!--begin::Dropdown-->
                    <div class="dropdown dropdown-inline mr-2">
                    	<?php
                    	if(isset($date_id)){ 
                    		?><input type="text" id="fecha" name="fecha" step="1" min="01-01-2023" max="31-12-2023" value="<?php echo $date; ?>" class="form-control" disabled><?php
                    	}else{
                    		?><input type="date" id="fecha" name="fecha" step="1" min="01-01-2023" max="31-12-2023" value="" class="form-control" required><?php
                    	}
                    	?>
                    </div>
                    <!--end::Dropdown-->
                    <?php
                    if(isset($date_id)){ 
                    ?>
                    <!--begin::Button-->
                    <a href="" onclick="guardar()" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#modal_teacher_edit">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Guardar Asistencia</a>
                    <!--end::Button-->
                    <?php
                    }else{
                    ?>
                    <!--begin::Button-->
                    <a href="" onclick="cargar()" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#modal_teacher_edit">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Tomar Asistencias</a>
                    <!--end::Button-->
                    <?php
                    }
                    ?>
                </div>
                <?php //echo "</form>"; ?>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--begin::Entry-->
