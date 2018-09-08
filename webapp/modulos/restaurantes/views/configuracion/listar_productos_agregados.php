<?php
// Valida que existan reservaciones
	if (empty($_SESSION['productos_agregados']['productos'])&&empty($_SESSION['productos_agregados']['productos_preparados'])) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				Selecciona <strong>"productos"</strong>
				 o <strong>"productos preparados"</strong> para agregarlos.
			</p>
		</blockquote><?php
		
		return 0;
	} ?>

<br /><?php 
// productos normales
if (!empty($_SESSION['productos_agregados']['productos'])) { ?>
	<?php if($objeto['tipo'] != 5) { ?>
	<table id="tabla_productos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th><strong>producto</strong></th>
				<th align="center"><strong>Precio</strong></th>
			</tr>
		</thead>
		<tbody><?php
			foreach ($_SESSION['productos_agregados']['productos'] as $k => $v) { if($k != 'comprar' && $k != 'obtener'){
				if ($v['sub_total'] != 'undefined') {
					$total += $v['sub_total'];
				} ?>
				<tr>
					<td><?php echo $v['nombre'] ?></td>
					<td align="center"> $ <?php echo number_format($v['precio'], 2, '.', ''); ?></td>
				</tr><?php
				}
			} ?>
		</tbody>
	</table><?php
	} else { ?>
		<div class="panel-group" id="accordion_comprar" role="tablist" aria-multiselectable="true">
			<div class="panel panel-info" id="panel_comprar">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-5"
							id="heading_comprar" 
							role="tab" role="button" 
							style="cursor: pointer;" 
							data-toggle="collapse" 
							data-parent="#accordion_comprar" 
							href="#tab_comprar" 
							aria-controls="collapse_comprar" 
							aria-expanded="true">
							<h4><strong>Comprar</strong></h4>
						</div>
						<div class="col-md-1">
							<!-- Div para generar espacio -->
		                </div>
						<div class="col-md-3" align="right">
		                    <div class="input-group">
		                        <span class="input-group-addon"><strong><i class="fa fa-cubes"></i></span>
		                        <input 
		                        	onchange="configuracion.cambiar_cantidad_promo({
		                        		grupo: 'comprar',
		                        		cantidad: $(this).val()
		                        	})"
		                        	type="number" 
		                        	min="1" 
		                        	class="form-control" 
		                        	value="<?php echo $_SESSION['productos_agregados']['productos']['comprar']['cantidad']?>">
		                    </div>
		                </div>
						<div class="col-md-3" align="right">
							<button 
								id="btn_seleccionar_grupo_comprar" 
								type="button" 
								class="btn btn-info btn-lg"
								style="display: none;"
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								onclick="configuracion.seleccionar_grupo_promo({grupo: 'comprar'})">
								<i class="fa fa-mouse-pointer"></i>
							</button>
						</div>
					</div>
				</div>
				<div 
					id="tab_comprar" 
					class="contraer panel-collapse collapse in" 
					role="tabpanel" 
					aria-labelledby="heading_comprar">
					<div class="panel-body">
						<table class="table table-striped table-bordered" id="tabla_comprar_5" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><strong>producto</strong></th>
									<th align="center"><strong>Precio</strong></th>
								</tr>
							</thead>
							<tbody><?php
								foreach ($_SESSION['productos_agregados']['productos']['comprar'] as $k => $v) {if($k != 'cantidad'){
									if ($v['sub_total'] != 'undefined') {
										$total += $v['sub_total'];
									} ?>
									<tr id="tr_comprar_<?php echo $v['id']?>">
										<td><?php echo $v['nombre'] ?></td>
										<td align="center"> $ <?php echo number_format($v['precio'], 2, '.', ''); ?></td>
									</tr><?php }
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-group" id="accordion_recibir" role="tablist" aria-multiselectable="true">
			<div class="panel panel-default" id="panel_recibir">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-5"
							id="heading_recibir" 
							role="tab" role="button" 
							style="cursor: pointer;" 
							data-toggle="collapse" 
							data-parent="#accordion_recibir" 
							href="#tab_recibir" 
							aria-controls="collapse_recibir" 
							aria-expanded="true">
							<h4><strong>recibir</strong></h4>
						</div>
						<div class="col-md-1">
							<!-- Div para generar espacio -->
		                </div>
						<div class="col-md-3" align="right">
		                    <div class="input-group">
		                        <span class="input-group-addon"><strong><i class="fa fa-cubes"></i></span>
		                        <input 
		                        	onchange="configuracion.cambiar_cantidad_promo({
		                        		grupo: 'obtener',
		                        		cantidad: $(this).val()
		                        	})"
		                        	type="number" 
		                        	min="1" 
		                        	class="form-control" 
		                        	value="<?php echo $_SESSION['productos_agregados']['productos']['obtener']['cantidad']?>">
		                    </div>
		                </div>
						<div class="col-md-3" align="right">
							<button 
								id="btn_seleccionar_grupo_recibir" 
								type="button" 
								class="btn btn-info btn-lg"
								style=""
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								onclick="configuracion.seleccionar_grupo_promo({grupo: 'recibir'})">
								<i class="fa fa-mouse-pointer"></i>
							</button>
						</div>
					</div>
				</div>
				<div 
					id="tab_recibir" 
					class="contraer panel-collapse collapse in" 
					role="tabpanel" 
					aria-labelledby="heading_recibir">
					<div class="panel-body">
						<table class="table table-striped table-bordered" id="tabla_recibir_5" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><strong>producto</strong></th>
									<th align="center"><strong>Precio</strong></th>
								</tr>
							</thead>
							<tbody><?php
								foreach ($_SESSION['productos_agregados']['productos']['obtener'] as $k => $v) { if($k != 'cantidad'){
									if ($v['sub_total'] != 'undefined') {
										$total += $v['sub_total'];
									} ?>
									<tr id="tr_recibir_<?php echo $v['id']?>">
										<td><?php echo $v['nombre'] ?></td>
										<td align="center"> $ <?php echo number_format($v['precio'], 2, '.', ''); ?></td>
									</tr><?php
								}
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php }
} ?>
<script type="text/javascript">
	if(configuracion.comprar_recibir == 1){
		$("#btn_seleccionar_grupo_recibir").show();
		$("#btn_seleccionar_grupo_comprar").hide();
		$("#panel_recibir").removeClass("panel-info");
        $("#panel_recibir").addClass("panel-default");
        $("#panel_comprar").removeClass("panel-default");
        $("#panel_comprar").addClass("panel-info");

	} else {
		$("#btn_seleccionar_grupo_recibir").hide();
		$("#btn_seleccionar_grupo_comprar").show();
		$("#panel_recibir").removeClass("panel-default");
        $("#panel_recibir").addClass("panel-info");
        $("#panel_comprar").removeClass("panel-info");
        $("#panel_comprar").addClass("panel-default");
	}
</script>