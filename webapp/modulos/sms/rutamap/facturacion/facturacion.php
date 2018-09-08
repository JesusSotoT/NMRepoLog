<?php
    function facturar($idFact, $idVenta, $bloqueo, $mensaje) {


        $_SESSION["caja"] = $this->object_to_array($_SESSION["caja"]);

        $monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
        $impuestos = 0;

        $arraytmp = (object) $_SESSION['caja'];
        foreach ($arraytmp as $key => $producto) {
            if ($key != 'cargos') {
                $impuestos = 0;
                foreach ($producto->cargos as $key2 => $value2) {
                    $impuestos += $value2;
                }
            }
        }

        if ($memsaje != false || $mensaje != '') {
            $updateVenta = $this->queryArray("Update venta set observacion = '" . $mensaje . "' where idVenta =" . $idVenta);
        }

        $folios = "SELECT serie,folio FROM pvt_serie_folio LIMIT 1";

        $data = $this->queryArray($folios);
        if ($data["total"] > 0) {
            $data = $data["rows"][0];
        }


        $df = (object) $this->datosFacturacion($idFact);


        // Receptor
        //===============================================================
        $parametros['Receptor'] = array();
        $parametros['Receptor']['RFC'] = $df->rfc;
        $parametros['Receptor']['RazonSocial'] = utf8_decode($df->razon_social);
        $parametros['Receptor']['Pais'] = utf8_decode($df->pais);
        $parametros['Receptor']['Calle'] = utf8_decode($df->domicilio);
        $parametros['Receptor']['NumExt'] = $df->num_ext;
        $parametros['Receptor']['Colonia'] = utf8_decode($df->colonia);
        $parametros['Receptor']['Municipio'] = utf8_decode($df->municipio);
        $parametros['Receptor']['Ciudad'] = utf8_decode($df->ciudad);
        $parametros['Receptor']['CP'] = $df->cp;
        $parametros['Receptor']['Estado'] = utf8_decode($df->estado);
        $parametros['Receptor']['Email1'] = $df->correo;


        //Obteniendo la descripcion de la forma de pago
        $formapago = "";

        $queryFormaPago = " select nombre,referencia from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;

        $resultqueryFormaPago = $this->queryArray($queryFormaPago);

        foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
            if (strlen($pagosValue["referencia"]) > 0) {
                $formapago .= $pagosValue['nombre'] . " Ref:" . $pagosValue['referencia'] . ",";
            } else {
                $formapago .= $pagosValue['nombre'] . ",";
            }
        }

        $formapago = substr($formapago, 0, strlen($formapago) - 1);

        if ($formapago == "") {
            $formapago = ".";
        }



        $Email = $df->correo;


        $parametros['DatosCFD']['FormadePago'] = "Pago en una sola exhibicion";
        $parametros['DatosCFD']['MetododePago'] = utf8_decode($formapago);
        $parametros['DatosCFD']['Moneda'] = "MXP";
        $parametros['DatosCFD']['Subtotal'] = str_replace(",", "", $_SESSION["caja"]["cargos"]["subtotal"]);
        $parametros['DatosCFD']['Total'] = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
        $parametros['DatosCFD']['Serie'] = $data['serie'];
        $parametros['DatosCFD']['Folio'] = $data['folio'];
        $parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
        $parametros['DatosCFD']['MensajePDF'] = "";
        $parametros['DatosCFD']['LugarDeExpedicion'] = "Mexico";

        $x = 0;
        $textodescuento="";

        foreach ($_SESSION['caja'] as $key => $producto) {
            if ($key != 'cargos') {

                $producto = (object) $producto;
                $descuentogeneral = 0;
                //echo "( descuento -> ".$producto->descuento_cantidad.")";
                if($producto->tipodescuento=="%")
                {
                    $descuentogeneral=(($producto->precioventa*$producto->descuento)/100)*$producto->cantidad;
                    if($producto->descuento>0)
                    {
                        $textodescuento.=" - ".cajaModel::cortadec($producto->descuento_cantidad)." %"; 
                    }
                }
                if($producto->tipodescuento=="$")
                {
                    $descuentogeneral=$producto->descuento; 
                    if($producto->descuento>0)
                    {
                        $textodescuento.=" - $".cajaModel::cortadec($producto->descuento_cantidad)."";  
                    }
                }



                $conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
                $conceptosDatos[$x]["Unidad"] = $producto->unidad;
                $conceptosDatos[$x]["Precio"] = $producto->precioventa;
                if ($producto->descripcion != '') {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion." ".$textodescuento);
                } else {
                    $conceptosDatos[$x]["Descripcion"] = trim($producto->nombre." ".$textodescuento);
                }
                $textodescuento = '';
                $conceptosDatos[$x]['Importe'] = ($producto->cantidad*$producto->precioventa - $producto->descuento );   
                $x++;

                //print_r($conceptosDatos);

                $queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
                $queryImpuestos .= " from impuesto i, mrp_producto p ";
                $queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
                $queryImpuestos .= " where p.idProducto=" . $producto->id . " and i.id=pi.idImpuesto ";
                $queryImpuestos .= " Order by pi.idImpuesto DESC ";

                $resultImpuestos = $this->queryArray($queryImpuestos);

                foreach ($resultImpuestos["rows"] as $key => $value) {

                    if ($value["nombre"] == 'IEPS') {
                        $calculos = str_replace(",", "", number_format(((($producto->precioventa * $producto->cantidad  - $producto->descuento_neto )* $value["valor"])) / 100, 2));
                        $nn2[$value["nombre"]][$value["valor"]]["Valor"] = $calculos;
                        $ieps = $calculos;
                    } else {
                        if ($ieps != 0) {
                            $nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", number_format((((($producto->precioventa  * $producto->cantidad) + $ieps  - $producto->descuento_neto) * $value["valor"]) ) / 100, 2));
                        } else {
                            $nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", number_format(((($producto->precioventa  * $producto->cantidad - $producto->descuento_neto) * $value["valor"])) / 100, 2));
                        }
                    }

                    //$nn2[$value["nombre"]][$value["valor"]]["Valor"] = $_SESSION['caja']["cargos"]["impuestos"][$value["nombre"]];
                }
            }
        }

    //        unset($_SESSION['pagos-caja']);
    //        unset($_SESSION['caja']);

        /* FACTURACION AZURIAN
        ============================================================== */
        require_once('../../modulos/SAT/config.php');

        date_default_timezone_set("Mexico/General");
        $fecha = date('Y-m-d') . 'T' . date('H:i:s', strtotime("-5 minute"));


        $logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
        $logo = $this->queryArray($logo);
        $r3 = $logo["rows"][0];

        $azurian = array();
        if ($bloqueo == 0) {
            $queryConfiguracion = "SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;";
            $returnConfiguracion = $this->queryArray($queryConfiguracion);
            if ($returnConfiguracion["total"] > 0) {
                $r = (object) $returnConfiguracion["rows"][0];

                /* DATOS OBLIGATORIOS DEL EMISOR
                ================================================================== */
                $rfc_cliente = $r->rfc;

                $parametros['EmisorTimbre'] = array();
                $parametros['EmisorTimbre']['RFC'] = $r->rfc;
                $parametros['EmisorTimbre']['RegimenFiscal'] = $r->regimenf;
                $parametros['EmisorTimbre']['Pais'] = $r->pais;
                $parametros['EmisorTimbre']['RazonSocial'] = $r->razon_social;
                $parametros['EmisorTimbre']['Calle'] = $r->calle;
                $parametros['EmisorTimbre']['NumExt'] = $r->num_ext;
                $parametros['EmisorTimbre']['Colonia'] = $r->colonia;
                $parametros['EmisorTimbre']['Ciudad'] = $r->ciudad; //Ciudad o Localidad
                $parametros['EmisorTimbre']['Municipio'] = $r->municipio;
                $parametros['EmisorTimbre']['Estado'] = $r->estado;
                $parametros['EmisorTimbre']['CP'] = $r->cp;
                $cer_cliente = $pathdc . '/' . $r->cer;
                $key_cliente = $pathdc . '/' . $r->llave;
                $pwd_cliente = $r->clave;
            } else {

                $JSON = array('success' => 0,
                    'error' => 1001,
                    'mensaje' => 'No existen datos de emisor.');
                echo json_encode($JSON);
                exit();
            }
        }

        /* Observaciones pdf */
        $azurian['Observacion']['Observacion'] = $mensaje;

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
        $azurian['Basicos']['noCertificado'] = '';
        $azurian['Basicos']['certificado'] = '';
        $str_subtotal = number_format($parametros['DatosCFD']['Subtotal'], 2);
        $azurian['Basicos']['subTotal'] = str_replace(",", "", $str_subtotal);
        $str_total = number_format($parametros['DatosCFD']['Total'], 2);
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
        //echo $iva.'  '.$isr; exit();
        /*  foreach ($impuestosDatos as $key => $value) {


          if($value['TipoImpuesto']=='ISR' || $value['TipoImpuesto']=='isr' || $value['TipoImpuesto']=='Isr'){
          $isr="<cfdi:Retenciones><cfdi:Retencion impuesto='ISR' importe='";
          $tisr=($value['Importe']*1)+($tisr*1);
          $oriisr='|ISR|';
          $oriisr.=number_format($tisr,2,'.','').'|';
          $oriisr.=number_format($tisr,2,'.','');
          }

          if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
          $iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
          $tiva=($value['Importe']*1)+($tiva*1);
          $oriiva='|IVA|';
          $oriiva.='16|';
          $oriiva.=number_format($tiva,2,'.','').'|';
          $oriiva.=number_format($tiva,2,'.','');
          }

          if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
          $iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
          $tiva=($value['Importe']*1)+($tiva*1);
          $oriiva='|IVA|';
          $oriiva.='16|';
          $oriiva.=number_format($tiva,2,'.','').'|';
          $oriiva.=number_format($tiva,2,'.','');
          }
          }
         */

          $azurian['Impuestos']['isr'] = $retenids;
          $azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');

          $azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
          $azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');

        /* if($iva!=''){
          $iva.=number_format($tiva,2,'.','')."'"." /></cfdi:Traslados>";
          }
          if($isr!=''){
          $isr.=number_format($tisr,2,'.','')."'"." /></cfdi:Retenciones>";
      } */
      $ivas.=$isr . $iva;

      $azurian['Impuestos']['ivas'] = $ivas;

      unset($_SESSION['pagos-caja']);
      unset($_SESSION['caja']);


      require_once('../../modulos/lib/nusoap.php');
      require_once('../../modulos/SAT/funcionesSAT.php');

        //require_once('../../modulos/WS_facturacion.php');
    }

    function datosFacturacion($id) {
        if ($id != '') {
            $datosFacturacion = "Select nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
            e.estado estado,ciudad,municipio,regimen_fiscal
            from comun_facturacion cf left join estados e on  e.idestado=cf.estado
            where  id=" . $id;

            $result = $this->queryArray($datosFacturacion);

            if ($result["total"] > 0) {
                return $result["rows"][0];
            }
        } else {
            return false;
        }
    }  
?>