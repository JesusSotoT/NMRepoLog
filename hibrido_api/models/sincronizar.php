<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class SincronizarModel extends Connection
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

        public function caja($sql){
            try{
                $resultados = array();
                $sqls = explode(";", $sql);
                foreach ($sqls as $query) {
                    if(!is_null($query) && $query != "") {
                        $resultado = $this->queryArray($query);
                        if($resultado["status"]){
                            $resultados[] = $resultado["rows"];
                        }
                    }
                }
                $ids = $resultados[count($resultados)-1][0];
                $json = array("success" => true, "ids" => $ids);
            } catch(Exception $e){
                $json = array("error" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function foodware($sql){
            try{
                $resultados = array();
                $sqls = explode(";", $sql);
                foreach ($sqls as $query) {
                    if(!is_null($query) && $query != "") {
                        $resultado = $this->queryArray($query);
                        if($resultado["status"]){
                            $resultados[] = $resultado["rows"];
                        }
                    }
                }
                $ids = $resultados[count($resultados)-1][0];
                $json = array("success" => true, "ids" => $ids);
            } catch(Exception $e){
                $json = array("error" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

    }
?>
