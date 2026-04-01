<script type="text/javascript">

    function recover_self(section_id, subject_id) {

        $.ajax({

            url: "<?php echo base_url(); ?>teacher/recover_self/" + section_id + "/" + subject_id,

            type: "get",

            beforeSend: function () {

                document.getElementById('mostrar_loading').style.display = "block"

            },

            success: function (response) {

                document.getElementById('mostrar_loading').style.display = "none"

                document.getElementById('mostrar_tabla').innerHTML = response;

            },

        });



    }

</script>

<?php

//Link Planilla

function link_sheet($partial_locked, $sheet_id)
{

    $link_sheet = "";

    if ($partial_locked == 1) {

        $link_sheet = "<a href='https://docs.google.com/spreadsheets/d/" . $sheet_id . "/edit' target='_blank' class='btn btn-success btn-sm' >Ir a la Planilla</a>";

    }

    echo $link_sheet;

}

//Au

//Reportar medio Trimestre

function link_half_phase($subject_id, $partial_locked)
{

    $link_half = "";

    if ($partial_locked == 0) {

        $link_half = "<a href='" . base_url() . "teacher/half_phase/" . $subject_id . "' target='_blank' class='btn btn-warning btn-sm' >Reportar Medio Trimestre</a>";

    }

    echo $link_half;

}

//Autoevaluaciones

function link_autoevaluacion($self_appraisal, $section_id, $subject_id, $locked)
{

    $link_auto = '';

    if ($self_appraisal == 'si' && $locked == 0) {

        $link_auto = "<a onclick='recover_self(" . $section_id . "," . $subject_id . ");' class='btn btn-light-warning font-weight-bold mr-2'></i>Recuperar Autoevaluaciones</a>";

    }

    echo $link_auto;

}

//Habilitar consolidar Notas

function link_consolidate_sheet($subject_id, $official_id, $locked)
{

    $link_consolidate = "";

    if ($official_id == 1 && $locked = 0) {

        $link_consolidate = "<a href='" . base_url() . "teacher/deliver_notes/" . $subject_id . "' target='_blank' class='btn btn-primary btn-sm' >Consolidar Notas</a>";

    }

    echo $link_consolidate;

}

?>

<!--begin::Entry-->

<div class="d-flex flex-column-fluid">

    <!--begin::Container-->

    <div class="container-fluid">

        <!--begin::Notice-->

        <div class="alert alert-custom alert-white alert-shadow fade show gutter-b" role="alert">

            <div class="alert-icon">

                <span class="svg-icon svg-icon-primary svg-icon-xl">

                    <!--begin::Svg Icon | path:assets/media/svg/icons/Tools/Compass.svg-->

                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">

                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">

                            <rect x="0" y="0" width="24" height="24" />

                            <path
                                d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z"
                                fill="#000000" opacity="0.3" />

                            <path
                                d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z"
                                fill="#000000" fill-rule="nonzero" />

                        </g>

                    </svg>

                    <!--end::Svg Icon-->

                </span>

            </div>

            <div class="alert-text">Selecione un nivel para ver sus Materias.</div>

        </div>

        <!--end::Notice-->

        <!--begin::Accordion-->

        <div class="accordion accordion-toggle-arrow" id="accordionExample1">

            <?php

            $c = 0;

            if (count($sub_prim12) > 0) {

                ?>

                <div class="card">

                    <div class="card-header">

                        <div class="card-title <?php if ($c == 0) {
                            echo 'collapsed';
                        } ?> " data-toggle="collapse"
                            data-target="#collapseOne1">Primaria 1ro - 2do </div>

                    </div>

                    <div id="collapseOne1" class="collapse <?php if ($c == 0) {
                        echo 'show';
                    } ?>"
                        data-parent="#accordionExample1">

                        <div class="card-body">

                            <table class="table">

                                <thead>

                                    <tr>

                                        <th scope="col">Materias</th>

                                        <th scope="col">Estado</th>

                                        <th scope="col">Acción</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    foreach ($sub_prim12 as $subject) {

                                        ?>

                                        <tr>

                                            <th scope="row"><?php echo $subject['name']; ?> - <?php echo $subject['nick_name']; ?>
                                            </th>

                                            <td>

                                                <?php

                                                if ($subject['sheet_id'] == "0") {

                                                    echo "Planilla sin Crear";

                                                } elseif ($subject['partial_locked'] == 0) {

                                                    echo "Sin habilitar planilla";

                                                } elseif ($subject['locked'] == 0) {

                                                    echo "Sin reportar Medio Trimestre";

                                                } elseif ($subject['official_id'] == 0) {

                                                    echo "Medio Trimestre Reportado";

                                                } else {

                                                    echo "Notas Consolidadas del: " . $phase_name;

                                                }

                                                ?>

                                            </td>

                                            <td>

                                                <?php link_sheet($subject['partial_locked'], $subject['sheet_id']); ?>

                                                <?php link_half_phase($subject['subject_id'], $subject['partial_locked']); ?>

                                                <?php link_autoevaluacion($self_appraisal, $subject['section_id'], $subject['subject_id'], $subject['locked']); ?>

                                                <?php link_consolidate_sheet($subject['subject_id'], $subject['official_id'], $subject['locked']); ?>

                                            </td>

                                        </tr>

                                        <?php

                                    }

                                    ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <?php

                $c += 1;

            }

            ?>





            <?php

            $c = 0;

            if (count($sub_prim36) > 0) {

                ?>

                <div class="card">

                    <div class="card-header">

                        <div class="card-title <?php if ($c == 0) {
                            echo 'collapsed';
                        } ?> " data-toggle="collapse"
                            data-target="#collapseOne2">Primaria 3ro - 6to </div>

                    </div>

                    <div id="collapseOne2" class="collapse <?php if ($c == 0) {
                        echo 'show';
                    } ?>"
                        data-parent="#accordionExample1">

                        <div class="card-body">

                            <table class="table">

                                <thead>

                                    <tr>

                                        <th scope="col">Materias</th>

                                        <th scope="col">Estado</th>

                                        <th scope="col">Acción</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    foreach ($sub_prim36 as $subject) {

                                        ?>

                                        <tr>

                                            <th scope="row"><?php echo $subject['name']; ?> - <?php echo $subject['nick_name']; ?>
                                            </th>

                                            <td>

                                                <?php

                                                if ($subject['sheet_id'] == "0") {

                                                    echo "Planilla sin Crear";

                                                } elseif ($subject['partial_locked'] == 0) {

                                                    echo "Sin habilitar planilla";

                                                } elseif ($subject['locked'] == 0) {

                                                    echo "Sin reportar Medio Trimestre";

                                                } elseif ($subject['official_id'] == 0) {

                                                    echo "Medio Trimestre Reportado";

                                                } else {

                                                    echo "Notas Consolidadas del: " . $phase_name;

                                                }

                                                ?>

                                            </td>

                                            <td>

                                                <?php link_sheet($subject['partial_locked'], $subject['sheet_id']); ?>

                                                <?php link_half_phase($subject['subject_id'], $subject['partial_locked']); ?>

                                                <?php link_autoevaluacion($self_appraisal, $subject['section_id'], $subject['subject_id'], $subject['locked']); ?>

                                                <?php link_consolidate_sheet($subject['subject_id'], $subject['official_id'], $subject['locked']); ?>

                                            </td>

                                        </tr>

                                        <?php

                                    }

                                    ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <?php

                $c += 1;

            }

            ?>



            <?php

            $c = 0;

            if (count($sub_sec13) > 0) {

                ?>

                <div class="card">

                    <div class="card-header">

                        <div class="card-title <?php if ($c == 0) {
                            echo 'collapsed';
                        } ?> " data-toggle="collapse"
                            data-target="#collapseOne3">Secundaria 1ro - 3ro </div>

                    </div>

                    <div id="collapseOne3" class="collapse <?php if ($c == 0) {
                        echo 'show';
                    } ?>"
                        data-parent="#accordionExample1">

                        <div class="card-body">

                            <table class="table">

                                <thead>

                                    <tr>

                                        <th scope="col">Materias</th>

                                        <th scope="col">Estado</th>

                                        <th scope="col">Acción</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    foreach ($sub_sec13 as $subject) {

                                        ?>

                                        <tr>

                                            <th scope="row"><?php echo $subject['name']; ?> - <?php echo $subject['nick_name']; ?>
                                            </th>

                                            <td>

                                                <?php

                                                if ($subject['sheet_id'] == "0") {

                                                    echo "Planilla sin Crear";

                                                } elseif ($subject['partial_locked'] == 0) {

                                                    echo "Sin habilitar planilla";

                                                } elseif ($subject['locked'] == 0) {

                                                    echo "Sin reportar Medio Trimestre";

                                                } elseif ($subject['official_id'] == 0) {

                                                    echo "Medio Trimestre Reportado";

                                                } else {

                                                    echo "Notas Consolidadas del: " . $phase_name;

                                                }

                                                ?>

                                            </td>

                                            <td>

                                                <?php link_sheet($subject['partial_locked'], $subject['sheet_id']); ?>

                                                <?php link_half_phase($subject['subject_id'], $subject['partial_locked']); ?>

                                                <?php link_autoevaluacion($self_appraisal, $subject['section_id'], $subject['subject_id'], $subject['locked']); ?>

                                                <?php link_consolidate_sheet($subject['subject_id'], $subject['official_id'], $subject['locked']); ?>

                                            </td>

                                        </tr>

                                        <?php

                                    }

                                    ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <?php

                $c += 1;

            }

            ?>

            <?php

            $c = 0;

            if (count($sub_sec46) > 0) {

                ?>

                <div class="card">

                    <div class="card-header">

                        <div class="card-title <?php if ($c == 0) {
                            echo 'collapsed';
                        } ?> " data-toggle="collapse"
                            data-target="#collapseOne4">Secundaria 4to - 6to </div>

                    </div>

                    <div id="collapseOne4" class="collapse <?php if ($c == 0) {
                        echo 'show';
                    } ?>"
                        data-parent="#accordionExample1">

                        <div class="card-body">

                            <table class="table">

                                <thead>

                                    <tr>

                                        <th scope="col">Materias</th>

                                        <th scope="col">Estado</th>

                                        <th scope="col">Acción</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    foreach ($sub_sec46 as $subject) {

                                        ?>

                                        <tr>

                                            <th scope="row"><?php echo $subject['name']; ?> - <?php echo $subject['nick_name']; ?>
                                            </th>

                                            <td>

                                                <?php

                                                if ($subject['sheet_id'] == "0") {

                                                    echo "Planilla sin Crear";

                                                } elseif ($subject['partial_locked'] == 0) {

                                                    echo "Sin habilitar planilla";

                                                } elseif ($subject['locked'] == 0) {

                                                    echo "Sin reportar Medio Trimestre";

                                                } elseif ($subject['official_id'] == 0) {

                                                    echo "Medio Trimestre Reportado";

                                                } else {

                                                    echo "Notas Consolidadas del: " . $phase_name;

                                                }

                                                ?>

                                            </td>

                                            <td>

                                                <?php link_sheet($subject['partial_locked'], $subject['sheet_id']); ?>

                                                <?php link_half_phase($subject['subject_id'], $subject['partial_locked']); ?>

                                                <?php link_autoevaluacion($self_appraisal, $subject['section_id'], $subject['subject_id'], $subject['locked']); ?>

                                                <?php link_consolidate_sheet($subject['subject_id'], $subject['official_id'], $subject['locked']); ?>

                                            </td>

                                        </tr>

                                        <?php

                                    }

                                    ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

                <?php

                $c += 1;

            }

            ?>

        </div>

        <!--end::Accordion-->

        <div id="mostrar_loading" class="spinner spinner-primary spinner-lg mr-15 spinner-center" style="display:none;">
        </div>

        <div class="card-body" id="mostrar_tabla"></div>

    </div>

    <!--end::Container-->

</div>

<!--end::Entry-->