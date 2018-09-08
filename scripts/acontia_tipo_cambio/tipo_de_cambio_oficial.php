Generando tipo de cambio oficial peso - dolar de hoy.
<?php 
    require_once('lib/nusoap.php'); 
     
    $oSoapClient = new nusoap_client('http://www.banxico.org.mx/DgieWSWeb/DgieWS?WSDL','wsdl'); 
         
    if ($sError = $oSoapClient->getError()) { 
        echo "No se pudo realizar la operación [" . $sError . "]"; 
        die(); 
    } 
    //$aParametros = array("TITULO" => $titulo,"IDSERIE"=> $serie,"BANXICO_FREQ"=>$frec, "BANXICO_UNIT_TYPE"=> $tipo); 
    //$aParametros = ""; 
    $respuesta = $oSoapClient->call("tiposDeCambioBanxico", array(),"http://ws.dgie.banxico.org.mx","","","","rpc","http://schemas.xmlsoap.org/soap/encoding/","encoded"); 
     
    // Existe alguna falla en el servicio?  
    if ($oSoapClient->fault) { // Si 
        echo 'No se pudo completar la operación'; 
        die(); 
    }else { // No 
        $sError = $oSoapClient->getError(); 
        // Hay algun error ? 
        if ($sError) { // Si 
            echo 'Error:' . $sError; 
            die(); 
        } 

        $xml = $respuesta;

        //---------Si queremos crear y guardar la respuesta en un archivo xml, descomentamos esto.-------
            //$fichero = "archivoxml.xml";
            //$archivo = fopen($fichero, "w+");                    
            //fwrite($archivo,$respuesta); 
            //fclose($archivo);     
            //$xml = file_get_contents($fichero);

          $DOM = new DOMDocument('1.0', 'utf-8');
          $DOM->loadXML($xml);
          $cambios = $DOM->getElementsByTagName('Obs');
          $cuenta = 1;
          foreach($cambios as $cambio) 
          {
            if($cuenta == 1)
            {
                $valor = $cambio->getAttribute('OBS_VALUE');
                $fecha = $cambio->getAttribute('TIME_PERIOD');
            }
            $cuenta++;
          }
          $query = "INSERT INTO nmdev_common.tipo_cambio_oficial(fecha,valor,moneda)
SELECT '$fecha',$valor,2
FROM dual
WHERE NOT EXISTS (SELECT fecha FROM nmdev_common.tipo_cambio_oficial WHERE fecha='$fecha' LIMIT 1)";
$conexion = mysqli_connect('nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com','nmcommon','common123','nmdev_common');
$conexion->query($query);
$conexion->close();

    }
    echo "<br />Valor: $".$valor."</br>Fecha: ".$fecha;
  
?>