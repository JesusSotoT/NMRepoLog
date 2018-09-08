<?php
include('inc/curl.php');

$jsnData = array('intResult'=>'','strResult'=>'','strUsr'=>'','strPwd'=>'');
$strUsr = 'pruebasPronti@pagos.com';
$strPwd = 'ProntiP30%';

//##### obtainCatalogProducts #####

$strXMLBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siv="http://siveta.ws.com">';
$strXMLBody .= '    <soapenv:Header/>';
$strXMLBody .= '    <soapenv:Body>';
$strXMLBody .= '        <siv:obtainCatalogProducts/>';
$strXMLBody .= '    </soapenv:Body>';
$strXMLBody .= '</soapenv:Envelope>';

//##### obtainCatalogProducts #####

//##### TopUpService - sellService #####
/*
$strXMLBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siv="http://siveta.ws.com">';
$strXMLBody .= '    <soapenv:Header/>';
$strXMLBody .= '    <soapenv:Body>';
$strXMLBody .= '        <siv:sell>';
$strXMLBody .= '            <amount>50</amount>';
$strXMLBody .= '            <reference>5555555555</reference>';
$strXMLBody .= '            <sku>S3AXTELMXN</sku>';
$strXMLBody .= '            <clientReference>3</clientReference>';
$strXMLBody .= '        </siv:sell>';
$strXMLBody .= '    </soapenv:Body>';
$strXMLBody .= '</soapenv:Envelope>';
*/
//##### TopUpService - sellService #####

$xmlResult = execCurl($strUsr,$strPwd,'sell',$strXMLBody);

$objDOM = new DOMDocument();
$objDOM->loadXML($xmlResult);
$nodeProducts = $objDOM->getElementsByTagName('products');
$strOut = "";
foreach($nodeProducts as $objNode){
    $strDescription = strtoupper($objNode->getElementsByTagName('description')->item(0)->nodeValue);
    $strFixedFee = $objNode->getElementsByTagName('fixedFee')->item(0)->nodeValue;
    $decPrice = $objNode->getElementsByTagName('price')->item(0)->nodeValue;
    $strProductName = strtoupper($objNode->getElementsByTagName('description')->item(0)->nodeValue);
    $nodeReferences = $objNode->getElementsByTagName('referencesProductsList');
    $strReference = "";
    $strRegex = "";
    $strType = "";
    foreach($nodeReferences as $objReferences){
        $strReference = $objReferences->getElementsByTagName('reference')->item(0)->nodeValue;
        $strRegex = $objReferences->getElementsByTagName('regex')->item(0)->nodeValue;
        $strType = $objReferences->getElementsByTagName('type')->item(0)->nodeValue;
    }
    unset($objReferences);
    unset($nodeReferences);
    $strSku = strtoupper($objNode->getElementsByTagName('sku')->item(0)->nodeValue);
    $strOut .= $strDescription . "|" . $strFixedFee . "|" . $decPrice  . "|" . $strProductName . "|" . $strReference . "|" . $strRegex . "|" . $strType . "|" . $strSku . "\n";
}
unset($objNode);
unset($nodeProducts);
unset($objDOM);
?>
<textarea style="width: 900px; height: 400px;"><?php echo $strOut; ?></textarea>