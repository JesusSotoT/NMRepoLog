<?php
  //ini_set('display_errors', 1);
  function object_to_array($data){
    if (is_array($data) || is_object($data))
      {
          $result = array();
          foreach ($data as $key => $value){
              $result[$key] = object_to_array($value);
          }
          return $result;
      }
      return $data;
  }
 // require_once("../configFC.php");

  $arregloFactura = array();
  $cont=0;
  $countCon=0;
  $countImp=0;
  $Email="";
  $f_mp='';

  switch ($clave) {
    case 'cancel':
      $result = $this->conexion->consultar("select total from pvt_contadorFacturas where id='1' LIMIT 1");
      /*if($rs = $this->conexion->siguiente($result)){
          if($rs{'total'}>100){
            $JSON = array('success' =>0, 
              'error'=>210, 
              'mensaje'=>'Has superado el limite de 100 timbres permitidos.');
              echo json_encode($JSON);
              exit();
          }
      }*/
      $cancelFac=1;
      $result = $this->conexion->consultar("select a.folio, a.idComprobante, b.rfc, b.cer, b.llave, b.clave, a.serieCsdEmisor from pvt_respuestaFacturacion a, pvt_configura_facturacion b where a.id='$idVenta' LIMIT 1");
      if($rs = $this->conexion->siguiente($result)){
        $rfccancel=  $rs{'rfc'};
        $cancelFolio=  $rs{'folio'};
        $cancelID= $rs{'idComprobante'};
        $elkey= $rs{'llave'};
        $elcer= $rs{'cer'};
        $clave= $rs{'clave'};
        $serieCsdE= $rs{'serieCsdEmisor'};
        //$cancelXMLopen = fopen('../../modulos/facturas/'.$cancelFolio.'.xml', "r");
       // $cancelXML = fread($cancelXMLopen, filesize('../../modulos/facturas/'.$cancelFolio.'.xml'));
       // fclose($cancelXMLopen);
       // $val=explode(' ', $cancelXML);
       /* if($val[0]!='<?xml'){
          $JSON = array('success' =>0, 
          'error'=>201, 
          'mensaje'=>'El XML de la factura a cancelar esta mal formado.');
          echo json_encode($JSON);
          exit();
        }  */
      }else{
        $JSON = array('success' =>0, 
        'error'=>200, 
        'mensaje'=>'No se encontro el XML de la factura solicitada.');
        echo json_encode($JSON);
        exit();
      }

      $strUUID = $cancelFolio;

      require_once('../../modulos/SAT/config.php');
      //require_once('../../modulos/lib/nusoap.php');
      //require_once('../../modulos/SAT/funcionesSAT.php');

      if($serieCsdE=='3'){
          include ($positionPath.'/wsinvoice/cancelInvoice.php');
      }else{

          require_once('../../modulos/lib/nusoap.php');
          require_once('../../modulos/SAT/funcionesSAT.php');   
      }

    break;
    
    case 'guardaNc':
      //echo 'id='.$idVenta.'iva='.$iva.'montosin='.$montosiniva.'total='.$total;
      //exit();
      $montosiniva=number_format($montosiniva,2);
      $iva=number_format($iva,2);
      $total=number_format($total,2);
      //echo $folio;
      
      require_once('../../modulos/SAT/config.php');
      date_default_timezone_set("Mexico/General");
      $fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));

      $result = $this->conexion->consultar("SELECT cadenaOriginal, idFact, idSale,trackid FROM pvt_respuestaFacturacion WHERE id='$idVenta';");
      $rs = $this->conexion->siguiente($result);
      $rrfc=$rs{'idFact'};
      $idFact=$rs{'idFact'};
      $idVenta=$rs{'idSale'};
      ///recuperar si tiene track id
      $rastreo = $rs{'trackid'};
      if($rastreo!=0){
         $consultaCFDI = 1;
      }

      $qrpac = $this->conexion->consultar("SELECT pac FROM pvt_configura_facturacion WHERE id=1;");
      $respac = $this->conexion->siguiente($qrpac);
      $pac = $respac{'pac'};

      $azurian=base64_decode($rs{'cadenaOriginal'});
      //var_dump($azurian);
      if($azurian!=''){ 
        $azurian=json_decode($azurian); 
        //var_dump($azurian);
      }
      $azurian = object_to_array($azurian);
      //var_dump($azurian);

      //print_r($azurian);
      //echo 'XXXX'.$azurian['nn']['nn']['IVA']['16.000000']['Valor'];
      if (isset($azurian['nn']['nn']['IVA']['0.0']['Valor'])) {
          $iva = 0.00;
          $ivaPorcet = '0.00';
          $montosiniva = $total;
      }else{
          $ivaPorcet = '16.00';
      }
        
      $result2 = $this->conexion->consultar("SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;");
      $rs2 = $this->conexion->siguiente($result2);

      /* DATOS OBLIGATORIOS DEL EMISOR
      ================================================================== */
      $rfc_cliente=strtoupper($rs2{'rfc'});

      $parametros['EmisorTimbre'] = array(); 
      $parametros['EmisorTimbre']['RFC'] = strtoupper($rs2{'rfc'}); 
      $parametros['EmisorTimbre']['RegimenFiscal'] = strtoupper($rs2{'regimenf'});
      $parametros['EmisorTimbre']['Pais'] = $rs2{'pais'}; 
      $parametros['EmisorTimbre']['RazonSocial'] = $rs2{'razon_social'}; 
      $parametros['EmisorTimbre']['Calle'] = $rs2{'calle'}; 
      $parametros['EmisorTimbre']['NumExt'] = $rs2{'num_ext'};
      $parametros['EmisorTimbre']['Colonia'] = $rs2{'colonia'};
      $parametros['EmisorTimbre']['Ciudad'] = $rs2{'ciudad'}; //Ciudad o Localidad
      $parametros['EmisorTimbre']['Municipio'] = $rs2{'municipio'};
      $parametros['EmisorTimbre']['Estado'] = $rs2{'estado'};
      $parametros['EmisorTimbre']['CP'] = $rs2{'cp'};
      $cer_cliente=$pathdc.'/'.$rs2{'cer'};
      $key_cliente=$pathdc.'/'.$rs2{'llave'};
      $pwd_cliente=$rs2{'clave'};

      if($rs2{'rfc'}==''){

        $JSON = array('success' =>0,
          'error'=>1001, 
          'mensaje'=>'No existen datos de emisor.');
        echo json_encode($JSON);
        exit();

      }

      /* Nota de credito
      ============================================================== */
      $azurian['Basicos']['tipoDeComprobante']='egreso';

      /* Datos Receptor
      ============================================================== */
      if($rrfc>0){
        //$result = $this->conexion->consultar("SELECT * FROM comun_facturacion WHERE id='$rrfc';");
        $result = $this->conexion->consultar("SELECT c.id, c.rfc, c.razon_social, c.correo, c.pais, c.regimen_fiscal, c.domicilio, c.num_ext, c.cp, c.colonia, e.estado, c.ciudad, c.municipio from comun_facturacion c , estados e WHERE e.idestado=c.estado and id='$rrfc';");
        $rs = $this->conexion->siguiente($result);
        $idCliente=$rs{'nombre'};
        $azurian['Receptor']['rfc']=strtoupper($rs{'rfc'});
        $azurian['Receptor']['nombre']=strtoupper($rs{'razon_social'});
        $azurian['DomicilioReceptor']['calle']=$rs{'domicilio'};
        $azurian['DomicilioReceptor']['noExterior']=$rs{'num_ext'};
        $azurian['DomicilioReceptor']['colonia']=$rs{'colonia'};
        $azurian['DomicilioReceptor']['localidad']=$rs{'ciudad'};
        $azurian['DomicilioReceptor']['municipio']=$rs{'municipio'};
        $azurian['DomicilioReceptor']['estado']=$rs{'estado'};
        $azurian['DomicilioReceptor']['pais']=$rs{'pais'};
        $azurian['DomicilioReceptor']['codigoPostal']=$rs{'cp'};
        $azurian['Correo']['Correo'] = $rs{'correo'};
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

      $result3 = $this->conexion->consultar("SELECT * FROM pvt_serie_folio WHERE id=1;");
      $rs3 = $this->conexion->siguiente($result3);

      $result4 = $this->conexion->consultar("SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;");
      $rs4 = $this->conexion->siguiente($result4);

      $azurian['org']['logo'] = $rs4{'logoempresa'};

      /* Datos serie y folio
      ============================================================== */
      $azurian['Basicos']['serie']=$rs3{'serie_nc'}; //No obligatorio
      $azurian['Basicos']['folio']=$rs3{'folio_nc'};

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
            $conceptosOri.='Nota de credito vinculada a la factura con folio '.$folio.'|';
            $conceptosOri.=str_replace(",", "", $montosiniva) . '|';
            $conceptosOri.=str_replace(",", "", $montosiniva);
            $conceptos.="<cfdi:Concepto cantidad='1' unidad='concepto' descripcion='Nota de credito vinculada a la factura con folio ".$folio."' valorUnitario='" . str_replace(",", "", $montosiniva) . "' importe='" . str_replace(",", "", $montosiniva) . "'/>";

      $azurian['Conceptos']['conceptos'] = $conceptos;
      $azurian['Conceptos']['conceptosOri'] = $conceptosOri;


      $azurian['Basicos']['subTotal'] = str_replace(",", "", $montosiniva);
      $azurian['Basicos']['total'] = str_replace(",", "", $total);
      $ivax="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='".$ivaPorcet."' importe='".str_replace(",", "", $iva)."' /></cfdi:Traslados>";
      $azurian['Impuestos']['ivas'] = $ivax;
      $azurian['Impuestos']['totalImpuestosTrasladados']=str_replace(",", "", $iva);
      $azurian['Impuestos']['iva']='|IVA|'.$ivaPorcet.'|'.str_replace(",", "", $iva).'|'.str_replace(",", "", $iva).'';

     // print_r($azurian);
     // exit();

     if($pac==2){
        require_once('../../modulos/SAT/funcionesSAT2.php');
    }else if($pac==1){
        require_once('../../modulos/lib/nusoap.php');
        require_once('../../modulos/SAT/funcionesSAT.php');  
    }

    break;

    case 'reff':

      $f_tc='F';
      $result = $this->conexion->consultar("select a.idSale, a.idFact, b.*, c.monto from pvt_respuestaFacturacion a
  INNER JOIN comun_facturacion b on b.id=a.idFact
  INNER JOIN venta c on c.idVenta=a.idSale
 where a.id='$idVenta' LIMIT 1");
      if($rs = $this->conexion->siguiente($result)){
        $iids=  $rs{'idSale'};
        $f_subtotal=  $rs{'monto'}; 
        $f_total=$rs{'monto'};
        $facOri=$rs{'idFact'};
      }

        $iddFact=$facOri;
    

      //$result = $this->conexion->consultar("SELECT * FROM comun_facturacion WHERE id='$iddFact'");
      $result = $this->conexion->consultar("SELECT c.id, c.rfc, c.razon_social, c.correo, c.pais, c.regimen_fiscal, c.domicilio, c.num_ext, c.cp, c.colonia, e.estado, c.ciudad, c.municipio from comun_facturacion c , estados e WHERE e.idestado=c.estado and id='$iddFact';"      
);
      if($rs = $this->conexion->siguiente($result)){
      
        $f_rfc= $rs{'rfc'};
        $f_rz=  $rs{'razon_social'};
        $f_pais=$rs{'pais'};

        $f_calle=$rs{'domicilio'};
        $f_numExt=$rs{'num_ext'};
        $f_colonia=$rs{'colonia'};
        $f_municipio=$rs{'municipio'};
        $f_ciudad=$rs{'ciudad'};
        $f_cp=$rs{'cp'};
        $f_estado=$rs{'estado'};

        $f_serie="A";
        $f_comp= $f_tc;
        $f_pdf= "";
        $f_email='';
        $f_venta=$idVenta;
        $f_fact='';
      }
    

   $result = $this->conexion->consultar("select a.preciounitario, a.impuestosproductoventa, a.total, b.nombre, b.precioventa, a.cantidad from venta_producto a
  inner join mrp_producto b on b.idProducto=a.idProducto
  -- inner join venta_producto_impuesto c on c.idVentaproducto=a.idventa_producto
  -- inner join impuesto d on d.id=c.idImpuesto
where a.idVenta='$iids';");
      $x=0;
      $totalimp=0;
      while($rsw = $this->conexion->siguiente($result)){
        $conceptosDatos[$x]['Cantidad'] = $rsw['cantidad'];
        $conceptosDatos[$x]['Unidad'] = 'Pieza';
        $conceptosDatos[$x]['Descripcion'] = utf8_decode($rsw['nombre']);
        $conceptosDatos[$x]['Precio'] = $rsw['preciounitario'];
        $conceptosDatos[$x]['Importe'] = $rsw['total'];
        $totalimp=($totalimp*1)+($rsw['impuestosproductoventa']*1);
        $x++;
      }

      $countCon=1;

      $impuestosDatos = array(
          array(
            'TipoImpuesto' => "IVA",
            'Tasa' => 16,
            'Importe' => $totalimp
          )
      );
      $f_subtotal=($f_total*1)-($totalimp*1);
      $countImp=1;

    break;

    case 'genericFact':
      $f_tc='F';
      $result = $this->conexion->consultar("select a.idSale, a.idFact, a.folio, b.*, c.monto from pvt_respuestaFacturacion a
  INNER JOIN comun_facturacion b on b.id=a.idFact
  INNER JOIN venta c on c.idVenta=a.idSale
 where a.id='$idVenta' LIMIT 1");
 
 
 /*"select if(b.rfc is null,'XAXX010101000',b.rfc) as rfc,b.razon_social razonsocial,b.domicilio calle,b.num_ext numext,b.colonia colonia,b.municipio municipio,b.ciudad ciudad,b.estado estado,b.pais pais,b.correo correos,b.cp cp,b.id id, f.monto, group_concat(fp.nombre) as fpago, group_concat(g.referencia) as referencia from pvt_pendienteFactura a 
          left join comun_facturacion b on b.nombre=a.id_cliente and b.id='$rrfc'
          left join venta_pagos g on g.idVenta=a.id_sale
          left join forma_pago fp on fp.idFormapago = g.idFormapago 
          inner join venta f on f.idVenta=a.id_sale where a.id_sale=".$id*/
 
      if($rs = $this->conexion->siguiente($result)){
        $nrfc="XAXX010101000";
        $iids=  $rs{'idSale'};
        $ffolio=$rs{'folio'};
        $rz=  $rs{'razon_social'};
        $pais=$rs{'pais'};
        $subtotal=  $rs{'monto'}; 
        $total=$rs{'monto'};
        $importe=$total;
		
        if(preg_match('/,/', $rs{'fpago'}) ){
          $tfpa=explode(',',$rs{'fpago'});
          $tfre=explode(',',$rs{'referencia'});
          $h=0;

          foreach ($tfpa as $key => $value) {
            if($tfre[$h]!=''){
              $add=' Ref: '.$tfre[$h];
            }else{
              $add='';
            }
            $f_mp.=' '.$value.' '.$add.',';
            $h++;
          }
          $f_mp=trim($f_mp, ',');
          $f_mp=utf8_decode($f_mp);
        }else{
          if($rs{'referencia'}!=''){
              $add=' Ref: '.$rs{'referencia'};
            }else{
              $add='';
            }
          $f_mp=$rs{'fpago'}.' '.$add;
          $f_mp=utf8_decode($f_mp);
        }

        $Calle=$rs{'domicilio'};
        $NumExt=$rs{'num_ext'};
        $Colonia=$rs{'colonia'};
        $Municipio=$rs{'municipio'};
        $Ciudad=$rs{'ciudad'};
        $CP=$rs{'cp'};
        $Estado=$rs{'estado'};
      }

      $result = $this->conexion->consultar("select a.preciounitario, a.impuestosproductoventa, a.total, b.nombre, b.precioventa, a.cantidad from venta_producto a
  inner join mrp_producto b on b.idProducto=a.idProducto
where a.idVenta='$iids';");
      $x=0;
      $totalimp=0;
      while($rsw = $this->conexion->siguiente($result)){
        $conceptosDatos[$x]['Cantidad'] = $rsw['cantidad'];
        $conceptosDatos[$x]['Unidad'] = 'Pieza';
        $conceptosDatos[$x]['Descripcion'] = utf8_decode($rsw['nombre']);
        $conceptosDatos[$x]['Precio'] = $rsw['preciounitario'];
        $conceptosDatos[$x]['Importe'] = $rsw['total'];
        $totalimp=($totalimp*1)+($rsw['impuestosproductoventa']*1);
        $x++;
      }

      $countCon=1;

      $impuestosDatos = array(
          array(
            'TipoImpuesto' => "IVA",
            'Tasa' => 16,
            'Importe' => $totalimp
          )
      );
      $countImp=1;

      $f_subtotal=$subtotal;
      $f_total=$total;
      $f_serie="A";
      $f_comp= $f_tc;
      $f_pdf= "";

      $f_rfc=$nrfc;
      $f_rz=$rz;
      $f_pais=$pais;
      $f_email='';

      $f_calle=$Calle;
      $f_numExt=$NumExt;
      $f_colonia=$Colonia;
      $f_municipio=$Municipio;
      $f_ciudad=$Ciudad;
      $f_cp=$CP;
      $f_estado=$Estado;
      $f_email='';

      $f_venta=$iids;
      $f_fact='';

$f_subtotal=($f_total*1)-($totalimp*1);
    break;

    case 'allfs':
      $cadena=trim($cadena,',');

      require_once('../../modulos/SAT/config.php');
      date_default_timezone_set("Mexico/General");
      $fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));


      $result2 = $this->conexion->consultar("SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;");
      $rs2 = $this->conexion->siguiente($result2);


      $result3 = $this->conexion->consultar("SELECT * FROM pvt_serie_folio WHERE id=1;");
      $rs3 = $this->conexion->siguiente($result3);

      $result4 = $this->conexion->consultar("SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;");
      $rs4 = $this->conexion->siguiente($result4);

      $azurian['org']['logo']        = $rs4{'logoempresa'};

      $qrpac = $this->conexion->consultar("SELECT pac FROM pvt_configura_facturacion WHERE id=1;");
      $respac = $this->conexion->siguiente($qrpac);
      $pac = $respac{'pac'};


      /* DATOS OBLIGATORIOS DEL EMISOR
      ================================================================== */
      $rfc_cliente=$rs2{'rfc'};

      $parametros['EmisorTimbre'] = array(); 
      $parametros['EmisorTimbre']['RFC'] = $rs2{'rfc'}; 
      $parametros['EmisorTimbre']['RegimenFiscal'] = $rs2{'regimenf'};
      $parametros['EmisorTimbre']['Pais'] = $rs2{'pais'}; 
      $parametros['EmisorTimbre']['RazonSocial'] = $rs2{'razon_social'}; 
      $parametros['EmisorTimbre']['Calle'] = $rs2{'calle'}; 
      $parametros['EmisorTimbre']['NumExt'] = $rs2{'num_ext'};
      $parametros['EmisorTimbre']['Colonia'] = $rs2{'colonia'};
      $parametros['EmisorTimbre']['Ciudad'] = $rs2{'ciudad'}; //Ciudad o Localidad
      $parametros['EmisorTimbre']['Municipio'] = $rs2{'municipio'};
      $parametros['EmisorTimbre']['Estado'] = $rs2{'estado'};
      $parametros['EmisorTimbre']['CP'] = $rs2{'cp'};
      $cer_cliente=$pathdc.'/'.$rs2{'cer'};
      $key_cliente=$pathdc.'/'.$rs2{'llave'};
      $pwd_cliente=$rs2{'clave'};

      if($rs2{'rfc'}==''){

        $JSON = array('success' =>0,
          'error'=>1001, 
          'mensaje'=>'No existen datos de emisor.');
        echo json_encode($JSON);
        exit();

      }

      /* Datos Basicos
      ============================================================== */
      $azurian['Basicos']['Moneda']='MXN';
      $azurian['Basicos']['metodoDePago']='99';
      $azurian['Basicos']['LugarExpedicion']='Mexico';
      $azurian['Basicos']['version']='3.2';
      $azurian['Basicos']['serie']=''; //No obligatorio
      $azurian['Basicos']['folio']=''; //No obligatorio
      $azurian['Basicos']['fecha']=$fecha;
      $azurian['Basicos']['sello']='';
      $azurian['Basicos']['formaDePago']='Pago en una sola exhibicion';
      $azurian['Basicos']['tipoDeComprobante']='ingreso';
      $azurian['Basicos']['noCertificado']='';
      $azurian['Basicos']['certificado']='';
      $azurian['Basicos']['subTotal']=0.00;
      $azurian['Basicos']['total']=0.00;
      /* Datos serie y folio
    ============================================================== */
      $azurian['Basicos']['serie']=$rs3{'serie'}; //No obligatorio
      $azurian['Basicos']['folio']=$rs3{'folio'};
      /* Datos Emisor
      ============================================================== */
      $azurian['Emisor']['rfc']=$parametros['EmisorTimbre']['RFC'];
      $azurian['Emisor']['nombre']=$parametros['EmisorTimbre']['RazonSocial'];

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

      /* Datos Receptor
      ============================================================== */
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



      $azurian['Impuestos']['totalImpuestosRetenidos']=0.00;
      $azurian['Impuestos']['totalImpuestosTrasladados']=0.00;

      $result = $this->conexion->consultar("SELECT cadenaOriginal FROM pvt_pendienteFactura WHERE id_sale in (".$cadena.");");
      $azuriant='';
      while($rs = $this->conexion->siguiente($result)){
          $azuriant=base64_decode($rs{'cadenaOriginal'});
          $azuriant = str_replace("\\", "", $azuriant);
          if($azuriant!=''){ $azuriant=json_decode($azuriant); }
          $azuriant = object_to_array($azuriant);

          $azurian['Conceptos']['conceptos'].=$azuriant['Conceptos']['conceptos'];
          $azurian['Conceptos']['conceptosOri'].=$azuriant['Conceptos']['conceptosOri'];

          $azurian['Impuestos']['totalImpuestosRetenidos']+=$azuriant['Impuestos']['totalImpuestosRetenidos'];
          $azurian['Impuestos']['totalImpuestosTrasladados']+=$azuriant['Impuestos']['totalImpuestosTrasladados'];

          $azurian['Basicos']['subTotal']+=$azuriant['Basicos']['subTotal'];
          $azurian['Basicos']['total']+=$azuriant['Basicos']['total'];
          
      }

      $ivas='';
      $tisr=0.00;
      $tiva=0.00;

      $isr='';
      $iva='';

      $xCon = $azurian['Conceptos']['conceptosOri'];
      $xCon = explode('|',$azurian['Conceptos']['conceptosOri']);
      $subTotImportes = 0;
      for($i = 5; $i < count($xCon); $i+=5) {
          $subTotImportes+= $xCon[$i];

      }


      $azurian['Conceptos']['conceptos']="<cfdi:Concepto cantidad='1' unidad='1' descripcion='Facturacion Global(".$cadena.")' valorUnitario='".str_replace(",","",number_format($subTotImportes,2))."' importe='".str_replace(",","",number_format($subTotImportes,2))."'/>";
      $azurian['Conceptos']['conceptosOri']="|1|1|Facturacion Global(".$cadena.")|".str_replace(",","",number_format($subTotImportes,2))."|".str_replace(",","",number_format($subTotImportes,2))."";


      $azurian['Impuestos']['totalImpuestosRetenidos']=str_replace(',','',number_format($azurian['Impuestos']['totalImpuestosRetenidos'],2));
      $azurian['Impuestos']['totalImpuestosTrasladados'] = str_replace(',','',number_format($azurian['Impuestos']['totalImpuestosTrasladados'],2));


      $tisr=number_format($azurian['Impuestos']['totalImpuestosRetenidos'],2,'.','');
      $tiva=number_format($azurian['Impuestos']['totalImpuestosTrasladados'],2,'.','');

     $azurian['Impuestos']['totalImpuestosRetenidos']=str_replace(',','',number_format($azurian['Impuestos']['totalImpuestosRetenidos'],2));
      $azurian['Impuestos']['totalImpuestosTrasladados'] = str_replace(',','',number_format($azurian['Impuestos']['totalImpuestosTrasladados'],2));

      if($tisr>0){
        $azurian['Impuestos']['isr']='|ISR|'.$tisr.'|'.$tisr.'|';
        $isr="<cfdi:Retenciones><cfdi:Retencion impuesto='ISR' importe='".number_format($tisr,2,'.','')."' /></cfdi:Retenciones>";
      }
      if($tiva>0){
        $azurian['Impuestos']['iva']='|IVA|16|'.$tiva.'|'.$tiva.'';
        $iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='".number_format($tiva,2,'.','')."' /></cfdi:Traslados>";
      }

      //$azurian['Observacion']['Observacion']="Esta nota de credito esta vinculada a la factura con folio ".$folio;
      $nn2["IVA"]["16.0"]["Valor"] = str_replace(",", "", $tiva);
      //echo $nn2["IVA"]["16.0"]["Valor"];
      //exit();

      $azurian['nn']['nn']=$nn2;
      $ivas.=$isr.$iva;
      $azurian['Impuestos']['ivas']=$ivas;

        $xsubT =  number_format($subTotImportes, 2, '.', '');
        $xsubA =  number_format($azurian['Basicos']['subTotal'], 2, '.', '');
       
        if($xsubT < $xsubA){
          $azurian['Basicos']['subTotal'] = $azurian['Basicos']['subTotal'] - 0.01;
          $trasladsimp = $trasladsimp + 0.01;
        }elseif($xsubT > $xsubA){
          if($trasladsimp > 0){
            $azurian['Basicos']['subTotal'] = $azurian['Basicos']['subTotal'] + 0.01;
            $trasladsimp = $trasladsimp - 0.01;
          }else{
            $azurian['Basicos']['subTotal'] = $azurian['Basicos']['subTotal'] + 0.01;
            $azurian['Basicos']['total'] = $azurian['Basicos']['total'] + 0.01;
          }
          
        } 

      if($pac==2){
        require_once('../../modulos/SAT/funcionesSAT2.php');
    }else if($pac==1){
        require_once('../../modulos/lib/nusoap.php');
        require_once('../../modulos/SAT/funcionesSAT.php');  
    }
      
      
    
    break;
	
	case 'allFacts':
      $f_tc='F';

        $iids=  0;
        $facOri=0;

        $f_rfc= "XAXX010101000";
        $f_rz=  "Factura Generica";
        $f_pais="Mexico";

        $f_calle="";
        $f_numExt='';
        $f_colonia='';
        $f_municipio='';
        $f_ciudad='';
        $f_cp='';
        $f_estado='';

        $f_serie="A";
        $f_comp= $f_tc;
        $f_pdf= "";
        $f_email='';
        $f_venta=$idVenta;
        $f_fact='';
    	
	$result = $this->conexion->consultar("select a.id, a.id_sale venta, b.monto, b.montoimpuestos from pvt_pendienteFactura a inner join venta b on b.idVenta=a.id_sale where a.fecha LIKE '%".$fecha."%' group by a.id"); 
$x=0;
$totalimp=0;
$totaltotal=0;
$totaltotalimp=0;
  while($rs = $this->conexion->siguiente($result)){
    $totaltotal=($totaltotal*1)+($rs{'monto'}*1);
  $totaltotalimp=($totaltotalimp*1)-($rs{'montoimpuestos'}*1);
      $result2 = $this->conexion->consultar("select a.preciounitario, a.impuestosproductoventa, a.total, b.nombre, b.precioventa, a.cantidad from venta_producto a
  inner join mrp_producto b on b.idProducto=a.idProducto
where a.idVenta='".$rs{'id_sale'}."';");

      
      
      while($rsw = $this->conexion->siguiente($result)){
        $conceptosDatos[$x]['Cantidad'] = $rsw['cantidad'];
        $conceptosDatos[$x]['Unidad'] = 'Pieza';
        $conceptosDatos[$x]['Descripcion'] = utf8_decode($rsw['nombre']);
        $conceptosDatos[$x]['Precio'] = $rsw['preciounitario'];
        $conceptosDatos[$x]['Importe'] = $rsw['total'];
        $totalimp=($totalimp*1)+($rsw['impuestosproductoventa']*1);
        $x++;
      }
  }  
$f_total=$totaltotal;
$f_subtotal=$totaltotalimp;
$countCon=1;

$impuestosDatos = array(
          array(
            'TipoImpuesto' => "IVA",
            'Tasa' => 16,
            'Importe' => $totaltotalimp
          )
      );

      $countImp=1;

	break;
	
	case 'oneFact':

    require_once('../../modulos/SAT/config.php');
    date_default_timezone_set("Mexico/General");
    $fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));
    $idVenta=$id;
    $result = $this->conexion->consultar("SELECT cadenaOriginal,factNum FROM pvt_pendienteFactura WHERE id_sale='$id';");
    $rs = $this->conexion->siguiente($result);
    ///recuperar si tiene trackid
    $rastreo = $rs{'factNum'};
    if($rastreo!=0){
       $consultaCFDI = 1;
    }
   


    $azurian=base64_decode($rs{'cadenaOriginal'});
    $azurian = str_replace("\\", "", $azurian);
    if($azurian!=''){ $azurian=json_decode($azurian); }
    $azurian = object_to_array($azurian);

    $qrpac = $this->conexion->consultar("SELECT pac FROM pvt_configura_facturacion WHERE id=1;");
    $respac = $this->conexion->siguiente($qrpac);
    $pac = $respac{'pac'};



    $result2 = $this->conexion->consultar("SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;");
    $rs2 = $this->conexion->siguiente($result2);

    /* DATOS OBLIGATORIOS DEL EMISOR
    ================================================================== */
    $rfc_cliente=strtoupper($rs2{'rfc'});

    $parametros['EmisorTimbre'] = array(); 
    $parametros['EmisorTimbre']['RFC'] = strtoupper($rs2{'rfc'}); 
    $parametros['EmisorTimbre']['RegimenFiscal'] = strtoupper($rs2{'regimenf'});
    $parametros['EmisorTimbre']['Pais'] = $rs2{'pais'}; 
    $parametros['EmisorTimbre']['RazonSocial'] = $rs2{'razon_social'}; 
    $parametros['EmisorTimbre']['Calle'] = $rs2{'calle'}; 
    $parametros['EmisorTimbre']['NumExt'] = $rs2{'num_ext'};
    $parametros['EmisorTimbre']['Colonia'] = $rs2{'colonia'};
    $parametros['EmisorTimbre']['Ciudad'] = $rs2{'ciudad'}; //Ciudad o Localidad
    $parametros['EmisorTimbre']['Municipio'] = $rs2{'municipio'};
    $parametros['EmisorTimbre']['Estado'] = $rs2{'estado'};
    $parametros['EmisorTimbre']['CP'] = $rs2{'cp'};
    $cer_cliente=$pathdc.'/'.$rs2{'cer'};
    $key_cliente=$pathdc.'/'.$rs2{'llave'};
    $pwd_cliente=$rs2{'clave'};

    if($rs2{'rfc'}==''){

      $JSON = array('success' =>0,
        'error'=>1001, 
        'mensaje'=>'No existen datos de emisor.');
      echo json_encode($JSON);
      exit();

    }
    

    /* Datos Receptor
    ============================================================== */
    if($rrfc>0){
      //$result = $this->conexion->consultar("SELECT * FROM comun_facturacion WHERE id='$rrfc';");
      $result = $this->conexion->consultar("SELECT c.id, c.rfc, c.razon_social, c.correo, c.pais, c.regimen_fiscal, c.domicilio, c.num_ext, c.cp, c.colonia, e.estado, c.ciudad, c.municipio from comun_facturacion c , estados e WHERE e.idestado=c.estado and id='$rrfc';");
      $rs = $this->conexion->siguiente($result);
      $idCliente=$rs{'nombre'};
      $azurian['Receptor']['rfc']=strtoupper($rs{'rfc'});
      $azurian['Receptor']['nombre']=strtoupper($rs{'razon_social'});
      $azurian['DomicilioReceptor']['calle']=$rs{'domicilio'};
      $azurian['DomicilioReceptor']['noExterior']=$rs{'num_ext'};
      $azurian['DomicilioReceptor']['colonia']=$rs{'colonia'};
      $azurian['DomicilioReceptor']['localidad']=$rs{'ciudad'};
      $azurian['DomicilioReceptor']['municipio']=$rs{'municipio'};
      $azurian['DomicilioReceptor']['estado']=$rs{'estado'};
      $azurian['DomicilioReceptor']['pais']=$rs{'pais'};
      $azurian['DomicilioReceptor']['codigoPostal']=$rs{'cp'};
      $azurian['Correo']['Correo'] = $rs{'correo'};

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

    $result3 = $this->conexion->consultar("SELECT * FROM pvt_serie_folio WHERE id=1;");
    $rs3 = $this->conexion->siguiente($result3);

    $result4 = $this->conexion->consultar("SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;");
    $rs4 = $this->conexion->siguiente($result4);

    $azurian['org']['logo']        = $rs4{'logoempresa'};

    /* Datos serie y folio
    ============================================================== */
    $azurian['Basicos']['serie']=$rs3{'serie'}; //No obligatorio
    $azurian['Basicos']['folio']=$rs3{'folio'};

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

    $azurian['Observacion']['Observacion']=$addo;

    if($pac==2){
        require_once('../../modulos/SAT/funcionesSAT2.php');
    }else if($pac==1){
        require_once('../../modulos/lib/nusoap.php');
        require_once('../../modulos/SAT/funcionesSAT.php');  
    }

  break;

  }

  if($cont==0){
  /* Arreglos Basicos
  =============================================================== */
  $arregloFactura['idOs']=$f_venta;
  $arregloFactura['idFactura']=$f_fact;

   /* Ruta
   =============================================================== */
   $ruta_guardar='facturacion/';


  $result = $this->conexion->consultar("SELECT serie,folio FROM pvt_serie_folio LIMIT 1");
      if($rs = $this->conexion->siguiente($result)){
        $f_serie=  $rs{'serie'};
        $f_folio=  $rs{'folio'}; 

      }
//echo $f_mp; exit();
    /* Basicos
    =============================================================== */
    $parametros['DatosCFD'] = array();
    $parametros['DatosCFD']['FormadePago']       = "Pago en una sola exhibicion";
    $parametros['DatosCFD']['MetododePago']      = $f_mp;
    $parametros['DatosCFD']['Moneda']            = "MXP";
    $parametros['DatosCFD']['Subtotal']          = $f_subtotal;
    $parametros['DatosCFD']['Total']             = $f_total;
    $parametros['DatosCFD']['Serie']             = $f_serie;
    $parametros['DatosCFD']['Folio']             = $f_folio;
    $parametros['DatosCFD']['TipodeComprobante'] = $f_comp; //F o C
    $parametros['DatosCFD']['MensajePDF']        = "Factutra Electronica";
    $parametros['DatosCFD']['LugarDeExpedicion']        = "Mexico";
    

    /* Receptor
    =============================================================== */
    $parametros['Receptor'] = array();
    $parametros['Receptor']['RFC']           = $f_rfc;
    $parametros['Receptor']['RazonSocial']   = utf8_decode($f_rz);
    $parametros['Receptor']['Pais']          = utf8_decode($f_pais);
    $parametros['Receptor']['Email1']        = $Email;

    $parametros['Receptor']['Calle'] = utf8_decode($f_calle);
    $parametros['Receptor']['NumExt'] = $f_numExt;
    $parametros['Receptor']['Colonia'] = utf8_decode($f_colonia);
    $parametros['Receptor']['Municipio'] = utf8_decode($f_municipio);
    $parametros['Receptor']['Ciudad'] = utf8_decode($f_ciudad);
    $parametros['Receptor']['CP'] = $f_cp;
    $parametros['Receptor']['Estado'] = utf8_decode($f_estado);
    $parametros['Receptor']['Email1'] = $Email;
	
    /* Conceptos
    =============================================================== */
    
    if($countCon==0){
      $conceptosDatos = array(
          array(
            'Cantidad' => $f_cantLT,
            'Unidad' => 'Lt',
            'Descripcion' => utf8_decode($f_descLT),
            'Precio' => $f_unitLT,
            'Importe' => $f_totLT
          ), 
          array(
            'Cantidad' => $f_cantKM,
            'Unidad' => 'Km',
            'Descripcion' => utf8_decode($f_descKM),
            'Precio' => $f_unitKM,
            'Importe' => $f_totKM
          )
      );
    }

    /* Impuestos
    =============================================================== */
    if($countImp==0){
      $impuestosDatos = array(
          array(
            'TipoImpuesto' => "IVA",
            'Tasa' => 16,
            'Importe' => round((($f_subtotal*1)*0.16),2)
          ), 
          array(
            'TipoImpuesto' => "ISR",
            'Tasa' => 10,
            'Importe' => round((($f_subtotal*1)*0.10),2)
          )
      );
    }
  }
?>