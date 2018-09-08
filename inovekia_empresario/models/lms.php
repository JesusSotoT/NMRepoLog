<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class LmsModel extends Connection
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

        public function obtenerSeguimiento($empleado, $curso){
            try{
                $seleccionar = "SELECT * FROM seguimiento_inovekia WHERE id_curso = :id_curso AND id_empleado = :id_empleado ORDER BY ultimo_slide DESC LIMIT 1;";
                $seleccionar_resultado = $this->queryArray($seleccionar, array("id_curso" => $curso, "id_empleado" => $empleado));
                return $seleccionar_resultado;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function guardarSeguimiento($empleado, $seguimiento){
            try{
                $insertar = "INSERT INTO seguimiento_inovekia VALUES(null, :id_empleado, :id_curso, :ultimo_slide, :seguimiento, :latitud, :longitud, :creado);";
                $parametros = array("id_empleado" => $empleado, "id_curso" => $seguimiento["id_curso"], "ultimo_slide" => $seguimiento['ultimo_slide'], "seguimiento" => $seguimiento["seguimiento"], "latitud" => $seguimiento["latitud"], "longitud" => $seguimiento["longitud"], "creado" => date("Y-m-d H:i:s"));
                $insertar_resultado = $this->queryArray($insertar, $parametros);
                return $insertar_resultado;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

    }

?>
