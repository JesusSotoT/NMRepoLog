<?php 
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/pedido/pedido.php");

class Pedido extends Common {

    public $pedidoModel;

    function __construct() {
        //Se crea el objeto que instancia al modelo que se va a utilizar
        $this->pedidoModel = new pedidoModel();
    }

    function imprimeGridP() {
        unset($_SESSION['pedido']);
        require('views/pedido/pedido.php');
    }
    function loadProductsP(){
        require('views/pedido/newPedido.php');
    }
    function buscaP(){
        $idEmpleado = $_POST['empleado'];
        $idCliente = $_POST['cliente'];
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];

        $grid = $this->pedidoModel->buscaP($idEmpleado,$idCliente,$desde,$hasta);
        echo json_encode($grid);
    }
    function buscaP2(){
        $idEmpleado = $_POST['empleado'];
        $idCliente  = $_POST['cliente'];
        $desde      = $_POST['desde'];
        $hasta      = $_POST['hasta'];
        $lim        = $_POST['lim'];

        $grid = $this->pedidoModel->buscaP2($idEmpleado,$idCliente,$desde,$hasta,$lim);
        echo json_encode($grid);
    }
    function printGridP(){
        $pedidos = $this->pedidoModel->getGridP();
        echo json_encode($pedidos);
    }
    function printFiltrosP(){
        $filtros = $this->pedidoModel->printFiltrosP();
        echo json_encode($filtros);
    }
    function pedidoView(){
        $idPedido = $_GET['pe'];
        
        $filtros = $this->pedidoModel->pedidoView($idPedido);
        //print_r($filtros);
        require('views/pedido/loadPedido.php');
    }
    function pedidoView1(){
        require('views/pedido/newPedido.php');
    }
    function addProductP(){
        $idProducto = $_POST['idProducto'];
        $cantidad = $_POST['cantidad'];
        $addProduct = $this->pedidoModel->addProductP($idProducto,$cantidad);
  
        echo json_encode($addProduct);
    }
    function sendP(){
        $idPedido = $_POST['idPedido'];
        $idCliente = $_POST['idCliente'];
        $observacion = $_POST['observacion'];
        $cotizacion = $this->pedidoModel->saveP($idCliente,$observacion,$idPedido);

        echo json_encode($cotizacion);
    }
    function deleteProP(){
          $idPedido = $_POST['idPedido']; 
          $idProducto = $_POST['id'];
          $products = $this->pedidoModel->deleteProP($idProducto,$idPedido);
          echo json_encode($products);
    }
    function sendCajaPedido(){
        $idPedido = $_POST['idPedido'];
        $products = $this->pedidoModel->sendCajaPedido($idPedido);
        echo json_encode($products);
    }   
    function aProceso(){
        $idPedido = $_POST['id'];

        $res = $this->pedidoModel->aProceso($idPedido);

        echo json_encode($res);
    }
    function aTerminado(){
        $idPedido = $_POST['id'];

        $res = $this->pedidoModel->aTerminado($idPedido);
        
        echo json_encode($res);
    }


    function cancelarP(){
        $idPedido = $_POST['id'];
        $res = $this->pedidoModel->cancelarP($idPedido);
        echo json_encode($res);

    }
    function eliminarP(){
        $idPedido = $_POST['id'];
        $res = $this->pedidoModel->eliminarP($idPedido);
        echo json_encode($res);

    }
}






















?>