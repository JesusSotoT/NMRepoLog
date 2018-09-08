<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    //require("models/connection.php"); // funciones mySQL 
    require_once dirname(__DIR__) .'/models/connection_sqli_manual.php'; // funciones mySQLi 
    //ini_set("display_errors",1); error_reporting(E_ALL)
    include_once dirname(__FILE__). '/../../../netwarelog/defuse-crypto/defuse-crypto.phar';
    use Defuse\Crypto\Key;
    use Defuse\Crypto\Crypto;

    class MailingModel extends Connection {

        public $servidor;
        public $correo;
        public $clave;

        function __construct()
        {
            $this->connect();
            $this->servidor = (object) array("tipo" => null, "url" => null, "puerto" => null, "autentificacion" => null, "metodo" => null);
            $this->obtener(false);
        }

        public function guardar($datos)
        {
            try{
                $this->servidor->tipo = $datos["mailing_tipo"];
                if($this->servidor->tipo != 5){
                    $this->connect(true);
                    $sql = "SELECT * FROM configuracion_smtp WHERE id = ". $datos["mailing_tipo"] .";";
                    $sql = $this->queryArray($sql);
                    $sql = $sql["rows"][0];
                    $this->servidor->url = $sql["servidor"];
                    $this->servidor->puerto = $sql["puerto"];
                    $this->servidor->autentificacion = $sql["autentificacion"];
                    $this->servidor->metodo = $sql["metodo"];
                    $this->connect();
                } else {
                    $this->servidor->url = $datos["mailing_conf_servidor"];
                    $this->servidor->puerto = $datos["mailing_conf_puerto"];
                    $this->servidor->autentificacion = $datos["mailing_conf_activo"];
                    $this->servidor->metodo = $datos["mailing_conf_metodo"];
                }

                if($datos["mailing_contrasena"] == "*****"){
                    $datos["mailing_contrasena"] = $this->clave;
                } else {
                    $keyAscii = Key::loadFromAsciiSafeString(file_get_contents(dirname(__FILE__). '/../../../netwarelog/defuse-crypto/data-key.txt'));
                    $datos["mailing_contrasena"] = Crypto::encrypt($datos["mailing_contrasena"], $keyAscii);
                } 

                $sql = "DELETE FROM configuracion_mailing;";
                $sql = $this->queryArray($sql);
                $sql = "INSERT INTO configuracion_mailing VALUES(null, ". $this->servidor->tipo .", ". $this->servidor->autentificacion .", '". $this->servidor->metodo ."', '". $this->servidor->url ."', ". $this->servidor->puerto .", '". $datos["mailing_correo"] ."', '". $datos["mailing_contrasena"] ."');";
                $sql = $this->queryArray($sql);
                $this->obtener(false);
                return array("status" => true); 
            }catch(Exception $e){
                return array("status" => false, "mensaje" => $e->getMessage());
            }
        }

        public function obtener($returns = true)
        {
            try{
                $sql = "SELECT * FROM configuracion_mailing;";
                $sql = $this->queryArray($sql);
                $sql = $sql["rows"][0];
                $this->servidor->tipo = $sql["tipo"];
                $this->servidor->url = $sql["servidor"];
                $this->servidor->puerto = $sql["puerto"];
                $this->servidor->autentificacion = $sql["autentificacion"];
                $this->servidor->metodo = $sql["metodo"];
                $this->correo = $sql["correo"];
                $keyAscii = Key::loadFromAsciiSafeString(file_get_contents(dirname(__FILE__). '/../../../netwarelog/defuse-crypto/data-key.txt'));
                $this->clave = Crypto::decrypt($sql["clave"], $keyAscii);
                if($returns){
                    $this->clave = "*****";
                    $respuesta = array("status" => true, "informacion" => array("mailing_tipo" => $this->servidor->tipo, "mailing_conf_servidor" => $this->servidor->url, "mailing_conf_puerto" => $this->servidor->puerto, "mailing_conf_activo" => $this->servidor->autentificacion, "mailing_conf_metodo" => $this->servidor->metodo, "mailing_correo" => $this->correo, "mailing_contrasena" => $this->clave));
                    return $respuesta;
                }
            }catch (\Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException $e) {
                return array("status" => false, "mensaje" => $e->getMessage());
            }
        }

    }

?>