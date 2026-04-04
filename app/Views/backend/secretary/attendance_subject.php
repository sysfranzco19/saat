<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container-fluid">
        <!--begin::Notice-->
        <div class="alert alert-custom alert-white alert-shadow fade show gutter-b" role="alert">
            <div class="alert-icon">
                <span class="svg-icon svg-icon-primary svg-icon-xl">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Compass.svg-->
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3" />
                            <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero" />
                        </g>
                    </svg>
                    <!--end::Svg Icon-->
                </span>
            </div>
            <div class="alert-text">Selecione un curso y revise las opciones de asistencia por Materia.</div>
        </div>
        <!--end::Notice-->
		<!--begin::Accordion-->
		<div class="accordion accordion-toggle-arrow" id="accordionExample1">
			<?php
			$c=0;
            foreach ($cursos as $paralelo) {
			?>
			<div class="card">
				<div class="card-header">
					<div class="card-title <?php if($c==0){echo 'collapsed';} ?> " data-toggle="collapse" data-target="#collapseOne<?php echo $paralelo['section_id']?>">Paralelo <?php echo $paralelo['nick_name']; ?></div>
				</div>
				<div id="collapseOne<?php echo $paralelo['section_id']?>" class="collapse <?php if($c==0){echo 'show';} ?>" data-parent="#accordionExample1">
					<div class="card-body">
                                                                


                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" >Materias</th>
                                    <th scope="col" >Registros</th>
                                    <th scope="col" >Reportes</th>
                                    <th scope="col" >Descargas</th>
                                </tr>
                            </thead>
                        <tbody>
                    <?php 
                        foreach($subjects as $subject){
                            if ($subject['section_id']==$paralelo['section_id']) {
                            ?>
                            <tr>
                                <th scope="row" ><?php echo $subject['materia'];?></th>
                                <td><a href="<?php echo base_url(); ?>index.php/teacher/attendance/<?php echo $subject['subject_id'];?>" class="btn btn-text-primary btn-hover-light-primary font-weight-bold mr-2">Tomar Asistencia</a>
                                </td>
                                <td>
                                    <a href="<?php echo base_url(); ?>index.php/teacher/attendance_report/<?php echo $subject['subject_id'];?>" class="btn btn-text-success btn-hover-light-success font-weight-bold mr-2"
                                        target="_blank" >Ver Asistencias</a>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Descargar Asistencias
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/assists_excel/<?php echo $subject['subject_id'];?>/<?php echo $paralelo['section_id'];?>/1">1er TRIM</a>
                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/assists_excel/<?php echo $subject['subject_id'];?>/<?php echo $paralelo['section_id'];?>/2">2do TRIM</a>
                                            <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/teacher/assists_excel/<?php echo $subject['subject_id'];?>/<?php echo $paralelo['section_id'];?>/3">3er TRIM</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php
                            }
                        }
                    ?>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        <?php
                $c+=1;
            }
        ?>                                       
		</div>
		<!--end::Accordion-->
	</div>
	<!--end::Container-->
</div>
<!--end::Entry-->
                    