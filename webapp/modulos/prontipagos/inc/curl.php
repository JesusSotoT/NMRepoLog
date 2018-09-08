<?php
function execCurl($strUsr,$strPwd,$strSOAPAction,$strXMLBody){
    $strAuth = $strUsr . ":" . $strPwd;
    $strUrl = "http://wsapp.prontipagos.mx/siveta-endpoint-ws-1.0-SNAPSHOT/ProntipagosTopUpServiceEndPoint?wsdl";

    $arrHeaders = array(
        'Content-Type: text/xml; charset="utf-8"',
        'Content-Length: ' . strlen($strXMLBody),
        'Accept: text/xml',
        'Cache-Control: no-cache',
        'Pragma: no-cache',
        'SOAPAction: "' . $strSOAPAction . '"'
    );
    $curlObj = curl_init();
    curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curlObj, CURLOPT_URL, $strUrl);
    curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curlObj, CURLOPT_TIMEOUT, 180);
    curl_setopt($curlObj, CURLOPT_HTTPHEADER, $arrHeaders);
    curl_setopt($curlObj, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curlObj, CURLOPT_POST, true);
    curl_setopt($curlObj, CURLOPT_POSTFIELDS, $strXMLBody);
    curl_setopt($curlObj, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curlObj, CURLOPT_USERPWD, $strAuth);
    $xmlResult = curl_exec($curlObj);
    curl_close($curlObj);
    unset($curlObj);
    return $xmlResult;
    unset($xmlResult);
}
?>
