<div class="col-xs-12 GtableProductsContent qui"><?php
// Valida que existan kits
	if ($datos) {
		foreach ($datos as $key => $value) {
			$kit = json_encode($value);
			$kit = str_replace('"', "'", $kit); ?>
			
			<div id="kit_<?php echo $value['id_kit'] ?>" class="pull-left" style="padding:5px">
				<button 
					class="btn btn-default" 
					title="<?php echo($value['extras']) ?>" 
					onclick="comandas.listar_productos_kit({
								kit: <?php echo $kit ?>,
								id: <?php echo $value['id_kit'] ?>,
								div: 'div_productos_kit',
								boton: 'kit_<?php echo $value['id_kit'] ?>'
							})"
							style="width: 110px"
							data-toggle="modal" 
							data-target="#modal_kit" >
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
							$ <?php echo $value['precio'] ?>
						</div>
					</div>
				</button>
			</div><?php
		}
	} else { ?>
		<div align="center">
			<h3><span class="label label-default">* Sin kits *</span></h3>
		</div><?php
	} ?>
</div>