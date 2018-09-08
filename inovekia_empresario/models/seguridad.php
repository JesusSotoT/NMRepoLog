<?php
	
	//Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi
    require_once("auth/nusoap.php");
    require_once("libraries/input.php");

	class Seguridad extends Connection {

		public $Usuario;
		public $Sesion;

		function fencripta($pwd, $salt){
			$resultado = crypt($pwd,$salt);
			return $resultado;
		}

		function encriptar($texto){
			require('webconfig.php');
			return $this->fencripta($texto, $accelog_salt);
		}

		function validaParametros($parametros, $validaciones){
			foreach ($validaciones as $index => $validacion) {
				if(($validacion["tipo"] == "imagen" || $validacion["tipo"] == "video") && !Input::tieneArchivo($index) && !$validacion["nulo"]){
					return array("status" => false, "mensaje" => "El parametro ::". $index .":: no puede ser vacio o nulo");
				}else if(($validacion["tipo"] != "imagen" && $validacion["tipo"] != "video") && ((!array_key_exists($index, $parametros) && !$validacion["nulo"]) || (!array_key_exists($index, $parametros) && $parametros[$index] == "" && !$validacion["vacio"]))){
					return array("status" => false, "mensaje" => "El parametro ::". $index .":: no puede ser vacio o nulo");
				}else if(!array_key_exists($index, $parametros) || Input::tieneArchivo($index)){
					$error = array("status" => false, "mensaje" => "El parametro ::". $index .":: debe ser de tipo ::". $validacion["tipo"] ."::");
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

		private function validarJSON($json){
            $json = json_decode($json, true);
            if(json_last_error() === JSON_ERROR_NONE) return true;
            return false;
        }

		function generaToken($dispositivo, $usuario){
			date_default_timezone_set("Mexico/General");
			$inicio = date("Y-m-d H:i:s");
            $fin = date("Y-m-d H:i:s", strtotime($inicio ." + 30 minutes"));
			$texto = $fin ."::". $usuario ."://". $inicio ."$%;". substr($dispositivo, 0, 10);
			$salt = substr(base64_encode(openssl_random_pseudo_bytes('30')), 0, 25);
			$salt = strtr($salt, array('+' => '.'));
			$token = crypt($texto, '$2y$10$'. $salt);

			$consulta = " SELECT id FROM api_token_inovekia WHERE token = :token;";
			$resultados = $this->queryArray($consulta, array("token" => $token));
			if($resultados["total"] > 0){
				return $this->generaToken($dispositivo, $usuario, $inicio, $fin);
			}else{
				$consulta = " UPDATE api_token_inovekia SET activo = 0 WHERE dispositivo = :dispositivo AND activo = 1;";
				$resultados = $this->queryArray($consulta, array("dispositivo" => $dispositivo));
				if(!$resultados["status"]) return $this->generaToken($dispositivo, $usuario, $inicio, $fin);

				$consulta = " INSERT INTO api_token_inovekia (id, id_empleado, dispositivo, token, inicio, fin) VALUES ";
				$consulta .= " (null, :usuario, :dispositivo, :token, :inicio, :fin);";
				$parametros = array(
					"usuario" => $usuario,
					"dispositivo" => $dispositivo,
					"token" => $token,
					"inicio" => $inicio,
					"fin" => $fin
					);
				$resultados = $this->queryArray($consulta, $parametros);
				if(!$resultados["status"]) return $this->generaToken($dispositivo, $usuario, $inicio, $fin);
			}

			return $token;
		}

		function generaTokenGenerico($dispositivo){
			date_default_timezone_set("Mexico/General");
			$fecha = date("Y-m-d H:i:s");
			$texto = $fecha ."$%;". substr($dispositivo, 0, 10);
			$salt = substr(base64_encode(openssl_random_pseudo_bytes('30')), 0, 25);
			$salt = strtr($salt, array('+' => '.'));
			$token = crypt($texto, '$2y$10$'. $salt);

			return $token;
		}

		function validaToken($dispositivo, $token){
			$consulta = " SELECT id, id_empleado AS empleado, dispositivo, inicio, fin FROM api_token_inovekia WHERE token = :token AND activo = 1;";
			$resultados = $this->queryArray($consulta, array("token" => $token));
			if($resultados["total"] == 1){
				$registro = $resultados["rows"][0];
				$texto = $registro["fin"] ."::". $registro["empleado"] ."://". $registro["inicio"] ."$%;". substr($registro["dispositivo"], 0, 10);
				if(crypt($texto, $token) == $token){
					date_default_timezone_set("Mexico/General");
					$nuevo_token = null;
					if(strtotime($registro["fin"]) < strtotime(date("Y-m-d H:i:s"))){
						$consulta = " UPDATE api_token_inovekia SET activo = 0 WHERE id = :id;";
						$resultados = $this->queryArray($consulta, array("id" => $registro["id"]));
						if(!$resultados["status"]) return $this->validaToken($dispositivo, $token);
						if($dispositivo == $registro["dispositivo"]){
							$nuevo_token = $this->generaToken($dispositivo, $registro["empleado"]);
						}
						if(is_null($nuevo_token)) return false;
					}
					$this->Usuario = $registro["empleado"];
					$this->Sesion = $registro["id"];
					return $nuevo_token;
				}
			}
			return false;
		}

		function tokenInvalido(){
			return array("status" => false, "mensaje" => "La sesión ha expirado");
		}

		function validaUsuarioAuth($instancia, $usuario, $contrasena){
			$objClient = new nusoap_client("http://wsserver.netwarmonitor.com/auth.php?wsdl");
            $objError = $objClient->getError();
            if ($objError) {
            	
       		}else{
				$strResult = $objClient->call("getAuthUrl", array("strInstance"=>$instancia,"strUser"=>$usuario,"strPassword"=>$contrasena));
				return $strResult;
       		}
		}

		function validaUsuario($usuario, $contrasena, $id = null){
			require('webconfig.php');
			$contrasena = $this->fencripta($contrasena, $accelog_salt);
			$usuario = str_replace("'", "", $usuario);
			$usuario = str_replace("=", "", $usuario);
			$usuario = str_replace("\\", "", $usuario);

			$query = '  SELECT e.idempleado, u.clave, au.idSuc, up.idperfil, au.correoelectronico FROM empleados AS e 
                            INNER JOIN accelog_usuarios AS u ON u.idempleado = e.idempleado 
                            INNER JOIN administracion_usuarios AS au ON au.idempleado = u.idempleado 
                            INNER JOIN accelog_usuarios_per AS up ON up.idempleado = au.idempleado ';
                            if($id == null){
                            	$query .= 'WHERE u.usuario = :usuario AND e.visible = -1 ';
                            	$parametros = array("usuario" => $usuario);
                            }
                            else{
                            	$query .= 'WHERE e.idempleado = :id AND e.visible = -1 AND (up.idperfil = 2 OR up.idperfil = 3) ';
                            	$parametros = array("id" => $id);
                            }
                            $query .= 'LIMIT 1;';
            $result = $this->queryArray($query, $parametros);
            
            $incorrecto = array("status" => false, "total" => 0, "rows" => array(), "insertId" => 0, "mensaje" => "Usuario o contraseña incorrectos");
            if($result["total"] == 1){
            	if($result["rows"][0]["clave"] != $contrasena){
            		$result = $incorrecto;
            	}
            	unset($result["rows"][0]["clave"]);
            }else{
            	$result = $incorrecto;
            }
            return $result;
		}

		function terminaUsuario($token){
			$consulta = " UPDATE api_token_inovekia SET activo = 0 WHERE id = ". $this->Sesion .";";
			$resultados = $this->queryArray($consulta);
			return array("status" => $resultados["status"]);
		}

	}

?>