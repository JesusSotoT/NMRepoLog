<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class CajaModel extends Connection
{

    public function datosFacturacion($id) {
        if ($id != '') {
            $datosFacturacion = "SELECT nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
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
    
    public function pendienteFacturacion($idFacturacion, $monto, $cliente, $idventa, $trackId, $azurian, $documento) {
    
        $azurian = base64_encode($azurian); 
        $fechaactual = date('Y-m-d H:i:s');
        $tipo = ($documento = 2 ? 'F' : 'R');

        if (is_numeric($cliente)) {
            $query = "INSERT into pvt_pendienteFactura values(''," . $idventa . ",'" . $fechaactual . "'," . $cliente . ",'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','" . $tipo . "');";
            $resultquery = $this->queryArray($query);

                //echo $query;
            return array("status" => true, "type" => 1);
        } else {
            $query = "INSERT into pvt_pendienteFactura values(''," . $idventa . ",'" . $fechaactual . "',NULL,'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','" . $tipo . "');";
                //echo $query;
            $resultquery = $this->queryArray($query);
            return array("status" => true, "type" => 2);
        }
    }

    public function envioFactura($uid, $Email, $azurian, $doc) {

            //$azurian=json_decode($azurian);
        $azurian = cajaModel::object_to_array($azurian);
        $datosTimbrado = $azurian['datosTimbrado'];

        if ($azurian['FiscalesEmisor']['noExterior'] == '') {
            $nemi = '';
        } else {
            $nemi = ' #' . $azurian['FiscalesEmisor']['noExterior'];
        }

        if ($azurian['DomicilioReceptor']['noExterior'] == '') {
            $nrec = '';
        } else {
            $nrec = ' #' . $azurian['DomicilioReceptor']['noExterior'];
        }

        //Obteniendo la descripcion de la forma de pago
        $idVenta = $datosTimbrado['idVenta'];
        $formapago = "";

        $queryFormaPago = " SELECT nombre,referencia,claveSat from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;

        $resultqueryFormaPago = $this->queryArray($queryFormaPago);

        foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
            if (strlen($pagosValue["referencia"]) > 0) {
                $formapago .= $pagosValue['nombre'] .'('.$pagosValue['claveSat'].')'.",";
                $refFormaPago = $pagosValue['referencia'];
                //$formapago .= $pagosValue['claveSat'] . ",";
            } else {
                //$formapago .= $pagosValue['nombre'] . ",";
                $formapago .= $pagosValue['nombre'] .'('.$pagosValue['claveSat'].')'.",";
                $refFormaPago = '';
            }
        }

        $formapago = substr($formapago, 0, strlen($formapago) - 1);

        if ($formapago == "") {
            $formapago = ".";
        }
        $azurian['Basicos']['metodoDePago'] = $formapago;
        $azurian['Basicos']['NumCtaPago'] = $refFormaPago;
        

        include "../webapp/modulos/SAT/PDF/CFDIPDF.php";

        $obj = new CFDIPDF( );

        if ($doc == 3) {
            $doc = "recibo";
        } else {
            $doc = "";
        }
        $azurian['Conceptos']['conceptosOri'] = preg_replace('/&apos;/', "'", $azurian['Conceptos']['conceptosOri']);
        $azurian['Conceptos']['conceptosOri'] = preg_replace('/&quot;/', '"', $azurian['Conceptos']['conceptosOri']);
            //$obj->ponerColor('#333333');
        $obj->datosCFD($datosTimbrado['UUID'], $azurian['Basicos']['serie'] . ' ' . $azurian['Basicos']['folio'], $datosTimbrado['noCertificado'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['noCertificadoSAT'], $azurian['Basicos']['formaDePago'], $azurian['Basicos']['tipoDeComprobante'], $doc);
        $obj->lugarE($azurian['Basicos']['LugarExpedicion']);
        $obj->datosEmisor($azurian['Emisor']['nombre'], $azurian['Emisor']['rfc'], $azurian['FiscalesEmisor']['calle'] . $nemi, $azurian['FiscalesEmisor']['localidad'], $azurian['FiscalesEmisor']['colonia'], $azurian['FiscalesEmisor']['municipio'], $azurian['FiscalesEmisor']['estado'], $azurian['FiscalesEmisor']['codigoPostal'], $azurian['FiscalesEmisor']['pais'], $azurian['Regimen']['Regimen']);
        $obj->datosReceptor($azurian['Receptor']['nombre'], $azurian['Receptor']['rfc'], $azurian['DomicilioReceptor']['calle'] . $nrec, $azurian['DomicilioReceptor']['localidad'], $azurian['DomicilioReceptor']['colonia'], $azurian['DomicilioReceptor']['municipio'], $azurian['DomicilioReceptor']['estado'], $azurian['DomicilioReceptor']['codigoPostal'], $azurian['DomicilioReceptor']['pais']);
        $obj->agregarConceptos($azurian['Conceptos']['conceptosOri']);
        $obj->agregarTotal($azurian['Basicos']['subTotal'], $azurian['Basicos']['total'], array());
        $obj->agregarMetodo($azurian['Basicos']['metodoDePago'], $refFormaPago, $azurian['Basicos']['total']);
        $obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
        $obj->agregarObservaciones($azurian['Observacion']['Observacion']);
        $obj->generar("../webapp/netwarelog/archivos/1/organizaciones/" . $azurian['org']['logo'] . "", 0);
        $obj->borrarConcepto();

        $queryIdReceptor = "SELECT nombre from comun_facturacion where rfc='".$azurian['Receptor']['rfc']."' order by nombre desc";
        $resultOne = $this->queryArray($queryIdReceptor);

        /*$queryCupon = "SELECT cupon from comun_cliente_inadem where idCliente=".$resultOne['rows'][0]['nombre'];
        if($this->queryArray($queryCupon)){
            $resultTwo = $this->queryArray($queryCupon);
            $cuponInadem = $resultTwo['rows'][0]['cupon'];
        }else{
           $resultTwo = '';
           $cuponInadem = '';
        }  */
       

        $cuponInadem = '';
        if ($Email != '') {

            require_once('../webapp/modulos/phpmailer/sendMail.php');

            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "NetwareMonitor";
            $mail->Subject = "Factura Generada";
            $mail->AltBody = "NetwarMonitor";
            $mail->MsgHTML('Factura Generada');
            if($cuponInadem==null || $cuponInadem==''){
            $mail->AddAttachment('../webapp/modulos/cont/xmls/facturas/temporales/'. $uid .'.xml');
            $mail->AddAttachment('../webapp/modulos/facturas/'. $uid .'.xml');
            $mail->AddAttachment('../webapp/modulos/facturas/'. $uid .'.pdf');
            }else{

            $mail->AddAttachment('../webapp/modulos/facturas/'. $uid .'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cuponInadem.'.xml');
            $mail->AddAttachment('../webapp/modulos/facturas/'. $uid .'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cuponInadem.'.pdf');
            } 

            $mail->AddAddress($Email, $Email);


            @$mail->Send();
        }
        //$cuponInadem='';
       if($cuponInadem ==null || $cuponInadem==''){
        return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => false);
       }else{
        return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => $cuponInadem);
       } 
    }

    public function guardarFacturacion($UUID, $noCertificadoSAT, $selloCFD, $selloSAT, $FechaTimbrado, $idComprobante, $idFact, $idVenta, $noCertificado, $tipoComp, $monto, $cliente, $trackId, $idRefact, $azurian, $estatus) {
        $azurian = base64_encode($azurian);
        $fechaactual = preg_replace('/T/', ' ', $FechaTimbrado);
        if ($idRefact == 'c') {
            $tipoComp = 'C';
            $queryRespuesta = "UPDATE pvt_respuestaFacturacion SET borrado=2 WHERE idSale='$idVenta'";
            $this->queryArray($queryRespuesta);
        }

        $insertRespuestaFacturacion = "INSERT INTO pvt_respuestaFacturacion "
        . "(idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp,idComprobante,cadenaOriginal,proviene) VALUES "
        . "('" . $idVenta . "','" . $idFact . "','" . $UUID . "','" . $trackId . "','" . $noCertificadoSAT . "','" . $noCertificado . "','" . $selloSAT . "','" . $selloCFD . "','" . $fechaactual . "',0,'" . $tipoComp . "','" . $idComprobante . "','" . $azurian . "','1');";

        $resultInsert = $this->queryArray($insertRespuestaFacturacion);
        $insertedId = $resultInsert["insertId"];


        if (is_numeric($insertedId)) {
            $queryUpdateContador = "UPDATE pvt_contadorFacturas set total=total+1 where id=1";
            $this->queryArray($queryUpdateContador);

            $ContadorLicencias = "UPDATE comun_parametros_licencias set valor=valor-1 where parametro='Facturas'";
            $this->queryArray($ContadorLicencias);

            if ($tipoComp == "R") {
                $queryUpdateFolo = "UPDATE pvt_serie_folio SET folio_r=folio_r+1 where id=1";
            } else {
                $queryUpdateFolo = "UPDATE pvt_serie_folio SET folio=folio+1 where id=1";
            }
            $this->queryArray($queryUpdateFolo); 
        }

        if (preg_match('/all/', $idRefact)) {
            $idRefact = preg_replace('/all/', '', $idRefact);
            $updatePendienteFactura = "UPDATE pvt_pendienteFactura SET facturado=1 WHERE id_sale in (" . $idRefact . ")";
            $this->queryArray($updatePendienteFactura);
        }

        if ($idRefact > 0 && $idRefact != 'c') {
            $updatePendienteFactura = "UPDATE pvt_pendienteFactura SET facturado=1 WHERE id_sale='$idRefact'";
            $this->queryArray($updatePendienteFactura);
        }
        $queryEnvio = "UPDATE venta set envio=2 where idVenta=".$idVenta;
        $this->queryArray($queryEnvio);
        
        return $insertedId;
    }

    public function datosorganizacion(){
        $selectOrg = "SELECT * from organizaciones c left join estados e on e.idestado=c.idestado left join municipios m on m.idmunicipio=c.idmunicipio where idorganizacion=1";
        $resultSelect = $this->queryArray($selectOrg);
        return $resultSelect['rows'];
    }

    public function datosventa($idventa){
        $selectVenta = "SELECT 
        v.idVenta as folio,
        v.fecha as fecha, 
        v.cambio as cambio,
        v.montoimpuestos as jsonImpuestos,
        CASE WHEN c.nombre IS NOT NULL 
               THEN c.nombre
               ELSE 'Publico general'
        END AS cliente,
        e.nombre as empleado,
        s.nombre as sucursal,
        CASE WHEN v.estatus =1 
               THEN 'Activa'
               ELSE 'Cancelada'
        END AS estatus,
        v.montoimpuestos as impuestos,
        (v.monto) as monto 
         from venta v left join comun_cliente c on c.id=v.idCliente inner join  empleados e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal 
         where v.idVenta=".$idventa;
        $resutl = $this->queryArray($selectVenta);
        return $resutl['rows'];
    } 

    public function obtenerIdVenta($codigoTicket){
        $id = $codigoTicket;
        $yadecimal =substr($id,2);
        $desc = hexdec($yadecimal);
        $idventa = substr($desc,14);
        return $idventa;
    }

    public function verificaFacturacionValida($idVenta){
        $idVenta = $this->obtenerIdVenta($idVenta);
        $selectVenta = "SELECT id FROM pvt_respuestaFacturacion WHERE idSale = " . $idVenta;
        $result = $this->queryArray($selectVenta);


        $selCanel = 'SELECT facturado from pvt_pendienteFactura where id_sale = '.$idVenta;
        $resCanel = $this->queryArray($selCanel);

        //echo $resCanel['rows'][0]['facturado'];

        if((int)$result["total"] > 0 || $resCanel['rows'][0]['facturado']==2){
            return "facturada";
        }else{
            return "ok";
        } 
        
    }

    public function productosventa($idVenta){
        $selProd = 'SELECT p.descorta ,p.idProducto,p.codigo,p.nombre,vp.preciounitario,vp.cantidad,vp.montodescuento,vp.total,vp.impuestosproductoventa,vp.comentario from venta_producto vp inner join mrp_producto p on vp.idProducto=p.idProducto where vp.idVenta='.$idVenta;
        $resSel = $this->queryArray($selProd);
        return $resSel['rows'];
    }

    public function pagos($idVenta){
        $selectPagos = "SELECT vp.monto, fp.nombre from venta_pagos vp inner join venta v on v.idVenta=vp.idVenta inner join forma_pago fp on vp.idFormapago=fp.idFormapago where v.idVenta=".$idVenta;
        $resPagos = $this->queryArray($selectPagos);
        return $resPagos['rows'];
    }

    public function estados(){
        $query = 'Select * from estados';
        $result = $this->queryArray($query);
        return $result['rows'];
    }

    public function municipios($idEstado){
        $queryM = "SELECT * from municipios where idestado=".$idEstado;
        $result = $this->queryArray($queryM);
        return $result['rows'];
    }
    
    public function munici(){
        $queryM = "SELECT * from municipios";
        $result = $this->queryArray($queryM);
        return $result['rows'];
    }

    public function descuentoGeneral($descuento){

    }

    public function verificaRfcmodal($rfc){
        if($this->validaRFC($rfc) == 1){
            $select = "SELECT f.id,f.nombre,f.rfc,f.razon_social,f.correo,f.pais,f.regimen_fiscal,f.domicilio,f.num_ext,f.cp,f.colonia,e.estado,f.ciudad,f.municipio from comun_facturacion f,estados e where f.rfc='".$rfc."' and f.estado=e.idestado";
            $resSelct = $this->queryArray($select);
            if($resSelct['total']>0){
                return array('estatus' => true ,'datosFac' => $resSelct['rows']);
            }else{
                return array('estatus' => false );
            }
        }else{
            return array('rfc_no_valido' => true);
        }
    }

    public function datosFacturacionCliente($idFact){
      /*$datosFacturacion = "SELECT nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
            e.estado estado,ciudad,municipio,regimen_fiscal,cf.estado as idEstado
            from comun_facturacion cf left join estados e on  e.idestado=cf.estado
            where  id=" . $idFact; */
        $datosFacturacion ="SELECT nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
            e.estado estado,ciudad,cf.municipio,regimen_fiscal,cf.estado as idEstado, m.idmunicipio as idMunicipio
            from comun_facturacion cf left join estados e on  e.idestado=cf.estado
            left join municipios m on cf.municipio=m.municipio
            where  id=".$idFact;   

            $result = $this->queryArray($datosFacturacion);
         
        return array("Datafact" => $result['rows']);
    }

    public function updateDatosFac($idFac,$rfc,$razSoc,$email,$pais,$regimen,$domicilio,$numero,$cp,$col,$estado,$municipio,$ciudad){
        if($this->validaRFC($rfc) == 1){
            $selcMuni = "SELECT * from municipios where idmunicipio=".$municipio;
            $resmunici = $this->queryArray($selcMuni);
            $municipioNombre = $resmunici['rows'][0]['municipio'];

            $update = "UPDATE comun_facturacion set rfc='".$rfc."', razon_social='".$razSoc."', correo='".$email."', pais='".$pais."', regimen_fiscal='".$regimen."', domicilio='".$domicilio."', num_ext='".$numero."', cp='".$cp."', colonia='".$col."', estado='".$estado."', ciudad='".$ciudad."', municipio='".$municipioNombre."' where id=".$idFac;

            $res = $this->queryArray($update);

            return  array('estatus' => true , 'Datos' => $res['rows'] );
        }else{
            return array('rfc_no_valido' => true);
        }

    }

    public function oneFact($idComunFactu,$idVenta){
        $id = $idVenta;
        $idVenta = base64_decode($id);
        $clave = array();

        $clave["instancia"] = substr($id,0,2);

        $clave["anho"] = substr($idVenta,2,4);
        $clave["mes"] = substr($idVenta,6,2);
        $clave["dia"] = substr($idVenta,8,2);
        $clave["hora"] = substr($idVenta,10,2);
        $clave["minuto"] = substr($idVenta,12,2);
        $clave["segundo"] = substr($idVenta,14,2);
        $clave["venta"] = substr($idVenta,16);

        $yadecimal =substr($id,2);
        $desc = hexdec($yadecimal);

        $idVenta = substr($desc,14);
        require_once('../webapp/modulos/SAT/config.php');
        date_default_timezone_set("Mexico/General");
        $fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));

        $SeleCad = "SELECT cadenaOriginal FROM pvt_pendienteFactura WHERE id_sale=".$idVenta;
        $cadenaOri = $this->queryArray($SeleCad);
        //echo $cadenaOri['rows'][0]['cadenaOriginal'];
        $azurian=base64_decode($cadenaOri['rows'][0]['cadenaOriginal']);

        $azurian = str_replace("\\", "", $azurian);
        if($azurian!=''){ 
            $azurian=json_decode($azurian); 
        }
        $azurian = $this->object_to_array($azurian);

         //////SELECCIONA PAC
        $qrpac = "SELECT pac FROM pvt_configura_facturacion WHERE id=1;";
        $respac = $this->queryArray($qrpac);
        $pac = $respac["rows"][0]["pac"];

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

        /* Datos Receptor
        ============================================================== */
        if($idComunFactu!=''){

            $selComFac = "SELECT * FROM comun_facturacion WHERE id=".$idComunFactu;
            $result = $this->queryArray($selComFac);
            //Estado
            $selEstado ="SELECT estado from estados where idestado=".$result['rows'][0]['estado'];
            $resultEstado = $this->queryArray($selEstado);
            

            $idCliente=$rs{'nombre'};
            $azurian['Receptor']['rfc']=strtoupper($result['rows'][0]['rfc']);
            $azurian['Receptor']['nombre']=strtoupper($result['rows'][0]['razon_social']);
            $azurian['DomicilioReceptor']['calle']=$result['rows'][0]['domicilio'];
            $azurian['DomicilioReceptor']['noExterior']=$result['rows'][0]['num_ext'];
            $azurian['DomicilioReceptor']['colonia']=$result['rows'][0]['colonia'];
            $azurian['DomicilioReceptor']['localidad']=$result['rows'][0]['ciudad'];
            $azurian['DomicilioReceptor']['municipio']=$result['rows'][0]['municipio'];
            $azurian['DomicilioReceptor']['estado']=$resultEstado['rows'][0]['estado'];
            $azurian['DomicilioReceptor']['pais']=$result['rows'][0]['pais'];
            $azurian['DomicilioReceptor']['codigoPostal']=$result['rows'][0]['cp'];
            $azurian['Correo']['Correo'] = $result['rows'][0]['correo'];
            
        }else{
            $idCliente='';
            $azurian['Receptor']['rfc']='XAXX010101000';
            $azurian['Receptor']['nombre']='Factura generica';
            $azurian['DomicilioReceptor']['calle']='';
            $azurian['DomicilioReceptor']['noExterior']='';
            $azurian['DomicilioReceptor']['colonia']='';
            $azurian['DomicilioReceptor']['localidad']='';
            $azurian['DomicilioReceptor']['municipio']='';
            $azurian['DomicilioReceptor']['estado']='';
            $azurian['DomicilioReceptor']['pais']='';
            $azurian['DomicilioReceptor']['codigoPostal']='';
            $azurian['Correo']['Correo'] = '';
        }       


        $serFol = "SELECT * FROM pvt_serie_folio WHERE id=1";
        $rs3 = $this->queryArray($serFol);

        $selecLogo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
        $rs4 = $this->queryArray($selecLogo);

        $azurian['org']['logo'] = $rs4['rows'][0]['logoempresa'];

        /* Datos serie y folio
        ============================================================== */
        $azurian['Basicos']['serie']=$rs3['rows'][0]['serie']; //No obligatorio
        $azurian['Basicos']['folio']=$rs3['rows'][0]['folio'];

        /* Datos Emisor
        ============================================================== */
        $azurian['Emisor']['rfc']=strtoupper($parametros['EmisorTimbre']['RFC']);
        $azurian['Emisor']['nombre']=strtoupper($parametros['EmisorTimbre']['RazonSocial']);
        /* Datos Fiscales Emisor
        ============================================================== */
        $azurian['FiscalesEmisor']['calle']=$parametros['EmisorTimbre']['Calle'];
        $azurian['FiscalesEmisor']['noExterior']=$parametros['EmisorTimbre']['NumExt'];
        $azurian['FiscalesEmisor']['colonia']=$parametros['EmisorTimbre']['Colonia'];
        $azurian['FiscalesEmisor']['localidad']=$parametros['EmisorTimbre']['Ciudad'];
        $azurian['FiscalesEmisor']['municipio']=$parametros['EmisorTimbre']['Municipio'];
        $azurian['FiscalesEmisor']['estado']=$parametros['EmisorTimbre']['Estado'];
        $azurian['FiscalesEmisor']['pais']=$parametros['EmisorTimbre']['Pais'];
        $azurian['FiscalesEmisor']['codigoPostal']=$parametros['EmisorTimbre']['CP'];

        /* Datos Regimen
        ============================================================== */
        $azurian['Regimen']['Regimen']=$parametros['EmisorTimbre']['RegimenFiscal'];

        /* Fecha Factura
        ============================================================== */
        $azurian['Basicos']['fecha']=$fecha;
       

        /* Impuestos
        ============================================================== */
        $tisr=$azurian['Impuestos']['totalImpuestosRetenidos'];
        $tiva=$azurian['Impuestos']['totalImpuestosTrasladados'];
        $tieps=$azurian['Impuestos']['totalImpuestosIeps']; 


        //Obteniendo la descripcion de la forma de pago
        $formapago = "";

        $queryFormaPago = " SELECT nombre,referencia,claveSat from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;

        $resultqueryFormaPago = $this->queryArray($queryFormaPago);

        foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
            if (strlen($pagosValue["referencia"]) > 0) {
                //$formapago .= $pagosValue['claveSat'] . " Ref:" . $pagosValue['referencia'] . ",";
               // $formapago .= $pagosValue['claveSat'] . ",";
                $formapago .= $pagosValue['claveSat'].",";
                $refFormaPago = $pagosValue['referencia'];
            } else {
                $formapago .= $pagosValue['claveSat'] . ",";
                $refFormaPago = '';
            }
        }

        $formapago = substr($formapago, 0, strlen($formapago) - 1);
       
        if ($formapago == "") {
            $formapago = ".";
        }

        $azurian['Basicos']['metodoDePago'] = $formapago;
        $azurian['Basicos']['NumCtaPago'] = $refFormaPago;
    //    $positionPath='../../webapp/modulos';

       if($pac==2){
            require_once('../webapp/modulos/SAT/funcionesSAT2.php');
        }else if($pac==1){
            require_once('../webapp/modulos/lib/nusoap.php');
            require_once('../webapp/modulos/SAT/funcionesSAT.php');  
        } 
        
        //require_once('../webapp/modulos/lib/nusoap.php');
        //require_once('../webapp/modulos/SAT/funcionesSAT.php');      

    }

    public function newClientDatfact($idFac,$rfc,$razSoc,$email,$pais,$regimen,$domicilio,$numero,$cp,$col,$estado,$municipio,$ciudad){
        if($this->validaRFC($rfc) == 1){
            $queryCliente = "INSERT INTO comun_cliente (nombre,direccion,colonia,email,cp,idEstado,idMunicipio,rfc) values ";
            $queryCliente .="('".$razSoc."','".$domicilio.' '.$numero."','".$col."','".$email."','".$cp."','".$estado."','".$municipio."','".$rfc."')";
            $insertClienteRes = $this->queryArray($queryCliente);
            $idClienteInsert = $insertClienteRes['insertId'];



            $selcMuni = "SELECT * from municipios where idmunicipio=".$municipio;
            $resmunici = $this->queryArray($selcMuni);
            $municipioNombre = $resmunici['rows'][0]['municipio'];

            $insertCo = "INSERT into comun_facturacion(nombre,rfc,razon_social,correo,pais,regimen_fiscal,domicilio,num_ext,cp,colonia,estado,ciudad,municipio) values('".$idClienteInsert."','".$rfc."','".$razSoc."','".$email."','".$pais."','".$regimen."','".$domicilio."','".$numero."','".$cp."','".$col."','".$estado."','".$ciudad."','".$municipioNombre."')";
            $resInsert = $this->queryArray($insertCo);
            
            if(is_numeric($resInsert['insertId'])){
                return  array('estatus' => true );
            }else{
                return  array('estatus' => false );
            }
        }else{
            return array('rfc_no_valido' => true);
        }
    }

    public function validaRFC($rfc){
        if (preg_match('/[A-Z,Ñ,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?/', $rfc)) {
            return 1;
        }else{
            return 0;
        }
    }

} ///fin de la clase
?>
