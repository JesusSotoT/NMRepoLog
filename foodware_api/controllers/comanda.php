<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/comanda.php");

    class Comanda extends Comun
    {
        public $ComandaModel;

        public static $OBTENERINFORMACION = array();
        public static $OBTENERMESAS = array("id" => array("nulo" => true, "vacio" => false, "tipo" => "entero"));
        public static $OBTENERAREAS = array();
        public static $OBTENEREMPLEADOS = array();
        public static $OBTENERCONFIGURACION = array();
        public static $INICIARSESION = array();
        public static $OBTENERDEPARTAMENTOS = array();
        public static $OBTENERFAMILIAS = array();
        public static $OBTENERLINEAS = array();
        public static $OBTENERPRODUCTOSALL = array();
        public static $OBTENERPRODUCTOSDEP = array();
        public static $OBTENERPRODUCTOSFAM = array();
        public static $OBTENERPRODUCTOSLIN = array();
        public static $DETALLESPRODUCTO = array();
        public static $ELIMINARMESAS = array();
        public static $JUNTARMESAS = array();
        public static $GUARDARPEDIDO = array();
        public static $ENVIARCAJA = array();


        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ComandaModel = new ComandaModel($this->Seguridad);
            $this->ComandaModel->connect();
            if(isset($_SESSION)) session_start();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->ComandaModel->close();
        } 

        function obtenerInformacion(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerInformacion();
            parent::responder($resultado);
        }

        function obtenerMesas(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            if(!isset($_REQUEST["id"])){
                unset($_SESSION["tables"]);
                unset($_SESSION["area"]);
            }
            $resultado = $this->ComandaModel->obtenerMesas((isset($_REQUEST["id"])) ? $_REQUEST["id"] : null);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }

        function obtenerAreas(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerAreas();
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }

        function obtenerEmpleados(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerEmpleados();
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }

        function obtenerConfiguracion(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerConfiguracion();
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function iniciarSesion(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->iniciarSesion($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function obtenerDepartamentos(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerDepartamentos();
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function obtenerFamilias(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerFamilias($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function obtenerLineas(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerLineas($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function obtenerProductosAll(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerProductosAll();
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function obtenerProductosDep(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerProductosDep($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function obtenerProductosFam(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerProductosFam($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function obtenerProductosLin(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->obtenerProductosLin($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function detallesProducto(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->detallesProducto($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function eliminarMesas(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->eliminarMesas($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function juntarMesas(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->juntarMesas($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function guardarPedido(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->guardarPedido($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }
        function enviarCaja(){
            $_SESSION = unserialize(base64_decode($_REQUEST["sesion"]));
            $resultado = $this->ComandaModel->enviarCaja($_REQUEST);
            $sesion = base64_encode(serialize($_SESSION));
            $resultado["sesion"] = $sesion;
            parent::responder($resultado);
        }

    }

?>
