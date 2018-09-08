<?php

//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/cotizacion/cotizacion.php");

class Cotizacion extends Common {

	public $cotizacionModel;

	function __construct() {
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->cotizacionModel = new cotizacionModel();
	}

	  function imprimeGrid() {

		  require('views/cotizacion/cotizacion.php');
	  }
	  function loadProducts(){
		  require('views/cotizacion/newCotizacion.php');
	  }
	  function deleteSession(){
		unset($_SESSION['cotiza']);
		echo '1';
	  }
	  function buscar(){
		$idCliente = $_POST['cliente'];
		$idEmpleado = $_POST['empleado'];
		$desde = $_POST['desde'];
		$hasta = $_POST['hasta'];
		$result = $this->cotizacionModel->buscar($idCliente,$idEmpleado,$desde,$hasta);
		echo json_encode($result);
	  }

	  function listas(){
		$idProducto= $_POST['idProducto'];
		$result = $this->cotizacionModel->listas($idProducto);
		echo json_encode($result);        
	  }

	  function getClient(){
		$client = $this->cotizacionModel->getClient();
		echo json_encode($client);
	  }
	  function getProduct(){
		$products = $this->cotizacionModel->getProducts();
		echo json_encode($products);

	  }
	  function addProduct(){
		$idProducto = $_POST['idProducto'];
		$cantidad = $_POST['cantidad'];
		$precio = $_POST['precio'];
		
		if($precio == ''){
		  $precio = $this->cotizacionModel->precio($idProducto);
		}else{
		  $precio = $precio;
		}
		
		$addProduct = $this->cotizacionModel->addProduct($idProducto,$cantidad,$precio);

		echo json_encode($addProduct);
	  }
	  function deletePro(){
		  $idProducto = $_POST['id'];
		  $products = $this->cotizacionModel->deletePro($idProducto);
		  echo json_encode($products);
	  }
	  function send(){
		$idCliente = $_POST['idCliente'];
		$observacion = $_POST['observacion'];
		$cotizacion = $this->cotizacionModel->save($idCliente,$observacion);

		echo json_encode($cotizacion);
	  }
	  function printGrid(){
		$grid = $this->cotizacionModel->printGrid();
		echo json_encode($grid);
	  }
	  function printFiltros(){
		$filtros = $this->cotizacionModel->printFiltros();
		echo json_encode($filtros);
	  }
	  function resubmit(){
		$id = $_POST['id'];
		$reenvio = $this->cotizacionModel->resubmit($id);
		echo json_encode($reenvio);
	  }
	  function createPedido(){
		$idCotizacion = $_POST['idCotizacion'];
		$pedi = $this->cotizacionModel->createPedido($idCotizacion);
		echo json_encode($pedi);
	  }
	  function eliminaCoti(){
		$idcoti = $_POST['id'];

		$res = $this->cotizacionModel->eliminaCoti($idcoti);

		echo json_encode($res);
	  }



}

?>