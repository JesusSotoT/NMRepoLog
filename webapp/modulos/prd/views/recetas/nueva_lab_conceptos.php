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
											<th align="center"><strong>Tipo</strong></th>
											<!-- <th><strong>Lim. Inf.</strong></th> -->
											<th align="center"><strong>Parametro</strong></th>
											<th align="center"><strong>Unidad</strong></th>
											<!-- <th align="center"><strong>Valor referencia</strong></th> -->
											<!-- <th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th> -->
										</tr>
									</thead>
									<tbody><?php
										foreach ($datos['lab_conceptos'] as $k => $v) { ?>
											<tr id="tr_<?php echo $v['id']; ?>" style="cursor: pointer">
												<td>
													<?php echo $v['tipo']; ?>
												</td>
												<td>
													<?php echo $v['parametro']; ?>
												</td>
												<td>
													<?php echo $v['unidad']; ?>
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
				<div class="col-md-9">
					<h3><small>Tipo:</small></h3>
							<div class="input-group input-group-lg" id="notificaciones">
						<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
						<select id="tipo_concepto" class="selectpicker" data-width="80%"><?php
							foreach ($datos['lab_tipos'] as $key => $value) {
								$select = ($datos['unidad_compra'] == $value['id']) ? 'selected' : '' ; ?>
								<option<?php echo $select ?> value="<?php echo $value['id'] ?>">
									<?php echo $value['descripcion'] ?>
								</option><?php
							} ?>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9 col-sm-9">
					<h3><small>Parámetro:</small></h3>
	        		<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-font"></i></span>
						<input id="parametro" type="text" class="form-control"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<h3><small>Valor numérico:</small></h3>
							<div class="input-group input-group-lg" id="notificaciones">
						<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
						<select id="is_numeric" class="selectpicker" data-width="80%">
							<option selected value="1">Si</option>
							<option value="0">No</option>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<h3><small>Unidad:</small></h3>
		       		<div class="input-group input-group-lg" id="notificaciones">
						<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
						<select id="unidad" class="selectpicker" data-width="80%">
							<?php
							foreach ($datos['lab_unidades'] as $key => $value) {
								$select = ($datos['unidad_compra'] == $value['id']) ? 'selected' : '' ; ?>
								<option<?php echo $select ?> value="<?php echo $value['id'] ?>">
									<?php echo $value['descripcion'] ?>
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
						id="btn_guardar_conceptos_lab"
						type="button"
						class="btn btn-success btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.guardar_lab_varias({
									tipo: 'lab_conceptos',
									tipo_concepto: $('#tipo_concepto').val(),
									parametro: $('#parametro').val(),
									is_numeric: $('#is_numeric').val(),
									unidad: $('#unidad').val(),
									btn:'btn_guardar_conceptos_lab'
								})">
						<i class="fa fa-check"></i> Ok
					</button>
					<button
						id="btn_actualizar_conceptos_lab"
						style="display: none"
						type="button"
						class="btn btn-primary btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.actualizar_conceptos_lab({
									id_concepto: $(this).attr('id_concepto'),
									parametro:$('#parametro').val(),
									is_numeric:$('#is_numeric').val(),
									unidad:$('#unidad').val(),
									btn:'btn_actualizar_conceptos_lab'
								})">
						<i class="fa fa-check"></i> Ok
					</button>
				</div>
			</div>
		</div>
	</div>
</div><!-- Fin lado derecho -->
