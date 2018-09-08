<?php

    /*
        Clase que proporciona los métodos de verificación de seguridad
    */

    //Cargar la clase padre para este controlador
        //ini_set("display_errors", 1); error_reporting(E_ALL);
    require_once('controllers/api/common.php');
    //Cargar los archivos necesarios

    class Seguridad extends Common
    {

        //Definir los filtros sobre los parámetros que ingresen a la petición, en caso de no necesitar parámetros, dejar un array vació
        public static   $LOGIN = array(
                            "usuario" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                            "contrasena" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                            "push" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                        );
        public static   $LOGOUT = array();
        public static   $INICIARSESIONMESERO = array(
                            "empleado" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                            "contrasena" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                        );

        public static   $OBTENERAJUSTES = array();

        function __construct(){
            //Llamar al constructor del padre para verificar datos de seguridad
            parent::__construct();
        }

        function __destruct(){
            //Llamar al destructor del padre para terminar las conexión
            parent::__destruct();
        }

        public function login()
        {
            parent::responder($this->Seguridad->login($_REQUEST["usuario"], $_REQUEST["contrasena"], null, $_REQUEST["dispositivo"], $_REQUEST["push"]));
        }

        public function logout()
        {
            parent::responder(SeguridadModel::logout());
        }

        public function iniciarSesionMesero()
        {
            parent::responder($this->Seguridad->iniciarSesionMesero($_REQUEST["empleado"], $_REQUEST["contrasena"]));
        }

        public function obtenerAjustes()
        {
            parent::responder($this->Seguridad->obtenerAjustes());
        }

    }

?>
