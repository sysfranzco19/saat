<script type="text/javascript">
    function imprim1(imp1){
        var printContents = document.getElementById('imp1').innerHTML;
        w = window.open();
        w.document.write(printContents);
        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10
        w.print();
        w.close();
        //return true;
    }
</script>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
            	<div class="card-title">
                    <h3 class="card-label">Notas de : <?php echo $completo; ?>
                    <span class="d-block text-muted pt-2 font-size-sm">El reporte muestra todas las notas del curso</span></h3>
                </div>
                <div class="card-toolbar">
                	<!--begin::Dropdown-->
                                            <div class="dropdown dropdown-inline mr-2">
                                                <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="svg-icon svg-icon-md">
                                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
                                                            <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
                                                        </g>
                                                    </svg>
                                                    <!--end::Svg Icon-->
                                                </span>Acción</button>
                                                <!--begin::Dropdown Menu-->
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                    <!--begin::Navigation-->
                                                    <ul class="navi flex-column navi-hover py-2">
                                                        <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Seleccione una opción:</li>
                                                        <li class="navi-item">
                                                            <a href="javascript:imprim1(imp1);" class="navi-link">
                                                                <span class="navi-icon">
                                                                    <i class="la la-print"></i>
                                                                </span>
                                                                <span class="navi-text">Imprimir</span>
                                                            </a>
                                                        </li>
                                                        <li class="navi-item">
                                                            <a href="<?php echo base_url(); ?>index.php/teacher/notes_excel/<?php echo $section_id;?>" class="navi-link">
                                                                <span class="navi-icon">
                                                                    <i class="la la-file-excel-o"></i>
                                                                </span>
                                                                <span class="navi-text">Descargar Excel</span>
                                                            </a>
                                                        </li>
                                                        <li class="navi-item">
                                                            <a href="<?php echo base_url(); ?>index.php/teacher/notes_pdf/<?php echo $section_id;?>" class="navi-link" target="_blank">
                                                                <span class="navi-icon">
                                                                    <i class="la la-file-pdf-o"></i>
                                                                </span>
                                                                <span class="navi-text">Descargar PDF</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <!--end::Navigation-->
                                                </div>
                                                <!--end::Dropdown Menu-->
                                            </div>
                                            <!--end::Dropdown-->
                    
                </div>
                <div class="card-body" id="imp1" >

                    <!--begin: Datatable-->
                    <table class="table">
                        <thead class="thead-inverse">
                            <tr>
                    			<th>Nombre</th>
                                <?php
                                foreach($subjects as $mat):
                                ?>
                    			<th><?php echo $mat['name'];?></th>
                                <?php 
                                endforeach;
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($students as $row):
                        	?>
                                <tr>
                                    <td><?php echo $row['name'];?></td>
                                    <?php
                                //$csl1 = "SELECT * FROM attendance_dates WHERE phase_id=".$phase_id." ORDER BY date_class LIMIT 7";
                                //$dias = $this->db->query($csl1)->result_array();
                                foreach($subjects as $mat):
                                    //$newDate = date("d M", strtotime($dia['date_class']));
                                    //$csl3 = "SELECT * FROM csamarks WHERE subject_id=".$mat['subject_id']." AND student_id=".$row['student_id'];
                                    //$notas = $this->db->query($csl3)->result_array();
                                    
                                ?>
                                <td><?php 
                                //$popover="";
                                
                                    foreach($notas as $not):
                                        if ($not['student_id']==$row['student_id'] && $not['subject_id']==$mat['subject_id']) {
                                            
                                            $popover="";
                                            $popover.="Docente : ".$mat['docente']."<br />";
                                            $popover.="Ser : ".$not['ser_average']."<br />";
                                            $popover.="Saber : ".$not['saber_average']."<br />";
                                            $popover.="Hacer : ".$not['hacer_average']."<br />";
                                            $popover.="Autoevaluación : ".$not['autoevaluacion']."<br />";
                                            if ($not['total_average']<51) {
                                                
                                                ?>
                                                <button type="button" class="btn btn-danger  btn-sm" data-container="body" data-toggle="popover" data-html="true" data-placement="top" 
                                                title="Notas de: <?php echo $row['name'];?>" data-content="<?php echo $popover;?>">
                                                    <?php echo $not['total_average'];?>
                                                </button>
                                                <?php
                                            }else{
                                                ?>
                                                <button type="button" class="btn btn-hover-bg-dark btn-text-dark btn-hover-text-white border-0 font-weight-bold mr-2" data-container="body" data-toggle="popover" data-html="true" data-placement="top" 
                                                title="Notas de: <?php echo $row['name'];?>" data-content="<?php echo $popover;?>">
                                                    <?php echo $not['total_average'];?>
                                                </button>
                                                <?php
                                            }
                                        }

                                    endforeach;
                                ?></td>
                                <?php 
                                    
                                endforeach;
                                ?>

                                </tr>
                        <?php 
                       		endforeach;
                        ?>
                        </tbody>
                    </table>
                    <!--end: Datatable-->
            </div>

           	</div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--begin::Entry-->