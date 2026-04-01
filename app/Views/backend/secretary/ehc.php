<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Enfermeria
                    <span class="text-muted pt-2 font-size-sm d-block">Registro de Visitas</span></h3>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-checkable" id="kt_datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Estudiante</th>
                            <th>Fecha</th>
                            <th>H. Ingreso</th>
                            <th>H. Salida</th>
                            <th>Síntoma</th>
                            <th>Medicamento</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($datos as $key):?>
                        <tr>
                            <td><?php echo $key->id; ?></td>
                            <td><?php echo $key->nombre; ?></td>
                            <td><?php echo $key->fecha; ?></td>
                            <td><?php echo $key->hora_ingreso; ?></td>
                            <td><?php echo $key->hora_salida; ?></td>
                            <td><?php echo $key->sintoma; ?></td>
                            <td><?php echo $key->medicamento; ?></td>
                            <td>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
                'excel', 'pdf', 'print' // Agrega los botones de exportación
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