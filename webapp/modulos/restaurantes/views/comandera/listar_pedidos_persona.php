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
	$status_promo = "";
	if($value['status'] == 3){
		$status = 'disabled="1" style="background-color:#D6872D"';
		$status_promo = 'style="disabled="1" background-color:#D6872D; text-align: right;"';
		$status_cantidad = 'disabled="1" style="background-color:#D6872D; width: 60px"';
	}else{
		$status_cantidad = 'style="width: 60px"';
	} ?>

	<div class="input-group">
		<?php
			
		// pedido procesado, solo se puede modificar por el admin
			if($value['status'] == -1){
				$status = 'style=""'; // Pedido pendiente
				$status_promo = 'style="butext-align: right;"';
				$status_admin = ''; 
			}
			else if($value['status'] == 0 || $value['status'] == 1){
				$status = 'style="background-color:#DCB435"'; // Pedido pendiente
				$status_promo = 'style="background-color:#DCB435; text-align: right;"';
				$status_admin = 'disabled="1"'; 
			} else if($value['status'] == 2){
				$status = 'style="background-color:#6DAE9F"'; // Pedido terminado
				$status_promo = 'style="background-color:#6DAE9F; text-align: right;"';
				$status_admin = 'disabled="1"'; 
			} else if($value['status'] == 4){
				$status = 'style="background-color:#209775"'; // Pedido entregado
				$status_promo = 'style="background-color:#209775; text-align: right;"';
				$status_admin = 'disabled="1"'; 
			} ?>

		<?php if($value['id_promocion'] == 0){ ?>

		<input min="1" id="num_pedidos<?php echo $value['id'] ?>" <?php echo $status_cantidad ?>  type="number" class="form-control" value="1">
		<span class="input-group-btn">
			<button 
				<?php echo $status ?> 
				class="btn btn-default" 
				data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
				id="sum_ped_<?php echo $value['id']?>"
				onclick="comandera.sumar_pedido({
						idorder:<?php echo $value['id'] ?>,
						idperson: <?php echo $objeto['persona'] ?>,
						idcomanda: <?php echo $objeto['id_comanda'] ?>
				})" 
				type="button">
				+
			</button>
			<button 
				<?php echo $status ?> 
				disabled="1" 
				id="cantidad_<?php echo $value['id'] ?>" 
				class="btn btn-default" 
				type="button">
				<?php echo $value['cantidad'] ?>
			</button>
			<button 
				data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
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
		<?php } 
		if($value['status'] == 0 || $value['status'] == 1 || $value['status'] == 2 || $value['status'] == 4){
			$notaedit = ' disabled="1" ';
		}else{
			$notaedit = '';
		}
		?>
		<input id="idpedido_<?php echo $value['id'] ?>" <?php echo $status ?> title="<?php echo $value['notap'] ?>" <?php echo $notaedit; ?> type="text" readonly  		
			onclick="nota('<?php echo $value['id'] ?>','<?php echo $value['nombre']; ?> ','<?php echo $value['notap'] ?>');" class="form-control" value="<?php echo $value['nombre']; ?>" >
		<span <?php echo $status ?> class="input-group-addon" id="basic-addon1" onclick="comandera.addcomple(<?php echo $value['id'] ?>);" ondblclick="comandera.desc_pedido({
						idorder: <?php echo $value['id'] ?>,
						idperson: <?php echo $objeto['persona'] ?>,
						idcomanda: <?php echo $objeto['id_comanda'] ?>,
						id_promocion: <?php echo $value['id_promocion'] ?>,
						precio: <?php echo $value['precio'] ?>,
						nombre: <?php echo "'".$value['nombre']."'" ?>,
						monto_desc: <?php echo "'".$value['monto_desc']."'" ?>
					})"
		><?php echo $value['precio'] ?></span>
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
						idcomanda: <?php echo $objeto['id_comanda'] ?>,
						id_promocion: <?php echo $value['id_promocion'] ?>
					})">
					<i class="fa fa-trash"></i> &nbsp;
				</button>			
				<?php
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
	if (!empty($value['promociones'])) {
		foreach ($value['promociones'] as $k => $v) { ?>
			<div class="input-group">
				<input type="text" disabled="1" class="form-control" <?php echo $status_promo?>; value="<?php echo $v['nombre'] ?>">
				<span class="input-group-addon"><?php echo $v['precio'] ?></span>
				<span class="input-group-btn">
				</span>
			</div><?php
		}
	}
} ?>

<script>
	function nota(id,nombre,nota){
		$('#pedidoN').text('');
		$('#idPedidoN, #notaT').val('');
		$('#modalNota').modal('show');
		$('#pedidoN').text(nombre);
		$('#idPedidoN').val(id);
		$('#notaT').val(nota);
		setTimeout(function (){
	        $('#notaT').focus();
	    }, 800);
		
	}
	function closenota(){
		$('#pedidoN').text('');
		$('#idPedidoN, #notaT').val('');
		$('#modalNota').modal('hide');

	}
	function savenota(){
		var id_pedido = $('#idPedidoN').val();
		var nota = $('#notaT').val();

		$.ajax({
			url : 'ajax.php?c=comandas&f=savenota',
			type : 'POST',
			dataType : 'json',
			data:{id_pedido:id_pedido,nota:nota}
		}).done(function(data) {
			console.log('-------');
			console.log(data);
			if(data == true){
				var $mensaje = 'Nota añadida';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 3000,
					className : 'success',
				});
				$("#idpedido_"+id_pedido).attr('title', nota);
			}else{
				var $mensaje = 'Error al añadir nota';
				$.notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 3000,
					className : 'error',
				});
			}
			closenota();
		});
		
	}


</script>


<div class="modal fade" id="modalNota" role="dialog" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onclick="closenota()">&times;</button>
				<h4 class="modal-title">Comentario</h4>								
			</div>
			<div class="modal-body">
				<input type="hidden" id="idPedidoN">
				<div><label id="pedidoN"></label></div>									
				<input class="col-md-12 form-control" type="text" id="notaT" autofocus="true">
				<br><br>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="closenota();">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="savenota();">Guardar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->





