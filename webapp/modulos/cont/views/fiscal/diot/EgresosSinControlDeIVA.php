<?php
	//include("../../../netwarelog/catalog/conexionbd.php");
?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/rep_fiscal.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<style type="text/css">
		.cuerpo{width: 420px; height: 200px;  padding: 7px; font-family: arial;}
		#checkboxAjuste{width: 15px; height: 20px; position: relative; top: -16px; left: 130px;}
		#fimpr{display: none;}
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
	<script type="text/javascript">
	$(document).ready(function() {
		$('#nmloader_div',window.parent.document).hide();
	});
		function valida(f)
		{
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

			if(f.impresion.checked && f.fecha_impresion.value == '')
			{
				$('#nmloader_div',window.parent.document).hide();
				alert("Falta la fecha de impresion.");
				f.fecha_impresion.focus();
				return false;
			}

		}


		function fecImp()
		{
			if($('#impresion:checked').val())
			{
				$('#fimpr').show('slow')
			}
			else
			{
				$('#fimpr').hide('slow')
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
				<h3 class="nmwatitles text-center">Egresos sin control de IVA</h3>
				<form name='reporte' id='info' method='post' action='index.php?c=EgresosSinIva&f=VerReporte' onsubmit='return valida(this)'>
					<div class="row">
						<div class="col-md-12">
							<label>Movimientos Del:</label>
							<input class="form-control" type="date" id="fecha_ini" name='fecha_ini' placeholder="aaaa-mm-dd">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Al:</label>
							<input class="form-control" type="date" id="fecha_fin" name='fecha_fin' placeholder="aaaa-mm-dd">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<label>Usar fecha de impresi&oacute;n:</label>
							<input class="nminputcheck" type='checkbox' id='impresion' name='impresion' value='1' onclick='fecImp();'>
						</div>
					</div>
					<section id="fimpr">
						<div class="row">
							<div class="col-md-12">
								<label>Fecha del Reporte:</label>
								<input type="date" class="form-control" id="fecha_impresion" name='fecha_impresion' placeholder="aaaa-mm-dd">
							</div>
						</div>
					</section>
					<div class="row">
						<div class="col-md-6 col-md-offset-6">
							<input class="btn btn-primary btnMenu" type="submit" onclick="$('#nmloader_div',window.parent.document).show();" value="Ejecutar Reporte">
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