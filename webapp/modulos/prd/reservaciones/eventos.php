<?php
	include_once("../../../netwarelog/catalog/conexionbd.php");

// Consulta las reservaciones
	$q=mysql_query("Select * from com_reservaciones where activo=1");

// Inicializa un array donde se guardaran las reservaciones
	$jsonArray = array();

// Recorre los registros para agregarlos al array
	while($row=mysql_fetch_array($q)){
		if($row["todoeldia"]==1){
			$allday=true;
		}else{
			$allday=false;
		}
	
	// Crea un registro al array
	 	$buildjson = array(
	 		'id'=>$row["id"],
	 		'title' =>$row["titulo"], 
	 		'start' =>$row["inicio"],
	 		'end'=>$row["fin"],
	 		'description'=>$row["descripcion"],
	 		'allDay' =>$allday,
	 		'color'=>$row["color"],
	 		'mesa'=>$row["mesa"]
		);
	 	
	// Agrega un registro al array
	 	array_push($jsonArray, $buildjson);
	}

// Regresa al ajax el array creado
	echo json_encode($jsonArray);
?>