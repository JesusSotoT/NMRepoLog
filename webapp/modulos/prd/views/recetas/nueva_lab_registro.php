<?php
// Valida que existan productos o recetas
	if (empty($datos)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php

		return 0;
	} ?>

 <!-- Fin lado izquierdo -->
<div class="col-md-12">
	<div class="panel panel-<?php echo $panel ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<strong>
					Liberación de producto terminado
				</strong>
			</h4>
		</div>
		<div class="panel-body">
			<!-- Productos-->
			<div class="row">
				<div class="col-md-6">
					<h3><small>Producto:</small></h3>
							<div class="input-group input-group-lg" id="notificaciones">
								<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
								<select id="sel_productos" class="selectpicker" onchange="recetas.cargar_formulario_lab({div:'div_insumos_agregados', producto:$(this).val()})" data-width="80%"><?php
									foreach ($datos['insumos_preparados_formula'] as $key => $value) {
										$select = ($datos['productos_terminados'] == $value['id']) ? 'selected' : '' ; ?>
										<option<?php echo $select ?> value="<?php echo $value['idProducto'] ?>">
											<?php echo $value['nombre'] ?>
										</option><?php
									} ?>
								</select>
							</div>
				</div>
			</div>
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

			<div class="row">
				<div class="col-md-3 col-sm-3">
					<h3><small>Orden de Producción:</small></h3>
	        		<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-font"></i></span>
						<input id="txt_orden_produccion" type="text" class="form-control"/>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<h3><small>Número de mezclas:</small></h3>
	        		<div class="input-group input-group-lg">
						<input id="txt_numero_mezclas" type="number" class="form-control" value="0"/>
						<span class="input-group-addon"><i class="fa fa-percent"></i></span>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<h3><small>Fecha de elaboración:</small></h3>
	        		<div class="input-group input-group-lg">
						<input id="txt_fecha_elaboracion" type="date" class="form-control"/ value="<?php echo date("Y-m-d")?>">
						<span class="input-group-addon"><i class="fa fa-percent"></i></span>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<h3><small>Fecha de recepción:</small></h3>
	        		<div class="input-group input-group-lg">
						<input id="txt_fecha_recepcion" type="date" class="form-control"/ value="<?php echo date("Y-m-d")?>">
						<span class="input-group-addon"><i class="fa fa-percent"></i></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3 col-sm-3">
					<h3><small>Fecha de Liberación:</small></h3>
	        		<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-font"></i></span>
						<input id="txt_fecha_liberacion" type="date" class="form-control" value="<?php echo date("Y-m-d")?>"/>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<h3><small>Fecha de Caducidad:</small></h3>
	        		<div class="input-group input-group-lg">
						<input id="txt_fecha_caducidad" type="date" class="form-control" value="<?php echo date("Y-m-d")?>"/>
						<span class="input-group-addon"><i class="fa fa-percent"></i></span>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<h3><small>Fecha de Análisis:</small></h3>
	        		<div class="input-group input-group-lg">
						<input id="txt_fecha_analisis" type="date" class="form-control" value="<?php echo date("Y-m-d")?>"/>
						<span class="input-group-addon"><i class="fa fa-percent"></i></span>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<h3><small>Lote de Análisis:</small></h3>
	        		<div class="input-group input-group-lg">
						<input id="txt_lote_analisis" type="text" class="form-control"/>
						<span class="input-group-addon"><i class="fa fa-percent"></i></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3 col-sm-3">
					<h3><small>Lote de Fabricación:</small></h3>
	        		<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-font"></i></span>
						<input id="txt_lote_fabricacion" type="text" class="form-control"/>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<h3><small>Lote de Produccion:</small></h3>
	        		<div class="input-group input-group-lg">
						<input id="txt_lote_produccion" type="text" class="form-control" />
						<span class="input-group-addon"><i class="fa fa-percent"></i></span>
					</div>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-8 col-sm-8">
					<button
						id="btn_guardar_registro_lab"
						type="button"
						class="btn btn-success btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="recetas.guardar_lab_registro({
									producto: $('#sel_productos').val(),
									orden_produccion: $('#txt_orden_produccion').val(),
									numero_mezclas: $('#txt_numero_mezclas').val(),
									fecha_elaboracion: $('#txt_fecha_elaboracion').val(),
									fecha_recepcion: $('#txt_fecha_recepcion').val(),
									fecha_liberacion: $('#txt_fecha_liberacion').val(),
									fecha_caducidad: $('#txt_fecha_caducidad').val(),
									fecha_analisis: $('#txt_fecha_analisis').val(),
									lote_analisis: $('#txt_lote_analisis').val(),
									lote_fabricacion: $('#txt_lote_fabricacion').val(),
									lote_produccion: $('#txt_lote_produccion').val(),
									btn: 'btn_guardar_registro_lab'
								})">
						<i class="fa fa-check"></i> Ok
					</button>
					<button
						id="btn_actualizar"
						style="display: none"
						type="button"
						class="btn btn-primary btn-lg"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						>
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
