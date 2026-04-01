<script type="text/javascript">
    $(document).ready(function() {
        $('#student').select2();
    });
</script>
<?php
    $fecha_actual = date("Y-m-d");
    $fecha_inicial = strval(date("Y-m"))."-01";
?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">Asistencias por Estudiante</h3>
                    </div>
                    <!--begin::Form-->
                    <form method="POST" action="<?php echo base_url().$account_type.'/atte_report_student_xlsx'; ?>" >
                        <div class="card-body">
                            <div class="form-group">
                                <label>Estudiante:</label>
                                <select class="form-control select2" style="width: 100%;" id="student" name="student" >
                                    <option value="">Seleccione al Estudiante</option>
                                    <?php
                                    foreach($students as $stu):
                                    ?>
                                        <option value=<?php echo $stu['student_id'];?> ><?php echo $stu['lastname']." ".$stu['lastname2']." ". $stu['name'];?> - <b><?php echo $stu['nick_name'];?></b></option>
                                    <?php
                                    endforeach;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label  class="col-3 col-form-label" for="fechaIni">Fecha Inicial :
                                <span class="text-danger">*</span></label>
                                <div class="col-9">
                                <input type="date" class="form-control" value="<?php echo $fecha_inicial;?>" id="fechaIni" name="fechaIni" required >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-3 col-form-label" for="fechaFin">Fecha Final :
                                <span class="text-danger">*</span></label>
                                <div class="col-9">
                                <input type="date" class="form-control" value="<?php echo $fecha_actual;?>" id="fechaFin" name="fechaFin" required >
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">Descargar</button>
                            <a href="<?php echo base_url('secretary/dashboard');?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card-->
            </div>
            <div class="col-md-6">
                <!--begin::Card-->
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">Reporte de asistencias por CURSO</h3>
                    </div>
                    <!--begin::Form-->
                    <form method="POST" action="<?php echo base_url().$account_type.'/ausencias_suma_xlsx'; ?>" >
                        <div class="card-body">
                            <div class="form-group row">
                                <label  class="col-3 col-form-label" for="fechaIni">Fecha Inicial :
                                <span class="text-danger">*</span></label>
                                <div class="col-9">
                                <input type="date" class="form-control" value="<?php echo $fecha_actual;?>" id="fechaIni" name="fechaIni" required >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-3 col-form-label" for="fechaFin">Fecha Final :
                                <span class="text-danger">*</span></label>
                                <div class="col-9">
                                <input type="date" class="form-control" value="<?php echo $fecha_actual;?>" id="fechaFin" name="fechaFin" required >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-3 col-form-label" for="cursoIni">Del Curso :
                                <span class="text-danger">*</span></label>
                                <div class="col-9">
                                    <select class="form-control" name="cursoIni" id="cursoIni">
                                        <option value="">Seleccione curso inicial</option>
                                        <?php
                                        foreach($cursos as $cur):
                                        ?>
                                            <option value=<?php echo $cur['section_id'];?> ><?php echo $cur['completo'];?></option>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-3 col-form-label" for="cursoFin">Hasta :
                                <span class="text-danger">*</span></label>
                                <div class="col-9">
                                    <select class="form-control" name="cursoFin" id="cursoFin">
                                        <option value="">Seleccione curso final</option>
                                        <?php
                                        foreach($cursos as $cur):
                                        ?>
                                            <option value=<?php echo $cur['section_id'];?> ><?php echo $cur['completo'];?></option>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary mr-2">Descargar</button>
                            <a href="<?php echo base_url('secretary/dashboard');?>" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Card-->
            </div>
            
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
<script type="text/javascript">
    fillFecha();
</script>