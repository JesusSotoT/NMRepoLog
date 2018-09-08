<!DOCTYPE html>
<html>
	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
	<LINK href="../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
	<LINK href="../../../netwarelog/design/default/netwarlog.css" rel="stylesheet" type="text/css" />

<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<body>
	<?php 
		include("../../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd); ?>
<div class="nmwatitles">Cambio de par&aacute;metros del punto de venta</div>
<h3 align="center" style="font-size:10pt; color:#6E6E6E">El recuadro muestra el par&aacute;metro actual,reemplaze por el nuevo y guarde los cambios.</h3>
<br></br>
<div align="center">
<fieldset style=" font-size:12px;font-weight:bold; color:#6E6E6E;vertical-align:top; display:inline-block;">
	<legend>Cambio de IVA</legend>
		<table>
			
			<th>
	        I.V.A.
			</th>
			<tr>
				<td>
					<?php 
					$iva=$conection->query("select * from parametros_pv");
					if($ivaa=$iva->fetch_array(MYSQLI_ASSOC)){ ?>
					<input type="text" id="iva" value=<?php echo $ivaa['iva']; ?> class="nminputtext">
				<?php	}
					?>
				</td>
				<td>
					<input type="button" value="Guardar" onclick="cambio();" class="nminputbutton_color2" />
				</td>
			</tr>
		</table>
</fieldset>
</div>
</body>
<script type="text/javascript">
	function cambio(){
		var iva=jQuery('#iva').val();
		$.post("../consultalmacen.php",{opc:3,iva:iva},
	function(respues) {
		
		alert(respues),window.location.reload();
		
		
   	});	
	}
</script>
</html>
