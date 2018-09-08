<div class="row">
	<div class="col-md-12">
		<!-- $objeto es un array codificada en json con los datos de la comanda y la persona -->
		<button
			onclick="comandera.guardar_detalles_pedido(<?php echo $objeto ?>)" 
			type="button" class="btn btn-success"
			id="btn_guardar_detalles_pedido"
			data-loading-text="<i class='fa fa-refresh fa-spin'></i>">
			<i class="fa fa-check"></i> Guardar
		</button>
		<button onclick="comandera.area_inicio()" type="button" class="btn btn-danger">
			<i class="fa fa-ban"></i> Cancelar
		</button>
	</div>
</div>
<div class="row" id="div"><?php
			
foreach ($datos as $key => $value) {
	if($key == 'sin'){ ?>
		<div class="col-md-4">
			<div class="panel panel-warning">
				<div class="panel-heading">
					Sin:
				</div>
				<div class="panel-body"><?php
					foreach ($value as $k => $v) { ?>
						<div class="row">
							<div class="col-md-12" align="left">
								<div style="background:#D8D8D8;" class="itemProductCheck">
									<a href="javascript:void(0)" style="color:#000000;text-decoration:none">
										<table>
											<tr>
												<td>
													<input 
														type="checkbox" 
														class="itemCheck" 
														value="<?php echo $v['idProducto'] ?>" 
														opcional="1"/>
												</td>
												<td>
													<div style="font-size:11px;font-family:verdana">
														<?php echo $v['nombre'] ?>
													</div>
												</td>
											</tr>
										</table> 
									</a>
								</div>
							</div>
						</div><?php
					} ?>
					<div class="row">
						<div class="col-md-12">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-pencil"></i>
								</div>
								<textarea id="nota_sin" class="form-control" style="cursor: se-resize"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	} //Fin sin
	
	if($key == 'extra'){ ?>
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					Extra:
				</div>
				<div class="panel-body"><?php
					foreach ($value as $k => $v) { ?>
						<div class="row">
							<div class="col-md-12" align="left">
								<div style="background:#D8D8D8;" class="itemProductCheck">
									<a href="javascript:void(0)" style="color:#000000;text-decoration:none">
										<table>
											<tr>
												<td>
													<input 
														type="checkbox" 
														class="itemCheck" 
														value="<?php echo $v['idProducto'] ?>" 
														opcional="2"/>
												</td>
												<td>
													<div style="font-size:11px;font-family:verdana">
														<?php echo $v['nombre'] ?>
													</div>
												</td>
											</tr>
										</table> 
									</a>
								</div>
							</div>
						</div><?php
					} ?>
					<div class="row">
						<div class="col-md-12">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-pencil"></i>
								</div>
								<textarea id="nota_extra" class="form-control" style="cursor: se-resize"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	} //Fin extra
	
	if($key == 'opcionales'){ ?>
		<div class="col-md-4">
			<div class="panel panel-success">
				<div class="panel-heading">
					Opcionales:
				</div>
				<div class="panel-body"><?php
					foreach ($value as $k => $v) { ?>
						<div class="row">
							<div class="col-md-12" align="left">
								<div style="background:#D8D8D8;" class="itemProductCheck">
									<a href="javascript:void(0)" style="color:#000000;text-decoration:none">
										<table>
											<tr>
												<td>
													<input 
														type="checkbox" 
														class="itemCheck" 
														value="<?php echo $v['idProducto'] ?>" 
														opcional="3"/>
												</td>
												<td>
													<div style="font-size:11px;font-family:verdana">
														<?php echo $v['nombre'] ?>
													</div>
												</td>
											</tr>
										</table> 
									</a>
								</div>
							</div>
						</div><?php
					} ?>
					<div class="row">
						<div class="col-md-12">
							<div class="input-group">
								<div class="input-group-addon">
									<i class="fa fa-pencil"></i>
								</div>
								<textarea id="nota_opcional" class="form-control" style="cursor: se-resize"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	} // FIN opcional
} ?>
<script>
	$(".itemProductCheck").click(function() {
		if ($(this).css('background-color') != 'rgb(216, 216, 216)') {
			$(this).css('background-color', '#D8D8D8');
			$(this).find('input').prop('checked', false);
		} else {
			$(this).css('background-color', '#81F781');
			$(this).find('input').prop('checked', true);
		}
	}); 
</script>