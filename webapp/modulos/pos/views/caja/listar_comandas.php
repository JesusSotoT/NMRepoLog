<?php
// Valida que existan comandas en los parametros seleccionadas
	if (empty($comandas)) { ?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>
	<table class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th align="left"><strong><i class="fa fa-barcode"></i></strong></th>
				<th align="left"><strong><i class="fa fa-object-group"></i></strong></th>
				<th align="center"><strong><i class="fa fa-hand-o-up"></i></strong></th>
				<th align="center"><strong><i class="fa fa-clock-o"></i></strong></th>
				<th align="center"><strong><i class="fa fa-usd"></i></strong></th>
				<th align="center"><strong><i class="fa fa-check"></i></strong></th>
			</tr>
		</thead>
		<tbody><?php
		// $comandas es un array con las comandas viene desde el controlador
			foreach ($comandas as $key => $value) { ?>
				
				<tr id="tr_<?php echo $value['codigo'] ?>">
					<td><?php echo $value['codigo'] ?></td>
					<td align="left"><?php echo $value['nombre_mesa'] ?></td>
					<td><?php echo $value['usuario'] ?></td>
					<td align="center"><?php echo $value['timestamp'] ?></td>
					<td align="center">$ <?php echo $value['total'] ?></td>
					<td align="center">						
						<button 
							class="btn btn-success btn-lg" 
							onclick="caja.mandar_comanda_caja({codigo:'<?php echo $value['codigo'] ?>'})">
							<i class="fa fa-check"></i>
						</button>
						<button 
							class="btn btn-info btn-lg" 
							onclick="caja.mandar_comanda_caja({codigo:'<?php echo $value['codigo'] ?>',print:1})">
							<i class="fa fa-print"></i>
						</button>
					</td>
				</tr> <?php
			} ?>
		</tbody>
	</table>