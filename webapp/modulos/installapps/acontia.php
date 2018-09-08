<?php
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor,"nmdevel","nmdevel","netwarstore");
mysqli_set_charset($objCon,"utf8");
$strActKey = $_REQUEST['strActKey'];
$strApp = $_REQUEST['strApp'];
$strResult = "OK";
if($strActKey!=''){
    $strSql = "SELECT * FROM codigos WHERE codigo = '" . $strActKey . "' AND estatus = 0 AND salesman = '" . $strApp . "';";
    $rstInstance = mysqli_query($objCon,$strSql);
    if(mysqli_num_rows($rstInstance)==0){
        $strResult = "--NOF1--";
    }else{
        $strSql = "UPDATE codigos SET estatus = 1 WHERE codigo = '" . $strActKey . "' AND estatus = 0 AND salesman = '" . $strApp . "';";
        mysqli_query($objCon,$strSql);
    }
    mysqli_free_result($rstInstance);
    unset($rstInstance);
}
if($strResult=="OK"){
    $strDBInst = $bd;
    $strSql = "SELECT id,idclient FROM customer WHERE nombre_db = '" . $strDBInst . "';";
    $rstInstance = mysqli_query($objCon,$strSql);
    if(mysqli_num_rows($rstInstance)==0){
        $strResult = "--NOF2--";
        if($strActKey!=''){
            $strSql = "UPDATE codigos SET estatus = 0 WHERE codigo = '" . $strActKey . "' AND estatus = 0 AND salesman = '" . $strApp . "';";
            mysqli_query($objCon,$strSql);
        }
    }else{
        $strSql = "INSERT INTO appclient (idclient,idapp,idcustomer,initdate,agreement,idstatus,installkey,limitdate,activ_pend) VALUES(";
        $objInst = mysqli_fetch_assoc($rstInstance);
        $strSql .= $objInst['idclient'] . ",";
        $strSql .= "'" . $strApp . "',";
        $strSql .= $objInst['id'] . ",";
        unset($objInst);
        $strSql .= "'" . date('Y-m-d') . "',";
        $strSql .= 1 . ",";
        $strSql .= 1 . ",";
        if($strActKey!=''){
            $strSql .= "'" . $strActKey . "',";
            $strSql .= "'" . date('Y-m-d', strtotime('+365 days')) . "',";
            $strSql .= 1;
        }else{
            $strSql .= "'',";
            $strSql .= "'" . date('Y-m-d', strtotime('+30 days')) . "',";
            $strSql .= 0;
        };
        $strSql .= ");";
        mysqli_query($objCon,$strSql);
    }
    mysqli_free_result($rstInstance);
    unset($rstInstance);
}
if($strResult=="OK") {
    mysqli_select_db($objCon, $bd);
    $strSqlFile = file_get_contents('../../../../../netwarstore/sqlfile/conta.sql');
    mysqli_query($objCon,$strSqlFile);
}
mysqli_close($objCon);
unset($objCon);
echo $strResult;
?>