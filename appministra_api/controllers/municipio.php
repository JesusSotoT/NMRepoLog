<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/municipio.php");

    class Municipio extends Commonapi
    {
        public static $OBTENERMUNICIPIO = array(
                "estado" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "id" => array("nulo" => true, "vacio" => false, "tipo" => "entero")
                );
        public $MunicipioModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->MunicipioModel = new MunicipioModel();
            $this->MunicipioModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->MunicipioModel->close();
        } 

        function obtenerMunicipio(){
            $filtros = (isset($_REQUEST["estado"])) ? "idestado = ". $_REQUEST["estado"] ." AND " : "";
            $filtros .= (isset($_REQUEST["id"])) ? "idmunicipio = ". $_REQUEST["id"] : "1=1";

            $parametros = array(
                'estado' => (isset($_REQUEST["estado"])) ? $_REQUEST["estado"] : null,                    
                'id' => (isset($_REQUEST["id"])) ? $_REQUEST["id"] : null
                );
                
            $resultado = $this->MunicipioModel->obtenerMunicipio($filtros, $parametros);
            parent::responder($resultado);
        }

    }

?>
