<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/sucursal.php");

    class Sucursal extends Commonapi
    {
        public static $OBTENERSUCURSAL = array("nombre" => array("nulo" => true, "vacio" => false, "tipo" => "string"));
        public static $AGREGARSUCURSAL = array(
                                                "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                "direccion" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                "estado" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "municipio" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "codigo_postal" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "telefono" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "contacto" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                );
        public static $EDITARSUCURSAL = array(
                                                "id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                "direccion" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                "estado" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "municipio" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "codigo_postal" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "telefono" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "contacto" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                );
        public $SucursalModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->SucursalModel = new SucursalModel();
            $this->SucursalModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->SucursalModel->close();
        } 

        function obtenerSucursal(){
            $filtros = (isset($_REQUEST["nombre"])) ? "nombre LIKE '%". $_REQUEST["nombre"] ."%'" : "1=1";
            $parametros = array('nombre' => (isset($_REQUEST["nombre"])) ? $_REQUEST["nombre"] : null);
            $resultado = $this->SucursalModel->obtenerSucursal($filtros, $parametros);
            parent::responder($resultado);
        }

        function agregarSucursal(){
            $nombre = $_REQUEST["nombre"];
            $direccion = $_REQUEST["direccion"];
            $estado = $_REQUEST["estado"];
            $municipio = $_REQUEST["municipio"];
            $codigo_postal = $_REQUEST["codigo_postal"];
            $telefono = $_REQUEST["telefono"];
            $contacto = $_REQUEST["contacto"];
            $resultado = $this->SucursalModel->agregarSucursal($nombre, $direccion, $estado, $municipio, $codigo_postal, $telefono, $contacto);
            parent::responder($resultado);
        }

        function editarSucursal(){
            $id = $_REQUEST["id"];
            $nombre = $_REQUEST["nombre"];
            $direccion = $_REQUEST["direccion"];
            $estado = $_REQUEST["estado"];
            $municipio = $_REQUEST["municipio"];
            $codigo_postal = $_REQUEST["codigo_postal"];
            $telefono = $_REQUEST["telefono"];
            $contacto = $_REQUEST["contacto"];
            $resultado = $this->SucursalModel->editarSucursal($id, $nombre, $direccion, $estado, $municipio, $codigo_postal, $telefono, $contacto);
            parent::responder($resultado);
        }

        function eliminarSucursal(){
            $id = $_REQUEST["id"];
            $resultado = $this->SucursalModel->eliminarSucursal($id);
            parent::responder($resultado);
        }
        
    }

?>
