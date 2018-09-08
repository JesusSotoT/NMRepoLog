<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class PlantillaModel extends Connection
{
   
    public function procesar(){
        try {
            require_once '../../libraries/Excel/reader.php';
            if(isset($_FILES["excel"]) && $_FILES["excel"]['error'] != 4){
                $directorio = "views/plantilla/temporal.xls";
                if (!move_uploaded_file($_FILES["excel"]["tmp_name"], $directorio)){
                    throw new Exception("No fue posible subir el archivo, intentalo nuevamente", 1);
                }
                
                $archivo = new Spreadsheet_Excel_Reader();
                $archivo->read($directorio);
                $hoja = $archivo->sheets[0];
                $filas = $hoja['numRows'];
                $columnas = $hoja['numCols'];
                for($i = 2; $i <= $filas; $i++){
                    $organismo = $hoja['cells'][$i][1];
                    $consultor = $hoja['cells'][$i][2];
                    $empresario = $hoja['cells'][$i][3];
                    $folio = $hoja['cells'][$i][4];

                    $sql_organismo = "SELECT id FROM netwarstore.inovekia_organismo WHERE nombre = '". $organismo ."';";
                    $sql_organismo = $this->queryArray($sql_organismo);
                    if($sql_organismo["total"] > 0){
                        $sql_consultor = "SELECT idempleado AS id FROM empleados WHERE nombre = '". $consultor ."';";
                        $sql_consultor = $this->queryArray($sql_consultor);
                        if($sql_consultor["total"] > 0){
                            $sql_empresario = "SELECT id FROM netwarstore.inovekia_empresario WHERE razon = '". $empresario ."';";
                            $sql_empresario = $this->queryArray($sql_empresario);
                            if($sql_empresario["total"] > 0){
                                $sql_organismo_consultor = "SELECT id FROM netwarstore.inovekia_organismo_consultor WHERE id_consultor = ". $sql_consultor["rows"][0]["id"] .";";
                                $sql_organismo_consultor = $this->queryArray($sql_organismo_consultor);
                                if($sql_organismo_consultor["total"] > 0){
                                    $sql_insertar = "UPDATE netwarstore.inovekia_organismo_consultor SET id_organismo = ". $sql_organismo["rows"][0]["id"] .", modificado = '". date("Y-m-d H:i:s") ."' WHERE id_consultor = ". $sql_consultor["rows"][0]["id"] .";";
                                }else{
                                    $sql_insertar = "INSERT IGNORE INTO netwarstore.inovekia_organismo_consultor VALUES(null, ". $sql_consultor["rows"][0]["id"] .", ". $sql_organismo["rows"][0]["id"] .", 1, '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."');";
                                }
                                $sql_insertar = $this->queryArray($sql_insertar);
                                if(!$sql_insertar["status"]){
                                    throw new Exception("No fue posible crear la relacion organismo - consultor [". $organismo . " :: " . $consultor ."]", 1);
                                } else {
                                    $sql_empresario_consultor = "SELECT id FROM netwarstore.inovekia_empresario_consultor WHERE id_empresario = ". $sql_empresario["rows"][0]["id"] .";";
                                    $sql_empresario_consultor = $this->queryArray($sql_empresario_consultor);
                                    if($sql_empresario_consultor["total"] > 0){
                                        $sql_insertar = "UPDATE netwarstore.inovekia_empresario_consultor SET id_consultor = ". $sql_consultor["rows"][0]["id"] .", modificado = '". date("Y-m-d H:i:s") ."' WHERE id_empresario = ". $sql_empresario["rows"][0]["id"] .";";
                                    }else{
                                        $sql_insertar = "INSERT IGNORE INTO netwarstore.inovekia_empresario_consultor VALUES(null, ". $sql_empresario["rows"][0]["id"] .", ". $sql_consultor["rows"][0]["id"] .", 1, '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."');";
                                    }
                                    $sql_insertar = $this->queryArray($sql_insertar);
                                    if(!$sql_insertar["status"]){
                                        throw new Exception("No fue posible crear la relacion empresario - consultor [". $empresario . " :: " . $consultor ."]", 1);
                                    } else {
                                        $sql_empresario_folio = "SELECT id FROM netwarstore.inovekia_empresario_folio WHERE id_empresario = ". $sql_empresario["rows"][0]["id"] .";";
                                        $sql_empresario_folio = $this->queryArray($sql_empresario_folio);
                                        if($sql_empresario_folio["total"] > 0){
                                            $sql_insertar = "UPDATE netwarstore.inovekia_empresario_folio SET folio = '". $folio ."', modificado = '". date("Y-m-d H:i:s") ."' WHERE id_empresario = ". $sql_empresario["rows"][0]["id"] .";";
                                        }else{
                                            $sql_insertar = "INSERT IGNORE INTO netwarstore.inovekia_empresario_folio VALUES(null, ". $sql_empresario["rows"][0]["id"] .", '". $folio ."', 1, '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."');";
                                        }
                                        $sql_insertar = $this->queryArray($sql_insertar);
                                        if(!$sql_insertar["status"]){
                                            throw new Exception("No fue posible crear la relacion empresario - folio [". $empresario . " :: " . $folio ."]", 1);
                                        }
                                    }
                                }
                            } else {
                                throw new Exception("No existe el empresario [". $empresario ."]", 1);
                            }
                        } else {
                            throw new Exception("No existe el consultor [". $consultor ."]", 1);
                        }
                    } else {
                        throw new Exception("No existe el organismo intermedio [". $organismo ."]", 1);
                    }
                }
            } else {
                throw new Exception("No se selecciono ningun archivo", 1);
            }
            $json = array("status" => true);
        } catch (Exception $e) {
            $json = array("status" => false, "mensaje" => $e->getMessage());
        }
        return $json;
    }


    public function agregarUsuario(){
        require_once '../../libraries/Excel/reader.php';

        if(isset($_FILES["excel_usuarios"]) && $_FILES["excel_usuarios"]['error'] != 4){
            $directorio = "views/plantilla/temporal_usuarios.xls";
            
            if (!move_uploaded_file($_FILES["excel_usuarios"]["tmp_name"], $directorio)){
                    throw new Exception("No fue posible subir el archivo, intentalo nuevamente", 1);
                }
            mysqli_begin_transaction($this->connection, MYSQLI_TRANS_START_READ_WRITE);

            $archivo = new Spreadsheet_Excel_Reader();
            $archivo->read($directorio);
            $hoja = $archivo->sheets[0];
            $filas = $hoja['numRows'];
            $columnas = $hoja['numCols'];

            try{
                $repetidos = array();
                for($i = 2; $i <= $filas; $i++){

                    $nombre = $hoja['cells'][$i][1]; 
                    $apellido_1 = $hoja['cells'][$i][2];
                    $apellido_2 = $hoja['cells'][$i][3]; 
                    $usuario = $hoja['cells'][$i][4]; 
                    $contrasena = $hoja['cells'][$i][5];
                    $perfil = "34";//inovekia
                    $email = $hoja['cells'][$i][6];;
                    $sucursal = "1";
                    $administrador = 0;
                    $puesto = 1;//administrador

                    $verifica = $this->validarUsuario($usuario);
                    if(!$verifica["status"]) throw new Exception("Error", 601);
                    if($verifica["total"] == 0){
                        
                        $queryUsuario = "   INSERT INTO empleados (idempleado, nombre, apellido1, apellido2, idorganizacion, visible, administrador) VALUES ";
                        $queryUsuario .= "  (null, '$nombre', '$apellido_1', '$apellido_2', 1, -1, $administrador);";
                        $result = $this->queryArray($queryUsuario);
                        if(!$result["status"]) throw new Exception("Empleado no insertado", 1);
                        $empleado_id = $result["insertId"];
                        
                        $queryUsuario = "  INSERT INTO accelog_usuarios (idempleado, usuario, clave, css) VALUES ";
                        $queryUsuario .= "  ($empleado_id, '$usuario', '" .$this->encriptar($contrasena). "', 'default');";
                        $result = $this->queryArray($queryUsuario);
                        if(!$result["status"]) throw new Exception("Usuario no insertado", 2);

                        $queryUsuario = "  INSERT INTO administracion_usuarios (idadmin, nombre, apellidos, nombreusuario, correoelectronico, idperfil, idempleado, idorganizacion, idpuesto, idSuc) VALUES";

                        $queryUsuario .= " (null, '$nombre', '$apellido_1 $apellido_2', '$usuario', '$email', '$perfil', '$empleado_id', 1, '$puesto', '$sucursal');";
                        $result = $this->queryArray($queryUsuario);
                        if(!$result["status"]) throw new Exception("Administracion no insertada", 3);

                        $queryUsuario = "  INSERT INTO accelog_usuarios_per (idperfil, idempleado) VALUES ('$perfil', '$empleado_id');";
                        $result = $this->queryArray($queryUsuario);

                        if(!$result["status"]) throw new Exception("Permisos no insertados", 4);

                        mysqli_commit($this->connection);
                        $Usuario = $this->validarUsuario($usuario);
                        $rowUsuario = $Usuario["rows"][0];
                    }else{
                        $repetidos[] = $usuario;  
                    }
                }

                if(count($repetidos)>0){
                    return array("status" => true, "rows" => array(), "mensaje" => "Usuarios ya existentes: ". implode(", ", $repetidos));
                }else{
                    return array("status" => true, "rows" => array(), "mensaje" => "");
                }
                
            }catch(Exception $e){
                mysqli_rollback($this->connection);
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }
    }

    public function validarUsuario($usuario){
        try{
            $query = 'SELECT e.idempleado, e.nombre, e.apellido1, e.apellido2, u.usuario, au.correoelectronico, au.idSuc, up.idperfil FROM empleados AS e 
                        INNER JOIN accelog_usuarios AS u ON u.idempleado = e.idempleado 
                        INNER JOIN administracion_usuarios AS au ON au.idempleado = u.idempleado 
                        INNER JOIN accelog_usuarios_per AS up ON up.idempleado = au.idempleado 
                        WHERE u.usuario = "'. $usuario .'"  
                        ORDER BY e.nombre ASC;';

            $result = $this->queryArray($query);
            return $result;
        }catch(Exception $e){
            return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
        }
        
    }

    function fencripta($pwd, $salt){
        $resultado = crypt($pwd,$salt);
        return $resultado;
    }

    function encriptar($texto){
        require('../../netwarelog/webconfig.php');
        return $this->fencripta($texto, $accelog_salt);
    }

}

?>