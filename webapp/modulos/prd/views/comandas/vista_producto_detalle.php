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
	<!--  Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!--  dataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
	    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
	    <script src="../../libraries/export_print/jszip.min.js"></script>
	<!--  Morris  -->
		<script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
		<script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>
	<!-- Datepicker -->
	    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
		
	<!--  Sistema  -->
		<script type="text/javascript" src="js/comandas/comandas.js"></script>
		
<!-- ///////////////// ******** ---- 		FIN JS			------ ************ ////////////////// -->

		<script>
///////////////// ******** ---- 		select_buscador		------ ************ //////////////////
//////// Cambia los select por select con buscador.
	// Como parametros puede recibir:
		// Array con los id de los select
		
	function select_buscador ($objeto) {
	// Recorre el arreglo y establece las propiedades del buscador
		$.each( $objeto, function( key, value ) {
			$("#"+value).select2({
				width : "100px"
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
		        	<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Año:
							<select id="ano">	
							<option value="2017">2017</option>						
							</select>
						</div>
					</div>
		           	<div class="col-md-2" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Mes:
							<select id="mes">								
								<option value="01">Enero</option>
								<option value="02">Febrero</option>
								<option value="03">Marzo</option>
								<option value="04">Abril</option>
								<option value="05">Mayo</option>
								<option value="06">Junio</option>
								<option value="07">Julio</option>
								<option value="08">Agosto</option>
								<option value="09">Septiembre</option>
								<option value="10">Octubre</option>
								<option value="11">Noviembre</option>
								<option value="12">Diciembre</option>
							</select>
						</div>
					</div>
					<div class="col-md-3" style="padding-top: 10px">
						<div class="input-group input-group-lg">
				    		Sucursal:
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
					<div class="col-md-3" style="padding-top: 10px">
						<div class="input-group input-group-lg">
					    	Departamento:
							<select id="departamento">	
							<option selected value="*">-- Todos --</option>							
							<?php
								foreach ($departamentos as $key => $value) { ?>
									<option value="<?php echo $value['idDep'] ?>">
										<?php echo $value['nombre'] ?>
									</option> <?php
								} ?>
							</select>
						</div>
					</div>
					<div class="col-md-1" style="padding-top: 5px">
						<button 
							id="btn_utilidad" 
							class="btn btn-default" 
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
							style="width: 135px" 
							onclick="comandas.listar_productos_detalle({
								mes : $('#mes').val(),
								yyyy : $('#ano').val(),
								departamento : $('#departamento').val(),
								sucursal : $('#sucursal').val(),
								btn : 'btn_utilidad',
								div : 'contenedor',
								vista : 'listar_productos_detalle'
							});">
							<i class="fa fa-search"></i> Buscar
						</button>
					</div>
		        </div>

		        <div id="contenedor" class="col-md-12 panel-body back">
						<!-- En esta div se carga el contenido desde el modelo -->
				</div>
		    </div>
		</div>	


	</body>
</html>

<!-- Cambiamos los select por select con buscador -->
<script type="text/javascript">

	var hoy = new Date();
	var mm = hoy.getMonth()+1;
	$("#mes").val(mm).prop('selected', 'selected');


// Creamos un arreglo con los id de los select
	$objeto = [];	
	$objeto[0] = 'ano';
	$objeto[1] = 'mes';
	$objeto[2] = 'sucursal';
	$objeto[3] = 'departamento';

// Mandamos llamar la funcion que crea el buscador
	select_buscador($objeto);

// Ejecuta la funcion que consulta las comandas
	comandas.listar_productos_detalle({ // llama a funcion al cargar pagina
		mes : $('#mes').val(),
		yyyy : $('#ano').val(),
		departamento : $('#departamento').val(),
		sucursal : $('#sucursal').val(),
		btn : 'btn_utilidad',
		empleado : $('#empleado').val(),
		div : 'contenedor',
		vista : 'listar_productos_detalle'
	});

</script>

