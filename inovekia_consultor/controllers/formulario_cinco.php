<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/formulario_cinco.php");

    class Formulario_cinco extends Common
    {
        public static $GUARDARFORMULARIOCINCO = array(
                                                    "empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "f5p1a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f5p2a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f5p3a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f5p4a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f5p5a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f5p6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f5p7a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f5p8a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "visita" => array("nulo" => false, "vacio" => true, "tipo" => "string")
                                                    );

        public static $OBTENERFORMULARIOCINCO = array();
        
        public $Formulario_cincoModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_cincoModel = new Formulario_cincoModel();
            $this->Formulario_cincoModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_cincoModel->close();
        } 

        function guardarFormularioCinco(){
            $resultado = $this->Formulario_cincoModel->guardarFormularioCinco($_REQUEST, $this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerFormularioCinco(){
            $resultado = $this->Formulario_cincoModel->obtenerFormularioCinco();
            parent::responder($resultado);
        }

    }

?>
