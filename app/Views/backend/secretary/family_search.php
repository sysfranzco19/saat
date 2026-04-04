<script type="text/javascript">
    function cargar(){
        var sel = document.getElementById('buscar').value;
        // Validar si el valor tiene 3 o más caracteres
        if (sel.length >= 3) {
            // Realizar la acción deseada, por ejemplo, realizar la búsqueda
            window.location.assign('<?php echo base_url(); ?>index.php/secretary/family_search/<?php echo $user; ?>/' + sel )
            // Puedes agregar aquí la lógica para realizar la búsqueda
        } else {
            // Mostrar un mensaje de error o tomar otra acción si el valor no cumple con la longitud requerida
            alert("Por favor, introduce al menos 3 caracteres para buscar.");
            document.getElementById('buscar').focus();
        }
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
            $com_completo = $fila['lastname1']." ".$fila['lastname2'];
            $texto .= $com_completo . '<br />';
            $datos[$fila['family_id']]=$com_completo;
            //$cursos['section_'.$fila['student_id']]=$fila['section_id'];
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
                    <h3 class="card-label">Buscar Familia
                    <span class="d-block text-muted pt-2 font-size-sm">Buscar al familia por Apellido Paterno</span></h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Dropdown-->
                    <div class="dropdown dropdown-inline mr-2">
                        <div class="form-group">
                            <input id="buscar" name="buscar" class="form-control" type="search" placeholder="Buscar aquí..." value="<?php echo $busqueda; ?>" autofocus >
                        </div>
                    </div>
                    <div class="dropdown dropdown-inline mr-2">  
                        <div class="form-group">
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
                            <th>Familias</th>
                            <th>Estudiantes Inscritos</th>
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
                            <td>
                            <?php
                                foreach($students as $row) {
                                    if ($row['family_id']==$id) {
                                        echo $row['lastname'].' '.$row['lastname2'].' '.$row['name'].' - '.$row['nick_name']. '<br />';
                                    }
                                }
                            ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" onclick="window.location.href = '<?php echo base_url(); ?>index.php/secretary/family_info/<?php echo $id;?>';" >Ver información</button>
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