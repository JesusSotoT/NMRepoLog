<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones 
    require_once("libraries/input.php");

    class Formulario_seisModel extends Connection
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

        public function guardarFormularioSeis($request, $consultor){
            try{
                $select = "SELECT * FROM inovekia_formulario_seis WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor;";
                $resultSelect = $this->queryArray($select, array("id_empresario" => $request['empresario'], "id_consultor" => $consultor));

                if($resultSelect["total"] == 0){
                    $insert = "INSERT INTO inovekia_formulario_seis (f6p1a, f6p2a, f6p3a, f6p4a, f6p5a, f6p6a, f6p7a, f6p8a, f6p9a, f6p10a, f6p11a, f6p12a, f6p13a, f6p14a, f6p15a, f6p16a, f6p17a, f6p18a, f6p19a, f6p20a, f6p21a, f6p22a, f6p23a, f6p24a, f6p25a, id_empresario, id_consultor) VALUES ";

                    $insert .= "(:f6p1a, :f6p2a, :f6p3a, :f6p4a, :f6p5a, :f6p6a, :f6p7a, :f6p8a, :f6p9a, :f6p10a, :f6p11a, :f6p12a, :f6p13a, :f6p14a, :f6p15a, :f6p16a, :f6p17a, :f6p18a, :f6p19a, :f6p20a, :f6p21a, :f6p22a, :f6p23a, :f6p24a, :f6p25a, :id_empresario, :id_consultor);";

                    $parametros = array(
                                        "f6p1a" => $request['f6p1a'],
                                        "f6p2a" => $request['f6p2a'],
                                        "f6p3a" => $request['f6p3a'],
                                        "f6p4a" => $request['f6p4a'],
                                        "f6p5a" => $request['f6p5a'],
                                        "f6p6a" => $request['f6p6a'],
                                        "f6p7a" => $request['f6p7a'],
                                        "f6p8a" => $request['f6p8a'],
                                        "f6p9a" => $request['f6p9a'],
                                        "f6p10a" => $request['f6p10a'],
                                        "f6p11a" => $request['f6p11a'],
                                        "f6p12a" => $request['f6p12a'],
                                        "f6p13a" => $request['f6p13a'],
                                        "f6p14a" => $request['f6p14a'],
                                        "f6p15a" => $request['f6p15a'],
                                        "f6p16a" => $request['f6p16a'],
                                        "f6p17a" => $request['f6p17a'],
                                        "f6p18a" => $request['f6p18a'],
                                        "f6p19a" => $request['f6p19a'],
                                        "f6p20a" => $request['f6p20a'],
                                        "f6p21a" => $request['f6p21a'],
                                        "f6p22a" => $request['f6p22a'],
                                        "f6p23a" => $request['f6p23a'],
                                        "f6p24a" => $request['f6p24a'],
                                        "f6p25a" => $request['f6p25a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 

                    $resultInsert = $this->queryArray($insert, $parametros);

                    return $resultInsert;
                }else{
                    $update = "UPDATE inovekia_formulario_seis SET f6p1a = :f6p1a, f6p2a = :f6p2a, f6p3a = :f6p3a, f6p4a = :f6p4a, 
                                f6p5a = :f6p5a, f6p6a = :f6p6a, f6p7a = :f6p7a, f6p8a = :f6p8a, f6p9a = :f6p9a, f6p10a = :f6p10a, 
                                f6p11a = :f6p11a, f6p12a = :f6p12a, f6p13a = :f6p13a, f6p14a = :f6p14a, f6p15a = :f6p15a, f6p16a = :f6p16a, 
                                f6p17a = :f6p17a, f6p18a = :f6p18a, f6p19a = :f6p19a, f6p20a = :f6p20a, f6p21a = :f6p21a, f6p22a = :f6p22a, 
                                f6p23a = :f6p23a, f6p24a = :f6p24a, f6p25a = :f6p25a, id_empresario = :id_empresario, 
                                id_consultor = :id_consultor WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor;";

                    $parametros = array(
                                        "f6p1a" => $request['f6p1a'],
                                        "f6p2a" => $request['f6p2a'],
                                        "f6p3a" => $request['f6p3a'],
                                        "f6p4a" => $request['f6p4a'],
                                        "f6p5a" => $request['f6p5a'],
                                        "f6p6a" => $request['f6p6a'],
                                        "f6p7a" => $request['f6p7a'],
                                        "f6p8a" => $request['f6p8a'],
                                        "f6p9a" => $request['f6p9a'],
                                        "f6p10a" => $request['f6p10a'],
                                        "f6p11a" => $request['f6p11a'],
                                        "f6p12a" => $request['f6p12a'],
                                        "f6p13a" => $request['f6p13a'],
                                        "f6p14a" => $request['f6p14a'],
                                        "f6p15a" => $request['f6p15a'],
                                        "f6p16a" => $request['f6p16a'],
                                        "f6p17a" => $request['f6p17a'],
                                        "f6p18a" => $request['f6p18a'],
                                        "f6p19a" => $request['f6p19a'],
                                        "f6p20a" => $request['f6p20a'],
                                        "f6p21a" => $request['f6p21a'],
                                        "f6p22a" => $request['f6p22a'],
                                        "f6p23a" => $request['f6p23a'],
                                        "f6p24a" => $request['f6p24a'],
                                        "f6p25a" => $request['f6p25a'],
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

        public function obtenerFormularioSeis(){
            try{
                $select = "SELECT * FROM inovekia_formulario_seis;";
                $resultSelect = $this->queryArray($select, array());
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

    }

?>

