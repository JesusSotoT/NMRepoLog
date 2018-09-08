<?php
	include("../../../netwarelog/webconfig.php");
	$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	$suspendidas = $connection->query("SELECT id FROM venta_suspendida WHERE borrado=0 AND s_empleado =" . $_SESSION['accelog_idempleado'] . ";" );
	$connection->close();
	echo $suspendidas->num_rows;
?>