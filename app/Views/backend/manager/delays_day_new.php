<?php

function fechaCastellano($fecha) {
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    
    $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    
    $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    
    return $nombredia . " " . $numeroDia . " de " . $nombreMes . " de " . $anio;
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
                    <h3 class="card-label">Retrasos del: 
                        <?php 
                            // Mostrar la fecha solo si está disponible
                            if (isset($date) && !empty($date)) {
                                echo fechaCastellano($date);
                            } else {
                                echo "Seleccione una fecha";
                            }
                        ?>
                        <span class="d-block text-muted pt-2 font-size-sm"></span>
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <!-- Campo para ingresar fecha -->
                <form action="<?php echo base_url('manager/delays_day_new'); ?>" method="post">
                    <input type="date" name="fechaRetraso" value="<?php echo isset($date) ? $date : ''; ?>" class="form-control mb-3" required />
                    <button type="submit" class="btn btn-info py-3 px-6 btn-custom">Buscar</button>
                </form>

                <!--begin: Datatable-->
                <?php if (isset($delays) && !empty($delays)): ?>
                <table class="table mt-3">
                    <thead class="thead-inverse">
                        <tr>
                            <th><div>Nº</div></th>
                            <th><div>Estudiante</div></th>
                            <th><div>Curso</div></th>
                            <th><div>H. Ingreso</div></th>
                            <th><div>H. Llegada</div></th>
                            <th><div>Motivo del Retraso</div></th>
                            <th><div>Tarde con:</div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $count = 0;
                        foreach($delays as $row):
                            $count++;
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $row['student']; ?></td>
                            <td><?php echo $row['completo']; ?></td>
                            <td><?php echo $row['hora_ingreso']; ?></td>
                            <td><?php echo $row['hora_llegada']; ?></td>
                            <td><?php echo $row['motivo']; ?></td>
                            <td><?php echo $row['tarde_con']; ?> min.</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p>No hay retrasos para la fecha seleccionada.</p>
                <?php endif; ?>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
