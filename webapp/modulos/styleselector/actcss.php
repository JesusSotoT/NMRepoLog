<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
mysqli_query($objCon, "SET NAMES 'utf8'");

$intIdEmpleado = $_REQUEST['idempleado'];
$strCss = $_REQUEST['css'];

$strSql = "UPDATE accelog_usuarios SET css = '" . $strCss . "' WHERE idempleado = " . $intIdEmpleado . ";";
mysqli_query($objCon,$strSql);

echo "OK";

mysqli_close($objCon);
unset($objCon);
?>
