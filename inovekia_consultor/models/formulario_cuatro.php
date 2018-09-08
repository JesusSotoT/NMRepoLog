<?php

	//Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    /**
    * 
    */
    class Formulario_cuatroModel extends Connection
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
        public function guardarFormularioCuatro($request, $consultor){
            try{

                $select = "SELECT * FROM inovekia_formulario_cuatro WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor;";
                $resultSelect = $this->queryArray($select, array("id_empresario" => $request['empresario'], "id_consultor" => $consultor));

                if($resultSelect["total"] == 0){
                    $insert = "INSERT INTO inovekia_formulario_cuatro (f4p1a, f4p2a, f4p3a, f4p4a, f4p5a, f4p6a, f4p7a, f4p8a, f4p9a, f4p10a, f4p11a, f4p12a, f4p13a, f4p14a, f4p15a, f4p16a, f4p17a, f4p18a, f4p19a, f4p20a, f4p21a, f4p22a, f4p23a, f4p24a, f4p24r1a, f4p24r2a, f4p24r3a, f4p24r4a, f4p24r5a, f4p24r6a, f4p24r7a, f4p24r8a, f4p25a, f4p25b, f4p26a, f4p27a, f4p28a, f4p29a, f4p30a, f4p31a, f4p32a, f4p33a, f4p34r1a, f4p34r1b, f4p34r1c, f4p34r1d, f4p34r1e, f4p34r1f, f4p34r1g, f4p34r1h, f4p35a, f4p36a, f4p37a, f4p38a, f4p39a, f4p40a, f4p41a, f4p42a, f4p43a, f4p44a, f4p45a, f4p46a, f4p47a, f4p48a, f4p49a, f4p50a, id_empresario, id_consultor) VALUES ";

                    $insert .= "(:f4p1a, :f4p2a, :f4p3a, :f4p4a, :f4p5a, :f4p6a, :f4p7a, :f4p8a, :f4p9a, :f4p10a, :f4p11a, :f4p12a, :f4p13a, :f4p14a, :f4p15a, :f4p16a, :f4p17a, :f4p18a, :f4p19a, :f4p20a, :f4p21a, :f4p22a, :f4p23a, :f4p24a, :f4p24r1a, :f4p24r2a, :f4p24r3a, :f4p24r4a, :f4p24r5a, :f4p24r6a, :f4p24r7a, :f4p24r8a, :f4p25a, :f4p25b, :f4p26a, :f4p27a, :f4p28a, :f4p29a, :f4p30a, :f4p31a, :f4p32a, :f4p33a, :f4p34r1a, :f4p34r1b, :f4p34r1c, :f4p34r1d, :f4p34r1e, :f4p34r1f, :f4p34r1g, :f4p34r1h, :f4p35a, :f4p36a, :f4p37a, :f4p38a, :f4p39a, :f4p40a, :f4p41a, :f4p42a, :f4p43a, :f4p44a, :f4p45a, :f4p46a, :f4p47a, :f4p48a, :f4p49a, :f4p50a, :id_empresario, :id_consultor);";
                    
                    $parametros = array(
                                        "f4p1a" => $request['f4p1a'], 
                                        "f4p2a" => $request['f4p2a'],
                                        "f4p3a" => $request['f4p3a'], 
                                        "f4p4a" => $request['f4p4a'], 
                                        "f4p5a" => $request['f4p5a'], 
                                        "f4p6a" => $request['f4p6a'], 
                                        "f4p7a" => $request['f4p7a'], 
                                        "f4p8a" => $request['f4p8a'], 
                                        "f4p9a" => $request['f4p9a'], 
                                        "f4p10a" => $request['f4p10a'], 
                                        "f4p11a" => $request['f4p11a'], 
                                        "f4p12a" => $request['f4p12a'], 
                                        "f4p13a" => $request['f4p13a'], 
                                        "f4p14a" => $request['f4p14a'], 
                                        "f4p15a" => $request['f4p15a'], 
                                        "f4p16a" => $request['f4p16a'], 
                                        "f4p17a" => $request['f4p17a'], 
                                        "f4p18a" => $request['f4p18a'], 
                                        "f4p19a" => $request['f4p19a'], 
                                        "f4p20a" => $request['f4p20a'], 
                                        "f4p21a" => $request['f4p21a'], 
                                        "f4p22a" => $request['f4p22a'], 
                                        "f4p23a" => $request['f4p23a'], 
                                        "f4p24a" => $request['f4p24a'], 
                                        "f4p24r1a" => $request['f4p24r1a'], 
                                        "f4p24r2a" => $request['f4p24r2a'],
                                        "f4p24r3a" => $request['f4p24r3a'],
                                        "f4p24r4a" => $request['f4p24r4a'],
                                        "f4p24r5a" => $request['f4p24r5a'],
                                        "f4p24r6a" => $request['f4p24r6a'],
                                        "f4p24r7a" => $request['f4p24r7a'],
                                        "f4p24r8a" => $request['f4p24r8a'],
                                        "f4p25a" => $request['f4p25a'],
                                        "f4p25b" => $request['f4p25b'],
                                        "f4p26a" => $request['f4p26a'],
                                        "f4p27a" => $request['f4p27a'],
                                        "f4p28a" => $request['f4p28a'],
                                        "f4p29a" => $request['f4p29a'],
                                        "f4p30a" => $request['f4p30a'],
                                        "f4p31a" => $request['f4p31a'],
                                        "f4p32a" => $request['f4p32a'],
                                        "f4p33a" => $request['f4p33a'],
                                        "f4p34r1a" => $request['f4p34r1a'],
                                        "f4p34r1b" => $request['f4p34r1b'],
                                        "f4p34r1c" => $request['f4p34r1c'],
                                        "f4p34r1d" => $request['f4p34r1d'],
                                        "f4p34r1e" => $request['f4p34r1e'],
                                        "f4p34r1f" => $request['f4p34r1f'],
                                        "f4p34r1g" => $request['f4p34r1g'],
                                        "f4p34r1h" => $request['f4p34r1h'],
                                        "f4p35a" => $request['f4p35a'],
                                        "f4p36a" => $request['f4p36a'],
                                        "f4p37a" => $request['f4p37a'],
                                        "f4p38a" => $request['f4p38a'],
                                        "f4p39a" => $request['f4p39a'],
                                        "f4p40a" => $request['f4p40a'],
                                        "f4p41a" => $request['f4p41a'],
                                        "f4p42a" => $request['f4p42a'],
                                        "f4p43a" => $request['f4p43a'],
                                        "f4p44a" => $request['f4p44a'],
                                        "f4p45a" => $request['f4p45a'],
                                        "f4p46a" => $request['f4p46a'],
                                        "f4p47a" => $request['f4p47a'],
                                        "f4p48a" => $request['f4p48a'],
                                        "f4p49a" => $request['f4p49a'],
                                        "f4p50a" => $request['f4p50a'],
                                        "id_empresario" => $request['empresario'],
                                        "id_consultor" => $consultor
                                        ); 
                    $resultInsert = $this->queryArray($insert, $parametros);

                    return $resultInsert;
                }else{
                    $update = "UPDATE inovekia_formulario_cuatro SET f4p1a = :f4p1a, f4p2a = :f4p2a, f4p3a = :f4p3a, f4p4a = :f4p4a, 
                            f4p5a = :f4p5a, f4p6a = :f4p6a, f4p7a = :f4p7a, f4p8a = :f4p8a, f4p9a = :f4p9a, f4p10a = :f4p10a, 
                            f4p11a = :f4p11a, f4p12a = :f4p12a, f4p13a = :f4p13a, f4p14a = :f4p14a, f4p15a = :f4p15a, f4p16a = :f4p16a, 
                            f4p17a = :f4p17a, f4p18a = :f4p18a, f4p19a = :f4p19a, f4p20a = :f4p20a, f4p21a = :f4p21a, f4p22a = :f4p22a, 
                            f4p23a = :f4p23a, f4p24a = :f4p24a, f4p24r1a = :f4p24r1a, f4p24r2a = :f4p24r2a, f4p24r3a = :f4p24r3a, 
                            f4p24r4a = :f4p24r4a, f4p24r5a = :f4p24r5a, f4p24r6a = :f4p24r6a, f4p24r7a = :f4p24r7a, f4p24r8a = :f4p24r8a, 
                            f4p25a = :f4p25a, f4p25b = :f4p25b, f4p26a = :f4p26a, f4p27a = :f4p27a, f4p28a = :f4p28a, f4p29a = :f4p29a, 
                            f4p30a = :f4p30a, f4p31a = :f4p31a, f4p32a = :f4p32a, f4p33a = :f4p33a, f4p34r1a = :f4p34r1a, 
                            f4p34r1b = :f4p34r1b, f4p34r1c = :f4p34r1c, f4p34r1d = :f4p34r1d, f4p34r1e = :f4p34r1e, f4p34r1f = :f4p34r1f, 
                            f4p34r1g = :f4p34r1g, f4p34r1h = :f4p34r1h, f4p35a = :f4p35a, f4p36a = :f4p36a, f4p37a = :f4p37a, 
                            f4p38a = :f4p38a, f4p39a = :f4p39a, f4p40a = :f4p40a, f4p41a = :f4p41a, f4p42a = :f4p42a, f4p43a = :f4p43a, 
                            f4p44a = :f4p44a, f4p45a = :f4p45a, f4p46a = :f4p46a, f4p47a = :f4p47a, f4p48a = :f4p48a, f4p49a = :f4p49a, 
                            f4p50a = :f4p50a, id_empresario = :id_empresario, id_consultor = :id_consultor 
                            WHERE id_empresario = :id_empresario AND id_consultor = :id_consultor; ";
                    
                    $parametros = array(
                                        "f4p1a" => $request['f4p1a'], 
                                        "f4p2a" => $request['f4p2a'],
                                        "f4p3a" => $request['f4p3a'], 
                                        "f4p4a" => $request['f4p4a'], 
                                        "f4p5a" => $request['f4p5a'], 
                                        "f4p6a" => $request['f4p6a'], 
                                        "f4p7a" => $request['f4p7a'], 
                                        "f4p8a" => $request['f4p8a'], 
                                        "f4p9a" => $request['f4p9a'], 
                                        "f4p10a" => $request['f4p10a'], 
                                        "f4p11a" => $request['f4p11a'], 
                                        "f4p12a" => $request['f4p12a'], 
                                        "f4p13a" => $request['f4p13a'], 
                                        "f4p14a" => $request['f4p14a'], 
                                        "f4p15a" => $request['f4p15a'], 
                                        "f4p16a" => $request['f4p16a'], 
                                        "f4p17a" => $request['f4p17a'], 
                                        "f4p18a" => $request['f4p18a'], 
                                        "f4p19a" => $request['f4p19a'], 
                                        "f4p20a" => $request['f4p20a'], 
                                        "f4p21a" => $request['f4p21a'], 
                                        "f4p22a" => $request['f4p22a'], 
                                        "f4p23a" => $request['f4p23a'], 
                                        "f4p24a" => $request['f4p24a'], 
                                        "f4p24r1a" => $request['f4p24r1a'], 
                                        "f4p24r2a" => $request['f4p24r2a'],
                                        "f4p24r3a" => $request['f4p24r3a'],
                                        "f4p24r4a" => $request['f4p24r4a'],
                                        "f4p24r5a" => $request['f4p24r5a'],
                                        "f4p24r6a" => $request['f4p24r6a'],
                                        "f4p24r7a" => $request['f4p24r7a'],
                                        "f4p24r8a" => $request['f4p24r8a'],
                                        "f4p25a" => $request['f4p25a'],
                                        "f4p25b" => $request['f4p25b'],
                                        "f4p26a" => $request['f4p26a'],
                                        "f4p27a" => $request['f4p27a'],
                                        "f4p28a" => $request['f4p28a'],
                                        "f4p29a" => $request['f4p29a'],
                                        "f4p30a" => $request['f4p30a'],
                                        "f4p31a" => $request['f4p31a'],
                                        "f4p32a" => $request['f4p32a'],
                                        "f4p33a" => $request['f4p33a'],
                                        "f4p34r1a" => $request['f4p34r1a'],
                                        "f4p34r1b" => $request['f4p34r1b'],
                                        "f4p34r1c" => $request['f4p34r1c'],
                                        "f4p34r1d" => $request['f4p34r1d'],
                                        "f4p34r1e" => $request['f4p34r1e'],
                                        "f4p34r1f" => $request['f4p34r1f'],
                                        "f4p34r1g" => $request['f4p34r1g'],
                                        "f4p34r1h" => $request['f4p34r1h'],
                                        "f4p35a" => $request['f4p35a'],
                                        "f4p36a" => $request['f4p36a'],
                                        "f4p37a" => $request['f4p37a'],
                                        "f4p38a" => $request['f4p38a'],
                                        "f4p39a" => $request['f4p39a'],
                                        "f4p40a" => $request['f4p40a'],
                                        "f4p41a" => $request['f4p41a'],
                                        "f4p42a" => $request['f4p42a'],
                                        "f4p43a" => $request['f4p43a'],
                                        "f4p44a" => $request['f4p44a'],
                                        "f4p45a" => $request['f4p45a'],
                                        "f4p46a" => $request['f4p46a'],
                                        "f4p47a" => $request['f4p47a'],
                                        "f4p48a" => $request['f4p48a'],
                                        "f4p49a" => $request['f4p49a'],
                                        "f4p50a" => $request['f4p50a'],
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

        public function obtenerFormularioCuatro(){
            try{
                $select = "SELECT * FROM inovekia_formulario_cuatro;";
                $resultSelect = $this->queryArray($select, array());
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }
    	
    }
?>