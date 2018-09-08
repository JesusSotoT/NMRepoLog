<?php

set_time_limit(0);
$strRFC = $_REQUEST['strRFC'];
$strCIEC = $_REQUEST['strCIEC'];
$strJCAPTCHA = $_REQUEST['jcaptcha'];
require '../../../netwarelog/webconfig.php';
include("../../../libraries/xml2json/xml2json.php");
$conNMDB = mysqli_connect($servidor,$usuariobd,$clavebd, $bd);
mysqli_query($conNMDB,"SET NAMES '" . DB_CHARSET . "'");
//$strSql = "UPDATE pvt_configura_facturacion SET rfc = '" . $strRFC . "', pass_ciec = '" . $strCIEC . "';";
mysqli_query($conNMDB,$strSql);
switch($_REQUEST['strDocument']){
    case 'R':
        $strDocument = 'recibidos';
        break;
    case 'E':
        $strDocument = 'emitidos';
        break;
}
$strYearFrom = $_REQUEST['strYearFrom'];
$strMonthFrom = $_REQUEST['strMonthFrom'];
$strDayFrom = $_REQUEST['strDayFrom'];
$strYearTo = $_REQUEST['strYearTo'];
$strMonthTo = $_REQUEST['strMonthTo'];
$strDayTo = $_REQUEST['strDayTo'];

$arrSTRIP = array('Š'=>'S','š'=>'s','Ž'=>'Z','ž'=>'z','À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Æ'=>'A','Ç'=>'C','È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ñ'=>'N','Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','Þ'=>'B','ß'=>'Ss','à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','æ'=>'a','ç'=>'c','è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ð'=>'o','ñ'=>'n','ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ù'=>'u','ú'=>'u','û'=>'u','ý'=>'y','þ'=>'b','ÿ'=>'y','.'=>'',"'"=>'',"/"=>"");

$jsnPhpScriptResponse = array('intXMLTotal'=>0, 'intXMLAssociated'=>0, 'intXMLDownloaded'=>0, 'intXMLToDownload'=>0, 'intXMLNewSupplier'=>0, 'intXMLNewClient'=>0, 'arrXMLs'=>array(), 'strError'=>'');

require "vendor/autoload.php";

use Blacktrue\Scraping\SATScraper;
use Blacktrue\Scraping\DownloadXML;

try{
	$satScraper = new SATScraper([
        'rfc' => $strRFC,
        'ciec' => $strCIEC,
        'jcaptcha' => $strJCAPTCHA,
        'tipoDescarga' => $strDocument,
        'cancelados' => false
    ]);


	$satScraper->downloadPeriod($strYearFrom,$strMonthFrom,$strDayFrom,$strYearTo,$strMonthTo,$strDayTo); 
    $data = $satScraper->getData(); // Obtenemos un array con la informacion
    $intXMLTotal = count($data);
    $intXMLAssociated = 0;
    $intXMLDownloaded = 0;
    $intXMLToDownload = 0;
    $intXMLNewSupplier = 0;
    $intXMLNewClient = 0;

    $path = "../";
    //$path = "../../../../../mlog/webapp/modulos/cont/";
    if(isset($_COOKIE['inst_lig']))
        $path = "../../../../../".$_COOKIE['inst_lig']."/webapp/modulos/cont/";

    foreach($data as $objKey=>$objValue){
        $arrFiles = glob($path.'xmls/facturas/*/*' . $objKey . "*");
        if(count($arrFiles) > 0){
            $intXMLDownloaded++;
            unset($data[$objKey]);
        }else{
            $intXMLCount = 0;
            $strSql = "SELECT (SELECT COUNT(*) AS 'TOTAL' FROM cont_grupo_facturas f INNER JOIN cont_polizas p ON p.id = f.IdPoliza WHERE p.activo = 1 AND f.Factura LIKE('%" . $objKey . "%')) + (SELECT COUNT(*) AS 'TOTAL' FROM cont_movimientos WHERE Activo = 1 AND Factura LIKE('%" . $objKey . "%')) AS 'TOTAL';";
            $rstCountAssociated = mysqli_query($conNMDB,$strSql);
            while($objCountAssociated = mysqli_fetch_assoc($rstCountAssociated)){
                $intXMLCount = $objCountAssociated['TOTAL'];
            }
            unset($objCountAssociated);
            mysqli_free_result($rstCountAssociated);
            unset($rstCountAssociated);
            if($intXMLCount>0){
                $intXMLAssociated++;
                unset($data[$objKey]);
            }else{
                $intXMLToDownload++;
                array_push($jsnPhpScriptResponse['arrXMLs'],array('uuid'=>$objValue['uuid'], 'rfcEmisor'=>$objValue['rfcEmisor'], 'nombreEmisor'=>$objValue['nombreEmisor'], 'rfcReceptor'=>$objValue['rfcReceptor'], 'nombreReceptor'=>$objValue['nombreReceptor'], 'fechaEmision'=>$objValue['fechaEmision'], 'fechaCertificacion'=>$objValue['fechaCertificacion'], 'pacCertifico'=>$objValue['pacCertifico'], 'total'=>$objValue['total'], 'efectoComprobante'=>$objValue['efectoComprobante'], 'estadoComprobante'=>$objValue['estadoComprobante'], 'fechaCancelacion'=>$objValue['fechaCancelacion']));
            }
        };

    }
    (new DownloadXML) // Creamos una instancia de la clase que descarga las facturas
        ->setSatScraper($satScraper) // Pasamos la instancia del scraper para compartir la misma cookie
        ->setConcurrency(50) // Definimos la concurrencia de descarga
        ->download(function ($contentXml,$name) use ($strRFC){
            $path = "../";
            //$path = "../../../../../mlog/webapp/modulos/cont/";
            if(isset($_COOKIE['inst_lig']))
                $path = "../../../../../".$_COOKIE['inst_lig']."/webapp/modulos/cont/";
            $f = new SplFileObject($path.'xmls/facturas/temporales/TEMP_'.$name,'w');
            $f->fwrite($contentXml);
            $f = null;
            chmod($path.'xmls/facturas/temporales/TEMP_'.$name, 0777);
        });
    foreach ($data as $objClave => $objValor)
    {
        $objXmlFile = new SimpleXMLElement($path.'xmls/facturas/temporales/TEMP_'.$objClave.'.xml',null,true);
        foreach ($objXmlFile->xpath('//cfdi:Comprobante') as $objValues){
            $strXMLTipoComp = $objValues['TipoDeComprobante'];

            if(isset($objValues['version']) && $objValues['version'] == 3.2)
            {
                $strXMLFolio = $objValues['folio'];
                $strXMLMetodoPago = $objValues['formaDePago'];
                $strXMLTotal = $objValues['total'];
                $strXMLMoneda = $objValues['moneda'];
                
                if($strXMLMoneda == '')
                    $strXMLMoneda = $objValues['Moneda'];

                $strXMLFecha = $objValues['fecha'];
                $version = $objValues['version'];
                $strXMLSerie = $objValues['serie'];

            }
            if(isset($objValues['Version']) && $objValues['Version'] == 3.3)
            {
                $strXMLFolio = $objValues['Folio'];
                $strXMLMetodoPago = $objValues['MetodoPago'];
                $strXMLTotal = $objValues['Total'];
                $strXMLMoneda = $objValues['Moneda'];
                $strXMLFecha = $objValues['Fecha'];
                $version = $objValues['Version'];
                $strXMLSerie = $objValues['Serie'];
            }
        }
        foreach ($objXmlFile->xpath('//cfdi:Comprobante//cfdi:Emisor') as $objValues){
            if($version == 3.2)
            {
                $strXMLEmisor = strtoupper(strtr($objValues['nombre'], $arrSTRIP));
                $strXMLEmisorRFC = strtoupper($objValues['rfc']);
            }

            if($version == 3.3)
            {
                $strXMLEmisor = strtoupper(strtr($objValues['Nombre'], $arrSTRIP));
                $strXMLEmisorRFC = strtoupper($objValues['Rfc']);
            }
            
            foreach ($objXmlFile->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $objValuesDom){
                $strXMLEmisorCalle = trim(strtoupper(strtr($objValuesDom['calle'], $arrSTRIP)));
                
                $strXMLEmisorNoExterior = '';
                if(intval($objValuesDom['noExterior']))
                    $strXMLEmisorNoExterior = trim(strtoupper($objValuesDom['noExterior']));

                $strXMLEmisorColonia = trim(strtoupper(strtr($objValuesDom['colonia'], $arrSTRIP)));
                $strXMLEmisorPais = trim(strtoupper($objValuesDom['pais']));
                if($strXMLEmisorPais=='MÉXICO' || $strXMLEmisorPais=='MEXICO' ){
                    $strXMLEmisorPais = 1;
                    $strXMLEmisorEstado = trim(strtoupper(strtr($objValuesDom['estado'], $arrSTRIP)));
                    $strXMLEmisorEstado_2 = $strXMLEmisorEstado;
                    $strSql = "SELECT idestado FROM estados WHERE UPPER(estado) = '" . $strXMLEmisorEstado . "' AND idpais = 1;";
                    $rstEstado = mysqli_query($conNMDB,$strSql);
                    $strXMLEmisorMunicipio_2 = trim(strtoupper(strtr($objValuesDom['municipio'], $arrSTRIP)));
                    
                    if(mysqli_num_rows($rstEstado)!=0){
                        $arrValue = mysqli_fetch_assoc($rstEstado);
                        $strXMLEmisorEstado = $arrValue['idestado'];
                        unset($arrValue);
                        $strXMLEmisorMunicipio = trim(strtoupper($objValuesDom['municipio']));
                        
                        $strSql = "SELECT idmunicipio FROM municipios WHERE UPPER(municipio) = '" . $strXMLEmisorMunicipio . "' AND idestado = " . $strXMLEmisorEstado . ";";
                        $rstMunicipio = mysqli_query($conNMDB,$strSql);
                        if(mysqli_num_rows($rstMunicipio)!=0){
                            $arrValue = mysqli_fetch_assoc($rstMunicipio);
                            $strXMLEmisorMunicipio = $arrValue['idmunicipio'];
                            unset($arrValue);
                        }else{
                            $strXMLEmisorMunicipio = 0;
                        }
                        mysqli_free_result($rstMunicipio);
                        unset($rstMunicipio);
                    }else{
                        $strXMLEmisorEstado = 0;
                        $strXMLEmisorMunicipio = 0;
                    }
                    mysqli_free_result($rstEstado);
                    unset($rstEstado);
                }else{
                    $strXMLEmisorPais = 0;
                    $strXMLEmisorEstado = 0;
                    $strXMLEmisorMunicipio = 0;
                }
            
                $strXMLEmisorCodigoPostal = 0;
                if(intval($objValuesDom['codigoPostal']))
                    $strXMLEmisorCodigoPostal = strtoupper($objValuesDom['codigoPostal']);
            }
        }

        foreach ($objXmlFile->xpath('//cfdi:Comprobante//cfdi:Receptor') as $objValues){
            if($version == 3.2)
            {
                $strXMLReceptor = strtoupper(strtr($objValues['nombre'], $arrSTRIP));
                $strXMLReceptorRFC = strtoupper($objValues['rfc']);
            }

            if($version == 3.3)
            {
                $strXMLReceptor = strtoupper(strtr($objValues['Nombre'], $arrSTRIP));
                $strXMLReceptorRFC = strtoupper($objValues['Rfc']);
            }

            
            foreach ($objXmlFile->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $objValuesDom){
                $strXMLReceptorCalle = trim(strtoupper(strtr($objValuesDom['calle'], $arrSTRIP)));
                $strXMLReceptorColonia = trim(strtoupper(strtr($objValuesDom['colonia'], $arrSTRIP)));
                
                $strXMLReceptorNoExterior = '';
                if(intval($objValuesDom['noExterior']))
                    $strXMLReceptorNoExterior = trim(strtoupper($objValuesDom['noExterior']));

                $strXMLReceptorNoExterior = trim(strtoupper($objValuesDom['noExterior']));
                
                $strXMLReceptorPais = trim(strtoupper($objValuesDom['pais']));
                if($strXMLReceptorPais=='MÉXICO' || $strXMLReceptorPais=='MEXICO' ){
                    $strXMLReceptorPais = 1;
                    $strXMLReceptorEstado = trim(strtoupper(strtr($objValuesDom['estado'], $arrSTRIP)));
                    $strXMLReceptorEstado_2 = $strXMLReceptorEstado;
                    $strSql = "SELECT idestado FROM estados WHERE UPPER(estado) = '" . $strXMLReceptorEstado . "' AND idpais = 1;";
                    $rstEstado = mysqli_query($conNMDB,$strSql);
                    if(mysqli_num_rows($rstEstado)!=0){
                        $arrValue = mysqli_fetch_assoc($rstEstado);
                        $strXMLReceptorEstado = $arrValue['idestado'];
                        unset($arrValue);
                        $strXMLReceptorMunicipio = trim(strtoupper(strtr($objValuesDom['municipio'], $arrSTRIP)));
                        $strXMLReceptorMunicipio_2 = $strXMLReceptorMunicipio;
                        $strSql = "SELECT idmunicipio FROM municipios WHERE UPPER(municipio) = '" . $strXMLReceptorMunicipio . "' AND idestado = " . $strXMLReceptorEstado . ";";
                        $rstMunicipio = mysqli_query($conNMDB,$strSql);
                        if(mysqli_num_rows($rstMunicipio)!=0){
                            $arrValue = mysqli_fetch_assoc($rstMunicipio);
                            $strXMLReceptorMunicipio = $arrValue['idmunicipio'];
                            unset($arrValue);
                        }else{
                            $strXMLReceptorMunicipio = 0;
                        }
                        mysqli_free_result($rstMunicipio);
                        unset($rstMunicipio);
                    }else{
                        $strXMLReceptorEstado = 0;
                        $strXMLReceptorMunicipio = 0;
                    }
                    mysqli_free_result($rstEstado);
                    unset($rstEstado);
                }else{
                    $strXMLReceptorPais = 0;
                    $strXMLReceptorEstado = 0;
                    $strXMLReceptorMunicipio = 0;
                }
                $strXMLReceptorCodigoPostal = strtoupper($objValuesDom['codigoPostal']);
            }
        }
        $es_nomina = 0;
        foreach ($objXmlFile->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina12:Receptor') as $objValues)
        {
            if($objValues['NumEmpleado'] != '')
                $es_nomina = 1;
        }
        //unset($objXmlFile);


        $strXMLFolio = str_replace('-', '', $strXMLFolio);
        $strXMLFileName = $path.'xmls/facturas/temporales/'.$strXMLFolio.'_';
        switch($_REQUEST['strDocument']){
            case 'R':
                $strXMLRFC = $strXMLEmisorRFC;
                $strXMLFileName .= $strXMLEmisor.'_';
                $tipo = "Egreso";
                if(intval($_REQUEST['strNuevosPc']))
                {
                    $strSql = "SELECT COUNT(*) AS 'EXS' FROM mrp_proveedor WHERE rfc = '" . $strXMLEmisorRFC . "';";
                    $rstCount = mysqli_query($conNMDB,$strSql);
                    $arrCount = mysqli_fetch_assoc($rstCount);
                    if($arrCount['EXS']==0){
                        $intXMLNewSupplier++;
                        if(!$strXMLEmisorEstado)
                            $strXMLEmisorEstado = 14;

                        if(!$strXMLEmisorMunicipio)
                            $strXMLEmisorMunicipio = 538;
                        $strSql = "INSERT INTO mrp_proveedor (razon_social, rfc, domicilio, idestado, idmunicipio,idtipotercero, idtipoperacion, idTasaPrvasumir,calle,no_ext,cp,colonia) VALUES ('$strXMLEmisor','$strXMLEmisorRFC','$strXMLEmisorCalle $strXMLEmisorNoExterior $strXMLEmisorColonia $strXMLEmisorMunicipio_2  $strXMLEmisorEstado_2 $strXMLEmisorCodigoPostal',$strXMLEmisorEstado,$strXMLEmisorMunicipio,0,0,0,'$strXMLEmisorCalle','$strXMLEmisorNoExterior','$strXMLEmisorCodigoPostal','$strXMLEmisorColonia');";
                        //$strSql = "INSERT INTO mrp_proveedor (razon_social, rfc, domicilio, idpais, idestado, idmunicipio, calle, no_ext, cp, idtipotercero, idtipoperacion, idTasaPrvasumir) VALUES ('" . $strXMLEmisor . "','" . $strXMLEmisorRFC . "','" . $strXMLEmisorCalle . " " . $strXMLEmisorNoExterior . "'," . $strXMLEmisorPais . "," . $strXMLEmisorEstado . "," . $strXMLEmisorMunicipio . ",'" . $strXMLEmisorCalle . "'," . $strXMLEmisorNoExterior . ",'" . $strXMLEmisorCodigoPostal . "',0,0,0);";
                        mysqli_query($conNMDB, $strSql);
                        $insert_id = mysqli_insert_id($conNMDB);

                        $strSql = "INSERT INTO comun_facturacion(nombre,rfc,razon_social,domicilio,num_ext,cp,colonia,municipio,estado,cliPro) VALUES($insert_id,'$strXMLEmisorRFC','$strXMLEmisor','$strXMLEmisorCalle','$strXMLEmisorNoExterior', '$strXMLReceptorCodigoPostal', '$strXMLEmisorColonia', '$strXMLEmisorMunicipio_2', $strXMLEmisorEstado,2)";
                        mysqli_query($conNMDB, $strSql);
                    }
                    unset($arrCount);
                    mysqli_free_result($rstCount);
                    unset($rstCount);
                }
                
                break;
            case 'E':
                $strXMLRFC = $strXMLReceptorRFC;
                $strXMLFileName .= $strXMLReceptor.'_';
                $tipo = "Ingreso";
                if(intval($_REQUEST['strNuevosPc']))
                {
                    if(!$es_nomina)
                    {
                        $strSql = "SELECT COUNT(*) AS 'EXS' FROM comun_cliente WHERE rfc = '" . $strXMLReceptorRFC . "';";
                        $rstCount = mysqli_query($conNMDB,$strSql);
                        $arrCount = mysqli_fetch_assoc($rstCount);
                        if($arrCount['EXS']==0)
                        {
                            $intXMLNewClient++;
                            if(!$strXMLReceptorEstado)
                                $strXMLReceptorEstado = 14;

                            if(!$strXMLReceptorMunicipio)
                                $strXMLReceptorMunicipio = 538;
                            $strSql = "INSERT INTO comun_cliente(nombre,direccion,num_ext,colonia,rfc,idEstado,idMunicipio, cp) VALUES('$strXMLReceptor','$strXMLReceptorCalle', '$strXMLReceptorNoExterior', '$strXMLReceptorColonia','$strXMLReceptorRFC',$strXMLReceptorEstado,$strXMLReceptorMunicipio, '$strXMLReceptorCodigoPostal');";
                            //$strSql = "INSERT INTO comun_cliente (nombre,direccion,cp,idPais,idEstado,idMunicipio,rfc,num_ext) VALUES ('" . $strXMLReceptor . "','" . $strXMLReceptorCalle . "','" . $strXMLReceptorCodigoPostal . "'," . $strXMLReceptorPais . "," . $strXMLReceptorEstado . "," . $strXMLReceptorMunicipio . ",'" . $strXMLReceptorRFC . "'," . $strXMLReceptorNoExterior . ");";
                            mysqli_query($conNMDB, $strSql);
                            $insert_id = mysqli_insert_id($conNMDB);

                            $strSql = "INSERT INTO comun_facturacion(nombre,rfc,razon_social,domicilio,num_ext,cp,colonia,municipio,estado,cliPro) VALUES($insert_id,'$strXMLReceptorRFC','$strXMLReceptor','$strXMLReceptorCalle','$strXMLReceptorNoExterior', '$strXMLReceptorCodigoPostal', '$strXMLReceptorColonia', '$strXMLReceptorMunicipio_2', $strXMLReceptorEstado,1)";
                            mysqli_query($conNMDB, $strSql);
                        }
                    }
                    else
                    {
                        $strSql = "SELECT COUNT(*) AS 'EXS' FROM nomi_empleados WHERE rfc = '$strXMLReceptorRFC';";
                        $rstCount = mysqli_query($conNMDB,$strSql);
                        $arrCount = mysqli_fetch_assoc($rstCount);
                        if($arrCount['EXS']==0)
                        {
                            $intXMLNewClient++;
                            $strSql = "INSERT INTO nomi_empleados(idEmpleado, codigo, nombreEmpleado, idestado, idmunicipio, rfc) VALUES(0,'".$objValues['NumEmpleado']."','$strXMLReceptor', 14, 0, '$strXMLReceptorRFC');";
                            
                            mysqli_query($conNMDB, $strSql);
                        }
                    }
                    unset($arrCount);
                    mysqli_free_result($rstCount);
                    unset($rstCount); 
                }
                if($es_nomina)
                    $tipo = "Nomina";

               
                break;
        }
        $strXMLFileName .= $objClave.'.xml';
        if(file_exists($strXMLFileName)){
            unlink($strXMLFileName);
        }
        rename($path.'xmls/facturas/temporales/TEMP_'.$objClave.'.xml',$strXMLFileName);

        //Extrae el contenido del xml y lo convierte a json.
        $cont_xml = simplexml_load_file($strXMLFileName);
        $cont_array = xmlToArray($cont_xml);
        //$json = utf8_encode(json_encode($cont_array,JSON_UNESCAPED_UNICODE));
        $json = utf8_encode(json_encode($cont_array));

        //Guarda en la tabla de facturas
        $strDocument = $_REQUEST['strDocument'];
        $FileName = explode('temporales/',$strXMLFileName);
        $FileName = $FileName[1];
        
        $myQuery = "INSERT INTO cont_facturas (id, folio, uuid, er, tipo, serie, emisor, receptor, importe, moneda, rfc, fecha, fecha_subida, xml, version, cancelada, json) VALUES(0, '$strXMLFolio', '$objClave', '$strDocument', '$tipo', '$strXMLSerie', '$strXMLEmisor', '$strXMLReceptor', $strXMLTotal, '$strXMLMoneda', '$strXMLRFC', '$strXMLFecha', DATE_SUB(NOW(), INTERVAL 6 HOUR), '$FileName', $version, 0, '$json');";

        mysqli_query($conNMDB, $myQuery);
        //Si es una factura de pagos agrega la relacion de complementos
        if($strXMLTipoComp == "P")
        {
            $IdDocumento        = $objXmlFile->xpath('//@IdDocumento');
            $ImpPagado          = $objXmlFile->xpath('//@ImpPagado');
            $ImpSaldoAnt        = $objXmlFile->xpath('//@ImpSaldoAnt');
            $ImpSaldoInsoluto   = $objXmlFile->xpath('//@ImpSaldoInsoluto');
            $MonedaDR           = $objXmlFile->xpath('//@MonedaDR');
            $MetodoDePagoDR     = $objXmlFile->xpath('//@MetodoDePagoDR');
            $NumParcialidad     = $objXmlFile->xpath('//@NumParcialidad');

            if(!intval($NumParcialidad))
                $NumParcialidad = 0;

            if(is_array($IdDocumento))
            {
                for($h=0;$h<=count($IdDocumento)-1;$h++)
                {
                    $myQuery = "INSERT IGNORE INTO cont_facturas_relacion VALUES('".$objClave."','".$IdDocumento[$h]."',".$ImpPagado[$h].",".$ImpSaldoAnt[$h].",".$ImpSaldoInsoluto[$h].",'".$MonedaDR[$h]."','".$MetodoDePagoDR[$h]."',".$NumParcialidad[$h].");";
                    mysqli_query($conNMDB, $myQuery);
                }
            }
            else
            {
                $myQuery = "INSERT IGNORE INTO cont_facturas_relacion VALUES('".$objClave."','".$IdDocumento."',".$ImpPagado.",".$ImpSaldoAnt.",".$ImpSaldoInsoluto.",'".$MonedaDR."','".$MetodoDePagoDR."',".$NumParcialidad.");";
                    mysqli_query($conNMDB, $myQuery);
            }
        }
        unset($objXmlFile);
    }
    $jsnPhpScriptResponse['intXMLTotal'] = $intXMLTotal;
    $jsnPhpScriptResponse['intXMLDownloaded'] = $intXMLDownloaded;
    $jsnPhpScriptResponse['intXMLAssociated'] = $intXMLAssociated;
    $jsnPhpScriptResponse['intXMLToDownload'] = $intXMLToDownload;
    $jsnPhpScriptResponse['intXMLNewSupplier'] = $intXMLNewSupplier;
    $jsnPhpScriptResponse['intXMLNewClient'] = $intXMLNewClient;
    
    $temps = glob($path.'xmls/facturas/temporales/TEMP_*'); // obtiene todos los archivos que no se generaron bien
    foreach($temps as $temp)
    {
        unlink($temp); // lo elimina
    }

}catch(\Blacktrue\Scraping\Exceptions\SATException $e){
	$jsnPhpScriptResponse['strError'] = $e->getMessage().PHP_EOL;
}
echo json_encode($jsnPhpScriptResponse);
mysqli_close($conNMDB);
unset($conNMDB);


