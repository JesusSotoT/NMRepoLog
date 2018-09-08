<?php

	/*$funcion=$_POST['funcion_xml'];
    switch ($funcion) {
        
        case 'desplegar_articulos':
                  
        break;

        case 'crear_articulo':

        break;

        case 'crear_comentario':

        break;
    }
*/
	/* CATALOGO BASICOS
	======================================================== */
	/*$xml['Catalogo']['version'];
	$xml['Catalogo']['rfc'];
	$xml['Catalogo']['TotalCtas'];
	$xml['Catalogo']['Mes'];
	$xml['Catalogo']['Ano'];

		// CATALOGO CUETAS ARREGLO
		$xml['Catalogo']['Cuentas'];
		$xml['Catalogo']['Cuentas'][0]['CodAgrup'];
		$xml['Catalogo']['Cuentas'][0]['NumCta'];
		$xml['Catalogo']['Cuentas'][0]['Desc'];
		$xml['Catalogo']['Cuentas'][0]['Nivel'];
		$xml['Catalogo']['Cuentas'][0]['Natur'];

	$XMLC='';
	$XMLC.="<Catalogo version='".$xml['Catalogo']['version']."' RFC='".$xml['Catalogo']['rfc']."' TotalCtas='".$xml['Catalogo']['TotalCtas']."' Mes='".$xml['Catalogo']['Mes']."' Ano='".$xml['Catalogo']['Ano']."' >";
	foreach ($xml['Catalogo']['Cuentas'] as $key => $row) {
		$XMLC.="<Ctas CodAgrup='".$row['CodAgrup']."' NumCta='".$row['NumCta']."' Desc='".$row['Desc']."' Nivel='".$row['Nivel']."' Natur='".$row['Natur']."' />";
	}
	$XMLC.="</Catalogo>";

	$archivo = fopen('../../modulos/facturas/cualquiercosa.xml','w');
	fwrite($archivo,$XMLC);
	fclose($archivo);*/

	/* BALANZA BASICOS
	======================================================== */
	/*$xml['Balanza']Balanza['version'];
	$xml['Balanza']['rfc'];
	$xml['Balanza']['TotalCtas'];
	$xml['Balanza']['Mes'];
	$xml['Balanza']['Ano'];

		// CATALOGO CUETAS ARREGLO
		$xml['Balanza']['Cuentas'];
		$xml['Balanza']['Cuentas'][0]['NumCta'];
		$xml['Balanza']['Cuentas'][0]['SaldoIni'];
		$xml['Balanza']['Cuentas'][0]['Debe'];
		$xml['Balanza']['Cuentas'][0]['Haber'];
		$xml['Balanza']['Cuentas'][0]['SaldoFin'];

	$XMLB='';
	$XMLB.="<Balanza version='".$xml['Balanza']['version']."' RFC='".$xml['Balanza']['rfc']."' TotalCtas='".$xml['Balanza']['TotalCtas']."' Mes='".$xml['Balanza']['Mes']."' Ano='".$xml['Balanza']['Ano']."' >";
	foreach ($xml['Balanza']['Cuentas'] as $key => $row) {
		$XMLB.="<Ctas NumCta='".$row['NumCta']."' SaldoIni='".$row['SaldoIni']."' Debe='".$row['Debe']."' Haber='".$row['Haber']."' SaldoFin='".$row['SaldoFin']."' />";
	}
	$XMLB.="</Balanza>";

	$archivo = fopen('../../modulos/facturas/cualquiercosa.xml','w');
	fwrite($archivo,$XMLB);
	fclose($archivo);
*/
	/* POLIZAS BASICOS
	======================================================== */
	$xml['Poliza']['version']='1.0';
	$xml['Poliza']['rfc']='rfcpoliza';
	$xml['Poliza']['Mes']='mespoliza';
	$xml['Poliza']['Ano']='anopoliza';
	$xml['Poliza']['Poliza']=array();
		// POLIZAS NODO POLIZA
		$xml['Poliza']['Poliza'][0]['Tipo']='nodopolizatipo';
		$xml['Poliza']['Poliza'][0]['Num']='nodopolizanum';
		$xml['Poliza']['Poliza'][0]['Fecha']='nodopolizanum';
		$xml['Poliza']['Poliza'][0]['Concepto']='nodopolizanum';
		$xml['Poliza']['Poliza'][0]['Transaccion']=array();

			$xml['Poliza']['Poliza'][0]['Transaccion'][0]['numCta']='transcuenta';
			$xml['Poliza']['Poliza'][0]['Transaccion'][0]['debe']='tarnsdebe';
			$xml['Poliza']['Poliza'][0]['Transaccion'][0]['Comprobante']=array();	

				$xml['Poliza']['Poliza'][0]['Transaccion'][0]['Comprobante'][0]['uuid']='uiidcomp';
				$xml['Poliza']['Poliza'][0]['Transaccion'][0]['Comprobante'][0]['rfc']='rfccomp';

				$xml['Poliza']['Poliza'][0]['Transaccion'][0]['Comprobante'][1]['uuid']='uiidcomp';
				$xml['Poliza']['Poliza'][0]['Transaccion'][0]['Comprobante'][1]['rfc']='rfccomp';

				$xml['Poliza']['Poliza'][0]['Transaccion'][0]['Comprobante'][2]['uuid']='uiidcomp';
				$xml['Poliza']['Poliza'][0]['Transaccion'][0]['Comprobante'][2]['rfc']='rfccomp';

			$xml['Poliza']['Poliza'][0]['Transaccion'][1]['numCta']='transcuenta';
			$xml['Poliza']['Poliza'][0]['Transaccion'][1]['debe']='tarnsdebe';
			$xml['Poliza']['Poliza'][0]['Transaccion'][1]['Comprobante']=0;	

			$xml['Poliza']['Poliza'][0]['Transaccion'][2]['numCta']='transcuenta';
			$xml['Poliza']['Poliza'][0]['Transaccion'][2]['debe']='tarnsdebe';
			$xml['Poliza']['Poliza'][0]['Transaccion'][2]['Comprobante']=array();

				$xml['Poliza']['Poliza'][0]['Transaccion'][2]['Comprobante'][0]['uuid']='uiidcomp';
				$xml['Poliza']['Poliza'][0]['Transaccion'][2]['Comprobante'][0]['rfc']='rfccomp';


		$xml['Poliza']['Poliza'][1]['Tipo']='nodopolizatipo';
		$xml['Poliza']['Poliza'][1]['Num']='nodopolizanum';
		$xml['Poliza']['Poliza'][1]['Fecha']='nodopolizanum';
		$xml['Poliza']['Poliza'][1]['Concepto']='nodopolizanum';
		$xml['Poliza']['Poliza'][1]['Transaccion']=0;

		$xml['Poliza']['Poliza'][2]['Tipo']='nodopolizatipo';
		$xml['Poliza']['Poliza'][2]['Num']='nodopolizanum';
		$xml['Poliza']['Poliza'][2]['Fecha']='nodopolizanum';
		$xml['Poliza']['Poliza'][2]['Concepto']='nodopolizanum';
		$xml['Poliza']['Poliza'][2]['Transaccion']=array();


			// POLIZAS NODO TRANSACCION
	/*		$xml['Poliza']['Poliza']['Transaccion'];
			$xml['Poliza']['Poliza']['Transaccion'][0]['Tipo'];
			$xml['Poliza']['Poliza']['Transaccion'][0]['Num'];
			$xml['Poliza']['Poliza']['Transaccion'][0]['Fecha'];
			$xml['Poliza']['Poliza']['Transaccion'][0]['Concepto'];

	$XMLP='';
	$XMLP.="<Polizas version='".$xml['Poliza']['version']."' RFC='".$xml['Poliza']['rfc']."' TotalCtas='".$xml['Poliza']['TotalCtas']."' Mes='".$xml['Poliza']['Mes']."' Ano='".$xml['Poliza']['Ano']."' >";
*/
	/*
	$idComprobante=time();
	$azurian['Receptor']['nombre']= preg_replace('/&/', '&amp;', $azurian['Receptor']['nombre']);
	$azurian['Conceptos']['conceptos']= preg_replace('/&/', '&amp;', $azurian['Conceptos']['conceptos']);
	$azurian['Emisor']['rfc']= preg_replace('/&/', '&amp;', $azurian['Emisor']['rfc']);
	$azurian['Receptor']['rfc']= preg_replace('/&/', '&amp;', $azurian['Receptor']['rfc']);
	*/


















?>
<pre>
	<?php print_r($xml); ?>
</pre>