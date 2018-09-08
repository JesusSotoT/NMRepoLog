<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');

$objCon = mysqli_connect($servidor,"nmdevel","nmdevel","nmdev_common");
mysqli_set_charset($objCon,"utf8");

$intOption=$_REQUEST['intOption'];

switch ($intOption) {
    case 1:
        $strSql = "SELECT DISTINCT(Estado) AS 'strAddress' FROM cat_direcciones WHERE Pais = '" . $_REQUEST['strCountry'] . "';";
        break;
    case 2:
        $strSql = "SELECT DISTINCT(Municipio) AS 'strAddress' FROM cat_direcciones WHERE Pais = '" . $_REQUEST['strCountry'] . "' AND Estado = '" . $_REQUEST['strState'] . "';";
        break;
    case 3:
        $strSql = "SELECT DISTINCT(Colonia) AS 'strAddress' FROM cat_direcciones WHERE Pais = '" . $_REQUEST['strCountry'] . "' AND Estado = '" . $_REQUEST['strState'] . "' AND Municipio = '" . $_REQUEST['strTown'] . "';";
        break;
    case 4:
        $strSql = "SELECT DISTINCT(CP) AS 'strAddress' FROM cat_direcciones WHERE Pais = '" . $_REQUEST['strCountry'] . "' AND Estado = '" . $_REQUEST['strState'] . "' AND Municipio = '" . $_REQUEST['strTown'] . "' AND Colonia = '" . $_REQUEST['strSettlement'] . "';";
        break;
    case 5:
        $strSql = "SELECT DISTINCT(Id) AS 'strAddress' FROM cat_direcciones WHERE Pais = '" . $_REQUEST['strCountry'] . "' AND Estado = '" . $_REQUEST['strState'] . "' AND Municipio = '" . $_REQUEST['strTown'] . "' AND Colonia = '" . $_REQUEST['strSettlement'] . "' AND CP = '" . $_REQUEST['strZipcode'] . "';";
        break;
};

$rstAddress = mysqli_query($objCon,$strSql);
while($objAddress = mysqli_fetch_assoc($rstAddress)){
    if($intOption!=5) {
        echo '<option id="' . $objAddress['strAddress'] . ' ">' . $objAddress['strAddress'] . '</option>';
    }else{
        echo $objAddress['strAddress'];
    }
};
unset($objAddress);
mysqli_free_result($rstAddress);
unset($rstAddress);

mysqli_close($objCon);
unset($objCon);
?>