<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones 
    require_once("libraries/input.php");

    class Formulario_nueveModel extends Connection
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

        public function guardarFormularioNueve($request, $consultor){
            try{
                $select = "SELECT * FROM inovekia_formulario_nueve WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor;";
                $resultSelect = $this->queryArray($select, array("id_empresario" => $request['empresario'], "id_consultor" => $consultor));

                if($resultSelect["total"] == 0){
                    $insert = "INSERT INTO inovekia_formulario_nueve (f9p1a, f9p2a, f9p3a, f9p4a, f9p5a, f9p6a, f9p7a, f9p8a, f9p9a, f9p10a, id_empresario, id_consultor) VALUES ";

                    $insert .= "(:f9p1a, :f9p2a, :f9p3a, :f9p4a, :f9p5a, :f9p6a, :f9p7a, :f9p8a, :f9p9a, :f9p10a, :id_empresario, :id_consultor);";

                    $parametros = array(
                                        "f9p1a" => $request['f9p1a'],
                                        "f9p2a" => $request['f9p2a'],
                                        "f9p3a" => $request['f9p3a'],
                                        "f9p4a" => $request['f9p4a'],
                                        "f9p5a" => $request['f9p5a'],
                                        "f9p6a" => $request['f9p6a'],
                                        "f9p7a" => $request['f9p7a'],
                                        "f9p8a" => $request['f9p8a'],
                                        "f9p9a" => $request['f9p9a'],
                                        "f9p10a" => $request['f9p10a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 

                    $resultInsert = $this->queryArray($insert, $parametros);
                    
                    return $resultInsert; 
                }else{
                    $update = "UPDATE inovekia_formulario_nueve SET f9p1a = :f9p1a, f9p2a = :f9p2a, f9p3a = :f9p3a, 
                    f9p4a = :f9p4a, f9p5a = :f9p5a, f9p6a = :f9p6a, f9p7a = :f9p7a, f9p8a = :f9p8a, f9p9a = :f9p9a, 
                    f9p10a = :f9p10a, id_empresario = :id_empresario, id_consultor = :id_consultor WHERE id_empresario = :id_empresario 
                    AND id_consultor = :id_consultor;";

                    $parametros = array(
                                        "f9p1a" => $request['f9p1a'],
                                        "f9p2a" => $request['f9p2a'],
                                        "f9p3a" => $request['f9p3a'],
                                        "f9p4a" => $request['f9p4a'],
                                        "f9p5a" => $request['f9p5a'],
                                        "f9p6a" => $request['f9p6a'],
                                        "f9p7a" => $request['f9p7a'],
                                        "f9p8a" => $request['f9p8a'],
                                        "f9p9a" => $request['f9p9a'],
                                        "f9p10a" => $request['f9p10a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 

                    $resultInsert = $this->queryArray($update, $parametros);
                    
                    return $resultInsert;
                }
                
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function obtenerFormularioNueve(){
            try{
                $select = "SELECT * FROM inovekia_formulario_nueve;";
                $resultSelect = $this->queryArray($select, array());
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

    }

?>

