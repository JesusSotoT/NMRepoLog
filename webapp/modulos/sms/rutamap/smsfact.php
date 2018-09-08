<?php
	/* FACTURACION AZURIAN
	============================================================== */
	$idVenta=$_POST['idVenta'];
	$idFact=$_POST['idFact'];
	$idProd=$_POST['idProd'];
	$bloqueo=$_POST['bloqueo'];
	$cantidad=$_POST['cantidad'];
	$precio=$_POST['precio'];
	$carpeta=$_POST['carpeta'];

	require_once("../../../netwarelog/catalog/conexionbd.php"); 
	require_once("../../../modulos/SAT/config.php");

	$pathd='../../../modulos/SAT/netwar';
	$pathdc='../../../modulos/SAT/cliente';
	$p12_netwar=$pathd.'/netwar.desarrollo.pem';
	$positionPath="../";

	function datosFacturacion($id)
	{
		$q=mysql_query("Select 
			nombre, 
			domicilio,
			cp,
			colonia,
			num_ext,
			pais,
			correo,
			razon_social,
			rfc,
			cf.id as idFac,
			e.estado estado,
			ciudad,
			municipio,
			regimen_fiscal
			from comun_facturacion cf left join estados e on  e.idestado=cf.estado where  id=".$id);
		$r=mysql_fetch_object($q);
		return $r;

	}

	/* Basicos
	=============================================================== */
	$df=datosFacturacion($idFact);
	$idCliente=$df->nombre;
	$q=mysql_query("SELECT serie,folio FROM pvt_serie_folio LIMIT 1");
	$data=mysql_fetch_array($q);
	$supertotal=0;
	$parametros = array();	
	$parametros['DatosCFD'] = array();
	$ruta_guardar='facturacion/';
	$nn = array();

	/* Receptor
	=============================================================== */ 
	$parametros['Receptor'] = array();
	$parametros['Receptor']['RFC']           = $df->rfc;
	$parametros['Receptor']['RazonSocial']   = utf8_decode($df->razon_social);
	$parametros['Receptor']['Pais']          = utf8_decode($df->pais);
	$parametros['Receptor']['Calle']         = utf8_decode($df->domicilio);
	$parametros['Receptor']['NumExt']        = $df->num_ext;
	$parametros['Receptor']['Colonia']       = utf8_decode($df->colonia);
	$parametros['Receptor']['Municipio']     = utf8_decode($df->municipio);
	$parametros['Receptor']['Ciudad']        = utf8_decode($df->ciudad);
	$parametros['Receptor']['CP']            = $df->cp;
	$parametros['Receptor']['Estado']        = utf8_decode($df->estado);
	$parametros['Receptor']['Email1']        = $df->correo;

	$ee=mysql_query("SELECT if(deslarga='',nombre,deslarga) as descri FROM mrp_producto WHERE idProducto='$idProd';");
	$ridd=mysql_fetch_array($ee);
	$producto->nombre = $ridd['descri'];

	/* CONCEPTOS
	================================================== */
	$conceptosDatos[0]['Cantidad'] = $cantidad;
	$conceptosDatos[0]['Unidad'] = 'Oferta';
	$conceptosDatos[0]['Descripcion'] = utf8_decode('Descripcion');
	$conceptosDatos[0]['Descripcion'] = trim($conceptosDatos[0]['Descripcion']);
	$conceptosDatos[0]['Precio'] = $precio;
	$textodescuento='';
	$importe=$precio;
	$impfinal=($precio*$cantidad);
	$impfinalaa=($precio*$cantidad);
	$supertotal=($precio*$cantidad);

	$conceptosDatos[0]['Importe'] = $impfinal;

	/* IMPUESTOS
	================================================== */      
	
	$impuestosDatos=array();
	$calculototalimpuestos=0;

	$nn2['IVA'][0.00]['Valor']=0.00;
	$impuestos_venta2['IVA']=0.00;

	$impuestos_venta=$impuestos_venta2;
	foreach($impuestos_venta as $impuesto=>$valorimpuesto)
	{
		$calculototalimpuestos+=$valorimpuesto;
		$impuestosDatos[]=array('TipoImpuesto' =>strtoupper($impuesto),'Tasa' => 16,'Importe' => round($valorimpuesto,2));

	}

	$impuestosDatos[]=array('TipoImpuesto' =>'IVA','Tasa' => 0,'Importe' => 0);

	/* BASICOS
	================================================== */ 
	$parametros['DatosCFD'] = array();
	$formapago = "";
	$sql = " select nombre,referencia from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=".$idVenta;
	$qry_vp = mysql_query($sql);
	if($ri=mysql_fetch_object($qry_vp))
	{
		if(strlen($ri->referencia)>0)
		{	
			$formapago .= $ri->nombre." Ref:".$ri->referencia.",";
		}
		else
		{
			$formapago .= $ri->nombre.",";
		}
	}

	$formapago=substr($formapago,0,strlen($formapago)-1);

	if($formapago==""){
		$formapago=".";
	}



	$Email=$df->correo;

	$parametros['DatosCFD']['FormadePago']       = "Pago en una sola exhibicion";
	$parametros['DatosCFD']['MetododePago']      = utf8_decode($formapago);
	$parametros['DatosCFD']['Moneda']            = "MXP";
	$parametros['DatosCFD']['Subtotal']          = $supertotal;
	$parametros['DatosCFD']['Total']             = ($supertotal+$calculototalimpuestos);
	$parametros['DatosCFD']['Serie']             = $data['serie'];
	$parametros['DatosCFD']['Folio']             = $data['folio'];
  	$parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
  	$parametros['DatosCFD']['MensajePDF']        = "";		
  	$parametros['DatosCFD']['LugarDeExpedicion'] = "Mexico";

	date_default_timezone_set("Mexico/General");
	$fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));
	

	$q3=mysql_query("SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;");
	$r3=mysql_fetch_object($q3);

	$azurian=array();
	if($bloqueo==0){
		$q=mysql_query("SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;");
		if(mysql_num_rows($q)>0){
			$r=mysql_fetch_object($q);

			/* DATOS OBLIGATORIOS DEL EMISOR
			================================================================== */
			$rfc_cliente=$r->rfc;

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
			$cer_cliente=$pathdc.'/'.$r->cer;
			$key_cliente=$pathdc.'/'.$r->llave;
			$pwd_cliente=$r->clave;
		}else{
			$JSON = array('success' =>0,
				'error'=>1001, 
				'mensaje'=>'No existen datos de emisor.');
			echo json_encode($JSON);
			exit();
		}
	}

	/* CORREO RECEPTOR
	============================================================== */
	
	$nn=$nn2;
	$azurian['nn']['nn']        = $nn;
	$azurian['org']['logo']        = $r3->logoempresa;

	/* CORREO RECEPTOR
	============================================================== */
	$azurian['Correo']['Correo']        = 'christianpl02@gmail.com';
	$azurian['Observacion']['Observacion']        = 'SMS facturas';

	/* Datos Basicos
	============================================================== */
	$azurian['Basicos']['Moneda']=$parametros['DatosCFD']['Moneda'];
	$azurian['Basicos']['metodoDePago']=$parametros['DatosCFD']['MetododePago'];
	$azurian['Basicos']['LugarExpedicion']=$parametros['DatosCFD']['LugarDeExpedicion'];
	$azurian['Basicos']['version']='3.2';
	$azurian['Basicos']['serie']=$parametros['DatosCFD']['Serie']; //No obligatorio
	$azurian['Basicos']['folio']=$parametros['DatosCFD']['Folio']; //No obligatorio
	$azurian['Basicos']['fecha']=$fecha;
	$azurian['Basicos']['sello']='';
	$azurian['Basicos']['formaDePago']=$parametros['DatosCFD']['FormadePago'];
	$azurian['Basicos']['tipoDeComprobante']='ingreso';
	$azurian['Basicos']['noCertificado']='';
	$azurian['Basicos']['certificado']='';
	$azurian['Basicos']['subTotal']=number_format($parametros['DatosCFD']['Subtotal'],2,'.','');
	$azurian['Basicos']['total']=number_format($parametros['DatosCFD']['Total'],2,'.','');

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

	/* Datos Receptor
	============================================================== */

	$azurian['Receptor']['rfc']=strtoupper($parametros['Receptor']['RFC']);
	$azurian['Receptor']['nombre']=strtoupper($parametros['Receptor']['RazonSocial']);

	/* Datos Domicilio Receptor
	============================================================== */

	$azurian['DomicilioReceptor']['calle']=$parametros['Receptor']['Calle'];
	$azurian['DomicilioReceptor']['noExterior']=$parametros['Receptor']['NumExt'];
	$azurian['DomicilioReceptor']['colonia']=$parametros['Receptor']['Colonia'];
	$azurian['DomicilioReceptor']['localidad']=$parametros['Receptor']['Ciudad'];
	$azurian['DomicilioReceptor']['municipio']=$parametros['Receptor']['Municipio'];
	$azurian['DomicilioReceptor']['estado']=$parametros['Receptor']['Estado'];
	$azurian['DomicilioReceptor']['pais']=$parametros['Receptor']['Pais'];
	$azurian['DomicilioReceptor']['codigoPostal']=$parametros['Receptor']['CP'];
	
	$conceptosOri='';
	$conceptos='';

	foreach ($conceptosDatos as $key => $value) {
		$conceptosOri.='|'.$value['Cantidad'].'|';
		$conceptosOri.=$value['Unidad'].'|';
		$conceptosOri.=$value['Descripcion'].'|';
		$conceptosOri.=$value['Precio'].'|';
		$conceptosOri.=$value['Importe'];
		$conceptos.="<cfdi:Concepto cantidad='".$value['Cantidad']."' unidad='".$value['Unidad']."' descripcion='".$value['Descripcion']."' valorUnitario='".$value['Precio']."' importe='".$value['Importe']."'/>";
	}



	$ivas='';
	$tisr=0.00;
	$tiva=0.00;
	$tieps=0.00;

	$oriisr='';
	$oriiva='';

	$isr='';
	$iva='';
	$azurian['Conceptos']['conceptos']=$conceptos;
	$azurian['Conceptos']['conceptosOri']=$conceptosOri;

	$traslads='';
	$retenids='';
	$haytras=0;
	$hayret=0;
	$trasladsimp=0.00;
	$retenciones=0.00;
	$trasxml='';
	$retexml='';

	foreach ($nn as $clave => $imm) {
		if($clave=='IEPS' || $clave=='IVA'){

			$haytras=1;
			foreach ($nn[$clave] as $clavetasa => $val) {
				if($clave=='IEPS'){
					$tieps+=number_format($val['Valor'],2,'.','');
				}
				if($clave=='IVA'){
					$tiva+=number_format($val['Valor'],2,'.','');
				}
				$traslads.='|'.$clave.'|';
				$traslads.=''.$clavetasa.'|';
				$traslads.=number_format($val['Valor'],2,'.','');
				$trasladsimp+=number_format($val['Valor'],2,'.','');
				$trasxml.="<cfdi:Traslado impuesto='".$clave."' tasa='".$clavetasa."' importe='".number_format($val['Valor'],2,'.','')."' />";
			}
			
		}elseif($clave=='ISR'){
			$hayret=1;
			foreach ($nn[$clave] as $clavetasa => $val) {
				$tisr+=number_format($val['Valor'],2,'.','');
				$retenids.='|'.$clave.'|';
				$retenids.=''.number_format($val['Valor'],2,'.','').'|';
				$retenids.=number_format($val['Valor'],2,'.','');
				$retenciones+=number_format($val['Valor'],2,'.','');
				$retexml.="<cfdi:Retencion impuesto='".$clave."' importe='".number_format($val['Valor'],2,'.','')."' />";	
			}
		}
	}
	$azurian['Impuestos']['totalImpuestosIeps']=$tieps;
	if($haytras==1){
		$iva.='<cfdi:Traslados>'.$trasxml.'</cfdi:Traslados>';
	}else{
		$traslads.='|IVA|';
		$traslads.='0.00|';
		$traslads.='0.00';
		$trasladsimp='0.00';
		$iva.="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='0.00' importe='0.00' /></cfdi:Traslados>";
	}
	if($hayret==1){
		$isr.='<cfdi:Retenciones>'.$retexml.'</cfdi:Retenciones>';
	}
	

	$azurian['Impuestos']['isr']=$retenids;
	$azurian['Impuestos']['iva']=$traslads.'|'.number_format($trasladsimp,2,'.','');

	$azurian['Impuestos']['totalImpuestosRetenidos']=number_format($retenciones,2,'.','');
	$azurian['Impuestos']['totalImpuestosTrasladados']=number_format($trasladsimp,2,'.','');

	$ivas.=$isr.$iva;

	$azurian['Impuestos']['ivas']=$ivas;
	$azurian['Venta']['venta']=$idVenta;

	//echo json_encode($azurian);
	//exit();

	require_once('../../../modulos/lib/nusoap.php');
	require_once('../../../modulos/SAT/funcionesSAT.php');
?>