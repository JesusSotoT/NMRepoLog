<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_api_sqli_manual.php"); // funciones mySQLi

    class UsuarioModel extends Connectionapi
    {
        private $Seguridad;

        function __construct($seguridad)
        {
            $this->Seguridad = $seguridad;
        }

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

        public function iniciarSesionUsuario($dispositivo, $usuario, $contrasena){
            $servidor = explode("/", $_SERVER['REQUEST_URI']);
            $cliente = $servidor[array_search('clientes', $servidor) + 1];
            
            $login1 = $this->Seguridad->validaUsuarioAuth($cliente, $usuario, $contrasena);
            $login = $this->Seguridad->validaUsuario($usuario, $contrasena);
            if($login["status"]){
                $token = $this->Seguridad->generaToken($dispositivo, $login["rows"][0]["idempleado"]);
                unset($login["rows"][0]["idempleado"]);
                unset($login["fields"]);
                $login["rows"][0]["token"] = $token;
            }
            return $login;
        }

        public function terminarSesionUsuario(){
            $login = $this->Seguridad->terminaUsuario();
            return $login;
        }

        public function obtenerUsuario($filtros, $parametros){
            try{
                $query = '  SELECT e.idempleado, e.nombre, e.apellido1, e.apellido2, u.usuario, au.correoelectronico, au.idSuc, up.idperfil FROM empleados AS e 
                            INNER JOIN accelog_usuarios AS u ON u.idempleado = e.idempleado 
                            INNER JOIN administracion_usuarios AS au ON au.idempleado = u.idempleado 
                            INNER JOIN accelog_usuarios_per AS up ON up.idempleado = au.idempleado 
                            WHERE '. $filtros .' AND e.visible = -1 AND (up.idperfil = 2 OR up.idperfil = 3) 
                            ORDER BY e.nombre ASC;';
                $result = $this->queryArray($query, $parametros);
                return $result;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function agregarUsuario($nombre, $apellido_1, $apellido_2, $usuario, $contrasena, $perfil, $email, $sucursal){
            try{
                $verifica = $this->obtenerUsuario("u.usuario = '". $usuario ."' OR au.correoelectronico = '". $email ."'");
                if(!$verifica["status"]) throw new Exception("Error", 601);
                if($verifica["total"] == 0){
                    $seguridad = new Seguridad();
                    $administrador = ($perfil == 2) ? 0 : -1;
                    $puesto = ($perfil == 2) ? 2 : 1;

                    $estatus = false;
                    $this->connection->beginTransaction();
                    try {
                        $queryUsuario = "   INSERT INTO empleados (idempleado, nombre, apellido1, apellido2, idorganizacion, visible, administrador) VALUES ";
                        $queryUsuario .= "  (null, :nombre, :apellido_1, :apellido_2, 1, -1, :administrador);";
                        $parametros = array("nombre" => $nombre, "apellido_1" => $apellido_1, "apellido_2" => $apellido_2, "administrador" => $administrador);
                        $result = $this->queryArray($queryUsuario, $parametros);
                        if(!$result["status"]) throw new Exception("Empleado no insertado", 1);
                        $empleado_id = $result["insertId"];
                        
                        $queryUsuario = "  INSERT INTO accelog_usuarios (idempleado, usuario, clave, css) VALUES ";
                        $queryUsuario .= "  (:empleado_id, :usuario, :contrasena, 'default');";
                        $parametros = array("empleado_id" => $empleado_id, "usuario" => $usuario, "contrasena" => $seguridad->encriptar($contrasena));
                        $result = $this->queryArray($queryUsuario, $parametros);
                        if(!$result["status"]) throw new Exception("Usuario no insertado", 2);

                        $queryUsuario = "  INSERT INTO administracion_usuarios (idadmin, nombre, apellidos, nombreusuario, correoelectronico, idperfil, idempleado, idorganizacion, idpuesto, idSuc) VALUES ";
                        $queryUsuario .= "  (null, :nombre, :apellidos, :usuario, :email, :perfil, :empleado_id, 1, :puesto, :sucursal);";
                        $parametros = array("nombre" => $nombre, "apellidos" => $apellido_1 . " " . $apellido_2, "usuario" => $usuario, "email" => $email, "perfil" => $perfil, "empleado_id" => $empleado_id, "puesto" => $puesto, "sucursal" => $sucursal);
                        $result = $this->queryArray($queryUsuario, $parametros);
                        if(!$result["status"]) throw new Exception("Administracion no insertada", 3);

                        $queryUsuario = "  INSERT INTO accelog_usuarios_per (idperfil, idempleado) VALUES (:perfil, :empleado_id);";
                        $parametros = array("perfil" => $perfil, "empleado_id" => $empleado_id);
                        $result = $this->queryArray($queryUsuario, $parametros);
                        if(!$result["status"]) throw new Exception("Permisos no insertados", 4);

                        $this->connection->commit();
                        $estatus = true;
                    } catch (Exception $e) {
                        $this->connection->rollBack();
                        echo $e->getMessage(); exit();
                    }

                    $Usuario = $this->obtenerUsuario("u.usuario = '". $usuario ."'");
                    $rowUsuario = $Usuario["rows"][0];

                    return array("status" => $estatus, "rows" => array(), "insertId" => $rowUsuario["idempleado"]);
                }else{
                    return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Usuario o email ya han sido registrados");
                }
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function editarUsuario($id, $nombre, $apellido_1, $apellido_2, $usuario, $contrasena, $perfil, $email, $sucursal){
            try{
                $verifica = $this->obtenerUsuario("(u.usuario = '". $usuario ."' OR au.correoelectronico = '". $email ."') AND e.idempleado != ". $id);
                if(!$verifica["status"]) throw new Exception("Error", 601);
                if($verifica["total"] == 0){
                    $seguridad = new Seguridad();
                    $administrador = ($perfil == 2) ? 0 : -1;
                    $puesto = ($perfil == 2) ? 2 : 1;

                    $estatus = false;
                    $this->connection->beginTransaction();
                    try{
                    $queryUsuario = "UPDATE empleados SET nombre = :nombre, apellido1 = :apellido_1, apellido2 = :apellido_2, administrador = :administrador WHERE idempleado = :id;";
                   
                    $parametros = array("nombre" => $nombre, 
                                        "apellido_1" => $apellido_1, 
                                        "apellido_2" => $apellido_2, 
                                        "administrador" => $administrador,
                                        'id' => $id
                                        );
                        $result = $this->queryArray($queryUsuario, $parametros);
                        if(!$result["status"]) throw new Exception("Empleado no modificado", 1);
                    
                    if($contrasena != null){
                        $queryUsuario = "  UPDATE accelog_usuarios SET usuario = :usuario, clave = :seguridad WHERE idempleado = :id;";
                        $parametros = array(
                                        'usuario' => $usuario,
                                        'seguridad' => $$seguridad->encriptar($contrasena),
                                        'id' => $id
                                        );
                        $result = $this->queryArray($queryUsuario, $parametros);
                        if(!$result["status"]) throw new Exception("Usuario no modificado", 2);
                    }
                    else{
                        $queryUsuario = "  UPDATE accelog_usuarios SET usuario = '". $usuario ."' WHERE idempleado = ". $id .";";
                        $parametros = array(
                                        'usuario' => $usuario,
                                        'id' => $id
                                        );
                        $result = $this->queryArray($queryUsuario, $parametros);
                        if(!$result["status"]) throw new Exception("Usuario no modificado", 2);
                    }

                    $queryUsuario = "  UPDATE administracion_usuarios SET nombre = :nombre, apellidos = :apellidos, nombreusuario = :usuario, ";
                    $queryUsuario .= "  correoelectronico = :email, idperfil = :perfil, idpuesto = :puesto, idSuc = :sucursal WHERE idempleado = :id;";

                        $parametros  = array(
                                    'nombre' => $nombre,
                                    'apellidos' => $apellido_1." ".$apellido_2,
                                    'usuario' => $usuario,
                                    'email' => $email,
                                    'perfil' => $perfil,
                                    'puesto' => $puesto,
                                    'sucursal' => $sucursal,
                                    'id' => $id
                                    );

                        $result = $this->queryArray($queryUsuario, $parametros);
                        if(!$result["status"]) throw new Exception("Administracion no modificados", 3);

                    $queryUsuario = "  UPDATE accelog_usuarios_per SET idperfil = :perfil WHERE idempleado = :id;";
                    $parametros = array(
                                    "perfil" => $perfil, 
                                    "id" => $id
                                    );
                        $result = $this->queryArray($queryUsuario, $parametros);
                        if(!$result["status"]) throw new Exception("Permisos no modificados", 4);

                        $this->connection->commit();
                        $estatus = true;
                }catch (Exception $e) {
                        $this->connection->rollBack();
                        echo $e->getMessage(); exit();
                    }


                    return array("status" => $estatus, "rows" => array(), "insertId" => 0);
                }else{
                    return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Usuario o email ya han sido registrados");
                }
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible editar la información", "error" => $e->getMessage());
            }
        }

        public function eliminarUsuario($id){
            try{
                $sel = 'UPDATE empleados set visible = 0 where idempleado = :id';
                $res = $this->queryArray($sel, array('id' => $id));
                return  $res;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible eliminar la información", "error" => $e->getMessage());
            }
        }

    }
?>
