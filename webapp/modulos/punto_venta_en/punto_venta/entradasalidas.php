<?php
include("funcionesPv.php"); 
?>
<!DOCTYPE HTML>
<html lang="es">
<html>
<head>
		<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>		
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script type="text/javascript" src="punto_venta.js" ></script>
		<script type="text/javascript" src="../punto_venta/js/ui.datepicker-es-MX.js"></script>
		<script>
			$(function(){
			
			$("#preloader").hide();
				
			$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	$("#fin").datepicker({dateFormat: "yy-mm-dd"});
	$("#inicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
	  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
		$('#fin').datepicker('setDate', parsedDate);
		$('#fin').datepicker( "option", "minDate", parsedDate);
	}});
				
			});
			
		</script>
</head>	
<body>
	
	<fieldset style="width:95%;"><legend>Filtros</legend>
	
	<table width="95%" align="center">
	<tr>
		<td>Desde:</td><td><input type="text" id="inicio"></td>
		
		<td>Hasta:</td><td><input type="text" id="fin"></td>
		
		<td>Producto:</td><td><?php echo productos();?></td>
		
		<td>Movimiento:</td><td><select id="movimiento"><option  value="">-Todos-</option> <option value="Venta">-Venta-</option> <option value="Compra">-Compra-</option> <option value="Traspaso">-Traspasos-</option> <option value="Ingreso inventario">-Ingreso inventario-</option> </select></td>
		
		
		<td>Resultados:<select id="registros">
			<option value="20">20</option>
			<option value="30">30</option>
			<option value="40">40</option>
			<option value="50">50</option>
			<option value="60">60</option>
			<option value="70">70</option>
			<option value="80">80</option>
			<option value="90">90</option>
			<option value="100">100</option>
</select></td>
		
			<td><input type="button" value="Buscar" onclick="filtramovimientos();" /></td>
			<td><input type="button" value="Limpiar" onclick="Limpiacampos();" /></td>
			
			<td><img src="img/preloader.gif" id="preloader"></td>
	</tr>	
	</table>
		
	</fieldset>
	<br>
	<span id="movimientosmercancia">
	<?php echo entradasalidas(); ?>
	</span>
</body>
</html>