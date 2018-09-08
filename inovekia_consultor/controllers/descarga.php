<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/descarga.php");

    class Descarga extends Common
    {
        public static $OBTENERCURSO = array();
        
        public $DescargaModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->DescargaModel = new DescargaModel();
            $this->DescargaModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->DescargaModel->close();
        } 

        function obtenerCurso(){
            $resultado = $this->DescargaModel->obtenerCurso();
            parent::responder($resultado);
        }

    }

?>
