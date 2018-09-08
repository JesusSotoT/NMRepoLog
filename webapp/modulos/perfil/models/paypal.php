<?php

	//Carga la clase de coneccion con sus metodos para consultas o transacciones
    //require("models/connection.php"); // funciones mySQL 
    require_once dirname(__DIR__) .'/models/connection_sqli_manual.php'; // funciones mySQLi 
    //ini_set("display_errors",1); error_reporting(E_ALL);
    require_once dirname(__DIR__) .'/models/perfil.php';

    class PaypalModel extends Connection {
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

        public function crearTicket($resumen)
        {
            $total = 0;
            foreach ($resumen["productos"] as &$producto) {
                $producto["producto"] = $producto["producto"] ." ". $producto["version"];
                $total += $producto["precio"];
            }
            $this->perfil->paypal->setItems($resumen["productos"]);
            $this->perfil->paypal->setDetails($total);
            $this->perfil->paypal->setTransaction("Pago de productos Netwarstore");
            $this->perfil->paypal->setRedirectUrls("http://localhost/mlog/webapp/netwarelog/accelog/menu.php?success=true&payment=true", "http://localhost/mlog/webapp/netwarelog/accelog/menu.php?success=false&payment=true");
            $resultado = $this->perfil->paypal->setUpPayment();
            if(!$resultado["status"]){
                $resultado = array("status" => false, "mensaje" => $resultado["message"]);
            } else {
                $resultado = array("status" => true, "link" => $this->perfil->paypal->getPaymentLink());
            }
            return $resultado;
        }

        private function procesarPago($cliente, $customer, $payerId, $paymentId, $id_venta, $fecha, $productos) {
            try{
                $pago_resultado = $this->perfil->paypal->pay($payerId, $paymentId);
                if(!$pago_resultado["status"]) throw new Exception($pago_resultado["message"], 1);

                $total = 0;
                foreach ($productos as $producto) {
                    $total += $producto["precio"];
                }

                $sql_venta = "UPDATE pago_paypal SET referencia = '". $this->perfil->paypal->getReference() ."', total = $total, pagado = 1 WHERE id = $id_venta;";
                $sql_venta = $this->queryArray($sql_venta);

                $sql_producto_venta = "UPDATE venta_producto SET idcustomer = $customer WHERE id_pago_paypal = $id_venta;";
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
                require_once dirname(__DIR__) .'/views/emails/ticketPaypal.php';
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

                $respuesta = array("status" => false, "email" => $email, "envio" => $mail->Send(), "echo" => "<script>var mostrar_mensaje_pagado_correctamente = true;</script>");
            }catch(Exception $e){
                $error_venta = "UPDATE pago_paypal SET paypal = '". $e->getMessage() ."' WHERE id = $id_venta;";
                $error_venta = $this->queryArray($error_venta);
                $respuesta = array("status" => false, "echo" => "<script>var mostrar_mensaje_pagado_erroneamente = '". $e->getMessage() ."';</script>");
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
            $sql_venta = "INSERT INTO pago_paypal VALUES(null, '', '$cliente', 0, 0, 0, 0, null, 1, '$fecha', '$fecha');";
            $sql_venta = $this->queryArray($sql_venta);
            if(!$sql_venta["status"]) throw new Exception("Error Processing Request", 1);
            $id_venta = $sql_venta["insertId"];
            foreach ($productos as $producto) {
                $sql_producto_venta = "INSERT INTO venta_producto VALUES(null, '$cliente', null, null, $id_venta, ". $producto["idapp"] .", '". strtolower($producto["periodo"]) ."', ". $producto["precio"] .");";
                $sql_producto_venta = $this->queryArray($sql_producto_venta);
                $total += $producto["precio"];
            }
            $sql_venta = "UPDATE pago_paypal SET subtotal = $total, total = $total WHERE id = $id_venta;";
            $sql_venta = $this->queryArray($sql_venta);
            return array($id_venta, $fecha, $productos);
        }

        public function pagar($resumen)
        {
            if(isset($resumen['success']) && isset($resumen["payment"])) {
                if($resumen['success'] == 'true' && $resumen["payment"] == 'true'){
                    $this->connect(true);
                    $sql_buscar_pago = "SELECT id FROM pago_paypal WHERE referencia = '". $resumen["paymentId"] ."';";
                    $sql_buscar_pago = $this->queryArray($sql_buscar_pago);
                    if($sql_buscar_pago["status"] && $sql_buscar_pago["total"] > 0){
                        $respuesta = array("status" => false, "echo" => "<script>var mostrar_mensaje_pagado_anteriormente = true;</script>");
                    } else {
                        $venta = self::insertarVenta($resumen["productos"], $resumen["cliente"]);
                        $respuesta = self::procesarPago($resumen["cliente"], $resumen["customer"], $resumen["PayerID"], $resumen["paymentId"], $venta[0], $venta[1], $venta[2]);
                    }
                    $this->connect(false);
                } else {
                    $respuesta = array("status" => false, "echo" => "<script>var mostrar_mensaje_cancelado = true;</script>");
                }
            }else{
                $respuesta = array("status" => true);
            }
            return $respuesta;
        }

    }

?>
