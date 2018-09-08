<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>   
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script src="js/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
		<script type="text/javascript" src='js/auxiliarbene.js'></script>
	</head>
	<body>
		<div style="width:50%;background: #F2F2F2;" align="center" class="container well" >	
			<div class="panel panel-default" >
				<div class="panel-heading"  style="height: 46px"><b style="font-size: 16px;">Auxiliar por Beneficiario / Pagador</b></div> 
					<div class="panel-body" >	
						<form action="index.php?c=Calendario&f=reporteAuxiliar" id="filtro" method="post">
						<ul class="list-group" >
							<li class="list-group-item">
								<input type="checkbox" name="clicheck" id="clicheck" onclick="muestracli()" />
								<input type="hidden" value="0" id="beneficiario" name="beneficiario">
								<b style="font-size: 14px">Clientes</b>
								<input type="checkbox" name="provecheck" id="provecheck" onclick="muestrapro()" />
								<b style="font-size: 14px">Proveedores</b>
								<input type="checkbox" name="emplecheck" id="emplecheck" onclick="muestraempleados()" />
								<b style="font-size: 14px">Empleados</b>
								<br>
								<select id="prove"  name="prove[]" multiple="" onchange="anular('prove')" >
									<option value="0">--Todos--</option>
									<?php while($prove = $provee->fetch_array()){ ?>
									<option value="<?php echo $prove['idPrv']; ?>" ><?php echo $prove['razon_social']?></option>
									<?php } ?>
								</select>
								<select id="cliente" name="cliente[]" multiple="" onchange="anular('cliente')"  >
									<option value="0">--Todos--</option>
									<?php while($cli = $cliente->fetch_array()){ ?>
									<option value="<?php echo $cli['id']; ?>" ><?php echo $cli['nombre']?></option>
									<?php } ?>
								</select>
								<select id="empleado" name="empleado[]" multiple="" onchange="anular('empleado')" >
									<option value="0">--Todos--</option>

									<?php while($emp = $empleado->fetch_array()){ ?>
									<option value="<?php echo $emp['idEmpleado']; ?>" ><?php echo $emp['nombreEmpleado']." ".$emp['apellidoPaterno']?></option>
									<?php } ?>
								</select>
							</li>
							<li class="list-group-item">
								<label class="control-label" ><b style="font-size: 14px">Del</b></label>
								<input type="date" id="fechainicio" name="fechainicio" class="form-control" style="width:160px; "> 
							</li>
							<li class="list-group-item">
								<label class="control-label" ><b style="font-size: 14px">Al</b></label>
								<input type="date" id="fechafin" name="fechafin" class="form-control" style="width:160px; ">
							</li>
							<li class="list-group-item">
								<label class="control-label" ><b style="font-size: 14px">Moneda:</b></label><br>
								<select id="moneda" name="moneda"  onchange="cuentasPorMoneda()">
									<option value="0" selected="">--Seleccione--</option>
									<?php while($moni = $moneda->fetch_array()){ ?>
									<option value="<?php echo $moni['coin_id']; ?>" ><?php echo $moni['description']?></option>
									<?php } ?>
								</select>
								<i class='fa fa-refresh fa-spin ' id="progres" style="display: none"></i>
							</li>
							<li class="list-group-item">
								<label class="control-label" ><b style="font-size: 14px">Cuenta:</b></label><br>
								<select id="cuenta" name="cuenta"   >
								<option value="0" selected="">--Seleccione moneda--</option>

								</select>
							</li>
							
							<!-- <li class="list-group-item">
								<input type="radio" value="2" name="tipo" />Detalle
							</li> -->
							<li class="list-group-item">
								<input type="checkbox" value="3" id="checkpro" onclick="cambiacheck();" />Incluir No Depositados del Periodo
								<input type="hidden" value="0" id="proyectados" name="proyectados">
							</li>
							<li class="list-group-item">
								<input type="checkbox" value="1" id="checkcobro" onclick="cambiacobro();" />Incluir Cheques No Cobrados
								<input type="hidden" value="1" id="cobrados" name="cobrados">
							</li>
							<li class="list-group-item">																
								<button type="button" class="btn btn-primary btn-lg " id="load" style="width: 200px" data-loading-text="Consultando<i class='fa fa-refresh fa-spin '></i>">Consultar</button>
							</li>
						</ul>	
						</form>	
			</div>
		</div>
	
	</body>
</html>