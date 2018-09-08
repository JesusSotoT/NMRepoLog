<?php

require_once ('config.php');
require_once ('lib/nusoap.php');
require_once ('lib/fpdf.php');
require_once ('lib/QRcode.php');
require_once ('class.invoice.pdf.php');




/*
$strFile = "CSD01_AAA010101AAA.xml";
$strXML = "";
$objFile = fopen($strFile, "r");
$strXML = base64_encode(fread($objFile,filesize($strFile)));
fclose($objFile);
unset($objFile);


echo date('Y-m-d h:i:s') . "<br /><br />";

$arrRetention = sealRetention($strXML,23,102,161);
var_dump($arrRetention);
unset($arrRetention);

echo "<br /><br />" . date('Y-m-d h:i:s');
*/

//Clave: 12345678a
//FC_User: pruebasWS
//FC_Pass: pruebasWS

//FC_User: IHA03MAS
//FC_Pass: 06rg1491*

//echo $XML;
$ws='ws_pro';

$strXML = base64_encode($XML);

$arrRetention = sealRetention($strXML,23,102,161,$idVenta,$idFact,$azurian,$ws,$idRetencion,$datosPrv['razon_social']);


function sealRetention($strXML,$intRed=0,$intGreen=0,$intBlue=0,$idVenta,$idFact,$azurian,$ws,$idRetencion,$razon_social){
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

    $strSql = "SELECT fc_user, fc_password FROM pvt_configura_facturacion;";
    $rstFCAuthData = mysqli_query($conDB,$strSql);
    while($objFCAuthData=mysqli_fetch_assoc($rstFCAuthData)){
        $strFCUser = $objFCAuthData['fc_user'];
        $strFCPassword = $objFCAuthData['fc_password'];
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

   
    
    if($strFCUser=='pruebasWS'){
        $devprod=WS_DEV;
    }else{
        $devprod=WS_PROD;
    }

    $objClient = new nusoap_client(WSRETENTION_URL, TRUE);
    $objResult = $objClient->call("sealRetention",array("strUser"=>$strFCUser,"strPassword"=>$strFCPassword,"strXML"=>$strXML,"strEnvironment"=>$devprod));

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
        $strXMLFile = INVOICE_PATHDIGITAL . $objResult[0]['strCode'] . '.xml';

        file_put_contents($strXMLFile,base64_decode($objResult[0]['strResult']));

        //$objXmlToPDf = new RetentionXmlToPdf($strXMLFile,$strLogoEmpresa,$intRed,$intGreen,$intBlue,$strPDFFile);
        //$objXmlToPDf->genPDF();

        $JSON = array('success' =>1, 
                'estatus'=>'La factura se ha creado exitosamente.', 'azurian' =>json_encode($azurian),
                'datos'=>$datosTimbrado,
                'idVenta'=>$idVenta,
                'idCliente'=>$idFact,
                'monto'=>$azurian['Basicos']['total'],
                'xmlfile'=>$objResult[0]['strCode'] . '.xml',
                'correo'=>$azurian['Correo']['Correo']);   
		$xmlfile='_'.$razon_social.'_'.$datosTimbrado['UUID'].'.xml';
		
		//mysqli_query($conDB,"update bco_pendiente_timbrar set timbrado=1, selloSAT = '".$datosTimbrado['selloSAT']."' , fechaTimbrado='". $datosTimbrado['FechaTimbrado']."' , UUID='".$datosTimbrado['UUID']."',nombreXML='".$xmlfile."',selloCFD='". $datosTimbrado['selloCFD']."' where idRetencion=".$idRetencion);
			        
 		
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
	mysqli_close($conDB);
    	unset($conDB);
		
    return $JSON;
    unset($JSON);
}
?>