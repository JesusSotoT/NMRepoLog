<?php
	//POST respuesta
	$respuesta=1;

	$cadena=$_GET['id'];
	$cadena=base64_decode($cadena);
	$separa=explode('&', $cadena);
	//ido=140&idoc=1418&rfc=IHA000314A38&idcli=8001

	$idoexp=explode('=', $separa[0]);
		$ido=$idoexp[1];
	$idocexp=explode('=', $separa[1]);
		$idoc=$idocexp[1];
	$rfcexp=explode('=', $separa[2]);
		$rfc=$rfcexp[1];
	$idcliexp=explode('=', $separa[3]);
		$idcli=$idcliexp[1];
	$bdexp=explode('=', $separa[4]);
		$bd=$bdexp[1];
	$bd2exp=explode('=', $separa[5]);
		$bd2=$bd2exp[1];

	//echo $ido.' > '.$idoc.' > '.$rfc.' > '.$idcli.' > '.$bd.' > '.$bd2;

	$conexion = mysqli_connect('nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com', 'nmdevel', 'nmdevel', $bd);
	if (!$conexion) {
	    die('Error de Conexión');
	    exit();
	}
	$query = "SELECT * FROM sms_oferta_client WHERE id_oferta_cliente='$idoc';";
	$result = mysqli_query($conexion, $query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_assoc($result);
		if($row['estatus']>0){
			echo 'REPETIDO';
		}else{
			$query = "UPDATE sms_oferta_client SET estatus='$respuesta' WHERE id_oferta_cliente='$idoc';";
			$result = mysqli_query($conexion, $query);
			mysqli_close($conexion);
			unset($conexion);
			$fecha=date('Y-m-d');
			$conexion = mysqli_connect('nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com', 'nmdevel', 'nmdevel', $bd2);
			//$query = "UPDATE sms_oferta_cliente SET contesto='$respuesta', cantidad=1, fechaRespuesta='$fecha' WHERE id='$idoc';";
			$query = "UPDATE sms_oferta_cliente SET contesto='$respuesta', cantidad=1, fechaRespuesta='$fecha';";
			$result = mysqli_query($conexion, $query);
			mysqli_close($conexion);
			unset($conexion);
			echo 'OK';
		}
	}
?>