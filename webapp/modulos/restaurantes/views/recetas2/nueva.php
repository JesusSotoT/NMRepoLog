<?php
// Valida que existan productos o recetas
	if (empty($datos)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>

<div class="col-md-5"><?php
// Valida que existan productos
	if (!empty($datos['insumos'])) { ?>
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="panel-group" id="accordion_insumos" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div hrefer class="panel-heading" id="heading_insumos" role="tab" role="button" style="cursor: pointer" data-toggle="collapse" data-parent="#accordion_insumos" href="#tab_insumos" aria-controls="collapse_insumos" aria-expanded="true">
							<h4 class="panel-title">
								<strong>Insumos</strong>
							</h4>
						</div>
						<div id="tab_insumos" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_insumos">
							<div class="panel-body">
								<table id="tabla_insumos" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>											
										<tr>
											<th align="center"><strong>Insumo</strong></th>
											<th><strong>Unidad</strong></th>
											<th align="center"><strong>Costo</strong></th>
											<th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
										</tr>
									</thead>
									<tbody><?php
										foreach ($datos['insumos'] as $k => $v) { ?>
											<tr 
												id="tr_<?php echo $v['idProducto'] ?>" 
												onclick="recetas.agregar_insumo2({
													id:<?php echo $v['idProducto'] ?>,
													nombre:'<?php echo $v['nombre'] ?>',
													unidad:'<?php echo $v['unidad'] ?>',
													unidad_compra:<?php echo $v['idunidadCompra'] ?>,
													costo:<?php echo $v['costo'] ?>,
													id_unidad:<?php echo $v['idunidad'] ?>,
													ids_proveedor:'<?php echo $v['ids_proveedor'] ?>',
													proveedor_select:'<?php echo $v['proveedor_select'] ?>',
													costos:'<?php echo $v['costos'] ?>',
													div:'div_insumos_agregados',
													vista: 'listar_productos_agregados_combos_recetas',
													check:$('#check_<?php echo $v['idProducto'] ?>').prop('checked'),
													grupo_recetas: $('#grupo_recetas').val()
												})" 
												style="cursor: pointer">
												<td align="center">
													<?php echo $v['nombre'] ?>
												</td>
												<td>
													<?php echo $v['unidad'] ?>
												</td>
												<td align="center">
													$ <?php echo number_format($v['costo'], 2, '.', ''); ?>
												</td> 
												<td align="center">
													<input 
														style="cursor: pointer" 
														disabled="1" 
														type="checkbox" 
														id="check_<?php echo $v['idProducto'] ?>" />
												</td>
											</tr><?php
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			recetas.convertir_dataTable({id: 'tabla_insumos', botones: 2});
		</script><?php
	}

// Valida que existan recetas
	if (!empty($datos['insumos_preparados'])) { ?>
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="panel-group" id="accordion_insumos_preparados" role="tablist" aria-multiselectable="true">
					<div class="panel panel-info">
						<div hrefer class="panel-heading" id="heading_insumos_preparados" role="tab" role="button" style="cursor: pointer" data-toggle="collapse" data-parent="#accordion_insumos_preparados" href="#tab_insumos_preparados" aria-controls="collapse_insumos_preparados" aria-expanded="true">
							<h4 class="panel-title">
								<strong>Insumos preparados</strong>
							</h4>
						</div>
						<div id="tab_insumos_preparados" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_insumos_preparados">
							<div class="panel-body">
								<table id="tabla_insumos_preparados" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th align="center"><strong>Insumo</strong></th>
											<th align="center"><strong>Costo</strong></th>
											<th><strong>Preparacion</strong></th>
											<th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
										</tr>
									</thead>
									<tbody><?php
										foreach ($datos['insumos_preparados'] as $k => $v) {
											$preparacion =	utf8_decode($value["preparacion"]); ?>
											
											<tr id="tr_preparado_<?php echo $v['idProducto'] ?>" onclick="recetas.agregar_insumo_preparado({insumos_preparados:'<?php echo $v['insumos_preparados'] ?>', preparacion:'<?php echo $preparacion ?>', preparado:1, id:<?php echo $v['idProducto'] ?>, nombre:'<?php echo $v['nombre'] ?>', unidad:'<?php echo $v['unidad'] ?>', unidad_compra:<?php echo $v['idunidadCompra'] ?>, costo:<?php echo $v['costo'] ?>, id_unidad:<?php echo $v['idunidad'] ?>, div:'div_insumos_agregados', check:$('#check_preparado_<?php echo $v['idProducto'] ?>').prop('checked')})" style="cursor: pointer">
												<td align="center">
													<?php echo $v['nombre'] ?>
												</td>
												<td>
													$ <?php echo number_format($v['costo'], 2, '.', ''); ?>
												</td>
												<td align="center">
													<?php echo $v['preparacion'] ?>
												</td> 
												<td align="center">
													<input style="cursor: pointer" disabled="1" type="checkbox" id="check_preparado_<?php echo $v['idProducto'] ?>" />
												</td>
											</tr><?php
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
		// Convierte la tabla en DataTable
			recetas.convertir_dataTable({id: 'tabla_insumos_preparados', botones: 2});
		</script><?php
	} ?>
</div> <!-- Fin lado izquierdo -->
<div class="col-md-7">
	<div class="panel panel-<?php echo $panel ?>">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-6" align="left">
					<h4 class="panel-title">
						<strong>
							Receta / Insumo preparado
						</strong>
					</h4>
				</div>
				<div class="col-md-6" align="right">
					<button 
						id="btn_agregar_grupo_recetas" type="button" 
						class="btn btn-success btn-lg" 
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
						onclick="recetas.agregar_grupo_recetas({div: 'div_insumos_agregados'})">
						<i class="fa fa-plus"></i> Grupo
					</button>
				</div>
			</div>			
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12 col-sm-12" id="div_insumos_agregados">
				<!-- En esta div se cargan los insumos de la receta -->
					<br /><br />
					<blockquote style="font-size: 16px">
				    	<p>
				      		Selecciona <strong>"Insumos"</strong> 
							o <strong>"Insumos preparados"</strong> para agregarlos.
				    	</p>
				    </blockquote>
				</div>
			</div>

			<input id="grupo_recetas" type="number" style="display: none" value="<?php echo $grupo_recetas ?>" />

			<div class="row">
				<div class="col-md-9 col-sm-9">
					<h3><small>Nombre:</small></h3>
	        		<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-font"></i></span>
						<input id="nombre" type="text" class="form-control"/>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<h3><small>Ganancia:</small></h3>
	        		<div class="input-group input-group-lg">
						<input onchange="recetas.calcular_ganancia({porcentaje:$(this).val()})" id="margen_ganancia" type="number" class="form-control"/>
						<span class="input-group-addon"><i class="fa fa-percent"></i></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9 col-sm-9">
					<h3><small>Codigo:</small></h3>
	        		<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
						<input onchange="recetas.validar_codigo({id:'codigo'})" size="25" id="codigo" type="text" class="form-control"/>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<h3><small>Precio:</small></h3>
	        		<div class="input-group input-group-lg">
						<span class="input-group-btn">
							<button id="btn_precio_venta" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" onclick="recetas.restaurar_precio({btn: 'btn_precio_venta', id: $(this).attr('id_receta')})" class="btn btn-danger" type="button">
								<i class="fa fa-undo"></i>
							</button>
						</span>
						<input id="precio_venta" type="number" class="form-control"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<h3><small>Unidad de compra:</small></h3>
		       		<div class="input-group input-group-lg" id="notificaciones">
						<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
						<select id="unidad_compra" class="selectpicker" data-width="80%"><?php
							foreach ($datos['unidades'] as $key => $value) {
								$select = ($datos['unidad_compra'] == $value['id']) ? 'selected' : '' ; ?>
								<option<?php echo $select ?> value="<?php echo $value['id'] ?>">
									<?php echo $value['nombre'] ?>
								</option><?php
							} ?>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<h3><small>Unidad de venta:</small></h3>
		       		<div class="input-group input-group-lg" id="notificaciones">
						<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
						<select id="unidad_venta" class="selectpicker" data-width="80%"><?php
							foreach ($datos['unidades'] as $key => $value) {
								$select = ($datos['unidad_venta'] == $value['id']) ? 'selected' : '' ; ?>
								<option<?php echo $select ?> value="<?php echo $value['id'] ?>">
									<?php echo $value['nombre'] ?>
								</option><?php
							} ?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-sm-4">
					<h3><small>Tipo:</small></h3>
		       		<div class="input-group input-group-lg" id="notificaciones">
						<span class="input-group-addon"><i class="fa fa-edit"></i></span>
						<select id="tipo" class="selectpicker" data-width="80%">
							<option selected value="1">Receta</option>
							<option value="2">Insumo preparado</option>
						</select>
					</div>
				</div>
				<div class="col-md-8 col-sm-8">
					<h3><small>Preparacion:</small></h3>
					<textarea class="form-control" id="preparacion" rows="2" placeholder="Deja aqui tus comentarios :)"></textarea>
				</div>
			</div><br />
			<div class="row">
				<div class="col-md-8 col-sm-8">
					<button 
						id="btn_guardar_receta" 
						type="button" 
						class="btn btn-success btn-lg" 
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
						onclick="recetas.guardar({
									codigo:$('#codigo').val(),
									nombre:$('#nombre').val(),
									precio_venta:$('#precio_venta').val(),
									margen_ganancia:$('#margen_ganancia').val(),
									tipo:$('#tipo').val(),
									unidad_venta:$('#unidad_venta').val(),
									unidad_compra:$('#unidad_compra').val(),
									preparacion:$('#preparacion').val(),
									btn:'btn_guardar_receta'
								})">
						<i class="fa fa-check"></i> Ok
					</button>
					<button 
						id="btn_actualizar" 
						style="display: none" 
						type="button" 
						class="btn btn-primary btn-lg" 
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
						onclick="recetas.actualizar({
									id_receta: $(this).attr('id_receta'),
									codigo:$('#codigo').val(),
									nombre:$('#nombre').val(),
									precio_venta:$('#precio_venta').val(),
									margen_ganancia:$('#margen_ganancia').val(),
									tipo:$('#tipo').val(),
									unidad_venta:$('#unidad_venta').val(),
									unidad_compra:$('#unidad_compra').val(),
									preparacion:$('#preparacion').val(),
									btn:'btn_actualizar'
								})">
						<i class="fa fa-check"></i> Ok
					</button>
				</div>
			</div>
		</div>
	</div>
</div><!-- Fin lado derecho -->