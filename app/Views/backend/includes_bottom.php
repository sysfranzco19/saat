<!--begin::Global Config-->
<script>
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1200
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#663259",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#F3F6F9",
                    "dark": "#212121"
                }
            }
        },
        "font-family": "Poppins"
    };
</script>
<!--end::Global Config-->

<!--begin::Page Vendors-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="<?= base_url('assets/plugins/custom/datatables/datatables.bundle.js') ?>"></script>
<script src="<?= base_url('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') ?>"></script>
<script src="<?= base_url('assets/plugins/custom/gmaps/gmaps.js') ?>"></script>
<!--end::Page Vendors-->

<!--begin::Page Scripts-->
<script src="<?= base_url('assets/js/pages/custom/wizard/wizard-3.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/custom/wizard/wizard-4.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/widgets.js') ?>"></script>
<!--end::Page Scripts-->

<!-- ================= TOASTR ================= -->

<?php
$session = session();
if ($session->get('flash_message') != ""):
    ?>
    <script>
        toastr.options = {
            closeButton: false,
            progressBar: false,
            positionClass: "toast-top-right",
            timeOut: "5000"
        };
        toastr.success('<?= $session->get("flash_message"); ?>');
    </script>
    <?php
    $session->remove('flash_message');
endif;
?>

<?php if ($session->get('flash_message_error') != ""): ?>
    <script>
        toastr.warning('<?= $session->get("flash_message_error"); ?>');
    </script>
    <?php
    $session->remove('flash_message_error');
endif;
?>