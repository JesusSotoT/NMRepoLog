<html>
	<head>
<!-- ///////////////// ******** ---- 		CSS		------ ************ ////////////////// -->

	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
		
<!-- ///////////////// ******** ---- 		FIN CSS		------ ************ ////////////////// -->

<!-- ///////////////// ******** ---- 		JS		------ ************ ////////////////// -->
	 
	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
		
	<!-- configuracion.js -->
		<script src="js/recetas/recetas.js"></script>
		
<!-- ///////////////// ******** ---- 		FIN JS		------ ************ ////////////////// -->
	
	</head>
	<body>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4>Preparacion de insumos</h4>
			</div>
			<div class="panel-body">
				<blockquote style="font-size: 14px">
					<p>
						Para <strong>preparar</strong> un insumo introduce la <strong>cantidad</strong> al lado del 
						<strong>insumo preparado </strong> y preciona 
						<button class="btn btn-default btn-lg"><i class="fa fa-retweet"></i></button>. 
						Al terminar de prepararlo, preciona
						<button class="btn btn-success btn-lg"><i class="fa fa-check"></i></button>
					</p>
				</blockquote>
			</div>
			<div class="panel-footer" style="overflow: scroll; height: 65%">
				<div class="row">
					<div class="col-md-12">
						<table id="tabla_productos" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>						
									<th style="width: 5%">Producto</th>
									<th align="center">Costo</th>
									<th align="center">Precio</th>
									<th align="center">Insumos</th>
									<th align="center">Preparacion</th>
									<th align="center">Cantidad</th>
									<th align="center"><i class="fa fa-retweet" aria-hidden="true"></i> Preparar</th>
									<th align="center"><i class="fa fa-check" aria-hidden="true"></i> Terminado</th>
								</tr>				
							</thead>
							<tbody><?php
								foreach ($insumos_preparados as $key => $value) {
									$insumos = json_encode($value['insumos']);
									$insumos = str_replace('"', "'", $insumos);
									
								// Genera una cadena con los nombres de lo insumos
									$texto_insumos = '';
									foreach ($value['insumos'] as $k => $v) {
										$texto_insumos .= $v['nombre'].', ';
									}
									$texto_insumos = substr($texto_insumos, 0,-2); ?>
									
									<tr>
										<td title="<?php echo $value['nombre'] ?>">
											<?php echo substr($value['nombre'],0,50) ?>
										</td>
										<td align="center"><?php echo $value['costo'] ?></td>
										<td align="center"><?php echo $value['precio'] ?></td>
										<td><?php echo $texto_insumos ?></td>
										<td><?php echo $value['preparacion'] ?></td>
										<td>
											<input 
												id="cantidad_<?php echo $value['idProducto'] ?>" 
												type="number" 
												class="form-control"
												style="width: 100px" />
										</td>
										<td align="center">
											<button 
												id="btn_preparar_<?php echo $value['idProducto'] ?>" 
												type="button" 
												data-loading-text="<i class='fa fa-retweet fa-spin'></i>" 
												class="btn btn-default btn-lg"
												onclick="recetas.preparar_insumo({
															insumos:<?php echo $insumos ?>,
															btn: 'btn_preparar_<?php echo $value['idProducto'] ?>',
															id_producto: <?php echo $value['idProducto'] ?>,
															cantidad: $('#cantidad_<?php echo $value['idProducto'] ?>').val()
														})">
												<i class="fa fa-retweet" aria-hidden="true"></i>
											</button>
										</td>
										<td align="center">
											<button 
												style="display: none"
												id="btn_terminar_<?php echo $value['idProducto'] ?>" 
												type="button" 
												data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
												class="btn btn-success btn-lg" 
												onclick="recetas.terminar_insumo({
															unidad_venta: <?php echo $value['idunidad'] ?>,
															unidad_compra: <?php echo $value['idunidadCompra'] ?>,
															precio: <?php echo $value['precio'] ?>,
															cantidad: $('#cantidad_<?php echo $value['idProducto'] ?>').val(),
															id_preparacion: $(this).attr('id_preparacion'),
															btn: 'btn_terminar_<?php echo $value['idProducto'] ?>',
															id: <?php echo $value['idProducto'] ?>
														})">
												<i class="fa fa-check" aria-hidden="true"></i>
											</button>
										</td>
									</tr><?php
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
	</body>
</html>
<script>
// Crea el datatable
	recetas.convertir_dataTable({id:'tabla_productos'});
</script>