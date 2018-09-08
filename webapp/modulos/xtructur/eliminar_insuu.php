<?php
	include('conexiondb.php');
	$id_obra=$_POST['id_obra'];
	$mysqli->query("DELETE FROM constru_insumos WHERE id_obra='$id_obra';");


		$pass=$_POST['pass'];
		date_default_timezone_set('America/Mexico_City');
		 $fecha=date('Y-m-d H:i:s');
		$idusr = $_SESSION['accelog_idempleado'];
		   
		$SQL = "SELECT nombreusuario as username, idempleado from administracion_usuarios where idempleado='$idusr';";
    $result = $mysqli->query( $SQL ) ;
$row = $result->fetch_array();
 $nombre=$row['username'];
    $id_username_global=$row['idempleado'];

    	$mysqli->query("INSERT INTO constru_superadmin(fecha,modulo,usuario,idusuario,pass,accion,idobra) values('$fecha','Explosion de insumos','$nombre','$id_username_global','$pass',concat('Se elimino la explosion de insumos'),'$id_obra');");
?>