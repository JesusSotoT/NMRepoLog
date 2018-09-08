<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/producto.php");

    class Producto extends Commonapi
    {
        public static $OBTENERPRODUCTO = array(
                                                "departamento" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                                                "nombre" => array("nulo" => true, "vacio" => false, "tipo" => "string")
                                                );

        public static $AGREGARPRODUCTO = array(
                                                "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                "descripcion_corta" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                "descripcion_larga" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                "precio" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                                                "codigo" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                "tipo" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "moneda" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "medida" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "departamento" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "impuestos" => array("nulo" => false, "vacio" => false, "tipo" => "json"),
                                                "foto" => array("nulo" => true, "vacio" => false, "tipo" => "string")
                                                );

        public static $EDITARPRODUCTO = array(
                                                "id" => array("nulo" => false, "vacio" => false, "tipo" => "int"),
                                                "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                "descripcion_corta" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                "descripcion_larga" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                "precio" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                                                "codigo" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                "tipo" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "moneda" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "medida" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "departamento" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "impuestos" => array("nulo" => false, "vacio" => false, "tipo" => "json"),
                                                "foto" => array("nulo" => true, "vacio" => false, "tipo" => "string")
                                                );
        public static $ELIMINARPRODUCTO = array("id" => array("nulo" => false, "vacio" => false, "tipo" => "int")); 
        public $ProductoModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ProductoModel = new ProductoModel();
            $this->ProductoModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->ProductoModel->close();
        } 

        function obtenerProducto(){
            $filtros = (isset($_REQUEST["departamento"])) ? "departamento = ". $_REQUEST["departamento"] ." AND " : "";
            $filtros .= (isset($_REQUEST["nombre"])) ? "nombre LIKE '%". $_REQUEST["nombre"] ."%'" : "1=1";

            $parametros = array(
                                "departamento" => (isset($_REQUEST["departamento"])) ? $_REQUEST["departamento"] : null,
                                "nombre" => (isset($_REQUEST["nombre"])) ? $_REQUEST["nombre"] : null
                                );

            $resultado = $this->ProductoModel->obtenerProducto($filtros, $parametros);
            parent::responder($resultado);
        }

        function agregarProducto(){
            $nombre = $_REQUEST["nombre"];
            $des_corta = $_REQUEST["descripcion_corta"];
            $des_larga = $_REQUEST["descripcion_larga"];
            $precio = $_REQUEST["precio"];
            $codigo = $_REQUEST["codigo"];
            $tipo = $_REQUEST["tipo"];
            $moneda = $_REQUEST["moneda"];
            $medida = $_REQUEST["medida"];
            $departamento = $_REQUEST["departamento"];
            $impuestos = json_decode($_REQUEST["impuestos"], true);
            if(isset($_REQUEST["foto"])){
                date_default_timezone_set("Mexico/General");
                $fecha_actual = date("YmdHis");
                $foto_b64 = $_REQUEST["foto"];
                $foto_b64 = str_replace('data:image/jpeg;base64,', '', $foto_b64);
                $foto_b64 = str_replace(' ', '+', $foto_b64);
                $foto_b64 = base64_decode($foto_b64);
                $imagen = 'images/productos/inco-'. $fecha_actual .'.jpeg';
                if(file_put_contents('../webapp/modulos/pos/'. $imagen, $foto_b64) !== false){
                    $resultado = $this->ProductoModel->agregarProducto($nombre, $codigo, $precio, $des_larga, $des_corta, $departamento, $tipo, $medida, $impuestos["lista"], $moneda, $imagen);
                }else{
                    $resultado = array("status" => false, "mensaje" => "No fue posible guardar la imagen, intentalo nuevamente");
                }
            }else{
                $resultado = $this->ProductoModel->agregarProducto($nombre, $codigo, $precio, $des_larga, $des_corta, $departamento, $tipo, $medida, $impuestos["lista"], $moneda, "noimage.jpeg");
            }
            parent::responder($resultado);
        }

        function editarProducto(){
            $id = $_REQUEST["id"];
            $nombre = $_REQUEST["nombre"];
            $des_corta = $_REQUEST["descripcion_corta"];
            $des_larga = $_REQUEST["descripcion_larga"];
            $precio = $_REQUEST["precio"];
            $codigo = $_REQUEST["codigo"];
            $tipo = $_REQUEST["tipo"];
            $moneda = $_REQUEST["moneda"];
            $medida = $_REQUEST["medida"];
            $departamento = $_REQUEST["departamento"];
            $impuestos = json_decode($_REQUEST["impuestos"], true);
            if(isset($_REQUEST["foto"])){
                $foto_anterior = $_REQUEST["foto_actual"];
                $foto_b64 = $_REQUEST["foto"];
                $foto_b64 = str_replace('data:image/jpeg;base64,', '', $foto_b64);
                $foto_b64 = str_replace(' ', '+', $foto_b64);
                $foto_b64 = base64_decode($foto_b64);
                if($foto_anterior == "noimage.jpeg"){
                    date_default_timezone_set("Mexico/General");
                    $fecha_actual = date("YmdHis");
                    $foto_anterior = 'images/productos/inco-'. $fecha_actual .'.jpeg';
                }
                $imagen = $foto_anterior;
                if(file_put_contents('../webapp/modulos/pos/'. $imagen, $foto_b64) !== false){
                    $resultado = $this->ProductoModel->editarProducto($id, $nombre, $codigo, $precio, $des_larga, $des_corta, $departamento, $tipo, $medida, $impuestos["lista"], $moneda, $imagen);
                }else{
                    $resultado = array("status" => false, "mensaje" => "No fue posible guardar la imagen, intentalo nuevamente");
                }
            }else{
                $resultado = $this->ProductoModel->editarProducto($id, $nombre, $codigo, $precio, $des_larga, $des_corta, $departamento, $tipo, $medida, $impuestos["lista"], $moneda, null);
            }
            parent::responder($resultado);
        }

        function eliminarProducto(){
            $id = $_REQUEST["id"];
            $resultado = $this->ProductoModel->eliminarProducto($id);
            parent::responder($resultado);
        }
        
    }

?>
