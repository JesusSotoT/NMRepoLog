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
	</style>
	<script src="../cont/js/jquery-1.10.2.min.js"></script>
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function()
	{
		$('#nmloader_div',window.parent.document).hide();
	});
		
	</script>
</head>
<body>
	
	<div class="container">
		<div class="row">
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
				<h3 class="nmwatitles text-center">Catalogo de Cuentas</h3>
				<div class="row">
					<form name='reporte' id='info' method='post' action='index.php?c=reports&f=catalogoCuentasReporte'>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<label style="text-align: left; !important;">Naturaleza:</label>
									<select name='naturaleza' class="form-control">
										<option value='0'>Todos</option>
										<?php
										while($n = $naturalezas->fetch_object())
										{
											echo "<option value='$n->nature_id'>$n->description</option>";
										}
										?>		
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label  style="text-align: left; !important;">Tipo:</label>
									<select name='tipo' class="form-control">
										<option value='0'>Todos</option>
										<?php
										while($t = $tipos->fetch_object())
										{
											echo "<option value='$t->type_id'>$t->description</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-md-offset-6">
									<input type="submit" onclick="$('#nmloader_div',window.parent.document).show();" class="btn btn-primary btnMenu" value="Ejecutar Reporte">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-4">
			</div>
		</div>
	</div>

	
</body>
</html>