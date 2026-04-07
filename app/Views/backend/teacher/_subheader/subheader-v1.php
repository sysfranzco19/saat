
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-6 subheader-transparent" id="kt_subheader">
	<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">

		<!--begin::Info-->
		<div class="d-flex align-items-center flex-wrap mr-1">

			<!--begin::Page Heading-->
			<div class="d-flex align-items-baseline flex-wrap mr-5">

				<!--begin::Page Title-->
				<h5 class="text-dark font-weight-bold my-1 mr-5">Docentes</h5>

				<!--end::Page Title-->

				<!--begin::Breadcrumb-->
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<!--INICIO-->
					<li class="breadcrumb-item">
						<a href="<?php echo base_url(); ?><?php echo $account_type; ?>/dashboard" class="text-muted">Dashboard</a>
					</li>
					
					<!--PAGINA 1-->
					<?php
					if (isset($page_title_0)) {
						$link0 = "#";
						if (isset($page_name_0)) {$link0=base_url().$account_type."/".$page_name_0;}
					?>
					<li class="breadcrumb-item">
						<a href="<?php echo $link0; ?>" class="text-muted"><?php echo $page_title_0; ?></a>
					</li>
					<?php
					}
					?>
					<!--PAGINA2-->
					<?php
					if (isset($page_title_1)) {
						$link1 = "#";
						if (isset($page_name_1)) {$link1=base_url().$account_type."/".$page_name_1;}
					?>
					<li class="breadcrumb-item">
						<a href="<?php echo $link1; ?>" class="text-muted"><?php echo $page_title_1; ?></a>
					</li>
					<?php
					}
					?>
					<!--PAGINA Final-->
					<?php
					if (isset($page_title)) {
					?>
					<li class="breadcrumb-item">
						<!--<a href="<?php echo base_url(); ?><?php echo $account_type; ?>/<?php echo $page_name; ?>" class="text-muted"><?php echo $page_title; ?></a>-->
						<a href="<?php echo base_url(); ?>" class="text-muted"><?php echo $page_title; ?></a>
					</li>
					<?php
					}
					?>
				</ul>

				<!--end::Breadcrumb-->
			</div>

			<!--end::Page Heading-->
		</div>

		<!--end::Info-->

		<!--begin::Toolbar-->
		<div class="d-flex align-items-center">

									

			<!--begin::Dropdown-->
									
			<!--end::Dropdown-->
		</div>

		<!--end::Toolbar-->
	</div>
</div>

<!--end::Subheader-->