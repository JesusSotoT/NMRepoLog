<?php
include('../../netwarelog/webconfig.php');

$objCon = mysqli_connect($servidor,"nmdevel","nmdevel",$bd);
mysqli_set_charset($objCon,"utf8");

$strPwd = $_REQUEST['strPwd'];
$strPwd = crypt($strPwd,$accelog_salt);

$strResult = "NOF";

$strSql = "SELECT * FROM accelog_usuarios WHERE idempleado = 2 AND clave = '" . $strPwd . "';";
$rstPwd = mysqli_query($objCon, $strSql);
if(mysqli_num_rows($rstPwd)>0){
    $strResult = "OK";
}

mysqli_free_result($rstPwd);
unset($rstPwd);
mysqli_close($objCon);
unset($objCon);

echo $strResult;
?>