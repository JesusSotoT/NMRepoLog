<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/formulario_siete.php");

    class Formulario_siete extends Common
    {
        //public static $GUARDARFORMULARIOSIETE = array();
        public static $GUARDARFORMULARIOSIETE = array(
                                                    "empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "f7p1a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f7p2a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f7p3a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f7p4a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f7p5a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f7p6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f7p7a" => array("nulo" => false, "vacio" => true, "tipo" => "string")
                                                    );

        public static $OBTENERFORMULARIOSIETE = array();

        
        public $Formulario_sieteModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_sieteModel = new Formulario_sieteModel();
            $this->Formulario_sieteModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_sieteModel->close();
        } 

        function guardarFormularioSiete(){
            $resultado = $this->Formulario_sieteModel->guardarFormularioSiete($_REQUEST, $this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerFormularioSiete(){
            $resultado = $this->Formulario_sieteModel->obtenerFormularioSiete();
            parent::responder($resultado);
        }

    }

?>
