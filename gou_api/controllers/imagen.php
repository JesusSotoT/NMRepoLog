<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/imagen.php");

    class Imagen extends Common
    {
        public $ImagenModel;

        public static $OBTENERIMAGENES = array();
        public static $OBTENERIMAGENORGANIZACION = array();

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ImagenModel = new ImagenModel($this->Seguridad);
            $this->ImagenModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->ImagenModel->close();
        } 

        function obtenerImagenes(){
            $resultado = $this->ImagenModel->obtenerImagenes();
            parent::responder($resultado, false);
        }

        function obtenerImagenOrganizacion(){
            $resultado = $this->ImagenModel->obtenerImagenOrganizacion();
            parent::responder($resultado, false);
        }

        function obtener(){
            $resultado = $this->ImagenModel->obtenerImagenes();
            foreach ($resultado['rows'] as $key => $value) {
                $this->ImagenModel->obtener($value['image_url']);
                # code...
            }
            parent::responder($resultado, false);
        }

    }

?>
