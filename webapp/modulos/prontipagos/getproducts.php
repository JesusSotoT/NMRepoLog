<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
include('inc/curl.php');
$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
mysqli_query($objCon, "SET NAMES 'utf8'");
$jsnData = array('intResult'=>'','strResult'=>'','strUsr'=>'','strPwd'=>'');
$strUsr = $_REQUEST['strUsr'];
$strPwd = $_REQUEST['strPwd'];


//Actualiza información de usuario.
$strSql = "SELECT * FROM prontipagos_configuracion";
$rstUP = mysqli_query($objCon, $strSql);
if(mysqli_num_rows($rstUP)==0){
    $strSts = "NORESULTS";
    $strSql = "INSERT INTO prontipagos_configuracion (id, strUser, strPassword) VALUES (1, '$strUsr', '$strPwd')";
    $rstUP = mysqli_query($objCon, $strSql);

}else{
  $strSql = "UPDATE prontipagos_configuracion SET strUser='$strUsr', strPassword='$strPwd' WHERE id=1";
  $rstUP = mysqli_query($objCon, $strSql);
}
mysqli_free_result($rstUP);
unset($rstUp);


$strXMLBody = '
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siv="http://siveta.ws.com">
        <soapenv:Header/>
        <soapenv:Body>
            <siv:obtainCatalogProducts/>
        </soapenv:Body>
    </soapenv:Envelope>
';

$xmlResult = execCurl($strUsr,$strPwd,'obtainCatalogProducts',$strXMLBody);

if(strpos($xmlResult,"This request requires HTTP authentication ().") == false) {
    //$strSql = "INSERT IGNORE INTO pvt_prontipagos_configuracion (id, strUser, strPassword) VALUES (1,'" . $strUsr . "','" . $strPwd . "');";
    $strSql = "INSERT IGNORE INTO prontipagos_configuracion (id, strUser, strPassword) VALUES (1,'" . $strUsr . "','" . $strPwd . "');";

    //echo $strSql;
    mysqli_query($objCon,$strSql);
/*
    $strSql = "INSERT INTO mrp_departamento VALUES (0,'Servicios Prontipagos');";
    mysqli_query($objCon,$strSql);
    $intDepartment = mysqli_insert_id($objCon);
    $strSql = "INSERT INTO mrp_familia VALUES (0,'Servicios Prontipagos'," . $intDepartment . ");";
    mysqli_query($objCon,$strSql);
    $intFamily = mysqli_insert_id($objCon);
    $strSql = "INSERT INTO mrp_linea VALUES (0,'Servicios Prontipagos'," . $intFamily . ");";
    mysqli_query($objCon,$strSql);
    $intLine = mysqli_insert_id($objCon);
    $strSql = "INSERT INTO mrp_proveedor VALUES (0,'Prontipagos','XAXX010101000','Domicilio','1234567890','pronti@pagos.com','www.prontipagos.com',0,1,1,0,0,0,0,0,0,0,0,0,0,'Mexicana',0,0,0,0,0);";
    mysqli_query($objCon,$strSql);
    $intSupplier = mysqli_insert_id($objCon);
    $strSql = "INSERT INTO mrp_unidades VALUES (0,'Servicio Prontipagos',1,1,0,0);";
    mysqli_query($objCon,$strSql);
    $intUnit = mysqli_insert_id($objCon);
*/
    $objDOM = new DOMDocument();
    $objDOM->loadXML($xmlResult);
    $nodeProducts = $objDOM->getElementsByTagName('products');
    foreach($nodeProducts as $objNode){
        $strSku = $objNode->getElementsByTagName('sku')->item(0)->nodeValue;
        $strDesc = strtoupper($objNode->getElementsByTagName('description')->item(0)->nodeValue);
        $decPrice = $objNode->getElementsByTagName('price')->item(0)->nodeValue;
        $strSql = "INSERT INTO mrp_producto VALUES (0,'" . $strSku . "','" . $strDesc . "','" . $strDesc . "','" . $strDesc . "','" . $strDesc . "',NULL,NULL,1,0," . $intLine . ",1,1,'images/noimage.jpeg','" . $strSku . "',0," . $decPrice . ",0,0," . $intSupplier . "," . $decPrice . ",0,0," . $intUnit . ",8," . $intUnit . ",1," . $decPrice . ");";
        mysqli_query($objCon,$strSql);
        $intProduct = mysqli_insert_id($objCon);
        $strSql = "INSERT INTO mrp_producto_proveedor VALUES (0," . $intProduct . "," . $intSupplier . "," . $decPrice . "," . $intUnit . ",NULL);";
        mysqli_query($objCon,$strSql);
    }
    unset($objNode);
    unset($nodeProducts);
    $jsnData['intResult'] = 1;
    //$jsnData['strResult'] = "Se han agregado " . $objDOM->getElementsByTagName( "products" )->length . " pagos de servicios a su catalogo de productos<br /><br />Ya puede comenzar a vender servicios";
    $jsnData['strResult'] = "Ya puede comenzar a vender servicios";
    $jsnData['strUsr'] = $strUsr;
    $strMaskedPwd = "";
    for($intIx = 0; $intIx<strlen($strPwd)-4;$intIx++){
        $strMaskedPwd .= "*";
    }
    $strMaskedPwd .= substr($strPwd,strlen($strPwd)-4,strlen($strPwd));
    $jsnData['strPwd'] = $strMaskedPwd;
    unset($objDOM);
} else{
    $jsnData['intResult'] = 0;
    $jsnData['strResult'] = "Usuario y o Contraseña inválidos, por favor verifique.";
}
echo json_encode($jsnData);
mysqli_close($objCon);
unset($objCon);
?>
