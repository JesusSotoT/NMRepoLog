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
	</style>
	<script src="../cont/js/jquery-1.10.2.min.js"></script>
	<script src="../cont/js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../cont/js/select2/select2.css" />
	<script type="text/javascript">
	
	$(document).ready(function()
	{
		 $("#periodo,#ejercicio,#cuentabancaria").select2({width : "150px",});

	});
		
		
	</script>
</head>
<body>


	<div class="container">
		<div class="row">
			<div class="col-md-4">
			</div>
			<div class="col-md-4">
				<h3 class="nmwatitles text-center">Reporte de Conciliacion</h3>
				<div class="row">
					<form  method='post' action='index.php?c=conciliacionAcontia&f=ReporteConciliacion' >
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12">
									<label>Ejercicio:</label>
									<select name='ejercicio' id='ejercicio' class="">
										<?php 
										while($p = $ejercicio->fetch_object())
										{
											echo "<option value='".$p->Id."'>".$p->NombreEjercicio."</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label>Periodo:</label>
									<select id="periodo" name="periodo" class="">
										<?php while($p = $periodo->fetch_assoc()){?>
											<option value="<?php echo $p['id'];?>"  > <?php echo $p['mes'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<label>Cuenta bancaria:</label>
									<select id="cuentabancaria" name="cuentabancaria" class="">
										<?php 
											while($b=$cuentasB->fetch_array()){
												?>
												<option value="<?php echo  $b['idbancaria']; ?>" > <?php echo $b['nombre']." (".$b['cuenta'].")"; ?> </option>
										<?php   } ?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-md-offset-6">
									<input type="submit" class="btn btn-primary btnMenu" value="Ejecutar Reporte">
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