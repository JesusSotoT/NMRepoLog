<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/comprobante.php");

    class Comprobante extends Common
    {
        public $ComprobanteModel;
        public static $LISTADO = array("anticipo" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));
        public static $AGREGAR = array(
                                        "anticipo" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                        "categoria" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                        "monto" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                        "ticket1" => array("nulo" => true, "vacio" => false, "tipo" => "imagen"),
                                        "ticket2" => array("nulo" => true, "vacio" => false, "tipo" => "imagen"),
                                        "observaciones" => array("nulo" => false, "vacio" => true, "tipo" => "string")
                                    );
        public static $EDITAR = array(  
                                        "id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                        "anticipo" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                        "categoria" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                        "monto" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                        "ticket1" => array("nulo" => true, "vacio" => false, "tipo" => "imagen"),
                                        "ticket2" => array("nulo" => true, "vacio" => false, "tipo" => "imagen"),
                                        "observaciones" => array("nulo" => false, "vacio" => true, "tipo" => "string")
                                    );
        public static $GRAFICO = array("anticipo" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ComprobanteModel = new ComprobanteModel($this->Seguridad);
            $this->ComprobanteModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->ComprobanteModel->close();
        } 

        function listado(){
            $resultado = $this->ComprobanteModel->listado($_REQUEST["anticipo"]);
            parent::responder($resultado);
        }

        function agregar(){
            $resultado = $this->ComprobanteModel->agregar($_REQUEST);
            parent::responder($resultado);
        }

        function editar(){
            $resultado = $this->ComprobanteModel->editar($_REQUEST);
            parent::responder($resultado);
        }

        function grafico(){
            $resultado = $this->ComprobanteModel->grafico($_REQUEST["anticipo"]);
            parent::responder($resultado);
        }

    }

?>
