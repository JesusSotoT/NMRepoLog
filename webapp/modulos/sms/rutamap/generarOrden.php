<?php

	include("../../../netwarelog/webconfig.php");

	if(isset($_POST['idruta'])){

		$rutaoferta=$_POST['idruta'];
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

		$conection->query("SET NAMES utf8");
		$result=$conection->query("select cc.id,cc.nombre, (so.precio*oc.cantidad) monto, (select GROUP_CONCAT(cf.id,'-',cf.rfc) rfc from comun_cliente cc1, comun_facturacion cf where cc1.id=cf.nombre and cc1.id=cc.id) rfc
								from sms_ruta_oferta_cliente roc, 
								sms_oferta_cliente oc,comun_cliente cc, estados e, municipios m, sms_oferta so, comun_facturacion cf
								where roc.idRutaOferta=".$rutaoferta." and oc.id=roc.idOfertacliente and 
								cc.id=oc.idCliente and e.idestado=cc.idEstado and m.idmunicipio=cc.idMunicipio and so.idOferta=oc.idOferta group by cc.id");

		$rows = array();
		while($row=$result->fetch_array(MYSQLI_ASSOC))
	   		$rows[]=$row;

	   	mysqli_free_result($result);
	   	echo json_encode($rows);
	   	mysqli_close($conection);
	}

	/*if(isset($_POST['idcliente'])){

		$cliente=$_POST['idcliente'];
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

		$conection->query("SET NAMES utf8");
		$result=$conection->query("select cf.id, cf.rfc from comun_cliente cc, comun_facturacion cf where cc.id=cf.nombre and cc.id=$cliente");

		$rows = array();
		while($row=$result->fetch_array(MYSQLI_ASSOC))
	   		$rows[]=$row;

	   	mysqli_free_result($result);
	   	echo json_encode($rows);
	   	mysqli_close($conection);
	}*/
?>