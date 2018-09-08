<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_api_sqli_manual.php"); // funciones mySQLi

    class ClienteModel extends Connectionapi
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

        public function obtenerCliente($filtros, $parametros){
            try{
            

                $query_cliente = 'SELECT cc.id, cc.nombre, cc.direccion, cc.colonia, cc.email, cc.cp, cc.idEstado, cc.idMunicipio, cc.rfc, cc.telefono1, cc.num_ext, cc.num_int, cf.rfc from comun_cliente AS cc LEFT JOIN comun_facturacion AS cf ON cf.nombre = cc.id where '. $filtros .' AND borrado = 0 ORDER BY id DESC';
                $result_cliente = $this->queryArray($query_cliente, $parametros);
                
                $array_cliente = array();
                $id_cliente = "";
                
                foreach ($result_cliente["rows"] as &$cliente) {
                    $id_cliente .= $cliente["id"] . ",";
                    $cliente["rfcs"] = array();
                    $array_cliente[$cliente["id"]] = $cliente;
                    
                }
                $id_cliente = trim($id_cliente, ",");

                if($id_cliente != ""){
                    $query_rfc = 'SELECT id, nombre, rfc from comun_facturacion WHERE nombre IN ('. $id_cliente .');'; 
                    $result_rfc = $this->queryArray($query_rfc);

                    foreach ($result_rfc["rows"] as &$rfc) {
                        $cliente = $rfc["nombre"];
                        unset($rfc["nombre"]);
                        $array_cliente[$cliente]["rfcs"][] = $rfc;
                    }

                    $result_cliente["rows"] =  array();

                    foreach ($array_cliente as &$cliente) {
                        $result_cliente["rows"][] = $cliente;
                    }
                }
                return $result_cliente;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function agregarCliente($nombre, $direccion, $num_int, $num_ext, $colonia, $estado, $municipio, $codigo_postal, $email, $telefono, $rfc){
            try{
                $parametros0 = array(
                                'email' => $email,
                                'rfc' => $rfc
                                );

                $verifica = $this->obtenerCliente("cc.email = :email OR cc.rfc = :rfc", $parametros0);
                if(!$verifica["status"]) throw new Exception($verifica["msg"], 601);
                if($verifica["total"] == 0){
                    $queryCliente = "INSERT INTO comun_cliente (nombre, direccion, colonia, email, cp, idEstado, idMunicipio, rfc, telefono1, num_ext, num_int) values ";
                    $queryCliente .= "(:nombre, :direccion, :colonia, :email, :codigo_postal, :estado, :municipio, :rfc, :telefono, :num_ext, :num_int)";

                    $parametros = array(
                                    'nombre' => $nombre,
                                    'direccion' => $direccion,
                                    'colonia' => $colonia,
                                    'email' => $email,
                                    'codigo_postal' => $codigo_postal,
                                    'estado' => $estado,
                                    'municipio' => $municipio,
                                    'rfc' => $rfc,
                                    'telefono' => $telefono,
                                    'num_ext' => $num_ext,
                                    'num_int' => $num_int,
                                    );

                    $insertClienteResult = $this->queryArray($queryCliente, $parametros);
                }else{
                    $insertClienteResult = array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Email o RFC ya han sido registrados");
                }
                return $insertClienteResult;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function editarCliente($id, $nombre, $direccion, $num_int, $num_ext, $colonia, $estado, $municipio, $codigo_postal, $email, $telefono, $rfc){
            try{
                $parametros0 = array(
                                'email' => $email,
                                'rfc' => $rfc,
                                'id' => $id
                                );
                $verifica = $this->obtenerCliente("(cc.email = :email OR cc.rfc = :rfc) AND cc.id != :id", $parametros0);
                if(!$verifica["status"]) throw new Exception("Error", 601);
                if($verifica["total"] == 0){
                    $queryCliente = "UPDATE comun_cliente SET nombre = :nombre, direccion = :direccion, colonia = :colonia, email = :email, cp = :codigo_postal, idEstado = :estado, idMunicipio = :municipio, rfc = :rfc, telefono1 = :telefono, num_ext = :num_ext, num_int = :num_int WHERE id = :id";

                    $parametros = array(
                                    'id' => $id,
                                    'nombre' => $nombre,
                                    'direccion' => $direccion,
                                    'colonia' => $colonia,
                                    'email' => $email,
                                    'codigo_postal' => $codigo_postal,
                                    'estado' => $estado,
                                    'municipio' => $municipio,
                                    'rfc' => $rfc,
                                    'telefono' => $telefono,
                                    'num_ext' => $num_ext,
                                    'num_int' => $num_int,
                                    );
                    
                    $insertClienteResult = $this->queryArray($queryCliente, $parametros);
                }else{
                    $insertClienteResult = array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Email o RFC ya han sido registrados");
                }
                return $insertClienteResult;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible editar la información", "error" => $e->getMessage());
            }
        }

        public function eliminarCliente($id){
            try{
                $sel = 'UPDATE comun_cliente set borrado = 1 where id = :id';
                $res = $this->queryArray($sel, array('id' => $id));
                return  $res;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible eliminar la información", "error" => $e->getMessage());
            }
        }

        public function obtenerFacturacion($filtros){
            try{
                $query_facturacion = 'SELECT * FROM comun_facturacion WHERE '. $filtros .';';
                $result_facturacion = $this->queryArray($query_facturacion);
                return $result_facturacion;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }

            /*try{
                $query_facturacion = 'SELECT * FROM comun_facturacion WHERE '. $filtros .';';
                $result_facturacion = $this->queryArray($query_facturacion, $parametros);
                return $result_facturacion;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }*/
        }

        public function agregarFacturacion($nombre, $rfc, $razon_social, $email, $pais, $regimen_fiscal, $direccion, $num_ext, $codigo_postal, $colonia, $estado, $ciudad, $municipio){
            try{
                //$verifica = $this->obtenerCliente("email = '". $email ."' OR rfc = '". $rfc ."'");
                //if(!$verifica["status"]) throw new Exception("Error", 601);
                //if($verifica["total"] == 0){
                    
                    $queryCliente = "INSERT INTO comun_facturacion (nombre, rfc, razon_social, correo, pais, regimen_fiscal, domicilio, num_ext, cp, colonia, estado, ciudad, municipio) values ";
                    
                    $queryCliente .= "(:nombre, :rfc, :razon_social, :email, :pais, :regimen_fiscal, :direccion, :num_ext, :codigo_postal, :colonia, :estado, :ciudad, :municipio)";

                    $parametros = array(
                                        'nombre' => $nombre,
                                        'rfc' => $rfc,
                                        'razon_social' => $razon_social,
                                        'email' => $email,
                                        'pais' => $pais,
                                        'regimen_fiscal' => $regimen_fiscal,
                                        'direccion' => $direccion,
                                        'num_ext' => $num_ext,
                                        'codigo_postal' => $codigo_postal,
                                        'colonia' => $colonia,
                                        'estado' => $estado,
                                        'ciudad' => $ciudad,
                                        'municipio' => $municipio
                                        );

                    $insertClienteResult = $this->queryArray($queryCliente, $parametros);
                //}else{
                //    $insertClienteResult = array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "Email o RFC ya han sido registrados");
                //}
                return $insertClienteResult;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function editarFacturacion($id, $nombre, $rfc, $razon_social, $email, $pais, $regimen_fiscal, $direccion, $num_ext, $codigo_postal, $colonia, $estado, $ciudad, $municipio){

            try{
                
                   $queryRfc = "UPDATE comun_facturacion SET nombre = :nombre, rfc = :rfc, razon_social = :razon_social, correo = :email, pais = :pais, regimen_fiscal = :regimen_fiscal, domicilio = :direccion, num_ext = :num_ext, cp = :codigo_postal, colonia = :colonia, estado = :estado, ciudad = :ciudad, municipio = :municipio WHERE id = :id";

                    $parametros = array(
                                        'id' => $id,
                                        'nombre' => $nombre,
                                        'rfc' => $rfc,
                                        'razon_social' => $razon_social,
                                        'email' => $email,
                                        'pais' => $pais,
                                        'regimen_fiscal' => $regimen_fiscal,
                                        'direccion' => $direccion,
                                        'num_ext' => $num_ext,
                                        'codigo_postal' => $codigo_postal,
                                        'colonia' => $colonia,
                                        'estado' => $estado,
                                        'ciudad' => $ciudad,
                                        'municipio' => $municipio
                                        );

                    $updateRfc = $this->queryArray($queryRfc, $parametros);
              
                return $updateRfc;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible editar la información", "error" => $e->getMessage());
            }
        }

    }
?>
