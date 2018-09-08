<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/trimestral.php");

class Trimestral extends Common
{
    public $TrimestralModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->TrimestralModel = new TrimestralModel();
        $this->TrimestralModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->TrimestralModel->close();
    } 

    function index(){
        $folios = $this->TrimestralModel->obtenerFolios();
        require('views/trimestral/index.php');
    }

    function obtenerConsultorPorFolio(){
        echo json_encode($this->TrimestralModel->obtenerConsultorPorFolio($_REQUEST["folio"]));
    }

    function reporteTrimestral(){
        json_encode($this->TrimestralModel->reporteTrimestral($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function reporteMicroMercado(){
        json_encode($this->TrimestralModel->reporteMicroMercado($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function analisisMicroMercado(){
        json_encode($this->TrimestralModel->analisisMicroMercado($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function fotosMicroEmpresario(){
        json_encode($this->TrimestralModel->fotosMicroEmpresario($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function analisisFinanciero(){
        json_encode($this->TrimestralModel->analisisFinanciero($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }
    
    function planAccion(){
        json_encode($this->TrimestralModel->planAccion($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function ife(){
        json_encode($this->TrimestralModel->ife($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function autoEmpleo(){
        json_encode($this->TrimestralModel->autoEmpleo($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function reciboLuz(){
        json_encode($this->TrimestralModel->reciboLuz($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function listaRayaHombre(){
        json_encode($this->TrimestralModel->listaRayaHombre($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function ifeEmpleadoHombre(){
        json_encode($this->TrimestralModel->ifeEmpleadoHombre($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function listaRayaMujer(){
        json_encode($this->TrimestralModel->listaRayaMujer($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function ifeEmpleadoMujer(){
        json_encode($this->TrimestralModel->ifeEmpleadoMujer($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }

    function rfcActualizada(){
        json_encode($this->TrimestralModel->rfcActualizada($_REQUEST["folio"], $_REQUEST["fecha_inicial"], $_REQUEST["fecha_final"], $_REQUEST["consultor"]));
    }
}

?>