<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.number.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="../cont/js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="js/config.js"></script>
	<link rel="stylesheet" href="css/style.css" type="text/css">

</head>
<body>
<div class=" nmwatitles ">Configuracion</div><br>
<div style="width:900px;background: #F2F2F2;">
<div class="nmcatalogbusquedatit" >Informacion</div>
<div id="polizaAutAcontia">
<input type="checkbox" checked="" name="polizaAu" id="polizaAu" style="" align="right" value="1" /><b>Crear/Actualizar Poliza al Guardar Documento</b>
</div>
<br><br/>
<script>
$(document).ready(function(){

<?php
	  if($acontia){?>
	  	$("#polizaAutAcontia").show();
		$("#acontiacheck").show();
		$("#sinAcontia").hide();
<?php }else{ ?>
		$("#polizaAutAcontia").hide();
		$("#acontiacheck").hide();
		$("#sinAcontia").show();
<?php } ?>
});
</script>
<?php
if(!$configuracionBancos->num_rows>0){

?>
<form action="ajax.php?c=Configuracion&f=guardaConfig" method="post" onsubmit=" return validaDatos()">
<input type="hidden" value="<?php echo $acontia; ?>" name="acontiaconf" id="acontiaconf"/>
<div id="acontiacheck">
<input type="checkbox" checked="" id="acontia" name="acontia" style=""  align="right" value="1" onclick="acontiaConfig()" /><b style="color: red">Configuracion acorde a ACONTIA.</b><br><br>
</div>
<br>
<div id="sinAcontia" style="display: none">
	<table cellpadding="2" cellspacing="3" width="30%">
		<tr>
			<td><b>Ejercicio</b></td>
			<td>
				<select id="ejercicio" name="ejercicio" >
					<option value="2013">2013</option>
					<option value="2014">2014</option>
					<option value="2015">2015</option>
				</select>
			</td>
		</tr>
		<tr>	
			<td><b>Periodo Vigente</b></td>
			<td><input type="text" id="vigente" name="vigente" class="nminputtext" /></td>
		</tr>
		<tr>		
			<td><b>RFC</b></td>
			<td><input type="text" id="rfc" name="rfc" class="nminputtext" /></td>
		</tr>
	</table><br>
		<input type="checkbox" checked="" name="periodosabiertos" id="periodosabiertos" value="1"><b>Manejar periodos abiertos</b><br>

<br>

</div>

<br>
<div align="">
	<input class="nminputbutton" type="submit" value="Guardar" onclick="" name="save"><br>
</div>
</form>
<?php 
	
}else{ ?>
<div align="center" >
<?php
 if(!$acontia){ ?>
<input class="nminputbutton" type="button" value="Reiniciar Configuracion"  onclick="reiniciar()" name="reinicio" id="reinicio" style="color: white"><br>
<?php 
}?>
</div><br>
<?php
	if($config = $configuracionBancos->fetch_assoc()){
		if($config['AcontiaConf']==1){
			$visible = "display:none";
			$visible2 = "";
		}else{
			$visible = "";
			$visible2 = "display:none";
		}
		if($config['PolizaAuto']==0){
		?>
		<script>
			$("#polizaAu").attr("checked",false);
		</script>
	<?php } ?>
	
		<form >
			<table style="<?php echo $visible; ?>">
				<tr>
					<td><b>Ejercicio</b></td>
					<td>
						<input type="text" disabled="" value="<?php echo $config['EjercicioActual'];?>" class="nminputtext"  />
					</td>
				</tr>
				<tr>
					<td><b>Periodo Vigente</b></td>
					<td>
						<input type="text" name="vigente"  id="vigente" value="<?php echo $config['PeriodoActual'];?>" class="nminputtext" />
					</td>
				</tr>
				<tr>
					<td><b>RFC</b></td>
					<td><input type="text" id="rfc" name="rfc" value="<?php echo $config['RFC'];?>" class="nminputtext" /></td>
				</tr>
				<tr>	
				<td>
					<?php if($config['PeriodosAbiertos']==1){
						$checa = "checked=''";
						}else{ $checa="";} ?>
						<input type="checkbox" <?php echo $checa;?> name="periodosabiertos" id="periodosabiertos" value="1" ><b>Manejar periodos abiertos</b></td>

				</tr>
				<tr>
					<td><br>
						<input class="nminputbutton" type="button" value="Guardar" onclick="updateInfo()"><br>
					</td>
				</tr>
			</table>

		</form>
		<div style="<?php echo $visible2;?>">
		Configuracion acorde a ACONTIA.<br>
		<input class="nminputbutton" type="button" value="Guardar" onclick="updatePolizaAuto()"><br>
		</div>
<?php 
	}

}
?>

<img src="images/loading.gif" style="display: none" id="load2">
</body>
</html>