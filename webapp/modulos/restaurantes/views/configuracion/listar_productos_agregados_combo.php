<?php
foreach ($productos as $key => $value) {
	if ($key == $objeto['grupo']) {
		$collapse = 'in';
		$class = 'info';
		$style = ' display: none';
	} else {
		$collapse = '';
		$class = 'default';
		$style = '';
	} ?>
	
	<div class="panel-group" id="accordion_<?php echo $key ?>" role="tablist" aria-multiselectable="true">
		<div class="panel panel-<?php echo $class ?>" id="panel_<?php echo $key ?>">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-5"
						id="heading_<?php echo $key ?>" 
						role="tab" role="button" 
						style="cursor: pointer;" 
						data-toggle="collapse" 
						data-parent="#accordion_<?php echo $key ?>" 
						href="#tab_<?php echo $key ?>" 
						aria-controls="collapse_<?php echo $key ?>" 
						aria-expanded="true">
						<h4><strong><i class="fa fa-cutlery"></i> Grupo <?php echo $key ?></strong></h4>
					</div>
					<div class="col-md-1">
						<!-- Div para generar espacio -->
	                </div>
					<div class="col-md-3" align="right">
	                    <div class="input-group">
	                        <span class="input-group-addon"><strong><i class="fa fa-cubes"></i></span>
	                        <input 
	                        	onchange="configuracion.cambiar_cantidad_combo({
	                        		grupo: <?php echo $key ?>,
	                        		cantidad: $(this).val()
	                        	})"
	                        	type="number" 
	                        	min="1" 
	                        	class="form-control" 
	                        	value="<?php echo $value['cantidad'] ?>">
	                    </div>
	                </div>
					<div class="col-md-3" align="right">
						<button 
							id="btn_seleccionar_grupo_<?php echo $key ?>" 
							type="button" 
							class="btn btn-info btn-lg"
							style="<?php echo $style ?>"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
							onclick="configuracion.seleccionar_grupo({grupo: <?php echo $key ?>})">
							<i class="fa fa-mouse-pointer"></i>
						</button><?php
						
						$p = json_encode($productos[$key]['productos']);
						$p = str_replace('"', "'", $p) ?>
						
						<button 
							id="btn_eliminar_grupo_<?php echo $key ?>" type="button" 
							class="btn btn-danger btn-lg" 
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
							onclick="configuracion.agregar_grupo({productos:<?php echo $p ?>, grupo: <?php echo $key ?>, div: 'div_productos_agregados'})">
							<i class="fa fa-trash"></i>
						</button>
					</div>
				</div>
			</div>
			<div 
				id="tab_<?php echo $key ?>" 
				class="contraer panel-collapse collapse <?php echo $collapse ?>" 
				role="tabpanel" 
				aria-labelledby="heading_<?php echo $key ?>">
				<div class="panel-body"><?php
					if (!empty($productos[$key]['productos'])) { ?>
						<table class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><strong>producto</strong></th>
									<th align="center"><strong>Precio</strong></th>
								</tr>
							</thead>
							<tbody><?php
								foreach ($productos[$key]['productos'] as $k => $v) { ?>
									<tr>
										<td><?php echo $v['nombre'] ?></td>
										<td align="center"> $ <?php echo number_format($v['precio'], 2, '.', ''); ?></td>
									</tr><?php
								} ?>
							</tbody>
						</table><?php
					} else { ?>
						<blockquote style="font-size: 16px">
					    	<p>Selecciona <strong>Productos</strong> para agregarlos al grupo.</p>
						</blockquote><?php
					} ?>
				</div>
			</div>
		</div>
	</div><?php
} ?>