
<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/ingresos.css" />
	<script src="../cont/js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="js/sessionejercicio.js"></script>
	<script src="js/depositos.js"></script>
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<script type="text/javascript" src="js/jquery.number.js"></script>
	<script language='javascript' src='../cont/js/pdfmail.js'></script>


	
</head>
<style>
	.fiel{
   		border-radius:8px;
    		/*box-shadow: 0 9px #58ACFA ;*/
    		
	}
	.iconos {
	text-align:right;
    color: #58ACFA;
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
$(document).ready(function(){
		<?php
		if(isset($_SESSION['depositonew'])){
		 $idtemporal = $_SESSION['depositonew'];} ?>		
	dias_periodo(<?php echo $NombreEjercicio; ?>,<?php echo $v; ?>,'<?php echo $bancosfiltro;?>');
	$("#guardarimg").attr('onclick',"guardaDeposito(2)");//para q no pida cuentas a proyectados
	<?php 
if($acontia){
		if($info['PolizaAuto']==1){?>
			$("#automatica").val(1);
			$("#tdpoliza").show();
			$("#guardarimg").attr('onclick',"guardaDeposito(1)");//solo pedira cuenta cuando sea automatica
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
					$("#poliza").attr("onclick","return  validaCuentaProyectados(2)");//se ira primero aqui para ver si todos los seleccionadoss tienen cuenta
			<?php }
	 	}
}else{?>
	$("#automatica").val(0);
	$("#tdpoliza").hide();
	$("#poliza").hide();
<?php }
if(isset($_COOKIE['ejercicio'])){
	if($_COOKIE['periodo']<9){ $peri="0".$_COOKIE['periodo'];} else{ $peri = $_COOKIE['periodo'];}
		$InicioEjercicio = explode("-","01-".$peri."-".$_COOKIE['ejercicio']); 
		$FinEjercicio = explode("-","31-".$peri."-".$_COOKIE['ejercicio']);  
		$periodoactual = $_COOKIE['periodo'];
		$ejercicioactual = $_COOKIE['ejercicio'];
	}else{
		$InicioEjercicio = explode("-",$Ex['InicioEjercicio']); 
		$FinEjercicio = explode("-",$Ex['FinEjercicio']); 
		$periodoactual = $Ex['PeriodoActual'];
		$ejercicioactual = $Ex['EjercicioActual'];
		
	}
if(isset($datos)){ $separa = explode('-', $datos['fecha']); 
	if($datos['tipocambio']>0){ ?>
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
<?php	
	}
}?>
if($("#textarea").html()==""){
	$("#selectconceptos").show();
	$("#buscarconcepto").hide();
}
<?php if(isset($datos)){
	 if($datos['idtraspaso']>0 ){?>
 		$("#poliza").hide();
<?php }
	 if($datos['idtraspaso']>0 || $datos['conciliado']==1 ){
	 	 $oculta="display:none";?>
	 	 $("#tdpoliza").hide();
<?php	}
 }else{ $oculta=""; } 

 ?>
});
</script>
<body>
<div style="width:90%;background: #D8D8D8;" align="center" class="container well" >	
		<h3 class="text-primary" style="font-size: 30px">Documento Deposito</h3>

<?php 
if(isset($datos)){?>
	<form  action="ajax.php?c=Ingresos&f=updateDeposito&id=<?php echo $datos['id'];?>" method="post" id="datos" >
<?php }else{?>
	<form action="ajax.php?c=Ingresos&f=creaDeposito" method="post" id="datos" >
<?php } ?>
<div align="right" class="iconos" style="font-size: 20px;">
	<span class="fa fa-refresh fa-spin" id="load" style="display: none" ></span>
	<span id="guardarimg"  title="Guardar" style="<?php echo $oculta;?>" class="glyphicon glyphicon-floppy-disk nmwaicons"></span>
	
	<span class="glyphicon glyphicon-list-alt nmwaicons" id="poliza" title="Poliza" style="display: none"></span>
	<span  class="glyphicon glyphicon-paperclip nmwaicons" id="FacturasButton" title="Asociar Facturas"  onclick="javascript:abrefacturas()">
	</span>
	<span class=" glyphicon glyphicon-print nmwaicons" title="Imprimir" id="imprimir" onclick="javascript:print();"></span>
  	<span onclick="javascript:pdf();" class="glyphicon glyphicon-save-file nmwaicons" title ="Generar Documento en PDF" border="0" id="pdfre" style="display:none"></span> 
 	<span onclick="javascript:mail();" class=" glyphicon glyphicon-envelope nmwaicons" id="impri"  title ="Enviar Documento por correo electrónico" border="0" style="display:none"></span>
	<input type="hidden" id="automatica" name="automatica" value="1" />
	<input type="hidden" id="depositodoc" name="depositodoc" value="1" />
	<input type="hidden" id="acontia" name="acontia" value="<?php echo $acontia;?>" />
	<input type="hidden" name="idtemporal" id="idtemporal" value="<?php echo $idtemporal;?>"/>
	<a  href='index.php?c=Ingresos&f=filtro&fun=verDeposito&cancela=<?php echo $idtemporal;?>' onclick="" id='filtros'>		
		<span class="btn btn-danger " title="Cancelar/Salir" ><b>X</b></span>
	</a>
	<?php if(isset($datos)){?>
	 <input type="hidden" name="id" id="id" value="<?php echo $datos['id'];?>"/>

	<?php	if($datos['conciliado']==1){?>
			 <input type="checkbox"  disabled="" align="right" name="conciliado"  id="conciliado" checked=""/><b>Conciliado</b>
	<?php	}else{ ?>
			<input type="checkbox" disabled="" align="right" name="conciliado"  id="conciliado" /><b>Conciliado</b>
	<?php   }
	} else{	?>
	        <input type="checkbox" disabled="" name="conciliado" align="right" id="conciliado" /><b>Conciliado</b>
	<?php } ?>
	
</div>
<div id="imprimible">
		<div align="center" class="row-fluid">

	<table cellpadding="2" cellspacing="2" width="90%">
		
		<tr>
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
		
	</table>	</div>

<div class="panel panel-primary" >
	<div class="panel-heading" align="left" style="">
		<div class="row">
			<div class="col-md-6">
				<b style="font-size: 16px;">Datos del Documento</b>
			</div>
			<div class="col-md-6"  align="right">
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

		<div class="row">
			<div class="col-md-2">
				
				Tipo<b style="color: red">*</b>:
				<a id="loadtipoducumento" href="#" title="Actualizar Catalogo"><i id="update1"  class="fa fa-refresh "></i></a>

				<select id="tipodocumento" name="tipodocumento" class="selectpicker" data-width="100%" data-live-search="true">
					<?php
					while($b=$tipodocumento->fetch_array()){$sl = "";
						if(isset($datos)){
							if($datos['idTipoDoc']==$b['idTipoDoc']){ $sl = "selected";} }?>
						<option value="<?php echo  $b['idTipoDoc']; ?>" <?php echo $sl; ?>><?php echo $b['nombre']; ?> </option>
					<?php } ?>
				</select>
				
			</div>
			<div class="col-md-2">
				
				Fecha<b style="color: red">*</b>:
				<input type="text" id="fecha" class="form-control" name="fecha" style="" onmousemove="javascript:fechadefauldepo();" value="<?php if(isset($datos)){ echo date('d-m-Y', strtotime($datos['fecha']));}?>"/>

			</div>
			<div class="col-md-2">
				
				Fecha de Aplicacion<b style="color: red">*</b>
				<input type="text" id="fechaaplicacion" class="form-control" name="fechaaplicacion" style="" onmousemove="javascript:fechadefauldepo();" value="<?php if(isset($datos)){ echo date('d-m-Y', strtotime($datos['fechaaplicacion']));}?>"/>

			</div>
			<div class="col-md-2">
				
				Cuenta:<b style="color: red">*</b>
				<span class="glyphicon glyphicon-share-alt" title="Agregar Cuenta" onclick="mandacuentabancaria()"></span>
				<a id="loadcuentadepo" href="#" title="Actualizar Catalogo"><i id="update3"  class="fa fa-refresh "></i></a>

				<select id="cuenta" name="cuenta" onchange="ListanoDepo()" required class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0">--Seleccione--</option>
					<option value="t">Traspasos</option>
					<?php
					while($b=$cuentasbancarias->fetch_array()){$sel = "";
					if(isset($datos)){
						if($datos['idbancaria']==$b['idbancaria']){ $sel = "selected";} }?>
						<option value="<?php echo  $b['idbancaria']."//".$b['account_id']."//".$b['coin_id']; ?>" <?php echo $sel; ?>><?php echo $b['nombre']." (".$b['cuenta'].")"; ?> </option>
					<?php } ?>
				</select>
				<div id='consul' style='font-size:12px;color:blue;width:20px;display: none;'> Consultando...<progress></progress></div>
				
				
			</div>
			<div  id="extra" style="display: none" class="col-md-2">
				Tipo Cambio
				
				<select id="tipocambio" class="t1" onchange="tipoc(this.value)"  data-width="100%" data-live-search="true"></select>
				<img src="../cont/images/intro.png" style="vertical-align:middle;" width="22px" height="22px" id="int" onclick="cambiaintro()" title="Introducir Tipo de Cambio"/>
				<img src="../cont/images/dine.jpeg" style="vertical-align:middle;display: none" width="22px" height="22px" id="int2" onclick="listadoin()" title="Seleccionar Tipo de Cambio"/>
				<input type="hidden" id="cambio" name="cambio" value="0.0000"/>
				<input class="t2" type="text" id="tipocambio2" name="tipocambio2" placeholder="0.00" style="display: none;color: black;"  onkeypress="return tcvalida(event,this);" onkeyup="tipoc(this.value);">
				
				
			</div>
			<div class="col-md-2" id="tdmoneda" style="display:none">
				Moneda
  				<select id="moneda" name="moneda" onchange="ListanoDepo()" class="selectpicker" data-width="100%" data-live-search="true">
  					<option value="0">--Seleccione--</option>
  					<?php while($moni = $moneda->fetch_array()){$s = "";
					if(isset($datos)){
  						if($datos['idmoneda']==$moni['coin_id']){ $s = "selected";} }?>
  							<option value="<?php echo $moni['coin_id']; ?>" <?php echo $s;?>><?php echo $moni['description']?></option>
  					<?php } ?>
  				</select>
			</div>
		</div><br>
		<div class="row">
			<?php if(isset($datos)){?>
			<script>
			$(document).ready(function(){
				$("#formadeposito").val(<?php echo $datos['formadeposito'];?>);
				$("#formadeposito").selectpicker('refresh');
			});
			</script>
			<?php } ?>
			
			
			<div class="col-md-2">
				
				Importe
				<input type="text"  id="importe" class="form-control input-md" name="importe"  readonly="" value="<?php if(isset($datos)){echo number_format($datos['importe'],2,'.',',');}?>">

			</div>
			<div class="col-md-2" id="tdpoliza" >
				
				<b style="color: red">Poliza</b>
				<select id="tipoPoliza" name="tipoPoliza" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0">--Seleccione--</option>
					<option value="1">Cobro con Provision sin IVA</option>
					<option value="2">Cobro con Provision con IVA</option>
					<option value="3">Cobro sin Provision</option>
				</select>
				
			</div>
			
			<div class="col-md-2">
				
				Referencia
				<input type="text"  id="referencia" class="form-control" name="referencia"  value="<?php if(isset($datos)){echo $datos['referencia'];}?>">

			</div>
			<div class="col-md-2">
				
				Concepto General:
				<input id="textarea" class="form-control" style="height: 33px" name="textarea" value='<?php if(isset($datos)){echo $datos['concepto'];}?>'/>
				<img onclick="conceptos();" id="buscarconcepto" title="Buscar Concepto" src="images/busca3.png" style="width: 30px;height: 30px"></img>
				
			</div>
			<div id="selectconceptos" style="display: none;" title="Lista Conceptos" class="col-md-2">
				Lista Conceptos
				<select id="listaconcepto" onchange="conceptext()" class="selectpicker" data-width="100%" data-live-search="true">
					<option value="0">--Seleccione--</option>
					<?php  while( $row = $listaconceptos->fetch_array()) {?>
					<option value="<?php echo $row['descripcion']; ?>"><?php echo $row['descripcion']."(".$row['codigo'].")"; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>	
				<!-- <td>
					<select id="formadeposito" name="formadeposito">
						<option value="0">--Ninguno--</option>
						<option value="1">Mismo Banco</option>
						<option value="2">Otros Bancos</option>
					</select>
				</td> -->
		
		<!-- <td><img  onclick="ocultalista();" id="ocultaconcepto" title="Introducir Concepto" src="images/edita.png" style="width: 30px;height: 30px;display: none"></img></td> -->
<br>

	</div>
	
</div>


<div class="panel panel-primary" ><div class="panel-heading" align="left"><b style="font-size: 16px;">Datos del Pago</b></div>
	<div class="panel-body">
		<div class="col-md-3">
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
		<div class="col-md-3">
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
			<div class="col-md-3">
				Forma de pago
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
						
					
				</select>
			</div>
			<div class="col-md-3">Numero
				<input type="text" class="form-control" id="numeroformapago" name="numeroformapago" value="<?php if(isset($datos)){ echo $datos['numeroformapago'];}?>">
			</div>
		</div>
	</div>





<div class="panel panel-info" >
	<div class="panel-heading" align="left"><b style="font-size: 16px;">Ingresos Proyectados</b></div>
	<div class="panel-body">
<fieldset class="fiel papel">
    <!-- <li><a href="#tabs-2">Otros Datos</a></li> -->
  <form>
  <div align="center" class="row-fluid">

<label for="buscar">Buscar:</label> <input type="text" id="buscar" value=""/>
</div>
	</form>
  	<table id="nodepo" class="table-responsive" cellspacing="2" cellpadding="2" width="100%" >
  		<thead>
  			<tr style="background-color:#BDBDBD;color:white;font-weight:bold;height:30px;">
	  			<th></th>
	  			<th>Cuenta</th>
	  			<th>Fecha</th>
	  			<th>Pagador</th>
	  			<th>Referencia</th>
	  			<th>Concepto</th>
	  			<th>Importe</th>
	  			<th>Moneda</th>
  			</tr>
  		</thead>
  		<tbody>
  	<?php if($ingresoNo && $datos['idtraspaso']==0){
  				while($in = $ingresoNo->fetch_assoc()){?>
  					<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" >
  						<td><input  type="checkbox" name="Nodepositado[]" class="listacheck" data-value="<?php echo $in['idbancaria']; ?>" value="<?php echo $in['importe']."/".$in['id']."/".$in['idbancaria'];?>" onclick="calculo()"/> </td>
  						<td><?php echo $in['cuenta'];?></td>
  						<td><?php echo $in['fecha'];?></td>
  						<td><?php echo $in['nombre'];?></td>
  						<td><?php echo $in['referencia'];?></td>
  						<td><?php echo $in['concepto'];?></td>
  						<td><b style="color:red"><?php echo number_format($in['importe'],2,'.',',');?></b></td>
						<td><?php echo $in['description'];?></td>
  					</tr>
  					
  		<?php 	}
		}else{ ?> 
  			<tr>
  				<td colspan="8" align="center">No tiene Ingresos Proyectados</td>
  			</tr>
  	<?php }
		if(isset($datos)){ 
			while($in = $agregados->fetch_array()){ 
				if($in['idtraspaso']>0){?>
					<script>
						$(document).ready(function(){
							$("#guardarimg").hide();
							$("#tdpoliza").hide();
							
						});
					</script>
			<?php } ?>
  					<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" >
  						<td><input  type="checkbox" name="Nodepositado[]" class="listacheck" data-value="<?php echo $in['idbancaria']; ?>" value="<?php echo $in['importe']."/".$in['id']."/".$in['idbancaria'];?>" onclick="calculo()" checked=""/> </td>
  						<td><?php echo $in['cuenta'];?></td>
  						<td><?php echo $in['fecha'];?></td>
  						<td><?php echo $in['nombre'];?></td>
  						<td><?php echo $in['referencia'];?></td>
  						<td><?php echo $in['concepto'];?></td>
  						<td><b style="color:red"><?php echo number_format($in['importe'],2,'.',',');?></b></td>
						<td><?php echo $in['description'];?></td>
  					</tr>
  					
  		<?php 	}
		} ?>
  		</tbody>
  	</table>
 </fieldset><br>
 </div>
 </div>
 
 
 
 </div><!-- imprimible -->
<?php if(isset( $_COOKIE['ejercicio'])){ ?>
	<input type="hidden" value="<?php echo $_COOKIE['ejercicio']; ?>" id="ejercicio" name="ejercicio">
	<input type="hidden" value="<?php echo $_COOKIE['periodo']; ?>" id="idperiodo" name="idperiodo">	
<?php }else{ ?>
	<input type="hidden" value="<?php echo $ejercicioactual; ?>" id="ejercicio" name="ejercicio">
	<input type="hidden" value="<?php echo $periodoactual; ?>" id="idperiodo" name="idperiodo">	
<?php } ?>
 </form>
 </div>

<div id="sincuenta" style="display: none;background-image: url('images/cuadriculado.jpg')" >
	<label style="color: red;text-align: center" >Seleccione la cuenta para el Pagador.</label>
	<br></br>
	<table id="cuentaProyectados" cellpadding="2" cellspacing="2" width="90%">
		<thead >
			<tr style="background-color:#BDBDBD;color:white;font-weight:bold;height:30px;">
				<th>Pagador</th>
				<th>Cuenta</th>
			</tr>
		</thead>
		<tbody></tbody>
	</table>
</div>
<div id="divpanelpdf"
				style="
					position: absolute; top:200px; left: 40%;
					opacity:0.9;
					padding: 20px;
					-webkit-border-radius: 20px;
    			border-radius: 10px;
					background-color:#000;
					color:white;
				  display:none;	
				  z-index:1;
				">
					<form id="formpdf" action="../cont/libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
				<!--form id="formpdf" action="../../../webapp/netwarelog/repolog/pdf.php" method="post" target="_blank" onsubmit="generar_pdf()">-->
					<center>
					<b> Generar PDF </b>
					<br><br>

					<table style="border:none;">
						<tbody>
							<tr>
								<td style="color:white;font-size:13px;">Escala:</td>
								<td style="color:white;font-size:13px;">
									<select id="cmbescala" name="cmbescala">
									<option value=100>100</option>
<option value=99>99</option>
<option value=98>98</option>
<option value=97>97</option>
<option value=96>96</option>
<option value=95>95</option>
<option value=94>94</option>
<option value=93>93</option>
<option value=92>92</option>
<option value=91>91</option>
<option value=90>90</option>
<option value=89>89</option>
<option value=88>88</option>
<option value=87>87</option>
<option value=86>86</option>
<option value=85>85</option>
<option value=84>84</option>
<option value=83>83</option>
<option value=82>82</option>
<option value=81>81</option>
<option value=80>80</option>
<option value=79>79</option>
<option value=78>78</option>
<option value=77>77</option>
<option value=76>76</option>
<option value=75>75</option>
<option value=74>74</option>
<option value=73>73</option>
<option value=72>72</option>
<option value=71>71</option>
<option value=70>70</option>
<option value=69>69</option>
<option value=68>68</option>
<option value=67>67</option>
<option value=66>66</option>
<option value=65>65</option>
<option value=64>64</option>
<option value=63>63</option>
<option value=62>62</option>
<option value=61>61</option>
<option value=60>60</option>
<option value=59>59</option>
<option value=58>58</option>
<option value=57>57</option>
<option value=56>56</option>
<option value=55>55</option>
<option value=54>54</option>
<option value=53>53</option>
<option value=52>52</option>
<option value=51>51</option>
<option value=50>50</option>
<option value=49>49</option>
<option value=48>48</option>
<option value=47>47</option>
<option value=46>46</option>
<option value=45>45</option>
<option value=44>44</option>
<option value=43>43</option>
<option value=42>42</option>
<option value=41>41</option>
<option value=40>40</option>
<option value=39>39</option>
<option value=38>38</option>
<option value=37>37</option>
<option value=36>36</option>
<option value=35>35</option>
<option value=34>34</option>
<option value=33>33</option>
<option value=32>32</option>
<option value=31>31</option>
<option value=30>30</option>
<option value=29>29</option>
<option value=28>28</option>
<option value=27>27</option>
<option value=26>26</option>
<option value=25>25</option>
<option value=24>24</option>
<option value=23>23</option>
<option value=22>22</option>
<option value=21>21</option>
<option value=20>20</option>
<option value=19>19</option>
<option value=18>18</option>
<option value=17>17</option>
<option value=16>16</option>
<option value=15>15</option>
<option value=14>14</option>
<option value=13>13</option>
<option value=12>12</option>
<option value=11>11</option>
<option value=10>10</option>
<option value=9>9</option>
<option value=8>8</option>
<option value=7>7</option>
<option value=6>6</option>
<option value=5>5</option>
<option value=4>4</option>
<option value=3>3</option>
<option value=2>2</option>
<option value=1>1</option>
									</select> %
								</td>
							</tr>
							<tr>
								<td style="color:white;font-size:13px;">Orientación:</td>
								<td style="color:white;">
									<select id="cmborientacion" name="cmborientacion">
										<option value='P'>Vertical</option>
										<option value='L'>Horizontal</option>
									</select>
								</td>
							</tr>
					</tbody>
				</table>
				<br>
					
				<textarea id="contenido" name="contenido" style="display:none"></textarea>
				<input type='hidden' name='tipoDocu' value='hg'>
				<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
				<input type='hidden' name='nombreDocu' value='Detalle Saldo Conciliacion'>
				<input type="submit" value="Crear PDF" autofocus >
				<input type="button" value="Cancelar" onclick="cancelar_pdf()">
				
				</center>
				</form>
			</div>
			<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:2;">
			<div 
				id="divmsg"
				style="
					opacity:0.8;
					position:relative;
					background-color:#000;
					color:white;
					padding: 20px;
					-webkit-border-radius: 20px;
    				border-radius: 10px;
					left:-50%;
					top:-200px
				">
				<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...
				</center>
			</div>
			</div>
			<script>
				function cerrarloading(){
					$("#loading").fadeOut(0);
					var divloading="<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...</center>";
					$("#divmsg").html(divloading);
				}
			</script>
</body>
</html>