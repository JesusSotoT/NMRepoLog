<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class UsuarioModel extends Conexion
    {
        private $Seguridad;

        function __construct($seguridad)
        {
            $this->Seguridad = $seguridad;
        }

        public function object_to_array($data) {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = $this->object_to_array($value);
                }
                return $result;
            }
            return $data;
        }

        public function iniciarSesionUsuario($dispositivo, $usuario, $contrasena){
            $servidor = explode("/", $_SERVER['REQUEST_URI']);
            $cliente = $servidor[array_search('clientes', $servidor) + 1];
            
            //$login1 = $this->Seguridad->validaUsuarioAuth($cliente, $usuario, $contrasena);
            $login = $this->Seguridad->validaUsuario($usuario, $contrasena);
            if($login["status"]){
                $token = $this->Seguridad->generaToken($dispositivo, $login["rows"][0]["idempleado"]);
                unset($login["rows"][0]["idempleado"]);
                unset($login["fields"]);
                $login["rows"][0]["token"] = $token;
                $login["token"] = $token;
            }
            return $login;
        }

        public function terminarSesionUsuario(){
            $login = $this->Seguridad->terminaUsuario();
            return $login;
        }

    }
?>
