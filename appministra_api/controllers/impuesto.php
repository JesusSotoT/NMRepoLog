<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/impuesto.php");

    class Impuesto extends Commonapi
    {
        public static $OBTENERIMPUESTO = array("id" => array("nulo" => true, "vacio" => false, "tipo" => "entero"));
        public $ImpuestoModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ImpuestoModel = new ImpuestoModel();
            $this->ImpuestoModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->ImpuestoModel->close();
        } 

        function obtenerImpuesto(){
            $filtros = (isset($_REQUEST["id"])) ? "id = :id" : "1=1";
            $parametros = array('id' => $_REQUEST["id"]);
            $resultado = $this->ImpuestoModel->obtenerImpuesto($filtros, $parametros);

            parent::responder($resultado);
        }

    }

?>
