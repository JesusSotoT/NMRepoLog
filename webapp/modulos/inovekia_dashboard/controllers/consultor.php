<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/consultor.php");

class Consultor extends Common
{
    public $ConsultorModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->ConsultorModel = new ConsultorModel();
        $this->ConsultorModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->ConsultorModel->close();
    } 

    function index(){
        require('views/consultor/index.php');
    }

    function grid(){
        echo json_encode($this->ConsultorModel->grid(((isset($_REQUEST["organismo"])) ? $_REQUEST["organismo"] : 0)));
    }

    function seleccionar(){
        echo json_encode($this->ConsultorModel->seleccionar($_REQUEST['id_organismo'], $_REQUEST['id_consultor']));
    }

    function eliminar(){
        echo json_encode($this->ConsultorModel->eliminar($_REQUEST['id_organismo'], $_REQUEST['id_consultor']));
    }

    function inadem(){
        echo json_encode($this->ConsultorModel->inadem($_REQUEST['id_consultor'], $_REQUEST['email']));
    }

}

?>