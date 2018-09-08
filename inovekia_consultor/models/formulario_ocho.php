<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones 
    require_once("libraries/input.php");

    class Formulario_ochoModel extends Connection
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

        public function guardarFormularioOcho($request, $consultor){
            try{
                $select = "SELECT * FROM inovekia_formulario_ocho WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor;";
                $resultSelect = $this->queryArray($select, array("id_empresario" => $request['empresario'], "id_consultor" => $consultor));

                if($resultSelect["total"] == 0){
                    $insert = "INSERT INTO inovekia_formulario_ocho (f8p1a, f8p2a, f8p3a, f8p4a, f8p5a, f8p6a, f8p7a, f8p8a, f8p9a, id_empresario, id_consultor) VALUES ";

                    $insert .= "(:f8p1a, :f8p2a, :f8p3a, :f8p4a, :f8p5a, :f8p6a, :f8p7a, :f8p8a, :f8p9a, :id_empresario, :id_consultor);";

                    $parametros = array(
                                        "f8p1a" => $request['f8p1a'],
                                        "f8p2a" => $request['f8p2a'],
                                        "f8p3a" => $request['f8p3a'],
                                        "f8p4a" => $request['f8p4a'],
                                        "f8p5a" => $request['f8p5a'],
                                        "f8p6a" => $request['f8p6a'],
                                        "f8p7a" => $request['f8p7a'],
                                        "f8p8a" => $request['f8p8a'],
                                        "f8p9a" => $request['f8p9a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 

                    $resultInsert = $this->queryArray($insert, $parametros);
                    
                    return $resultInsert;
                }else{

                    $update = "UPDATE inovekia_formulario_ocho SET f8p1a = :f8p1a, f8p2a = :f8p2a, f8p3a = :f8p3a, 
                                f8p4a = :f8p4a, f8p5a = :f8p5a, f8p6a = :f8p6a, f8p7a = :f8p7a, f8p8a = :f8p8a, 
                                f8p9a = :f8p9a, id_empresario = :id_empresario, id_consultor = :id_consultor 
                                WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor;";

                    $parametros = array(
                                        "f8p1a" => $request['f8p1a'],
                                        "f8p2a" => $request['f8p2a'],
                                        "f8p3a" => $request['f8p3a'],
                                        "f8p4a" => $request['f8p4a'],
                                        "f8p5a" => $request['f8p5a'],
                                        "f8p6a" => $request['f8p6a'],
                                        "f8p7a" => $request['f8p7a'],
                                        "f8p8a" => $request['f8p8a'],
                                        "f8p9a" => $request['f8p9a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 

                    $resultUpdate = $this->queryArray($update, $parametros);
                    
                    return $resultUpdate;


                }
                
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function obtenerFormularioOcho(){
            try{
                $select = "SELECT * FROM inovekia_formulario_ocho;";
                $resultSelect = $this->queryArray($select, array());
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

    }

?>

