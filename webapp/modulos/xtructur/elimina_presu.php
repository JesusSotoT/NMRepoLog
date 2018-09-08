<?php
	include('conexiondb.php');
	$id_obra=$_POST['id_obra'];
	$mysqli->query("DELETE FROM constru_recurso WHERE id_obra='$id_obra';");
	$mysqli->query("DELETE FROM constru_presupuesto WHERE id_obra='$id_obra';");
	$mysqli->query("UPDATE constru_agrupador SET borrado=1 WHERE id_obra='$id_obra';");
	$mysqli->query("UPDATE constru_especialidad SET borrado=1 WHERE id_obra='$id_obra';");
	$mysqli->query("UPDATE constru_area SET borrado=1 WHERE id_obra='$id_obra';");
	$mysqli->query("UPDATE constru_partida SET borrado=1 WHERE id_obra='$id_obra';");
	$mysqli->query("DELETE FROM constru_asignaciones WHERE id_obra='$id_obra';");
	$mysqli->query("DELETE FROM constru_vol_tope WHERE id_obra='$id_obra';");

		$pass=$_POST['pass'];
		date_default_timezone_set('America/Mexico_City');
		 $fecha=date('Y-m-d H:i:s');
		$idusr = $_SESSION['accelog_idempleado'];
		   
		$SQL = "SELECT nombreusuario as username, idempleado from administracion_usuarios where idempleado='$idusr';";
    $result = $mysqli->query( $SQL ) ;
$row = $result->fetch_array();
 $nombre=$row['username'];
    $id_username_global=$row['idempleado'];

    	$mysqli->query("INSERT INTO constru_superadmin(fecha,modulo,usuario,idusuario,pass,accion,idobra) values('$fecha','Presupuesto Contractual','$nombre','$id_username_global','$pass',concat('Se elimino el presupuesto'),'$id_obra');");
?>