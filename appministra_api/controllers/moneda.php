<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/moneda.php");

    class Moneda extends Commonapi
    {
        public static $OBTENERMONEDA = array("id" => array("nulo" => true, "vacio" => false, "tipo" => "entero"));
        public $MonedaModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->MonedaModel = new MonedaModel();
            $this->MonedaModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->MonedaModel->close();
        } 

        function obtenerMoneda(){
            $filtros = (isset($_REQUEST["id"])) ? "coin_id = ". $_REQUEST["id"] : "1=1";
            $parametros = array('id' => (isset($_REQUEST["id"])) ? $_REQUEST["id"] : null);
            $resultado = $this->MonedaModel->obtenerMoneda($filtros, $parametros);
            parent::responder($resultado);
        }

    }

?>
