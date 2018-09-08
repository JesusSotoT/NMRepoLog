<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
mysqli_query($objCon, "SET NAMES 'utf8'");

$intProfile = $_REQUEST['intProfile'];

$jsnData = array('strProfile'=>'','arrMenus'=>array());

$strSql = "SELECT nombre FROM accelog_perfiles WHERE idperfil = " . $intProfile . ";";
$rstProfile = mysqli_query($objCon, $strSql);
while($objProfile=mysqli_fetch_assoc($rstProfile)){
    $jsnData['strProfile']=$objProfile['nombre'];
}
unset($objProfile);
mysqli_free_result($rstProfile);
unset($rstProfile);

$strSql = "SELECT idmenu FROM accelog_perfiles_me WHERE idperfil = " . $intProfile . " ORDER BY idmenu;";
$rstMenus = mysqli_query($objCon,$strSql);
while($objMenus=mysqli_fetch_assoc($rstMenus)){
    array_push($jsnData['arrMenus'],array('intMenu'=>$objMenus['idmenu']));
}
unset($objMenus);
mysqli_free_result($rstMenus);
unset($rstMenus);

echo json_encode($jsnData);

mysqli_close($objCon);
unset($objCon);
?>
