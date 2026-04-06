<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-4">
                <div class="card-title">
                    <h3 class="card-label">Buscar Estudiante
                        <span class="d-block text-muted pt-2 font-size-sm">Buscar por apellido paterno — resultados en tiempo real</span>
                    </h3>
                </div>
                <div class="card-toolbar w-100 mt-3 d-flex align-items-center">
                    <div class="input-group" style="max-width:400px;">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0">
                                <i class="flaticon2-search-1 text-muted"></i>
                            </span>
                        </div>
                        <input id="buscar" name="buscar" class="form-control border-left-0 border-right-0"
                            type="search" placeholder="Escriba el apellido..." autocomplete="off" autofocus>
                        <div class="input-group-append">
                            <span class="input-group-text bg-white" id="search-spinner" style="display:none;">
                                <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                            </span>
                            <span class="input-group-text bg-white" id="search-icon">
                                <i class="flaticon2-search-1 text-muted" style="font-size:0.8rem;"></i>
                            </span>
                        </div>
                    </div>
                    <small class="text-muted ml-3" id="result-count"></small>
                </div>
            </div>

            <div class="card-body pt-2">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="font-weight-bolder text-dark-50">Estudiante</th>
                            <th class="font-weight-bolder text-dark-50">Curso</th>
                            <th class="font-weight-bolder text-dark-50 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="search-results-body">
                        <tr id="placeholder-row">
                            <td colspan="3" class="text-center text-muted py-6">
                                Escriba al menos 2 letras para buscar
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var user        = '<?php echo esc($user); ?>';
    var baseUrl     = '<?php echo base_url(); ?>';
    var tbody       = document.getElementById('search-results-body');
    var spinner     = document.getElementById('search-spinner');
    var searchIcon  = document.getElementById('search-icon');
    var countEl     = document.getElementById('result-count');
    var debounceTimer;

    function setPlaceholder(msg) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-6">' + msg + '</td></tr>';
    }

    function renderRow(s) {
        var nombre = (s.lastname + ' ' + (s.lastname2 || '') + ' ' + s.name).replace(/\s+/g, ' ').trim();
        var curso  = s.completo || s.nick_name || '';
        var id     = s.student_id;
        return '<tr>' +
            '<td class="align-middle font-weight-bold">' + nombre + '</td>' +
            '<td class="align-middle"><span class="badge badge-light-primary font-size-sm px-3 py-2">' + curso + '</span></td>' +
            '<td class="text-right align-middle">' +
                '<a href="' + baseUrl + 'index.php/teacher/student_licenses/' + id + '/all/0" class="btn btn-sm btn-primary">Licencias</a>' +
            '</td>' +
        '</tr>';
    }

    function doSearch(val) {
        if (val.length < 2) {
            setPlaceholder('Escriba al menos 2 letras para buscar');
            countEl.textContent = '';
            return;
        }

        spinner.style.display = 'inline-block';
        searchIcon.style.display = 'none';
        countEl.textContent = '';
        setPlaceholder('Buscando...');

        fetch(baseUrl + 'index.php/teacher/student_search_json/' + user + '/' + encodeURIComponent(val))
            .then(function (r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function (data) {
                spinner.style.display = 'none';
                searchIcon.style.display = 'inline-block';

                if (!data || data.length === 0) {
                    setPlaceholder('Sin resultados para <strong>' + val + '</strong>');
                    countEl.textContent = '';
                    return;
                }

                tbody.innerHTML = '';
                countEl.textContent = data.length + ' resultado' + (data.length !== 1 ? 's' : '');
                var html = '';
                data.forEach(function (s) { html += renderRow(s); });
                tbody.innerHTML = html;
            })
            .catch(function (err) {
                spinner.style.display = 'none';
                searchIcon.style.display = 'inline-block';
                setPlaceholder('Error al buscar. Intente nuevamente.');
                console.error('student_search_json error:', err);
            });
    }

    document.getElementById('buscar').addEventListener('input', function () {
        clearTimeout(debounceTimer);
        var val = this.value.trim();
        debounceTimer = setTimeout(function () { doSearch(val); }, 350);
    });
})();
</script>
