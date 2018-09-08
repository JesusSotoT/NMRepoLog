<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/tipo_pago.php");

    class TipoPago extends Commonapi
    {
        public $TipoPagoModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->TipoPagoModel = new TipoPagoModel();
            $this->TipoPagoModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->TipoPagoModel->close();
        } 

        function obtenerTipoPago(){
            $filtros = (isset($_REQUEST["id"])) ? "idFormapago = ". $_REQUEST["id"] : "1=1";
            $resultado = $this->TipoPagoModel->obtenerTipoPago($filtros);
            parent::responder($resultado);
        }

    }

?>
