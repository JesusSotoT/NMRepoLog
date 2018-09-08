<html>
	<head>
<!-- **	/////////////////////////- -				 CSS 				--///////////////////// **-->
		
	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
		
<!-- **	//////////////////////////- -				FIN CSS 				--///////////////////// **-->
	
<!-- **	//////////////////////////- -				JS 				--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    
	<!-- Sistema -->
		<script src="js/configuracion/configuracion.js"></script>

<!-- **	//////////////////////////- -				FIN JS 				--///////////////////// **-->

		<title>complementos</title>
	</head>
	<body>
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">

							<div class="col-md-10 col-xs-10">
								<blockquote style="font-size: 14px">
									<p>
										* Selecciona los <strong> Insumos </strong> o <strong> Insumos preparados </strong> 
										y escribe la <strong> cantidad </strong> que se descontara de inventario.
										Despues pulsa en <button type="button" class="btn btn-success"> <i class="fa fa-check"></i> Guardar </button>
									</p>
								</blockquote>
							</div>
							<div class="col-xs-2" style="margin-top: 10px" align="right">
								<button 
									id="btn_guardar" 
									onclick="configuracion.guardar_complementos({
										complementos: configuracion.complementos,
										btn: 'btn_guardar'
									})" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									type="button" 
									class="btn btn-success btn-lg" 
									style="width: 170px; margin-top: 0.5%;">
									<i class="fa fa-check"></i> Guardar
								</button>
							</div>
						</div>
					</div>
				    <div class="panel-body">
						<div class="row" style="overflow: scroll;height:75%"><?php
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
													<div 
														class="panel-heading" 
														id="heading_insumos" 
														role="tab" 
														role="button" 
														style="cursor: pointer" 
														data-toggle="collapse" 
														data-parent="#accordion_insumos" 
														href="#tab_insumos" 
														aria-controls="collapse_insumos" 
														aria-expanded="true">
														<h4 class="panel-title">
															<strong>Insumos</strong>
														</h4>
													</div>
													<div id="tab_insumos" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_insumos">
														<div class="panel-body">
															<table id="tabla_insumos" class="table table-striped table-bordered" cellspacing="0" width="100%">
																<thead>											
																	<tr>
																		<th align="center"><strong>Insumo</strong></th>
																		<th><strong>Unidad</strong></th>
																		<th align="center"><strong>Costo</strong></th>
																		<th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
																	</tr>
																</thead>
																<tbody><?php
																	foreach ($datos['insumos'] as $k => $v) { ?>
																		<tr 
																			id="tr_<?php echo $v['idProducto'] ?>" 
																			onclick="configuracion.agregar_complemento({
																				id:<?php echo $v['idProducto'] ?>,
																				nombre:'<?php echo $v['nombre'] ?>',
																				unidad:'<?php echo $v['unidad'] ?>',
																				costo:<?php echo $v['costo'] ?>,
																				id_unidad:<?php echo $v['idunidad'] ?>,
																				sucursales:<?php echo $v['idsuc'] ?>,
																				div:'div_insumos_agregados',
																				check:$('#check_<?php echo $v['idProducto'] ?>').prop('checked')
																			})" 
																			style="cursor: pointer">
																			<td align="center">
																				<?php echo $v['nombre'] ?>
																			</td>
																			<td>
																				<?php echo $v['unidad'] ?>
																			</td>
																			<td align="center">
																				$ <?php echo number_format($v['costo'], 2, '.', ''); ?>
																			</td> 
																			<td align="center">
																				<input 
																					style="cursor: pointer" 
																					disabled="1" 
																					type="checkbox" 
																					id="check_<?php echo $v['idProducto'] ?>" />
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
										
									</script><?php
								}
							
							// Valida que existan recetas
								if (!empty($datos['insumos_preparados'])) { ?>
									<div class="row">
										<div class="col-md-12 col-sm-12">
											<div class="panel-group" id="accordion_insumos_preparados" role="tablist" aria-multiselectable="true">
												<div class="panel panel-info">
													<div 
														class="panel-heading" 
														id="heading_insumos_preparados" 
														role="tab" 
														role="button" 
														style="cursor: pointer" 
														data-toggle="collapse" 
														data-parent="#accordion_insumos_preparados" 
														href="#tab_insumos_preparados" 
														aria-controls="collapse_insumos_preparados" 
														aria-expanded="true">
														<h4 class="panel-title">
															<strong>Insumos preparados</strong>
														</h4>
													</div>
													<div 
														id="tab_insumos_preparados" 
														class="panel-collapse collapse in" 
														role="tabpanel" 
														aria-labelledby="heading_insumos_preparados">
														<div class="panel-body">
															<table 
																id="tabla_insumos_preparados" 
																class="table table-striped table-bordered" 
																cellspacing="0" 
																width="100%">
																<thead>											
																	<tr>
																		<th align="center"><strong>Insumo</strong></th>
																		<th><strong>Unidad</strong></th>
																		<th align="center"><strong>Costo</strong></th>
																		<th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
																	</tr>
																</thead>
																<tbody><?php
																	foreach ($datos['insumos_preparados'] as $k => $v) { ?>
																		<tr 
																			id="tr_preparado_<?php echo $v['idProducto'] ?>" 
																			onclick="configuracion.agregar_complemento({
																				preparado: 1,
																				id:<?php echo $v['idProducto'] ?>,
																				nombre:'<?php echo $v['nombre'] ?>',
																				unidad:'<?php echo $v['unidad'] ?>',
																				costo:<?php echo $v['costo'] ?>,
																				id_unidad:<?php echo $v['idunidad'] ?>,
																				sucursales:<?php echo $v['idsuc'] ?>,
																				div:'div_insumos_agregados',
																				check:$('#check_<?php echo $v['idProducto'] ?>').prop('checked')
																			})" 
																			style="cursor: pointer">
																			<td align="center">
																				<?php echo $v['nombre'] ?>
																			</td>
																			<td>
																				<?php echo $v['unidad'] ?>
																			</td>
																			<td align="center">
																				$ <?php echo number_format($v['costo'], 2, '.', ''); ?>
																			</td> 
																			<td align="center">
																				<input 
																					style="cursor: pointer" 
																					disabled="1" 
																					type="checkbox" 
																					id="check_<?php echo $v['idProducto'] ?>" />
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
									// Convierte la tabla en DataTable
										
									</script><?php
								} ?>
							</div> <!-- Fin lado izquierdo -->
							<div class="col-md-7" id="div_insumos_agregados">
								
							</div>
						</div>
				  	</div>
				</div>
			</div>
		</div>
	<!-- Modal editar-->
		<div class="modal fade" id="modal_editar" role="dialog" aria-labelledby="titulo_editar">
			<div class="modal-dialog" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_editar" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_editar">Modificar</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:60%">
							<div class="col-xs-12" id="div_editar">
								<!-- En esta div se cargan las complementos e insumos preparados -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal editar -->
	
	<!-- Modal eliminar-->
		<div class="modal fade" id="modal_eliminar" role="dialog" aria-labelledby="titulo_eliminar">
			<div class="modal-dialog" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_eliminar" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_eliminar">Eliminar</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:60%">
							<div class="col-xs-12" id="div_eliminar">
								<!-- En esta div se cargan las complementos -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal editar -->
	</body>
</html><?php
/* Agrega los complementos si existen */
if (!empty($datos['complementos'])) {	
	foreach ($datos['complementos'] as $k => $v) {

		$preparado = ($v['tipo_producto'] == 4) ? 1 : 0 ; ?>
		
		<script>
			configuracion.agregar_complemento({
				preparado: <?php echo $preparado ?>,
				id:<?php echo $v['id'] ?>,
				cantidad: <?php echo $v['cantidad'] ?>,
				nombre:'<?php echo $v['nombre'] ?>',
				unidad:'<?php echo $v['unidad'] ?>',
				sucursales:'<?php echo $v['idsuc'] ?>',
				costo:<?php echo $v['costo'] ?>,
				div:'div_insumos_agregados',
				check:$('#check_<?php echo $v['id'] ?>').prop('checked')
			});



		</script><?php
	} ?>
	<script type="text/javascript">
		configuracion.convertir_dataTable({id: 'tabla_insumos'});
		configuracion.convertir_dataTable({id: 'tabla_insumos_preparados'});
	</script>
<?php } else {?>
	<script type="text/javascript">
		configuracion.convertir_dataTable({id: 'tabla_insumos'});
		configuracion.convertir_dataTable({id: 'tabla_insumos_preparados'});
	</script>
<?php }
?>