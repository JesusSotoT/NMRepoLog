<?php
// Valida que existan reservaciones
	if (empty($reservaciones)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>
		
	<table class="table table-bordered table-striped">
			<tr>
				<td><strong>Reservacion</strong></td>
				<td><strong>Mesa</strong></td>
				<td><strong>Cliente</strong></td>
				<td><strong>Inicio</strong></td>
				<td><strong>Fin</strong></td>
				<td><strong>Descripcion</strong></td>
				<td><strong>Estatus</strong></td>
			</tr><?php
			
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
					<td><?php echo $value['mesa'] ?></td>
					<td><?php echo $value['cliente'] ?></td>
					<td><?php echo $value['inicio'] ?></td>
					<td><?php echo $value['fin'] ?></td>
					<td><?php echo $value['descripcion'] ?></td>
					<td><?php echo $estatus ?></td>
				</tr> <?php
			} ?>
		</table>