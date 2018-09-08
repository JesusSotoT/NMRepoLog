<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
include('inc/curl.php');

$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
mysqli_query($objCon, "SET NAMES 'utf8'");
$jsnData = array('intResult'=>'','strResult'=>'','strUsr'=>'','strPwd'=>'');
$strUsr = "ebustamante@netwarmonitor.com";
$strPwd = "56884";

//<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siv="http://siveta.ws.com">

$strXMLBody = '
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siv="http://prontipagos.ws.com">
        <soapenv:Header/>
        <soapenv:Body>
            <siv:obtainCatalogProducts/>
        </soapenv:Body>
    </soapenv:Envelope>
';

//echo $strXMLBody;

$xmlResult = execCurl($strUsr,$strPwd,'obtainCatalogProducts',$strXMLBody);


$objDOM = new DOMDocument();
$objDOM->loadXML($xmlResult);
$nodeProducts = $objDOM->getElementsByTagName('products');

//var_dump($objDOM);


foreach($nodeProducts as $objNode){
  $strSku = $objNode->getElementsByTagName('sku')->item(0)->nodeValue;
  $productName = $objNode->getElementsByTagName('productName')->item(0)->nodeValue;
  $strDesc = strtoupper($objNode->getElementsByTagName('description')->item(0)->nodeValue);
  $fixedFee = $objNode->getElementsByTagName('fixedFee')->item(0)->nodeValue;
  $decPrice = $objNode->getElementsByTagName('price')->item(0)->nodeValue;

  //echo "SKU:  $strSku  Product Name: $productName Descripción: <br> $strDesc  Precio: $decPrice Fixed Fee: $fixedFee <br><br>" ;

  $sql = "INSERT IGNORE INTO prontipagos_products
    (strDescription, blnFixedFee, decPrice, strProductName, strSku)
    VALUES
  	('$strDesc', $fixedFee, $decPrice, '$productName', '$strSku');";
  echo $sql . "<br> ";

}

//echo $xmlResult;

?>
