<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<link rel="stylesheet" type="text/css" href="css/cheque.css" />
	<script src="js/traspaso.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 

</head>
<body>
	<div class="nmwatitles">&nbsp;Traspaso</b></div><br></br>
	<div class="container" >
		<fieldset style="background: #F2F2F2"><br>
			<div style="width:40%; display:inline-block;border: 2px; ">
				<table>
					<tr>
						<td>Fecha:</td>
						<td>
							<input type="date" id="fechaorigen" onchange="" value="<?php echo date('Y-m-d');?>" />
						</td>
					</tr>
					<tr>
						<td>Documento origen</td>
						<td>
							<select id="documentosbancarios" onchange="informacion();" style="">
								<option value="0">--Seleccione--</option>
							<?php while($b = $documentos->fetch_assoc()){ ?>
								<option value="<?php echo  $b['id']; ?>"><?php echo $b['concepto']." (".$b['folio'].")"; ?> </option>
							<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Folio</td>
						<td>
							<input type="text" size="10" id="folio">
						
						Importe:
						<label id="importe" id="importe">0.00</label>
						</td>
					</tr>
					<tr>
						<td>Beneficiario:</td>
						<td>
							<?php echo $or['nombreorganizacion'];?>
						</td>
					<tr>
						<td>
							Concepto:
						</td>
						<td>
							<input type="text" id="concepto"/>	
						</td>
					</tr>
					<tr>
						<td>Referencia:</td>
						<td>
							<input type="text" id="referencia"/>	
						</td>
					</tr>
				</table>
			</div>
			<div style="display:inline-block; border: 2px">
			<img src="images/flecha.png"  style="width:70px;height: 60px;" />
			</div>
			<div style="width:40%;display:inline-block; float: right; border: 2px">
				<table>
					<tr>
						<td>Fecha:
							<input type="date" id="fecha" onchange="" value="<?php echo date('Y-m-d');?>" />
							<hr class="nmwatitles">
						</td>
					</tr>
					<tr>
						<td>Cuenta Destino:
							<select id="cuenta">
								<option value="0">--Seleccione--</option>
							<?php while($b = $cuentasbancariaslista->fetch_assoc()){ ?>
								<option value="<?php echo  $b['idbancaria']."//".$b['account_id']; ?>"><?php echo $b['nombre']." (".$b['description']."[".$b['manual_code']."])"; ?> </option>
							<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<label class="nmwatitles">Documento a Generar</label><br>
							<input type="radio" name="documento" value="1" id="nodepositado"/>Ingreso No Depositado<br>
							<input type="radio" name="documento" value="2" id="deposito"/>Deposito
						</td>
					</tr>
					
				</table>
				<br>
				<input type="button" id="traspasa" value="Crear Traspaso" onclick="creartraspaso()"/>
			</div>
			
		</fieldset>	
</body>
</html>
