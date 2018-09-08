<?php

    //ini_set("display_errors", 1); error_reporting(E_ALL);
	//Cargar la clase padre para este controlador
    require_once("controllers/api/common.php");
    //Cargar el modelo para este controlador
    require_once("models/api/productos.php");
    //Cargar los archivos necesarios

	class Productos extends Common
	{
		//Definir los filtros sobre los parametros que ingresen a la peticion, en caso de no necesitar parametros, dejar un array vacio
        public static   $INDEX = array();
        public static   $OBTENERDEPARTAMENTOS = array();
        public static   $OBTENERFAMILIAS = array();
        public static   $OBTENERLINEAS = array();
        public static   $OBTENERPRODUCTOS = array();
        public static   $BUSCARPRODUCTOS = array();
        public static   $DETALLEPRODUCTO = array();
        public static   $GUARDARPEDIDO = array();
        public static   $ENVIARPEDIDO = array();
        public static   $LISTARPRODUCTOS = array();
        public static   $LISTARCOMBOS = array();
        public static   $LISTARPRODUCTOSCOMBOS = array();
        public static   $GUARDARPEDIDOCOMBO = array();
        public static   $LISTARPROMOCIONES = array();
        public static   $LISTARPRODUCTOSPROMOS = array();
        public static   $GUARDARPEDIDOPROMOCION = array();


        function __construct(){
        	parent::__construct();
        }

        function __destruct(){
        	parent::__destruct();
        }

		public function index()
		{
			
		}

        public function obtenerDepartamentos()
        {
            parent::responder(ProductosModel::obtenerDepartamentos());
        }

        public function obtenerFamilias()
        {
            parent::responder(ProductosModel::obtenerFamilias($_REQUEST));
        }

        public function obtenerLineas()
        {
            parent::responder(ProductosModel::obtenerLineas($_REQUEST));
        }

        public function obtenerProductos()
        {
            parent::responder(ProductosModel::obtenerProductos($_REQUEST));
        }

        public function detalleProducto()
        {
            parent::responder(ProductosModel::detalleProducto($_REQUEST));
        }

        public function guardarPedido()
        {
            /* Guarda productos, combos y promociones en com_pedidos -----------------------------------------------*/

            $request['productos'] = json_decode(utf8_decode($_REQUEST['productos']), true);
            $idcomanda = $_REQUEST["id_comanda"];
            $usuario = $_REQUEST["id_mesero"];

            $id_insertado;
            $array_respuestas = array();
            $registros = array();
            foreach ($request['productos'] as $key => $value) {

                $registros = array();
                $cantidad = $value['cantidad'];
                $idproduct = $value['id_producto'];
                $idperson = $value['persona'];
                $opcionales = $value['opcionales'];
                $extras = $value['extras'];
                $sin = $value['sin'];
                $iddep = $value['departamento'];
                $nota_opcional = $value['nota_opcional'];
                $nota_extra = $value['nota_extra'];
                $nota_sin = $value['nota_sin'];
                $comentario = $value['comentario'];

                //echo $comentario; exit();

                // promociones
                if($idproduct == "0"){
                    $is_promocion = 1;
                    $id_promocion = $value['id_promocion'];
                    $dependencia_promocion = 0;
                    for($i = 1; $i <= $cantidad; $i++){
                        $id_insertado = ProductosModel::guardarPedido($idcomanda, $usuario, $idproduct,  $idperson, $opcionales, $extras, $sin, $iddep, $nota_opcional , $nota_extra, $nota_sin, $cantidad, $id_promocion, $is_promocion, $comentario, $dependencia_promocion);

                        $registros[$id_promocion] = $id_insertado;
                        $array_respuestas[]= $registros;
                    } 
                }else {
                    for($i = 1; $i <= $cantidad; $i++){
                        $dependencia_promocion = 0;
                        $id_insertado = ProductosModel::guardarPedido($idcomanda, $usuario, $idproduct,  $idperson, $opcionales, $extras, $sin, $iddep, $nota_opcional , $nota_extra, $nota_sin, $cantidad, $id_promocion, $is_promocion, $comentario, $dependencia_promocion);

                        $registros[$idproduct] = $id_insertado;
                        $array_respuestas[]= $registros;
                    }
                }            
            }

            parent::responder(array('status' => true, 'registros'=> $array_respuestas));
        }

        public function enviarPedido()
        {
            parent::responder(ProductosModel::enviarPedido($_REQUEST));
        }

        public function listarProductos()
        {
            $pedidos = ProductosModel::listarProductos($_REQUEST);
            $pedidos = $pedidos['registros'];

            foreach($pedidos[0] as &$comensal){

                foreach($comensal as &$producto){

                    if (!empty($producto['complementos'])) {
                        $filtro['complementos'] = $producto['complementos'];
                        $complementos = ProductosModel::listar_complementos($filtro, $_REQUEST['id_mesero']);
                        //Impuestos del productos ==============================================================
                        foreach ($complementos['registros'] as $kk => $vv) {
                            $precio = $vv['precio'];
                            $objeto['id'] = $vv['id'];
                
                            $impuestos = ProductosModel::listar_impuestos($objeto);
                            if ($impuestos['total'] > 0) {
                                foreach ($impuestos['registros'] as $k => $v) {
                                    if ($v["clave"] == 'IEPS') {
                                        $producto_impuesto = $ieps = (($v["precio"]) * $v["valor"] / 100);
                                    } else {
                                        if ($ieps != 0) {
                                            $producto_impuesto = ((($v["precio"] + $ieps)) * $v["valor"] / 100);
                                        } else {
                                            $producto_impuesto = (($v["precio"]) * $v["valor"] / 100);
                                        }
                                    }
                                // Precio actualizado
                                    $precio += $producto_impuesto;
                                    $precio = round($precio, 2);
                                }
                                
                                $complementos['registros'][$kk]['precio'] = $precio;
                            }
                        }
                        //FIN Impuestos del producto ===========================================================
                    }
                    if($producto['id_promocion'] != 0){

                        $precio = 0;
                        $promocion =  ProductosModel::get_promocion($producto['id_promocion']);
                        $producto['nombre'] = $promocion['nombre'];
                        $producto['tipo'] = $promocion['tipo'];
                        $producto['cantidad_to'] = $promocion['cantidad'];
                        $producto['cantidad_descuento'] = $promocion['cantidad_descuento'];
                        $producto['descuento'] = $promocion['descuento'];
                        $producto['precio_fijo'] = $promocion['precio_fijo'];
                        $promociones =  ProductosModel::get_promociones($producto['id'], $producto['id_promocion']);
                        $promociones = $promociones['registros'];
  
                        if($promocion['tipo'] == 1){
                            foreach ($promociones as $k => $v) {
                                $precio += $v['precio'];
                                $promociones[$k]['precio'] = 0;
                            }
                            $desc = (100 - $promocion['descuento']) / 100;
                            $precio = $precio * $desc;
                            $producto['precio'] = $precio;
                            
                        } else if($promocion['tipo'] == 2){
                            foreach ($promociones as $k => $v) {
                                if($k%2==0){
                                    $precio += $v['precio'];
                                }
                                $promociones[$k]['precio'] = 0;
                            }
                            $producto['precio'] = $precio;
                        } else if($promocion['tipo'] == 4){
                            foreach ($promociones as $k => $v) {
                                $precio += $promocion['precio_fijo'];
                                $promociones[$k]['precio'] = 0;
                            }
                            $producto['precio'] = $precio;
                            
                        } else if($promocion['tipo'] == 3){
                            for ($x=0; $x < $promocion['cantidad_descuento']; $x++) { 
                                $promociones[(count($promociones)-1) - $x]['precio'] = 0;
                            }
                            foreach ($promociones as $k => $v) {
                                $precio += $v['precio'];
                                $promociones[$k]['precio'] = 0;
                            }
                            $producto['precio'] = $precio;
                        } else if($promocion['tipo'] == 5){
                            //print_r($promociones);
                            foreach ($promociones as $k => $v) {
                                if($v['comprar'] == 1){
                                    $precio += $v['precio'];
                                }
                                $promociones[$k]['precio'] = 0;
                            }
                            $producto['precio'] = $precio;
                        } 
                        
                        $producto['promociones'] = $promociones;
                    }

                    //print_r($producto);
                }

            }

            parent::responder(array('status' => true, 'registros'=> $pedidos));
        }

        public function listarCombos()
        {
            parent::responder(ProductosModel::listarCombos($_REQUEST));
        }

        public function listarProductosCombos()
        {
            parent::responder(ProductosModel::listarProductosPromos($_REQUEST));
        }

        public function guardarPedidoCombo()
        {
            //ini_set("display_errors", 1); error_reporting(E_ALL);
            /* Guarda productos del combo en com_pedidos_combo -----------------------------------------------*/

            $request['productos'] = json_decode(utf8_decode($_REQUEST['productos']), true);
            $idcomanda = $_REQUEST["id_comanda"];
            $usuario = $_REQUEST["id_mesero"];
            $array_respuestas = array();
            $registros = array();
            $id_insertado;
            //print_r($_REQUEST); 
            foreach ($request['productos'] as $key => $value) {

                $registros = array();
                $cantidad = 1; // por default en web
                $idproduct = $value['id_producto'];
                $idperson = $value['persona'];
                $opcionales = $value['opcionales'];
                $extras = $value['extras'];
                $sin = $value['sin'];
                $iddep = $value['departamento'];
                $nota_opcional = $value['nota_opcional'];
                $nota_extra = $value['nota_extra'];
                $nota_sin = $value['nota_sin'];
                $idpedido = $value['id_insert'];
                        
                $id_insertado = ProductosModel::guardarPedidoCombo($idpedido, $idcomanda, $idproduct,  $idperson, $opcionales, $extras, $sin, $iddep, $nota_opcional, $nota_extra, $nota_sin, $cantidad);
                $registros[$idproduct] = $id_insertado;
                $array_respuestas[]= $registros;  
            }
            parent::responder(array('status' => true, 'registros'=> $array_respuestas));
        }

        public function listarPromociones()
        {
            parent::responder(ProductosModel::listarPromociones($_REQUEST));
        }

        public function listarProductosPromos()
        {
            $productos = ProductosModel::listarProductosPromos($_REQUEST);
            $productos = $productos['registros'];
            $grupo = '';
            $contador = 0;
            $cantidad = 0;
            // Ordena los productos del combo
            foreach ($productos as $k => &$v) {        
                if($_REQUEST['tipo_promocion'] == 1 || $_REQUEST['tipo_promocion'] == 2 || $_REQUEST['tipo_promocion'] == 4){
                    if(is_null($v['cantidad_grupo'])){
                        $productos[$contador]['cantidad_grupo'] = 0; 
                    }
                    $grupo = "1";
                } else if($_REQUEST['tipo_promocion'] == 3){
                    if(is_null($v['cantidad_grupo'])){
                        $productos[$contador]['cantidad_grupo'] = 0; 
                    }
                    $grupo = "2";
                } else if($_REQUEST['tipo_promocion'] == 5){
                    if($v['comprar'] == 1){
                        if(is_null($v['cantidad_grupo'])){
                            $productos[$contador]['cantidad_grupo'] = 0; 
                        }
                        $grupo = "3";
                    } else{
                        if(is_null($v['cantidad_grupo'])){
                            $productos[$contador]['cantidad_grupo'] = 0; 
                        }
                        $grupo = "4";
                    }
                }
                $productos[$contador]['grupo'] = $grupo; 
                $contador++;
            }
            parent::responder(array('status' => true, 'registros'=> $productos));
        }

        public function guardarPedidoPromocion()
        {
            /* Guarda productos de la promocion -----------------------------------------------*/
            $request['productos'] = json_decode(utf8_decode($_REQUEST['productos']), true);
            $idcomanda = $_REQUEST["id_comanda"];
            $usuario = $_REQUEST["id_mesero"];

            $id_insertado;
            $array_respuestas = array();
            $registros = array();
            foreach ($request['productos'] as $key => $value) {
                $registros = array();
                $cantidad = $value['cantidad'];
                $idproduct = $value['id_producto'];
                $idperson = $value['persona'];
                $opcionales = $value['opcionales'];
                $extras = $value['extras'];
                $sin = $value['sin'];
                $iddep = 0;
                $nota_opcional = $value['nota_opcional'];
                $nota_extra = $value['nota_extra'];
                $nota_sin = $value['nota_sin'];
                $dependencia_promocion = $value['dependencia_promocion'];

                    for($i = 1; $i <= $cantidad; $i++){

                        $id_insertado = ProductosModel::guardarPedidoPromocion($idproduct, $idperson, $idcomanda, $opcionales, $extras, $sin, $iddep, $nota_opcional, $nota_extra, $nota_sin, $id_promocion, $dependencia_promocion);

                        $registros[$idproduct] = $id_insertado;
                        $array_respuestas[]= $registros;
                    }    
            }

            parent::responder(array('status' => true, 'registros'=> $array_respuestas));
        }
	}
?>