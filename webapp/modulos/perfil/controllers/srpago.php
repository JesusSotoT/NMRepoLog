<?php
    
    //ini_set('display_errors', 1); error_reporting(E_ALL);
    //Carga la funciones comunes top y footer
    require_once 'common.php';

    //Carga el modelo para este controlador
    require_once 'models/srpago.php';

    class SrPago extends Common
    {
        public $SrPagoModel;

        function __construct()
        {
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->SrPagoModel = new SrPagoModel();
        }

        function __destruct()
        {
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->SrPagoModel->close();
        }

        function agregarTarjeta()
        {
            echo json_encode($this->SrPagoModel->agregarTarjeta($_REQUEST));
        }

        function eliminarTarjeta()
        {
            echo json_encode($this->SrPagoModel->eliminarTarjeta($_REQUEST["crd"]));
        }

        function obtenerTarjetas()
        {
            echo json_encode($this->SrPagoModel->obtenerTarjetas());
        }

        function defaultTarjeta()
        {
            echo json_encode($this->SrPagoModel->defaultTarjeta($_REQUEST["crd"]));
        }

    }

?>