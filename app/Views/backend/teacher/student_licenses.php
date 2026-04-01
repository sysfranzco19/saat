
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Licencias solicitadas
                    <span class="text-muted pt-2 font-size-sm d-block">Muestra las licencias solicitadas</span></h3>
                </div>
                <div class="card-toolbar">
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                        <th>Estudiante</th>
                        <th>Detalle</th>
                        <th>Tipo Licencia</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($licencias as $key):?>
                        <tr>
                            <td><?php echo $key->student; ?></td>
                            <td><?php echo $key->detalle; ?></td>
                            <td><?php echo $key->tipo; ?></td>
                            <td><?php echo $key->inicio; ?></td>
                            <td><?php echo $key->fin; ?></td>
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