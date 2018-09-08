<?php 
include_once("../../netwarelog/catalog/conexionbd.php"); 
include("funcionesPv.php"); 
$producto=datosArticulo($_POST["idArticulo"]);
$cantidadproductocaja=cantidadProductocaja($_POST["idArticulo"]);

$articulo=$_SESSION['caja'][$_POST["idArticulo"]];

$descuento=$articulo->descuento;
$tipodescuento=$articulo->tipodescuento;

$materiales="";
			$querycompuesto=mysql_query("select p.esreceta, p.nombre as producto,pm.cantidad,pm.idUnidad,pu.compuesto from mrp_producto_material pm inner join mrp_unidades pu on  pm.idUnidad=pu.idUni 
inner join mrp_producto p on p.idProducto=pm.idMaterial 
where pm.idProducto=".$_POST["idArticulo"]);
			if(mysql_num_rows($querycompuesto)>0)
			{
				$materiales.="Este producto contiene:<br><ul>";	
				$propiedad='style="font-size:18px;font-weight:bold;"';	
				while($rowcompuesto=mysql_fetch_array($querycompuesto))
				{
					if($rowcompuesto["esreceta"]==0)
					{		
						$materiales.="<li>".$rowcompuesto["cantidad"]." ".$rowcompuesto["compuesto"]." ".$rowcompuesto["producto"]."</li>";
					}
				}
				$materiales.="</ul>";
			}
			

?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Editar producto</title>
	<meta charset="utf-8" />


	<link rel="stylesheet" href="punto_venta.css" />
	<script type="text/javascript" src="punto_venta.js" ></script>
	<script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>

	<script>
	$(function(){ 

		$('#cantidadarticulo,#descuento-producto').bind('copy paste', function (e) {
       e.preventDefault();
    });
		
		$("#cantidadarticulo").numeric({allow:"."});
		
		
		$("#descuento-producto").numeric({allow:"."});
		});	
		
	</script>
</head>
<body >

<table width="100%" style="font-size:12px;" border="0">		
<tr>	
	<td width="60%" align="center"><h1><?php echo $producto->nombre; ?></h1></td> 
	<td rowspan="6" width="250">
		<?php if(strlen($producto->imagen)>3){?>
		<img src="../mrp/<?php echo $producto->imagen; ?>" width="250" height="300" />
		<?php } else {?>
		<img src="../mrp/images/noimage.jpeg" width="250" height="300" />	
		<?php } ?>
	</td>
</tr>
<?php if($producto->tipo_producto==6){ ?>
<tr>	
	<td width="60%"><textarea id="txtComentario" placeholder="Escribe un comentario" style="width:318px; height:120px;"></textarea></td> 
	<td rowspan="6" width="250">
		
	</td>
</tr>
<?php }else{ ?> <input type="hidden" id="txtComentario" value=""> <?php } ?>
<tr>	
	<td style="text-align:justify;"><?php echo $materiales; ?>
	</td>

<tr>	
	<td style="text-align:justify;"><?php echo $producto->deslarga; ?>
	</td>
</tr>	
<tr>	
	<td align="center">
	<span id="subtitulo" style="font-weight: bold;font-size:18px; color:blue;">
	Precio:$<?php echo number_format($producto->precioventa,2,".",","); ?>	
	</span>
	<span style="font-weight: bold;font-size:12px;">
	Descuento:<input id="descuento-producto" type="text" maxlength="6" style="width:40px;" value="<?php if($descuento!=0){ echo $descuento;}?>">
	
	<?php
		switch ($tipodescuento)
		{
			case '$': $propiedad1='selected="selected"'; $propiedad2="";break;
			case '%': $propiedad2='selected="selected"'; $propiedad1="";break;
			default: $propiedad1='selected="selected"'; $propiedad2="";break;
		}

	
	?>
	
	
	<select id="tipodescuento" style="width: 40px;"><option  <?php echo $propiedad1;?> value="$">$</option><option <?php echo $propiedad2;?> value="%">%</option></select>
	</span>	
	</td>
</tr>


<?php
 //si es simple//
		if(simple()){	  
	  //end si es simple//	
?>
	  
<tr><td align="center"><b style="color:green;">Existencia:<?php echo existenciaproducto($_POST["idArticulo"],$_POST["almacen"]);?></b></td></tr>

<?php
 //si es simple//
		}  
	  //end si es simple//	
?>



<tr>	
	<td align="center"><div id="labelcantidadarticulo">Cantidad</div>
		<input type="hidden" id="cantidadanterior" value="<?php echo $cantidadproductocaja; ?>">
		<input type="hidden" id="idproducto-e" value="<?php echo $_POST["idArticulo"]; ?>">
		<input type="text"  maxlength="6" placeholder="<?php echo $cantidadproductocaja; ?>" id="cantidadarticulo"  /></td>
</tr>
</table>
		
		
</body>
</html>
