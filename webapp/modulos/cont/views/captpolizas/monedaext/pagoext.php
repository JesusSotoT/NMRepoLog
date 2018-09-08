<!DOCTYPE html>
	<head>
				        <meta charset="UTF-8" />
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/clipolizas.css"/>
		<script type="text/javascript" src="js/pagoext.js"></script>
		<script src="js/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<script type="text/javascript" src="js/sessionejer.js"></script>
<style>.me {background: #BDBDBD;}</style>
		
<?php
if(isset($_COOKIE['ejercicio'])){
	$NombreEjercicio = $_COOKIE['ejercicio'];
	$v=1;
}else{
	$NombreEjercicio = $Ex['NombreEjercicio'];
	$v=0;
}
?>
<script>
	dias_periodo(<?php echo $NombreEjercicio; ?>,<?php echo $v; ?>);
	function pagoss(che,MontoParcial){
		if($('#'+che).is(":checked")) {
			$('#impor'+che+',#impormxn'+che).show();
			$("#impor2"+che+',#impor2mxn'+che).show();
			$("#iva"+che+',#ivamxn'+che).show();
			$("#iva2"+che+',#iva2mxn'+che).show();
			$("#ieps"+che+',#iepsmxn'+che).show();
			$("#ieps2"+che+',#ieps2mxn'+che).show();
			
			$("#imporintro"+che+",#impormxnintro"+che).hide();
			$("#imporintro2"+che+",#impor2mxnintro"+che).hide();
			
			
			$("#ivacobrado"+che+",#iva2mxnintro"+che).hide();
			$("#ivapendiente"+che+",#ivamxnintro"+che).hide();
			$("#ipendiente"+che+",#iepsmxnintro"+che).hide();
			$("#icobrado"+che+",#ieps2mxnintro"+che).hide();
			//input
			$("#imporinput"+che).val("0.00");
			$("#imporinput2"+che).val("0.00");
			$("#ivacobradoinput"+che).val("0.00");
			$("#ivapendienteinput"+che).val("0.00");
			$("#ipendienteinput"+che).val("0.00");
			$("#icobradoinput"+che).val("0.00");
			//select


		}else{
			$('#impor'+che+',#impormxn'+che).hide();
			$("#impor2"+che+',#impor2mxn'+che).hide();
			$("#iva"+che+',#ivamxn'+che).hide();
			$("#iva2"+che+',#iva2mxn'+che).hide();
			$("#ieps"+che+',#iepsmxn'+che).hide();
			$("#ieps2"+che+',#ieps2mxn'+che).hide();
			
			$("#imporintro"+che+",#impormxnintro"+che).show();
			$("#imporintro2"+che+",#impor2mxnintro"+che).show();
			
			
			$("#ivacobrado"+che+",#iva2mxnintro"+che).show();
			$("#ivapendiente"+che+",#ivamxnintro"+che).show();
			$("#ipendiente"+che+",#iepsmxnintro"+che).show();
			$("#icobrado"+che+",#ieps2mxnintro"+che).show();
			
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
		var idformapago = $('#formapago').val().split('/');
		if(idformapago[0]==2){
	  		if($('#numero').val()==""){
	  			alert("La forma de pago en Cheque requiere que proporcione un numero");
	  			$('#numero').css("border-color","red");
	  			return false;
	  		}
	  	}	
	  	for(i;i<cont;i++){
	   <?php if($statusIVAIEPS==0){ 
	  	 		if($statusIVA==1){?>
			  	  	if( ($("#ivapendientepago"+i).val()==0 || $("#ivapago"+i).val()==0 )){
				  			alert("Elija una cuenta de IVA!!"); return false;
				  		}
			<?php }if($statusIEPS==1){ ?>
				  		if( ($("#iepspendiente"+i).val()==0 || $("#iepspago"+i).val()==0 )){
				  			alert("Elija una cuenta de IEPS!!"); return false;
				  		}
			  <?php 	}
			}else {
				if($statusIVA==1){ ?>
				  	if($("#ivapendientepago").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA Acreditable Pendiente de pago");  return false;}
					if($("#ivapago").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IVA Acreditable Pagado");  return false;}
	
			<?php }if($statusIEPS==1){ ?>
						if($("#iepspendiente").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IEPS Acreditable Pendiente de pago");  return false;}
						if($("#iepspago").val()=='ASIGNE CUENTA'){ alert("Elija una cuenta en Asignacion de Cuentas IEPS Acreditable Pagado");  return false;}
		<?php 		}
		} ?>
	  	 $.post('index.php?c=CaptPolizas&f=guardanewvalores',{
  			cont : i,
	  		imporinput : $("#imporinput2"+i).val(),//import
			ivacobradoinput : $("#ivacobradoinput"+i).val(),//iva 
			ipendienteinput : $("#ipendienteinput"+i).val(),//ieps
			idclien : $("#idcli"+i).val(),//valor para almacenar en array
			
			ivapendiente : $("#ivapendientepago"+i).val(),//cuenta
			ivacobrado : $("#ivapago"+i).val(),
			iepspendiente : $("#iepspendiente"+i).val(),
			iepscobro : $("#iepspago"+i).val(),
			
			array:"proveedor"
		 },function(resp){
  			status+=1;
  			
  			if(status==cont ){
	 			$("#agrega").click();
			}
  		 });
  		 
	 	}
	 	//alert(status);alert(cont);
	 	
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
		height:200px;
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
		width: 98.2% !important;
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
	<?php
	$disable = "";
	$idbeneficiario=0;
	$numero = "";
	$rfc ="";
	$numtarje = "";
	$idbanco =0;
	$prove=0;
	$formap="";
	$numeroorigen = "";
	$idbancoorigen = 0;
	$bancocuenta=0;
if(isset($_SESSION['proveedor'])){
		//$disable = "disabled=''";
	
	foreach($_SESSION['proveedor'] as $cli){
		foreach($cli as $prove){
			if(isset($prove['formapago'])){
				$formap=$prove['formapago'];
			}
			if(isset($prove['beneficiario'])){
				 $idbeneficiario = $prove['beneficiario'];
			}
			if(isset($prove['numero'])){
				$numero = $prove['numero'];
			}
			if(isset($prove['rfc'])){
				$rfc =$prove['rfc'];
			}
			if(isset($prove['numtarje'])){
				$numtarje = $prove['numtarje'];
			}
			if(isset($prove['listabanco'])){
				$provebancoid = explode('/', $prove['listabanco']);
				 $idbanco = $provebancoid[0];
				// $idbanco = $prove['listabanco'];
			}
			if(isset($prove['proveedor'])){
				$provee=$prove['proveedor'];
				
			}
			if(isset($prove['numorigen'])){
				$numeroorigen=$prove['numorigen'];
				
			}
			if(isset($prove['listabancoorigen'])){
				$banorigen = $prove['listabancoorigen'];
				
			}
			 if(isset($prove['banco'])){
				
				 $bancocuenta=$prove['banco'];
			}
			
		}
	}
}
if(isset($_SESSION['datospagoext']['bancosxmoneda']) || isset($_SESSION['datospagoext']['cuentaprvxmoneda'])){
	$bancosxmoneda="";
			foreach($_SESSION['datospagoext']['bancosxmoneda'] as $li){ 
				if($bancocuenta == ($li['account_id']."/".$li['description']) ){ $s="selected";  }else{ $s="";}
				$bancosxmoneda.= "<option value='".$li['account_id']."/".$li['description']."' $s>".$li['description']."(".$li['manual_code'].")</option>";
			} 
			$prvcuentas=""; 	
			foreach ($_SESSION['datospagoext']['cuentaprvxmoneda'] as $pr){ 
				if(($pr['account_id'].'-'. $pr['description'])==$provee){ $sel = "selected";}else{$sel="";}
				$prvcuentas .= "<option value='".$pr['account_id']."-".$pr['description']."' $sel>".$pr['description']."(".$pr['manual_code'].")</option>";
			}
			$prv="";
			foreach ($_SESSION['datospagoext']['prvconcuenta'] as $pr){  $razon_social=  str_replace('/', ' ', $b['razon_social']);$razon_social = str_replace('-', ' ', $razon_social); 
				if(($pr['cuenta'].'/'. $pr['idPrv'].'/'. $pr['razon_social'])==$provee){ $se="selected";}else{$se="";} 
				$prv .= "<option value='".$pr['cuenta']."/".$pr['idPrv']."/".$pr['razon_social']."' $se>".$pr['razon_social']."</option>";
			}
}
	?>


	<div class="container">
		<div class="row">
			<div class="col-md-1">
			</div>
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-12">
						<h3 class="nmwatitles text-center">
							Pago a proveedor
							<a  href='index.php?c=CaptPolizas&f=filtroAutomaticas&t=pago' onclick="" id='filtros'>
								<img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'>
							</a>
						</h3>
					</div>
				</div>
				<form method="post" ENCTYPE="multipart/form-data" action="index.php?c=automaticasMonedaExt&f=arraytabla" onsubmit="return validacampos(this)">
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
					<h4>Tipo de cambio</h4>
					<section>
						<div class="row">
							<div class="col-md-4">
								<label>Moneda:</label>
								<select id="moneda" name="moneda" onchange="consultaTipoCambio(this.value)">
									<?php 
										while($m = $moneda->fetch_assoc()){
											if($m['coin_id']==1){?>
												<option value="1">Elija una moneda</option>
									<?php   }else{ 
												$smo=""; 
												if(isset($_SESSION['datospagoext']['moneda'])){
													if($_SESSION['datospagoext']['moneda'] == $m['coin_id']){ $smo="selected"; }else{ $smo="";}
												}
											?>
											<option value="<?php  echo $m['coin_id']?>" <?php echo $smo; ?>><?php echo $m['description']." (".$m['codigo'].")"; ?></option>
									<?php 	}  
									}?>
								</select>
								<div id='consul' style='font-size:12px;color:blue;width:100%;display: none;'> Consultando...</div>
							</div>
							<div class="col-md-4">
								<label>Tipo de cambio:</label>
								<img src="images/intro.png" style="vertical-align:middle;" width="22px" height="22px" id="int" onclick="cambiaintro()" title="Introducir Tipo de Cambio"/>
								<img src="images/dine.jpeg" style="vertical-align:middle;display: none" width="22px" height="22px" id="int2" onclick="listadoin()" title="Seleccionar Tipo de Cambio"/>
								<input class="t2" type="text" id="tipocambio2" name="tipocambio2" placeholder="0.00" style="display: none" class="t2" onkeypress="return numeros(event);">
								<select id="tipocambio" name="tipocambio" class="t1">
									<option value="0">Elija una moneda</option>
									<?php 
									if(!isset($_SESSION['datospagoext']['tipocambiolista']) ){
										while ($row = $lista->fetch_assoc()){ $_SESSION['datospagoext']['codigo']=$row['codigo'];
										if(isset($_SESSION['datospagoext']['tipocambio'])){
											if ($_SESSION['datospagoext']['tipocambio']==$row['tipo_cambio']){ $ti = "selected";}else{ $ti="";}
										}
											echo "<option value='".$row['tipo_cambio']."' $ti>".$row['fecha']." (".$row['tipo_cambio'].")</option>";	
										}
									}else{
										foreach($_SESSION['datospagoext']['tipocambiolista'] as $row){
											if(isset($_SESSION['datospagoext']['tipocambio'])){
												if ($_SESSION['datospagoext']['tipocambio']==$row['tipo_cambio']){ $ti = "selected";}else{ $ti="";}
											}
											echo "<option value='".$row['tipo_cambio']."' $ti>".$row['fecha']." (".$row['tipo_cambio'].")</option>";	
										}	
									}
									 ?>
								</select>
							</div>
							<div class="col-md-4">
								<label>Seleccionar XML:</label>
								<input type="radio" class="nminputradio" name="radio" id="radio" value="1" onclick="checa()" checked="" />
								<select id="xml" name="xml[]" multiple="">
									<?php
									global $xp;
									$directorio=opendir('xmls/facturas/temporales'); 
									while ($archivo = readdir($directorio)){
										$solopagos = strpos($archivo, "Pago");
										if($archivo != '.' && $archivo != '..' && $archivo != '.file' && $archivo !='.DS_Store'){
											if($solopagos==true){
												$file 	= $archivo;
												$texto 	= file_get_contents('xmls/facturas/temporales/'.$file);
												$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
												$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
												$xml 	= new DOMDocument();
												$xml->loadXML($texto);
												$xp = new DOMXpath($xml);
												$moneda 	=strtoupper( $this->getpath("//@Moneda"));
												$monedacambio = $this->getpath("//@TipoCambio");

												if($moneda!="PESO MXN" && $moneda!="MXN" && $moneda!="PESO MEXICANO" && $moneda!="MN" && $moneda!="MXP" && $moneda!="PESOS"  && $moneda!="PESO" && $moneda!="M.N." && $moneda!="M.X.N." && $moneda!="PESOS MEXICANOS" && $monedacambio){
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
					<section>
						<div class="row">
							<div class="col-md-6">
								<h4>Cuentas</h4>
								<div class="row">
									<div class="col-md-6">
										<label>Banco:<?php echo @$bancosno; ?></label>
										<img style="vertical-align:middle;width: 15px;height: 15px" title="Agregar Cuenta Bancaria" id="mandacuentabanca" onclick="mandacuentabancaria()" src="images/mas.png">
										<br>
										<select  id="banco" name="banco" class="nminputselect" style="width: 150px; margin-left: 2%; margin-bottom: 8px;" onchange="cuentabancarias();">
											<option value="0">Elija un cuenta</option>
											<?php if(!isset($bancosxmoneda)){
													  if(isset($bancos)){
														 while($b=$bancos->fetch_array()){ 
														 	if($bancocuenta == ($b["account_id"].'/'. $b['description']) ){ ?>
																<option value='<?php echo $b["account_id"].'/'. $b['description']; ?>' selected><?php echo $b['description']."(".$b["manual_code"].")"; ?> </option>
												<?php 		}else{?>
																<option value='<?php echo $b["account_id"].'/'. $b['description']; ?>'><?php echo $b['description']."(".$b["manual_code"].")"; ?> </option>
												<?php		} 
														 }
													  }
												}else{
													echo $bancosxmoneda;
												}
											?>
										</select>
									</div>
									<div class="col-md-6">
										<label>Proveedor:<?php echo @$proveedoresno; ?></label>
										<img style="vertical-align:middle;width: 15px;height: 15px" title="Agregar Bancos al Prv" onclick='mandabancos()' src="images/mas.png">
										<br>
										<select id="proveedor" name="proveedor" class="nminputselect" style="width: 150px; margin-bottom: 8px;margin-left: 2%;" onchange="beneficiari();" <?php echo $disable; ?>>
											<?php 
											if(!isset($prvcuentas)){
												if(isset($proveedores)){ ?>
													<option value="0" >Elija un proveedor</option>
													
											<?php while($b=$proveedores2->fetch_array()){ $b['description'] = str_replace('/', ' ', $b['description']);$b['description']=str_replace('-', ' ', $b['description']); 
												  		if(($b['account_id'].'-'.$b['description'])==$provee){?>
															<option value="<?php echo $b['account_id'].'-'. $b['description']; ?>" selected><?php echo ($b['description']."(".$b['manual_code'].")"); ?> </option>
											<?php 		}else{ ?>
															<option value="<?php echo $b['account_id'].'-'. $b['description']; ?>" ><?php echo ($b['description']."(".$b['manual_code'].")"); ?> </option>
											<?php	    }
												  } 
												}
											}else{
												echo '<option value="0" >Elija un proveedor</option>';
												//echo $prv;
												echo $prvcuentas;
											}?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<h4>Datos de registro</h4>
								<div class="row">
									<div class="col-md-6">
										<label>Concepto:</label>
										<input type="text"  class="form-control" placeholder="Concepto..." id="concepto" name="concepto" />
									</div>
									<div class="col-md-6">
										<label>Segmento de negocio:</label>
										<select name='segmento' id='segmento' style='text-overflow: ellipsis;'  class="form-control">
											<?php
											while($LS = $ListaSegmentos->fetch_assoc())
											{
												echo "<option value='".$LS['idSuc']."//".$LS['nombre']."'>".$LS['nombre']."</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<label>Sucursal:</label></br>
										<select name='sucursal' id='sucursal' style='text-overflow: ellipsis;'  class="form-control">
											<?php
											while($LS = $ListaSucursales->fetch_assoc())
											{
												echo "<option value='".$LS['idSuc']."//".$LS['nombre']."'>".$LS['nombre']."</option>";
											}
											?>
										</select>
									</div>
									<div class="col-md-6">
										<label>Fecha de poliza:</label>
										<?php if(isset($_SESSION['fechaprove'])){ ?>
											<input  type="text" class="form-control" id="fecha" name="fecha" value="<?php echo $_SESSION['fechaprove']; ?>" onmousemove="javascript:fechadefault()" />
										<?php }else{ ?>
											<input  type="text" class="form-control" id="fecha" name="fecha" onmousemove="javascript:fechadefault()" />
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</section>
					<h4>Datos del pago</h4>
					<section>
						<div class="row">
							<div class="col-md-3">
								<label>Forma de pago:</label>
								<select id="formapago" name="formapago" class="form-control">
								 	<?php while($f=$forma_pago->fetch_array()){
								 			if(($f['idFormapago'].'/'.$f['nombre'])==$formap){ ?>
								 				<option value="<?php echo $f['idFormapago']."/".$f['nombre']; ?>" selected><?php echo $f['nombre'];?></option>
								 	<?php 	}else{?>
								 				<option value="<?php echo $f['idFormapago']."/".$f['nombre']; ?>"><?php echo ($f['nombre']);?></option>
								 	<?php	 } 
									}?>
							 	</select>
							</div>
							<div class="col-md-3">
								<label>Numero:</label>
								<input type="text"  class="form-control" size="20" id="numero"  name="numero" value="<?php echo $numero;?>"/>
							</div>
							<div class="col-md-3">
								<label>Banco Origen:</label>
								<select id="listabancoorigen" name="listabancoorigen" class="form-control">
									<?php 
									while($b=$listacuentasbancarias->fetch_array()){
										if($b['idbancaria']==$banorigen){?>
											<option value="<?php echo $b['idbancaria']; ?>" selected ><?php echo $b['nombre']; ?></option>";
									<?php	}else{ ?>
											<option value="<?php echo $b['idbancaria']; ?>"  ><?php echo $b['nombre']; ?></option>";
									<?php	}
									} 
									?>
								</select>
							</div>
							<div class="col-md-3">
								<label>No. Cuenta Bancaria Origen/tarjeta</label>
								<input type="text" id="numorigen" name="numorigen" class="form-control" value="<?php echo $numeroorigen; ?>" readonly/>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<label>Beneficiario:</label>
								<select id="beneficiario" name="beneficiario"  class="form-control"  onchange="cuentarbolbenefi();" >
									<option value="0">Elija un Beneficiario</option>
									<?php 
											while($b=$beneficiario->fetch_array()){ 
												if($b['idPrv']==$idbeneficiario){  ?>
													<option value="<?php echo  $b['idPrv']; ?>" selected><?php echo ($b['razon_social']); ?> </option>
									<?php 		}else{ ?>	
													<option value="<?php echo  $b['idPrv']; ?>"><?php echo ($b['razon_social']); ?> </option>
									<?php  		}
											} 
									?>
								</select>
							</div>
							<div class="col-md-3">
								<label>RFC:</label>
								<input type="text" id="rfc" name="rfc" class="form-control" value="<?php echo $rfc; ?>" readonly/>
							</div>
							<div class="col-md-3">
								<label>Banco Destino:</label>
								<select id="listabanco" name="listabanco" onchange="numerocuent()" class="form-control">
									<option value="0">Elija Banco</option>
									<?php 
										while($b=$listabancos->fetch_array()){
											if($b['idbanco']==$idbanco){ ?> 
											<option value="<?php echo  $b['idbanco']; ?>" selected><?php echo $b['nombre']."(".$b['Clave'].")"; ?> </option>
									<?php  		} else{ ?>
												<option value="<?php echo  $b['idbanco']; ?>"><?php echo $b['nombre']."(".$b['Clave'].")"; ?> </option>
									<?php		}
										}
									?>
								</select>
							</div>
							<div class="col-md-3">
								<label>No. Cuenta Bancaria Dest./tarjeta
								<img style="vertical-align:middle;width: 15px;height: 15px" title="Cargar numero" onclick='numerocuent()' src="images/reload.png">
								<input type="text" id="numtarje" name="numtarje" class="form-control" value="<?php echo $numtarje; ?>" readonly/>
							</div>
						</div>
					</section>
					<section>
						<div class="row">
							<div class="col-md-2">
								<button type="submit" class="btn btn-primary btnMenu" id="agregar">Leer XML's</button>
							</div>
							<div class="col-md-7">
								<input type="checkbox" value="0" id="unsolobanco" onclick="unSoloBanco()"/><b style="color:red;font-size: 17px">Un solo Abono a Bancos.</b>
							</div>
						</div>
					</section>
				</form>
				<section id="movimientos">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="datos" align="center" cellpadding="2" border="0" style="border: white 1px solid; " width="100%">
									<thead>
										<tr>
											<td></td>
											<td></td>
											<td class="nmcatalogbusquedatit" align="center">Cargo MXN</td>
											<td class="nmcatalogbusquedatit" align="center">Abono MXN</td>
											<td class="nmcatalogbusquedatit" align="center">Cargo M.E.</td>
											<td class="nmcatalogbusquedatit" align="center">Abono M.E.</td>
											<td class="nmcatalogbusquedatit" align="center">XML</td>
											<!-- <td class="nmcatalogbusquedatit" align="center">Forma de pago</td> -->						
											<td class="nmcatalogbusquedatit" align="center">Segmento</td>
											<td class="nmcatalogbusquedatit" align="center">Sucursal</td>

										</tr>
										<tr><td colspan="8"><hr></hr></td></tr>
									</thead>
										<tbody><?php 
										$cont = $totalbancosmxn = $totalbancosme = 0;
										
											 foreach($_SESSION['proveedor'] as $cli){
											 	//echo count($cli);
												foreach($cli as $prove){
											if(strrpos($prove['proveedor'],"-")){
												 $p=explode('-',$prove['proveedor']);
												 $cli=$p[1]; 
											}else{
												$p=explode('/',$prove['proveedor']);
												$cli=$p[2];
											}
											$totalbancosmxn += $prove['importemxn'];
											$totalbancosme  += $prove['importe'];
											$segment = explode('//',$prove['segmento']);
											$sucu = explode('//',$prove['sucursal']);
												  ?>
								
								 <tr class="trpagototal"><td colspan="7"><hr></hr>
								 <input type="checkbox"  checked="" id="<?php echo $cont; ?>" onclick="pagoss(<?php echo $cont; ?>,<?php echo $prove['MontoParcial']; ?>)"/>Pago Total</td></tr>

								 </tr>
								 	
									 <tr>
										 <td rowspan="2" align="center"><b><?php echo ($cli); ?></b><br><?php echo $prove['concepto']; ?></td>
										 <input type="hidden" value="<?php echo $prove['proveedor']; ?>" id="idcli<?php echo $cont; ?>"/>
										 <td  class="nmcatalogbusquedatit" align="center">Proveedores</td>
										<!-- mxn -->
										 <td align="center"  style="display: none" id="impormxnintro<?php echo $cont; ?>"><?php echo number_format($prove['importemxn'],2,'.',','); ?></td>
										 <td align="center" id="impormxn<?php echo $cont; ?>"><?php echo number_format($prove['importemxn'],2,'.',','); ?></td>
										 <td align="center">0.00</td>
										<!-- fin mxn -->
										
										 <td align="center" class="me"  id="impor<?php echo $cont; ?>"><?php echo number_format($prove['importe'],2,'.',','); ?></td>
										 <td align="center" class="me" style="display: none" id="imporintro<?php echo $cont; ?>" ><input type="text" placeholder="0.00" value="0.00" id="imporinput<?php echo $cont; ?>" disabled/></td>
										 <td align="center" class="me">0.00</td>
										 <td colspan=""></td>
										 <td align="center"><?php echo $segment[1]; ?></td>
										 <td align="center"><?php echo $sucu[1]; ?></td>
										  <td align="center"><?php echo $prove['xml']; ?></td>
										 <td><img src="images/eliminado.png" title="Eliminar Movimiento" onclick="borra(<?php echo $cont; ?>);"/></td>

									 </tr>
									 <tr class="trbancos">
										 <td  class="nmcatalogbusquedatit" align="center">Bancos</td>
										 <!-- mxm -->
										 <td align="center">0.00</td>
										 <td align="center" id="impor2mxn<?php echo $cont; ?>"><?php echo number_format($prove['importemxn'],2,'.',','); ?></td>
										 <td align="center"  style="display: none" id="impor2mxnintro<?php echo $cont; ?>"><?php echo number_format($prove['importemxn'],2,'.',','); ?></td>
										
										  <!--fin mxm -->
										 
										 <td align="center" class="me">0.00</td>
										 <td align="center" class="me" id="impor2<?php echo $cont; ?>"><?php echo number_format($prove['importe'],2,'.',','); ?></td>
										 <td align="center" class="me" style="display: none" id="imporintro2<?php echo $cont; ?>"><input  type="text" placeholder="0.00" value="0.00" id="imporinput2<?php echo $cont; ?>" onkeyup="calculaIVAIEPS('imporinput',<?php echo $cont; ?>,<?php echo $prove['tipocambio']; ?>)" /></td>
										 <!-- <td align="center"><?php $f=explode("/",$prove['formapago']); echo $f[1];?></td>	-->				
										 	
										 	 <td colspan="4"></td> 					
									 </tr>
									 <?php 
									 if($statusIVA==1){
									 	if($prove['IVA']>0){ //pato?>
									 	<script>
									 	$(document).ready(function(){
									 		$("#ivapendientepago<?php echo $cont ?>,#ivapago<?php echo $cont ?>").select2({
					        					 width : "150px"
					       					 });
					       				<?php	
					       				if($prove['MontoParcial']){?>
									 		$("#ivacobrado<?php echo $cont; ?>,#ivapendiente<?php echo $cont; ?>").show();
											$("#ivacobradoinput<?php echo $cont; ?>,#ivapendienteinput<?php echo $cont; ?>").val(<?php echo $prove['IVA']; ?>);
											$("#iva2<?php echo $cont; ?>,#iva<?php echo $cont; ?>").hide();
									 	<?php } ?> 
										});
									 	</script>
									 	<tr>
									 		<td colspan="" class="classiva"></td>
									 		<?php if($statusIVAIEPS==1){?>
									 		<td  class="nmcatalogbusquedatit" align="center"><!-- IVA pendiente de Pago -->
									 			<input type="button" id="ivapendientepago" name="ivapendientepago" onclick="mandaasignarcuenta();" title="Ir a asignacion de cuentas" class="nmcatalogbusquedatit" value="<?php  echo $ivapendientepago[1];?>">
											</td>
											<?php }else{ ?>
											<td  class="nmcatalogbusquedatit" align="center">IVA pendiente de Pago
									 					<select id="ivapendientepago<?php echo $cont; ?>" class="nmcatalogbusquedatit" >
									 						<option value="0">--Elija una cuenta--</option>
												 			<?php echo $listadoivaieps; ?>
									 					</select>
									 				</td>
												<?php } ?>
											<!-- mxn -->
											<td align="center">0.00</td>
									 		<td align="center" id="ivamxn<?php echo $cont; ?>" ><?php echo number_format($prove['IVAmxn'],2,'.',','); ?></td>
									 		<td align="center" style="display: none" id="ivamxnintro<?php echo $cont; ?>" ><?php echo number_format($prove['IVAmxn'],2,'.',','); ?></td>

									 		<!-- fin mxn -->
									 		<td align="center" class="me">0.00</td>
									 		<td align="center" class="me" id="iva<?php echo $cont; ?>" ><?php echo number_format($prove['IVA'],2,'.',','); ?></td>
									 		<td align="center" class="me" id="ivapendiente<?php echo $cont; ?>" style="display: none"><input type="text" value="0.00" placeholder="0.00" id="ivapendienteinput<?php echo $cont; ?>" disabled/></td>
									 	</tr>
									 	<tr>
									 		<td colspan=""></td>
									 		<?php if($statusIVAIEPS==1){?>
									 		<td  class="nmcatalogbusquedatit" align="center"><!-- IVA Pagado -->
									 			<input type="button" id="ivapago" name="ivapago" onclick="mandaasignarcuenta();" class="nmcatalogbusquedatit" title="Ir a asignacion de cuentas"  value="<?php  echo $CuentaIVApagado[1];?>">
									 		</td>
									 		<?php }else{ ?>
								 			<td  class="nmcatalogbusquedatit" align="center">IVA Pagado
							 					<select style="width : 170px" id="ivapago<?php echo $cont; ?>" class="nmcatalogbusquedatit" >
							 						<option value="0">--Elija una cuenta--</option>
										 			<?php echo $listadoivaieps; ?>
							 					</select>
							 				</td>
									 		<?php } ?>
									 		<!-- mxn -->
									 		<td align="center" id="iva2mxn<?php echo $cont; ?>"><?php echo number_format($prove['IVAmxn'],2,'.',','); ?> </td>
											<td align="center" style="display: none" id="iva2mxnintro<?php echo $cont; ?>"><?php echo number_format($prove['IVAmxn'],2,'.',','); ?> </td>
											<td align="center">0.00</td>
											<!--fin mxn -->
									 		<td align="center" class="me" id="iva2<?php echo $cont; ?>"><?php echo number_format($prove['IVA'],2,'.',','); ?> </td>
									 		<td align="center" class="me" id="ivacobrado<?php echo $cont; ?>" style="display: none"><input type="text" value="0.00" placeholder="0.00" id="ivacobradoinput<?php echo $cont; ?>" onkeyup="rellena('ivapendienteinput<?php echo $cont; ?>','ivacobradoinput<?php echo $cont; ?>')"/></td>
											<td align="center" class="me">0.00</td>
									 	</tr>
									 <?php }
									 }
									 if($statusIEPS==1){ 
									   if($prove['IEPS']>0){ ?>
									  	<script>
									  	$(document).ready(function(){
									 		$("#iepspendiente<?php echo $cont ?>,#iepspago<?php echo $cont ?>").select2({ width : "150px" });
					      					<?php 
									 	if($prove['MontoParcial']){ ?> 
									 		$("#ipendiente<?php echo $cont; ?>,#icobrado<?php echo $cont; ?>").show();
											$("#ipendienteinput<?php echo $cont; ?>,#icobradoinput<?php echo $cont; ?>").val(<?php echo $prove['IEPS']; ?>);
											$("#ieps<?php echo $cont; ?>,#ieps2<?php echo $cont; ?>").hide();

									 	<?php } ?>
					      				});
									 	</script>
									 	<tr>
									 		<td colspan="" class="classieps"></td>
									 		<?php if($statusIVAIEPS==1){?>
									 		<td  class="nmcatalogbusquedatit" align="center"><!-- IEPS pendiente de Pago -->
									 			<input type="button" id="iepspendiente" name="iepspendiente" onclick="mandaasignarcuenta();" title="Ir a asignacion de cuentas"  class="nmcatalogbusquedatit" value="<?php  echo $iepspendientepago[1];?>">
									 		</td>
									 		<?php } else{ ?>
								 			<td  class="nmcatalogbusquedatit" align="center">IEPS pendiente de Pago
									 			<select id="iepspendiente<?php echo $cont; ?>">
									 				<option value="0">--Elija una cuenta--</option>
									 				<?php echo $listadoivaieps; ?>
									 			</select>
								 			</td>
									 		<?php } ?>
									 		<!-- mxn -->
									 		<td align="center">0.00</td>
									 		<td align="center" id="iepsmxn<?php echo $cont; ?>"><?php echo number_format($prove['IEPSmxn'],2,'.',','); ?></td>
									 		<td align="center" style="display: none" id="iepsmxnintro<?php echo $cont; ?>"><?php echo number_format($prove['IEPSmxn'],2,'.',','); ?></td>

									 	<!--fin mxn -->
									 		<td align="center" class="me">0.00</td>
									 		<td align="center" class="me" id="ieps<?php echo $cont; ?>"><?php echo number_format($prove['IEPS'],2,'.',','); ?></td>
									 		<td align="center" class="me" id="ipendiente<?php echo $cont; ?>" style="display: none"><input type="text" value="0.00" placeholder="0.00" id="ipendienteinput<?php echo $cont; ?>" disabled/></td>
									 	
									 	</tr>
									 	<tr>
									 		<td colspan=""></td>
									 		<?php if($statusIVAIEPS==1){?>
									 		<td  class="nmcatalogbusquedatit" align="center"><!-- IEPS Pagado -->
									 			<input type="button" id="iepspago" name="iepspago" onclick="mandaasignarcuenta();" title="Ir a asignacion de cuentas" class="nmcatalogbusquedatit" value="<?php  echo $CuentaIEPSpagado[1];?>">
									 		</td>
									 		<?php } else{ ?>
									 			<td  class="nmcatalogbusquedatit" align="center">IEPS Pagado
									 			<select id="iepspago<?php echo $cont; ?>">
									 				<option value="0">--Elija una cuenta--</option>
									 				<?php echo $listadoivaieps; ?>
									 			</select>
									 			</td>
									 		<?php } ?>
									 		<!-- mxn -->
									 		<td align="center" id="ieps2mxn<?php echo $cont; ?>"><?php echo number_format($prove['IEPSmxn'],2,'.',','); ?></td>
										 	<td align="center" style="display:none" id="ieps2mxnintro<?php echo $cont; ?>"><?php echo number_format($prove['IEPSmxn'],2,'.',','); ?></td>
										 	<td align="center">0.00</td>
										 	<!-- fin mxn -->
									 		<td align="center" class="me" id="ieps2<?php echo $cont; ?>"><?php echo number_format($prove['IEPS'],2,'.',','); ?></td>
									 		<td align="center" class="me" id="icobrado<?php echo $cont; ?>" style="display: none"><input type="text" value="0.00" placeholder="0.00" id="icobradoinput<?php echo $cont; ?>" onkeyup="rellena('ipendienteinput<?php echo $cont; ?>','icobradoinput<?php echo $cont; ?>')"/></td>
										 	<td align="center" class="me">0.00</td>
									 	</tr>
									 <?php }
									 } ?>
									 <tr><td colspan="7"><hr></hr></td></tr>
								
									<?php $cont++; }
								 	} ?>
								 	<tr class="trUnsoloBanco" style="display: none"><td></td>
										 <td  class="nmcatalogbusquedatit" align="center"><b style="font-size: 17px;">Bancos</b></td>
										 <!-- mxm -->
										 <td align="center" >0.00</td>
										 <td align="center"><b style="font-size: 17px;"><?php echo number_format($totalbancosmxn,2,'.',','); ?></b></td>

										  <!--fin mxm -->
										 
										 <td align="center" class="me">0.00</td>
										 <td align="center" class="me"><b style="font-size: 17px;"><?php echo number_format($totalbancosme,2,'.',','); ?></b></td>
										 <!-- <td align="center"><?php $f=explode("/",$prove['formapago']); echo $f[1];?></td>	-->				
										 	 <td colspan="4"></td> 					
									 </tr>
								 	
								 	</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
				<section>
					<div class="row">
						<div class="col-md-3">
						</div>
						<div class="col-md-3">
							<img src="images/loading.gif" style="display: none" id="load">
						</div>
						<div class="col-md-3">
							<button class="btn btn-primary btnMenu" id="agregaprevio" onclick="antesdeguardar(<?php echo $cont; ?>);">Agregar poliza</button>
							<button class="btn btn-primary btnMenu" id="agrega" onclick="guarda();" style="display: none">Agregar poliza</button>
						</div>
						<div class="col-md-3">
							<button class="btn btn-danger btnMenu" id="cancela" onclick="cancela();">Cancelar poliza</button>
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
<script>
	//beneficiari();
</script>
