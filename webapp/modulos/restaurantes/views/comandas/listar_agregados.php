<div class="row"><?php	
// Pedidos es una variable que viene desde el controlador
	foreach ($pedidos as $key => $value) {?>
		<div id="agregado_<?php echo $key ?>" class="pull-left" style="padding:5px">
		<!-- clase es una variable que viene desde el controlador -->
			<button 
				type="button" 
				class="btn btn-<?php echo $clase ?>"  
				onclick='comandas.quitar_pedido({
						pedido:<?php echo json_encode($value) ?>, 
						id:"<?php echo $key ?>", 
						persona:$("#persona").val(), 
						div:"div_pedidos_personalizado"
				})' 
				style="width: 110px">
				<div class="row">
					<div class="col-xs-12">
						<?php echo substr($value['nombre'], 0, 9); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<input type="image" style="width:80px;height:80px" src="../pos/<?php echo $value['imagen'] ?>"/>
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