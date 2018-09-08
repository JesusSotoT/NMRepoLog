<?php

	//Cargar la clase de conexión padre para el modelo
    require_once("models/pdo_connection.php");
	//Cargar los archivos necesarios
    require_once("libraries/input.php");

	class SeguridadFatherModel {

		public static $ERROR_GLOBAL = array("status" => false, "mensaje" => "No se ha podido completar la acción, intentalo nuevamente");
		public static $TOKEN_INVALIDO = array("status" => false, "mensaje" => "La sesión ha expirado", "logout" => true);
		public static $SIN_PERMISO = array("status" => false, "mensaje" => "No cuentas con permisos para realizar esta acción", "logout" => true);
		public static $CONTROLADOR_INEXISTENTE = array("status" => false, "mensaje" => "El controlador al que se esta tratando de acceder, no existe.");
		public static $FUNCION_INEXISTENTE = array("status" => false, "mensaje" => "La funcion a la que se esta tratando de acceder, no existe.");

		function __construct()
    	{

    	}

    	function __destruct()
    	{

    	}

		//Validar que los parámetros entrantes en las peticiones cumplan con los filtros de seguridad
		function validaParametros($parametros, $validaciones)
		{
			foreach ($validaciones as $index => $validacion) {
				if(($validacion["tipo"] == "imagen" || $validacion["tipo"] == "video") && !Input::tieneArchivo($index) && !$validacion["nulo"]){
					return array("status" => false, "mensaje" => "El parámetro ::". $index .":: no puede ser vació o nulo");
				}else if(($validacion["tipo"] != "imagen" && $validacion["tipo"] != "video") && ((!array_key_exists($index, $parametros) && !$validacion["nulo"]) || (array_key_exists($index, $parametros) && $parametros[$index] == "" && !$validacion["vacio"]))){
					return array("status" => false, "mensaje" => "El parámetro ::". $index .":: no puede ser vació o nulo");
				}else if(!array_key_exists($index, $parametros) || Input::tieneArchivo($index)){
					$error = array("status" => false, "mensaje" => "El parámetro ::". $index .":: debe ser de tipo ::". $validacion["tipo"] ."::");
					switch ($validacion["tipo"]) {
						case 'entero':
							if(!preg_match('/^[0-9]*$/', $parametros[$index])) return $error;
							break;
						case 'decimal':
							if(!preg_match('/^-?(?:\d+|\d*\.\d+)$/', $parametros[$index])) return $error;
							break;
						case 'json':
							if(	preg_match('/^[0-9]*$/', $parametros[$index]) ||
								preg_match('/^-?(?:\d+|\d*\.\d+)$/', $parametros[$index]) ||
								!$this->validarJSON($parametros[$index]))
								return $error;
							break;
						case 'imagen':
							if(!Input::esImagen($index)) return $error;
							break;
						case 'video':
							if(!Input::esVideo($index)) return $error;
							break;
					}
				}
			}
			return true;
		}

		//Determinar si un string es un json valido
		protected function validarJSON($json)
		{
            $json = json_decode($json, true);
            if(json_last_error() === JSON_ERROR_NONE) return true;
            return false;
        }

	}

?>
