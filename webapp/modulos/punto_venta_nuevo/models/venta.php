<?php 
//ini_set('display_errors', 1);
//require("connection_sqli.php");

class Venta extends Connection{

    public $venta;

    function __construct() {
       // $connection = new Connection();
    }

    function ventaPrueba(){
        echo 'XXXXXX';
        $x='entro a prueba';
        return $x;
    }
    function ventaSencilla($idProducto,$cantidad){
        unset($_SESSION['casio']);
        $idProducto = 4208;

            $queryProducto = "SELECT p.idProducto, p.nombre, p.precioventa,p.idunidad,u.compuesto ";
            $queryProducto .=" FROM mrp_producto p,mrp_unidades u WHERE p.idunidad=u.idUni and p.idProducto=".$idProducto;
            $result = $this->queryArray($queryProducto);
            $importe=$result["rows"][0]["precioventa"];
                /*if (!isset($_SESSION['casio'])) {
                    session_start();
                } */
          /*  $E=$cantidad;
            $importe=$result["rows"][0]["precioventa"]*$cantidad;
           /* $_SESSION['casio'] = $this->object_to_array($_SESSION['casio']);
            foreach ($_SESSION['casio'] as $key => $value) {
                if($key==$idProducto){
                    $E=$value['cantidad']+$cantidad;
                    $importe=$value['precio']*$E;
                }
            } */
           // echo $E;
            $arraySession = new stdClass();

            $arraySession->cantidad = $cantidad;
            $arraySession->idProducto = $result["rows"][0]["idProducto"];
            $arraySession->nombre = $result["rows"][0]["nombre"];
            $arraySession->idunidad = $result["rows"][0]["idunidad"];
            $arraySession->unidad = $result["rows"][0]["compuesto"];
            $arraySession->precio = $result["rows"][0]["precioventa"];
            $arraySession->importe = $importe;
            $arraySession->impuesto = array();

            $_SESSION['casio'][$result["rows"][0]["idProducto"]]=$arraySession; 

            //print_r($_SESSION['casio']); 
            $x=$this->calculaImpuestos();

            ///inserciones 
            date_default_timezone_set("Mexico/General");
            $fecha = date("Y-m-d H:i:s");

            $monto = $_SESSION['casio']['charges']['Tot'];
            $montoimpuestos = $_SESSION['casio']['charges']['taxesTot'];

            //echo 'monrto='.$monto;
            //echo 'impuesto'.$montoimpuestos;
            $idCliente = 1268;
            $idEmpleado = 3;
            $sucursal = 1;
            $insertVenta = "INSERT INTO venta(idCliente,monto,estatus,idEmpleado,documento,fecha,cambio,montoimpuestos,idSucursal)";
            $insertVenta .=" values('".$idCliente."','".$monto."','1','".$idEmpleado."','1','".$fecha."','0.00','".$montoimpuestos."','".$sucursal."')";
            $resultVinsert = $this->queryArray($insertVenta);
            $idVenta = $resultVinsert["insertId"];
            //echo $insertVenta;  

           // $_SESSION['casio'] = $this->object_to_array($_SESSION['casio']);
            //print_r($_SESSION['casio']);
            $montoimpuestosProducto = 0.00;
            $total = 0.00;

            foreach ($_SESSION['casio'] as $key => $value) {
                if($key!='charges'){

                    foreach ($_SESSION['casio'][$key]['impuesto'] as $key2 => $value2) {
                        $montoimpuestosProducto += $value2;
                    }
                    $total = $value['importe']+$montoimpuestosProducto;
                    /*---- Insert producto de las ventas*/
                    $insertVePro = "INSERT INTO venta_producto(idProducto,cantidad,preciounitario,subtotal,idVenta,impuestosproductoventa,total)";
                    $insertVePro .=" VALUES('".$key."','".$value['cantidad']."','".$value['precio']."','".$value['importe']."','".$idVenta."','".$montoimpuestosProducto."','".$total."')";
                   // echo $insertVePro;
                    $restInsertBePro = $this->queryArray($insertVePro);
                    $idVentaProd = $restInsertBePro["insertId"];

                    $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre,i.id";
                    $queryImpuestos .= " from impuesto i, mrp_producto p ";
                    $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                    $queryImpuestos .= " where p.idProducto=".$value['idProducto']." and i.id=pi.idImpuesto ";
                    $queryImpuestos .= " Order by pi.idImpuesto asc ";

                    $result = $this->queryArray($queryImpuestos);
                    foreach ($result['rows'] as $key3 => $value3) {
                        $insertVeProImp = "INSERT INTO venta_producto_impuesto(idVentaproducto,idImpuesto,porcentaje)";
                        $insertVeProImp .=" VALUES('".$idVentaProd."','".$value3['id']."','".$value3['valor']."')";
                        //echo $insertVeProImp;
                        $resultVPI = $this->queryArray($insertVeProImp);
                    }

                                    
                }

            } /*
             /*--- Insert de los pagos y forma de pago */

            $insertPago = "INSERT INTO venta_pagos(idVenta,idFormapago,monto) VALUES('".$idVenta."','1','".$monto."')";
            $res = $this->queryArray($insertPago);
           // print_r($_SESSION['casio']);
            /*--------- Parte para crear el pvt_penditeFacurar---------------------*/
            $parametros['Receptor'] = array();
            $parametros['Receptor']['RFC'] = "XAXX010101000";

            $formapago  = 'Efectivo';

            $parametros['DatosCFD']['FormadePago'] = "Pago en una sola exhibicion";
            $parametros['DatosCFD']['MetododePago'] = utf8_decode($formapago);
            $parametros['DatosCFD']['Moneda'] = "MXP";
            $parametros['DatosCFD']['Subtotal'] = str_replace(",", "", number_format($_SESSION["casio"]["charges"]["sbtot"],2));
           // $parametros['DatosCFD']['Subtotal'] = $parametros['DatosCFD']['Subtotal'] - 0.01;
            $parametros['DatosCFD']['Total'] = str_replace(",", "", number_format($_SESSION["casio"]["charges"]["Tot"],2));
           // $parametros['DatosCFD']['Total'] = $parametros['DatosCFD']['Total'] - 0.01;
            $parametros['DatosCFD']['Serie'] = $data['serie'];
            $parametros['DatosCFD']['Folio'] = $data['folio'];
            $parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
            $parametros['DatosCFD']['MensajePDF'] = "";
            $parametros['DatosCFD']['LugarDeExpedicion'] = "Mexico";

            $x = 0;
            $textodescuento = "";

            foreach ($_SESSION['casio'] as $key => $producto) {
            if ($key != 'charges') {
                
                $producto = (object) $producto;
                $descuentogeneral = 0;
                //echo "( descuento -> ".$producto->descuento_cantidad.")";
                $conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
                $conceptosDatos[$x]["Unidad"] = $producto->unidad;
                $conceptosDatos[$x]["Precio"] = $producto->precio;
                /*if ($producto->descripcion != '') {
                        $conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion);
                } else { */
                        $conceptosDatos[$x]["Descripcion"] = trim($producto->nombre);
                //}
                $textodescuento = '';
                $conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio);
                $x++;

                //print_r($conceptosDatos);

                $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
                $queryImpuestos .= " from impuesto i, mrp_producto p ";
                $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                $queryImpuestos .= " where p.idProducto=" . $producto->idProducto . " and i.id=pi.idImpuesto ";
                $queryImpuestos .= " Order by pi.idImpuesto DESC ";
                //echo $queryImpuestos;
                $resultImpuestos = $this->queryArray($queryImpuestos);

                foreach ($resultImpuestos["rows"] as $key => $value) {

                    if ($value["nombre"] == 'IEPS') {
                        $calculos = str_replace(",", "", ((($producto->precio * $producto->cantidad) * $value["valor"])) / 100);
                        $nn2[$value["nombre"]][$value["valor"]]["Valor"] += $calculos;
                        $ieps = $calculos;
                    } else {
                        if ($ieps != 0) {
                            $nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", (((($producto->precio * $producto->cantidad) + $ieps) * $value["valor"]) ) / 100);
                            //$nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", number_format((((($producto->precioventa * $producto->cantidad) - str_replace(",", "", $producto->descuento_neto)) * $value["valor"]) ) / 100, 2));
                        } else {
                            $nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", ((($producto->precio * $producto->cantidad) * $value["valor"])) / 100);

                            //echo "(".$producto->precioventa  ."*". $producto->cantidad ."-". str_replace(",", "", $producto->descuento_neto) ."*". $value["valor"].")";
                        }
                    }

                    //$nn2[$value["nombre"]][$value["valor"]]["Valor"] = $_SESSION['caja']["cargos"]["impuestos"][$value["nombre"]];
                }
            }
        } //fin del for
       /* print_r($nn2);
        echo '<br />';
        echo 'azurian';
        echo '<br />'; */
        //var_dump($parametros);
            /* FACTURACION AZURIAN
            ============================================================== */
           // require_once('../../modulos/SAT/config.php');

            date_default_timezone_set("Mexico/General");
            $fecha = date('Y-m-d') . 'T' . date('H:i:s', strtotime("-5 minute"));


            $logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
            $logo = $this->queryArray($logo);
            $r3 = $logo["rows"][0];

            $azurian = array();
                        /* Observaciones pdf */
            $azurian['Observacion']['Observacion'] = '';

            /* CORREO RECEPTOR
            ============================================================== */
            if ($nn2 == '') {
                $nn2["IVA"]["0.0"]["Valor"] = 0.00;
            }
            $nn = $nn2;
            $azurian['nn']['nn'] = $nn;
            $azurian['org']['logo'] = $r3["logoempresa"];

            /* CORREO RECEPTOR
            ============================================================== */
            $azurian['Correo']['Correo'] = $Email;

            /* Datos Basicos
            ============================================================== */
            $azurian['Basicos']['Moneda'] = $parametros['DatosCFD']['Moneda'];
            $azurian['Basicos']['metodoDePago'] = $parametros['DatosCFD']['MetododePago'];
            $azurian['Basicos']['LugarExpedicion'] = $parametros['DatosCFD']['LugarDeExpedicion'];
            $azurian['Basicos']['version'] = '3.2';
            $azurian['Basicos']['serie'] = $parametros['DatosCFD']['Serie']; //No obligatorio
            $azurian['Basicos']['folio'] = $parametros['DatosCFD']['Folio']; //No obligatorio
            $azurian['Basicos']['fecha'] = $fecha;
            $azurian['Basicos']['sello'] = '';
            $azurian['Basicos']['formaDePago'] = $parametros['DatosCFD']['FormadePago'];
            $azurian['Basicos']['tipoDeComprobante'] = 'ingreso';
            $azurian['tipoFactura'] = 'factura';
            $azurian['Basicos']['noCertificado'] = '';
            $azurian['Basicos']['certificado'] = '';
            $str_subtotal = number_format($parametros['DatosCFD']['Subtotal'], 2);
            $azurian['Basicos']['subTotal'] = str_replace(",", "", $str_subtotal);
            $str_total = number_format($parametros['DatosCFD']['Total'], 2);
            $str_total = str_replace(',', '',$str_total);
            //$str_total = $str_total - 0.01;
            //$str_total = number_format($str_total,0).'.00';  //Comente para que Salgan Decimales Normalmente
            $str_total = number_format($str_total,2);
            $azurian['Basicos']['total'] = str_replace(",", "", $str_total);    
            /* Datos Emisor
            ============================================================== */

            $azurian['Emisor']['rfc'] = strtoupper($parametros['EmisorTimbre']['RFC']);
            $azurian['Emisor']['nombre'] = strtoupper($parametros['EmisorTimbre']['RazonSocial']);

            /* Datos Fiscales Emisor
            ============================================================== */

            $azurian['FiscalesEmisor']['calle'] = $parametros['EmisorTimbre']['Calle'];
            $azurian['FiscalesEmisor']['noExterior'] = $parametros['EmisorTimbre']['NumExt'];
            $azurian['FiscalesEmisor']['colonia'] = $parametros['EmisorTimbre']['Colonia'];
            $azurian['FiscalesEmisor']['localidad'] = $parametros['EmisorTimbre']['Ciudad'];
            $azurian['FiscalesEmisor']['municipio'] = $parametros['EmisorTimbre']['Municipio'];
            $azurian['FiscalesEmisor']['estado'] = $parametros['EmisorTimbre']['Estado'];
            $azurian['FiscalesEmisor']['pais'] = $parametros['EmisorTimbre']['Pais'];
            $azurian['FiscalesEmisor']['codigoPostal'] = $parametros['EmisorTimbre']['CP'];

            /* Datos Regimen
            ============================================================== */

            $azurian['Regimen']['Regimen'] = $parametros['EmisorTimbre']['RegimenFiscal'];

                   /* Datos Receptor
            ============================================================== */

            $azurian['Receptor']['rfc'] = strtoupper($parametros['Receptor']['RFC']);
            $azurian['Receptor']['nombre'] = strtoupper($parametros['Receptor']['RazonSocial']);

            /* Datos Domicilio Receptor
            ============================================================== */

            $azurian['DomicilioReceptor']['calle'] = $parametros['Receptor']['Calle'];
            $azurian['DomicilioReceptor']['noExterior'] = $parametros['Receptor']['NumExt'];
            $azurian['DomicilioReceptor']['colonia'] = $parametros['Receptor']['Colonia'];
            $azurian['DomicilioReceptor']['localidad'] = $parametros['Receptor']['Ciudad'];
            $azurian['DomicilioReceptor']['municipio'] = $parametros['Receptor']['Municipio'];
            $azurian['DomicilioReceptor']['estado'] = $parametros['Receptor']['Estado'];
            $azurian['DomicilioReceptor']['pais'] = $parametros['Receptor']['Pais'];
            $azurian['DomicilioReceptor']['codigoPostal'] = $parametros['Receptor']['CP'];

            $conceptosOri = '';
            $conceptos = '';    

            foreach ($conceptosDatos as $key => $value) {
                $conceptosOri.='|' . $value['Cantidad'] . '|';
                $conceptosOri.=$value['Unidad'] . '|';
                $conceptosOri.=$value['Descripcion'] . '|';
                $conceptosOri.=str_replace(",", "", $value['Precio']) . '|';
                $conceptosOri.=str_replace(",", "", $value['Importe']);
                $conceptos.="<cfdi:Concepto cantidad='" . $value['Cantidad'] . "' unidad='" . $value['Unidad'] . "' descripcion='" . $value['Descripcion'] . "' valorUnitario='" . str_replace(",", "", $value['Precio']) . "' importe='" . str_replace(",", "", $value['Importe']) . "'/>";
            }
                $ivas = '';
                $tisr = 0.00;
                $tiva = 0.00;
                $tieps = 0.00;

                $oriisr = '';
                $oriiva = '';

                $isr = '';
                $iva = '';
                $azurian['Conceptos']['conceptos'] = $conceptos;
                $azurian['Conceptos']['conceptosOri'] = $conceptosOri;

                $traslads = '';
                $retenids = '';
                $haytras = 0;
                $hayret = 0;
                $trasladsimp = 0.00;
                $retenciones = 0.00;
                $trasxml = '';
                $retexml = '';
            foreach ($nn as $clave => $imm) {
                if ($clave == 'IEPS' || $clave == 'IVA') {

                    $haytras = 1;
                    foreach ($nn[$clave] as $clavetasa => $val) {
                        if ($clave == 'IEPS') {
                            $tieps+=number_format($val['Valor'], 2, '.', '');
                        }
                        if ($clave == 'IVA') {
                            $tiva+=number_format($val['Valor'], 2, '.', '');
                        }
                        $traslads.='|' . $clave . '|';
                        $traslads.='' . $clavetasa . '|';
                        $traslads.=number_format($val['Valor'], 2, '.', '');
                        $trasladsimp+=number_format($val['Valor'], 2, '.', '');
                        $trasxml.="<cfdi:Traslado impuesto='" . $clave . "' tasa='" . $clavetasa . "' importe='" . number_format($val['Valor'], 2, '.', '') . "' />";
                    }
                } elseif ($clave == 'ISR') {
                    $hayret = 1;
                    foreach ($nn[$clave] as $clavetasa => $val) {
                        $tisr+=number_format($val['Valor'], 2, '.', '');
                        $retenids.='|' . $clave . '|';
                        $retenids.='' . number_format($val['Valor'], 2, '.', '') . '|';
                        $retenids.=number_format($val['Valor'], 2, '.', '');
                        $retenciones+=number_format($val['Valor'], 2, '.', '');
                        $retexml.="<cfdi:Retencion impuesto='" . $clave . "' importe='" . number_format($val['Valor'], 2, '.', '') . "' />";
                    }
                }
            }
            $azurian['Impuestos']['totalImpuestosIeps'] = $tieps;
            if ($haytras == 1) {
                $iva.='<cfdi:Traslados>' . $trasxml . '</cfdi:Traslados>';
            } else {
                $traslads.='|IVA|';
                $traslads.='0.00|';
                $traslads.='0.00';
                $trasladsimp = '0.00';
                $iva.="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='0.00' importe='0.00' /></cfdi:Traslados>";
            }
            if ($hayret == 1) {
                $isr.='<cfdi:Retenciones>' . $retexml . '</cfdi:Retenciones>';
            }
            $azurian['Impuestos']['isr'] = $retenids;
            $azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');

            $azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
            $azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');

                  $ivas.=$isr . $iva;

            $azurian['Impuestos']['ivas'] = $ivas;

            $azurian = base64_encode(json_encode($azurian));
            $trackId = 0;
            $tipo = 'F';
            $query = "insert into pvt_pendienteFactura values(''," . $idVenta . ",'" . $fecha . "',NULL,'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','" . $tipo . "');";
            //echo $query;
            $resultquery = $this->queryArray($query);
           /* echo '?'.$retenids.'?';            
            print_r($azurian['DomicilioReceptor']); */
            return $idVenta;
    } 
        function calculaImpuestos(){
        
        
        $_SESSION["casio"]["charges"]["taxes"]['IVA'] = 0.0;
        $_SESSION["casio"]["charges"]["taxes"]['IEPS'] = 0.0;
        $_SESSION["casio"]["charges"]["taxes"]['test'] = 0.0;
        $_SESSION["casio"]["charges"]["taxes"]['ISH'] = 0.0;
        $_SESSION["casio"]["charges"]["taxes"]['ISR'] = 0.0;
        $_SESSION['casio'] = $this->object_to_array($_SESSION['casio']);
            $ieps=0;
            
            foreach ($_SESSION['casio'] as $key => $value) {
                if($key!='charges'){
                    $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
                    $queryImpuestos .= " from impuesto i, mrp_producto p ";
                    $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                    $queryImpuestos .= " where p.idProducto=".$value['idProducto']." and i.id=pi.idImpuesto ";
                    $queryImpuestos .= " Order by pi.idImpuesto asc ";

                    $result = $this->queryArray($queryImpuestos);

                    $subtotal=$value['precio']*$value['cantidad'];

                    foreach ($result['rows'] as $key2 => $value2) {
                        if($value2['nombre']=='IEPS'){
                             $producto_impuesto += $ieps = (($subtotal) * $value2["valor"] / 100);

                        }else{
                            if($ieps != 0){
                                $producto_impuesto += ((($subtotal + $ieps)) * $value2["valor"] / 100);
                            }else{
                                $producto_impuesto += (($subtotal) * $value2["valor"] / 100);
                            }
                        }
                       
                        $_SESSION["casio"]["charges"]["taxes"][$value2["nombre"]]=$producto_impuesto;
                        $_SESSION["casio"][$key]['impuesto'][$value2["nombre"]]=$producto_impuesto;
                    }
                    /* $_SESSION["casio"][$key]->impuesto = 'ESto es algo';
                     foreach ($_SESSION['casio'][$key]['impuesto'] as $key => $value) {
                         
                     }*/
                }
            }
            $_SESSION['casio'] = $this->object_to_array($_SESSION['casio']);
            foreach ($_SESSION["casio"] as $key => $value) {
                if($key!='charges'){
                    $subtot+=$value['importe'];
                    echo 'value='.$value['importe'];
                    echo 'subto='.$subtot;
                }   
            }
            foreach ($_SESSION["casio"]["charges"]["taxes"] as $key => $value) {
                $taxesTot +=$value; 
                    
            }

            $_SESSION["casio"]["charges"]["sbtot"] = $subtot; 
            $_SESSION["casio"]["charges"]["taxesTot"] = $taxesTot;
            $_SESSION["casio"]["charges"]["Tot"] = $subtot + $taxesTot;

            //print_r($_SESSION['casio']);
    } 
        function object_to_array($data) {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = $this->object_to_array($value);
                }
                return $result;
            }
            return $data;
        } 


}



















?>