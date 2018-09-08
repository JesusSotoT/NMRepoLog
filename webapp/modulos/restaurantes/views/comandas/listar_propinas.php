<?php 
// Valida que existan propinas en los parametros seleccionadas
	if (empty($propinas)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>
	<div class="col-xs-12 col-md-12">
		<div style="float: right;">
			<p id="total_pro" style="font-size: 16px; margin:0"><strong>Total propinas: </strong></p>
		</div>
	</div>
	<table id="tabla_propinas" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th align="center"><strong><i class="fa fa-credit-card"></i></strong></th> <!-- ID venta -->
				<th align="center"><strong><i class="fa fa-barcode"></i></strong></th> <!-- Codigo -->
				<th align="center"><strong><i class="fa fa-object-group"></i></strong></th> <!-- Nombre Mesa -->
				<th align="center"><strong><i class="fa fa-hand-o-up"></i></strong></th> <!-- Mesero -->
				<th align="center"><strong><i class="fa fa-home"></i></strong></th> <!-- Sucursal -->
				<th align="center"><strong><i class="fa fa-usd"></i> Propina</strong></th> <!-- Total propina-->
				<th align="center"><strong><i class="fa fa-usd"></i> Venta</strong></th> <!-- Total venta-->
				<th align="center"><strong><i class="fa fa-language"></i></strong></th> <!-- Via de contacto -->
				<th align="center"><strong><i class="fa fa-usd"></i> Pago</strong></th> <!-- Via de contacto -->
				<th align="center"><strong><i class="fa fa-print"></i></strong></th> <!-- Via de contacto -->
			</tr>
		</thead>
		<tbody><?php
		// $propinas es un array con las propinas viene desde el controlador
			foreach ($propinas as $key => $value) {
				$propina = $value;
				$propina['f_ini'] = $objeto['f_ini'];
				$propina['f_fin'] = $objeto['f_fin'];
				$propina = json_encode($propina);
				$propina = str_replace('"', "'", $propina);
				
				$total += $value['total_propina']; ?>
				
				<tr>
					<td><?php echo $value['id_venta'] ?></td>
					<td><?php echo $value['codigo'] ?></td>
					<td><?php echo $value['nombre_mesa'] ?></td>
					<td><?php echo $value['mesero'] ?></td>
					<td><?php echo $value['sucursal'] ?></td>
					<td align="center">$ <?php echo $value['total_propina'] ?></td>
					<td align="center">$ <?php echo $value['total_venta'] ?></td>
					<td><?php echo $value['via_contacto'] ?></td>
					<td><?php echo $value['metodo_pago'] ?></td>
					<td align="center">
						<button class="btn btn-primary" onclick="comandas.imprimir_propina(<?php echo $propina ?>)">
							<i class="fa fa-print"></i>
						</button>
					</td>
				</tr><?php $total_pro = $total_pro + $value['total_propina'];
			} ?>
		</tbody>
	</table>
	<?php echo '<script>$("#total_pro").append(" $ '.number_format($total_pro, 2).'");</script>'; ?>