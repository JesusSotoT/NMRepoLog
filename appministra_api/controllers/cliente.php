<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/cliente.php");

    class Cliente extends Commonapi
    {
        public static $OBTENERCLIENTE = array(
                "nombre" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                "id" => array("nulo" => true, "vacio" => false, "tipo" => "entero"),
                );

        public static $AGREGARCLIENTE = array(
                "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "num_int" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "num_ext" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "direccion" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "colonia" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "estado" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "municipio" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "email" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "telefono" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "rfc" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "cp" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                );

        public static $EDITARCLIENTE = array(
                "id" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "num_int" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "num_ext" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "direccion" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "colonia" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "estado" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "municipio" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "email" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "telefono" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "rfc" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "cp" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                );

        public static $ELIMINARCLIENTE = array("id" => array("nulo" => false, "vacio" => false, "tipo" => "string"));

        public static $OBTENERFACTURACION = array(
                "rfc" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                "cliente" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                "id" => array("nulo" => true, "vacio" => false, "tipo" => "entero"),
                );

        public static $AGREGARFACTURACION = array(
                "id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "rfc" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "razon_social" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "email" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "pais" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "regimen_fiscal" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "direccion" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "num_ext" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "cp" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "colonia" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "estado" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "ciudad" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "municipio" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                );

        public static $EDITARFACTURACION = array(
                "id_rfc" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "rfc" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "razon_social" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "email" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "pais" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "regimen_fiscal" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "direccion" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "num_ext" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "cp" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "colonia" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "estado" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "ciudad" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "municipio" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                );

        public $ClienteModel;

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ClienteModel = new ClienteModel();
            $this->ClienteModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->ClienteModel->close();
        } 

        function obtenerCliente(){
            $filtros = (isset($_REQUEST["nombre"])) ? "cc.nombre LIKE '%:nombre%' AND " : "";
            $filtros .= (isset($_REQUEST["id"])) ? "cc.id = :id" : "1=1";

            $parametros = array('nombre' => $_REQUEST["nombre"], 'id' => $_REQUEST["id"]);
            $resultado = $this->ClienteModel->obtenerCliente($filtros, $parametros);
            parent::responder($resultado);
        }

        function agregarCliente(){
            $nombre = $_REQUEST['nombre'];
            $num_int = $_REQUEST['num_int']; 
            $num_ext = $_REQUEST['num_ext'];
            $direccion = $_REQUEST['direccion'];
            $colonia = $_REQUEST['colonia']; 
            $codigo_postal = $_REQUEST['cp'];
            $estado = $_REQUEST['estado'];  
            $municipio = $_REQUEST['municipio'];
            $email = $_REQUEST['email'];
            $telefono =  $_REQUEST['telefono'];
            $rfc = $_REQUEST['rfc'];
            $cliente = $this->ClienteModel->agregarCliente($nombre, $direccion, $num_int, $num_ext, $colonia, $estado, $municipio, $codigo_postal, $email, $telefono, $rfc);         
            parent::responder($cliente);
        }

        function editarCliente(){
            $id = $_REQUEST["id"];
            $nombre = $_REQUEST['nombre'];
            $num_int = $_REQUEST['num_int']; 
            $num_ext = $_REQUEST['num_ext'];
            $direccion = $_REQUEST['direccion'];
            $colonia = $_REQUEST['colonia']; 
            $codigo_postal = $_REQUEST['cp'];
            $estado = $_REQUEST['estado'];  
            $municipio = $_REQUEST['municipio'];
            $email = $_REQUEST['email'];
            $telefono =  $_REQUEST['telefono'];
            $rfc = $_REQUEST['rfc'];
            $cliente = $this->ClienteModel->editarCliente($id, $nombre, $direccion, $num_int, $num_ext, $colonia, $estado, $municipio, $codigo_postal, $email, $telefono, $rfc);         
            parent::responder($cliente);
        }

        function eliminarCliente(){
            $id = $_REQUEST['id'];
            $res = $this->ClienteModel->eliminarCliente($id);
            parent::responder($res);
        }

        function obtenerFacturacion(){
            $filtros = (isset($_REQUEST["rfc"])) ? "rfc LIKE '%". $_REQUEST["rfc"] ."%' AND " : "";
            $filtros .= (isset($_REQUEST["cliente"])) ? "nombre = ". $_REQUEST["cliente"] ." AND " : "";
            $filtros .= (isset($_REQUEST["id"])) ? "id = ". $_REQUEST["id"] : "1=1";
            $resultado = $this->ClienteModel->obtenerFacturacion($filtros);
            parent::responder($resultado);

            /*$filtros = (isset($_REQUEST["rfc"])) ? "rfc LIKE '%:rfc%' AND " : "";
            $filtros .= (isset($_REQUEST["cliente"])) ? "nombre = :cliente AND " : "";
            $filtros .= (isset($_REQUEST["id"])) ? "id = :id" : "1=1";

            $parametros = array(
                    'rfc' => (isset($_REQUEST["rfc"])) ? $_REQUEST["rfc"] : "null", 
                    'cliente' => (isset($_REQUEST["cliente"])) ? $_REQUEST["cliente"] : "null", 
                    'id' => (isset($_REQUEST["id"])) ? $_REQUEST["id"] : "-1"
                    );
            $resultado = $this->ClienteModel->obtenerFacturacion($filtros, $parametros);
            parent::responder($resultado);*/
        }

        function agregarFacturacion(){
            $nombre = $_REQUEST['id'];
            $rfc = $_REQUEST['rfc'];
            $razon_social = $_REQUEST['razon_social'];
            $email = $_REQUEST['email'];
            $pais = $_REQUEST['pais'];
            $regimen_fiscal = $_REQUEST['regimen_fiscal'];
            $direccion = $_REQUEST['direccion'];
            $num_ext = $_REQUEST['num_ext'];
            $codigo_postal = $_REQUEST['cp'];
            $colonia = $_REQUEST['colonia']; 
            $estado = $_REQUEST['estado']; 
            $ciudad = $_REQUEST['ciudad']; 
            $municipio = $_REQUEST['municipio'];
            
            $facturacion = $this->ClienteModel->agregarFacturacion($nombre, $rfc, $razon_social, $email, $pais, $regimen_fiscal, $direccion, $num_ext, $codigo_postal, $colonia, $estado, $ciudad, $municipio);         
            parent::responder($facturacion);
        }

        function editarFacturacion(){
            $id = $_REQUEST['id_rfc'];
            $nombre = $_REQUEST['id'];
            $rfc = $_REQUEST['rfc'];
            $razon_social = $_REQUEST['razon_social'];
            $email = $_REQUEST['email'];
            $pais = $_REQUEST['pais'];
            $regimen_fiscal = $_REQUEST['regimen_fiscal'];
            $direccion = $_REQUEST['direccion'];
            $num_ext = $_REQUEST['num_ext'];
            $codigo_postal = $_REQUEST['cp'];
            $colonia = $_REQUEST['colonia']; 
            $estado = $_REQUEST['estado']; 
            $ciudad = $_REQUEST['ciudad']; 
            $municipio = $_REQUEST['municipio'];
            
            $facturacion = $this->ClienteModel->editarFacturacion($id, $nombre, $rfc, $razon_social, $email, $pais, $regimen_fiscal, $direccion, $num_ext, $codigo_postal, $colonia, $estado, $ciudad, $municipio);         
            parent::responder($facturacion);
        }

    }

?>
