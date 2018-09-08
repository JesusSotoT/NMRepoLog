<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/formulario_nueve.php");

    class Formulario_nueve extends Common
    {
        //public static $GUARDARFORMULARIONUEVE = array();
        public static $GUARDARFORMULARIONUEVE = array(
                                                    "empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "f9p1a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f9p2a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f9p3a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f9p4a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f9p5a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f9p6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f9p7a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f9p8a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f9p9a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f9p10a" => array("nulo" => false, "vacio" => true, "tipo" => "string")
                                                    );

        public static $OBTENERFORMULARIONUEVE = array();

        
        public $Formulario_nueveModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_nueveModel = new Formulario_nueveModel();
            $this->Formulario_nueveModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_nueveModel->close();
        } 

        function guardarFormularioNueve(){
            $resultado = $this->Formulario_nueveModel->guardarFormularioNueve($_REQUEST, $this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerFormularioNueve(){
            $resultado = $this->Formulario_nueveModel->obtenerFormularioNueve();
            parent::responder($resultado);
        }

    }

?>
