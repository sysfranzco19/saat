<script type="text/javascript">
    function confirmar(sel)
    {
        var datos = sel.split('-');
        var respuesta = confirm("¿Esta seguro que desea cambiar de Consejero?");
        if (respuesta == true){
            window.location.assign('<?php echo base_url(); ?>index.php/secretary/counselors_update/' + datos[0] + '/' + datos[1] )
        }else{
            return false;
        }
    }
    function imprim1(imp1){
        var printContents = document.getElementById('imp1').innerHTML;
        w = window.open();
        w.document.write(printContents);
        w.document.close(); // necessary for IE >= 10
        w.focus(); // necessary for IE >= 10
        w.print();
        w.close();
        return true;
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
                    <h3 class="card-label">Reporte Conductual
                    <span class="d-block text-muted pt-2 font-size-sm">Listado de Reportes Conductuales</span></h3>
                </div>
                <div class="card-toolbar">

                    <!--begin::Dropdown-->
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
                                    <a href="#" class="navi-link">
                                        <span class="navi-icon">
                                            <i class="la la-file-excel-o"></i>
                                        </span>
                                        <span class="navi-text">Descargar Excel</span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link" target="_blank">
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
                    <!--end::Dropdown-->
                </div>
            </div>
            <div class="card-body" id="imp1"> 
                <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Nro.</th>
                            <th>Curso</th>
                            <th>Consejero</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count=0;
                        foreach($cursos as $row):
                            $count+=1;
                        ?>
                        <tr >
                            <th scope="row"><?php echo $count;?></th>
                            <td><?php echo $row['completo'];?></td>
                            <td>
                                <input type="hidden" id="section" value="<?php echo $row['section_id']; ?>" />
                                <select class="form-control selectpicker" onChange="confirmar(this.value)" name="teacher_id">
                                    <option value="" >Seleccione un Docente</option>
                                        <?php foreach ($teachers as $tea){ ?>
                                    <option value="<?php echo $row['section_id']."-".$tea['teacher_id']; ?>" <?php if ($row['teacher_id']==$tea['teacher_id']) {echo 'selected';} ?> ><?php echo $tea['name']; ?></option>
                                            <?php
                                        } ?>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>


           