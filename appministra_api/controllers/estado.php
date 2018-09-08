<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/estado.php");

    class Estado extends Commonapi
    {
        public static $OBTENERESTADO = array("id" => array("nulo" => true, "vacio" => false, "tipo" => "entero"));
        public $EstadoModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->EstadoModel = new EstadoModel();
            $this->EstadoModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->EstadoModel->close();
        } 

        function obtenerEstado(){
            $filtros = (isset($_REQUEST["id"])) ? "idestado = ". $_REQUEST["id"] : "1=1";
            $parametros = array('id' => (isset($_REQUEST["id"])) ? $_REQUEST["id"] : null);            
            $resultado = $this->EstadoModel->obtenerEstado($filtros, $parametros);
        
            parent::responder($resultado);
        }

    }

?>
