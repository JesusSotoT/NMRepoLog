<?php
require('common.php');//Carga la funciones comunes top y footer
require('models/factura.php');
require ('../wsinvoice/lib/QRcode.php');
//require ('../wsinvoice/config_api.php');
//require ('../wsinvoice/lib/fpdf.php');
//require ('../wsinvoice/class.invoice.pdf.php');

class Factura extends Common
{
	public $FacturaModel;

	function __construct(){
		$this->FacturaModel = new FacturaModel();
		$this->FacturaModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->FacturaModel->close();
	}

  function visor_factura(){
    $factura = $this->FacturaModel->obtener_datos_factura($_GET['uuid']);
    $factura = $factura->fetch_assoc();
    #Extraemos los datos del timbre fiscal
    $xml = $this->obtener_complemento_xml($factura['xml']);
    #Cambiamos los caracteres raros
    $unwanted_array = array(
      'u00c9'=>'É', 'u00c1'=>'Á', 'u00ed'=>'Í', 'u00f3'=>'Ó', 'u00e9'=>'É', 'u00d3'=>'Ó', 
      'u00e1'=>'Á');
    $factura['json'] = strtr($factura['json'], $unwanted_array );
    #Quitamos los saltos de linea
    $factura['json'] = str_replace("\\", "", $factura['json']);
    $json = json_decode($factura['json']);
    $json = $this->object_to_array($json);

    #Total
    if(isset($json['Comprobante']['@total'])) {
      $total = $json['Comprobante']['@total'];
    } elseif($json['Comprobante']['@Total']) {
      $total = $json['Comprobante']['@Total'];
    } else {
      $total = 0.00;
    }

    #Subtotal
    if(isset($json['Comprobante']['@subTotal'])){
      $subtotal = $json['Comprobante']['@subTotal'];
    } elseif(isset($json['Comprobante']['@SubTotal'])){
      $subtotal = $json['Comprobante']['@SubTotal'];
    } else {
      $subtotal = 0.00;
    }

    #Descuento
    if(isset($json['Comprobante']['@descuento'])){
      $descuento = $json['Comprobante']['@descuento'];
    } elseif(isset($json['Comprobante']['@Descuento'])){
      $descuento = $json['Comprobante']['@Descuento'];
    } else {
      $descuento = 0.00;
    }

    #Impuestos Trasladados
    if (isset($json['Comprobante']['cfdi:Impuestos']['@totalImpuestosTrasladados'])) {
      $trasladados = $json['Comprobante']['cfdi:Impuestos']['@totalImpuestosTrasladados'];
    } elseif ($json['Comprobante']['cfdi:Impuestos']['@TotalImpuestosTrasladados']) {
      $trasladados = $json['Comprobante']['cfdi:Impuestos']['@TotalImpuestosTrasladados'];
    }

    #Impuestos Retenidos
    if (isset($json['Comprobante']['cfdi:Impuestos']['@totalImpuestosTrasladados'])) {
      $trasladados = $json['Comprobante']['cfdi:Impuestos']['@totalImpuestosTrasladados'];
    } elseif ($json['Comprobante']['cfdi:Impuestos']['@TotalImpuestosTrasladados']) {
      $trasladados = $json['Comprobante']['cfdi:Impuestos']['@TotalImpuestosTrasladados'];
    }

    #Timbre Fiscal
    $timbreFiscal = $xml['Complemento']['TimbreFiscalDigital'];

    #Cadena original del complemento de certificacion digital del sat
    if ($factura['version'] == '3.2') {
      $coccd = "||". $timbreFiscal['version'] . "|" . $factura['uuid'] . "|" . $timbreFiscal["FechaTimbrado"] . "|" . $timbreFiscal['selloCFD'] ."|". $timbreFiscal["noCertificadoSAT"] ."||";  
    } 
    //Para los CFDI 3.3
    else {
      $coccd = "||" . $factura['Version'] . "|" . $factura['uuid'] . "|" . $timbreFiscal["FechaTimbrado"]. "|" .
      $json['Comprobante']['SelloCFD']."|". $timbreFiscal["NoCertificadoSAT"] ."||"; 
    }

    #Sello Emisor
    if (isset($timbreFiscal['selloSAT'])) {
      $selloSAT = $timbreFiscal['selloSAT'];
    } elseif(isset($timbreFiscal['SelloSAT'])) {
      $selloSAT = $timbreFiscal['SelloSAT'];
    }

    #Sello Emisor
    if (isset($timbreFiscal['selloCFD'])) {
      $selloEmisor = $timbreFiscal['selloCFD'];
    } elseif(isset($timbreFiscal['SelloCFD'])) {
      $selloEmisor = $timbreFiscal['SelloCFD'];
    }

    #Certificado Emisor
    if (isset($json['Comprobante']['@noCertificado'])) {
      $certificado_emisor = $json['Comprobante']['@noCertificado'];
    } elseif(isset($json['Comprobante']['@NoCertificado'])){
      $certificado_emisor = $json['Comprobante']['@NoCertificado'];
    } else {
      $certificado_emisor = "";
    }

    #Certificado SAT
    if (isset($timbreFiscal['noCertificadoSAT'])) {
      $certificado_SAT = $timbreFiscal['noCertificadoSAT'];
    } elseif(isset($timbreFiscal['NoCertificadoSAT'])){
      $certificado_SAT = $timbreFiscal['NoCertificadoSAT'];
    } else {
      $certificado_SAT = "";
    }

    #Tipo Comprobante
    if (isset($json['Comprobante']['@tipoDeComprobante'])) {
      $tipo_comprobante = $json['Comprobante']['@tipoDeComprobante'];
    } elseif(isset($json['Comprobante']['@TipoDeComprobante'])){
      $tipo_comprobante = $json['Comprobante']['@TipoDeComprobante'];
    } else {
      $tipo_comprobante = "";
    }

    #Metodo pago
    if (isset($json['Comprobante']['@metodoDePago'])){
      $metodo_pago = $json['Comprobante']['@metodoDePago'];
    } elseif(isset($json['Comprobante']['@MetodoDePago'])){
      $metodo_pago = $json['Comprobante']['@MetodoDePago'];
    } else {
      $metodo_pago = "";
    }

    #Forma pago
    if (isset($json['Comprobante']['@formaDePago'])) {
      $forma_pago = $json['Comprobante']['@formaDePago'];
    } elseif(isset($json['Comprobante']['@FormaDePago'])){
      $forma_pago = $json['Comprobante']['@FormaDePago'];
    } else {
      $forma_pago = "";
    }

    #Moneada
    if (isset($json['Comprobante']['@moneda'])) {
      $moneda = $json['Comprobante']['@moneda'];
    } elseif(isset($json['Comprobante']['@Moneda'])){
      $moneda = $json['Comprobante']['@Moneda'];
    } else {
      $moneda = "";
    }

    #Definimos cadena para QR
    //$qrString = "?re=" . $json['Comprobante']['cfdi:Emisor']['@rfc'] . "&rr=" . $json['Comprobante']['cfdi:Receptor']['@rfc'] . "&tt=" . $this->cantidad10_6($factura['importe']) . "&id=" . $factura['uuid'];

    //var_dump($json['Comprobante']);

    require('views/captpolizas/visorfacturas.php');
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

  public function stringFormatMoney($cantidad){
    $negativo = ($cantidad < 0 ?  "-" : "" );
    $cantidad = number_format($cantidad, 2, ".", ",");
    $cantidad = $negativo . " $ " . $cantidad;
    return $cantidad;
  }

  public function obtener_complemento_xml($xml){
    #Cargamos archivo
    $ruta = "xmls/facturas/temporales/".$xml;
    if (file_exists($ruta)) {
      $objeto = array();
      $sxe = new SimpleXMLElement($ruta, NULL, TRUE);
      $objeto["Complemento"] = array("TimbreFiscalDigital" => array());

      $hijos = $sxe->children('cfdi', true);

      $complemento = $hijos->Complemento->children('tfd', true);

      foreach($complemento->TimbreFiscalDigital->attributes() as $campo => $valor){
        $objeto["Complemento"]["TimbreFiscalDigital"][$campo] = (string)$valor[0];
      }
    }
    return $objeto;
  }

  public function cantidadLetra($num, $fem = false, $dec = true) {
    //http://www.thezilus.com/blog/wp-content/uploads/2010/06/num2letras.txt
    $matuni[2]  = "dos";
    $matuni[3]  = "tres";
    $matuni[4]  = "cuatro";
    $matuni[5]  = "cinco";
    $matuni[6]  = "seis";
    $matuni[7]  = "siete";
    $matuni[8]  = "ocho";
    $matuni[9]  = "nueve";
    $matuni[10] = "diez";
    $matuni[11] = "once";
    $matuni[12] = "doce";
    $matuni[13] = "trece";
    $matuni[14] = "catorce";
    $matuni[15] = "quince";
    $matuni[16] = "dieciseis";
    $matuni[17] = "diecisiete";
    $matuni[18] = "dieciocho";
    $matuni[19] = "diecinueve";
    $matuni[20] = "veinte";
    $matunisub[2] = "dos";
    $matunisub[3] = "tres";
    $matunisub[4] = "cuatro";
    $matunisub[5] = "quin";
    $matunisub[6] = "seis";
    $matunisub[7] = "sete";
    $matunisub[8] = "ocho";
    $matunisub[9] = "nove";

    $matdec[2] = "veint";
    $matdec[3] = "treinta";
    $matdec[4] = "cuarenta";
    $matdec[5] = "cincuenta";
    $matdec[6] = "sesenta";
    $matdec[7] = "setenta";
    $matdec[8] = "ochenta";
    $matdec[9] = "noventa";
    $matsub[3]  = 'mill';
    $matsub[5]  = 'bill';
    $matsub[7]  = 'mill';
    $matsub[9]  = 'trill';
    $matsub[11] = 'mill';
    $matsub[13] = 'bill';
    $matsub[15] = 'mill';
    $matmil[4]  = 'millones';
    $matmil[6]  = 'billones';
    $matmil[7]  = 'de billones';
    $matmil[8]  = 'millones de billones';
    $matmil[10] = 'trillones';
    $matmil[11] = 'de trillones';
    $matmil[12] = 'millones de trillones';
    $matmil[13] = 'de trillones';
    $matmil[14] = 'billones de trillones';
    $matmil[15] = 'de billones de trillones';
    $matmil[16] = 'millones de billones de trillones';

    //Zi hack
    $float=explode('.',$num);
    $num=$float[0];

    $num = trim((string)@$num);
    if ($num[0] == '-') {
      $neg = 'menos ';
      $num = substr($num, 1);
    }else
      $neg = '';
    while ($num[0] == '0') $num = substr($num, 1);
    if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
    $zeros = true;
    $punt = false;
    $ent = '';
    $fra = '';
    for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,'''", $n) === false)) {
        if ($punt) break;
        else{
          $punt = true;
          continue;
        }

      }elseif (! (strpos('0123456789', $n) === false)) {
        if ($punt) {
          if ($n != '0') $zeros = false;
          $fra .= $n;
        }else

          $ent .= $n;
      }else

        break;

    }
    $ent = '     ' . $ent;
    if ($dec and $fra and ! $zeros) {
      $fin = ' coma';
      for ($n = 0; $n < strlen($fra); $n++) {
        if (($s = $fra[$n]) == '0')
          $fin .= ' cero';
        elseif ($s == '1')
          $fin .= $fem ? ' una' : ' un';
        else
          $fin .= ' ' . $matuni[$s];
      }
    }else
      $fin = '';
    if ((int)$ent === 0) return 'Cero ' . $fin;
    $tex = '';
    $sub = 0;
    $mils = 0;
    $neutro = false;
    while ( ($num = substr($ent, -3)) != '   ') {
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
        $matuni[1] = 'una';
        $subcent = 'as';
      }else{
        $matuni[1] = $neutro ? 'un' : 'uno';
        $subcent = 'os';
      }
      $t = '';
      $n2 = substr($num, 1);
      if ($n2 == '00') {
      }elseif ($n2 < 21)
        $t = ' ' . $matuni[(int)$n2];
      elseif ($n2 < 30) {
        $n3 = $num[2];
        if ($n3 != 0) $t = 'i' . $matuni[$n3];
        $n2 = $num[1];
        $t = ' ' . $matdec[$n2] . $t;
      }else{
        $n3 = $num[2];
        if ($n3 != 0) $t = ' y ' . $matuni[$n3];
        $n2 = $num[1];
        $t = ' ' . $matdec[$n2] . $t;
      }
      $n = $num[0];
      if ($n == 1) {
        $t = ' ciento' . $t;
      }elseif ($n == 5){
        $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
      }elseif ($n != 0){
        $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
      }
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
        if ($num == 1) {
          $t = ' mil';
        }elseif ($num > 1){
          $t .= ' mil';
        }
      }elseif ($num == 1) {
        $t .= ' ' . $matsub[$sub] . '?n';
      }elseif ($num > 1){
        $t .= ' ' . $matsub[$sub] . 'ones';
      }
      if ($num == '000') $mils ++;
      elseif ($mils != 0) {
        if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
        $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
    }
    $tex = $neg . substr($tex, 1) . $fin;
    //Zi hack --> return ucfirst($tex);
    $end_num=ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
    return $end_num;
  }

  private function dateFormatString($d){
    //2016-08-29T16:45:22
    $meses = array( "01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre" );
    $result =  substr($d, 8, 2) . " de " . $meses[substr($d, 5, 2)] . " del " . substr($d, 0, 4) . " " . substr($d, 11, 8);
    return $result;
  }

  private function cantidad10_6($cantidad){
    settype($cantidad, "string");
    $cantidad = number_format($cantidad, 6, ".", "");
    settype($cantidad, "string");
    $posPunto = strpos($cantidad, ".");
    $cantidad =  str_pad($cantidad, 17, "0", STR_PAD_LEFT);
    return $cantidad;
  }

}