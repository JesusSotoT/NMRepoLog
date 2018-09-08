<div class="row">
	<div class="col-md-12 col-xs-12">
		<!-- $objeto es un array codificada en json con los datos de la comanda y la persona -->
		<button
				onclick="comandera.guardar_detalles_pedido(<?php echo $objeto_json ?>)" 
				type="button" class="btn"
				id="btn_guardar_detalles_pedido"
				style="background-color: #209775; color:white;"
				data-loading-text="<i class='fa fa-refresh fa-spin'></i>">
				<i class="fa fa-check"></i> Save
		</button><?php
		if ($objeto['combo'] == 1) { ?>
			<button 
				onclick="comandera.reiniciar_grupo({
								grupo: <?php echo $objeto['grupo'] ?>, 
								div: 'div_combo_grupo_<?php echo $objeto['grupo'] ?>'
						})" 
				type="button" 
				style="background-color: #D6872D; color:white;"
				class="btn">
				<i class="fa fa-ban"></i> Cancel
			</button><?php
		}else{ ?>
			<button onclick="comandera.area_inicio()" type="button" class="btn" style="background-color: #D6872D; color:white;">
				<i class="fa fa-ban"></i> Cancel
			</button><?php
		} ?>
	</div>
</div><br />
<div class="row" id="div"><?php

foreach ($datos as $key => $value) {
	if($key == 'sin'){ ?>
		<div class="panel panel-default" style="width: calc(100% - 30px); margin-left: 15px;">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-3" style="padding-top: 5px">
						<h4 class="panel-title">Without:</h4>
					</div>
					<div class="col-md-9" align="right">
						<button 
							class="btn" 
							style="background-color: #D6872D; color:white;"
							onclick="comandera.reiniciar_opcionales({
									grupo: 1
								})">
							<i class="fa fa-undo"></i> Restart
						</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-9" id="div_opcionales_sin"><?php
						foreach($value as $k => $v) { ?>
							
							<div
								class = "pull-left" 
								style = "padding:5px" 
								>
								<button
									type="button" 
									id="btn_sin_<?php echo $v['idProducto'] ?>"
									onclick="comandera.seleccionar_opc({
										id_producto: <?php echo $v['idProducto'] ?>,
										grupo: 1
									})" 
									class="btn btn-default btn_sin" 
									style="width: 120px;height: 60px;white-space: normal;">
									<div class="row">
										<div style="font-weight: bold;" class="">
											<?php echo substr($v['nombre'], 0, 25)  ?>
										</div>
									</div>
								</button>
							</div><?php
						} ?>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
					      <label for="disabledTextInput">Note: </label>
					      <textarea id="nota_sin" class="form-control" rows="6" style="cursor: se-resize"></textarea>
					    </div>
						
					</div>
				</div>
			</div>
		</div>
		<?php
	} //Fin sin
	
	if($key == 'extra'){ ?>
		<div class="panel panel-default" style="width: calc(100% - 30px); margin-left: 15px;">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-3" style="padding-top: 5px">
						<h4 class="panel-title">Extra:</h4>
					</div>
					<div class="col-md-9" align="right">
						<button 
							class="btn" 
							style="background-color: #D6872D; color:white;"
							onclick="comandera.reiniciar_opcionales({
									grupo: 2
								})">
							<i class="fa fa-undo"></i> Restart
						</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-9" id="div_opcionales_extra"><?php
						foreach($value as $k => $v) { ?>
							
							<div
								class = "pull-left" 
								style = "padding:5px" 
								>
								<button
									type="button" 
									id="btn_extra_<?php echo $v['idProducto'] ?>"
									onclick="comandera.seleccionar_opc({
										id_producto: <?php echo $v['idProducto'] ?>,
										grupo: 2
									})" 
									class="btn btn-default btn_extra" 
									style="width: 120px;height: 60px;white-space: normal;">
									<div class="row">
										<div style="font-weight: bold;" class="">
											<?php echo substr($v['nombre'], 0, 25)  ?>
										</div>
									</div>
								</button>
							</div><?php
						} ?>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
					      <label for="disabledTextInput">Note: </label>
					      <textarea id="nota_extra" class="form-control" rows="6" style="cursor: se-resize"></textarea>
					    </div>
						
					</div>
				</div>
			</div>
		</div><?php
	} //Fin extra
	
	if($key == 'opcionales'){ ?>
		<div class="panel panel-default" style="width: calc(100% - 30px); margin-left: 15px;">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-3" style="padding-top: 5px">
						<h4 class="panel-title">Optionals:</h4>
					</div>
					<div class="col-md-9" align="right">
						<button 
							class="btn" 
							style="background-color: #D6872D; color:white;"
							onclick="comandera.reiniciar_opcionales({
									grupo: 3
								})">
							<i class="fa fa-undo"></i> Restart
						</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-9" id="div_opcionales_opcional"><?php
						foreach($value as $k => $v) { ?>
							
							<div
								class = "pull-left" 
								style = "padding:5px" 
								>
								<button
									type="button" 
									id="btn_opcional_<?php echo $v['idProducto'] ?>"
									onclick="comandera.seleccionar_opc({
										id_producto: <?php echo $v['idProducto'] ?>,
										grupo: 3
									})" 
									class="btn btn-default btn_opcional" 
									style="width: 120px;height: 60px; font-weight: bold; white-space: normal;">
									<div class="row">
										<div style="font-weight: bold;" class="">
											<?php echo substr($v['nombre'], 0, 25)  ?>
										</div>
									</div>
								</button>
							</div><?php
						} ?>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
					      <label for="disabledTextInput">Note: </label>
					      <textarea id="nota_opcional" class="form-control" rows="6" style="cursor: se-resize"></textarea>
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