
<html lang="es">
<head>
		<meta http-equiv="Expires" content="0">
		<title>Cotizaciones</title>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/typeahead.css" />
		<link rel="stylesheet" href="css/caja/caja.css" />

		<?php include('../../netwarelog/design/css.php');?>
		<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/cotizacion/cotizacion.js" ></script>
		<script type="text/javascript" src="js/typeahead.js" ></script>
		<script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
		<script src="js/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

<script>
		$(document).ready(function() {
				$.ajax({
						url: 'ajax.php?c=cotizacion&f=getClient',
						type: 'GET',
						dataType: 'json',
				})
				.done(function(data) {
								$.each(data, function(index, value) {
										var optionCliente = $(document.createElement('option')).attr({'value': value.id}).html(value.nombre).appendTo($('#selectcliente'));
								});
				})
				.fail(function() {
						console.log("error");
				})
				.always(function() {
						console.log("complete");
				});
								$.ajax({
										url: 'ajax.php?c=cotizacion&f=getProduct',
										type: 'GET',
										dataType: 'json',
								})
								.done(function(data) {
										
										$.each(data, function(index, value) {
												var optionProduct = $(document.createElement('option')).attr({'value': value.idProducto}).html(value.nombre).appendTo($('#selectProduct'));
										 });
								})
								.fail(function() {
										console.log("error");
								})
								.always(function() {
										console.log("complete");
								});

		$("#selectcliente").select2({
				 width : "100px"
		});
		$("#selectProduct").select2({
				 width : "150px"
		});   

				
		});

		
</script>

<style>

	.tit_tabla_buscar td
	{
		font-size:medium;
	}

	#logo_empresa /*Logo en pdf*/
	{
		display:none;
	}

	@media print
	{
		#imprimir,#filtros,#excel, #botones
		{
			display:none;
		}
		#logo_empresa
		{
			display:block;
		}
		.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100% !important;
		}
	}
	.btnMenu{
			border-radius: 0; 
			width: 100%;
			margin-bottom: 0.3em;
			margin-top: 0.3em;
	}
	.row
	{
			margin-top: 0.5em !important;
	}
	h5, h4, h3{
			background-color: #eee;
			padding: 0.4em;
	}
	.modal-title{
		background-color: unset !important;
		padding: unset !important;
	}
	.nmwatitles, [id="title"] {
			padding: 8px 0 3px !important;
		background-color: unset !important;
	}
	.select2-container{
			width: 100% !important;
	}
	.select2-container .select2-choice{
			background-image: unset !important;
		height: 31px !important;
	}
	.twitter-typeahead{
		width: 100% !important;
	}
	.tablaResponsiva{
			max-width: 100vw !important; 
			display: inline-block;
	}
	.table tr, .table td{
		border: none !important;
	}
</style>

</head>

<body>

<div class="container" style="width:100%" id="contenido">
		<div class="row">
				<div class="col-md-12">
						<h3 class="nmwatitles text-center">
								Cotizaciones a Clientes
								<input type="hidden" id="idPedidoHide" value="<?php echo $idPedido; ?>">
						</h3>
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-2">
								 <input type="button" value="Regresar" onclick="backbutton();" class="btn btn-warning btnMenu">
							</div>
						</div>
						<div class="row">
								<div class="col-md-1">
								</div>
								<div class="col-md-10">
										<div class="panel panel-default">
												<div class="panel-heading">Cotizacion</div>
												<div class="panel-body">
														<div class="row">
																<div class="col-md-3">
																		<label>Cliente:</label>
																		<select name="" id="selectcliente" >
																				<option value="0">--Selecciona un Cliente--</option>
																		</select>
																</div>
																<div class="col-md-3">
																		<label>Productos:</label>
																		 <select name="" id="selectProduct" onchange="cargaListas()">
																				<option value="0">--Selecciona un Producto--</option>
																		</select>
																</div>
																<div class="col-md-2">
																		<label>Cantidad:</label>
																		<input type="text" id="cantidad" class="form-control">
																</div>
																
																<div class="col-md-2">
																		<label>Lista de Precios:</label>
																		 <select name="" id="selectPrecio" class="form-control">
																				<option value="0">--Selecciona un Precio--</option>
																		</select>
																</div>

																<div class="col-md-3">
																		<div style="margin-top:6%;">
																			<input type="button" value="Agregar" onclick="agrega();" class="btn btn-primary btnMenu">
																		</div>
																</div>
														</div> 
												</div>
										</div>
										<section id="tableContainer" style="display:none;">
												<h5>Productos agregados</h5>
												<div class="row">
														<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
																<div class="table-responsive">
																		<table class="table" id="cotTable">
																				<thead>
																						<tr>
																								<th>Cantidad</th>
																								<th>Producto</th>
																								<th>Unidad</th>
																								<th>Precio</th>
																								<th>Importe</th>
																								<th>Imagen</th>
																								<th></th>
																						</tr>
																				</thead>
																		</table>
																</div>
														</div>
												</div>
												<div class="row">
														<div class="col-md-4 col-sm-4" id="x">
														</div>
														<div class="col-md-4 col-sm-4" id="divTaxes">
														</div>
														<div class="col-md-4 col-sm-4" id="divTotal">
																<div class="total">Total:$<label id="totalLab"></label></div>
														</div>
												</div>
												<div class="row">
														<div class="col-md-6 col-sm-6">
																<label>Observaciones:</label>
																<textarea class="form-control" rows="3" id="observ"></textarea>
														</div>
														<div class="col-md-2 col-md-offset-2 col-sm-2 col-sm-offset-2">
																<div id="loading" style="display:none;">
																		<i class="fa fa-refresh fa-3x fa-spin" aria-hidden="true"></i>
																</div>
															 <div id="sendBtn">
																	<input type="button" value="Cotizar y Enviar" onclick="send();" class="btn btn-primary btnMenu" style="margin-top: 4.5em;">
															 </div>
														</div>
														<div class="col-md-2 col-sm-2">
															 
														</div>
												</div>
										</section>
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
						<div align="center"><label id="lblMensajeEstado">Procesando...</label></div>
						<div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
								 <span class="sr-only">Loading...</span>
						 </div>
				</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
			function cargaListas(){
					$('#selectPrecio option').remove();
				 
					$.ajax({
							url: 'ajax.php?c=cotizacion&f=listas',
							type: 'POST',
							dataType: 'json',
							data: {idProducto: $('#selectProduct').val()},
					})
					.done(function(resp) {
							console.log(resp);
							$.each(resp.pventa, function(key, value) {
					var option = $(document.createElement('option')).attr({'value': value.idProducto}).html(value.precio).appendTo($('#selectPrecio'));
				});
							$.each(resp.lventa, function(key, value) {
					var option = $(document.createElement('option')).attr({'value': value.idProducto}).html(value.precio).appendTo($('#selectPrecio'));
				});
					})
					.fail(function() {
							console.log("error");
					})
					.always(function() {
							console.log("complete");
				});
			}
	</script>
</body> 
</html>