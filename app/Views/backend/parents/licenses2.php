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
            <div class="alert-text">Las licencias para el día de hoy solo podrán registrarse hasta las <code>9:30 a.m.</code> y tendrán una duración máxima de 3 días.<br />
            Si necesita solicitar más días, por favor contacte a: <a class="font-weight-bold" href="mailto:secretaria@tiquipaya.edu.bo" target="_blank">secretaria@tiquipaya.edu.bo</a>.</div>
        </div>
        <!--end::Notice-->
        <?php foreach($students as $row): ?>
        <!--begin::Card-->
        <div class="card card-custom gutter-b">                   
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">Licencias de: <?php echo $row['student'];?></h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <button type="button" id="licenseButton" class="btn btn-primary btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/student_license_modal_dia/<?php echo $row['student_id'];?>/<?php echo $row['student'];?>/<?php echo $row['family_id'];?>/0');" >
                        + Licencia por Día
                    </button>&nbsp;
                    <button type="button" id="licenseButton" class="btn btn-secondary btn-sm" onclick="showAjaxModal('<?php echo base_url();?>/modal/popup/student_license_modal_hora/<?php echo $row['student_id'];?>/<?php echo $row['student'];?>/<?php echo $row['family_id'];?>/0');" >
                        + Licencia por Horas
                    </button>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable" id="kt_datatable">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Fecha solicitada</th>
                            <th>Detalle</th>
                            <th>Tipo</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($licencias[$row['student_id']]) && !empty($licencias[$row['student_id']])): ?>
                            <?php foreach($licencias[$row['student_id']] as $key): ?>
                                <tr>
                                    <td><?php echo $key->fecha; ?></td>
                                    <td><?php echo $key->detalle; ?></td>
                                    <td><?php echo $key->tipo; ?></td>
                                    <td><?php echo $key->inicio; ?></td>
                                    <td><?php echo $key->fin; ?></td>
                                    <td><?php 
                                    if ($key->enviado==1) {
                                        ?>
                                        <span class="label label-inline label-light-success font-weight-bold">
                                            Autorizado
                                        </span>
                                        <?php
                                    }else{
                                        ?>
                                        <span class="label label-inline label-light-danger font-weight-bold">
                                            Pendiente
                                        </span>
                                        <?php
                                    }
                                    ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay licencias registradas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
        <?php endforeach; ?>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->