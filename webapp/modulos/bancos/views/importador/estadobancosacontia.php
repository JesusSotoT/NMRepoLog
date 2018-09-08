<!DOCTYPE html>
<head>
	<meta charset="UTF-8" />
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>   
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<link rel="stylesheet" href="css/conciliacion.css" type="text/css">
		<script src="js/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<script type="text/javascript" src="js/importar.js"></script>


<script>
$(document).ready(function(){
	<?php 
	if(isset($_SESSION['bancos']['idbancaria'])){?> 
		$("#cuentabancaria").val(<?php echo $_SESSION['bancos']['idbancaria'];?>).select2({width : "130px"});
		$("#periodo").val(<?php echo $_SESSION['bancos']['periodo'];?>).select2({width : "130px"});
		$("#ejercicio").val(<?php echo $_SESSION['bancos']['idejercicio'];?>).select2({width : "130px"});
		
	<?php
	}
	
	?>
});
</script>	
</head>
<body>
<?php
require("views/importador/conciliadocubanco.php");
?>
<div style="width:98%;background: #F2F2F2;" align="center" class="container well" >	
	<div class="panel panel-default" >
		<div class="panel-heading"  style="height: 46px"><b style="font-size: 16px;">Importar Estado de Cuenta Bancos</b></div> 
	</div>		
	<div class="panel-body" >	
		<form action="index.php?c=importarEstadoCuenta&f=almacenaEstadoBancos" method="post" enctype="multipart/form-data" id="submit" >
			<table>
				<tr>
					<td>Cuenta Bancaria
						<select id="cuentabancaria" name="cuentabancaria">
							<?php 
								while($b=$cuentasB->fetch_array()){ ?>
									<option value="<?php echo  $b['idbancaria']; ?>"><?php echo $b['nombre']." (".$b['cuenta'].")"; ?> </option>
						<?php   } ?>
						</select>
					</td>
					<td>Periodo 
						<select id="periodo" name="periodo">
							<?php while($p = $periodos->fetch_assoc()){?>
								<option value="<?php echo $p['id'];?>"><?php echo $p['mes'];?></option>
							<?php } ?>
						</select>
					</td>
					<td>Ejercicio 
						<select id="ejercicio" name="ejercicio">
							<?php while($p = $ejercicio->fetch_assoc()){?>
								<option value="<?php echo $p['Id'];?>"><?php echo $p['NombreEjercicio'];?></option>
							<?php } ?>
						</select>
					</td>
					
					
				</tr>
			</table><br>
			<div align="left" style=" display: table-cell;">
				<img src='../cont/images/xls_icon.gif'> <a href='plantillabancos.xls'>Descargar plantilla</a>
			</div>
			<br>
			<div style='color: #FF0000;'>(No elimine ninguna columna del formato)</div>
			<table>
				<tr>
					<td colspan="2"><input type="file"  name="archivo"></td>
				</tr>
				<tr><td><br></td></tr>
				<tr>
					<td><button type="button" style="width: 120px" class="btn btn-primary btn-xl"  data-loading-text="Importando<i class='fa fa-refresh fa-spin '></i>" id="antessubmitbancos">Importar</button></td>
					<td><button type="button" class="btn btn-primary" data-loading-text="<i class='fa fa-refresh fa-spin '></i>" id="consultapre">Consultar Previos</button></td>
				</tr>
			</table>	
							
						  	
			Nota:<br>
			·El estado de cuenta no debe rebasar los 900 elementos por carga.<br>
			·No se deben insertar comillas (") ni comillas simples (') en ningún campo<br>
		</form>
		<input type="hidden" id="acontia" value="<?php echo $acontia;?>" />
	</div>
	
	<div class="panel panel-default" >
		<div class="panel-heading"  style="height: 46px"><b style="font-size: 16px;">Conciliacion Bancos-Documentos</b></div> 
			<div class="panel-body" >
				
				<table  class="table table-striped table-bordered" >
					<thead>
						<tr>
							<th colspan="4" align="center" style="background:#F2F2F2 ">Movimientos Conciliados</th>
						</tr>
						<tr>
							<th>Fecha</th>
							<th>Concepto</th>
							<th>Documento</th>
							<th>Importe</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if($listaconciliados){
						while( $val = $listaconciliados->fetch_assoc() ) {?>
						<tr>
							<td><?php echo $val['fecha'];?></td>
							<td><?php echo $val['concepto'];?></td>
							<td><?php echo $val['documento'];?></td>
							<td align="right"><?php echo number_format($val['importe'],2,'.',',');?></td>
						</tr>
						<?php }
						} ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="panel panel-default"  align="center">
			<div class="panel-heading"  style="height: 46px"><b style="font-size: 16px;" >Deslize los documentos correspondientes al Mov. Bancario</b></div> 

			<div class="panel-body" >
				<section>
					<div class="col-md-6">

					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
							<div class="table-responsive">
								<table id="tmovbancos" class="table table-striped table-bordered"  style="min-width: 520px;">
									<thead>
										<tr><th style="border: 0 !important; background-color:#6E6E6E;color:white;font-weight:bold;height:30px;" colspan="6">Movimientos Banco
											<?php if(!isset( $_SESSION['todosConciliados'] ) ){?>
										<img src="../cont/images/mas.png" onclick="sumaDoc()" title="Sumar movimientos bancarios" style="vertical-align:middle;width: 15px;height: 15px">
										<?php } ?>
											</th>
											
										</tr>
										<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
											<th>Fecha</th>
											<th>Referencia</th>
											<th>Concepto</th>
											<th>Depositos</th>
											<th>Retiros</th>
											<th>
												<input type="text" id="buscar" placeholder="Buscar..." align="right" style="color: black" size="12"/>
											</th>
										</tr>
									</thead>
									<tbody style="overflow: auto; display: inline-block; height: 23vw ! important;">
										<?php $cont=0;
										if($pendiente){
										while($row = $pendiente->fetch_assoc()) {$cont++; ?>
										<tr >
											<td style='word-wrap: break-word;' align="center"><?php echo $row['fecha'];?></td>
											<td style='word-wrap: break-word;' align="center"><?php echo $row['folio'];?></td>
											<td style='overflow: hidden; text-overflow: ellipsis; word-wrap: break-word;' align="center"><?php echo $row['concepto'];?></td>
											<td style='word-wrap: break-word;' align="right"><?php echo number_format($row['abonos'],2,'.',',');?></td>
											<td style='word-wrap: break-word;' align="right"><?php echo number_format($row['cargos'],2,'.',',');?></td>
											<td style='' align="center">
												<div id="bancos<?php echo $row['id'];?>" data-role="movbancos" data-value="<?php echo $row['id'];?>" class="droptarget" ondrop="drop(event)" ondragover="allowDrop(event)" style="overflow:scroll;">
												</div>
											</td>
										</tr>
										<?php  }
										}	
										else{ ?>
											<tr>	<td colspan="6" style="background: red;color: white;" align="center">Sin Movimimientos</td></tr>
										<?php }?>
										<tr><td><input type="hidden" value="<?php echo $cont;?>" id="numregistros"/></td></tr>
									</tbody>
									<tfoot>
										<tr>
											<td>
												<?php
												if($pendiente){?>
													<button id="conciliamanual" style=""  class="btn btn-primary" data-loading-text="Conciliando<i class='fa fa-refresh fa-spin '></i>">Conciliar</button>
												<?php } ?>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
					<div class="col-md-6" align="center">
						<table id="" class="table">
							<thead>
								<tr>
									<th align="center" style="border: 0 !important; background-color:#6E6E6E;color:white;font-weight:bold;height:30px;" colspan="2">Documentos Bancarios <img src="../cont/images/reload.png" onclick="window.location.reload()" title="Actualizar Documentos" style="vertical-align:middle;"></th>
								</tr>
							</thead>
							<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;"><div id="circuloingreso" style="display: inline-block"></div>Ingresos</th></tr>
							<tr>
								<td>
									<div style='height:80px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
										<table style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
										<?php
										if($ingresosP){	
											while ($row = $ingresosP->fetch_assoc()){
												 
												if($row['importe']>0){
													echo "<tr><td >";
													echo "<li id=".$row['id']." value=".$row['id']." class=\"out\"  ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style=' background: #FFBF00'>
													[".$row['fecha']."] - [".$row['concepto']."]- [".number_format($row['importe'],2,'.',',')."]</li>";
													echo  "</td></tr>";
												}
											
											}
										}?>
										</table>
									</div>
								</td>
							</tr>
							<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;"><div id="circulodeposito" style="display: inline-block"></div>Depositos</th></tr>
							<tr>
								<td>
									<div style='height:80px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
										<table  style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
											<?php
											if($depositosP){	
												while ($row = $depositosP->fetch_assoc()){
												 
													if($row['importe']>0){
														echo "<tr><td >";
														echo "<li id=".$row['id']." value=".$row['id']." class=\"out\"  ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style=' background: #FA5882'>
														[".$row['fecha']."] - [".$row['concepto']."]- [".number_format($row['importe'],2,'.',',')."]</li>";
														echo  "</td></tr>";
													}
												}
											}?>
										</table>
									</div>
								</td>
							</tr>
							<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;"><div id="circuloegreso" style="display: inline-block"></div>Egresos</th></tr>
							<tr>
								<td>
									<div style='height:80px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
										<table  style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
											<?php
											if($egresosP){	
												while ($row = $egresosP->fetch_assoc()){
												 
													if($row['importe']>0){
														echo "<tr><td >";
														echo "<li id=".$row['id']." value=".$row['id']." class=\"out\"  ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style=' background: #5882FA'>
														[".$row['fecha']."] - [".$row['concepto']."]- [".number_format($row['importe'],2,'.',',')."]</li>";
														echo  "</td></tr>";
													}
												}
											}?>
										</table>
									</div>
								</td>
							</tr>
							<tr><th style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;"><div id="circulocheque" style="display: inline-block"></div>Cheques</th></tr>
							<tr>
								<td>
									<div style='height:80px;overflow:scroll;word-wrap: break-word;' class="" ondrop="drop(event)" ondragover="allowDrop(event)">
										<table  style="word-wrap: break-word;" class="" ondrop="drop(event)" ondragover="allowDrop(event)">
											<?php
											if($chequesP){	
												while ($row = $chequesP->fetch_assoc()){
												 
													if($row['importe']>0){
														echo "<tr><td >";
														echo "<li id=".$row['id']." value=".$row['id']." class=\"out\"  ondragstart=\"dragStart(event)\" ondrag=\"dragging(event)\" draggable='true' style=' background: #FE2EF7'>
														[".$row['fecha']."] - [".$row['folio']."] - [".$row['concepto']."]- [".number_format($row['importe'],2,'.',',')."]</li>";
														echo  "</td></tr>";
													}
												}
											}?>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</section>
				<?php 
				if(!$pendiente && isset($_SESSION['bancos']['idbancaria'])){?>
				<button id="fin" style="" class="btn btn-danger btn-lg" data-loading-text="<i class='fa fa-refresh fa-spin '></i>">Finalizar Conciliacion</button>
				<?php } ?>
			</div>
	</div>

</div>

</body>
</html>