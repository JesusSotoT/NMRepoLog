<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/supervisor.php");

class Supervisor extends Common
{
    public $SupervisorModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->SupervisorModel = new SupervisorModel();
        $this->SupervisorModel->connect(true);
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->SupervisorModel->close();
    } 

    function index(){
        require('views/supervisor/index.php');
    }

    function grid(){
        echo json_encode($this->SupervisorModel->grid($_REQUEST['organismo']));
    }

    function seleccionar(){
        echo json_encode($this->SupervisorModel->seleccionar($_REQUEST['id_organismo'], $_REQUEST['id_supervisor']));
    }

    function eliminar(){
        echo json_encode($this->SupervisorModel->eliminar($_REQUEST['id_organismo'], $_REQUEST['id_supervisor']));
    }

}

?>