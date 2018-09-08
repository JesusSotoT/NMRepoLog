
<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.number.js"></script>
	<link rel="stylesheet" type="text/css" href="css/ingresos.css" />
	<script src="../cont/js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="js/egresos.js"></script>
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<script src="js/sessionejercicio.js"></script>



</head>
<style>
.fila-base{ display: none; } /* fila base oculta */
.eliminar{ cursor: pointer; color: #000; }
	.fiel{
   		border-radius:8px;
    		/*box-shadow: 0 9px #58ACFA ;*/
    		
	}
	legend {
	/*text-align:right;*/
    color: #58ACFA;;
	}
@media print
{
	span,.iconos,#buscarconcepto,#listaempleado,.row-fluid
	{
		display:none;
	}
	
}	
</style>
<?php $bancosfiltro='';
require("views/documentos/relacionarfacturas.php");
if($info['RFC']!=""){
	$Ex['PeriodoActual'] = $info['PeriodoActual'];
	$Ex['EjercicioActual'] = $info['EjercicioActual'];
	$Ex['InicioEjercicio'] = $info['EjercicioActual'].'-01-01';
	$Ex['FinEjercicio'] = $info['EjercicioActual'].'-12-31';
	$Ex['PeriodosAbiertos'] = $info['PeriodosAbiertos'];
	$Ex['NombreEjercicio'] = $info['EjercicioActual'];
	$bancosfiltro = $info['EjercicioActual'].'-01-01';
}
if(isset($_COOKIE['ejercicio'])){
	$NombreEjercicio = $_COOKIE['ejercicio'];
	$bancosfiltro = $_COOKIE['ejercicio'].'-01-01';
	$v=1;
}else{
	$NombreEjercicio = $Ex['NombreEjercicio'];
	$v=0;
}
?>
<script>
	var contador=0;
	var suma=0;
	var sumaretencion = 0;
$(document).ready(function(){
		<?php 
		if(isset($_SESSION['egresonew'])){
			$idtemporal = $_SESSION['egresonew']; 
		} ?>	
		
		dias_periodo(<?php echo $NombreEjercicio; ?>,<?php echo $v; ?>,'<?php echo $bancosfiltro;?>');
		$("#poliza").hide();//oculta por default	
		<?php 

			if($acontia){
				if($info['PolizaAuto']==1){?>
					$("#automatica").val(1);
					$("#tdpoliza").show();
			
			<?php if(isset($datos)){ //solo ver poliza en edicion?>
				$("#tipoPoliza").val(<?php echo $datos['tipoPoliza'];?>);$("#tipoPoliza").selectpicker('refresh');
					$("#poliza").show();
					$("#poliza").attr("onclick","return verPoliza()");
					$("#poliza").attr("title","Ver Poliza");
			<?php } 
	
				}else{?>
					$("#automatica").val(0);
					$("#tdpoliza").hide();
			
			<?php if(isset($datos)){ //solo crear poliza en edicion ?>
					$("#poliza").show();
					//$("#poliza").attr("onclick","return creaPoliza()");
					$("#poliza").attr("onclick","return verPoliza()");
					$("#poliza").attr("title","Ver Poliza");
			<?php }
			
			 	}
			
			}else{?>
				$("#automatica").val(0);
				$("#tdpoliza").hide();
				$("#poliza").hide();
<?php 		}
	if(isset($datos)){ $separa = explode('-', $datos['fecha']); 
	
		if($datos['tipocambio']>0){
			
		?>
			$("#extra").show();
			$("#tipoPoliza option[value='3']").remove();
			$("#int2").show();
			$("#int").hide();
			$(".t1").selectpicker('hide');
			$(".t2").show(); 
			$("#cambio,#tipocambio2").val('<?php echo $datos['tipocambio'];?>');
			$.post('ajax.php?c=Cheques&f=tipoCambio',{ idmoneda:<?php echo $datos['idmoneda'];?>,fecha:<?php echo $separa[0];?>+'-'+"<?php echo $separa[1];?>"}
			,function(c){
				$("#tipocambio").empty();
				$("#tipocambio").html(c).selectpicker("refresh");
			});
			
<?php	}
if($datos['beneficiario']==2){?>	
		$("#listaempleado").attr("checked",true);
<?php	}
		if($datos['comision']==1){ ?>
	 		$("#statuscomision").val(1);
			$("#tdpoliza").hide();
			$("#checkcomision").attr("checked",true);
<?php	}


 }
?>
	if($("#textarea").html()==""){
		$("#selectconceptos").show();
		$("#buscarconcepto").hide();
	}

variossub();	

<?php
$listaprv = "<option value=0 >--Seleccione--</option>";
$listaempleado = '<option value=0 >--Seleccione--</option>';
while($c = $empleados->fetch_array()){ $sel = "";
	if(isset($datos)){
		if($datos['beneficiario']==2){
		if($datos['idbeneficiario']==$c['idEmpleado']){ $sel = "selected";} } } 
		$listaempleado .= "<option value='".$cuentasAsiganacion['CuentaSueldoxPagar']."/". $c['idEmpleado']."/2 ' ". $sel.">".$c['nombreEmpleado']." ".$c['apellidoPaterno']." (".$c['codigo'].")</option>";
 }

while($c = $sqlprov->fetch_array()){ $se = "";
	if(isset($datos)){
		if($datos['beneficiario']==1){
		if($datos['idbeneficiario']==$c['idPrv']){ $se = "selected";} } }
		$listaprv .= "<option value='".$c['cuenta']."/". $c['idPrv']."/1/".$c['idtipo']."' ".$se.">".$c['razon_social']."</option>";
}

?>
 
 listaempleado = "<?php echo $listaempleado; ?>";
 listaprv = "<?php echo $listaprv; ?>";
<?php
	if(isset($datos)){
	 if($datos['traspaso']>0 || $datos['conciliado']==1){
	 	 $oculta="display:none";
	 }
	 
	 if($datos['traspaso']>0){ $tra = explode('/',$traspasoDocdestino);
	 if($tra[1]==4){ $doctipo = 2;}elseif($tra[1]==3){ $doctipo =1;}
	 
	 ?>
	 $("#statustraspaso").val(1);
	 	//$.post("ajax.php?c=Cheques&f=cuentasTraspaso",{
			//	idbancaria:<?php echo $datos['idbancaria'];?>
			//},function (request){ 
			 	$("#tdtraspaso,#tddoctraspaso").show();
			 	//$("#listatraspaso").html(request);
			 	$("#listatraspaso").empty();
			 	$('#bancodestino').find('option:selected').clone().appendTo('#listatraspaso').selectpicker('refresh');
			 	//$("#listatraspaso").html($("#bancodestino > option").clone())
			 	$("#listadoctraspaso").val(<?php echo $doctipo;?>).selectpicker('refresh');
			 	$("#checktraspaso").attr("checked",true);

	 	//});
<?php	 
	}
 }else{ $oculta=""; }
 
 
 if(isset($datos)){
	if($datos['anticipo']==1){?>
		$("#checktanticipo").attr("checked",true);
		anticipo();
		$("#usuarios").val( <?php echo $datos['idUser']; ?> );
	<?php
	}
}
 ?>
 
});
</script>
<body>
<?php $cuentaarbol="";
	while($b=$cuentasAfectables->fetch_array()){ 
		$cuentaarbol .= "<option value='".$b['account_id']."/".$b['currency_id']."'>".$b['description']."(".$b['manual_code'].")</option>";
	}
	
	 ?>

<div style="width:90%;background: #D8D8D8;" align="center" class="container well" >	
	<h3 class="text-primary" style="font-size: 30px">Documento Egreso</h3>

	<form action="ajax.php?c=Cheques&f=creaEgreso" method="post" onsubmit="">
		<div align="right" class="iconos" style="font-size: 20px;">
		
			<span class="glyphicon glyphicon-floppy-disk nmwaicons" id="guardarimg" onclick="guardarEgreso();" title="Guardar" style="<?php echo $oculta; ?>" ></span>
			
			<span class="glyphicon glyphicon-list-alt nmwaicons" id="poliza" title="Poliza" ></span>
			<span class="fa fa-refresh fa-spin nmwaicons" id="img" style="display: none;" ></span>
			<span  class="glyphicon glyphicon-paperclip nmwaicons" id="FacturasButton" title="Asociar Facturas" style="font-weight:bold;color:black;" onclick="javascript:abrefacturas()" ></span>
			<span class=" glyphicon glyphicon-print nmwaicons" title="Imprimir" id="imprimir" onclick="javascript:print();"></span>
 			<span onclick="javascript:mail();" class=" glyphicon glyphicon-envelope nmwaicons" id="impri"  title ="Enviar Documento por correo electrónico" border="0" style="display:none"></span>
			<img  data-toggle="modal" data-target="#divretencion"  style="width: 26px;height: 26px" class="" src="images/re3.png" id="retencion"  title ="" >			
			<input type="hidden" id="documentoTip" value="verEgresos">
			<input type="hidden" id="cobrarpagar" value="1">
			<a  href='index.php?c=Ingresos&f=filtro&fun=verEgresos&cancela=<?php echo $idtemporal ;?>' onclick="" id='filtros'>	
				<span class="btn btn-danger "  title="Cancelar/Salir" ><b>X</b></span>
			</a>
<?php if(isset($datos)){ 
		if($datos['proceso']==1){?>
			<!-- <input type="radio" name="proceso"  align="right" value="1" id="proyectado" checked=""/><b>Proyectado</b> -->
       		<input type="radio" value="2"  align="right" name="proceso" id="autorizado" /><b>Autorizado</b>
       
<?php 	}else{?>
			<!-- <input type="radio" name="proceso"  align="right" value="1" id="proyectado" /><b>Proyectado</b> -->
       	 	<input type="radio" value="2" name="proceso" align="right"  checked="" id="autorizado" /><b>Autorizado</b>
<?php   }
		if($datos['conciliado']==1){?>
			 <input type="checkbox"  disabled="" align="right" name="conciliado"  id="conciliado" checked=""/><b>Conciliado</b>
<?php	}else{ ?>
			<input type="checkbox" disabled="" align="right" name="conciliado"  id="conciliado" /><b>Conciliado</b>
<?php   }	
	}else{?>
		<!-- <input type="radio" name="proceso" value="1" align="right" id="proyectado"/><b>Proyectado</b> -->
        <input type="radio" value="2" name="proceso" align="right" checked="" id="autorizado" /><b>Autorizado</b>
        <input type="checkbox" disabled="" name="conciliado" align="right" id="conciliado" /><b>Conciliado</b>
<?php } ?>
</div>


<!-- <div class="nmcatalogbusquedatit" >
	<input type="checkbox" name="creapoli" id="creapoli" checked="" align="right" value="" />Crear/Actualizar Poliza al Guardar Documento 
</div> -->
	<!-- <fieldset class="fiel papel"> -->
	<div align="center" class="row-fluid">
	<table cellpadding="2" cellspacing="2" width="90%">
		
		<tr>
			<?php 
			
			if(isset($_COOKIE['ejercicio'])){ 
				$InicioEjercicio = explode("-","01-0".$_COOKIE['periodo']."-".$_COOKIE['ejercicio']); 
				$FinEjercicio = explode("-","31-0".$_COOKIE['periodo']."-".$_COOKIE['ejercicio']);  
				$periodoactual = $_COOKIE['periodo'];
				$ejercicioactual = $_COOKIE['ejercicio'];
			}else{
				$InicioEjercicio = explode("-",$Ex['InicioEjercicio']); 
				$FinEjercicio = explode("-",$Ex['FinEjercicio']); 
				$periodoactual = $Ex['PeriodoActual'];
				$ejercicioactual = $Ex['EjercicioActual'];
				
			}
			
			?>
		<td ><b>Ejercicio Vigente:</b> 
			<?php
			if($Ex['PeriodosAbiertos'])
				{
					if($ejercicioactual > $firstExercise)
					{
						?><a href='javascript:cambioEjercicio(<?php echo $periodoactual; ?>,<?php echo $ejercicioactual-1; ?>);' title='Ejercicio Anterior'><img class='flecha' src='../cont/images/flecha_izquierda.png'></a>
				<?php }
				} ?>
	
			del (<?php echo $InicioEjercicio['2']."-".$InicioEjercicio['1']."-".$InicioEjercicio['0']; ?>) al (<?php echo $FinEjercicio['2']."-".$FinEjercicio['1']."-".$FinEjercicio['0']; ?>)
			<?php if($Ex['PeriodosAbiertos'])
				{
					if($ejercicioactual < $lastExercise)
					{
						?><a href='javascript:cambioEjercicio(<?php echo $periodoactual; ?>,<?php echo $ejercicioactual+1; ?>)' title='Ejercicio Siguiente'><img class='flecha' src='../cont/images/flecha_derecha.png'></a>
				<?php }
				} ?> </td>

			<td>
			
				<td>	<b>Periodo actual:</b> 

		<?php 
				if($Ex['PeriodosAbiertos'])
				{
					if($periodoactual>1)
					{
						?><a href='javascript:cambioPeriodo(<?php echo $periodoactual-1; ?>,<?php echo $ejercicioactual; ?>);' title='Periodo Anterior'><img class='flecha' src='../cont/images/flecha_izquierda.png'></a>
				<?php }
				} ?>  
				<label id='PerAct'><?php echo $periodoactual; ?></label><input type='hidden' id='Periodo' value='<?php echo $periodoactual; ?>'> del (<label id='inicio_mes'></label>) al (<label id='fin_mes'></label>)  
			 	<?php if($Ex['PeriodosAbiertos'])
				{
					if($periodoactual<13)
					{
						?><a href='javascript:cambioPeriodo(<?php echo $periodoactual+1; ?>,<?php echo $ejercicioactual; ?>)' title='Periodo Siguiente'><img class='flecha' src='../cont/images/flecha_derecha.png'></a>
				<?php }
				} ?> </td>
			 </td>
		
		<td>
			Acorde a configuracion:<img src="images/reload.png" onclick="periodoactual()" title="Ejercicio y periodo de configuracion por defecto" style="vertical-align:middle;">
		</td>
		
	</table><br>
	</div>
<div class="panel panel-primary" >
	<div class="panel-heading" align="left" style="">
		<div class="row">
			<div class="col-md-2">
				<b style="font-size: 16px;">Datos del Documento</b>
			</div>
			<div class="col-md-2">
				<?php if(!isset($datos) || $datos['traspaso']==1){ ?>
				<input type="checkbox" id="checktraspaso"  onclick="listaTraspaso()"/>Traspaso
			</div>
			<div class="col-md-2">
				<?php }if(!isset($datos) || $datos['comision']==1){?>
						<input type="checkbox" id="checkcomision"  onclick="comision()"/>Comision
				<?php } ?>
			</div>
			<div class="col-md-2">
				<input type="checkbox" id="checktanticipo"  onclick="anticipo()"/>Anticipo de Gastos
				<input type="hidden" id="statusanticipo" name="statusanticipo" value="0">

			</div>
			<div class="col-md-4"  align="right">
				<?php 
				if(isset($datos)){?>
				<td  ><b>No.Documento</b>
				<input type="text" disabled="" size="5" class="text-danger" value="<?php echo $datos['numdoc'];?>" />	
				</td>
				<?php } ?>
			</div>
		</div>
	</div> 
		<div class="panel-body">				

			<input type="hidden" id="automatica" name="automatica" value="1" />
			<input type="hidden" id="acontia" name="acontia" value="<?php echo $acontia;?>" />
			
			<div class="row">
				<div class="col-md-2">
					<b>Tipo</b><b style="color: red">*</b>:
					<a id="loadtipoducumento" href="#" title="Actualizar Catalogo"><i id="update1"  class="fa fa-refresh "></i></a>

					<select id="tipodocumento" name="tipodocumento" class="selectpicker" data-width="100%" data-live-search="true">
						<?php
						while($b=$tipodocumento->fetch_array()){$sl = "";
							if(isset($datos)){
								if($datos['idTipoDoc']==$b['idTipoDoc']){ $sl = "selected";} }?>
						<option value="<?php echo  $b['idTipoDoc']; ?>" <?php echo $sl; ?>><?php echo $b['nombre']; ?> </option>
						<?php	 
						} ?>
					</select>
				</div>
				<div class="col-md-2">
					<b>Fecha:</b> <b style="color: red">*</b>
	  				<input id="fecha" name="fecha"  type="text" placeholder="" class="form-control input-md" required="" onmousemove="javascript:fechadefault()" value="<?php if(isset($datos)){ echo date('d-m-Y', strtotime($datos['fecha']));}?>">

				</div>
				<div class="col-md-2">
					<b>Cuenta:</b><b style="color: red">*</b>
				  	<span class="glyphicon glyphicon-share-alt" title="Agregar Cuenta" onclick="mandacuentabancaria()"></span>
					<a id="loadcuenta" href="#" title="Actualizar Catalogo"><i id="update3"  class="fa fa-refresh "></i></a>

					<input type="hidden" name="idtemporal" id="idtemporal" value="<?php echo $idtemporal ;?>"/>
					<?php 	if(isset($datos)){?>
						<input type="hidden" name="link" value="c=Ingresos&f=filtro&fun=verEgresos"/>
						<input type="hidden" name="id" id="id" value="<?php echo $datos['id'];?>"/>

					<?php 	}else{ ?>
						<input type="hidden" name="link" value="verEgresos"/>
					<?php 	} ?>
		
					<select id="cuenta" name="cuenta"  onchange="listatipocambio()" class="selectpicker" data-width="100%" data-live-search="true"><!-- onchange="beneficiarioCuenta()" esta es para q la cuenta contable del beneficiario el listadito sea acorde ala moneda-->
						<option value="0" selected="">--Seleccione--</option>
					<?php	
						while($b=$cuentasbancarias->fetch_array()){$sel = "";
							if(isset($datos)){
								if($datos['idbancaria']==$b['idbancaria']){ $sel = "selected";
								}
			 				}
								?>
						<option value="<?php echo  $b['idbancaria']."//".$b['account_id']."//".$b['coin_id']; ?>" <?php echo $sel; ?>><?php echo $b['nombre']." (".$b['cuenta'].")"; ?> </option>
					<?php 				
						} ?><!-- echo "<script>
							beneficiarioCuenta('".$b['idbancaria']."//".$b['account_id']."');
						</script>";?> -->
						</select>
						<div id='consul' style='font-size:12px;color:blue;display: none' align=""> Consultando T.C.<progress></progress></div>

				</div>
				<div class="col-md-2" id="extra" style="display: none;">
					
						<label class="" for="textinput">Tipo Cambio</label>
						<select id="tipocambio" class="t1" onchange="tipoc(this.value)" data-width="100%" data-live-search="true"></select>
						<img src="../cont/images/intro.png" style="vertical-align:middle;" width="22px" height="22px" id="int" onclick="cambiaintro()" title="Introducir Tipo de Cambio"/>
						<img src="../cont/images/dine.jpeg" style="vertical-align:middle;display: none" width="22px" height="22px" id="int2" onclick="listadoin()" title="Seleccionar Tipo de Cambio"/>
						<input type="hidden" id="cambio" name="cambio" value="0.0000"/>
						<input class="t2" type="text" id="tipocambio2" name="tipocambio2" placeholder="0.00" style="display: none;color: black;"  onkeypress="return tcvalida(event,this);" onkeyup="tipoc(this.value);">
			
					
				</div>
				<div class="col-md-2"  id="paguese">
					<input type="hidden" id="statustraspaso" name="statustraspaso" value="0" />
					<input type="hidden" id="statuscomision" name="statuscomision" value="0" />
					<i class='fa fa-refresh fa-spin 'id="progrestraspaso" style="display: none" ></i>
						<input type="checkbox" id="listaempleado"  onclick="listaEm()"/>Empleados/<b>Pagador</b>
						<span class="glyphicon glyphicon-share-alt" title="Agregar Proveedor" onclick="irProveedores()"></span>
					<input type="hidden" id="appministra" name="appministra" value="<?php echo $appministra; ?>" />
					<a id="loadpagador" href="#" title="Actualizar Catalogo"><i id="update4"  class="fa fa-refresh "></i></a>

					<select id="pagador" name="pagador"  onchange="bancoDestino()" class="selectpicker" data-width="100%" data-live-search="true">
					<?php 
					if(isset($datos)){
							if($datos['beneficiario']==2){
								echo $listaempleado.$listaprv;
							}else{
								echo $listaprv;
							}
						}else{
							echo $listaprv;
						} 
					// CLIENTES COMO BENEFICIARIO PAGADOR
					// while($c = $clientesBeneficiario->fetch_array()){ $sel = "";
						// if(isset($datos)){
							// if($datos['beneficiario']==5){
								// if($datos['idbeneficiario']==$c['id']){ $sel = "selected";} 
							// }
						// }?>
						<!-- <option value="<?php echo $c['cuentaprv']."/".$c['id']."/5";?>" <?php echo $sel;?>><?php echo $c['nombre']; ?></option> -->

					</select>
				</div>
				<!-- ANTICIPO GASTOS -->
			<div id="divusuarios" style="display: none" class="col-md-2">
				Usuario/Deudor:
				<select id="usuarios" name="usuarios" class="selectpicker" data-width="100%" data-live-search="true" onchange="" >
						<?php
							while($user = $usuarios->fetch_assoc()){ ?>
								<option value="<?php  echo $user['idempleado']?>"><?php echo $user['usuario']?></option>
						<?php } ?>
				</select>
			</div>
			<!-- FIN ANTICIPO GASTOS -->
			
				<div id="vercuentasprv" style="display: none" class="col-md-2" >
					<label style="color: red;center">Cuenta Pagador
					<img style="vertical-align:middle;" title="Actualizar Listado" onclick="actualizalistapaguese2()" src="images/reload.png">
					<img id="progres" style="vertical-align:middle;width: 25px;height: 25px;display: none" src="images/loading.gif" class="nmwaicons"></label>
					<select id="paguese2" name="paguese2" class="selectpicker" data-width="80%" data-live-search="true">
					<?php echo $cuentaarbol; ?>
					</select>
				</div>
				<div class="col-md-2" id="tdtraspaso" style="display: none;">
						<label style="color: red;center">Cuenta Destino</label>
						<select id="listatraspaso" name="listatraspaso" onchange="bancodestinotraspaso()" class="selectpicker" data-width="100%" data-live-search="true">
						</select>
				</div>
				<div class="col-md-2" id="tddoctraspaso" style="display: none;">
						<label style="color: red;center">Documento Destino</label>
						<select id="listadoctraspaso" name="listadoctraspaso" class="selectpicker" data-width="100%" data-live-search="true">
							<option value="1">Ingreso por Depositar</option>
							<option value="2">Deposito</option>
						</select>
				</div>
			</div><br>
			<div class="row">
				<div class="col-md-2">
					
					<b>Importe:</b><b style="color: red">*</b>
					<input type="text"  id="importe"  name="importe" onkeyup="porcentajecal(this.value)" onkeypress="return tcvalida(event,this);" value="<?php if(isset($datos)){echo number_format($datos['importe'],2,'.',',');}?>" class="form-control input-md">

				</div>
				<div class="col-md-2">
					SubClasificador:
					<a id="clasificadorload" href="#" title="Actualizar Catalogo"><i id="update2"  class="fa fa-refresh "></i></a>

					<select id="clasificador" name="clasificador" onchange="variossub()" data-width="100%" class="selectpicker" data-width="80%" data-live-search="true">
						<option value="0">--Varios--</option>
						<?php while($c = $clasificador->fetch_array()){ $selec = "";
						if(isset($datos)){
							if($datos['idclasificador']==$c['id']){ $selec = "selected";} }?>
								<option value="<?php echo $c['id']; ?>" <?php echo $selec;?>> <?php echo $c['nombreclasificador']."(".$c['codigo'].")"; ?></option>
						<?php } ?>
					</select>
					
				</div>
				<div class="col-md-2" id="tdpoliza">
					<b style="color: red">Poliza</b>
					<select id="tipoPoliza" name="tipoPoliza" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="0">--Seleccione--</option>
						<option value="1">Pago con Provision sin IVA</option>
						<option value="2">Pago con Provision con IVA</option>
						<option value="3">Pago sin Provision</option>
					</select>
				</div>
				<div class="col-md-2">
					
					Referencia:
					<input type="text" class="form-control" id="referencia" name="referencia" value="<?php if(isset($datos)){echo $datos['referencia'];}?>"/>

				</div>
				<div class="col-md-2">
					
					<b>Concepto General:</b>
					<input id="textarea" class="form-control" style="height: 33px"  name="textarea" value='<?php if(isset($datos)){echo $datos['concepto'];}?>'/>
					<img onclick="conceptos();" id="buscarconcepto" title="Buscar Concepto" src="images/busca3.png" style="width: 30px;height: 30px"></img>

				</div>
				<div class="col-md-2" id="selectconceptos" style="display: none" title="Lista Conceptos" >
					Lista Conceptos
					<select id="listaconcepto" onchange="conceptext()" class="selectpicker" data-width="100%" data-live-search="true">
						<option value="0">--Seleccione--</option>
						<?php  while( $row = $listaconceptos->fetch_array()) {?>
						<option value="<?php echo $row['descripcion']; ?>"><?php echo $row['descripcion']."(".$row['codigo'].")"; ?></option>
						<?php } ?>
					</select>
					
				</div>
			</div>
		<!-- <td colspan="" width=""><b>Saldo contable al:</b> &nbsp;</td>
			<td width="" ><input type="date" id="fechasaldo" style="height: 25px;" onchange="saldofecha()" value="<?php echo date('Y-m-d');?>"  /></td>
			<td  width="" id="saldo" style="font: center;color: #FF0000"></td>
			<td colspan="" ><b>Saldo Bancario al:</b> &nbsp;</td>
			<td ><input type="date" id="fechasaldobanco" style="height: 25px;"  disabled="" onchange="saldofecha()" value="<?php echo date('Y-m-d');?>" /></td>
			<td  id="saldobancario" style="font: center;color: #FF0000"></td>
			 -->
			
	<!-- <fieldset class="fiel papel"> -->
	<!-- <td><img  onclick="ocultalista();" id="ocultaconcepto" title="Introducir Concepto" src="images/edita.png" style="width: 30px;height: 30px;display: none"></img></td> -->
			<!-- <td>Moneda:</td>
			<td>
  			<select id="moneda" name="moneda" onchange="letra()" style="width: 170px;">
  					<?php while($moni = $moneda->fetch_array()){$s = "";
					if(isset($datos)){
  						if($datos['idmoneda']==$moni['coin_id']){ $s = "selected";} }?>
  							<option value="<?php echo $moni['coin_id']."/".$moni['description']."/".$moni['codigo']; ?>" <?php echo $s;?>><?php echo $moni['description']?></option>
  					<?php } ?>
  				</select>
  			</td>
		<!-- </fieldset> -->
		
	</div>
	
</div>

		<?php	
			if(isset($datos)){
				if($datos['traspaso']==1){
				$tra = explode('/',$traspasoDocdestino);
			 ?>
						<script>
							$(document).ready(function(){
								$("#loadcuenta").hide
								$("#pagador,#tipoPoliza").val(0);
								
								$("#pagador").selectpicker('destroy');
								$("#tipoPoliza").selectpicker('destroy');
								$("#pagador,#paguese,#tdpoliza").hide();
							});
						</script>
						<div class="alert alert-info" align="center">
  							<strong style="font-size: 18px">Traspaso</strong>
  							 <a onclick="irDocumento(<?php echo $tra[0];?>,<?php echo $tra[1];?>)" >Ir Documento destino</a>
						</div>

						
				<?php	
				}
			}?>
			
<div class="panel panel-primary" ><div class="panel-heading" align="left"><b style="font-size: 16px;">Datos del Pago</b></div>
	<div class="panel-body">
<!-- <fieldset class="fiel papel"> -->
		<div class="col-md-12">
			<div class="col-md-4">
			Segmento
			<select class="selectpicker"  id="segmento" name="segmento" data-width="100%" data-live-search="true">
				<?php
				while($f = $segmento->fetch_array()){$sd = "";
					if(isset($datos)){ if($datos['idSeg']==$f['idSuc']){ $sd = "selected";} }?>
					
					<option value="<?php echo $f['idSuc'];?>" <?php echo $sd;?> >
						<?php echo"(".$f['clave'].") ".$f['nombre'];?>
					</option>
					
				<?php } ?>
			</select>
			</div>
			<div class="col-md-4">
				<b>Forma de pago</b>
				<select id="formapago" name="formapago" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0">Elija una forma de pago</option>
					<?php mb_internal_encoding("UTF-8");
					while($f = $formapago->fetch_array()){$sd = "";
					if($f['idFormapago']!=2){
						if(isset($datos)){
	  						if($datos['formadeposito']==$f['idFormapago']){ $sd = "selected";} }
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
							}
						} ?>
						
					<!-- <?php while($p = $formapago->fetch_array()){$sd = "";
					if($p['idFormapago']!=2){
						if(isset($datos)){
	  						if($datos['formadeposito']==$p['idFormapago']){ $sd = "selected";} }?>
	  							<option value="<?php echo $p['idFormapago']; ?>" <?php echo $sd;?>><?php echo $p['nombre']?></option>
	  					<?php } 
					} ?> -->
				</select>
			</div>
			<div class="col-md-4"><b>Numero</b>
				<input type="text" class="form-control" id="numeroformapago" name="numeroformapago" value="<?php if(isset($datos)){ echo $datos['numeroformapago'];}?>">
			</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-4">
			Sucursal
			<select class="selectpicker" id="sucursal" name="sucursal" data-width="100%" data-live-search="true">
				<?php
				while($f = $sucursal->fetch_array()){$sd = "";
					if(isset($datos)){ if($datos['idSuc']==$f['idSuc']){ $sd = "selected";} }?>
					
					<option value="<?php echo $f['idSuc'];?>" <?php echo $sd;?> >
						<?php echo $f['nombre'];?>
					</option>
					
				<?php } ?>
			</select>
		</div>
			
			<div class="col-md-4">
				<b>Banco Destino</b><b style="color: red">*</b>:
				<select name="bancodestino" id="bancodestino" onchange="numerocuent();" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0">--Seleccione--</option>
					<?php while ($ban = $bancos->fetch_array()){ $des=""; 
						if(isset($datos)){
							if($datos['bancoDestino']==$ban['idbanco']){ $des = "selected";}	
						} ?>
						<option value="<?php echo $ban['idbanco']."/0"; ?>" <?php echo $des;?>><?php echo $ban['nombre'] ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-4">
				<b>Cuenta Bancaria Destino</b><b style="color: red">*</b>:
				<input type="text" class="form-control" name="numcuentadestino" id="numcuentadestino" value="<?php if(isset($datos)){echo $datos['numBancoDes'];}?>"></td>
			</div>
		</div>
				<br>
</div>
</div>
<!-- </fieldset> -->
<div class="panel panel-info" >
	<div class="panel-heading" align="left"><b style="font-size: 16px;">SubClasificador (Varios)</b></div>
	<div class="panel-body">
<fieldset  class="fiel papel" id="subclasificador" >
	<legend></legend>
	<table id="tabla" width="50%">
	<thead>
		<tr>
			
			<th align="center">Subclasificador</th>
			<th align="center">%</th>
			<th align="center">Importe</th>
		</tr>
	</thead>
 
	<tbody>
 		<tr class="fila-base">
			<td>
				<select name="subcategorias[]" id="subcategorias" class="subc">
					<option value="0">--Seleccione--</option>
					<?php 
						if($clasificadorsub->num_rows>0){
						while($c = $clasificadorsub->fetch_array()){?>
						<option value="<?php echo $c['id']; ?>"> <?php echo $c['nombreclasificador']."(".$c['codigo'].")"; ?></option>
					<?php } 
						}else{?>
							<option value="0">No tiene Subcategorias</option>
					<?php }?>
				</select>
			</td
		</tr>
		<?php 
	  if(isset($datos)){ $contadorphp=0;
	  	while($va = $subcategoriasAsignadas->fetch_array()){?>
	  		<tr>
	  		<td>
	  		<select name="subcategorias[]" id="subcategorias" class="subc" >
					<option value="0">--Seleccione--</option>
					<?php $categorias = $this->ChequesModel->clasificador();
						while($c =$categorias ->fetch_array()){ 
							if($va['idSubcategoria']==$c['id']){ $s="selected";}else{$s="";}?>
						<option value="<?php echo $c['id']; ?>" <?php echo $s;?> > <?php echo $c['nombreclasificador']."(".$c['codigo'].")"; ?></option>
					<?php } ?>
				</select>
			</td>
			<td><input type="text" name="porcentaje[]" onkeypress="return tcvalida(event,this);" class="porcentajesuma" onkeyup="tecleado(this.value,'<?php echo $contadorphp;?>');" value="<?php echo $va['porcentaje'];?>"/></td>
			<td><label id="importecategoria" name="importecategoria[]" onkeyup="" class="impsuma" data-value='<?php echo $contadorphp;?>' data-importe="<?php echo $va['importe'];?>"><?php echo number_format($va['importe'],2,'.','');?></label></td>
			<td class="eliminar">Eliminar</td>
			</tr>
				
<?php $contadorphp++;
		}?>
		<script>
			contador=<?php echo $contadorphp;?>;
		</script>
<?php } ?>
		
	</tbody>
	<tfoot></tfoot>
</table><input type="button" id="agregar" value="+" onclick="sumaPorciento();"/>
</fieldset><br>
<input type="submit" id="submit" style="display: none" />
<?php if(isset( $_COOKIE['ejercicio'])){ ?>
			<input type="hidden" value="<?php echo $_COOKIE['ejercicio']; ?>" id="ejercicio" name="ejercicio">
			<input type="hidden" value="<?php echo $_COOKIE['periodo']; ?>" id="idperiodo" name="idperiodo">	
		<?php }else{ ?>
			<input type="hidden" value="<?php echo $ejercicioactual; ?>" id="ejercicio" name="ejercicio">
			<input type="hidden" value="<?php echo $periodoactual; ?>" id="idperiodo" name="idperiodo">	
		<?php } ?>
</div></div>
<?php
if($appministra==1){ ?>
	
<div class="panel panel-info" >
	<div class="panel-heading" align="left"><b style="font-size: 16px;">Cuentas por Pagar (Appministra)</b></div>
	<div class="panel-body">
		<table class="table table-striped table-bordered" >
			<thead>
				<tr>
					<th colspan="4" style="background-color:#BDBDBD;color:white;text-align: center;size: 20px;"><b>REALIZADOS</b></th>
				</tr>
				<tr>
					<th>Pago</th>
					<th>Concepto</th>
					<th>Abono</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="realizadoapp">
				<?php 
				if($appPagos->num_rows>0){
					while($p = $appPagos->fetch_object()){?>
						<script>
							totalcuentasPrevias += <?php echo $p->abonorelacion;?>;
						</script>
					<tr id="tr<?php echo $p->id_pago;?>" class="trappministra">
						<td><?php echo $p->id_pago;?></td>
						<td><?php echo $p->concepto;?></td>
						<td><?php echo $p->abonorelacion;?></td>
						<td style="<?php echo $oculta; ?>"><img src="images/cancelar.png" width="20px" height="20px" title="Eliminar Pago" onclick="eliminaPagoApp(<?php echo $p->id_pago;?>,<?php echo $p->id;?>,<?php echo $p->abonorelacion;?>)"/></td>
					</tr>
				<?php } 
				}else{ ?>
					<tr>
						<td colspan="4" align="center">No ha realizado Pagos con este Documento</td>
					</tr>
				<?php }?>
			</tbody>
		</table>
	</div>
	<div class="panel-body" id="contenidoapp">
		
	</div>
</div>

<?php } ?>	
</form><br>
		
<div class="modal fade" id="divretencion" >
</div>
<script type="text/javascript">

$(function(){
	$("#agregar").on('click', function(){ 
		suma=0;
		$('#clasificador').val(0);
		$(".porcentajesuma").each(function(){
			if($(this).val()){ 
				suma+=parseFloat($(this).val());
			}
		});
		if(suma<100){
			$('#subcategorias').find('option').removeAttr("selected");
			
			$("#tabla tbody tr:eq(0)").clone().removeClass('fila-base').appendTo("#tabla tbody");
			$("#tabla tbody td:last").after('<td><input type="text" name="porcentaje[]" onkeypress="return tcvalida(event,this);" class="porcentajesuma" onkeyup="tecleado(this.value,'+contador+');"/></td>');
			$("#tabla tbody td:last").after('<td><label id="importecategoria" name="importecategoria[]" onkeyup="" class="impsuma" data-value='+contador+'></label></td>');
			$("#tabla tbody td:last").after('<td class="eliminar">Eliminar</td>');
			//$("#subcategorias").select2('destroy');
			//$(".subc").select2({'width':'300px'});
		}
		contador++;
	});
 
	$(document).on("click",".eliminar",function(){
		suma=0;
		var parent = $(this).parents().get(0);
		$(parent).remove();
		$(".porcentajesuma").each(function(){
			if($(this).val()){
				suma += parseFloat($(this).val());
			}
		});
		
	});
	
	
});

$(function(){
	$("#agregarr").on('click', function(){ 
		$("#tretencion tbody tr:eq(0)").clone().removeClass('fila-baser').appendTo("#tretencion tbody");
		$("#tretencion tbody td:last").after('<td class="eliminar">Eliminar</td>');
			
	});
 
	$(document).on("click",".eliminarr",function(){
		var parent = $(this).parents().get(0);
		$(parent).remove();
		
	});
	
	//$("#listaconcepto").selectpicker('refresh');
});
$(document).ready(function(){
<?php if(isset($datos)){ 
		if($appministra==1){?>
			$("#contenidoapp").load("ajax.php?c=Cheques&f=cxcycxp&idPrvCli="+<?php echo $datos['idbeneficiario'];?>+"&mone="+<?php echo $datos['idmoneda'];?>+"&cobrar_pagar=1&cambio="+$("#cambio").val());
<?php	}
	}?>
});

</script>
</body>
</html>

