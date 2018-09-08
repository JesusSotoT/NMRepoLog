<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/formulario_uno.php");

    class Formulario_uno extends Common
    {
        public static $GUARDARFORMULARIOUNO = array(
                                                    "empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "f1p1a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p2a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p3a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p4a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p5a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p5b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p5c" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p7a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p8a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p9a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p10a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p11a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p12a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p13a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p14a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p15a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p16a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p17a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p18a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p19a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p20a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p21a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p22a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p23a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p24a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p24b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p25a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p26a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p26b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p27a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p28a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p28b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p29a" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p29b" => array("nulo" => false, "vacio" => true, "tipo" => "imagen"),
                                                    "f1p30a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p30b" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p30c" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p30d" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p31a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p32a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p33a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f1p34a" => array("nulo" => false, "vacio" => true, "tipo" => "string")
                                                    );

        public static $OBTENERFORMULARIOUNO = array();

        
        public $Formulario_unoModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_unoModel = new Formulario_unoModel();
            $this->Formulario_unoModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_unoModel->close();
        } 

        function guardarFormularioUno(){
            $resultado = $this->Formulario_unoModel->guardarFormularioUno($_REQUEST, $this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerFormularioUno(){
            $resultado = $this->Formulario_unoModel->obtenerFormularioUno();
            parent::responder($resultado);
        }

    }

?>
