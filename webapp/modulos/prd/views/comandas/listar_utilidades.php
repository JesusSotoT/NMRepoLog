<?php
// Valida que existan actividades
	if (empty($utilidades)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>

<div class="row">
	<div id="grafica_utilidades_dona" class="col-xs-4" style="height: 40%">
		<!-- En esta div se carga la grafica de dona -->
	</div>
	<div id="grafica_utilidades_lineal" class="col-xs-8" style="height: 40%">
		<!-- En esta div se carga la grafica de barras -->
	</div>
</div>
<div class="row">
	<div class="col-md-6"></div>
	<div class="col-md-6" id="total" align="right">
		<!-- En esta div se carga el total de utilidad -->
	</div>
</div><br />
<table id="tabla_utilidades" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<td align="center"><strong>Cantidad</strong></td>
			<td ><strong>Sucursal</strong></td>
			<td ><strong>Producto</strong></td>
			<td align="center"><strong>Venta</strong></td>
			<td align="center"><strong>Costo</strong></td>
			<td align="center"><strong>Total venta</strong></td>
			<td align="center"><strong>Total costo</strong></td>
			<td align="center"><strong>Ganancia</strong></td>
			<td align="center"><strong>%</strong></td>
		</tr>
	</thead>
	<tbody><?php
	// $utilidades es un array que viene desde el controlador
		foreach ($utilidades as $key => $value) {
			$total += $value['ganancia'];  ?>
			
			<tr>
				<td align="center"><?php echo $value['ventas'] ?></td>
				<td><?php echo $value['sucursal'] ?></td>
				<td><?php echo $value['nombre'] ?></td>
				<td align="center"><?php echo $value['precio'] ?></td>
				<td align="center"><?php echo $value['costo'] ?></td>
				<td align="center"><?php echo $value['venta_total'] ?></td>
				<td align="center"><?php echo $value['costo_total'] ?></td>
				<td align="center"><?php echo $value['ganancia'] ?></td>
				<td align="center"><?php echo $value['porcentaje'] ?></td>
			</tr> <?php
		} ?>
	</tbody>
</table>
<script>
	$("#total").html('<h4>Total: <strong>$'+<?php echo $total ?>+'</strong><h4>');
	
	comandas.graficar({
		div:'grafica_utilidades', 
		x:'fecha', 
		y:'ventas', 
		label:'Ventas', 
		dona:<?php echo json_encode($dona) ?>, 
		lineal:<?php echo json_encode($lineal) ?>
	});
</script>