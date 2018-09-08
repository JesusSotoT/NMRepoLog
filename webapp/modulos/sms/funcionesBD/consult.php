<?php
	include('../../../netwarelog/webconfig.php');
	$idRuta = $_POST['idRuta'];
	
	$connection = new mysqli($servidor, $usuariobd, $clavebd, $bd);
	
	$result = $connection->query("SELECT c.id, c.nombretienda, c.direccion, c.colonia, c.cp, e.estado, m.municipio  
										FROM comun_cliente c
										INNER JOIN sms_oferta_cliente oc ON oc.idCliente = c.id 
										INNER JOIN sms_ruta_oferta_cliente rc ON rc.idOfertacliente = oc.id 
										INNER JOIN sms_ruta_oferta r ON r.id = rc.idRutaOferta  
										INNER JOIN municipios m ON m.idmunicipio = c.idMunicipio
										INNER JOIN estados e ON e.idestado = c.idEstado 		
										WHERE r.status = 1 AND rc.idRutaOferta = ".$idRuta." 
										AND rc.idOfertacliente = oc.id;");
	
	if($result->num_rows > 0)
	{
		while($row = $result->fetch_array())
			$rows[] = $row;
		foreach($rows as $row)
		{
			echo $row[direccion].", ".$row[colonia].", ".$row[cp].", ".$row[municipio].", ".$row[estado]."///$$$<<<"; 
		}
	}
	
	mysqli_close($connection);
?>