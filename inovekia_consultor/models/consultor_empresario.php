<?php

	//Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    /**
    * 
    */
    class ConsultorEmpresarioModel extends Connection
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

        public function obtenerEmpresario($id_consultor){
            try{
                $seleccionar = "SELECT id_empresario AS id FROM inovekia_empresario_consultor WHERE id_consultor = :id_consultor ;";

                $resultado = $this->queryArray($seleccionar, array("id_consultor" => $id_consultor));
                
                $ids = "";
                foreach ($resultado["rows"] as $empresario) {
                    $ids .= $empresario["id"] . ",";
                }
                $ids = trim($ids, ",");

                $seleccionar = "SELECT * FROM inovekia_empresario WHERE id IN ($ids);";

                $resultado2 = $this->queryArray($seleccionar, array());

                return $resultado2;

            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function obtenerEmpresario2($id_consultor){
            try{
                $seleccionar = "SELECT id_organismo FROM inovekia_organismo_consultor WHERE id_consultor = :id_consultor ;";
                $resultado = $this->queryArray($seleccionar, array("id_consultor" => $id_consultor));

                $id_organismo = $resultado["rows"][0]["id_organismo"];

                $seleccionar = "SELECT id_consultor AS id FROM inovekia_organismo_consultor WHERE id_organismo = :id_organismo ;";
                $resultado = $this->queryArray($seleccionar, array("id_organismo" => $id_organismo));

                $ids = "";
                foreach ($resultado["rows"] as $consultor) {
                    $ids .= $consultor["id"] . ",";
                }
                $ids = trim($ids, ",");

                //Tabla1

                $seleccionar_1 = "SELECT id_empresario AS id FROM inovekia_empresario_consultor WHERE id_consultor IN ($ids);";

                $resultado_1 = $this->queryArray($seleccionar_1, array());

                $ids_tabla_1 = "";
                foreach ($resultado_1["rows"] as $empresario) {
                    $ids_tabla_1 .= $empresario["id"] . ",";
                }
                $ids_tabla_1 = trim($ids_tabla_1, ",");

                $seleccionar_tabla_1 = "SELECT e.id, e.razon, e.nombre, f.folio, iec.id_consultor  FROM inovekia_empresario AS e LEFT OUTER JOIN inovekia_empresario_folio AS f ON f.id_empresario = e.id INNER JOIN inovekia_empresario_consultor as iec ON iec.id_empresario = e.id WHERE e.id IN ($ids_tabla_1);";
                $resultado_tabla_1 = $this->queryArray($seleccionar_tabla_1, array());
                
                return $resultado_tabla_1;

            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function guardarConsultorEmpresario($request){
            try{
                
                $insertar = "INSERT INTO inovekia_empresario_consultor VALUES(null, :id_empresario, :id_consultor, :activo, :creado, :modificado);";

                /*$parametros = array("id_empresario" => $request["id_empresario"], 
                					"id_consultor" => $request["id_consultor"], 
                					"activo" => $request["activo"], 
                					"creado" => date("Y-m-d H:i:s"), 
                					"modificado" => date("Y-m-d H:i:s"));*/					

                $insertar_resultado = $this->queryArray($insertar, $parametros);

                return $insertar_resultado;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function reasignarEmpresario($consultor, $empresario){
            try{
                
                $insertar = "UPDATE inovekia_empresario_consultor SET id_consultor = :consultor WHERE id_empresario = :empresario;";          
                $insertar_resultado = $this->queryArray($insertar, array("consultor" => $consultor, "empresario" => $empresario));
                if($insertar_resultado["status"]){
                    $json = $insertar_resultado;
                } else {
                    throw new Exception("", 1);
                }
            }catch(Exception $e){
                $json = array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
            return $json;
        }

    }

?>