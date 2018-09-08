<html>
	<head>
<!-- ///////////////// ******** ---- 		CSS				------ ************ ////////////////// -->

	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- Select con buscador  -->
    	<link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />
	<!--  dataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
	<!--  Morris  -->
	    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <!--Button Print css -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
	<!-- Datepicker -->
    	<link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
		
<!-- ///////////////// ******** ---- 		FIN CSS			------ ************ ////////////////// -->

<!-- ///////////////// ******** ---- 		JS				------ ************ ////////////////// -->
	 
	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap JavaScript -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- Select con buscador  -->
		<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- dataTables  -->
	<!--	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
	    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
	    <script src="../../libraries/export_print/jszip.min.js"></script> -->

	      <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<!--    <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RCA -->
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>
	<!-- Morris  -->
		<script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
		<script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
	<!-- Datepicker -->
	    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
		
	<!--  Sistema  -->
		<script type="text/javascript" src="js/comandas.js"></script>
		
<!-- ///////////////// ******** ---- 		FIN JS			------ ************ ////////////////// -->

		<script>
///////////////// ******** ---- 		select_buscador		------ ************ //////////////////
//////// Cambia los select por select con buscador.
	// Como parametros puede recibir:
		// Array con los id de los select
		$(document).ready(function() {

			$( "#btn_utilidad" ).on( "click", function() {

			});
			$( "#btn_utilidad" ).trigger( "click" );

		});
	function select_buscador ($objeto) {
	// Recorre el arreglo y establece las propiedades del buscador
		$.each( $objeto, function( key, value ) {
			$("#"+value).select2({
				width : "100%"
			});
		});
	}


///////////////// ******** ---- 		FIN select_buscador		------ ************ //////////////////
		</script>
	</head>
	
	<body>
	<div class="container well">
		<div class="row">
	        <div class="col-xs-12 col-md-12">
	           <h3>Utilidad </h3>
	        </div>
	    </div>
	    <div class="row">
		<div class="panel panel-default" style="width: 100%">
			<div class="panel-heading">
			<?php
					date_default_timezone_set('America/Mexico_City');
			
					$f_fin = $f_ini = date('Y-m-d'); 

					$f_ini = strtotime ( '-30 day' , strtotime ( $f_fin ) ) ;
					$f_ini = date ( 'Y-m-d' , $f_ini );
					 

					?>
				<div class="row">
					<div class="col-sm-3">

						<label>Desde</label>
                        <div id="datetimepicker1" class="input-group date">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input type="text" id="f_ini" class="form-control" value="<?php echo $f_ini; ?>">
                        </div>
						
					</div>
					<div class="col-sm-3">
						<label>Hasta</label>
						<div id="datetimepicker2" class="input-group date">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
						<input type="text" id="f_fin" class="form-control" value="<?php echo $f_fin; ?>">
                        </div>

					</div>
					<!-- <div class="col-sm-3">
						<label>Empleado</label><br>
							<select id="empleado" class="form-control">
								<option selected value="*">-- Todos --</option><?php
								
								foreach ($empleados as $key => $value) { ?>
									<option value="<?php echo $value['id'] ?>">
										<?php echo $value['usuario'] ?>
									</option> <?php
								} ?>
							</select>
					</div>
					<div class="col-sm-3">
						<label>Cliente</label><br>
							<select id="cliente" class="form-control">
								<option value="0">-Selecciona-</option>
								<?php 

									foreach ($clientes['rows'] as $key => $value) {
										echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
									} 


								?>
							</select>
					</div> -->
					<div class="col-sm-3">
						<label>Productos</label><br>
							<select id="producto" class="form-control">
								<option selected value="">-- Todos --</option><?php
							
							// $productos es una variable que viene desde el controlador
								foreach ($productos as $key => $value) { ?>
									<option value="<?php echo $value['idProducto'] ?>">
										<?php echo $value['nombre'] ?>
									</option> <?php
								} ?>
								
							</select>
					</div>
					<div class="col-sm-3">
						<label>Sucursal</label><br>
							<select id="sucursal" class="form-control">
								<option selected value="">-- Todas --</option><?php
								
								foreach ($sucursales as $key => $value) { ?>
									<option value="<?php echo $value['id'] ?>">
										<?php echo $value['nombre'] ?>
									</option> <?php
								} ?>
							</select>
					</div>
				</div>
				<div class="row">
					<!-- <div class="col-sm-3">
						<label>Productos</label><br>
							<select id="producto" class="form-control">
								<option selected value="*">-- Todos --</option><?php
							
							// $productos es una variable que viene desde el controlador
								foreach ($productos as $key => $value) { ?>
									<option value="<?php echo $value['idProducto'] ?>">
										<?php echo $value['nombre'] ?>
									</option> <?php
								} ?>
								
							</select>
					</div>
					<div class="col-sm-3">
						<label>Sucursal</label><br>
							<select id="sucursal" class="form-control">
								<option selected value="*">-- Todas --</option><?php
								
								foreach ($sucursales as $key => $value) { ?>
									<option value="<?php echo $value['id'] ?>">
										<?php echo $value['nombre'] ?>
									</option> <?php
								} ?>
							</select>
					</div>
					<div class="col-sm-3">
						<label>Graficar por</label><br>
							<select id="grafica" class="form-control">
								<option value="1">Dia</option>
								<option selected value="2">Semana</option>
								<option value="3">Mes</option>
								<option value="4">Año</option>
							</select>
					</div> -->
					<div class="col-sm-3"><br>
						<button 
							id="btn_utilidad" 
							class="btn btn-default" 
							
							style="width: 135px" 
							onclick="comandas.listar_utilidades({
								sucursal : $('#sucursal').val(),
								grafica : $('#grafica').val(),
								btn : 'btn_utilidad',
								empleado : $('#empleado').val(),
								producto : $('#producto').val(),
								cliente : $('#cliente').val(),
								f_ini : $('#f_ini').val(),
								f_fin : $('#f_fin').val(),
								div : 'contenedor'
							});">
							Generar
						</button>
					</div>
				</div>
			<!--	<div class="row"><?php
					date_default_timezone_set('America/Mexico_City');
			
					$f_fin = $f_ini = date('Y-m-d'); ?>
					
					<div class="col-md-5" style="padding-top: 20px">
						<div class="input-group input-group-lg">
					    	<div class="input-group-addon">
					    		<i class="fa fa-calendar"></i> inicio: 
					    	</div>
							<input type="text" id="f_ini" class="form-control" value="<?php echo $f_ini; ?>" style="width: 300px">
					    </div>
					</div>
					<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Empleado:<br />
							<select id="empleado">
								<option selected value="*">-- Todos --</option><?php
								
								foreach ($empleados as $key => $value) { ?>
									<option value="<?php echo $value['id'] ?>">
										<?php echo $value['usuario'] ?>
									</option> <?php
								} ?>
								
							</select>
						</div>
					</div>
					<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Graficar por:<br />
							<select id="grafica">
								<option value="1">Dia</option>
								<option selected value="2">Semana</option>
								<option value="3">Mes</option>
								<option value="4">Año</option>
							</select>
						</div>
					</div>
					<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Cliente:<br />
							<select id="cliente">
								<option value="0">-Selecciona-</option>
								<?php 

									foreach ($clientes['rows'] as $key => $value) {
										echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
									} 


								?>
							</select>
						</div>
					</div>
				</div> -->
				<!--<div class="row">
					<div class="col-md-5" style="padding-top: 15px">
						<div class="input-group input-group-lg">
						   	<div class="input-group-addon">
						   		<i class="fa fa-calendar"></i> fin: &nbsp;&nbsp;&nbsp;&nbsp;
						   	</div>
							<input type="text" id="f_fin" class="form-control" value="<?php echo $f_fin; ?>" style="width: 300px">
					    </div>
					</div>
					<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Productos:<br />
							<select id="producto">
								<option selected value="*">-- Todos --</option><?php
							
							// $productos es una variable que viene desde el controlador
								foreach ($productos as $key => $value) { ?>
									<option value="<?php echo $value['idProducto'] ?>">
										<?php echo $value['nombre'] ?>
									</option> <?php
								} ?>
								
							</select>
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
					<div class="col-md-1" style="padding-top: 15px">
						<button 
							id="btn_utilidad" 
							class="btn btn-default btn-lg" 
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
							style="width: 135px" 
							onclick="comandas.listar_utilidades({
								sucursal : $('#sucursal').val(),
								grafica : $('#grafica').val(),
								btn : 'btn_utilidad',
								empleado : $('#empleado').val(),
								producto : $('#producto').val(),
								cliente : $('#cliente').val(),
								f_ini : $('#f_ini').val(),
								f_fin : $('#f_fin').val(),
								div : 'contenedor'
							});">
							<i class="fa fa-search"></i> Buscar
						</button>
					</div>
				</div> -->
			</div>
			<div class="panel-body">
				<div class="row">
					<div id="contenedor" class="col-md-12">
						<!-- En esta div se carga el contenido desde el modelo -->
					</div>
				</div>
			</div>
		</div>	    	
	    </div>
	</div>
	<!--	<div class="panel panel-default" style="width: 100%">
			<div class="panel-heading">
				<div class="row"><?php
					date_default_timezone_set('America/Mexico_City');
			
					$f_fin = $f_ini = date('Y-m-d'); ?>
					
					<div class="col-md-5" style="padding-top: 20px">
						<div class="input-group input-group-lg">
					    	<div class="input-group-addon">
					    		<i class="fa fa-calendar"></i> inicio: 
					    	</div>
							<input type="text" id="f_ini" class="form-control" value="<?php echo $f_ini; ?>" style="width: 300px">
					    </div>
					</div>
					<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Empleado:<br />
							<select id="empleado">
								<option selected value="*">-- Todos --</option><?php
								
								foreach ($empleados as $key => $value) { ?>
									<option value="<?php echo $value['id'] ?>">
										<?php echo $value['usuario'] ?>
									</option> <?php
								} ?>
								
							</select>
						</div>
					</div>
					<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Graficar por:<br />
							<select id="grafica">
								<option value="1">Dia</option>
								<option selected value="2">Semana</option>
								<option value="3">Mes</option>
								<option value="4">Año</option>
							</select>
						</div>
					</div>
					<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Cliente:<br />
							<select id="cliente">
								<option value="0">-Selecciona-</option>
								<?php 

									foreach ($clientes['rows'] as $key => $value) {
										echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
									} 


								?>
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
							<input type="text" id="f_fin" class="form-control" value="<?php echo $f_fin; ?>" style="width: 300px">
					    </div>
					</div>
					<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Productos:<br />
							<select id="producto">
								<option selected value="*">-- Todos --</option><?php
							
							// $productos es una variable que viene desde el controlador
								foreach ($productos as $key => $value) { ?>
									<option value="<?php echo $value['idProducto'] ?>">
										<?php echo $value['nombre'] ?>
									</option> <?php
								} ?>
								
							</select>
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
					<div class="col-md-1" style="padding-top: 15px">
						<button 
							id="btn_utilidad" 
							class="btn btn-default btn-lg" 
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
							style="width: 135px" 
							onclick="comandas.listar_utilidades({
								sucursal : $('#sucursal').val(),
								grafica : $('#grafica').val(),
								btn : 'btn_utilidad',
								empleado : $('#empleado').val(),
								producto : $('#producto').val(),
								cliente : $('#cliente').val(),
								f_ini : $('#f_ini').val(),
								f_fin : $('#f_fin').val(),
								div : 'contenedor'
							});">
							<i class="fa fa-search"></i> Buscar
						</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div id="contenedor" class="col-md-12" style="overflow: scroll;height: 65%">
						<!-- En esta div se carga el contenido desde el modelo -->
				<!--	</div>
				</div>
			</div>
		</div> -->
	</body>
</html>

<!-- Cambiamos los select por select con buscador -->
<script type="text/javascript">
// Creamos un arreglo con los id de los select
	$objeto = [];
	$objeto[0] = 'empleado';
	$objeto[1] = 'producto';
	$objeto[2] = 'grafica';
	$objeto[3] = 'sucursal';
	$objeto[4] = 'cliente';
// Mandamos llamar la funcion que crea el buscador
	select_buscador($objeto);

// Ejecuta la funcion que consulta las comandas
	comandas.listar_utilidades({
		sucursal : $('#sucursal').val(),
		grafica : $('#grafica').val(),
		btn : 'btn_utilidad',
		empleado : $('#empleado').val(),
		producto : $('#producto').val(),
		cliente : $('#cliente').val(),
		f_ini : $('#f_ini').val(),
		f_fin : $('#f_fin').val(),
		div : 'contenedor'
	});
	
	comandas.convertir_calendario({id: 'f_ini'});
	comandas.convertir_calendario({id: 'f_fin'});
</script>