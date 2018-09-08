<?php
	//POST respuesta
	$respuesta=1;

	$cadena=$_GET['id'];
	$cadena=base64_decode($cadena);
	$separa=explode('&', $cadena);
	//echo $cadena;
	//ido=140&idoc=1418&rfc=IHA000314A38&idcli=8001

	$idiexp=explode('=', $separa[0]);
		$idi=$idiexp[1];
	$bdexp=explode('=', $separa[1]);
		$bd=$bdexp[1];


	//echo $idi.' > '.$bd;

	$conexion = mysqli_connect('nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com', 'nmdevel', 'nmdevel', $bd);
	if (!$conexion) {
	    die('Error de Conexión');
	    exit();
	}
	$query = "SELECT * FROM sms_invitados WHERE id_cliente_dev='$idi';";
	$result = mysqli_query($conexion, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		if($row['estatus']>0){
			echo 'REPETIDO';
		}else{
			$query = "UPDATE sms_invitados SET estatus='$respuesta' WHERE id_cliente_dev='$idi';";
			$result = mysqli_query($conexion, $query);
			mysqli_close($conexion);
			unset($conexion);
			echo 'OK';
		}
	}
?>