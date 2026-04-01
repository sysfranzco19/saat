<?php
$section_id = $param1;
$subject_id = $param2;

$db = \Config\Database::connect('tiquipaya');
$query = $db->query("SELECT * FROM subject WHERE subject_id = '$subject_id'");
$subject = $query->getRowArray();
$query2 = $db->query("SELECT * FROM section WHERE section_id = '$section_id'");
$section = $query2->getRowArray();
?>

<div class="modal-body">
    <div class="modal-header" style="background-color:#5F73E2">
        <h6 class="modal-title" style="color:white">Planificar Evaluación -
            <?php echo $subject['name']; ?> (
            <?php echo $section['nick_name']; ?>)
        </h6>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <form id="evaluation_form" action="<?php echo base_url(); ?>teacher/evaluation_save" method="POST"
        enctype="multipart/form-data">
        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
        <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">

        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Título:</label>
            <div class="col-lg-9 col-xl-9">
                <input type="text" class="form-control" name="title" required />
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Descripción:</label>
            <div class="col-lg-9 col-xl-9">
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-xl-3 col-lg-3 col-form-label">Fecha:</label>
            <div class="col-lg-6 col-xl-6">
                <input type="date" class="form-control" name="date" id="exam_date" required
                    onchange="checkDateSaturation()">
            </div>
            <div class="col-lg-3 col-xl-3">
                <div id="traffic_light"
                    style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: bold; border-radius: 5px; color: white;">
                    <!-- Indicator will appear here -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div id="alert_message" class="alert" style="display:none"></div>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-12 text-center">
                <button type="submit" class="btn btn-primary" id="save_btn" disabled>Guardar Evaluación</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    function checkDateSaturation() {
        var date = document.getElementById('exam_date').value;
        var section_id = '<?php echo $section_id; ?>';
        var light = document.getElementById('traffic_light');
        var btn = document.getElementById('save_btn');
        var msg = document.getElementById('alert_message');

        if (date) {
            $.ajax({
                url: '<?php echo base_url(); ?>teacher/evaluation_check_date',
                type: 'POST',
                data: { date: date, section_id: section_id },
                success: function (response) {
                    var count = parseInt(response);

                    if (count < 2) {
                        // Green: 0 or 1 exam
                        light.style.backgroundColor = '#1BC5BD'; // Green
                        light.innerHTML = count + ' Exámenes';
                        btn.disabled = false;
                        msg.style.display = 'none';
                    } else if (count == 2) {
                        // Yellow: 2 exams
                        light.style.backgroundColor = '#FFA800'; // Yellow
                        light.innerHTML = count + ' Exámenes';
                        btn.disabled = false;
                        msg.className = 'alert alert-custom alert-warning fade show';
                        msg.innerHTML = '<div class="alert-text">Advertencia: Este sería el 3er examen del día.</div>';
                        msg.style.display = 'block';
                    } else {
                        // Red: 3 or more exams
                        light.style.backgroundColor = '#F64E60'; // Red
                        light.innerHTML = count + ' Exámenes';
                        btn.disabled = true;
                        msg.className = 'alert alert-custom alert-danger fade show';
                        msg.innerHTML = '<div class="alert-text">Bloqueado: Ya existen 3 o más exámenes este día.</div>';
                        msg.style.display = 'block';
                    }
                }
            });
        }
    }
</script>