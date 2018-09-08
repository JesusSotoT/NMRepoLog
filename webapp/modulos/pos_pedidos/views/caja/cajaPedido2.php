<?php 
 //echo 'hola';
?>
<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Caja</title>
  
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../../libraries/typeahead/typeahead.css">
	<script src="../../libraries/jquery.min.js"></script>
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="../../libraries/numeric.js"></script>
	<script src="js/caja2.js"></script>
	<script src="../../libraries/numeric/jquery.numeric.js"></script>
	<script src="../../libraries/typeahead/typeahead.js"></script>
	<!--Select 2 -->
	<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

	<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

	<!-- Modificaciones RCA -->
	<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
	<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
	<script src="../../libraries/export_print/buttons.html5.min.js"></script>
	<script src="../../libraries/export_print/jszip.min.js"></script>

	<script>
		$(document).ready(function() {
			$('#monedaVenta').select2({width: '100%'});
			$( document ).keydown(function(e) {
				keyCode = e.which;
				if (keyCode == 13 && (!$('#search-producto').is( ":focus" ) && $('.typeahead').is( ":focus" ))) {
					//alert("El campo de busqueda no tenia el foco intentelo nuevamente.");
					$('#search-producto').focus();
				}
			});

			caja.init();
			$('.numeros').numeric();
			$('#tableSales').DataTable({
				dom: 'Bfrtip',
				buttons: ['excel'],
				language: {
					search: "Buscar:",
					lengthMenu:"",
					zeroRecords: "No hay datos.",
					infoEmpty: "No hay datos que mostrar.",
					info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
					paginate: {
						first:      "Primero",
						previous:   "Anterior",
						next:       "Siguiente",
						last:       "Último"
					},
				},
				aaSorting : [[0,'desc' ]]
			});
		});
	</script>
	
	<style>

	body {
		overflow-x:hidden !important;
	}
	/* CSS used here will be applied after bootstrap.css */
.modal-header-success {
	color:#fff;
	padding:9px 15px;
	border-bottom:1px solid #eee;
	background-color: #5cb85c;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-topright: 5px;
	 border-top-left-radius: 5px;
	 border-top-right-radius: 5px;
}
.modal-header-warning {
	color:#fff;
	padding:9px 15px;
	border-bottom:1px solid #eee;
	background-color: #f0ad4e;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-topright: 5px;
	 border-top-left-radius: 5px;
	 border-top-right-radius: 5px;
}
.modal-header-danger {
	color:#fff;
	padding:9px 15px;
	border-bottom:1px solid #eee;
	background-color: #d9534f;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-topright: 5px;
	 border-top-left-radius: 5px;
	 border-top-right-radius: 5px;
}
.modal-header-info {
	color:#fff;
	padding:9px 15px;
	border-bottom:1px solid #eee;
	background-color: #5bc0de;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-topright: 5px;
	 border-top-left-radius: 5px;
	 border-top-right-radius: 5px;
}
.modal-header-primary {
	color:red;
	padding:9px 15px;
	border-bottom:1px solid #eee;
	background-color: #428bca;
	-webkit-border-top-left-radius: 5px;
	-webkit-border-top-right-radius: 5px;
	-moz-border-radius-topleft: 5px;
	-moz-border-radius-topright: 5px;
	 border-top-left-radius: 5px;
	 border-top-right-radius: 5px;
}
.wrapPro {
	word-wrap: break-word;
	position:justify;
	font-size: 11px;
	width: 80%;
	padding: 10px 10px 10px 10px;
	height: auto;
	overflow-x: auto;
	color: #000;
}

#cliente-caja{
	margin-bottom: -3%;
	width: 100%;
}
#search-producto{
	margin-bottom: -3%;
	width: 100%;
}


	</style>
	</head>
	<body>
<div class="container well">
<!--- Row de Ventas Suspendidas -->
<!--<div class="row"> 
			<div id="divSuspendidas" class="col-xs-12 well" style="display:none;">
			<form class="form-horizontal" role="form">
				<div class="form-group col-xs-12">
					<div class="col-xs-12">
						<label class="col-xs-2 control-label">Ventas suspendidas:</label>
						<select id="s_cliente" class="form-control">
							<option value="0" selected>Selecciona</option>
						</select>
					</div>
					<br>
					<div id="divAccionesSuspender" class="form-group col-xs-12">
						<input type="button" value="Cargar" class="btn btn-success btn btn-success col-xs-offset-1 col-xs-3" onclick="caja.cargarSuspendida();"> 
						<input id="sselimina" type="button" class="btn btn-danger col-xs-offset-1 col-xs-3" value="Eliminar" onclick="caja.eliminarSuspendida();"> 
						<input  id="ssnueva" class="btn btn-success col-xs-offset-1 col-xs-3"  type="button" value="Realizar nueva venta" onclick="caja.cancelarCaja();">
					</div>
				</div>
			</form>
		</div>
</div> -->
<!-- Fin del Row de Ventas Suspendidas -->
<!-- Inicio del primer Row(documento, cantidad,cliente) -->
<div class="row" style="margin-top:-1%;">
	<div class="col-sm-7">
		<div class="row">
			<div class="col-sm-3">
				<button class="btn btn-default btn-block" onclick="caja.backGrid();"><i class="fa fa-arrow-left" aria-hidden="true"></i> Regresar</button>
			</div>
			<!-- <div class="col-sm-1">
				<i class="fa fa-filter" aria-hidden="true" onclick="caja.toggleFilters();"></i>
			</div> -->
			<div class="col-sm-4">
				<select id="monedaVenta" onchange="caja.buscaProdCoin();" class="form-control">
					<?php 
						foreach ($moneda as $key => $value) {
							echo '<option value="'.$value['coin_id'].'">'.$value['description'].'/'.$value['codigo'].'</option>';
						}
					?>
				</select>
			</div>
			<div class="col-sm-4">
				<div class="input-group">
					<span class="input-group-addon"><label>Cantidad</label></span>
					<input type="text" class="typeahead form-control numero" onkeypress="return caja.isNumberKey(event)" id="cantidad-producto" data-provide="typeahead" value="1" style="width:100%;">
				</div>               
			</div>
		<!--    <div class="col-sm-4">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
					<input type="text" class="typeahead form-control " id="search-producto" placeholder="Ingrese c&oacute;digo o descripci&oacute;n" data-provide="typeahead" onkeypress="caja.busquedaXcodigo(event)">
				</div>                
			</div> -->
		</div>
	</div>
	<div class="col-sm-5">
		<div class="row">
			<div class="col-sm-6">
				<div class="input-group">
					<span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
					<input type="text" class="typeahead form-control " id="search-producto" placeholder="Ingrese c&oacute;digo o descripci&oacute;n" data-provide="typeahead" onkeypress="caja.busquedaXcodigo(event)">
				</div>                
			</div>
			<div class="col-sm-6">

			
			<input type="text" class="form-control " id="cliente-cajaP" value="<?php echo $clientes[0]['nombre']; ?>" readonly>
			<input type="hidden" id="hidencliente-caja" value="<?php echo $clientes[0]['id']; ?>">
			<input type="hidden" id="listaDePreciosClient">
			<input type="hidden" id="idPedidox" value="<?php echo $idPedido; ?>">

			</div>
		<!--    <div class="col-sm-6">
				<div id="selectrfc" style="display:none;">
					
					<select class="form-control" id="rfc">
						<option value="0">XAXX010101000</option>
					</select>
				</div>  
			</div> -->
		</div>
	</div>   
</div><!-- Fin del primer Row(documento, cantidad,cliente) -->
<!-- Inicio del dicv de productos y cuenta -->

<div class="row">
	<!-- inicio productos -->
	<div class="col-sm-7">
						<div class="panel-group" id="accordion_insumos_preparados" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default">
							<div hrefer class="panel-heading" id="heading_insumos_preparados" role="tab" role="button" style="cursor: pointer"  data-parent="#accordion_insumos_preparados" href="#tab_insumos_preparados" aria-controls="collapse_insumos_preparados" aria-expanded="true">
							<div class="row">
								<div class="col-sm-2">
									<h4 class="panel-title">
										<h5><strong>Productos</strong></h5>
									</h4> 
								</div>

								<div class="col-sm-10">
									<input type="hidden" id="rango" value="100">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <select class="erre form-control " id="selectDepartamento" >

                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="erre form-control" id="selectFamilia" >

                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="erre form-control" id="selectLinea" >

                                            </select>
                                        </div>
                                    <div class="col-sm-1">
                                    
                                        <i class="fa fa-undo fa-2x" aria-hidden="true" onclick="caja.resetFilters();" style="color: white; background-color: rgba(0, 0, 0, 0.1); border-radius: 100%; margin: 4px;"></i>
                                    
                                    </div>
                                    </div>
                                </div>
							</div>
								

								
							</div>
							<div id="tab_insumos_preparados" class="" role="tabpanel" aria-labelledby="heading_insumos_preparados" >
								<div class="panel-body" style="margin-top: 0px;">
								<!--    <div id="contProducts" style="height:300px;overflow:auto;" class="col-sm-12"> -->
										<div class="row ">
										
											<div id="containerTouch" style="overflow:auto;height:440px;" class="col-sm-12">
													   <?php 
															$contador = 1;
															$nombre = '';
																foreach ($proTouchContainer as $key => $value) {
																	if($value['descripcion_corta']!=''){
																		$nombre = substr($value['descripcion_corta'],0,10);
																	}else{
																		$nombre = substr($value['nombre'],0,10);
																	}

																	echo '<div class="pull-left" style="padding:2px;">';
																	echo '  <button class="btn btn-default" codigoProTouch="'.$value['codigo'].'" onclick="caja.agregaProTouch(this)">';
																	echo '    <div class="row">';
																	echo '       <div style="width:90px;" class="wrapPro">';
																	echo '          <label>'.$nombre.'</label>';
																	echo '       </div>';
																	echo '    </div>';
																	echo '    <div class="row">';
																	echo '      <div style="height:70px; width:100px;">';
																	echo '          <img src="../pos/'.$value['ruta_imagen'].'" alt="" style="height:70px; width:90px;">';
																	echo '      </div>';
																	echo '    </div>';
																	echo '    <div class="row">';
																	echo '      <label>$'.number_format($value['precio'],2).'</label>';
																	echo '    </div>';
																	echo '  </button>';
																	echo '</div>'; 
															   

																	$contador++;    

																}
															?>
											</div>
										</div> 
								<!--    </div> -->
								</div>
							</div>
						</div>
					</div>
	<!--   <div class="panel panel-info">
			<div class="panel-heading">
				<h4 class="panel-title">
					<i class="fa fa-th fa-lg" aria-hidden="true"></i>
					<strong>Productos</strong> 
				</h4>
			</div>
			<div class="panel-body">
				<div class="row">
					<div style="overflow:auto;height:440px;" class="col-sm-12">
							   <?php 
									$contador = 1;
									$nombre = '';
										foreach ($proTouchContainer as $key => $value) {
											if($value['descripcion_corta']!=''){
												$nombre = substr($value['descripcion_corta'],0,12);
											}else{
												$nombre = substr($value['nombre'],0,12);
											}

											echo '<div class="pull-left" style="padding:2px;">';
											echo '  <button class="btn btn-default" codigoProTouch="'.$value['codigo'].'" onclick="caja.agregaProTouch(this)">';
											echo '    <div class="row">';
											echo '       <div style="width:90px;" class="wrapPro">';
											echo '          <label>'.$nombre.'</label>';
											echo '       </div>';
											echo '    </div>';
											echo '    <div class="row">';
											echo '      <div style="height:70px; width:100px;">';
											echo '          <img src="'.$value['ruta_imagen'].'" alt="" style="height:70px; width:90px;">';
											echo '      </div>';
											echo '    </div>';
											echo '    <div class="row">';
											echo '      <label>$'.number_format($value['precio'],2).'</label>';
											echo '    </div>';
											echo '  </button>';
											echo '</div>'; 
									   

											$contador++;    

										}
									?>
					</div>
				</div>
			</div>
		</div> el container -->
				<!--    <div class="panel-group" id="accordion_insumos_preparados" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default">
							<div hrefer class="panel-heading" id="heading_insumos_preparados" role="tab" role="button" style="cursor: pointer" data-toggle="collapse" data-parent="#accordion_insumos_preparados" href="#tab_insumos_preparados" aria-controls="collapse_insumos_preparados" aria-expanded="true">
								<h4 class="panel-title">
									<i class="fa fa-th fa-lg" aria-hidden="true"></i>
									<strong>Productos</strong> 

								</h4>
							</div>
							<div id="tab_insumos_preparados" class="panel-collapse collapse " role="tabpanel" aria-labelledby="heading_insumos_preparados" >
								<div class="panel-body" >
									<div id="contProducts" style="height:300px;overflow:auto;" class="col-sm-12">
										<div class="row">
										   <!-- <div class="col-sm-12"> -->
											<!-- <div class="col-sm-3"> -->
									<?php 
										foreach ($proTouchContainer as $key => $value) {
										 /*   echo '<div class="pull-left" style="padding:2px;">';
											echo '  <button class="btn btn-default" codigoProTouch="'.$value['codigo'].'" onclick="caja.agregaProTouch(this)">';
											echo '    <div class="row">';
											echo '       <div style="width:110px;" class="wrapPro">';
											echo '          <label>'.$value['nombre'].'</label>';
											echo '       </div>';
											echo '    </div>';
											echo '    <div class="row">';
											echo '      <div style="height:90px; width:120px;">';
											echo '          <img src="'.$value['ruta_imagen'].'" alt="" style="height:90px; width:110px;">';
											echo '      </div>';
											echo '    </div>';
											echo '    <div class="row">';
											echo '      <label>$'.$value['precio'].'</label>';
											echo '    </div>';
											echo '  </button>';
											echo '</div>'; */

										}
									?>
				<!--                        </div> 
									</div>
								</div>
							</div>
						</div>
					</div> -->
					   <!--Inicio del Div de Botones -->
			<!--    <div class="row" style="margin-top:-2%;">
					<div class="col-sm-12">
								<div aria-multiselectable="true" role="tablist" id="accordion_funciones" class="panel-group">
									<div class="panel panel-success">
										<div aria-expanded="true" aria-controls="collapse_funciones" href="#tab_funciones" data-parent="#accordion_funciones" data-toggle="collapse" style="cursor: pointer" role="tab" id="heading_funciones" class="panel-heading" hrefer="">
											<h4 class="panel-title">
												<strong><i class="fa fa-wrench"></i> Funciones</strong>
											</h4>
										</div>
										<div aria-labelledby="heading_funciones" role="tabpanel" class="panel-collapse collapse" id="tab_funciones" aria-expanded="true" style="">
											<div class="panel-body">
												<div class="col-sm-4">
													<button type="button" class="btn btn-warning btn-lg btn-block" onclick="caja.corteButtonAccion();"> <i class="fa fa-scissors"></i> Corte</button>
												</div>
												<div class="col-sm-4">
													<button type="button" class="btn btn-default btn-lg btn-block" onclick="caja.ventasButtonAccion();"> <i class="fa fa-shopping-cart"></i> Venta</button>
												</div>
												<div class="col-sm-4">
													<button type="button" class="btn btn-info btn-lg btn-block" onclick="caja.facturarButton();"><i class="fa fa-file-text-o"></i> Factura</button>
												</div>
											</div>
										</div>
									</div>
								</div>
					</div>                    
				</div> --><!--Fin del Div de Botones -->
	</div>
	<!-- Fin productos -->
	<!-- inicio Cuenta -->
	<div class="col-sm-5">
		<div class="row">
			<div class="panel panel-primary" class="col-sm-12" >
				<div class="panel-heading "> <i class="fa fa-list-alt fa-lg" aria-hidden="true"></i> Productos pedidos </div>
				<div class="panel-body" >
				<div style="height:330px;overflow: auto;"> 
					<table class="table table-hover" id="productsTable1" style="background-color:#F9F9F9; border:1px solid #c8c8c8;">
						<thead>
						  <tr>
							<th>Cant.</th>
							<th>Producto</th>
							<th>Precio</th>
							<th>Subtotal</th>
							<th></th>
						  </tr>
						</thead>
						<div id="productsTable">
						<tbody>
						 <!-- <tr>
							<td><input type="text" value="3" style="width:27%"></td>
							<td>Jamon Fud viena</td>
							<td>$<input type="text" value="4" class="span1" style="width:30%"></td>
							<td>$200.00</td>
							<td><span class="glyphicon glyphicon-trash"></span></td>
						  </tr> -->
	   
						</tbody>
						</div>
					</table>
					</div>   
					<div class="row">
						<div id="articulos" class="col-sm-6">
							Articulos:
							<label id="totalDeProductos">0</label>
							<input type="hidden" id="totalDeProductosInput">
						</div>
						<div id='desceunto' class="col-sm-6" style="display:none;">
							<div class="row">
								<div class="col-sm-6"><input type="text" id="descuentoGeneral" class="form-control numeros" placeholder="Desc %"></div>
								<div class="col-sm-6"><button class="btn btn-default" onclick="caja.aplicaDescuento();">Aplicar</button></div>
							</div>
						 <!--   <label>Descuento %</label>
							<input type="text" id="descuentoGeneral">
						   <button class="btn btn-default" onclick="caja.aplicaDescuento();">Aplicar</button> -->
					  <!--  </div> -->
					<!--    <div class="col-sm-2">
							<div style="padding-top:10%;">
							<button type="button" class="btn btn-default" onclick="caja.aplicaDescuento();">Aplicar</button>
						   </div>
						</div> -->
					</div>
					</div>
				</div>
			</div>  
		</div> 
		<div class="row">
			<div class="panel panel-default" id="desgloce-total" style="background-color:#E9E9E9;margin-top:-2%;">
				<div class="panel-body">
					<div id="cargos">
						<div style="font-size:15px;" id="desDiven">

						</div>
						<div class="immpuestos" id="impestosDiv" style="font-size:20px;">
						   <!-- <div class="row">
								<div class="col-sm-3">
									IVA
									
								</div>
								<div class="col-sm-3">
									<label>$23454</label>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									IVA
									
								</div>
								<div class="col-sm-3">
									$23454
								</div>
							</div> -->
						</div>
						<div style="font-size:15px;">
							<div class="col-sm-6">
								Subtotal
							</div>
							<div class="col-sm-6">
								<label id="subtotalLabel">$0.00</label>
							</div>
						</div>
						<div class="total">
							<div id="pagar">
								 <button type="button" class="btn btn-success btn-lg btn-block" onclick="caja.pedir();"> <i class="fa fa-money"></i> PEDIR <label id="totalLabel">$0.00</label></button>
							</div>
						  <!--  <div class="col-sm-6">
							   <h1>Total</h1> 
							</div>
							 <div class="col-sm-6">
								<label><h1 id="totalLabel">$0.00</h1></label>
							</div> -->
						</div>                   
					</div>
				</div>
			</div>
		</div>         
	</div>
	<!-- Fin Cuenta -->
</div><!-- Fin del dicv de productos y cuenta -->

<!--Inicio del Div de Botones -->
<!--<div class="row">
	<div class="col-sm-4">
		<button type="button" class="btn btn-warning btn-lg btn-block" onclick="caja.corteButtonAccion();"> <i class="fa fa-scissors"></i> Corte</button>
	</div>
	<div class="col-sm-4">
		<button type="button" class="btn btn-default btn-lg btn-block" onclick="caja.ventasButtonAccion();"> <i class="fa fa-shopping-cart"></i> Venta</button>
	</div>
	<div class="col-sm-4">
		<button type="button" class="btn btn-info btn-lg btn-block" onclick="caja.facturarButton();"><i class="fa fa-file-text-o"></i> Factura</button>
	</div>
</div> --><!--Fin del Div de Botones -->

<!--<div class="row" >
	<div class="col-sm-7" style="max-width:97vw !important; display:inline-block">
					<div class="panel-group" id="accordion_insumos_preparados" role="tablist" aria-multiselectable="true">
						<div class="panel panel-default">
							<div hrefer class="panel-heading" id="heading_insumos_preparados" role="tab" role="button" style="cursor: pointer" data-toggle="collapse" data-parent="#accordion_insumos_preparados" href="#tab_insumos_preparados" aria-controls="collapse_insumos_preparados" aria-expanded="true">
								<h4 class="panel-title">
									<i class="fa fa-th fa-lg" aria-hidden="true"></i>
									<strong>Productos</strong> 

								</h4>
							</div>
							<div id="tab_insumos_preparados" class="panel-collapse collapse " role="tabpanel" aria-labelledby="heading_insumos_preparados" >
								<div class="panel-body" >
									<div id="contProducts" style="height:300px;overflow:auto;" class="col-sm-12">
										<div class="row">
										   <!-- <div class="col-sm-12"> -->
											<!-- <div class="col-sm-3"> -->
									<?php 
										foreach ($proTouchContainer as $key => $value) {
										  /*  echo '<div class="pull-left" style="padding:2px;">';
											echo '  <button class="btn btn-default" codigoProTouch="'.$value['codigo'].'" onclick="caja.agregaProTouch(this)">';
											echo '    <div class="row">';
											echo '       <div style="width:110px;" class="wrapPro">';
											echo '          <label>'.$value['nombre'].'</label>';
											echo '       </div>';
											echo '    </div>';
											echo '    <div class="row">';
											echo '      <div style="height:90px; width:1100px;">';
											echo '          <img src="'.$value['ruta_imagen'].'" alt="" style="height:90px; width:110px;">';
											echo '      </div>';
											echo '    </div>';
											echo '    <div class="row">';
											echo '      <label>$'.$value['precio'].'</label>';
											echo '    </div>';
											echo '  </button>';
											echo '</div>'; */

										}
									?>
											<!--    <div class="pull-left" style="padding:5px;">
													<button class="btn btn-default">
														<div class="row">
															<label>jdjdjdjdjdjjd</label>
														</div>
														<div class="row">
														<div style="height:90px; width:120px;">
															<img src="noimage.jpeg" alt="" style="height:90px; width:110px;">
														</div>
															
														</div>
														<div class="row">
															<label>$100.00</label>
														</div>
													</button>    
												</div> -->
										 <!--  </div> -->
								   <!--     </div> 
									</div>
								</div>
							</div>
						</div>
					</div>
	<!--    <div class="panel panel-primary" class="col-sm-12" >
			<div class="panel-heading "> <i class="fa fa-list-alt fa-lg" aria-hidden="true"></i> Productos en Venta </div>
			<div class="panel-body" >
			<div style="height:380px;overflow: auto;"> 
				<table class="table table-hover" id="productsTable1" style="background-color:#F9F9F9; border:1px solid #c8c8c8;">
					<thead>
					  <tr>
						<th>Cant.</th>
						<th>Producto</th>
						<th>Precio</th>
						<th>Subtotal</th>
						<th></th>
					  </tr>
					</thead>
					<div id="productsTable">
					<tbody>
					 <!-- <tr>
						<td><input type="text" value="3" style="width:27%"></td>
						<td>Jamon Fud viena</td>
						<td>$<input type="text" value="4" class="span1" style="width:30%"></td>
						<td>$200.00</td>
						<td><span class="glyphicon glyphicon-trash"></span></td>
					  </tr> -->
   
   <!--                 </tbody>
					</div>
				</table>
				</div>   
				<div class="row">
					<div id="articulos" class="col-sm-6">
						Articulos:
						<label id="totalDeProductos">0</label>
					</div>
					<div id='desceunto' class="col-sm-4"  style="display:none;">
						<label>Descuento %</label>
						<input type="text" id="descuentoGeneral" class="form-control">
					   
					</div>
					<div class="col-sm-2" style="display:none;">
						<div style="padding-top:10%;">
						<button type="button" class="btn btn-default" onclick="caja.aplicaDescuento();">Aplicar</button>
					   </div>
					</div>
				</div>
			</div>
		</div> -->
	   <!-- <div id="Acciones">
			<div class="col-sm-4"><button type="button" class="btn btn-warning btn-lg btn-block">Corte</button></div>
			<div class="col-sm-4"><button type="button" class="btn btn-default btn-lg btn-block">Venta</button></div>
			<div class="col-sm-4"><button type="button" class="btn btn-info btn-lg btn-block">Factura</button></div>
		</div> 
	</div>-->
   
<!--    <div class="col-sm-5" id="cliente">
		<div class="panel-body">-->
		  <!--  <div class="input-group" id="clienteSelect">
			  <span class="input-group-btn">
				<button class="btn btn-default" type="button">Cliente</button>
			  </span>
			  <input type="text" class="form-control">
			</div> -->
   <!--     <div id="documentoDiv">
			<label>Documento</label>
			<select class="form-control" id="documento">
				<option value="1">Ticket</option>
				<option value="2">Factura</option>
			</select>
		</div>
		<div id="selectrfc" style="display:none;">
			<label>RFC</label>
			<select class="form-control" id="rfc">
				<option value="0">XAXX010101000</option>
			</select>
		</div>   -->
	


	  <!--  <div class="panel panel-default" id="desgloce-total" style="background-color:#E9E9E9;">
			<div class="panel-body">
				<div id="cargos">
					<div class="immpuestos" id="impestosDiv" style="font-size:20px;">
					   <!-- <div class="row">
							<div class="col-sm-3">
								IVA
								
							</div>
							<div class="col-sm-3">
								<label>$23454</label>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-3">
								IVA
								
							</div>
							<div class="col-sm-3">
								$23454
							</div>
						</div> -->
	 <!--               </div>
					<div style="font-size:20px;">
						<div class="col-sm-6">
							Subtotal
						</div>
						<div class="col-sm-6">
							<label id="subtotalLabel">$0.00</label>
						</div>
					</div>
					<div class="total">
						<div class="col-sm-6">
						   <h1>Total</h1> 
						</div>
						 <div class="col-sm-6">
							<label><h1 id="totalLabel">$0.00</h1></label>
						</div>
					</div>                   
				</div>
			</div>
		</div> -->
		
	<!--    <div id="pagar">
			<button type="button" class="btn btn-success btn-lg btn-block" onclick="caja.modalPagar();"> <i class="fa fa-money"></i> PAGAR</button>
		</div>
		<br>
		<div id="Promociones">
		<div id="Acciones">
			<div class="col-sm-4"><button type="button" class="btn btn-warning btn-lg btn-block" onclick="caja.corteButtonAccion();"> <i class="fa fa-scissors"></i> Corte</button></div>
			<div class="col-sm-4"><button type="button" class="btn btn-default btn-lg btn-block" onclick="caja.ventasButtonAccion();"> <i class="fa fa-shopping-cart"></i> Venta</button></div>
			<div class="col-sm-4"><button type="button" class="btn btn-info btn-lg btn-block" onclick="caja.facturarButton();"><i class="fa fa-file-text-o"></i> Factura</button></div>
		</div>
		</div> -->
		<!--</div> 
	</div>
</div>-->
  <div class="modal fade" id="modalConfirm" role="dialog" >
	<div class="modal-dialog modal-md">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Pedido</h4>
		</div>
		<div class="modal-body">
			<div class="row"><div align="center"><label>Deseas confirmar pedido?</label></div></div>
			<div class="row"><div align="center"><label>Observaciones</label></div></div>
				    <div class="row">
                        <div class="col-md-12">
                            <textarea id="txtareaObservaciones" style="width:100%; resize:none" rows="5" class="form-control"></textarea>
                        </div>
                    </div><br>
	
			<div class="row">
			 <div class="col-sm-6"><button class="btn btn-warning btn-block" onclick="javascript:$('#modalConfirm').modal('toggle');">Cancelar</button></div>
				<div class="col-sm-6"><button class="btn btn-primary btn-block" onclick="caja.pedirConfirm();">Confirmar</button></div>
			   
			</div>
		
		</div>
	  </div>
	</div>
  </div>

  <div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
	<div class="modal-dialog modal-sm">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Espere un momento...</h4>
		</div>
		<div class="modal-body">
		  <div class="alert alert-default">
			<div align="center"><label id="lblMensajeEstado"></label></div>
			<div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
				 <span class="sr-only">Loading...</span>
			 </div>
		</div>
		</div>
	  </div>
	</div>
  </div>

	<div class="modal fade" id="modalPagar" role="dialog">
	<div class="modal-dialog modal-md">
	  <div class="modal-content">
		<div class="modal-header modal-header-success">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title"><i class="fa fa-money fa-lg"></i> Pagos</h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-sm-4">
					<select id="cboMetodoPago" class="form-control">
						<option value="1">Efectivo</option>
						<option value="2">Cheque</option>
						<option value="3">Tarjeta de regalo</option>
						<option value="4">Tarjeta de crédito</option>
						<option value="5">Tarjeta de debito</option>
						<option value="6">Crédito</option>
						<option value="7">Transferencia</option>
						<option value="8">Spei</option>
						<option value="9">-No Identificado-</option>
					</select>
				</div>
				<div class="col-sm-4">
					<input type="number" id="txtCantidadPago" class="form-control numeros">
				</div>
				<div class="col-sm-4">
					<button class="btn btn-default btn-block" id="btnAgregarPago">Agrega Pago</button>
				</div>
			</div><br>
					<div class="row" id="divReferenciaPago" style="display:none;">
						<div class="col-md-12">
							<label id="lblReferencia">Referencia transferencia:</label>
							<input type="text" id="txtReferencia" class="form-control pull-left" value="">
						</div>
					</div>
			<div class="row">
				<div class="col-sm-5">
					<label>Total a Pagar:</label>
					<label id="lblTotalxPagar"></label>
				</div>
				<div class="col-sm-2"></div>
				<div class="col-sm-5">
					<label>Entregado:</label>
					<label id="lblAbonoPago"></label>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>Metodo</th>
								<th>Cantidad</th>
								<th>Accion</th>
							</tr>
						</thead>
						<tbody id="divDesglosePagoTablaCuerpo">

						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-5">
					<label>Aun por Pagar:</label>
					<label id="lblPorPagar"></label>
				</div>
				<div class="col-sm-2"></div>
				<div class="col-sm-5">
					<label>Cambio:</label>
					<label id="lblCambio"></label>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3">
					<button class="btn btn-success btn-block" onclick="javascript:caja.pagar();">Pagar    </button>
				</div>
				<div class="col-sm-3">
					<button class="btn btn-warning btn-block" onclick="javascript:$('#modalPagar').modal('toggle'); caja.suspender();">Suspender</button>
				</div>
				<div class="col-sm-3">
					<button class="btn btn-primary btn-block" onclick="javascript:$('#modalPagar').modal('toggle');">Salir    </button>
				</div>
				<div class="col-sm-3">
					<button class="btn btn-danger btn-block" onclick="javascript:caja.cancelarCaja(true); $('#modalPagar').modal('toggle');">Cancelar  </button>
				</div>
			</div>
		</div>
	  </div>
	</div>
  </div>

	<div id='modalComprobante' class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Ticket</h4>
					<input type="hidden" id="idVentaTicket">
				</div>
				<div class="modal-body">
					<div class="row rTouch">
						<div class="col-md-12">
							<iframe id="frameComprobante" src="" frameborder="0" style="float:left;height:380px;width:100%;"></iframe>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
					<!--   <div class="col-md-6 col-md-offset-6">
							<input type="text" id="emailTicket" class="form-control">
							<button class="btn btn-primary" onclick="caja.enviarTicket();">Enviar</button>
							<button class="btn btn-danger btnMenu" onclick="javascript:window.location.reload();">Salir</button>
						</div> -->
						<div class="col-sm-2">
							<label>Email</label>
						</div>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="emailTicket">
						</div>
						<div class="col-sm-3">
							<button onclick="caja.enviarTicket();" class="btn btn-primary btn-block"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar</button>
						</div>
						<div class="col-sm-3">
							<button onclick="javascript:window.location.reload();" class="btn btn-danger btnMenu btn-block">Salir</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- Modal de observaciones para facturar -->
	<div id='modal_Observaciones' class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header modal-header-warning">
					<h4 class="modal-title">Observaciones</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<label class="col-md-12 text-center" > Comentarios en </label>
							<label class="col-md-12 text-center" id="lblComentarioE"></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<textarea id="txtareaObservacioneswww" style="width:100%; resize:none" rows="5"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- Modal de Facturacion -->
	<div id='modalFacturacion' class="modal fade facturarModales" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" style="width:90%">
			<div class="modal-content">
				<div class="modal-header modal-header-info">
					<button type="button" class="close" data-dismiss="modal" id="cierre">&times;</button>
					<h4 class="modal-title"><i class="fa fa-file-text-o fa-lg"></i>  Facturar</h4>
				</div>
				<div class="modal-body ">
					<div class="row">
						<div class="col-sm-3">
							<label>Introduce El RFC</label>
						</div>
						<div class="col-sm-6">
							<input type="text" id="rfcMoldal" class="form-control">
						</div>
						<div class="col-sm-3">
							<button type="button" onclick="caja.revisaRfc();" class="btn btn-primary">Verifica RFC</button>
						</div>
					</div>
					<br>
					<div style="overlow:auto;overflow-y: hidden;">
					<div class="row">
						
						<div class="col-sm-12" style="display:none;" id="gridHidden">
							
							<table class="table table-hover table-bordered" id="datosFactGrid" >
								<thead>
									<tr>
										<th>RFC</th>
										<th>Razon Social</th>
										<th>Correo</th>
										<th>Pais</th>
										<th>Regimen F.</th>
										<th>Domicilio</th>
										<th>Numero</th>
										<th>Codigo Postal</th>
										<th>Colonia</th>
										<th>Estado</th>
										<th>Municipio</th>
										<th>Ciudad</th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							
						</div>

					</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<!-- <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal de cliente -->
	<div id='modalCliente' class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-info">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-user-plus fa-lg" aria-hidden="true"></i> Agregar Cliente </h4>
				</div>
				<div class="modal-body">
					<div class="row">
					  <div class="col-sm-2">
						<label class="control-label" for="email">ID</label>
						<input id="idCliente" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['id'];}?>" readonly placeholder="(Autonumerico)">
					  </div>
					  <div class="col-sm-3">
						  <label class="control-label">Codigo</label>
						  <input id="codigo" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['codigo'];}?>">
					  </div>

					</div>

					<div class="row">
					  <div class="col-sm-6">
						<label class="control-label">Nombre</label>
						<input id="nombre" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['nombre'];}?>">
					  </div>
					  <div class="col-sm-6">
						  <label class="control-label">Tienda</label>
						  <input id="tienda" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['nombretienda'];}?>">
					  </div>
					</div>

					<div class="row">
					  <div class="col-sm-6">
						<label class="control-label">Direccion</label>
						<input id="direccion" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['direccion'];}?>">
					  </div>
					  <div class="col-sm-3">
						<label class="control-label">Exterior</label>
						<input id="numext" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['num_ext'];}?>">
					  </div>
					  <div class="col-sm-3">
						<label class="control-label">Interior</label>
						<input id="numint" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['num_int'];}?>">        
					  </div>
					</div>

					<div class="row">
					  <div class="col-sm-3">
						<label class="control-label">Colonia</label>
						<input id="colonia" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['colonia'];}?>"> 
					  </div>
					  <div class="col-sm-3">
						  <label class="control-label">Codio Postal</label>
						  <input id="cp" class="form-control numeros" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['cp'];}?>"> 
					  </div>
					  <div class="col-sm-3">
						  <label class="control-label">Estado</label>
						  <select id="estado" class="form-control" onchange="caja.municipiosF();">
							<option value="0">-Selecciona un estado</option>
							<?php 
							  foreach ($estados as $key => $value) {
								echo '<option value="'.$value['idestado'].'">'.$value['estado'].'</option>';
							  }
							?>
						  </select>
					  </div>
					  <div class="col-sm-3">
						  <label class="control-label">Municipio</label>
						  <select  id="municipios" class="form-control">
							<option value='0'>-Selecciona un municipio--</option>

						  </select>
					  </div>
					</div>

					<div class="row">
					  <div class="col-sm-3">
						<label class="control-label">Email</label>
						<input id="email" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['email'];}?>"> 
					  </div>
					  <div class="col-sm-3">
						<label class="control-label">Celular</label>
						<input id="celular" class="form-control numeros" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['celular'];}?>"> 
					  </div>
					  <div class="col-sm-3">
						<label class="control-label">Telefono 1</label>
						<input id="tel1" class="form-control numeros" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['telefono1'];}?>"> 
					  </div>
					  <div class="col-sm-3">
						<label class="control-label">Telefono 2</label>
						<input id="tel2" class="form-control numeros" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['telefono2'];}?>"> 
					  </div>      
					</div>
					
					<div class="row">
					  <div class="col-sm-3">
						<label class="control-label">RFC</label>
						<input id="rfc2" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['rfc'];}?>"> 
					  </div>
					  <div class="col-sm-3">
						<label class="control-label">CURP</label>
						<input id="curp" class="form-control" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['curp'];}?>"> 
					  </div>
					</div>

					<div class="row">
					  <div class="col-sm-3">
						<label class="control-label">Dias de Credito</label>
						<input id="diasCredito" class="form-control numeros" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dias_credito'];}?>"> 
					  </div>
					  <div class="col-sm-3">
						<label class="control-label">Limite de Credito</label>
						<input id="limiteCredito" class="form-control numeros" type="text" value="<?php 
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['limite_credito'];}?>"> 
					  </div>
					  <div class="col-sm-3">
						<label class="control-label">Moneda</label>
						<!--<input id="moneda" class="form-control" type="text" value=""> -->
						<select id="moneda" class="form-control">
						  <?php 
					
							foreach ($moneda as $keyMon => $valueMon) {
							  echo '<option value="'.$valueMon['coin_id'].'">'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
							}

						  ?>
						</select>
					  </div>
					  <div class="col-sm-3"></div>
					</div>

					<div class="row">
					  <div class="col-sm-3">
						<label class="control-label">Lista de Precio</label>
						<select id="listaPrecio" class="form-control">
						  <?php 
						  foreach ($listaPre as $key1 => $value1) {
							echo '<option value="'.$value1['id'].'">'.$value1['nombre'].'</option>';
						  }
						  ?>
						</select>
					  </div>
					  <div class="col-sm-3"></div>
					  <div class="col-sm-3"></div>
					  <div class="col-sm-3"></div>
					</div>

					<div class="row"><br>
					  <div class="col-sm-10"></div>
					  <div class="col-sm-1"></div>
					</div>    
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<button type="button" class="btn btn-primary" onclick="caja.guardaCliente();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
							<!-- <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal cliente guardado con exito -->
  <div id="modalSuccess" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<h4 id="modal-label">Exito!</h4>
			</div>
			<div class="modal-body">
				<p>Tu Cliente se guardo existosamente</p>
			</div>
			<div class="modal-footer">
				<button id="modal-btnconf2-uno" type="button" class="btn btn-default" onclick="caja.cierramodales();">Continuar</button> 
			</div>
		</div>
	</div> 
  </div>
<!-- Modal de Ventas -->
	<div id='modalVentasList' class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header modal-header-default">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-shopping-cart fa-lg"></i>  Ventas</h4>
				</div>
				<div class="modal-body">
			   <!-- <div class="col-sm-12"> -->
					<div class="row">
						<div class="col-sm-1">
							<label>ID Venta</label>
						</div>
						<div class="col-sm-3">
							<input type="text" id="inputidVenta" class="form-control">
						</div>
						<div class="col-sm-3">
							<button class="btn btn-info" onclick="caja.buscarVenta();"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
						</div>
						<div class="col-sm-3"></div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div style="height:400px;overflow:auto;">
							<table class="table table-bordered table-hover" id="tableSales">
								<thead>
									<tr>
										<th>Folio</th>
										<th>Fecha</th>
										<th>Cliente</th>
										<th>Empleado</th>
										<th>Sucursal</th>
										<th>Estatus</th>
										<th>Impuestos</th>
										<th>Monto</th>
										<th>Acciones</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							</div>
						</div>
					</div>
			 <!--   </div> -->
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<!-- <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>  
		<!-- Modal modalVentasDetalle -->
<!-- Modal de Ventas -->
	<div id='modalVentasDetalle' class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header modal-header-default">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="idFacPanel"></h4>
				</div>
				<div class="modal-body">
					<div style="height:400px;overflow:auto;">
						<div class="row">
							<div class="col-sm-12">
									<input id="idVentaHidden" type="hidden">
								<table class="table table-bordered" id="tableSale">
									<thead>
										<tr>
											<th>Codigo</th>
											<th>Descripcion</th>
											<th>Cantidad</th>
											<th>Precio U.</th>
										   <!-- <th>Descuento</th> -->
											<th>Impuestos</th>
											<th>Subtotal</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							 
							</div>
						</div>  
					<div class="row">
					<div class="col-sm-6">
						<div id="pay">
							
						</div>
					</div>
					<div class="col-sm-3" id="impuestosDiv"></div>
					<div class="col-sm-3">
						<div id="subtotalDiv" class="totalesDiv"></div>
						<div id="totalDiv" class="totalesDiv"></div>
						<!-- inputs donde se guarda el total y subtotal -->
						<input type="hidden" id="inputSubTotal">
						<input type="hidden" id="inputTotal">
					</div>
					</div>
					</div>                  
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<button class="btn btn-danger" onclick="javascript:caja.cancelaVenta();"> <i class="fa fa-ban" aria-hidden="true"></i> Cancelar</button> 
							<button class="btn btn-primary" onclick="javascript:caja.reImprimeticket();"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</button> 
							<button class="btn btn-warning" onclick="javascript:$('#modalVentasDetalle').modal('hide');"><i class="fa fa-times" aria-hidden="true"></i> Salir</button> 
						  <!--  <button class="btn btn-info" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>  
<!-- Modal de Formulario Facturacion -->
	<div id='modalCuestion' class="modal fade facturarModales" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header modal-header-warning">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-file-text-o fa-lg"></i>  Facturar</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-12">
							No se encontraron coincidencias. ¿Quieres dar de alta tus datos para facturacion.?
						</div>
					</div>
					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-4">
							<button class="btn btn-success btn-block" onclick="caja.despliegaForm();">Dar de Alta los datos</button>
						</div>
						<div class="col-sm-4">
							<button class="btn btn-danger btn-block">Salir</button>
						</div>
						<div class="col-sm-2"></div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<!-- <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal de formulario de datos de Facturacion -->
	<div id='modalFormFact' class="modal fade facturarModales" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header modal-header-info">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-file-text-o fa-lg"></i>  Facturar</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-1">
							<div id="newOrUpd"></div>
						</div>
						<div class="col-sm-1">
							<input type="hidden" id="comFacId">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<label>RFC</label>
							<input type="text" class="form-control formF" id="rfcFormF">
						</div>
						<div class="col-sm-4">
							<label>Razon Social <span>*</span></label>
							<input type="text" class="form-control formF" id="razonSFormF">
						</div>
						<div class="col-sm-4">
							<label>Correo<span>*</span></label>
							<input type="text" class="form-control formF" id="emailFormF">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<label>Pais<span>*</span></label>
							<input type="text" class="form-control formF" id="paisFormF">
						</div>
						<div class="col-sm-4">
							<label>Regimen Fiscal<span>*</span></label>
							<input type="text" class="form-control formF" id="regimenFormF">
						</div>
						<div class="col-sm-4">
							<label>Domicilio<span>*</span></label>
							<input type="text" class="form-control formF" id="domicilioFormF">
						</div>
					</div>                    
					<div class="row">
						<div class="col-sm-4">
							<label>Numero Ext. int.<span>*</span></label>
							<input type="text" class="form-control formF" id="numeroFormF">
						</div>
						<div class="col-sm-4">
							<label>Codigo Postal<span>*</span></label>
							<input type="text" class="form-control formF" id="cpFormF">
						</div>
						<div class="col-sm-4">
							<label>Colonia<span>*</span></label>
							<input type="text" class="form-control formF" id="coloniaFormF">
						</div>
					</div>                    
					<div class="row">
						<div class="col-sm-4">
							<label>Estado<span>*</span></label>
							<select id="estadoFormF" class="form-control formF" onchange="caja.municipiosFact();">
								<option value="0">-Selecciona un Estado-</option>
								<?php 
									foreach ($estados as $keyE => $valueE) {
										echo '<option value="'.$valueE['idestado'].'">'.$valueE['estado'].'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-sm-4">
							<label>Municipio<span>*</span></label>
							<select id="municipioFormF" class="form-control formF">
								<option value="0">-Selecciona un Municipio-</option>
								<?php 
									foreach ($municipios as $keyE => $valueE) {
										echo '<option value="'.$valueE['idmunicipio'].'">'.$valueE['municipio'].'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-sm-4">
							<label>Ciudad<span>*</span></label>
							<input type="text" class="form-control formF" id="ciudadFormF">
						</div>
					</div>                    
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<div id="butlo" style="display:none;"><i class="fa fa-refresh fa-spin fa-3x fa-fw margin-bottom"></i></div>
							<div id="but">
								<button class="btn btn-primary" onclick="caja.guardaFormF();"><i class="fa fa-floppy-o"></i> Guardar</button> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id='modalCodigoVenta' class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header modal-header-info">
					<button type="button" class="close" data-dismiss="modal" >&times;</button>
					<h4 class="modal-title"><i class="fa fa-file-text-o fa-lg"></i>  Venta a Facturar</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<input type="hidden" id="idComunFactu">
					</div>
					<div class="row">
						<div class="col-sm-2">
							<label>Ingresa el Codigo del Ticket</label>
						</div>
						<div class="col-sm-3">
							<input type="text" id="codigoTicket" class="form-control">
						</div>
						<div class="col-sm-3">
							<button class="btn btn-default" onclick="caja.buscaTicket();"> Verifica Ticket</button>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div style="height:400px; display:none;" id="ticketHideDiv">
								<iframe id="ticketDiv" src="" frameborder="0" style="float:left;height:100%;width:100%;"></iframe>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<div id="facB" style="display:none;">
								<button class="btn btn-success" onclick="caja.factSale();"><i class="fa fa-floppy-o"></i> Facturar</button> 
						   </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Div del inicio de Caja -->
	<div id='inicio_caja' class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Inicializar caja</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12" id="divContSucursal">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Saldo actual en caja:</label>
							<label id="saldocaja"></label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Ingrese con cuanto inicia caja:</label>
							<input type="text" class="form-control" onkeypress="return caja.isNumberKey(event)" id="iniciocaja" maxlength="8" value="">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6">
							<button class="btn btn-success btnMenu btn-block" onclick="javascript:caja.cajaIniciar();">Iniciar</button>
						</div>
						<div class="col-md-6">
							<button class="btn btn-danger btnMenu btn-block" onclick="javascript:caja.cajaCancelar();">Cancelar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal de Corte de Caja -->
	<div id='modalCorteDeCaja' class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg" style="width:98%">
			<div class="modal-content">
				<div class="modal-header modal-header-warning">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><i class="fa fa-scissors"></i>  Corte de Caja</h4>
				</div>
				<div class="modal-body ">
				<!--    <div class="row">
						<div class="col-sm-3">
							<label>Desde</label>
							<input type="text" id="desdeCut" class="form-control" readonly>
						</div>
						<div class="col-sm-3">
							<label>Hasta</label>
							<input type="text" id="hastaCut" class="form-control" readonly>
						</div>
					</div> -->
					<br>
					<div class="row">
						<div class="col-sm-12">
							<div class="row">
								<div class="col-sm-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											
											<div class="row">
												<div class="col-sm-4">
													<h3 class="panel-title">Saldos</h3>
											   <!-- <label>Desde 2030-9494-90</label>
												<label>Hasta 4849-900-009</label> -->
												</div>
											   <div class="col-sm-4">
													<label>Desde: </label><label id="desdeCutText"></label>
													<input type="hidden" id="desdeCut" class="form-control" readonly>
												</div>
												<div class="col-sm-4">
													<label>Hasta: </label><label id="hastaCutText"></label>
													<input type="hidden" id="hastaCut" class="form-control" readonly>
												</div> 
											</div>
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-sm-2">
													<div class="input-group">
														<span class="input-group-addon">
															<label>Saldo inicial</label>
														</span>
														<input type="text" class="form-control" id="saldo_inicial" readonly>
													</div>    
												</div>
												<div class="col-sm-2">
													<div class="input-group">
														<span class="input-group-addon">
															<label>Monto Ventas</label>
														</span>    
														<input type="text" class="form-control" id="monto_ventas" readonly>
													</div>
												</div>
												<div class="col-sm-2">
													<div class="input-group">
														<span class="input-group-addon">
															<label>Disponible</label>
														</span>    
														<input type="text" class="form-control" id="saldo_disponible" readonly>
													</div>
												</div>
												<div class="col-sm-2">
													<div class="input-group">
														<span class="input-group-addon">
															<label>Retiro</label>
														</span>    
														<input type="text" class="form-control numeros" id="retiro_caja" style="background-color: #FFCCDD;">
													</div>
												</div>
												<div class="col-sm-2">
													<div class="input-group">
														<span class="input-group-addon">
															<label>Deposito</label>
														</span>    
														<input type="text" class="form-control numeros" id="deposito_caja" style="background-color: #A9F5A9;">
													</div>
												</div>
												<div class="col-sm-2">
											 
														<button type="button" class="btn btn-primary btn-block" onclick="caja.newCut();">Hacer Corte </button>
													
												</div>
											</div>
									   <!--     <div class="row">
												<div class="col-sm-12">
													<h4>Depositos/Retiros</h4>
												</div>
											</div> -->
										<!--    <div class="row">
												<div class="col-sm-4">
													<label>Retiro de Caja $</label>
													<input type="text" class="form-control numeros" id="retiro_caja" style="background-color: #FFCCDD;">
												</div>
												<div class="col-sm-4">
													<label>Deposito de Caja $</label>
													<input type="text" class="form-control numeros" id="deposito_caja" style="background-color: #A9F5A9;">
												</div>
												
												<div class="col-sm-3">
													<div style="padding-top:8%;">
														<button type="button" class="btn btn-primary btn-block" onclick="caja.newCut();">Hacer Corte </button>
													</div>
												</div>
											</div> -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div style="height:350px;overflow:auto;">
								<div class="row">
									<div class="col-sm-12">
										<h4>Pagos</h4>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-bordered table-hover" id="gridPagosCut">
											<thead>
												<tr>
													<th>ID Venta</th>
													<th>Cliente</th>
													<th>Fecha</th>
													<th>EF</th>
													<th>TD</th>
													<th>TC</th>
													<th>CR</th>
													<th>CH</th>
													<th>TRA</th>
													<th>SPEI</th>
													<th>TR</th>
													<th>NI</th>
													<th>Cambio</th>
													<th>Impuestos</th>
													<th>Monto</th>
													<th>Importe</th>
													<th>Ingreso(EF-Cambio)</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<h4>Productos Vendidos</h4>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-bordered table-hover" id="gridProductosCut">
											<thead>
												<tr>
													<th>Codigo</th>
													<th>Producto</th>
													<th>Cantidad</th>
													<th>Precio Unitario</th>
													<th>Descuento</th>
													<th>Impuestos</th>
													<th>Subtotal</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<h4>Retiros de Caja</h4>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-bordered table-hover" id="gridRetirosCut">
											<thead>
												<tr>
													<th>ID Retiro</th>
													<th>Fecha</th>
													<th>Concepto</th>
													<th>Usuario</th>
													<th>Cantidad</th>
												</tr>
											</thead>
											<tbody>
											</tbody>
										</table>
									</div>
								</div>
							</div>    
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<!-- <button class="btn btn-success btnMenu" onclick="javascript:caja.observacionesEnviar();">Enviar</button> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Descuento parcial-->

  <div class="modal fade" id="modalDescParcial" role="dialog">
	<div class="modal-dialog modal-md">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Descuento Parcial</h4>
		</div>
		<div class="modal-body">
			<div class="row">
			<input type="hidden" id="xProParc">
				<div class="col-sm-8">
					<h3 id="encabezadoNombre"></h3>
				</div>
				<div class="col-sm-4">
					<h3 id="encabezadoPrecio"></h3>
					<input type="hidden" id="encabezadoPrecioInput">
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-sm-6">
					<div class="row">   
						<div class="col-sm-4">
							<label>Descuento;</label>
						</div>
						<div class="col-sm-4">
							<select id="tipoDescu" class="form-control">
								<option value="%">%</option>
								<option value="$">$</option>
							</select>
						</div>
						<div class="col-sm-4">
							<input type="text" class="form-control" id="desCantidad">
						</div>
					</div>
				</div>
				<div class="col-sm-3">
					<button class="btn btn-primary btn-block" onclick="caja.aplicaDesParcial();">Aplicar</button>
				</div>
				<div class="col-sm-3">
					<button class="btn btn-danger btn-block" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div>
	  </div>
	</div>
  </div>







	<!--- Modal Caracteristicass -->
	<div id="modalCarac" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content panel-default">
				<div class="modal-header panel-heading">
					<h4 id="modal-labelCr"></h4>
					<input type="hidden" id="carIdProddiv">
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<img id="divImagenPro" height="250" width="250">
						</div>
						<div class="col-sm-6" id="prodCarcDiv"></div>
					</div>
				   <!-- <div class="row" id="prodCarcDiv"></div> -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" onclick="caja.agregaCarac();">Agregar</button> 
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
				</div>
			</div>
		</div> 
	</div>
</div>
	</body>
	</html>    