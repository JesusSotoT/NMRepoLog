<?php

    ini_set('display_errors', 1);
    //error_reporting(E_ALL);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/anticipo.php");

    class Anticipo extends Common
    {
        public $AnticipoModel;
        public static $LISTADO = array();

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->AnticipoModel = new AnticipoModel($this->Seguridad);
            $this->AnticipoModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->AnticipoModel->close();
        } 

        function listado(){
            $id =  $this->AnticipoModel->Seguridad->Usuario; //$_REQUEST["idUsuario"];
            $resultado = $this->AnticipoModel->listado($id);
            parent::responder($resultado);
        }

    }

?>
