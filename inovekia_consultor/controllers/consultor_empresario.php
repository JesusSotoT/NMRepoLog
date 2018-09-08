<?php

	ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/consultor_empresario.php");

    /**
    * 
    */
    class Consultor_Empresario extends Common
    {
    	public static $GUARDARCONSULTOREMPRESARIO = array(
                                                    "id_empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "id_consultor" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "activo" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "creado" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                    "modificado" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                                                );

    	public static $OBTENEREMPRESARIO = array();
        public static $OBTENEREMPRESARIO2 = array();
        public static $REASIGNAREMPRESARIO = array("id_empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));

        
        public $ConsultorEmpresarioModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ConsultorEmpresarioModel = new ConsultorEmpresarioModel();
            $this->ConsultorEmpresarioModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->ConsultorEmpresarioModel->close();
        } 

        function obtenerEmpresario(){
            $resultado = $this->ConsultorEmpresarioModel->obtenerEmpresario($this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function obtenerEmpresario2(){
            $resultado = $this->ConsultorEmpresarioModel->obtenerEmpresario2($this->Seguridad->Usuario);
            parent::responder($resultado);
        }

        function guardarConsultorEmpresario(){
            $resultado = $this->ConsultorEmpresarioModel->guardarConsultorEmpresario($_REQUEST);
            parent::responder($resultado);
        }

        function reasignarEmpresario(){
            $resultado = $this->ConsultorEmpresarioModel->reasignarEmpresario($this->Seguridad->Usuario, $_REQUEST["id_empresario"]);
            parent::responder($resultado);
        }
    }

?>