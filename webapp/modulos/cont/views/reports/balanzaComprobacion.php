<?php
	//include("../../../netwarelog/catalog/conexionbd.php");
?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/rep_fiscal.css">
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		.cuerpo{width: 520px; height: auto  padding: 7px; font-family: arial;}
		.tamanoSel{width: 200px;	text-overflow: ellipsis;}
		#conv{display: none;}
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
	  	.tablaResponsiva{
	        max-width: 100vw !important; 
	        display: inline-block;
	    }
	    #info label {
		    margin-right: unset !important;
		    text-align: unset !important;
		    width: unset !important;
		}
	</style>
	<script src="../cont/js/jquery-1.10.2.min.js"></script>
	<script src="../cont/js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../cont/js/select2/select2.css" />
	<script type="text/javascript">
	$(function()
		 {
		 	 $("#moneda").select2({
						 width : "250px",
						 placeholder:"Selecciona una moneda"
						});
		 });
	
	$(document).ready(function()
	{
		$('#nmloader_div',window.parent.document).hide();
	});
		
		// Formulario Tipo de Cambio Moneda
		function valida(f)
		{
			if($('#tipoC:checked').val() &&  f.valMon.value =='')
			{
				alert('Debe colocar una cantidad en el Tipo de Cambio');
				return false;
			}
			else 
			{
			if(!$('#tipoC:checked').val() &&  f.valMon.value >0)
			{
				alert('Elimine la cantidad en Tipo de Cambio o Active Convertir Moneda');
				return false;
			}
			}
		}
	
	function conMon()
		{
			if($('#tipoC:checked').val())
			{
				$('#conv').show('slow')
			}
			else
			{
				$('#conv').hide('slow')
			}
		}
	
	</script>
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-sm-1">
			</div>
			<div class="col-md-4 col-sm-10">
				<h3 class="nmwatitles text-center">Balanza de Comprobación</h3>
				<form name='reporte' id='info' method='post' action='index.php?c=reports&f=balanzaComprobacionReporte' onsubmit='return valida(this)'>
					<div class="row">
						<div class="col-md-12">
							<label>Ejercicio:</label>
							<select name='ejercicio' id='ejercicio' class="form-control">
								<?php 
								while($p = $ejercicios->fetch_object())
								{
									echo "<option value='".$p->Id."'>".$p->NombreEjercicio."</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Movimientos De:</label>
							<select name='periodo_inicio' id='periodo_inicio' class="form-control">
								<option value='1'>Enero</option>
								<option value='2'>Febrero</option>
								<option value='3'>Marzo</option>
								<option value='4'>Abril</option>
								<option value='5'>Mayo</option>
								<option value='6'>Junio</option>
								<option value='7'>Julio</option>
								<option value='8'>Agosto</option>
								<option value='9'>Septiembre</option>
								<option value='10'>Octubre</option>
								<option value='11'>Noviembre</option>
								<option value='12'>Diciembre</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>A:</label>
							<select name='periodo_fin' id='periodo_fin' class="form-control">
								<option value='1'>Enero</option>
								<option value='2'>Febrero</option>
								<option value='3'>Marzo</option>
								<option value='4'>Abril</option>
								<option value='5'>Mayo</option>
								<option value='6'>Junio</option>
								<option value='7'>Julio</option>
								<option value='8'>Agosto</option>
								<option value='9'>Septiembre</option>
								<option value='10'>Octubre</option>
								<option value='11'>Noviembre</option>
								<option value='12'>Diciembre</option>
								<option value='13'>Cierre del ejercicio</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Idioma:</label>
							<select name='idioma' id='idioma' class="form-control">
								<option value='0'>Español</option>
								<option value='1'>Inglés</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<fieldset align='center'>
								<legend style="margin-bottom: unset; border: unset;"><b>Ver por:</b></legend>
								<input type='radio' name='tipo' value='1' checked> Nivel Afectables<br />
								<input type='radio' name='tipo' value='2'> Nivel de Mayor<br />
								<input type='radio' name='tipo' value='3'> Todos<br />
							</fieldset>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Convertir Moneda:</label>
							<input type='checkbox' value='1' id='tipoC' name='tipoC' onclick='conMon();'>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Mostrar Cuentas de Orden:</label>
							<input type='checkbox' value='Si' id='orden' name='orden'>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Mostrar Cuentas en ceros:</label>
							<input type='checkbox' value='Si' id='ceros' name='ceros'>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Mostrar solo cuentas con Saldo Final:</label>
							<input type='checkbox' value='Si' id='saldo' name='saldo'>
						</div>
					</div>
					<section id="conv">
						<div class="row">
							<div class="col-md-12">
								<label>Moneda:</label>
								<select name="moneda" id='moneda'>
									<?php
									while($coin = $monedas->fetch_object())
									{
										if($coin->coin_id>1){
										echo "<option value='".$coin->description."'>".$coin->description."(".$coin->codigo.")</option>";
											}
									}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<label>Tipo de Cambio:</label>
								<input class="form-control" type="text" name="valMon" placeholder="00.00" />
							</div>
						</div>
					</section>
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<input type="submit" class="btn btn-primary btnMenu" value="Ejecutar Reporte">
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-4 col-sm-1">
			</div>
		</div>
	</div>
	
</body>
</html>