<!--begin::Header-->
<div id="kt_header" class="header header-fixed">
    <!--begin::Header Wrapper-->
    <div class="header-wrapper rounded-top-xl d-flex flex-grow-1 align-items-center">
        <!--begin::Container-->
        <div class="container-fluid d-flex align-items-center justify-content-end justify-content-lg-between flex-wrap">
            <!--begin::Menu Wrapper-->
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                <!--begin::Menu-->
                <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                    <!--begin::Nav-->
                    <ul class="menu-nav">
                        <li class="menu-item menu-item-open menu-item-here menu-item-submenu menu-item-rel menu-item-open menu-item-here" data-menu-toggle="click" aria-haspopup="true">
                            <a href="<?php echo base_url(); ?><?php echo $account_type; ?>/dashboard" class="menu-link">
                                <span class="menu-text">Dashboard</span>
                                <i class="menu-arrow"></i>
                            </a>
                        </li>
                    </ul>
                    <!--end::Nav-->
                </div>
                <!--end::Menu-->
            </div>
            <!--end::Menu Wrapper-->

            <!--begin::Toolbar-->
            <div class="d-flex align-items-right py-3 py-lg-2">
                <a href="<?php echo base_url();?>logout" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">Cerrar Sesión</a>
                
                <!--end::Dropdown<span class="menu-text"><?php echo $phase_name; ?></span>-->
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Header Wrapper-->
</div>

<!--end::Header-->