<?php
	//include("../../../netwarelog/catalog/conexionbd.php");
?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/rep_fiscal.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/mask.js"></script>
	<style type="text/css">
		.cuerpo{width: 420px; height: 200px;  padding: 7px; font-family: arial;}
		#checkboxAjuste{width: 15px; height: 20px; position: relative; top: -16px; left: 130px;}
		#fimpr{display: none;}
		.row
	{
		margin-top: 1em !important;
	}
	.btnMenu{
		border-radius: 0; 
		width: 100%;
		margin-bottom: 1em;
	}
		#info label {
    margin-right: unset;
    text-align: unset;
    width: unset;
}
	</style>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#nmloader_div',window.parent.document).hide();
		$('.rango').mask('AAAAA', {'translation': {
                                        A: {pattern: /[0-9]/}  
                                      }
                                });
	});
		function valida(f)
		{
		/*	if(f.xperiodo.checked)
			{
				if(f.periodoIni.value == '')
				{
					$('#nmloader_div',window.parent.document).hide();
					alert("Elije Periodo inicial.");
					f.fecha_impresion.focus();
					return false;
				}
				if(f.periodoFin.value == '')
				{
					$('#nmloader_div',window.parent.document).hide();
					alert("Elije Periodo final.");
					f.fecha_impresion.focus();
					return false;
				}
			}
			else
			{ */
				if(f.fecha_ini.value == '')
				{
					$('#nmloader_div',window.parent.document).hide();
					alert("Falta la fecha de inicio.");
					f.fecha_ini.focus();
					return false;
				}

				if(f.fecha_fin.value == '')
				{
					$('#nmloader_div',window.parent.document).hide();
					alert("Falta la fecha fin.");
					f.fecha_fin.focus();
					return false;
				}
		/*	} */
		}


		function fecImp()
		{
			if($('#xperiodo:checked').val())
			{
				$('#fimpr').show('slow')
				$('#pol').hide('hide')
			}
			else
			{
				$('#fimpr').hide('slow')
				$('#pol').show('hide')
			}
		}
	</script>
</head>
<?php 
if($_GET['tipo']==1){ 
					$tit="Impresión de Polizas"; 
				}else{
					$tit="Libro de Diario";
					}
?>
<body>

	<div class="container">
		<h3 class='repTitulo'><?php echo "$tit"; ?></h3>
		<div class="row">
			<div class="col-md-4">
			</div>
			<div class="col-md-4" style="border: 1px solid">
				<form name='reporte' id='info' method='post' action='index.php?c=polizasImpresion&f=VerReporte&tipo=<?php echo $_GET['tipo']; ?>' onsubmit='return valida(this)'>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Polizas Del:</label>
								<input class="form-control" type="date" id="fecha_ini" name='fecha_ini' placeholder="aaaa-mm-dd">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Al:</label>
								<input class="form-control" type="date" id="fecha_fin" name='fecha_fin' placeholder="aaaa-mm-dd">
							</div>
						</div>
					</div>
					<div class="row" id="fimpr">
						<div class="col-md-6">
							<div class="form-group">
								<label>Periodo Inicial:</label>
								<select name='periodoIni' id='periodoIni' class="form-control">
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
						<div class="col-md-6">
							<div class="form-group">
								<label>Periodo Final:</label>
								<select name='periodoFin' id='periodoFin' class="form-control">
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
					</div>
					<?php if($_GET['tipo']==1){ ?>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Tipo de Poliza:</label>
								<select name="tipo" id="tipo" class="form-control">
									<option value="0">Todos</option>
									<?php 
										while($tipoP=$tipo->fetch_object())
										{ 
											echo "<option value=$tipoP->id >$tipoP->titulo</option>"; 
										}
									?>
								</select>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="row">
						<div class="col-md-12">
							<div class="checkbox">
								<label style="padding-left: 0 !important;">
							    	Saldos:
									<input type='checkbox' name='saldos' id='saldos' value='1' style="margin-left: 20px !important;">
							  	</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Rango de polizas Del:</label>
								<input type='text' class='rango form-control' id='pol_ini' name='pol_ini' placeholder='Inicial' size='5'>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Al:</label>
								<input type='text' class='rango form-control' id='pol_fin' name='pol_fin' placeholder='Final' size='5'>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
						</div>
						<div class="col-md-6">
							<button type="submit" class="btn btn-primary btnMenu" onclick="$('#nmloader_div',window.parent.document).show();">Ejecutar Reporte</button>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-4">
			</div>
		</div>
	</div>
</body>
</html>