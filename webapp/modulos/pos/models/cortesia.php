<?php 

require("models/connection_sqli_manual.php"); // funciones mySQLi

class CortesiaModel extends Connection {



	public function buscarProductos( $patron ) {
		$sql = "SELECT	id, nombre as text
				FROM	app_productos
				WHERE	nombre LIKE '%$patron%' ";

		$res = $this->queryArray($sql);

		return json_encode( $res );
	}

	public function agregar($nombre, $desde, $hasta, $productos) {
		$sql = "INSERT	INTO app_cortesia (nombre, estatus, fecha_inicio, fecha_fin)
				VALUES	('$nombre', 1, '$desde', '$hasta')";
		$res = $this->queryArray($sql);

		if($res['status'] == true){
			$idC = $res['insertId'];
			foreach ($productos as $key => $value) {
				$idP = $value['id'];
				$sql = "INSERT	INTO app_cortesia_producto (id_cortesia, id_producto)
						VALUES	('$idC', '$idP')";
				$res2 = $this->queryArray($sql);
			}	
		}
		return $res2;
	}

	public function actualizar($id, $nombre, $desde, $hasta, $productos) {
		$sql = "UPDATE	app_cortesia 
				SET		nombre='$nombre', fecha_inicio='$desde', fecha_fin='$hasta' 
				WHERE	id='$id'";
		$res = $this->queryArray($sql);

		if($res['status'] == true){
			$sql = "DELETE	FROM app_cortesia_producto
					WHERE	id_cortesia = '$id'";
			$this->queryArray($sql);

			foreach ($productos as $key => $value) {
				$idP = $value['id'];
				$sql = "INSERT	INTO app_cortesia_producto (id_cortesia, id_producto)
						VALUES	('$id', '$idP')";
				$res2 = $this->queryArray($sql);
			}	
		}

		return $res2;
	}

	public function obtener() {
		$sql = "SELECT	id, nombre, estatus
				FROM	app_cortesia";
		$res = $this->queryArray($sql);
		return $res['rows'];
	}

	public function obtenerUno($id) {
		$sql = "SELECT	*
				FROM	app_cortesia
				WHERE	id=$id";
		$res = $this->queryArray($sql);
		$response = $res['rows'][0];

		$sql = "SELECT	p.id AS id, p.nombre AS nombre 
				FROM	app_cortesia_producto AS c, app_productos AS p
				WHERE	c.id_producto = p.id AND c.id_cortesia = '$id'";
		$res = $this->queryArray($sql);
		$response['productos'] = $res['rows'];
		return $response;
	}

	public function activar($id) {
		$sql = "UPDATE	app_cortesia 
				SET		estatus='1'
				WHERE	id='$id'";
		return $this->queryArray($sql);
	}

	public function desactivar($id) {
		$sql = "UPDATE	app_cortesia 
				SET		estatus='0'
				WHERE	id='$id'";
		return $this->queryArray($sql);
	}
}