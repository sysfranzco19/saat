
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Comunicación a los Padres del estudiante: <?php echo $student; ?>
                    <span class="text-muted pt-2 font-size-sm d-block">Curso: <?php echo $completo; ?></span></h3>
                </div>
                <div class="card-toolbar">
                </div>
            </div>
            <div class="card-body">
                <table class="table datatable_students" data-export-title="Comunicaciones a los Padres - <?php echo htmlspecialchars($student, ENT_QUOTES, 'UTF-8');?>">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Materia</th>
                            <th scope="col">Docente</th>
                            <th scope="col">Comunicación</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if(count($behaviors)>0){
                        $count=0;
                        foreach ($behaviors as $row):
                            setlocale(LC_ALL,"es_ES");
                            $newDate = date("d/m/Y", $row['date']);
                            $count+=1;                             
                        ?>
                        <tr>
                            <th scope="row"><?php echo $count;?></th>
                            <td><?php echo $newDate;?></th>
                            <td><?php echo $row['materia'];?></td>
                            <td><?php echo $row['docente'];?></td>
                            <td><?php echo $row['behavior'];?></td>
                        </tr>
                        <?php
                        endforeach;
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.datatable_students').each(function () {
            var exportTitle = $(this).data('export-title');
            $(this).DataTable({
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: exportTitle,
                        className: 'btn btn-light-success font-weight-bold'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: exportTitle,
                        className: 'btn btn-light-danger font-weight-bold'
                    },
                    {
                        extend: 'print',
                        title: exportTitle,
                        className: 'btn btn-light-primary font-weight-bold'
                    }
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
                },
                lengthMenu: [ [10, 25, 30, 50, -1], [10, 25, 30, 50, "Todos"] ],
                pageLength: 30
            });
        });
    });
</script>