
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Faltas leves del estudiante: <?php echo $student; ?>
                    <span class="text-muted pt-2 font-size-sm d-block">Curso: <?php echo $completo; ?></span></h3>
                </div>
                <div class="card-toolbar">
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead class="thead-inverse">
                        <tr>
                            <th scope="col" >Fecha</th>
                            <th scope="col" >Materia</th>
                            <th scope="col" >Docente</th>
                            <th scope="col" >Falta Cometida</th>
                            <th scope="col" >Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($infractions as $inf):?>
                        <tr class="table-danger" >
                            <td><?php echo $inf['date']; ?></td>
                            <td><?php echo $inf['materia']; ?></td>
                            <td><?php echo $inf['docente']; ?></td>
                            <td><?php echo $inf['criteria']; ?></td>
                            <td><?php echo $inf['detail']; ?></td>
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