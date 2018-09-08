<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_api_sqli_manual.php"); // funciones mySQLi

    class DepartamentoModel extends Connectionapi
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

        public function obtenerDepartamento($filtros, $parametros){
            try{
                $select = "SELECT * FROM app_departamento WHERE ". $filtros .";";
                $resultSelect = $this->queryArray($select, $parametros);
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function agregarDepartamento($nombre){
            try{
                $verifica = $this->obtenerDepartamento("nombre = :nombre", array("nombre" => $nombre));
                if(!$verifica["status"]) throw new Exception("Error", 601);
                if($verifica["total"] == 0){
                    $insert = "INSERT INTO app_departamento VALUES(null, :nombre);";
                    $resultInsert = $this->queryArray($insert, array("nombre" => $nombre));
                }else{
                    $resultInsert = array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Nombre ya ha sido registrado");
                }
                return $resultInsert;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function editarDepartamento($id, $nombre){
            try{
                $verifica = $this->obtenerDepartamento("nombre = :nombre AND id != :id;", array("id" => $id, "nombre" => $nombre));
                if(!$verifica["status"]) throw new Exception("Error", 601);
                if($verifica["total"] == 0){
                    $update = "UPDATE app_departamento SET nombre = :nombre WHERE id = :id;";
                    $resultUpdate = $this->queryArray($update, array("id" => $id, "nombre" => $nombre));
                }else{
                    $resultUpdate = array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Nombre ya ha sido registrado");
                }
                return $resultUpdate;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible editar la información", "error" => $e->getMessage());
            }
        }

    }

?>
