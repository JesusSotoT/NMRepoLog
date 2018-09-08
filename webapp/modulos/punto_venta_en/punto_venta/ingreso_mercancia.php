<?php include("funcionesPv.php"); ?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Ingreso mercancia</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script src="../../../modulos/mrp/js/jquery.numeric.js"></script>	
	<link rel="stylesheet" href="punto_venta.css" />
	<script type="text/javascript" src="punto_venta.js" ></script>
	<script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
	<script>
		
		$(function(){  
			$(".num").numeric(); 
			$(".float").numeric({allow:"."}); 
		})
		$(".float").numeric();
		$('.float').bind("cut copy paste",function(e) {
			e.preventDefault();
		})

		function validaneg(){
			if( $("#cantidad").val()<0){
				alert("No puedes tener numero negativos.");
				$("#cantidad").val(0).focus();
			}
		}
	</script>
</head>
<body>
	
	<table width="90%" border="0" align="center">
		<tr><td>
		
		
	<p style="font-size: 12px; font-weight: bold; margin: 15px;">
	Seleccione el producto  para poder ver sus existencias en almacen y posteriormente ingrese la cantidad que desea ingresar a inventario.	
	</p>	
		
	<fieldset><legend>Filtros producto</legend>
<table width="100%" style="font-size:14px;">
	<tr>	
			<td>Departamento</td><td><?php echo departamentos2();?></td>
			<td>Familia</td><td><span id="span-familias"><?php echo familias2();?></span></td>
			<td>L&iacute;nea</td><td><span id="span-lineas"><?php echo lineas2();?></span></td>
	</tr>  
</table>	
	</fieldset>
		
<table width="100%" style="font-size:14px;">		
<tr>	
	<td  align="center"><strong>*Producto</strong><span id="span-productos"><?php echo productosexistencias();?></span><img src="img/preloader.gif" id="preloader"></td>
</tr>
</table>

<br>
<span id="detalle-producto">
<?php echo existenciasSucursal(0);?> 	
</span>		
	

<br>
<table width="100%" border="0" align="center">
	<tr>	
		<td  align="center" ><strong>*Sucursal:</strong></td><td width="45%"><?php echo sucursales();?></td>
		<td  align="center"><strong>*Cantidad:</strong></td><td><input type="text" id="cantidad" class="float" onblur="validaneg();" /></td>
		<td  align="center"></td>
	</tr>
	
		<tr>	
		<td  align="center" ><strong>Proveedor:</strong></td><td width="45%"><?php echo proveedores();?></td>
		<td  align="center"><strong>Costo:</strong></td><td><input type="text" id="costo" class="float" /></td>
		<td  align="center"><input type="button" value="Ingresar producto" onclick="Inproduct();" /><img src="img/preloader.gif" id="preloader2"></td>
	</tr>
</table>	
	
	
	
	</td>
	</tr>
	</table>
	
	
</body>
</html>	

<script>
	$("#preloader").hide();
	
	$("#preloader2").hide();  
	
</script>	