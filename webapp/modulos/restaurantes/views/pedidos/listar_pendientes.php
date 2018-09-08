<?php

session_start(); ?>
<div class="row" style="height: 100%;">
	<div class="col-md-1 col-xs-1" style="height: 100%; display: table;">
		<div style="display: table-cell;
    		vertical-align: middle;">
		    <i
			    class="fa fa-caret-left"
			    style="color: #DCB435; float:right; font-size: 11vw;"
			    onclick="pedidos.mover_scroll({
				    direccion: 'izquierda',
				    div: 'div_pend',
				    cantidad: 600
			    })">
			</i>
		</div>
	</div>
	<div class="col-md-10 col-xs-10 div_scroll_x" id="div_pend" style="height: 100%;">
	<?php foreach ($pedidos_permanentes["pedidos"][$id]["comanda"] as $key => $value) {
		$hora=substr($value['inicioPedido'], 10, 6);
		$segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($value['inicioPedido']);
		$minutos = $segundos / 60;
		$_SESSION['terminados'][$key]['mesa'] = $value['nombre_mesa'];
		$_SESSION['terminados'][$key]['hora'] = $hora;
		$_SESSION['eliminados'][$key]['mesa'] = $value['nombre_mesa'];
		$_SESSION['eliminados'][$key]['hora'] = $hora; ?>
				<div
					class="btn btn-coman" id="ped-<?php echo $value['comanda']?>" style="height: 65vh; cursor: auto; margin: 5px">
					<div class="row" style="width:250px;">       
					   <div class="row" style="height: 8vh; padding:0;border:solid #714789;  border-top-left-radius: 2em;  border-top-right-radius: 2em;margin:0; <?php if($minutos > $ajustes['time_amarillo']) { ?> background-color: #DCB435; <?php } if($minutos > $ajustes['time_rojo']) {?>background-color: #bf2334; <?php } ?>">
					   		<div class="col-md-6 col-xs-6" style="text-align:left; font-size: 2.5vh;">
					   			<span style="margin-left: 15px"><strong style="font-weight: bold">Comanda: </strong><?php echo $value['comanda']?></span><br>
					   			
					   		</div>
					   		<div class="col-md-6 col-xs-6" style="text-align:right;  font-size: 2.5vh;">
					   			<span style="margin-right: 15px"><strong style="font-weight: bold"><?php $h = explode(" ", $value['inicioPedido']); echo $h[1]?></strong></span>
					   		</div>
					   		<div class="col-md-12 col-xs-12" style="text-align:center;  font-size: 2.5vh; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
					   			<span ><strong style="font-weight: bold"><?php if($value['tipo'] != 1 && $value['tipo'] != 2) { ?>Mesa <?php } ?><?php echo $value['nombre_mesa']?></strong></span><br>
					   		</div>
					   </div>
					   <div class="row" style="height: 54vh;padding:0;border-bottom :solid #714789; border-left :solid #714789; border-right :solid #714789; border-bottom-left-radius: 2em;  border-bottom-right-radius: 2em;margin:0;">
					   		<div class="row" style="height: 46vh; margin: 0; overflow-y: auto;">
					   			<?php foreach ($value['persona'] as $k => $v) { ?>
					   				<span style="font-weight: bold; ">Persona <?php echo $k ?></span>
				   					<?php foreach ($v['productos'] as $kk => $vv) {
				   						// Formateamos el pedido
										$pedido = $vv;
										$pedido['comanda'] = $key;
										$pedido['persona'] = $k;
										$pedido = json_encode($pedido);
										$pedido = str_replace('"', "'", $pedido);
										
										$preparacion = (!empty($vv['opcionalesDesc'])) ? 
											'<footer style="font-size: 12px">'.$vv['opcionalesDesc'].'</footer>' : '' ; 
										$preparacion .= (!empty($vv['adicionalesDesc'])) ?
											'<footer style="font-size: 12px">'.$vv['adicionalesDesc'].'</footer>' : '' ; 
										$preparacion .= (!empty($vv['sin_desc'])) ? 
											'<footer style="font-size: 12px">'.$vv['sin_desc'].'</footer>' : '' ;
										$preparacion .= (!empty($value['desc_kit'])) ? 
											'<footer style="font-size: 12px">'.$value['desc_kit'].'</footer>' : '' ;  ?>
								   		<div class="row" id="pedido_<?php echo $vv['producto'] ?>" style="padding-top: 10px; margin: 0;">
											<div class="col-md-8 col-xs-8">
													<p style="font-size: 15px; font-weight: bold; white-space: normal; text-align:left;"><?php echo '1x '.$vv['descripcion'] ?>															   
													</p>
											</div>											
											<div class="col-md-4 col-xs-4" style="padding:0;">
												<button style="padding: 3px 6px;" id="loader_<?php echo $vv['producto'] ?>" onclick="pedidos.terminar(<?php echo $pedido ?>)" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-success btn-loader">
													<i class="fa fa-check"></i>
												</button>
												<button 
													style="padding: 3px 6px;"
													id="loader_eliminar_<?php echo $value['producto'] ?>" 
													data-toggle="modal" 
													data-target="#modal_eliminar_pedido"
													onclick="pedidos.pedido_seleccionado = <?php echo $pedido ?>" 
													data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
													class="btn btn-danger">
													<i class="fa fa-trash"></i>
												</button>
											</div>											
											<div class="col-md-12 col-xs-12" style="font-size: 15px; text-align: left">
												<p><?php echo $preparacion ?></p>
											</div>
											<div class="col-md-12 col-xs-12">
												<?php echo $vv['notap'] ?>
											</div>
										</div>	
								   	<?php } ?>
								<?php } ?>
					   		</div>
					   		<div class="row" style="height: 7vh; margin-top: 1vh;">
					   			<buttom class="btn btn-success" onclick="pedidos.terminartodo(<?php echo $value['comanda']?>);" style="width: 60%; font-size: 2.4vh">TERMINAR</buttom>
					   		</div>
						</div>
					</div> 
					
				</div>
	<?php } ?>
	</div>
	<div class="col-md-1 col-xs-1" id="div_mover_scroll" style="height: 100%; display:table">
		<div style="display: table-cell;
    		vertical-align: middle;">
		    <i
			    class="fa fa-caret-right fa-4x"
			    style="color: #DCB435;font-size: 11vw;"
			    onclick="pedidos.mover_scroll({
				    direccion: 'derecha',
				    div: 'div_pend',
				    cantidad: 600
			    })">
			</i>
		</div>
	</div>
</div>
<?php 
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
	$("#div_pend").scrollLeft(pedidos.scrollL);
</script>
