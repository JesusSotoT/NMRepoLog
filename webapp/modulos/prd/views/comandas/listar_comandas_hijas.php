<?php
// Valida que existan comandas en los parametros seleccionadas
	if (empty($sub_comandas)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>
	
	<table id="tabla_sub_comandas" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th align="center"><strong><i class="fa fa-barcode"></i></strong></th>
				<th align="center"><strong><i class="fa fa-object-group"></i></strong></th>
				<th align="center"><strong><i class="fa fa-user"></i></strong></th>
				<th align="center"><strong><i class="fa fa-check-square-o"></i></strong></th>
				<th align="center"><strong><i class="fa fa-hand-o-up"></i></strong></th>
				<th align="center"><strong><i class="fa fa-clock-o"></i></strong></th>
				<th align="center"><strong><i class="fa fa-usd"></i></strong></th>
				<th align="center"><strong><i class="fa fa-credit-card"></i></strong></th>
				<th align="center"><strong><i class="fa fa-ticket"></i></strong></th>
				<th align="center"><strong><i class="fa fa-pencil-square-o"></i></strong></th>
			</tr>
		</thead>
		<tbody><?php
		// $sub_comandas es un array con las comandas viene desde el controlador
			foreach ($sub_comandas as $key => $value) {
				$value['vista_estatus_comanda'] = 1;
				$comanda =json_encode($value);
				$comanda = str_replace('"', "'", $comanda) ?>
				
				<tr>
					<td><?php echo $value['codigo'] ?></td>
					<td align="center"><?php echo $value['nombre_mesa'] ?></td>
					<td align="center"><?php echo $value['persona'] ?></td>
					<td><?php echo $value['status'] ?></td>
					<td><?php echo $value['usuario'] ?></td>
					<td align="center"><?php echo $value['fecha'] ?></td>
					<td align="center">$ <?php echo $value['total'] ?></td>
					<td align="center"><?php echo $value['id_venta'] ?></td>
					<td align='center'>
						<!-- CloseComanda es una funcion que viene desde js/comandas/reimprime.js -->
						<img src='../../modulos/restaurantes/images/impresora.jpeg' title='Cuenta' style='cursor:pointer;' onclick="comandas.imprime_sub_comanda(<?php echo $comanda ?>)"/>
					</td>
					<td align='center'>
						<!-- imprimePedido es una funcion que viene desde js/comandas/reimprime.js -->
						<img src='../../modulos/restaurantes/images/impresora2.jpeg' title='Pedidos' style='cursor:pointer;' onclick="imprimePedido({id_comanda:'<?php echo $value['idpadre'] ?>', pedidos:'<?php echo $value['pedidos'] ?>'})">
					</td>
				</tr> <?php
			} ?>
		</tbody>
	</table>