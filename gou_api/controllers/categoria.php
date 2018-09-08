<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/categoria.php");

    class Categoria extends Common
    {
        public $CategoriaModel;
        public static $LISTADO = array();

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->CategoriaModel = new CategoriaModel($this->Seguridad);
            $this->CategoriaModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->CategoriaModel->close();
        } 

        function listado(){
            $resultado = $this->CategoriaModel->listado();
            parent::responder($resultado);
        }

    }

?>
