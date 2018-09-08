<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');

$usuariobd  = "nmdevel"; 
$clavebd    = "nmdevel";

$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, "netwarstore");
mysqli_query($objCon, "SET NAMES 'utf8'");

$jsnData = array('blnFixedFee'=>'','decPrice'=>'','strReference'=>'','strRegex'=>'','strSku'=>'','strMethod'=>'');
//$strSql = "SELECT * FROM prontipagos_products WHERE intId = " . $_REQUEST['product'] . ";";
$strSql = "SELECT * FROM prontipagos_products WHERE intId = " . $_REQUEST['product'] . ";";

$rstProduct = mysqli_query($objCon,$strSql);
while($objProduct=mysqli_fetch_assoc($rstProduct)){
    $jsnData['blnFixedFee']   = $objProduct['blnFixedFee'];
    $jsnData['decPrice']      = $objProduct['decPrice'];
    $jsnData['strReference']  = $objProduct['strReference'];
    $jsnData['strRegex']      = $objProduct['strRegex'];
    $jsnData['strSku']        = $objProduct['strSku'];
    $jsnData['strMethod']     = $objProduct['strMethod'];
}
unset($objProduct);
mysqli_free_result($rstProduct);
unset($rstProduct);
echo json_encode($jsnData);
mysqli_close($objCon);
unset($objCon);
?>
