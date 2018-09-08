<?php
session_start();
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/lms.php");

class Lms extends Common
{
    public $LmsModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->LmsModel = new LmsModel();
        if($_SESSION['accelog_nombre_instancia'] != "inovekia"){
            $this->LmsModel->connect();
        } else {
            $this->LmsModel->connect(true);
        }
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->LmsModel->close();
    } 

    function index(){
        require('views/lms/index.php');
    }

    function obtenerSeguimiento(){
        echo json_encode($this->LmsModel->obtenerSeguimiento($_SESSION['accelog_idempleado'], $_REQUEST['empresario'], $_REQUEST['curso']));
    }

    function guardarSeguimiento(){
        echo json_encode($this->LmsModel->guardarSeguimiento($_SESSION['accelog_idempleado'], $_REQUEST));
    }

}

?>