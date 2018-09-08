<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script type="text/javascript" src="js/sessionejer.js"></script>
	<script type="text/javascript" src="js/pagonomina.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
<style>
	.datos td, th
{
	width:158px;
	height:30px;
	text-align: center;
	word-wrap: break-word;
	max-width:150px; 
  	width:150px;
}
</style>
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
</style>
<?php 
if(isset($_COOKIE['ejercicio'])){
	$NombreEjercicio = $_COOKIE['ejercicio'];
	$v=1;
}else{
	$NombreEjercicio = $Ex['NombreEjercicio'];
	$v=0;
}
if(!isset($_SESSION['conceptopoli'])){
	$concepto="";
}else{
	$concepto=$_SESSION['conceptopoli'];
}
$listacuentabanco="";
while($b=$bancosArbol->fetch_array()){
	if($_SESSION['datospoliza'][2]==$b['idbancaria']){ $sele = "selected"; }else{ $sele="";}
	$listacuentabanco .="<option value=".$b['account_id']." $sele>".$b['description']."(".$b['manual_code'].")</option>";
}
$segmento="";
while($LS = $ListaSegmentos->fetch_assoc()){
	$segmento .= "<option value=".$LS['idSuc'].">".$LS['nombre']."</option>";
} 
$sucursal="";
while($LS = $ListaSucursales->fetch_assoc()){
	$sucursal .= "<option value='".$LS['idSuc']."'>".$LS['nombre']."</option>";
}
$listaCuentaSueldo = "";
	while($row = $listaSueldo->fetch_array()){
		$listaCuentaSueldo .= "<option value='".$row['account_id']."'>".$row['description']."(".$row['manual_code'].")</option>";
	} ?>
<script>
dias_periodo(<?php echo $NombreEjercicio; ?>,<?php echo $v; ?>);
$(document).ready(function(){
<?php if(isset($_SESSION['datospoliza'])){ ?>
	$("#formapago").val(<?php echo $_SESSION['datospoliza'][0]; ?>);
	$("#numero").val("<?php echo $_SESSION['datospoliza'][1]; ?>");
	$("#listabancoorigen").val(<?php echo $_SESSION['datospoliza'][2]; ?>);
	$("#numorigen").val("<?php echo $_SESSION['datospoliza'][3]; ?>");
	$("#beneficiario").val(<?php echo $_SESSION['datospoliza'][4]; ?>);
	$("#rfc").val("<?php echo $_SESSION['datospoliza'][5]; ?>");
	$("#listabanco").val(<?php echo $_SESSION['datospoliza'][6]; ?>);
	$("#numtarje").val("<?php echo $_SESSION['datospoliza'][7]; ?>");
	$("#concepto").val("<?php echo $_SESSION['datospoliza'][8]; ?>");
	$("#formapago,#listabancoorigen,#beneficiario,#listabanco").select2({width : "130px"});

<?php }	?>
});
</script>
</head>
<body>

<div class="container">
		<div class="row">
			<div class="col-md-1">
			</div>
			<div class="col-md-10">
				<h3 class="nmwatitles text-center">Pago Recibo Nomina</h3>
				<h4>Datos del ejercicio</h4>
				<section>
					<?php 
					if(isset($_COOKIE['ejercicio'])){ 
						$InicioEjercicio = explode("-","01-0".$_COOKIE['periodo']."-".$_COOKIE['ejercicio']); 
						$FinEjercicio = explode("-","31-0".$_COOKIE['periodo']."-".$_COOKIE['ejercicio']);  
						$peridoactual = $_COOKIE['periodo'];
						$ejercicioactual = $_COOKIE['ejercicio'];
					}else{
						$InicioEjercicio = explode("-",$Ex['InicioEjercicio']); 
						$FinEjercicio = explode("-",$Ex['FinEjercicio']); 
						$peridoactual = $Ex['PeriodoActual'];
						$ejercicioactual = $Ex['EjercicioActual'];
						
					}
					?>
					<div class="row" style="font-size: 15px;">
						<div class="col-md-6 col-sm-6 " style="padding-top: 0.5em;">
							<div class="form-group">
								<label>Ejercicio Vigente: </label>
								<?php
									if($Ex['PeriodosAbiertos'])
										{
											if($ejercicioactual > $firstExercise)
											{?>
												<a href='javascript:cambioEjercicio(<?php echo $peridoactual; ?>,<?php echo $ejercicioactual-1; ?>);' title='Ejercicio Anterior'><img class='flecha' src='images/flecha_izquierda.png'></a>
										<?php }
										} ?>
							
									del (<?php echo $InicioEjercicio['2']."-".$InicioEjercicio['1']."-".$InicioEjercicio['0']; ?>) al (<?php echo $FinEjercicio['2']."-".$FinEjercicio['1']."-".$FinEjercicio['0']; ?>)
									<?php if($Ex['PeriodosAbiertos'])
										{
											if($ejercicioactual < $lastExercise)
											{
												?><a href='javascript:cambioEjercicio(<?php echo $peridoactual; ?>,<?php echo $ejercicioactual+1; ?>)' title='Ejercicio Siguiente'><img class='flecha' src='images/flecha_derecha.png'></a>
										<?php }
									} 
								?>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 " style="padding-top: 0.5em;">
							<div class="form-group">
								<label>Periodo actual:</label> 
								<?php 
								if($Ex['PeriodosAbiertos'])
								{
									if($peridoactual>1)
									{
										?><a href='javascript:cambioPeriodo(<?php echo $peridoactual-1; ?>,<?php echo $ejercicioactual; ?>);' title='Periodo Anterior'><img class='flecha' src='images/flecha_izquierda.png'></a>
								<?php }
								} ?>  
								<label id='PerAct'><?php echo $peridoactual; ?></label><input type='hidden' id='Periodo' value='<?php echo $peridoactual; ?>'> del (<label id='inicio_mes'></label>) al (<label id='fin_mes'></label>)  
							 	<?php if($Ex['PeriodosAbiertos'])
								{
									if($peridoactual<13)
									{
										?><a href='javascript:cambioPeriodo(<?php echo $peridoactual+1; ?>,<?php echo $ejercicioactual; ?>)' title='Periodo Siguiente'><img class='flecha' src='images/flecha_derecha.png'></a>
								<?php }
								} ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label>Acorde a configuracion:</label>
							<img src="images/reload.png" onclick="periodoactual()" title="Ejercicio y periodo de configuracion por defecto" style="vertical-align:middle;">
						</div>
						<div class="col-md-6">
							<input type="hidden" id="diferencia" value="<?php echo number_format($Cargos['Cantidad']-$Abonos['Cantidad'], 2); ?>" />
						</div>
					</div>
				</section>
				<form method="post" ENCTYPE="multipart/form-data" action="index.php?c=Nomina&f=visualizaPagoXML" onsubmit="">
					<h4>Informacion de poliza</h4>
					<section>
						<div class="row">
							<div class="col-md-6">
								<label>Fecha de poliza:</label>
								<?php if(isset($_SESSION['fechapago'])){ ?>
									<input  type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $_SESSION['fechapago']; ?>" onmousemove="javascript:fechadefault()" />
								<?php }else{ ?>
									<input  type="date" class="form-control" id="fecha" name="fecha" onmousemove="javascript:fechadefault()" />
								<?php } ?>
							</div>
							<div class="col-md-6">
								<label>Concepto:</label>
								<input id="concepto" class="form-control" type="text" maxlength="50" name="concepto" value="<?php echo $concepto; ?>">
							</div>
						</div>
					</section>
					<h4>Datos del pago</h4>
					<section>
						<div class="row">
							<div class="col-md-3">
								<label>Forma de pago:</label>
								<select id="formapago" name="formapago">
								 	<?php 	
								 	while($f=$forma_pago->fetch_array()){ ?>
								 		<option value="<?php echo $f['idFormapago']; ?>"><?php echo ($f['nombre']);?></option>
								 	<?php
										} 
								 	?>
								 </select>
							</div>
							<div class="col-md-3">
								<label>Numero:</label>
								<input type="text" size="20" id="numero"  name="numero" value="" class="form-control"/>
							</div>
							<div class="col-md-3">
								<label>Banco origen:</label>
								<img style="vertical-align:middle;width: 15px;height: 15px" title="Agregar Cuenta Bancaria" onclick="mandacuentabancaria()" src="images/mas.png">
								<img style="vertical-align:middle;" title="Actualizar Listado" onclick="actcuentasbancarias()" src="images/reload.png">
								<div id='cargando-mensaje' style='font-size:12px;color:blue;width:100%;display: none'> Cargando...</div>
								<select id="listabancoorigen" name="listabancoorigen" onchange="numeroCuentaOrigen()">
									<option value="0">Elija Banco</option>
									<?php 
										while($b=$listacuentasbancarias->fetch_array()){ ?>
											<option value="<?php echo  $b['idbancaria']; ?>"><?php echo $b['nombre']." (".$b['description']."[".$b['manual_code']."])"; ?> </option>
									<?php   
										} 
									?>
								</select>
							</div>
							<div class="col-md-3">
								<label>No. cuenta bancaria origen/tarjeta:</label>
								<input type="text" id="numorigen" class="form-control" name="numorigen" value="" readonly/>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<label>Beneficiario:</label>
								<img style="vertical-align:middle;" title="Agregar Beneficiario" onclick="iraEmpleados()" src="images/cuentas.png">
								<img style="vertical-align:middle;" title="Actualizar Listado" onclick="actualizaListaEmpleado()" src="images/reload.png">
								<div id='mensajeb' style='font-size:12px;color:blue;width:100%;display: none'> Cargando...</div>
								<select id="beneficiario" name="beneficiario"   onchange="datosEmpleado(this.value);">
									<option value="0">Elija un Beneficiario</option>
									<?php while($e = $listaEmpleados->fetch_assoc()){ ?>
										<option value="<?php echo $e['idEmpleado']; ?>"><?php echo $e['nombreEmpleado']." ".$e['apellidoPaterno']." ".$e['apellidoMaterno']."(".$e['codigo'].")";?> </option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-3">
								<label>RFC:</label>
								<input type="text" id="rfc" name="rfc" value="" class="form-control" readonly/>
							</div>
							<div class="col-md-3">
								<label>Banco destino:</label>
								<select id="listabanco" name="listabanco" onchange="numerocuent()" >
									<option value="0">Elija Banco</option>
									<?php 
										while($b=$listabancos->fetch_array()){ ?>
											<option value="<?php echo  $b['idbanco']; ?>"><?php echo $b['nombre']."(".$b['Clave'].")"; ?> </option>
									<?php	} ?>
								</select>
							</div>
							<div class="col-md-3">
								<label>No. cuenta bancaria dest./tarjeta:</label>
								<input type="text" id="numtarje" name="numtarje" class="form-control" value="" readonly/>
							</div>
						</div>
					</section>
					<h4>Seleccion de recibos</h4>
					<section>
						<div class="row">
							<div class="col-md-9">
								<select id="xml" name="xml[]" style="border: none !important;" class=""  multiple="">
									<?php
									$directorio=opendir('xmls/facturas/temporales'); 
									while ($archivo = readdir($directorio)){
										$solopagos = strpos($archivo, "Nomina");
										if($archivo != '.' && $archivo != '..' && $archivo != '.file' && $archivo !='.DS_Store'){
											if($solopagos==true){
											 echo '<option value="'.$archivo.'">'.($archivo).'</option>';
											}
										}
									}
									closedir($directorio); 
									?>
								</select>
							</div>
							<div class="col-md-3">
								<input type="submit" value="Leer Recibo(s)" id="agregar" class="btn btn-primary btnMenu" >
							</div>
						</div>
					</section>
				</form>
				<section>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
							<div class="table-responsive">
								<table id="lista" class="datos table" style="border:1px solid #BDBDBD;">
									<thead>
									<tr style="background-color:#BDBDBD;color:white;font-weight:bold;">
										<td width="30%">Cuenta</td>
										<td>Cargos</td>
										<td>Abonos</td>
										<td>Referencia</td>
										<td>Segmento</td>
										<td>Sucursal</td>
										<td>Recibo</td>
										<td></td>
									</tr>
									</thead>
									<tbody>
								<?php $cont=0; $sueldo = explode("//",$sueldo); 

								 foreach($_SESSION['pagonomina'] as $data){ ?>
										<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
								 			<td>
								 			<?php if($cuentasconf['statuSueldoxPagar']==1){ ?>
												<input type="button" id="cuentasueldo" style="width: auto;" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="btn btn-primary btnMenu" value="<?php  echo $sueldo[1];?>">
											<?php }else{ ?>
												<script>
													$(document).ready(function(){
														$("#cuentasueldo<?php echo $cont; ?>").select2({width : "130px"});
													});
												</script>
												<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
												<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaingresosact(<?php echo $cont; ?>)" src="images/reload.png">
												<br>
												<div id='cargando-mensaje<?php echo $cont; ?>' style='font-size:12px;color:blue;width:100%;display: none'> Cargando...</div>
												<select id="cuentasueldo<?php echo $cont; ?>" name="cuentasueldo<?php echo $cont; ?>" class="">
													<?php echo $listaCuentaSueldo; ?>
												</select>
												
											<?php	} ?>
											</td>
								 			<td align="left"><?php echo number_format($data['total'],2,'.',',');?></td>
								 			<td align="left">0.00</td>
								 			<td align="center"><?php echo $data['uuid']; ?></td>
								 			<script>
												$(document).ready(function(){
													$("#sucursal<?php echo $cont; ?>,#segmento<?php echo $cont; ?>").select2({width : "130px"});
												});
											</script>
								 			<td align="center"><select name='segmento<?php echo $cont; ?>' id='segmento<?php echo $cont; ?>' style='width: 100%;text-overflow: ellipsis;'  class="">
													<?php echo $segmento; ?>
												</select>
											</td>
											<td align="center">
												<select name='sucursal<?php echo $cont; ?>' id='sucursal<?php echo $cont; ?>' style='width: 100%;text-overflow: ellipsis;'  class="">
													<?php echo $sucursal; ?>
												</select>
											</td>
											<td align="center" width="3px;"><?php echo $data['xml']; ?></td> 
											<td><img src="images/eliminado.png" title="Eliminar Movimiento"  onclick="borra(<?php echo $cont; ?>,'pagonomina');"/></td>
									 	</tr>
									 <tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
										<script>
											$(document).ready(function(){
												$("#banco<?php echo $cont; ?>").select2({width : "130px"});
											});
										</script>
										<td>
											<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
											<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="actualizaListaBanco(<?php echo $cont; ?>)" src="images/reload.png">
											<br>
											<div id='cargando<?php echo $cont; ?>' style='font-size:12px;color:blue;width:100%;display: none'> Cargando...</div>
											<select id="banco<?php echo $cont; ?>" name="banco<?php echo $cont; ?>" class="">
											<?php echo $listacuentabanco; ?>
											</select>
										</td>
										<td align="left">0.00</td>
										<td align="left"><?php echo number_format($data['total'],2,'.',',');?></td>
								 		<td align="center"><?php echo $data['uuid']; ?></td>
								 		<td colspan="4"></td>
								 	</tr>
								<?php $cont++;
									} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
				<h4>Total</h4>
				<section>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
							<div class="table-responsive">
								<table class="captura table">
									<tr>
										<td>Cargos: <b>$<label id="cargo"></label> </b></td><td>Abonos: <b>$<label id="abono"></label></b></td><td>Diferencia: <b  style="color:red;"> $<label id="dife"></label></b></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</section>
				<section>
					<div class="row" id="load2" style="display: none;">
						<div class="col-md-12">
							<h5 class="text-center" style="color: green;">Espera un momento...</h5>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-md-offset-6">
							<input type="button" class="btn btn-primary btnMenu" value="Agregar Poliza"  id="agregaprevio" onclick="antesdeguardar(<?php echo $cont; ?>,<?php echo $cuentasconf['statuSueldoxPagar']; ?>);"/>	
							<input type="button" class="btn btn-primary btnMenu" value="Agregar Poliza"  id="agrega" onclick="guardaPago();" style="display: none"/>
						</div>
						<div class="col-md-3">
							<input type="button" class="btn btn-danger btnMenu" value="Cancelar Poliza"  id="cancela" onclick="cancela();" />
						</div>
					</div>
				</section>
			</div>
			<div class="col-md-1">
			</div>
		</div>
	</div>
<?php if(isset( $_COOKIE['ejercicio'])){ ?>
		<input type="hidden" id="idperio" value="<?php echo $_COOKIE['periodo']; ?>">
		<input type="hidden" id="ejercicio" value="<?php echo $_COOKIE['ejercicio'];?>" />
	<?php }else{ ?>
		<input type="hidden" id="idperio" value="<?php echo $Ex['PeriodoActual']; ?>">
		<input type="hidden" id="ejercicio" value="<?php echo $Ex['NombreEjercicio'];?>" />
	<?php } ?>
</body>
<script>
	$(function () {
         var cargo=0;var abono=0;
        $("#lista tbody tr").each(function (index) //recorre todos los tr
        {
            $(this).children("td").each(function (index2) //en la fila actual recorremos los td
            {
                switch (index2) //indice
                {
                    case 1: cargo += parseFloat($(this).text().replace(/,/gi,''));
                            break;
                    case 2: abono += parseFloat($(this).text().replace(/,/gi,''));
                            break;
                }
            })
        })
          $("#abono").html(abono.toFixed(2));
          $("#cargo").html(cargo.toFixed(2));
          $("#dife").html( Math.abs((cargo.toFixed(2)-abono.toFixed(2)).toFixed(2)));
});

</script>
</html>