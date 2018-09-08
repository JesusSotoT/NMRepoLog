<?php

if(isset($proviene_xtructur)){
    require_once ('../../modulos/wsinvoice/config.php');
    require_once ('../../modulos/wsinvoice/lib/nusoap.php');
    require_once ('../../modulos/wsinvoice/lib/fpdf.php');
    require_once ('../../modulos/wsinvoice/lib/QRcode.php');
    require_once ('../../modulos/wsinvoice/class.invoice.pdf.php');

}else{
    if(!isset($llamada_api)){
        require_once ('config.php');
        require_once ('lib/nusoap.php');
    }
    else
        require_once ('config_api.php');
    require_once ('lib/fpdf.php');
    require_once ('lib/QRcode.php');
    require_once ('class.invoice.pdf.php');

}


/*
$strFile = "CSD01_AAA010101AAA.xml";
$strXML = "";
$objFile = fopen($strFile, "r");
$strXML = base64_encode(fread($objFile,filesize($strFile)));
fclose($objFile);
unset($objFile);


echo date('Y-m-d h:i:s') . "<br /><br />";

$arrInvoice = sealInvoice($strXML,23,102,161);
var_dump($arrInvoice);
unset($arrInvoice);

echo "<br /><br />" . date('Y-m-d h:i:s');
*/


//****Desarrollo****
//Clave: 12345678a
//FC_User: pruebasWS
//FC_Pass: pruebasWS

//****Produccion****
//Clave: H4G4G2015
//FC_User: IHA03MAS
//FC_Pass: 06rg1491*

//echo $XML;


$strXML = base64_encode($XML);
$arrInvoice = sealInvoice($strXML,23,102,161,$idVenta,$idFact,$azurian,$ws);
    if(!isset($llamada_api)){
       
		if(!$nominas){
			echo json_encode($arrInvoice);
			exit();
			
		}
        
        //return;
    }else{
        $JSON = $arrInvoice;
        return;
    }



function sealInvoice($strXML,$intRed=0,$intGreen=0,$intBlue=0,$idVenta,$idFact,$azurian){

    $conNetwarstoreDB = mysqli_connect(DB_SERVER,DB_STORE_USER,DB_STORE_USER_PASS, DB_STORE);
    mysqli_query($conNetwarstoreDB,"SET NAMES '" . DB_CHARSET . "'");
    $strSql = "SELECT nombre_db, usuario_db, pwd_db FROM customer WHERE instancia = '" . INSTANCE_NAME . "';";
    //$strSql = "SELECT nombre_db, usuario_db, pwd_db FROM customer WHERE instancia = 'gmorales';";
    $rstDB = mysqli_query($conNetwarstoreDB,$strSql);
    while($objDB=mysqli_fetch_assoc($rstDB)){
        $strInstanceDB = $objDB['nombre_db'];
        $strInstanceDBUser = $objDB['usuario_db'];
        $strInstanceDBPassword = $objDB['pwd_db'];
    }
    unset($objDB);
    mysqli_free_result($rstDB);
    unset($rstDB);
    mysqli_close($conNetwarstoreDB);
    unset($conNetwarstoreDB);

    $conDB = mysqli_connect(DB_SERVER,$strInstanceDBUser,$strInstanceDBPassword,$strInstanceDB);
    mysqli_query($conDB,"SET NAMES '" . DB_CHARSET . "'");

    $strSql = "SELECT fc_user, fc_password, version FROM pvt_configura_facturacion;";
    $rstFCAuthData = mysqli_query($conDB,$strSql);
    while($objFCAuthData=mysqli_fetch_assoc($rstFCAuthData)){
        $strFCUser = $objFCAuthData['fc_user'];
        $strFCPassword = $objFCAuthData['fc_password'];
        $strFCVersion = $objFCAuthData['version'];
    }
    
    unset($objFCAuthData);
    mysqli_free_result($rstFCAuthData);
    unset($rstFCAuthData);

    $strSql = "SELECT logoempresa FROM organizaciones;";
    $rstImageLogo = mysqli_query($conDB,$strSql);
    while($objImageLogo=mysqli_fetch_assoc($rstImageLogo)){
        $strLogoEmpresa = $objImageLogo['logoempresa'];
    }
    unset($objImageLogo);
    mysqli_free_result($rstImageLogo);
    unset($rstImageLogo);

    if(trim(strtolower($strLogoEmpresa))=='x.png'){
        $strLogoEmpresa = '';
    }

    mysqli_close($conDB);
    unset($conDB);

    if($strFCUser=='pruebasWS'){
        if($strFCVersion=='3.3'){
            $devprod=WS_DEV_33;
        }else{
            $devprod=WS_DEV;
        }
        
    }else{
        if($strFCVersion=='3.3'){
            $devprod=WS_PROD_33;
        }else{
            $devprod=WS_PROD;
        }
        //$devprod=WS_PROD;
    }
    $objClient = new nusoap_client(WSINVOICE_URL, TRUE);
    $objResult = $objClient->call("sealInvoice",array("strUser"=>$strFCUser,"strPassword"=>$strFCPassword,"strXML"=>$strXML,"strEnvironment"=>$devprod));

    $arrResult = array('strStatus'=>'','strMessage'=>'');
    if($objResult[0]['strStatus']=='1'){
        $xmlorigi=base64_decode($objResult[0]['strResult']);

        $pcad=explode('UUID="',$xmlorigi);
        $cad=explode('"',$pcad[1]);
        $datosTimbrado['UUID']=$cad[0];

        $pcad=explode('noCertificadoSAT="',$xmlorigi);
        $cad=explode('"',$pcad[1]);
        $datosTimbrado['noCertificadoSAT']=$cad[0];

        $pcad=explode('selloCFD="',$xmlorigi);
        $cad=explode('"',$pcad[1]);
        $datosTimbrado['selloCFD']=$cad[0];

        $pcad=explode('selloSAT="',$xmlorigi);
        $cad=explode('"',$pcad[1]);
        $datosTimbrado['selloSAT']=$cad[0];

        $pcad=explode('FechaTimbrado="',$xmlorigi);
        $cad=explode('"',$pcad[1]);
        $datosTimbrado['FechaTimbrado']=$cad[0];
        $datosTimbrado['idFact']=$idFact;
        $datosTimbrado['idVenta']=$idVenta;
        $datosTimbrado['noCertificado']=3;
        $datosTimbrado['tipoComp']='F';
        $datosTimbrado['csdComplemento']='|1.0|'.$datosTimbrado['UUID'].'|'.$datosTimbrado['FechaTimbrado'].'|'.$datosTimbrado['selloCFD'].'|'.$datosTimbrado['noCertificadoSAT'];

        $azurian['datosTimbrado']=$datosTimbrado;
        //var_dump($datosTimbrado);

        $arrResult['strStatus'] = '1';
        $arrResult['strMessage'] = $objResult[0]['strCode'];
        $strPDFFile = INVOICE_PATH . $objResult[0]['strCode'] . '.xml';

        /////Saber donde guardar el XML , diferencia entre lo nuevo y lo viejo.
        $conDB = mysqli_connect(DB_SERVER,$strInstanceDBUser,$strInstanceDBPassword,$strInstanceDB);
        mysqli_query($conDB,"SET NAMES '" . DB_CHARSET . "'");
        $strSql = "SHOW TABLES LIKE 'app_respuestaFacturacion';";
        $rstFCAuthData = mysqli_query($conDB,$strSql);
        $tableExists = mysqli_num_rows($rstFCAuthData) > 0;
        if($tableExists > 0){
            $strXMLFile = INVOICE_PATHDIGITAL . $objResult[0]['strCode'] . '.xml';
        }else{
            $strXMLFile = INVOICE_PATHDIGITAL . $objResult[0]['strCode'] . '.xml';
        }

        unset($objFCAuthData);
        mysqli_free_result($rstFCAuthData);
        unset($rstFCAuthData); 
        

        file_put_contents($strXMLFile,base64_decode($objResult[0]['strResult']));

        //$objXmlToPDf = new invoiceXmlToPdf($strXMLFile,$strLogoEmpresa,$intRed,$intGreen,$intBlue,$strPDFFile);        
        //$objXmlToPDf->genPDF();

        $JSON = array('success' =>1, 
                'estatus'=>'La factura se ha creado exitosamente.', 'azurian' =>json_encode($azurian),
                'datos'=>$datosTimbrado,
                'idVenta'=>$idVenta,
                'idCliente'=>$idFact,
                'monto'=>$azurian['Basicos']['total'],
                'xmlfile'=>$objResult[0]['strCode'] . '.xml',
                'correo'=>$azurian['Correo']['Correo']);           

    }else{
        $arrResult['strStatus'] = '0';
        $arrResult['strMessage'] = $objResult[0]['strCode'] . ' - ' . $objResult[0]['strResult'];

        if($arrResult['strMessage']==''){
            $objResult[0]['strCode']='110';
            $arrResult['strMessage']='Error 110, en estos momentos no es posible conectar con los servidores del SAT, intentar mas tarde.';
        }
        $JSON = array('success' =>0, 'azurian' =>json_encode($azurian), 
                'error'=>$objResult[0]['strCode'], 
                'mensaje'=>$arrResult['strMessage'], 
                'dump'=>'',
                'idVenta'=>$idVenta);
            
    }
    return $JSON;
    unset($JSON);
}
?>