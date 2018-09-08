<?php 
ini_set('display_erros', 1);
require('common.php');
require('models/cortesia.php');

class Cortesia extends Common {

	public $CortesiaModel;

	function __construct(){
		$this->CortesiaModel = new CortesiaModel();
		$this->CortesiaModel->connect();
	}

	function __destruct() {
		$this->CortesiaModel->close();
	}

	 function index() {
		require 'views/cortesia/cortesia.php';
	}

	function buscarProducto() {
		
		echo $this->CortesiaModel->buscarProductos( $_GET['patron'] );
	}	

	function agregar() {

		$datos = array(
			"id"		=> $_POST['id'],
			"nombre"	=> $this->nombreValido( $_POST['nombre'] ) ? $_POST['nombre'] : die('Nombre inválido'),
			"desde"		=> $this->fechaValida( $_POST['desde'] ) ? $_POST['desde'] : die('Fecha inválida'),
			"hasta"		=> $this->fechaValida( $_POST['hasta'] ) ? $_POST['hasta'] : die('Fecha inválida'),
			"productos" => $this->productosValidos( $_POST['productos'] ) ? $_POST['productos'] : die('Producto inválido')
		);

		if($datos['id'] == "") {
			echo json_encode($this->CortesiaModel->agregar($datos['nombre'], $datos['desde'], $datos['hasta'], $datos['productos'] ));
		}
		else {
			echo json_encode($this->CortesiaModel->actualizar( $datos['id'], $datos['nombre'], $datos['desde'], $datos['hasta'], $datos['productos'] ));
		}
	}

	private function nombreValido($nombre) {
		return ( $nombre !== "" );
	}

	private function fechaValida($fecha) {
		return preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/", $fecha);
	}

	private function productosValidos($prod) {
		$result = true;

		if( is_array( $prod ) ) {
			if( count( $prod ) > 0 ) {
				foreach ($prod as $key => $value) {
					if ( !is_integer($key) )
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
		echo json_encode( $this->CortesiaModel->obtener( ) );
	}

	function obtenerUno() {
		echo json_encode( $this->CortesiaModel->obtenerUno( $_GET['id'] ) );
	}

	function activar() {
		echo json_encode( $this->CortesiaModel->activar( $_GET['id'] ) );
	}

	function desactivar() {
		echo json_encode( $this->CortesiaModel->desactivar( $_GET['id'] ) );
	}

}

