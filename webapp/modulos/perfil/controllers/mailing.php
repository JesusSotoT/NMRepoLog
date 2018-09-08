<?php
    
    //ini_set('display_errors', 1); error_reporting(E_ALL);
    //Carga la funciones comunes top y footer
    require('common.php');

    //Carga el modelo para este controlador
    require("models/mailing.php");

    class Mailing extends Common
    {
        public $MailingModel;

        function __construct()
        {
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->MailingModel = new MailingModel();
        }

        function __destruct()
        {
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->MailingModel->close();
        }

        function guardar(){
            echo json_encode($this->MailingModel->guardar($_REQUEST));
        }

        function obtener(){
            echo json_encode($this->MailingModel->obtener());
        }

    }

?>