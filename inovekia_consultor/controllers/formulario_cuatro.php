<?php

 	ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/formulario_cuatro.php");

    /**
    * 
    */
    class Formulario_cuatro extends Common
    {
    	public static $GUARDARFORMULARIOCUATRO = array(
                                                    "empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "f4p1a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p2a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p3a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p4a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p5a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p7a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p8a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p9a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p10a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p11a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p12a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p13a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p14a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p15a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p16a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p17a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p18a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p19a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p20a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p21a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p22a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p23a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p24a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p24r1a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p24r2a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p24r3a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p24r4a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p24r5a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p24r6a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p24r7a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p24r8a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p25a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p25b" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p26a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p27a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p28a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p29a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p30a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p31a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p32a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p33a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p34r1a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p34r1b" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p34r1c" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p34r1d" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p34r1e" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p34r1f" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p34r1g" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p34r1h" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p35a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p36a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p37a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p38a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p39a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p40a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p41a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p42a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p43a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p44a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p45a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p46a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p47a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p48a" => array("nulo" => false, "vacio" => true, "tipo" => "string"), 
                                                    "f4p49a" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                                                    "f4p50a" => array("nulo" => false, "vacio" => true, "tipo" => "string")     
                                                    );
    	public static $OBTENERFORMULARIOCUATRO = array();

    	public $Formulario_cuatroModel;

    	function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_cuatroModel = new Formulario_cuatroModel();
            $this->Formulario_cuatroModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->Formulario_cuatroModel->close();
        } 

        function guardarFormularioCuatro(){
            
            $resultado = $this->Formulario_cuatroModel->guardarFormularioCuatro($_REQUEST, $this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerFormularioCuatro(){
            $resultado = $this->Formulario_cuatroModel->obtenerFormularioCuatro();
            parent::responder($resultado);
        }
    }

    

?>