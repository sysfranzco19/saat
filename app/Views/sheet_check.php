<?php
//$plistar = $POST["plistar"];

//echo $plistar;
?>

<table class='table'>
	<thead class='thead-inverse'>
        <tr>
            <th>Tipo Revisión</th>
            <th>Estado</th>
        </tr>
	</thead>
	<tbody>
		<?php
		foreach ($rev as $clave => $valor) {
    // $array[3] se actualizará con cada valor de $array...
    //echo "{$clave} => {$valor} ";
    //print_r($array);
		
		?>
        <tr>
        	<td><?php echo $clave;?></td>
        	<td><?php echo $valor;?></td>
        </tr>
        <?php
        }
		?>
	</tbody>
</table>