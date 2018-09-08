<div class="row"><?php
	foreach ($_SESSION['cerrar_personalizado']['pedidos'] as $key => $value) { ?>
		<div id="pedido_<?php echo $key ?>" class="pull-left" style="padding:5px">
			<button 
				type="button" 
				class="btn btn-default" 
				title="<?php echo($value['extras']) ?>"  
				onclick='comandas.agregar_pedido({
						pedido:<?php echo json_encode($value) ?>, 
						id: "<?php echo $key ?>", 
						persona: $("#persona").val(), 
						div: "div_agregados_personalizado", 
						clase: $("#clase").val(), 
						boton: "pedido_<?php echo $key ?>"
				})' 
				style="width: 110px">
				<div class="row">
					<div class="col-xs-12">
						<?php echo substr($value['nombre'], 0, 9); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<input type="image" style="width:80px;height:80px" src="<?php echo $value['imagen'] ?>"/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						$ <?php echo $value['precioventa'] ?>
					</div>
				</div>
			</button>
		</div><?php
	} ?>
</div>
