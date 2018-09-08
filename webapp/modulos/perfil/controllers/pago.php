<?php
    
    //ini_set('display_errors', 1); error_reporting(E_ALL);
    //Carga la funciones comunes top y footer
    require('common.php');

    //Carga el modelo para este controlador
    require("models/pago.php");

    class Pago extends Common
    {
        public $PagoModel;

        function __construct()
        {
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->PagoModel = new PagoModel();
        }

        function __destruct()
        {
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->PagoModel->close();
        }

        function grid(){
            echo json_encode($this->PagoModel->grid());
        }

        function productos(){
            echo json_encode($this->PagoModel->productos($_REQUEST["pago"], $_REQUEST["tipo"]));
        }

        function obtenerPagoPendiente(){
            echo json_encode($this->PagoModel->obtenerPagoPendiente());
        }

        function pagar(){
            echo json_encode($this->PagoModel->pagar($_REQUEST["tipo"], @$_REQUEST["crd"]));
        }

    }

?>