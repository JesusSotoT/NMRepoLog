<?php
//ini_set("display_errors",1);
//ini_set('mbstring.internal_encoding','UTF-8');
require_once ('config.php');
require_once ('lib/nusoap.php');

//$strUUID = '9B78D582-45EB-4E74-BC0B-7F25625D849B';

//echo date('Y-m-d h:i:s') . "<br /><br />";
if(!$nominas){$nominas=0;}
$arrInvoice = cancelInvoice($strUUID,$nominas);

echo json_encode($arrInvoice);
unset($arrInvoice);

//var_dump($arrInvoice);

/* array(3) {
  ["strStatus"]=>
  string(1) "0"
  ["strCode"]=>
  string(3) "202"
  ["strResult"]=>
  string(26) "UUID 


  rray(3) {
  ["strStatus"]=>
  string(1) "0"
  ["strCode"]=>
  string(3) "201"
  ["strResult"]=>
  string(14) "UUI
  */

//echo '?'.$strVersion.'?';


//echo "<br /><br />" . date('Y-m-d h:i:s');

function cancelInvoice($strUUID,$nominas,$strVersion){

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
    $strSql = "SELECT fc_user, fc_password, rfc, cer, llave, clave FROM pvt_configura_facturacion;";
    $rstFCAuthData = mysqli_query($conDB,$strSql);
    while($objFCAuthData=mysqli_fetch_assoc($rstFCAuthData)){
        $strFCUser = $objFCAuthData['fc_user'];
        $strFCPassword = $objFCAuthData['fc_password'];
        $strRFC = $objFCAuthData['rfc'];
        $strCER = $objFCAuthData['cer'];
        $strFile = CER_KEY_PATH . $strCER;
        $objFile = fopen($strFile, "r");
        $strCER = base64_encode(fread($objFile,filesize($strFile)));
        fclose($objFile);
        unset($strFile);
        unset($objFile);
        $strKEY = $objFCAuthData['llave'];
        $strFile = CER_KEY_PATH . $strKEY;
        $objFile = fopen($strFile, "r");
        $strKEY = base64_encode(fread($objFile,filesize($strFile)));
        fclose($objFile);
        unset($objFile);
        $strKEYPassword = $objFCAuthData['clave'];
    }

    unset($objFCAuthData);
    mysqli_free_result($rstFCAuthData);
    unset($rstFCAuthData);
    

    /*if($strFCUser=='pruebasWS'){
        $devprod=WS_DEV;
    }else{
        $devprod=WS_PROD;
    } */
    //echo '/X'.$strVersion.'X)';
    if($strFCUser=='pruebasWS'){
        if($strVersion=='3.3'){
            //echo '333';
            $devprod=WS_DEV_33;
        }else{
            //echo '233';
            $devprod=WS_DEV_33;
        }
        
    }else{
        if($strVersion=='3.3'){
            $devprod=WS_PROD_33;
        }else{
            $devprod=WS_PROD_33;
        }
    }

    $objClient = new nusoap_client(WSCANCELINVOICE_URL, true);
    $objResult = $objClient->call("cancelInvoice",array(
        "strUUID"=>$strUUID,
        "strRFC"=>$strRFC,
        "strUser"=>$strFCUser,
        "strPassword"=>$strFCPassword,
        "strCER"=>$strCER,
        "strKEY"=>$strKEY,
        "strKEYPassword"=>$strKEYPassword,
        "strEnvironment"=>$devprod
    ));



    $arrResult = array('strStatus'=>$objResult[0]['strStatus'],'strCode'=>$objResult[0]['strCode'],'strResult'=>$objResult[0]['strResult']);
 	$sql = "update nomi_nominas_timbradas set cancelado=1 where UUID='".$strUUID."'";

    if($objResult[0]['strCode']=='201'){
        $JSON = array('success' =>1, 
                    'mensaje'=>'Factura cancelada correctamente'); //cancel ok
             	if($nominas == 1){
          			$cancelaNomina = mysqli_query($conDB,$sql);
		  		}
    }else if($objResult[0]['strCode']=='202'){
        $JSON = array('success' =>1, 
                    'mensaje'=>'Factura cancelada correctamente'); // timbre previo cancelado
                if($nominas == 1){
      				$cancelaNomina = mysqli_query($conDB,$sql);
	  			}
    }else{
        $JSON = array('success' =>0, 
                    'mensaje'=>$objResult[0]['strResult']);
    }
	mysqli_close($conDB);
    unset($conDB);
    return $JSON;

    //unset($arrResult);
}
?>