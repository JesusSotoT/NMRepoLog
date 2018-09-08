<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    //require("models/connection.php"); // funciones mySQL 
    require_once dirname(__DIR__) .'/models/connection_sqli_manual.php'; // funciones mySQLi 
    //ini_set("display_errors",1); error_reporting(E_ALL);
    include_once '../../libraries/srpago/srpago.php';
    include_once '../../libraries/paypal/paypal.php';

    class PerfilModel extends Connection {

        private $razonSocial;
        private $rfc;
        private $usuario;
        private $email;
        private $contrasena;
        private $id_instancia;
        private $id_cliente;
        public $srpago;
        public $paypal;

        function __construct()
        {
            $this->connect();
            $this->srpago = new PhpSrPago("20.629616", "-103.3451508", "MXN", false);
            $this->paypal = new PhpPayPal();
            $this->usuario = (object) array("id" => null, "nombre" => null, "email" => null, "contrasena" => null, "srpago" => null);
            $this->llenarPerfil();
        }

        private function llenarPerfil()
        {
            //Razon social
            $this->razonSocial = $_SESSION["accelog_nombre_organizacion"];
            //RFC
            $sql = "SELECT RFC AS rfc FROM organizaciones WHERE idorganizacion = ". $_SESSION["accelog_idorganizacion"] .";";
            $sql = $this->queryArray($sql);
            $this->rfc = $sql["rows"][0]["rfc"];
            //Usuario
            $this->usuario->id = $_SESSION["accelog_idempleado"];
            $this->usuario->nombre = $_SESSION["accelog_login"];
            $sql = "SELECT correoelectronico AS email FROM administracion_usuarios WHERE idempleado = ". $this->usuario->id .";";
            $sql = $this->queryArray($sql);
            $this->usuario->email = $sql["rows"][0]["email"];
            $this->usuario->contrasena = substr(base64_encode(openssl_random_pseudo_bytes('30')), 0, 10);
            //SrPago
            $this->connect(true);
            $sql = "SELECT r.id, r.name, r.email, r.srpago_usuario FROM customer AS c INNER JOIN regcustomer AS r ON r.id = c.idclient WHERE c.instancia = '". $_SESSION["accelog_nombre_instancia"] ."';";
            $sql = $this->queryArray($sql);
            $srpago_usuario = $sql["rows"]["0"]["srpago_usuario"];
            if(is_null($srpago_usuario) || $srpago_usuario == ""){
                $srpago_usuario = $this->srpago->createCustomer($sql["rows"]["0"]["name"], $sql["rows"]["0"]["email"]);
                $sql = "UPDATE regcustomer SET srpago_usuario = '". $srpago_usuario ."' WHERE id = ". $sql["rows"]["0"]["id"] .";";
                $sql = $this->queryArray($sql);
            }
            $this->usuario->srpago = $srpago_usuario;
            //Instancia y cliente
            $sql = "SELECT id AS instancia, idclient AS cliente FROM customer WHERE instancia = '". $_SESSION["accelog_nombre_instancia"] ."';";
            $sql = $this->queryArray($sql);
            $this->id_instancia = $sql["rows"][0]["instancia"];
            $this->id_cliente = $sql["rows"][0]["cliente"];
            $this->connect();
        }

        public function obtenerRazonSocial()
        {
            return $this->razonSocial;
        }

        public function obtenerRFC()
        {
            return $this->rfc;
        }

        public function obtenerNombreUsuario()
        {
            return $this->usuario->nombre;
        }

        public function obtenerEmailUsuario()
        {
            return $this->usuario->email;
        }

        public function obtenerContrasenaUsuario()
        {
            return $this->usuario->contrasena;
        }

        public function obtenerUsuarioSrPago()
        {
            return $this->usuario->srpago;
        }

        public function cambiarContrasenaUsuario($datos)
        {
            if($datos["contrasena_nueva"] == $datos["contrasena_repetir"]){
                require_once '../../netwarelog/webconfig.php';
                global $accelog_salt_perfil;
                $contrasena_actual = crypt($datos["contrasena_actual"], $accelog_salt_perfil);
                $contrasena_nueva = crypt($datos["contrasena_nueva"], $accelog_salt_perfil);
                $sql = "SELECT clave FROM accelog_usuarios WHERE idempleado = ". $this->usuario->id .";";
                $sql = $this->queryArray($sql);
                if($sql["status"] && $sql["rows"] > 0 && $sql["rows"][0]["clave"] == $contrasena_actual){
                    $sql = "UPDATE accelog_usuarios SET clave = '". $contrasena_nueva ."' WHERE idempleado = ". $this->usuario->id .";";
                    $sql = $this->queryArray($sql);
                    if($sql["status"]){
                        $respuesta = array("status" => true);
                    } else {
                        $respuesta = array("status" => false, "mensaje" => "No fue posible cambiar la contraseña, intentalo nuevamente");
                    }
                } else {
                    $respuesta = array("status" => false, "mensaje" => "Usuario o contraseña incorrecto");
                }
            } else {
                $respuesta = array("status" => false, "mensaje" => "Las contraseñas deben de coincidir");
            }
            return $respuesta;
        }

        public function informacion()
        {
            return array("status" => true, "informacion" => array("razon" => $this->razonSocial, "rfc" => $this->rfc, "usuario" => $this->usuario->nombre, "contrasena" => $this->usuario->contrasena, "correo" => $this->usuario->email));
        }

    }

?>