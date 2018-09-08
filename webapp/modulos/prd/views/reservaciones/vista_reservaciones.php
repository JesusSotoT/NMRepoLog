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
	<!--  Morris  -->
	    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
	<!-- Datepicker -->
    	<link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
		
<!-- ///////////////// ******** ---- 		FIN CSS		------ ************ ////////////////// -->

<!-- ///////////////// ******** ---- 		JS			------ ************ ////////////////// -->

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
	<!--  Morris  -->
		<script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
		<script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
	<!-- Datepicker -->
	    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
		
	<!-- Sistema -->
		<script src="js/reservaciones/reservaciones.js"></script>
		
<!-- ///////////////// ******** ---- 		FIN JS		------ ************ ////////////////// -->

		<script>
///////////////// ******** ---- 		select_buscador		------ ************ //////////////////

	//////// Cambia los select por select con buscador.
		// Como parametros puede recibir:
			// Array con los id de los select
		
			function select_buscador ($objeto) {
			// Recorre el arreglo y establece las propiedades del buscador
				$.each( $objeto, function( key, value ) {
					$("#"+value).select2({
						width : "150px"
					});
				});
			}

///////////////// ******** ---- 		FIN select_buscador		------ ************ //////////////////
		</script>
	</head>
	<body>
		<div class="panel panel-default" style="width: 100%">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-12">
						<div class="row"><?php
							date_default_timezone_set('America/Mexico_City');
					
							$f_fin = $f_ini = date('Y-m-d'); ?>
							
							<div class="col-md-5" style="padding-top: 20px">
								<div class="input-group input-group-lg">
							    	<div class="input-group-addon">
							    		<i class="fa fa-calendar"></i> inicio: 
							    	</div>
									<input type="text" id="f_ini" class="form-control" value="<?php echo $f_ini; ?>">
							    </div>
							</div>
							<div class="col-md-2" style="padding-top: 10px">
								<div class="input-group input-group-lg">
							    	Sucursal:<br />
									<select id="sucursal">
										<option selected value="*">-- Todas --</option><?php
										
										foreach ($sucursales as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>">
												<?php echo $value['nombre'] ?>
											</option> <?php
										} ?>
									</select>
								</div>
							</div>
							<div class="col-md-2" style="padding-top: 10px">
								<div class="input-group input-group-lg">
							    	Cliente:<br />
									<select id="cliente">
										<option selected value="*">-- Todos --</option><?php
										
										foreach ($clientes as $key => $value) { ?>
											<option value="<?php echo $value['id'] ?>">
												<?php echo $value['nombre'] ?>
											</option> <?php
										} ?>
										
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-5" style="padding-top: 15px">
								<div class="input-group input-group-lg">
								   	<div class="input-group-addon">
								   		<i class="fa fa-calendar"></i> fin: &nbsp;&nbsp;&nbsp;&nbsp;
								   	</div>
									<input type="text" id="f_fin" class="form-control" value="<?php echo $f_fin; ?>">
							    </div>
							</div>
							<div class="col-md-2" style="padding-top: 10px">
								<div class="input-group input-group-lg">
							    	Mesa:<br />
									<select id="mesas">
										<option selected value="*">-- Todas --</option><?php
									
									// Mesas es una variable que viene desde el controlador
										foreach ($mesas as $key => $value) { ?>
											<option value="<?php echo $value['mesa'] ?>">
												<?php echo $value['nombre_mesa'] ?>
											</option> <?php
										} ?>
										
									</select>
								</div>
							</div>
							<div class="col-md-1" style="padding-top: 15px">
								<button 
									id="btn_reservaciones" 
									class="btn btn-default btn-lg" 
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
									style="width: 135px" 
									onclick="reservaciones.listar({
										btn : 'btn_reservaciones',
										vista : 1,
										sucursal : $('#sucursal').val(),
										cliente : $('#cliente').val(),
										mesa : $('#mesas').val(),
										f_ini : $('#f_ini').val(),
										f_fin : $('#f_fin').val(),
										div : 'contenedor'
									}); ">
									<i class="fa fa-search"></i> Buscar
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div id="contenedor" class="col-md-12" style="overflow: scroll;height: 65%">
						<!-- En esta div se carga el contenido desde el modelo -->
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<script>
// Creamos un arreglo con los id de los select
	$objeto=[];
	$objeto[0]='cliente';
	$objeto[1]='mesas';
	$objeto[2]='sucursal';
	
// Mandamos llamar la funcion que crea los select con buscador
	select_buscador($objeto);
	
	reservaciones.listar({
		btn : 'btn_reservaciones',
		vista : 1,
		sucursal : $('#sucursal').val(),
		cliente : $('#cliente').val(),
		mesa : $('#mesas').val(),
		f_ini : $('#f_ini').val(),
		f_fin : $('#f_fin').val(),
		div : 'contenedor'
	}); 
	
	reservaciones.convertir_calendario({id: 'f_ini'});
	reservaciones.convertir_calendario({id: 'f_fin'});
</script>