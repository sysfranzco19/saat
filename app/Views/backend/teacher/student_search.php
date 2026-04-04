<script type="text/javascript">
    function cargar(){
        var sel = document.getElementById('buscar').value;
        window.location.assign('<?php echo base_url(); ?>index.php/teacher/student_search/<?php echo $user; ?>/' + sel )
        //alert('hola');
    }
</script>
<?php
//Variable que contendrá el resultado de la búsqueda
$texto = '';
//Variable que contendrá el número de resgistros encontrados
$registros = 'Resultados';
$datos = array();
$cursos = array();
$entero = 0;
if (empty($busqueda)){
    $texto = 'Búsqueda sin resultados';
}else{
    //Si hay resultados...
    if ($resultado!=NULL){ 
        // Se recoge el número de resultados         
        $registros = '<span>Se encontraron ' . count($resultado) . ' registros </span>';
        // Se almacenan las cadenas de resultado
        //$i=0;
        foreach($resultado as $fila) {
            $com_completo = $fila['lastname']." ".$fila['lastname2']." ".$fila['name'] ;
            $texto .= $com_completo . '<br />';
            $datos[$fila['student_id']]=$com_completo;
            $cursos[$fila['student_id']]=$fila['completo'];
            $cursos['section_'.$fila['student_id']]=$fila['section_id'];
        }
    }else{
            $registros = "<span>No hay Resultados</span>";  
    }
}
?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Buscar Estudiante
                    <span class="d-block text-muted pt-2 font-size-sm">Buscar al estudiante por Apellido Paterno</span></h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Dropdown-->
                    <div class="dropdown dropdown-inline mr-2">
                        <div class="form-group">
                            <input id="buscar" name="buscar" class="form-control" type="search" placeholder="Buscar aquí..." value="<?php echo $busqueda; ?>" autofocus >
                            <input type="button" onclick="cargar()" class="btn btn-primary" value="Buscar">
                        </div>                  
                    </div>
                    <!--end::Dropdown-->
                </div>
            </div>
            <div class="card-body" id="imp1">
                <!--begin: Datatable-->
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Estudiantes</th>
                            <th>Curso</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    //Resultado, número de registros y contenido.
                    foreach ($datos as $id=>$nombre)
                    {
                    ?>
                        <tr>
                            <td><?php echo $nombre; ?></td>
                            <td><?php echo $cursos[$id]; ?></td>
                            <td>
                                <!--
                                <div class="btn-group dropup">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Acción</button>
                                    <div class="dropdown-menu" style="">
                                        <a class="dropdown-item" href="<?php echo base_url(); ?>index.php/secretary/student_attendance/<?php echo $id;?>/all/0">Ver Asistencias</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" onclick="showAjaxModal('<?php echo base_url();?>index.php/modal/popup/modal_leave_days/<?php echo $id;?>/<?php echo $nombre;?>/<?php echo $cursos['section_'.$id];?>/0');">Registrar licencia por día</a>
                                        <a class="dropdown-item" onclick="showAjaxModal('<?php echo base_url();?>index.php/modal/popup/modal_leave_hours/<?php echo $id;?>/<?php echo $nombre;?>/<?php echo $cursos['section_'.$id];?>/0');">Registrar licencia por hora</a>
                                    </div>
                                </div>-->
                                <a href="<?php echo base_url(); ?>index.php/teacher/notes_half_student/<?php echo $id;?>" class="btn btn-warning btn-sm" >Medio Trimestre</a>
                                <a href="<?php echo base_url(); ?>index.php/teacher/student_notes/<?php echo $id;?>" class="btn btn-danger btn-sm" >Ver notas</a>
                                <a href="<?php echo base_url(); ?>index.php/teacher/student_attendance/<?php echo $id;?>/all/0" class="btn btn-success btn-sm"  >Ver Asistencias</a>
                                <a href="<?php echo base_url(); ?>index.php/teacher/student_licenses/<?php echo $id;?>/all/0" class="btn btn-primary btn-sm"  >Ver Licencias</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                        <tr>
                            <td colspan="3"><?php echo $registros; ?></td>
                        </tr>
                    </tbody>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>