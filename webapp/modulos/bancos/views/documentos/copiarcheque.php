<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/copiardocumento.js"></script>
	<script>
		$('#cuentacopi').select2({
    	width : "150px"	
    	});
	</script>
</head>
<body>
	<img class="nmwaicons" src="images/cierra.png" style="width: 15px;height: 15px; " align="right" title="Cerrar" onclick="cierra()" >
	<div class="nmwatitles">Copiar Documento</b></div><br>
	<table>
		<tr>	
		<td>Cuenta</td>
		<td>	
			<select id="cuentacopi" onchange="numerocopia()" >
				<option value="0">------</option>
				<?php while($b=$listabanaria->fetch_array()){ ?>
				<option value="<?php echo $b['idbancaria']; ?>"><?php echo $b['nombre']." (".$b['description']."[".$b['manual_code']."])"; ?></option>
				<?php } ?>
			</select>
		</td>
		</tr>
		<tr><br>
		<td>Numero:</td>
		<td>
			<input type="text" id="numerocopi" style="width: 50px" />
			<img src="images/reload.png" onclick="actualcopi()" title="Numero Actual">
		</td>
		</tr>
		<tr><td align="center"><br><input type="button" value="Copiar" onclick="guardacopi()" class="btn-primary" align="center"/>	</td></tr>
	</table><br>
	
</body>
</html>