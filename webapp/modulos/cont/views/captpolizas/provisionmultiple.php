<!DOCTYPE html>
<head>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<link rel="stylesheet" type="text/css" href="css/clipolizas.css"/>
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.number.js"></script>
	
	<script type="text/javascript" src="js/sessionejer.js"></script>
	<script type="text/javascript" src="js/poliprovisional.js" ></script>
	<script src="js/select2/select2.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<script type="text/javascript" src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script> 
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/	daterangepicker.css" />

	<style>
	.datos td, th{
		height:20px;
		word-wrap: break-word;	
	}
	.inputtext{
		color:black;
	}
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
</style>
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
function antesdeguardar(cont){
		
		//MODIFICADO POR IVAN
		//Obliga a tomar el resultado que se selecciono o la primer opcion en caso de no cambiar las opciones el combo
		var nsel = $("select[id*='cuentaingre']").length
		$("select[id*='cuentaingre']").trigger('change')
		for(i=0;i<=nsel-1;i++)
			console.log("cuenta: "+i+", "+$("#cuentaingre"+i).val())
		//MODIFICADO POR IVAN HASTA AQUI

		var i=0; var status=0; var tipo="";var arra= "";
	  	for(i;i<cont;i++){
	  		
	  <?php if($statusIVAIEPS==1){ 
	  			if($statusIVA==1){?>
	  				if($("#ivaingre").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA ");  return false;}
			<?php } if($statusIEPS==1){?>
					if($("#ieps").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IEPS ");  return false;}
	<?php		}
		 }
		if($statusIEPS==0){?>
			if($("#ieps").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion Cuenta de Gasto ");  return false;}
	<?php }
		if($statusIVA==0){?>
			if($("#ivaingre").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion Cuenta de Gasto ");  return false;}

<?php } if($statusRetencionISH==1){?>
	  			if($("#IVA").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA retenido");  return false;}
				if($("#ISR").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas ISR retenido");  return false;}
				if($("#ish").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas ISH");  return false;}
	<?php   } 
	  if(isset($_SESSION['comprobante'])){
	  	if($_SESSION['comprobante']==1 || $_SESSION['comprobante']==3){?>
	  		tipo="cliente";arra="provisioncliente";
		  		if($("#CuentaClientes"+i).is(":visible") ){
		  			if($("#CuentaClientes"+i).val()==0 || !$("#CuentaClientes"+i).val()){
		  				alert("Seleccione cuenta para Cliente");
		  				$("#load2").hide();$("#agregaprevio,#cancela").show();
		  				return false;
		  			}
		  		}
	  <?php	}if($_SESSION['comprobante']==2 || $_SESSION['comprobante']==4){?>
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
    		var porcentaje=0;var prorrateo = [];
		$(".inputtext"+i).each(function(){
	       porcentaje += parseInt($(this).val());
	       prorrateo.push(parseInt($(this).val()));
	    });
    		if(porcentaje < 100 || porcentaje >100){
   		 	alert("Debe cubrir el 100% de Prorrateo");
   		 	$("#load2").hide();
			$("#agregaprevio").show();
			$("#cancela").show();
    			return false;
    		}
    		var segmento = [];var sucursal=[];var cuentacompraventa=[];var conceptos=[];
		$($("select.segme"+i)).each(function(){
	       segmento.push(parseInt($(this).val()));
	    });
	    $($("select.sucu"+i)).each(function(){
	       sucursal.push(parseInt($(this).val()));
	    });
	    $($("select.ingrecuenta"+i)).each(function(){
	       cuentacompraventa.push(parseInt($(this).val()));
	    });
	     $($(".conceptospro"+i)).each(function(){
	       conceptos.push($(this).val());
	    })
		  	 $.post('index.php?c=CaptPolizas&f=guardanewvaloresprovision',{
	  			cont : i,
		  		ivapendiente : $("#ivaingre"+i).val(),//cuenta
				iepspendiente : $("#ieps"+i).val(),
				iva : $("#IVA"+i).val(),
				isr : $("#ISR"+i).val(),
				ish : $("#ish"+i).val(),
				hayProrrateo:$("#hayProrrateo"+i).val(),
				CuentaClientes : $("#CuentaClientes"+i).val(),
				CuentaProveedores : $("#CuentaProveedores"+i).val(),
				segmento : segmento,
				sucursal : sucursal,
				cuentacompraventa:cuentacompraventa,
				concepto:conceptos,
				prorrateo:prorrateo,
				tipo : tipo,
				array:arra
			 },function (resp){
	  			status+=1;
	  			
	  			if(status==cont ){
		 			$("#agrega").click();
		 			// $("#load2").hide();
					// $("#agregaprevio").show();
					// $("#cancela").show();
				}
	  		 });
  		
	 	}
	 	
	 	
  }		 
dias_periodo(<?php echo $NombreEjercicio; ?>,<?php echo $v; ?>)

$(document).ready(function(){
	 $('#periodomanual').val($('#Periodo').val());
});

</script>
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
						P&oacute;lizas de provisi&oacute;n 
						<a href='index.php?c=CaptPolizas&f=filtroAutomaticas&t=provision' onclick="" id='filtros'>
							<img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'>
						</a>
					</h3>
				</div>
			</div>
			<h4>Datos del Ejercicio</h4>
			<section>
				<div class="row">
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
					<div class="col-md-6">
						<label>Ejercicio Vigente:</label>
						<?php
							if($Ex['PeriodosAbiertos'])
							{
								if($ejercicioactual > $firstExercise)
								{
						?>			<a href='javascript:cambioEjercicio(<?php echo $peridoactual; ?>,<?php echo $ejercicioactual-1; ?>);' title='Ejercicio Anterior'>
										<img class='flecha' src='images/flecha_izquierda.png'>
									</a>
						<?php 	
								}
							}
						?>
						del (<?php echo $InicioEjercicio['2']."-".$InicioEjercicio['1']."-".$InicioEjercicio['0']; ?>) 
						al (<?php echo $FinEjercicio['2']."-".$FinEjercicio['1']."-".$FinEjercicio['0']; ?>)
						<?php 
							if($Ex['PeriodosAbiertos'])
							{
								if($ejercicioactual < $lastExercise)
								{
						?>			<a href='javascript:cambioEjercicio(<?php echo $peridoactual; ?>,<?php echo $ejercicioactual+1; ?>)' title='Ejercicio Siguiente'><img class='flecha' src='images/flecha_derecha.png'></a>
						<?php 
								}
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
						?>			<a href='javascript:cambioPeriodo(<?php echo $peridoactual-1; ?>,<?php echo $ejercicioactual; ?>);' title='Periodo Anterior'><img class='flecha' src='images/flecha_izquierda.png'></a>
						<?php 	}
							} 
						?>  
						<label id='PerAct'><?php echo $peridoactual; ?></label>
						<input type='hidden' id='Periodo' value='<?php echo $peridoactual; ?>'> 
						del (<label id='inicio_mes'></label>) 
						al (<label id='fin_mes'></label>)  
					 	<?php 
						 	if($Ex['PeriodosAbiertos'])
							{
								if($peridoactual<13)
								{
						?>			<a href='javascript:cambioPeriodo(<?php echo $peridoactual+1; ?>,<?php echo $ejercicioactual; ?>)' title='Periodo Siguiente'>
										<img class='flecha' src='images/flecha_derecha.png'>
									</a>
						<?php 	}
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
						<label>Acorde a configuraci&oacute;n:</label>
						<img src="images/reload.png" onclick="periodoactual()" title="Ejercicio y periodo de configuracion por defecto" style="vertical-align:middle;">
					</div>
					<div class="col-md-6">
						<input type="hidden" id="diferencia" value="<?php echo number_format($Cargos['Cantidad']-$Abonos['Cantidad'], 2); ?>" />
					</div>
				</div>
			</section>
			<form method="post" ENCTYPE="multipart/form-data" action="index.php?c=CaptPolizas&f=guardaProvisionMultiple" id="formulario" onsubmit="return sele()">
				<h4>Informaci&oacute;n P&oacute;liza</h4>
				<section>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label>Fecha de p&oacute;liza:</label>
								<?php 	if(isset($_SESSION['fechaprovi'])){ ?>
											<input  type="text" class="form-control" id="fecha" name="fecha" value="<?php echo $_SESSION['fechaprovi']; ?>" onmousemove="javascript:fechadefault()" /></td>
								<?php 	}else{ ?>
											<input  type="text" class="form-control" id="fecha" name="fecha" onmousemove="javascript:fechadefault()" /></td>
								<?php 	} ?>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Concepto:</label>
								<input id="conceptopoliza" class="form-control" type="text" maxlength="50" name="conceptopoliza" value="">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Seleccione un comprobante:</label>
								<select id="comprobante" name="comprobante" onchange="" class="form-control">
									<option value="0" selected="">Elija una opci&oacute;n.</option>
									<option value="1">Ingresos</option>
									<option value="2">Egresos</option>
									<option value="3">Notas de crédito de Ingreso</option>
									<option value="4">Notas de crédito de Egresos</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<button type="button" class="btn btn-primary btnMenu" onclick="abrefacturas()">Facturas no Asignadas</button>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<input id="xml" type="file" multiple="" name="xml[]" >
							</div>
						</div>
						<div class="col-md-3 col-md-offset-1">
							<button type="submit" name="Submit" class="btn btn-primary btnMenu">Previsualizar</button>
						</div>
						
					</div>
				</section>
				<div class="row" id='cargando-mensaje' style='font-size:12px; color:blue; display: none;'>
					<div class="col-md-12">
						Cargando...
					</div>
				</div>
			</form>
			<section>
				<?php 
				$head = '<thead style="display:none;">
							<tr >
								<td align="center" width="15%"></td>
								<td align="center" width="20%"></td>
								<td align="center" width="10%"></td>
								<td align="center" width="10%"></td>
								<td align="center" width="10%"></td>
								<td align="center" width="10%"></td>
								<td align="center" width="10%"></td>
								<td align="center" width="5%"></td>
							</tr>
							<tr >
								<td colspan="2" align="center"></td>
								<td colspan="2" align="center"></td>
								<td colspan="4"></td>
							</tr>
						</thead>';
				?>
				<input type="hidden" id="valorpro"/>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive">          
			  				<table class="table table-bordered datos" style="margin-bottom: 0;">
								<thead>
									<tr style="background-color:#999999; color:white; font-weight:bold;">
										<td align="center" width="15%">Concepto</td>
										<td align="center" width="20%">Cuenta</td>
										<td align="center" width="10%">Cargo</td>
										<td align="center" width="10%">Abono</td>
										<td align="center" width="10%">%</td>
										<td align="center" width="10%">Segmento</td>
										<td align="center" width="10%">Sucursal</td>
										<td align="center" width="5%"></td>
									</tr>
									<tr style="background-color:#999999; color:white; font-weight:bold;">
										<td colspan="2" align="center">Factura</td>
										<td colspan="2" align="center">Referencia</td>
										<td colspan="4"></td>
									</tr>
								</thead>
			  				</table>
			  			</div>
			  		</div>
			  	</div>
			  	<div class="row" style="margin-top: 0 !important;">
			  		<div class="col-md-12">
			  			<?php 
			  				$cont=0;
							$monto=0;$cargos=0; $abonos=0;
							if($_SESSION['comprobante']==1){
								foreach($_SESSION['provisioncliente'] as $cliente){
									foreach($cliente as $cli){
										if(is_array($cli['concepto'])){ 
											$concepto = $cli['concepto'][0];
										}else{
											$concepto = $cli['concepto'];
										}
						?>		
										<script>
											$(document).ready(function(){
												$("#cuentaingre<?php echo $cont; ?>,#sucursal<?php echo $cont; ?>,#segmento<?php echo $cont; ?>,#CuentaClientes<?php echo $cont; ?>").select2({width : "130px"});
											});
										</script>
										<div class="row">
											<div class="col-md-12">
												<div class="table-responsive">          
									  				<table class="table datos" id="tabla<?php echo $cont;?>" >
														<?php echo $head;?>
														<tbody>
															<tr style="background-color:#BDBDBD;color:#585858;font-weight:bold;">
																<td align="center" width="3px;" colspan="2"><?php echo ($cli['xml']); ?><input type="hidden" id="hayProrrateo<?php echo $cont; ?>" value="0"/><!-- 0 no 1 si --></td> 
																<td align="center" width="1%" colspan="2"><?php echo $cli['referencia']; ?></td>
																<td><input type="button" id="agregar" value="+Prorrateo" title="Agregar Niveles Prorrateo" onclick="porra('tabla<?php echo $cont;?>',<?php echo $cont; ?>,<?php echo number_format($cli['abono'],2,'.','');?>)"/></td>
																<td colspan="2">
																	<?php 
																		if(isset($cli['listacliente'])){
																	?>
																			<font color="white" face="Comic Sans MS,arial,verdana">Por favor elija una cuenta para el movimiento del cliente.</font>
																			<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
																			<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="actualizaCuentascli(<?php echo $cont ;?>)" src="images/reload.png">
																			<select id="CuentaClientes<?php echo $cont; ?>">
																				<?php echo $cuentacliente; ?>
																			</select> 
																	<?php	
																		} 
																	?>
																</td>
																<td><img src="images/eliminado.png" title="Eliminar Movimiento"  onclick="borra(<?php echo $cont; ?>,'provisioncliente');"/></td>
															</tr>
									  						<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" class="porrateo" >
															 	<td rowspan="1" align="center" style=""><textarea name="conceptoprorrateo<?php echo $cont; ?>[]"  class="conceptospro<?php echo $cont; ?>" style="color: black" id="conceptoprorrateo<?php echo $cont; ?>"><?php echo $concepto;?></textarea></td>
																<td  class="" align="center">
																 	<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
																	<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaingresosact(<?php echo $cont; ?>)" src="images/reload.png">
																	<br>		
																	<select id="cuentaingre<?php echo $cont; ?>" name="cuentaingre<?php echo $cont; ?>[]" class="ingrecuenta<?php echo $cont; ?>" >
																		<?php echo $cuenta; ?>
																	</select>
																</td>
																<td align="center">0.00</td>
															    <td align="center">
															    	<label id="montoprorra<?php echo $cont; ?>" name="montoprorra<?php echo $cont; ?>" data-valor="v<?php echo $cont; ?>v0">
															    	<?php echo number_format($cli['abono'],2,'.',','); $abonos+=$cli['abono']; ?>
															     	</label>
															    </td>
															    <td id="input<?php echo $cont; ?>"><input  onkeyup="calculaprorrateo(this.value,<?php echo number_format($cli['abono'],2,'.',''); ?>,<?php echo $cont;?>,0)" type="text" name="prorrateo<?php echo $cont; ?>[]" id="prorrateo<?php echo $cont; ?>" style="width:80px;display:none;color: black" class="inputtext<?php echo $cont; ?>" align="center" /></td>
															    <td align="center" id="">
															     	<select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																		<?php echo $segmento; ?>
																	</select>
															    </td>
															    <td align="center" id="">
															     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																	<?php echo $sucursal; ?>
																	</select>
															    </td>
															</tr>
														</tbody>
														<tfoot>
															<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																<?php 	if($cli['ieps']>0){ ?>
																			<td></td>
																	<?php 	if($statusIEPS==1){
																				if($statusIVAIEPS==1){ ?>
																					<td  class="" align="center">
																						<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="form-control btn-primary" style="width: 180px; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php  echo $iepspendientecobro[1]; ?>"/>
																					</td>
																	<?php 		}else{ ?>
																					<script>
																						$(document).ready(function(){
																							$("#ieps<?php echo $cont; ?>").select2({width : "130px"});
																						});
																					</script>
																					<td class="" align="center">
																						<font color="white" face="Comic Sans MS,arial,verdana">Cuenta para IEPS</font>
																						<select id="ieps<?php echo $cont; ?>" name="ieps<?php echo $cont; ?>" class="">
																							<?php echo $cuentaparaimpuest; ?>
																						</select>
																					</td>
																	<?php 		}
																			}else{ 
																	?>
																				<td>
																					<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $CuentaIEPSgasto[1]; ?>"/>
																				</td>
																	<?php 	} ?>
																			<td align="center">0.00</td>
																			<td align="center" id="importeieps"><?php echo number_format($cli['ieps'],2,'.',','); $abonos+=$cli['ieps'];?></td>
																			<td></td>
																			<td align="center" id="segieps<?php echo $cont; ?>" style="display: none">
																		     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																					<?php echo $segmento; ?>
																				</select>
																		     </td>
																		     <td align="center" id="sucuieps<?php echo $cont; ?>" style="display: none">
																		     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																				<?php echo $sucursal; ?>
																				</select>
																		     </td>
																		     <td colspan="2" id="muestrameieps<?php echo $cont; ?>"></td>
																			<td></td>
																<?php 	} ?>
															</tr>
															<!-- ISH -->
															<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																<?php if($cli['ish']>0 ){ ?>
																<td></td>
																	<td class="" align="center"><!-- Cuenta para ISH -->
																		<?php if($statusRetencionISH==1){ ?>
																				<input type="button" id="ish" name="ish" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 180px; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php   echo $ishh[1]; ?>"/> 					
																		<?php }else{?>
																				<script>
																					$(document).ready(function(){
																						$("#ish<?php echo $cont;?>").select2({width : "130px"});
																					});
																				</script>
																				<font color="white" face="Comic Sans MS,arial,verdana">Cuenta para ISH </font>
																				<select id="ish<?php echo $cont;?>" name="ish<?php echo $cont;?>" class="" style='width: 100px;text-overflow: ellipsis;' >
																					<?php echo $cuentaparaimpuest; ?>
																				</select>
																		<?php } ?>
																	</td>
																
																	<td align="center">0.00</td>
																	<td align="center" id="importeish<?php echo $cont; ?>"><?php echo number_format($cli['ish'],2,'.',','); $abonos+=$cli['ish'];?></td>
																	<td></td>
																	<td align="center" id="segish<?php echo $cont; ?>" style="display: none">
																     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																			<?php echo $segmento; ?>
																		</select>
															    		 </td>
															   		<td align="center" id="sucuish<?php echo $cont; ?>" style="display: none">
																     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																		<?php echo $sucursal; ?>
																		</select>
															     	</td>
															     <td colspan="2" id="muestrameish<?php echo $cont; ?>"></td>
																<td></td>
																<?php }?>
															</tr>
															<!--FIN  ISH -->
															<?php if($cli['abono2']>0){ ?>
															 <tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
															  	<td></td>
																<?php 
																if($statusIVA==1){ 
																	if($statusIVAIEPS==1){ ?>
																				<td  class="" align="center">
																				<input style="width: 180px; font-size: 10px; padding-top: 2px; white-space: normal;" type="button" id="ivaingre" name="ivaingre<?php echo $cont; ?>" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="form-control btn-primary" value="<?php  echo $ivapendientecobro[1];?>">
																				</td>
																	<?php }else{?>
																			<script>
																				$(document).ready(function(){
																					$("#ivaingre<?php echo $cont; ?>").select2({width : "130px"});
																				});
																			</script>
																			<td  class="" align="center">
																				<font color="white" face="Comic Sans MS,arial,verdana">IVA Pendiente de cobro</font>
																				<select id="ivaingre<?php echo $cont; ?>" name="ivaingre<?php echo $cont; ?>" class="">
																					<?php echo $cuentaparaimpuest; ?>
																				</select>
																			</td> 
																	<?php } 
																	}else{ /*echo "no calcula";*/?>
																		<td>
																			<input type="button" id="ivaingre" name="ivaingre" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $CuentaIVAgasto[1]; ?>"/>
																		</td>
																	<?php }?> 		
																		<td align="center">0.00</td>
																		<td align="center" id="importe"><?php echo number_format($cli['abono2'],2,'.',','); $abonos+=$cli['abono2'];?></td>
																		<td></td>
																		<td align="center" id="segiva<?php echo $cont; ?>" style="display: none">
																	     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																				<?php echo $segmento; ?>
																			</select>
																	     </td>
																	     <td align="center" id="sucuiva<?php echo $cont; ?>" style="display: none">
																	     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																			<?php echo $sucursal; ?>
																			</select>
																	     </td>
																	     <td colspan="2" id="muestrameiva<?php echo $cont; ?>"></td>
																		<td></td>
																	 </tr>
																	<?php } ?> 
																	 <tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																	 	<td rowspan="1" align="center" ><!-- <textarea style="color: black" id="concepto<?php echo $cont; ?>" class="nminputtext"><?php echo $cli['concepto'];?></textarea> --></td>
																		 <td  class="" align="center" ><?php echo $cli['nombre']; ?></td>
																	     <td align="center" id="total<?php echo $cont; ?>"><?php echo number_format($cli['cargo'],2,'.',','); $cargos+=$cli['cargo'];?></td>
																	     <td align="center">0.00</td>
																	     <td></td>
																		<td align="center" id="segnombre<?php echo $cont; ?>" style="display: none">
																	     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																				<?php echo $segmento; ?>
																			</select>
																	     </td>
																	     <td align="center" id="sucunombre<?php echo $cont; ?>" style="display: none">
																	     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																			<?php echo $sucursal;?>
																			</select>
																	     </td>
																		<td></td>
																	 </tr>
																	  <!-- retencion -->
																	  <!-- <tr>
																	  <td colspan="8" class="nmwatitles">Retencion</td></tr> -->
																	 <?php foreach ( $cli['retenidos'] as $key => $value){ 
																	 	if($value>0){
																	 	?>
																	 	
																	<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																	 	<td rowspan="1" align="center">Cuenta para <?php echo $key; ?> </td>
																	 	
																	 	<?php 
																	 	if($statusRetencionISH==1){
																	 		
																	 		if($key=="IVA"){ ?>
																		 		<td class="" align="center">
																					<input type="button" id="IVA" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 180px; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php  echo $ivaretenido[1]; ?>"/>
																				</td>	
																	 <?php	} 
																	 		if($key=="ISR"){?>
																		 		<td class="" align="center">
																					<input type="button" id="ISR" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 180px; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php   echo $isrretenido[1]; ?>"/>
																				</td>	
																	 <?php	}
																		}else{?>
																			<script>
																	 		$(document).ready(function(){
																				$("#<?php echo $key.$cont;?>").select2({width : "100px"});
																			});
																	 	</script>
																			<td class="" align="center">
																		 		<select id="<?php echo $key.$cont;?>" class="" style='width: 100px;text-overflow: ellipsis;'> 
																					<?php echo $cuentaparaimpuest; ?>
																				</select>
																			</td>	
																			
																	<?php } ?>
																	    <td align="center" id="total<?php echo $key.$cont; ?>" name="total<?php echo $key.$cont; ?>"><?php echo number_format($value,2,'.',','); $cargos+=$value;?></td>
																	    <td align="center">0.00</td>
																	    <td></td>
																		<td align="center" id="seg<?php echo $key.$cont; ?>" style="display: none">
																	     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																				<?php echo $segmento; ?>
																			</select>
																	     </td>
																	     <td align="center" id="sucu<?php echo $key.$cont; ?>" style="display: none">
																	     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																			<?php echo $sucursal; ?>
																			</select>
																	     </td>
																	     <td colspan="2" id="muestrame<?php echo $key.$cont; ?>"></td>
																		<td></td>
																	 </tr>
																	 <?php } } ?>
																	<!-- fin retencion -->
														</tfoot>
									  				</table>
									  			</div>
									  		</div>
									  	</div>
					<?php  				$cont++; 
									}
								}
							}
							//INICIA COMPORBANTE 3 IGUAL A INGRE PERO INVERTIDO nota credito ongre
							if($_SESSION['comprobante']==3){ ?>
									<?php	 foreach($_SESSION['provisioncliente'] as $cliente){
												 	
													foreach($cliente as $cli){ if(is_array($cli['concepto'])){ $concepto = $cli['concepto'][0];}else{ $concepto = $cli['concepto'];}
												?>	
									
														<script>
														$(document).ready(function(){
															$("#cuentaingre<?php echo $cont; ?>,#sucursal<?php echo $cont; ?>,#segmento<?php echo $cont; ?>,#CuentaClientes<?php echo $cont; ?>").select2({width : "130px"});
														});
														</script>
														<div class="row">
															<div class="col-md-12">
																<div class="table-responsive">          
													  				<table class="table datos" id="tabla<?php echo $cont;?>">
																		<?php echo $head;?>
																		<tbody>
																		<tr style="background-color:#BDBDBD;color:#585858;font-weight:bold;">
																		<td align="center" width="3px;" colspan="2"><?php echo ($cli['xml']); ?>
																		<input type="hidden" id="hayProrrateo<?php echo $cont; ?>" value="0"/><!-- 0 no 1 si -->
																		</td> 
																		<td align="center" width="1%" colspan="2"><?php echo $cli['referencia']; ?></td>
																		<td>	<input type="button" id="agregar" value="+Prorrateo" title="Agregar Niveles Prorrateo" onclick="porra('tabla<?php echo $cont;?>',<?php echo $cont; ?>,<?php echo number_format($cli['abono'],2,'.','');?>)"/></td>
																		<td colspan="2"><?php 
																		if(isset($cli['listacliente'])){?>
																			<font color="white" face="Comic Sans MS,arial,verdana">Por favor elija una cuenta para el movimiento del cliente.</font>
																			<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
																			<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="actualizaCuentascli(<?php echo $cont ;?>)" src="images/reload.png">
																			
																			<select id="CuentaClientes<?php echo $cont; ?>">
																				<!-- <option selected="" value="-1"> Elija una cuenta</option> -->
																				<?php echo $cuentacliente; ?>
																			</select> 
																		<?php	} ?></td>
																		<td><img src="images/eliminado.png" title="Eliminar Movimiento"  onclick="borra(<?php echo $cont; ?>,'provisioncliente');"/></td>
																		</tr>
																		
																		
																		 <tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" class="porrateo" >
																			 	<td rowspan="1" align="center" style=""><textarea name="conceptoprorrateo<?php echo $cont; ?>[]"  class="conceptospro<?php echo $cont; ?>" style="color: black" id="conceptoprorrateo<?php echo $cont; ?>"><?php echo $concepto;?></textarea></td>
																				 <td  class="" align="center">
																				 	<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
																					<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaingresosact(<?php echo $cont; ?>)" src="images/reload.png">
																					<br>		
																					<select id="cuentaingre<?php echo $cont; ?>" name="cuentaingre<?php echo $cont; ?>[]" class="ingrecuenta<?php echo $cont; ?>" >
																						<?php echo $cuenta; ?>
																					</select>
																				 </td>
																			     <td align="center">
																			     	<label id="montoprorra<?php echo $cont; ?>" name="montoprorra<?php echo $cont; ?>" data-valor="v<?php echo $cont; ?>v0">
																			     	<?php echo number_format($cli['abono'],2,'.',',');$cargos+=$cli['abono'];?>
																			     	</label>
																			     </td>
																			     <td align="center">0.00</td>
																			     <td id="input<?php echo $cont; ?>"><input  onkeyup="calculaprorrateo(this.value,<?php echo number_format($cli['abono'],2,'.',''); ?>,<?php echo $cont;?>,0)" type="text" name="prorrateo<?php echo $cont; ?>[]" id="prorrateo<?php echo $cont; ?>" style="width:80px;display:none;color: black" class="inputtext<?php echo $cont; ?>" align="center" /></td>
																			     <td align="center" id="">
																			     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																						<?php echo $segmento; ?>
																					</select>
																			     </td>
																			     <td align="center" id="">
																			     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																					<?php echo $sucursal; ?>
																					</select>
																			     </td>
																			  </tr>
																			</tbody>
																			<tfoot>
																		<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																				
																				<?php if($cli['ieps']>0){ ?>
																						<td></td>
																				<?php if($statusIEPS==1){
																						 if($statusIVAIEPS==1){ ?>
																						<td  class="" align="center">
																							<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="form-control btn-primary" style="width: 180px; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php  echo $iepspendientecobro[1]; ?>"/>
																						</td>
																				<?php }else{?>
																						<script>
																							$(document).ready(function(){
																								$("#ieps<?php echo $cont; ?>").select2({width : "130px"});
																							});
																						</script>
																						<td class="" align="center">
																						<font color="white" face="Comic Sans MS,arial,verdana">Cuenta para IEPS</font>
																						<select id="ieps<?php echo $cont; ?>" name="ieps<?php echo $cont; ?>" class="">
																							<?php echo $cuentaparaimpuest; ?>
																						</select>
																						</td>
																				<?php }
																					}else{ ?>
																						<td>
																						<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $CuentaIEPSgasto[1]; ?>"/>
																						</td>
																				<?php } ?>
																					<td align="center" id="importeieps"><?php echo number_format($cli['ieps'],2,'.',','); $cargos+=$cli['ieps'];?></td>
																					<td align="center">0.00</td>
																					<td></td>
																					<td align="center" id="segieps<?php echo $cont; ?>" style="display: none">
																				     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																							<?php echo $segmento; ?>
																						</select>
																				     </td>
																				     <td align="center" id="sucuieps<?php echo $cont; ?>" style="display: none">
																				     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																						<?php echo $sucursal; ?>
																						</select>
																				     </td>
																				     <td colspan="2" id="muestrameieps<?php echo $cont; ?>"></td>
																					<td></td>
																				<?php } ?>
																				</tr>
																				<!-- ISH -->
																				<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																				
																				<?php if($cli['ish']>0 ){ ?>
																				
																				<td></td>
																					<td class="" align="center"><!-- Cuenta para ISH -->
																						<?php if($statusRetencionISH==1){ ?>
																								<input type="button" id="ish" name="ish" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 180px; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php   echo $ishh[1]; ?>"/> 					
																						<?php }else{?>
																								<script>
																									$(document).ready(function(){
																										$("#ish<?php echo $cont;?>").select2({width : "130px"});
																									});
																								</script>
																								<font color="white" face="Comic Sans MS,arial,verdana">Cuenta para ISH </font>
																								<select id="ish<?php echo $cont;?>" name="ish<?php echo $cont;?>" class="" style='width: 100px;text-overflow: ellipsis;' >
																									<?php echo $cuentaparaimpuest; ?>
																								</select>
																						<?php } ?>
																					</td>
																				
																					<td align="center" id="importeish<?php echo $cont; ?>"><?php echo number_format($cli['ish'],2,'.',','); $cargos+=$cli['ish'];?></td>
																					<td align="center">0.00</td>
																					<td></td>
																					<td align="center" id="segish<?php echo $cont; ?>" style="display: none">
																				     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																							<?php echo $segmento; ?>
																						</select>
																			    		 </td>
																			   		<td align="center" id="sucuish<?php echo $cont; ?>" style="display: none">
																				     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																						<?php echo $sucursal; ?>
																						</select>
																			     	</td>
																			     <td colspan="2" id="muestrameish<?php echo $cont; ?>"></td>
																				<td></td>
																				<?php }?>
																				</tr>
																				<!--FIN  ISH -->
																			<?php if($cli['abono2']>0){ ?>
																			 <tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																			  	<td></td>
																		<?php 
																		 if($statusIVA==1){
																			if($statusIVAIEPS==1){ ?>
																						<td  class="" align="center">
																						<input style="width: 180px; font-size: 10px; padding-top: 2px; white-space: normal;" type="button" id="ivaingre" name="ivaingre<?php echo $cont; ?>" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="form-control btn-primary" value="<?php  echo $ivapendientecobro[1];?>">
																						</td>
																			<?php }else{?>
																					<script>
																						$(document).ready(function(){
																							$("#ivaingre<?php echo $cont; ?>").select2({width : "130px"});
																						});
																					</script>
																					<td  class="" align="center">
																						<font color="white" face="Comic Sans MS,arial,verdana">IVA Pendiente de cobro</font>
																						<select id="ivaingre<?php echo $cont; ?>" name="ivaingre<?php echo $cont; ?>" class="">
																							<?php echo $cuentaparaimpuest; ?>
																						</select>
																					</td> 
																			<?php }
																		 }else{ ?> 
																		 	<td>
																				<input type="button" id="ieivaingreps" name="ivaingre" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="nmcatalogbusquedatit" style="width: 100%" value="<?php  echo $CuentaIVAgasto[1]; ?>"/>
																			</td>
																		 <?php } ?>		
																				<td align="center" id="importe"><?php echo number_format($cli['abono2'],2,'.',','); $cargos+=$cli['abono2'];?></td>
																				<td align="center">0.00</td>
																				<td></td>
																				<td align="center" id="segiva<?php echo $cont; ?>" style="display: none">
																			     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																						<?php echo $segmento; ?>
																					</select>
																			     </td>
																			     <td align="center" id="sucuiva<?php echo $cont; ?>" style="display: none">
																			     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																					<?php echo $sucursal; ?>
																					</select>
																			     </td>
																			     <td colspan="2" id="muestrameiva<?php echo $cont; ?>"></td>
																				<td></td>
																			 </tr>
																			<?php } ?> 
																			 <tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																			 	<td rowspan="1" align="center" ><!-- <textarea style="color: black" id="concepto<?php echo $cont; ?>" class="nminputtext"><?php echo $cli['concepto'];?></textarea> --></td>
																				 <td  class="" align="center" ><?php echo $cli['nombre']; ?></td>
																			     <td align="center">0.00</td>
																			     <td align="center" id="total<?php echo $cont; ?>"><?php echo number_format($cli['cargo'],2,'.',','); $abonos+=$cli['cargo'];?></td>
																			     <td></td>
																				<td align="center" id="segnombre<?php echo $cont; ?>" style="display: none">
																			     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																						<?php echo $segmento; ?>
																					</select>
																			     </td>
																			     <td align="center" id="sucunombre<?php echo $cont; ?>" style="display: none">
																			     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																					<?php echo $sucursal; ?>
																					</select>
																			     </td>
																				<td></td>
																			 </tr>
																			  <!-- retencion -->
																			  <!-- <tr>
																			  <td colspan="8" class="nmwatitles">Retencion</td></tr> -->
																			 <?php foreach ( $cli['retenidos'] as $key => $value){
																			 	if($value>0){ ?>
																			 	
																			<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																			 	<td rowspan="1" align="center">Cuenta para <?php echo $key; ?> </td>
																			 	
																			 	<?php 
																			 	if($statusRetencionISH==1){
																			 		
																			 		if($key=="IVA"){ ?>
																				 		<td class="" align="center">
																							<input type="button" id="IVA" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="form-control" style="width: 180px;" value="<?php  echo $ivaretenido[1]; ?>"/>
																						</td>	
																			 <?php	} 
																			 		if($key=="ISR"){?>
																				 		<td class="" align="center">
																							<input type="button" id="ISR" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="form-control" style="width: 180px;" value="<?php   echo $isrretenido[1]; ?>"/>
																						</td>	
																			 <?php	}
																				}else{?>
																					<script>
																			 		$(document).ready(function(){
																						$("#<?php echo $key.$cont;?>").select2({width : "100px"});
																					});
																			 	</script>
																					<td class="" align="center">
																				 		<select id="<?php echo $key.$cont;?>" class="" style='width: 100px;text-overflow: ellipsis;'> 
																							<?php echo $cuentaparaimpuest; ?>
																						</select>
																					</td>	
																					
																			<?php } ?>
																				<td align="center">0.00</td>
																			    <td align="center" id="total<?php echo $key.$cont; ?>" name="total<?php echo $key.$cont; ?>"><?php echo number_format($value,2,'.',','); $abonos+=$value;?></td>
																			    <td></td>
																				<td align="center" id="seg<?php echo $key.$cont; ?>" style="display: none">
																			     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																						<?php echo $segmento; ?>
																					</select>
																			     </td>
																			     <td align="center" id="sucu<?php echo $key.$cont; ?>" style="display: none">
																			     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																					<?php echo $sucursal; ?>
																					</select>
																			     </td>
																			     <td colspan="2" id="muestrame<?php echo $key.$cont; ?>"></td>
																				<td></td>
																			 </tr>
																			 <?php } }?>
																			<!-- fin retencion -->
																			 <!-- <tr>
																			 	<td colspan="7"><hr></hr></td></tr> -->
																			</tfoot>
																	</table>
													  			</div>
													  		</div>
													  	</div>
			  				<?php  						$cont++; 		
			  										} 
			  									}
											}
											//TERMINA COMPROBANTE 3

											if($_SESSION['comprobante']==2){ ?>
											<?php  foreach($_SESSION['poliprove'] as $pro){ 
													 	foreach($pro as $prove){ if(is_array($prove['concepto'])){ $concepto = $prove['concepto'][0];}else{ $concepto = $prove['concepto'];} ?>
													 		<script>
																$(document).ready(function(){
																	$("#cuentaingre<?php echo $cont; ?>,#sucursal<?php echo $cont; ?>,#segmento<?php echo $cont; ?>,#CuentaProveedores<?php echo $cont; ?>").select2({width : "130px"});
																});
															</script>
															<div class="row">
																<div class="col-md-12">
																	<div class="table-responsive">          
														  				<table class="table" id="tabla<?php echo $cont;?>" >
																						<?php echo $head;?>
																						<tbody>
																						<tr style="background-color:#BDBDBD;color:#585858;font-weight:bold;">
																							<td align="center" width="3px;" colspan="2"><?php echo ($prove['xml']); ?>
																								<input type="hidden" id="hayProrrateo<?php echo $cont; ?>" value="0"/><!-- 0 no 1 si -->
																							</td> 
																							<td align="center" width="1%" colspan="2"><?php echo $prove['referencia']; ?></td>
																							<td>	<input type="button" id="agregar" value="+Prorrateo" title="Agregar Niveles Prorrateo" onclick="porra('tabla<?php echo $cont;?>',<?php echo $cont; ?>,<?php echo number_format($prove['cargo'],2,'.','');?>)"/></td>
																							<td colspan="2"><?php 
																							if(isset($prove['listaprove'])){?>
																								<font color="white" face="Comic Sans MS,arial,verdana">Por favor elija una cuenta para el movimiento del Proveedor.</font>
																								<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
																								<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="actualizaCuentas(<?php echo $cont; ?>)" src="images/reload.png">
																								
																								<select id="CuentaProveedores<?php echo $cont; ?>">
																									<?php echo $cuentaprove; ?>
																								</select>
																					<?php	} ?>
																							</td>
																							<td>
																					   			<img src="images/eliminado.png" title="Eliminar Movimiento"  onclick="borra(<?php echo $cont; ?>,'poliprove');"/>
																							</td>
																						</tr>
																						
																			 			<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" class="porrateo" >
																						 	<td rowspan="1" align="center" style=""><textarea name="conceptoprorrateo<?php echo $cont; ?>[]"  class="conceptospro<?php echo $cont; ?>" style="color: black" id="conceptoprorrateo<?php echo $cont; ?>"><?php echo $concepto;?></textarea></td>
																							<td  class="" align="center">
																								<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
																								<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaegresosact(<?php echo $cont; ?>)" src="images/reload.png">
																								<br>		
																								<select id="cuentaingre<?php echo $cont; ?>" name="cuentaingre<?php echo $cont; ?>[]" class="ingrecuenta<?php echo $cont; ?>" >
																									<?php echo $cuentaegre; ?>
																								</select>
																							</td>
																							<td align="center" id="subtotalegre">
																								<label id="montoprorra<?php echo $cont; ?>" name="montoprorra<?php echo $cont; ?>" data-valor="v<?php echo $cont; ?>v0">
																						     	<?php echo number_format(floatval($prove['cargo']),2,'.',','); $cargos+=$prove['cargo']; ?>
																						     	</label>
																							</td>
																					 		<td align="center">0.00</td>
																					 		<td id="input<?php echo $cont; ?>"><input  onkeyup="calculaprorrateo(this.value,<?php echo number_format($prove['cargo'],2,'.',''); ?>,<?php echo $cont;?>,0)" type="text" name="prorrateo<?php echo $cont; ?>[]" id="prorrateo<?php echo $cont; ?>" style="width:80px;display:none;color: black" class="inputtext<?php echo $cont; ?>" align="center" /></td>
																							<td align="center" id="">
																					     		<select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																									<?php echo $segmento; ?>
																								</select>
																					    		</td>
																					    		<td align="center" id="">
																					     		<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																								<?php echo $sucursal; ?>
																								</select>
																					    		</td>
																					   		</tr>
																						</tbody>
																						<tfoot>
																					<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																					<?php if($prove['ieps']>0){ ?>
																						<td></td>
																				<?php if($statusIEPS==1){
																				 	if($statusIVAIEPS==1){ ?>
																						<td align="center">
																						<input type="button" id="ieps" name="ieps<?php $cont;?>" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 100%; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php   echo $iepspendientepago[1]; ?>"/>
																						</td>
																				<?php }else{ ?>
																						<script>
																						$(document).ready(function(){
																							$("#ieps<?php echo $cont; ?>").select2({width : "130px"});
																						});
																						</script>
																						<td class="" align="center">
																						<font color="white" face="Comic Sans MS,arial,verdana">Cuenta para IEPS</font>
																						<select id="ieps<?php echo $cont; ?>" name="ieps<?php echo $cont; ?>" class="">
																							<?php echo $cuentaparaimpuest; ?>
																						</select>
																						</td>
																					<?php }	
																					}else{ ?>
																						<td>
																						<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="form-control btn-primary" style="width: 100%; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php  echo $CuentaIEPSgasto[1]; ?>"/>
																						</td>
																				<?php } ?>

																					<td align="center" id="importeiepsegre"><?php echo number_format($prove['ieps'],2,'.',','); $cargos+=$prove['ieps'];?></td>
																					<td align="center">0.00</td>
																					<td></td>
																					<td align="center" id="segieps<?php echo $cont; ?>" style="display: none">
																				     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																							<?php echo $segmento; ?>
																						</select>
																				     </td>
																				     <td align="center" id="sucuieps<?php echo $cont; ?>" style="display: none">
																				     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																						<?php echo $sucursal; ?>
																						</select>
																				     </td>
																				     <td colspan="2" id="muestrameieps<?php echo $cont; ?>"></td>
																					<td></td>
																			<?php } ?>

																				</tr>
																				<!-- ISH -->
																			<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																			<?php if($prove['ish']>0){ ?>
																				<td></td>
																				<td class="" align="center"><!-- Cuenta para ISH -->
																				<?php if($statusRetencionISH==1){ ?>
																						<input type="button" id="ish" name="ish" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 100%; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php   echo $ishh[1]; ?>"/> 					
																				<?php }else{?>
																						<script>
																							$(document).ready(function(){
																								$("#ish<?php echo $cont;?>").select2({width : "130px"});
																							});
																						</script>
																						<font color="white" face="Comic Sans MS,arial,verdana">Cuenta para ISH </font>
																						<select id="ish<?php echo $cont;?>" name="ish<?php echo $cont;?>" class="" style='width: 100px;text-overflow: ellipsis;' >
																							<?php echo $cuentaparaimpuest; ?>
																						</select>
																				<?php } ?>
																				</td>

																				<td align="center" id="importeishegre"><?php echo number_format(floatval($prove['ish']),2,'.',',');$cargos+=$prove['ish'];?></td>
																				<td align="center">0.00</td>
																				<td></td>
																				<td align="center" id="segish<?php echo $cont; ?>" style="display: none">
																			     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																						<?php echo $segmento; ?>
																					</select>
																		    		 </td>
																		   		<td align="center" id="sucuish<?php echo $cont; ?>" style="display: none">
																			     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																					<?php echo $sucursal; ?>
																					</select>
																		     	</td>
																		     	<td colspan="2" id="muestrameish<?php echo $cont; ?>"></td>
																				<td></td>
																			<?php } ?>
																				</tr>
																				<!--FIN  ISH -->
																		<?php

																		 	if($prove['cargo2']>0){ ?>
																				<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																						<td></td>
																					<?php 
																					if($statusIVA==1){
																					if($statusIVAIEPS==1){ ?>
																						<td   align="center">
																						<input type="button" id="ivaingre" name="ivaingre<?php echo $cont; ?>" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 100%; white-space: normal; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php   echo $ivapendientepago[1]; ?>"/>
																						</td>
																					<?php }else{?>
																							<script>
																								$(document).ready(function(){
																									$("#ivaingre<?php echo $cont; ?>").select2({width : "130px"});
																								});
																							</script>
																							<td  class="" align="center">
																								<font color="white" face="Comic Sans MS,arial,verdana">IVA Pendiente de Pago</font>
																								<select id="ivaingre<?php echo $cont; ?>" name="ivaingre<?php echo $cont; ?>" class="">
																									<?php echo $cuentaparaimpuest; ?>
																								</select>
																							</td> 
																					<?php }
																			}else{ ?>
																				<td>
																				<input type="button" id="ivaingre" name="ivaingre" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="form-control btn-primary" style="width: 100%; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php  echo $CuentaIVAgasto[1]; ?>"/>
																				</td>
																			<?php } ?>
																					<td align="center" id="importeegre"><?php if($prove['cargo2']){echo number_format($prove['cargo2'],2,'.',',');}else{ echo 0;}  $cargos+=$prove['cargo2'];?></td>
																					<td align="center">0.00</td>
																					<td></td>
																					<td align="center" id="segiva<?php echo $cont; ?>" style="display: none">
																				     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																							<?php echo $segmento; ?>
																						</select>
																				     </td>
																				     <td align="center" id="sucuiva<?php echo $cont; ?>" style="display: none">
																				     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																						<?php echo $sucursal; ?>
																						</select>
																				     </td>
																				     <td colspan="2" id="muestrameiva<?php echo $cont; ?>"></td>
																					<td></td>
																				<?php } ?> 
																				<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																					<td rowspan="1" align="center"></td>
																					<td  class="" align="center"><?php echo ($prove['nombre']); ?></td>
																					<td align="center">0.00</td>
																					<td align="center" id="totalegre<?php echo $cont; ?>"><?php echo number_format(floatval($prove['abono']),2,'.',','); $abonos+=$prove['abono'];?></td>
																					<td></td>
																					<td align="center" id="segnombre<?php echo $cont; ?>" style="display: none">
																				     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																							<?php echo $segmento; ?>
																						</select>
																				     </td>
																				     <td align="center" id="sucunombre<?php echo $cont; ?>" style="display: none">
																				     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																						<?php echo $sucursal; ?>
																						</select>
																				     </td>
																					<td></td>
																				</tr>
																		<!-- retencion -->
																				<?php foreach ( $prove['retenidos'] as $key => $value){
																					if($value>0){ ?>
																				<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																						 	<td rowspan="1" align="center">Cuenta para <?php echo $key; ?> </td>
																						 	
																						 	<?php 
																						 	if($statusRetencionISH==1){
																						 		
																						 		if($key=="IVA"){ ?>
																							 		<td class="" align="center">
																										<input type="button" id="IVA" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="form-control" style="width: 100%" value="<?php  echo $ivaretenido[1]; ?>"/>
																									</td>	
																						 <?php	} 
																						 		if($key=="ISR"){?>
																							 		<td class="" align="center">
																										<input type="button" id="ISR" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="form-control" style="width: 100%" value="<?php   echo $isrretenido[1]; ?>"/>
																									</td>	
																						 <?php	}
																							}else{?>
																								<script>
																						 		$(document).ready(function(){
																									$("#<?php echo $key.$cont;?>").select2({width : "100px"});
																								});
																						 	</script>
																								<td class="" align="center">
																							 		<select id="<?php echo $key.$cont;?>" class="" style='width: 100px;text-overflow: ellipsis;'> 
																										<?php echo $cuentaparaimpuest; ?>
																									</select>
																								</td>	
																								
																						<?php } ?>
																						<td align="center">0.00</td>
																					    <td align="center" id="total<?php echo $key; ?>" name="total<?php echo $key; ?>"><?php echo number_format(floatval($value),2,'.',','); $abonos+=$value; ?></td>
																						<td></td>
																						<td align="center" id="seg<?php echo $key.$cont; ?>" style="display: none">
																					     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																								<?php echo $segmento; ?>
																							</select>
																					     </td>
																					     <td align="center" id="sucu<?php echo $key.$cont; ?>" style="display: none">
																					     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																							<?php echo $sucursal; ?>
																							</select>
																					     </td>
																					     <td colspan="2" id="muestrame<?php echo $key.$cont; ?>"></td>
																						<td></td>
																						</tr>

																					<?php } }//foreach retencion ?>
																							<!-- fin retencion -->

																				</tfoot>
																		</table>
														  			</div>
														  		</div>
														  	</div>
									<?php  					$cont++; 
											  			}//foreach interno
											     	} 
											 	} 
											 	if($_SESSION['comprobante']==4){ 
												  	foreach($_SESSION['poliprove'] as $pro){
														 	foreach($pro as $prove){if(is_array($prove['concepto'])){ $concepto = $prove['concepto'][0];}else{ $concepto = $prove['concepto'];} ?>
														 		<script>
																$(document).ready(function(){
																	$("#cuentaingre<?php echo $cont; ?>,#sucursal<?php echo $cont; ?>,#segmento<?php echo $cont; ?>,#CuentaProveedores<?php echo $cont; ?>").select2({width : "130px"});
																});
																</script>
																<div class="row">
																	<div class="col-md-12">
																		<div class="table-responsive">          
															  				<table class="table datos" id="tabla<?php echo $cont;?>"  >
																				<?php echo $head;?>
																				<tbody>
																				<tr style="background-color:#BDBDBD;color:#585858;font-weight:bold;">
																					<td align="center" width="3px;" colspan="2"><?php echo ($prove['xml']); ?>
																						<input type="hidden" id="hayProrrateo<?php echo $cont; ?>" value="0"/><!-- 0 no 1 si -->
																					</td> 
																					<td align="center" width="1%" colspan="2"><?php echo $prove['referencia']; ?></td>
																					<td>	<input type="button" id="agregar" value="+Prorrateo" title="Agregar Niveles Prorrateo" onclick="porra('tabla<?php echo $cont;?>',<?php echo $cont; ?>,<?php echo number_format($prove['cargo'],2,'.','');?>)"/></td>
																					<td colspan="2"><?php 
																					if(isset($prove['listaprove'])){?>
																						<font color="white" face="Comic Sans MS,arial,verdana">Por favor elija una cuenta para el movimiento del Proveedor.</font>
																						<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
																						<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="actualizaCuentas(<?php echo $cont; ?>)" src="images/reload.png">
																						
																						<select id="CuentaProveedores<?php echo $cont; ?>">
																							<?php echo $cuentaprove; ?>
																						</select>
																			<?php	} ?>
																					</td>
																					<td>
																			   			<img src="images/eliminado.png" title="Eliminar Movimiento"  onclick="borra(<?php echo $cont; ?>,'poliprove');"/>
																					</td>
																				</tr>
																				
																	 			<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" class="porrateo" >
																				 	<td rowspan="1" align="center" style=""><textarea name="conceptoprorrateo<?php echo $cont; ?>[]"  class="conceptospro<?php echo $cont; ?>" style="color: black" id="conceptoprorrateo<?php echo $cont; ?>"><?php echo $concepto;?></textarea></td>
																					<td  class="" align="center">
																						<img style="vertical-align:middle;" title="Abrir Ventana de Cuentas" onclick="iracuenta()" src="images/cuentas.png">
																						<img style="vertical-align:middle;" title="Actualizar Cuentas" onclick="cuentaegresosact(<?php echo $cont; ?>)" src="images/reload.png">
																						<br>		
																						<select id="cuentaingre<?php echo $cont; ?>" name="cuentaingre<?php echo $cont; ?>[]" class="ingrecuenta<?php echo $cont; ?>" >
																							<?php echo $cuentaegre; ?>
																						</select>
																					</td>
																					<td align="center">0.00</td>
																					<td align="center" id="subtotalegre">
																						<label id="montoprorra<?php echo $cont; ?>" name="montoprorra<?php echo $cont; ?>" data-valor="v<?php echo $cont; ?>v0">
																				     	<?php echo number_format(floatval($prove['cargo']),2,'.',','); $abonos+=$prove['cargo'];?>
																				     	</label>
																					</td>
																			 		<td id="input<?php echo $cont; ?>"><input  onkeyup="calculaprorrateo(this.value,<?php echo number_format($prove['cargo'],2,'.',''); ?>,<?php echo $cont;?>,0)" type="text" name="prorrateo<?php echo $cont; ?>[]" id="prorrateo<?php echo $cont; ?>" style="width:80px;display:none;color: black" class="inputtext<?php echo $cont; ?>" align="center" /></td>
																					<td align="center" id="">
																			     		<select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																							<?php echo $segmento; ?>
																						</select>
																			    		</td>
																			    		<td align="center" id="">
																			     		<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																						<?php echo $sucursal; ?>
																						</select>
																			    		</td>
																			   		</tr>
																				</tbody>
																				<tfoot>
																			<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																			<?php if($prove['ieps']>0){ ?>
																				<td></td>
																		<?php if($statusIEPS==1){
																		 	if($statusIVAIEPS==1){ ?>
																				<td align="center">
																				<input type="button" id="ieps" name="ieps<?php $cont;?>" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 100%; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php   echo $iepspendientepago[1]; ?>"/>
																				</td>
																		<?php }else{ ?>
																				<script>
																				$(document).ready(function(){
																					$("#ieps<?php echo $cont; ?>").select2({width : "130px"});
																				});
																				</script>
																				<td class="" align="center">
																				<font color="white" face="Comic Sans MS,arial,verdana">Cuenta para IEPS</font>
																				<select id="ieps<?php echo $cont; ?>" name="ieps<?php echo $cont; ?>" class="">
																					<?php echo $cuentaparaimpuest; ?>
																				</select>
																				</td>
																			<?php }	
																			}else{ ?>
																				<td>
																				<input type="button" id="ieps" name="ieps" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="form-control btn-primary" style="width: 100%; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php  echo $CuentaIEPSgasto[1]; ?>"/>
																				</td>
																		<?php } ?>
																			<td align="center">0.00</td>
																			<td align="center" id="importeiepsegre"><?php echo number_format($prove['ieps'],2,'.',','); $abonos+=$prove['ieps']; ?></td>
																			<td></td>
																			<td align="center" id="segieps<?php echo $cont; ?>" style="display: none">
																		     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																					<?php echo $segmento; ?>
																				</select>
																		     </td>
																		     <td align="center" id="sucuieps<?php echo $cont; ?>" style="display: none">
																		     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																				<?php echo $sucursal; ?>
																				</select>
																		     </td>
																		     <td colspan="2" id="muestrameieps<?php echo $cont; ?>"></td>
																			<td></td>
																	<?php } ?>

																		</tr>
																		<!-- ISH -->
																	<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																	<?php if($prove['ish']>0){ ?>
																		<td></td>
																		<td class="" align="center"><!-- Cuenta para ISH -->
																		<?php if($statusRetencionISH==1){ ?>
																				<input type="button" id="ish" name="ish" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 100%; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php   echo $ishh[1]; ?>"/> 					
																		<?php }else{?>
																				<script>
																					$(document).ready(function(){
																						$("#ish<?php echo $cont;?>").select2({width : "130px"});
																					});
																				</script>
																				<font color="white" face="Comic Sans MS,arial,verdana">Cuenta para ISH </font>
																				<select id="ish<?php echo $cont;?>" name="ish<?php echo $cont;?>" class="" style='width: 100px;text-overflow: ellipsis;' >
																					<?php echo $cuentaparaimpuest; ?>
																				</select>
																		<?php } ?>
																		</td>
																		<td align="center">0.00</td>
																		<td align="center" id="importeishegre"><?php echo number_format(floatval($prove['ish']),2,'.',','); $abonos+=$prove['ish']; ?></td>
																		<td></td>
																		<td align="center" id="segish<?php echo $cont; ?>" style="display: none">
																	     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																				<?php echo $segmento; ?>
																			</select>
																    		 </td>
																   		<td align="center" id="sucuish<?php echo $cont; ?>" style="display: none">
																	     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																			<?php echo $sucursal; ?>
																			</select>
																     	</td>
																     	<td colspan="2" id="muestrameish<?php echo $cont; ?>"></td>
																		<td></td>
																	<?php } ?>
																		</tr>
																		<!--FIN  ISH -->
																<?php 

																if($prove['cargo2']>0){ ?>
																		<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																				<td></td>
																			<?php
																			if($statusIVA==1){
																			 if($statusIVAIEPS==1){ ?>
																				<td   align="center">
																				<input type="button" id="ivaingre" name="ivaingre<?php echo $cont; ?>" title="Ir a asignacion de cuentas" onclick="mandaasignarcuenta();" class="form-control btn-primary" style="width: 100%; white-space: normal; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php   echo $ivapendientepago[1]; ?>"/>
																				</td>
																			<?php }else{?>
																					<script>
																						$(document).ready(function(){
																							$("#ivaingre<?php echo $cont; ?>").select2({width : "130px"});
																						});
																					</script>
																					<td  class="" align="center">
																						<font color="white" face="Comic Sans MS,arial,verdana">IVA Pendiente de Pago</font>
																						<select id="ivaingre<?php echo $cont; ?>" name="ivaingre<?php echo $cont; ?>" class="">
																							<?php echo $cuentaparaimpuest; ?>
																						</select>
																					</td> 
																			<?php }
																		}else{ ?>
																			<td>
																			<input type="button" id="ivaingre" name="ivaingre" title="Ir a asignacion de cuentas"  onclick="mandaasignarcuenta();"  class="form-control btn-primary" style="width: 100%; font-size: 10px; padding-top: 2px; white-space: normal;" value="<?php  echo $CuentaIVAgasto[1]; ?>"/>
																			</td>
																	<?php } ?>
																			<td align="center">0.00</td>
																			<td align="center" id="importeegre"><?php if($prove['cargo2']){echo number_format($prove['cargo2'],2,'.',',');}else{ echo 0;} $abonos+=$prove['cargo2']; ?></td>
																			<td></td>
																			<td align="center" id="segiva<?php echo $cont; ?>" style="display: none">
																		     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																					<?php echo $segmento; ?>
																				</select>
																		     </td>
																		     <td align="center" id="sucuiva<?php echo $cont; ?>" style="display: none">
																		     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																				<?php echo $sucursal; ?>
																				</select>
																		     </td>
																		     <td colspan="2" id="muestrameiva<?php echo $cont; ?>"></td>
																			<td></td>
																		<?php } ?> 
																		<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																			<td rowspan="1" align="center"></td>
																			<td  class="" align="center"><?php echo ($prove['nombre']); ?></td>
																			<td align="center" id="totalegre<?php echo $cont; ?>"><?php echo number_format(floatval($prove['abono']),2,'.',','); $cargos+=$prove['abono'];?></td>
																			<td align="center">0.00</td>
																			<td></td>
																			<td align="center" id="segnombre<?php echo $cont; ?>" style="display: none">
																		     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																					<?php echo $segmento; ?>
																				</select>
																		     </td>
																		     <td align="center" id="sucunombre<?php echo $cont; ?>" style="display: none">
																		     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																				<?php echo $sucursal; ?>
																				</select>
																		     </td>
																			<td></td>
																		</tr>
																<!-- retencion -->
																		<?php foreach ( $prove['retenidos'] as $key => $value){
																			if($value>0){ ?>
																		<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'">
																				 	<td rowspan="1" align="center">Cuenta para <?php echo $key; ?> </td>
																				 	
																				 	<?php 
																				 	if($statusRetencionISH==1){
																				 		
																				 		if($key=="IVA"){ ?>
																					 		<td class="" align="center">
																								<input type="button" id="IVA" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="form-control" style="width: 100%" value="<?php  echo $ivaretenido[1]; ?>"/>
																							</td>	
																				 <?php	} 
																				 		if($key=="ISR"){?>
																					 		<td class="" align="center">
																								<input type="button" id="ISR" name="<?php echo $key.$cont;?>" onclick="mandaasignarcuenta();" class="form-control" style="width: 100%" value="<?php   echo $isrretenido[1]; ?>"/>
																							</td>	
																				 <?php	}
																					}else{?>
																						<script>
																				 		$(document).ready(function(){
																							$("#<?php echo $key.$cont;?>").select2({width : "100px"});
																						});
																				 	</script>
																						<td class="" align="center">
																					 		<select id="<?php echo $key.$cont;?>" class="" style='width: 100px;text-overflow: ellipsis;'> 
																								<?php echo $cuentaparaimpuest; ?>
																							</select>
																						</td>	
																						
																				<?php } ?>
																			    <td align="center" id="total<?php echo $key; ?>" name="total<?php echo $key; ?>"><?php echo number_format(floatval($value),2,'.',','); $cargos+=$value; ?></td>
																				<td align="center">0.00</td>
																				<td></td>
																				<td align="center" id="seg<?php echo $key.$cont; ?>" style="display: none">
																			     	 <select name='segmento<?php echo $cont; ?>[]' id='segmento<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="segme<?php echo $cont;?>">
																						<?php echo $segmento; ?>
																					</select>
																			     </td>
																			     <td align="center" id="sucu<?php echo $key.$cont; ?>" style="display: none">
																			     	<select name='sucursal<?php echo $cont; ?>[]' id='sucursal<?php echo $cont; ?>' style='width: 100px;text-overflow: ellipsis;'  class="sucu<?php echo $cont;?>">
																					<?php echo $sucursal; ?>
																					</select>
																			     </td>
																			     <td colspan="2" id="muestrame<?php echo $key.$cont; ?>"></td>
																				<td></td>
																				</tr>

																			<?php } }//foreach retencion ?>
																					<!-- fin retencion -->

																				</tfoot>
																			</table>
															  			</div>
															  		</div>
															  	</div>
										 <?php  $cont++; 	}//foreach interno
											     	} 
											}  // TERMINA COMPORBANTE 4 IGUAL A EGRE PERO INVERTIDO MOVI ?>
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
						<button class="btn btn-primary btnMenu" id="agregaprevio" onclick="antesdeguardar(<?php echo $cont; ?>);">Agregar Poliza</button>
						<button class="btn btn-primary btnMenu" id="agrega" onclick="guardaprovimultiple();" style="display: none;">Agregar Poliza</button>
					</div>
					<div class="col-md-3">
						<button class="btn btn-danger btnMenu" id="cancela" onclick="cancela();">Cancelar Poliza</button>
					</div>
				</div>
			</section>
			<div id="almacen" class="modal fade" tabindex="-1" >
			  	<div class="modal-dialog modal-lg" style="width: 80%">
			    	<div class="modal-content" >
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

    $("#abono").html('<?php echo number_format($abonos,2,'.',','); ?>');
    $("#cargo").html('<?php echo number_format($cargos,2,'.',','); ?>');
    $("#dife").html('<?php echo number_format(abs($cargos - $abonos),2,'.',','); ?>');

	<?php if(isset($_SESSION['comprobante'])){?>
			$("#comprobante").val(<?php echo $_SESSION['comprobante']; ?>);
		<?php } 
		if(isset($_SESSION['provisioncliente']) || isset($_SESSION['poliprove'])){?>
			$("#comprobante").attr("disabled",true);
		<?php } ?>
		function sele(){
			$("#comprobante").attr("disabled",false);
			if($("#comprobante").val()==0){
				alert("Debe eligir un comprobante");
				return false;
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