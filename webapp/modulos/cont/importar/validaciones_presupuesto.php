<?php
$bandera_cuentas_inexistentes = $bandera_segmentos = $bandera_sucursales = $bandera_suma = $bandera_repetidos = '';
$listaPresup = array('<br />');
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);
require(dirname(__FILE__)."/../../../netwarelog/webconfig.php");

$objCon = mysqli_connect($servidor,$usuariobd,$clavebd,$bd);


if($_FILES['presupuesto_xls']['name']){

require_once dirname(__FILE__).'/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/presupuesto.xls');

$arrRows = array();
$ConfCuentas = buscaConfCuentas($objCon);

$bandera_suma = 0;
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)
{
    
    
    if(trim($data->sheets[0]["cells"][$i][1]))
    {

        $str01 = trim($data->sheets[0]["cells"][$i][1]); //Cuenta
        $str02 = trim($data->sheets[0]["cells"][$i][2]); //idsegmento
        $str03 = trim($data->sheets[0]["cells"][$i][3]); //idsucursal
        $str04 = trim($data->sheets[0]["cells"][$i][4]); //Anual

        $enero = trim($data->sheets[0]["cells"][$i][5]); //enero
        $febrero = trim($data->sheets[0]["cells"][$i][6]); //febrero
        $marzo = trim($data->sheets[0]["cells"][$i][7]); //marzo
        $abril = trim($data->sheets[0]["cells"][$i][8]); //abril
        $mayo = trim($data->sheets[0]["cells"][$i][9]); //mayo
        $junio = trim($data->sheets[0]["cells"][$i][10]); //junio
        $julio = trim($data->sheets[0]["cells"][$i][11]); //julio
        $agosto = trim($data->sheets[0]["cells"][$i][12]); //agosto
        $septiembre = trim($data->sheets[0]["cells"][$i][13]); //septiembre
        $octubre = trim($data->sheets[0]["cells"][$i][14]); //octubre
        $noviembre = trim($data->sheets[0]["cells"][$i][15]); //noviembre
        $diciembre = trim($data->sheets[0]["cells"][$i][16]); //diciembre



        if($ConfCuentas == 'm')
        {
            $NumeroCuenta = maskAccount($str01,$strMask, $strSeparator);
        }
        else
        {
            $NumeroCuenta = $str01;
        }
        $strSql = "SELECT account_id FROM cont_accounts WHERE removed= 0 AND manual_code = '" . $NumeroCuenta . "';";
        $rstAccId = mysqli_query($objCon, $strSql);
        $Cuenta = 0;
        while($objAccId = mysqli_fetch_row($rstAccId))
        {
            $Cuenta = $objAccId[0];
        }

        if(!intval($Cuenta))
        {
            $bandera_cuentas_inexistentes .= $NumeroCuenta.", ";
        }
        


        unset($objAccId);
        mysqli_free_result($rstAccId);
        unset($rstAccId);
        
        
        $IdSegmento = buscaIdSegmento($str02,$objCon);

        if(!intval($IdSegmento))
        {
            $bandera_segmentos .= $str02.", ";
        }

        $IdSucursal = buscaIdSucursal($str03,$objCon);

        if(!intval($IdSucursal))
        {
            $bandera_sucursales .= $str03.", ";
        }
        
        $totalMeses = floatval($enero)+floatval($febrero)+floatval($marzo)+floatval($abril)+floatval($mayo)+floatval($junio)+floatval($julio)+floatval($agosto)+floatval($septiembre)+floatval($octubre)+floatval($noviembre)+floatval($diciembre);
        
        
            $bandera_suma += $totalMeses - floatval($str04);

        //Compara si hay segmentos existentes
        $presupCompara = "<tr><td>".$str01."*</td><td>".$str02."*</td><td>".$str03."</td></tr>";
        $contPres = 0;
        for($pr=0;$pr<=count($listaPresup)-1;$pr++)
        {
            if($presupCompara == $listaPresup[$pr])
            {
                $bandera_repetidos .= $presupCompara;
                $contPres++;
            }
        }

        if(!intval($contPres))
            array_push($listaPresup, $presupCompara);
        


    }
}


//######

mysqli_close($objCon);
unset($objCon);
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

function buscaIdSegmento($ClSeg,$objCon)
{
    $res = mysqli_query($objCon,"SELECT idSuc FROM cont_segmentos WHERE Clave = '$ClSeg'");
    $res = mysqli_fetch_assoc($res);
    return $res['idSuc'];
}

function buscaIdCuenta($ClCuenta,$objCon)
{
    $strSql = "SELECT account_id FROM cont_accounts WHERE manual_code = '" . $ClCuenta . "';";
        $rstAccId = mysqli_query($objCon, $strSql);
        $objAccId = mysqli_fetch_row($rstAccId);
            return $objAccId[0];
        
}

function buscaIdSucursal($ClSuc,$objCon)
{
    $res = mysqli_query($objCon,"SELECT idSuc FROM mrp_sucursal WHERE idSuc = $ClSuc");
    $res = mysqli_fetch_assoc($res);
    return $res['idSuc'];
}

function buscaConfCuentas($objCon)
{
    $res = mysqli_query($objCon,"SELECT TipoNiveles FROM cont_config WHERE id = 1");
    $res = mysqli_fetch_assoc($res);
    return $res['TipoNiveles'];
}

