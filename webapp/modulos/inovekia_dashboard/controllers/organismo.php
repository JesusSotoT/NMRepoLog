<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/organismo.php");

class Organismo extends Common
{
    public $OrganismoModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->OrganismoModel = new OrganismoModel();
        $this->OrganismoModel->connect(true);
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->OrganismoModel->close();
    } 

    function index(){
        require('views/organismo/index.php');
    }

    function grid(){
        echo json_encode($this->OrganismoModel->grid());
    }

}

?>