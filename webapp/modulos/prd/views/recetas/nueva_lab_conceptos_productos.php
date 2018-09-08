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
								<strong>Parámetros de laboratorio</strong>
							</h4>
						</div>
						<div id="tab_insumos" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_insumos">
							<div class="panel-body">
								<table id="tabla_insumos" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th align="center"><strong>Tipo</strong></th>
											<th><strong>Parámetro</strong></th>
											<th align="center"><strong>Unidad</strong></th>
											<th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
										</tr>
									</thead>
									<tbody><?php
										foreach ($datos['lab_conceptos'] as $k => $v) { ?>
											<tr id="tr_<?php echo $v['id'] ?>"
												onclick="recetas.agregar_parametro({
													id:<?php echo $v['id'] ?>,
													parametro:'<?php echo $v['parametro'] ?>',
													is_numeric:<?php echo $v['is_numeric'] ?>,
													unidad:'<?php echo $v['unidad']?>',
													div:'div_insumos_agregados',
													check:$('#check_<?php echo $v['id'] ?>').prop('checked')
												})"
												style="cursor: pointer">
												<td align="center">
													<?php echo $v['tipo'] ?>
												</td>
												<td>
													<?php echo $v['parametro'] ?>
												</td>
												<td align="center">
													 <?php echo $v['unidad']; ?>
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
					Parámetros de Laboratorio
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
				      		Seleccione un <strong>producto</strong>
							y establezca sus respectivos <strong>parámetros</strong>.
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
									foreach ($datos['productos_formula'] as $key => $value) {
										$select = ($datos['productos_terminados'] == $value['id']) ? 'selected' : '' ; ?>
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
						id="btn_guardar_conceptos_lab_producto"
						type="button"
						class="btn btn-success btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.guardar_lab_conceptos_productos({
									btn:'btn_guardar_conceptos_lab_producto',
									prd: $('#sel_productos').val()
								})">
						<i class="fa fa-check"></i> Ok
					</button>
					<button
						id="btn_actualizar_conceptos_lab_productos"
						style="display: none"
						type="button"
						class="btn btn-primary btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.actualizar_conceptos_lab_productos({
									prd: $('#sel_productos').val(),
									btn:'btn_actualizar_conceptos_lab_productos'
								})">
						<i class="fa fa-check"></i> Ok
					</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$('#sel_productos').select2();
	</script>
</div><!-- Fin lado derecho -->
