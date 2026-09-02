<style>
.cupo-bar-bg   { background:#e0e0e0; border-radius:6px; height:12px; overflow:hidden; margin:6px 0; }
.cupo-bar-fill { height:12px; border-radius:6px; transition:width .4s; }
.cupo-ok   { background:#50cd89; }
.cupo-warn { background:#ffa800; }
.cupo-full { background:#f1416c; }
.child-tab { border-radius:20px; padding:7px 20px; font-weight:700; font-size:.88rem;
             border:2px solid #e0e0e0; background:#fff; cursor:pointer; transition:all .2s; margin-right:8px; }
.child-tab.active   { border-color:#6f42c1; background:#6f42c1; color:#fff; }
.child-tab:not(.active):hover { border-color:#6f42c1; color:#6f42c1; }
.est-pill  { padding:3px 12px; border-radius:12px; font-size:.78rem; font-weight:700; }
.ep-pend   { background:#fff8dd; color:#ffa800; }
.ep-apro   { background:#e8fff3; color:#50cd89; }
.ep-rech   { background:#fff0f2; color:#f1416c; }
.ep-elim   { background:#f0f0f0; color:#7e8299; }
.page-tab  { border-radius:10px; padding:10px 22px; font-weight:700; font-size:.9rem;
             border:2px solid #e0e0e0; background:#fff; color:#7e8299; text-decoration:none;
             cursor:pointer; transition:all .2s; display:inline-block; }
.page-tab:hover { text-decoration:none; }
.page-tab.pt-lic.active { border-color:#6f42c1; background:#6f42c1; color:#fff; }
.page-tab.pt-lic:not(.active):hover { border-color:#6f42c1; color:#6f42c1; }
.page-tab.pt-rec.active { border-color:#e65100; background:#e65100; color:#fff; }
.page-tab.pt-rec:not(.active):hover { border-color:#e65100; color:#e65100; }
.tipo-opt  { border:2px solid #e0e0e0; border-radius:10px; padding:14px 16px; cursor:pointer;
             transition:all .2s; display:block; }
.tipo-opt:hover { border-color:#e65100; }
.tipo-opt input { margin-right:8px; }
.tipo-opt.checked { border-color:#e65100; background:#fff8f0; }
</style>

<div class="container-fluid pb-8">

    <!--
        Flash messages: se muestran como modal (ver modalInfo al final de la página).
        Ojo: estas claves se guardan con session()->set() (no setFlashdata()), y el
        layout global (includes_bottom.php) también las lee para mostrarlas como
        toastr — por eso las leemos con get() y las removemos aquí mismo, así el
        layout global ya no encuentra nada que mostrar y no se duplica el aviso.
    -->
    <?php $s = session(); ?>
    <?php $flashOk  = $s->get('flash_message'); ?>
    <?php $flashErr = $s->get('flash_message_error'); ?>
    <?php if ($flashOk)  $s->remove('flash_message'); ?>
    <?php if ($flashErr) $s->remove('flash_message_error'); ?>

    <!-- Header -->
    <div class="card card-custom gutter-b shadow-sm" style="background:linear-gradient(135deg,#6f42c1,#4b2e9e); border-radius:12px;">
        <div class="card-body p-5 d-flex align-items-center">
            <div>
                <h3 class="text-white font-weight-bolder mb-1">📄 Licencias Primaria</h3>
                <span class="text-white opacity-75 font-size-sm">
                    Trimestre: <strong><?= esc($phase_name) ?></strong>
                </span>
            </div>
            <div class="ml-auto">
                <a href="<?= base_url('parents/dashboard') ?>" class="btn btn-light font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Navegación entre secciones de Primaria -->
    <div class="d-flex flex-wrap mb-6" style="gap:10px;">
        <a href="javascript:void(0)" class="page-tab pt-lic" id="tab_dia" onclick="toggleForm('dia')">
            <i class="fas fa-calendar-plus mr-1"></i> Solicitar por Días
        </a>
        <a href="javascript:void(0)" class="page-tab pt-lic" id="tab_sal" onclick="toggleForm('sal')">
            <i class="fas fa-calendar-alt mr-1"></i> Licencia por Periodos
        </a>
        <a href="javascript:void(0)" class="page-tab pt-rec" id="tab_rec" onclick="toggleForm('rec')">
            <i class="fas fa-bus mr-1"></i> Cambio de Recojo
        </a>
    </div>

    <!-- Reglamento de licencias primaria -->
    <div class="card card-custom gutter-b shadow-sm">
        <div class="card-header border-0 pt-5 pb-0" style="cursor:pointer;" data-toggle="collapse" data-target="#collapseReglamento" aria-expanded="false">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label font-weight-bolder text-dark">
                    <i class="fas fa-balance-scale text-primary mr-2"></i>Reglamento de Licencias — Aspectos clave
                </span>
                <span class="text-muted mt-1 font-weight-bold font-size-sm">Haz clic para ver / ocultar</span>
            </h3>
            <div class="card-toolbar">
                <i class="fas fa-chevron-down text-muted"></i>
            </div>
        </div>
        <div id="collapseReglamento" class="collapse">
            <div class="card-body pt-4 pb-5">

                <!-- Cupo trimestral -->
                <div class="row mb-5">
                    <div class="col-12 mb-3">
                        <span class="font-weight-bolder text-dark font-size-base">
                            <i class="fas fa-calendar-alt text-primary mr-2"></i>Cupo Trimestral
                        </span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bg-light-primary rounded p-4 text-center h-100">
                            <div class="font-size-h2 font-weight-bolder text-primary">9</div>
                            <div class="font-size-sm font-weight-bold text-dark-75">días máx. por trimestre</div>
                            <div class="text-muted font-size-xs mt-1">justificada o no, toda ausencia cuenta (excepto Excepciones)</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bg-light-warning rounded p-4 text-center h-100">
                            <div class="font-size-h2 font-weight-bolder text-warning">6</div>
                            <div class="font-size-sm font-weight-bold text-dark-75">días → Alerta</div>
                            <div class="text-muted font-size-xs mt-1">Coordinación de reunión preventiva</div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="bg-light-danger rounded p-4 text-center h-100">
                            <div class="font-size-h2 font-weight-bolder text-danger">9+</div>
                            <div class="font-size-sm font-weight-bold text-dark-75">días → Límite</div>
                            <div class="text-muted font-size-xs mt-1">Ya no se pueden solicitar más licencias este trimestre; pierde el derecho a recuperar evaluaciones por ausencias adicionales</div>
                        </div>
                    </div>
                </div>

                <div class="row font-size-sm text-dark-75">

                    <!-- Licencias por periodos -->
                    <div class="col-md-6 mb-4">
                        <div class="font-weight-bolder mb-2"><i class="fas fa-door-open text-warning mr-1"></i> Licencias por Periodos</div>
                        <ul class="pl-4 mb-0">
                            <li class="mb-2">Hasta <strong>2 horas</strong> de licencia por periodos = <strong>½ día</strong> de cupo</li>
                            <li class="mb-2">Más de <strong>2 horas</strong> de licencia por periodos = <strong>1 día completo</strong> de cupo</li>
                            <li class="mb-2">Si son para el mismo día, deben solicitarse en el SAAT hasta las <strong><?= date('g:i a', strtotime((new \Config\PrimReglas())->horaCierre)) ?></strong> (mismo horario que licencias por día)</li>
                            <li class="mb-2">El padre, madre o tutor debe <strong>recoger personalmente</strong> al estudiante</li>
                        </ul>
                    </div>

                    <!-- Excepciones -->
                    <div class="col-md-6 mb-4">
                        <div class="font-weight-bolder mb-2"><i class="fas fa-shield-alt text-success mr-1"></i> Excepciones (NO consumen cupo)</div>
                        <ul class="pl-4 mb-0">
                            <li class="mb-1">Internación hospitalaria <span class="text-muted">(con certificado)</span></li>
                            <li class="mb-1">Accidente grave <span class="text-muted">(con informe médico)</span></li>
                            <li class="mb-1">Fallecimiento de familiar</li>
                            <li class="mb-1">Desastre natural o emergencia declarada</li>
                        </ul>
                        <div class="alert alert-warning py-2 mt-2 mb-0 font-size-xs">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            <strong>Citas de rutina, controles y consultas odontológicas SÍ consumen cupo</strong>
                        </div>
                    </div>

                    <!-- Responsabilidad académica -->
                    <div class="col-md-6 mb-4">
                        <div class="font-weight-bolder mb-2"><i class="fas fa-book text-info mr-1"></i> Responsabilidad Académica</div>
                        <ul class="pl-4 mb-0">
                            <li class="mb-2">La licencia aprobada <strong>no exime</strong> al estudiante de ponerse al día. La iniciativa es siempre del estudiante.</li>
                            <li class="mb-2">Debe completar tareas y evaluaciones en la <strong>siguiente clase</strong> de cada materia tras reincorporarse.</li>
                            <li class="mb-2">Si la licencia supera los <strong>3 días</strong>, debe coordinar con el maestro la reprogramación del examen.</li>
                            <li class="mb-2">El incumplimiento resulta en <strong>nota 1 sin derecho a reclamo</strong>.</li>
                        </ul>
                    </div>

                    <!-- Estudiantes representantes y registro -->
                    <div class="col-md-6 mb-4">
                        <div class="font-weight-bolder mb-2"><i class="fas fa-trophy text-warning mr-1"></i> Estudiantes Representantes</div>
                        <ul class="pl-4 mb-2">
                            <li class="mb-2">Las ausencias por torneos o eventos <strong>consumen cupo igual</strong> que cualquier otra ausencia.</li>
                            <li class="mb-2">Se requiere convocatoria oficial con <strong>5 días hábiles de anticipación</strong>.</li>
                        </ul>
                        <div class="font-weight-bolder mb-2 mt-3"><i class="fas fa-edit text-primary mr-1"></i> Registro en el SAAT</div>
                        <ul class="pl-4 mb-0">
                            <li class="mb-2">Toda ausencia debe registrarse <strong>antes de que ocurra o el mismo día</strong>.</li>
                            <li class="mb-2">
                                Las licencias por día(s) que <strong>empiezan hoy</strong> solo se pueden solicitar por esta plataforma hasta las
                                <strong><?= date('g:i a', strtotime((new \Config\PrimReglas())->horaCierre)) ?></strong>.
                                Después de esa hora, comuníquese con la secretaría de su nivel.
                            </li>
                            <li class="mb-2">El registro es obligatorio pero <strong>no descuenta</strong> la ausencia del cupo.</li>
                            <li class="mb-2">Documentos falsos o adulterados derivan en <strong>medidas disciplinarias</strong>.</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php if (empty($children_prim)): ?>
    <div class="alert alert-info font-weight-bold">
        <i class="fas fa-info-circle mr-2"></i>
        No tienes hijos inscritos en Primaria 3ro a 6to Grado.
    </div>
    <?php return; endif; ?>

    <!-- Selector de hijo (si hay más de uno) -->
    <?php if (count($children_prim) > 1): ?>
    <div class="mb-5 d-flex flex-wrap">
        <?php foreach ($children_prim as $child): ?>
        <a href="<?= base_url('parents/prim_licencias?student_id=' . $child['student_id']) ?>"
           class="child-tab <?= $child['student_id'] == $selected_id ? 'active' : '' ?>">
            <?= esc($child['student']) ?>
            <small class="d-block font-weight-normal" style="font-size:.75rem;"><?= esc($child['completo']) ?></small>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($selected): ?>
    <div class="row">

        <!-- Panel izquierdo: info del alumno + cupo -->
        <div class="col-lg-4">
            <div class="card card-custom gutter-b shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="symbol symbol-50 symbol-circle symbol-light-primary mr-3">
                            <span class="symbol-label font-weight-bolder font-size-h4">
                                <?= strtoupper(substr($selected['student'], 0, 1)) ?>
                            </span>
                        </div>
                        <div>
                            <div class="font-weight-bolder text-dark"><?= esc($selected['student']) ?></div>
                            <div class="text-muted font-size-sm"><?= esc($selected['completo']) ?></div>
                        </div>
                    </div>

                    <!-- Cupo trimestral -->
                    <div id="cupo_panel">
                        <div class="d-flex justify-content-between font-size-sm font-weight-bold mb-1">
                            <span>Cupo Trimestral</span>
                            <span id="cupo_texto">— / 9 días</span>
                        </div>
                        <div class="cupo-bar-bg">
                            <div class="cupo-bar-fill cupo-ok" id="cupo_barra" style="width:0%"></div>
                        </div>
                        <div class="d-flex justify-content-between font-size-xs text-muted mt-1">
                            <span>Consumido: <strong id="cupo_consumido">—</strong></span>
                            <span>Restante: <strong id="cupo_restante">—</strong> días</span>
                        </div>
                        <div id="cupo_alerta" class="mt-2" style="display:none;"></div>
                    </div>
                </div>
            </div>

            <!-- Leyenda de estados -->
            <div class="card card-custom shadow-sm">
                <div class="card-body py-4 px-5">
                    <div class="font-weight-bold font-size-sm mb-3 text-muted">Estados de solicitud</div>
                    <div class="d-flex flex-column gap-2">
                        <div><span class="est-pill ep-pend mr-2">⏳ Pendiente</span> <small class="text-muted">En revisión por secretaría</small></div>
                        <div class="mt-2"><span class="est-pill ep-apro mr-2">✓ Aprobada</span> <small class="text-muted">Licencia válida</small></div>
                        <div class="mt-2"><span class="est-pill ep-rech mr-2">✗ Rechazada</span> <small class="text-muted">No autorizada</small></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel derecho: formularios y historial -->
        <div class="col-lg-8">

            <!-- Formulario: Licencia por Día -->
            <div id="form_dia" class="card card-custom gutter-b shadow-sm" style="display:none;">
                <div class="card-header border-0 py-4">
                    <h5 class="card-label font-weight-bolder mb-0">
                        <i class="fas fa-calendar-times text-primary mr-2"></i>Nueva Licencia por Días
                    </h5>
                </div>
                <form method="POST" action="<?= base_url('parents/license_save_dia') ?>" enctype="multipart/form-data" onsubmit="return validarDia()">
                    <?= csrf_field() ?>
                    <input type="hidden" name="student_id" value="<?= $selected_id ?>">
                    <input type="hidden" name="parent_text" id="dia_parent_text" value="">
                    <div class="card-body pt-2">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Parentesco <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="parents" id="dia_parentesco" onchange="fillSolicitante('dia', this.value)" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($parentescos as $p): ?>
                                        <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Solicitante <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="dia_solicitante" placeholder="Nombre completo del solicitante" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Motivo <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="motivo_id" id="dia_motivo" required onchange="actualizarCupoImpacto('dia')">
                                    <option value="">Seleccione un motivo...</option>
                                    <?php foreach ($motivos as $mo): ?>
                                        <option value="<?= $mo->motivo_id ?>"
                                            data-excepcion="<?= $mo->es_excepcion ?>"
                                            data-condicional="<?= in_array($mo->motivo_id, [1, 2]) ? 1 : 0 ?>">
                                            <?= ($mo->es_excepcion ? '⭐ ' : '') . esc($mo->motivo) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Detalle</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="detalle" placeholder="Información adicional (opcional)">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Desde <span class="text-danger">*</span></label>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="fecha_inicio" id="dia_inicio" onchange="calcDias()" required>
                            </div>
                            <div class="col-sm-1 d-flex align-items-center justify-content-center text-muted font-weight-bold">→</div>
                            <div class="col-sm-3">
                                <input type="date" class="form-control" name="fecha_fin" id="dia_fin" onchange="calcDias()" required>
                                <small class="text-muted">Hasta</small>
                            </div>
                            <div class="col-sm-1 d-flex align-items-center">
                                <span class="badge badge-light-primary font-size-sm" id="dia_cantidad_badge">1d</span>
                                <input type="hidden" name="cantidad" id="dia_cantidad" value="1">
                            </div>
                        </div>

                        <div class="form-group row" id="dia_carta_box" style="display:none;">
                            <label class="col-sm-4 col-form-label font-weight-bold">
                                Carta de Solicitud <span class="text-danger">*</span>
                            </label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" name="carta_solicitud" id="dia_carta" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Obligatoria para licencias de más de 3 días. PDF o imagen, máx. 5 MB.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Comprobante</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" name="comprobante_medico" id="dia_comprobante" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF o imagen, máx. 5 MB (opcional)</small>
                                <div class="form-check mt-2" id="dia_doc_pendiente_box">
                                    <input class="form-check-input" type="checkbox" id="dia_check_pendiente" onchange="toggleDocPendiente('dia')">
                                    <label class="form-check-label font-size-sm text-muted" for="dia_check_pendiente">
                                        No tengo el documento ahora, lo enviaré en los próximos 3 días
                                    </label>
                                </div>
                                <input type="hidden" name="doc_pendiente" id="dia_doc_pendiente_val" value="0">
                            </div>
                        </div>

                        <div id="dia_impacto" class="alert py-2 mb-0" style="display:none;"></div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-paper-plane mr-1"></i> Enviar Solicitud
                        </button>
                        <button type="button" class="btn btn-light ml-2" onclick="toggleForm(null)">Cancelar</button>
                    </div>
                </form>
            </div>

            <!-- Formulario: Licencia por Periodos -->
            <div id="form_sal" class="card card-custom gutter-b shadow-sm" style="display:none;">
                <div class="card-header border-0 py-4">
                    <h5 class="card-label font-weight-bolder mb-0">
                        <i class="fas fa-door-open text-warning mr-2"></i>Solicitar Licencia por Periodos
                    </h5>
                </div>
                <form method="POST" action="<?= base_url('parents/license_save_periodo') ?>" enctype="multipart/form-data" onsubmit="return validarSal()">
                    <?= csrf_field() ?>
                    <input type="hidden" name="student_id" value="<?= $selected_id ?>">
                    <input type="hidden" name="parent_text" id="sal_parent_text" value="">
                    <div class="card-body pt-2">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Parentesco <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="parents" id="sal_parentesco" onchange="fillSolicitante('sal', this.value)" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($parentescos as $p): ?>
                                        <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Solicitante <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="sal_solicitante" placeholder="Nombre completo del solicitante" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Motivo <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="motivo_id" id="sal_motivo" required onchange="actualizarCupoImpacto('sal')">
                                    <option value="">Seleccione un motivo...</option>
                                    <?php foreach ($motivos as $mo): ?>
                                        <option value="<?= $mo->motivo_id ?>"
                                            data-excepcion="<?= $mo->es_excepcion ?>"
                                            data-condicional="<?= in_array($mo->motivo_id, [1, 2]) ? 1 : 0 ?>">
                                            <?= ($mo->es_excepcion ? '⭐ ' : '') . esc($mo->motivo) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Detalle</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="detalle" placeholder="Información adicional (opcional)">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Fecha <span class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="date" class="form-control" name="fecha" id="sal_fecha" required>
                            </div>
                            <div class="col-sm-4">
                                <input type="time" class="form-control" name="hora_salida" id="sal_hora">
                                <small class="text-muted">Hora de salida</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Período(s) <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div id="periodos_container" style="border:1px solid #ebedf3; border-radius:8px; padding:12px; max-height:200px; overflow-y:auto;">
                                    <em class="text-muted font-size-sm">Cargando períodos...</em>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">¿Quién lo recoge? <span class="text-danger">*</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="recoge_nombre" id="sal_recoge_nombre" placeholder="Nombre completo" required>
                            </div>
                            <div class="col-sm-4">
                                <select class="form-control" name="recoge_parentesco_id" id="sal_recoge_parentesco" required>
                                    <option value="">Parentesco...</option>
                                    <?php foreach ($parentescos_todos as $p): ?>
                                        <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="se_reincorpora" value="1" id="sal_se_reincorpora">
                                    <label class="form-check-label font-weight-bold" for="sal_se_reincorpora">
                                        El estudiante se reincorporará (volverá a clases)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Comprobante</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" name="comprobante_medico" id="sal_comprobante" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF o imagen, máx. 5 MB (opcional)</small>
                                <div class="form-check mt-2" id="sal_doc_pendiente_box">
                                    <input class="form-check-input" type="checkbox" id="sal_check_pendiente" onchange="toggleDocPendiente('sal')">
                                    <label class="form-check-label font-size-sm text-muted" for="sal_check_pendiente">
                                        No tengo el documento ahora, lo enviaré en los próximos 3 días
                                    </label>
                                </div>
                                <input type="hidden" name="doc_pendiente" id="sal_doc_pendiente_val" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning font-weight-bold">
                            <i class="fas fa-paper-plane mr-1"></i> Enviar Solicitud
                        </button>
                        <button type="button" class="btn btn-light ml-2" onclick="toggleForm(null)">Cancelar</button>
                    </div>
                </form>
            </div>

            <!-- Formulario: Cambio de Recojo -->
            <div id="form_rec" class="card card-custom gutter-b shadow-sm" style="display:none;">
                <div class="card-header border-0 py-4">
                    <h5 class="card-label font-weight-bolder mb-0">
                        <i class="fas fa-bus text-warning mr-2" style="color:#e65100!important;"></i>Nuevo Aviso de Cambio de Recojo
                    </h5>
                </div>
                <form method="POST" action="<?= base_url('parents/prim_cambio_recojo_create') ?>" id="form_recojo">
                    <input type="hidden" name="student_id" value="<?= $selected_id ?>">
                    <input type="hidden" name="family_id" value="<?= $family_id ?>">
                    <input type="hidden" name="parent_text" id="rec_parent_text" value="">
                    <div class="card-body pt-2">

                        <div class="form-group">
                            <label class="font-weight-bold">¿Qué cambia hoy? <span class="text-danger">*</span></label>

                            <label class="tipo-opt mb-2">
                                <input type="radio" name="tipo" value="1" onchange="tipoChangeRec(1)" required>
                                <strong>Recogerá otra persona</strong>
                                <div class="text-muted font-size-sm ml-4">Distinta al padre/madre/tutor habitual</div>
                            </label>

                            <label class="tipo-opt mb-2">
                                <input type="radio" name="tipo" value="2" onchange="tipoChangeRec(2)">
                                <strong>No usará transporte escolar</strong>
                                <div class="text-muted font-size-sm ml-4">El estudiante se irá con sus papás</div>
                            </label>

                            <label class="tipo-opt mb-2">
                                <input type="radio" name="tipo" value="3" onchange="tipoChangeRec(3)">
                                <strong>Otro</strong>
                                <div class="text-muted font-size-sm ml-4">Especifique el detalle</div>
                            </label>
                        </div>

                        <!-- Datos de la persona (tipo=1) -->
                        <div id="bloque_persona" style="display:none;">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label font-weight-bold">Nombre de la persona <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="persona_nombre" placeholder="Nombre completo">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label font-weight-bold">Parentesco con el estudiante <span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <select class="form-control" name="persona_parentesco_id" id="rec_persona_parentesco_sel" onchange="togglePersonaParentescoOtro()">
                                        <option value="">Seleccione...</option>
                                        <?php foreach ($parentescos_todos as $p): ?>
                                            <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                        <?php endforeach; ?>
                                        <option value="0">Otro (especifique)</option>
                                    </select>
                                    <input type="text" class="form-control mt-2" name="persona_parentesco_otro"
                                        id="rec_persona_parentesco_otro" placeholder="Especifique el parentesco" style="display:none;">
                                </div>
                            </div>
                        </div>

                        <!-- Detalle (obligatorio si tipo=3) -->
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">
                                Detalle <span class="text-danger" id="detalle_req" style="display:none;">*</span>
                            </label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="detalle" id="rec_detalle" rows="2" placeholder="Información adicional"></textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Parentesco (usted) <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="parents" id="rec_parentesco" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($parentescos as $p): ?>
                                        <option value="<?= $p->parentesco_id ?>"><?= esc($p->parentesco) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label font-weight-bold">Solicitado por <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="rec_solicitante" placeholder="Nombre completo del solicitante" required oninput="document.getElementById('rec_parent_text').value=this.value">
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn font-weight-bold" style="background:#e65100;color:#fff;">
                            <i class="fas fa-paper-plane mr-1"></i> Enviar Aviso
                        </button>
                        <button type="button" class="btn btn-light ml-2" onclick="toggleForm(null)">Cancelar</button>
                    </div>
                </form>
            </div>

            <!-- Historial de licencias -->
            <div id="hist_licencias" class="card card-custom shadow-sm">
                <div class="card-header border-0 py-4">
                    <h5 class="card-label font-weight-bolder mb-0">Historial de Solicitudes</h5>
                </div>
                <div class="card-body py-2">
                    <?php if (empty($licencias)): ?>
                    <p class="text-muted text-center py-6">Sin solicitudes registradas para este trimestre.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover font-size-sm">
                            <thead class="text-muted text-uppercase font-size-xs">
                                <tr>
                                    <th>Fecha solicitud</th>
                                    <th>Tipo</th>
                                    <th>Motivo</th>
                                    <th>Período / Fechas</th>
                                    <th class="text-center">Cupo</th>
                                    <th class="text-center">Comprobante</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($licencias as $lic): ?>
                                <tr>
                                    <td><?= $lic['fecha_solicitud'] ? date('d-m-Y', strtotime($lic['fecha_solicitud'])) : '—' ?></td>
                                    <td>
                                        <?php if ($lic['tipo_id'] == 1): ?>
                                            <span class="badge badge-light-primary">📅 Por día</span>
                                        <?php else: ?>
                                            <span class="badge badge-light-warning">🚪 Periodos</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($lic['motivo'] ?? '—') ?></td>
                                    <td class="font-size-xs">
                                        <?php if ($lic['tipo_id'] == 1 && $lic['fecha_inicio']): ?>
                                            <?= date('d/m', strtotime($lic['fecha_inicio'])) ?>
                                            <?= $lic['fecha_fin'] != $lic['fecha_inicio'] ? ' → ' . date('d/m', strtotime($lic['fecha_fin'])) : '' ?>
                                            <?= $lic['cantidad_dias'] ? " ({$lic['cantidad_dias']}d)" : '' ?>
                                        <?php elseif ($lic['fecha_periodo']): ?>
                                            <?= date('d/m/Y', strtotime($lic['fecha_periodo'])) ?>
                                            <?= $lic['periodos_nombre'] ? '<br><small>' . esc($lic['periodos_nombre']) . '</small>' : '' ?>
                                            <?php if (!empty($lic['recoge_nombre'])): ?>
                                                <br><small class="text-muted">🚶 Recoge: <?= esc($lic['recoge_nombre']) ?><?= $lic['recoge_parentesco'] ? ' (' . esc($lic['recoge_parentesco']) . ')' : '' ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>—<?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($lic['es_excepcion']): ?>
                                            <span class="badge badge-light-success">Excepción</span>
                                        <?php elseif ($lic['fraccion_cupo'] !== null): ?>
                                            <?= number_format((float)$lic['fraccion_cupo'], 1) ?> día(s)
                                        <?php else: ?>—<?php endif; ?>
                                    </td>
                                    <td class="text-center" id="comp_cell_<?= $lic['licencias_id'] ?>">
                                        <?php if (!empty($lic['comprobante_medico'])): ?>
                                            <a href="<?= base_url('uploads/comprobantes_medicos/' . $lic['comprobante_medico']) ?>" target="_blank" class="btn btn-xs btn-light-success font-size-xs">
                                                <i class="fas fa-paperclip mr-1"></i>Ver
                                            </a>
                                        <?php elseif (!empty($lic['doc_pendiente']) && $lic['enviado'] != 2): ?>
                                            <?php
                                            $dias_trans = (int)floor((time() - strtotime($lic['fecha_solicitud'])) / 86400);
                                            $dias_rest  = max(0, 3 - $dias_trans);
                                            ?>
                                            <?php if ($dias_rest > 0): ?>
                                                <button type="button"
                                                    class="btn btn-xs btn-light-warning font-size-xs d-block mb-1"
                                                    onclick="abrirUpload(<?= $lic['licencias_id'] ?>, <?= $selected_id ?>)">
                                                    <i class="fas fa-upload mr-1"></i>Adjuntar
                                                </button>
                                                <small class="text-warning font-weight-bold">
                                                    <?= $dias_rest === 1 ? 'Vence mañana' : "Quedan {$dias_rest} días" ?>
                                                </small>
                                            <?php else: ?>
                                                <span class="badge badge-light-danger font-size-xs">Plazo vencido</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                        <?php if (!empty($lic['carta_solicitud'])): ?>
                                            <a href="<?= base_url('uploads/cartas_solicitud/' . $lic['carta_solicitud']) ?>" target="_blank" class="btn btn-xs btn-light-primary font-size-xs d-block mt-1">
                                                <i class="fas fa-file-alt mr-1"></i>Carta
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $e = $lic['enviado'];
                                        if ($e == 1)      echo '<span class="est-pill ep-apro">✓ Aprobada</span>';
                                        elseif ($e == 2)  echo '<span class="est-pill ep-rech">✗ Rechazada</span>';
                                        elseif ($e == 3)  echo '<span class="est-pill ep-elim">🗑 Eliminada</span>';
                                        else              echo '<span class="est-pill ep-pend">⏳ Pendiente</span>';
                                        ?>
                                        <?php if ($e == 0): ?>
                                        <button type="button" class="btn btn-xs btn-light-danger font-size-xs d-block mt-1 mx-auto"
                                            onclick="cancelarLicenciaPropia(<?= $lic['licencias_id'] ?>)">
                                            <i class="fas fa-times mr-1"></i>Cancelar
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Modal subida tardía de comprobante -->
                        <div class="modal fade" id="modalUploadComp" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header py-3">
                                        <h6 class="modal-title font-weight-bold">Adjuntar comprobante</h6>
                                        <button type="button" class="close" data-dismiss="modal"><i class="ki ki-close"></i></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="file" id="upload_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted">PDF o imagen, máx. 5 MB</small>
                                        <div id="upload_msg" class="mt-2" style="display:none;"></div>
                                    </div>
                                    <div class="modal-footer py-3">
                                        <button type="button" class="btn btn-primary btn-sm font-weight-bold" onclick="enviarComprobante()">
                                            <i class="fas fa-upload mr-1"></i>Subir
                                        </button>
                                        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historial de Cambios de Recojo -->
            <div id="hist_recojo" class="card card-custom shadow-sm" style="display:none;">
                <div class="card-header border-0 py-4">
                    <h5 class="card-label font-weight-bolder mb-0">Historial de Cambios de Recojo</h5>
                </div>
                <div class="card-body py-2">
                    <?php if (empty($historial_recojo)): ?>
                    <p class="text-muted text-center py-6">Aún no hay avisos registrados.</p>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover font-size-sm">
                            <thead class="text-muted text-uppercase font-size-xs">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($historial_recojo as $h): ?>
                                <tr>
                                    <td><?= date('d-m-Y', strtotime($h['fecha'])) ?></td>
                                    <td>
                                        <?php if ($h['tipo'] == 1): ?>
                                            Otra persona: <strong><?= esc($h['persona_nombre']) ?></strong>
                                            <?php if (!empty($h['persona_parentesco'])): ?> (<?= esc($h['persona_parentesco']) ?>)<?php endif; ?>
                                        <?php elseif ($h['tipo'] == 2): ?>
                                            Sin transporte escolar
                                        <?php else: ?>
                                            Otro
                                        <?php endif; ?>
                                        <?php if (!empty($h['detalle'])): ?>
                                            <div class="text-muted"><?= esc($h['detalle']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $e = $h['enviado'];
                                        if ($e == 1)      echo '<span class="est-pill ep-apro">✓ Aprobado</span>';
                                        elseif ($e == 2)  echo '<span class="est-pill ep-rech">✗ Rechazado</span>';
                                        else              echo '<span class="est-pill ep-pend">⏳ Pendiente</span>';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    <?php endif; ?>

    <!-- Modal de aviso (reemplaza al banner de flash message) -->
    <div class="modal fade" id="modalInfo" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-3" id="modalInfoHeader">
                    <h6 class="modal-title font-weight-bolder mb-0" id="modalInfoTitle">Aviso</h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body py-3">
                    <p class="font-size-sm mb-0" id="modalInfoBody"></p>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Continuar</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
const BASE      = '<?= base_url() ?>';
const CSRF_TOK  = '<?= csrf_token() ?>';
const CSRF_HASH = '<?= csrf_hash() ?>';
const FAMILY_ID = <?= (int)$family_id ?>;
const SECTION_ID = <?= (int)($selected['section_id'] ?? 0) ?>;
const STUDENT_ID = <?= (int)$selected_id ?>;

/* ─── Mostrar / ocultar formulario ─── */
function toggleForm(which) {
    document.getElementById('form_dia').style.display = (which === 'dia') ? '' : 'none';
    document.getElementById('form_sal').style.display = (which === 'sal') ? '' : 'none';
    document.getElementById('form_rec').style.display = (which === 'rec') ? '' : 'none';
    document.getElementById('tab_dia').classList.toggle('active', which === 'dia');
    document.getElementById('tab_sal').classList.toggle('active', which === 'sal');
    document.getElementById('tab_rec').classList.toggle('active', which === 'rec');
    document.getElementById('hist_licencias').style.display = (which === 'rec') ? 'none' : '';
    document.getElementById('hist_recojo').style.display    = (which === 'rec') ? '' : 'none';

    if (which === 'dia') setDefaultDatesDia();
    if (which === 'sal') setDefaultDatesSal();
}

function tipoChangeRec(tipo) {
    document.querySelectorAll('#form_rec .tipo-opt').forEach(function(el) { el.classList.remove('checked'); });
    event.currentTarget.classList.add('checked');

    document.getElementById('bloque_persona').style.display = (tipo === 1) ? '' : 'none';
    document.querySelectorAll('#bloque_persona input, #bloque_persona select').forEach(function(el) {
        el.required = (tipo === 1);
    });
    togglePersonaParentescoOtro();

    document.getElementById('detalle_req').style.display = (tipo === 3) ? '' : 'none';
    document.getElementById('rec_detalle').required = (tipo === 3);
}

function togglePersonaParentescoOtro() {
    var sel  = document.getElementById('rec_persona_parentesco_sel');
    var otro = document.getElementById('rec_persona_parentesco_otro');
    var esOtro = sel.value === '0';
    otro.style.display = esOtro ? '' : 'none';
    otro.required = esOtro && sel.required;
    if (!esOtro) otro.value = '';
}

const AUTO_FORM = <?= json_encode(in_array($_GET['form'] ?? '', ['dia', 'sal', 'rec'], true) ? $_GET['form'] : null) ?>;
if (AUTO_FORM) toggleForm(AUTO_FORM);

function setDefaultDatesDia() {
    var hoy = todayStr();
    if (!document.getElementById('dia_inicio').value) document.getElementById('dia_inicio').value = hoy;
    if (!document.getElementById('dia_fin').value)    document.getElementById('dia_fin').value    = hoy;
    calcDias();
}

function setDefaultDatesSal() {
    var hoy = todayStr();
    if (!document.getElementById('sal_fecha').value) document.getElementById('sal_fecha').value = hoy;
    var now = new Date();
    var pad = n => ('0'+n).slice(-2);
    if (!document.getElementById('sal_hora').value) document.getElementById('sal_hora').value = pad(now.getHours())+':'+pad(now.getMinutes());
}

function todayStr() {
    var d = new Date();
    var pad = n => ('0'+n).slice(-2);
    return d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate());
}

/* ─── Calcular días ─── */
function calcDias() {
    var fi = new Date(document.getElementById('dia_inicio').value);
    var ff = new Date(document.getElementById('dia_fin').value);
    if (isNaN(fi) || isNaN(ff) || ff < fi) {
        document.getElementById('dia_cantidad').value = 1;
        document.getElementById('dia_cantidad_badge').textContent = '1d';
        return;
    }
    var dias = 0, cur = new Date(fi);
    while (cur <= ff) { if (cur.getDay() !== 0) dias++; cur.setDate(cur.getDate() + 1); }
    dias = dias || 1;
    document.getElementById('dia_cantidad').value = dias;
    document.getElementById('dia_cantidad_badge').textContent = dias + 'd';

    var cartaBox = document.getElementById('dia_carta_box');
    cartaBox.style.display = (dias > 3) ? '' : 'none';
    document.getElementById('dia_carta').required = (dias > 3);

    actualizarCupoImpacto('dia');
}

function toggleDocPendiente(pref) {
    var check     = document.getElementById(pref + '_check_pendiente');
    var fileInput = document.getElementById(pref + '_comprobante');
    var hidVal    = document.getElementById(pref + '_doc_pendiente_val');
    if (check.checked) {
        fileInput.disabled = true;
        fileInput.value    = '';
        hidVal.value       = '1';
    } else {
        fileInput.disabled = false;
        hidVal.value       = '0';
    }
}

/* ─── Impacto cupo ─── */
function actualizarCupoImpacto(tipo) {
    var sel = document.getElementById(tipo + '_motivo');
    var box = document.getElementById(tipo + '_impacto');
    if (!sel || !sel.value || !box) return;
    var opt           = sel.options[sel.selectedIndex];
    var esExcep       = opt ? opt.dataset.excepcion    == 1 : false;
    var esCondicional = opt ? opt.dataset.condicional  == 1 : false;
    box.style.display = '';
    if (esExcep) {
        box.className = 'alert alert-success py-2 mb-0 font-size-sm';
        box.innerHTML = '<i class="fas fa-shield-alt mr-2"></i>Esta ausencia <strong>NO consumirá cupo</strong> trimestral (excepción Art. 10).';
    } else if (esCondicional) {
        box.className = 'alert alert-info py-2 mb-0 font-size-sm';
        box.innerHTML = '<i class="fas fa-question-circle mr-2"></i>El colegio evaluará el comprobante presentado y determinará si esta ausencia consume cupo o aplica como excepción.';
    } else {
        var dias = parseInt(document.getElementById('dia_cantidad') ? document.getElementById('dia_cantidad').value : 1) || 1;
        box.className = 'alert alert-warning py-2 mb-0 font-size-sm';
        box.innerHTML = '<i class="fas fa-calendar-minus mr-2"></i>Esta ausencia consumirá <strong>' + dias + ' día(s)</strong> del cupo trimestral (máx. 9 días).';
    }
}

/* ─── Autocompletar solicitante ─── */
function fillSolicitante(pref, parentesco_id) {
    if (!parentesco_id || parentesco_id > 2) {
        document.getElementById(pref + '_solicitante').value = '';
        document.getElementById(pref + '_parent_text').value = '';
        return;
    }
    $.get(BASE + 'server/fill_parent_relationship/' + FAMILY_ID + '/' + parentesco_id, function(r) {
        var c = JSON.parse(r);
        if (c.length) {
            var nombre = c[0].name + ' ' + c[0].lastname1 + ' ' + c[0].lastname2;
            document.getElementById(pref + '_solicitante').value = nombre;
            document.getElementById(pref + '_parent_text').value = nombre;
        }
    });
}

document.getElementById('dia_solicitante').addEventListener('input', function() {
    document.getElementById('dia_parent_text').value = this.value;
});
document.getElementById('sal_solicitante').addEventListener('input', function() {
    document.getElementById('sal_parent_text').value = this.value;
});

/* ─── Validación antes de enviar ─── */
function validarDia() {
    document.getElementById('dia_parent_text').value = document.getElementById('dia_solicitante').value;
    if (!document.getElementById('dia_solicitante').value.trim()) { alert('Ingrese el nombre del solicitante'); return false; }
    if (!document.getElementById('dia_motivo').value) { alert('Seleccione un motivo'); return false; }
    var dias = parseInt(document.getElementById('dia_cantidad').value) || 1;
    if (dias > 3 && !document.getElementById('dia_carta').files.length) {
        alert('Para licencias de más de 3 días debe adjuntar la carta de solicitud.');
        return false;
    }
    return true;
}

function validarSal() {
    document.getElementById('sal_parent_text').value = document.getElementById('sal_solicitante').value;
    if (!document.getElementById('sal_solicitante').value.trim()) { alert('Ingrese el nombre del solicitante'); return false; }
    if (!document.getElementById('sal_motivo').value) { alert('Seleccione un motivo'); return false; }
    var checks = document.querySelectorAll('#periodos_container input[type=checkbox]:checked');
    if (!checks.length) { alert('Seleccione al menos un período'); return false; }
    if (!document.getElementById('sal_recoge_nombre').value.trim()) { alert('Indique quién recogerá al estudiante'); return false; }
    if (!document.getElementById('sal_recoge_parentesco').value) { alert('Seleccione el parentesco de quien recoge'); return false; }
    return true;
}

/* ─── Cancelar solicitud propia (solo mientras está pendiente) ─── */
function cancelarLicenciaPropia(licId) {
    if (!confirm('¿Seguro que deseas cancelar esta solicitud de licencia?')) return;
    var data = {};
    data['licencias_id'] = licId;
    data[CSRF_TOK] = CSRF_HASH;
    $.post(BASE + 'parents/prim_licencia_delete', data, function(r) {
        var res = typeof r === 'string' ? JSON.parse(r) : r;
        if (res.ok) {
            location.reload();
        } else {
            alert(res.msg || 'No se pudo cancelar la solicitud');
        }
    });
}

/* ─── Modal subida tardía de comprobante ─── */
var _uploadLicenciaId = 0;
var _uploadStudentId  = 0;

function abrirUpload(licencias_id, student_id) {
    _uploadLicenciaId = licencias_id;
    _uploadStudentId  = student_id;
    document.getElementById('upload_file').value = '';
    document.getElementById('upload_msg').style.display = 'none';
    $('#modalUploadComp').modal('show');
}

function enviarComprobante() {
    var file = document.getElementById('upload_file').files[0];
    var msg  = document.getElementById('upload_msg');
    if (!file) { msg.style.display=''; msg.className='alert alert-warning py-2 font-size-sm'; msg.textContent='Selecciona un archivo'; return; }
    if (file.size > 5*1024*1024) { msg.style.display=''; msg.className='alert alert-danger py-2 font-size-sm'; msg.textContent='El archivo supera los 5 MB'; return; }

    var fd = new FormData();
    fd.append('licencias_id', _uploadLicenciaId);
    fd.append('student_id',   _uploadStudentId);
    fd.append('comprobante_medico', file);
    fd.append(CSRF_TOK, CSRF_HASH);

    msg.style.display=''; msg.className='alert alert-info py-2 font-size-sm'; msg.textContent='Subiendo...';

    $.ajax({
        url: BASE + 'parents/prim_upload_comprobante',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        success: function(r) {
            if (r.ok) {
                msg.className='alert alert-success py-2 font-size-sm'; msg.textContent='Comprobante adjuntado correctamente';
                var cell = document.getElementById('comp_cell_' + _uploadLicenciaId);
                if (cell) cell.innerHTML = '<a href="<?= base_url('uploads/comprobantes_medicos/') ?>' + r.archivo + '" target="_blank" class="btn btn-xs btn-light-success font-size-xs"><i class="fas fa-paperclip mr-1"></i>Ver</a>';
                setTimeout(function(){ $('#modalUploadComp').modal('hide'); }, 1200);
            } else {
                msg.className='alert alert-danger py-2 font-size-sm'; msg.textContent = r.msg || 'Error al subir';
            }
        },
        error: function() { msg.className='alert alert-danger py-2 font-size-sm'; msg.textContent='Error de conexión'; }
    });
}

/* ─── Cargar períodos de la sección ─── */
function cargarPeriodos() {
    var cont = document.getElementById('periodos_container');
    cont.innerHTML = '<em class="text-muted font-size-sm">Cargando...</em>';
    $.get(BASE + 'server/fill_periodos_section/' + SECTION_ID, function(r) {
        cont.innerHTML = '';
        if (!r.length) { cont.innerHTML = '<span class="text-muted">Sin períodos.</span>'; return; }
        r.forEach(function(p) {
            var hi = p.hora_inicio ? p.hora_inicio.substring(0,5) : '';
            var hf = p.hora_fin    ? p.hora_fin.substring(0,5)    : '';
            var lbl = document.createElement('label');
            lbl.className = 'd-flex align-items-center mb-2';
            lbl.innerHTML = '<input type="checkbox" name="periodos[]" value="'+p.periodo_id+'" class="mr-2"> '
                          + '<span class="font-size-sm">'+p.periodo
                          + (hi ? ' <span class="text-muted">('+hi+' – '+hf+')</span>' : '')
                          + '</span>';
            cont.appendChild(lbl);
        });
    }).fail(function() {
        cont.innerHTML = '<span class="text-danger font-size-sm">Error al cargar períodos.</span>';
    });
}

/* ─── Cupo del alumno ─── */
function cargarCupo() {
    var data = {};
    data['student_id'] = STUDENT_ID;
    data[CSRF_TOK]     = CSRF_HASH;
    $.post(BASE + 'parents/prim_cupo_estudiante', data, function(cupo) {
        var total    = parseFloat(cupo.total) || 0;
        var restante = parseFloat(cupo.cupo_restante) || 0;
        var pct      = Math.min((total / 9) * 100, 100);
        document.getElementById('cupo_texto').textContent     = total.toFixed(1) + ' / 9 días';
        document.getElementById('cupo_consumido').textContent = total.toFixed(1);
        document.getElementById('cupo_restante').textContent  = restante.toFixed(1);
        var barra = document.getElementById('cupo_barra');
        barra.style.width = pct + '%';
        barra.className   = 'cupo-bar-fill ' + (cupo.limite9 ? 'cupo-full' : (cupo.alerta6 ? 'cupo-warn' : 'cupo-ok'));
        var alerta = document.getElementById('cupo_alerta');
        if (cupo.limite9) {
            alerta.innerHTML = '<div class="alert alert-danger py-2 font-size-sm mb-0"><i class="fas fa-exclamation-triangle mr-1"></i> Alumno en <strong>límite de 9 días</strong></div>';
            alerta.style.display = '';
        } else if (cupo.alerta6) {
            alerta.innerHTML = '<div class="alert alert-warning py-2 font-size-sm mb-0"><i class="fas fa-exclamation-circle mr-1"></i> Superó los <strong>6 días</strong> de cupo</div>';
            alerta.style.display = '';
        } else {
            alerta.style.display = 'none';
        }
    });
}

// Cargar cupo y períodos al abrir la página
cargarCupo();
cargarPeriodos();

// Mostrar el resultado de la última solicitud (éxito o error) como modal en vez de banner
<?php if ($flashErr): ?>
document.getElementById('modalInfoHeader').style.background = '#fff0f2';
document.getElementById('modalInfoTitle').textContent = '⚠️ Aviso';
document.getElementById('modalInfoBody').textContent = <?= json_encode($flashErr) ?>;
$('#modalInfo').modal('show');
<?php elseif ($flashOk): ?>
document.getElementById('modalInfoHeader').style.background = '#e8fff3';
document.getElementById('modalInfoTitle').textContent = '✓ Listo';
document.getElementById('modalInfoBody').textContent = <?= json_encode($flashOk) ?>;
$('#modalInfo').modal('show');
<?php endif; ?>
</script>
