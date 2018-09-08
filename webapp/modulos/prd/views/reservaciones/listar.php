<?php
// Valida que existan reservaciones
	if (empty($reservaciones)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>
	
<div class="row">
	<div id="grafica_reservaciones_dona" class="col-xs-4" style="height: 40%">
		<!-- En esta div se carga la grafica de dona -->
	</div>
	<div id="grafica_reservaciones_lineal" class="col-xs-8" style="height: 40%">
		<!-- En esta div se carga la grafica de barras -->
	</div>
</div>
<table id="tabla_reservaciones_listar" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th><strong>Reservacion</strong></th>
			<th><strong>Sucursal</strong></th>
			<th><strong>Mesa</strong></th>
			<th><strong>Cliente</strong></th>
			<th><strong>Inicio</strong></th>
			<th><strong>Fin</strong></th>
			<th><strong>Descripcion</strong></th>
			<th><strong>Estatus</strong></th>
		</tr>
	</thead>
	<tbody><?php
	// $promedios es un array con los promedios por comensal que viene desde el controlador
		foreach ($reservaciones as $key => $value) {
			if ($value['activo']==1) {
				$estatus='Activa';
				$class='warning';
			}
			
			if($value['activo']==0){
				$estatus='Cerrada';
				$class='success';
			}
			
			if($value['activo']==2){
				$estatus='Cancelada';
				$class='danger';
			} ?>
			
			<tr class="<?php echo $class ?>">
				<td><?php echo $value['id'] ?></td>
				<td><?php echo $value['sucursal'] ?></td>
				<td><?php echo $value['nombre_mesa'] ?></td>
				<td><?php echo $value['cliente'] ?></td>
				<td><?php echo $value['inicio'] ?></td>
				<td><?php echo $value['fin'] ?></td>
				<td><?php echo $value['descripcion'] ?></td>
				<td><?php echo $estatus ?></td>
			</tr> <?php
		} ?>
	</tbody>
</table>
<script>
	reservaciones.graficar({div:'grafica_reservaciones',x:'inicio',y:'reservaciones',label:'Reservaciones',dona:<?php echo json_encode($dona) ?>,lineal:<?php echo json_encode($lineal) ?>});
</script>