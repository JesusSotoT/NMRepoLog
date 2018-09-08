<style>
	.wrapPro {
	    word-wrap: break-word;
	    position:justify;
	    font-size: 11px;
	    width: 80%;
	    padding: 10px;
	    height: auto;
	    overflow-x: auto;
	}
</style>
<div class="row">
	<div class="col-xs-2"><?php
		foreach($objeto['kit']['productos'] as $value){ 
		// Comprueba si es platillo especial
			$clase = (!empty($value['materiales'])) ? 'warning' : 'default' ; ?>
			
			<div onclick="comandas.detalles_producto({
							idProduct: <?php echo $value['idProducto'] ?>,
							id_comanda: <?php echo $objeto['kit']['id_comanda'] ?>,
							materiales: '<?php echo $value['materiales'] ?>',
							tipo: '<?php echo $value['tipo'] ?>',
							div: 'div_detalles_producto_kit'
						})"
				class = "pull-left" 
				style = "padding:5px" 
				idproducto = "<?php echo $value['idProducto'] ?>" 
				idcomanda = "<?php echo $objeto['comanda'] ?>" 
				materiales = "<?php echo $value['materiales'] ?>" 
				tipo = "<?php echo $value['tipo'] ?>" 
				iddep = "<?php echo $value['id_departamento'] ?>">
				<button type="button" class="btn btn-<?php echo $clase ?>" style="width: 103px;height: 148px">
					<div class="row">
						<div style="width:85px;" class="wrapPro">
							<?php echo substr($value['nombre'], 0, 25)  ?>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<input type="image" alt=" " style="width:80px;height:80px" src="../pos/<?php echo $value['imagen'] ?>"/>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							$ <?php echo $value['precio'] ?>
						</div>
					</div>
				</button>
			</div><?php
		} ?>
	</div>
	<div id="div_detalles_producto_kit" class="col-xs-10">
		<!-- En esta div se cargan los detalles del producto -->
	</div>
</div>
