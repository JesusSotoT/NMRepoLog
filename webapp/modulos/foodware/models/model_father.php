<?php

	//Cargar la clase de conexión padre para el modelo
    require_once("models/pdo_connection.php");
    //Cargar la estructura de la base de datos para el modelo
    require_once("config/data_base_definition.php");
    //Cargar la estructura de la base de datos para el modelo
    require_once("libraries/input.php");

    class Model
    {
        protected $id = null;
    	protected $creado = null;
    	protected $modificado = null;

        private $clase = null;
        private $estructura_db = null;
        private $estructura_db_columnas = null;
        private $estructura_db_columnas_relacional = null;
        private $estructura_db_columnas_relacional_combinada = null;
        private $columna_identificadora = null;
        private $valores_estructura_db_auxiliar = null;

        public $activo = null;

    	function __construct($id, $constructor = true)
    	{
            global $db;
            $this->id = $id;
            $this->clase = self::obtenerClase();
            $this->estructura_db = $db[$this->clase];
            $this->columna_identificadora = self::obtenerColumnaIdentificadora();
            $this->estructura_db_columnas = $this->obtenerColumnas();
            $this->estructura_db_columnas_relacional = $this->obtenerColumnas(true);
            $this->estructura_db_columnas_relacional_combinada = $this->obtenerColumnas(true, true);
            $this->estructura_db_columnas_relacional_combinada_auxiliar = str_replace(", ", " AND ", $this->obtenerColumnas(true, true, true, "aux"));
            $this->popularColumnas($constructor);
    	}

    	function __destruct()
    	{

    	}

        private static function obtenerClase()
        {
            $clase = str_replace("Model", "", get_called_class());
            preg_match_all('#([A-Z]+)#', $clase, $mayusculas);
            $mayusculas = $mayusculas[0];
            array_shift($mayusculas);
            if(count($mayusculas) > 0){
                foreach ($mayusculas as $mayuscula) {
                    $clase = str_replace($mayuscula, "_". strtolower($mayuscula), $clase);
                }
            }
            $clase = strtolower(trim($clase, "_"));
            return $clase;
        }

        private function obtenerColumnas($relacional = false, $combinar = false, $trigger = true, $auxiliar = "")
        {
            $columnas = "";
            if($relacional && !$combinar){
                foreach ($this->estructura_db as $columna => $parametro) {
                    if($columna != $this->columna_identificadora && ((isset($parametro["trigger"]) && $trigger) || !isset($parametro["trigger"]))) $columnas.= ":$columna". (($auxiliar != "") ? "_". $auxiliar : "") .", ";
                }
                
            }else if($relacional && $combinar){
                foreach ($this->estructura_db as $columna => $parametro) {
                    if($columna != $this->columna_identificadora && ((isset($parametro["trigger"]) && $trigger) || !isset($parametro["trigger"]))) $columnas.= "$columna = :$columna". (($auxiliar != "") ? "_". $auxiliar : "") .", ";
                }
            }else{
                foreach ($this->estructura_db as $columna => $parametro) {
                    if($columna != $this->columna_identificadora && ((isset($parametro["trigger"]) && $trigger) || !isset($parametro["trigger"]))) $columnas.= "$columna, ";
                }
            }
            $columnas = trim($columnas, ", ");
            return $columnas;
        }

        private static function obtenerColumnaIdentificadora()
        {
            global $db;
            $columna_identificadora = null;
            foreach ($db[self::obtenerClase()] as $columna => $parametros) {
                if(isset($parametros["id"])){
                    $columna_identificadora = $columna;
                    break;
                }
            }
            return $columna_identificadora;
        }

        private function obtenerValores($identificador = true, $trigger = true, $auxiliar = "")
        {
            $valores = array();
            foreach ($this->estructura_db as $columna => $parametros) {
                if($columna != $this->columna_identificadora){
                    if((isset($parametros["trigger"]) && $trigger) || !isset($parametros["trigger"])){
                        $valores[$columna .(($auxiliar != "") ? "_". $auxiliar : "")] = $this->$columna;
                        if(is_null($valores[$columna .(($auxiliar != "") ? "_". $auxiliar : "")]) && isset($parametros["default"])){
                            $valores[$columna .(($auxiliar != "") ? "_". $auxiliar : "")] = $parametros["default"];
                        }
                    }
                }
            }

            if(!is_null($this->id) && $identificador){
                $valores[$this->columna_identificadora .(($auxiliar != "") ? "_". $auxiliar : "")] = $this->id;
            }

            return $valores;
        }

    	public function obtenerId(){
    		return $this->id;
    	}

        public function obtenerCreado(){
            return $this->creado;
        }

        public function obtenerModificado(){
            return $this->modificado;
        }

        public static function buscar($filtros = "1=1", $args = array())
        {
            $clase = get_called_class();
            $clase_nombre = self::obtenerClase();
            $columna_identificadora = self::obtenerColumnaIdentificadora();
            $sql = "SELECT ". ((is_null($columna_identificadora)) ? "*" : $columna_identificadora) ." FROM $clase_nombre WHERE $filtros;";
            $respuesta = DB::queryArray($sql, $args);
            if(!$respuesta["status"]) throw new Exception($respuesta["msg"], 3);
            $resultados = array();
            foreach ($respuesta["registros"] as $fila) {
                if(!is_null($columna_identificadora)){
                    $resultados[] = new $clase($fila[$columna_identificadora]);
                } else {
                    $registro = new $clase(null, false);
                    $registro->convertirDesdeArray($fila, true);
                    $resultados[] = $registro;
                }
            }
            return $resultados;
        }

        public function convertirArray($id = false, $fechas = false, $activo = false)
        {
            $array = $this->obtenerValores();
            if(!$id) unset($array[$this->columna_identificadora]);
            if(!$fechas){
                unset($array["creado"]);
                unset($array["modificado"]);
            }
            if(!$activo) unset($array["activo"]);
            return $array;
        }

        public function convertirDesdeArray($array, $auxiliar = false)
        {
            foreach($array as $columna => $valor){
                $this->{$columna} = $valor;
            }
            if($auxiliar) $this->valores_estructura_db_auxiliar = $this->obtenerValores(true, true, "aux");
        }

        private function triggers()
        {
            $trigger = false;
            foreach ($this->estructura_db as $columna => $parametros) {
                if(isset($parametros["trigger"]) && !is_null($this->$columna)){
                    $trigger = true;
                }
            }
            return $trigger;
        }

    	public function guardar()
    	{
            if(is_null($this->id) && !$this->triggers()){
                $sql = "INSERT INTO $this->clase ($this->estructura_db_columnas) VALUES($this->estructura_db_columnas_relacional);";
                $parametros = $this->obtenerValores(true);
            }else{
                if(!is_null($this->columna_identificadora)){
                    $sql = "UPDATE $this->clase SET $this->estructura_db_columnas_relacional_combinada WHERE $this->columna_identificadora = :$this->columna_identificadora;";
                    $parametros = $this->obtenerValores(true);
                } else {
                    $sql = "UPDATE $this->clase SET $this->estructura_db_columnas_relacional_combinada WHERE $this->estructura_db_columnas_relacional_combinada_auxiliar;";
                    $parametros = array_merge($this->obtenerValores(true), $this->valores_estructura_db_auxiliar);
                }
            }
            $respuesta = DB::queryArray($sql, $parametros);
            if(!$respuesta["status"]){
                $mensaje = "No se ha podido guardar la información, intentalo nuevamente";
                if($respuesta["codigo"] == 1062){
                    $mensaje = "El campo <b>". explode("'", $respuesta["msg"])[3] ."</b> ya se encuentra registrado, favor de verificarlo";
                }
                throw new Exception($mensaje, $respuesta["codigo"]);
            }
            $this->id = (is_null($this->id) && !is_null($this->columna_identificadora)) ? $respuesta["id_insertado"] : $this->id;
            $this->popularColumnas();
    	}

        public function eliminar()
        {
            if(is_null($this->id) && !$this->triggers()) throw new Exception("No es posible eliminar un objeto que no existe", 2);
            if(!is_null($this->columna_identificadora)){
                $sql = "DELETE FROM $this->clase WHERE $this->columna_identificadora = :id;";
                $respuesta = DB::queryArray($sql, array("id" => $this->id));
            } else {
                $sql = "DELETE FROM $this->clase WHERE $this->estructura_db_columnas_relacional_combinada_auxiliar LIMIT 1;";
                $respuesta = DB::queryArray($sql, $this->valores_estructura_db_auxiliar);
            }
            if(!$respuesta["status"]){
                $mensaje = "No se ha podido guardar la información, intentalo nuevamente";
                throw new Exception($mensaje, $respuesta["codigo"]);
            }   
        }

        public function cambiarEstatus()
        {
            if(is_null($this->id)) throw new Exception("No es posible eliminar un objeto que no existe", 2);
            $this->activo = ($this->activo * -1) + 1;
            $this->guardar();        
        }

        private function popularColumnas($constructor = false){
            if(!is_null($this->id)){
                $sql = "SELECT * FROM $this->clase WHERE $this->columna_identificadora = :id;";
                $parametros = array("id" => $this->id);
                $respuesta = DB::queryArray($sql, $parametros);
                if($respuesta["total"] != 1) throw new Exception("Registro no encontrado [". $this->id ."]", 3);
                $respuesta = $respuesta["registros"][0];
                foreach ($respuesta as $columna => $valor) {
                    $this->$columna = $valor;
                }
                $this->valores_estructura_db_auxiliar = $this->obtenerValores(true, true, "aux");
            } else {
                if(!$constructor){
                    $estructura_db_columnas_selectoras_combinada = str_replace(", ", " AND ", $this->obtenerColumnas(true, true, false));
                    $sql = "SELECT * FROM $this->clase WHERE $estructura_db_columnas_selectoras_combinada;";
                    $respuesta = DB::queryArray($sql, $this->obtenerValores(true, false));
                    if($respuesta["total"] < 1) throw new Exception("Registro no encontrado [-]", 3);
                    $respuesta = $respuesta["registros"][count($respuesta["registros"]) - 1];
                    foreach ($respuesta as $columna => $valor) {
                        $this->$columna = $valor;
                    }
                    $this->valores_estructura_db_auxiliar = $this->obtenerValores(true, true, "aux");
                }
            }
        }
        
    }

?>
