<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/formulario_dos.php");

    class Formulario_dos extends Common
    {
        public static $GUARDARFORMULARIODOS = array(
                                                    "empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "f2p1a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f2p2a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f2p2b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f2p3a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f2p4a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f2p4b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f2p5a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f2p5b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f2p6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f2p6b" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f2p6c" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f2p6d" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f2p7a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f2p8a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f2p9a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f2p10a" => array("nulo" => false, "vacio" => true, "tipo" => "string")
                                                    );

        public static $OBTENERFORMULARIODOS = array();
        
        public $Formulario_dosModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_dosModel = new Formulario_dosModel();
            $this->Formulario_dosModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_dosModel->close();
        } 

        function guardarFormularioDos(){
            $resultado = $this->Formulario_dosModel->guardarFormularioDos($_REQUEST, $this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerFormularioDos(){
            $resultado = $this->Formulario_dosModel->obtenerFormularioDos();
            parent::responder($resultado);
        }

    }

?>
