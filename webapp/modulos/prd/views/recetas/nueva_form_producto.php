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
								<strong>Insumos / Formulación de Productos</strong>
							</h4>
						</div>
						<div id="tab_insumos" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_insumos">
							<div class="panel-body">
								<table id="tabla_insumos" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th><strong>Código</stong></th>
											<th align="center"><strong>Insumo</strong></th>
											<th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
										</tr>
									</thead>
									<tbody><?php
										foreach ($datos['insumos'] as $k => $v) { ?>
											<tr
												id="tr_<?php echo $v['idProducto'] ?>"
												onclick="recetas.agregar_insumos_producto({
													id:<?php echo $v['idProducto'] ?>,
													codigo:'<?php echo $v['codigo'] ?>',
													nombre:'<?php echo $v['nombre']?>',
													unidad_nombre:'<?php echo $v['unidad']?>',
													idunidad:'<?php echo $v['unidad_codigo']?>',
													unidad_clave:'<?php echo $v['unidad_clave']?>',
													div:'div_insumos_producto_agregados',
													check:$('#check_<?php echo $v['idProducto'] ?>').prop('checked')
												})"
												style="cursor: pointer">
												<td>
													<?php echo $v['codigo']?>
												</td>
												<td align="center">
													<?php echo $v['nombre'] ?>
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
?>
</div> <!-- Fin lado izquierdo -->
<div class="col-md-7">
	<div class="panel panel-<?php echo $panel ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<strong>
					Insumos
				</strong>
			</h4>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12 col-sm-12" id="div_insumos_producto_agregados">
				<!-- En esta div se cargan los insumos de la receta -->
					<br /><br />
					<blockquote style="font-size: 16px">
				    	<p>
				      		Selecciona <strong>"Insumos"</strong> para agregarlos.
				    	</p>
				    </blockquote>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9 col-sm-9">
					<h3><small>Nombre:</small></h3>
	        		<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-font"></i></span>
						<input id="nombre" type="text" class="form-control"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9 col-sm-9">
					<h3><small>Codigo:</small></h3>
	        		<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
						<input size="25" id="codigo" type="text" class="form-control"/>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 col-sm-6">
					<h3><small>Factor minimo de producción:</small></h3>
	        		<div class="input-group input-group-lg">
						<input id="factor" type="text" class="form-control" value="1" onchange="checamultiplof();" />
						<span class="input-group-addon"><i class="fa fa-slack"></i></span>
					</div>
				</div>
				<div class="col-md-6 col-sm-6">
					<h3><small>Cantidad mínima de producción:</small></h3>
	        		<div class="input-group input-group-lg">
						<input id="cant_minima" type="text" class="form-control" onchange="checamultiplo();" />
						<span class="input-group-addon"><i class="fa fa-slack"></i></span>
					</div>
				</div>
				<div class="col-md-6">
					<h3><small>Unidad:</small></h3>
		       		<div class="input-group input-group-lg" id="notificaciones">
								<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
								<select id="unidad_compra_venta" class="selectpicker" data-width="80%" style="height: 46px;"><?php
									foreach ($datos['unidades'] as $key => $value) {
										?>
										<option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php
									} ?>
								</select>
							</div>
				</div>
			</div>

			<br />
			<div class="row">
				<div class="col-md-8 col-sm-8">
					<button
						id="btn_guardar_receta"
						type="button"
						class="btn btn-success btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.guardar_insumos_producto({
									btn:'btn_guardar_insumos_producto',
									nombre: $('#nombre').val(),
									codigo: $('#codigo').val(),
									cant_min: $('#cant_minima').val(),
									factor:$('#factor').val(),
									btn: 'btn_guardar_receta',
									unidad: $('#unidad_compra_venta').val()
								})">
						<i class="fa fa-check"></i> Ok
					</button>
					<button
						id="btn_actualizar"
						style="display: none"
						type="button"
						class="btn btn-primary btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.actualizar_form_producto({
									id_receta: $(this).attr('id_receta'),
									codigo:$('#codigo').val(),
									nombre:$('#nombre').val(),
									cant_min:$('#cant_minima').val(),
									factor:$('#factor').val(),
									btn:'btn_actualizar'
								})">
						<i class="fa fa-check"></i> Ok
					</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$('#unidad_compra_venta').select2();
		
		
	</script>
</div><!-- Fin lado derecho -->
