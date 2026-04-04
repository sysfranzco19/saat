<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Search Section-->
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="input-group input-group-lg input-group-solid">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="flaticon2-search-1"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control" id="global_student_search"
                                placeholder="Buscar cualquier estudiante por apellido..." />
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary font-weight-bold"
                                    id="btn_search">Buscar</button>
                            </div>
                        </div>
                        <div id="search_results" class="mt-4 shadow-sm"
                            style="display:none; position: absolute; z-index: 100; width: calc(100% - 30px); background: white;">
                            <div class="list-group" id="results_list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Search Section-->

        <!--begin::Notice-->
        <div class="alert alert-custom alert-white alert-shadow fade show gutter-b" role="alert">
            <div class="alert-icon">
                <span class="svg-icon svg-icon-primary svg-icon-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                        version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path
                                d="M7,3 L17,3 C18.1045695,3 19,3.8954305 19,5 L19,19 C19,20.1045695 18.1045695,21 17,21 L7,21 C5.8954305,21 5,20.1045695 5,19 L5,5 C5,3.8954305 5.8954305,3 7,3 Z"
                                fill="#000000" />
                            <rect fill="#000000" opacity="0.3" x="7" y="7" width="10" height="2" rx="1" />
                            <rect fill="#000000" opacity="0.3" x="7" y="11" width="10" height="2" rx="1" />
                            <rect fill="#000000" opacity="0.3" x="7" y="15" width="7" height="2" rx="1" />
                        </g>
                    </svg>
                </span>
            </div>
            <div class="alert-text">Seleccione un curso para ver el Historial Conductual general de sus estudiantes.
            </div>
        </div>
        <!--end::Notice-->

        <!--begin::Accordion-->
        <div class="accordion accordion-toggle-arrow" id="historyAccordion">

            <?php
            $levels = [
                ['id' => 'prim12', 'title' => 'Primaria 1ro - 2do', 'data' => $sub_prim12],
                ['id' => 'prim36', 'title' => 'Primaria 3ro - 6to', 'data' => $sub_prim36],
                ['id' => 'sec13', 'title' => 'Secundaria 1ro - 3ro', 'data' => $sub_sec13],
                ['id' => 'sec46', 'title' => 'Secundaria 4to - 6to', 'data' => $sub_sec46]
            ];

            $first = true;
            foreach ($levels as $level):
                if (count($level['data']) > 0):
                    ?>
                    <div class="card mb-2">
                        <div class="card-header">
                            <div class="card-title <?= $first ? '' : 'collapsed' ?>" data-toggle="collapse"
                                data-target="#collapse_<?= $level['id'] ?>">
                                <?= $level['title'] ?>
                            </div>
                        </div>
                        <div id="collapse_<?= $level['id'] ?>" class="collapse <?= $first ? 'show' : '' ?>"
                            data-parent="#historyAccordion">
                            <div class="card-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Curso / Sección</th>
                                            <th>Materia</th>
                                            <th class="text-right">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($level['data'] as $section): ?>
                                            <?php
                                            $hasSubjects = false;
                                            foreach ($subjects as $subj):
                                                if ($subj['section_id'] == $section['section_id']):
                                                    $hasSubjects = true;
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <span class="font-weight-bolder text-dark-75"><?= $section['completo'] ?></span>
                                                            <span class="text-muted d-block"><?= $section['nick_name'] ?></span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="font-weight-bold text-primary"><?= $subj['materia'] ?></span>
                                                        </td>
                                                        <td class="text-right align-middle">
                                                            <a href="<?= base_url('index.php/teacher/history_students/' . $section['section_id'] . '/' . $subj['subject_id']) ?>"
                                                                class="btn btn-light-primary font-weight-bold btn-sm">Ver Estudiantes</a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                endif;
                                            endforeach;
                                            if (!$hasSubjects):
                                                ?>
                                                <tr>
                                                    <td>
                                                        <span class="font-weight-bolder text-dark-75"><?= $section['completo'] ?></span>
                                                        <span class="text-muted d-block"><?= $section['nick_name'] ?></span>
                                                    </td>
                                                    <td class="text-muted align-middle">General</td>
                                                    <td class="text-right align-middle">
                                                        <a href="<?= base_url('index.php/teacher/history_students/' . $section['section_id'] . '/0') ?>"
                                                            class="btn btn-light-primary font-weight-bold btn-sm">Ver Estudiantes</a>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                    $first = false;
                endif;
            endforeach;
            ?>
        </div>
        <!--end::Accordion-->
    </div>
</div>

<script>
    $(document).ready(function () {
        var searchTimer;

        $('#global_student_search').on('keyup', function () {
            var query = $(this).val();
            clearTimeout(searchTimer);

            if (query.length < 3) {
                $('#search_results').hide();
                return;
            }

            searchTimer = setTimeout(function () {
                $.ajax({
                    url: '<?= base_url('index.php/teacher/student_search/adviser') ?>/' + encodeURIComponent(query),
                    type: 'GET',
                    success: function (response) {
                        // This app uses full page reloads for search usually, but we want an autocomplete feel.
                        // For now, let's redirect to the standard search result page if they press enter or click search.
                    }
                });
            }, 500);
        });

        $('#btn_search').on('click', function () {
            var query = $('#global_student_search').val();
            if (query.length >= 2) {
                window.location.href = '<?= base_url('index.php/teacher/student_search/manager') ?>/' + encodeURIComponent(query);
            }
        });

        $('#global_student_search').on('keypress', function (e) {
            if (e.which == 13) {
                $('#btn_search').click();
            }
        });
    });
</script>