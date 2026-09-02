<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Asistencia Secundaria por Curso y Fecha
                    <span class="d-block text-muted pt-2 font-size-sm">Selecciona un curso y una fecha para consultar la asistencia</span></h3>
                </div>
            </div>
            <div class="card-body">
                <div class="form-row align-items-end mb-4">
                    <div class="col-md-4">
                        <label class="font-weight-bold">Curso</label>
                        <select id="att_section_id" class="form-control">
                            <option value="">Seleccione un curso</option>
                            <?php foreach ($cursos as $cur): ?>
                                <option value="<?= $cur['section_id'] ?>"><?= $cur['completo'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold">Fecha</label>
                        <input type="date" id="att_fecha" class="form-control" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="btnBuscarAsistencia" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-search mr-1"></i> Buscar
                        </button>
                    </div>
                </div>

                <div id="att_resumen" class="row mb-4" style="display:none;">
                    <div class="col-6 col-md-2">
                        <div class="card card-custom bg-light-primary">
                            <div class="card-body p-4 text-center">
                                <div class="font-weight-bolder font-size-h2" id="resTotal">0</div>
                                <div class="font-weight-bold text-muted">Total</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card card-custom bg-light-success">
                            <div class="card-body p-4 text-center">
                                <div class="font-weight-bolder font-size-h2 text-success" id="resPresente">0</div>
                                <div class="font-weight-bold text-muted">Presentes</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card card-custom bg-light-danger">
                            <div class="card-body p-4 text-center">
                                <div class="font-weight-bolder font-size-h2 text-danger" id="resAusente">0</div>
                                <div class="font-weight-bold text-muted">Ausentes</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card card-custom bg-light-info">
                            <div class="card-body p-4 text-center">
                                <div class="font-weight-bolder font-size-h2 text-info" id="resLicencia">0</div>
                                <div class="font-weight-bold text-muted">Licencias</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card card-custom bg-light-warning">
                            <div class="card-body p-4 text-center">
                                <div class="font-weight-bolder font-size-h2 text-warning" id="resRetraso">0</div>
                                <div class="font-weight-bold text-muted">Retrasos</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="card card-custom bg-light-secondary">
                            <div class="card-body p-4 text-center">
                                <div class="font-weight-bolder font-size-h2 text-secondary" id="resSinRegistro">0</div>
                                <div class="font-weight-bold text-muted">Sin Registro</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="att_alert"></div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="tablaAsistenciaCurso">
                        <thead class="thead-light">
                            <tr>
                                <th>Id</th>
                                <th>Estudiante</th>
                                <th>Curso</th>
                                <th>Fecha</th>
                                <th>Asistencia</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var tabla = null;

    function initTabla() {
        if (tabla) {
            tabla.destroy();
            $('#tablaAsistenciaCurso tbody').empty();
        }
        tabla = $('#tablaAsistenciaCurso').DataTable({
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'print'],
            order: [[1, 'asc']],
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ filas",
                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                infoEmpty: "No hay entradas disponibles",
                infoFiltered: "(filtrado de _MAX_ entradas totales)",
                zeroRecords: "No se encontraron resultados",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            }
        });
    }

    function actualizarResumen(data) {
        var conteo = { 'Presente': 0, 'Ausente': 0, 'Licencia': 0, 'Retraso': 0, 'Sin Registro': 0 };
        data.forEach(function (row) {
            if (conteo.hasOwnProperty(row.Asistencia)) conteo[row.Asistencia]++;
        });

        $('#resTotal').text(data.length);
        $('#resPresente').text(conteo['Presente']);
        $('#resAusente').text(conteo['Ausente']);
        $('#resLicencia').text(conteo['Licencia']);
        $('#resRetraso').text(conteo['Retraso']);
        $('#resSinRegistro').text(conteo['Sin Registro']);
        $('#att_resumen').show();
    }

    function badgeAsistencia(estado) {
        var clases = {
            'Presente': 'badge-success',
            'Ausente': 'badge-danger',
            'Licencia': 'badge-info',
            'Retraso': 'badge-warning',
            'Sin Registro': 'badge-secondary'
        };
        var clase = clases[estado] || 'badge-secondary';
        return '<span class="badge ' + clase + '">' + estado + '</span>';
    }

    function buscarAsistencia() {
        var section_id = $('#att_section_id').val();
        var fecha = $('#att_fecha').val();

        $('#att_alert').empty();

        if (!section_id || !fecha) {
            $('#att_alert').html('<div class="alert alert-light-warning">Seleccione un curso y una fecha para consultar.</div>');
            $('#att_resumen').hide();
            return;
        }

        $.ajax({
            url: '<?= base_url('secretary/attendance_by_course_data') ?>',
            type: 'GET',
            data: { section_id: section_id, fecha: fecha },
            dataType: 'json',
            success: function (resp) {
                if (!resp.status) {
                    $('#att_alert').html('<div class="alert alert-light-danger">' + resp.message + '</div>');
                    return;
                }

                initTabla();

                if (!resp.data.length) {
                    $('#att_alert').html('<div class="alert alert-light-info">No hay estudiantes activos para ese curso.</div>');
                    $('#att_resumen').hide();
                    return;
                }

                actualizarResumen(resp.data);

                resp.data.forEach(function (row) {
                    tabla.row.add([
                        row.Id,
                        row.Estudiante,
                        row.Curso,
                        row.Fecha,
                        badgeAsistencia(row.Asistencia)
                    ]);
                });
                tabla.draw();
            },
            error: function () {
                $('#att_alert').html('<div class="alert alert-light-danger">Error al consultar la asistencia.</div>');
            }
        });
    }

    $('#btnBuscarAsistencia').on('click', buscarAsistencia);

    // Permite iniciar la búsqueda presionando Enter en la fecha
    $('#att_fecha').on('keyup', function (e) {
        if (e.key === 'Enter') buscarAsistencia();
    });
})();
</script>
