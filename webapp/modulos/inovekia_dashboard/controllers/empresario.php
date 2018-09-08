<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/empresario.php");

class Empresario extends Common
{
    public $EmpresarioModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->EmpresarioModel = new EmpresarioModel();
        $this->EmpresarioModel->connect(true);
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->EmpresarioModel->close();
    } 

    function index(){
        require('views/empresario/index.php');
    }

    function grid(){
        echo json_encode($this->EmpresarioModel->grid((array_key_exists("consultor", $_REQUEST)) ? $_REQUEST['consultor'] : 0));
    }

    function empresario(){
        echo json_encode($this->EmpresarioModel->empresario($_REQUEST['consultor'], $_REQUEST['cliente']));
    }

    function seleccionar(){
        echo json_encode($this->EmpresarioModel->seleccionar($_REQUEST['id_consultor'], $_REQUEST['id_empresario']));
    }

    function eliminar(){
        echo json_encode($this->EmpresarioModel->eliminar($_REQUEST['id_consultor'], $_REQUEST['id_empresario']));
    }

    function visita(){
        echo json_encode($this->EmpresarioModel->visita($_REQUEST));
    }

    function seguimiento(){
        echo json_encode($this->EmpresarioModel->seguimiento($_REQUEST['consultor'], $_REQUEST['empresario']));
    }

    function mostrarFolio(){
        echo json_encode($this->EmpresarioModel->mostrarFolio($_REQUEST['id']));
    }

    function folio(){
        echo json_encode($this->EmpresarioModel->folio($_REQUEST['id'], $_REQUEST['folio']));
    }

}

?>