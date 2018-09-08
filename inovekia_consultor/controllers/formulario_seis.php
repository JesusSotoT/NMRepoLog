<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/formulario_seis.php");

    class Formulario_seis extends Common
    {
        //public static $GUARDARFORMULARIOSEIS = array();
        public static $GUARDARFORMULARIOSEIS = array(
                                                    "empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "f6p1a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p2a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p3a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p4a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p5a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p7a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p8a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p9a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p10a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p11a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p12a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p13a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p14a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p15a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p16a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p17a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p18a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p19a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p20a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p21a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p22a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p23a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p24a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f6p25a" => array("nulo" => false, "vacio" => true, "tipo" => "string")
                                                    );

        public static $OBTENERFORMULARIOSEIS = array();

        
        public $Formulario_seisModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_seisModel = new Formulario_seisModel();
            $this->Formulario_seisModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_seisModel->close();
        } 

        function guardarFormularioSeis(){
            $resultado = $this->Formulario_seisModel->guardarFormularioSeis($_REQUEST, $this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerFormularioSeis(){
            $resultado = $this->Formulario_seisModel->obtenerFormularioSeis();
            parent::responder($resultado);
        }

    }

?>
