<?php 

require("models/connection_sqli_manual.php"); // funciones mySQLi

class GarantiaModel extends Connection {



	public function buscarProductos( $patron ) {
		$sql = "SELECT	id, nombre as text
				FROM	app_productos
				WHERE	nombre LIKE '%$patron%' ";

		$res = $this->queryArray($sql);

		return json_encode( $res );
	}

	public function buscarClasificadores( $clasificador, $patron ) {
		switch ($clasificador) {
			case '1':
				$tabla = 'app_departamento';
				break;
			case '2':
				$tabla = 'app_familia';
				break;
			case '3':
				$tabla = 'app_linea';
				break;
			default:
				# code...
				break;
		}

		$sql = "SELECT	id, nombre as text
				FROM	$tabla
				WHERE	nombre LIKE '%$patron%' ";

		$res = $this->queryArray($sql);

		return json_encode( $res );
	}

	public function agregar($nombre, $tipo, $derecho, $duracion, $politica, $tabla) {
		$sql = "INSERT	INTO app_pos_garantia (nombre, tipo, derecho, duracion, politica)
				VALUES	('$nombre', '$tipo', '$derecho', '$duracion', '$politica')";
		$res = $this->queryArray($sql);

		$idC = $res['insertId'];
		if($res['status'] == true){
			if($tipo == "1"){
				foreach ($tabla as $key => $value) {
					$idTipClas = $value['idTipClas'];
					$idClas = $value['idClas'];
					$sql = "INSERT	INTO app_pos_garantia_clasificador (id_garantia, id_tipo_clasificador, id_clasificador)
							VALUES	('$idC', '$idTipClas', '$idClas')";
					$res2 = $this->queryArray($sql);
				}	
			}
			else {
				foreach ($tabla as $key => $value) {
					$idProducto = $value['idProducto'];
					$sql = "INSERT	INTO app_pos_garantia_producto (id_garantia, id_producto)
							VALUES	('$idC', '$idProducto')";
					$res2 = $this->queryArray($sql);
				}
			}
			
		}
		return $res2;
	}

	public function actualizar($id, $nombre, $tipo, $derecho, $duracion, $politica, $tabla) {
		$sql = "UPDATE app_pos_garantia 
				SET		nombre='$nombre', tipo='$tipo', derecho='$derecho', duracion='$duracion', politica='$politica'
				WHERE	id='$id'";
		$res = $this->queryArray($sql);

		$idC = $id;
		if($res['status'] == true){
			$this->queryArray($sql);
			if($tipo == "1"){
				$sql = "DELETE	FROM app_pos_garantia_clasificador
					WHERE	id_garantia = '$id'";
				$this->queryArray($sql);

				foreach ($tabla as $key => $value) {
					$idTipClas = $value['idTipClas'];
					$idClas = $value['idClas'];
					$sql = "INSERT	INTO app_pos_garantia_clasificador (id_garantia, id_tipo_clasificador, id_clasificador)
							VALUES	('$idC', '$idTipClas', '$idClas')";
					$res2 = $this->queryArray($sql);
				}	
			}
			else {
				$sql = "DELETE	FROM app_pos_garantia_producto
					WHERE	id_garantia = '$id'";
				$this->queryArray($sql);

				foreach ($tabla as $key => $value) {
					$idProducto = $value['idProducto'];
					$sql = "INSERT	INTO app_pos_garantia_producto (id_garantia, id_producto)
							VALUES	('$idC', '$idProducto')";
					$res2 = $this->queryArray($sql);
				}
			}
			
		}

		return $res2;
	}

	public function obtener() {
		$sql = "SELECT	id, nombre, tipo, duracion
				FROM	app_pos_garantia";
		$res = $this->queryArray($sql);
		return $res['rows'];
	}

	public function obtenerUna($id) {
		$sql = "SELECT	*
				FROM	app_pos_garantia
				WHERE	id=$id";
		$res = $this->queryArray($sql);
		$response = $res['rows'][0];
		$response['tabla'] = [];

		if($response['tipo'] == "1") {

			$sql = "SELECT	id_tipo_clasificador, id_clasificador
					FROM	app_pos_garantia_clasificador AS c, app_pos_garantia AS g
					WHERE	c.id_garantia = g.id AND c.id_garantia = '$id'";

			$resTmp = $this->queryArray($sql);

			foreach ($resTmp['rows'] as $key => $value) {

				switch ($value['id_tipo_clasificador']) {
					case '1':
						$tabla = 'app_departamento';
						break;
					case '2':
						$tabla = 'app_familia';
						break;
					case '3':
						$tabla = 'app_linea';
						break;
					default:
						# code...
						break;
				}

				$idClasificador = $value['id_clasificador'];
				$sql = "SELECT	id, nombre
						FROM	$tabla
						WHERE	id = '$idClasificador' ";

				$res = $this->queryArray($sql);
				$objTmp = [	"idTipoClasificador"	=>	$value['id_tipo_clasificador'],
							"idClasificador"		=>	$idClasificador,
							"nombre"				=>	$res['rows'][0]['nombre']
				];
				array_push($response['tabla'], $objTmp);
				# code...
			}

		}
		else {
			$sql = "SELECT	id_producto
					FROM	app_pos_garantia_producto AS p, app_pos_garantia AS g
					WHERE	p.id_garantia = g.id AND p.id_garantia = '$id'";

			$resTmp = $this->queryArray($sql);

			foreach ($resTmp['rows'] as $key => $value) {

				$idProducto = $value['id_producto']; 
				$sql = "SELECT	id, nombre
						FROM	app_productos
						WHERE	id = '$idProducto' ";

				$res = $this->queryArray($sql);

				array_push($response['tabla'], $res['rows'][0]);
				# code...
			}
		}
		return $response;
	}

	function buscarPoliticas($patron){
		$sql = "SELECT	id, nombre as text
				FROM	app_pos_garantia_politica
				WHERE	nombre LIKE '%$patron%' ";

		$res = $this->queryArray($sql);

		return json_encode( $res );
	}

	function descripcionPolitica($id) {
		$sql = "SELECT	id, descripcion
				FROM	app_pos_garantia_politica
				WHERE	id='$id'";
		$res = $this->queryArray($sql);
			
		return json_encode( $res['rows'][0] );
	}

	function agregarPolitica($nombre, $descripcion) {
		$sql = "INSERT INTO	app_pos_garantia_politica (nombre, descripcion)
				VALUES ('$nombre', '$descripcion')";
		$res = $this->queryArray($sql);

		return json_encode( $res );
	}

	function existeProducto( $id ) {
		$sql = "SELECT	*
				FROM	app_pos_garantia_producto
				WHERE	id_producto = '$id'";

		$res = $this->queryArray($sql);

		return $res;
	}

	function existeClasificador( $idTipoClasificador, $idClasificador) {
		$sql = "SELECT	*
				FROM	app_pos_garantia_clasificador
				WHERE	id_tipo_clasificador = '$idTipoClasificador' AND id_clasificador = '$idClasificador'";

		$res = $this->queryArray($sql);

		return $res;
	}

}