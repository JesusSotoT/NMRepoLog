<?php

	ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/numero_visita.php");

    /**
    * 
    */
    class Numero_Visita extends Common
    {
    	public static $GUARDARVISITA = array(
												"id_consultor" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "id_empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "visita" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "activo" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                "creado" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                                "modificado" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                                            );
    	public static $OBTENERVISITA = array("id_empresario" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));

    	public $NumeroVisitaModel;

    	function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->NumeroVisitaModel = new NumeroVisitaModel();
            $this->NumeroVisitaModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->NumeroVisitaModel->close();
        } 

        function obtenerVisita(){
            $resultado = $this->NumeroVisitaModel->obtenerVisita($this->Seguridad->Usuario, $_REQUEST['id_empresario']);
            parent::responder($resultado);
        }

        function guardarVisita(){
            $resultado = $this->NumeroVisitaModel->guardarVisita($_REQUEST);
            parent::responder($resultado);
        }
    	
    }

?>