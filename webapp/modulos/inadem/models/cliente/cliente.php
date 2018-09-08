<?php
//ini_set('display_errors', 1);
require("models/connection_sqli.php"); // funciones mySQLi

class clienteModel extends Connection {
     
    public function __construct() {
        session_start();


      /*  cotizacionModel::simple();
        cotizacionModel::propina();
        cotizacionModel::sessiooon();
        //unset($_SESSION["sucursal"]);
        //unset($_SESSION["caja"]);
        //unset($_SESSION["simple"]);
        //$_SESSION["simple"] = true; */
    }
    function gridCliente(){
        $selectGrid = "SELECT ci.id,ci.factura,ci.archivo,c.nombre,ci.folio_inadem,ci.convocatoria,ci.vitrina,ci.cupon,ci.organismo_inter,ci.promotor,ci.resp_nwm ";
        $selectGrid .=" from cliente_inadem ci, comun_cliente c ";
        $selectGrid .=" where ci.idCliente = c.id";
        //echo $selectGrid;
        $result1 = $this->queryArray($selectGrid);
        return array('grid' => $result1["rows"]);
    }
    /*function gridFacturados(){
        $selectGrid = "SELECT ci.id,ci.factura,c.nombre,ci.folio_inadem,ci.convocatoria,ci.vitrina,ci.cupon,ci.organismo_inter,ci.promotor,ci.resp_nwm ";
        $selectGrid .=" from cliente_inadem ci, comun_cliente c ";
        $selectGrid .=" where ci.idCliente = c.id and ci.factura=''";
        echo $selectGrid;
        $result1 = $this->queryArray($selectGrid);
        return array('grid' => $result1["rows"]);
    } */
    function filtros(){
        $selectFolio = "SELECT distinct folio_inadem from comun_cliente_inadem;";
        $result1 = $this->queryArray($selectFolio);
        
        $selectConvocatoria = "SELECT distinct convocatoria from comun_cliente_inadem;";
        $result2 = $this->queryArray($selectConvocatoria);

        $selectConvocatoria = "SELECT distinct organismo_inter from comun_cliente_inadem;";
        $result3 = $this->queryArray($selectConvocatoria);

        return array('folio' => $result1["rows"], 'convocatoria' => $result2["rows"], 'organismo' => $result3["rows"]);
    }
    function buscar($convocatoria,$folio,$organismo){

        $filtro='';
        if($convocatoria!='0'){
            $filtro .= ' and convocatoria="'.$convocatoria.'" ';
        }
        if($folio!='0'){
            $filtro.=' and folio_inadem="'.$folio.'" ';
        }
        if($organismo!='0'){
            $filtro.=' and organismo_inter="'.$organismo.'" ';
        }

        $selectGrid = "SELECT ci.id,c.nombre,ci.folio_inadem,ci.convocatoria,ci.vitrina,ci.cupon,ci.organismo_inter,ci.promotor,ci.resp_nwm ";
        $selectGrid .=" from comun_cliente_inadem ci, comun_cliente c ";
        $selectGrid .=" where ci.idCliente = c.id".$filtro;
        
        $result1 = $this->queryArray($selectGrid);
        return array('grid' => $result1["rows"]);

    }
    function getinfoInadem($idCliente){
        $selectInadem = "SELECT ci.id,c.nombre,ci.folio_inadem,ci.convocatoria,ci.vitrina,ci.cupon,ci.monto_beneficio,ci.monto_aportacion,ci.organismo_inter,ci.promotor,ci.resp_nwm,ci.fecha_entrega,ci.instancia,ci.resp_legal";
        $selectInadem .=" from comun_cliente_inadem ci, comun_cliente c  ";
        $selectInadem .=" where ci.idCliente = c.id and ci.id=".$idCliente;

        $result1 = $this->queryArray($selectInadem);



        return array('inadem' => $result1["rows"]);
    }
    function getinfoBasics($idCliente){
            $selectIdCliente = "SELECT idCliente from comun_cliente_inadem  where id=".$idCliente;
            $result1 = $this->queryArray($selectIdCliente);

            $selectInfoCliente ="SELECT *,e.estado as estadoname, f.celular from comun_facturacion c,estados e,comun_cliente f where c.estado=e.idestado and  f.id=c.nombre and c.nombre=".$result1['rows'][0]['idCliente'];
            $result2 = $this->queryArray($selectInfoCliente);


            return array('basicos' => $result2["rows"]);
            
    }
    function saveClient($idInademCliente,$idInadem,$convocatoria,$vitrina,$cupon,$beneficio,$aportacion,$organismo,$promotor,$respNwm,$fecha,$instancia,$respLegal){

        $updateInadem = "UPDATE comun_cliente_inadem SET folio_inadem='".$idInadem."', convocatoria='".$convocatoria."', vitrina='".$vitrina."',cupon='".$cupon."', monto_beneficio='".$beneficio."', monto_aportacion='".$aportacion."', organismo_inter='".$organismo."', promotor='".$promotor."', resp_nwm='".$respNwm."', instancia='".$instancia."', resp_legal='".$respLegal."', fecha_entrega='".$fecha."' where id=".$idInademCliente;

        $resultUpdate = $this->queryArray($updateInadem);
        return array('status' => true);


    }
    function bus_estado($estado){

        /*$select = "SELECT idestado from estados where estado='".$estado."';";
        //echo $select;
        $res = $this->queryArray($select);
        //echo '('.$res['total'].')';
        if($res['total'] > 0){
           // echo 'z'.$res['rows'][0]['idestado'].'z';
            return $res['rows'][0]['idestado'];
        }else{
            return 0;
        } */

        return 1;
        
    }
    function bus_municipio($muni){
        /*$select = "SELECT idmunicipio from municipios where municipio='".$muni."';";
        $res = $this->queryArray($select);

        if($res['total'] > 0){
            return $res['rows'][0]['idmunicipio'];
        }else{
            return 0;
        } */

        return 12;
        
    }
    function guardaNota($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$monto,$cliente,$trackId,$idRefact,$azurian,$total=0.00,$idFacturaRelacion){

        $azurian=base64_encode($azurian);
        $fechaactual=preg_replace('/T/', ' ', $FechaTimbrado); 
        if($idRefact=='c'){
            $tipoComp='C';
            $q="UPDATE app_respuestaFacturacion SET borrado=2 WHERE idSale='$idVenta' AND tipoComp='F'";
            $res = $this->queryArray($q);
        }
        $inser = "INSERT INTO app_respuestaFacturacion
            (idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp,idComprobante,cadenaOriginal,origen) VALUES ('".$idVenta."','".$idFact."','".$UUID."','".$trackId."','".$noCertificadoSAT."','".$noCertificado."','".$selloSAT."','".$selloCFD."','".$fechaactual."',0,'".$tipoComp."','".$idComprobante."','".$azurian."','2');";
            //exit();
        $res = $this->queryArray($inser);
        


        $insertedId = $res['insertId'];
        $ins = "INSERT into app_notas_credito(idFactura,idNota,monto,idVenta) values('".$idFacturaRelacion."','".$insertedId."','".$total."','".$idVenta."')";
        $resNot = $this->queryArray($ins);


        if(is_numeric($insertedId))
        {
            $this->queryArray('UPDATE pvt_serie_folio SET folio_nc=folio_nc+1 where id=1');
        } 

        /*if(preg_match('/all/', $idRefact)){
            $idRefact=preg_replace('/all/', '', $idRefact);
            $idRefact = trim($idRefact,',');
            $this->queryArray("UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale in (".$idRefact.")");
        } */

        if($idRefact>0 && $idRefact!='c'){
            $this->queryArray("UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale='$idRefact'");
        }

        /*if($idRefact=='c'){
            $tipoComp='C';
            $q="INSERT into pvt_notadeCredito (total,idfac) values ('".$total."','".$insertedId."')";
            $res = $this->queryArray();
        } */

        return $insertedId;

    }
    function guardarLayNotas($dato){
       /* echo '('.$dato[14].')';
        exit(); */
        $insrt = "INSERT into app_inadem_notas(idFac,rfc,uuid,folio_fac,idVenta,importe_nota,cliente) values('".$dato[1]."','".$dato[4]."','".$dato[6]."','".$dato[7]."','".$dato[8]."','".$dato[14]."','".$dato[5]."')";
        $res = $this->queryArray($insrt);

        return $res['insertId'];

    }
    function gridNotas(){
        $select = "SELECT * from app_inadem_notas";
        $res = $this->queryArray($select);

        return array('grid' => $res['rows']);
    }
    function guardarLay($dato){
        //echo 'deededeededededededed';
        //exit();
        //print($dato);
        $sel1 = 'SELECT id,nombre from comun_facturacion where rfc="'.$dato[17].'";';
        echo $sel1.'<br>';
        $res1 = $this->queryArray($sel1);
        if($res1['total']>0){
            //echo 'entro al update<br>'; 
            $int_ext = '';
            if($dato[21]!=''){
                $int_ext = $dato[20].' int. '.$dato[21];
            }else{
                $int_ext = $dato[20];
            }
            $idCliente = $res1['rows'][0]['nombre'];


            $selectmun = 'select municipio from municipios where idmunicipio='.$dato[24];
            $resmun = $this->queryArray($selectmun);

            $municipioLetr = $resmun['rows'][0]['municipio'];


            $upd = 'UPDATE comun_facturacion set razon_social="'.$dato[16].'", correo="'.$dato[28].'", domicilio="'.$dato[19].'", num_ext="'.$int_ext.'", municipio="'.$municipioLetr.'" , ciudad="'.$municipioLetr.'" where nombre="'.$idCliente.'";';
            //echo $upd.'<br>';
            $resupd = $this->queryArray($upd);


            $sel1x = 'SELECT idCliente from cliente_inadem where idCliente="'.$dato[17].'";';
            $res1x = $this->queryArray($sel1);

            /*if($res1x['total'] > 0){
                $updajdjf = 'UPDATE cliente_inadem set folio_inadem="'.$dato[7].'", vitrina="'.$dato[13].'", cupon="'.$dato[8].'", organismo_inter="'.$dato[6].'", resp_nwm="'.$dato[2].'", archivo="'.$dato[10].'" where id='.$res1x['rows'][0]['id'];
                echo '('.$updajdjf.')<br><br>';
                $oror = $this->queryArray($updajdjf);

            }else{ */
                $ini3 = 'INSERT into cliente_inadem(idCliente,folio_inadem,convocatoria,vitrina,cupon,monto_beneficio,organismo_inter,resp_nwm,tipo,archivo) values("'.$idCliente.'","'.$dato[7].'","","'.$dato[13].'","'.$dato[8].'","'.$dato[14].'","'.$dato[6].'","'.$dato[2].'","'.$dato[5].'","'.$dato[10].'")';
                //echo $ini3;
                $resini3 = $this->queryArray($ini3);
            //}
            

            return $resini3['insertId'];

        }else{
            $in1 = 'INSERT into comun_cliente(nombre,direccion,colonia,email,celular,cp,idEstado,idMunicipio,rfc,telefono1,num_ext,num_int) values("'.$dato[15].'","'.$dato[19].'","'.$dato[22].'","'.$dato[28].'","'.$dato[27].'","'.$dato[23].'","'.$dato[3].'","'.$dato[4].'","'.$dato[17].'","'.$dato[26].'","'.$dato[20].'","'.$dato[21].'")';
            $resini1 = $this->queryArray($in1);
            echo '('.$in1.')<br><br><br>';
            $idCliente = $resini1['insertId'];

            $int_ext = '';
            if($dato[21]!=''){
                $int_ext = $dato[20].' int. '.$dato[21];
            }else{
                $int_ext = $dato[20];
            }

            $selectmun = 'select municipio from municipios where idmunicipio='.$dato[24];
            $resmun = $this->queryArray($selectmun);

            $municipioLetr = $resmun['rows'][0]['municipio'];

            $ini2 = 'INSERT into comun_facturacion(nombre,rfc,razon_social,correo,pais,regimen_fiscal,domicilio,num_ext,cp,colonia,estado,ciudad,municipio) values("'.$idCliente.'","'.$dato[17].'","'.$dato[16].'","'.$dato[28].'","Mexico","General","'.$dato[19].'","'.$int_ext.'","'.$dato[23].'","'.$dato[22].'","'.$dato[25].'","'.$municipioLetr.'","'.$municipioLetr.'")';
            //echo '('.$ini2.')';
            $resini2 = $this->queryArray($ini2);

            $ini3 = 'INSERT into cliente_inadem(idCliente,folio_inadem,convocatoria,vitrina,cupon,monto_beneficio,organismo_inter,resp_nwm,tipo,archivo) values("'.$idCliente.'","'.$dato[7].'","","'.$dato[13].'","'.$dato[8].'","'.$dato[14].'","'.$$dato[6].'","'.$dato[2].'","'.$dato[5].'","'.$dato[10].'")';
            //echo '('.$ini3.')';
            $resini3 = $this->queryArray($ini3);

            return $resini3['insertId'];

        } 

    }
    public function GuardaFacInadem($uuid,$idInadem,$venta){
        $upd = "UPDATE cliente_inadem set factura='".$uuid."', idVenta='".$venta."' where id=".$idInadem;
        $x = $this->queryArray($upd);

        return true;
    } 
    public function  GuardaNotaInadem($uuid,$idRegNota){
        $upd = "UPDATE app_inadem_notas set uuidNota='".$uuid."' where id=".$idRegNota;
        $x = $this->queryArray($upd);

        return true;
    } 

    public function datosFacturacion($id) {
        if ($id != '') {
            $datosFacturacion = "SELECT nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
            e.estado estado,ciudad,municipio,regimen_fiscal
            from comun_facturacion cf left join estados e on  e.idestado=cf.estado
            where  id=" . $id;
            
            //exit();
            $result = $this->queryArray($datosFacturacion);

            if ($result["total"] > 0) {
                return $result["rows"][0];
            }
        } else {
            return false;
        }
    }
    public function guardarFacturacion($UUID, $noCertificadoSAT, $selloCFD, $selloSAT, $FechaTimbrado, $idComprobante, $idFact, $idVenta, $noCertificado, $tipoComp, $monto, $cliente, $trackId, $idRefact, $azurian, $estatus) {
        $azurian = base64_encode($azurian);
        $fechaactual = preg_replace('/T/', ' ', $FechaTimbrado);
        if ($idRefact == 'c') {
            $tipoComp = 'C';
            $queryRespuesta = "UPDATE app_respuestaFacturacion SET borrado=2 WHERE idSale='$idVenta'";
            $this->queryArray($queryRespuesta);
        }

        $insertRespuestaFacturacion = "INSERT INTO app_respuestaFacturacion "
        . "(idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp,idComprobante,cadenaOriginal,xmlfile,origen,proviene) VALUES "
        . "('" . $idVenta . "','" . $idFact . "','" . $UUID . "','" . $trackId . "','" . $noCertificadoSAT . "','" . $noCertificado . "','" . $selloSAT . "','" . $selloCFD . "','" . $fechaactual . "',0,'" . $tipoComp . "','" . $idComprobante . "','" . $azurian . "','" . $UUID . ".xml','2','3');";

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
            $idRefact = trim($idRefact,',');
            $updatePendienteFactura = "UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale in (" . $idRefact . ")";
            $this->queryArray($updatePendienteFactura);
        }

        if ($idRefact > 0 && $idRefact != 'c') {
            $updatePendienteFactura = "UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale='$idRefact'";
            $this->queryArray($updatePendienteFactura);
        }
        $queryEnvio = "UPDATE app_pos_venta set envio=2 where idVenta=".$idVenta;
        $this->queryArray($queryEnvio);
        
        return $insertedId;
        //return  array('facResp' => $insertedId, 'facUuid' => $UUID );
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
    public function envioFactura($uid, $Email, $azurian, $doc) {
       
        //$azurian=json_decode($azurian);
        //if($Email=="muchasFac@gmail.com"){  
            $azurian=json_decode($azurian);
            $azurian = $this->object_to_array($azurian);
            $azurian['nnf']['nnf'] = $azurian['nn']['nn'];
        //}

        $datosTimbrado = $azurian['datosTimbrado'];
        //echo 'rrrrrrrrrr';
        //print_r($azurian['datosTimbrado']);
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
    
        $idVenta = $azurian['datosTimbrado']['idVenta'];
        $formapago = "";

        $queryFormaPago = " SELECT nombre,referencia,claveSat from app_pos_venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;

        $resultqueryFormaPago = $this->queryArray($queryFormaPago);

        foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
            if (strlen($pagosValue["referencia"]) > 0) {
                $formapago .= $pagosValue['nombre'] .'('.$pagosValue['claveSat'].')'." Ref:" . $pagosValue['referencia'] . ",";
                //$formapago .= $pagosValue['nombre'] . ",";
            } else {
                $formapago .= $pagosValue['nombre'] .'('.$pagosValue['claveSat'].')'.",";
            }
        }
        
        $formapago = substr($formapago, 0, strlen($formapago) - 1);

        if ($formapago == "") {
            $formapago = ".";
        } 

        include "../../modulos/SAT/PDF/CFDIPDF.php";

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
        $obj->agregarTotal($azurian['Basicos']['subTotal'], $azurian['Basicos']['total'], $azurian['nnf']['nnf']);
        $obj->agregarMetodo($formapago, '', 'MXN');
        $obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
        $obj->agregarObservaciones($azurian['Observacion']['Observacion']);
        $obj->generar("../../netwarelog/archivos/1/organizaciones/" . $azurian['org']['logo'] . "", 0);
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
        $selRes = "SELECT serieCsdEmisor from app_respuestaFacturacion where folio='".$uid."'";
        $res = $this->queryArray($selRes);
       

        $cuponInadem = '';
        if ($Email != '') {

            require_once('../../modulos/phpmailer/sendMail.php');

            $mail->From = "mailer@netwarmonitor.com";
            $mail->FromName = "NetwareMonitor";
            $mail->Subject = "Factura Generada";
            $mail->AltBody = "NetwarMonitor";
            $mail->MsgHTML('Factura Generada');
            if($res['rows'][0]['serieCsdEmisor']=='3'){
                $mail->AddAttachment('../../modulos/cont/xmls/facturas/temporales/'. $uid .'.xml');
                $mail->AddAttachment('../../modulos/facturas/'. $uid .'.pdf');
            }else{
                $mail->AddAttachment('../../modulos/cont/xmls/facturas/temporales/'. $uid .'.xml');
                $mail->AddAttachment('../../modulos/facturas/'. $uid .'.pdf');
            } 
            //$Email = 'ovazquez@netwarmonitor.com';
            $Email = explode(';', $Email);
            foreach ($Email as $key => $value) {
                $mail->AddAddress($value, $value);
            }
            //$mail->AddAddress($Email, $Email);


            @$mail->Send();
        }
        //$cuponInadem='';
       if($cuponInadem ==null || $cuponInadem==''){
        return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => false);
       }else{
        return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => $cuponInadem);
       } 
    }

    function guardarVenta($vitrina,$idCliente){

        date_default_timezone_set("Mexico/General");
        $fechaactual = date("Y-m-d H:i:s");

        switch ($vitrina) {
            case '2200':
                $productos = array("475", "476","477","478","479","480");
                $monto = 26900.00;
                $montoImpuestos = 3710.34;
                $jsonImpues = '{"IVA 16%":3710.34}';
                $subtotalX = 23189.65;
                break;
            case '2206':
                $productos = array("475", "481","482","483","484","480");
                $monto = 26900.00;
                $montoImpuestos = 3710.34;
                $jsonImpues = '{"IVA 16%":3710.34}';
                $subtotalX = 23189.65;
                break;
            case '2214':
                $productos = array("475", "476","485","486","487","488","484","480");
                $monto = 27045.00;
                $montoImpuestos = 3710.34;
                $jsonImpues = '{"IVA 16%":3710.34}';
                $subtotalX = 23314.65;
                break;        
            
            default:
                # code...
                break;
        }
        $insert1 = "INSERT into app_pos_venta(idCliente,monto,estatus,idEmpleado,documento,fecha,cambio,montoimpuestos,idSucursal,impuestos,subtotal) values('".$idCliente."','".$monto."','1','3','2','".$fechaactual."','0.00','".$montoImpuestos."','1','".$jsonImpues."','".$subtotalX."')";
        $res1 = $this->queryArray($insert1);
        $idVenta = $res1['insertId'];

        foreach ($productos as $key => $value) {
            $selPr = "SELECT id,precio from app_productos where id=".$value;
            //echo $selPr;
            $resSelPr = $this->queryArray($selPr);
            $impuestoproductoventa = (float) $resSelPr['rows'][0]['precio'] * 0.16;
            $total = (float) $resSelPr['rows'][0]['precio'] + (float) $impuestoproductoventa;
            $insert2 = "INSERT into app_pos_venta_producto(idProducto,cantidad,preciounitario,subtotal,idVenta,impuestosproductoventa,total) values('".$value."','1','".$resSelPr['rows'][0]['precio']."','".$resSelPr['rows'][0]['precio']."','".$idVenta."','".$impuestoproductoventa."','".$total."')";
            //echo $insert2;
            //exit();
            $res2 = $this->queryArray($insert2);
            $idVentaProdcutoI = $res2['insertId'];

            $insertventaproductoimpuesto = "INSERT into app_pos_venta_producto_impuesto (idVentaproducto,idImpuesto,porcentaje) values (" . $idVentaProdcutoI. ",'1','16');";
            $resultventaproductoimpuesto = $this->queryArray($insertventaproductoimpuesto);

        }
         $insert3 = "INSERT into app_pos_venta_pagos(idVenta,idFormapago,monto) values('".$idVenta."','7','".$monto."')";
         $res3 = $this->queryArray($insert3);

         return $idVenta;

    } 
    function guardaError($error,$idInadem){
        $upd = "UPDATE cliente_inadem set factura='Error' where id=".$idInadem;
 
        $esdr = $this->queryArray($upd);

        return 1;
    }
    function guardaErrorNota($error,$idInadem){
        $upd = "UPDATE app_inadem_notas set uuidNota='Error' where id=".$idInadem;
 
        $esdr = $this->queryArray($upd);

        return 1;
    }


    
    function facturaIndem($idCIn){

        $selc1 = 'SELECT * from cliente_inadem where id='.$idCIn;
        $res1 = $this->queryArray($selc1);
        $idCliente = $res1['rows'][0]['idCliente'];        
        
        $selec2 = 'SELECT id from comun_facturacion where nombre='.$idCliente;
        $res2 = $this->queryArray($selec2);
        $idFact = $res2['rows'][0]['id'];

        //echo '('.$idCIn.'-'.$idCliente.'-'.$idFact.')';


        $folios = "SELECT serie,folio FROM pvt_serie_folio LIMIT 1";
        $data = $this->queryArray($folios);
        if ($data["total"] > 0) {
            $data = $data["rows"][0];
        }
        ///Busca el pack para facturar
        $qrpac = "SELECT pac FROM pvt_configura_facturacion WHERE id=1;";
        $respac = $this->queryArray($qrpac);
        $pac = $respac["rows"][0]["pac"];
        // Receptor
        //===============================================================

        $parametros['Receptor'] = array();
        if ($idFact == 0) {

            $parametros['Receptor']['RFC'] = "XAXX010101000";
        } else {
            $df = (object) $this->datosFacturacion($idFact);
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
        }
        $formapago = '03';

        $Email = $df->correo;

        $parametros['DatosCFD']['FormadePago'] = "Pago en una sola exhibicion";
        $parametros['DatosCFD']['MetododePago'] = utf8_decode($formapago);
        $parametros['DatosCFD']['Moneda'] = "MXP";
        //$parametros['DatosCFD']['Subtotal'] = str_replace(",", "", number_format($_SESSION["caja"]["cargos"]["subtotal"],2));
       // $parametros['DatosCFD']['Subtotal'] = $parametros['DatosCFD']['Subtotal'] - 0.01;
        //$parametros['DatosCFD']['Total'] = str_replace(",", "", number_format($_SESSION["caja"]["cargos"]["total"],2));
       // $parametros['DatosCFD']['Total'] = $parametros['DatosCFD']['Total'] - 0.01;
        $parametros['DatosCFD']['Serie'] = $data['serie'];
        $parametros['DatosCFD']['Folio'] = $data['folio'];
        $parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
        $parametros['DatosCFD']['MensajePDF'] = "";
        $parametros['DatosCFD']['LugarDeExpedicion'] = "Mexico";

        ///////////Manda a generar venta 
        $idVenta = $this->guardarVenta($res1['rows'][0]['vitrina'],$idCliente);

        //////Segun la vitrina los conceptos

        switch ($res1['rows'][0]['vitrina']) {
            case '2200':
                $conceptosDatos[0]["Cantidad"] = '1.00';
                $conceptosDatos[0]["Unidad"] = 'Unidad';
                $conceptosDatos[0]["Precio"] = '0.00';
                $conceptosDatos[0]["Descripcion"]='SOLUCION:'.$res1['rows'][0]['vitrina'].';VALE:'.$res1['rows'][0]['cupon'].';FOLIO:'.$res1['rows'][0]['folio_inadem'];
                $conceptosDatos[0]['Importe'] = '0.00';

                $conceptosDatos[1]["Cantidad"] = '1.00';
                $conceptosDatos[1]["Unidad"] = 'Unidad';
                $conceptosDatos[1]["Precio"] = '7284.48';
                $conceptosDatos[1]["Descripcion"]='LapTop HP 240 G4, Procesador Core i3, 4 Gb de Memoria Ram, 500 GB de Disco Duro';
                $conceptosDatos[1]['Importe'] = '7284.48';

                $conceptosDatos[2]["Cantidad"] = '1.00';
                $conceptosDatos[2]["Unidad"] = 'Unidad';
                $conceptosDatos[2]["Precio"] = '2974.14';
                $conceptosDatos[2]["Descripcion"]='Tableta 2 en 1 Marca INCO-Microsoft, 2 GB de Mamoria Ram, 16 GB de Disco Duro, Incluye Teclado Fisico';
                $conceptosDatos[2]['Importe'] = '2974.14';

                $conceptosDatos[3]["Cantidad"] = '1.00';
                $conceptosDatos[3]["Unidad"] = 'Unidad';
                $conceptosDatos[3]["Precio"] = '4310.35';
                $conceptosDatos[3]["Descripcion"]='ACONTIA Software de Contabilidad Electrónica, APPMINISTRA Software para Administracion y Comercial';
                $conceptosDatos[3]['Importe'] = '4310.35';

                $conceptosDatos[4]["Cantidad"] = '1.00';
                $conceptosDatos[4]["Unidad"] = 'Unidad';
                $conceptosDatos[4]["Precio"] = '6465.52';
                $conceptosDatos[4]["Descripcion"]='Asesoría técnica especializada en el uso del Hardware y Software Acontia y Appministra';
                $conceptosDatos[4]['Importe'] = '6465.52';

                $conceptosDatos[5]["Cantidad"] = '1.00';
                $conceptosDatos[5]["Unidad"] = 'Unidad';
                $conceptosDatos[5]["Precio"] = '2155.17';
                $conceptosDatos[5]["Descripcion"]='USB Alcatel Touch Modelo C602D, UMTS 3G 900-21000 MHz, con 300 Mb por Mes';
                $conceptosDatos[5]['Importe'] = '2155.17';

                $parametros['DatosCFD']['Subtotal']  = '23189.66';
                $parametros['DatosCFD']['Total'] = '26900.000';
                
                $nn2["IVA"]["16.00"] = '3710.34';
                $nnf["IVA"]["16.00"]['Valor'] = '3710.34';
                
                break;
            case '2206':
                $conceptosDatos[0]["Cantidad"] = '1.00';
                $conceptosDatos[0]["Unidad"] = 'Unidad';
                $conceptosDatos[0]["Precio"] = '0.01';
                $conceptosDatos[0]["Descripcion"]='SOLUCION:'.$res1['rows'][0]['vitrina'].';VALE:'.$res1['rows'][0]['cupon'].';FOLIO:'.$res1['rows'][0]['folio_inadem'];
                $conceptosDatos[0]['Importe'] = '0.01';

                $conceptosDatos[1]["Cantidad"] = '1.00';
                $conceptosDatos[1]["Unidad"] = 'Unidad';
                $conceptosDatos[1]["Precio"] = '7327.59';
                $conceptosDatos[1]["Descripcion"]='Equipo de computo de escritorio AIO Todo en Uno, Marca HP Modelo 205 G2, Monitor 18.5 Pulgadas, Procesador AMD E1-6010 Dual Core, 1 TB disco duro, 4 GB de Memoria Ram';
                $conceptosDatos[1]['Importe'] = '7327.59';

                $conceptosDatos[2]["Cantidad"] = '1.00';
                $conceptosDatos[2]["Unidad"] = 'Unidad';
                $conceptosDatos[2]["Precio"] = '2931.03';
                $conceptosDatos[2]["Descripcion"]='Tablet Duplet Tab Mini, Procesador Intel Quad Core a 1.8 GHz, 2 Gb de Memoria Ram, 16 Gb Almacenamiento, Teclado Fisico, Pantalla Multitouch 8.1 Pulgadas, Camara Frontal y Trasera de 0.3 Mpx';
                $conceptosDatos[2]['Importe'] = '2931.03';

                $conceptosDatos[3]["Cantidad"] = '1.00';
                $conceptosDatos[3]["Unidad"] = 'Unidad';
                $conceptosDatos[3]["Precio"] = '4310.34';
                $conceptosDatos[3]["Descripcion"]='Software APPMINISTRA Producción para Administracion y Comercial';
                $conceptosDatos[3]['Importe'] = '4310.34';

                $conceptosDatos[4]["Cantidad"] = '1.00';
                $conceptosDatos[4]["Unidad"] = 'Unidad';
                $conceptosDatos[4]["Precio"] = '6465.52';
                $conceptosDatos[4]["Descripcion"]='Asesoría técnica especializada en el uso del Hardware y Software Acontia y Appministra';
                $conceptosDatos[4]['Importe'] = '6465.52';

                $conceptosDatos[5]["Cantidad"] = '1.00';
                $conceptosDatos[5]["Unidad"] = 'Unidad';
                $conceptosDatos[5]["Precio"] = '2155.17';
                $conceptosDatos[5]["Descripcion"]='USB Alcatel Touch Modelo C602D, UMTS 3G 900-21000 MHz, con 300 Mb por Mes';
                $conceptosDatos[5]['Importe'] = '2155.17';

                $parametros['DatosCFD']['Subtotal']  = '23189.66';
                $parametros['DatosCFD']['Total'] = '26900.000';
                
                $nn2["IVA"]["16.00"] = '3710.34';
                $nnf["IVA"]["16.00"]['Valor'] = '3710.34';
                break;
            case '2214':
                $conceptosDatos[0]["Cantidad"] = '1.00';
                $conceptosDatos[0]["Unidad"] = 'Unidad';
                $conceptosDatos[0]["Precio"] = '0.01';
                $conceptosDatos[0]["Descripcion"]='SOLUCION:'.$res1['rows'][0]['vitrina'].';VALE:'.$res1['rows'][0]['cupon'].';FOLIO:'.$res1['rows'][0]['folio_inadem'];
                $conceptosDatos[0]['Importe'] = '0.01';

                $conceptosDatos[1]["Cantidad"] = '1.00';
                $conceptosDatos[1]["Unidad"] = 'Unidad';
                $conceptosDatos[1]["Precio"] = '7284.48';
                $conceptosDatos[1]["Descripcion"]='LapTop HP 240 G4, Procesador Core i3, 4 Gb de Memoria Ram, 500 GB de Disco Duro';
                $conceptosDatos[1]['Importe'] = '7284.48';

                $conceptosDatos[2]["Cantidad"] = '1.00';
                $conceptosDatos[2]["Unidad"] = 'Unidad';
                $conceptosDatos[2]["Precio"] = '1379.31';
                $conceptosDatos[2]["Descripcion"]='Miniprinter termica ECLine EC-PM-5890X';
                $conceptosDatos[2]['Importe'] = '1379.31';

                $conceptosDatos[3]["Cantidad"] = '1.00';
                $conceptosDatos[3]["Unidad"] = 'Unidad';
                $conceptosDatos[3]["Precio"] = '948.28';
                $conceptosDatos[3]["Descripcion"]='Lector de Código de Barras ECLine EC-CD-8500';
                $conceptosDatos[3]['Importe'] = '948.28';

                $conceptosDatos[4]["Cantidad"] = '1.00';
                $conceptosDatos[4]["Unidad"] = 'Unidad';
                $conceptosDatos[4]["Precio"] = '771.55';
                $conceptosDatos[4]["Descripcion"]='Cajon de dinero ECLine EC-G5100-II-Grey';
                $conceptosDatos[4]['Importe'] = '771.55';



                $conceptosDatos[5]["Cantidad"] = '1.00';
                $conceptosDatos[5]["Unidad"] = 'Unidad';
                $conceptosDatos[5]["Precio"] = '4310.34';
                $conceptosDatos[5]["Descripcion"]='Appministra Sistema administrativo y comercial con especializacion en punto de venta';
                $conceptosDatos[5]['Importe'] = '4310.34';

                $conceptosDatos[6]["Cantidad"] = '1.00';
                $conceptosDatos[6]["Unidad"] = 'Unidad';
                $conceptosDatos[6]["Precio"] = '6465.52';
                $conceptosDatos[6]["Descripcion"]='Asesoría técnica especializada en el uso del Hardware y Software Acontia y Appministra';
                $conceptosDatos[6]['Importe'] = '6465.52';

                $conceptosDatos[7]["Cantidad"] = '1.00';
                $conceptosDatos[7]["Unidad"] = 'Unidad';
                $conceptosDatos[7]["Precio"] = '2155.17';
                $conceptosDatos[7]["Descripcion"]='USB Alcatel Touch Modelo C602D, UMTS 3G 900-21000 MHz, con 300 Mb por Mes';
                $conceptosDatos[7]['Importe'] = '2155.17';

                $parametros['DatosCFD']['Subtotal']  = '23314.66';
                $parametros['DatosCFD']['Total'] = '27045.000';
                
                $nn2["IVA"]["16.00"] = '3730.34';
                $nnf["IVA"]["16.00"]['Valor'] = '3730.34';
                break;
            default:
                # code...
                break;
        }


        /* FACTURACION AZURIAN
        ============================================================== */
        require_once('../../modulos/SAT/config.php');

        date_default_timezone_set("Mexico/General");
        $fecha = date('Y-m-d') . 'T' . date('H:i:s', strtotime("-10 minute"));


        $logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
        $logo = $this->queryArray($logo);
        $r3 = $logo["rows"][0];

        $azurian = array();
        //echo $bloqueo.'??';
        $bloqueo = 0;
        if ($bloqueo == 0) {
            //echo 'entro a bloqueo';
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
        $azurian['Observacion']['Observacion'] = '';

        

        $nn = $nn2;
        $azurian['nn']['nn'] = $nn;
        $azurian['nnf']['nnf'] = $nnf;
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
        $azurian['Basicos']['subTotal'] = str_replace(',', '', $str_subtotal);
        $str_total = number_format($parametros['DatosCFD']['Total'], 2);
        $str_total = str_replace(',', '',$str_total);
        //$str_total = $str_total - 0.01;
        //$str_total = number_format($str_total,0).'.00';  //Comente para que S//algan Decimales Normalmente
        $str_total = number_format($str_total,2);
        $azurian['Basicos']['total'] = str_replace(',', '', $str_total); 

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
                //se emepiza a llenar los conceptos en el arreglo de azurian
        
        foreach ($conceptosDatos as $key => $value) {
            $value['Descripcion'] = preg_replace("/'/", "&apos;", $value['Descripcion']);
            $value['Descripcion'] = preg_replace('/"/', "&quot;", $value['Descripcion']); 
           // $value['Descripcion'] = preg_replace('("|\')', "&apos;", $value['Descripcion']);
            $value['Descripcion'] = eregi_replace("[\n|\r|\n\r]", " ", $value['Descripcion']);
            $value['Descripcion'] = trim($value['Descripcion']); 
            if($value['Unidad']==''){
                $value['Unidad']= "No Aplica";
            }
            $conceptosOri.='|' . $value['Cantidad'] . '|';
            $conceptosOri.=$value['Unidad'] . '|';
            $conceptosOri.=$value['Descripcion'] . '|';
            $conceptosOri.=str_replace(",", "", number_format($value['Precio'],2)) . '|';
            $conceptosOri.=str_replace(",", "", number_format($value['Importe'],2));
            $conceptos.="<cfdi:Concepto cantidad='" . $value['Cantidad'] . "' unidad='" . $value['Unidad'] . "' descripcion='" . $value['Descripcion'] . "' valorUnitario='" . str_replace(",", "", number_format($value['Precio'],2)) . "' importe='" . str_replace(",", "", number_format($value['Importe'],2)) . "'/>";
        }
        //////////impuestos azurian
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
                    if($clavetasa=='0.0'){
                        $val = 0;
                    }
                    if ($clave == 'IEPS') {
                        $tieps+=number_format($val, 2, '.', '');
                    }
                    if ($clave == 'IVA') {
                        $tiva+=number_format($val, 2, '.', '');
                    }
                    $traslads.='|' . $clave . '|';
                   // $traslads.='' . $clavetasa . '|';
                    $traslads.='' . number_format($clavetasa,2) . '|';
                    $traslads.=number_format($val, 2, '.', '');
                    $trasladsimp+=number_format($val, 2, '.', '');
                    $trasxml.="<cfdi:Traslado impuesto='" . $clave . "' tasa='" . number_format($clavetasa,2) . "' importe='" . number_format($val, 2, '.', '') . "' />";
                }
            } elseif ($clave == 'ISR' || $clave == 'IVAR') {
                $hayret = 1;

                foreach ($nn[$clave] as $clavetasa => $val) {
                    if($clavetasa=='0.0'){
                        $val = 0;
                    }
                if($clave == 'IVAR'){
                    $clave = substr($clave, 0, -1);
                    $king = 1;
                } 
                    $tisr+=number_format($val, 2, '.', '');
                    $retenids.='|' . $clave . '|';
                    $retenidsT.='' . number_format($val, 2, '.', '') . '|';
                    $retenids.=number_format($val, 2, '.', '');
                    $retenciones+=number_format($val, 2, '.', '');
                    $retexml.="<cfdi:Retencion impuesto='" . $clave . "' importe='" . number_format($val, 2, '.', '') . "' />";
                    /*if($king ==1){
                        $clave = 'IVAR';
                        $king = 0;
                    } */
                }
            }
        }////fin del foreach nn

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
          if($hayret == 1){
            $cadRet = '|'.str_replace(',', '', number_format($tisr,2));
          }else{
            $cadRet = '';
          } 

          $azurian['Impuestos']['isr'] = $retenids.$cadRet;
          $azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');

          $azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
          $azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');

        $ivas.=$isr . $iva;

        $azurian['Impuestos']['ivas'] = $ivas;       

        if($pac==2){
            require_once('../../modulos/SAT/funcionesSAT2.php');
        }else if($pac==1){
            require_once('../../modulos/lib/nusoap.php');
            require_once('../../modulos/SAT/funcionesSAT.php');  
        }


        //return array('datois' => 10 );
    } 

    function notaInadem($idReg){

        $sele = "SELECT * from app_inadem_notas where id=".$idReg;
        $rese = $this->queryArray($sele);

        $folioUuid = $rese['rows'][0]['uuid'];
        $monto = $rese['rows'][0]['importe_nota']*1;
   
        $montosiniva = ($monto/1.16); //subtotal o importe
        $iva = ($montosiniva*0.16); // puro iva
        $total = $montosiniva+$iva;

          $montosiniva=str_replace(',', '', number_format($montosiniva,2));
          $iva=str_replace(',','',number_format($iva,2));
          $total=str_replace(',','',number_format($total,2));

        require_once('../../modulos/SAT/config.php');
        date_default_timezone_set("Mexico/General");
        $fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));

        //$SeleCad = "SELECT cadenaOriginal, idFact, idSale FROM app_respuestaFacturacion WHERE id=".$idFac;
        $SeleCad = "SELECT cadenaOriginal, idFact, idSale FROM app_respuestaFacturacion WHERE folio='".$folioUuid."'";;
        $cadenaOri = $this->queryArray($SeleCad);

        $rrfc=$cadenaOri['rows'][0]['idFact'];
        $idFact=$cadenaOri['rows'][0]['idFact'];
        $idVenta=$cadenaOri['rows'][0]['idSale'];
        //echo $cadenaOri['rows'][0]['cadenaOriginal'];
        $azurian=base64_decode($cadenaOri['rows'][0]['cadenaOriginal']);

        $azurian = str_replace("\\", "", $azurian);
        if($azurian!=''){ 
            $azurian=json_decode($azurian); 
        }
        $azurian = $this->object_to_array($azurian);

       $facConcepto = $azurian['Basicos']['folio'];
      

          if (isset($azurian['nn']['nn']['IVA']['0.0']['Valor'])) {
              $iva = 0.00;
              $ivaPorcet = '0.00';
              $montosiniva = $total;
          }else{
              $ivaPorcet = '16.00';
          }

        ////Busca el pack para facturar
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

        /* Nota de credito
          ============================================================== */
          /*echo 'primero';
          print_r($azurian['Receptor']);
          echo '<br><br>'; */
         $azurian['Basicos']['tipoDeComprobante']='egreso';
          if($rrfc>0){
            //$result = $this->conexion->consultar("SELECT * FROM comun_facturacion WHERE id='$rrfc';");
            $rs = $this->queryArray("SELECT c.id, c.rfc, c.razon_social, c.correo, c.pais, c.regimen_fiscal, c.domicilio, c.num_ext, c.cp, c.colonia, e.estado, c.ciudad, c.municipio from comun_facturacion c , estados e WHERE e.idestado=c.estado and id='$rrfc';");
     
            $idCliente=$rs['rows'][0]['nombre'];
           /* $azurian['Receptor']['rfc']=strtoupper($rs['rows'][0]['rfc']);
            $azurian['Receptor']['nombre']=strtoupper($rs['rows'][0]['razon_social']);
            $azurian['DomicilioReceptor']['calle']=$rs['rows'][0]['domicilio'];
            $azurian['DomicilioReceptor']['noExterior']=$rs['rows'][0]['num_ext'];
            $azurian['DomicilioReceptor']['colonia']=$rs['rows'][0]['colonia'];
            $azurian['DomicilioReceptor']['localidad']=$rs['rows'][0]['ciudad'];
            $azurian['DomicilioReceptor']['municipio']=$rs['rows'][0]['municipio'];
            $azurian['DomicilioReceptor']['estado']=$rs['rows'][0]['estado'];
            $azurian['DomicilioReceptor']['pais']=$rs['rows'][0]['pais'];
            $azurian['DomicilioReceptor']['codigoPostal']=$rs['rows'][0]['cp'];
            $azurian['Correo']['Correo'] = $rs['rows'][0]['correo']; */
          }else{
            $idCliente='';
           /* $azurian['Receptor']['rfc']='XAXX010101000';
            $azurian['Receptor']['nombre']='Factura generica';
            $azurian['DomicilioReceptor']['calle']='';
            $azurian['DomicilioReceptor']['noExterior']='';
            $azurian['DomicilioReceptor']['colonia']='';
            $azurian['DomicilioReceptor']['localidad']='';
            $azurian['DomicilioReceptor']['municipio']='';
            $azurian['DomicilioReceptor']['estado']='';
            $azurian['DomicilioReceptor']['pais']='';
            $azurian['DomicilioReceptor']['codigoPostal']='';
            $azurian['Correo']['Correo'] = ''; */
          }

              $result3 = "SELECT * FROM pvt_serie_folio WHERE id=1;";
              $rs3 = $this->queryArray($result3);

              $result4 = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
              $rs4 = $this->queryArray($result4);

              $azurian['org']['logo'] = $rs4['rows'][0]['logoempresa'];

              /* Datos serie y folio
              ============================================================== */
              $azurian['Basicos']['serie']=$rs3['rows'][0]['serie_nc']; //No obligatorio
              $azurian['Basicos']['folio']=$rs3['rows'][0]['folio_nc'];

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
              $azurian['Basicos']['sello']='';

            /* Impuestos
              ============================================================== */
              $tisr=$azurian['Impuestos']['totalImpuestosRetenidos'];
              $tiva=$azurian['Impuestos']['totalImpuestosTrasladados'];
              $tieps=$azurian['Impuestos']['totalImpuestosIeps'];
               //$azurian['Observacion']['Observacion']="Esta nota de credito esta vinculada a la factura con folio ".$folio;
              $nn2["IVA"][$ivaPorcet]["Valor"] = str_replace(",", "", $iva);
              //echo $nn2["IVA"]["16.0"]["Valor"];
              //exit();

              $azurian['nn']['nn']=$nn2;

              
              $conceptosOri='';
              $conceptos='';
                  
                  $conceptosOri.='|1|';
                    $conceptosOri.='concepto|';
                    $conceptosOri.='Nota de credito vinculada a la factura con folio '.$facConcepto.'|';
                    $conceptosOri.=str_replace(",", "", $montosiniva) . '|';
                    $conceptosOri.=str_replace(",", "", $montosiniva);
                    $conceptos.="<cfdi:Concepto cantidad='1' unidad='concepto' descripcion='Nota de credito vinculada a la factura con folio ".$facConcepto."' valorUnitario='" . str_replace(",", "", $montosiniva) . "' importe='" . str_replace(",", "", $montosiniva) . "'/>";

              $azurian['Conceptos']['conceptos'] = $conceptos;
              $azurian['Conceptos']['conceptosOri'] = $conceptosOri;


              $azurian['Basicos']['subTotal'] = str_replace(",", "", $montosiniva);
              $azurian['Basicos']['total'] = str_replace(",", "", $total);
              $ivax="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='".$ivaPorcet."' importe='".str_replace(",", "", $iva)."' /></cfdi:Traslados>";
              $azurian['Impuestos']['ivas'] = $ivax;
              $azurian['Impuestos']['totalImpuestosTrasladados']=str_replace(",", "", $iva);
              $azurian['Impuestos']['iva']='|IVA|'.$ivaPorcet.'|'.str_replace(",", "", $iva).'|'.str_replace(",", "", $iva).'';
              
              //print_r($azurian);
              // exit();


        if($pac==2){
            require_once('../../modulos/SAT/funcionesSAT2.php');
        }else if($pac==1){
            require_once('../../modulos/lib/nusoap.php');
            require_once('../../modulos/SAT/funcionesSAT.php');  
        } 


    }

}

?>