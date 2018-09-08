<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/actualizar.php");

    class Actualizar extends Common
    {
        public $ActualizarModel;

        public static $ACTUALIZAR = array("url" => array("nulo" => false, "vacio" => false, "tipo" => "string"));
        public static $ACTUALIZARPC = array("fecha" => array("nulo" => false, "vacio" => false, "tipo" => "string"));

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ActualizarModel = new ActualizarModel($this->Seguridad);
            $this->ActualizarModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->ActualizarModel->close();
        } 

        function actualizar(){
            $resultado = $this->ActualizarModel->actualizar($_REQUEST["url"]);
            parent::responder($resultado, false);
        }

        function actualizarPc(){
            $resultado = $this->ActualizarModel->actualizarPC($_REQUEST["fecha"]);
            parent::responder($resultado, false);
        }

    }

?>
