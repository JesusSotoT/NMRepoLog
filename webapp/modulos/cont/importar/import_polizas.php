<?php
ini_set("display_errors",0);
ini_set('memory_limit', '-1');
//error_reporting(E_WARNING);


$objCon = mysqli_connect($servidor,$usuariobd,$clavebd,$bd);


if($_FILES['polizas_xls']['name']){

$arrRows = array();
$ConfCuentas = buscaConfCuentas($objCon);

for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++)
{
    $Tipo = $data->sheets[0]["cells"][$i][1]; //Tipo registro
    if($Tipo=="P")
    {
        $strPoliza01 = trim($data->sheets[0]["cells"][$i][2]); //Fecha
        $strPoliza02 = trim($data->sheets[0]["cells"][$i][3]); //Tipo Poliza
        $strPoliza03 = trim($data->sheets[0]["cells"][$i][5]); //Concepto
        $strPoliza03 = str_replace('"', "", $strPoliza03);
        $strPoliza03 = str_replace("'", "", $strPoliza03);
        $strPoliza04 = trim($data->sheets[0]["cells"][$i][4]); //NumPol
        $strPoliza12 = $data->sheets[0]["cells"][$i][12]; //Facturas


        $Organizacion = 1;
        $Ejercicio = 0;
        $strSql = "SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = " . (int)substr($strPoliza01,0,4) . ";";
        $rstEjercicio = mysqli_query($objCon, $strSql);
        while ($objEjercicio = mysqli_fetch_row($rstEjercicio)){
            $Ejercicio = $objEjercicio[0];
        };
        unset($objEjercicio);
        mysqli_free_result($rstEjercicio);
        unset($rstEjercicio);
        $Periodo = (int)substr($strPoliza01,4,2);
        if($Periodo != $intPeriodoPrev){
            $intPeriodoPrev = $Periodo;
            //$contPoliza = 1;
        }
        //$numPol = $contPoliza;
        $numPol = $strPoliza04;
        $TipoPoliza = $strPoliza02;
        $Referencia = "---";
        $Concepto = $strPoliza03;
        $Cargos = "";
        $Abonos = "";
        $Ajuste = "";
        $Fecha = substr($strPoliza01,0,4) . "-" . substr($strPoliza01,4,2) . "-" . substr($strPoliza01,6,2);
        $TimeStamp = date("Y-m-d");
        $Activo = 1;
        $Eliminado = 0;
        $pdv_aut = 0;
        $strSql = "INSERT INTO cont_polizas (id,idorganizacion,idejercicio,idperiodo,numpol,idtipopoliza,referencia,concepto,origen,idorigen,ajuste,fecha,fecha_creacion,activo,eliminado,pdv_aut,relacionExt,beneficiario,numero,rfc,idbanco,numtarjcuent) VALUES (0," . $Organizacion . "," . $Ejercicio . "," . $Periodo . "," . $numPol . ","  . $TipoPoliza . ",\"$Referencia\",\"$Concepto\",'" . $Cargos . "','" . $Abonos . "','" . $Ajuste . "','" . $Fecha . "', '" . $TimeStamp . "', " . $Activo . "," . $Eliminado ."," . $pdv_aut . ",0,0,0,NULL,0,0);";
        
        if(intval($Ejercicio))//Si existe el ejercicio
        {
            mysqli_query($objCon,$strSql);
            $contMovimiento = 1;
            $contPoliza = mysqli_insert_id($objCon);
            
            if($strPoliza12 != "" && $strPoliza12 != " ")
                {
                    $strPoliza12 = explode(', ',$strPoliza12);
                    $limite = count($strPoliza12);
                    for($j=0;$j<=$limite-1;$j++)
                    {
                        $dir = "xmls/facturas/temporales/*".$strPoliza12[$j]."*";
                        if($existefactura = glob($dir,GLOB_NOSORT))
                        {
                            $factura = explode('temporales/',$existefactura[0]);
                            $newdir = "xmls/facturas/$contPoliza";

                            if(!file_exists($newdir))
                            {
                                mkdir($newdir, 0777);
                            }
                            copy($existefactura[0],"$newdir/".$factura[1]);
                            //unlink($existefactura[0]);
                        }
                    }
                }
        }

        //$contPoliza++;
    }

    if($Tipo=="M" && intval($Ejercicio))
    {
        $strMovimiento01 = trim($data->sheets[0]["cells"][$i][6]); //Cuenta
        $strMovimiento02 = trim($data->sheets[0]["cells"][$i][7]); //Referencia
        $strMovimiento02 = str_replace('"', "", $strMovimiento02);
        $strMovimiento02 = str_replace("'", "", $strMovimiento02);
        $strMovimiento03 = strtoupper(trim($data->sheets[0]["cells"][$i][8])); //Tipo Movimiento
        $strMovimiento04 = str_replace(",","",trim($data->sheets[0]["cells"][$i][9])); //Importe
        $strMovimiento05 = trim($data->sheets[0]["cells"][$i][10]); //Concepto
        $strMovimiento05 = str_replace('"', "", $strMovimiento05);
        $strMovimiento05 = str_replace("'", "", $strMovimiento05);
        $strMovimiento06 = trim($data->sheets[0]["cells"][$i][12]); //UUID
        $strMovimiento07 = trim($data->sheets[0]["cells"][$i][11]); //IdSegmentoNegocio

        $idPoliza = $contPoliza;
        $NumMovto = $contMovimiento;
        $idSucursal = 1;

        if($ConfCuentas == 'm')
        {
            $NumeroCuenta = maskAccount($strMovimiento01,$strMask, $strSeparator);
        }
        else
        {
            $NumeroCuenta = $strMovimiento01;
        }
        $strSql = "SELECT account_id FROM cont_accounts WHERE manual_code = '" . $NumeroCuenta . "';";
        $rstAccId = mysqli_query($objCon, $strSql);
        while($objAccId = mysqli_fetch_row($rstAccId)){
            $Cuenta = $objAccId[0];
        }
        unset($objAccId);
        mysqli_free_result($rstAccId);
        unset($rstAccId);

        $TipoMovto = tipoMovto($strMovimiento03);
        $Importe = $strMovimiento04;

        
        
        $Concepto = "-".$strMovimiento05;
        $Referencia = $strMovimiento02; 
        $Activo = 1;
        $Fecha = date("Y-m-d");
        $factura = "";
        $Persona = "1-";
        $IdSegmento = buscaIdSegmento($strMovimiento07,$objCon);
        
        //COMIENZA PROCESO QUE BUSCA EL UUID DEL MOVIMIENTO Y LO RELACIONA A UNA FACTURA
        //COPIA LA FACTURA y LA BORRA DE LA CARPETA TEMPORALES Y LO MANDA A LA CARPETA DE LA POLIZA
        
        if($strMovimiento06)
        {
            $dir = "xmls/facturas/temporales/*$strMovimiento06*";
            if($existefactura = glob($dir,GLOB_NOSORT))
            {
                $Referencia = $strMovimiento06;    

                $factura = explode('temporales/',$existefactura[0]);
                $newdir = "xmls/facturas/$idPoliza";

                if(!file_exists($newdir))
                {
                    mkdir($newdir, 0777);
                }
                copy($existefactura[0],"$newdir/".$factura[1]);
                //unlink($existefactura[0]);
                $factura = $factura[1];
            }

        }

        //TERMINA PROCESO DEL UUID-FACTURA
        
        $strSql = "INSERT INTO cont_movimientos (id,idPoliza,NumMovto,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona,FormaPago,IdSegmento) ";
        $strSql .= "VALUES (0," . $idPoliza . "," . $NumMovto . "," . $idSucursal . "," . $Cuenta . ",'"  . $TipoMovto . "'," . $Importe . ",\"$Referencia\",\"$Concepto\", " . $Activo . ", '" . $Fecha . "', '" . $factura . "', '" . $Persona . "',0,$IdSegmento);";
        
        mysqli_query($objCon,$strSql);
        $contMovimiento++;
    }

    if($Tipo=="PA" && intval($Ejercicio))
    {
        $idPoliza;
        $strPagos01 = trim($data->sheets[0]["cells"][$i][2]); //Folio
        $strPagos03 = trim($data->sheets[0]["cells"][$i][3]); //Cuenta Origen
        $strPagos04 = trim($data->sheets[0]["cells"][$i][4]); //Cuenta Destino
        $strPagos05 = trim($data->sheets[0]["cells"][$i][5]); //Banco Destino Nacional

        if($r = buscaIdProv(trim($data->sheets[0]["cells"][$i][6]),$objCon))//IDProvBeneficiario
        {
            $strPagos07 = $r;
        }
        else
        {
            $strPagos07 = 0;
        }
        $strPagos08 = trim($data->sheets[0]["cells"][$i][6]); //RFC BENEFICIARIO
        $strPagos10 = trim($data->sheets[0]["cells"][$i][7]); //IdTipoPago

        if($strPagos05 == '' || $strPagos05 == '0') $strPagos05 == '99';

        $strSql = "UPDATE cont_polizas SET 
        beneficiario    = $strPagos07, 
        numero          = '$strPagos01',  
        rfc             = '$strPagos08',  
        idbanco         = (SELECT idbanco FROM cont_bancos WHERE Clave = '$strPagos05'),  
        numtarjcuent    = '$strPagos04',  
        idCuentaBancariaOrigen = $strPagos03
        WHERE id = $idPoliza;";

        mysqli_query($objCon,$strSql);

        $strSql = "UPDATE cont_movimientos SET FormaPago = (SELECT idFormapago FROM forma_pago WHERE claveSat = ".sprintf('%02d', (intval($strPagos10))).") WHERE IdPoliza = $idPoliza;";

        mysqli_query($objCon,$strSql);
    }

    /*if($Tipo=="RP")
    {
        $strRP01 = buscaIdProv(trim($data->sheets[0]["cells"][$i][2]),$objCon); //IdProveedor
        $strRP02 = trim($data->sheets[0]["cells"][$i][3]); //Referencia
        $strRP03 = buscaTasa($objCon,trim($data->sheets[0]["cells"][$i][4]),$strRP01); //Tasa
        $strRP04 = trim($data->sheets[0]["cells"][$i][5]); //Importe
        $strRP05 = trim($data->sheets[0]["cells"][$i][6]); //Importe Base
        $strRP06 = trim($data->sheets[0]["cells"][$i][7]); //Otras Erogaciones
        $strRP07 = trim($data->sheets[0]["cells"][$i][8]); //IVA Retenido
        $strRP08 = trim($data->sheets[0]["cells"][$i][9]); //ISR Retenido
        $strRP09 = trim($data->sheets[0]["cells"][$i][10]); //Iva Pagado No Acreditable
        $strRP11 = trim($data->sheets[0]["cells"][$i][12]); //Aplica
        $strRP12 = trim($data->sheets[0]["cells"][$i][13]); //Periodo Acreditamiento
        $strRP13 = buscaEjercicio($objCon,trim($data->sheets[0]["cells"][$i][14])); //Ejercicio Acreditamiento
        $strRP14 = trim($data->sheets[0]["cells"][$i][15]); //Acreditable IETU
        $strRP15 = trim($data->sheets[0]["cells"][$i][16]); //Id IETU

        $strSql = "INSERT INTO cont_rel_pol_prov VALUES (0,$idPoliza,$strRP01,'$strRP02',$strRP03,$strRP04,$strRP05,$strRP06,$strRP07,$strRP08,$strRP09,$strRP11,1,$strRP12,$strRP13,$strRP15,$strRP14);";

        mysqli_query($objCon,$strSql);
    }

    if($Tipo=="DI")
    {
        $strDI01 = buscaIdProv(trim($data->sheets[0]["cells"][$i][2]),$objCon); //IdProveedor
        $strDI02 = trim($data->sheets[0]["cells"][$i][3]); //Referencia
        $strDI03 = buscaTasa($objCon,trim($data->sheets[0]["cells"][$i][4]),$strRP01); //Tasa
        $strDI04 = trim($data->sheets[0]["cells"][$i][5]); //Importe
        $strDI05 = trim($data->sheets[0]["cells"][$i][6]); //Importe Base
        $strDI06 = trim($data->sheets[0]["cells"][$i][7]); //Otras Erogaciones
        $strDI07 = trim($data->sheets[0]["cells"][$i][8]); //IVA Retenido
        $strDI08 = trim($data->sheets[0]["cells"][$i][9]); //ISR Retenido
        $strDI09 = trim($data->sheets[0]["cells"][$i][10]); //Iva Pagado No Acreditable
        $strDI11 = trim($data->sheets[0]["cells"][$i][12]); //Aplica
        $strDI12 = trim($data->sheets[0]["cells"][$i][13]); //Periodo Acreditamiento
        $strDI13 = buscaEjercicio($objCon,trim($data->sheets[0]["cells"][$i][14])); //Ejercicio Acreditamiento
        $strDI14 = trim($data->sheets[0]["cells"][$i][15]); //Acreditable IETU
        $strDI15 = trim($data->sheets[0]["cells"][$i][16]); //Id IETU

        $strSql = "INSERT INTO cont_rel_desglose_iva VALUES (0,$idPoliza,);";

        mysqli_query($objCon,$strSql);
    }*/
}

$files = glob('xmls/facturas/temporales/*'); // get all file names
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}

//######

mysqli_close($objCon);
unset($objCon);
}

function tipoMovto($strType)
{
    switch ($strType){
        case 'C':
            $tipo = "Cargo";
            break;
        case 'A':
            $tipo = "Abono";
            break;
    }
    return $tipo;
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
                    case 2:
                        $strMain_Father = 0;
                        break;
                    case 1:
                        $strMain_Father = $strAccount_Id;
                        break;
                    case 3:
                        $strSql = "SELECT * FROM cont_accounts WHERE main_account = 1 ORDER BY 1 DESC LIMIT 1;";
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
        case 'D':
        case 'E':
            $strAccountId = '2';
            break;
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

