<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/lms.php");

    class Lms extends Common
    {
        public static $GUARDARSEGUIMIENTO = array(
                                                    "seguimiento" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                    "latitud" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                                                    "longitud" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                                                    "id_curso" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                                                );

        public static $OBTENERSEGUIMIENTO = array("id_curso" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));
        
        public $LmsModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->LmsModel = new LmsModel();
            $this->LmsModel->connect();
        }

        function __destruct(){
            parent::__destruct();
        } 

        function obtenerSeguimiento(){
            $resultado = $this->LmsModel->obtenerSeguimiento($this->Seguridad->Usuario, $_REQUEST['id_curso']);
            parent::responder($resultado);
        }

        function guardarSeguimiento(){
            $resultado = $this->LmsModel->guardarSeguimiento($this->Seguridad->Usuario, $_REQUEST);
            parent::responder($resultado);
        }

    }

?>
