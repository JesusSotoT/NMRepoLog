<?php
	include_once("../../netwarelog/catalog/conexionbd.php");
	$result = mysql_query("SELECT idUni, compuesto, conversion, unidad FROM mrp_unidades ORDER BY idUni desc LIMIT 1");
	
	if($row = mysql_fetch_assoc($result))
	{
		if($row['unidad'] == 1 && $row['conversion'] == 1234)
		{
			$result = mysql_query("UPDATE mrp_unidades SET unidad = ".$row['idUni'].", conversion=1 WHERE idUni = ".$row['idUni'].";");
		}
	}
?>