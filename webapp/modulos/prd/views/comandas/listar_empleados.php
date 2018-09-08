<table class="table table-bordered table-striped">
	<tr>
		<td align="center"><strong>ID</strong></td>
		<td><strong>Mesero</strong></td>
		<td><strong>Asignaciones</strong></td>
		<td><strong>Permisos</strong></td>
		<td align="center"><strong><i class="fa fa-pencil"></strong></td>
	</tr><?php
	foreach ($_SESSION['permisos']['empleados'] as $key => $value) { ?> 
		<tr>
			<td align="center"><?php echo $value['id'] ?></td>
			<td><?php echo $value['usuario'] ?></td>
			<td><?php echo $value['mesas_asignacion'] ?></td>
			<td><?php echo $value['mesas_permisos'] ?></td>
			<td align="center">
				<button id="btn_guardar" type="button" class="btn btn-default" onclick="comandas.listar_asignacion({id:<?php echo $value['id'] ?>})" data-toggle="modal" data-target="#modal_mesas">
					<i class="fa fa-pencil"></i>
				</button>
			</td>
		</tr><?php
	} ?>
</table>