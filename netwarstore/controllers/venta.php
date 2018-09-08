<?php

    //ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/venta.php");

    class Venta extends Common
    {
        public $VentaModel;

        public static $VENDER = array(
                                    "productos" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                                );

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->VentaModel = new VentaModel($this->Seguridad);
            $this->VentaModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->VentaModel->close();
        } 

        function venta(){
            $resultado = $this->VentaModel->venta($_REQUEST["productos"], $_REQUEST["total"]);
            parent::responder($resultado, false);
        }

    }

?>
