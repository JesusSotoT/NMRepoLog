<?php include("funcionesPv.php"); ?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Pagar caja</title>
	<meta charset="utf-8" />

	<link rel="stylesheet" href="punto_venta.css" />
	<script type="text/javascript" src="punto_venta.js" ></script>
</head>
<body >

<fieldset><legend>Filtros art&iacute;culo</legend>
<table width="100%" style="font-size:12px;">
	<tr>	
			<td>Departamento</td><td><?php echo departamentos();?></td>
			<td>Familia</td><td><span id="span-familias"><?php echo familias();?></span></td>
			<td>L&iacute;nea</td><td><span id="span-lineas"><?php echo lineas();?></span></td>
	</tr>  
</table>	
	</fieldset>
		
<table width="100%" style="font-size:12px;">		
<tr>	
	<td  align="center">Art&iacute;culo<span id="span-productos"><?php echo productos();?></span></td>
</tr>
</table>


<span id="detalle-producto">

<table width="100%" style="font-size:12px;" border="0">		
<tr>	
	<td><h1></h1></td> <td rowspan="3" width="250"><img src="../mrp/images/noimage.jpeg"  width="250" height="300" /></td>
</tr>
<tr>	
	<td style="text-align: justify;"></td>
</tr>	
<tr>	
	<td><h2></h2></td>
</tr>
</table>
		
	</span>	
</body>
</html>