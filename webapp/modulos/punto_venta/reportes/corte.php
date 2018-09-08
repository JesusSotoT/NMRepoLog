<?php
@session_start();
$usuario=$_SESSION['accelog_idempleado'];
  include("../../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
date_default_timezone_set("Mexico/General");
$fecha=date("Y-m-d H:i:s");
$sql=$conection->query('select v.idVenta,i.id from venta v,
inicio_caja i where i.idCortecaja is null and i.idUsuario=v.idEmpleado and v.idEmpleado='.$usuario.'
and v.fecha 
BETWEEN i.fecha and  "'.$fecha.'"
 ORDER BY i.id desc LIMIT 1');
 if($sql->num_rows>0){//si hay ventas
 	echo 'si';
 }else{
 	echo 'no';
 }

?>