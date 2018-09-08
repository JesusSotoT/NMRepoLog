<?php
// Valida que existan pedidos
if (empty($pedidos)) {?>
	<div align="center">
		<h3><span class="label label-default">Selecciona un platillo ----------></span></h3>
	</div><?php
	
	return 0;
}

foreach ($pedidos as $key => $value) {
// Se elimino de cocina
	if($value['status'] == 3){
		$status = 'disabled="1" style="background-color:#FF6961"';
	} ?>
	
	<div class="input-group">
	<input min="1" id="num_pedidos<?php echo $value['id'] ?>" type="number" class="form-control" style="width: 60px" value="1">
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
			if($value['status'] == 0 || $value['status'] == 1 || $value['status'] == 2 || $value['status'] == 4){
				$status = 'style="background-color:#77DD77"';
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
				id="btn_restar_<?php echo $value['id'] ?>" 
				<?php echo $status ?> 
				<?php echo $status_admin ?> 
				class="btn btn-default" 
				onclick="comandera.restar_pedido({
						id: <?php echo $value['id'] ?>,
						persona: <?php echo $objeto['persona'] ?>,
						id_comanda: <?php echo $objeto['id_comanda'] ?>
				})" 
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
					class="btn btn-default" 
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
} ?>