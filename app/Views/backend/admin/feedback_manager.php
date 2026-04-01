<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">Lista de Comentarios y Sugerencias
                <span class="d-block text-muted pt-2 font-size-sm">Comentarios recibidos de todos los usuarios</span>
            </h3>
        </div>
    </div>
    <div class="card-body">
        <!--begin: Datatable-->
        <table class="table table-separate table-head-custom table-checkable" id="kt_datatable_feedback">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Tipo</th>
                    <th>Comentario</th>
                    <th>URL de origen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $row): ?>
                    <tr>
                        <td>
                            <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?>
                        </td>
                        <td>ID:
                            <?php echo $row['user_id']; ?>
                        </td>
                        <td>
                            <span class="label label-lg label-inline label-light-primary font-weight-bold">
                                <?php echo strtoupper($row['user_type']); ?>
                            </span>
                        </td>
                        <td>
                            <?php echo nl2br(htmlspecialchars($row['comment'])); ?>
                        </td>
                        <td>
                            <a href="<?php echo $row['url']; ?>" target="_blank" class="text-primary font-size-sm">
                                Ver página
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!--end: Datatable-->
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#kt_datatable_feedback').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
            }
        });
    });
</script>