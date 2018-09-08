<?php
ini_set("display_errors",0);

include('../../netwarelog/webconfig.php');
include('inc/curl.php');

$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
mysqli_query($objCon, "SET NAMES 'utf8'");

$strSql = "SELECT strUser, strPassword FROM prontipagos_configuracion";
$rstUser = mysqli_query($objCon,$strSql);

while($objUser=mysqli_fetch_assoc($rstUser)){
    $strUsr = $objUser['strUser'];
    $strPwd = $objUser['strPassword'];
}
mysqli_close($objCon);

$objCon = mysqli_connect($servidor, "nmdevel", "nmdevel", "netwarstore");
mysqli_query($objCon, "SET NAMES 'utf8'");
$intProduct = $_REQUEST['product'];
$strReference = $_REQUEST['reference'];
$decAmount = $_REQUEST['amount'];
$jsnData = array('codeTransaction'=>'', 'codeDescription'=>'', 'dateTransaction'=>'', 'transactionId'=>'', 'folioTransaction'=>'', 'additionalInfo'=>'');

$strSql = "SELECT * FROM prontipagos_products WHERE intId = " . $intProduct . ";";

$rstProduct = mysqli_query($objCon,$strSql);
while($objProduct=mysqli_fetch_assoc($rstProduct)){
    $strSku = $objProduct['strSku'];
}
unset($objProduct);
mysqli_free_result($rstProduct);
unset($rstProduct);

$strXMLBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:pron="http://prontipagos.ws.com">';
$strXMLBody .= '<soapenv:Header/>';
$strXMLBody .= '<soapenv:Body>';
$strXMLBody .= '<pron:sellService>';
    $strXMLBody .= '<amount>' . $decAmount . '</amount>';
    $strXMLBody .= '<reference>' . $strReference . '</reference>';
    $strXMLBody .= '<sku>' . $strSku . '</sku>';
    $strXMLBody .= '<clientReference></clientReference>';
$strXMLBody .= '</pron:sellService>';
$strXMLBody .= '</soapenv:Body>';
$strXMLBody .= '</soapenv:Envelope>';
//echo $strXMLBody;
$xmlResult = execCurl($strUsr,$strPwd,"sellService",$strXMLBody);

$objDOM = new DOMDocument();
$objDOM->loadXML($xmlResult);
$nodeReturn = $objDOM->getElementsByTagName('return');
foreach($nodeReturn as $objReturn){
    $jsnData['codeTransaction'] = $objReturn->getElementsByTagName('codeTransaction')->item(0)->nodeValue;
    $jsnData['codeDescription'] = $objReturn->getElementsByTagName('codeDescription')->item(0)->nodeValue;
    $jsnData['dateTransaction'] = $objReturn->getElementsByTagName('dateTransaction')->item(0)->nodeValue;
    $jsnData['transactionId']   = $objReturn->getElementsByTagName('transactionId')->item(0)->nodeValue;
}
unset($objReturn);
unset($nodeReturn);
unset($objDOM);

sleep(1);

if(is_null($jsnData['codeTransaction'])){
    $dteStartTime = strtotime(date('Y-m-d h:i:s'));
    $dteCurrTime = strtotime(date('Y-m-d h:i:s'));
    $dteElapsedTime = $dteCurrTime - $dteStartTime;
    while($dteElapsedTime<=65){
      $strXMLBody = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:pron="http://prontipagos.ws.com">';
      $strXMLBody .= '    <soapenv:Header/>';
      $strXMLBody .= '    <soapenv:Body>';
      $strXMLBody .= '        <pron:checkStatusService>';
      $strXMLBody .= '            <transactionId>' . $jsnData['transactionId'] . '</transactionId>';
      $strXMLBody .= '            <clientReference></clientReference>';
      $strXMLBody .= '        </pron:checkStatusService>';
      $strXMLBody .= '    </soapenv:Body>';
      $strXMLBody .= '</soapenv:Envelope>';
      $strXMLBody .= '</soapenv:Envelope>';

        $xmlResult = execCurl($strUsr,$strPwd,'checkStatusService',$strXMLBody);
        $objDOM = new DOMDocument();
        $objDOM->loadXML($xmlResult);
        $nodeReturn = $objDOM->getElementsByTagName('return');
        foreach($nodeReturn as $objReturn){
            $jsnData['codeTransaction'] = $objReturn->getElementsByTagName('codeTransaction')->item(0)->nodeValue;
            $jsnData['codeDescription'] = $objReturn->getElementsByTagName('codeDescription')->item(0)->nodeValue;
            $jsnData['dateTransaction'] = $objReturn->getElementsByTagName('dateTransaction')->item(0)->nodeValue;
            $jsnData['transactionId']   = $objReturn->getElementsByTagName('transactionId')->item(0)->nodeValue;
            $jsnData['folioTransaction'] = $objReturn->getElementsByTagName('folioTransaction')->item(0)->nodeValue;
            $jsnData['additionalInfo'] = $objReturn->getElementsByTagName('additionalInfo')->item(0)->nodeValue;
        }
        unset($objReturn);
        unset($nodeReturn);
        unset($objDOM);
        if($jsnData['codeTransaction']=='N/A'){
            sleep(2);
            $dteCurrTime = strtotime(date('Y-m-d h:i:s'));
            $dteElapsedTime = $dteCurrTime - $dteStartTime;
        }else{
            $dteElapsedTime = 66;
        }
    }
}else{
    $jsnData['folioTransaction'] = '';
    $jsnData['additionalInfo'] = '';
}
echo json_encode($jsnData);
mysqli_close($objCon);
unset($objCon);
?>
