<?php
// Valida que existan pedidos
if (empty($pedidos)) {?>
	<div align="center">
		<h3><span class="label label-default">Select a saucer ----------></span></h3>
	</div><?php
	
	return 0;
}

foreach ($pedidos as $key => $value) {
// Se elimino de cocina
	if($value['status'] == 3){
		$status = 'disabled="1" style="background-color:#D6872D"';
		$status_cantidad = 'disabled="1" style="background-color:#D6872D; width: 60px"';
	}else{
		$status_cantidad = 'style="width: 60px"';
	} ?>
	
	<div class="input-group">
		<input min="1" id="num_pedidos<?php echo $value['id'] ?>" <?php echo $status_cantidad ?>  type="number" class="form-control" value="1">
		<span class="input-group-btn">
			<button 
				<?php echo $status ?> 
				class="btn btn-default" 
				onclick="comandera.sumar_pedido({
						idorder:<?php echo $value['id'] ?>,
						idperson: <?php echo $objeto['persona'] ?>,
						idcomanda: <?php echo $objeto['id_comanda'] ?>
				})" 
				type="button">
				+
			</button><?php
		// pedido procesado, solo se puede modificar por el admin
			if($value['status'] == 0 || $value['status'] == 1){
				$status = 'style="background-color:#DCB435"'; // Pedido pendiente
				$status_admin = 'disabled="1"'; 
			} else if($value['status'] == 2){
				$status = 'style="background-color:#6DAE9F"'; // Pedido terminado
				$status_admin = 'disabled="1"'; 
			} else if($value['status'] == 4){
				$status = 'style="background-color:#209775"'; // Pedido entregado
				$status_admin = 'disabled="1"'; 
			} ?>
			<button 
				<?php echo $status ?> 
				disabled="1" 
				id="cantidad_<?php echo $value['id'] ?>" 
				class="btn btn-default" 
				type="button">
				<?php echo $value['cantidad'] ?>
			</button>
			<button 
				id="btn_restar_<?php echo $value['id'] ?>"<?php 
			
			// Valida que el pedido no esta eliminado y que ya se hubiera pedido
				if ($value['status'] >= 0  && $value['status'] != 3) {
					echo '	data-toggle="modal" 
							data-target="#modal_merma"';
				}
				
				echo $status;
				echo $status_admin ?> 
				class="btn btn-default" <?php
				
				if ($value['status'] == -1) { ?>
					onclick="comandera.restar_pedido({
							id: <?php echo $value['id'] ?>,
							persona: <?php echo $objeto['persona'] ?>,
							id_comanda: <?php echo $objeto['id_comanda'] ?>
					})"<?php
				}else{
				// Valida que el pedido no esta eliminado y que ya se hubiera pedido
					if ($value['status'] >= 0  && $value['status'] != 3) {
						$merma = json_encode($value);
						$merma = str_replace('"', "'", $merma);
						
						echo 'onclick="comandera.pedido_merma = '.$merma .'"';
					} 
				}  ?>
				
				type="button">
				-
			</button>
		</span>
		<input <?php echo $status ?> type="text" disabled="1" class="form-control" value="<?php echo $value['nombre'] ?>">
		<span <?php echo $status ?> class="input-group-addon" id="basic-addon1"><?php echo $value['precio'] ?></span>
		<span class="input-group-btn" id="span_accion_<?php echo $value['id'] ?>"><?php
		// pedido procesado, solo se puede modificar por el admin
			if($value['status'] == 0 || $value['status'] == 1 || $value['status'] == 2 || $value['status'] == 4){
				$status = 'style="background-color:#77DD77"';
				$status_admin = 'disabled="1"'; ?>
				
				<button 
					class="btn btn-default" 
					onclick="$('#id_pedido_modificar').val(<?php echo $value['id'] ?>)" 
					type="button" 
					data-toggle="modal" 
					data-target="#modal_autorizar_pedido">
					<i class="fa fa-key"></i> &nbsp;
				</button><?php
		// Pedido normal
			}else{ ?>
				<button 
					<?php echo $status ?> 
					class="btn btn-danger" 
					id="btn_eliminar_pedido_<?php echo $value['id'] ?>" 
					type="button" 
					onclick="comandera.eliminar_pedido({
						idorder: <?php echo $value['id'] ?>,
						idperson: <?php echo $objeto['persona'] ?>,
						idcomanda: <?php echo $objeto['id_comanda'] ?>
					})">
					<i class="fa fa-trash"></i> &nbsp;
				</button><?php
			} ?>
		</span>
	</div><?php
	if (!empty($value['complementos'])) {
		foreach ($value['complementos'] as $k => $v) { ?>
			<div class="input-group">
				<input type="text" disabled="1" class="form-control" style="text-align:right" value="-- <?php echo $v['nombre'] ?>">
				<span class="input-group-addon"><?php echo $v['precio'] ?></span>
				<span class="input-group-btn"><?php
				// Solo se puede eliminar si aun no se pide
					if($value['status'] == -1){
						$complementos = json_encode($value['complementos']);
						$complementos = str_replace('"', "'", $complementos); ?>
						
						<button 
							<?php echo $status ?> 
							class="btn btn-danger" 
							id="btn_eliminar_pedido_<?php echo $value['id'] ?>" 
							type="button" 
							onclick="comandera.eliminar_complemento({
								id_pedido: <?php echo $value['id'] ?>,
								id_complemento: <?php echo $v['id'] ?>,
								complementos: <?php echo $complementos ?>
							})">
							<i class="fa fa-trash"></i> &nbsp;
						</button<?php
					} ?>
				</span>
			</div><?php
		}
	}
} ?>