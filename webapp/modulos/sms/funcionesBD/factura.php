<?php 

include("../../../netwarelog/catalog/conexionbd.php");
require_once '../../../modulos/posclasico/lib/nusoap.php';
 function fact($idOfertaClient)
{
include("../../../modulos/configFC.php");
	$ruta="../../../modulos/sms/facturacion/";  
	
$parametros = array();
$parametros['Usuario'] = $FC_user;
$parametros['Contrasena'] = $FC_password;
$serverURL = $FC_url; 
$serverScript = 'CFD.asmx';
$metodoALlamar = 'GeneraCFD';

 $TC='C';//GENERA NOTA DE CREDITO  
 $TC='F';//GENERA FACTURA 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//$idOfertaCliente=$_REQUEST["idOfertaCliente"];
$idOfertaCliente=$idOfertaClient;
$consulta="select  
oc.id,
c.nombre,
c.direccion,
c.colonia,
c.nombretienda,
c.celular,
c.cp,
e.estado,
m.municipio,
oc.cantidad,
oc.estatus estatusofertacliente,
CONCAT('Oferta:',o.cantidad,' ',u.compuesto,' ',p.nombre) concepto,
u.compuesto unidad,
o.precio,
o.estatus estatusoferta
from sms_oferta_cliente oc, comun_cliente c ,sms_oferta o,mrp_producto p,mrp_unidades u,estados e,municipios m
where 
c.id=oc.idCliente and 
oc.contesto=1 and 
oc.cantidad>0 and 
o.idOferta=oc.idOferta and 
o.idProducto=p.idProducto and 
u.idUni=o.idUnidad and 
c.idEstado=e.idestado and 
c.idMunicipio=m.idmunicipio and 
oc.id=".$idOfertaCliente;


$q=mysql_query($consulta); 
$row=mysql_fetch_array($q); 

 
 $subtotal=$row["precio"];
 $total=$row["precio"]*$row["cantidad"];	
 
 
  $rfc='XXXX00000XXXX';
  $rz='RFC GENERICO';
  $pais='MEXICO';  
 
  /* EXTRAER CONCEPTO DE FACTURACION*/
  $cantidad=$row["cantidad"];
  $unidad=$row["unidad"];
  $descripcion=$row["concepto"];
  $precio=$row["precio"];
  $importe=$row["precio"];
   /*END  EXTRAER CONCEPTO DE FACTURACION*/

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // Datos del documento
  // Parametros generales del documento
  $parametros['DatosCFD'] = array();
  $parametros['DatosCFD']['FormadePago']       = "Pago en una sola exhibicion";
  $parametros['DatosCFD']['Moneda']            = "MXP";
  $parametros['DatosCFD']['Subtotal']          = $subtotal;
  $parametros['DatosCFD']['Total']             = $total;
  $parametros['DatosCFD']['Serie']             = "A";
  $parametros['DatosCFD']['TipodeComprobante'] = $TC;
  $parametros['DatosCFD']['MensajePDF']        = "PDF de prueba";
  
  // Datos del cliente receptor
  $parametros['Receptor'] = array();
  $parametros['Receptor']['RFC']           = $rfc;
  $parametros['Receptor']['RazonSocial']   = $rz;
  $parametros['Receptor']['Pais']          = $pais;
  $parametros['Receptor']['Email1']        = "";
  
  $parametros['Receptor']['Estado']        = $row["estado"];
  $parametros['Receptor']['Ciudad']        = $row["municipio"];
  $parametros['Receptor']['Colonia']        =$row["colonia"];
  $parametros['Receptor']['Calle']        = $row["direccion"];
  $parametros['Receptor']['CP']        = $row["cp"];
  
  
  
  // Concepto
  $conceptosDatos = array(
      array(
        'Cantidad' => $cantidad,
        'Unidad' => $unidad,
        'Descripcion' => $descripcion,
		'Precio' => $precio,
        'Importe' => $importe*$cantidad,      
      )
  );
  
  // Agregamos conceptos a los parametros de manera que el WebService los lea correctamente 
  foreach($conceptosDatos as $concepto) {
    $parametros['Conceptos'][] = new soapval('Concepto', 'Concepto', $concepto);
  }
  // Impuestos
  $impuestosDatos = array(
      array(
        'TipoImpuesto' => "IVA",
        'Tasa' => 16,
        'Importe' => $total
      ), 
      array(
        'TipoImpuesto' => "ISR",
        'Tasa' => 10,
        'Importe' => $total
      )
  );

  // Agregamos impuestos a los parametros de manera que el WebService los lea correctamente 
  foreach($impuestosDatos as $impuesto) {
      $parametros['Impuestos'][] = new soapval('Impuesto', 'Impuesto', $impuesto);
  }

  // Lugar para agregar la addenda en caso de ser utulizada
  $parametros['XMLAddenda'] = "";

  $cliente = new nusoap_client("$serverURL/$serverScript?WSDL", 'wsdl');
    
    $error = $cliente->getError();
    if ($error) {
         echo '<pre style="color: red">' . $error . '</pre>';
         echo '<p style="color:red;'>htmlspecialchars($cliente->getDebug(), ENT_QUOTES).'</p>';
         die();
    }

    // Llamar a la funcion GeneraCFD del servidor
    $result = $cliente->call(
        $metodoALlamar, 
        $parametros,
        "uri:$serverURL/$serverScript",
        "uri:$serverURL/$serverScript/$metodoALlamar"
    );

    // Analizando resultados
    if ($cliente->fault) {
        echo '<b>Error: ';
        print_r($result);
        echo '</b>';
    } else {
        $error = $cliente->getError();
        if ($error) {
            echo '<b style="color: red">Error encontrado: ' . $error . '</b>';
        } else {
              // echo 'xxxPartirCadenaxxx';
    $cadnew=explode('<?xml',$cliente->response);
    
    $pcad=explode('UUID="',$cadnew[1]);
    $cad=explode('"',$pcad[1]);
    $UUID=$cad[0];

    $pcad=explode('noCertificadoSAT="',$cadnew[1]);
    $cad=explode('"',$pcad[1]);
    $noCertificadoSAT=$cad[0];

    $pcad=explode('folio="',$cadnew[1]);
    $cad=explode('"',$pcad[1]);
    $folio=$cad[0];

    $pcad=explode('selloCFD="',$cadnew[1]);
    $cad=explode('"',$pcad[1]);
    $selloCFD=$cad[0];

    $pcad=explode('selloSAT="',$cadnew[1]);
    $cad=explode('"',$pcad[1]);
    $selloSAT=$cad[0];

    $pcad=explode('sello="',$cadnew[1]);
    $cad=explode('"',$pcad[1]);
    $sello=$cad[0];

    $pcad=explode('noCertificado="',$cadnew[1]);
    $cad=explode('"',$pcad[1]);
    $noCertificado=$cad[0];

   
    echo $UUID;
   /*
    echo 'xd-dx';
    echo $noCertificadoSAT;echo 'xd-dx';
    echo $folio;echo 'xd-dx';
    echo $selloCFD;echo 'xd-dx';
    echo $selloSAT;echo 'xd-dx';
    echo $noCertificado;
*/
    file_put_contents($ruta.''.$UUID.".xml", $result['GeneraCFDResult']);
            // Generacion del PDF
            $parametrosPDF = array();
            $metodoALlamar = 'GeneraPDF';
            // Datos de acceso
              $parametrosPDF['Usuario'] = $parametros['Usuario'];
             $parametrosPDF['Contrasena'] = $parametros['Contrasena'];
             $parametrosPDF['Serie'] = $parametros['DatosCFD']['Serie'];
             
             // Obtenemos el numero de folio del XML recibido
             $result = $result['GeneraCFDResult'];
             $i1 = strpos($result, 'folio="');
             $i2 = strpos($result, '"', $i1+7);
             $l = $i2 - ($i1+7);
             $parametrosPDF['Folio'] = substr($result, $i1+7, $l);

            // Llamar a la funcion GeneraCFD del servidor
            $result = $cliente->call(
                $metodoALlamar, 
                $parametrosPDF,
                "uri:$serverURL/$serverScript",
                "uri:$serverURL/$serverScript/$metodoALlamar"
            );       
            // Analizando rsultados
            if ($cliente->fault) {
                echo '<b>Error: ';
                print_r($result);
                echo '</b>';
            } else {
                $error = $cliente->getError();
                if ($error) {
                    echo '<b style="color: red">Error encontrado: ' . $error . '</b>';
                } else {
                    // Creando el archivo PDF
                      $data = base64_decode($result['GeneraPDFResult']);
                    file_put_contents($ruta.''.$UUID.".pdf", $data);
                }
            }
  //  $result = mysql_query("INSERT INTO pvt_respuestaFacturacion (idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,tipoComp) VALUES ('".$iids."','0','".$UUID."','".$folio."','".$noCertificadoSAT."','".$noCertificado."','".$selloSAT."','".$selloCFD."', NOW(), '".$TC."')");
  }
 return $UUID;
}

}       
?>