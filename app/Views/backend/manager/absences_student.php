<script type="text/javascript">
    function cargar(sel){
        window.location.assign('<?php echo base_url(); ?>index.php/secretary/licensed/' + sel )
    }
    function confirmar()
    {
        var respuesta = confirm("¿Esta seguro de realizar los Cambios?");
        if (respuesta == true){
            document.form_license.submit(); 
        }else{
            return false;
        }
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
                    <h3 class="card-label">Ausencias a clases del estudiante: <?php echo $student;?>
                    <span class="d-block text-muted pt-2 font-size-sm">Curso: <?php echo $completo;?></span></h3>
                </div>
            </div>
            <div class="card-body">
                    <!--begin: Datatable-->
                <table class="table table-bordered table-checkable" id="kt_datatable">
                    <thead class="thead-inverse">
                        <tr>
                            <th><div>Nº</div></th>
                            <th>Docente/Materia</th>
                            <th>Detalle</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        //absence_add
                        $count=0;
                        if (count($absences)==0) {
                            echo "<tr><td colspan=6 >No tiene retrasos</td></tr>";
                        }
                        foreach($absences as $row):
                            $count+=1;
                        ?>
                        <tr>
                            <td><?php echo $count;?></td>
                            <td><?php echo $row['doc_materia']; ?></td>
                            <td><?php echo $row['obs']; ?></td>
                            <td><?php echo $row['fecha']; ?></td>
                            <td><?php echo $row['hora']; ?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    <!--end: Datatable-->
            </div>
                <?php
                
                ?>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
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
                    title: 'Ausencias de <?php echo $student; ?>' // Cambia el título para Excel
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Ausencias de <?php echo $student; ?>' // Cambia el título para PDF
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
