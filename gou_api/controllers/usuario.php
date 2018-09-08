<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/usuario.php");

    class Usuario extends Common
    {
        public $UsuarioModel;

        public static $INICIARSESIONUSUARIO = array(
                                    "identificador" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "usuario" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "contrasena" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "push" => array("nulo" => true, "vacio" => false, "tipo" => "string")
                                    );

        public static $TERMINARSESIONUSUARIO = array();

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->UsuarioModel = new UsuarioModel($this->Seguridad);
            $this->UsuarioModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->UsuarioModel->close();
        } 

        function iniciarSesionUsuario(){
            $identificador = $_REQUEST["identificador"];
            $usuario = $_REQUEST["usuario"];
            $contrasena = $_REQUEST["contrasena"];
            $push = $_REQUEST["push"];
            $resultado = $this->UsuarioModel->iniciarSesionUsuario($identificador, $usuario, $contrasena, $push);
            parent::responder($resultado);
        }

        function terminarSesionUsuario(){
            $resultado = $this->UsuarioModel->terminarSesionUsuario();
            parent::responder($resultado, false);
        }

    }

?>
