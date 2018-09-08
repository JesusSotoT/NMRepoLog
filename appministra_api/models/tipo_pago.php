<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_api_sqli_manual.php"); // funciones mySQLi

    class TipoPagoModel extends Connectionapi
    {
        
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

        public function obtenerTipoPago($filtros){
            try{
                $select = "SELECT idFormapago AS id, nombre FROM forma_pago WHERE ". $filtros;
                $resultSelect = $this->queryArray($select);
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertedId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

    }

?>