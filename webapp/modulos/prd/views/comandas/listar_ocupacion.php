<?php
// Valida que existan actividades
	if (empty($ocupaciones)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>

<div class="row">
	<div id="grafica_ocupacion_dona" class="col-xs-4" style="height: 40%">
		<!-- En esta div se carga la grafica de dona -->
	</div>
	<div id="grafica_ocupacion_lineal" class="col-xs-8" style="height: 40%">
		<!-- En esta div se carga la grafica lineal -->
	</div>
</div>
<div class="row">
	<div class="col-md-6"></div>
	<div class="col-md-6" id="total" align="right">
		<!-- En esta div se carga el total de utilidad -->
	</div>
</div><br />
<table id="tabla_ocupaciones" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<td align="center"><strong>Comandas</strong></td>
			<td><strong>Hora</strong></td>
			<td align="center"><strong>Comensales</strong></td>
			<td><strong>Sucursal</strong></td>
			<td><strong>Mesas</strong></td>
			<td><strong>Zonas</strong></td>
			<td><strong>Empleados</strong></td>
		</tr>
	</thead>
	<tbody><?php
	// $ocupaciones es un array con las comandas viene desde el controlador
		foreach ($ocupaciones as $key => $value) { 
			$total_comandas += $value['comandas']; 
			$total_comensales += $value['comensales']; ?>
			
			<tr>
				<td align="center"><?php echo $value['comandas'] ?></td>
				<td><?php echo $value['hora'] ?></td>
				<td align="center"><?php echo $value['comensales'] ?></td>
				<td><?php echo $value['sucursal'] ?></td>
				<td><?php echo $value['mesas'] ?></td>
				<td><?php echo $value['zonas'] ?></td>
				<td><?php echo $value['usuarios'] ?></td>
			</tr> <?php
		} ?>
	</tbody>
</table>
<script>
	var $texto = '<h4>'+
					'Comensales: '+<?php echo $total_comensales ?>+' / '+
					'Comandas: '+<?php echo $total_comandas ?>+
				'</h4>';
					
	$("#total").html($texto);
	
	comandas.graficar({
		div:'grafica_ocupacion',
		x:'hora_grafica',
		y:'comensales',
		label:'Comensales',
		dona:<?php echo json_encode($dona) ?>,
		lineal:<?php echo json_encode($lineal) ?>
	});
</script>