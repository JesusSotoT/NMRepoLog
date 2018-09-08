<?php

    ini_set('display_errors', 1);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/usuario.php");

    class Usuario extends Commonapi
    {
        public $UsuarioModel;
                            /*array(
                                    "" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                                    );*/

        public static $INICIARSESIONUSUARIO = array(
                                    "identificador" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "usuario" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "contrasena" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                                    );

        public static $TERMINARSESIONUSUARIO = array();

        public static $OBTENERUSUARIO = array(
                                    "nombre" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                                    "id" => array("nulo" => true, "vacio" => false, "tipo" => "entero")
                                    ); 

        public static $AGREGARUSUARIO = array(
                                    "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "apellido_1" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "apellido_2" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "usuario" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "contrasena" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "email" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "sucursal" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "perfil" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                                    );

        public static $EDITARUSUARIO = array(
                                    "id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                                    "nombre" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "apellido_1" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "apellido_2" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "usuario" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "contrasena" => array("nulo" => true, "vacio" => false, "tipo" => "string"),
                                    "email" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "sucursal" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                                    "perfil" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                                    );

        public static $ELIMINARUSUARIO = array(
                                    "id" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                                    );

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
            $resultado = $this->UsuarioModel->iniciarSesionUsuario($identificador, $usuario, $contrasena);
            parent::responder($resultado);
        }

        function terminarSesionUsuario(){
            $resultado = $this->UsuarioModel->terminarSesionUsuario();
            parent::responder($resultado, false);
        }

        function obtenerUsuario(){
            $filtros = (isset($_REQUEST["nombre"])) ? "e.nombre LIKE '%:nombre%' AND " : "";
            $filtros .= (isset($_REQUEST["id"])) ? "e.idempleado = :id" : "1=1";

            $parametros = array(
                            'nombre' => (isset($_REQUEST["nombre"])) ? $_REQUEST["nombre"] : "",
                            'id' => (isset($_REQUEST["id"])) ? $_REQUEST["id"] : ""
                             );
            $resultado = $this->UsuarioModel->obtenerUsuario($filtros, $parametros);
            parent::responder($resultado);
        }

        function agregarUsuario(){
            $nombre = $_REQUEST['nombre'];
            $apellido_1 = $_REQUEST['apellido_1']; 
            $apellido_2 = $_REQUEST['apellido_2'];
            $usuario = $_REQUEST['usuario'];
            $contrasena = $_REQUEST['contrasena']; 
            $email = $_REQUEST['email'];
            $sucursal = $_REQUEST['sucursal'];
            $perfil = $_REQUEST['perfil'];
            $usuario = $this->UsuarioModel->agregarUsuario($nombre, $apellido_1, $apellido_2, $usuario, $contrasena, $perfil, $email, $sucursal);         
            parent::responder($usuario);
        }

        function editarUsuario(){
            $id = $_REQUEST["id"];
            $nombre = $_REQUEST['nombre'];
            $apellido_1 = $_REQUEST['apellido_1']; 
            $apellido_2 = $_REQUEST['apellido_2'];
            $usuario = $_REQUEST['usuario'];
            $contrasena = (isset($_REQUEST['contrasena'])) ? $_REQUEST['contrasena'] : null;  
            $email = $_REQUEST['email'];
            $sucursal = $_REQUEST['sucursal'];
            $perfil = $_REQUEST['perfil'];
            $usuario = $this->UsuarioModel->editarUsuario($id, $nombre, $apellido_1, $apellido_2, $usuario, $contrasena, $perfil, $email, $sucursal);         
            parent::responder($usuario);
        }

        function eliminarUsuario(){
            $id = $_REQUEST['id'];
            $res = $this->UsuarioModel->eliminarUsuario($id);
            parent::responder($res);
        }

    }

?>
