<?php
$strDBHost="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
$strDBUsr="nmdevel";
$strDBPwd="nmdevel";
$strDBName="_dbmlog0000000815";
$objCon = mysqli_connect($strDBHost,$strDBUsr,$strDBPwd,$strDBName);

$strSql = "SELECT * FROM mrp_producto ORDER BY 1";
$rstProducto = mysqli_query($objCon, $strSql);
while($objProducto = mysqli_fetch_assoc($rstProducto)){
	$decPrecio = number_format($objProducto['precioventa'],2);
	$decIVA = 0;
	$valIva = 0;
	$decIEPS = 0;
	$valIEPS = 0;
	echo $objProducto['idProducto'] . " - " . $objProducto['nombre'] . " - " . $decPrecio;
	$strSql = "SELECT pi.valor, i.nombre FROM impuesto i, mrp_producto p LEFT JOIN producto_impuesto pi ON p.idProducto=pi.idProducto WHERE p.idProducto=" . $objProducto['idProducto']  . " AND i.id=pi.idImpuesto ORDER BY pi.idImpuesto DESC;";
	$rstImpuesto = mysqli_query($objCon, $strSql);
	while($objImpuesto=mysqli_fetch_array($rstImpuesto)){
		echo " - " . $objImpuesto[0] . " - " . $objImpuesto[1];
		if($objImpuesto[1]=='IEPS'){
			$decIEPS = number_format($decPrecio * number_format(($objImpuesto[0] / 100),2),2);
			$valIEPS = $objImpuesto[0];
		}
		if($objImpuesto[1]=='IVA'){
			$decIVA = number_format($decPrecio * number_format(($objImpuesto[0] / 100),2),2);
			$valIVA = $objImpuesto[0];
		}
	}
	$decNeto = number_format(($decPrecio + $decIEPS + $decIVA),2);
	echo " - PRECIO FINAL: " . $decNeto;
	$decBruto = ($decNeto / (1 + ($valIEPS/100) + ($valIVA / 100)));
	echo " - PERCIO BRUTO: " . $decBruto;

	


	unset($objImpuesto);
	mysqli_free_result($rstImpuesto);
	unset($rstImpuesto);
	echo "<br />";
}
unset($objProducto);
mysqli_free_result($rstProducto);
unset($rstProducto);

mysqli_close($objCon);
unset($objCon);
?>

