<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/departamento.php");

    class Departamento extends Commonapi
    {
        public static $OBTENERDEPARTAMENTO = array("nombre" => array("nulo" => true, "vacio" => false, "tipo" => "string"));
        public static $AGREGARDEPARTAMENTO = array("nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"));
        public static $EDITARDEPARTAMENTO = array(
                                                    "id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                                    "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                                                );
        public static $ELIMINARDEPARTAMENTO = array("id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));
        
        public $DepartamentoModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->DepartamentoModel = new DepartamentoModel();
            $this->DepartamentoModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->DepartamentoModel->close();
        } 

        function obtenerDepartamento(){
            $filtros = (isset($_REQUEST["nombre"])) ? "nombre LIKE CONCAT('%', :nombre, '%')" : "1=1";
            $parametros = array('nombre' => (isset($_REQUEST["nombre"])) ? $_REQUEST["nombre"] : null);
            $resultado = $this->DepartamentoModel->obtenerDepartamento($filtros, $parametros);
           parent::responder($resultado);
        }

        function agregarDepartamento(){
            $nombre = $_REQUEST["nombre"];
            $resultado = $this->DepartamentoModel->agregarDepartamento($nombre);
           
            parent::responder($resultado);
        }

        function editarDepartamento(){
            $id = $_REQUEST["id"];
            $nombre = $_REQUEST["nombre"];
            $resultado = $this->DepartamentoModel->editarDepartamento($id, $nombre);
        
            parent::responder($resultado);
        }

        function eliminarDepartamento(){
          $id = $_REQUEST["id"];
          $resultado = $this->DepartamentoModel->eliminarDepartamento($id);
           
          parent::responder($resultado);
        }
    }

?>
