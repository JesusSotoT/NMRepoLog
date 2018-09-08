<?php
// Valida que existan productos
	if (empty($productos)) {?>
		<div align="center">
			<h3><span class="label label-default">* No hay productos *</span></h3>
		</div><?php
		
		return 0;
	} ?>

<div class="col-md-5"><?php
// Valida que existan productos
	if (!empty($productos)) { ?>
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="panel-group" id="accordion_productos" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div hrefer class="panel-heading" id="heading_productos" role="tab" role="button" style="cursor: pointer" data-toggle="collapse" data-parent="#accordion_productos" href="#tab_productos" aria-controls="collapse_productos" aria-expanded="true">
							<h4 class="panel-title">
								<strong>Productos</strong>
							</h4>
						</div>
						<div id="tab_productos" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_productos">
							<div class="panel-body">
								<table id="tabla_productos" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>											
										<tr>
											<th align="center"><strong>Producto</strong></th>
											<th><strong>Unidad</strong></th>
											<th align="center"><strong>Precio</strong></th>
											<th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
										</tr>
									</thead>
									<tbody><?php
										foreach ($productos as $k => $v) { ?>
											<tr id="tr_<?php echo $v['idProducto'] ?>" onclick="configuracion.agregar_producto({id:<?php echo $v['idProducto'] ?>, nombre:'<?php echo $v['nombre'] ?>', precio:'<?php echo $v['precio'] ?>', div:'div_productos_agregados', check:$('#check_<?php echo $v['idProducto'] ?>').prop('checked')})" style="cursor: pointer">
												<td align="center">
													<?php echo $v['nombre'] ?>
												</td>
												<td>
													<?php echo $v['unidad'] ?>
												</td>
												<td align="center">
													$ <?php echo number_format($v['precio'], 2, '.', ''); ?>
												</td> 
												<td align="center">
													<input style="cursor: pointer" disabled="1" type="checkbox" id="check_<?php echo $v['idProducto'] ?>" />
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
			configuracion.convertir_dataTable({id: 'tabla_productos'});
		</script><?php
	} ?>
</div> <!-- Fin lado izquierdo -->
<div class="col-md-7">
	<div class="panel panel-<?php echo $panel ?>">
		<div class="panel-heading">
			<h4 class="panel-title">
				<strong>
					Promocion
				</strong>
			</h4>
		</div>
		<div class="panel-body">
			<div class="row">
			<!-- En esta div se cargan los productos de la receta -->
				<div class="col-md-12 col-sm-12" id="div_productos_agregados">
					<br /><br />
					<blockquote style="font-size: 16px">
				    	<p>
				      		Selecciona <strong>"Productos"</strong> para agregarlos a la promocion.
				    	</p>
				    </blockquote>
				</div>
			</div>
			<form id="form_promocion">
				<div class="row">
					<div class="col-md-8 col-sm-8">
						<h3><small>Nombre:</small></h3>
		        		<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-font"></i></span>
							<input required="1" id="nombre" type="text" class="form-control"/>
						</div>
					</div>
					<div class="col-md-4 col-sm-4">
						<h3><small>Tipo:</small></h3>
			       		<div class="input-group input-group-lg" id="notificaciones">
							<span class="input-group-addon"><i class="fa fa-edit"></i></span>
							<select id="tipo" onchange="configuracion.cambiar_tipo({tipo: $(this).val()})" class="selectpicker" data-width="80%">
								<option selected value="1">Por descuento</option>
								<option value="2">Por cantidad</option>
							</select>
						</div>
						<script>configuracion.cambiar_tipo({tipo: $('#tipo').val()})</script>
					</div>
				</div>
				<div class="row" id="div_por_descuento">
					<div class="col-md-3 col-sm-3">
						<h3><small>Descuento:</small></h3>
		        		<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-percent"></i></span>
							<input id="descuento" type="number" class="form-control"/>
						</div>
					</div>
				</div>
				<div class="row" id="div_por_cantidad">
					<div class="col-md-6 col-sm-6">
						<h3><small>Por cada:</small></h3>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
							<input id="cantidad" type="number" class="form-control"/>
						</div>
					</div>
					<div class="col-md-6 col-sm-6">
						<h3><small>Descontar:</small></h3>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="fa fa-cube"></i></span>
							<input id="cantidad_descuento" type="number" class="form-control"/>
						</div>
					</div>
				</div><br />
				<div class="row">
					<div class="col-md-12 col-sm-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h5><i class="fa fa-clock-o"></i> Horario</h5>
							</div>
							<div class="panel-body">
								<div class="col-md-7">
									<label class="checkbox-inline">
										<input id="do" type="checkbox" value="">
										Do
									</label>
									<label class="checkbox-inline">
										<input id="lu" type="checkbox" value="">
										Lu 
									</label>
									<label class="checkbox-inline">
										<input id="ma" type="checkbox" value="">
										Ma 
									</label>
									<label class="checkbox-inline">
										<input id="mi" type="checkbox" value="">
										Mi 
									</label>
									<label class="checkbox-inline">
										<input id="ju" type="checkbox" value="">
										Ju 
									</label>
									<label class="checkbox-inline">
										<input id="vi" type="checkbox" value="">
										Vi 
									</label>
									<label class="checkbox-inline">
										<input id="sa" type="checkbox" value="">
										Sa 
									</label>
								</div>
								<div class="col-md-5">
									<div class="row" align="center">
										<div class="col-xs-5">
											<input 
												type="time" 
												value="<?php echo $value['inicio'] ?>" 
												id="inicio" />
										</div>
										<div class="col-xs-1">a</div>
										<div class="col-xs-5">
											<input 
												type="time" 
												value="<?php echo $value['fin'] ?>" 
												id="fin" />
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-md-8 col-sm-8">
					<button 
						id="btn_guardar_promocion" type="button" 
						class="btn btn-success btn-lg" 
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
						onclick="configuracion.guardar_promocion({form:'form_promocion', btn:'btn_guardar_promocion'})">
						<i class="fa fa-check"></i> Ok
					</button>
					<button 
						id="btn_actualizar_promocion" 
						style="display: none" 
						type="button" 
						class="btn btn-primary btn-lg" 
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
						onclick="configuracion.actualizar_promocion({id_promocion:$(this).attr('id_promocion'), form:'form_promocion', btn:'btn_actualizar_promocion'})">
						<i class="fa fa-check"></i> Ok
					</button>
				</div>
			</div>
		</div>
	</div>
</div><!-- Fin lado derecho -->