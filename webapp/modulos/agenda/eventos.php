<?php
	include_once("../../netwarelog/catalog/conexionbd.php");
	$q=mysql_query("Select * from agenda where activo=1");

$jsonArray = array();
while($row=mysql_fetch_array($q))
{
	if($row["todoeldia"]==1){$allday=true;}else{$allday=false;}
 $buildjson = array('id'=>$row["id"],'title' =>$row["titulo"], 'start' =>$row["inicio"],'end'=>$row["fin"],'description'=>$row["descripcion"],'allDay' =>$allday,'color'=>$row["color"]);
 array_push($jsonArray, $buildjson);
    }
echo json_encode($jsonArray);
		