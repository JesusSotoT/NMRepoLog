<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    //require("models/connection.php"); // funciones mySQL 
    require_once dirname(__DIR__) .'/models/connection_sqli_manual.php'; // funciones mySQLi 
    //ini_set("display_errors",1); error_reporting(E_ALL);
    require_once dirname(__DIR__) .'/models/perfil.php';

    class SrPagoModel extends Connection {
        //Definir los atributos de la clase
        private $perfil;

        function __construct()
        {
            $this->perfil = new PerfilModel();
            $this->connect();
        }

        function __destruct()
        {

        }

        public function agregarTarjeta($tarjeta)
        {
            try{
                $usuario = $this->perfil->obtenerUsuarioSrPago();
                $tarjetahabiente = $tarjeta["tarjetahabiente"];
                $tarjeta = array("number" => $tarjeta["numero"], "expiration_date" => array("year" => $tarjeta["ano"], "month" => $tarjeta["mes"]), "cvv" => $tarjeta["cvv"]);
                $tarjeta = $this->perfil->srpago->addCard($usuario, $this->perfil->srpago->createCardToken($tarjetahabiente, $tarjeta));
                $json = array("status" => true, "crd" => $tarjeta);
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido agregar la tarjeta debido al siguiente problema: ". $e->getMessage());
            }
            return $json;
        }

        public function eliminarTarjeta($tarjeta)
        {
            try{
                $usuario = $this->perfil->obtenerUsuarioSrPago();
                $tarjeta = $this->perfil->srpago->removeCard($usuario, $tarjeta);
                $json = array("status" => true, "crd" => $tarjeta);
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido eliminar la tarjeta debido al siguiente problema: ". $e->getMessage());
            }
            return $json;
        }

        public function obtenerTarjetas()
        {
            try{
                $this->connect(true);
                $usuario = $this->perfil->obtenerUsuarioSrPago();
                $tarjetas = $this->perfil->srpago->getCustomerCards($usuario);
                $tarjetas = (is_null($tarjetas)) ? array() : $tarjetas;
                $sql = "SELECT tarjeta FROM srpago_tarjeta_default WHERE srpago_usuario = '". $this->perfil->obtenerUsuarioSrPago() ."' LIMIT 1;";
                $sql = $this->queryArray($sql);
                $default = ($sql["status"] && $sql["total"] > 0) ? $sql["rows"][0]["tarjeta"] : null;
                $json = array("status" => true, "tarjetas" => $tarjetas, "default" => $default);
            }catch(Exception $e){
                $json = array("status" => false, "mensaje" => $e->getMessage());
            }
            $this->connect();
            return $json;
        }

        public function defaultTarjeta($tarjeta)
        {
            try{
                $this->connect(true);
                $sql = "DELETE FROM srpago_tarjeta_default WHERE srpago_usuario = '". $this->perfil->obtenerUsuarioSrPago() ."';";
                $sql = $this->queryArray($sql);
                if($sql["status"]) {
                    $sql = "INSERT INTO srpago_tarjeta_default VALUES(null, '". $this->perfil->obtenerUsuarioSrPago() ."', '". $tarjeta ."');";
                    $sql = $this->queryArray($sql);
                    if($sql["status"]) {
                        $json = array("status" => true, "crd" => $tarjeta);
                    } else {
                        throw new Exception($sql["msg"], 1);
                    }
                } else {
                    throw new Exception($sql["msg"], 1);
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido definir la tarjeta debido al siguiente problema: ". $e->getMessage());
            }
            $this->connect();
            return $json;
        }

        private function procesarPago($cliente, $customer, $tarjeta, $id_venta, $fecha, $productos) {
            try{

                $this->perfil->srpago->setInternalTransactionReference("Pago de productos Netwarstore");
                $this->perfil->srpago->setTip("0.0");

                $this->perfil->srpago->setCardToken($tarjeta);
                
                $total = 0;
                $items = array();
                foreach ($productos as $producto) {
                    $items[] = array("id" => $producto["id"], "description" => $producto["producto"] ." ". $producto["version"], "price" => $producto["precio"], "quantity" => "1", "unit" => "Pza", "brand" => "Netwarmonitor", "category" => "Software", "tax" => "0.0");
                    $total += $producto["precio"];
                }
                $this->perfil->srpago->setItems($items);
                $this->perfil->srpago->setTaxes();

                $sql_venta = "UPDATE pago_srpago SET log = '". $this->perfil->srpago->getLog() ."' WHERE id = $id_venta;";
                $sql_venta = $this->queryArray($sql_venta);

                $pago = $this->perfil->srpago->payment($total);

                $sql_venta = "UPDATE pago_srpago SET referencia = '". $this->perfil->srpago->getReference() ."', total = $total, srpago = '". json_encode($pago) ."', log = '". $this->perfil->srpago->getLog() ."', pagado = 1 WHERE id = $id_venta;";
                $sql_venta = $this->queryArray($sql_venta);

                $sql_producto_venta = "UPDATE venta_producto SET idcustomer = $customer WHERE id_pago_srpago = $id_venta;";
                $sql_producto_venta = $this->queryArray($sql_producto_venta);

                foreach ($productos as $producto) {
                    $fecha_final = ($producto["periodo"] == "anual") ? date("Y-m-d", strtotime("+1 year", strtotime($producto["limitdate"]))) : date("Y-m-d", strtotime("+1 month", strtotime($producto["limitdate"])));
                    $sql_cliente = "UPDATE appclient SET initdate = '". $producto["limitdate"] ."', limitdate = '". $fecha_final ."' WHERE id = ". $producto["id_venta"] .";";
                    $sql_cliente = $this->queryArray($sql_cliente);
                }

                $sql_email = "SELECT email FROM regcustomer WHERE id = $cliente;";
                $sql_email = $this->queryArray($sql_email);
                $email = $sql_email["rows"][0]["email"];

                $resumen = array("productos" => $productos);
                $resumen["fecha"] = $fecha;
                $resumen["total"] = $total;

                $venta = $this->aplicarVenta($resumen);
                $resumen["codigo"] = $venta["codigo"];
                $resumen["url"] = $venta["ruta"];

                include_once '../../libraries/phpmailer/sendMail.php';
                ob_start();
                require_once 'views/emails/ticket.php';
                $html = ob_get_clean();
                $mail->IsHTML(true);
                $mail->setFrom("mailer@netwarmonitor.com", "NetwarStore");
                $mail->Subject = "Ticket de venta";
                $mail->Body = $html;
                $mail->AddAddress($email);

                $sql_actualizacion = "SELECT i.id FROM appclient AS i INNER JOIN appdescrip AS p ON p.idapp = i.idapp WHERE i.idclient = ". $cliente ." AND i.idcustomer = ". $customer ." AND i.idstatus = 1 AND i.limitdate < DATE(NOW());";
                $sql_actualizacion = $this->queryArray($sql_actualizacion);
                if($sql_actualizacion["total"] == 0){
                    $sql_actualizacion = "UPDATE customer SET cobranza = 0 WHERE instancia = '". $_SESSION["accelog_nombre_instancia"] ."';";
                    $sql_actualizacion = $this->queryArray($sql_actualizacion);
                    if(!isset($_SESSION)) session_start();
                    $_SESSION["estatus_cobranza"] = 0;
                }

                $respuesta = array("status" => true, "email" => $email, "envio" => $mail->Send());
            }catch(Exception $e){
                $error = $e->getMessage();
                $codigo = $e->getCode();
                if($e->getCode() == 50 || $e->getCode() == -10){
                    $error = explode("||", $error);
                    $codigo = $error[1];
                    $error = $error[0];
                }
                $error_venta = "UPDATE pago_srpago SET srpago = '". $error ."' WHERE id = $id_venta;";
                $error_venta = $this->queryArray($error_venta);
                $respuesta = array("status" => false, "mensaje" => $error);
            }
            return $respuesta;
        }

        private function aplicarVenta($productos){
            $ids = "";
            foreach ($productos["productos"] as $producto) {
                $ids .= $producto["id"] ."|";
            }
            $ids = trim($ids, "|");
            $url = 'https://www.netwarmonitor.com/clientes/nmwadmin/netwarstore/ajax.php?c=venta&f=venta&productos='. $ids .'&total='. $productos["total"]; //TODO: Cambiar a produccion
            $encabezados = array (
                'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $encabezados);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          
            $resultado = curl_exec($ch);
            curl_close($ch);
            return json_decode($resultado, true);
        }

        private function insertarVenta($productos, $cliente) {
            $total = 0;
            $fecha = date("Y-m-d H:i:s");
            $sql_venta = "INSERT INTO pago_srpago VALUES(null, '', '$cliente', 0, 0, 0, 0, '', 1, '$fecha', '$fecha', null);";
            $sql_venta = $this->queryArray($sql_venta);
            if(!$sql_venta["status"]) throw new Exception("Error Processing Request", 1);
            $id_venta = $sql_venta["insertId"];
            foreach ($productos as $producto) {
                $sql_producto_venta = "INSERT INTO venta_producto VALUES(null, '$cliente', null, $id_venta, null, ". $producto["idapp"] .", '". strtolower($producto["periodo"]) ."', ". $producto["precio"] .");";
                $sql_producto_venta = $this->queryArray($sql_producto_venta);
                $total += $producto["precio"];
            }
            $sql_venta = "UPDATE pago_srpago SET subtotal = $total, total = $total WHERE id = $id_venta;";
            $sql_venta = $this->queryArray($sql_venta);
            return array($id_venta, $fecha, $productos);
        }

        public function pagar($resumen, $tarjeta)
        {
            $this->connect(true);
            $venta = self::insertarVenta($resumen["productos"], $resumen["cliente"]);
            $pago = self::procesarPago($resumen["cliente"], $resumen["customer"], $tarjeta, $venta[0], $venta[1], $venta[2]);
            $this->connect(false);
            return $pago;
        }

    }

?>
