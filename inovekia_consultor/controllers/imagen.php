<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/imagen.php");

    class Imagen extends Common
    {
        public static $OBTENER = array(
                "archivo" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                );
        
        public $ImagenModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ImagenModel = new ImagenModel();
        }

        function __destruct(){
            parent::__destruct();
        } 

        function obtener(){
            $this->ImagenModel->obtener($_REQUEST['archivo']);
        }

    }

?>
