<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);

	$mysqli = new mysqli("localhost", "root", "root", "_dbmlog0000006473");
	if ($mysqli->connect_errno) {
		echo "Error en la conexion";
	}


	

	$bloqueo = 0;
	$registro = 0;

	$cuantos = $_GET['v']);
	echo $cuantos;

/*	$minombre = strtoupper($_GET['qnombre']);
	$micalle = strtoupper($_GET['qcalle']);
	$minumero = strtoupper($_GET['qnumero']);
	$mipoblacion = strtoupper($_GET['qpoblacion']);
	$miestado = strtoupper($_GET['qestado']);
	$micolonia = strtoupper($_GET['qcolonia']);
	$micp = $_GET['qcp'];
	$micorreo = $_GET['qcorreo'];

	echo "Grabando comun_cliente. <br /><br />";

	$myUpdate = "UPDATE comun_cliente SET nombre='$minombre', direccion='$micalle', colonia='$micolonia', email='$micorreo', cp='$micp', rfc='$mirfc'  
			Where rfc='$mirfc'";
	$ejecuta = mysqli_query($mysqli, $myUpdate); 

	$registro = $mysqli->affected_rows;


	$myfactura = "UPDATE comun_facturacion SET nombre='$minombre', rfc='$mirfc', razon_social='$minombre', correo='$micorreo', domicilio='$micalle', num_ext='$minumero', cp='$micp', colonia='$micolonia', estado='$miestado', ciudad='$mipoblacion'
		Where rfc='$mirfc'";
	$ejecutaf = mysqli_query($mysqli, $myfactura); 

    if ($registro == 1) {
    	echo 1;
	}else{

		$miRegistro = "INSERT INTO comun_cliente (nombre, direccion, colonia, email, cp, rfc) 
                        VALUES ('$minombre', '$micalle', '$micolonia', '$micorreo', '$micp', '$mirfc')";
        //echo ($miRegistro);
        $registra = mysqli_query($mysqli, $miRegistro);

		// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + +
		//
		// + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + + 
		$miregfact = "INSERT INTO comun_facturacion (nombre, rfc, razon_social, correo, domicilio, num_ext, cp, colonia, ciudad) 
	                    VALUES ('$minombre', '$mirfc', '$minombre', '$micorreo', '$micalle', '$minumero', '$micp', '$micolonia', '$mipoblacion')";
	    $registrando = mysqli_query($mysqli, $miregfact);
	}



titulofact(0) = rs("Factura") & "_"
				titulofact(1) = rs("LugarExp") & "_"
				titulofact(2) = rs("Fecha") & "_"
				titulofact(3) = rs("Origen") & "_"
				titulofact(4) = Remitente & "_"
				titulofact(5) = RFC1 & "_"
				titulofact(6) = Dir1 & "_"
				titulofact(7) = rs("Recoger") & "_"
				titulofact(8) = rs("Destino") & "_"
				titulofact(9) = Destinatario & "_"
				titulofact(10)= RFC2 & "_"
				titulofact(11)= Dir2 & "_"
				titulofact(12)= rs("Entregar") & "_"
				titulofact(13)= ValorUni & "_"
				titulofact(14)= ValorDec &"_"
				titulofact(15)= Peso & "_"
				titulofact(16)= rs("CondPago") & "_"
				
						
						cantidad = cantidad & rso(0) & "_" 
						servicio = servicio & rso(1) & "_"
						precio = precio & rso(2) & "_" 
						observacion = observacion & rso(3) & "_" 
						observacion2 = observacion2 & rso(4) & "_"
						i = i +1
				' <!-- CONCEPTOS DE OPERADOR -->
				titulofact(17)= Ope  & "_"
				titulofact(18)= Unidad
				titulofact(19)= Placas &"_"				
				titulofact(20)= repartos &"_"
				titulofact(21)= otros &"_"
				titulofact(22)= rs("Importe") & "_"
				titulofact(23)= rs("IVA") & "_"
				titulofact(24)= rs("Reten") & "_"
				titulofact(25)= rs("Total") & "_"
				titulofact(26)= Principal(rs("Total")) & "_"
				titulofact(27)= rs("Usuario")&"_"
				titulofact(28)= cadena & "_"



	echo "Registro actualizado con éxito";
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";   */
?>
