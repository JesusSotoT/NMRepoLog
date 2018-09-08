<html lang="en">
<head>
	<meta charset="UTF-8">
<!-- ///////////////// ******** ---- 		CSS		------ ************ ////////////////// -->

<!-- jquery-ui -->
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<!-- bootstrap min CSS -->
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
<!-- bootstrap-select -->
	<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
<!-- Iconos font-awesome -->
   	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
<!-- Select con buscador  -->
   	<link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />
	
<!-- Sistema -->
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/pedidos/pedidos.css">
	
<!-- ///////////////// ******** ---- 			FIN CSS		------ ************ ////////////////// -->

<!-- ///////////////// ******** ---- 			JS			------ ************ ////////////////// -->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- jquery.scrollTo.js -->
		<script type="text/javascript" src="js/jquery.scrollTo.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
		
	<!-- ** Sistema -->
		<script type="text/javascript" src="js/pedidos/pedidos.js"></script>

<!-- ///////////////// ******** ---- 			FIN JS			------ ************ ////////////////// -->
	
<!--  Notify  -->
	<script src="js/notify.js"></script> 

	<script type="text/javascript">
		$(document).ready(function() {
			pedidos.autocompleteProductos();
			
			pedidos.listar_ajustes();

			$('#btnAsignar').bind('click', function() {
				pedidos.asignaridPropina();
			});
		});
	</script>
</head>
<body>
	<br />
	<div class="col-md-12 container-fluid" >
		<div class="panel panel-default">
			<div class="panel-heading">
				<h5>Configura el producto que funcionara como propina en la caja</h5>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="form-group col-md-4">
						<label class="sr-only col-md-2" for="txtPropina">Producto Propina</label>
						<input 
							type="text" 
							class="input-sm col-md-9" 
							id="txtPropina" 
							placeholder="Busca el producto propina, que previamente diste de alta.">
						<input type="button" value="Asignar" id="btnAsignar" class="btn btn-success">
					</div>
					<div class="col-md-2">
						<div class="input-group">
							<div class="input-group-addon">
								<input 
									onclick="pedidos.mostrar_propina({mostrar:$('#check_propina').prop('checked')})" 
									type="checkbox" 
									id="check_propina"/>
							</div>
							<input 
								id="text_propina" 
								type="text" 
								disabled="disabled" 
								class="form-control"
								value="Mostrar propina">
						</div>
					</div>
					<div class="col-md-2">
						<div class="input-group">
							<div class="input-group-addon">
								<input 
									onclick="pedidos.mostrar_consumo({mostrar:$('#check_consumo').prop('checked')})" 
									type="checkbox" 
									id="check_consumo"/>
							</div>
							<input 
								id="text_consumo" 
								type="text" 
								disabled="disabled" 
								class="form-control"
								value="Mostrar consumo">
						</div>
					</div>
					<div class="col-md-2">
						<div class="input-group">
							<div class="input-group-addon">
								Pedir contraseña:
							</div>
							<select 
								onchange="pedidos.actualizar_configuracion({pedir_pass: $('#pedir_pass').val()})" 
								id="pedir_pass" 
								class="selectpicker">
								<option value="1">Si</option>
								<option value="2">No</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="input-group">
							<div class="input-group-addon" id="text_tipo_operacion">
								Tipo Operación:
							</div>
							<select 
								onchange="pedidos.actualizar_configuracion({tipo: $('#tipo_operacion').val()})" 
								id="tipo_operacion" 
								class="selectpicker">
								<option value="1">Terminar Pedidos Después de Pago</option>
								<option value="2">Mantener Pedidos Después de Pago</option>
								<option value="3">Comida rapida</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="input-group">
							<div class="input-group-addon">
								Mostrar dolares:
							</div>
							<select 
								onchange="pedidos.actualizar_configuracion({mostrar_dolares: $('#mostrar_dolares').val()})" 
								id="mostrar_dolares" 
								class="selectpicker">
								<option value="1">Si</option>
								<option value="2">No</option>
							</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="input-group">
							<div class="input-group-addon">
								Mostrar comanda:
							</div>
							<select 
								onchange="pedidos.actualizar_configuracion({mostrar_info_comanda: $('#mostrar_info_comanda').val()})" 
								id="mostrar_info_comanda" 
								class="selectpicker">
								<option value="1">Si</option>
								<option value="2">No</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>