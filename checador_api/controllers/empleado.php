<?php

    //ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/empleado.php");

    class Empleado extends Common
    {
        public $EmpleadoModel;

        public static $LISTA = array();
        public static $IDENTIFICARHUELLA = array();
        public static $GUARDARHUELLA = array("idEmpleado" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                             "datosHuella" => array("nulo" => false, "vacio" => false, "tipo" => "zip"),
                                             "dato" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));
        public static $CHECARHUELLA = array("idEmpleado" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                            "idnomp" => array("nulo" => false, "vacio" => false, "tipo" => "string"));
       // public static $ACTUALIZARPC = array("fecha" => array("nulo" => false, "vacio" => false, "tipo" => "string"));

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->EmpleadoModel = new EmpleadoModel($this->Seguridad);
            $this->EmpleadoModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->EmpleadoModel->close();
        } 

        function lista(){
            $resultado = $this->EmpleadoModel->lista();
            parent::responder($resultado, false);
        }

        function checarHuella(){
            $resultado = $this->EmpleadoModel->checarHuella($_REQUEST);
            parent::responder($resultado, false);
        }

        function identificarHuella(){
            $resultado = $this->EmpleadoModel->identificarHuella();
            parent::responder($resultado, false);
        }

        function guardarHuella(){          
            $resultado = $this->EmpleadoModel->guardarHuella($_REQUEST);
            parent::responder($resultado, false);
        }

    }

?>
