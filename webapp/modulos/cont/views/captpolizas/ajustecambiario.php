<!DOCTYPE html>
<head>
	<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<script src="js/jquery-1.10.2.min.js"></script>
	<script src="js/ajustecambiario.js"></script>
	<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
	<style type="text/css">
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
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-sm-1">
			</div>
			<div class="col-md-4 col-sm-10">
				<h3 class="nmwatitles text-center">Poliza de Ajuste por diferencia cambiaria</h3>
				<form action="" method="post">
					<h4>&nbsp;</h4>
					<div class="row">
						<div class="col-md-12">
							<label>¿Que desea que el proceso realize?</label>
							<select id="proceso" name="proceso" class="form-control">
								<option value="1">Generar poliza por diferencia cambiaria.</option>
								<option value="2">Mostrar detalle del calculo.</option>
							</select>
						</div>
					</div>
					<h4>Periodo de Calculo</h4>
					<div class="row">
						<div class="col-md-6">
							<label>Ejercicio:</label>
							<select id='ejercicio' name="ejercicio" class="form-control">
			                	<?php  while($d = $datos->fetch_array()){ ?>
				 					<option value="<?php echo $d['Id']."/".$d['NombreEjercicio']; ?>" selected><?php echo $d['NombreEjercicio']; ?></option>
					 			<?php } ?>
			                </select>
						</div>
						<div class="col-md-6">
							<label>Periodo:</label>
							<select style="margin-right: 12%;" class="form-control" id="periodo" name="periodo">
			                	<option selected value='1'>Enero</option>
			                	<option value="2">Febrero</option>
			                	<option value="3">Marzo</option>
			                	<option value="4">Abril</option>
			                	<option value="5">Mayo</option>
			                	<option value="6">Junio</option>
			                	<option value="7">Julio</option>
			                	<option value="8">Agosto</option>
			                	<option value="9">Septiembre</option>
			                	<option value="10">Octubre</option>
			                	<option value="11">Noviembre</option>
			                	<option value="12">Diciembre</option>
			                </select>
						</div>
					</div>
					<h4>Definir cuentas para</h4>
					<div class="row">
						<div class="col-md-6">
							<label>Utilidad:</label>
							<select id="utilidad" name="utilidad" class="form-control">
								<?php  while($d = $utilidad->fetch_array()){ ?>
				 				<option value="<?php echo $d['account_id']."//".$d['description']; ?>" ><?php echo $d['description']."(".$d['manual_code'].")"; ?></option>
					 			<?php } ?>
							</select>
						</div>
						<div class="col-md-6">
							<label>Perdida:</label>
							<select id="perdida" name="perdida" class="form-control">
								<?php  while($d = $perdida->fetch_array()){ ?>
			 					<option value="<?php echo $d['account_id']."//".$d['description']; ?>" ><?php echo $d['description']."(".$d['manual_code'].")"; ?></option>
				 				<?php } ?>
							</select>
						</div>
					</div>
					<h4>&nbsp;</h4>
					<div class="row">
						<div class="col-md-6">
							<label>Cuentas en:</label>
							<select id="moneda" name="moneda" class="form-control">
								<?php  while($m = $tipomoneda->fetch_array()){ 
											if($m['coin_id']!=1){
									?>
										<option value="<?php echo $m['coin_id']; ?>" ><?php echo $m['description']; ?></option>
						 		<?php 		}
									  } ?>
							</select>
						</div>
						<div class="col-md-6">
							<label>Tipo de cambio:</label>
							<input type="text" size="10" id="tc" name="tc" class="form-control"/>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label>Segmento de negocio:</label>
							<select name='segmento' id='segmento' style='text-overflow: ellipsis;'  class="form-control">
								<?php
									while($LS = $ListaSegmentos->fetch_assoc())
									{
										echo "<option value='".$LS['idSuc']."'>".$LS['nombre']."</option>";
									}
								?>
							</select>
						</div>
						<div class="col-md-6">
							<label>Sucursal:</label>
							<select name='sucursal' id='sucursal' style='text-overflow: ellipsis;'  class="form-control">
								<?php
								while($LS = $ListaSucursales->fetch_assoc())
								{
									echo "<option value='".$LS['idSuc']."'>".$LS['nombre']."</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<fieldset>
								<legend style="margin-bottom: unset; border: unset;"><b>En cuentas de Utilidad/Perdida hacer:</b></legend>
								<input style="margin-top: 1em; margin-right: 0.5em;" type="radio" name="radio" value="2" checked=""/>Ajuste por cadena cuenta
							</fieldset>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label style="display: none; color:green;" id="load">Cargando...</label>
						</div>
						<div class="col-md-6">
							<input class="btn btn-primary btnMenu" type="button" value="Procesar" id="proceso" onclick="procesa();"/>
						</div>
					</div>
				</form>
				<div class="row">
					<div class="col-md-12">
						<h4>Información</h4>
						Definición:<br>
						Procesa las cuentas con saldos en el periodo en moneda<br>
						diferente a pesos, para calcular la diferencia cambiaria y <br>
						crear los movimientos de ajuste. Crea ademas movimientos<br>
						a las cuentas de Utilidad o Perdida, segun corresponda.
						<br><br>
						Resultado:<br>
						La poliza con los movimientos de ajuste <br>
						y la bitacora del proceso.
					</div>
				</div>
			</div>
			<div class="col-md-4 col-sm-1">
			</div>
		</div>
	</div>

</body>
</html>