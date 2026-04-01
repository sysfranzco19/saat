<script type="text/javascript">
    function showAjaxModal(url) {
        // LOADING THE AJAX MODAL
        jQuery('#modal_ajax').modal('show', {
            backdrop: 'true'
        });

        // SHOW AJAX RESPONSE ON REQUEST SUCCESS
        $.ajax({
            url: url,
            success: function (response) {
                jQuery('#modal_ajax .modal-content').html(response);
            }
        });
    }
</script>

<div class="modal fade" id="modal_ajax" tabindex="-1" role="dialog" aria-labelledby="exampleModalSizeLg"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

<script type="text/javascript">
    function confirm_modal(delete_url) {
        jQuery('#modal-4').modal('show', {
            backdrop: 'static'
        });
        document.getElementById('delete_link').setAttribute('href', delete_url);
    }

    function openFeedbackModal() {
        $('#feedback_comment').val('');
        $('#modal_feedback').modal('show');
    }

    function sendFeedback() {
        const comment = $('#feedback_comment').val().trim();
        if (!comment) {
            toastr.warning('Por favor escribe un comentario.');
            return;
        }

        const btn = $('#btn_send_feedback');
        const originalText = btn.text();
        btn.attr('disabled', true).text('Enviando...');

        $.post('<?php echo site_url('login/save_feedback'); ?>', {
            comment: comment,
            url: window.location.href
        }, function (data) {
            if (data.status === 'success') {
                toastr.success(data.message);
                $('#modal_feedback').modal('hide');
            } else {
                toastr.error('Error: ' + data.message);
            }
        }, 'json').fail(function (xhr, status, error) {
            console.error("Feedback AJAX Fail:", status, error, xhr.responseText);
            toastr.error('Error de conexión al enviar el comentario.');
        }).always(function () {
            btn.attr('disabled', false).text(originalText);
        });
    }
</script>

<!-- (Normal Modal)-->
<div class="modal fade" id="modal-4">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:100px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="text-align:center;">¿Estás seguro de eliminar esta información?</h4>
            </div>
            <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                <a href="#" class="btn btn-danger" id="delete_link">Eliminar</a>
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Feedback Modal -->
<div class="modal fade" id="modal_feedback" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <div class="w-100 text-center pt-4">
                    <div class="symbol symbol-60 symbol-circle symbol-light-primary mb-3">
                        <span class="symbol-label">
                            <i class="flaticon2-chat-1 text-primary icon-xl"></i>
                        </span>
                    </div>
                    <h5 class="modal-title font-weight-bolder text-dark-75" id="feedbackModalLabel">Enviar Comentario o Sugerencia</h5>
                    <p class="text-muted font-size-sm mt-2">Tu opinión nos ayuda a mejorar la plataforma</p>
                </div>
                <button type="button" class="close position-absolute" style="top: 1.5rem; right: 1.5rem;" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2 px-10 pb-10">
                <div class="form-group mb-5">
                    <label for="feedback_comment" class="font-weight-bold text-dark-75">¿Qué tienes en mente?</label>
                    <textarea class="form-control form-control-solid rounded-xl p-5" id="feedback_comment" rows="5"
                        placeholder="Escribe aquí tus comentarios, sugerencias o reportes de errores..."></textarea>
                </div>
                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                    <span class="svg-icon svg-icon-primary svg-icon-2tx mr-3">
                        <i class="fa fa-info-circle text-primary"></i>
                    </span>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-bold">
                            <div class="fs-6 text-gray-700">
                                <small class="text-primary-75">Tu comentario será registrado con tu usuario para seguimiento por el equipo técnico.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 px-10 pb-10">
                <button type="button" class="btn btn-light-primary font-weight-bold px-8" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary font-weight-bold px-8 shadow-sm" id="btn_send_feedback" onclick="sendFeedback()">
                    <i class="fa fa-paper-plane mr-2"></i>Enviar Comentario
                </button>
            </div>
        </div>
    </div>
</div>
