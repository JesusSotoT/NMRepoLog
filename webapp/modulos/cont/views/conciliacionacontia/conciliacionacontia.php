<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.number.js"></script>
	<script src="js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script type="text/javascript" src="js/conciliacionacontia.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
<script>
	function antespdf(){
		$('#datosempresa').show();
		$("#paraimp").show();
		//$("#1,#2,#3,#4").show();
		//$("#saldobanco,#libros,#cargosbanco,#bancodepositos,#misdepositos,#chequescircula").attr("border","1px");
		pdf();
		$('#datosempresa').hide();
		$("#paraimp").hide();
		//$("#1,#2,#3,#4").hide();
		//$("#saldobanco,#libros,#cargosbanco,#bancodepositos,#misdepositos,#chequescircula").attr("border","0px");

	}
$(document).ready(function(){
	<?php
	if($bancos==1){?>
		$("#btnconcilia").attr("onclick","verificaConciliacionBancos();");
		
	<?php } 
	if(isset($_SESSION['movpolizasdescartarnuevosinpoliza'])){ ?>
		volverAdescartar();
	<?php } ?>
});	
</script>
<style>

.datos2{
	background: #F2F2F2;width: 100%;table-layout: fixed;word-wrap: break-word;
}

.listacon td, th
{
	width:158px;
	height:30px;
	text-align: center;
	border:1px solid #BDBDBD;
	word-wrap: break-word;

}
.datos td, th
{
	height:20px;
	word-wrap: break-word;

	
}
.over{
background-color:#91C313;
color:#FFF;
}
.out{
background-color:;
color:;
}
.fila{
	background: #A4A4A4; color: #FFFFFF;font-weight: bold;
}
.fila2{
	background: #848484; color: #FFFFFF;font-weight: bold;
}
 .droptarget {
    float: left; 
    width: 90%; 
    height: 35px;
    margin: 15px;
    padding: 10px;
    border: 1px solid #aaaaaa;
}
#volverdescartar,#sumamov{ display:none}
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
  	h5, h4, h3{
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
    .droptarget {
    	width: 100% !important;
    	margin: 0 !important;
    }
    .table thead, .table tbody tr {
	    display:table;
	    width:100%;
	   	table-layout: fixed;/* even columns width , fix width of table too*/
	}
	.table tfoot{
		display: table;
    	width: 100%;
	}
</style>
</head>
<body>
<?php 
require("views/conciliacionacontia/volverdescartar.php");
require("views/conciliacionacontia/sumamovbancarios.php");
?>

<div class="container">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
			<h3 class="nmwatitles text-center">Conciliacion Bancaria</h3>
			<h4>Estado de cuenta</h4>
			<section>
				<div class="row">
					<div class="col-md-4">
						<label>Importar Estado de Cuenta</label>
						<img style="vertical-align:middle;" title="Abrir Ventana de Importacion de Estado Bancario" onclick="abririmportacion()" src="images/xls_icon.gif">
					</div>
				</div>
			</section>
			<h4>Informacion</h4>
			<section>
				<div class="row">
					<div class="col-md-3">
						<label>Cuenta bancaria:</label>
						<select id="cuentabancaria" name="cuentabancaria">
							<?php 
								while($b=$cuentasB->fetch_array()){
									if($_SESSION['datos']['idbancaria']==$b['idbancaria']){$s = "selected"; }else{$s="";} ?>
									<option value="<?php echo  $b['idbancaria']; ?>" <?php echo $s;?> > <?php echo $b['nombre']." (".$b['cuenta'].")"; ?> </option>
							<?php   } ?>
						</select>
					</div>
					<div class="col-md-3">
						<label>Periodo:</label>
						<select id="periodo" name="periodo">
							<?php while($p = $periodo->fetch_assoc()){
									if($_SESSION['datos']['periodo']==$p['id']){$se = "selected"; }else{$se="";} ?>
								<option value="<?php echo $p['id'];?>" <?php echo $se;?> > <?php echo $p['mes'];?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-3">
						<label>Ejercicio:</label>
						<select id="idejercicio" name="ejercicio">
							<?php while($p = $ejercicio->fetch_assoc()){
									if($_SESSION['datos']['idejercicio']==$p['Id']){$sel = "selected"; }else{$sel="";} ?>
								<option value="<?php echo $p['Id'];?>" <?php echo $sel;?> > <?php echo $p['NombreEjercicio'];?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-3">
						<label>&nbsp;</label>
						<label id="load2" style="display:none;">Espere un momento...</label>
						<input type="button" class="btn btn-primary btnMenu" value="Conciliar" onclick="conciliar();" id="btnconcilia"/>
					</div>
				</div>
			</section>
			<section id="conciliacionhechas">
				<h4>Movimientos conciliados</h4>
				<section>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
							<div class="table-responsive">
								<!--Tabla de contenido conciliados -->
								<table class="listacon table" id="tconcili">
									<thead>
										<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
											<th>Fecha</th>
											<th>Referencia</th>
											<th>Concepto</th>
											<th>Cargos</th>
											<th>Abonos</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($_SESSION['conciliados'] as $row){ ?>
										<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'"  >
													<td align="center"><?php echo $row['fecha'];?></td>
													<td align="center"><?php echo $row['numero'];?></td>
													<td align="center"><?php echo $row['concepto'];?></td>
													<td align="right"><?php echo $row['retiro'];?></td>
													<td align="right"><?php echo $row['deposito'];?></td>
												</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</section>
				<h4>Movimientos no conciliados</h4>
				<section>
					<div class="row" style="margin: 0 !important; background-color: #eee;">
						<div class="col-md-7">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
									<div class="table-responsive">
										<!--Tabla de contenido no conciliados bancos-->
										<table id="tmovbancosd" class="table table-striped table-bordered" style="min-width: 520px;">
											<thead>
												<tr><th style="border: 0 !important; background-color:#6E6E6E;color:white;font-weight:bold;height:30px;" colspan="6">Movimientos Banco
													<?php if(!isset( $_SESSION['todosConciliados'] ) ){?>
												<img src="images/mas.png" onclick="sumaMov()" title="Sumar movimientos bancarios" style="vertical-align:middle;width: 15px;height: 15px">
												<?php } ?>
													</th></tr>
												<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
													<th>Fecha</th>
													<th>Referencia</th>
													<th>Concepto</th>
													<th>Depositos</th>
													<th>Retiros</th>
													<th>
														<input type="text" id="buscarvalor" placeholder="Buscar..." align="right" style="color: black" size="12"/>
													</th>
												</tr>
											</thead>
											<tbody style="overflow: auto; display: inline-block; height: 23vw ! important;">
												<?php $cont=0;
												foreach ($_SESSION['movbancos'] as $row){ $cont++; ?>
												<tr >
													<td style='word-wrap: break-word;' align="center"><?php echo $row['fecha'];?></td>
													<td style='word-wrap: break-word;' align="center"><?php echo $row['numero'];?></td>
													<td style='overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;' align="center"><?php echo $row['concepto'];?></td>
													<td style='word-wrap: break-word;' align="right"><?php echo $row['deposito'];?></td>
													<td style='word-wrap: break-word;' align="right"><?php echo $row['retiro'];?></td>
													<td style='' align="center">
														<div id="bancos<?php echo $row['id'];?>" data-role="movbancos" data-value="<?php echo $row['id'];?>" class="droptarget" ondrop="drop(event)" ondragover="allowDrop(event)" style="overflow:scroll;">
														</div>
													</td>
												</tr>
												<?php  }?>	
												<?php	
													if(isset( $_SESSION['todosConciliados'] ) ){ ?>
													<tr>	<td colspan="6" style="background: red;color: white;" align="center">Todos los movimientos fueron conciliados :)</td></tr>
												<?php }?>
												<tr><td><input type="hidden" value="<?php echo $cont;?>" id="numregistros"/></td></tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
									<div class="table-responsive">
										<table id="" class="table" >
											<thead>
												<tr>
													<th style="border: 0 !important; background-color:#6E6E6E;color:white;font-weight:bold;height:30px;" colspan="2">Mov. Polizas <img src="images/reload.png" onclick="conciliar()" title="Actualizar Polizas" style="vertical-align:middle;"><img src="images/cuentas.png" onclick="volverAdescartar()" title="Descartar Movimientos" style="vertical-align:middle;"></th>
												</tr>
												<tr><th colspan="2" style="white-space: normal;border-bottom: medium none;background-color:#6E6E6E;color:white;font-weight:bold;">Deslize los movimientos correspondientes al Mov. Bancario</th></tr>
											<thead>
											<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;">Cargos</th></tr>
											<tr>
												<td>
													<div style='height:100px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
														<table style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
														<?php	
														foreach ($_SESSION['movpolizas'] as $row){
															if($row['cargo']>0){
																echo "<tr><td >";
																echo "	<li id=".$row['idmov']."  value=".$row['idmov']." class=\"out\"   ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style='background: #81BEF7'>
																[".$row['fecha']."]-[".$row['numero']."]-[".$row['concepto']."]-Cargo[".$row['cargo']."]</li>";
																echo  "</td></tr>";
															}
															
														}?>
														</table>
													</div>
												</td>
											</tr>
											<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">Abono</th></tr>
											<tr>
												<td>
													<div style='height:100px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
														<table  style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
															<?php	
															foreach ($_SESSION['movpolizas'] as $row){
															 
																if($row['abono']>0){
																	echo "<tr><td >";
																	echo "<li id=".$row['idmov']." value=".$row['idmov']." class=\"out\"  ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style=' background: #819FF7'>
																	[".$row['fecha']."]-[".$row['numero']."]-[".$row['concepto']."]-Abono[".$row['abono']."]</li>";
																	echo  "</td></tr>";
																}
															
															}?>
														</table>
													</div>
												</td>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if( isset($_SESSION['Nohaypolizas']) ){ ?>	
					<div class="row">
						<div class="col-md-12">
							<label style="color: red" class="text-center">No tiene Movimientos correspondientes a la cuenta bancaria</label>
						</div>
					</div>
					<?php } ?>
					<div class="row">
						<div class="col-md-3 col-md-offset-7">
							<?php if(isset($_SESSION['movbancos'])){?>
								<input type="button" value="Conciliar Movimientos" class="btn btn-primary btnMenu" id="conciliarmov">
							<?php } ?>
						</div>
						<div class="col-md-2">
							<label style="display: none" id="load">Espere un momento...</label>
							<input  type="button" value="Ir captura"  onclick="capturapoli(<?php echo $_SESSION['datos']['nombreejercicio']; ?>)" class="btn btn-primary btnMenu"/> 
						</div>
					</div>
				</section>
				<h4>Detalle de saldo</h4>
				<section>
					<?php if(isset($_SESSION['datos']['sinsaldo'])){?>
						<div class="row">
							<div class="col-md-6">
								<label>Introduzca su saldo Inicial (DEUDOR(+) ACREEDOR(-)</label>
								<input type="text"  id="sinsaldo" class="form-control" onkeyup="saldoInicial(this.value)"/>
								<label style="color: green; display: none" id="load3">Espere un momento...</label>
							</div>
						</div>
					<?php }	 ?>	
					<div class="row" style="margin: 0;">
						<div class="col-md-6" style="background-color: #eee;">
							<h5 class="text-center" style="border-bottom: 1px solid gray;">Banco</h5>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
									<div class="table-responsive">
										<!-- Cheques en Circulacion -->
										<table class="table" id="chequescircula">
											<thead>
												<tr></tr>
												<tr class="fila2">
													<td colspan="3" align="center">Cheques en Circulacion</td>
												</tr>
												<tr class="fila">
													<td>Fecha</td>
													<td>Numero</td>
													<td>Importe</td>
												</tr>
											</thead>
											<tbody style="overflow: auto; display: inline-block; height: 10vw ! important;">
												<?php if(isset($_SESSION['cheques'])){
													foreach ($_SESSION['cheques'] as $c){?>
														<tr>
															<td><?php echo $c['fecha'];?></td>
															<td><?php echo $c['numero'];?></td>
															<td align="right" class="number"><?php echo $c['abono'];?></td>
														</tr>
													<?php } 
												}?>
											</tbody>
											<tfoot>
												<tr class="fila">
													<td></td>
													<td>TOTAL</td>
													<td id="totalcheque" align="right" class="number">0.00</td>
												</tr>
											</tfoot>
										</table>
										<label id="4" style="display:none"></label>
									 	<!-- fin Cheques en Circulacion -->
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
									<div class="table-responsive">
										<!-- Nuestros Depositos -->
										<table class="table" id="misdepositos">
											<thead>
												<tr></tr>
												<tr class="fila2">
													<td colspan="3" align="center">Nuestros Depositos</td>
												</tr>
												<tr class="fila">
													<td>Fecha</td>
													<td>Concepto</td>
													<td>Importe</td>
												</tr>
											</thead>
											<tbody style="overflow: auto; display: inline-block; height: 10vw ! important;">
												<?php if(isset($_SESSION['misdepositos'])){
													 foreach ($_SESSION['misdepositos'] as $c){?>
														<tr>
															<td><?php echo $c['fecha'];?></td>
															<td><?php echo $c['concepto'];?></td>
															<td align="right" class="number"><?php echo $c['cargo'];?></td>
														</tr>
												<?php } 
													}
												?>
											</tbody>
											<tfoot>
												<tr class="fila">
													<td></td>
													<td>TOTAL</td>
													<td id="totalmideposito" align="right" class="number">0.00</td>
												</tr>
											</tfoot>
										</table>
										<label id="3" style="display:none"></label>
										<!-- fin Nuestros Depositos -->
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
									<div class="table-responsive">
										<table id="saldobanco" class="table">
											</tbody>
											<tr>
												<tr style="background: #FA5858;font-weight: bold;">
													<td colspan="2">Saldo Bancos</td>
													<td id="saldoEstadoCuenta" align="right"><?php echo number_format(@$_SESSION['datos']['saldoEstadoCuenta'],2,'.',','); ?></td>
												</tr>
												<tr>
													<td colspan="2">
														(-)Cheques en Circulación
													</td>
													<td id="chequessaldo" align="right" class="number">0.00</td>
												</tr>
												<tr>
													<td colspan="2">
														(+)Nuestros Deposito 
													</td>
													<td id="depositossaldo" align="right" class="number">0.00</td>
												</tr>
											<tbody>
											<tfoot>
												<tr style="background: #FA5858;font-weight: bold;">
													<td colspan="2">Saldos Iguales</td>
													<td id="saldobancototal" align="right">0.00</td>
												</tr>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6" style="background-color: #eee;">
							<h5 class="text-center" style="border-bottom: 1px solid gray;">Libros</h5>
							<div class="row">
								<div class="col-sm-12 col-md-12 col-xs-12 tablaResponsiva">
									<div class="table-responsive">
										<!-- Cargos del Banco -->
										<table class="table" id="cargosbanco">
											<thead>
												<tr></tr>
												<tr class="fila2">
													<td colspan="3" align="center">Cargos del Banco</td>
												</tr>
												<tr class="fila">
													<td>Fecha</td>
													<td>Concepto</td>
													<td>Importe</td>
												</tr>
											</thead>
											<tbody style="overflow: auto; display: inline-block; height: 10vw ! important;">
												<?php if(isset($_SESSION['bancocargos'])){
												 foreach ($_SESSION['bancocargos'] as $c){?>
													<tr>
														<td><?php echo $c['fecha'];?></td>
														<td><?php echo $c['concepto'];?></td>
														<td align="right" class="number"><?php echo $c['cargos'];?></td>
													</tr>
												<?php } 
												}?>
											</tbody>
											<tfoot>
												<tr class="fila" >
													<td></td><td>TOTAL</td>
													<td id="totalcargosbanco" align="right" class="number">0.00</td>
												</tr>
											</tfoot>
										</table>	
										<label id="2" style="display:none"></label>
										<!-- fin Cargos del Banco -->
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-12 col-xs-12 tablaResponsiva">
									<div class="table-responsive">
										<!-- Depositos del Banco -->
										<table class="table" id="bancodepositos">
											<thead>
												<tr></tr>
												<tr class="fila2">
													<td colspan="3" align="center">Depositos del Banco</td>
												</tr>
												<tr class="fila">
													<td>Fecha</td>
													<td>Concepto</td>
													<td>Importe</td>
												</tr>
											</thead>
											<tbody style="overflow: auto; display: inline-block; height: 10vw ! important;">
												<?php if(isset($_SESSION['bancodepositos'])){
												 foreach ($_SESSION['bancodepositos'] as $c){?>
													<tr>
														<td style='width:100px !important;' ><?php echo $c['fecha'];?></td>
														<td style='width:120px !important;'><?php echo $c['concepto'];?></td>
														<td align="right" style='width:90px !important;' class="number"><?php echo $c['abonos'];?></td>
													</tr>
												<?php } 
												}?>
											</tbody>
											<tfoot>
												<tr class="fila">
													<td></td>
													<td>TOTAL</td>
													<td id="bancodepositostotal" align="right" class="number">0.00</td>
												</tr>
											</tfoot>
										</table>
										<label id="1" style="display:none"></label>
										<!-- fin Depositos del Banco -->
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-12 col-xs-12 tablaResponsiva">
									<div class="table-responsive">
										<table class="table" id="libros">
											<tr>
												<tr style="background: #FA5858; font-weight: bold;">
													<td colspan="2">Nuestro Saldo</td>
													<td id="saldoEmpresa" align="right"><?php echo number_format(@$_SESSION['datos']['saldoEmpresa'],2,'.',','); ?></td>
												</tr>
												<tr>
													<td colspan="2">
														(-) Cargos Bancos
													</td>
													<td id="totalcargosbancosaldo" align="right" class="number">0.00</td>
												</tr>
												<tr>
													<td colspan="2">
														(+)Depositos Bancos
													</td>
													<td id="totalbancodepositosaldo" align="right" class="number">0.00</td>
												</tr>
												<tr style="background: #FA5858; font-weight: bold;">
													<td colspan="2">Saldos Iguales</td>
													<td id="totalnuestrosaldo" align="right" class="number">0.00</td>
												</tr>
											</tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				<section>
					<div class="row">
						<div class="col-md-3 col-md-offset-6">
							<?php if(!isset($_SESSION['datos']['finalizada'])){?>
								<input type="button" value="Finalizar Conciliacion" class="btn btn-primary btnMenu" id="finconciliacion" onclick="finalizaConciliacion()">
							<?php } ?>
						</div>
						<div class="col-md-3">
							<input type="button" value="Salir" class="btn btn-danger btnMenu" id="finconciliacion" onclick="Salir()">
						</div>
					</div>
				</section>
			</section>
			<section id="descartarmov">
				<?php
					// movimientos sin conciliar para descartar
					if(isset($_SESSION['datos']['sinconciliacion']) && (!isset($_SESSION['movpolizasdescartar'])) && (!isset($_SESSION['datos']['finalizada'])) ){ ?>
						<script>
							$("#conciliacionhechas").hide();
						</script>
						<section id="filtro">
							<h4>Movimientos sin conciliar para descartar</h4>
							<div class="row">
								<div class="col-md-12">
									<p class="bg-danger">
										<span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'>
	            						</span>Seleccione el rango de fechas de movimientos de polizas para descartar.
	            					</p>
								</div>
							</div>
							<div class="row">
								<form action="index.php?c=conciliacionAcontia&f=descartar" method="post">
									<div class="col-md-3">
										<label>Desde:</label>
										<input type="hidden" name="nameejercicio"  value="<?php echo $_SESSION['datos']['idejercicio']; ?>"/>
										<input type="hidden" name="periodo" value="<?php echo $_SESSION['datos']['periodo']; ?>"/>
										<input  type="date" class="form-control" id="desde" name="desde" />
									</div>
									<div class="col-md-3">
										<label>Hasta:</label>
										<input type="hidden" value="<?php echo $_SESSION['datos']['idbancaria'];?>"  name="idbancaria"/>
										<input  type="date" class="form-control" id="hasta" name="hasta"/>
									</div>
									<div class="col-md-3">
										<label>&nbsp;</label>
										<input type="submit" value="Consultar" class="btn btn-primary btnMenu">
									</div>
									<div class="col-md-3">
										<label>&nbsp;</label>
										<input type="button" value="Conservar Todos" class="btn btn-primary btnMenu" onclick="conservar()">
									</div>
								</form>
							</div>
						</section>
				<?php 
					}
					if( (isset($_SESSION['datos']['sinconciliacion'])) && (isset($_SESSION['movpolizasdescartar'])) ){ ?>
						<script>$("#conciliacionhechas").hide();</script>
						<h4>Movimientos sin conciliar para descartar</h4>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive"  style="overflow:scroll; height:300px;">
									<!--Tabla de contenido conciliados -->
									<table class="listacon table">
										<thead>
										<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
											<th align="center">Fecha</th>
											<th align="center">No. Poliza</th>
											<th align="center">Referencia</th>
											<th align="center">Concepto</th>
											<th align="right">Cargos</th>
											<th align="right">Abonos</th>
											<th style='font-weight:bold;font-size:9px;text-align:center;color: black'>
												<button id="todos" onclick="buttonclick('descartar')" >Todos</button>
												<button id="todos" onclick="buttondesclick('descartar')">Desmarcar</button>
											</th>
										</tr>
										</thead>
										<tbody>
											<?php $cont=1;
											foreach ($_SESSION['movpolizasdescartar'] as $row){?>
												<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'"  >
													<td align="center"><?php echo $row['fecha'];?></td>
													<td align="center"><?php echo $row['numpol'];?></td>
													<td align="center"><?php echo $row['numero'];?></td>
													<td align="center"><?php echo $row['concepto'];?></td>
													<td align="right"><?php echo $row['cargo'];?></td>
													<td align="right"><?php echo $row['abono'];?></td>
													<td align="right">
														<input title='r' type='radio' name='radio-<?php echo $cont;?>' id='descartar-<?php echo $cont;?>' value='<?php echo $row['idmov'];?>' class='descartar'>
													</td>
												</tr>
											<?php $cont++; } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 col-md-offset-3">
							</div>
							<div class="col-md-3">
								<label style="display:none" id="loaddescar2">Espere un momento...</label>

								<input type="button" value="Descartar" class="btn btn-primary btnMenu" id="finconciliacion2" onclick="descartar()">
							</div>
							<div class="col-md-3">
								<input type="button" value="Conservar Todos" class="btn btn-primary btnMenu" onclick="conservar()">
							</div>
						</div>
				<?php 
					}  
					echo $_SESSION['movpolizasdescartarnuevo']; 
				?>
			</section>
		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>

<script>
	function finalizaConciliacion(){
		var saldoinicial=0;
<?php if(isset($_SESSION['datos']['idbancaria'])){ ?>
		if(confirm("Esta seguro de Finalizar la Conciliacion")){
			<?php if(isset($_SESSION['movbancos'])){ ?>
					alert("Aun tiene movimientos bancarios sin conciliar.");
					return false;
			<?php }else{
						if(isset($_SESSION['datos']['saldoEmpresainicial'])){ ?>
							saldoinicial = <?php echo $_SESSION['datos']['saldoEmpresainicial']; ?>;
			<?php	    }else{ ?> 
							//saldoinicial = $("#sinsaldo").val();
			<?php 		} ?>
				
			if($("#totalnuestrosaldo").html()!= $("#saldobancototal").html()){
				alert("Los saldos no son iguales \n Valide su informacion");
					return false;
			}else{
				fin(saldoinicial);
			}	
				
			<?php  } ?>
		}
<?php }else{ ?>
		alert("No ha conciliado ningun Movimiento");
		
	<?php } ?>
	}
function fin(saldoinicial){
	$.post("ajax.php?c=conciliacionAcontia&f=finalizaConciliacion",{
		saldoinicial:saldoinicial,
		saldofinal: replaceAll($("#totalnuestrosaldo").html(),",",""),
		idbancaria:$("#cuentabancaria").val(),
		periodo:$("#periodo").val(),
		ejercicio:$("#idejercicio").val()
	},function (resp){
		if(resp){
			alert("Conciliacion Finalizada.");
			window.location.reload();
		}else{
			alert("Error al finalizar conciliacion intente de nuevo.");
		}
	});
}
function conservar(){
	$("#conciliacionhechas").show();
	$("#descartarmov,#filtro").hide();
	
}



</script>

</body>
</html>

