<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/actualizar.php");

class Actualizar extends Common
{
    public $ActualizarModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ActualizarModel = new ActualizarModel();
        $this->ActualizarModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ActualizarModel->close();
    } 

    function index(){
        require('views/actualizar/index.php');
    }

    function grid(){
        echo json_encode($this->ActualizarModel->grid());
    }

    function actualizar(){
        echo json_encode($this->ActualizarModel->actualizar($_REQUEST));
    }

}

?>