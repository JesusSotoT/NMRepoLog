<?php
ini_set('display_errors', 0);
date_default_timezone_set('America/Mexico_City');
//##### APP CONSTANTS #####
$arrInstanciaG = explode("/",$_SERVER['REQUEST_URI']);
$strInstanciaG = $arrInstanciaG[array_search('webapp',$arrInstanciaG) - 1];
if($strInstanciaG==''){
$strInstanciaG = $arrInstanciaG[array_search('clientes',$arrInstanciaG) + 1];
}
$strInstanciaG = "mlog";
if(!defined('INSTANCE_NAME'))           define('INSTANCE_NAME',$strInstanciaG);
//##### DB CONSTANTS #####
if(!defined('DB_SERVER'))               define('DB_SERVER','nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com');
if(!defined('DB_CHARSET'))              define('DB_CHARSET','utf8');
if(!defined('DB_STORE'))                define('DB_STORE','netwarstore');
if(!defined('DB_STORE_USER'))           define('DB_STORE_USER','nmdevel');
if(!defined('DB_STORE_USER_PASS'))      define('DB_STORE_USER_PASS','nmdevel');
//##### INVOICES CONSTANTS #####
if(!defined('WS_DEV'))		            define('WS_DEV','DEV');
if(!defined('WS_PROD'))		            define('WS_PROD','PROD');
if(!defined('WSINVOICE_URL'))           define('WSINVOICE_URL','http://wsserver.netwarmonitor.com/sealInvoice.php?wsdl');
if(!defined('WSCANCELINVOICE_URL'))     define('WSCANCELINVOICE_URL','http://wsserver.netwarmonitor.com/cancelInvoice.php?wsdl');
if(!defined('WSRETENTION_URL'))         define('WSRETENTION_URL','http://wsserver.netwarmonitor.com/sealRetention.php?wsdl');
if(!defined('WSCANCELRETENTION_URL'))   define('WSCANCELRETENTION_URL','http://wsserver.netwarmonitor.com/cancelRetention.php?wsdl');
if(!defined('INVOICE_PATH'))            define('INVOICE_PATH','../webapp/modulos/facturas/');
if(!defined('INVOICE_PATHADIGITAL'))    define('INVOICE_PATHADIGITAL','../webapp/modulos/cont/xmls/facturas/temporales/');
if(!defined('CER_KEY_PATH'))            define('CER_KEY_PATH','../webapp/modulos/SAT/cliente/');
//##### PDF CONSTANTS #####
if(!defined('FPDF_FONTPATH'))           define('FPDF_FONTPATH','lib/font/');
if(!defined('FPDF_LOGOIMAGEPATH'))      define('FPDF_LOGOIMAGEPATH', str_replace('appministra_api', '', getcwd()) . 'webapp/netwarelog/archivos/1/organizaciones/');
?>
