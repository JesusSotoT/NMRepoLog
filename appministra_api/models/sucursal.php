<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_api_sqli_manual.php"); // funciones mySQLi

    class SucursalModel extends Connectionapi
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

        public function obtenerSucursal($filtros, $parametros){
            try{
                $select = "SELECT * FROM mrp_sucursal WHERE ". $filtros ." AND activo = -1;";
                $resultSelect = $this->queryArray($select, $parametros);
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function agregarSucursal($nombre, $direccion, $estado, $municipio, $codigo_postal, $telefono, $contacto){
            try{

                $verifica = $this->obtenerSucursal("nombre = :nombre", array("nombre" => $nombre));

                if(!$verifica["status"]) throw new Exception("Error", 601);
                if($verifica["total"] == 0){
                    $insert = "INSERT INTO mrp_sucursal (idSuc, nombre, direccion, idEstado, idMunicipio, cp, tel_contacto, contacto) VALUES ";
                    $insert .= "(null, :nombre, :direccion, :estado, :municipio, :codigo_postal, :telefono, :contacto);";
                    
                    $parametros = array(
                                        "nombre" => $nombre,
                                        "direccion" => $direccion,
                                        "estado" => $estado,
                                        "municipio" => $municipio,
                                        "codigo_postal" => $codigo_postal,
                                        "telefono" => $telefono,
                                        "contacto" => $contacto
                                        ); 
                    $resultInsert = $this->queryArray($insert, $parametros);
                }else{
                    $resultInsert = array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Nombre ya ha sido registrado");
                }
                return $resultInsert;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function editarSucursal($id, $nombre, $direccion, $estado, $municipio, $codigo_postal, $telefono, $contacto){
            try{
                $verifica = $this->obtenerSucursal("nombre = :nombre AND idSuc != :id", array("id" => $id, "nombre" => $nombre));
                if(!$verifica["status"]) throw new Exception("Error", 601);
                if($verifica["total"] == 0){
                    $update = "UPDATE mrp_sucursal SET nombre = :nombre, direccion = :direccion, idEstado = :estado, idMunicipio = :municipio, cp = :codigo_postal, tel_contacto = :telefono, contacto = :contacto WHERE idSuc = :id;";

                    $parametros = array(
                                        "id" => $id,
                                        "nombre" => $nombre,
                                        "direccion" => $direccion,
                                        "estado" => $estado,
                                        "municipio" => $municipio,
                                        "codigo_postal" => $codigo_postal,
                                        "telefono" => $telefono,
                                        "contacto" => $contacto
                                        ); 

                    $resultUpdate = $this->queryArray($update, $parametros);
                }else{
                    $resultUpdate = array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Nombre ya ha sido registrado");
                }
                return $resultUpdate;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function eliminarSucursal($id){
            try{
                $sel = 'UPDATE mrp_sucursal set activo = 0 where idSuc = '. $id;
                $res = $this->queryArray($sel);
                return  $res;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible editar la información", "error" => $e->getMessage());
            }
        }

    }

?>
