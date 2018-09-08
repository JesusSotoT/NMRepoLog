<?php
$banderaxmls = $bandera_cuentas_inexistentes = $bandera_cuentas_mayor = $bandera_segmentos = '';
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);
require(dirname(__FILE__)."/../../../netwarelog/webconfig.php");

$objCon = mysqli_connect($servidor,$usuariobd,$clavebd,$bd);


if($_FILES['polizas_xls']['name']){

require_once dirname(__FILE__).'/Excel/reader.php';

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('CP1251');
$data->read(dirname(__FILE__).'/polizas2.xls');

$arrRows = array();
$ConfCuentas = buscaConfCuentas($objCon);


for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)
{
    $Tipo = $data->sheets[0]["cells"][$i][1]; //Tipo registro
    if($Tipo=="P")
    {
        $strPoliza12 = $data->sheets[0]["cells"][$i][12]; //Facturas

        
        if($strPoliza12 != "" && $strPoliza12 != " ")
            {
                $strPoliza12 = explode(', ',$strPoliza12);
                $limite = count($strPoliza12);
                for($j=0;$j<=$limite-1;$j++)
                {
                    $dir = "xmls/facturas/temporales/*".$strPoliza12[$j]."*";
                    if(!$existefactura = glob($dir,GLOB_NOSORT))
                    {
                        $banderaxmls .= $strPoliza12[$j];
                    }

                }
            }

        //$contPoliza++;
    }

    if($Tipo=="M")
    {

        $strMovimiento01 = trim($data->sheets[0]["cells"][$i][6]); //Cuenta
        $strMovimiento06 = trim($data->sheets[0]["cells"][$i][12]); //UUID
        $strMovimiento07 = trim($data->sheets[0]["cells"][$i][11]); //IdSegmentoNegocio



        if($ConfCuentas == 'm')
        {
            $NumeroCuenta = maskAccount($strMovimiento01,$strMask, $strSeparator);
        }
        else
        {
            $NumeroCuenta = $strMovimiento01;
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
        else
        {
            $strSql = "SELECT main_account FROM cont_accounts WHERE manual_code = '" . $NumeroCuenta . "';";
            $rstAccId = mysqli_query($objCon, $strSql);
            while($objAccId = mysqli_fetch_row($rstAccId))
            {
                $TipoCuenta = $objAccId[0];
            }

            if(intval($TipoCuenta) != 3)
            {
                $bandera_cuentas_mayor .= $NumeroCuenta.", ";
            }

        }


        unset($objAccId);
        mysqli_free_result($rstAccId);
        unset($rstAccId);
        
        $factura = "";
        $IdSegmento = buscaIdSegmento($strMovimiento07,$objCon);

        if(!intval($IdSegmento))
        {
            $bandera_segmentos .= $strMovimiento07.", ";
        }
        
        //COMIENZA PROCESO QUE BUSCA EL UUID DEL MOVIMIENTO Y LO RELACIONA A UNA FACTURA
        //COPIA LA FACTURA y LA BORRA DE LA CARPETA TEMPORALES Y LO MANDA A LA CARPETA DE LA POLIZA
        
        if($strMovimiento06)
        {
            $dir = "xmls/facturas/temporales/*$strMovimiento06*";
            if(!$existefactura = glob($dir,GLOB_NOSORT))
            { 
                $banderaxmls .= $strMovimiento06.", ";
            }

        }
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

function buscaConfCuentas($objCon)
{
    $res = mysqli_query($objCon,"SELECT TipoNiveles FROM cont_config WHERE id = 1");
    $res = mysqli_fetch_assoc($res);
    return $res['TipoNiveles'];
}

