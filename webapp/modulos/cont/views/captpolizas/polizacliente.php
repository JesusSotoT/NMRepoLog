<!DOCTYPE html>
	<head>
		<meta charset="UTF-8" />
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/clipolizas.css"/>
		<?php include('../../netwarelog/design/css.php');?>
		<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
		<script src="js/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
		<script type="text/javascript" src="js/cobroclientes.js"></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<script type="text/javascript" src="js/sessionejer.js"></script>
	<?php 
if(isset($_COOKIE['ejercicio'])){
	$NombreEjercicio = $_COOKIE['ejercicio'];
	$v=1;
}else{
	$NombreEjercicio = $Ex['NombreEjercicio'];
	$v=0;
}
if(isset($_SESSION['tabla'])){

	foreach($_SESSION['tabla'] as $cli){
		foreach($cli as $prove){
			if(isset($prove['formapago'])){
				$formap=$prove['formapago'];
			}
			if(isset($prove['numeroformapago'])){
				$numero = $prove['numeroformapago'];
			}
			if(isset($prove['banco'])){
				$idbanco = $prove['banco'];
			}
		}
	}
}

?>
<script>
	dias_periodo(<?php echo $NombreEjercicio; ?>,<?php echo $v; ?>);
	function pagoss(che,MontoParcial){
		if($('#'+che).is(":checked")) {
			$('#impor'+che).show();
			$("#impor2"+che).show();
			$("#iva"+che).show();
			$("#iva2"+che).show();
			$("#ieps"+che).show();
			$("#ieps2"+che).show();
			$("#imporintro"+che).hide();
			$("#imporintro2"+che).hide();
			$("#ivacobrado"+che).hide();
			$("#ivapendiente"+che).hide();
			$("#ipendiente"+che).hide();
			$("#icobrado"+che).hide();
			//input
			$("#imporinput"+che).val("0.00");
			$("#imporinput2"+che).val("0.00");
			$("#ivacobradoinput"+che).val("0.00");
			$("#ivapendienteinput"+che).val("0.00");
			$("#ipendienteinput"+che).val("0.00");
			$("#icobradoinput"+che).val("0.00");
			//select
			//$("#ivapendientecobro"+che).val(0);
			//$("#iepspendiente"+che).val(0);
			//$("#ivacobro"+che).val(0);
			//$("#iepscobro"+che).val(0);
			 
		}else{
			$("#impor"+che).hide();
			$("#impor2"+che).hide();
			$("#iva"+che).hide();
			$("#iva2"+che).hide();
			$("#ieps"+che).hide();
			$("#ieps2"+che).hide();
			$("#imporintro"+che).show();
			$("#imporintro2"+che).show();
			$("#ivacobrado"+che).show();
			$("#ivapendiente"+che).show();
			$("#ipendiente"+che).show();
			$("#icobrado"+che).show();
			
		}
		if(MontoParcial){ 
		 		$("#ipendiente"+che+",#icobrado"+che).show();
				$("#ipendienteinput"+che+",#icobradoinput"+che).val("0.00");
				$("#ieps"+che+",#ieps2"+che).hide();
				$("#ivacobrado"+che+",#ivapendiente"+che).show();
				$("#ivacobradoinput"+che+",#ivapendienteinput"+che).val("0.00");
				$("#iva2"+che+",#iva"+che).hide();
		 	}
	}
	function antesdeguardar(cont){
		var i=0; var status=0;
	  	for(i;i<cont;i++){
	
	   <?php	 if($statusIVAIEPS==0){ ?>
		  		if( ($("#ivapendientecobro"+i).val()==0 || $("#ivacobro"+i).val()==0 )){
		  			alert("Elija una cuenta de IVA!!"); return false;
		  		}
		  <?php if($statusIEPS==1){ ?>
			  		if( ($("#iepspendiente"+i).val()==0 || $("#iepscobro"+i).val()==0 )){
			  			alert("Elija una cuenta de IEPS!!"); return false;
			  		}
			<?php } 
	  	 }else { ?>
			  	if($("#ivapendientecobro").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA Trasladado Pendiente de cobro");  return false;}
				if($("#ivacobro").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA Trasladado Cobrado");  return false;}
		<?php if($statusIEPS==1){ ?>	
				if($("#iepscobro").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IEPS Trasladado Cobrado");  return false;}
				if($("#iepspendiente").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IEPS Trasladado Pendiente de cobro");  return false;}
		<?php  }
		} 
		?>
		
		  	 $.post('index.php?c=CaptPolizas&f=guardanewvalores',{
	  			cont : i,
		  		imporinput : $("#imporinput2"+i).val(),//import
				ivacobradoinput : $("#ivacobradoinput"+i).val(),//iva 
				ipendienteinput : $("#ipendienteinput"+i).val(),//ieps
				idclien : $("#idcli"+i).val(),//valor para almacenar en array
				
				ivapendiente : $("#ivapendientecobro"+i).val(),//cuenta
				ivacobrado : $("#ivacobro"+i).val(),
				iepspendiente : $("#iepspendiente"+i).val(),
				iepscobro : $("#iepscobro"+i).val(),
				
				array:"tabla"
			 },function(resp){
	  			status+=1;
	  			
	  			if(status==cont ){
		 			$("#agrega").click();
				}
	  		 });
  		
	 	}
	 	
	 	
  }		 	
 $(document).ready(function(){
	 $('#periodomanual').val($('#Periodo').val());
});
  
 
			</script>
	<style>
		.datos{
			font-size:12px;
			font-weight:bold; 
			color:#6E6E6E;
			width: 40%;
			height:190px;
			vertical-align:middle;
			display:inline;
			margin:0;
		}
		.dat{
			width: 100%;
			margin:0;
			border:0;
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
		td{
			border: medium none !important;
		}
		#s2id_xml{
			width: 90% !important;
		}
		input[type="checkbox"]{
			margin-right: 1em !important;
		}
		.tdt{
			background-color: #eee !important;
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
								Cobros a Clientes
								<a href='index.php?c=CaptPolizas&f=filtroAutomaticas&t=cobro' onclick="" id='filtros'>
									<img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'>
								</a>
							</h3>
						</div>
					</div>
					<form method="post" ENCTYPE="multipart/form-data" action="index.php?c=CaptPolizas&f=tabla" onsubmit='return validacli(this)'>
						<section>
							<h4>Seleccionar xml</h4>
							<div class="row">
								<div class="col-md-12">
									<input type="radio" class="nminputradio" name="radio" id="radio" value="1" onclick="checa()" checked=""/>
									<select id="xml" name="xml[]"  multiple="" style="width: 90% !important;">
										<?php
										global $xp;
										$directorio=opendir($this->path().'xmls/facturas/temporales'); 
										while ($archivo = readdir($directorio)){
											$solocobros = strpos($archivo, "Cobro");
											if($archivo != '.' && $archivo != '..' && $archivo != '.file' && $archivo !='.DS_Store'){
												if($solocobros==true){
													$file 	= $archivo;
													$texto 	= file_get_contents($this->path().'xmls/facturas/temporales/'.$file);
													$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
													$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
													$xml 	= new DOMDocument();
													$xml->loadXML($texto);
													$xp = new DOMXpath($xml);
													$moneda 	=strtoupper( $this->getpath("//@Moneda"));
													
													
												if($moneda=="PESO MXN" || $moneda=="MXN" || $moneda=="PESO MEXICANO" || $moneda=="MN" || $moneda=="MXP" || $moneda=="PESOS" || $moneda=="PESO" || $moneda=="M.N." || $moneda=="M.X.N." || $moneda=="PESOS MEXICANOS" || !($moneda)){
														echo  '<option value="'.htmlentities($archivo).'">'.($archivo).'</option>';
													}
												}
											}
										}
							  			closedir($directorio); 
										?>
									</select>
								</div>
							</div>
						</section>
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
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<div class="col-md-6">
									<h4>Cuentas</h4>
									<div class="row">
										<div class="col-md-6">
											<label>Banco:<?php echo @$bancosno; ?></label></br>
											<select id="banco" name="banco">
												<?php 
													if(isset($bancos)){
														while($b=$bancos->fetch_array()){$f="";
															if($b["account_id"].'/'. $b['description']==$idbanco){$f="selected";  }?> 
												
															<option value='<?php echo $b["account_id"].'/'. $b['description']; ?>' <?php echo $f; ?> > <?php echo $b['description']."(".$b["manual_code"].")"; ?> </option>
												<?php 	
														} 
													} 
												?>
											</select>
										</div>
										<div class="col-md-6">
											<label>Cliente:<?php echo @$clientesno; ?></label></br>
											<select id="cliente" name="cliente" onclick="validacuenta()">
												<option value="0">--Elija un cliente--</option>
												<?php 
													if(!$clientesno){
														while($b=$clientes->fetch_array()){ $nombrecl = str_replace('/', ' ', $b['nombre']);$nombrecl=str_replace('-', ' ', $nombrecl); ?>
															<option value='<?php echo $b["id"].'/'.$nombrecl  ?>'><?php echo ($b['nombre']); ?> </option>					
												<?php  }
													}
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Forma de pago:</label>
											<select id="formapago" name="formapago" class="form-control">
								 	
												<?php mb_internal_encoding("UTF-8");
												while($f = $forma_pago->fetch_array()){$sd = "";
													if(($f['idFormapago'])==$formap){  $sd = "selected";} 
													//Si es la claveSat 98 se cambiara por NA
											 		if($f['claveSat'] == '98') { ?>
														<option value="<?php echo $f['idFormapago'];?>" <?php echo $sd;?>>
															<?php echo"(".$f['claveSat'].") NA";?>
														</option>
													<!-- Si cumple con las siguientes caracteristicas no se va a mostrar -->
													<?php } else if(
														($f['nombre'] == 'Cortesia') || 
														($f['nombre'] == 'Credito') || 
														($f['nombre'] == 'Crédito') || 
														($f['claveSat'] == '28') || 
														($f['claveSat'] == '29') || 
														(($f['claveSat'] == '99') && ($f['nombre'] !== 'Otros')) || 
														($f['claveSat'] == 'NA')
													){ ?>
													<!-- Si es la claveSat 07 se cambiara a tarjeta digital -->
													<?php } else if($f['claveSat'] == '07') { ?>
														<option value="<?php echo $f['idFormapago'];?>" <?php echo $sd;?>>
															<?php echo"(".$f['claveSat'].") TARJETAS DIGITALES";?>
														</option>
													<!-- si no, imprimir de forma normal -->
											 		<?php } else { ?>
												 		<option value="<?php echo $f['idFormapago'];?>" <?php echo $sd;?>>
															<?php echo "(".$f['claveSat'].") ".mb_strtoupper($f['nombre']); ?>
														</option>
											 		<?php	}
														
													} ?>
													
															</select>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label>Numero:</label>
														<input size="20" id="numeroformapago" name="numeroformapago" value="<?php echo $numero;?>" class="form-control" type="text">
													</div>
												</div>
										 </div>
								</div>
								<div class="col-md-6" id="muestra" style="display: none;">
									<h4>Cuenta</h4>
									<select class="" id="cuentacliente" name="cuentacliente" onclick="agregacuenta()">
										<option value="0">Elija una cuenta</option>
										<?php 
										if(!$clientesno){
											while($cli2=$cuentasinarbol->fetch_array()){ ?>
													<option value='<?php echo $cli2["account_id"]; ?>'><?php echo ($cli2['description']."(".$cli2['manual_code'].")"); ?> </option>
										<?php	}
										}?>
									</select>
									<input type="hidden"  value="0" id="clientesincuenta" name="clientesincuenta"/>
								</div>
								<div class="col-md-6">
									<h4>Datos de registro</h4>
									<div class="row">
										<div class="col-md-6">
											<label>Segmento del negocio:</label>
											<select name='segmento' id='segmento' style='text-overflow: ellipsis;'  class="form-control">
												<?php
												while($LS = $ListaSegmentos->fetch_assoc())
												{
													echo "<option value='".$LS['idSuc']."//".$LS['nombre']."'>".$LS['nombre']."</option>";
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
													echo "<option value='".$LS['idSuc']."//".$LS['nombre']."'>".$LS['nombre']."</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<label>Concepto:</label>
											<input type="text" class="form-control" placeholder="Concepto..." id="concepto" name="concepto"/>
										</div>
										<div class="col-md-6">
											<label>Fecha de poliza:</label>
											<?php if(isset($_SESSION['fechacli'])){ ?>
												<input  type="date" class="form-control" id="fecha" onmousemove="javascript:fechadefault()" name="fecha" value="<?php echo $_SESSION['fechacli']; ?>" />
											<?php }else{ ?>
												<input  type="date" class="form-control" id="fecha" onmousemove="javascript:fechadefault()" name="fecha"/>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<div class="col-md-2">
									<button type="submit" class="btn btn-primary btnMenu" id="agregar">Leer XML's</button>
								</div>
								<div class="col-md-7">
									<input type="checkbox" value="0" id="unsolobanco" onclick="unSoloBanco()"/><b style="color:red;font-size: 17px">Un solo Cargo a Bancos.</b>
								</div>
							</div>
						</section>
					</form>
					<section id="movimientos">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">          
					  				<table class="table" id="datos" align="center" cellpadding="2" border="0" style="border: white 1px solid;">
										<thead>
											<tr>
												<td class="nmcatalogbusquedatit tdt" align="center">Cliente</td>
												<td class="nmcatalogbusquedatit tdt" align="center">Cuentas</td>
												<td class="nmcatalogbusquedatit tdt" align="center">Cargo</td>
												<td class="nmcatalogbusquedatit tdt" align="center">Abono</td>
												<td class="nmcatalogbusquedatit tdt" align="center">XML</td>
												<td class="nmcatalogbusquedatit tdt" align="center">Segmento</td>
												<td class="nmcatalogbusquedatit tdt" align="center">Sucursal</td>
												<td class="nmcatalogbusquedatit tdt" align="center"></td>
											</tr>
											<!--<tr><td colspan="7"><hr></hr></td></tr>-->
										</thead>
											<tbody><?php 
												 //echo count($_SESSION['tabla']);
												
											$cont = $totalbancos = 0;
											
											//echo $_SESSION['tabla'][2]['101-CLIENTETEST']['cliente'];
												 foreach($_SESSION['tabla'] as $cli){
												 	//echo count($cli);
													foreach($cli as $cliente){
													if(strrpos($cliente['cliente'],'/')){
														 $clie=explode('/',$cliente['cliente']); 
													}else{
													 $clie=explode('-',$cliente['cliente']); 
													} 
													$totalbancos += $cliente['importe'];
													
													$segment = explode('//',$cliente['segmento']);
													$sucu = explode('//',$cliente['sucursal']);
												
													?>
									
									 <tr class="trpagototal"><td colspan="7"><hr></hr>
									 	<input type="checkbox"  checked="" id="<?php echo $cont; ?>" onclick="pagoss(<?php echo $cont; ?>,<?php echo $cliente['MontoParcial']; ?>)"/>Pago Total</td>
									 </tr>
										 <tr>
										 	<input type="hidden" value="<?php echo $cliente['cliente']; ?>" id="idcli<?php echo $cont; ?>"/>
											 <td rowspan="2" align="center"><b><?php echo ($clie[1]); ?></b><br><?php echo $cliente['concepto']; ?></td>
											 <td  class="nmcatalogbusquedatit" align="center">Clientes</td>
											 <td align="center">0.00</td>
											 <td align="center" id="impor<?php echo $cont; ?>"><?php echo number_format($cliente['importe'],2,'.',','); ?> </td>
											  <td align="center" style="display: none" id="imporintro<?php echo $cont; ?>" ><input type="text" placeholder="0.00" value="0.00" id="imporinput<?php echo $cont; ?>" disabled/></td>
											 <td></td>
											 <td align="center"><?php echo $segment[1]; ?></td>
											 <td align="center"><?php echo $sucu[1]; ?></td>
											 <td align="center"><?php echo ($cliente['xml']); ?></td>
						 					 <td><img src="images/eliminado.png" title="Eliminar Movimiento"  onclick="borra(<?php echo $cont; ?>);"/></td>

										 </tr>
										 <tr class="trbancos">
											 <td  class="nmcatalogbusquedatit" align="center">Bancos</td>
											 <td align="center" id="impor2<?php echo $cont; ?>"><?php echo number_format($cliente['importe'],2,'.',','); ?></td>
											 <td align="center" style="display: none" id="imporintro2<?php echo $cont; ?>"><input  type="text" placeholder="0.00" value="0.00" id="imporinput2<?php echo $cont; ?>" onkeyup="calculaIVAIEPS('imporinput',<?php echo $cont; ?>)" /></td>
											 <td align="center">0.00</td>
											 <td colspan="4"></td>
										 </tr>
										 <?php 
										 if($statusIVA==1){
										 if($cliente['IVA']>0){ //pato?>
										 	<script>
										 	
										 	$(document).ready(function(){
										 		$("#ivapendientecobro<?php echo $cont ?>,#ivacobro<?php echo $cont ?>").select2({
						        					 width : "150px"
						       					 });
						       					 <?php 
										 	if($cliente['MontoParcial']){?>
										 		$("#ivacobrado<?php echo $cont; ?>,#ivapendiente<?php echo $cont; ?>").show();
												$("#ivacobradoinput<?php echo $cont; ?>,#ivapendienteinput<?php echo $cont; ?>").val(<?php echo $cliente['IVA']; ?>);
												$("#iva2<?php echo $cont; ?>,#iva<?php echo $cont; ?>").hide();
										 	<?php } ?>
											});
						      
										 	</script>

										 	<tr>
										 		<td colspan=""  class="classiva"></td>
										 			<?php if($statusIVAIEPS==1){?>
										 				<td  class="nmcatalogbusquedatit" align="center"><!-- IVA pendiente de cobro -->
															<input type="button" id="ivapendientecobro" title="Ir a asignacion de cuentas" name="ivapendientecobro" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" value="<?php  echo $ivapendientecobro[1];?>">
														</td>
													<?php }else{?>
														<td  class="" align="center">IVA pendiente de cobro
										 					<select id="ivapendientecobro<?php echo $cont; ?>" class="nmcatalogbusquedatit" >
										 						<option value="0">--Elija una cuenta--</option>
													 			<?php echo $listadoivaieps; ?>
										 					</select>
										 				</td>
													<?php } ?>
												
										 		<td align="center" id="iva<?php echo $cont; ?>" ><?php echo number_format($cliente['IVA'],2,'.',','); ?></td>
										 		<td align="center" id="ivapendiente<?php echo $cont; ?>" style="display: none"><input type="text" value="0.00" placeholder="0.00" id="ivapendienteinput<?php echo $cont; ?>" disabled/></td>
										 		<td align="center">0.00</td>
										 	</tr>
										 	<tr>
										 		<td colspan=""></td>
										 		<?php if($statusIVAIEPS==1){?>
										 		<td  class="nmcatalogbusquedatit" align="center"><!-- IVA Cobrado -->
										 			<input type="button" id="ivacobro" title="Ir a asignacion de cuentas"  name="ivacobro" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" value="<?php  echo $ivacobrado[1];?>">
												</td>
												<?php }else{?>
														<td  class="" align="center">IVA Cobrado
										 					<select style="width : 170px" id="ivacobro<?php echo $cont; ?>" class="nmcatalogbusquedatit" >
										 						<option value="0">--Elija una cuenta--</option>
													 			<?php echo $listadoivaieps; ?>
										 					</select>
										 				</td>
													<?php } ?>
										 		<td align="center">0.00</td>
										 		<td align="center" id="iva2<?php echo $cont; ?>"><?php echo number_format($cliente['IVA'],2,'.',','); ?> </td>
										 		<td align="center" id="ivacobrado<?php echo $cont; ?>" style="display: none"><input type="text" value="0.00" placeholder="0.00" id="ivacobradoinput<?php echo $cont; ?>" onkeyup="rellena('ivapendienteinput<?php echo $cont; ?>','ivacobradoinput<?php echo $cont; ?>')"/></td>

										 	</tr>
										 <?php }
										 }
										if($statusIEPS==1){ 
										 	 if($cliente['IEPS']>0){ ?>
										  	<script>
										  	
										  	$(document).ready(function(){
										 		$("#iepspendiente<?php echo $cont ?>,#iepscobro<?php echo $cont ?>").select2({ width : "150px" });
						      				<?php 
										 	if($cliente['MontoParcial']){?> 
										 		$("#ipendiente<?php echo $cont; ?>,#icobrado<?php echo $cont; ?>").show();
												$("#ipendienteinput<?php echo $cont; ?>,#icobradoinput<?php echo $cont; ?>").val(<?php echo $cliente['IEPS']; ?>);
												$("#ieps<?php echo $cont; ?>,#ieps2<?php echo $cont; ?>").hide();

										 	<?php } ?>
						      				});
										 	</script>
										 	<tr>
										 		<td colspan="" class="classieps"></td>
										 		<?php if($statusIVAIEPS==1){?>
										 		<td  class="nmcatalogbusquedatit" align="center"><!-- IEPS pendiente de cobro  -->
										 			<input type="button" id="iepspendiente" title="Ir a asignacion de cuentas"  name="iepspendiente" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" value="<?php  echo $iepspendientecobro[1];?>">
										 		</td>
										 		<?php }else{?>
										 			<td  class="nmcatalogbusquedatit" align="center">IEPS pendiente de cobro
										 			<select id="iepspendiente<?php echo $cont; ?>">
										 				<option value="0">--Elija una cuenta--</option>
										 				<?php echo $listadoivaieps; ?>
										 			</select>
										 			</td>
										 		<?php } ?>
										 		<td align="center" id="ieps<?php echo $cont; ?>"><?php echo number_format($cliente['IEPS'],2,'.',','); ?></td>
										 		<td align="center" id="ipendiente<?php echo $cont; ?>" style="display: none"><input type="text" value="0.00" placeholder="0.00" id="ipendienteinput<?php echo $cont; ?>" disabled/></td>
										 		
										 		<td align="center">0.00</td>
										 	</tr>
										 	<tr>
										 		<td colspan=""></td>
										 		<?php if($statusIVAIEPS==1){?>
										 		<td  class="nmcatalogbusquedatit" align="center"><!-- IEPS Cobrado -->
										 			<input type="button" id="iepscobro" title="Ir a asignacion de cuentas"  name="iepscobro" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" value="<?php  echo $iepscobrado[1];?>">
										 		</td>
										 		<?php }else{?>
										 			<td  class="nmcatalogbusquedatit" align="center">IEPS Cobrado
										 			<select id="iepscobro<?php echo $cont; ?>">
										 				<option value="0">--Elija una cuenta--</option>
										 				<?php echo $listadoivaieps; ?>
										 			</select>
										 			</td>
										 		<?php } ?>
										 		<td align="center">0.00</td>
										 		<td align="center" id="ieps2<?php echo $cont; ?>"><?php echo number_format($cliente['IEPS'],2,'.',','); ?></td>
										 		<td align="center" id="icobrado<?php echo $cont; ?>" style="display: none"><input type="text" value="0.00" placeholder="0.00" id="icobradoinput<?php echo $cont; ?>" onkeyup="rellena('ipendienteinput<?php echo $cont; ?>','icobradoinput<?php echo $cont; ?>')"/></td>

										 	</tr>
										 <?php } 
										 }?>
										 <tr><td colspan="7"><hr></hr></td></tr>
									
										<?php	$cont++; }
										 } ?>
										 <tr class="trUnsoloBanco" style="display: none">
									 		<td></td>
									 		<td align="center" ><b style="font-size: 17px;">Bancos</b></td>
									 		<td align="center" ><b style="font-size: 17px;"><?php echo number_format($totalbancos,2,'.',',');?></b></td>
									 		<td align="center" ><b style="font-size: 17px;">0.00</b></td>
									 	</tr>
										 </tbody>	
									</table>
					  			</div>
					  		</div>
					  	</div>
					</section>
					<section>
						<div class="row">
							<div class="col-md-4">
							</div>
							<div class="col-md-4">
								<img src="images/loading.gif" style="display: none" id="load">
							</div>
							<div class="col-md-4">
								<button class="btn btn-primary btnMenu" id="agrega" onclick="guarda();" style="display: none">Agregar poliza</button>
								<button class="btn btn-primary btnMenu" id="agregaprevio" onclick="antesdeguardar(<?php echo $cont; ?>);">Agregar poliza</button>
							</div>
						</div>
					</section>
				</div>
				<div class="col-md-1">
				</div>
			</div>
		</div>
			
		<?php if(isset( $_COOKIE['ejercicio'])){ ?>
			<input type="hidden" value="<?php echo $_COOKIE['ejercicio']; ?>" id="ejercicio" name="ejercicio">
			<input type="hidden" value="<?php echo $_COOKIE['periodo']; ?>" id="idperiodo" name="idperiodo">	
		<?php }else{ ?>
			<input type="hidden" value="<?php echo $ejercicio; ?>" id="ejercicio" name="ejercicio">
			<input type="hidden" value="<?php echo $idperiodo; ?>" id="idperiodo" name="idperiodo">	
		<?php } ?>

	</body>
</html>