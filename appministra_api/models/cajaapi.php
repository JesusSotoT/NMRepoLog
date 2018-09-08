<?php
    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);
    //Carga la clase de coneccion con sus metodos para consultas o transacciones 
    require_once("models/connection_api_sqli_manual.php"); // funciones mySQLi
    require_once("../webapp/modulos/pos/controllers/caja.php");

    class CajaapiModel extends Connectionapi
    {
        private $CajaController;

        function __construct()
        {
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->CajaController = new Caja();
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

        private function generarArrayProductos($lista_productos){
            $relacional = array();
            $ids = "";
            foreach ($lista_productos as $item) {
                $ids .= $item["producto"] . ",";
                $relacional[$item["producto"]] = $item["cantidad"];
            }
            $ids = trim($ids, ",");
            $productos = "SELECT   
                            id, codigo, nombre, descripcion_corta, if(descripcion_larga IS NULL, '' , descripcion_larga) as descripcion_larga, 
                            precio, if(id_moneda IS NULL, 0, id_moneda) as id_moneda, if(ruta_imagen!='', ruta_imagen, 'noimage.jpeg') as imagen, 
                            if(tipo_producto IS NULL, 0, tipo_producto) as tipo_producto, if(departamento IS NULL, 0, departamento) as departamento, 
                            if(formulaIeps IS NULL, 0, formulaIeps) as formulaIeps
                        FROM app_productos  
                        WHERE status = 1 AND id IN($ids) 
                        ORDER BY nombre ASC;";
            $productos = $this->queryArray($productos);
            foreach ($productos["rows"] as &$producto) {
                $producto["cantidad"] = $relacional[$producto["id"]];
            }
            return $productos["rows"];
        }

        public function obtenerResumenVenta($lista_productos, $descuento, $redondear = false){
            return $this->calcularImpuestos($this->generarArrayProductos($lista_productos), $descuento, false, $redondear);
        }

        public function calcularImpuestos($productos, $descuento, $realizada = false, $redondear = false){
            try {
                $monto_total = 0;
                $monto_subtotal = 0;
                $monto_impuestos = 0;
                $array_impuestos = array();
                $lista_productos = array();
                $descuentoGeneral = 0;
               
                foreach ($productos as $key => &$producto) {
                    $total = $subtotal = $producto["precio"] * $producto["cantidad"];
                    if($descuento > 0){
                        $descuentoGeneral += $producto["precio"] * ((float)$descuento / 100);
                        $total = $subtotal = $subtotal - ($subtotal * ((float)$descuento / 100));
                    }

                    $ieps = 0;
                    $producto_impuesto = 0;
                    $producto_impuesto2 = 0;
                    $producto_impuestoR = 0;
                    if($producto["formulaIeps"] == 2){
                        $ordenform = 'ASC';
                    }else{
                        $ordenform = 'DESC';
                    }

                    if(!$realizada){
                        $obtener_impuestos = "SELECT p.precio, i.valor, i.clave, pi.formula, i.nombre, pi.id_impuesto AS id";
                        $obtener_impuestos .= " FROM app_impuesto i, app_productos p ";
                        $obtener_impuestos .= " LEFT JOIN app_producto_impuesto pi on p.id = pi.id_producto ";
                        $obtener_impuestos .= " WHERE p.id = ".$producto["id"] ." AND i.id = pi.id_impuesto ";
                        $obtener_impuestos .= " ORDER BY pi.id_impuesto ".$ordenform;

                        $parametros = array();
                    }else{
                        $parametros = array();
                        $obtener_impuestos = "SELECT i.id, i.nombre, i.clave, vi.porcentaje AS valor FROM app_pos_venta_producto_impuesto AS vi ".
                                "INNER JOIN app_impuesto AS i ON vi.idImpuesto = i.id WHERE vi.idVentaproducto = ". $producto["idVentaProducto"] .";";
                    }
                    
                    $obtener_impuestos = $this->queryArray($obtener_impuestos, $parametros);

                    $impuestos = array();
                    foreach ($obtener_impuestos["rows"] as $key => $valueImpuestos) {
                        $impuesto = array();
                        if ($valueImpuestos["clave"] == 'IEPS') {
                            $producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
                            $producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);
                        } elseif($valueImpuestos["clave"]=='IVAR' || $valueImpuestos["clave"]=='ISR'){
                            $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
                            $producto_impuestoR = (($subtotal) * $valueImpuestos["valor"] / 100);
                            $producto_impuestoR += (($subtotal) * $valueImpuestos["valor"] / 100);
                            $producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);
                        } else {
                            if ($ieps != 0) {   
                                $producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);                     
                            } else {
                                $producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100); 
                                $producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);                               
                            }
                        }

                        $impuesto["id"] = $valueImpuestos["id"];
                        $impuesto["nombre"] = $valueImpuestos["nombre"];
                        $impuesto["porcentaje"] = $valueImpuestos["valor"];
                        $impuesto["clave"] = $valueImpuestos["clave"];

                        $impuesto["monto"] = ($redondear) ? round($producto_impuesto, 2) : $producto_impuesto;
                        $impuestos[] = $impuesto;

                        $monto_impuestos += $impuesto["monto"];
                        if(isset($array_impuestos[$impuesto["nombre"]]))
                            $array_impuestos[$impuesto["nombre"]]["monto"] += $producto_impuesto;
                        else
                            $array_impuestos[$impuesto["nombre"]]["monto"] = $producto_impuesto;
                        $array_impuestos[$impuesto["nombre"]]["id"] = $valueImpuestos["id"];
                        $array_impuestos[$impuesto["nombre"]]["clave"] = $valueImpuestos["clave"];
                        $array_impuestos[$impuesto["nombre"]]["porcentaje"] = $valueImpuestos["valor"];
                    }

                    $total = ($subtotal + $producto_impuesto2) - $producto_impuestoR;
                    $monto_total += $total;
                    $monto_subtotal += $subtotal;

                    if($redondear) $producto["precio"] = round($producto["precio"], 2);
                    $producto["subtotal"] = ($redondear) ? round($subtotal, 2) : $subtotal;
                    $producto["impuestos"] = $impuestos;
                    $producto["suma_impuestos"] = $producto_impuesto2 - $producto_impuestoR;
                    $producto["total"] = ($redondear) ? round($total, 2) : $total;
                    $lista_productos[] = $producto;
                }
                
                $impuestos_array = array();
                $i = 0;
                foreach ($array_impuestos as $key => $impuesto) {
                    $impuestos_array[$i]["id"] = $array_impuestos[$key]['id'];
                    $impuestos_array[$i]["nombre"] = $key;
                    $impuestos_array[$i]["monto"] = ($redondear) ? round($array_impuestos[$key]["monto"], 2) : $array_impuestos[$key]["monto"];
                    $impuestos_array[$i]["porcentaje"] = $array_impuestos[$key]['porcentaje'];
                    $impuestos_array[$i]["clave"] = $array_impuestos[$key]['clave'];

                    $i++;
                }
                
                return array(   "status" => true, 
                                "rows" => array(), 
                                "insertId" => 0,
                                "monto_total" => ($redondear) ? round($monto_total, 2) : $monto_total,
                                "monto_subtotal" => ($redondear) ? round($monto_subtotal, 2) : $monto_subtotal,
                                "productos" => $lista_productos, 
                                "impuestos" => $array_impuestos,
                                "impuestos_array" => $impuestos_array,
                                "monto_impuestos" => ($redondear) ? round($monto_impuestos, 2) : $monto_impuestos,
                                "descuento_general" => ($redondear) ? round($descuentoGeneral, 2) : $descuentoGeneral);
            } catch (Exception $e) {
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener el detalle de la venta", "error" => $e->getMessage());
            }
        }

        public function guardarVenta($token_venta){
            try{
                $query_token = 'SELECT apvp.idFormapago, apv.token_venta, apv.monto, apv.idVenta';
                $query_token .= ' FROM app_pos_venta_pagos apvp';
                $query_token .= ' INNER JOIN app_pos_venta as apv on apvp.idVenta = apv.idVenta ';
                $query_token .= ' WHERE apv.token_venta = :token_venta;';

                $result_token = $this->queryArray($query_token, array('token_venta' => $token_venta));

                if($result_token["total"] > 0){
                    $result_token["status"] = false;
                    return $result_token;
                }else{

                    //GUARDAR VENTA
                    unset($_POST);
                    $_POST["idFact"] = 0;
                    $_POST["documento"] = 1;
                    $_POST["cliente"] = ($_REQUEST["cliente"] == 0) ? null : $_REQUEST["cliente"];
                    $_POST["vendedor"] = null;
                    $_POST["suspendida"] = 0;
                    $_POST["moneda"] = 1;
                    $_POST["tipocambio"] = 1;
                    $_POST["usarPuntos"] = 0;
                    $_POST["totalPuntosInput"] = 0;
                    $_POST["tr"] = null;
                    $venta = $this->CajaController->guardarVenta();

                    return array("status" => true, "rows" => array(), "insertId" => $venta["idVenta"], "extra" => array("total" => $_SESSION["caja"]["cargos"]["total"], "session" => base64_encode(json_encode($_SESSION))));
                }
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible registrar la venta", "error" => $e->getMessage());
            }
        }

        public function facturarVenta() {
            //FACTURACION
            unset($_POST);
            $_POST["idFact"] = 0;
            $_POST["idVenta"] = $_REQUEST["venta"];
            $_POST["doc"] = 1;
            $_POST["mensaje"] = false;
            $_POST["consumo"] = $_REQUEST["consumo"];
            $_POST['moneda'] = 1;
            $_POST['tipocambio'] = 1.00;
            $_POST['serie'] = 1;
            //Obtener tipo de facturacion
            $tipo_facturacion = "SELECT version FROM pvt_configura_facturacion WHERE id = 1";
            $tipo_facturacion = $this->queryArray($tipo_facturacion, array());
            $tipo_facturacion = $tipo_facturacion["rows"][0]["version"];
            if($tipo_facturacion == 3.2){
                $_POST['usoCfdi'] = $_REQUEST["usoCfdi"];
                $_POST['mpCat'] = $_REQUEST["mpCat"];
                $_POST['relacion'] = $_REQUEST["relacion"];
            } else {
                $_POST['usoCfdi'] = 1;
                $_POST['mpCat'] = 1;
                $_POST['relacion'] = 0;
            }
            $_POST['uuidRelacion'] = $_REQUEST["uuidRelacion"];
            $facturacion = $this->CajaController->facturar();
            //GUARDAR DATOS DE FACTURA
            unset($_POST);
            $_POST["azurian"] = $facturacion["azurian"];
            $_POST["idFact"] = 0;
            $_POST["monto"] = $_REQUEST["monto"];
            $_POST["cliente"] = ($_REQUEST["cliente"] == 0) ? null : $_REQUEST["cliente"];
            $_POST["trackId"] = $_REQUEST["trackId"];
            $_POST["idVenta"] = $_REQUEST["venta"];
            $_POST["doc"] = 1;
            $guardar_factura = $this->CajaController->pendienteFacturacion();

            return array("status" => true, "success" => $facturacion["success"], "uuid" => '', "correo" => '', "azurian" => $facturacion["azurian"], "documento" => $_POST["doc"]);
        }

        public function obtenerVenta($filtros, $parametros, $tipo, $descuento = true, $redondear = true){
            try {

                if($tipo == 0){
                    $obtener_ventas = "SELECT r.idSale, v.idVenta, v.estatus, v.idCliente, if(c.nombre IS NULL, 'Publico General', c.nombre) AS cliente, v.monto, idEmpleado, email, v.fecha, cambio, ".
                                    "montoimpuestos, observacion, impuestos, subtotal, descuentoGeneral ".
                                    "FROM app_pos_venta AS v LEFT JOIN comun_cliente AS c ON v.idCliente = c.id LEFT JOIN app_respuestaFacturacion AS r ON r.idSale = v.idVenta WHERE ". $filtros ." ORDER BY idVenta DESC LIMIT 10;";
                }else if($tipo == 1){
                    $obtener_ventas = "SELECT r.idSale, v.idVenta, v.estatus, v.idCliente, if(c.nombre IS NULL, 'Publico General', c.nombre) AS cliente, v.monto, idEmpleado, email, v.fecha, cambio, ".
                                    "montoimpuestos, observacion, impuestos, subtotal, descuentoGeneral ".
                                    "FROM app_pos_venta AS v LEFT JOIN comun_cliente AS c ON v.idCliente = c.id INNER JOIN app_respuestaFacturacion AS r ON r.idSale = v.idVenta WHERE ". $filtros ." ORDER BY idVenta DESC LIMIT 10;";
                }else if($tipo == 2){
                    $obtener_ventas = "SELECT r.idSale, v.idVenta, v.estatus, v.idCliente, if(c.nombre IS NULL, 'Publico General', c.nombre) AS cliente, v.monto, idEmpleado, email, v.fecha, cambio, ".
                                    "montoimpuestos, observacion, impuestos, subtotal, descuentoGeneral ".
                                    "FROM app_pos_venta AS v LEFT JOIN comun_cliente AS c ON v.idCliente = c.id LEFT JOIN app_respuestaFacturacion AS r ON r.idSale = v.idVenta WHERE ". $filtros ." AND r.idSale IS NULL ORDER BY idVenta DESC LIMIT 10;";
                }

                $obtener_ventas = $this->queryArray($obtener_ventas, $parametros);

                foreach ($obtener_ventas["rows"] as $ventaKey => $venta) {
                    $monto_total = 0;
                    $monto_subtotal = 0;
                    $monto_impuestos = 0;
                    $descuentoGeneral = 0;
                    $array_impuestos = array();
                    $array_productos = array();
                    $obtener_productos = "SELECT vp.idventa_producto AS idVentaProducto, vp.formulaIeps, p.id AS id, p.nombre, ".
                                        "p.ruta_imagen, p.descripcion_larga, p.codigo, p.tipo_producto, u.nombre AS unidad, vp.cantidad, vp.preciounitario AS precio, vp.subtotal, ".
                                        "vp.impuestosproductoventa, vp.total, vp.formulaIeps FROM app_pos_venta_producto AS vp ".
                                        "INNER JOIN app_productos AS p ON vp.idProducto = p.id INNER JOIN app_unidades_medida AS u ON u.id = p.id_unidad_venta WHERE vp.idVenta = :venta;";
                    $obtener_productos = $this->queryArray($obtener_productos, array('venta' => $venta["idVenta"]));

                    $total_suma = 0;
                    foreach ($obtener_productos["rows"] as $producto) {
                        $total_suma += $producto["precio"];
                    }

                    if($descuento){
                        $descuento = 0;
                        if($venta["descuentoGeneral"] != null && $venta["descuentoGeneral"] > 0){
                            $descuento = round(($venta["descuentoGeneral"] * 100) / $total_suma, 2);
                        }
                    }else{
                        $descuento = 0;
                    }
                    
                    $venta_sin_descuento = $this->calcularImpuestos($obtener_productos["rows"], 0, $redondear);
                    if($descuento > 0) $venta_con_descuento = $this->calcularImpuestos($obtener_productos["rows"], $descuento, $redondear);
                    else $venta_con_descuento = $venta_sin_descuento;

                    $monto_total = $venta_con_descuento["monto_total"];
                    $monto_subtotal = $venta_con_descuento["monto_subtotal"];
                    $monto_impuestos = $venta_con_descuento["monto_impuestos"];
                    $array_impuestos = $venta_con_descuento["impuestos_array"];
                    $array_productos = $venta_sin_descuento["productos"];

                    $obtener_ventas["rows"][$ventaKey]["impuestos"] = $array_impuestos;
                    $impuestos_array = array();
                    $i = 0;
                    foreach ($obtener_ventas["rows"][$ventaKey]["impuestos"] as $key => $impuesto) {
                        $impuestos_array[$i]["id"] = $impuesto["id"];
                        $impuestos_array[$i]["nombre"] = $impuesto["nombre"];
                        $impuestos_array[$i]["monto"] = ($redondear) ? round($impuesto["monto"], 2) : $impuesto["monto"];
                        $impuestos_array[$i]["clave"] = $impuesto["clave"];
                        $impuestos_array[$i]["porcentaje"] = $impuesto["porcentaje"];
                        $i++;
                    }
                    $obtener_ventas["rows"][$ventaKey]["monto"] = ($redondear) ? round($monto_total, 2) : $monto_total;
                    $obtener_ventas["rows"][$ventaKey]["montoimpuestos"] = ($redondear) ? round($monto_impuestos, 2) : $monto_impuestos;
                    $obtener_ventas["rows"][$ventaKey]["subtotal"] = ($redondear) ? round($monto_subtotal, 2) : $monto_subtotal;
                    $obtener_ventas["rows"][$ventaKey]["impuestos_array"] = $impuestos_array;
                    $obtener_ventas["rows"][$ventaKey]["descuentoGeneral"] = ($redondear) ? round($descuento, 2) : $descuento;
                    $obtener_ventas["rows"][$ventaKey]["productos"] = $array_productos;

                    $obtener_pagos = "SELECT tp.nombre, vp.monto, vp.referencia FROM app_pos_venta_pagos AS vp INNER JOIN forma_pago AS tp ON vp.idFormapago = tp.idFormapago WHERE idVenta = :venta;";
                    $obtener_pagos = $this->queryArray($obtener_pagos, array('venta' => $venta["idVenta"]));

                    $obtener_pagos["rows"][0]["monto"] = $obtener_ventas["rows"][$ventaKey]["monto"];
                    $obtener_ventas["rows"][$ventaKey]["pagos"] = $obtener_pagos["rows"];
                }
                $obtener_ventas["status"] = true;
                $obtener_ventas["insertId"] = 0;
                return $obtener_ventas;
            } catch (Exception $e) {
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener el detalle de la venta", "error" => $e->getMessage());
            }
        }

        public function eliminarVenta($id){
            try{
                $sel = 'UPDATE app_pos_venta set estatus = 0 where idVenta = :id';
                $res = $this->queryArray($sel, array('id' => $id));
                return  $res;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible eliminar la información", "error" => $e->getMessage());
            }
        }

        public function enviarTicket($email, $venta){
            $error = array("status" => false, "mensaje" => "No fue posible enviar el ticket");
            try{
                require 'libraries/phpmailer/sendMail.php';

                $servidor = explode("/", $_SERVER['REQUEST_URI']);
                $cliente = $servidor[array_search('clientes', $servidor) + 1];
                $longuitud = strlen($cliente);
                $codinstancia = $cliente[0] . $cliente[$longuitud - 1];
                $fecha = str_replace('-', '', $venta['fecha']);
                $fecha = str_replace(':', '', $fecha);
                $fecha = str_replace(' ', '', $fecha);
                $referencia = $codinstancia . dechex($fecha . $venta['idVenta']);

                $json_dec = $venta;
                $cuerpo_html = '<!doctype html><html><head><meta charset="utf-8"><title>Appministra Ticket</title><style>.invoice-box{max-width:800px;margin:auto;padding:10px;border:1px solid #eee;box-shadow:0 0 10px rgba(0, 0, 0, .15);font-size:16px;line-height:24px;font-family:\'Helvetica Neue\', \'Helvetica\', Helvetica, Arial, sans-serif;color:#555;}.invoice-box table{width:100%;line-height:inherit;text-align:left;}.invoice-box table td{padding:5px;vertical-align:top;}.invoice-box table tr td:nth-child(4){text-align:right;}.invoice-box table tr.top table td{padding-bottom:20px;}.invoice-box table tr.top table td.title{font-size:45px;line-height:45px;color:#333;}.invoice-box table tr.information table td{padding-bottom:40px;}.invoice-box table tr.heading td{background:#eee;border-bottom:1px solid #ddd;font-weight:bold;}.invoice-box table tr.details td{padding-bottom:20px;}.invoice-box table tr.item td{border-bottom:1px solid #eee;}.invoice-box table tr.item.last td{border-bottom:none;}.invoice-box table tr.total td:nth-child(2){border-top:2px solid #eee;font-weight:bold;}@media only screen and (max-width: 600px) {.invoice-box table tr.top table td{width:100%;display:block;text-align:center;}.invoice-box table tr.information table td{width:100%;display:block;text-align:center;}}</style></head><body><div class="invoice-box"><table cellpadding="0" cellspacing="0"><tr class="top"><td colspan="4"><table><tr><td class="title"><img src="https://store.netwarmonitor.com/images/iconos/appministramax.png" style="width:100%; max-width:300px;"></td><td></td><td></td><td></td><td></td><td style="text-align:left!important"><b>Fecha:</b><br>'. $json_dec["fecha"] .'<br><b>Cliente:</b><br>'. $json_dec["cliente"] .'</td>';
                $cuerpo_html .= '</tr></table></td></tr><tr class="heading"><td>Producto</td><td>Cantidad</td><td>Precio</td><td>Subtotal</td></tr>';
                
                $array_productos = $json_dec["productos"];
                for ($i = 0; $i < count($array_productos); $i++) {
                    $precio_producto = round($array_productos[$i]["precio"], 2);
                    $precio_subtotal = round($array_productos[$i]["subtotal"], 2);
                    $cuerpo_html .= '<tr class="item"><td>'.substr($array_productos[$i]["nombre"], 0, 25).'</td><td>'.$array_productos[$i]["cantidad"].'</td><td>$'. number_format($precio_producto, 2) .'</td><td>$'. number_format($precio_subtotal, 2) .'</td></tr>'; 
                }
                $cuerpo_html .= '<tr class="heading"><td>Impuestos</td><td></td><td></td><td></td></tr>';
                
                $array_impuestos = $json_dec["impuestos_array"];
                for ($i = 0; $i < count($array_impuestos); $i++) {
                    $precio_impuesto = round($array_impuestos[$i]["monto"], 2);
                    $cuerpo_html .= '<tr class="item"><td>'.$array_impuestos[$i]["nombre"].'</td><td></td><td></td><td>$'. number_format($precio_impuesto, 2) .'</td></tr>';
                }
                $descuento_general = round($json_dec["descuentoGeneral"], 2);
                $costo_subtotal = round($json_dec["subtotal"], 2);
                $costo_total = round($json_dec["monto"], 2);
                $cuerpo_html .= '<tr class="heading"><td></td><td></td><td></td><td></td></tr><tr class="Descuento"><td></td><td></td><td>Descuento:</td><td>'. number_format($descuento_general, 2) .'%</td></tr><tr class="Subtotal"><td></td><td></td><td>Subtotal:</td><td>$'. number_format($costo_subtotal, 2) .'</td></tr><tr class="Total"><td></td><td></td><td>Total:</td><td>$'. number_format($costo_total, 2) .'</td></tr><tr class="heading"><td> Métodos de pago</td><td>Monto</td><td>Referencia</td><td></td></tr>';

                $array_pagos = $json_dec["pagos"];
                for ($i = 0; $i < count($array_pagos); $i++) {
                    $monto_pago = round($array_pagos[$i]["monto"], 2);
                    $cuerpo_html .= '<tr class="item"><td>'.$array_pagos[$i]["nombre"].'</td><td>$'. number_format($monto_pago, 2) .'</td><td>'.$array_pagos[$i]["referencia"].'</td><td></td></tr>';
                }
                $cuerpo_html .= '<tr class="heading"><td></td><td></td><td></td><td></td></tr>';
                $cuerpo_html .= '<tr><td colspan=2>Para obtener su factura ingrese a <a href="http://netwarmonitor.mx/clientes/'. $cliente .'/kiosko" target="_blank">netwarmonitor.mx/clientes/'. $cliente .'/kiosko</a> e ingrese el siguiente código: <b>'. $referencia .'</b></td></tr>';
                $cuerpo_html .= '</table></div></body></html>';

                $mail->IsHTML(true);
                $mail->setFrom("mailer@netwarmonitor.com", "Appministra Lite");
                $mail->Subject = "Ticket de compra";
                $mail->Body = $cuerpo_html;
                $mail->AddAddress($email);
                $respuesta = $mail->Send();

                if(!$respuesta) return $error;
            }catch(Exception $e){
                return $error;
            }
            return array("status" => true);
        }

        public function suspenderVenta($cliente, $doc, $impuestos, $monto, $sucursal, $clienteNombre, $productos, $descuento, $empleado){
            date_default_timezone_set("Mexico/General");
            $fechaactual = date("Y-m-d H:i:s");
            $almacen = 0;
            $cambio = 0;
            $idFact = 0;

            $productos = $this->generarArrayProductos($productos);
            $venta_sin_descuento = $this->calcularImpuestos($productos, 0, true);
            $venta_con_descuento = $this->calcularImpuestos($productos, $descuento, true);

            $arr = $this->crearArrayCarrito($venta_con_descuento, $venta_sin_descuento);
            $arr = json_encode((object) $arr);
            $arr2 ='{"pagos":{},"Abonado":"0.00","porPagar":"0.00","cambio":"0.00"}'; 
            $identi = $clienteNombre . " - " . $fechaactual . " - $" . $monto;

            $insert = "INSERT INTO app_pos_venta_suspendida (s_almacen,s_cambio,s_cliente,s_documento,s_empleado,s_funcion,s_idFact,s_impuestos,s_monto,s_pagoautomatico,s_sucursal,s_impuestost,arreglo1,arreglo2,identi,fecha) VALUES "
            . "(:almacen, :cambio, :cliente, :doc, :empleado,'suspenderVenta', :idFact, :impuestos, :monto,0, :sucursal, :impuestos, :arr, :arr2, :identi, :fechaactual);";

            $parametros = array(
                            'almacen' => $almacen,
                            'cambio' => $cambio,
                            'cliente' => $cliente,
                            'doc' => $doc,
                            'empleado' => $empleado,
                            'idFact' => $idFact,
                            'impuestos' => $impuestos,
                            'monto' => $monto,
                            'sucursal' => $sucursal,
                            'impuestos' => $impuestos,
                            'arr' => $arr,
                            'arr2' => $arr2,
                            'identi' => $identi,
                            'fechaactual' => $fechaactual
                            );
        
            $result = $this->queryArray($insert, $parametros);
            
            return $result;
        }

        public function crearArrayCarrito($venta_con_descuento, $venta_sin_descuento){
            $facturar = array();
            
            foreach ($venta_sin_descuento["productos"] as $producto) {
                $facturar[$producto["id"]]["idProducto"] = $producto["id"];
                $facturar[$producto["id"]]["codigo"] = $producto["codigo"];
                $facturar[$producto["id"]]["nombre"] = $producto["nombre"];
                $facturar[$producto["id"]]["descripcion"] = $producto["descripcion_larga"];
                $facturar[$producto["id"]]["unidad"] = "PIEZA";
                $facturar[$producto["id"]]["idunidad"] = 1;
                $facturar[$producto["id"]]["precio"] = $producto["precio"];
                $facturar[$producto["id"]]["cantidad"] = $producto["cantidad"];
                $facturar[$producto["id"]]["ruta_imagen"] = $producto["ruta_imagen"];
                $facturar[$producto["id"]]["importe"] = $producto["total"] - $producto["suma_impuestos"];
                $facturar[$producto["id"]]["impuesto"] = $producto["suma_impuestos"];
                $facturar[$producto["id"]]["suma_impuestos"] = 0;
                foreach ($producto["impuestos"] as $impuesto) {
                    $facturar[$producto["id"]]["cargos"][$impuesto["nombre"]] = $impuesto["monto"];
                }
                $facturar[$producto["id"]]["formula"] = $producto["formulaIeps"];
                $facturar[$producto["id"]]["caracteristicas"] = "";
                $facturar[$producto["id"]]["tipoProducto"] = $producto["tipo_producto"];
            }
            $facturar["descGeneral"] = $venta_con_descuento["descuentoGeneral"];
            foreach ($venta_con_descuento["impuestos_array"] as $impuesto) {
                if(!isset($facturar["cargos"]["impuestos"][$impuesto["clave"]])) $facturar["cargos"]["impuestos"][$impuesto["clave"]] = 0;
                $facturar["cargos"]["impuestos"][$impuesto["clave"]] += $impuesto["monto"];
                $facturar["cargos"]["impuestosPorcentajes"][$impuesto["nombre"]] = $impuesto["monto"];
                $facturar["cargos"]["impuestosFactura"][$impuesto["clave"]][$impuesto["porcentaje"]] = $impuesto["monto"];
                $facturar["cargos"]["impuestosPdf"][$impuesto["clave"]][$impuesto["porcentaje"]]["Valor"] = $impuesto["monto"];
            }
            $facturar["cargos"]["subtotal"] = $venta_con_descuento["subtotal"];
            $facturar["cargos"]["total"] = $venta_con_descuento["monto"];

            return $facturar;
        }

        function listarVentasSuspendidas($filtros, $parametros){
            try{
                $select = "SELECT * FROM app_pos_venta_suspendida WHERE ". $filtros .";";
                $resultSelect = $this->queryArray($select, $parametros);
                return $resultSelect;
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        function continuarVenta($id){
            try{
                $select = "SELECT * FROM app_pos_venta_suspendida WHERE id = :id;";
                $parametros = array('id' => $id);
                $resultSuspendida = $this->queryArray($select, $parametros);
                
                if ($resultSuspendida["total"] > 0) {
                $pos = strpos($resultSuspendida["rows"][0]["arreglo1"], "\"\"");
                $json = json_decode($resultSuspendida["rows"][0]["arreglo1"], true);

                $i = 0;
                foreach ($json as $key => $producto) {
                    
                    if(preg_match('/^[0-9]*$/', $key)){
                        $productos[$i] = $json[$key]; 
                        $i +=1;  
                    }
                }
                return array("status" => true, "total" => $i, "rows" => $productos, "insertId" => 0); 
            }
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }


        function eliminarVentaSuspendida($id){
            try{
                $delete = "DELETE FROM app_pos_venta_suspendida WHERE id = :id;";
                $parametros = array('id' => $id);
                $resultDelete = $this->queryArray($delete, $parametros);

                return $resultDelete;

            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible obtener la información", "error" => $e->getMessage());
            }
        }

        function prepararSession($empleado){
            try{
                if(!isset($_SESSION)); session_start();
                //CONFIGURAR PARAMETROS DE SESSION
                $_SESSION["accelog_idempleado"] = $empleado;
                $_SESSION["sucursal"] = $_REQUEST["sucursal"];
                $productos = json_decode($_REQUEST["venta"], true);
                //AGREGA PRODUCTOS
                foreach ($productos["venta"] as $producto) {
                    $info_producto = "SELECT codigo FROM app_productos WHERE id = :id;";
                    $info_producto = $this->queryArray($info_producto, array('id' => $producto["producto"]));
                    $_POST["id"] = $info_producto["rows"][0]["codigo"];
                    $_POST["cantidad"] = $producto["cantidad"];
                    $_POST['caracter'] = '';
                    $_POST['cliente'] = '';
                    $_POST['series'] = '';
                    $_POST['lotes'] = '';
                    $this->CajaController->agregaProducto();
                }
                //AGREGA DESCUENTO GLOBAL
                unset($_POST);
                if($_REQUEST["descuento"] > 0){
                    $_POST['descuento'] = $_REQUEST["descuento"];
                    $this->CajaController->descuentoGeneral();
                }
                //AGREGA PAGO
                unset($_POST);
                $info_tipo_pago = "SELECT claveSat, nombre FROM forma_pago WHERE idFormapago = :id AND activo = :activo;";
                $info_tipo_pago = $this->queryArray($info_tipo_pago, array("id" => $_REQUEST["tipo_pago"], "activo" => 1));
                $info_tipo_pago = $info_tipo_pago["rows"][0];
                $str_tipo_pago = "(". $info_tipo_pago["claveSat"] .")+". $info_tipo_pago["nombre"];
                $_POST["tipo"] = $_REQUEST["tipo_pago"];
                $_POST["tipostr"] = $str_tipo_pago;
                $_POST["cantidad"] = $_SESSION["caja"]["cargos"]["total"];
                $_POST["txtReferencia"] = $_REQUEST["referencia"];
                $_POST['tarjeta'] = $_REQUEST["tarjeta"];
                $this->CajaController->agregaPago();
            }catch(Exception $e){
                return array("status" => false, "rows" => array(), "insertId" => 0, "mensaje" => "No fue posible reformar la información", "error" => $e->getMessage());
            }
        }

    }

?>
