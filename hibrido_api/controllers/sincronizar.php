<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('common.php');
    //Carga el modelo para este controlador
    require_once("models/sincronizar.php");

    class Sincronizar extends Common
    {
        public $SincronizarModel;

        public static $CAJA = array("sql" => array("nulo" => false, "vacio" => false, "tipo" => "string"));
        public static $FOODWARE = array("sql" => array("nulo" => false, "vacio" => false, "tipo" => "string"));

        function __construct()
        {
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->SincronizarModel = new SincronizarModel($this->Seguridad);
            $this->SincronizarModel->connect();
        }

        function __destruct()
        {
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->SincronizarModel->close();
        } 

        function caja(){
            $resultado = $this->SincronizarModel->caja(base64_decode($_REQUEST["sql"]));
            parent::responder($resultado, false);
        }

        function foodware(){
            $resultado = $this->SincronizarModel->foodware(base64_decode($_REQUEST["sql"]));
            parent::responder($resultado, false);
        }

    }

?>
