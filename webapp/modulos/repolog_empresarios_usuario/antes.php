<?php
	function clearFilter($data,$index,$removingString)
	{
		if(trim($data[$index]) == $removingString)
		{
			$data[$index] = " ";	
		}
		return $data;
	}

	$data = explode("\n", $sql);
	for ($i=0; $i < count($data) ; $i++) { 
		echo $data[ $i ]."($i) <br/>";
		# code...
	}
	$data = clearFilter($data, 34, "AND inadem_empresarios.idempleado = 0");
	$data = clearFilter($data, 35, "AND inadem_estatus.idestatus = 0");
	//$data = clearFilter($data, 35, 'AND inadem_empresarios.fecharegistro BETWEEN "2014-02-06" AND "2014-02-06"');
	$data = clearFilter($data, 37, "AND inadem_rubros.rubro = 0");
	$data = clearFilter($data, 38, "AND estados.idestado = 0");
	$data = clearFilter($data, 39, "AND municipios.idmunicipio = 0");
	$data = clearFilter($data, 40, 'AND inadem_empresarios.email LIKE "%%"');
	$data = clearFilter($data, 41, 'AND inadem_tipopersona.tipopersona = 0');
	$data = clearFilter($data, 42, 'AND inadem_clasificacion.clasificacion = 0');
	$data = clearFilter($data, 43, 'AND inadem_empresarios.localidad LIKE "%%"');
	$data = clearFilter($data, 44, 'AND inadem_empresarios.codigopostal LIKE "%%"');
	$data = clearFilter($data, 45, 'AND inadem_empresarios.asentamiento LIKE "%%"');
	$data = clearFilter($data, 46, 'AND inadem_empresarios.tipoasentamiento LIKE "%%"');
	$data = clearFilter($data, 47, 'AND inadem_empresarios.nombrevialidad LIKE "%%"');
	$data = clearFilter($data, 48, 'AND inadem_empresarios.tipovialidad LIKE "%%"');
	$data = clearFilter($data, 49, 'AND inadem_empresarios.numeroexterior1 LIKE "%%"');
	$data = clearFilter($data, 50, 'AND inadem_empresarios.numeroexterior2 LIKE "%%"');
	$data = clearFilter($data, 51, 'AND inadem_empresarios.numerointerior LIKE "%%"');	
	
	$sql = join($data);
?>