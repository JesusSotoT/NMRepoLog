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

        public function obtenerSeguimiento($consultor, $curso){
            try{
                $seleccionar = "SELECT * FROM inovekia_seguimiento WHERE id_curso = :curso AND id_consultor = :consultor AND id_empresario = :empresario ORDER BY creado DESC LIMIT 1;";
                $seleccionar_resultado = $this->queryArray($seleccionar, array("curso" => $curso['id_curso'], "consultor" => $consultor, "empresario" => $curso['id_empresario']));
                return $seleccionar_resultado;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function guardarSeguimiento($consultor, $seguimiento){
            try{
                $insertar = "INSERT INTO inovekia_seguimiento VALUES(null, :consultor, :empresario, :curso, :ultimo_slide, :scorm, :latitud, :longitud, :creado, :modificado, 1);";
                $parametros = array("consultor" => $consultor, "empresario" => $seguimiento["id_empresario"], "curso" => $seguimiento["id_curso"], "ultimo_slide" =>$seguimiento["ultimo_slide"], "scorm" => $seguimiento["seguimiento"], "latitud" => $seguimiento["latitud"], "longitud" => $seguimiento["longitud"], "creado" => date("Y-m-d H:i:s"), "modificado" => date("Y-m-d H:i:s"));
                $insertar_resultado = $this->queryArray($insertar, $parametros);
                return $insertar_resultado;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function guardarEvaluacion($consultor, $post){
            try{
                $insertar = "INSERT INTO inovekia_evaluacion VALUES(null, :id_consultor, :id_empresario, :calificacion, :id_curso, 1, :creado, :modificado);";
                $parametros = array("id_consultor" => $consultor, 
                                    "id_empresario" => $post["id_empresario"], 
                                    "calificacion" => $post["calificacion"],
                                    "id_curso" => $post["id_curso"],
                                    "creado" => date("Y-m-d H:i:s"), 
                                    "modificado" => date("Y-m-d H:i:s"));

                $insertar_resultado = $this->queryArray($insertar, $parametros);
                return $insertar_resultado;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function guardarSeguimientoEspecial($consultor, $seguimiento){
            try{
                $insertar = "INSERT INTO inovekia_seguimiento VALUES(null, :consultor, :empresario, :curso, :ultimo_slide, :scorm, :latitud, :longitud, :creado, :modificado, 1);";
                $parametros = array("consultor" => $consultor, "empresario" => $seguimiento["id_empresario"], "curso" => $seguimiento["id_curso"], "ultimo_slide" =>$seguimiento["ultimo_slide"], "scorm" => json_encode($seguimiento["seguimiento"]), "latitud" => $seguimiento["latitud"], "longitud" => $seguimiento["longitud"], "creado" => date("Y-m-d H:i:s"), "modificado" => date("Y-m-d H:i:s"));
                $insertar_resultado = $this->queryArray($insertar, $parametros);
                return $insertar_resultado["status"];
            }catch(Exception $e){
                return false;
            }
        }

         public function guardarSeguimientos($consultor, $seguimientos){
            try{
                foreach ($seguimientos as $seguimiento) {
                    //print_r($seguimiento["latitud"]);
                    if(!$this->guardarSeguimientoEspecial($consultor, $seguimiento)){
                        return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => "no fue posible insertar algun registro");
                    }
                }
                return array("status" => true, "rows" => array(), "insertId" => 0, "mensaje" => "Datos Guardados");
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

    }

?>
