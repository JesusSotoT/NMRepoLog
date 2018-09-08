<?php

  include("../catalog/conexionbd.php");

   for($i=0;$i<=1000;$i++){
		$sql = "insert into estados (nombreestado) values ('texto".$i."')";
		$conexion->consultar($sql);
   }

   $conexion->cerrar();

?>