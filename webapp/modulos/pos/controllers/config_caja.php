<?php 
//ini_set('display_erros', 1);
require('common.php');
require('models/config_caja.php');

class Config_Caja extends Common {

	public $ConfigCajaModel;

	function __construct(){
		$this->ConfigCajaModel = new ConfigCajaModel();
		$this->ConfigCajaModel->connect();
	}

	function __destruct() {
		$this->ConfigCajaModel->close();
	}

	 function index() {
	 	$metodos = $this->ConfigCajaModel->get_metodos_pago();
	 	/* ==== MOD CHRIS - tipo de documento === */
	 	$documentos = $this->ConfigCajaModel->get_documentos_pago();
	 	/* ==== FIN MOD === */
		require 'views/caja/config_caja.php';
	}

	function vista_metodos_pago() {
		$metodos = $this->ConfigCajaModel->get_metodos_pago();
		require 'views/caja/vista_metodos_pago.php';
	}

	function actualiza() {

		$tipoDescuento = $this->validarMonto( $_POST['tipoDescuento'] , 3) ? $_POST['tipoDescuento'] : die('Error: tipo des descuento inválido.') ;
		$limitUnitPorcentaje = $this->validarMonto( $_POST['limitUnitPorcentaje'] , 100) ? $_POST['limitUnitPorcentaje'] : die('Error: Porcentaje de descuento por producto inválido');
		$limitUnitCantidad = is_numeric( $_POST['limitUnitCantidad'] ) ? $_POST['limitUnitCantidad'] : die('Error: Cantidad de descuento por producto inválido') ;
		$limitGlobalPorcentaje = $this->validarMonto( $_POST['limitGlobalPorcentaje'] , 100) ? $_POST['limitGlobalPorcentaje'] : die('Error: Porcentaje de descuento global inválido');
		$limitGlobalCantidad = is_numeric( $_POST['limitGlobalCantidad'] ) ?  $_POST['limitGlobalCantidad'] : die('Error: Cantidad de descuento global inválido') ;
		$password = $_POST['password'];
		$cajaMax = is_numeric( $_POST['cajaMax'] ) ? $_POST['cajaMax'] : die('Error: Max. caja inválido');
		$retitoMax = is_numeric( $_POST['retitoMax'] ) ? $_POST['retitoMax'] : die('Error: Max. retiro inválido');
		//die($_POST['ticket']);
		$ticket = str_replace( '\n', ' ', $_POST['ticket'] ) ;
		$printAuto = $_POST['printAuto'];
		$puntos = $_POST['puntos'];
		$precio_unit_ticket = is_numeric( $_POST['precio_unit_ticket'] ) ? $_POST['precio_unit_ticket'] : die('Error: Max. retiro inválido');
		$cotizacionDescuento = $_POST['cotizacionDescuento'] == "true" ? 1 : 0;
		$ordenVentaDescuento = $_POST['ordenVentaDescuento'] == "true" ? 1 : 0;
		$activarDevCan = $_POST['activarDevCan'];
		$activarRetiroDevCan = $_POST['activarRetiroDevCan'];
		$activaPrecio = $_POST['modifica_precios'];
		$moduloPrint = $_POST['moduloPrint'];

		echo json_encode($this->ConfigCajaModel->actualiza( $tipoDescuento , $limitUnitPorcentaje , $limitUnitCantidad , $limitGlobalPorcentaje , $limitGlobalCantidad , $password , $cajaMax , $retitoMax , $ticket , $cotizacionDescuento , $ordenVentaDescuento, $precio_unit_ticket , $printAuto,$puntos, $activarDevCan, $activarRetiroDevCan,$activaPrecio, $moduloPrint ) ); 
	}

	private function validarMonto($cantidad, $maximo) {
		return ($cantidad <= $maximo);
	}

	public function consulta(){
		echo json_encode($this->ConfigCajaModel->consulta());
	}

	public function activarProntipagos(){

		echo json_encode($this->ConfigCajaModel->activarProntipagos($_REQUEST));
	}

	public function activarProductosProntipagos(){
		$listaSKU = $_REQUEST["productos"];
		echo json_encode($this->ConfigCajaModel->activarProductosProntipagos($listaSKU));
	}

}

