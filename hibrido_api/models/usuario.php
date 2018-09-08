<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class UsuarioModel extends Connection
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

        public function iniciarSesionUsuario($dispositivo, $usuario, $contrasena, $push){
            $servidor = explode("/", $_SERVER['REQUEST_URI']);
            $cliente = $servidor[array_search('clientes', $servidor) + 1];
            
            $login1 = $this->Seguridad->validaUsuarioAuth($cliente, $usuario, $contrasena);
            $login = $this->Seguridad->validaUsuario($usuario, $contrasena);
            if($login["status"]){
                $token = $this->Seguridad->generaToken($dispositivo, $login["rows"][0]["idempleado"]);
                $sql = "INSERT INTO push_nmserver VALUES(null, ". $login["rows"][0]["idempleado"] .", '". $push ."', 1);";
                $sql = $this->queryArray($sql);
                unset($login["rows"][0]["idempleado"]);
                unset($login["fields"]);
                $login["rows"][0]["token"] = $token;
            }
            return $login;
        }

        public function terminarSesionUsuario(){
            $login = $this->Seguridad->terminaUsuario();
            return $login;
        }

        public function obtenerImagenes(){
            try{
                $logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
                $logo = $this->queryArray($logo);
                $r3 = $logo["rows"][0];

                $directory = "../webapp/modulos/pos/images/productos";
                $images = scandir($directory);
                $images = array_values(array_diff($images, array(".", "..")));

                $imagenes[] = ( $r3 );
                
                foreach($images as $image)
                {
                    $imagenes[] = array( 'image_url' => $image );
                }


                if(!empty($imagenes)){
                    $json = array("status" => true, "rows" => $imagenes);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
                

            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerImagenOrganizacion(){
            try{

                $logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
                $logo = $this->queryArray($logo);
                $r3 = $logo["rows"][0];

                if(!empty($logo)){
                    $json = array("status" => true, "rows" => $logo);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
                

            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

    }
?>
