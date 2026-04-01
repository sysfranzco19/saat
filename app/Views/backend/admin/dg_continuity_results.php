<?php
$si = count($students_si);
$no = count($students_no);
$in = count($students_in);
$nr = count($students_prospective) - count($students_si)-count($students_no)-count($students_in);
?>
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-xl-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Resultados Consulta de Continuidad 2024
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <span>Estudiantes posibles: <?php echo count($students_prospective)?></span>
                        <div id="chart_12" class="d-flex justify-content-center"></div>
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <div class="col-xl-12">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Últimas 10 respuestas
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                    <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" >Estudiante</th>
                                    <th scope="col" >Curso</th>
                                    <th scope="col" >Respuesta</th>
                                </tr>
                            </thead>
                        <tbody>
                    <?php 
                        foreach($continuity_students_10 as $con){
                            ?>
                            <tr>
                                <td><?php echo $con['student'];?></td>
                                <td><?php echo $con['completo'];?></td>
                                <td><?php 
                                switch ($con['respuesta']) {
                                    case "SI":
                                        echo '<span class="label label-xl label-info label-inline mr-2">SI</span>';
                                        break;
                                    case "NO":
                                        echo '<span class="label label-xl label-success label-inline mr-2">NO</span>';
                                        break;
                                    case "INDECISO":
                                        echo '<span class="label label-xl label-warning label-inline mr-2">INDECISO</span>';
                                        break;
                                    default:
                                        echo "Opción no válida";
                                }
                                //echo $con['respuesta'];
                                ?></td>
                            </tr>
                        <?php
                        }?>
                        </tbody>
                    </table>
                    </div>
                </div>
                <!--end::Card-->
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
<script>
"use strict";

// Shared Colors Definition
const primary = '#6993FF';
const success = '#1BC5BD';
const info = '#8950FC';
const warning = '#FFA800';
const danger = '#F64E60';

// Class definition
function generateBubbleData(baseval, count, yrange) {
    var i = 0;
    var series = [];
    while (i < count) {
      var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;;
      var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
      var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;
  
      series.push([x, y, z]);
      baseval += 86400000;
      i++;
    }
    return series;
  }

function generateData(count, yrange) {
    var i = 0;
    var series = [];
    while (i < count) {
        var x = 'w' + (i + 1).toString();
        var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;

        series.push({
            x: x,
            y: y
        });
        i++;
    }
    return series;
}

var KTApexChartsDemo = function () {
	// Private functions
	var _demo12 = function () {
		const apexChart = "#chart_12";
		var options = {
			series: [<?php echo $si?>, <?php echo $no?>, <?php echo $in?>, <?php echo $nr?>],
			chart: {
				width: 420,
				type: 'pie',
			},
			labels: ['Si[<?php echo $si?>]', 'No[<?php echo $no?>]', 'Indeciso[<?php echo $in?>]', 'Sin Responder[<?php echo $nr?>]'],
			responsive: [{
				breakpoint: 480,
				options: {
					chart: {
						width: 200
					},
					legend: {
						position: 'bottom'
					}
				}
			}],
			colors: [primary, success, warning, danger, info]
		};

		var chart = new ApexCharts(document.querySelector(apexChart), options);
		chart.render();
	}

	return {
		// public functions
		init: function () {
			_demo12();
		}
	};
}();

jQuery(document).ready(function () {
	KTApexChartsDemo.init();
});
</script>
