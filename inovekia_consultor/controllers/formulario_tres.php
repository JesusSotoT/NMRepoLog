<?php

 	ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/formulario_tres.php");

    /**
    * 
    */
    class Formulario_tres extends Common
    {
    	public static $GUARDARFORMULARIOTRES = array(
                                                    "empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "f3p1a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f3p2a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f3p2b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f3p3a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f3p4a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f3p4b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f3p5a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f3p5b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f3p6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f3p6b" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f3p6c" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f3p6d" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f3p7a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f3p8a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f3p9a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f3p10a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f3p11a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen")
                                                    );
    	public static $OBTENERFORMULARIOTRES = array();

    	public $Formulario_tresModel;

    	function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_tresModel = new Formulario_tresModel();
            $this->Formulario_tresModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_tresModel->close();
        } 

        function guardarFormularioTres(){
            $resultado = $this->Formulario_tresModel->guardarFormularioTres($_REQUEST, $this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerFormularioTres(){
            $resultado = $this->Formulario_tresModel->obtenerFormularioTres();
            parent::responder($resultado);
        }
    }

    

?>