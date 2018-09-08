<?php
    //ini_set("display_errors", 1); error_reporting(E_ALL);
    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class ComprobanteModel extends Connection
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

        public function listado($anticipo){
            try {
                $query = "SELECT id, concepto, importe, ticket1, ticket2, IF(idCategoria IS NULL, 'No definida', (SELECT nombre FROM cont_comprobante_categoria WHERE id = idCategoria)) AS categoria, status AS comprobado, fecha FROM cont_nodeducible WHERE idAnticipo = $anticipo;";
                $result = $this->queryArray($query);
                if($result["status"]){
                    $json = array("status" => true, "rows" => $result["rows"]);
                }else{
                    $json = array("status" => false, "mensaje" => $result["msg"]);
                }
            } catch (Exception $e) {
                $json = array("status" => false, "mensaje" => $e->getMessage());
            } 
            return $json;       
        }

        public function agregar($comprobante){
            try {
                if(Input::tieneArchivo("ticket1") || Input::tieneArchivo("ticket2")){
                    $ticket1 = "../webapp/modulos/cont/images/comprobantes/". date("YmdHis") .".". Input::extencion("ticket1");
                    sleep(1);
                    $ticket2 = "../webapp/modulos/cont/images/comprobantes/". date("YmdHis") .".". Input::extencion("ticket2");
                    if (Input::tieneArchivo("ticket1") && !move_uploaded_file($_FILES["ticket1"]["tmp_name"], $ticket1)){
                        throw new Exception("No fue posible subir el ticket 1, intentalo nuevamente", 1);
                    }
                    if (Input::tieneArchivo("ticket2") && !move_uploaded_file($_FILES["ticket2"]["tmp_name"], $ticket2)){
                        @unlink($ticket1);
                        throw new Exception("No fue posible subir el ticket 2, intentalo nuevamente", 2);
                    }
                    $query = "INSERT INTO cont_nodeducible VALUES(NULL, '". $comprobante["observaciones"] ."', ". $comprobante["monto"] .", ". $comprobante["anticipo"] .", ". $comprobante["categoria"] .", 1 , '". $ticket1 ."', '". $ticket2 ."', '". date("Y-m-d H:i:s") ."', null);";

                    $result = $this->queryArray($query);
                    if($result["status"]){
                        $json = array("status" => true, "rows" => $result["rows"]);
                    }else{
                        throw new Exception($result["msg"], 3);
                    }
                } else{
                    throw new Exception("No se selecciono ninguna imagen", 4);
                }
            } catch (Exception $e) {
                @unlink($ticket1); @unlink($ticket2);
                $json = array("status" => false, "mensaje" => $e->getMessage());
            } 
            return $json;       
        }

        public function editar($comprobante){
            try {
                if(strpos($comprobante["ticket1"], 'comprobantes') && strpos($comprobante["ticket2"], 'comprobantes')){
                    $query = "UPDATE cont_nodeducible SET concepto = '". $comprobante["observaciones"] ."', importe = ". $comprobante["monto"] .", idCategoria = ". $comprobante["categoria"] .", fecha = '". date("Y-m-d H:i:s") ."' WHERE id =" . $comprobante["id"] ." ; ";

                        $result = $this->queryArray($query);
                        if($result["status"]){
                            $json = array("status" => true, "rows" => $result["rows"]);
                        }else{
                            throw new Exception($result["msg"], 3);
                        }
                           
                } else if (strpos($comprobante["ticket1"], 'comprobantes')){

                    $queryDelete = "SELECT ticket2 from cont_nodeducible WHERE id =" . $comprobante["id"] ." ; ";
                    $resultDelete = $this->queryArray($queryDelete);
                    if($resultDelete["status"]){
                        @unlink($resultDelete["rows"][0]["ticket2"]);
                    }

                    if(Input::tieneArchivo("ticket2")){
                        $ticket2 = "../webapp/modulos/cont/images/comprobantes/". date("YmdHis") .".". Input::extencion("ticket2");
                        if (Input::tieneArchivo("ticket2") && !move_uploaded_file($_FILES["ticket2"]["tmp_name"], $ticket2)){
                            //@unlink($ticket1);
                            throw new Exception("No fue posible subir el ticket 2, intentalo nuevamente", 2);
                        }
                        $query = "UPDATE cont_nodeducible SET concepto = '". $comprobante["observaciones"] ."', importe = ". $comprobante["monto"] .", idCategoria = ". $comprobante["categoria"] .", ticket2 = '". $ticket2 ."', fecha = '". date("Y-m-d H:i:s") ."' WHERE id =" . $comprobante["id"] ." ; ";

                        $result = $this->queryArray($query);
                        if($result["status"]){
                            $json = array("status" => true, "rows" => $result["rows"]);
                        }else{
                            throw new Exception($result["msg"], 3);
                        }
                    } else{
                        throw new Exception("No se selecciono ninguna imagen", 4);
                    }
                } else if (strpos($comprobante["ticket2"], 'comprobantes')){
                    $queryDelete = "SELECT ticket1 from cont_nodeducible WHERE id =" . $comprobante["id"] ." ; ";
                    $resultDelete = $this->queryArray($queryDelete);
                    if($resultDelete["status"]){
                        @unlink($resultDelete["rows"][0]["ticket1"]);
                    }
                    if(Input::tieneArchivo("ticket1")){
                        $ticket1 = "../webapp/modulos/cont/images/comprobantes/". date("YmdHis") .".". Input::extencion("ticket1");
                        if (Input::tieneArchivo("ticket1") && !move_uploaded_file($_FILES["ticket1"]["tmp_name"], $ticket1)){
                            //@unlink($ticket1);
                            throw new Exception("No fue posible subir el ticket 1, intentalo nuevamente", 2);
                        }
                        $query = "UPDATE cont_nodeducible SET concepto = '". $comprobante["observaciones"] ."', importe = ". $comprobante["monto"] .", idCategoria = ". $comprobante["categoria"] .", ticket1 = '". $ticket1 ."', fecha = '". date("Y-m-d H:i:s") ."' WHERE id =" . $comprobante["id"] ." ; ";

                        $result = $this->queryArray($query);
                        if($result["status"]){
                            $json = array("status" => true, "rows" => $result["rows"]);
                        }else{
                            throw new Exception($result["msg"], 3);
                        }
                    } else{
                        throw new Exception("No se selecciono ninguna imagen", 4);
                    }
                } else if(Input::tieneArchivo("ticket1") || Input::tieneArchivo("ticket2")){
                    $queryDelete = "SELECT ticket1 , ticket2 from cont_nodeducible WHERE id =" . $comprobante["id"] ." ; ";
                    $resultDelete = $this->queryArray($queryDelete);
                    if($resultDelete["status"]){
                        @unlink($resultDelete["rows"][0]["ticket1"]);
                        @unlink($resultDelete["rows"][0]["ticket2"]);
                        //print_r($resultDelete["rows"][0]["ticket2"]);
                    }

                    $ticket1 = "../webapp/modulos/cont/images/comprobantes/". date("YmdHis") .".". Input::extencion("ticket1");
                    sleep(1);
                    $ticket2 = "../webapp/modulos/cont/images/comprobantes/". date("YmdHis") .".". Input::extencion("ticket2");
                    if (Input::tieneArchivo("ticket1") && !move_uploaded_file($_FILES["ticket1"]["tmp_name"], $ticket1)){
                        throw new Exception("No fue posible subir el ticket 1, intentalo nuevamente", 1);
                    }
                    if (Input::tieneArchivo("ticket2") && !move_uploaded_file($_FILES["ticket2"]["tmp_name"], $ticket2)){
                        //@unlink($ticket1);
                        throw new Exception("No fue posible subir el ticket 2, intentalo nuevamente", 2);
                    }
                    $query = "UPDATE cont_nodeducible SET concepto = '". $comprobante["observaciones"] ."', importe = ". $comprobante["monto"] .", idCategoria = ". $comprobante["categoria"] .", ticket1 = '". $ticket1 ."', ticket2 = '". $ticket2 ."', fecha = '". date("Y-m-d H:i:s") ."' WHERE id =" . $comprobante["id"] ." ; ";

                    $result = $this->queryArray($query);
                    if($result["status"]){
                        $json = array("status" => true, "rows" => $result["rows"]);
                    }else{
                        throw new Exception($result["msg"], 3);
                    }
                } else{
                    throw new Exception("No se selecciono ninguna imagen", 4);
                }            

            } catch (Exception $e) {
                @unlink($ticket1); @unlink($ticket2);
                $json = array("status" => false, "mensaje" => $e->getMessage());
            } 
            return $json;       
        }
        

        public function grafico($anticipo){
            try {
                $categorias = "SELECT SUM(importe) AS total, IF(idCategoria IS NULL, 'No definida', (SELECT nombre FROM cont_comprobante_categoria WHERE id = idCategoria)) AS categoria, (SELECT color FROM cont_comprobante_categoria WHERE id = idCategoria) AS color, COUNT(id) AS cantidad FROM cont_nodeducible WHERE idAnticipo = $anticipo GROUP BY idCategoria;";
                $categorias = $this->queryArray($categorias);
                if($categorias["status"]){
                    $generales = "SELECT COUNT(id) AS cantidad, SUM(importe) AS total FROM cont_nodeducible WHERE idAnticipo = $anticipo;";
                    $generales = $this->queryArray($generales);
                    if($generales["status"]){
                        $json = array("status" => true, "rows" => $categorias["rows"], "extra" => array("cantidad" => $generales["rows"][0]["cantidad"], "sumatoria" => $generales["rows"][0]["total"]));
                    }else{
                        throw new Exception($result["msg"], 1);
                    } 
                }else{
                    throw new Exception($result["msg"], 2);
                }
            } catch (Exception $e) {
                $json = array("status" => false, "mensaje" => $e->getMessage());
            } 
            return $json;       
        }

    }
?>
