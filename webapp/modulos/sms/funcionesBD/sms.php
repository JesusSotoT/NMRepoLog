<?php

	//include("../../../netwarelog/webconfig.php");


	$thisconnection=mysqli_connect($servidor, $usuariobd, $clavebd, $bd);

	if(!$thisconnection)
		echo "connection failed";
	else{
		//$ids="8001,8002";
		$ids=$ides;
		//$rfcs=mysqli_query($thisconnection,"select GROUP_CONCAT(b.rfc) as rfc from comun_cliente a inner join comun_facturacion b on b.nombre=a.id where a.id in(".$ids.")");
		$rfcs=mysqli_query($thisconnection,"select b.rfc from comun_cliente a inner join comun_facturacion b on b.nombre=a.id where a.id='$value' LIMIT 1 ");
		$vars="";
		if(mysql_num_rows($rfcs)>0){
		/*if($rows = mysqli_fetch_array($rfcs)){
			$vars="'".str_replace(",","','",$rows['rfc'])."'";
		}*/
			$rows = mysqli_fetch_assoc($rfcs);
			$rfccli=$rows['rfc'];

			$nsconnection=mysqli_connect($servidor, $usuariobd, $clavebd,"netwarstore");
			// Check connection
			if(!$nsconnection)
				echo "connection failed";
			else{

				//$result=mysqli_query($nsconnection,"select nombre_db, usuario_db, pwd_db from customer where rfc in(".$vars.")");
				$result=mysqli_query($nsconnection,"select nombre_db, usuario_db, pwd_db from customer where rfc='$rfccli' ");

				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_array($result)){
						$cconnection=mysqli_connect("nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com", $row['usuario_db'], $row['pwd_db'], $row['nombre_db']);
						if(!$cconnection)
							echo "connection failed";
						else{
							$url="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"."?ido=".$id."&idoc=".$idoc."&rfc=".$rfccli."&idcli=".$value." ";
							$insert=mysqli_query($cconnection,"insert into sms_oferta_client (id, descripcion, url, inicio_ofe, fin_ofe, estatus, fecha) values (null,'$sms','$url','$f_inicio','$f_fin',0,'$f_creacion')");
							//$tuncate=mysqli_query($cconnection,"truncate sms_oferta_client");
							/*$results=mysqli_query($cconnection,"select usuario from accelog_usuarios limit 1");
							while($rows = mysqli_fetch_array($results)){
								echo $rows['usuario']."</br>";
							}*/
						}
						mysqli_close($cconnection);
					}
				}
			}
			mysqli_close($nsconnection);
	}
	mysqli_close($thisconnection);

	//1331, 93
?>