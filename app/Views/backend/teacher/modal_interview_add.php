<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
        <?php echo $system_title; ?> - Registrar Entrevista
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <i aria-hidden="true" class="ki ki-close"></i>
    </button>
</div>
<form action="<?php echo base_url('teacher/interview_save'); ?>" method="post" enctype="multipart/form-data">
    <div class="modal-body">
        <input type="hidden" name="section_id" value="<?php echo $param1; ?>">

        <!-- Student Selector -->
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Estudiante:</label>
            <div class="col-lg-9 col-xl-9">
                <select class="form-control select2" id="student_select2" name="student_id" required
                    style="width: 100%;">
                    <option value="">Seleccione un estudiante...</option>
                    <?php
                    $StudentMod = new \App\Models\StudentModel();
                    // Assuming teacher_id is in session or passed. For modal, we need to re-fetch if not passed.
                    // Ideally pass students via param or fetch here. 
                    // To keep it simple, we'll fetch using the section_id (param2) and teacher_id.
                    $session = session();
                    $students = $StudentMod->studentsSection($param1, $session->get('teacher_id'));
                    foreach ($students as $row):
                        ?>
                        <option value="<?php echo $row['student_id']; ?>">
                            <?php echo $row['student']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Assistant (Parent/Tutor) -->
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Asistente (Tutor):</label>
            <div class="col-lg-9 col-xl-9">
                <input type="text" class="form-control" name="assistant"
                    placeholder="Nombre del padre/tutor presente" />
            </div>
        </div>

        <!-- Reason -->
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Motivo:</label>
            <div class="col-lg-9 col-xl-9">
                <select class="form-control" name="reason">
                    <option value="">Seleccione...</option>
                    <option value="Académico">Académico</option>
                    <option value="Conducta">Conducta</option>
                    <option value="Rutinario">Rutinario</option>
                </select>
            </div>
        </div>

        <!-- Description (Situation) -->
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Situación Actual:</label>
            <div class="col-lg-9 col-xl-9">
                <textarea class="form-control" name="description" rows="3"
                    placeholder="Observaciones y anotaciones..."></textarea>
            </div>
        </div>

        <!-- Agreements -->
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Acuerdos y Compromisos:</label>
            <div class="col-lg-9 col-xl-9">
                <textarea class="form-control" name="agreements" rows="3"
                    placeholder="Lo que se pactó en la reunión..."></textarea>
            </div>
        </div>

        <!-- Attachment -->
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Adjuntar Documento/Evidencia:</label>
            <div class="col-lg-9 col-xl-9">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="userfile" id="customFile" />
                    <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                </div>
            </div>
        </div>

        <!-- Follow-up Date -->
        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Fecha de Seguimiento:</label>
            <div class="col-lg-9 col-xl-9">
                <input type="date" class="form-control" name="follow_up_date" />
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Guardar Entrevista</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#student_select2').select2({
            dropdownParent: $('#student_select2').closest('.modal')
        });
    });
</script>