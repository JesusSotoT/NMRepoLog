<?php
// Valida que existan actividades
	if (empty($zonas)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>

<div class="row">
	<div id="grafica_zonas_dona" class="col-xs-4" style="height: 40%">
		<!-- En esta div se carga la grafica de dona -->
	</div>
	<div id="grafica_zonas_barras" class="col-xs-8" style="height: 40%">
		<!-- En esta div se carga la grafica de barras -->
	</div>
</div>
<div class="row">
	<div class="col-md-6"></div>
	<div class="col-md-6" id="total" align="right">
		<!-- En esta div se carga el total de utilidad -->
	</div>
</div><br />
<table id="tabla_zonas" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th align="center"><strong>Consumo</strong></th>
			<th align="center"><strong>Sucursal</strong></th>
			<th ><strong>Zona</strong></th>
			<th align="center"><strong>Mesa</strong></th>
			<th><strong>Empleado</strong></th>
			<th align="center"><strong>Comensales</strong></th>
			<th align="center"><strong>Comandas</strong></th>
		</tr>
	</thead>
	<tbody><?php
	// $zonas es un array con las comandas viene desde el controlador
		foreach ($zonas as $key => $value) { 
			$total += $value['comandas'];
			$monto += $value['total']; 
			$comensales += $value['comensales']; ?>
			
			<tr>
				<td align="center">$ <?php echo $value['total'] ?></td>
				<td><?php echo $value['sucursal'] ?></td>
				<td><?php echo $value['zona'] ?></td>
				<td align="center"><?php echo $value['mesa'] ?></td>
				<td><?php echo $value['usuario'] ?></td>
				<td align="center"><?php echo $value['comensales'] ?></td>
				<td align="center"><?php echo $value['comandas'] ?></td>
			</tr> <?php
		} ?>
	</tbody>
</table>
<script>
	var $texto = '<h4>'+
					'Comensales: '+<?php echo $comensales ?>+' / '+
					'Comandas: '+<?php echo $total ?>+' / '+
					'Consumo: $'+<?php echo $monto ?>+
				'</h4>';
					
	$("#total").html($texto);
	
	comandas.graficar({
		caracter:'$',
		div:'grafica_zonas',
		x:'zona',
		y:'comandas',
		label:'Comandas',
		dona:<?php echo json_encode($dona) ?>,
		barras:<?php echo json_encode($barras) ?>
	});
</script>