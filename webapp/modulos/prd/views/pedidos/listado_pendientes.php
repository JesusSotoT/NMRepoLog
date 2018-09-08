<table id="tabla_listado_pedientes" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th><strong># <i class="fa fa-pencil"></i></strong></th>
			<th><strong><i class="fa fa-hashtag"></i></strong></th>
			<th><strong><i class="fa fa-pencil-square-o"></i></strong></th>
			<th><strong><i class="fa fa-object-group"></i></strong></th>
			<th><strong><i class="fa fa-user"></i></strong></th>
			<th><strong><i class="fa fa-clock-o"></i></strong></th>
			<th><strong><i class="fa fa-check"></i></strong></th>
			<th><strong><i class="fa fa-trash"></i></strong></th>
		</tr>
	</thead>
	<tbody><?php
	// $listado_pedidos es un array con los pedidos que viene del controller
		foreach ($listado_pedidos as $key => $value) {
		// Formateamos el pedido
			$pedido = $value;
			$pedido = json_encode($pedido);
			$pedido = str_replace('"', "'", $pedido);
		// Para llevar
			$clase = ($value['tipo'] == 1) ? 'danger' : '' ;
		// Servicio a domicilio
			$clase = ($value['tipo'] == 2) ? 'info' : $clase;
			
			$preparacion = (!empty($value['opcionalesDesc'])) ? 
								'<footer style="font-size: 15px">'.$value['opcionalesDesc'].'</footer>' : '' ; 
			$preparacion .= (!empty($value['adicionalesDesc'])) ?
								'<footer style="font-size: 15px">'.$value['adicionalesDesc'].'</footer>' : '' ; 
			$preparacion .= (!empty($value['sin_desc'])) ? 
								'<footer style="font-size: 15sipx">'.$value['sin_desc'].$value['nota_sin'].'</footer>' : '' ; 
			$preparacion .= (!empty($value['desc_kit'])) ? 
								'<footer style="font-size: 15sipx">'.$value['desc_kit'].'</footer>' : '' ; ?>
			
			<tr class="<?php echo $clase ?>" id="tr_listado_pendientes_<?php echo $value['producto'] ?>">
				<td><?php echo $value['producto'] ?></td>
				<td><?php echo $value['comanda'] ?></td>
				<td>
					<blockquote>
						<p style="font-size: 18px"><?php echo $value['descripcion'] ?></p>
						<?php echo $preparacion ?>
					</blockquote>
				</td>
				<td><?php echo $value['nombre_mesa'] ?></td>
				<td><?php echo $value['persona'] ?></td>
				<td><?php echo $value['tiempo'] ?></td>
				<td>
					<button id="loader_<?php echo $value['producto'] ?>" onclick="pedidos.terminar(<?php echo $pedido ?>)" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-success btn-lg">
						<i class="fa fa-check"></i>
					</button>
				</td>
				<td>
					<button id="loader_eliminar_<?php echo $value['producto'] ?>" onclick="pedidos.eliminar(<?php echo $pedido ?>)" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-danger btn-lg">
						<i class="fa fa-trash"></i>
					</button>
				</td>
			</tr> <?php
		} ?>
	</tbody>	
</table><?php

if ($ticket == 1 && !empty($pedidos_nuevos)) { ?>
	<script>
		pedidos.imprimir('', <?php echo json_encode($pedidos_nuevos) ?>);
	</script><?php
}

// Sonido de notificacion
if (!empty($pedidos_nuevos)) { ?>
	<script>
			var audioElement = document.createElement('audio');
			audioElement.setAttribute('src', 'sonidos/notificacion.ogg');
			audioElement.setAttribute('autoplay', 'autoplay');
			audioElement.addEventListener("load", function() {
				audioElement.play();
			}, true);
			audioElement.play();
	</script><?php
} ?>

<script>
	function buscar(){
		console.log('------> entra a Buscar');
		var $objeto = [];
		$objeto['id'] = pedidos.tipo;
		pedidos.comandas($objeto);
	}
	setTimeout(buscar, 10000);
</script>
