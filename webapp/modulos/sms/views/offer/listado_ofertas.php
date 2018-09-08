<?php include("../../funcionesBD/grid.php") ?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html lang="sp">
	<head>						
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>	
	<LINK href="../../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
	<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>		
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="../../../sms/js/ui.datepicker-es-MX.js"></script>
	<script src="../../js/paginaciongrid.js"></script>
		<script>
	
	$(function(){
	$("#preloader").hide();	
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	$("#ffin").datepicker({dateFormat: "yy-mm-dd"});
	$("#finicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
	  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
		$('#ffin').datepicker('setDate', parsedDate);
		$('#ffin').datepicker( "option", "minDate", parsedDate);
	}});
	
		
	
	});
	
</script>
	</head>
	<body>
<?php
	if(!isset($_SESSION)) 
	{
		session_start();
	}
			
	if (isset ($_SESSION["contador_clientes"])) unset($_SESSION["contador_clientes"]);
    if (isset ($_SESSION["id_array"])) unset($_SESSION["id_array"]);
    if (isset ($_SESSION["nombre_array"])) unset($_SESSION["nombre_array"]);
    if (isset ($_SESSION["direccion_array"])) unset($_SESSION["direccion_array"]);
    if (isset ($_SESSION["giro_array"])) unset($_SESSION["giro_array"]);
    if (isset ($_SESSION["rubro_array"])) unset($_SESSION["rubro_array"]);
    if (isset ($_SESSION["check_array"])) unset($_SESSION["check_array"]);
?>	



<fieldset><legend>Filtro de busqueda por fecha de promoción</legend>	
<table>
	<tr>
		<td><label>Fecha inicio</label><input type="text" readonly="" id="finicio" /></td>
		<td><label>Fecha fin</label><input type="text" readonly="" id="ffin" /></td>
		<td><label><input type="button" value="Buscar promoción" onclick="buscapromocion();" /></td>
		<td><img id="preloader" src="../../../../modulos/mrp/images/preloader.gif">	</td>
	</tr>
</table>	
</fieldset>	
				
	<span id="grid"> <?php echo gridOfertas();?></span>
	</body>
	</html> 

