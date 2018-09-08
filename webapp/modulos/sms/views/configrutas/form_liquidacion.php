<?php 
include("../../../../modulos/sms/funcionesBD/gridliquidacionrutas.php");
$encabezado=datosRuta($_GET["idr"]); 
?>
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
<script src="../../../../modulos/sms/js/paginaciongridliquidacion.js"></script>

</head>
<body >		
<span id="encabezado">
<table width="100%" align="center" border=3 bgcolor="#91C313" style="color:#FFFFFF">
	<tr>
		<td align="left"><h2>Oferta:<?php echo $encabezado["oferta"]; ?></h2></td>
		<td align="center"><h2>Ruta:<?php echo $encabezado["nombreruta"]; ?></h2></td>
		<td align="right"><h2>Transporte:<?php echo $encabezado["transporte"]; ?></h2></td>
	</tr>	
</table>		
</span>

					
<span id="grid"><?php  echo LiquidacionRuta($_GET["idr"],$_GET["oferta"]);?></span>
<div id="dialog"></div>
</body>
</html> 