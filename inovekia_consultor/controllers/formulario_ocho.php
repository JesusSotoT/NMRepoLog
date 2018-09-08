<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/formulario_ocho.php");

    class Formulario_ocho extends Common
    {
        //public static $GUARDARFORMULARIOOCHO = array();
        public static $GUARDARFORMULARIOOCHO = array(
                                                    "empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "f8p1a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f8p2a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f8p3a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f8p4a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f8p5a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f8p6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f8p7a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f8p8a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f8p9a" => array("nulo" => false, "vacio" => true, "tipo" => "string")
                                                    );

        public static $OBTENERFORMULARIOOCHO = array();

        
        public $Formulario_ochoModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_ochoModel = new Formulario_ochoModel();
            $this->Formulario_ochoModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_ochoModel->close();
        } 

        function guardarFormularioOcho(){
            $resultado = $this->Formulario_ochoModel->guardarFormularioOcho($_REQUEST, $this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerFormularioOcho(){
            $resultado = $this->Formulario_ochoModel->obtenerFormularioOcho();
            parent::responder($resultado);
        }

    }

?>
