<style>
    .gamified-table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: #B5B5C3;
        letter-spacing: 0.05em;
    }

    .gamified-table td {
        vertical-align: middle;
        font-size: 1.1em;
    }

    .score-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: 800;
        min-width: 70px;
        text-align: center;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .score-badge.high {
        background: #c9f7f5;
        color: #1bc5bd;
    }

    .score-badge.mid {
        background: #fff4de;
        color: #ffa800;
    }

    .score-badge.low {
        background: #ffe2e5;
        color: #f64e60;
    }

    .score-avatar-badge {
        position: absolute;
        bottom: -5px;
        right: -5px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 800;
        z-index: 20;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .score-avatar-badge.high { background: #1bc5bd; color: #fff; }
    .score-avatar-badge.mid { background: #ffa800; color: #fff; }
    .score-avatar-badge.low { background: #f64e60; color: #fff; }

    .status-pill {
        cursor: pointer;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: bold;
        font-size: 0.85rem;
        border: 1px solid transparent;
        transition: all 0.2s;
        text-transform: uppercase;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .status-present {
        background: #e8fff3;
        color: #50cd89;
        border-color: #50cd89;
    }

    .status-absent {
        background: #fff5f8;
        color: #f1416c;
        border-color: #f1416c;
    }

    .status-late {
        background: #fff8dd;
        color: #ffc700;
        border-color: #ffc700;
    }

    .status-license {
        background: #e1f0ff;
        color: #009ef7;
        border-color: #009ef7;
    }

    .status-license-periodo {
        background: #fff3e0;
        color: #e65100;
        border-color: #e65100;
    }

    .quick-action-btn {
        background: transparent;
        border: none;
        padding: 2px 6px;
        margin: 0 4px;
        font-size: 1.6rem;
        cursor: pointer;
        transition: transform 0.1s, filter 0.1s;
        filter: grayscale(0.2);
    }

    .quick-action-btn:hover {
        transform: scale(1.3);
        filter: grayscale(0);
    }

    .quick-action-btn:active {
        transform: scale(0.95);
    }

    @keyframes score-pop {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.3);
        }

        100% {
            transform: scale(1);
        }
    }

    .pop {
        animation: score-pop 0.3s ease-out;
    }

    /* Rings for visual feedback */
    .ring-1 {
        box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.4);
        border-radius: 50%;
        position: relative;
    }

    .ring-2 {
        box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.4), 0 0 0 6px rgba(54, 153, 255, 0.2);
        border-radius: 50%;
    }

    .ring-3 {
        box-shadow: 0 0 0 3px rgba(54, 153, 255, 0.4), 0 0 0 6px rgba(54, 153, 255, 0.2), 0 0 0 9px rgba(54, 153, 255, 0.1);
        border-radius: 50%;
    }

    .prev-attendance-indicator {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: block;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: absolute;
        top: -2px;
        right: -2px;
        z-index: 10;
    }

    .swal-wide {
        max-width: 700px !important;
        width: 95% !important;
    }
    .swal-wide .swal2-html-container {
        max-height: 75vh !important;
        overflow-y: auto;
    }
</style>

<div class="container-fluid">



    <!-- Floating Save / Header -->
    <div class="card card-custom gutter-b shadow-sm sticky-top" style="z-index: 90; top: 10px;">
        <div class="card-body p-4 d-flex align-items-center justify-content-between">
            <div>
                <h2 class="font-weight-bolder text-dark mb-1"><?= $curso ?></h2>
                <span class="text-muted font-weight-bolder font-size-lg">📅 <?= $date_display ?> |
                    <div class="d-inline-flex align-items-center bg-light-primary rounded px-4 py-2 ml-4">
                        <i class="la la-clock text-primary mr-2 icon-lg"></i>
                        <span class="text-primary font-weight-bolder">Periodo <?= $periodo ?></span>
                    </div>
                </span>
            </div>
            <div class="d-flex align-items-center">
                <!-- Stats Mini -->
                <div class="d-none d-md-flex mr-8 font-weight-bold">
                    <span class="mr-4 text-primary">Total: <span id="count-total"><?= count($students) ?></span></span>
                    <span class="mr-4 text-success">Presentes: <span id="count-present">0</span></span>
                    <span class="text-danger">Ausentes: <span id="count-absent">0</span></span>
                </div>

                <button type="button" onclick="submitAttendance()"
                    class="btn btn-primary font-weight-bolder btn-lg px-8 shadow">
                    <i class="fa fa-save"></i> GUARDAR CLASE
                </button>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card card-custom gutter-b shadow-sm">
        <div class="card-body py-2">
            <form action="<?= base_url() . '/teacher/attendance_save' ?>" method="POST" id="form_attendance_save">
                <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                <input type="hidden" name="section_id" value="<?= $section_id ?>">
                <input type="hidden" name="date_id" value="<?= $date_id ?>">
                <input type="hidden" name="periodos" value="<?= $periodo ?>">

                <!-- Licencias Activas -->
                <div>
                    <?php if (!empty($licencias) || !empty($licencias_periodo)): ?>
                        <p class="mb-2"><strong>Licencias Activas:</strong></p>
                        <div class="mb-4">
                            <?php if (!empty($licencias)): ?>
                                <?php foreach ($licencias as $lic): ?>
                                    <span class="label label-danger label-pill label-inline mr-2 py-3 px-3 mb-1" title="Licencia (Todo el día)">
                                        <?= $lic['student'] ?> - <?= $lic['detalle'] ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if (!empty($licencias_periodo)): ?>
                                <?php foreach ($licencias_periodo as $lic): ?>
                                    <span class="label label-warning label-pill label-inline mr-2 py-3 px-3 mb-1" title="Licencia (Solo este periodo)">
                                        <?= $lic['student'] ?> - <?= $lic['detalle'] ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center gamified-table"
                        id="kt_advance_table_widget_1">
                        <thead>
                            <tr class="text-left">
                                <th style="min-width: 250px" class="pl-4">ESTUDIANTE</th>
                                <th style="min-width: 140px" class="text-center">ASISTENCIA</th>
                                <th style="min-width: 350px">REGISTRO RÁPIDO</th>
                                <th class="text-right pr-4">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <?php
                                $scoreClass = 'high';
                                if ($student['daily_score'] < 50)
                                    $scoreClass = 'low';
                                elseif ($student['daily_score'] < 80)
                                    $scoreClass = 'mid';

                                $statusLabel = 'Presente';
                                $statusClass = 'status-present';
                                $statusCode = 1;
                                if ($student['attendance_status'] == 0) {
                                    $statusLabel = 'Ausente';
                                    $statusClass = 'status-absent';
                                    $statusCode = 0;
                                } elseif ($student['attendance_status'] == 3) {
                                    $statusLabel = 'Retraso';
                                    $statusClass = 'status-late';
                                    $statusCode = 3;
                                } elseif ($student['attendance_status'] == 2) {
                                    $statusLabel = 'Licencia';
                                    $statusClass = 'status-license';
                                    $statusCode = 2;
                                }
                                foreach ($licencias as $lic): 
                                    if ($student['student_id']==$lic['student_id']) {
                                        $statusLabel = 'Licencia';
                                        $statusClass = 'status-license';
                                        $statusCode = 2;
                                        break; // 👈 sale del foreach
                                    }
                                 endforeach; 
                                 
                                 if (isset($licencias_periodo)) {
                                     foreach ($licencias_periodo as $lic):
                                         if ($student['student_id']==$lic['student_id']) {
                                             $statusLabel = 'Licencia';
                                             $statusClass = 'status-license-periodo';
                                             $statusCode = 2;
                                             break; // 👈 sale del foreach
                                         }
                                      endforeach;
                                 }
                                ?>
                                <tr id="row-<?= $student['student_id'] ?>" style="height: 80px;">
                                    <td class="pl-4">
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="symbol symbol-50 symbol-circle symbol-light-primary mr-4 position-relative">
                                                <?php
                                                // Previous Attendance Indicator Logic
                                                $prevStatus = isset($prev_attendance[$student['student_id']]) ? $prev_attendance[$student['student_id']] : -1;
                                                $indColor = '#E0E0E0'; // Gray (No info)
                                                $indTitle = 'Sin registro previo hoy';

                                                if ($prevStatus == 1 || $prevStatus == 3 || $prevStatus == 4) {
                                                    $indColor = '#FFC700'; // Yellow (Presente/Retraso/Virtual)
                                                    $indTitle = 'Presente en clase anterior';
                                                } elseif ($prevStatus === '0' || $prevStatus === 0 || $prevStatus == 2) {
                                                    $indColor = '#F64E60'; // Red (Ausente/Licencia)
                                                    $indTitle = ($prevStatus == 2) ? 'Licencia en clase anterior' : 'Ausente en clase anterior';
                                                }
                                                ?>
                                                <div class="prev-attendance-indicator"
                                                    style="background-color: <?= $indColor ?>;" title="<?= $indTitle ?>"
                                                    data-toggle="tooltip"></div>
                                                <span class="symbol-label font-size-h4 font-weight-bold">
                                                    <?= substr($student['student'], 0, 1) ?>
                                                </span>
                                                <!-- Score Badge on Avatar -->
                                                <div class="score-avatar-badge <?= $scoreClass ?>" id="badge-<?= $student['student_id'] ?>">
                                                    <?= $student['daily_score'] ?>
                                                </div>
                                            </div>
                                            <div>
                                                <!-- Link to Profile -->
                                                <!-- Link to Profile -->
                                                <a href="<?= base_url('teacher/student_profile/' . $student['student_id'] . '/' . $subject_id) . '?date=' . $date . '&periodo=' . $periodo ?>"
                                                    target="_blank" class="text-dark-75 font-weight-bolder text-hover-primary mb-1 font-size-lg"><?= $student['student'] ?></a>
                                                <span class="text-muted font-weight-bold d-block font-size-sm mt-1">
                                                    <span class="text-danger">
                                                        <span id="negative-count-<?= $student['student_id'] ?>"><?= isset($student['negative_count']) ? $student['negative_count'] : 0 ?></span> Incidencias
                                                    </span>
                                                    <span class="mx-1 text-muted">|</span>
                                                    <span class="text-success">
                                                        <span id="positive-count-<?= $student['student_id'] ?>"><?= isset($student['positive_count']) ? $student['positive_count'] : 0 ?></span>
                                                        <i class="flaticon-star text-success icon-sm mb-1 ml-1"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <input type="hidden" id="input-status-<?= $student['student_id'] ?>"
                                            name="check_<?= $student['student_id'] ?>" value="<?= $statusCode ?>">
                                    </td>
                                    <!--  
                                    <td class="text-center">
                                        
                                        <span class="status-pill <?= $statusClass ?>"
                                            id="status-<?= $student['student_id'] ?>"
                                            onclick="toggleStatus(<?= $student['student_id'] ?>)">
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>                                    
                                    -->
                                    <td class="text-center">
                                        <?php
                                        $bloquearClick = false;

                                        foreach ($licencias as $lic) {
                                            if ($student['student_id'] == $lic['student_id']) {
                                                $statusLabel = 'Licencia';
                                                $statusClass = 'status-license';
                                                $statusCode  = 2;
                                                $bloquearClick = true;
                                                break;
                                            }
                                        }

                                        if (isset($licencias_periodo)) {
                                            foreach ($licencias_periodo as $lic) {
                                                if ($student['student_id'] == $lic['student_id']) {
                                                    $statusLabel = 'Licencia';
                                                    $statusClass = 'status-license-periodo';
                                                    $statusCode  = 2;
                                                    $bloquearClick = true;
                                                    break;
                                                }
                                            }
                                        }
                                        ?>

                                        <span class="status-pill <?= $statusClass ?>"
                                            id="status-<?= $student['student_id'] ?>"
                                            <?= !$bloquearClick ? "onclick=\"toggleStatus({$student['student_id']})\"" : '' ?>>
                                            <?= $statusLabel ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (isset($behaviors['negative'])): ?>
                                                <?php foreach ($behaviors['negative'] as $b): ?>
                                                    <?php if (stripos($b['name'], 'Otro') !== false || stripos($b['name'], 'Other') !== false || stripos($b['icon'], 'Otro') !== false): ?>
                                                        <button type="button" class="quick-action-btn" title="<?= $b['name'] ?>"
                                                            onclick="openRegisterModal(<?= $student['student_id'] ?>, '<?= addslashes($student['student']) ?>')">
                                                            <?= $b['icon'] ?>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="quick-action-btn"
                                                            id="btn-behavior-<?= $student['student_id'] ?>-<?= $b['id'] ?>"
                                                            title="<?= $b['name'] ?> (<?= $b['points'] ?>)"
                                                            onclick="registerBehavior(<?= $student['student_id'] ?>, <?= $b['id'] ?>, <?= $b['points'] ?>)">
                                                            <?= $b['icon'] ?>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <div style="border-left: 2px solid #eee; height: 30px; margin: 0 10px;">
                                            </div>

                                            <?php if (isset($behaviors['positive'])): ?>
                                                <?php foreach ($behaviors['positive'] as $b): ?>
                                                    <?php if (stripos($b['name'], 'Otro') !== false || stripos($b['name'], 'Other') !== false || stripos($b['icon'], 'Otro') !== false): ?>
                                                        <button type="button" class="quick-action-btn" title="<?= $b['name'] ?>"
                                                            onclick="openRegisterModal(<?= $student['student_id'] ?>, '<?= addslashes($student['student']) ?>')">
                                                            <?= $b['icon'] ?>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="quick-action-btn"
                                                            id="btn-behavior-<?= $student['student_id'] ?>-<?= $b['id'] ?>"
                                                            title="<?= $b['name'] ?> (+<?= $b['points'] ?>)"
                                                            onclick="registerBehavior(<?= $student['student_id'] ?>, <?= $b['id'] ?>, <?= $b['points'] ?>)">
                                                            <?= $b['icon'] ?>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            <!-- Neutral Behaviors (Nurse/Bathroom) -->
                                            <div style="border-left: 2px solid #eee; height: 30px; margin: 0 10px;"></div>
                                            <?php if (isset($behaviors['neutral'])): ?>
                                                <?php foreach ($behaviors['neutral'] as $b): ?>
                                                    <button type="button" class="quick-action-btn"
                                                        id="btn-behavior-<?= $student['student_id'] ?>-<?= $b['id'] ?>"
                                                        title="<?= $b['name'] ?>"
                                                        onclick="registerBehavior(<?= $student['student_id'] ?>, <?= $b['id'] ?>, <?= $b['points'] ?>)">
                                                        <?= $b['icon'] ?>
                                                    </button>
                                                <?php endforeach; ?>
                                            <?php endif; ?>




                                        </div>
                                    </td>
                                    <td class="text-right pr-4">
                                        <a href="javascript:;" onclick="viewDetails(<?= $student['student_id'] ?>)"
                                            class="font-weight-bolder text-primary small ml-1">Detalles 📄</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detalles -->
<div class="modal fade" id="modalDetails" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="modalDetailsTitle">Incidencias de la Clase</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalDetailsContent">
                    <div class="spinner spinner-primary spinner-lg text-center mx-auto my-5"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar Incidencia -->
<div class="modal fade" id="modalRegister" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Registrar Incidencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-5">
                    <div class="symbol symbol-50 symbol-light-primary mr-3">
                        <span class="symbol-label font-size-h2 font-weight-bold" id="modal-reg-avatar">A</span>
                    </div>
                    <div>
                        <span class="text-dark-75 font-weight-bold font-size-h5" id="modal-reg-name">Student Name</span>
                        <div class="text-muted font-size-sm">Seleccione el tipo de comportamiento</div>
                    </div>
                </div>

                <input type="hidden" id="reg-student-id">
                <input type="hidden" id="reg-behavior-id">
                <input type="hidden" id="reg-points">

                <h6 class="font-weight-bold text-dark mb-3">Comportamiento Negativo</h6>
                <div class="row mb-5">
                    <?php if (isset($behaviors['negative'])): ?>
                        <?php foreach ($behaviors['negative'] as $b): ?>
                            <div class="col-6 col-md-4 mb-3">
                                <button type="button"
                                    class="btn btn-outline-secondary btn-block p-4 text-left d-flex align-items-center behavior-select-btn"
                                    onclick="selectBehaviorType(this, <?= $b['id'] ?>, <?= $b['points'] ?>)">
                                    <span class="font-size-h2 mr-3"><?= $b['icon'] ?></span>
                                    <span class="font-weight-bold"><?= $b['name'] ?></span>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <h6 class="font-weight-bold text-dark mb-3">Comportamiento Positivo</h6>
                <div class="row mb-5">
                    <?php if (isset($behaviors['positive'])): ?>
                        <?php foreach ($behaviors['positive'] as $b): ?>
                            <div class="col-6 col-md-4 mb-3">
                                <button type="button"
                                    class="btn btn-outline-secondary btn-block p-4 text-left d-flex align-items-center behavior-select-btn"
                                    onclick="selectBehaviorType(this, <?= $b['id'] ?>, <?= $b['points'] ?>)">
                                    <span class="font-size-h2 mr-3"><?= $b['icon'] ?></span>
                                    <span class="font-weight-bold"><?= $b['name'] ?></span>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Observaciones (Opcional)</label>
                    <textarea class="form-control" id="reg-observation" rows="3"
                        placeholder="Detalles adicionales sobre el incidente..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary font-weight-bold" onclick="submitRegisterModal()">Guardar
                    Registro</button>
            </div>
        </div>
    </div>
</div>

<script>
    const SUBJECT_ID = "<?= isset($subject_id) ? $subject_id : '' ?>";
    const DATE_ID = "<?= isset($date_id) ? $date_id : '' ?>";
    const PERIOD = "<?= isset($periodo) ? $periodo : '' ?>";
    const BASE_URL = "<?= base_url() ?>";
    let hasUnsavedChanges = false;

    window.addEventListener('beforeunload', function (e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });


    function submitAttendance() {
        if (!PERIOD || PERIOD === '0' || PERIOD === '') {
            alert("¡Debe seleccionar un periodo para guardar la asistencia!");
            return;
        }
        hasUnsavedChanges = false;
        $('#form_attendance_save').submit();
    }



    function updateStats() {
        let present = 0, absent = 0, total = 0;
        document.querySelectorAll('[id^="status-"]').forEach(el => {
            const txt = el.innerText.toUpperCase();
            if (txt.includes('PRESENTE')) present++;
            if (txt.includes('AUSENTE')) absent++;
            total++;
        });
        if (document.getElementById('count-present')) document.getElementById('count-present').innerText = present;
        if (document.getElementById('count-absent')) document.getElementById('count-absent').innerText = absent;
        if (document.getElementById('count-total')) document.getElementById('count-total').innerText = total;
    }

    function toggleStatus(studentId) {
        const el = document.getElementById('status-' + studentId);
        let current = el.innerText.trim().toUpperCase();
        let newStatus = 1;

        if (current === 'PRESENTE') newStatus = 0;
        else if (current === 'LICENCIA') newStatus = 2;
        else if (current === 'AUSENTE') newStatus = 3;
        else newStatus = 1;

        updateStatusUI(studentId, newStatus);
        const input = document.getElementById('input-status-' + studentId);
        if (input) input.value = newStatus;

        hasUnsavedChanges = true;

        $.post(BASE_URL + "/teacher/update_attendance_ajax", {
            student_id: studentId, status: newStatus, subject_id: SUBJECT_ID, date_id: DATE_ID
        });
    }

    function updateStatusUI(studentId, status) {
        const el = document.getElementById('status-' + studentId);
        el.classList.remove('status-present', 'status-absent', 'status-late', 'status-license');
        if (status == 1) { el.innerText = 'Presente'; el.classList.add('status-present'); }
        else if (status == 0) { el.innerText = 'Ausente'; el.classList.add('status-absent'); }
        else if (status == 3) { el.innerText = 'Retraso'; el.classList.add('status-late'); }
        else { el.innerText = 'Licencia'; el.classList.add('status-license'); }
        updateStats();
    }

    function registerBehavior(studentId, behaviorId, points) {
        if (!PERIOD || PERIOD === '0' || PERIOD === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Falta Periodo',
                text: '¡Seleccione un periodo primero!',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // --- Star (behavior_id=2): Show Attribute Selection ---
        if (behaviorId === 2) {
            const starAttributes = [
                { name: 'Razonamiento', desc: 'Deberes escolares cumplidos de forma sobresaliente.', icon: '🧠', color: '#6993FF' },
                { name: 'Indagación', desc: 'Esfuerzo notable en mejorar el rendimiento o autonomía al aprender.', icon: '🔍', color: '#1BC5BD' },
                { name: 'Audacia', desc: 'Liderazgo en el curso o resiliencia ante desafíos complejos.', icon: '🦁', color: '#F64E60' },
                { name: 'Solidaridad', desc: 'Actos de sinceridad, bondad, empatía o apoyo a compañeros.', icon: '🤝', color: '#FFA800' },
                { name: 'Reflexión', desc: 'Admitir responsabilidad y demostrar un cambio real de actitud.', icon: '🪞', color: '#8950FC' },
                { name: 'Integridad', desc: 'Actuación con honradez.', icon: '⚖️', color: '#3699FF' },
                { name: 'Comunicación', desc: 'Trabajo en equipo efectivo y escucha respetuosa.', icon: '💬', color: '#50CD89' },
                { name: 'Otro', desc: 'Especificar motivo de la estrella.', icon: '📝', color: '#7E8299' }
            ];

            let htmlOptions = '<div style="display: grid; gap: 10px;">';
            starAttributes.forEach((attr, idx) => {
                htmlOptions += `
                    <div class="star-attr-option" data-idx="${idx}" 
                         style="display: flex; align-items: center; padding: 12px 16px; border-radius: 10px; cursor: pointer; border: 2px solid #eee; transition: all 0.2s; background: #fff;"
                         onmouseover="this.style.borderColor='${attr.color}'; this.style.background='${attr.color}10';"
                         onmouseout="if(!this.classList.contains('selected')){this.style.borderColor='#eee'; this.style.background='#fff';}">
                        <span style="font-size: 1.8rem; margin-right: 14px;">${attr.icon}</span>
                        <div style="text-align: left; flex: 1;">
                            <div style="font-weight: 700; font-size: 1rem; color: ${attr.color};">⭐ ${attr.name}</div>
                            <div style="font-size: 0.82rem; color: #7E8299; margin-top: 2px;">${attr.desc}</div>
                        </div>
                    </div>`;
            });
            htmlOptions += '</div>';

            Swal.fire({
                title: '⭐ Seleccione el Atributo Premiado',
                html: htmlOptions,
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'swal-wide',
                    cancelButton: 'btn btn-light'
                },
                buttonsStyling: false,
                didOpen: () => {
                    document.querySelectorAll('.star-attr-option').forEach(opt => {
                        opt.addEventListener('click', function() {
                            const idx = parseInt(this.dataset.idx);
                            const attr = starAttributes[idx];
                            Swal.close();

                            // For "Otro" → require text; for others → optional
                            const isOtro = (attr.name === 'Otro');
                            Swal.fire({
                                title: `⭐ ${attr.name}`,
                                html: `<p style="color:#7E8299; font-size:0.9rem; margin-bottom:12px;">${attr.desc}</p>
                                       <textarea id="swal-star-obs" class="swal2-textarea" rows="3" 
                                           placeholder="${isOtro ? 'Escriba el motivo de la estrella...' : 'Observación adicional (opcional)...'}"
                                           style="width:100%; margin:0;"></textarea>`,
                                showCancelButton: true,
                                confirmButtonText: '<i class="fa fa-save mr-2"></i> Guardar Estrella',
                                cancelButtonText: 'Cancelar',
                                customClass: {
                                    confirmButton: 'btn btn-primary',
                                    cancelButton: 'btn btn-light'
                                },
                                buttonsStyling: false,
                                preConfirm: () => {
                                    const obs = document.getElementById('swal-star-obs').value.trim();
                                    if (isOtro && !obs) {
                                        Swal.showValidationMessage('Debe escribir el motivo para "Otro"');
                                        return false;
                                    }
                                    return obs;
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const extraObs = result.value || '';
                                    let observation;
                                    if (isOtro) {
                                        observation = `Atributo: Otro — ${extraObs}`;
                                    } else {
                                        observation = `Atributo: ${attr.name}` + (extraObs ? ` — ${extraObs}` : '');
                                    }

                                    const btnId = 'btn-behavior-' + studentId + '-' + behaviorId;
                                    const btn = document.getElementById(btnId);
                                    executeRegisterBehavior(studentId, behaviorId, points, observation, btn);

                                    Swal.fire({
                                        icon: 'success',
                                        title: `⭐ ${attr.name}`,
                                        text: 'Estrella registrada correctamente',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }
                            });
                        });
                    });
                }
            });
            return;
        }

        // --- Normal behaviors (non-star) ---
        // Get Behavior Name for the title
        const btnId = 'btn-behavior-' + studentId + '-' + behaviorId;
        const btn = document.getElementById(btnId);
        const behaviorName = btn ? btn.getAttribute('title').split(' (')[0] : 'Incidencia';

        Swal.fire({
            title: `Registrar: ${behaviorName}`,
            text: '¿Deseas añadir algún detalle u observación?',
            input: 'textarea',
            inputPlaceholder: 'Escribe aquí el detalle (opcional)...',
            showCancelButton: true,
            confirmButtonText: '<i class="fa fa-save mr-2"></i> Guardar Registro',
            cancelButtonText: 'Cancelar',
            inputAttributes: {
                'autocapitalize': 'off',
                'autocorrect': 'off'
            },
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-light'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                const observation = result.value || '';
                executeRegisterBehavior(studentId, behaviorId, points, observation, btn);
            }
        });
    }

    function executeRegisterBehavior(studentId, behaviorId, points, observation, btn) {
        // Visual Feedback (Rings)
        if (btn) {
            let clicks = parseInt(btn.getAttribute('data-clicks') || 0) + 1;
            if (clicks > 3) clicks = 1;
            btn.setAttribute('data-clicks', clicks);
            btn.classList.remove('ring-1', 'ring-2', 'ring-3');
            btn.classList.add('ring-' + clicks);
        }

        const badge = document.getElementById('badge-' + studentId);
        let currentScore = parseInt(badge.innerText) || 100;
        let predictedScore = Math.max(0, currentScore + points);

        updateBadgeUI(badge, predictedScore);
        badge.classList.add('pop');
        setTimeout(() => badge.classList.remove('pop'), 300);

        hasUnsavedChanges = true;

        $.post(BASE_URL + "/teacher/register_behavior", {
            student_id: studentId,
            behavior_id: behaviorId,
            points: points,
            subject_id: SUBJECT_ID,
            date_id: DATE_ID,
            period: PERIOD,
            observation: observation
        }, function (data) {
            if (data.status === 'success') {
                updateBadgeUI(badge, data.new_score);
                
                // Update incident counts visually
                let negElem = document.getElementById('negative-count-' + studentId);
                if (negElem && data.new_negative_count !== undefined) {
                    negElem.innerText = data.new_negative_count;
                }
                let posElem = document.getElementById('positive-count-' + studentId);
                if (posElem && data.new_positive_count !== undefined) {
                    posElem.innerText = data.new_positive_count;
                }
            } else {
                Swal.fire('Error', data.message || "Error desconocido", 'error');
            }
        }, 'json').fail(function(xhr, status, error) {
            console.error("Behavior Register AJAX Fail:", status, error, xhr.responseText);
            Swal.fire('Error de Conexión', 'No se pudo conectar con el servidor.', 'error');
        });
    }

    function updateBadgeUI(badgeElement, score) {
        badgeElement.innerText = score;
        badgeElement.className = 'score-avatar-badge';
        if (score < 50) badgeElement.classList.add('low');
        else if (score < 80) badgeElement.classList.add('mid');
        else badgeElement.classList.add('high');
    }

    function viewDetails(studentId) {
        // Get Name from DOM - Try multiple selectors in case class names vary
        let studentName = 'Estudiante';
        let rowElem = document.getElementById('row-' + studentId);
        if (rowElem) {
            let nameLink = rowElem.querySelector('a.text-dark-75') || rowElem.querySelector('a.text-hover-primary') || rowElem.querySelector('a');
            if (nameLink) studentName = nameLink.innerText.trim();
        }

        $('#modalDetailsTitle').text('Incidencias de ' + studentName);
        $('#modalDetailsContent').html('<div class="d-flex justify-content-center my-4"><div class="spinner spinner-primary spinner-lg"></div></div>');
        $('#modalDetails').modal('show');

        // Diagnostic Console Log
        console.log("Fetching logs for Student:", studentId, "DateID:", (typeof DATE_ID !== 'undefined' ? DATE_ID : 'MISSING'), "SubjectID:", (typeof SUBJECT_ID !== 'undefined' ? SUBJECT_ID : 'MISSING'));

        if (typeof DATE_ID === 'undefined' || typeof SUBJECT_ID === 'undefined') {
            $('#modalDetailsContent').html('<div class="alert alert-warning">Error: Metadatos de sesión (Fecha/Materia) no encontrados. Recargue la página.</div>');
            return;
        }

        // Fetch Logs
        $.post(BASE_URL + "/teacher/get_daily_log_ajax", {
            student_id: studentId,
            date_id: DATE_ID,
            subject_id: SUBJECT_ID
        }, function (data) {
            console.log("Logs received:", data);
            
            if (!Array.isArray(data)) {
                console.error("Invalid data format received:", data);
                $('#modalDetailsContent').html('<div class="alert alert-danger">Error: Formato de datos inválido.</div>');
                return;
            }

            if (data.length === 0) {
                $('#modalDetailsContent').html('<div class="text-center text-muted p-5">Sin incidencias registradas hoy.</div>');
            } else {
                let html = '<div class="table-responsive"><table class="table table-borderless table-vertical-center">';
                data.forEach(log => {
                    let color = log.points >= 0 ? 'text-success' : 'text-danger';
                    let sign = log.points >= 0 ? '+' : '';
                    let time = '-';
                    try {
                        if (log.created_at) {
                            time = log.created_at.split(' ')[1].substring(0, 5);
                        }
                    } catch (e) { console.error("Error parsing date", e); }

                    html += `<tr id="modal-log-row-${log.id}">
                        <td>
                            <span class="text-dark-75 font-weight-bold d-block">
                                <span class="symbol-label font-size-h4 mr-2">${log.icon || ''}</span> ${log.name || 'Sin nombre'}
                            </span>
                            <span class="text-muted font-size-sm">${time}</span>
                        </td>
                        <td class="text-right">
                            <span class="font-weight-bolder font-size-lg ${color}">${sign}${log.points || 0} pts</span>
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn btn-sm btn-light-danger font-weight-bold" onclick="deleteBehavior(${log.id})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
                html += '</table></div>';
                $('#modalDetailsContent').html(html);
            }
        }, 'json').fail(function (xhr, status, error) {
            console.error("AJAX Error Details:", {
                status: status,
                error: error,
                response: xhr.responseText,
                readyState: xhr.readyState
            });
            alert("Error de conexión al cargar datos.");
        });
    }

    function deleteBehavior(logId) {
        if (!confirm('¿Estás seguro de que deseas eliminar este registro?')) return;

        hasUnsavedChanges = true;

        $.post(BASE_URL + "/teacher/delete_behavior_ajax", {
            log_id: logId
        }, function (response) {
            if (response.status === 'success') {
                // Remove row from modal
                $('#modal-log-row-' + logId).fadeOut(300, function () { 
                    $(this).remove(); 
                    if ($('#modalDetailsContent table tbody tr').length === 0) {
                        $('#modalDetailsContent').html('<div class="text-center text-muted p-5">Sin incidencias registradas hoy.</div>');
                    }
                });

                // Update main table badge and counts
                if (response.student_id && response.new_score !== undefined) {
                    const badge = document.getElementById('badge-' + response.student_id);
                    if (badge) {
                        updateBadgeUI(badge, response.new_score);
                        badge.classList.add('pop');
                        setTimeout(() => badge.classList.remove('pop'), 300);
                    }
                    
                    let negElem = document.getElementById('negative-count-' + response.student_id);
                    if (negElem && response.new_negative_count !== undefined) {
                        negElem.innerText = response.new_negative_count;
                    }
                    let posElem = document.getElementById('positive-count-' + response.student_id);
                    if (posElem && response.new_positive_count !== undefined) {
                        posElem.innerText = response.new_positive_count;
                    }
                }
            } else {
                alert('Error al eliminar: ' + (response.message || 'Error desconocido'));
            }
        }, 'json');
    }


    // --- Register Modal Logic ---
    function openRegisterModal(studentId, studentName) {
        $('#reg-student-id').val(studentId);
        $('#modal-reg-name').text(studentName);
        $('#modal-reg-avatar').text(studentName.charAt(0));
        $('#reg-behavior-id').val('');
        $('#reg-points').val('');
        $('#reg-observation').val('');

        // Reset selection visual
        $('.behavior-select-btn').removeClass('active btn-primary text-white').addClass('btn-outline-secondary');

        $('#modalRegister').modal('show');
    }

    function selectBehaviorType(btn, id, points) {
        $('.behavior-select-btn').removeClass('active btn-primary text-white').addClass('btn-outline-secondary');
        $(btn).removeClass('btn-outline-secondary').addClass('active btn-primary text-white');

        $('#reg-behavior-id').val(id);
        $('#reg-points').val(points);
    }

    function submitRegisterModal() {
        const studentId = $('#reg-student-id').val();
        const behaviorId = $('#reg-behavior-id').val();
        const points = $('#reg-points').val();
        const observation = $('#reg-observation').val();

        if (!behaviorId) {
            alert("Por favor seleccione un tipo de comportamiento.");
            return;
        }

        if (!PERIOD || PERIOD === '0' || PERIOD === '') {
            alert("¡Seleccione un periodo primero!");
            return;
        }

        hasUnsavedChanges = true;

        $.post(BASE_URL + "/teacher/register_behavior", {
            student_id: studentId,
            behavior_id: behaviorId,
            points: points,
            subject_id: SUBJECT_ID,
            date_id: DATE_ID,
            period: PERIOD,
            observation: observation
        }, function (data) {
            if (data.status === 'success') {
                // Update Badge
                const badge = document.getElementById('badge-' + studentId);
                updateBadgeUI(badge, data.new_score);

                // Update incident counts visually
                let negElem = document.getElementById('negative-count-' + studentId);
                if (negElem && data.new_negative_count !== undefined) {
                    negElem.innerText = data.new_negative_count;
                }
                let posElem = document.getElementById('positive-count-' + studentId);
                if (posElem && data.new_positive_count !== undefined) {
                    posElem.innerText = data.new_positive_count;
                }

                // Close modal
                $('#modalRegister').modal('hide');
            } else {
                alert("Error al registrar incidencia: " + (data.message || "Error desconocido"));
            }
        }, 'json').fail(function(xhr, status, error) {
            console.error("Modal Register AJAX Fail:", status, error, xhr.responseText);
            alert("Error de conexión al registrar incidencia.");
        });
    }


    // --- Pulse & Auto-Save Logic ---
    // 1. Keep-Alive (Heartbeat) every 5 minutes 
    setInterval(function() {
        $.get(BASE_URL + "/teacher/session_keep_alive", function(data) {
            console.log("Pulse:", data.status);
        });
    }, 5 * 60 * 1000);

    // 2. Auto-Save every 15 minutes (always, even if no unsaved changes)
    setInterval(function() {
        console.log("Auto-saving attendance...");
        const formData = $('#form_attendance_save').serialize();
        $.post(BASE_URL + "/teacher/attendance_auto_save_ajax", formData, function(data) {
            if (data.status === 'success') {
                hasUnsavedChanges = false;
                console.log("Auto-save successful at", data.time || new Date().toLocaleTimeString());
            }
        }, 'json');
    }, 15 * 60 * 1000);

    // Init stats
    updateStats();
</script>