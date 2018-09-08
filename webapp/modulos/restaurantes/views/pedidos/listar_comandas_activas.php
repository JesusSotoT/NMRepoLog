<table id="tabla_comandas_activas" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th><strong><i class="fa fa-hashtag"></i></strong></th>
			<th><strong><i class="fa fa-object-group"></i></strong></th>
			<th><strong><i class="fa fa-clock-o"></i></strong></th>
			<th><strong><i class="fa fa-user"></i></strong></th>
			<th><strong><i class="fa fa-check"></i></strong></th>
			<th><strong><i class="fa fa-print"></i></strong></th>
		</tr>
	</thead> 
	<tbody><?php
	// $comandas es un array con los pedidos que viene del controller
		foreach ($comandas as $key => $value) { ?>
			<tr class="<?php echo $clase ?>" id="tr_comandas_activas_<?php echo $value['id_comanda'] ?>">
				<td align="center"><?php echo $value['id_comanda'] ?></td>
				<td><?php echo $value['nombre_mesa'] ?></td>
				<td><?php echo $value['fecha'] ?></td>
				<td><?php echo $value['mesero'] ?></td>
				<td align="center">
					<button 
						id="loader_<?php echo $value['producto'] ?>" 
						onclick="pedidos.actualizar_pedidos({status: 2, id_comanda: <?php echo $value['id_comanda'] ?>})" 
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
						class="btn btn-success btn-lg">
						<i class="fa fa-check"></i>
					</button>
				</td>
				<td align="center">
					<button 
						id="loader_eliminar_<?php echo $value['producto'] ?>" 
						onclick="comandera.imprimir_pedidos({id_comanda: <?php echo $value['id_comanda'] ?>})" 
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
						class="btn btn-warning btn-lg">
						<i class="fa fa-print"></i>
					</button>
				</td>
			</tr> <?php
		} ?>
	</tbody>	
</table>
<script>
	pedidos.convertir_dataTable({id: 'tabla_comandas_activas'});
</script>