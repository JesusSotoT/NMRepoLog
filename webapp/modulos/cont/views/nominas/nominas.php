<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script type="text/javascript" src="js/sessionejer.js"></script>
	<script type="text/javascript" src="js/provisionomina.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<script type="text/javascript" src="../../libraries/bootstrap/dist/js/bootstrap.min.js" ></script>
<style>
	.datos td, th
{
	width:158px;
	height:30px;
	text-align: center;
	border:1px solid #BDBDBD;
	word-wrap: break-word;
	max-width:140px; 
  	width:140px;
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
?>
<script>
dias_periodo(<?php echo $NombreEjercicio; ?>,<?php echo $v; ?>);
	

</script>
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-md-1">
			</div>
			<div class="col-md-10">
				<h3 class="nmwatitles text-center">Provision Recibo Nomina</h3>
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
								<?php   }
									} 
								?>
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
									} 
								?> 
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
				<form method="post" ENCTYPE="multipart/form-data" action="index.php?c=Nomina&f=visualizaXML" id="formulario" >
					<h4>Informacion de poliza</h4>
					<section>
						<div class="row">
							<div class="col-md-6">
								<label>Fecha de poliza:</label>
								<?php if(isset($_SESSION['fechanomina'])){ ?>
									<input  type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $_SESSION['fechanomina']; ?>" onmousemove="javascript:fechadefault()" />
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
					<h4>Seleccion de recibos</h4>
					<section>
						<div class="row">
							<div class="col-md-6">
								<input id="xml" type="file" multiple="" name="xml[]" >
							</div>
							<div class="col-md-2">
								<input type="submit" name="Submit" class="btn btn-primary btnMenu" value="Previsualizar" >
							</div>
						</div>
					</section>
					<h4>Seleccion de recibos en almacen</h4>
					<section>
						<div class="row">
							<div class="col-md-4">
								<input class="btn btn-primary btnMenu" type="button" value="Facturas no Asignadas" onclick="abrefacturas()"/>
							</div>
							<div class="col-md-6">
							</div>
						</div>
					</section>
				</form>
				<section>
					<?php 
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
							} 
						$listaCuentaOtros = "";
						while($row = $afectables->fetch_array()){
							$listaCuentaOtros .= "<option value='".$row['account_id']."'>".$row['description']."(".$row['manual_code'].")</option>";
						} 
						$sueldo = explode("//",$sueldo); 
					?>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
							<div class="table-responsive">
								<table id="lista" class="datos table">
									<thead>
									<tr style="background-color:#BDBDBD;color:white;font-weight:bold;">
										<td>Concepto</td>
										<td style="width: 30%">Cuenta</td>
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
								<?php
								 $cont=0; $totalpercepdedu=0; $otrascuenta=0;
									 foreach($_SESSION['provisionNomina'] as $data){
									 	$detalle=0;
									 	foreach ($data['percepciones'] as $percepcion){
									 		$percepcionar = $percepcionesarray[$percepcion['cuenta']];
									 		$cuentanombre = explode("//",$percepcionar);
									 		 ?>
									 		<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
									 			<td ><?php  echo $percepcion['concepto']; ?></td>
									 			<td >
									 		<?php  if($percepcion['cuenta']!="016"){?>
													<input type="button" id="percepcion<?php  echo $totalpercepdedu; ?>" style="white-space: normal !important;" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta('<?php echo $percepcion['cuenta'];?>',0,0);" class="btn btn-primary btnMenu" value="<?php  echo $cuentanombre[1];?>">
											<?php }else{ ?>	
													<script>
														$(document).ready(function(){
															$("#<?php echo $cont."cuentaOtrosPer".$detalle; ?>").select2({width : "130px"});
														});
													</script>
													<select id="<?php echo $cont."cuentaOtrosPer".$detalle; ?>"  style='width: auto;text-overflow: ellipsis;'  class="">
														<?php echo $listaCuentaOtros; ?>
													</select>
											<?php $detalle++;$otrascuenta++;
													} 
													 $totalpercepdedu++;?>	
												</td>
									 			<td align="left"><?php echo number_format($percepcion['importe'],2,'.',',');?></td>
									 			<td align="left">0.00</td>
									 			<td align="center"><?php echo $data['uuid']; ?></td>
												<td colspan="4"></td>
									 		</tr>
								<?php 
										}
					// OTROS PAGOS SE TOMAN EN CUENTA COMO PERCPECIONES
										foreach ($data['otrospagos'] as $Opagos){
									 		$otrosar = $otrosarray[$Opagos['cuenta']];
									 		$cuentanombre = explode("//",$otrosar);
									 		 ?>
									 		<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
									 			<td ><?php  echo $Opagos['concepto']; ?></td>
									 			<td >
													<input type="button" id="otrosp<?php  echo $totalpercepdedu; ?>" style="white-space: normal !important;" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta(0,0,'<?php echo $Opagos['cuenta'];?>');" class="btn btn-primary btnMenu" value="<?php  echo $cuentanombre[1];?>">
											<?php  $totalpercepdedu++;?>	
												</td>
									 			<td align="left"><?php echo number_format($Opagos['importe'],2,'.',',');?></td>
									 			<td align="left">0.00</td>
									 			<td align="center"><?php echo $data['uuid']; ?></td>
												<td colspan="4"></td>
									 		</tr>
								<?php 
										}
								// FION OTROS PAGOS

										foreach ($data['deducciones'] as $deduccion){
											$deduccionar = $deduccionesarray[$deduccion['cuenta']];
									 		$cuentanombre = explode("//",$deduccionar);
									 		 ?>
									 		<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
									 			<td ><?php  echo $deduccion['concepto']; ?></td>
									 			<td >
									 		<?php  if($deduccion['cuenta']!="004"){?>
													<input type="button" id="deduccion<?php  echo $totalpercepdedu; ?>" style="white-space: normal !important;" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta(0,'<?php echo $deduccion['cuenta']; ?>',0);" class="btn btn-primary btnMenu" value="<?php  echo $cuentanombre[1];?>">
											<?php }else{ ?>	
													<script>
														$(document).ready(function(){
															$("#<?php echo $cont."cuentaOtrosDedu".$detalle; ?>").select2({width : "130px"});
														});
													</script>
													<select  id="<?php echo $cont."cuentaOtrosDedu".$detalle; ?>"  style='width: auto;text-overflow: ellipsis;'  class="">
														<?php echo $listaCuentaOtros; ?>
													</select>
											<?php $detalle++;$otrascuenta++;}  $totalpercepdedu++;?>
												</td>
												<td align="left">0.00</td>
									 			<td align="left"><?php echo number_format($deduccion['importe'],2,'.',',');?></td>
									 			<td align="center"><?php echo $data['uuid']; ?></td>
												<td colspan="4"></td>
									 		</tr>
								<?php	} ?>

											<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
									 			<td>Sueldo por Pagar</td>
									 			<td>
									 			<?php if($cuentasconf['statuSueldoxPagar']==1){ ?>
													<input type="button" id="cuentasueldo" style="white-space: normal !important;" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="btn btn-primary btnMenu" value="<?php  echo $sueldo[1];?>">
												<?php }else{ ?>
													<script>
														$(document).ready(function(){
															$("#cuentasueldo<?php echo $cont; ?>").select2({width : "130px"});
														});
													</script>
													<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
													<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaingresosact(<?php echo $cont; ?>)" src="images/reload.png">
													<br>
													<div id='cargando-mensaje<?php echo $cont; ?>' style='font-size:12px;color:blue;width:auto;display: none'> Cargando...</div>
													<select id="cuentasueldo<?php echo $cont; ?>" name="cuentasueldo<?php echo $cont; ?>" class="">
														<?php echo $listaCuentaSueldo; ?>
													</select>
													
												<?php	} ?>
												</td>
												<td align="left">0.00</td>
									 			<td align="left"><?php echo number_format($data['total'],2,'.',',');?></td>
									 			<td align="center"><?php echo $data['uuid']; ?></td>
									 			<script>
													$(document).ready(function(){
														$("#sucursal<?php echo $cont; ?>,#segmento<?php echo $cont; ?>").select2({width : "130px"});
													});
												</script>
									 			<td align="center"><select name='segmento<?php echo $cont; ?>' id='segmento<?php echo $cont; ?>' style='width: auto;text-overflow: ellipsis;'  class="">
														<?php echo $segmento; ?>
													</select>
												</td>
												<td align="center">
													<select name='sucursal<?php echo $cont; ?>' id='sucursal<?php echo $cont; ?>' style='width: auto;text-overflow: ellipsis;'  class="">
														<?php echo $sucursal; ?>
													</select>
												</td>
												<td align="center" width="3px;"><?php echo $data['xml']; ?></td> 
												<td><img src="images/eliminado.png" title="Eliminar Movimiento"  onclick="borra(<?php echo $cont; ?>,'provisionNomina');"/></td>
									<input type="hidden" value="<?php echo $detalle; ?>" id="contador<?php echo $cont; ?>"/>

									 		</tr>
										
								<?php	$cont++;
										}
									 ?>
										
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
							<input type="button" class="btn btn-primary btnMenu" value="Agregar Poliza"  id="agregaprevio" onclick="antesdeguardar(<?php echo $cont; ?>,<?php echo $cuentasconf['statuSueldoxPagar']; ?>,<?php echo $totalpercepdedu; ?>,<?php echo $otrascuenta;?>);"/>	
							<input type="button" class="btn btn-primary btnMenu" value="Agregar Poliza"  id="agrega" onclick="guardaProvision();" style="display: none;"/>
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
                    case 2: cargo += parseFloat($(this).text().replace(/,/gi,''));
                            break;
                    case 3: abono += parseFloat($(this).text().replace(/,/gi,''));
                            break;
                }
            })
        })
          $("#abono").html(abono.toFixed(2));
          $("#cargo").html(cargo.toFixed(2));
          $("#dife").html( Math.abs((cargo.toFixed(2)-abono.toFixed(2)).toFixed(2)));
});

</script>

<?php if(isset( $_COOKIE['ejercicio'])){ ?>
		<input type="hidden" id="idperio" value="<?php echo $_COOKIE['periodo']; ?>">
		<input type="hidden" id="ejercicio" value="<?php echo $_COOKIE['ejercicio'];?>" />
	<?php }else{ ?>
		<input type="hidden" id="idperio" value="<?php echo $Ex['PeriodoActual']; ?>">
		<input type="hidden" id="ejercicio" value="<?php echo $Ex['NombreEjercicio'];?>" />
	<?php } ?>

<div id="almacen" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Almacen</h4>
            </div>
            <div class="modal-body">
                <div class="row">
		        			<div class="col-md-3">
		        				<input type='text' class="form-control" id='busqueda' name='busqueda' placeholder='Buscar Factura' onchange='listaTemporales()'>
		        				<input type='text' class="form-control" id='busqueda2' name='busqueda2' placeholder='Buscar Factura'>
		        			</div>
		        			<div class="col-md-2">
		        			<input type='hidden' id='todas_facturas' value='<?php echo $todas_facturas; ?>'>
		        			<select id='tipo_busqueda' class='form-control' onchange='listaTemporales()'>
		        					<option value='1'>Por Folio Fiscal (UUID)</option>
		        					<option value='0'>Por Folio</option>
		        					<option value='2'>Por Razon Social</option>
		        				</select>
		        				<!--<a id="loadalmacen" href="#" title="Actualizar Almacen"><i id="update1" class="fa fa-refresh "/></i>Actualizar Almacen</a>--><span style='font-size:10px;' id='titulo_tipo_busqueda'>Escribe el Folio Fiscal (UUID) o Folio para buscar la factura.</span>
		        			</div>
		        		</div>
                
                <div class="row">
			  		<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
			  			<div class="table-responsive" style="overflow:scroll; height:300px;">
			  				<span id='buscando_text' style='font-size:12px;color:blue;'><i>Buscando facturas...</i></span>
			  				<table class='table listado' id="tablalmacen">
							</table>
			  			</div>
			  		</div>
			  	</div>
            </div>
            <div class="modal-footer">
            	<div class="row">
                    <div class="col-md-3 col-md-offset-9">
                        <button type="button" class="btn btn-primary btnMenu" onclick="javascript:abreFacturasPrev();">Previsulizar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</html>