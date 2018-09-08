<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_api_sqli_manual.php"); // funciones mySQLi

    class SenorpagoModel extends Connectionapi
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

        private function guardarImagen($fotos_b64){
            date_default_timezone_set("Mexico/General");
            $fecha_actual = date("YmdHis");
            $ruta_carpeta = '../appministra_api/documentos/srpago/';
            if(!file_exists($ruta_carpeta)){
                if(!mkdir($ruta_carpeta, 0777, true))
                    return false;
            }

            $array_rutas = array();
            $borrar = false;
            foreach ($fotos_b64 as $tipo => $foto_b64) {
                $foto_b64 = str_replace('data:image/jpeg;base64,', '', $foto_b64);
                $foto_b64 = str_replace(' ', '+', $foto_b64);
                $foto_b64 = base64_decode($foto_b64);
                $nombre = substr(base64_encode(openssl_random_pseudo_bytes('30')), 0, 10). $fecha_actual .'.jpeg';
                $ruta_imagen = 'documentos/srpago/'. $nombre;
                if(file_put_contents($ruta_carpeta. $nombre, $foto_b64) !== false){
                    $array_rutas[$tipo] = $ruta_imagen;
                }else{
                    $borrar = true;
                    break;
                }
            }

            if($borrar){
                $this->eliminarImagen($array_rutas);
                return false;
            }

            return $array_rutas;
        }

        private function eliminarImagen($array_rutas){
            foreach ($array_rutas as $ruta_imagen) {
                unlink('../appministra_api/'. $ruta_imagen);
            }
        }

        public function crearSubAfiliadoSenorpago(  $email, $contrasena, $nombre, $primer_apellido, $segundo_apellido, 
                                                    $calle, $colonia, $ciudad, $codigo_postal, $cumpleanos, $tarjeta, 
                                                    $ife_delantera, $ife_posterior, $comprobante_domicilio, $contrato_pagina_1, 
                                                    $contrato_pagina_2, $contrato_pagina_3, $firma, $compania, 
                                                    $ventas_mensuales, $ventas_promedio, $tipo_compania){
    

            $respuesta = null;
            $guardar_documentos = false;
            try{
                if(!$this->Seguridad->validaUsuario(null, $contrasena, $this->Seguridad->Usuario)["status"])
                    throw new Exception("No fue posible verificar el usuario, contraseña incorrecta", 1);

                $array_imagenes = array("i1" => $ife_delantera, "i2" => $ife_posterior, "cd" => $comprobante_domicilio);
                if($contrato_pagina_1 != null){
                    $array_imagenes["c1"] = $contrato_pagina_1;
                    $array_imagenes["c2"] = $contrato_pagina_2;
                    $array_imagenes["c3"] = $contrato_pagina_3;
                }else{
                    $array_imagenes["f"] = $firma;
                }

                $guardar_documentos = $this->guardarImagen($array_imagenes);
                if($guardar_documentos === false) throw new Exception("No fue posible almacenar la documentación", 2);

                $servidor = explode("/", $_SERVER['REQUEST_URI']);
                $cliente = $servidor[array_search('clientes', $servidor) + 1];
                $servidor = "http://www.netwarmonitor.mx/clientes/". $cliente ."/appministra_api/";

                $ife_delantera = $guardar_documentos["i1"];
                $ife_posterior = $guardar_documentos["i2"];
                $comprobante_domicilio = $guardar_documentos["cd"];
                if($contrato_pagina_1 != null){
                    $contrato_pagina_1 = $guardar_documentos["c1"];
                    $contrato_pagina_2 = $guardar_documentos["c2"];
                    $contrato_pagina_3 = $guardar_documentos["c3"];
                    $firma = '';
                }else{
                    $firma = $guardar_documentos["f"];
                    $contrato_pagina_1 = '';
                    $contrato_pagina_2 = '';
                    $contrato_pagina_3 = '';
                }

                $guardar_informacion = $this->agregarSenorpago(  $email, $nombre, $primer_apellido, $segundo_apellido, 
                                                    $calle, $colonia, $ciudad, $codigo_postal, $cumpleanos, $tarjeta, 
                                                    $ife_delantera, $ife_posterior, $comprobante_domicilio, $contrato_pagina_1, 
                                                    $contrato_pagina_2, $contrato_pagina_3, $firma, $compania, 
                                                    $ventas_mensuales, $ventas_promedio, $tipo_compania);

                //aqui inicia srpago

                if(!$guardar_informacion["status"]) throw new Exception("No fue posible registrar la información", 3);
                
                $json = '{
                            "email": "'. $email .'",
                            "password": "'. $contrasena .'",
                            "name": "'. $nombre .'",
                            "last_name": "'. $primer_apellido .'",
                            "second_last_name": "'. $segundo_apellido .'",
                            "street": "'. $calle .'",
                            "area": "'. $colonia .'",
                            "city": "'. $ciudad .'",
                            "zip_code": "'. $codigo_postal .'",
                            "birth_date": "'. $cumpleanos .'",
                            "card_number": "'. $tarjeta .'",
                            "ife_front": "'. $ife_delantera .'",
                            "ife_back": "'. $ife_posterior .'",
                            "proof": "'. $comprobante_domicilio .'",';
                            if($contrato_pagina_1 != null){
                                $json .= '
                                "contract1": "'. $contrato_pagina_1 .'",
                                "contract2": "'. $contrato_pagina_2 .'",
                                "contract3": "'. $contrato_pagina_3 .'",';
                            }else{
                                $json .= '
                                "signature": "'. $firma .'",';
                            }
                            $json .= '
                            "company_name": "'. $compania .'",
                            "company_monthly_sales": "'. $ventas_mensuales .'",
                            "company_average_price": "'. $ventas_promedio .'",
                            "company_bussines_id": "'. $tipo_compania .'"}';

                $api_senorpago = $this->consultarApiSenorpagoPost("/v1/card/user/profile", $json);
                $valida_api_senorpago = $this->validarJSON($api_senorpago);
                if($valida_api_senorpago == null || !isset($valida_api_senorpago["success"])) throw new Exception("Error en la petición a Sr. Pago", 4);
                if(!$valida_api_senorpago["success"]) throw new Exception($valida_api_senorpago["error"]["message"], 5);

                $respuesta = array("status" => true);
            }catch(Exception $e){
                if($e->getCode() > 2) $this->eliminarImagen($guardar_documentos);
                if($e->getCode() > 3) $this->eliminarSenorpago();
                $respuesta = array("status" => false, "mensaje" => $e->getMessage());
            }
            return $respuesta;
        }

        private function validarJSON($json){
            $json = json_decode($json, true);
            if(json_last_error() === JSON_ERROR_NONE) return $json;
            return null;
        }

        private function consultarApiSenorpagoPost($metodo, $json){
            //$url = "https://api.srpago.com". $metodo;
            $url = "https://sandbox-api.srpago.com". $metodo;
            
            /*$encabezados = array(
                "POST ". $metodo ." HTTP/1.0", 
                "Content-Type: application/json",
                "Authorization: Basic " . base64_encode("9a0fb69f-2728-494b-9699-20ac8d4d5f3a:?b!*uw3bz8TJ")
            ); //Produccion*/

            $encabezados = array(
                "POST ". $metodo ." HTTP/1.0", 
                "Content-Type: application/json",
                "Authorization: Basic " . base64_encode("51372acb-9d5d-4355-83bf-c68d650f82f6:gQJ!fPb4rHei")
            ); //sandbox*/

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $encabezados);
            curl_setopt($ch, CURLOPT_POST, 1); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            $resultado = curl_exec($ch);
            if ($resultado === false) $resultado = curl_error($ch);
            curl_close($ch);
            return $resultado;
        }

        private function consultarApiSenorpagoGet($metodo, $json){
            //$url = "https://api.srpago.com". $metodo;
            $url = "https://sandbox-api.srpago.com". $metodo;
            
            /*$encabezados = array(
                "GET ". $metodo ." HTTP/1.0", 
                "Content-Type: application/json",
                "Authorization: Basic " . base64_encode("9a0fb69f-2728-494b-9699-20ac8d4d5f3a:?b!*uw3bz8TJ")
            ); //Produccion*/

            $encabezados = array(
                "POST ". $metodo ." HTTP/1.0", 
                "Content-Type: application/json",
                "Authorization: Basic " . base64_encode("51372acb-9d5d-4355-83bf-c68d650f82f6:gQJ!fPb4rHei")
            ); //sandbox*/

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $encabezados);
            $resultado = curl_exec($ch);
            if ($resultado === false) $resultado = curl_error($ch);
            curl_close($ch);
            return $resultado;
        }

        public function agregarSenorpago(   $email, $nombre, $primer_apellido, $segundo_apellido, 
                                            $calle, $colonia, $ciudad, $codigo_postal, $cumpleanos, $tarjeta, 
                                            $ife_delantera, $ife_posterior, $comprobante_domicilio, $contrato_pagina_1, 
                                            $contrato_pagina_2, $contrato_pagina_3, $firma, $compania, 
                                            $ventas_mensuales, $ventas_promedio, $tipo_compania){
            try{
                $verifica = $this->obtenerSenorpago();
                if(!$verifica["status"]) throw new Exception("Error", 1);
                if($verifica["total"] > 0){
                    $eliminarSenorpago = $this->eliminarSenorpago();
                    if(!$eliminarSenorpago["status"]) throw new Exception("Error", 1);
                }
                
                $queryCliente = "INSERT INTO configuracion_srpago VALUES (null, :email, :nombre, :primer_apellido, :segundo_apellido, ";
                $queryCliente .= ":calle, :colonia, :ciudad, :codigo_postal, :cumpleanos, :tarjeta, ";
                $queryCliente .= ":ife_delantera, :ife_posterior, :comprobante_domicilio, :contrato_pagina_1, ";
                $queryCliente .= ":contrato_pagina_2, :contrato_pagina_3, :compania, :ventas_mensuales, :tipo_compania, ";
                $queryCliente .= ":tipo_compania, :firma, 1);";

                $parametros = array(
                                'email' => $email,
                                'nombre' => $nombre,
                                'primer_apellido' => $primer_apellido,
                                'segundo_apellido' => $segundo_apellido,
                                'calle' => $calle,
                                'colonia' => $colonia,
                                'ciudad' => $ciudad,
                                'codigo_postal' => $codigo_postal,
                                'cumpleanos' => $cumpleanos,
                                'tarjeta' => $tarjeta,
                                'ife_delantera' => $ife_delantera,
                                'ife_posterior' => $ife_posterior,
                                'comprobante_domicilio' => $comprobante_domicilio,
                                'contrato_pagina_1' => $contrato_pagina_1,
                                'contrato_pagina_2' => $contrato_pagina_2,
                                'contrato_pagina_3' => $contrato_pagina_3,
                                'compania' => $compania,
                                'ventas_mensuales' => $ventas_mensuales,
                                'ventas_promedio' => $ventas_promedio,
                                'tipo_compania' => $tipo_compania,
                                'firma' => $firma
                                );
                
                $insertClienteResult = $this->queryArray($queryCliente, $parametros);
                
                return $insertClienteResult;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible agregar la información", "error" => $e->getMessage());
            }
        }

        public function eliminarSenorpago(){
            try{
                $sel = 'UPDATE configuracion_srpago SET activo = 0 WHERE activo = 1';
                $res = $this->queryArray($sel, array());
                return  $res;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible eliminar la información", "error" => $e->getMessage());
            }
        }

        public function obtenerSenorpago(){
            try{
                $select = "SELECT * FROM configuracion_srpago WHERE activo = 1;";
                $resultSelect = $this->queryArray($select, array());
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertedId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        public function validarSubAfiliadoSenorPago(){
            $respuesta = null;
            try{
                $subafiliado = $this->obtenerSenorpago();
                if(!$subafiliado["status"]) throw new Exception("Error", 1);
            
                $api_senorpago = $this->consultarApiSenorpagoGet("/v1/card/user/profile/documents/". $subafiliado["rows"][0]["email"], null);
                $valida_api_senorpago = $this->validarJSON($api_senorpago);
                if($valida_api_senorpago == null || !isset($valida_api_senorpago["success"])) throw new Exception("Error en la petición a Sr. Pago", 2);
                if(!$valida_api_senorpago["success"]) throw new Exception($valida_api_senorpago["error"]["message"], 3);

                $estatus_general = $valida_api_senorpago["result"];
                $estatus_documentos_general = $estatus_general["cards"][0];
                $estatus_documentos = $estatus_documentos_general["document_status"];

                $respuesta = array( "status" => true,
                                    "rows" => array(array("cuenta" => $estatus_general["active"],
                                                    "email" => $estatus_general["verifiedEmail"],
                                                    "srpago" => $estatus_documentos_general["status"],
                                                    "i1" => $estatus_documentos[0]["process"],
                                                    "i2" => $estatus_documentos[1]["process"],
                                                    "cd" => $estatus_documentos[2]["process"],
                                                    "c1" => $estatus_documentos[3]["process"],
                                                    "c2" => $estatus_documentos[5]["process"],
                                                    "c3" => $estatus_documentos[6]["process"], 
                                                    "f" => $estatus_documentos[4]["process"]))
                                );
            }catch(Exception $e){
                $respuesta = array( "status" => false,
                                    "mensaje" => $e->getMessage()
                                );
            }
            return $respuesta;
        }

        public function cargarImagenSubAfiliadoSenorPago($tipo, $url){
            $respuesta = null;
            try{
                $subafiliado = $this->obtenerSenorpago();
                if(!$subafiliado["status"]) throw new Exception("Error", 1);
            
                $json = '{
                            "number": "'. $subafiliado["rows"][0]["tarjeta"] .'", 
                            "url": "'. $url .'", 
                            "type": "'. $tipo .'", 
                            "email": "'. $subafiliado["rows"][0]["email"] .'"
                        }';

                $api_senorpago = $this->consultarApiSenorpagoPost("/v1/card/user/profile/documents", $json);
                $valida_api_senorpago = $this->validarJSON($api_senorpago);
                if($valida_api_senorpago == null || !isset($valida_api_senorpago["success"])) throw new Exception("Error en la petición a Sr. Pago", 2);
                if(!$valida_api_senorpago["success"]) throw new Exception($valida_api_senorpago["error"]["message"], 3);
                $respuesta = array("status" => true);
            }catch(Exception $e){
                $respuesta = array( "status" => false,
                                    "mensaje" => $e->getMessage()
                                );
            }
            return $respuesta;
        }


        public function editarSubAfiliadoSenorpago(  $email, $contrasena, $nombre, $primer_apellido, $segundo_apellido, 
                                                    $calle, $colonia, $ciudad, $codigo_postal, $cumpleanos, $tarjeta, 
                                                    $ife_delantera, $ife_posterior, $comprobante_domicilio, $contrato_pagina_1, 
                                                    $contrato_pagina_2, $contrato_pagina_3, $firma, $compania, 
                                                    $ventas_mensuales, $ventas_promedio, $tipo_compania){
    

            $respuesta = null;
            $guardar_documentos = false;
            try{
                if(!$this->Seguridad->validaUsuario(null, $contrasena, $this->Seguridad->Usuario)["status"])
                    throw new Exception("No fue posible verificar el usuario, contraseña incorrecta", 1);

                $array_imagenes = array("i1" => $ife_delantera, "i2" => $ife_posterior, "cd" => $comprobante_domicilio);
                if($contrato_pagina_1 != null){
                    $array_imagenes["c1"] = $contrato_pagina_1;
                    $array_imagenes["c2"] = $contrato_pagina_2;
                    $array_imagenes["c3"] = $contrato_pagina_3;
                }else{
                    $array_imagenes["f"] = $firma;
                }

                $guardar_documentos = $this->guardarImagen($array_imagenes);

                if($guardar_documentos === false) throw new Exception("No fue posible almacenar la documentación", 2);

                $servidor = explode("/", $_SERVER['REQUEST_URI']);
                $cliente = $servidor[array_search('clientes', $servidor) + 1];
                $servidor = "http://www.netwarmonitor.mx/clientes/". $cliente ."/appministra_api/";

                $ife_delantera = $guardar_documentos["i1"];
                $ife_posterior = $guardar_documentos["i2"];
                $comprobante_domicilio = $guardar_documentos["cd"];
                if($contrato_pagina_1 != null){
                    $contrato_pagina_1 = $guardar_documentos["c1"];
                    $contrato_pagina_2 = $guardar_documentos["c2"];
                    $contrato_pagina_3 = $guardar_documentos["c3"];
                    $firma = '';
                }else{
                    $firma = $guardar_documentos["f"];
                    $contrato_pagina_1 = '';
                    $contrato_pagina_2 = '';
                    $contrato_pagina_3 = '';
                }

                if($contrato_pagina_1 != ''){
                    $resultado1 = $this->cargarImagenSubAfiliadoSenorPago("ife_front",$servidor.$ife_delantera);
                    $resultado2 = $this->cargarImagenSubAfiliadoSenorPago("ife_back", $servidor.$ife_posterior);
                    $resultado3 = $this->cargarImagenSubAfiliadoSenorPago("proof", $servidor.$comprobante_domicilio);
                    $resultado4 = $this->cargarImagenSubAfiliadoSenorPago("contract1", $servidor.$contrato_pagina_1);
                    $resultado5 = $this->cargarImagenSubAfiliadoSenorPago("contract2", $servidor.$contrato_pagina_2);
                    $resultado6 = $this->cargarImagenSubAfiliadoSenorPago("contract3", $servidor.$contrato_pagina_3);

                    /*$respuesta = array( "status" => true,
                                    "rows" => array("ife_front" => $resultado1["status"],
                                                    "ife_back" => $resultado2["status"],
                                                    "proof" => $resultado3["status"],
                                                    "contract1" => $resultado4["status"],
                                                    "contract1" => $resultado5["status"],
                                                    "contract1" => $resultado6["status"])
                                );*/
                    if(!$resultado1["status"] || !$resultado2["status"] || !$resultado3["status"] || !$resultado4["status"] || !$resultado5["status"] || !$resultado6["status"]){
                        return array("status" => false);
                    }
                }else{
                    $resultado1 = $this->cargarImagenSubAfiliadoSenorPago("ife_front",$servidor.$ife_delantera);
                    $resultado2 = $this->cargarImagenSubAfiliadoSenorPago("ife_back", $servidor.$ife_posterior);;
                    $resultado3 = $this->cargarImagenSubAfiliadoSenorPago("proof", $servidor.$comprobante_domicilio);
                    $resultado4 = $this->cargarImagenSubAfiliadoSenorPago("signature", $servidor.$firma);
                    
                    /*$respuesta = array( "status" => true,
                                    "rows" => array("ife_front" => $resultado1["status"],
                                                    "ife_back" => $resultado2["status"],
                                                    "proof" => $resultado3["status"],
                                                    "signature" => $firma["status"])
                                );*/

                    if(!$resultado1["status"] || !$resultado2["status"] || !$resultado3["status"] || !$resultado4["status"]){
                        return array("status" => false);
                    }
                }

                $guardar_informacion = $this->agregarSenorpago($email, $nombre, $primer_apellido, $segundo_apellido, 
                                                    $calle, $colonia, $ciudad, $codigo_postal, $cumpleanos, $tarjeta, 
                                                    $ife_delantera, $ife_posterior, $comprobante_domicilio, $contrato_pagina_1, 
                                                    $contrato_pagina_2, $contrato_pagina_3, $firma, $compania, 
                                                    $ventas_mensuales, $ventas_promedio, $tipo_compania);

                $respuesta = array("status" => true);
            }catch(Exception $e){
                if($e->getCode() > 2) $this->eliminarImagen($guardar_documentos);
                //if($e->getCode() > 3) $this->eliminarSenorpago();
                $respuesta = array("status" => false, "mensaje" => $e->getMessage());
            }
            return $respuesta;
        }

    }

?>
