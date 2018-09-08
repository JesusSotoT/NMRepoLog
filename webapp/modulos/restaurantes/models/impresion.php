<?php
require ("models/connection_sqli.php");

class impresionModel extends Connection {
	//session_start();

	function insertar($REQUEST) {
		session_start();
		$sucursal = "	SELECT 
							mp.nombre AS nombree 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['nombree'];

		$respuesta = "";
		$impresora = "";
		$sql = "";
		$fecha = date("Y-m-d H:i:s");

		$sql1 = "SELECT impresora FROM servidor_area_impresora WHERE area = '". $REQUEST['area'] ."' AND sucursal = '". $sucursal ."';";
		$sql1 = $this -> queryArray($sql1);

		if($sql1["rows"][0]["impresora"] != null){
			$impresora = $sql1["rows"][0]["impresora"];

			$sql = "INSERT INTO servidor_impresion (id, impresora, ticket, creado, modificado, codigo, sucursal) VALUES (null, '". $impresora ."', '". $REQUEST['ticket'] ."', '". $fecha ."', '". $fecha ."', '". $REQUEST['codigo'] ."', '". $sucursal ."');";
			$sql = $this -> queryArray($sql);
		}else{
			$sql["status"] = false;
		}
		
		if($sql["status"]){
			$respuesta = array("status" => true, "id" => $sql["insertedId"]);
		} else{
			$respuesta = "fallo al insertar";
		}

		return $respuesta;
	}

	function consultar($REQUEST) {
		$sql = "SELECT * FROM servidor_impresion WHERE sucursal = '". $REQUEST['sucursal'] ."';";
		$sql = $this -> queryArray($sql);
		//if($sql["status"]) $respuesta = array("status" => true, "impresiones" => $sql["rows"]);
		return json_encode($sql);
	}

	function borrar($REQUEST) {
		//$sql = "UPDATE TABLE servidor_impresion WHERE id = ". $REQUEST["id"];
		$sql = "DELETE FROM servidor_impresion WHERE id = ". $REQUEST["id"];
		$sql = $this -> queryArray($sql);
		return json_encode($sql);
	}

	function insertarVinculo($REQUEST) {
		$respuesta = "";
		$fecha = date("Y-m-d H:i:s");
		$sql = "INSERT INTO servidor_area_impresora (id, area, impresora, creado, modificado, sucursal) VALUES (null, '". $REQUEST['area'] ."', '". $REQUEST['impresora'] ."', '". $fecha ."', '". $fecha ."', '". $REQUEST['sucursal'] ."');";
		$sql = $this -> queryArray($sql);

		if($sql["status"]){
			$respuesta = array("status" => true, "id" => $sql["insertedId"]);
		} else{
			$respuesta = array("status" => false);
		}

		return json_encode($respuesta);
	}

	function leerVinculos($REQUEST) {
		$sql = "SELECT * FROM servidor_area_impresora WHERE sucursal = '" . $REQUEST['sucursal'] ."';";
		$sql = $this -> queryArray($sql);

		return json_encode($sql);
	}

	function borrarVinculo($REQUEST) {
		$respuesta = "";
		
		$sql = "DELETE FROM servidor_area_impresora WHERE area = '". $REQUEST['area'] ."' AND sucursal = '". $REQUEST['sucursal'] ."';";
		$respuesta = $this -> queryArray($sql);
		return json_encode($respuesta);
	}

	function leerAreas($REQUEST) {
		$respuesta = "";
		$sql = "SELECT *
				FROM   app_departamento ad
				WHERE  NOT EXISTS (SELECT sai.area
				                   FROM   servidor_area_impresora sai
				                   WHERE  ad.Nombre = sai.area 
				                   AND sai.sucursal = '". $REQUEST['sucursal'] ."')";
		$respuesta = $this -> queryArray($sql);
		return json_encode($respuesta);
	}

	function leerSucursales() {
		$sql = "SELECT nombre FROM mrp_sucursal";
		$sql = $this -> queryArray($sql);

		return json_encode($sql);
	}

	function sucursal(){
		session_start();
		$sucursal = "	SELECT 
							mp.nombre AS nombre 
						FROM 
							administracion_usuarios au 
						INNER JOIN 
								mrp_sucursal mp 
							ON 
								mp.idSuc = au.idSuc 
						WHERE 
							au.idempleado = " . $_SESSION['accelog_idempleado'] . " 
						LIMIT 1";
		$sucursal = $this -> queryArray($sucursal);
		$sucursal = $sucursal['rows'][0]['nombre'];
		return $sucursal;
	}
}
?>