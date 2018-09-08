<?php
    //ini_set("display_errors", 1); error_reporting(E_ALL);
    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class CategoriaModel extends Connection
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

        
        public function listado(){
            try {
                $query = "SELECT * FROM cont_comprobante_categoria;";
                $result = $this->queryArray($query);
                if($result["status"]){
                    $json = array("status" => true, "rows" => $result["rows"]);
                }else{
                    $json = array("status" => false, "mensaje" => $result["msg"]);
                }
            } catch (Exception $e) {
                $json = array("status" => false, "mensaje" => $e->getMessage());
            } 
            return $json;       
        }

    }
?>
