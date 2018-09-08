<?php
// Valida que existan productos
	if ($productos['rows']) {
		foreach($productos['rows'] as $value){ 
		// Comprueba si es platillo especial
			$clase = (!empty($value['especial'])) ? 'info' : 'default' ; ?>
			
			<div 
				class="pull-left"
				onclick="comandera.detalles_producto({
					div: 'div_productos',
					id_producto: <?php echo $value['idProducto'] ?>,
					id_comanda: comandera.datos_mesa_comanda.id_comanda,
					materiales: '<?php echo $value['materiales'] ?>',
					tipo: '<?php echo $value['tipo'] ?>',
					departamento: '<?php echo $value['idDep'] ?>',
					persona: comandera.datos_mesa_comanda['persona_seleccionada']
				})"
				style="padding:5px">
				<button 
					title="<?php echo $value['nombre'] ?>" 
					type="button" 
					class="btn btn-<?php echo $clase ?>" 
					style="width: 103px;height: 148px">
					<div class="row">
						<div class="col-md-12">
							<table>
								<tr>
									<td style="font-size: 12px" align="center">
										<?php echo substr($value['nombre'], 0, 25)  ?>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input 
								type="image" 
								alt=" " 
								style="width:80px;height:80px" 
								src="<?php echo $value['imagen'] ?>"/>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							$ <?php echo $value['precioventa'] ?>
						</div>
					</div>
				</button>
			</div><?php
		} ?><?php
		foreach ($datos as $key => $value) {
			$promocion = json_encode($value);
			$promocion = str_replace('"', "'", $promocion); ?>
			
			<div id="promocion_<?php echo $value['id_promocion'] ?>" class="pull-left" style="padding:5px">
				<button type="button" 
					onclick="comandera.listar_productos_promociones({
					promocion: <?php echo $promocion ?>,
					id: <?php echo $value['id_promocion'] ?>,
					div: 'div_productos_promocion',
					boton: 'promocion_<?php echo $value['id_promocion'] ?>'})"
					class="btn btn-default" 
					data-toggle="modal" 
					data-target="#modal_promocion"
					style="width: 120px;height: 60px;white-space: normal;">
					<div class="row">
						<div style="font-weight: bold;" class="">
							<?php echo $value['nombre'] ?>
						</div>
					</div>
				</button>
				
			</div><?php
		} 
// Si no hay productos muestra el mensaje
	} else if($datos) { ?>
		<?php
		foreach ($datos as $key => $value) {
			$promocion = json_encode($value);
			$promocion = str_replace('"', "'", $promocion); ?>
			
			<div id="promocion_<?php echo $value['id_promocion'] ?>" class="pull-left" style="padding:5px">
				<button type="button" 
					onclick="comandera.listar_productos_promociones({
					promocion: <?php echo $promocion ?>,
					id: <?php echo $value['id_promocion'] ?>,
					div: 'div_productos_promocion',
					boton: 'promocion_<?php echo $value['id_promocion'] ?>'})"
					class="btn btn-default" 
					data-toggle="modal" 
					data-target="#modal_promocion"
					style="width: 120px;height: 60px;white-space: normal;">
					<div class="row">
						<div style="font-weight: bold;" class="">
							<?php echo $value['nombre'] ?>
						</div>
					</div>
				</button>
				
			</div><?php
		} } else { ?>
		<div align="center">
			<h3><span class="label label-default">* Intenta con otra palabra *</span></h3>
		</div><?php
	} ?>