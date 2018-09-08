<?php 
ini_set('display_erros', 1);
require('common.php');
require('models/garantia.php');

class Garantia extends Common {

	public $GarantiaModel;

	function __construct(){
		$this->GarantiaModel = new GarantiaModel();
		$this->GarantiaModel->connect();
	}

	function __destruct() {
		$this->GarantiaModel->close();
	}

	function index() {
		require 'views/garantia/garantia.php';
	}

	function nueva() {
		$editar = false;
		if( isset( $_GET['idGarantia'] ) ){
			$editar =true;
			$idGarantia =$_GET['idGarantia'];
		}
		
		require 'views/garantia/nueva.php';
	}

	function buscarProducto() {
		
		echo $this->GarantiaModel->buscarProductos( $_GET['patron'] );
	}

	function buscarClasificadores() {
		echo $this->GarantiaModel->buscarClasificadores( $_GET['clasificador'], $_GET['patron'] );
	}	

	function agregar() {
		$datos = [
			"id"		=> $_POST['id'],
			"nombre"	=> $this->nombreValido( $_POST['nombre'] ) ? $_POST['nombre'] : die('Nombre inválido'),
			"tipoGarantia"	=> is_numeric( $_POST['tipoGarantia'] ) ? $_POST['tipoGarantia'] : die('Tipo de garantía inválida'),
			"derechoGarantia"	=> is_numeric( $_POST['derechoGarantia'] ) ? $_POST['derechoGarantia'] : die('Derecho de garantía inválido'),
			"duracionGarantia"	=> is_numeric( $_POST['duracionGarantia'] ) ? $_POST['duracionGarantia'] : die('Duración de garantía inválida'),
			"politica"	=> is_numeric( $_POST['politica'] ) ? $_POST['politica'] : die('Duración de garantía inválida'),
			"tabla" 	=> $this->objetosValidos( $_POST['tabla'] ) ? $_POST['tabla'] : die('Objetos de garantia inválidos')
		];

		if($datos['id'] == "") {
			echo json_encode($this->GarantiaModel->agregar($datos['nombre'], $datos['tipoGarantia'], $datos['derechoGarantia'], $datos['duracionGarantia'], $datos['politica'], $datos['tabla'] ));
		}
		else {
			echo json_encode($this->GarantiaModel->actualizar( $datos['id'], $datos['nombre'], $datos['tipoGarantia'], $datos['derechoGarantia'], $datos['duracionGarantia'], $datos['politica'], $datos['tabla'] ));
		}
	}

	private function nombreValido($nombre) {
		return ( $nombre !== "" );
	}

	private function objetosValidos($prod) {
		$result = true;
		if( is_array( $prod ) ) {
			if( count( $prod ) > 0 ) {
				foreach ($prod as $key => $value) {
					if ( !is_integer( $key ) )
						$result = false;
				}
			}
		}
		else {
			$result = false;
		}
		return $result;
	}

	function obtener() {
		echo json_encode( $this->GarantiaModel->obtener( ) );
	}

	function obtenerUna() {
		echo json_encode( $this->GarantiaModel->obtenerUna( $_GET['idGarantia'] ) );
	}

	function activar() {
		echo json_encode( $this->GarantiaModel->activar( $_GET['id'] ) );
	}

	function desactivar() {
		echo json_encode( $this->GarantiaModel->desactivar( $_GET['id'] ) );
	}

	function buscarPoliticas() {
		echo $this->GarantiaModel->buscarPoliticas( $_GET['patron'] );
	}

	function descripcionPolitica() {
		echo $this->GarantiaModel->descripcionPolitica( $_GET['id'] );
	}

	function agregarPolitica() {
		echo $this->GarantiaModel->agregarPolitica( $_POST['nombre'], $_POST['descripcion'] );
	}

	function existeProducto() {
		$res = $this->GarantiaModel->existeProducto( $_GET['idProducto']);
		echo json_encode($res);
	}

	function existeClasificador() {
		$res = $this->GarantiaModel->existeClasificador( $_GET['idTipoClasificador'] , $_GET['idClasificador'] );
		echo json_encode($res);
	}

}

