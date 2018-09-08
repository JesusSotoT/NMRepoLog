<?php
    
    //ini_set('display_errors', 1);
    //Carga la funciones comunes top y footer
    require('common.php');

    //Carga el modelo para este controlador
    require("models/perfil.php");

    class Perfil extends Common
    {
        public $PerfilModel;

        function __construct()
        {
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->PerfilModel = new PerfilModel();
        }

        function __destruct()
        {
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->PerfilModel->close();
        }

        function informacion(){
            echo json_encode($this->PerfilModel->informacion());
        }

        function cambiarContrasena(){
            echo json_encode($this->PerfilModel->cambiarContrasenaUsuario($_REQUEST));
        }

    }

?>