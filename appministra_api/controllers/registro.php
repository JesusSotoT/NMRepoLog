<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/registro.php");

    class Registro extends Commonapi
    {
        public $RegistroModel;
        public static $AGREGARREGISTRO = array(
                                  "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                  "email" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                  "telefono" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                                  );

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->RegistroModel = new RegistroModel();
            $this->RegistroModel->connect(true);
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->RegistroModel->close();
        } 

        function agregarRegistro(){
            $nombre = $_REQUEST['nombre'];
            $email = $_REQUEST['email']; 
            $telefono = $_REQUEST['telefono'];
            $registro = $this->RegistroModel->agregarRegistro($nombre, $email, $telefono);         
            parent::responder($registro);
        }

    }

?>
