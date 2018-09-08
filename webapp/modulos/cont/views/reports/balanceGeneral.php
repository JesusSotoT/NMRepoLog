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
		.hidden {
			visibility: hidden !important;
			display:none !important;
		}
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
	function displayGraphInput() {
		var graph = $('#graph_input');
		var periodo = $( "#periodo option:selected" ).text();
		if (periodo !== 'Todos') {
			graph.removeClass('hidden');
		} else {
			graph.addClass('hidden');
		}
	}

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

	<?php if($_GET['tipo']<3){
		// Formulario Tipo de Cambio Moneda
		echo 
		"function valida(f)
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
			}";
			
			if($_GET['tipo']==0){

			echo "
			if($('#comSeg:checked').val() &&  f.periodo.value == 0)
			{
				alert('Para hacer la comparación debe elegir un Periodo');
				return false;
			}
			else 
			{
			if($('#comSeg:checked').val() &&  f.periodo.value != 0 && f.segmento.value != 0)
			{
				alert('Para hacer la comparación debe elegir Todos los segmentos');
				return false;
			}
			}";

			}
echo "}
	

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
		}";

		}
	?>
	</script>
</head>
<body>
	<?php 
	switch($_GET['tipo'])
	{
		case 0: $titulo = 'Estado de Resultados.';$detalle="<fieldset style='width: 60%;margin-left: 20%;'><legend>Organizar por</legend><input type='radio' value='0' name='detalle' checked> De Mayor<br /><input type='radio' value='1' name='detalle'> Detalle</fieldset>";break;
		case 1: $titulo = 'Balance General.';break;
		case 2: $titulo = 'Estado de Origen y Aplicacion de Recursos.';break;
		case 3: $titulo = 'Estado de Situacion Financiera.';break;
		case 4: $titulo = 'Estado de Resultado Integral.';break;
	}
	?>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-sm-1">
			</div>
			<div class="col-md-4 col-sm-10">
				<h3 class="nmwatitles text-center"><?php echo $titulo;?></h3>
				<form name='reporte' method='post' id='info' action='index.php?c=reports&f=balanceGeneralReporte&tipo=<?php echo $_GET['tipo']; ?>' <?php if($_GET['tipo']<3){ echo "onsubmit='return valida(this)'";}?> >
					<div class="row">
						<div class="col-md-12">
							<label>Ejercicio:</label>
							<select name='ejercicio' id='ejercicio' class="form-control">
								<?php 
								while($p = $ejercicios->fetch_object())
								{
									echo "<option value='".$p->NombreEjercicio."'>".$p->NombreEjercicio."</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Periodo:</label>
							<select name='periodo' id='periodo' class="form-control" onchange="displayGraphInput()">
								<?php
									if(intval($_GET['tipo'])<2)
									{
										echo"<option value='0'>Todos</option>";
									}
								?>
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
							<label>Sucursal:</label>
							<select name='sucursal' id='sucursal' class="form-control">
								<option value='0'>Todos</option>
								<?php
									while($suc = $sucursales->fetch_assoc())
									{
										echo "<option value='".$suc['idSuc']."'>".$suc['nombre']."</option>";
									}
								?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Segmento:</label>
							<select name='segmento' id='segmento' class="form-control">
								<option value='0'>Todos</option>
								<?php
									while($seg = $segmentos->fetch_assoc())
									{
										echo "<option value='".$seg['idSuc']."'>".$seg['nombre']."</option>";
									}
								?>
							</select>
						</div>
					</div>
					<?php if($_GET['tipo']<3){ ?>
							<div class="row">
								<div class="col-md-12">
									<label>Idioma:</label>
									<select name='idioma' id='idioma' class="form-control">
										<option value='0'>Español</option>
										<option value='1'>Inglés</option>
									</select>
								</div>
							</div>
					<?php }
					?>
					<?php if($_GET['tipo']==0){ ?>
							<div class="row">
								<div class="col-md-12">
									<label>Comparar con presupuesto:</label>
									<select name='presup' id='presup' class="form-control">
										<option value='0'>No</option>
										<option value='1'>Desviación</option>
										<option value='2'>Alcanzado</option>
									</select>
								</div>
							</div>
					<?php } 
					?>
					<?php if($_GET['tipo']==0){ 
							$totalSeg=count($seg);
					?>
							<div class="row">
								<div class="col-md-12">
									<label>Comparativo por segmentos:</label>
									<input type='checkbox' value='1' id='comSeg' name='comSeg' ></li>
									<input type='hidden' value='$totalSeg' id='totalSeg' name='totalSeg' >
								</div>
							</div>
					<?php } 
					?>
					<?php if($detalle!=""){ ?>
							<div class="row">
								<div class="col-md-12">
									<label></label>
									<?php echo $detalle; ?>
								</div>
							</div>
					<?php } 
					?>
					<?php if($_GET['tipo']<3){ ?>
							<div class="row">
								<div class="col-md-12">
									<label>¿Convenrtir moneda?:</label>
									<input type='checkbox' value='1' id='tipoC' name='tipoC' onclick='conMon();'>
								</div>
							</div>
							<div class="row hidden" id="graph_input">
								<div class="col-md-12">
									<label>¿Desea graficar el reporte?</label>
									<input type="checkbox" name="graph_input" value="1">
								</div>
							</div>
							<section id="conv">
								<div class="row">
									<div class="col-md-12">
										<label>Moneda:</label>
										<select name="moneda" id="moneda">
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
										<label>Tipo de cambio:</label>
										<input type="text" name="valMon" placeholder="00.00" class="form-control"/>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
									</div>
								</div>
							</section>
					<?php }
					?>
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<input type="submit" <?php if($_GET['tipo']>=3){ ?>onclick="$('#nmloader_div',window.parent.document).show();" <?php } ?> class="btn btn-primary btnMenu" value="Ejecutar Reporte">
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