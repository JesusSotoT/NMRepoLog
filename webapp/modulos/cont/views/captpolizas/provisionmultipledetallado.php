<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	
	<script type="text/javascript" src="js/sessionejer.js"></script>
	<link rel="stylesheet" type="text/css" href="css/clipolizas.css"/>
	<script type="text/javascript" src="js/poliprovisional.js" ></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<script type="text/javascript" src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script> 
	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<?php 
if(isset($_COOKIE['ejercicio'])){
	$NombreEjercicio = $_COOKIE['ejercicio'];
	$v=1;
}else{
	$NombreEjercicio = $Ex['NombreEjercicio'];
	$v=0;
}
$cuenta="";
		while($ingre=$cuentaingresos->fetch_array()){
			$cuenta .= "<option value=".$ingre['account_id'].">".$ingre['description']."(".$ingre['manual_code'].")</option>";
		}
		$cuentaegre="";
		while($ingre=$cuentaegresos->fetch_array()){
			$cuentaegre .= "<option value=".$ingre['account_id'].">".$ingre['description']."(".$ingre['manual_code'].")</option>";
		} 
		$segmento="";
		while($LS = $ListaSegmentos->fetch_assoc()){
			$segmento .= "<option value=".$LS['idSuc'].">".$LS['nombre']."</option>";
		} 
		$sucursal="";
		while($LS = $ListaSucursales->fetch_assoc()){
			$sucursal .= "<option value='".$LS['idSuc']."'>".$LS['nombre']."</option>";
		} 
		$cuentaparaimpuest = "";
		while($ingre = $cuentaivas->fetch_array()){
			$cuentaparaimpuest .= "<option value=".$ingre['account_id'].">".$ingre['description']."(".$ingre['manual_code'].")</option>";
		} 
		$cuentacliente="<option value=0>--Seleccione--</option>";
		while ($li=$cuentalista->fetch_array()){
			$cuentacliente .= "<option value=".$li['account_id'].">".$li['description']."(".$li['manual_code'].")</option>";
		} 
		$cuentaprove="<option value=0>--Seleccione--</option>";
		while ($pr=$cuentaprov->fetch_array()){
			$cuentaprove .= "<option value=".$pr['account_id'].">".$pr['description']."(".$pr['manual_code'].")</option>";
		}
?>

<script>
function antesdeguardar(cont,contdeta){
		var i=0;  var status=0; var status2=0; var tipo="";var arra= ""; var proceso=0; var proceso2=0;
	  	for(i;i<cont;i++){
	  <?php if($statusIVAIEPS==1){ 
	  			if($statusIVA==1){?>
	  				if($("#ivaingre").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA ");  return false;}
		<?php	}	
				if($statusIEPS==1){?>
					if($("#ieps").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IEPS ");  return false;}
<?php			}
			 }
		if($statusIEPS==0){?>
			if($("#ieps").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion Cuenta de Gasto ");  return false;}
	<?php }if($statusIVA==0){?>
				if($("#ivaingre").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion Cuenta de Gasto ");  return false;}

	  	<?php }
	  	if($statusRetencionISH==1){?>
	  			if($("#IVA").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA retenido");  return false;}
				if($("#ISR").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas ISR retenido");  return false;}
				if($("#ish").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas ISH");  return false;}
	<?php   } 
	  if(isset($_SESSION['comprobante'])){
	  	if($_SESSION['comprobante']==1){?>
	  		tipo="cliente";arra="provisioncliente";
	  			if($("#CuentaClientes"+i).is(":visible") ){
		  			if($("#CuentaClientes"+i).val()==0 || !$("#CuentaClientes"+i).val()){
		  				alert("Seleccione cuenta para Cliente");
		  				$("#load2").hide();$("#agregaprevio,#cancela").show();
		  				return false;
		  			}
		  		}
	  <?php	}else{?>
	  		tipo = "proveedor"; arra = "poliprove";
	  			if($("#CuentaProveedores"+i).is(":visible") ){
	  				if($("#CuentaProveedores"+i).val()==0 || !$("#CuentaProveedores"+i).val()){
		  				alert("Seleccione cuenta para Proveedores");
		  				$("#load2").hide();$("#agregaprevio,#cancela").show();
		  				return false;
		  			}
		  		}
	 <?php  }
	  }
	  	?>
	  
		$("#load2").show();
		$("#agregaprevio").hide();
		$("#cancela").hide();
		  	 $.post('index.php?c=CaptPolizas&f=guardanewvaloresprovision',{
	  			cont : i,
		  		ivapendiente : $("#ivaingre"+i).val(),//cuenta
				iepspendiente : $("#ieps"+i).val(),
				iva : $("#IVA"+i).val(),
				isr : $("#ISR"+i).val(),
				ish : $("#ish"+i).val(),
				CuentaClientes : $("#CuentaClientes"+i).val(),
				CuentaProveedores : $("#CuentaProveedores"+i).val(),
				segmento : $("#segmento"+i).val(),
				sucursal : $("#sucursal"+i).val(),
				concepto:$("#conceptopoliza").val(),
				tipo : tipo,
				array:arra
			 },function(resp){
			 	
	  			status+=1;
	  			
	  			if(status==cont ){
	  				//proceso=1;
	  				for(i=0;i<cont;i++){
	  					var contador = $("#contador"+i).val();
					 	for(var c=0;c<contador;c++){
						 	$.post('ajax.php?c=CaptPolizas&f=detalleMultipleProvision',{
						 		cont : i,
						 		tipo : tipo,
						 		cuentadetalle:$("#"+i+"deta"+c).val(),
								array:arra
						 	},function(){
						 		status2+=1;
			  			
			  					if(status2==contdeta ){
			  						$("#agrega").click();
									// $("#load2").hide();
									// $("#agregaprevio").show();
									// $("#cancela").show();
								}
								// if((proceso2==proceso) && proceso2!=0 && proceso!=0){
									// $("#agrega").click();
									// $("#load2").hide();
									// $("#agregaprevio").show();
									// $("#cancela").show();
								// }
						 	});
			 			}
	 				}
				}
				
	 			
	  		 });
  				
	 			
	 			
	 		
	 		
	 		
	 		
	 	}
	 	
  }		 
dias_periodo(<?php echo $NombreEjercicio; ?>,<?php echo $v; ?>)

$(document).ready(function(){
	 $('#periodomanual').val($('#Periodo').val());
});
</script>
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
	.busqueda{
		background-image: url("search.png");
    	background-position: right center;
    	background-repeat: no-repeat;
    	background-size: 20px 20px;
	}
	.modal-title{
		background-color: unset !important;
		padding: unset !important;
	}
	td{
		border: medium none !important;
	}
</style>
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-md-1">
			</div>
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12">
						<h3 class="nmwatitles text-center">
							Polizas de Provision Detallada
							<a  href='index.php?c=CaptPolizas&f=filtroAutomaticas&t=provisiond&detalle=1' onclick="" id='filtros'>
								<img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'>
							</a>
						</h3>
					</div>
				</div>
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
					<div class="row">
						<div class="col-md-6">
							<label>Ejercicio Vigente:</label>
							<?php
								if($Ex['PeriodosAbiertos'])
								{
									if($ejercicioactual > $firstExercise)
									{
										?><a href='javascript:cambioEjercicio(<?php echo $peridoactual; ?>,<?php echo $ejercicioactual-1; ?>);' title='Ejercicio Anterior'><img class='flecha' src='images/flecha_izquierda.png'></a>
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
						<div class="col-md-6">
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
							<?php 
							if($Ex['PeriodosAbiertos'])
							{?>
							<select id="periodomanual" title="Seleccione un periodo" onchange="cambioPeriodo(this.value,<?php echo $ejercicioactual; ?>)">
						        <option value="1">1</option>
						        <option value="2">2</option>
						        <option value="3">3</option>
						        <option value="4">4</option>
						        <option value="5">5</option>
						        <option value="6">6</option>
						        <option value="7">7</option>
						        <option value="8">8</option>
						        <option value="9">9</option>
						        <option value="10">10</option>
						        <option value="11">11</option>
						        <option value="12">12</option>
						        <option value="13">13</option>
						      </select>
						<?php }	?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label>Acorde a configuracion:</label>
							<img src="images/reload.png" onclick="periodoactual()" title="Ejercicio y periodo de configuracion por defecto" style="vertical-align:middle;">
						</div>
						<div class="col-md-6">
							<input type="hidden" id="diferencia" value="<?php echo number_format($Cargos['Cantidad']-$Abonos['Cantidad'], 2); ?>" />
							<input type="hidden" value="1" id="detalle" />
						</div>
					</div>
				</section>
				<form method="post" ENCTYPE="multipart/form-data" action="index.php?c=CaptPolizas&f=guardaProvisionMultiple&detalle=1" id="formulario">
					<h4>Informaci&oacute;n de la p&oacute;liza</h4>
					<section>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Fecha de poliza:</label>
									<?php if(isset($_SESSION['fechaprovi'])){ ?>
										<input  type="text" class="form-control" id="fecha" name="fecha" value="<?php echo $_SESSION['fechaprovi']; ?>" onmousemove="javascript:fechadefault()" />
									<?php }else{ ?>
										<input  type="text" class="form-control" id="fecha" name="fecha" onmousemove="javascript:fechadefault()" />
									<?php } ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Selecione un comprobante:</label>
									<select id="comprobante" name="comprobante" onchange="" class="form-control">
										<option value="0" selected="">Elija una opcion.</option>
										<option value="1">Ingresos</option>
										<option value="2">Egresos</option>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<button type="button" class="btn btn-primary btnMenu" onclick="abrefacturas()">Facturas no Asignadas</button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<input id="xml" type="file" multiple="" name="xml[]" >
							</div>
							<div class="col-md-3 col-md-offset-1">
								<button type="button" class="btn btn-primary btnMenu" name="Submit" onclick="sele()">Previsualizar</button>
							</div>
							<div class="col-md-4">
								<div class="form-group">
								</div>
							</div>
						</div>
					</section>
				</form>
				<div class="row" id='cargando-mensaje' style='font-size:12px; color:blue; display: none;'>
					<div class="col-md-12">
						Cargando...
					</div>
				</div>
				<section>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="datos" align="" cellpadding="2" border="0" class="table">
									<thead>
										<tr>
											
											<td class="nmcatalogbusquedatit" align="center">Concepto</td>
											<td class="nmcatalogbusquedatit" align="center">Cuenta</td>
											<td class="nmcatalogbusquedatit" align="center">Cargo</td>
											<td class="nmcatalogbusquedatit" align="center">Abono</td>
											<td class="nmcatalogbusquedatit" align="center"></td>
											<td class="nmcatalogbusquedatit" align="center">XML</td>
											<td class="nmcatalogbusquedatit" align="center">Segmento</td>
											<td class="nmcatalogbusquedatit" align="center">Sucursal</td>
											
										</tr>
										<!--<tr><td colspan="7"><hr></hr></td></tr>-->
									</thead>
									<?php $cont=0;$contselec=0;$contdeta=0;
									if($_SESSION['comprobante']==1){ ?>
									<tbody>
									<?php	 foreach($_SESSION['provisioncliente'] as $cliente){
									foreach($cliente as $cli){
										$maximo = count($cli['conceptodetalle']);
										$maximo = (intval($maximo)-1); ?>	
												
										<script>
										$(document).ready(function(){
											$("#sucursal<?php echo $cont; ?>,#segmento<?php echo $cont; ?>,#CuentaClientes<?php echo $cont; ?>").select2({width : "130px"});
										});
										</script>
								 	<tr><td colspan="7"><hr id></hr></td></tr>
									 <tr><td></td>
										 <td align="center" width="1%"><b>Ref:</b> <?php echo $cli['referencia']; ?></td>
										
									</tr>
									
									<?php 
									if(is_array($cli['conceptodetalle'])){
									//$contdeta=1;	
									for($c=0;$c<=($maximo);$c++){?>
									<script>
										$(document).ready(function(){
											$("#<?php echo $cont."deta".$c; ?>").select2({width : "130px"});
										});
									</script>
									<tr>
										<td><?php echo $cli['conceptodetalle'][$c]  ?></td>
										 <td  class="nmcatalogbusquedatit" align="center">
										 	<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
											<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaingresosact(<?php echo $cont; ?>)" src="images/reload.png">
											<br>		
											<select id="<?php echo $cont."deta".$c; ?>" name="<?php echo $cont."deta".$c; ?>" class="nminputselect" >
												<?php echo $cuenta; ?>
											</select>
										 </td>
										 <td align="center">0.00</td>
									     <td align="center" ><?php echo number_format($cli['preciodetalle'][$c],2,'.',','); ?></td>
									</tr>
						 			<?php $contdeta ++; }?>
						 			<input type="hidden" value="<?php echo $c; ?>" id="contador<?php echo $cont; ?>"/>
									<?php } if($cli['abono2']>0){ ?>
									 <tr><td></td>
									<?php 
									if($statusIVA==1){
									if($statusIVAIEPS==1){ ?>
												<td  class="nmcatalogbusquedatit" align="center">
												<input type="button" id="ivaingre" name="ivaingre<?php echo $cont; ?>" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" value="<?php  echo $ivapendientecobro[1];?>">
												</td>
									<?php }else{?>
											<script>
												$(document).ready(function(){
													$("#ivaingre<?php echo $cont; ?>").select2({width : "130px"});
												});
											</script>
											<td  class="nmcatalogbusquedatit" align="center">
												<font color="red" face="Comic Sans MS,arial,verdana">IVA Pendiente de cobro</font>
												<select id="ivaingre<?php echo $cont; ?>" name="ivaingre<?php echo $cont; ?>" class="nminputselect">
													<?php echo $cuentaparaimpuest; ?>
												</select>
											</td> 
									<?php }
									} else{ ?> 
									<td>
									<input type="button" id="ivaingre" name="ivaingre" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $CuentaIVAgasto[1]; ?>"/>
									</td>
									<?php } ?>		
										<td align="center">0.00</td>
										<td align="center" id="importe"><?php echo number_format($cli['abono2'],2,'.',',');?></td>
									 </tr>
									<?php } ?> 
									<tr>
										
										<?php if($cli['ieps']>0){ ?>
												<td></td>
										<?php if($statusIEPS==1){
												 if($statusIVAIEPS==1){ ?>
												<td  class="nmcatalogbusquedatit" align="center">
													<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $iepspendientecobro[1]; ?>"/>
												</td>
										<?php }else{?>
												<script>
													$(document).ready(function(){
														$("#ieps<?php echo $cont; ?>").select2({width : "130px"});
													});
												</script>
												<td class="nmcatalogbusquedatit" align="center">
												<font color="red" face="Comic Sans MS,arial,verdana">Cuenta para IEPS</font>
												<select id="ieps<?php echo $cont; ?>" name="ieps<?php echo $cont; ?>" class="nminputselect">
													<?php echo $cuentaparaimpuest; ?>
												</select>
												</td>
										<?php }
											}else{ ?>
												<td>
												<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $CuentaIEPSgasto[1]; ?>"/>
												</td>
										<?php } ?>
											<td align="center">0.00</td>
											<td align="center" id="importeieps"><?php echo number_format($cli['ieps'],2,'.',',');?></td>
										<?php } ?>
										</tr>
										<!-- ISH -->
										<tr>
										
										<?php if($cli['ish']>0 ){ ?>
										
										<td></td>
											<td class="nmcatalogbusquedatit" align="center"><!-- Cuenta para ISH -->
												<?php if($statusRetencionISH==1){ ?>
														<input type="button" id="ish" name="ish" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php   echo $ishh[1]; ?>"/> 					
												<?php }else{?>
														<script>
															$(document).ready(function(){
																$("#ish<?php echo $cont;?>").select2({width : "130px"});
															});
														</script>
														<font color="red" face="Comic Sans MS,arial,verdana">Cuenta para ISH </font>
														<select id="ish<?php echo $cont;?>" name="ish<?php echo $cont;?>" class="nminputselect" style='width: 100px;text-overflow: ellipsis;' >
															<?php echo $cuentaparaimpuest; ?>
														</select>
												<?php } ?>
											</td>
										
											<td align="center">0.00</td>
											<td align="center" id="importeish<?php echo $cont; ?>"><?php echo number_format($cli['ish'],2,'.',','); ?></td>
										<?php } ?>
										</tr>
										<!--FIN  ISH -->
									 <tr>
									 	<td rowspan="1" align="center"></td>
										 <td  class="nmcatalogbusquedatit" align="center" ><?php echo $cli['nombre']; ?></td>
									     <td align="center" id="total<?php echo $cont; ?>"><?php echo number_format($cli['cargo'],2,'.',','); ?></td>
									     <td align="center">0.00</td>
									     <td><?php 
												if(isset($cli['listacliente'])){?>
													<font color="red" face="Comic Sans MS,arial,verdana">Por favor elija una cuenta para el movimiento del cliente.</font>
													<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
													<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="actualizaCuentascli(<?php echo $cont ;?>)" src="images/reload.png">
													
													<select id="CuentaClientes<?php echo $cont; ?>">
														<!-- <option selected="" value="-1"> Elija una cuenta</option> -->
														<?php echo $cuentacliente; ?>
													</select>
											<?php	} ?></td>
										<td align="center" width="3px;"><?php echo ($cli['xml']); ?></td> 
									     <td align="center" id="">
									     	 <select name='segmento<?php echo $cont; ?>' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="nminputselect">
												<?php echo $segmento; ?>
											</select>
									     </td>
									     <td align="center" id="">
									     	<select name='sucursal<?php echo $cont; ?>' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="nminputselect">
											<?php echo $sucursal; ?>
											</select>
									     </td>
									     <td>
									      </td>
									     <td><img src="images/eliminado.png" title="Eliminar Movimiento"  onclick="borra(<?php echo $cont; ?>,'provisioncliente');"/></td>

									 </tr>
									  <!-- retencion -->
									  <tr><td colspan="4" class="nmwatitles">Retencion</td></tr>
									 <?php foreach ( $cli['retenidos'] as $key => $value){ 
									 	if($value>0){?>
									 	
									<tr>
									 	<td rowspan="1" align="center">Cuenta para <?php echo $key; ?> </td>
									 	
									 	<?php 
									 	if($statusRetencionISH==1){
									 		
									 		if($key=="IVA"){ ?>
										 		<td class="nmcatalogbusquedatit" align="center">
													<input type="button" id="IVA" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $ivaretenido[1]; ?>"/>
												</td>	
									 <?php	} 
									 		if($key=="ISR"){?>
										 		<td class="nmcatalogbusquedatit" align="center">
													<input type="button" id="ISR" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php   echo $isrretenido[1]; ?>"/>
												</td>	
									 <?php	}
										}else{?>
											<script>
									 		$(document).ready(function(){
												$("#<?php echo $key.$cont;?>").select2({width : "100px"});
											});
									 	</script>
											<td class="nmcatalogbusquedatit" align="center">
										 		<select id="<?php echo $key.$cont;?>" class="nminputselect" style='width: 100px;text-overflow: ellipsis;'> 
													<?php echo $cuentaparaimpuest; ?>
												</select>
											</td>	
											
									<?php } ?>
									    <td align="center" id="total<?php echo $key.$cont; ?>" name="total<?php echo $key.$cont; ?>"><?php echo number_format($value,2,'.',','); ?></td>
									    <td align="center">0.00</td>
									    
									 </tr>
									 <?php } } ?>
									<!-- fin retencion -->
									 <tr><td colspan="7"><hr></hr></td></tr>
									</tbody>
									<?php  $cont++; } }
									}
									if($_SESSION['comprobante']==2){ ?>	
									<tbody>
									<?php  foreach($_SESSION['poliprove'] as $pro){
							 		foreach($pro as $prove){ 
							 		$maximo = count($prove['conceptodetalle']);
									$maximo = (intval($maximo)-1);
							 		?>
							 		<script>
										$(document).ready(function(){
											$("#sucursal<?php echo $cont; ?>,#segmento<?php echo $cont; ?>,#CuentaClientes<?php echo $cont; ?>").select2({width : "130px"});
										});
										</script>
									<tr><td colspan="7"><hr id></hr></td></tr>
						 			<tr><td></td>
							 			<td align="center" width="1%"><b>Ref:</b> <?php echo $prove['referencia']; ?></td>
							 		</tr>
							 		<?php	
							 		if(is_array($prove['conceptodetalle'])){
									//$contdeta=1;	
									for($c=0;$c<=($maximo);$c++){?>
									<script>
										$(document).ready(function(){
											$("#<?php echo $cont."deta".$c; ?>").select2({width : "130px"});
										});
									</script>
									<tr>
										<td><?php echo $prove['conceptodetalle'][$c]  ?></td>
							 			<td  class="nmcatalogbusquedatit" align="center">
											<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
											<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaegresosact(<?php echo $cont; ?>)" src="images/reload.png">
											<br>		
											<select id="<?php echo $cont."deta".$c; ?>" name="<?php echo $cont."deta".$c; ?>" class="nminputselect" >
												<?php echo $cuentaegre; ?>
											</select>
										</td>
										<td align="center" id="subtotalegre"><?php echo number_format(floatval($prove['preciodetalle'][$c]),2,'.',''); ?></td>
								 		<td align="center">0.00</td>
								 	</tr>
						 			<?php $contdeta ++; }?>
						 			<input type="hidden" value="<?php echo $c; ?>" id="contador<?php echo $cont; ?>"/>
									<?php }if($prove['cargo2']>0){ ?>
									<tr><td></td>
									<?php 
									if($statusIVA==1){
									if($statusIVAIEPS==1){ ?>
									<td   align="center">
									<input type="button" id="ivaingre" name="ivaingre<?php echo $cont; ?>" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%;white-space: normal;" value="<?php   echo $ivapendientepago[1]; ?>"/>
									</td>
									<?php }else{?>
										<script>
											$(document).ready(function(){
												$("#ivaingre<?php echo $cont; ?>").select2({width : "130px"});
											});
										</script>
										<td  class="nmcatalogbusquedatit" align="center">
											<font color="red" face="Comic Sans MS,arial,verdana">IVA Pendiente de Pago</font>
											<select id="ivaingre<?php echo $cont; ?>" name="ivaingre<?php echo $cont; ?>" class="nminputselect">
												<?php echo $cuentaparaimpuest; ?>
											</select>
										</td> 
									<?php }
									}else{ ?>
									<td>
									<input type="button" id="ivaingre" name="ivaingre" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $CuentaIVAgasto[1]; ?>"/>
									</td>
									<?php } ?>
									<td align="center" id="importeegre"><?php if($prove['cargo2']){echo number_format($prove['cargo2'],2,'.','');}else{ echo 0;} ?></td>
									<td align="center">0.00</td>
									<?php } ?> 
									</tr>	
									<?php if($prove['ieps']>0){ ?>
									<tr><td></td>
									<?php if($statusIEPS==1){
							 		if($statusIVAIEPS==1){ ?>
									<td align="center">
									<input type="button" id="ieps" name="ieps<?php $cont;?>" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php   echo $iepspendientepago[1]; ?>"/>
									</td>
									<?php }else{ ?>
									<script>
									$(document).ready(function(){
										$("#ieps<?php echo $cont; ?>").select2({width : "130px"});
									});
									</script>
									<td class="nmcatalogbusquedatit" align="center">
									<font color="red" face="Comic Sans MS,arial,verdana">Cuenta para IEPS</font>
									<select id="ieps<?php echo $cont; ?>" name="ieps<?php echo $cont; ?>" class="nminputselect">
										<?php echo $cuentaparaimpuest; ?>
									</select>
									</td>
									<?php }	
									}else{ ?>
									<td>
									<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $CuentaIEPSgasto[1]; ?>"/>
									</td>
									<?php } ?>
									<td align="center" id="importeiepsegre"><?php echo number_format($prove['ieps'],2,'.','');?></td>
									<td align="center">0.00</td>
									<?php } ?>
									</tr>
									<!-- ISH -->
									<tr>
									<?php if($prove['ish']>0){ ?>
									<td></td>
									<td class="nmcatalogbusquedatit" align="center"><!-- Cuenta para ISH -->
									<?php if($statusRetencionISH==1){ ?>
											<input type="button" id="ish" name="ish" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php   echo $ishh[1]; ?>"/> 					
									<?php }else{?>
											<script>
												$(document).ready(function(){
													$("#ish<?php echo $cont;?>").select2({width : "130px"});
												});
											</script>
											<font color="red" face="Comic Sans MS,arial,verdana">Cuenta para ISH </font>
											<select id="ish<?php echo $cont;?>" name="ish<?php echo $cont;?>" class="nminputselect" style='width: 100px;text-overflow: ellipsis;' >
												<?php echo $cuentaparaimpuest; ?>
											</select>
									<?php } ?>
											</td>
								
											<td align="center" id="importeishegre"><?php echo number_format(floatval($prove['ish']),2,'.','');?></td>
											<td align="center">0.00</td>
									<?php } ?>
										</tr>
										<!--FIN  ISH -->

										<tr><td></td>
											<td  class="nmcatalogbusquedatit" align="center"><?php echo ($prove['nombre']); ?></td>
											<td align="center">0.00</td>
											<td align="center" id="totalegre<?php echo $cont; ?>"><?php echo number_format(floatval($prove['abono']),2,'.',''); ?></td>
											<td>
											<?php 
											if(isset($prove['listaprove'])){?>
												<script>
												$(document).ready(function(){
													$("#CuentaProveedores<?php echo $cont; ?>").select2({width : "130px"});
												});
												</script>
												
												<font color="red" face="Comic Sans MS,arial,verdana">Por favor elija una cuenta para el movimiento del Proveedor.</font>
												<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
												<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="actualizaCuentas(<?php echo $cont; ?>)" src="images/reload.png">
												
												<select id="CuentaProveedores<?php echo $cont; ?>">
													<?php echo $cuentaprove; ?>
												</select>
												
									<?php	} ?> </td>
												<td align="" name="xml" id="xml" style="size: 10px"><?php echo ( $prove['xml']); ?></td> 
												<td align="center" id="">
										     		<select name='segmento<?php echo $cont; ?>' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="nminputselect">
														<?php echo $segmento; ?>
													</select>
										    		</td>
										    		<td align="center" id="">
										     		<select name='sucursal<?php echo $cont; ?>' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="nminputselect">
													<?php echo $sucursal; ?>
													</select>
										    		</td>
										   		<td>
										   			<img src="images/eliminado.png" title="Eliminar Movimiento"  onclick="borra(<?php echo $cont; ?>,'poliprove');"/>
										   		</td>
											</tr>
										
										<!-- retencion -->
								  		<tr><td colspan="4" class="nmwatitles">Retencion</td></tr>
										<?php foreach ( $prove['retenidos'] as $key => $value){ 
											if($value>0){?>
										<tr>
												 	<td rowspan="1" align="center">Cuenta para <?php echo $key; ?> </td>
												 	
												 	<?php 
												 	if($statusRetencionISH==1){
												 		
												 		if($key=="IVA"){ ?>
													 		<td class="nmcatalogbusquedatit" align="center">
																<input type="button" id="IVA" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $ivaretenido[1]; ?>"/>
															</td>	
												 <?php	} 
												 		if($key=="ISR"){?>
													 		<td class="nmcatalogbusquedatit" align="center">
																<input type="button" id="ISR" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" style="width: 100%" value="<?php   echo $isrretenido[1]; ?>"/>
															</td>	
												 <?php	}
													}else{?>
														<script>
												 		$(document).ready(function(){
															$("#<?php echo $key.$cont;?>").select2({width : "100px"});
														});
												 	</script>
														<td class="nmcatalogbusquedatit" align="center">
													 		<select id="<?php echo $key.$cont;?>" class="nminputselect" style='width: 100px;text-overflow: ellipsis;'> 
																<?php echo $cuentaparaimpuest; ?>
															</select>
														</td>	
														
												<?php } ?>
												<td align="center">0.00</td>
											    <td align="center" id="total<?php echo $key; ?>" name="total<?php echo $key; ?>"><?php echo number_format(floatval($value),2,'.',''); ?></td>
												</tr>

											<?php } }//foreach retencion ?>
													<!-- fin retencion -->

												<tr><td colspan="6"><hr></hr></td></tr>
									<?php  $cont++; }//foreach interno
								     	} ?>
										</tbody>
									<?php } ?>
									<tfoot>
										<tr><td>
											<label>Concepto Poliza:</label>
											<textarea id="conceptopoliza" class="form-control" rows="5"></textarea>
									</td></tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</section>
				<h3 class="nmwatitles">TOTAL</h3>
				<section>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">          
				  				<table class="table table-bordered" id='contex'>
				  					<tr>
										<td>Cargos: <b>$<label id="cargo"></label> </b></td>
										<td>Abonos: <b>$<label id="abono"></label></b></td>
										<td>Diferencia: <b  style="color:red;"> $<label id="dife"></label></b></td>
									</tr>
				  				</table>
				  			</div>
				  		</div>
					</div>
				</section>
				<section>
					<div class="row">
						<div class="col-md-12">
							<img src="images/loading.gif" style="display: none;" id="load2">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
						</div>
						<div class="col-md-3">
							<button class="btn btn-primary btnMenu" id="agregaprevio" onclick="antesdeguardar(<?php echo $cont; ?>,<?php echo $contdeta; ?>);">Agregar Poliza</button>
							<button class="btn btn-primary btnMenu" id="agrega" onclick="guardaprovimultiple();" style="display: none;">Agregar Poliza</button>
						</div>
						<div class="col-md-3">
							<!-- onclick="cancela();" -->
							<button class="btn btn-danger btnMenu" type="button" id="cancela"  data-loading-text="<i class='fa fa-spinner fa-pulse fa-2x fa-fw margin-bottom'></i>"> Cancelar Poliza</button>
						</div>
					</div>
				</section>
				<div id="almacen" class="modal fade" tabindex="-1" role="dialog" >
				  	<div class="modal-dialog modal-lg" style="width: 80%">
				    	<div class="modal-content">
				      		<div class="modal-header">
				        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        		<h4 class="modal-title">Almac&eacute;n digital</h4>
				      		</div>
				      		<div class="modal-body" >
			      			<div class="row">
		        			<div class="col-md-3">
		        				<input type='text' class="form-control" id='busqueda' name='busqueda' placeholder='Buscar Factura' onchange='listaTemporales()'>
		        				<input type='text' id='fecha_f' class='form-control fechas_izq' value='' onchange='listaTemporales()'>
		        			</div>
		        			
		        			<div class="col-md-2">
		        				<input type='text' class="form-control" id='busqueda2' name='busqueda2' placeholder='Buscar Factura'>
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
			  					<div class="col-md-12">
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
				      				<div class="col-md-9">
				      				</div>
				  					<div class="col-md-3">
				  						<button class="btn btn-primary btnMenu" onclick="javascript:abreFacturasPrev();">Previsualizar</button>
				  					</div>
				  				</div>
				      		</div>
				      	</div>
				    </div>
				</div>
			</div>
			<div class="col-md-1">
			</div>
		</div>
	</div>

<script>
$(function () {
         var cargo=0;var abono=0;
        $("#datos tbody tr").each(function (index) //recorre todos los tr
        {
            $(this).children("td").each(function (index2) //en la fila actual recorremos los td
            {
                switch (index2) //indice
                {
                    case 2: cargo += parseFloat($(this).text().replace(/,/gi,''));//se reemplazan todas las comas
                            break;
                    case 3: abono += parseFloat($(this).text().replace(/,/gi,''));
                            break;
                }
            })
        })
          $("#abono").html(abono.toFixed(2));
          $("#cargo").html(cargo.toFixed(2));
          $("#dife").html( Math.abs((cargo.toFixed(2)-abono.toFixed(2)).toFixed(2)));
})
<?php if(isset($_SESSION['comprobante'])){?>
		$("#comprobante").val(<?php echo $_SESSION['comprobante']; ?>);
	<?php } 
	if(isset($_SESSION['provisioncliente']) || isset($_SESSION['poliprove'])){?>
		$("#comprobante").attr("disabled",true);
	<?php } ?>
	function sele(){
		if($('#comprobante').val()==0){
			alert("Debe seleccionar un comprobante primero");
			return false;
		}else{
			
				$("#formulario").submit();
				$("#comprobante").attr("disabled",false);
		}
	}
</script>
<?php if(isset( $_COOKIE['ejercicio'])){ ?>
		<input type="hidden" id="idperio" value="<?php echo $_COOKIE['periodo']; ?>">
		<input type="hidden" id="ejercicio" value="<?php echo $_COOKIE['ejercicio'];?>" />
	<?php }else{ ?>
		<input type="hidden" id="idperio" value="<?php echo $Ex['PeriodoActual']; ?>">
		<input type="hidden" id="ejercicio" value="<?php echo $Ex['NombreEjercicio'];?>" />
	<?php } ?>
</body>

</html>