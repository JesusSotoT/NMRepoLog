<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/importar.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

</head>
<body>
	<div class=" nmwatitles ">Importar Estado de Cuenta</div><br>
		<form action="index.php?c=importarEstadoCuenta&f=almacenaEstado" method="post" enctype="multipart/form-data" >

		<div style="-moz-box-shadow: 0 0px 5px #848484;-webkit-box-shadow: 0 0px 5px #848484;box-shadow: 0 0px 5px #848484; width: 50%;background:#F2F2F2" >
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
				<div  style='display: table; ' >
					<div>
						<div align="left" style=" display: table-cell;">
							<img src='images/xls_icon.gif'> <a href='plantillabancos.xls'>Descargar plantilla</a>
						</div>
						<br>
						<div style='color: #FF0000;'>(No elimine ninguna columna del formato)</div>
						<table>
							<tr>
								<td><input type="file"  name="archivo"></td>
								<td><input type="button" value="Importar" class="nminputbutton" onclick="valida()" id="antessubmit"></td>
								<td style="display: none"><input type="submit" value="Importar" class="nminputbutton" id="submit"></td>
								<td><img src="images/loading.gif" style="display: none" id="load"></td>
							</tr>
						</table>	
							
						  	
					</div>
				</div>
			Nota:<br>
			·El estado de cuenta no debe rebasar los 900 elementos por carga.<br>
			·No se deben insertar comillas (") ni comillas simples (') en ningún campo<br>
		</div>
		</form>

</body>
</html>