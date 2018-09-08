<html>
	<head>
<!-- ///////////////// ******** ---- 		CSS		------ ************ ////////////////// -->

	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- Select con buscador  -->
    	<link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
		
<!-- ///////////////// ******** ---- 		FIN CSS		------ ************ ////////////////// -->

<!-- ///////////////// ******** ---- 		JS		------ ************ ////////////////// -->
	 
	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Select con buscador  -->
		<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
		
	<!-- configuracion.js -->
		<script src="js/configuracion/configuracion.js"></script>
		
<!-- ///////////////// ******** ---- 		FIN JS		------ ************ ////////////////// -->
	
	</head>
	<body>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2 class="panel-title">Horarios</h2>
			</div>
			<div class="panel-body" style="overflow: scroll; height: 95vh">
				<div class="row">
					<div class="col-md-12">
						<table id="tabla_productos" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>						
									<th style="width: 5%">Producto</th>
									<th align="center">Precio</th>
									<th>Dias</th>
									<th align="center">Horario</th>
									<th align="center"><i class="fa fa-check" aria-hidden="true"></i></th>
									<th align="center"><i class="fa fa-times" aria-hidden="true"></i></th>
								</tr>				
							</thead>
							<tbody><?php
								foreach ($productos as $key => $value) {
									$clase = (!empty($value['dias'])) ? 'success' : 'default' ;
									$check_do = (!empty(strpos($value['dias'], "0"))) ? 'checked="1"' : '' ;
									$check_lu = (!empty(strpos($value['dias'], "1"))) ? 'checked="1"' : '' ;
									$check_ma = (!empty(strpos($value['dias'], "2"))) ? 'checked="1"' : '' ;
									$check_mi = (!empty(strpos($value['dias'], "3"))) ? 'checked="1"' : '' ;
									$check_ju = (!empty(strpos($value['dias'], "4"))) ? 'checked="1"' : '' ;
									$check_vi = (!empty(strpos($value['dias'], "5"))) ? 'checked="1"' : '' ;
									$check_sa = (!empty(strpos($value['dias'], "6"))) ? 'checked="1"' : '' ; ?>
									
									<tr>
										<td title="<?php echo $value['nombre'] ?>">
											<?php echo substr($value['nombre'],0,50) ?>
										</td>
										<td align="center"><?php echo $value['precioventa'] ?></td>
										<td>
											<label class="checkbox-inline">
												<input onchange="configuracion.cambio({id:<?php echo $value['idProducto'] ?>})" <?php echo $check_do ?> id="do_<?php echo $value['idProducto'] ?>" type="checkbox" value="">Do
											</label>
											<label class="checkbox-inline">
												<input onchange="configuracion.cambio({id:<?php echo $value['idProducto'] ?>})" <?php echo $check_lu ?> id="lu_<?php echo $value['idProducto'] ?>" type="checkbox" value="">Lu
											</label>
											<label class="checkbox-inline">
												<input onchange="configuracion.cambio({id:<?php echo $value['idProducto'] ?>})" <?php echo $check_ma ?> id="ma_<?php echo $value['idProducto'] ?>" type="checkbox" value="">Ma
											</label>
											<label class="checkbox-inline">
												<input onchange="configuracion.cambio({id:<?php echo $value['idProducto'] ?>})" <?php echo $check_mi ?> id="mi_<?php echo $value['idProducto'] ?>" type="checkbox" value="">Mi
											</label>
											<label class="checkbox-inline">
												<input onchange="configuracion.cambio({id:<?php echo $value['idProducto'] ?>})" <?php echo $check_ju ?> id="ju_<?php echo $value['idProducto'] ?>" type="checkbox" value="">Ju
											</label>
											<label class="checkbox-inline">
												<input onchange="configuracion.cambio({id:<?php echo $value['idProducto'] ?>})" <?php echo $check_vi ?> id="vi_<?php echo $value['idProducto'] ?>" type="checkbox" value="">Vi
											</label>
											<label class="checkbox-inline">
												<input onchange="configuracion.cambio({id:<?php echo $value['idProducto'] ?>})" <?php echo $check_sa ?> id="sa_<?php echo $value['idProducto'] ?>" type="checkbox" value="">Sa
											</label>
										</td>
										<td>
											<div class="row" align="center">
												<div class="col-xs-1"></div>
												<div class="col-xs-4">
													<input onchange="configuracion.cambio({id:<?php echo $value['idProducto'] ?>})" type="time" value="<?php echo $value['inicio'] ?>" id="inicio_<?php echo $value['idProducto'] ?>" />
												</div>
												<div class="col-xs-1">a</div>
												<div class="col-xs-4">
													<input onchange="configuracion.cambio({id:<?php echo $value['idProducto'] ?>})" type="time" value="<?php echo $value['fin'] ?>" id="fin_<?php echo $value['idProducto'] ?>" />
												</div>
											</div>
										</td>
										<td align="center">
											<button id="btn_<?php echo $value['idProducto'] ?>" type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-<?php echo $clase ?>" onclick="configuracion.guardar_platillo({id:<?php echo $value['idProducto'] ?>,inicio:$('#inicio_'+<?php echo $value['idProducto'] ?>).val(),fin:$('#fin_'+<?php echo $value['idProducto'] ?>).val() })">
												<i class="fa fa-check" aria-hidden="true"></i>
											</button>
										</td>
										<td align="center">
											<button id="btn_eliminar_<?php echo $value['idProducto'] ?>" type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-danger" onclick="configuracion.eliminar_platillo({id:<?php echo $value['idProducto'] ?>})">
												<i class="fa fa-times" aria-hidden="true"></i>
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
	configuracion.convertir_dataTable({id:'tabla_productos'});
</script>