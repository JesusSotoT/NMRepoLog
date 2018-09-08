<?php
	
	//Cargar la clase padre para el modelo
    require_once("models/seguridad_father.php");
    //Cargar los archivos necesarios
    require_once("models/com_actividades.php");
    require_once("models/com_configuracion.php");
    require_once("models/administracion_usuarios.php");

	class SeguridadModel extends SeguridadFatherModel 
	{

		public $Usuario;
		public $Sesion;
		public $Sucursal;
		public $LoginComandera;

		function encriptar($cadena, $salt){
			global $accelog_salt;
			return crypt($cadena, $salt);
		}

		public function login($usuario, $contrasena, $id = null, $dispositivo, $push)
		{
			$validacion = array("status" => false, "mensaje" => "Usuario o contraseña incorrectos");
			$validar_usuario = $this->validaUsuario($usuario, $contrasena, $id);
			if($validar_usuario["status"]){
				$validar_usuario = $validar_usuario["registros"][0];
                $token = $this->generaToken($dispositivo, $validar_usuario["idempleado"], $push);
                unset($validar_usuario["idempleado"]);
                unset($validacion["mensaje"]);
                $validar_usuario["token"] = $validacion["token"] = $token;
                $validacion["registros"] = array();
                $validacion["status"] = true;

                if (!isset($_SESSION)) session_start();
                $_SESSION['f_ini'] = date('Y-m-d') . ' 00:01';
            	$_SESSION['f_fin'] = date('Y-m-d') . ' 23:59';
            }
            return $validacion;
		}

		public function logout()
		{
			$consulta = "UPDATE api_token_foodware_nativo SET activo = 0 WHERE id = :id;";
			$resultado = DB::queryArray($consulta, array("id" => self::$Sesion));
			return array("status" => $resultado["status"]);
		}

		public function logueado(&$renovar)
        {
            if(isset($_REQUEST["dispositivo"]) && isset($_REQUEST["llave"])){
                $seguridad = $this->validaToken($_REQUEST["dispositivo"], $_REQUEST["llave"]);
                if(is_array($seguridad)){
                    $renovar = (is_null($seguridad["token"])) ? null : $seguridad["token"];
                    return true;
                }
            }
            return false;
        }

		public function iniciarSesionMesero($empleado, $contrasena)
		{
			global $accelog_salt;
			$consulta = '';
			$parametros = array();
			// Valida si se debe de pedir el pass o no
			if ($this->LoginComandera != 2){
				$consulta .= " AND u.clave = :contrasena";
				$parametros["contrasena"] = $this->encriptar($contrasena, $accelog_salt);
			}
			$consulta = "SELECT u.idempleado AS id, usuario, permisos, p.idperfil AS perfil
						FROM accelog_usuarios u
						INNER JOIN administracion_usuarios a ON u.idempleado = a.idempleado
						LEFT JOIN com_meseros m ON m.id_mesero = u.idempleado
						LEFT JOIN accelog_usuarios_per p ON p.idempleado = u.idempleado 
						WHERE u.idempleado = :empleado" . $consulta;
						$parametros["empleado"] = $empleado;
			$resultado = DB::queryArray($consulta, $parametros);

			if($resultado["status"] && $resultado["total"] == 1){
				$log = new ComActividadesModel();
				$log->empleado = $empleado;
				$log->accion = 'Inicia sesion';
				$log->fecha = date('Y-m-d H:i:s');
				$log->id_sucursal = $this->Sucursal;
				$log->guardar();
			}else{
				$resultado["status"] = false;
				$resultado["mensaje"] = "Usuario o contraseña incorrectos";
			}

			return $resultado;

		}

		private function validaUsuario($usuario, $contrasena, $id = null){
			$usuario = utf8_encode($usuario);
			global $accelog_salt;
			$contrasena = $this->encriptar($contrasena, $accelog_salt);
			$usuario = str_replace("'", "", $usuario);
			$usuario = str_replace("=", "", $usuario);
			$usuario = str_replace("\\", "", $usuario);

			$consulta = 'SELECT e.idempleado, u.clave, au.idSuc, up.idperfil, au.correoelectronico FROM empleados AS e 
                        INNER JOIN accelog_usuarios AS u ON u.idempleado = e.idempleado 
                        INNER JOIN administracion_usuarios AS au ON au.idempleado = u.idempleado 
                        INNER JOIN accelog_usuarios_per AS up ON up.idempleado = au.idempleado ';
                        if($id == null){
                        	$consulta .= 'WHERE u.usuario = :usuario AND e.visible = -1 ';
                        	$parametros = array("usuario" => $usuario);
                        }
                        else{
                        	$consulta .= 'WHERE e.idempleado = :id AND e.visible = -1 ';
                        	$parametros = array("id" => $id);
                        }
                        $consulta .= 'LIMIT 1;';
            $resultado = DB::queryArray($consulta, $parametros);
            
            $incorrecto = array("status" => false, "mensaje" => "Usuario o contraseña incorrectos");
            if($resultado["total"] == 1){
            	if($resultado["registros"][0]["clave"] != $contrasena){
            		$resultado = $incorrecto;
            	}
            	unset($resultado["rows"][0]["clave"]);
            }else{
            	$resultado = $incorrecto;
            }
            return $resultado;
		}

		public function obtenerAjustes(){
			$configuracion = ComConfiguracionModel::buscar()[0];
			return array("status" => true, "registros" => array(array("propina" => $configuracion->propina, "tipo_operacion" => $configuracion->tipo_operacion, "pedir_pass" => $configuracion->pedir_pass)));
		}

		private function generaToken($dispositivo, $usuario, $push){
			date_default_timezone_set("Mexico/General");
			$inicio = date("Y-m-d H:i:s");
            $fin = date("Y-m-d H:i:s", strtotime($inicio ." + 30 minutes"));
			$texto = $fin ."::". $usuario ."://". $inicio ."$%;". substr($dispositivo, 0, 10);
			$salt = substr(base64_encode(openssl_random_pseudo_bytes('30')), 0, 25);
			$salt = strtr($salt, array('+' => '.'));
			$token = crypt($texto, '$2y$10$'. $salt);

			$consulta = "SELECT id FROM api_token_foodware_nativo WHERE token = :token;";
			$resultados = DB::queryArray($consulta, array("token" => $token));
			if($resultados["total"] > 0){
				return $this->generaToken($dispositivo, $usuario, $inicio, $fin);
			}else{
				$consulta = " UPDATE api_token_foodware_nativo SET activo = 0 WHERE dispositivo = :dispositivo AND activo = 1;";
				$resultados = DB::queryArray($consulta, array("dispositivo" => $dispositivo));
				if(!$resultados["status"]) return $this->generaToken($dispositivo, $usuario, $inicio, $fin);

				$consulta = " INSERT INTO api_token_foodware_nativo (id, id_empleado, dispositivo, token, push, inicio, fin) VALUES ";
				$consulta .= " (null, :usuario, :dispositivo, :token, :push, :inicio, :fin);";
				$parametros = array(
					"usuario" => $usuario,
					"dispositivo" => $dispositivo,
					"token" => $token,
					"push" => $push,
					"inicio" => $inicio,
					"fin" => $fin
					);
				$resultados = DB::queryArray($consulta, $parametros);
				if(!$resultados["status"]) return $this->generaToken($dispositivo, $usuario, $inicio, $fin);
			}

			return $token;
		}

		private function validaToken($dispositivo, $token){
			$consulta = " SELECT id, id_empleado AS empleado, dispositivo, inicio, fin FROM api_token_foodware_nativo WHERE token = :token AND activo = 1;";
			$resultados = DB::queryArray($consulta, array("token" => $token));
			if($resultados["total"] == 1){
				$registro = $resultados["registros"][0];
				$texto = $registro["fin"] ."::". $registro["empleado"] ."://". $registro["inicio"] ."$%;". substr($registro["dispositivo"], 0, 10);
				if(crypt($texto, $token) == $token){
					date_default_timezone_set("Mexico/General");
					$nuevo_token = null;
					if(strtotime($registro["fin"]) < strtotime(date("Y-m-d H:i:s"))){
						$consulta = " UPDATE api_token_foodware_nativo SET activo = 0 WHERE id = :id;";
						$resultados = DB::queryArray($consulta, array("id" => $registro["id"]));
						if(!$resultados["status"]) return $this->validaToken($dispositivo, $token);
						if($dispositivo == $registro["dispositivo"]){
							$nuevo_token = $this->generaToken($dispositivo, $registro["empleado"]);
						}
						if(is_null($nuevo_token)) return false;
					}
					@$configuracion = ComConfiguracionModel::buscar()[0];
					$this->LoginComandera = (is_object($configuracion)) ? $configuracion->pedir_pass : null;
					$this->Usuario = $registro["empleado"];
					$this->Sesion = $registro["id"];
					$this->Sucursal = AdministracionUsuariosModel::buscar("idempleado = :empleado", array("empleado" => $this->Usuario))[0]->idSuc;
					return array("token" => $nuevo_token);
				}
			}
			return false;
		}

	}

?>