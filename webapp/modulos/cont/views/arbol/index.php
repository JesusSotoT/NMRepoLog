	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Pruebas de click derecho</title>
	<link rel="stylesheet" href="js/select2/select2.css">
	<link rel="stylesheet" href="css/accountsTree.css">
	<script src='js/jquery.js' type='text/javascript'></script>
	<script src='js/jquery.tinysort.min.js' type='text/javascript'></script>
	<script src='js/select2/select2.js' type='text/javascript'></script>
	<script src='js/jquery.maskedinput.js' type='text/javascript'></script>

	<script type="text/javascript" src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	
	<script>
	<?php 
	$tipoinstancia = 1;
	require 'js/arbol.js.php';
	?>
	function abrir()
	{
		if($("#subircuentas").attr('abierto') == '0')
		{
			$('#subircuentas').show('slow')
			$("#subircuentas").attr('abierto','1')
			$("#abr").text('Cerrar')
		}
		else
		{
			$('#subircuentas').hide('slow')
			$("#subircuentas").attr('abierto','0')
			$("#abr").text('Abrir')
		}
	}

	function validar()
	{
		var extension = $("#layout_cuentas").val()
		extension = extension.split('.')
		if(!$("#layout_cuentas").val() || extension[1] != 'xls')
		{
			alert('Es necesario agregar el layout (descargar el archivo xls) para generar este proceso')
			return false
		}
	}
	</script>
	<style>
	#modifica
	{
		padding-bottom: 1em;
	}
	#captura td
	{
		height:45px;
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
	h4, h3{
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
	.nmwatitles{
		margin: 1em 0 !important;
		padding-right: 0 !important;
	}
	#titulo_captura{
		background: none;
		padding: 0;
	}
	</style>
</head>
<body>
	<div id="spinner"></div>
	<div class="layer"></div>
	<h3 class="nmwatitles text-center">Mi arbol Contable</h3>
	<div class="container">
		<section>
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Buscar:</label>
						<input type="text" class='form-control nmcatalogbusquedainputtext' id='search'>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
					<label>&nbsp;</label>
					<button type='button' class="btn btn-primary btnMenu" id="exportar" onclick="exportar()">Exportar cuentas</button>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12">
					<label>&nbsp;</label>
					<a role='button' href="index.php?c=reports&f=catalogoCuentas" class="btn btn-primary btnMenu">Reporte de catalago de cuentas</a>
				</div>
			</div>
		</section>
		<section>
			<?php
			if($tipoinstancia)
			{
			?>
				<label style='margin-top:10px;'>Subir cuentas a través de layout.</label>   <button onclick='abrir()' id='abr'>Abrir</button>
				<div style="margin-top:15px;" id='subircuentas' abierto='0'>
					<a href='Formato_cuentas3.xls'>Descargar Layout</a><br /><br />
					<form action='index.php?c=Config&f=saveNewAccounts' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar()'>
						<input type='file' name='layout_cuentas' id='layout_cuentas' style='margin-bottom:10px;'>
						<input type='submit' name='cargar' id='cargar' value='Cargar'>
					</form>
				</div>
			<?php 
			} 
			?>
		</section>
		<section>
			<div class="row" id="content">
				<div class="col-md-6" id='cont' style="overflow: auto; height: 40vw !important;">
					<ul></ul>
				</div>
				<div class="col-md-6" id='modifica'>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<a id='cerrar' href='javascript:cerrar(0);' class="btn btn-danger btnMenu" style="display:none;">Cerrar</a>
									<a id='abrir' href='javascript:cerrar(1);' class="btn btn-success btnMenu">Agregar cuenta</a>
								</div>
							</div>
							<div class="row" id='captura' style="display: none;">
								<div class="col-md-12">
									<h4 id='titulo_captura'></h4>
									<input type='hidden' id='idcuenta' value='0'>
									<!-- Numero de cuenta y Nombre de cuenta -->
									<div class="row">
										<!-- Numero de cuenta -->
										<div class="col-sm-12 col-md-6">
											<label>Numero de la cuenta:</label>
											<input type="text" class="validate form-control" placeholder="<?php echo $inputMask; ?>" name="accountNumber" id="accountNumber">
										</div> <!-- // numero de cuenta -->
										<!-- Nombre de cuenta -->
										<div class="col-sm-12 col-md-6">
											<label>Nombre de la cuenta:</label>
											<input type='text' id='nombre_cuenta' class="validate form-control">
										</div> <!-- // Nombre de cuenta -->
									</div> <!-- // Numero de cuenta y Nombre de cuenta -->
									<!-- Nombre en segundo idioma y Subcuenta -->
									<div class="row">
										<!-- Nombre en segundo idioma -->
										<div class="col-sm-12 col-md-6">
											<label>Nombre en segundo idioma:</label>
											<input type='text' id='nombre_cuenta_idioma' class="validate form-control">
										</div> <!-- // Nombre de cuenta -->
										<!-- Subcuenta de -->
										<div class="col-sm-12 col-md-6">
											<label id='sbcta'>Subcuenta de:</label>
											<select class="" id='subcuentade'></select>
											<label id='sbcta_label'></label>
											<input type='hidden' id='sbcta_hidden'>
										</div> <!-- // Subcuenta de -->
									</div> <!-- // Nombre en segundo idioma y Subcuenta -->
									<!-- Naturaleza y moneda -->
									<div class="row">
										<!-- Naturaleza -->
										<div class="col-sm-12 col-md-6">
											<label>Naturaleza:</label>
											<select class="" id='nature'><?php echo $nature; ?>
											</select>
										</div> <!-- // Naturaleza -->
										<!-- Moneda -->
										<div class="col-sm-12 col-md-6">
											<label>Moneda:</label>
											<select class="" id='coins'><?php echo $coins; ?>
											</select>
										</div> <!-- // Moneda -->	
									</div> <!-- // Naturaleza y Moneda -->
									<!-- Clasificación, Digito agrupador y Estatus -->
									<!-- Clasificación -->
									<div class="row">
										<div class="col-sm-12 col-md-4">
											<label>Clasificación:</label>
											<select class="" id='type'><?php echo $type; ?>
											</select>
										</div> <!-- // Clasificación -->
										<!-- Digito -->
										<div class="col-sm-12 col-md-8">
											<label>Digito agrupador:</label>
											<select class="" id='oficial'>
												<option value='0'>Ninguna</option>
												<?php echo $oficial; ?>
											</select>
										</div> <!-- // Digito -->
										<!-- Estatus -->
										<div class="col-sm-12 col-md-4">
											<label>Estatus:</label>
											<select class="" id='status'>
												<?php echo $status; ?>
											</select>
										</div> <!-- // Estatus -->
									</div> <!-- // Clasificación, Digito agrupador y Estatus -->
									<div class="row">
										<div class="col-md-12">
											<label id='saving'>Guardando...</label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<label id='mensajes'></label>
										</div>
									</div>
									<div class="row">
										<div class="col-md-3">
											<input type='hidden' id='tipoinstancia' value='<?php echo $tipoinstancia_2; ?>'>
										</div>
										<div class="col-md-3">
											<button class='btn btn-danger btnMenu' id='eliminar' onclick='eliminar()'>Eliminar</button>
										</div>
										<div class="col-md-3">
											<button class='btn btn-primary btnMenu' id='guardar' onclick='guardar()'>Guardar</button>
										</div>
										<div class="col-md-3">
											<button class='btn btn-danger btnMenu' id='cancelar' onclick='cancelar()'>Cancelar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
<div id='blanca' style='background-color:white;position:absolute;top:130px;width:30%;height:100%;font-size:20px;color:green;z-index:999;'>Cargando Información...</div>
<a href='javascript:abrir_co()' id='corregir_btn' style='color:white;'>.</a>
<!--Modal-->
<div class="modal fade bs-corregir-modal-sm" tabindex="-1" role="dialog" aria-labelledby="corregir">
  <div class="modal-dialog modal-sm">
	<div class="modal-content">
	  <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
				<b>Corregir Arbol</b>
			</div>
		<div class="modal-body well">
			<div class='row'>
				<div class='col-xs-12 col-md-4'>Activo</div>
				<div class='col-xs-12 col-md-8'><input type='text' id='activo_co' class='form-control'></div>
			</div>
			<div class='row'>
				<div class='col-xs-12 col-md-4'>Pasivo</div>
				<div class='col-xs-12 col-md-8'><input type='text' id='pasivo_co' class='form-control'></div>
			</div>
			<div class='row'>
				<div class='col-xs-12 col-md-4'>Capital</div>
				<div class='col-xs-12 col-md-8'><input type='text' id='capital_co' class='form-control'></div>
			</div>
			<div class='row'>
				<div class='col-xs-12 col-md-4'>Ingreso</div>
				<div class='col-xs-12 col-md-8'><input type='text' id='ingreso_co' class='form-control'></div>
			</div>
			<div class='row'>
				<div class='col-xs-12 col-md-4'>Egreso</div>
				<div class='col-xs-12 col-md-8'><input type='text' id='egreso_co' class='form-control'></div>
			</div>
			<div class='row'>
				<div class='col-xs-12 col-md-4'>Orden</div>
				<div class='col-xs-12 col-md-8'><input type='text' id='orden_co' class='form-control'></div>
			</div>
			<div class='row'><i>*Escribe el numero de cuenta incluyendo separadores por cada tipo de cuenta de titulo.</i></div>
			
		</div>
			<div class="modal-footer">
				<button id='guardar' class='btn btn-default btn-sm' onclick='corregir()'>Generar</button>
				<button class='btn btn-default btn-sm' onclick="cerrar_co()">Cerrar</button>
			</div>      
	</div>
  </div>
</div>
