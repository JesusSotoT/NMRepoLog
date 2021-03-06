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
								<strong>Procesos de producción</strong>
							</h4>
						</div>
						<div id="tab_insumos" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_insumos">
							<div class="panel-body">
								<table id="tabla_insumos" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th align="center"><strong>Orden</strong></th>
											<th><strong>Nombre</strong></th>
											<th align="center"><strong>Tiempo hrs</strong></th>
											<th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
										</tr>
									</thead>
									<tbody><?php
										foreach ($datos['procesos_produccion'] as $k => $v) { ?>
											<tr id="tr_<?php echo $v['id'] ?>" onclick="recetas.agregar_proceso({id:<?php echo $v['id'] ?>,nombre:'<?php echo $v['nombre'] ?>', tiempo_hrs: <?php echo $v['tiempo_hrs'] ?>, div:'div_insumos_agregados',check:$('#check_<?php echo $v['id'] ?>').prop('checked')})" style="cursor: pointer">
												<td align="center">
													<?php echo $v['id'] ?>
												</td>
												<td>
													<?php echo $v['nombre'] ?>
												</td>
												<td align="center">
													 <?php echo number_format($v['tiempo_hrs'], 2, ':', ''); ?>
												</td>
												<td align="center">
													<input style="cursor: pointer" disabled="1" type="checkbox" id="check_<?php echo $v['id'] ?>" />
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
			recetas.convertir_dataTable({id: 'tabla_insumos'});
		</script><?php
	}?>
</div> <!-- Fin lado izquierdo -->
<div class="col-md-7">
	<div class="panel panel-<?php echo $panel ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<strong>
					Procesos de Producción
				</strong>
			</h4>
		</div>
		<div class="panel-body">
			<div class="row">
			<!-- En esta div se cargan los insumos de la receta -->
				<div class="col-md-12 col-sm-12" id="div_insumos_agregados">
					<br /><br />
					<blockquote style="font-size: 16px">
				    	<p>
				      		Seleccione un <strong>"producto"</strong>
							y asígnele <strong>"procesos de producción"</strong>.
				    	</p>
				    </blockquote>
				</div>
			</div>
			<!-- Productos-->
			<div class="row">
				<div class="col-md-6">
					<h3><small>Producto:</small></h3>
							<div class="input-group input-group-lg" id="notificaciones">
								<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
								<select id="sel_productos" class="selectpicker" data-width="80%"><?php
									foreach ($datos['insumos_preparados_formula'] as $key => $value) {
										$select = ($datos['productos_formula'] == $value['id']) ? 'selected' : '' ; ?>
										<option<?php echo $select ?> value="<?php echo $value['idProducto'] ?>">
											<?php echo $value['nombre'] ?>
										</option><?php
									} ?>
								</select>
							</div>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-8 col-sm-8">
					<button
						id="btn_guardar_receta_prd"
						type="button"
						class="btn btn-success btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.guardar_procesos_produccion({
									btn:'btn_guardar_receta_prd',
									prd: $('#sel_productos').val()
								})">
						<i class="fa fa-check"></i> Ok
					</button>
					<button
						id="btn_actualizar"
						style="display: none"
						type="button"
						class="btn btn-primary btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.actualizar_procesos_produccion_producto({
									btn:'btn_actualizar',
									prd: $('#sel_productos').val()
								})">
						<i class="fa fa-check"></i> Ok
					</button>
				</div>
			</div>
			<!-- Familias productos -->
			<div id="familias">
				<div class="row">
				<div class="col-md-6">
					<h3><small>Familia de productos:</small></h3>
							<div class="input-group input-group-lg" id="notificaciones">
								<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
								<select id="sel_familias" class="selectpicker" data-width="80%"><?php
									foreach ($datos['familias_productos'] as $key => $value) {
										$select = ($datos['familias_productos'] == $value['id']) ? 'selected' : '' ; ?>
										<option<?php echo $select ?> value="<?php echo $value['id'] ?>">
											<?php echo $value['nombre'] ?>
										</option><?php
									} ?>
								</select>
							</div>
				</div>
			</div>
				<br />

				<div class="row">
				<div class="col-md-8 col-sm-8">
					<button
						id="btn_guardar_receta_fam"
						type="button"
						class="btn btn-success btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.guardar_procesos_produccion({
									btn:'btn_guardar_receta_fam',
									prd: $('#sel_familias').val()
								})">
						<i class="fa fa-check"></i> Ok
					</button>
					<button
						id="btn_actualizar"
						style="display: none"
						type="button"
						class="btn btn-primary btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.actualizar_procesos_produccion_producto({
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
	</div>
	<script>
		$('#sel_productos').select2();
		$('#sel_familias').select2();
	</script>
</div><!-- Fin lado derecho -->
