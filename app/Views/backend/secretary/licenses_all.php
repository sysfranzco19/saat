<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">Todas las Licencias
                    <span class="text-muted pt-2 font-size-sm d-block">Listado completo: días y períodos</span></h3>
                </div>
                <div class="card-toolbar">
                    <a href="<?php echo base_url('secretary/licenses_add'); ?>" class="btn btn-primary btn-sm">
                        <i class="flaticon2-plus icon-sm"></i> Nueva Licencia
                    </a>
                    <a href="<?php echo base_url('secretary/licenses_periodo_add'); ?>" class="btn btn-primary btn-sm">
                        <i class="flaticon2-plus icon-sm"></i> Nueva Licencia por Horas
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover" id="kt_datatable" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Tipo</th>
                            <th>Detalle</th>
                            <th>Fecha Solicitud</th>
                            <th>Inicio</th>
                            <th>Fin / Período(s)</th>
                            <th>Días</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->

<script>
$(document).ready(function() {
    var table = $('#kt_datatable').DataTable({
        processing: true,
        serverSide: true,
        deferRender: true,
        ajax: {
            url: '<?php echo base_url('secretary/licenses_all_data'); ?>',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTables AJAX error:', thrown);
            }
        },
        columns: [
            { data: null, orderable: false, searchable: false,
              render: function(data, type, row, meta) {
                  return meta.row + meta.settings._iDisplayStart + 1;
              }
            },
            { data: 'student',
              render: function(data, type, row) {
                  return data + ' <span class="badge badge-secondary">' + (row.nick_name || '') + '</span>';
              }
            },
            { data: 'nick_name' },
            { data: 'tipo',
              render: function(data, type, row) {
                  if (row.tipo_id == 1) {
                      return '<span class="badge badge-primary">' + data + '</span>';
                  } else {
                      return '<span class="badge badge-warning text-dark">' + data + '</span>';
                  }
              }
            },
            { data: 'detalle' },
            { data: 'fecha_solicitud' },
            { data: 'inicio', defaultContent: '-' },
            { data: 'fin', defaultContent: '-' },
            { data: 'cantidad_dias', defaultContent: '-', orderable: false,
              render: function(data) {
                  return data ? data : '-';
              }
            },
            { data: 'enviado', orderable: false,
              render: function(data) {
                  if (data == 1) {
                      return '<span class="badge badge-success">Enviado</span>';
                  } else if (data == 2) {
                      return '<span class="badge badge-danger">Rechazado</span>';
                  } else {
                      return '<span class="badge badge-secondary">Pendiente</span>';
                  }
              }
            },
            { data: 'licencias_id', orderable: false, searchable: false,
              render: function(data, type, row) {
                  var html = '<a href="<?php echo base_url('secretary/licenses_edit/'); ?>' + data + '" class="btn btn-xs btn-icon btn-light-primary" title="Editar"><i class="flaticon2-edit icon-sm"></i></a> ' +
                             '<a href="<?php echo base_url('secretary/license_report/'); ?>' + data + '" target="_blank" class="btn btn-xs btn-icon btn-light-info" title="Reporte PDF"><i class="flaticon2-file icon-sm"></i></a>';
                  if (row.documento) {
                      html += ' <a href="<?php echo base_url('uploads/comprobantes_medicos/'); ?>' + row.documento + '" target="_blank" class="btn btn-xs btn-icon btn-light-warning" title="Ver documento"><i class="fas fa-paperclip"></i></a>';
                  }
                  return html;
              }
            },
        ],
        order: [[5, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'Todos']],
        dom: '<"row"<"col-sm-6"B><"col-sm-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="far fa-file-excel"></i> Excel',
                className: 'btn btn-sm btn-success',
                exportOptions: { columns: [1,2,3,4,5,6,7,8,9] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="far fa-file-pdf"></i> PDF',
                className: 'btn btn-sm btn-danger',
                exportOptions: { columns: [1,2,3,4,5,6,7,8,9] },
                orientation: 'landscape',
                pageSize: 'A4'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-sm btn-secondary',
                exportOptions: { columns: [1,2,3,4,5,6,7,8,9] }
            }
        ],
        language: {
            processing:     'Cargando...',
            search:         'Buscar:',
            lengthMenu:     'Mostrar _MENU_ registros',
            info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
            infoEmpty:      'No hay registros disponibles',
            infoFiltered:   '(filtrado de _MAX_ registros totales)',
            loadingRecords: 'Cargando...',
            zeroRecords:    'No se encontraron resultados',
            emptyTable:     'No hay datos disponibles',
            paginate: {
                first:    'Primero',
                last:     'Último',
                next:     'Siguiente',
                previous: 'Anterior'
            }
        }
    });
});
</script>