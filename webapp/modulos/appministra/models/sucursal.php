<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class SucursalModel extends Connection
{
	function obtenerEstados(){
		$myQuery = "SELECT idestado, estado FROM estados WHERE idestado BETWEEN 1 AND 32";
		$Result = $this->query($myQuery);
		return $Result;
	}    

	function obtenerMunicipios($id){
		$myQuery = "SELECT idmunicipio, municipio FROM municipios WHERE idestado = $id ORDER BY municipio";
		$Result = $this->query($myQuery);
		return $Result;
	}

	function obtenerAlmacenes(){
		$myQuery = "SELECT idAlmacen AS id, nombre FROM almacen";
		$Result = $this->query($myQuery);
		return $Result;
	}

	function obtenerOrganizaciones(){
		$myQuery = "SELECT idorganizacion as id, nombreorganizacion as nombre FROM organizaciones";
		$Result = $this->query($myQuery);
		return $Result;
	}

	function validarFormulario($form){
		$clave 		= $form['clave'];
		$nombre 	= $form['nombre'];
		$estado 	= $form['estado'];
		$direccion 	= $form['direccion'];
		$municipio 	= $form['municipio'];

		$myQuery = "".
		"SELECT idSuc FROM mrp_sucursal 
		WHERE nombre 	= '$nombre'
		AND direccion 	= '$direccion'
		AND idEstado 	= $estado
		AND idMunicipio = $municipio
		AND clave 		= '$clave'";

		$Result = $this->query($myQuery);

		//Validamos que no se encuentre una sucursal con las mismas caracteristicas
		if ($Result->num_rows != 0) {
			//Encontro registros (no prosigue)
			return 1;
		} else {
			//No encontro registros (prosigue)
			return 0;
		}
	}

	function agregarSucursal($form){
		//Igualamos variables
		$activo 		= $form['activo'];
		$estado 		= $form['estado'];
		$almacen 		= $form['almacen'];
		$telefono 		= $form['telefono'];
		$municipio 		= $form['municipio'];
		$clave 			= trim($form['clave']);
		$nombre 		= trim($form['nombre']);
		$codigoPostal 	= $form['codigoPostal'];
		$organizacion 	= $form['organizacion'];
		$contacto 		= trim($form['contacto']);
		$direccion 		= trim($form['direccion']);

    if ($codigoPostal == '') {
    	$codigoPostal = 0;
    }

		$myQuery = "INSERT INTO mrp_sucursal (nombre, direccion, idEstado, idMunicipio, cp, tel_contacto, contacto, idOrganizacion, clave, activo, idAlmacen) 
			VALUES('$nombre', '$direccion', $estado, $municipio, '$codigoPostal', '$telefono', '$contacto', 
			$organizacion, '$clave', $activo, $almacen);";

		$Result = $this->query($myQuery) or die("Hubo un error.");
		return $Result;
	}
 
	function obtenerSucursales(){
		$myQuery = "SELECT 
		suc.idSuc AS id_suc,
		suc.nombre AS nombre_suc, 
		suc.direccion AS direccion_suc,
		suc.contacto AS contacto_suc, 
		suc.tel_contacto AS telefono_suc, 
		alm.nombre AS nombre_alm,
		suc.activo AS activo_suc
		
		FROM `mrp_sucursal` AS suc

		LEFT JOIN `almacen` AS alm
		ON alm.idAlmacen = suc.idAlmacen

		LEFT JOIN `estados` AS est
		ON suc.idEstado = est.idestado

		LEFT JOIN `municipios` AS mun
		ON suc.idMunicipio = mun.idmunicipio

		ORDER BY id_suc;";

		$Result = $this->query($myQuery) or die("Hubo un error");
		return $Result;
	}

	function obtenerSucursal($id){
		$myQuery = "SELECT nombre, direccion, idEstado, idMunicipio, cp AS codigoPostal, tel_contacto AS telefono, contacto, idOrganizacion, clave, activo, idalmacen AS almacen FROM mrp_sucursal WHERE idSuc = $id;";
		$Result = $this->query($myQuery);
		return $Result;
	}

	function modificarSucursal($form){
		//Igualamos variables
		$id				= $form['id'];
		$activo 		= $form['activo'];
		$estado 		= $form['estado'];
		$almacen 		= $form['almacen'];
		$telefono 		= $form['telefono'];
		$municipio 		= $form['municipio'];
		$clave 			= trim($form['clave']);
		$nombre 		= trim($form['nombre']);
		$codigoPostal 	= $form['codigoPostal'];
		$organizacion 	= $form['organizacion'];
		$contacto 		= trim($form['contacto']);
		$direccion 		= trim($form['direccion']);

    	$myQuery = "UPDATE mrp_sucursal
		SET 
		nombre = '$nombre', 
		direccion = '$direccion',
		idEstado = $estado,
		idMunicipio = $municipio,
		cp = '$codigoPostal',
		tel_contacto = '$telefono',
		contacto = '$contacto',
		idOrganizacion = $organizacion,
		clave = '$clave',
		activo = $activo,
		idAlmacen = $almacen 
		WHERE idSuc = $id;";

		$Result = $this->query($myQuery) or die("Hubo un error.");
		return $Result;
	}

}
?>
