<?php

	/*
		Clase que procesa todas las peticiones a la base de datos.
	*/

	class DB
	{
		public static $conexion;

        //Convertir un objeto en un array
        public function object_to_array($datos) {
            if (is_array($datos) || is_object($datos)) {
                $resultado = array();
                foreach ($datos as $llave => $contenido) {
                    $resultado[$llave] = self::object_to_array($contenido);
                }
                return $resultado;
            }
            return $datos;
        }

		//Conectar a la base de datos
		public static function conectar()
		{
			//Cargar los datos de configuración de las conexiones					
			require("config/data_base_connection.php");

			try {
				if(!isset(self::$conexion)){
	                self::$conexion = new PDO('mysql:host='. $wc_servidor .';port='. $wc_puerto .';dbname='. $wc_base_datos .';charset=utf8', $wc_usuario, $wc_clave);
	                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	            }
            } catch (PDOException $e) {
            	$error = SeguridadModel::$ERROR_GLOBAL;
            	$error["codigo"] = $e->getMessage();
            	echo json_encode($error);
            	exit();
            }
		}

		//Cerrar la conexión a la base de datos
		public function cerrar()
		{
			self::$conexion = null;
		}

		/*
			Realizar una consulta a la base de datos
			Parámetro $sql: Consulta sql para preparación en PDO
			Parámetro $arg: Array que contiene los valores asignados a la preparación del PDO
		*/
		private static function query($sql, $args)
		{
			$resultado = null;
			try{
				$resultado = self::$conexion->prepare($sql);
	            $resultado->execute($args);
	        }catch(PDOException $e){
	        	$resultado = $e->errorInfo[2] ."||". $e->errorInfo[1];
	        }
			return $resultado;
		}
		
		/*
			Realizar una consulta a la base de datos con retorno en forma de array
			Parámetro $sql: Consulta sql para preparación en PDO
			Parámetro $arg: Array que contiene los valores asignados a la preparación del PDO
			Parámetro $relational: Indicar como verdadero si se creara un array relacional
		*/
        public static function queryArray($sql, $args, $relacional = true)
        {
            try{
            	self::conectar();

                if(empty($sql)) throw new Exception("Sin sentencia SQL");
              
                $resultado = self::query($sql, $args);

                if (!$resultado || gettype($resultado) == "string"){
                	$error = explode("||", $resultado);
                    return array("status"=>false, "total" => $sql, "codigo" => $error[1], "msg"=> $error[0]);
                }

                $filas_afectadas = $resultado->rowCount();

                $campos = array();
                while ($informacion = @$resultado->getColumnMeta()) {
                    $campos[] = $informacion->name;
                }

                $filas = array();

                if(stristr($sql, "select")){
	                if  ($relacional) {
	                    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)){
	                        $filas[] = $fila;
	                    }
	                }else {
	                    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
	                        foreach ($fila as $llave => $contenido){
	                            $filas[$llave][] = $contenido;
	                        }
	                    }
	                }
	            }

                $id_insertado = self::$conexion->lastInsertId();
                
                return array("status" => true, "total" =>  $filas_afectadas, "registros" => $filas, "id_insertado" => $id_insertado);
            }catch(Exception $e){
                return array("status" => false, "msg" => $e->getMessage());
            }
        }
        
	}
?>
