<?php
include_once("../../netwarelog/catalog/conexionbd.php");
$idempleado = $_SESSION["accelog_idempleado"]; //$catalog_id_utilizado

$menus=$_GET["ms"];
$arrMenus=explode(",",$menus);


if (count($arrMenus)>0){
  //Eliminando Registros
  $strSql="delete from dashboard_contenido where idempleado=$idempleado";
  $conexion->consultar($strSql);
  //Agregando Nuevos
  $strSql="Insert Into dashboard_contenido (idtipo,idmenu,idempleado) VALUES ";
  for($i=0;$i<count($arrMenus);$i++) {
    $strSql.="(1,".$arrMenus[$i].",$idempleado),";
  }
  $strSql=substr($strSql, 0, -1);
  $conexion->consultar($strSql);
}

header('Location: index.php');
?>
