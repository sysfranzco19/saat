<?php

//$account_type   =	$this->session->userdata('login_type');

$session = session();

$account_type = $session->get('login_type');







function get_image_url($type = '', $id = '')
{

    $image_url = base_url() . 'uploads/user.jpg';



    if (file_exists('uploads/' . $type . '_image/' . $id . '.jpg')) {

        $image_url = base_url() . 'uploads/' . $type . '_image/' . $id . '.jpg';

    }



    return $image_url;

}



?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="SAAT - Colegio Tiquipaya Ltda." />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="author" content="Sysfranzco" />

    <link rel="canonical" href="https://keenthemes.com/metronic" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-K1SblyS5jiM7DpK1CcUAXm6U4P6U4V7KnX5y0eWoOJb5ep5NxV+y5BkO5uXd7vaU" crossorigin="anonymous">

    <title><?php echo $page_title; ?> | <?php echo $system_title; ?></title>

    <?php include 'includes_top.php'; ?>

    <style>
        .example-modal .modal {

            position: relative;

            top: auto;

            bottom: auto;

            right: auto;

            left: auto;

            display: block;

            z-index: 1;

        }



        .example-modal .modal {

            background: transparent !important;

        }
    </style>



</head>

<!-- <?php echo $page_name; ?> -->

<?php

switch ($page_name) {

    case "half_phase":

        ?>

        <body>

            <?php include $account_type . '/' . $page_name . '.php'; ?>

            <?php include 'modal.php'; ?>

            <?php include 'includes_bottom.php'; ?>

        </body>

        </html>

        <?php

        break;

    case "deliver_notes":

        ?>

        <body>
            <?php include $account_type . '/' . $page_name . '.php'; ?>

            <?php include 'modal.php'; ?>

            <?php include 'includes_bottom.php'; ?>

        </body>

        </html>

        <?php

        break;

    case "review_notes":

        ?>

        <body>

            <?php include $account_type . '/' . $page_name . '.php'; ?>

            <?php include 'modal.php'; ?>

            <?php include 'includes_bottom.php'; ?>

        </body>

        </html>

        <?php

        break;

    case "section_notes":

        ?>

        <body>

            <?php include $account_type . '/' . $page_name . '.php'; ?>

            <?php include 'modal.php'; ?>

            <?php include 'includes_bottom.php'; ?>

        </body>

        </html>

        <?php

        break;

    case "attendance_report":

        ?>

        <body>

            <?php include $account_type . '/' . $page_name . '.php'; ?>

            <?php include 'modal.php'; ?>

            <?php include 'includes_bottom.php'; ?>

        </body>

        </html>

        <?php

        break;

    case "report_card2":

        ?>

        <body>

            <?php include $account_type . '/' . $page_name . '.php'; ?>

            <?php include 'modal.php'; ?>

            <?php include 'includes_bottom.php'; ?>

        </body>

        </html>

        <?php

        break;

    case "low_averages":

        ?>

        <body>

            <?php include $account_type . '/' . $page_name . '.php'; ?>

            <?php include 'modal.php'; ?>

            <?php include 'includes_bottom.php'; ?>

        </body>

        </html>

        <?php

        break;

    default:

        ?>

        <body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled sidebar-enabled page-loading">

            <?php include '_header-mobile.php'; ?>

            <div class="d-flex flex-column flex-root">

                <div class="d-flex flex-row flex-column-fluid page">

                    <?php include($account_type . "/_aside.php"); ?>

                    <!--begin::Wrapper-->

                    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

                        <?php include($account_type . "/_header.php"); ?>

                        <!--begin::Content-->

                        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">

                            <?php include($account_type . "/_subheader/subheader-v1.php"); ?>

                            <?php include($account_type . "/" . $page_name . ".php"); ?>

                        </div>

                        <!--end::Content-->

                        <?php include($account_type . "/_footer.php"); ?>

                    </div>

                    <!--end::Wrapper-->

                    <?php include($account_type . "/_sidebar.php"); ?>

                </div>

                <!--end::Page-->

            </div>

            <?php include 'modal.php'; ?>

            <!--end::Main-->

            <?php include 'includes_bottom.php'; ?>

        </body>

        </html>

    <?php

}

?>