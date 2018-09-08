<?php
// Valida que existan pedidos terminados
if (empty($terminados)) {?>
	<div align="center">
		<h3><span class="label label-default">* Aqui aparecen los pedidos terminados *</span></h3>
	</div><?php
	
	return 0;
}

foreach ($terminados as $key => $value) {
	if(!empty($value['persona'])){ ?>
		<div class="panel panel-success">
			<div class="panel-heading" style="font-size: 25px">
				<i class="fa fa-cutlery"></i> <?php echo $key ?> &nbsp; / &nbsp;
				<i class="fa fa-object-group"></i> <?php echo $value['mesa'] ?> &nbsp; / &nbsp;
				<i class="fa fa-clock-o"></i> <?php echo $value['hora'] ?>
			</div>
			<div class="panel-body"><?php
				foreach ($value['persona'] as $k => $v) { ?> 
					<div class="panel panel-default">
						<div class="panel-heading" style="font-size: 25px">
							<i class="fa fa-pencil-square-o"></i> <?php echo $k ?>
						</div>
						<div class="panel-body"><?php
							foreach ($v['productos'] as $kk => $vv) {
							// Formateamos el pedido
								$pedido=$vv;
								$pedido['comanda']=$key;
								$pedido['persona']=$k;
								$pedido=json_encode($pedido);
								$pedido=str_replace('"', "'", $pedido);
								
								$preparacion = (!empty($vv['opcionalesDesc'])) ? 
									'<footer style="font-size: 20px">'.$vv['opcionalesDesc'].'</footer>' : '' ; 
								$preparacion .= (!empty($vv['adicionalesDesc'])) ? 
									'<footer style="font-size: 20px">'.$vv['adicionalesDesc'].'</footer>' : '' ; 
								$preparacion .= (!empty($vv['sin_desc'])) ? 
									'<footer style="font-size: 20px">'.$vv['sin_desc'].$vv['nota_sin'].'</footer>' : '' ; ?>
								
								<div class="row" style="padding-top: 10px">
									<div class="col-md-12">
										<blockquote>
											<p style="font-size: 25px"><?php echo $vv['descripcion'] ?></p>
											<?php echo $preparacion ?>
										</blockquote> 
									</div>
								</div><?php
							} ?>
						</div>
					</div><?php
				} ?>
			</div>
		</div> <?php
	}
} ?>