<?php
	include_once("../../netwarelog/webconfig.php");
	if(!isset($_COOKIE['xtructur'])){
		$JSON = array('success' => 0,
            'error' => 323,
            'mensaje' => 'La sesion caduco.');
        echo json_encode($JSON);
        exit();
	}else{
	    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
	    $id_obra = $cookie_xtructur['id_obra'];
	}

	date_default_timezone_set('America/Mexico_City');
	$fecha_timbrado=date('Y-m-d');


	$db = mysql_connect($servidor, $usuariobd, $clavebd)
	or die("Connection Error: " . mysql_error());
	mysql_select_db($bd) or die("Error conecting to db.");
	mysql_query("set names 'utf8'");


	$idEstCli=$_POST['idEstCli'];
	$fp=$_POST['fp'];


	//Seleccionar el id del cliente
	$SQL = "SELECT id_cliente FROM constru_estimaciones_bit_cliente WHERE id='$idEstCli' LIMIT 1";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$id_cliente = $row['id_cliente'];
	}else{
		$JSON = array('success' => 0,
            'error' => 2001,
            'mensaje' => 'No existen el cliente.');
        echo json_encode($JSON);
        exit();
	}
	

	//Obtener folio y serie
	$SQL = "SELECT serie,folio FROM pvt_serie_folio LIMIT 1";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$xtr_serie = $row['serie'];
		$xtr_folio = $row['folio'];
	}else{
		$xtr_serie = '';
		$xtr_folio = '';
	}
	//Fin Folio y serie

	//Forma de pago y nombre
	$SQL = "SELECT claveSat,nombre FROM forma_pago_caja where idFormapago='".$fp."';";
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$fp = $row['claveSat'];
		$fpnombre = $row['nombre'];
	}else{
		$fp = '01';
		$fpnombre = 'Efectivo';
	}
	$moncode='MXN';
	//Fin Forma de pago y nombre y codigo moneda
	
	$SQL = "SELECT * from constru_estimaciones_bit_cliente WHERE id_obra='$id_obra' AND id='$idEstCli' limit 1;";	
	$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);
		$estc_imp_estimacion = $row['imp_estimacion'];
		$estc_subtotal1 = $row['subtotal1'];
		$estc_subtotal2 = $row['subtotal2'];
		$estc_iva = $row['iva'];
		$estc_total = $row['total'];
        $amor = $row['amortizacion'];

        $subto=($estc_subtotal1-$estc_subtotal2);
        $descu=$amor+$estc_subtotal2;
	}else{
		$JSON = array('success' => 0,
            'error' => 2001,
            'mensaje' => 'No existen datos estimacion.');
        echo json_encode($JSON);
        exit();
	}
	


	 $SQL = "SELECT cliente as nombre, localizacion,cp,colonia,num_ext,id_pais,email,cliente,rfc, cf.id as idFac,
                e.estado estado, 'Regimen Fiscal' as regimen_fiscal, pa.pais, mu.municipio
                from constru_generales cf 
                left join estados e on  e.idestado=cf.id_estado
                left join paises pa on  pa.idpais=cf.id_pais
                left join municipios mu on  mu.idmunicipio=cf.id_municipio
                where  id='$id_cliente' limit 1;";

    $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
	if(mysql_num_rows($result)>0){
		$row = mysql_fetch_assoc($result);

		$parametros['Receptor']['RFC'] = $row['rfc'];
        $parametros['Receptor']['RazonSocial'] = utf8_decode($row['nombre']);
        $parametros['Receptor']['Pais'] = utf8_decode($row['pais']);
        $parametros['Receptor']['Calle'] = utf8_decode($row['localizacion']);
        $parametros['Receptor']['NumExt'] = utf8_decode($row['num_ext']);
        $parametros['Receptor']['Colonia'] = utf8_decode($row['colonia']);
        $parametros['Receptor']['Municipio'] = utf8_decode($row['municipio']);
        $parametros['Receptor']['Ciudad'] = utf8_decode($row['municipio']);
        $parametros['Receptor']['CP'] = $row['cp'];
        $parametros['Receptor']['Estado'] = utf8_decode($row['estado']);
        $parametros['Receptor']['Email1'] = $row['email'];

	}else{
		$parametros['Receptor']['RFC'] = "XAXX010101000";
        $parametros['Receptor']['RazonSocial'] = 'Factura Generica';
	}

		$Email = $row['email'];

        $parametros['DatosCFD']['FormadePago'] = "Pago en una sola exhibicion";
        $parametros['DatosCFD']['MetododePago'] = utf8_decode($fpnombre).' ('.$fp.')';
        $parametros['DatosCFD']['Moneda'] = $moncode;

        //Faltan sub y tot;
        $parametros['DatosCFD']['Subtotal'] = str_replace(",", "", number_format($estc_imp_estimacion,2));
        $parametros['DatosCFD']['Total'] = str_replace(",", "", number_format($estc_total,2));
        $parametros['DatosCFD']['Descuento'] = str_replace(",", "", number_format($descu,2));

        $parametros['DatosCFD']['Serie'] = $xtr_serie;
        $parametros['DatosCFD']['Folio'] = $xtr_folio;

        $parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C

        $devo='';
        if($devo=='s'){
            $parametros['DatosCFD']['TipodeComprobante'] = "C"; //F o C
        }
        $parametros['DatosCFD']['MensajePDF'] = "";
        $parametros['DatosCFD']['LugarDeExpedicion'] = "Mexico";




        $SQL = "SELECT c.codigo, c.descripcion, c.unidtext, d.vol_tope, c.precio_costo, a.vol_anterior, a.vol_estimacion
    			from constru_estimaciones_bit_cliente b
						    inner join constru_estimaciones_cliente a on a.id_bit_cliente=b.id
						    left join constru_recurso c on c.id=a.id_insumo
						    left join constru_vol_tope d on d.id_clave=c.id AND (d.id_area=b.id_area or d.id_area=b.id_area)
						    WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_cliente='$idEstCli';";	

		$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());


		$x = 0;
		$consumoTotal=0;
        $textodescuento = "";

		if(mysql_num_rows($result)>0){
			while($row = mysql_fetch_array($result)) {
				$conceptosDatos[$x]["Cantidad"] = $row['vol_estimacion'];
                $conceptosDatos[$x]["Unidad"] = $row['unidtext'];
                $conceptosDatos[$x]["Precio"] = $row['precio_costo'];
                $conceptosDatos[$x]["Descripcion"] = trim($row['descripcion']);

                $textodescuento = '';
                $conceptosDatos[$x]['Importe'] = ($row['vol_estimacion'] * $row['precio_costo']);
                $consumoTotal +=  $conceptosDatos[$x]['Importe']*1;
                $x++;

			}
		}else{
			$JSON = array('success' => 0,
            'error' => 2002,
            'mensaje' => 'No existen conceptos.');
	        echo json_encode($JSON);
	        exit();

		}

		$aaa=array();
		


		//$nn2 = $_SESSION['caja']['cargos']['impuestosFactura'];
        //$nnf = $_SESSION['caja']['cargos']['impuestosPdf'];



        $nn2["IVA"]["16"]["Valor"] = $estc_iva;
        $nnf["IVA"]["16"]["Valor"] = $estc_iva;


        /* FACTURACION AZURIAN
        ============================================================== */
        require_once('../../modulos/SAT/config.php');

        date_default_timezone_set("Mexico/General");
        $fecha = date('Y-m-d') . 'T' . date('H:i:s', strtotime("-7 minute"));


        $SQL = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1 limit 1;";
        $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
        if(mysql_num_rows($result)>0){
			$row = mysql_fetch_assoc($result);
			$r3 = $row["logoempresa"];
		}else{
			$r3 = '';
		}



        $SQL = "SELECT pac FROM pvt_configura_facturacion WHERE id=1 limit 1;";
        $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
        if(mysql_num_rows($result)>0){
			$row = mysql_fetch_assoc($result);
			$pac = $row["pac"];
		}else{
			$pac = '';
		}


        $azurian = array();

        $SQL = "SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;";

        $result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error());
        if(mysql_num_rows($result)>0){
			$row = mysql_fetch_assoc($result);
			/* DATOS OBLIGATORIOS DEL EMISOR
                ================================================================== */
            $rfc_cliente = $row['rfc'];
            $parametros['EmisorTimbre'] = array();
            $parametros['EmisorTimbre']['LugarExp'] = $row['lugar_exp'];
            $parametros['EmisorTimbre']['RFC'] = $row['rfc'];
            $parametros['EmisorTimbre']['RegimenFiscal'] = $row['regimenf'];
            $parametros['EmisorTimbre']['Pais'] = $row['pais'];
            $parametros['EmisorTimbre']['RazonSocial'] = $row['razon_social'];
            $parametros['EmisorTimbre']['Calle'] = $row['calle'];
            $parametros['EmisorTimbre']['NumExt'] = $row['num_ext'];
            $parametros['EmisorTimbre']['Colonia'] = $row['colonia'];
            $parametros['EmisorTimbre']['Ciudad'] = 'sssss'; //Ciudad o Localidad
            $parametros['EmisorTimbre']['Municipio'] = $row['municipio'];
            $parametros['EmisorTimbre']['Estado'] = $row['estado'];
            $parametros['EmisorTimbre']['CP'] = $row['cp'];
            $cer_cliente = $pathdc . '/' . $row['cer'];
            $key_cliente = $pathdc . '/' . $row['llave'];
            $pwd_cliente = $row['clave'];
		}else{
			$JSON = array('success' => 0,
            'error' => 1001,
            'mensaje' => 'No existen datos de emisor.');
	        echo json_encode($JSON);
	        exit();
		}

		$azurian['Observacion']['Observacion'] = '';

		/* IMPUESTOS
        ============================================================== */
        if ($nn2 == '') {
            $nn2["IVA"]["0.0"]["Valor"] = 0.00;
        }
        if ($nnf == '') {
            $nnf["IVA"]["0.0"]["Valor"] = 0.00;
        }
        $nn = $nn2;
        $azurian['nn']['nn'] = $nn;
        $azurian['nnf']['nnf'] = $nnf;
        $azurian['org']['logo'] = $r3;
           
        /* CORREO RECEPTOR
        ============================================================== */
        $azurian['Correo']['Correo'] = $Email;

        /* Datos Basicos
        ============================================================== */
        $azurian['Basicos']['Moneda'] = $parametros['DatosCFD']['Moneda'];
        $azurian['Basicos']['metodoDePago'] = $parametros['DatosCFD']['MetododePago'];
        $azurian['Basicos']['LugarExpedicion'] = $parametros['EmisorTimbre']['LugarExp'];
        $azurian['Basicos']['version'] = '3.2';
        $azurian['Basicos']['serie'] = $parametros['DatosCFD']['Serie']; //No obligatorio
        $azurian['Basicos']['folio'] = $parametros['DatosCFD']['Folio']; //No obligatorio
        $azurian['Basicos']['fecha'] = $fecha;
        $azurian['Basicos']['sello'] = '';
        $azurian['Basicos']['formaDePago'] = $parametros['DatosCFD']['FormadePago'];
        $azurian['Basicos']['tipoDeComprobante'] = 'ingreso';
        //if($devo=='s'){
          //  $azurian['Basicos']['tipoDeComprobante'] = 'egreso';
        //}
        $azurian['tipoFactura'] = 'factura';
        //if($devo=='s'){
          //  $azurian['tipoFactura'] = 'Nota de credito';
        //}
        $azurian['Basicos']['noCertificado'] = '';
        $azurian['Basicos']['certificado'] = '';
        $str_subtotal = number_format($parametros['DatosCFD']['Subtotal'], 2);
        $descuentot = number_format($parametros['DatosCFD']['Descuento'], 2);
        $azurian['Basicos']['subTotal'] = str_replace(",", "", $str_subtotal);
        $str_total = number_format($parametros['DatosCFD']['Total'], 2);
        $str_total = str_replace(',', '',$str_total);
        //$str_total = $str_total - 0.01;
        //$str_total = number_format($str_total,0).'.00';  //Comente para que Salgan Decimales Normalmente
        $str_total = number_format($str_total,2);
        $azurian['Basicos']['total'] = str_replace(",", "", $str_total); 
        $azurian['Basicos']['descuento'] = str_replace(",", "", $descuentot); 

        /* Datos Emisor
        ============================================================== */

        $azurian['Emisor']['rfc'] = strtoupper($parametros['EmisorTimbre']['RFC']);
        $azurian['Emisor']['nombre'] = strtoupper($parametros['EmisorTimbre']['RazonSocial']);

        /* Datos Fiscales Emisor
        ============================================================== */

        $azurian['FiscalesEmisor']['calle'] = $parametros['EmisorTimbre']['Calle'];
        $azurian['FiscalesEmisor']['noExterior'] = $parametros['EmisorTimbre']['NumExt'];
        $azurian['FiscalesEmisor']['colonia'] = $parametros['EmisorTimbre']['Colonia'];
        $azurian['FiscalesEmisor']['localidad'] = 'aaaa';
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
        $azurian['DomicilioReceptor']['localidad'] = 'ddddd';
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
        $rtp = '';



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

        //add rtp
        $complementortp='';
        $hayrtp = 0;
        $tisr2 = 0.00;
        $retexml2 = '';
        $retenids2='';
        //aqui

        foreach ($nn as $clave => $imm) {
            if ($clave == 'IVA') {

                $haytras = 1;
                foreach ($nn[$clave] as $clavetasa => $val) {
                	$val=$val['Valor'];
                    if($clavetasa=='0.0'){$val=0;}
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
          if($hayret == 1){
            $cadRet = '|'.str_replace(',', '', number_format($tisr,2));
          }else{
            $cadRet = '';
          } 
          //add rtp
        if ($hayrtp == 1) {
            $rtp.='<implocal:ImpuestosLocales version="1.0" TotaldeTraslados="0.00" TotaldeRetenciones="'.$tisr2.'">'.$retexml2.'</implocal:ImpuestosLocales>';

            $rtploc=$retenids2;
            $azurian['Impuestos']['rtp_cad'] = '|1.0|'.$tisr2.'|0.00'.$rtploc;
            //$isr.='<cfdi:Retenciones>' . $retexml . '</cfdi:Retenciones>';
        }else{
            $azurian['Impuestos']['rtp_cad'] = '';
        }
        


        $azurian['Impuestos']['isr'] = $retenids.$cadRet;
        $azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');

        $azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
        $azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');

          
        $azurian['Impuestos']['rtp'] =$rtp;

        $ivas.=$isr . $iva;

        $azurian['Impuestos']['ivas'] = $ivas;
             
        //print_r($azurian); 
        //echo json_encode($azurian);
        //exit();
        


        

        $appministra_nuevo=1;
        $no_permitidas=array("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹","/");
        $permitidas=array("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E","");
        $rzst = str_replace($no_permitidas, $permitidas ,$parametros['Receptor']['RazonSocial']);
        

        $proviene_xtructur=1;

        $idVenta=$idEstCli;

        if($pac==2){
            require_once('../../modulos/SAT/funcionesSAT2.php');
        }else if($pac==1){

            require_once('../../modulos/lib/nusoap.php');
            require_once('../../modulos/SAT/funcionesSAT.php');  
        }


?>