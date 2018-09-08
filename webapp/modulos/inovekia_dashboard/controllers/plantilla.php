<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/plantilla.php");

class Plantilla extends Common
{
    public $PlantillaModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->PlantillaModel = new PlantillaModel();
        $this->PlantillaModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->PlantillaModel->close();
    } 

    function index(){
        require('views/plantilla/index.php');
    }

    function procesar(){
        echo json_encode($this->PlantillaModel->procesar());
    }

    function agregarUsuario(){
        echo json_encode($this->PlantillaModel->agregarUsuario());
    }

}

?>