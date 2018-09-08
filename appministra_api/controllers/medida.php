<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/medida.php");

    class Medida extends Commonapi
    {
        public $MedidaModel;
        public static $OBTENERMEDIDA = array("id" => array("nulo" => true, "vacio" => false, "tipo" => "entero"));

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->MedidaModel = new MedidaModel();
            $this->MedidaModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->MedidaModel->close();
        } 

        function obtenerMedida(){
            $filtros = (isset($_REQUEST["id"])) ? "id = ". $_REQUEST["id"] : "1=1";
            $parametros = array('id' => (isset($_REQUEST["id"])) ? $_REQUEST["id"] : null);                
            $resultado = $this->MedidaModel->obtenerMedida($filtros, $parametros);
            parent::responder($resultado);
        }

    }

?>
