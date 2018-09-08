<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class ActualizarModel extends Connection
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

        public function actualizar($url){
            try{
                $sql = "SELECT * FROM push_nmserver WHERE activo = 1;";
                $sql = $this->queryArray($sql);
                if(!$sql["status"]) throw new Exception("No se ha podido obtener la informacion", 1);
                foreach ($sql["rows"] as $token) {
                    $google = 'https://fcm.googleapis.com/fcm/send';
                    $parametros = array (
                        'registration_ids' => array($token["token"]),
                        'data' => array ("message" => $url)
                    );
                    $parametros = json_encode($parametros);
                    $encabezados = array (
                        'Authorization: key=' . "AIzaSyCq20NwusOXedKGw2K1b_XcpHxj2kqWbsM",
                        'Content-Type: application/json'
                    );
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $google);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $encabezados);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
                    $resultado = curl_exec($ch);
                    curl_close($ch);
                }
                $json = array("success" => true);
            } catch(Exception $e){
                $json = array("error" => false, "mensaje" => $e->getMessage());
            }
            return $json;
        }

        public function actualizarPc($fecha){
            $json = array("status" => false);
            try {
                $sql = "SELECT * FROM nmserver WHERE activo = 1;";
                $resultado = $this->queryArray($sql);

                if($resultado["status"] && count($resultado["rows"]) > 0){
                    if(strtotime($resultado["rows"][0]["fecha"]) <= strtotime($fecha)){
                        $json = array("status" => true, "url" => $resultado["rows"][0]["url_windows"]);;
                    }                
                }
                $json = array("status" => false);          
            } catch (Exception $e) {
                $json = array("status" => false);
            }
            return json_encode($json);
        }

    }
?>
