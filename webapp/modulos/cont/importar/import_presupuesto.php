<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);


$objCon = mysqli_connect($servidor,$usuariobd,$clavebd,$bd);


if($_FILES['presupuesto_xls']['name']){

$arrRows = array();
$ConfCuentas = buscaConfCuentas($objCon);
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)
{
    if(trim($data->sheets[0]["cells"][$i][1]))
    {

        $strPres01 = trim($data->sheets[0]["cells"][$i][1]); //Cuenta
        $strPres02 = trim($data->sheets[0]["cells"][$i][2]); //Segmento
        $strPres03 = trim($data->sheets[0]["cells"][$i][3]); //Sucursal
        $strPres04 = trim($data->sheets[0]["cells"][$i][4]); //Anual

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
            $NumeroCuenta = maskAccount($strPres01,$strMask, $strSeparator);
        }
        else
        {
            $NumeroCuenta = $strPres01;
        }
        $Cuenta = buscaIdCuenta($NumeroCuenta,$objCon);

        $cadena = $enero."|".$febrero."|".$marzo."|".$abril."|".$mayo."|".$junio."|".$julio."|".$agosto."|".$septiembre."|".$octubre."|".$noviembre."|".$diciembre;
        

        $strSql = "INSERT INTO cont_presupuestos (id,ejercicio,cuenta,segmento,sucursal,anual,meses,activo) VALUES (0," . $_POST['xls_ejercicio'] . "," . $Cuenta . "," . buscaIdSegmento($strPres02,$objCon) . "," . $strPres03 . ","  . $strPres04 . ",'" . $cadena . "',1);";
        //echo $strSql;
        mysqli_query($objCon,$strSql);
        

    
    
    }
}


//######

mysqli_close($objCon);
unset($objCon);
}

