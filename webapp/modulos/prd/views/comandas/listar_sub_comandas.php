<div class="row">
	<div class="col-xs-6">
		<h3 class="text-danger">$ <?php echo $_SESSION['cerrar_personalizado']['total_comanda'] ?></h3>
	</div>
	<div class="col-xs-6">
		<h3 class="text-success">$ <?php echo number_format($_SESSION['cerrar_personalizado']['total_sub_comandas'], 2, '.', '');?></h3>
	</div>
</div><?php
foreach ($_SESSION['cerrar_personalizado']['comanda'] as $key => $value) {
	$total=0; 
	
	foreach ($value['pedidos'] as $k => $v) {
		$total += $v['precioventa'];
	}

// Valida que tenga pedidos la persona
	if (!empty($value['pedidos'])) { ?>
		<div class="row">
			<div class="col-xs-12">
				<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div hrefer class="panel-heading" role="tab" role="button" data-toggle="collapse" data-parent="#accordion" href="#tab_<?php echo $key ?>" aria-controls="collapse_<?php echo $key ?>">
							<h4 class="panel-title">
								<strong>$ <?php echo $total ?></strong>
							</h4>
						</div>
						<div id="tab_<?php echo $key ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_tab_<?php echo $key ?>">
							<div class="panel-body">
								<table class="table table-bordered table-striped">
									<tr></tr>
									<td align="center"><strong>Pedido</strong></td>
									<td><strong>Producto</strong></td>
									<td align="center"><strong>Costo</strong></td><?php
									
									foreach ($value['pedidos'] as $k => $v) { 
										$extras=''; ?>
										<tr><?php 
										
										// Arma una cadena con los nombres de los extras si existen
											if(!empty($v['extras'])){
												$extras='(Extras: ';
												
												foreach ($v['extras'] as $a => $b) {
													$extras.=$b['nombre'].',';
												}
												
												$extras = substr($extras, 0, -1);
												$extras.=')';
											} ?>
											
											<td align="center">
												<?php echo $k+1 ?>
											</td>
											<td>
												<?php echo $v['nombre'].$extras ?>
											</td>
											<td align="center">
												<?php echo $v['precioventa'] ?>
											</td> 
										</tr><?php
									} ?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><?php
	}
} 
?>