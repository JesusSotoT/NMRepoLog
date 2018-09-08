<?php

session_start();

foreach ($pedidos_permanentes["pedidos"][$id]["comanda"] as $key => $value) {
	$hora=substr($value['inicioPedido'], 10, 6);
	
	$_SESSION['terminados'][$key]['mesa'] = $value['nombre_mesa'];
	$_SESSION['terminados'][$key]['hora'] = $hora;
	$_SESSION['eliminados'][$key]['mesa'] = $value['nombre_mesa'];
	$_SESSION['eliminados'][$key]['hora'] = $hora; ?>

	<div class="panel panel-info" id="comanda_<?php echo $key ?>">
		<div class="panel-heading" style="font-size: 25px">
				<i class="fa fa-cutlery"></i> <?php echo $key ?> &nbsp; / &nbsp;
				<i class="fa fa-object-group"></i> <?php echo $value['nombre_mesa'] ?> &nbsp; / &nbsp;
				<i class="fa fa-clock-o"></i> <?php echo $hora ?>
		</div>
		<div class="panel-body" id="personas"><?php
			foreach ($value['persona'] as $k => $v) { ?> 
				<div class="panel panel-default" id="persona_<?php echo $k ?>">
					<div class="panel-heading" style="font-size: 25px">
						<i class="fa fa-pencil-square-o"></i> <?php echo $k ?>
					</div>
					<div class="panel-body" id="pedidos"><?php
						foreach ($v['productos'] as $kk => $vv) {
					// Formateamos el pedido
							$pedido = $vv;
							$pedido['comanda'] = $key;
							$pedido['persona'] = $k;
							$pedido = json_encode($pedido);
							$pedido = str_replace('"', "'", $pedido);
							
							$preparacion = (!empty($vv['opcionalesDesc'])) ? 
								'<footer style="font-size: 20px">'.$vv['opcionalesDesc'].'</footer>' : '' ; 
							$preparacion .= (!empty($vv['adicionalesDesc'])) ?
								'<footer style="font-size: 20px">'.$vv['adicionalesDesc'].'</footer>' : '' ; 
							$preparacion .= (!empty($vv['sin_desc'])) ? 
								'<footer style="font-size: 20px">'.$vv['sin_desc'].$vv['nota_sin'].'</footer>' : '' ;
							$preparacion .= (!empty($value['desc_kit'])) ? 
								'<footer style="font-size: 15sipx">'.$value['desc_kit'].'</footer>' : '' ;  ?>
							
							<div class="row" id="pedido_<?php echo $vv['producto'] ?>" style="padding-top: 10px">
								<div class="col-xs-8">
									<blockquote>
										<p style="font-size: 25px"><?php echo '['.$vv['tiempo'].'] '.$vv['descripcion'] ?></p>
										<?php echo $preparacion ?>
									</blockquote> 
								</div>
								<div class="col-xs-4">
									<button id="loader_<?php echo $vv['producto'] ?>" onclick="pedidos.terminar(<?php echo $pedido ?>)" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-success btn-lg">
										<i class="fa fa-check"></i>
									</button>
									<button id="loader_eliminar_<?php echo $vv['producto'] ?>" onclick="pedidos.eliminar(<?php echo $pedido ?>)" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-danger btn-lg">
										<i class="fa fa-trash"></i>
									</button>
								</div>
							</div><?php
						} ?>
					</div>
				</div><?php
			} ?>
		</div>
	</div> <?php
} 

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
	setTimeout(buscar,10000);
</script>
