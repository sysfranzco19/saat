
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Promedios bajos: <?php echo $grade; ?>
                    <span class="text-muted pt-2 font-size-sm d-block">Trimestre: <?php echo $phase_id; ?></span></h3>
                </div>
                <div class="card-toolbar">
                </div>
            </div>
            <div class="card-body">
            <table class="table table-bordered table-checkable" id="kt_datatable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Estudiante</th>
                            <th scope="col">Curso</th>
                            <th scope="col">Prom. T<?php echo $phase_id; ?></th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if(count($averages)>0){
                        $count=0;
                        foreach ($averages as $row):
                            $count+=1;                             
                        ?>
                        <tr>
                            <th scope="row"><?php echo $count;?></th>
                            <td><?php echo $row['student'];?></th>
                            <td><?php echo $row['nick_name'];?></td>
                            <td><?php 
                            $prom = "";
                            $valor = round($row['promedio']);
                            switch ($valor) {
                                case ($valor >= 85 && $valor <= 100):
                                    $prom = '<p class="text-info">'.$valor.'</p>';
                                    break;
                                case ($valor >= 68 && $valor <= 84):
                                    $prom = '<p class="text-dark">'.$valor.'</p>';
                                    break;
                                case ($valor >= 51 && $valor <= 67):
                                    $prom = '<p class="text-warning">'.$valor.'</p>';
                                    break;
                                case ($valor >= 1 && $valor <= 50):
                                    $prom = '<p class="text-danger">'.$valor.'</p>';
                                    break;
                                default:
                                $prom = '<p class="text-primary">'.$valor.'</p>';
                            }
                            echo $prom;
                            ?></td>
                            <td><a href="<?php echo base_url(); ?>teacher/student_notes/<?php echo $row['student_id'];?>" target="_blank" class="btn btn-light-primary btn-sm font-weight-bold mr-2">Ver notas</a></td>
                        </tr>
                        <?php
                        endforeach;
                    }else{
                        ?>
                        <tr><td colspan=5 >No existen registros.</td></tr>
                        <?php 
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
<!-- Script para inicializar DataTable -->
<script>
    $(document).ready(function() {
        // Obtener la fecha actual en el formato que se encuentra en la columna 3
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd; // Ajusta este formato según el formato de la fecha en tu tabla

        // Inicializar el DataTable con filtro de fecha en la columna 3
        var table = $('#kt_datatable').DataTable({
            dom: 'Bfrtip', // Define la posición de los elementos (B: Buttons, f: Filter, r: Processing, t: Table, i: Info, p: Pagination)
            buttons: [
                {
                    extend: 'excelHtml5', 
                    title: 'Promedios Bajos <?php echo $grade; ?>' // Cambia el título para Excel
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Promedios Bajos <?php echo $grade; ?>' // Cambia el título para PDF
                },
                'print' // Mantén el botón de impresión si lo necesitas
            ],
            language: {
                search: "Buscar:", // Cambia el texto del campo de búsqueda
                lengthMenu: "Mostrar _MENU_ filas", // Cambia el texto del menú de longitud
                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas", // Cambia el texto de la información
                infoEmpty: "No hay entradas disponibles", // Cambia el texto cuando no hay entradas disponibles
                infoFiltered: "(filtrado de _MAX_ entradas totales)", // Cambia el texto del filtro
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
        });
    });
</script>