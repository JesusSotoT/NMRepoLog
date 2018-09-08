<?php
    
    //ini_set('display_errors', 1);
    //Carga la funciones comunes top y footer
    require('common.php');

    //Carga el modelo para este controlador
    require("models/producto.php");

    class Producto extends Common
    {
        public $ProductoModel;

        function __construct()
        {
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->ProductoModel = new ProductoModel();
        }

        function __destruct()
        {
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->ProductoModel->close();
        }

        function listado(){
            echo json_encode($this->ProductoModel->listado());
        }

    }

?>