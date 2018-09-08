<?php 
//ini_set("display_errors", 1); error_reporting(E_ALL);

require("models/connection_sqli_manual.php"); // funciones mySQLi

class ConfigCajaModel extends Connection {



	public function actualiza( $tipoDescuento , $limitUnitPorcentaje , $limitUnitCantidad , $limitGlobalPorcentaje , $limitGlobalCantidad , $password , $cajaMax , $retitoMax , $ticket , $cotizacionDescuento , $ordenVentaDescuento, $precio_unit_ticket, $printAuto,$puntos, $activarDevCan , $activarRetiroDevCan,$activaPrecio, $moduloPrint) {

	 	$sql = "SELECT	id 
				FROM	app_config_ventas
				WHERE	id=1";

		$res = $this->queryArray($sql);


		if($res['total'] == 0) {
			$sql = "INSERT	INTO app_config_ventas (tipo_descuento, limit_sin_pass_p, limit_sin_pass_c, limit_global_p, limit_global_c, password, max_caja, max_retiro, leyenda_ticket, cotizacio_desc, ov_desc , modifica_precios,precio_unit_ticket , printAuto, puntos, activar_dev_can, activar_retiro_dev_can, moduloPrint)
					VALUES	( $tipoDescuento , $limitUnitPorcentaje , $limitUnitCantidad , $limitGlobalPorcentaje , $limitGlobalCantidad , '$password' , $cajaMax , $retitoMax , '$ticket' , $cotizacionDescuento , $ordenVentaDescuento , $activaPrecio,$precio_unit_ticket , $printAuto, $puntos, $activarDevCan , $activarRetiroDevCan, $moduloPrint )";
			$res = $this->queryArray($sql);
		}
		else {
			 $sql = "UPDATE	app_config_ventas 
					SET		tipo_descuento=$tipoDescuento , limit_sin_pass_p=$limitUnitPorcentaje , limit_sin_pass_c=$limitUnitCantidad , limit_global_p=$limitGlobalPorcentaje , limit_global_c=$limitGlobalCantidad , password='$password' , max_caja=$cajaMax , max_retiro=$retitoMax , leyenda_ticket='$ticket' , cotizacio_desc=$cotizacionDescuento , ov_desc=$ordenVentaDescuento , precio_unit_ticket=$precio_unit_ticket , printAuto=$printAuto, puntos = $puntos, activar_dev_can=$activarDevCan , activar_retiro_dev_can=$activarRetiroDevCan, modifica_precios=$activaPrecio, moduloPrint= $moduloPrint
					WHERE	id=1";
			$res = $this->queryArray($sql);
		}
		return $res;
	}

	public function consulta(){
		$sql = "SELECT	* 
				FROM	app_config_ventas
				WHERE	id=1";

		$res = $this->queryArray($sql);
		return $res['rows'];
	}

	public function activarProntipagos($request){
		//ini_set("display_errors", 1); error_reporting(E_ALL);
		include_once("librerias/prontipago.php");
		$prontipago = new Prontipago($request["usuario"], $request["contrasena"]);
		$updateLogin = "UPDATE app_config_ventas SET usuarioProntipago = '". $request["usuario"] ."', contrasenaProntipago = '". $request["contrasena"] ."' WHERE id = 1;";
		$resultUpdate = $this->queryArray($updateLogin);
		return $prontipago->obtenerListadoProductos();
	}

	public function activarProductosProntipagos($listaSKU){
		//ini_set("display_errors", 1); error_reporting(E_ALL);
		$selectUsusario = "SELECT usuarioProntipago, contrasenaProntipago FROM app_config_ventas WHERE id = 1;";
		$resultUsuario = $this->queryArray($selectUsusario);

		include_once("librerias/prontipago.php");
		$prontipago = new Prontipago($resultUsuario["rows"][0]["usuarioProntipago"], $resultUsuario["rows"][0]["contrasenaProntipago"]);
		$productos = $prontipago->obtenerListadoProductos();
		
		foreach ($listaSKU as $key => $value) {
			
			foreach ($productos["productos"] as $producto) {
				
				if($producto["sku"] == $value){					
					$sku = $value;
					$nombre = $producto["nombre"];
					$precio = $producto["precio"];
					$descripcion = $producto["descripcion"];

					$selectSku = "SELECT codigo FROM app_productos WHERE codigo ='". $sku ."';";
					$resultSelect = $this->queryArray($selectSku);

					if($resultSelect["total"] == 0){
						$queryInsert = "INSERT INTO app_productos (
						id, 
						codigo, 
						nombre, 
						precio, 
						descripcion_corta,
						tipo_producto,  
						comision,  
						status) VALUES 
						(null,
						'". $sku ."',
						'". $nombre ."',
						'". $precio ."',
						'". $descripcion."',
						8,
						0,
						1);";
						$result = $this->queryArray($queryInsert);

						$queryInsert = "INSERT INTO app_campos_foodware (
						id, 
						id_producto) VALUES 
						(null,
						". $result["insertId"] .");";
						
						$result = $this->queryArray($queryInsert);
					}
				}
			
			}
		}
		return array("status" => true);
	}

	public function get_metodos_pago(){
		$sql = "SELECT	* 
				FROM	forma_pago";

		$res = $this->queryArray($sql);
		return $res['rows'];
	}

	/* ==== MOD CHRIS - tipo de documento === */
	public function get_documentos_pago(){
		$sql = "SELECT	tipo_documento 
				FROM	app_config_ventas where id=1";

		$res = $this->queryArray($sql);
		return $res['rows'];
	}
	/* ==== FIN MOD === */
}