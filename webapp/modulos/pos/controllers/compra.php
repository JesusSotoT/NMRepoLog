<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/compra.php");

class Compra extends Common
{
	public $CompraModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->CompraModel = new CompraModel();
		$this->CompraModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->CompraModel->close();
	} 

	function indexGrid(){
		$ordenesCompra = $this->CompraModel->indexGrid();
		
		require('views/compra/gridIndex.php');
	}
	function recepcionGrid(){
		$ordenesCompra = $this->CompraModel->indexGrid();
		
		require('views/compra/gridRecepcion.php');
	}
	function index()
	{	
		$proveedores = $this->CompraModel->ListaProveedor();
		$almacenes = $this->CompraModel->almacenes();
		/*foreach ($proveedores as $key => $value) {
		 echo ''.$value['razon_social'].'<br>';
		} */
		require('views/compra/ordenCompra.php');
	}
	function pruebasVistas()
	{	
		$proveedores = $this->CompraModel->ListaProveedor();

		require('views/compra/formProducto.php');
	}
	function productosProveedor(){
		$idPrv = $_POST['idPrv'];
		$idAlmacen = $_POST['idAlmacen'];
		$productos = $this->CompraModel->productosProveedor($idPrv,$idAlmacen);
		echo json_encode($productos);
	}
	function buscaIdpro(){
		$codigo = $_POST['codigo'];
		$codigoPro = $this->CompraModel->buscaIdpro($codigo);
		echo json_encode($codigoPro);
	}
	function guardaOrden(){

		$idAlmacen = $_POST['idAlmacen'];
        $idProvedor = $_POST['idProvedor'];
        $productos = $_POST['productos'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $idOrden = $_POST['idOrden'];
        $subTotal = $_POST['subTotal'];
        $total = $_POST['total'];
        $user = $_POST['user'];
        if($idOrden!=''){
        	$guarda = $this->CompraModel->updateOrdenCompra($idOrden,$idAlmacen,$idProvedor,$productos,$fecha_entrega,$subTotal,$total,$user);
        }else{
        	$guarda = $this->CompraModel->guardaOrden($idAlmacen,$idProvedor,$productos,$fecha_entrega,$subTotal,$total,$user);
        }
 
 		echo json_encode($guarda);
	}
	function ordenCompra(){
		$idOdenCompra = $_GET['idorden'];
		
		$ordenBasicos = $this->CompraModel->ordenBasicos($idOdenCompra);
		//print_r($ordenBasicos[0]['idPrv']);
		$proPedidos = $this->CompraModel->productosOrden($idOdenCompra);
		$proveProduc = $this->CompraModel->produProve($ordenBasicos[0]['idPrv']);
		$almacenes = $this->CompraModel->almacenes();
		$usuarios = $this->CompraModel->usuarios();
		//$datosRecepcion = $this->CompraModel->datosRecepcion($idOdenCompra);

		require('views/compra/recepcionOrden.php');
	}
	function recepcionOrden(){
		$idOdenCompra = $_GET['idorden'];
		
		$ordenBasicos = $this->CompraModel->ordenBasicos($idOdenCompra);
		//print_r($ordenBasicos[0]['idPrv']);
		$proPedidos = $this->CompraModel->productosOrden($idOdenCompra);
		//print_r($proPedidos);
		$proveProduc = $this->CompraModel->produProve($ordenBasicos[0]['idPrv']);
		$usuarios = $this->CompraModel->usuarios();
		require('views/compra/recibeMercancia.php');
	}
	function recepcionOrdenRecibida(){
		$idOdenCompra = $_GET['idorden'];
		
		$ordenBasicos = $this->CompraModel->ordenBasicos($idOdenCompra);
		//print_r($ordenBasicos[0]['idPrv']);
		$proPedidos = $this->CompraModel->productosOrden($idOdenCompra);
		//print_r($proPedidos);
		$proveProduc = $this->CompraModel->produProve($ordenBasicos[0]['idPrv']);
		$usuarios = $this->CompraModel->usuarios();
		$recibidos = $this->CompraModel->recibidosPreviamente($idOdenCompra);
		$datosRecepcionesGenerales = $this->CompraModel->recepcionesDatosGenerales($idOdenCompra);
		require('views/compra/recibeMercancia.php');
	}
	function agregaMasProd(){
		$idProducto = $_POST['idProducto'];
		$cantidad = $_POST['cantidad'];
		$precio = $_POST['precio'];

		$datosPro = $this->CompraModel->agregaMasProd($idProducto,$cantidad,$precio);

		echo json_encode($datosPro);
	}
	function recibeOrden(){
		$idAlmacen = $_POST['idAlmacen'];
        $idProvedor = $_POST['idProvedor'];
        $productos = $_POST['productos'];
        $fecha_entrega = $_POST['fecha_entrega'];
        $idOrden = $_POST['idOrden'];
        $factura = $_POST['factura'];
        $observaciones = $_POST['observaciones'];
        $facturaImporte = $_POST['facturaImporte'];
        $fecha_factura = $_POST['fecha_factura'];

         $recepcion = $this->CompraModel->recibeOrden($idOrden,$idAlmacen,$idProvedor,$productos,$fecha_entrega,$factura,$observaciones,$facturaImporte,$fecha_factura);
		echo json_encode($recepcion);
	}
	function calculaPrecios(){
		$productos = $_POST['productos'];

		$precios = $this->CompraModel->calculaImpuestos($productos);
		echo json_encode($precios);
	}














}


?>
