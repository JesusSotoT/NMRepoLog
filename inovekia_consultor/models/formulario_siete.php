<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones 
    require_once("libraries/input.php");

    class Formulario_sieteModel extends Connection
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

        public function guardarFormularioSiete($request, $consultor){
            try{
                $insert = "INSERT INTO inovekia_formulario_siete (f7p1a, f7p2a, f7p3a, f7p4a, f7p5a, f7p6a, f7p7a, id_empresario, id_consultor) VALUES ";

                $insert .= "(:f7p1a, :f7p2a, :f7p3a, :f7p4a, :f7p5a, :f7p6a, :f7p7a, :id_empresario, :id_consultor);";

                $parametros = array(
                                    "f7p1a" => $request['f7p1a'],
                                    "f7p2a" => $request['f7p2a'],
                                    "f7p3a" => $request['f7p3a'],
                                    "f7p4a" => $request['f7p4a'],
                                    "f7p5a" => $request['f7p5a'],
                                    "f7p6a" => $request['f7p6a'],
                                    "f7p7a" => $request['f7p7a'],
                                    "id_empresario" => $request['empresario'],
                                    "id_consultor" => $consultor
                                    ); 

                $resultInsert = $this->queryArray($insert, $parametros);

                if($resultInsert['status']){
                    $insertv = "INSERT INTO inovekia_visita_consultor (id_consultor, id_empresario, visita, activo, creado, modificado) VALUES";
                    $insertv .= "(:id_consultor,:id_empresario, :visita, :activo, :creado, :modificado);";

                    $parametrosv = array("id_consultor" => $consultor,
                                        "id_empresario" => $request ['empresario'],
                                        "visita" =>  1,
                                        "activo" =>  1,
                                        "creado" =>  date('Y-m-d H:i:s'),
                                        "modificado" => date ('Y-m-d H:i:s'));
                    $resultInsert = $this->queryArray($insertv, $parametrosv);
                }
                return $resultInsert;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

         public function obtenerFormularioSiete($filtros, $parametros){
            try{
                $select = "SELECT * FROM mrp_sucursal WHERE ". $filtros ." AND activo = -1;";
                $resultSelect = $this->queryArray($select, $parametros);
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

    }

?>

