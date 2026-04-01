<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
							<!--begin::Container-->
							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-12">
										<!--begin::Card-->
										<div class="card card-custom gutter-b">
											<!--begin::Header-->
											<div class="card-header h-auto">
												<!--begin::Title-->
												<div class="card-title py-5">
													<h3 class="card-label">Estudiante: <?php echo $student; ?><p>Curso: <?php echo $completo; ?></p></h3>
												</div>
												<!--end::Title-->
											</div>
											<!--end::Header-->
											<div class="card-body">
												<!--begin::Chart-->
												<div id="chart"></div>
												<!--end::Chart-->
											</div>
										</div>
										<!--end::Card-->
									</div>
								</div>
							</div>
							<!--end::Container-->
						</div>
						<!--end::Entry-->


<script>
    <?php
    $ma ="";
    $t1 ="";
    $t2 ="";
    $t3 ="";
    foreach($csamarks as $row): 
        $ma.="'".$row['name']."',";
        $t1.=$row['T1'].",";
        $t2.=$row['T2'].",";
        $t3.=$row['T3'].",";
    endforeach; 
    ?>
    var options = {
        chart: {
            type: 'bar',
            height: 400
        },
        series: [{
            name: 'T1',
            data: [<?php echo rtrim($t1, ','); ?>]
        }, {
            name: 'T2',
            data: [<?php echo rtrim($t2, ','); ?>]
        }, {
            name: 'T3',
            data: [<?php echo rtrim($t3, ','); ?>]
        }],
        xaxis: {
            categories: [<?php echo rtrim($ma, ','); ?>]
        }
    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>