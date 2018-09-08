<?php
	// Abriendo la conexión a la base de datos...
	$servidor = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
	$user = "nmdevel";
	$contra = "nmdevel";
	$based = "_dbmlog0000006473";

	$mysqli = new mysqli($servidor, $user, $contra, $based);
	if ($mysqli->connect_errno) {
		echo "Error en la conexion";
	}
	

	$bloqueo = 0;
	$registro = 0;

	// Extrayendo la información de la url del catálogo de cliente de Domain Control
	$mirfc = strtoupper($_GET['qrfc']);
	$minombre = strtoupper($_GET['qnombre']);
	$micalle = strtoupper($_GET['qcalle']);
	$minumero = strtoupper($_GET['qnumero']);
	$mipoblacion = strtoupper($_GET['qpoblacion']);
	$miestado = strtoupper($_GET['qestado']);
	$micolonia = strtoupper($_GET['qcolonia']);
	$micp = $_GET['qcp'];
	$micorreo = $_GET['qcorreo'];

	// Buscando el ID del estado
	if ($miestado == "JALISCO") {
		$miestado = '14';
	} elseif ($miestado == "DISTRITO FEDERAL") {
		$miestado = '15';
	}

	// Buscando el id de cliente
	if ($micliente = mysqli_query($mysqli, "SELECT * FROM comun_cliente WHERE rfc='$mirfc'")) {
		while ($qcliente = $micliente->fetch_object()) {
			$id_cliente.=$qcliente->rfc; 
		}
	}

	// Si no existe el cliente Agregar el registro a la tabla comun_cliente
	if ($id_cliente == "" or $id_cliente == NULL) {
		$miRegistro = "INSERT INTO comun_cliente (nombre, direccion, colonia, email, cp, rfc) VALUES ('$minombre', '$micalle', '$micolonia', '$micorreo', '$micp', '$mirfc')";
		$registra = mysqli_query($mysqli, $miRegistro);

	    // Repito la busqueda del cliente para relacionar al cliente a la tabla comun_facturacion
		if ($micliente2 = mysqli_query($mysqli, "SELECT * FROM comun_cliente WHERE rfc='$mirfc'")) {
			while ($qcliente2 = $micliente2->fetch_object()) {
				$id_cliente2.=$qcliente2->id;
			}
		}

		// Agregando el registro a la tabla comun_facturacion
		$miregfact = "INSERT INTO comun_facturacion (nombre, rfc, razon_social, correo, domicilio, num_ext, cp, colonia, ciudad, pais, estado, municipio) VALUES ('$id_cliente2', '$mirfc', '$minombre', '$micorreo', '$micalle', '$minumero', '$micp', '$micolonia', '$mipoblacion', 'Mexico', '$miestado', '$mipoblacion')";
		$registrando = mysqli_query($mysqli, $miregfact);
	} else {
		
		// Si existe el ID del cliente actualizo la tabla comun_cliente
		$myUpdate = "UPDATE comun_cliente SET nombre='$minombre', direccion='$micalle'.'No. '.'$minumero', colonia='$micolonia', email='$micorreo', cp='$micp', rfc='$mirfc', estado='$miestado', municipio='$mipoblacion' Where rfc='$mirfc'";
		$ejecuta = mysqli_query($mysqli, $myUpdate); 
		$registro = $mysqli->affected_rows;
	    
		// Actualizo la tabla comun_facturacion
		$myfactura = "UPDATE comun_facturacion SET rfc='$mirfc', razon_social='$minombre', correo='$micorreo', domicilio='$micalle', num_ext='$minumero', cp='$micp', colonia='$micolonia', ciudad='$mipoblacion', pais='Mexico', estado='$miestado', municipio='$mipoblacion'	Where rfc='$mirfc'";
		$ejecutaf = mysqli_query($mysqli, $myfactura); 
	}

	//echo "<br /> Registro actualizado con éxito";
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
?>
