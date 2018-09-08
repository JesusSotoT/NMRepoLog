<?php
	//echo $sql;
	$datos = explode("\n",$sql);
	for($i = 0; $i < count($datos); $i++)
	{
		$datos[$i] = trim($datos[$i]);
		
		if($i == 11 && $datos[$i] == "AND p.id = 0")
		{
			//echo "<hr /> Entro!!!";
			$datos[$i] = "";
		}
		else
		{
			$datos[12] = '';
		}
	//	echo $datos[$i] . "{$i} <br />";
	}
	$sql = implode(" ",$datos);
	//$datos2 = explode(" ",$datos[8]);
	echo $sql;
//	var_dump($datos);
?>