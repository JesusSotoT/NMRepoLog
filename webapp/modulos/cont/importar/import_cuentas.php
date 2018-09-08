<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<?php

require(dirname(__FILE__)."/../../../netwarelog/webconfig.php");

$objCon = mysqli_connect($servidor,$usuariobd,$clavebd,$bd);
 
if ($_FILES['layout_cuentas']['name']){


require_once dirname(__FILE__).'/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/cuentas2.xls');

$dato = array();
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {

    $dato[1] = trim($data->sheets[0]["cells"][$i][1]); //A Tipo registro
    $dato[2] = trim($data->sheets[0]["cells"][$i][2]); //B Cuenta
    $dato[3] = trim($data->sheets[0]["cells"][$i][3]); //C Descripcion ESP
    $dato[4] = trim($data->sheets[0]["cells"][$i][4]); //D Descripcion ENG
    $dato[5] = trim($data->sheets[0]["cells"][$i][5]); //E Padre
    $dato[6] = trim($data->sheets[0]["cells"][$i][6]); //F Tipo Cuenta
    $dato[7] = trim($data->sheets[0]["cells"][$i][7]); //G Status
    $dato[8] = trim($data->sheets[0]["cells"][$i][8]); //H Mayor, Subcuenta, Argupadora
    $dato[9] = trim($data->sheets[0]["cells"][$i][9]); //I Fecha creacion
    $dato[10] = trim($data->sheets[0]["cells"][$i][10]); //J Digito Agrupador SAT
    $dato[11] = trim($data->sheets[0]["cells"][$i][11]); //K Moneda

    //quitar caracteres extraños.
    $dato[5] = preg_replace('([^A-Za-z0-9])', '', $dato[5]);
    $dato[2] = preg_replace('([^A-Za-z0-9])', '', $dato[2]);
    $dato[3] = str_replace("'", "", $dato[3]);
    $dato[3] = str_replace('"', "", $dato[3]);
    $dato[4] = str_replace("'", "", $dato[4]);
    $dato[4] = str_replace('"', "", $dato[4]);
    //$dato[10] = preg_replace('([^A-Za-z0-9])', '', $dato[10]);

    if($dato[1]=="C"){
        $manual_code = $dato[2];
        $father_manual_code = $dato[5];
        if($tipoNiveles == "manual_code")
        {
            $manual_code = maskAccount($dato[2],$strMask,$strSeparator);
            $father_manual_code = maskAccount($dato[5],$strMask,$strSeparator);
        }

        $father_account_id = father_account_id($father_manual_code,$objCon);
        $account_code = buscaAccountCode($father_account_id,$objCon);
        $naturaleza = getAccountNature($dato[6]);
        $maintype = getMain($dato[8]);
        $main_father = mainFather($father_account_id,$objCon);
        $main_account = explode('.',$account_code);
        $main_account = $main_account[0];
        $regdate = substr($dato[9],0,4) . "-" . substr($dato[9],4,2) . "-" . substr($dato[9],6,2);

        $af = 0;
        if(intval($main_account) == 3)
            $af = 1;

        $strSql = "INSERT INTO cont_accounts VALUES(0,'$account_code','$manual_code','$dato[3]','$dato[4]',".$main_account.",$dato[7],$maintype,0,'$regdate',".currencyId($dato[11],$objCon).",0,0,0,$af,'".date("Y-m-d")."',$father_account_id,1,$naturaleza,9,$main_father,'".diarioOficialId($dato[10],$objCon)."',0);";
        $id = mysqli_query($objCon,$strSql);

        //echo $strSql;---
        
        if(intval($maintype) == 1)
        {
            $id = mysql_insert_id($objCon);
            $strSql = "UPDATE cont_accounts SET main_father = $id WHERE account_id = $id";
            mysqli_query($objCon,$strSql);
        }
        
    }

};

unlink(dirname(__FILE__).'/cuentas2.xls');
mysqli_close($objCon);
}





function tipoPoliza($strType)
{
    switch ($strType){
        case 1:
            $poliza = 1;
            break;
        case 2:
            $poliza = 2;
            break;
        case 3:
            $poliza = 3;
            break;
    }
    return $poliza;
}

function getAffectable($strType){
    switch ($strType){
        case 1:
        case 3:
        case 4:
            $strAffectable = 0;
            break;
        case 2:
            $strAffectable = 1;
            break;
    }
    return $strAffectable;
}

function getMain($strType){
    switch ($strType){
        case 1:
            $strMain = 1;
            break;
        case 3:
        case 4:
            $strMain = 2;
            break;
        case 2:
            $strMain = 3;
            break;
    }
    return $strMain;
}

function maskAccount($strAccount,$strMask, $strSeparator){
//    global $strMask, $strSeparator;

    $arrMask = explode($strSeparator,$strMask);
    $intStart = 0;
    $strOut = "";
    for($intX=0; $intX<count($arrMask); $intX++){
        $strOut .= substr($strAccount,$intStart,strlen($arrMask[$intX])) . $strSeparator;
        $intStart = $intStart + strlen($arrMask[$intX]);
    }
    $strOut = substr($strOut,0,strlen($strOut)-1);
    return $strOut;
}

function createTable($objCon,$strTName){
    //global $strTBName;
    $strSql = "CREATE TABLE " . $strTName . " (
        id INT NOT NULL AUTO_INCREMENT,
        Field01 VARCHAR(31) NULL,
        Field02 VARCHAR(51) NULL,
        Field03 VARCHAR(51) NULL,
        Field04 VARCHAR(31) NULL,
        Field05 VARCHAR(10) NULL,
        Field06 VARCHAR(10) NULL,
        Field07 VARCHAR(10) NULL,
        Field08 VARCHAR(10) NULL,
        Field09 VARCHAR(10) NULL,
        Field10 VARCHAR(10) NULL,
        Field11 VARCHAR(10) NULL,
        Field12 VARCHAR(10) NULL,
        Field13 VARCHAR(10) NULL,
        Field14 VARCHAR(10) NULL,
        PRIMARY KEY (id));";

    mysqli_query($objCon,$strSql);
}

function getSons($strFather, $strLevel, $strAccType,$strTName,$strMask,$strSeparator,$objCon){
//    global $objCon;
    $strSql = "SELECT COUNT(*) FROM " . $strTName . " WHERE Field04 = '" . $strFather . "' ORDER BY Field01;";

    $rstCount = mysqli_query($objCon, $strSql);

//var_dump($rstCount);

    while($objCount = mysqli_fetch_row($rstCount)){
        if($objCount[0]!=0){
            $strSql = "SELECT * FROM " . $strTName . " WHERE Field04 = '" . $strFather . "' ORDER BY Field01;";
            $rstAccounts = mysqli_query($objCon, $strSql);
            $intCount = 0;
            while ($objAccounts = mysqli_fetch_row($rstAccounts)){
                $intCount++;
                $strAccountCode = $strLevel . $intCount;
                $strManualCode = maskAccount($objAccounts[1],$strMask,$strSeparator);
                $strDescription = $objAccounts[2];
                $strSec_Desc = $objAccounts[3];
                $strAccount_Type = $strAccType;
                $strStatus = $objAccounts[6];
                $strMain_Account = getMain($objAccounts[7]);
                $strCash_Flow = 0;
                #$strReg_Date = substr($objAccounts[9],0,4) . "-" . substr($objAccounts[9],4,2) . "-" . substr($objAccounts[9],6,2);
                $strReg_Date = date("Y-m-d");
                $strCurrency_Id = currencyId($objAccounts[12],$objCon);
                $strGroup_Dig = 0;
                $strId_Sucursal = 0;
                $strSeg_Neg_Mov = 0;
                $strAffectable = getAffectable($objAccounts[7]);
                $strMod_Date = date("Y-m-d");
                $strSql = "SELECT account_id FROM cont_accounts WHERE manual_code = '" . maskAccount($strFather,$strMask,$strSeparator) . "';";
                $rstFather = mysqli_query($objCon,$strSql);
                while($objFather = mysqli_fetch_row($rstFather)) {
                    $strFather_Account_Id = $objFather[0];
                }
                unset($objFather);
                mysqli_free_result($rstFather);
                unset($rstFather);
                $strRemovable = 1;
                $strAccount_Nature = getAccountNature($objAccounts[5]);
                $strRemoved = 0;
                $strMain_Father = 0;
                $strCuentaOficial = diarioOficialId($objAccounts[11],$objCon);

                $strSql = "INSERT INTO cont_accounts ";
                $strSql .= "VALUES(0,";
                $strSql .= "'" . $strAccountCode . "',";
                $strSql .= "'" . $strManualCode . "',";
                $strSql .= "'" . $strDescription . "',";
                $strSql .= "'" . $strSec_Desc . "',";
                $strSql .= "" . $strAccount_Type . ",";
                $strSql .= "" . $strStatus . ",";
                $strSql .= "" . $strMain_Account . ",";
                $strSql .= "" . $strCash_Flow . ",";
                $strSql .= "'" . $strReg_Date . "',";
                $strSql .= "" . $strCurrency_Id . ",";
                $strSql .= "" . $strGroup_Dig . ",";
                $strSql .= "" . $strId_Sucursal . ",";
                $strSql .= "" . $strSeg_Neg_Mov . ",";
                $strSql .= "" . $strAffectable . ",";
                $strSql .= "'" . $strMod_Date . "',";
                $strSql .= "" . $strFather_Account_Id . ",";
                $strSql .= "" . $strRemovable . ",";
                $strSql .= "" . $strAccount_Nature . ",";
                $strSql .= "" . $strRemoved . ",";
                $strSql .= "" . $strMain_Father . ",";
                $strSql .= "'" . $strCuentaOficial . "',0);";

                mysqli_query($objCon,$strSql);

                $strSql = "SELECT MAX(account_id) FROM cont_accounts;";
                $rstAccount_Id = mysqli_query($objCon,$strSql);
                while($objAccount_Id = mysqli_fetch_row($rstAccount_Id)) {
                    $strAccount_Id = $objAccount_Id[0];
                }
                unset($objAccount_Id);
                mysqli_free_result($rstAccount_Id);
                unset($rstAccount_Id);
                switch ($strMain_Account){
                
                    case 1:
                        $strMain_Father = $strAccount_Id;
                        break;
                    DEFAULT:
                        $strSql = "SELECT main_father FROM cont_accounts WHERE account_id = $strFather_Account_Id";
                        $rstMain_Father = mysqli_query($objCon,$strSql);
                        while($objMain_Father = mysqli_fetch_row($rstMain_Father)) {
                            $strMain_Father = $objMain_Father[0];
                        }
                        unset($objMain_Father);
                        mysqli_free_result($rstMain_Father);
                        unset($rstMain_Father);
                        break;
                }
                $strSql = "UPDATE cont_accounts SET main_father = " . $strMain_Father . " WHERE account_id = " . $strAccount_Id . ";";
                mysqli_query($objCon,$strSql);

                getSons($objAccounts[1], $strLevel . $intCount . ".", $strAccType,$strTName,$strMask,$strSeparator,$objCon);
            }
            mysqli_free_result($rstAccounts);
            unset($rstAccounts);
        }
    }
    unset($objCount);
    mysqli_free_result($rstCount);
    unset($rstCount);
}




function getAccountId($strAccountType){
    switch ($strAccountType){
        case 'A';
        case 'B':
            $strAccountId = '1';
            break;
        case 'C':
        case 'D':
            $strAccountId = '2';
            break;
        case 'E':
        case 'F':
            $strAccountId = '3';
            break;
        case 'G':
            $strAccountId = '4.2';
            break;
        case 'H':
            $strAccountId = '4.1';
            break;
        case 'K':
            $strAccountId = '5.1';
            break;
        case 'L':
            $strAccountId = '5.2';
            break;
        case 'I':
            $strAccountId = '7';
            break;
        case 'J':
            $strAccountId = '6';
            break;
    }
    return $strAccountId;
};

function getAccountNature($strAccountType){
    switch ($strAccountType){
        case 'B':
        case 'D':
        case 'F':
        case 'H':
        case 'J':
        case 'L':
            $strAccountNature = '1';
            break;
        case 'A':
        case 'E':
        case 'G':
        case 'I':
        case 'K':
            $strAccountNature = '2';
            break;
    }
    return $strAccountNature;
};

function diarioOficialId($IdOf,$objCon)
{
    $myQuery = "SELECT id FROM cont_diarioficial WHERE codigo_agrupador = '$IdOf'";
    $res = mysqli_query($objCon,$myQuery);
    $res = mysqli_fetch_assoc($res);
    return $res['id'];
};

function currencyId($IdCur,$objCon)
{
    $myQuery = "SELECT coin_id FROM cont_coin WHERE codigo = '$IdCur'";
    $res = mysqli_query($objCon,$myQuery);
    $res = mysqli_fetch_assoc($res);
    return $res['coin_id'];
}

function buscaTasa($objCon,$Tasa,$Prov)
{
    
    if($Tasa == 'E')
    {
        $where = "tasa = 'Exenta'";
    }elseif($Tasa == '0')
    {
        $where = "tasa = '0%'";
    }
    else
    {
        $where = "valor = $Tasa";
    }

    $res = mysqli_query($objCon,"SELECT id FROM cont_tasaPrv WHERE idPrv = $Prov AND $where");
    $res = mysqli_fetch_assoc($res);
    return $res['id'];
}
function buscaEjercicio($objCon,$ej)
{
    $res = mysqli_query($objCon,"SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = '$ej'");
    $res = mysqli_fetch_assoc($res);
    return $res['Id'];
}

function buscaIdProv($RFC,$objCon)
{
    $res = mysqli_query($objCon,"SELECT idPrv FROM mrp_proveedor WHERE rfc = '$RFC'");
    $res = mysqli_fetch_assoc($res);
    return $res['idPrv'];
}

function buscaIdSegmento($ClSeg,$objCon)
{
    $res = mysqli_query($objCon,"SELECT idSuc FROM cont_segmentos WHERE Clave = '$ClSeg'");
    $res = mysqli_fetch_assoc($res);
    return $res['idSuc'];
}

function father_account_id($padre,$objCon)
{
    $res = mysqli_query($objCon,"SELECT account_id FROM cont_accounts WHERE manual_code='$padre' AND removed = 0");
    $res = mysqli_fetch_assoc($res);
    return $res['account_id'];
}
function buscaAccountCode($padre,$objCon)
{
    $res = mysqli_query($objCon,"SELECT CONVERT(SUBSTRING_INDEX(account_code, '.', -1), UNSIGNED INTEGER) AS ultima FROM cont_accounts WHERE father_account_id = $padre AND (removed = 0 OR removed = 9) ORDER BY ultima DESC LIMIT 1");
    $res = mysqli_fetch_assoc($res);
    $padre_code = mysqli_query($objCon,"SELECT account_code FROM cont_accounts WHERE account_id = $padre AND removed = 0");
    $padre_code = mysqli_fetch_assoc($padre_code);
    if(intval($res['ultima']))
    {
        $siguiente = intval($res['ultima'])+1;
        return $padre_code['account_code'].".".$siguiente;

    }
    else
    {
        return $padre_code['account_code'].".1";
    }
}
function mainFather($padre,$objCon)
{
        $res = mysqli_query($objCon,"SELECT main_father FROM cont_accounts WHERE account_id = '$padre' AND removed = 0");
        $res = mysqli_fetch_assoc($res);
        return $res['main_father'];
}
?>
</body>
</html>