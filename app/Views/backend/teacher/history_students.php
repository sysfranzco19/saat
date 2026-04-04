<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Card-->
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <h3 class="card-label">
                        <span class="d-block text-dark font-weight-bolder"><?= $curso ?></span>
                        <span class="d-block text-muted mt-2 font-size-sm"><?= $materia ?></span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <a href="<?= base_url('index.php/teacher/history') ?>"
                        class="btn btn-light-primary font-weight-bolder btn-sm">
                        <i class="ki ki-long-arrow-back icon-sm"></i> Volver a Cursos
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!--begin: Search Form-->
                <div class="mb-7">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-xl-8">
                            <div class="row align-items-center">
                                <div class="col-md-4 my-2 my-md-0">
                                    <div class="input-icon">
                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Filtrar por nombre..." id="kt_datatable_search_query" />
                                        <span><i class="flaticon2-search-1 text-muted"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Search Form-->

                <!--begin: Datatable-->
                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center" id="student_list_table">
                        <thead>
                            <tr class="text-left">
                                <th style="min-width: 250px" class="pl-7">Estudiante</th>
                                <th class="text-right pr-7">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $row): ?>
                                <tr>
                                    <td class="pl-7">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40 symbol-light-primary mr-5">
                                                <span class="symbol-label font-size-h5 font-weight-bold">
                                                    <?= substr($row['student'], 0, 1) ?>
                                                </span>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="<?= base_url('index.php/teacher/student_profile/' . $row['student_id'] . '/' . $subject_id) ?>"
                                                    class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg">
                                                    <?= $row['student'] ?>
                                                </a>
                                                <span class="text-muted font-weight-bold">ID:
                                                    <?= $row['student_id'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right pr-7">
                                        <a href="<?= base_url('index.php/teacher/student_profile/' . $row['student_id'] . '/' . $subject_id) ?>"
                                            class="btn btn-light-primary font-weight-bold btn-sm" title="Ver Perfil">
                                            Ver Perfil
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#kt_datatable_search_query").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#student_list_table tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>