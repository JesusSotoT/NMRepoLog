<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
mysqli_query($objCon, "SET NAMES 'utf8'");

$strProfileMenus = $_REQUEST['strProfileMenus'];
$strAction = $_REQUEST['strAction'];
$strProfile = $_REQUEST['strProfile'];

switch($strAction){
    case -1:
        $strSql = "DELETE FROM accelog_perfiles WHERE idperfil = " . $strProfile . ";";
        mysqli_query($objCon, $strSql);
        $strSql = "DELETE FROM accelog_perfiles_me WHERE idperfil = " . $strProfile . ";";
        mysqli_query($objCon, $strSql);
        echo "DEL";
        break;
    case 0:
        $strSql = "INSERT INTO accelog_perfiles (nombre, visible) VALUES ('" . $strProfile . "',-1);";
        mysqli_query($objCon, $strSql);
        $intId = mysqli_insert_id($objCon);
        $arrMenus = explode("|",substr($strProfileMenus,0,strlen($strProfileMenus) - 1));
        for($intIx=0;$intIx<count($arrMenus);$intIx++){
            $strSql = "INSERT INTO accelog_perfiles_me VALUES (" . $intId . "," . $arrMenus[$intIx] . ")";
            mysqli_query($objCon, $strSql);
        }
        echo "INS";
        break;
    default:
        $strSql = "UPDATE accelog_perfiles SET nombre = '" . $strProfile . "' WHERE idperfil = " . $strAction . ";";
        mysqli_query($objCon, $strSql);
        $strSql = "DELETE FROM accelog_perfiles_me WHERE idperfil = " . $strAction . ";";
        mysqli_query($objCon, $strSql);
        $arrMenus = explode("|",substr($strProfileMenus,0,strlen($strProfileMenus) - 1));
        for($intIx=0;$intIx<count($arrMenus);$intIx++){
            $strSql = "INSERT INTO accelog_perfiles_me VALUES (" . $strAction . "," . $arrMenus[$intIx] . ")";
            mysqli_query($objCon, $strSql);
        }
        echo "UPD";
        break;
}
mysqli_close($objCon);
unset($objCon);
?>
