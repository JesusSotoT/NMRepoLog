<?php

	//Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    /**
    * 
    */
    class NumeroVisitaModel extends Connection
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

        public function obtenerVisita($consultor, $id_empresario){
                try{
                    $seleccionar = "SELECT visita FROM inovekia_visita_consultor WHERE id_empresario = :id_empresario ;";
                    $resultado = $this->queryArray($seleccionar, array("id_empresario" => $id_empresario));

                    return $resultado;

                }catch(Exception $e){
                    return array("status" => false, "rows" => array(), "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
                }
        }

    }

?>
