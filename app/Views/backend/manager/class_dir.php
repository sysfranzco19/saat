<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <div class="container">
        <div class="text-center pt-15">
            <h1 class="h2 font-weight-bolder text-dark mb-6">Grados</h1>
            <div class="h4 text-dark-50 font-weight-normal">Todos los Cursos Activos</div>
        </div>
        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th><div>Grado</div></th>
                    <th><div>Ranking<br />Notas</div></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($class as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['grade']); ?></td>
                    <td>
                        <a href="<?php echo base_url(); ?>manager/ranking_class/<?php echo $row['class_id']; ?>"
                           target="_blank" class="btn btn-primary btn-sm">Ranking Notas</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<!--end::Entry-->
