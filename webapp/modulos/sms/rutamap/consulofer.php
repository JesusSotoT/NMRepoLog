<?php
	include("../../../netwarelog/webconfig.php");

	$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	$idoferta=$_REQUEST['idoferta'];

	/*$rut=$conection->query("select 
	ro.id,
	ro.nombre,
	concat(ut.tipo,' ',um.marca,' ', cu.capacidad,' Placas:',u.placas)transporte,
	ro.cantidadtotal 
	from 
	sms_ruta_oferta ro, 
	trt_unidades u ,
	trt_unidad_tipo ut,
	trt_unidad_marca um,
	trt_capacidad_unidad cu,
	sms_ruta_oferta_cliente rfc,
	sms_oferta_cliente oc

	where ut.id=u.tipo and um.id=u.marca and cu.id=u.capacidad 
	and ro.idTransporte=u.id and ro.status=1 and  rfc.idRutaOferta=ro.id and oc.id=rfc.idOfertacliente and oc.idOferta=".$idoferta." GROUP BY ro.nombre");*/

	$rut=$conection->query("select ro.id,
								ro.nombre,
								CONCAT(b.tipo, ' ', a.transporte, ' ', c.capacidad, ' Placas: ', a.placas) transporte
								from 
								sms_oferta_cliente of,
								sms_ruta_oferta_cliente roc,
								sms_ruta_oferta ro,
								sms_transporte a,
								sms_tipo_unidad b,
								sms_capacidades c
								where 
								roc.idOfertacliente=of.id and roc.idRutaOferta=ro.id and b.idtipo=a.idtipo and c.idcapacidad=a.idcapacidad and a.id=ro.idTransporte and of.idOferta=".$idoferta."
								group by ro.id");

	if($rut->num_rows>0){
	 	
	 	while($ruta=$rut->fetch_array(MYSQLI_ASSOC)){

	   		//$trans=$transporte['id'];
	 		echo ' <option id="'.$ruta['id'].'">'.$ruta['id'].'->'.$ruta['nombre'].','.$ruta['transporte'].'</option>';
	   }
	}else
		echo '<option selected>No hay rutas iniciadas de esta oferta</option>';
?>