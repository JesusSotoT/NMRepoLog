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
					id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
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
// Si no hay productos muestra el mensaje
	} else { ?>
		<div align="center">
			<h3><span class="label label-default">* Intenta con otra palabra *</span></h3>
		</div><?php
	} ?>