<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Boletas Verde – Faltas Graves
                    <span class="d-block text-muted pt-2 font-size-sm">Selecciona un curso para registrar boletas</span></h3>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Curso</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cursos as $cur): ?>
                        <tr>
                            <td class="font-weight-bold"><?= $cur['completo'] ?></td>
                            <td>
                                <a href="<?= base_url('secretary/boletas_seccion/' . $cur['section_id']) ?>"
                                   class="btn btn-warning btn-sm font-weight-bold">
                                    <i class="fas fa-file-alt mr-1"></i> Registrar Boletas
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
