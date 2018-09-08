<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class Formulario_cincoModel extends Connection
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

        public function guardarFormularioCinco($request, $consultor){
            try{

                $select = "SELECT * FROM inovekia_formulario_cinco WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor AND visita = :visita;";
                $resultSelect = $this->queryArray($select, array("id_empresario" => $request['empresario'], "id_consultor" => $consultor, "visita" => $request['visita']));

                if($resultSelect["total"] == 0){
                    $insert = "INSERT INTO inovekia_formulario_cinco (f5p1a, f5p2a, f5p3a, f5p4a, f5p5a, f5p6a, f5p7a, f5p8a, id_empresario, id_consultor, visita) VALUES ";

                    $insert .= "(:f5p1a, :f5p2a, :f5p3a, :f5p4a, :f5p5a, :f5p6a, :f5p7a, :f5p8a, :id_empresario, :id_consultor, :visita);";
                        
                    $parametros = array(
                                        "f5p1a" => $request['f5p1a'],
                                        "f5p2a" => $request['f5p2a'],
                                        "f5p3a" => $request['f5p3a'],
                                        "f5p4a" => $request['f5p4a'],
                                        "f5p5a" => $request['f5p5a'],
                                        "f5p6a" => $request['f5p6a'],
                                        "f5p7a" => $request['f5p7a'],
                                        "f5p8a" => $request['f5p8a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor,
                                        "visita" => $request['visita']
                                        ); 
                    $resultInsert = $this->queryArray($insert, $parametros);
                    
                    return $resultInsert;   
                }else{
                    $update = "UPDATE inovekia_formulario_cinco SET f5p1a = :f5p1a, f5p2a = :f5p2a, f5p3a = :f5p3a, 
                                f5p4a = :f5p4a, f5p5a = :f5p5a, f5p6a = :f5p6a, f5p7a = :f5p7a, f5p8a = :f5p8a, 
                                id_empresario = :id_empresario, id_consultor = :id_consultor, visita = :visita 
                                WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor AND visita = :visita;";
                        
                    $parametros = array(
                                        "f5p1a" => $request['f5p1a'],
                                        "f5p2a" => $request['f5p2a'],
                                        "f5p3a" => $request['f5p3a'],
                                        "f5p4a" => $request['f5p4a'],
                                        "f5p5a" => $request['f5p5a'],
                                        "f5p6a" => $request['f5p6a'],
                                        "f5p7a" => $request['f5p7a'],
                                        "f5p8a" => $request['f5p8a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor,
                                        "visita" => $request['visita']
                                        ); 
                    $resultInsert = $this->queryArray($update, $parametros);
                    
                    return $resultInsert;
                }

            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function obtenerFormularioCinco(){
            try{
                $select = "SELECT * FROM inovekia_formulario_cinco;";
                $resultSelect = $this->queryArray($select, array());
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

    }

?>

