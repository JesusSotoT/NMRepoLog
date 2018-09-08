<?php include("funcionesPv.php"); ?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Punto de venta</title>
	<meta charset="utf-8" />
	<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="punto_venta.css" />
	
	<script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
	<script type="text/javascript" src="../punto_venta/js/ui.datepicker-es-MX.js"></script>
	<script type="text/javascript" src="punto_venta.js" ></script>
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
	<h3>Registro de ventas</h3>
	
	<fieldset style="width:98%;"><legend>Filtros</legend>
	
	<table width="95%" align="center">
	<tr>
		<td>Desde:</td><td><input type="text" id="inicio" readonly="readonly"></td>
		
		<td>Hasta:</td><td><input type="text" id="fin" readonly="readonly"></td>
		
		<td>Cliente:<?php echo clientes(); ?></td>
		<td>Vendedor:<?php echo vendedores(); ?></td>
		
		<?php
					 //si es simple//
		if(simple()){	  
	  //end si es simple//	
		?>
		<td>Sucursal:<?php echo sucursales(); ?></td>
		
		<?php
					 //si es simple//
		}  
	  //end si es simple//	
		?>
		
		<td>Estatus:<select id="estatus"><option value="">-Todos-</option><option value="1">Activa</option><option value="0">Cancelada</option></select></td>
		
		
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
		
		
			<td><input type="button" value="Buscar" onclick="filtraventas();" /></td>
			<td><input type="button" value="Limpiar" onclick="Limpiaventas();" /></td>
			
			<td><img src="img/preloader.gif" id="preloader"></td>
	</tr>	
	</table>
		
	</fieldset>
	<br>
	<span id="ventas"><?php echo ventas(); ?></span>
</body>
<div id="caja-dialog-confirmacion"></div>
<div id="caja-dialog"></div>


</html>	